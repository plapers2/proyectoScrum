<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
require_once '../../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

try {
    $resultado = $mysql->efectuarConsulta("SELECT * FROM instructores;");
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al traer datos de instructores!', 'error' => $th]);
}
$datos = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $datos[] = $row;
}
header('Content-Type: application/json');
echo json_encode($datos);
