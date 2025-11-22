<?php
require_once '../models/MySQL.php';

if (!isset($_GET["id"])) { die("ID inválido"); }

$id = intval($_GET["id"]);

$mysql = new MySQL();
$mysql->conectar();

$sql = "SELECT * FROM trabajos WHERE id_trabajo = $id";
$res = $mysql->efectuarConsulta($sql);
$t = mysqli_fetch_assoc($res);

// obtener aprendices
$aprSQL = $mysql->efectuarConsulta("
    SELECT id_aprendiz, nombre_aprendiz, apellido_aprendiz
    FROM aprendices WHERE estado_aprendiz='Activo'
");

$aprendices = [];

while ($a = mysqli_fetch_assoc($aprSQL)) {
    $aprendices[] = [
        "id" => $a["id_aprendiz"],
        "nombre" => $a["nombre_aprendiz"] . " " . $a["apellido_aprendiz"]
    ];
}

echo json_encode([
    "id_trabajo" => $t["id_trabajo"],
    "id_aprendiz" => $t["aprendices_id_aprendiz"],
    "fecha_limite" => $t["fecha_limite_trabajo"],
    "comentario" => $t["comentario_trabajo"],
    "archivo" => $t["ruta_trabajo"],
    "aprendices" => $aprendices
]);
