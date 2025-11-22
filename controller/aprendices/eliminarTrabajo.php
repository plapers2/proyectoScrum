<?php
session_start();
require_once '../../models/MySQL.php';

if (!isset($_SESSION["tipoUsuario"]) || 
   !in_array($_SESSION["tipoUsuario"], ["Instructor", "Administrador"])) {
    header("Location: ../../dist/trabajos.php?error=No autorizado");
    exit;
}

$mysql = new MySQL();
$mysql->conectar();

if (!isset($_GET["id"])) {
    header("Location: ../../dist/trabajos.php?error=ID inválido");
    exit;
}

$id = intval($_GET["id"]);

// Obtener archivo para eliminarlo del servidor
$consulta = $mysql->efectuarConsulta("SELECT ruta_trabajo FROM trabajos WHERE id_trabajo = $id");
$fila = mysqli_fetch_assoc($consulta);

if ($fila && file_exists($fila["ruta_trabajo"])) {
    unlink($fila["ruta_trabajo"]);
}

// Eliminar registro
$sql = "DELETE FROM trabajos WHERE id_trabajo = $id";

if ($mysql->efectuarConsulta($sql)) {
    header("Location: ../../dist/trabajos.php?success=Trabajo eliminado");
} else {
    header("Location: ../../dist/trabajos.php?error=No se pudo eliminar");
}

$mysql->desconectar();
?>
