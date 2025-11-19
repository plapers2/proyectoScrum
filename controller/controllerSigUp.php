<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !empty($_POST['usuarioNombre']) &&
        !empty($_POST['usuarioApellido']) &&
        !empty($_POST['usuarioEmail']) &&
        !empty($_POST['usuarioPass']) &&
        !empty($_POST['usuarioRol'])
    ) {
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        $nombre = filter_var($_POST['usuarioNombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST['usuarioApellido'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['usuarioEmail'], FILTER_SANITIZE_EMAIL);
        $pass = $_POST['usuarioPass'];
        $rol = $_POST['usuarioRol'];
        $cursoId = $_POST['cursoId'] ?? null; // puede venir vacío para algunos roles

        try {
            // Verificar si el correo ya existe en cualquiera de las tablas según el rol
            if ($rol === 'administrador') {
                $query = "SELECT * FROM administradores WHERE correo_administrador = '$email'";
            } elseif ($rol === 'instructor') {
                $query = "SELECT * FROM instructores WHERE correo_instrcutor = '$email'";
            } else { // aprendiz
                $query = "SELECT * FROM aprendices WHERE correo_aprendiz = '$email'";
            }
            $resultado = $mysql->efectuarConsulta($query);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo "ErrorConsulta";
            exit();
        }

        $datos = mysqli_fetch_assoc($resultado);

        if (!$datos) {
            $hash = password_hash($pass, PASSWORD_BCRYPT);

            if ($rol === 'administrador') {
                $queryInsert = "INSERT INTO administradores (id_administrador, nombre_administrador, apellido_administrador, pass_administrador, correo_administrador) VALUES (NULL, '$nombre', '$apellido', '$hash', '$email')";
            } elseif ($rol === 'instructor') {
                $queryInsert = "INSERT INTO instructores (id_instrcutor, nombre_instrcutor, apellido_instrcutor, pass_instrcutor, correo_instrcutor) VALUES (NULL, '$nombre', '$apellido', '$hash', '$email')";
            } else {
                // Validar que cursoId esté presente para aprendiz
                if (empty($cursoId)) {
                    http_response_code(400);
                    echo "FaltaCurso";
                    exit();
                }
                $queryInsert = "INSERT INTO aprendices (id_aprendiz, nombre_aprendiz, apellido_aprendiz, pass_aprendiz, correo_aprendiz, cursos_id_curso) VALUES (NULL, '$nombre', '$apellido', '$hash', '$email', $cursoId)";
            }

            try {
                $mysql->efectuarConsulta($queryInsert);
                http_response_code(200);
                echo "Registrado";
                exit();
            } catch (\Throwable $th) {
                http_response_code(500);
                echo "ErrorInsertando";
                exit();
            }
        } else {
            http_response_code(409);
            echo "EmailRepetido";
            exit();
        }
    } else {
        http_response_code(400);
        echo "CamposVacios";
        exit();
    }
}