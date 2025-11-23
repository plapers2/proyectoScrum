<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
header('Content-Type: application/json');
$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
require_once '../../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
try {
    $mysql->efectuarConsulta("UPDATE cursos SET nombre_curso = '$nombre', descripcion_curso = '$descripcion' WHERE id_curso = $id");
    //? Retorno de datos aplicando JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Curso actualizado exitosamente!']);
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al actualizar Curso!', 'error' => $th]);
};
