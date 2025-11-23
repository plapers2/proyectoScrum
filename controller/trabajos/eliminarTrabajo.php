<?php

require_once "../../models/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_trabajo"]) && !empty($_POST["id_trabajo"])) {
    //* variables
    $id = intval($_POST["id_trabajo"]) ?? 0;
    $inactivar = $sql->efectuarConsulta("UPDATE trabajos SET estado_trabajo = 'Inactivo'
                                        WHERE id_trabajo = $id");
    if ($inactivar) {
        echo "ok";
    } else {
        echo "No se pudo inactivar el trabajo";
    }
} else {
    echo "Falta el ID";
}
$sql->desconectar();
