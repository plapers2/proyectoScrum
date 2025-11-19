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
        $tipoUsuario = filter_var(trim($_POST['tipoUsuarioLogin'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $pass = $_POST['passLogin'];
        try {
            switch ($tipoUsuario) {
                case 'Administrador':
                    $resultado = $mysql->efectuarConsulta("SELECT * FROM administradores WHERE nombre_administrador = '$nombreUsuario';");
                    $tipo = '_administrador';
                    break;
                case 'Instructor':
                    $resultado = $mysql->efectuarConsulta("SELECT * FROM instructores WHERE nombre_instructor = '$nombreUsuario';");
                    $tipo = '_instructor';
                    break;
                case 'Aprendiz':
                    $resultado = $mysql->efectuarConsulta("SELECT * FROM aprendices WHERE nombre_aprendiz = '$nombreUsuario';");
                    $tipo = '_aprendiz';
                    break;
                default:
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Selecciona un rol!', 'error' => $th]);
                    break;
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al traer datos de usuario', 'error' => $th]);
        };
        //* Verificaciones
        if ($usuario = mysqli_fetch_assoc($resultado)) {
            if (password_verify($pass, $usuario['pass' . $tipo])) {
                //* Se guardan credenciales en variable global $_SESSION
                $_SESSION['idUsuario'] = $usuario['id' . $tipo];
                $_SESSION['emailUsuario'] = $usuario['correo' . $tipo];
                $_SESSION['nombreUsuario'] = $usuario['nombre' . $tipo];
                $_SESSION['apellidoUsuario'] = $usuario['apellido' . $tipo];
                switch ($tipo) {
                    case '_administrador':
                        $tipo = 'Administrador';
                        break;
                    case '_instructor':
                        $tipo = 'Instructor';
                        break;
                    case '_aprendiz':
                        $tipo = 'Aprendiz';
                        break;
                    default:
                        break;
                }
                $_SESSION['tipoUsuario'] = $tipo;

                //* Exito
                if ($_SESSION['tipoUsuario'] == 'Administrador') {
                    header("Location: ../dist/dashboard.php");
                    exit();
                } else {
                    header("Location: ../dist/libros.php");
                    exit();
                }
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
