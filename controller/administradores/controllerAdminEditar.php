<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
header('Content-Type: application/json');
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$pass = $_POST["pass"];
$id = filter_var(($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
$boolPass = filter_var($_POST['bool'], FILTER_VALIDATE_BOOLEAN);
if ($boolPass === false) {
    $hash = password_hash($pass, PASSWORD_BCRYPT);
} else {
    $hash = $pass;
}
require_once '../../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
try {
    $mysql->efectuarConsulta("UPDATE administradores SET
    nombre_administrador = '$nombre', apellido_administrador = '$apellido', pass_administrador = '$hash', correo_administrador = '$email' 
    WHERE id_administrador = $id;");
    //? Retorno de datos aplicando JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Administrador actualizado exitosamente!']);
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al actualizar Administrador!', 'error' => $th]);
};