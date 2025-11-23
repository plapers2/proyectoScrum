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
    isset($_POST['id'])
) {
    $idCurso = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    require_once '../../models/MySQL.php';
    $mysql = new MySQL();
    $mysql->conectar();
    try {
        $mysql->efectuarConsulta("DELETE FROM cursos_has_instructores 
        WHERE cursos_id_curso = $idCurso;");
        //? Retorno de datos aplicando JSON
        header("Content-Type: application/json");
        echo json_encode(['success' => true, 'message' => 'Instructores eliminados exitosamente (relacion instructor - curso)!']);
    } catch (\Throwable $th) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error al crear curso', 'error' => $th]);
    };
} else {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Faltan Datos']);
}
