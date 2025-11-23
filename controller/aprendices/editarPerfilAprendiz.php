<?php
session_start();
require_once "../../models/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset(
        $_POST["id_aprendiz"],
        $_POST["nombre_aprendiz"],
        $_POST["apellido_aprendiz"],
        $_POST["correo_aprendiz"]
    ) && !empty($_POST["id_aprendiz"])
    && !empty($_POST["nombre_aprendiz"])
    && !empty($_POST["apellido_aprendiz"])
    && !empty($_POST["correo_aprendiz"])
) {
    //// variables
    $id_aprendiz = intval($_POST["id_aprendiz"]) ?? 0;
    $nombre = filter_var($_POST["nombre_aprendiz"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $apellido = filter_var($_POST["apellido_aprendiz"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST["correo_aprendiz"], FILTER_SANITIZE_EMAIL);
    $contrasena = trim($_POST["pass_aprendiz"]) ?? "";

    if ($contrasena != "") {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $actualizar = $sql->efectuarConsulta("UPDATE aprendices SET nombre_aprendiz = '$nombre',
                                                apellido_aprendiz = '$apellido', correo_aprendiz = '$email',
                                                pass_aprendiz = '$hash' WHERE id_aprendiz = $id_aprendiz");
        if ($actualizar) {
            $_SESSION["nombreUsuario"] = $nombre;
            $_SESSION["apellidoUsuario"] = $apellido;
            $_SESSION["emailUsuario"] = $email;
            echo "ok";
            $sql->desconectar();
            exit;
        } else {
            echo "No se pudo actualizar el perfil";
            $sql->desconectar();
            exit;
        }
    } else {
        $actualizar = $sql->efectuarConsulta("UPDATE aprendices SET nombre_aprendiz = '$nombre',
                                                apellido_aprendiz = '$apellido', correo_aprendiz = '$email'
                                                WHERE id_aprendiz = $id_aprendiz");
        if ($actualizar) {
            $_SESSION["nombreUsuario"] = $nombre;
            $_SESSION["apellidoUsuario"] = $apellido;
            $_SESSION["correoUsuario"] = $email;
            echo "ok";
            $sql->desconectar();
            exit;
        } else {
            echo "No se pudo actualizar el perfil";
            $sql->desconectar();
            exit;
        }
    }
} else {
    echo "Datos incompletos";
}

$sql->desconectar();
