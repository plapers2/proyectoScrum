<?php
session_start();
require_once "../../models/MySQL.php";
$sql = new MySQL();
$sql->conectar();

// Validar campos obligatorios
if (
    isset(
        $_POST["nombre_aprendiz"],
        $_POST["apellido_aprendiz"],
        $_POST["correo_aprendiz"],
        $_POST["pass_aprendiz"],
        $_POST["cursos_id_curso"]
    )
    && !empty($_POST["nombre_aprendiz"])
    && !empty($_POST["apellido_aprendiz"])
    && !empty($_POST["correo_aprendiz"])
    && !empty($_POST["pass_aprendiz"])
    && !empty($_POST["cursos_id_curso"])
) {

    // Sanitizar datos
    $nombre = filter_var($_POST["nombre_aprendiz"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $apellido = filter_var($_POST["apellido_aprendiz"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST["correo_aprendiz"], FILTER_SANITIZE_EMAIL);
    $curso = intval($_POST["cursos_id_curso"]);
    $password = trim($_POST["pass_aprendiz"]);

    $aprendiz_repetido = $sql->efectuarConsulta("SELECT id_aprendiz FROM aprendices 
                                                WHERE correo_aprendiz = '$email'");
    if ($aprendiz_repetido->num_rows > 0) {
        echo "Esté correo ya está registrado en la base de datos. Ingresa con otro";
        $sql->desconectar();
        exit;
    }

    // Validar Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Correo no válido";
        $sql->desconectar();
        exit;
    }

    // Encriptar contraseña
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Consulta de inserción
    $insertar = $sql->efectuarConsulta("INSERT INTO aprendices
        (nombre_aprendiz, apellido_aprendiz, estado_aprendiz, correo_aprendiz, pass_aprendiz, cursos_id_curso)
        VALUES
        ('$nombre', '$apellido', '$estado', '$email', '$hash', $curso)
    ");

    if ($insertar) {
        echo "ok";
        $sql->desconectar();
        exit;
    } else {
        echo "No se pudo insertar el aprendiz";
        $sql->desconectar();
        exit;
    }
} else {
    echo "Datos incompletos";
    $sql->desconectar();
    exit;
}
