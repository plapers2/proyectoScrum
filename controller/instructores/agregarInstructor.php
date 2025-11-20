<?php

require_once "../../models/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset(
        $_POST["nombre_instructor"],
        $_POST["apellido_instructor"],
        $_POST["correo_instructor"],
        $_POST["pass_instructor"]
    )
    && !empty($_POST["nombre_instructor"]
        && !empty($_POST["apellido_instructor"])
        && !empty($_POST["correo_instructor"])
        && !empty($_POST["pass_instructor"]))
) {
    //* variables
    $nombre = filter_var($_POST["nombre_instructor"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $apellido = filter_var($_POST["apellido_instructor"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $correo = filter_var($_POST["correo_instructor"], FILTER_SANITIZE_EMAIL) ?? "";
    $contrasena = password_hash($_POST["pass_instructor"], PASSWORD_DEFAULT);

    $instructor_repetido = $sql->efectuarConsulta("SELECT id_instructor FROM instructores
                                                WHERE nombre_instructor = '$nombre'");
    if ($instructor_repetido->num_rows > 0) {
        echo "El instructor $nombre ya existe en el aplicativo. Intenta con otro";
        $sql->desconectar();
        exit;
    }
    $registrar = $sql->efectuarConsulta("INSERT INTO instructores(nombre_instructor, apellido_instructor,
                                    correo_instructor, pass_instructor, estado_instructor)
                                    VALUES('$nombre', '$apellido', '$correo', '$contrasena', 'Activo')");
    if ($registrar) {
        echo "ok";
    }
}
$sql->desconectar();
