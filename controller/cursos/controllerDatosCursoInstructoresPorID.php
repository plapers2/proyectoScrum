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
    $resultado = $mysql->efectuarConsulta("SELECT * FROM cursos_has_instructores as pivote
    JOIN instructores ON instructores.id_instructor = pivote.instructores_id_instructor
    WHERE pivote.cursos_id_curso = $id;");
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al traer datos de instructores por ID', 'error' => $th]);
}
$datos = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $row['cursos_id_curso'] = (int)$row['cursos_id_curso'];
    $row['instructores_id_instructor'] = (int)$row['instructores_id_instructor'];
    $row['id_instructor'] = (int)$row['id_instructor'];
    $datos[] = $row;
}
header('Content-Type: application/json');
echo json_encode($datos);
