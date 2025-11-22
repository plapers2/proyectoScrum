<?php
session_start();
require_once '../../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

if ($_SESSION["tipoUsuario"] != "Instructor") {
    header("Location: ../../dist/login.php?error=true&message=Acceso denegado");
    exit;
}

$id = $_GET["id"];

// borrar archivo del servidor
$trabajo = $mysql->efectuarConsulta("SELECT ruta_trabajo FROM trabajos WHERE id_trabajo = $id");

if ($fila = mysqli_fetch_assoc($trabajo)) {
    $archivo = "../../" . $fila["ruta_trabajo"];
    if (file_exists($archivo)) unlink($archivo); 
}

$mysql->efectuarConsulta("DELETE FROM trabajos WHERE id_trabajo = $id");

header("Location: ../../dist/trabajos.php?success=Trabajo eliminado");
exit;
