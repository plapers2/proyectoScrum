<?php
require_once "../../models/MySQL.php";
$sql = new MySQL();
$conexion = $sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $campos = ["id_instructor", "nombre_instructor", "apellido_instructor", "correo_instructor"];
    foreach ($campos as $campo) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
            exit("Error: faltan campos requeridos.");
        }
    }

    $id        = intval($_POST["id_instructor"]);
    $nombre    = filter_var($_POST["nombre_instructor"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $apellido     = filter_var($_POST["apellido_instructor"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $correo = filter_var($_POST["correo_instructor"], FILTER_SANITIZE_EMAIL);

    $instructor_repetido = $sql->efectuarConsulta("SELECT id_instructor FROM instructores
                                            WHERE correo_instructor = '$correo'
                                            AND id_instructor != $id");
    if ($instructor_repetido->num_rows > 0) {
        echo "Ya existe un instructor con ese correo";
        $sql->desconectar();
        exit;
    }

    $editar = $sql->efectuarConsulta("UPDATE instructores SET nombre_instructor = '$nombre',
                                        apellido_instructor = '$apellido', correo_instructor = '$correo',
                                        estado_instructor = 'Activo' WHERE id_instructor = $id");

    if ($editar) {
        echo "ok";
    } else {
        echo "No se pudo editar el libro correctamente.";
    }
}
$sql->desconectar();
