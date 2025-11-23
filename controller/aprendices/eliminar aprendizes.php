<?php
session_start();
require_once "../../models/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_aprendiz"]) && !empty($_POST["id_aprendiz"])) {
    
    $id = intval($_POST["id_aprendiz"]);

    $eliminar = $sql->efectuarConsulta(
        "DELETE FROM aprendices WHERE id_aprendiz = $id"
    );

    if ($eliminar) {
        echo "ok";
        $sql->desconectar();
        exit;
    } else {
        echo "Error al eliminar";
        $sql->desconectar();
        exit;
    }

} else {
    echo "Datos incompletos";
}

$sql->desconectar();
