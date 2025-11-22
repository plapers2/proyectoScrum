<?php
session_start();
require_once '../../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

// Validar que el aprendiz esté logueado
if (!isset($_SESSION['idUsuario']) || empty($_SESSION['idUsuario']) || $_SESSION['tipoUsuario'] != 'Aprendiz') {
    header('Location: ../../dist/aprendices.php?error=true&message=Debes iniciar sesión como aprendiz!');
    exit;
}

$idAprendiz = $_SESSION['idUsuario'];

// Validar que sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idInstructor = $_POST['id_instructor'] ?? null;
    $archivo = $_FILES['archivo'] ?? null;

    if (!$archivo || $archivo['error'] !== 0) {
        echo "No se subió ningún archivo o hubo un error.";
        exit;
    }

    // Carpeta de subida
    $rutaCarpeta = '../../uploads/trabajos/';
    if (!is_dir($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    // Guardar archivo
    $nombreArchivo = time() . '_' . basename($archivo['name']);
    $rutaArchivo = $rutaCarpeta . $nombreArchivo;

    if (!move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
        echo "Error al guardar el archivo.";
        exit;
    }

    // Escapar ruta
    $rutaArchivoEsc = mysqli_real_escape_string($mysql->getConexion(), $rutaArchivo);

    // Insertar en la BD
    $sqlInsert = "INSERT INTO trabajos 
        (calificacion_trabajo, ruta_trabajo, comentario_trabajo, fecha_subida, instructores_id_instructor, aprendices_id_aprendiz)
        VALUES ('', '$rutaArchivoEsc', '', NOW(), $idInstructor, $idAprendiz)";

    $resultado = $mysql->efectuarConsulta($sqlInsert);

    if ($resultado) {
        header('Location: ../../dist/aprendices.php?success=Trabajo subido correctamente');
    } else {
        echo "Error al subir el trabajo: " . mysqli_error($mysql->getConexion());
    }
}

$mysql->desconectar();
