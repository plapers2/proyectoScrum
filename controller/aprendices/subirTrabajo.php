<?php
session_start();

// cargar mysql
require_once '../../models/MySQL.php';

// verificar usuario aprendiz
if (!isset($_SESSION["idUsuario"]) || $_SESSION["tipoUsuario"] != "Aprendiz") {
    header('Location: ../../dist/login.php?error=true&message=Acceso denegado&title=Error');
    exit;
}

// verificar metodo post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mysql = new MySQL();
    $mysql->conectar();

    $id_trabajo = isset($_POST['id_trabajo']) ? intval($_POST['id_trabajo']) : 0;
    $id_aprendiz = $_SESSION["idUsuario"];

    if ($id_trabajo <= 0) {
        header('Location: ../../dist/aprendices.php?error=true&message=ID invalido&title=Error');
        $mysql->desconectar();
        exit;
    }

    // verificar trabajo
    $consulta_trabajo = "SELECT fecha_limite_trabajo, ruta_trabajo 
                         FROM trabajos 
                         WHERE id_trabajo = $id_trabajo 
                         AND aprendices_id_aprendiz = $id_aprendiz";

    $resultado = $mysql->efectuarConsulta($consulta_trabajo);

    if (mysqli_num_rows($resultado) == 0) {
        header('Location: ../../dist/aprendices.php?error=true&message=Trabajo no encontrado&title=Error');
        $mysql->desconectar();
        exit;
    }

    $trabajo = mysqli_fetch_assoc($resultado);
    $fecha_limite = $trabajo['fecha_limite_trabajo'];
    $ruta_anterior = $trabajo['ruta_trabajo'];

    // verificar fecha limite
    $fecha_actual = date('Y-m-d H:i:s');
    if ($fecha_actual > $fecha_limite) {
        header('Location: ../../dist/aprendices.php?error=true&message=Fecha limite vencida&title=Error');
        $mysql->desconectar();
        exit;
    }

    // verificar archivo
    if (!isset($_FILES['nuevo_archivo']) || $_FILES['nuevo_archivo']['error'] != 0) {
        header('Location: ../../dist/aprendices.php?error=true&message=Debe subir un archivo&title=Error');
        $mysql->desconectar();
        exit;
    }

    // directorio destino
    $directorio_destino = "../../uploads/trabajos/";

    if (!file_exists($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }

    // eliminar archivo anterior
    if ($ruta_anterior && file_exists($ruta_anterior)) {
        unlink($ruta_anterior);
    }

    // generar nombre nuevo
    $extension = pathinfo($_FILES['nuevo_archivo']['name'], PATHINFO_EXTENSION);
    $nombre_archivo = "trabajo_" . $id_trabajo . "_" . time() . "." . $extension;
    $ruta_completa = $directorio_destino . $nombre_archivo;

    // mover archivo
    if (move_uploaded_file($_FILES['nuevo_archivo']['tmp_name'], $ruta_completa)) {

        $ruta_completa_escapada = addslashes($ruta_completa);

        $consulta_actualizar = "UPDATE trabajos 
                                SET ruta_trabajo = '$ruta_completa_escapada' 
                                WHERE id_trabajo = $id_trabajo 
                                AND aprendices_id_aprendiz = $id_aprendiz";

        $resultado_update = $mysql->efectuarConsulta($consulta_actualizar);

        if ($resultado_update) {
            header('Location: ../../dist/aprendices.php?success=true&message=Archivo subido exitosamente&title=Exito');
        } else {
            unlink($ruta_completa);
            header('Location: ../../dist/aprendices.php?error=true&message=Error al actualizar la base de datos&title=Error');
        }
    } else {
        header('Location: ../../dist/aprendices.php?error=true&message=Error al subir el archivo&title=Error');
    }

    $mysql->desconectar();
} else {
    header('Location: ../../dist/aprendices.php?error=true&message=Metodo no permitido&title=Error');
    exit;
}
