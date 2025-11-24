<?php
require_once "../../models/MySQL.php";
$sql = new MySQL();
$conexion = $sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Campos requeridos
    $campos = [
        "id_aprendiz",
        "nombre_aprendiz",
        "apellido_aprendiz",
        "correo_aprendiz",
        "cursos_id_curso"
    ];

    foreach ($campos as $campo) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
            exit("Error: faltan campos requeridos ($campo).");
        }
    }

    // Sanitización
    $id       = intval($_POST["id_aprendiz"]);
    $nombre   = filter_var($_POST["nombre_aprendiz"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $apellido = filter_var($_POST["apellido_aprendiz"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $correo   = filter_var($_POST["correo_aprendiz"], FILTER_SANITIZE_EMAIL);
    $curso    = intval($_POST["cursos_id_curso"]);

    $repetido = $sql->efectuarConsulta("SELECT id_aprendiz FROM aprendices
        WHERE correo_aprendiz = '$correo'
        AND id_aprendiz != $id");

    if ($repetido && $repetido->num_rows > 0) {
        echo "Ya existe un aprendiz con esté correo. Por favor ingresa otro";
        $sql->desconectar();
        exit;
    }

    // Update
    $sqlUpdate = "
        UPDATE aprendices SET
            nombre_aprendiz = '$nombre',
            apellido_aprendiz = '$apellido',
            correo_aprendiz = '$correo',
            cursos_id_curso = $curso,
            estado_aprendiz = 'Activo'
        WHERE id_aprendiz = $id
    ";

    $editar = $sql->efectuarConsulta($sqlUpdate);

    echo $editar ? "ok" : "No se pudo editar el aprendiz.";
}

$sql->desconectar();
