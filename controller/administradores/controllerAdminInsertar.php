<?php 
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
if (
    isset($_POST['nombre']) &&
    isset($_POST['apellido']) &&
    isset($_POST['email']) &&
    isset($_POST['pass']) &&
    !empty($_POST['nombre']) &&
    !empty($_POST['apellido']) &&
    !empty($_POST['email']) &&
    !empty($_POST['pass'])
) {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    require_once '../../models/MySQL.php';
    $mysql = new MySQL();
    $mysql->conectar();
    try {
        $mysql->efectuarConsulta("INSERT INTO administradores VALUES (null,'$nombre','$apellido','Activo','$hash','$email');");
        //? Retorno de datos aplicando JSON
        header("Content-Type: application/json");
        echo json_encode(['success' => true, 'message' => 'Administrador creado exitosamente!']);
    } catch (\Throwable $th) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error al crear administrador', 'error' => $th]);
    };
} else {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Faltan Datos']);
}


?>