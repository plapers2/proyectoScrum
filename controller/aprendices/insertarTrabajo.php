<?php
session_start();
require_once '../../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

if ($_SESSION["tipoUsuario"] != "Instructor") {
    header("Location: ../../dist/login.php?error=true&message=Acceso denegado");
    exit;
}

$idInstructor = $_SESSION["idUsuario"];

$idAprendiz = $_POST["id_aprendiz"];
$fechaLimite = $_POST["fecha_limite"];
$archivo = $_FILES["archivo"];

if ($archivo['error'] !== 0) {
    die("Error al subir archivo");
}

$rutaCarpeta = "../../uploads/trabajos/";
if (!is_dir($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);

$nombreArchivo = time() . "_" . $archivo["name"];
$rutaFinal = $rutaCarpeta . $nombreArchivo;

move_uploaded_file($archivo["tmp_name"], $rutaFinal);

$rutaDB = "uploads/trabajos/" . $nombreArchivo;

$sql = "
    INSERT INTO trabajos (ruta_trabajo, fecha_subida, fecha_limite_trabajo, instructores_id_instructor, aprendices_id_aprendiz)
    VALUES ('$rutaDB', NOW(), '$fechaLimite', $idInstructor, $idAprendiz)
";

$mysql->efectuarConsulta($sql);

header("Location: ../../dist/trabajos.php?success=Trabajo asignado correctamente");
exit;
