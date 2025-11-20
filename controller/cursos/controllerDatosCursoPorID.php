<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
require_once '../../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

try {
    $resultado = $mysql->efectuarConsulta("SELECT * FROM cursos WHERE id_curso = $id;");
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al traer datos de curso por ID', 'error' => $th]);
}
$datos = mysqli_fetch_assoc($resultado);
if ($datos) {
    $datos['id_curso'] = (int)$datos['id_curso'];
}
header('Content-Type: application/json');
echo json_encode($datos);
