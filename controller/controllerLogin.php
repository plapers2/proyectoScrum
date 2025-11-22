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
        $tipoUsuario = filter_var(trim($_POST['tipoUsuarioLogin']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pass = trim($_POST['passLogin']);
        try {
            $resultado;
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
                    header("Location: ../dist/login.php?error=true&message=Seleccione un tipo de usuario!&title=Error!");
                    exit();
            }
        } catch (\Throwable $th) {
            header("Location: ../dist/login.php?error=true&message=Error al ejecutar consulta!&title=Error!");
            exit();
        };
        //* Verificaciones
        if ($usuario = mysqli_fetch_assoc($resultado)) {
            if (password_verify($pass, $usuario['pass'.$tipo])) {
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
                switch ($tipo) {
                    case 'Administrador':
                        header("Location: ../dist/administradores.php");
                        break;
                    case 'Instructor':
                        header("Location: ../dist/instructores.php");
                        break;
                    case 'Aprendiz':
                        header("Location: ../dist/aprendices.php");
                        break;
                    default:
                        break;
                }
            } else {
                $mysql->desconectar();
                header("Location: ../dist/login.php?error=true&message=Contraseña incorrecta, intenta nuevamente!&title=Contraseña!");
                exit();
            }
        } else {
            $mysql->desconectar();
            header("Location: ../dist/login.php?error=true&message=Usuario no encontrado, registrate!&title=Error!");
            exit();
        }
    } else {
        header("Location: ../dist/login.php?error=true&message=Ingrese todos los campos requeridos!&title=Faltan campos!");
        exit();
    }
}
