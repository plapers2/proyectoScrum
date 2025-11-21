<?php

require "../../models/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset($_POST["id_instructor"])
    && !empty($_POST["id_instructor"])
) {
    $id = filter_var($_POST["id_instructor"], FILTER_SANITIZE_NUMBER_INT);
    $eliminar = $sql->efectuarConsulta("UPDATE instructores SET estado_instructor = 'Inactivo'
                            WHERE id_instructor = $id");
    if ($eliminar) {
        echo "ok";
    }
}
$sql->desconectar();