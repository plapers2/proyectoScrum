<?php
session_start();
require_once '../../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

if ($_SESSION['tipoUsuario'] != 'Instructor' && $_SESSION['tipoUsuario'] != 'Administrador') {
    header('Location: ../../dist/trabajos.php?error=No autorizado');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idInstructor = $_POST['id_instructor'];
    $idAprendiz = $_POST['id_aprendiz'];
    $fechaLimite = $_POST['fecha_limite'];
    $comentario = $_POST['comentario'] ?? '';
    $archivo = $_FILES['archivo'];

    $carpeta = '../../uploads/trabajos/';
    if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

    $nombreArchivo = time() . "_" . basename($archivo['name']);
    $ruta = $carpeta . $nombreArchivo;

    if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
        echo "Error al subir archivo";
        exit;
    }

    $sql = "
        INSERT INTO trabajos (calificacion_trabajo, ruta_trabajo, comentario_trabajo, fecha_subida, fecha_limite_trabajo, instructores_id_instructor, aprendices_id_aprendiz)
        VALUES ('', '$ruta', '$comentario', NOW(), '$fechaLimite', $idInstructor, $idAprendiz)
    ";

    if ($mysql->efectuarConsulta($sql)) {
        header("Location: ../../dist/trabajos.php?success=Trabajo creado correctamente");
    } else {
        echo "Error al insertar trabajo";
    }
}

$mysql->desconectar();
?>
