<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validar si los campos existen y no están vacíos
    if (
        !empty($_POST['usuarioNombre']) &&
        !empty($_POST['usuarioApellido']) &&
        !empty($_POST['usuarioEmail']) &&
        !empty($_POST['usuarioPass'])
    ) {

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Recibir y sanitizar
        $nombre = filter_var($_POST['usuarioNombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST['usuarioApellido'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['usuarioEmail'], FILTER_SANITIZE_EMAIL);
        $pass = $_POST['usuarioPass'];

        // Verificar si ya existe el correo
        try {
            $query = "SELECT * FROM usuario WHERE correo_usuario = '$email'";
            $resultado = $mysql->efectuarConsulta($query);
        } catch (\Throwable $th) {
            header('Location: ../dist/registro.php?error=ErrorConsulta');
            exit();
        }

        $datos = mysqli_fetch_assoc($resultado);

        // Si no existe el correo, insertar
        if (!$datos) {

            $hash = password_hash($pass, PASSWORD_BCRYPT);

            // INSERT correcto según tu tabla
            $queryInsert = "
                INSERT INTO usuario 
                (id_usuario, nombre_usuario, apellido_usuario, pass_usuario, correo_usuario, fk_rol_usuario)
                VALUES 
                (NULL, '$nombre', '$apellido', '$hash', '$email', 1);
            ";

            try {
                $mysql->efectuarConsulta($queryInsert);
                header('Location: ../dist/login.php?success=Registrado');
                exit();
            } catch (\Throwable $th) {
                header('Location: ../dist/registro.php?error=ErrorInsertando');
                exit();
            }

        } else {
            header('Location: ../dist/registro.php?error=EmailRepetido');
            exit();
        }
    } else {
        header('Location: ../dist/registro.php?error=CamposVacios');
        exit();
    }
}
?>
