<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
require_once '../../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$id = $_POST['id'];
try {
    $mysql->efectuarConsulta("UPDATE administradores SET estado_administrador = 'Activo' WHERE id_administrador = $id;");
    //? Retorno de datos aplicando JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Administrador activado exitosamente!']);
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al activar el Administrador!', 'error' => $th]);
};
