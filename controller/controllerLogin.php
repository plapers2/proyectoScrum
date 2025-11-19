<?php
//! Funcion para iniciar sesion
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['passLogin'], $_POST['usuarioLogin']) && !empty($_POST['passLogin']) && !empty($_POST['usuarioLogin'])) {
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();
        //* Sanitizacion de email (la PassWord no se sanitiza nunca)
        $nombreUsuario = filter_var(trim($_POST['usuarioLogin']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pass = $_POST['passLogin'];
        try {
            $resultado = $mysql->efectuarConsulta("SELECT * FROM administradores JOIN roles on roles.id_rol = usuario.fk_rol_usuario where usuario.nombre_usuario = '$nombreUsuario'; ");
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al traer datos de usuario', 'error' => $th]);
        };
        //* Verificaciones
        if ($usuario = mysqli_fetch_assoc($resultado)) {
            if (password_verify($pass, $usuario['pass_usuario'])) {
                //* Se guardan credenciales en variable global $_SESSION
                $_SESSION['idUsuario'] = $usuario['id_usuario'];
                $_SESSION['emailUsuario'] = $usuario['correo_usuario'];
                $_SESSION['nombreUsuario'] = $usuario['nombre_usuario'];
                $_SESSION['idTipoUsuario'] = $usuario['id_rol'];
                $_SESSION['tipoUsuario'] = $usuario['nombre_rol'];
                $_SESSION['apellidoUsuario'] = $usuario['apellido_usuario'];
                //* Exito
                if ($_SESSION['tipoUsuario'] == 'Administrador') {
                    header("Location: ../dist/dashboard.php");
                    exit();
                // } else {
                //     header("Location: ../dist/libros.php");
                //     exit();
                // }
            } else {
                $mysql->desconectar();
                header("Location: ../dist/login.php?error=true&message=Contraseña incorrecta, intenta nuevamente!&title=Contraseña!");
                exit();
            }
        }
        $mysql->desconectar();
        header("Location: ../dist/login.php?error=true&message=Usuario no encontrado, registrate!&title=Error!");
        exit();
    } else {
        header("Location: ../dist/login.php?error=true&message=Ingrese todos los campos requeridos!&title=Faltan campos!");
        exit();
    }
}
