<?php
session_start();
if (!$_SESSION) {
    header('Location: ../dist/login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
switch ($_SESSION['tipoUsuario']) {
    case 'Instructor':
        header('Location: ../dist/instructores.php?error=true&message=No puedes acceder a esta pagina, Solo se aceptan administradores!&title=Acceso denegado');
        break;
    case 'Aprendiz':
        header('Location: ../dist/aprendices.php?error=true&message=No puedes acceder a esta pagina, Solo se aceptan administradores!&title=Acceso denegado');
        break;
    default:
        break;
}
if (
    isset($_POST['idInstructor']) &&
    isset($_POST['idCurso']) &&
    !empty($_POST['idInstructor']) &&
    !empty($_POST['idCurso'])
) {
    $idInstructor = filter_var($_POST['idInstructor'], FILTER_SANITIZE_NUMBER_INT);
    $idCurso = filter_var($_POST['idCurso'], FILTER_SANITIZE_NUMBER_INT);
    require_once '../../models/MySQL.php';
    $mysql = new MySQL();
    $mysql->conectar();
    try {
        $mysql->efectuarConsulta("INSERT INTO cursos_has_instructores VALUES ($idCurso, $idInstructor);");
        //? Retorno de datos aplicando JSON
        header("Content-Type: application/json");
        echo json_encode(['success' => true, 'message' => 'Instructor asociado exitosamente!']);
    } catch (\Throwable $th) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error al asociar instructor', 'error' => $th]);
    };
} else {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Faltan Datos']);
}
