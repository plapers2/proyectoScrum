<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$idUsuario = filter_var($_POST['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

try {
    $resultado = $mysql->efectuarConsulta("SELECT * FROM usuario 
JOIN tipoUsuario ON tipoUsuario.idTipoUsuario = usuario.fkTipoUsuario  
JOIN estado ON estado.idEstado = usuario.fkEstadoUsuario
WHERE usuario.idUsuario = $idUsuario;");
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al traer datos de usuario por ID', 'error' => $th]);
}
$datos;
while ($row = mysqli_fetch_assoc($resultado)) {
    $row['idUsuario'] = (int)$row['idUsuario'];
    $row['fkEstadoUsuario'] = (int)$row['fkEstadoUsuario'];
    $row['fkTipoUsuario'] = (int)$row['fkTipoUsuario'];
    $datos[] = $row;
}
header('Content-Type: application/json');
echo json_encode($datos);