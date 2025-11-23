<?php
header('Content-Type: application/json');

require_once "../../models/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset($_POST["id_trabajo"], $_POST["calificacion_trabajo"], $_POST["comentario_trabajo"])
    && !empty($_POST["calificacion_trabajo"]) && !empty($_POST["comentario_trabajo"])
    && !empty($_POST["id_trabajo"])
) {
    // Variables con validación
    $id_trabajo = filter_var($_POST["id_trabajo"], FILTER_SANITIZE_NUMBER_INT);
    $calificacion_trabajo = strtoupper($_POST["calificacion_trabajo"]);
    $comentario_trabajo = filter_var($_POST["comentario_trabajo"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validar que calificación sea A o D
    if ($calificacion_trabajo !== 'A' && $calificacion_trabajo !== 'D') {
        echo json_encode([
            "success" => false,
            "message" => "La calificación debe ser A o D"
        ]);
        exit;
    }
    $agregar_calificacion = $sql->efectuarConsulta("UPDATE trabajos SET calificacion_trabajo = '$calificacion_trabajo',
                                            comentario_trabajo = '$comentario_trabajo' WHERE id_trabajo = $id_trabajo"
    );

    if ($agregar_calificacion) {
        echo "ok";
    } else {
        echo "No fue posible agregar la calificación";
    }
} else {
    echo "Faltan campos";
}
