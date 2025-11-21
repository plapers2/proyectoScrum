<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
//* Ya que se usan varias tablas (admins, instructores y aprendices) y no tienen como tal un tipo de usuario, 
//* Se piden los datos para que el controller funcione en cualquiera de las tablas mencionadas
$tipoUsuario = filter_var($_POST['tipoUsuario'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tablaReferenciada = '';
switch ($tipoUsuario) {
    case 'Administrador':
        $tablaReferenciada = 'administradores';
        break;
    case 'Instructor':
        $tablaReferenciada = 'instructores';
        break;
    case 'Aprendiz':
        $tablaReferenciada = 'aprendices';
        break;
    default:
        $tablaReferenciada = '';
        break;
}
try {
    //* Como se menciona anteriormente se usan las variables para una consulta dinamica
    $resultado = $mysql->efectuarConsulta("SELECT * FROM $tablaReferenciada WHERE correo_" . $tipoUsuario . " = '$email';");
} catch (\Throwable $th) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error al verificar email', 'error' => $th]);
};
//* Verificaciones para saber si devuelve o no 
//? True = No hay correos iguales, puede insertar
//? False = Hay un correo igual, no puede insertar
$bool = true;
if (mysqli_num_rows($resultado) > 0) {
    $bool = false;
}
header("Content-Type: application/json");
echo json_encode($bool);