<?php
header('Content-Type: text/plain; charset=utf-8');

require_once "../../models/MySQL.php";

session_start();

$sql = new MySQL();
$sql->conectar();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Método no permitido';
    exit;
}

// Verificar que el usuario sea instructor y esté autenticado
if (!isset($_SESSION['idUsuario'])) {
    echo 'No tienes permisos para realizar esta acción';
    exit;
}

//* variables
$id_instructor = $_SESSION['idUsuario'];
if (isset($id_trabajo) && !empty($id_trabajo)) {
    $id_trabajo = filter_var($_GET["id_trabajo"], FILTER_SANITIZE_NUMBER_INT);
}

// Validar que se haya subido un archivo
if (!isset($_FILES['ruta_trabajo']) || $_FILES['ruta_trabajo']['error'] !== UPLOAD_ERR_OK) {
    echo 'Error al subir el archivo. Por favor intenta nuevamente.';
    exit;
}

// Validar fecha límite
if (!isset($_POST['fecha_limite_trabajo']) || empty($_POST['fecha_limite_trabajo'])) {
    echo 'La fecha límite es requerida';
    exit;
}

// Validar que la fecha límite no sea anterior a hoy
$fecha_limite = filter_var($_POST['fecha_limite_trabajo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$fecha_actual = date('Y-m-d');

if ($fecha_limite < $fecha_actual) {
    echo 'La fecha límite no puede ser anterior a hoy';
    exit;
}

// Validar que se hayan seleccionado aprendices
if (!isset($_POST['aprendices']) || !is_array($_POST['aprendices']) || count($_POST['aprendices']) === 0) {
    echo 'Debe seleccionar al menos un aprendiz';
    exit;
}

$aprendices = $_POST['aprendices'];

// Procesar el archivo subido
$archivo = $_FILES['ruta_trabajo'];
$nombre_original = $archivo['name'];
$extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

// Validar extensiones permitidas
$extensiones_permitidas = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'jpg', 'jpeg', 'png'];
if (!in_array($extension, $extensiones_permitidas)) {
    echo 'Tipo de archivo no permitido. Extensiones permitidas: ' . implode(', ', $extensiones_permitidas);
    exit;
}

// Validar tamaño del archivo (máximo 10MB)
$tamano_maximo = 10 * 1024 * 1024;
if ($archivo['size'] > $tamano_maximo) {
    echo 'El archivo es demasiado grande. Tamaño máximo: 10MB';
    exit;
}

// Generar nombre único para el archivo
$nombre_unico = uniqid() . '_' . time() . '.' . $extension;

// Definir ruta de destino
$directorio_destino = '../../uploads/trabajos/';

// Crear directorio si no existe
if (!file_exists($directorio_destino)) {
    if (!mkdir($directorio_destino, 0777, true)) {
        echo 'Error al crear el directorio de destino';
        exit;
    }
}

$ruta_completa = $directorio_destino . $nombre_unico;

// Mover el archivo a la carpeta de destino
if (!move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
    echo 'Error al guardar el archivo en el servidor';
    exit;
}

try {
    $errores = [];
    $exitosos = 0;

    foreach ($aprendices as $id_aprendiz) {
        // Validar que el ID sea numérico
        $id_aprendiz = filter_var($id_aprendiz, FILTER_SANITIZE_NUMBER_INT);

        if (!is_numeric($id_aprendiz) || $id_aprendiz <= 0) {
            $errores[] = "ID de aprendiz inválido: $id_aprendiz";
            continue;
        }
        if ($_SESSION['tipoUsuario'] == 'Aprendiz') {
            $insertar_trabajo = $sql->efectuarConsulta(
                "UPDATE trabajos SET ruta_trabajo_aprendiz = '$nombre_unico' WHERE id_trabajo = $id_trabajo"
            );
        } else if ($_SESSION['tipoUsuario'] == 'Instructor') {
            $insertar_trabajo = $sql->efectuarConsulta(
                "INSERT INTO trabajos (estado_trabajo, ruta_trabajo_instructor, fecha_subida, fecha_limite_trabajo, instructores_id_instructor, aprendices_id_aprendiz) 
             VALUES ('Activo', '$nombre_unico', NOW(), '$fecha_limite', $id_instructor, $id_aprendiz)"
            );
        }


        if (!$insertar_trabajo) {
            $errores[] = "Error al asignar trabajo al aprendiz ID: $id_aprendiz";
        } else {
            $exitosos++;
        }
    }

    // Verificar si hubo errores en las asignaciones
    if (count($errores) > 0) {
        if ($exitosos === 0) {
            // Si ninguno fue exitoso, eliminar el archivo
            if (file_exists($ruta_completa)) {
                unlink($ruta_completa);
            }
            echo 'Error: No se pudo asignar el trabajo a ningún aprendiz. ' . implode(', ', $errores);
        } else {
            echo 'Trabajo creado pero con errores en algunas asignaciones: ' . implode(', ', $errores);
        }
    } else {
        echo 'ok';
    }
} catch (Exception $e) {
    // Eliminar el archivo si hubo error
    if (file_exists($ruta_completa)) {
        unlink($ruta_completa);
    }

    echo 'Error: ' . $e->getMessage();
}
