<?php
session_start();
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

// Validacion de sesion
if (!isset($_SESSION["idUsuario"]) || empty($_SESSION["idUsuario"])) {
    header('Location: login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido&title=Acceso denegado');
    $mysql->desconectar();
    exit;
}

$id = $_SESSION["idUsuario"];

// Consulta de trabajos asignados
$resultado = $mysql->efectuarConsulta("
    SELECT 
        trabajos.*, 
        instructores.nombre_instructor,
        aprendices.nombre_aprendiz
    FROM trabajos
    LEFT JOIN instructores 
        ON trabajos.instructores_id_instructor = instructores.id_instructor
    LEFT JOIN aprendices 
        ON trabajos.aprendices_id_aprendiz = aprendices.id_aprendiz
    WHERE trabajos.aprendices_id_aprendiz = $id;
");

// Guardar registros en un arreglo
$aprendices = [];
while ($valor = mysqli_fetch_assoc($resultado)) {
    $aprendices[] = $valor;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Trabajos</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">

        <!-- CONTENIDO -->
        <div id="layoutSidenav_content">
            <main>

                <div class="container-fluid px-4">

                    <div class="card-body">
                        <table id="TaBlaTrabajos">

                            <thead>
                                <tr>
                                    <th>id_trabajo</th>
                                    <th>calificacion</th>
                                    <th>ruta</th>
                                    <th>comentario</th>
                                    <th>fecha_limite</th>
                                    <th>id_instructor</th>
                                    <th>id_aprendiz</th>
                                    <th>nombre_instructor</th>
                                    <th>nombre_aprendiz</th>
                                    <th>acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($aprendices as $a): ?>
                                    <tr>
                                        <td><?= $a["id_trabajo"]; ?></td>
                                        <td><?= $a["calificacion_trabajo"]; ?></td>
                                        <td><?= $a["ruta_trabajo"]; ?></td>
                                        <td><?= $a["comentario_trabajo"]; ?></td>
                                        <td><?= $a["fecha_limite_trabajo"]; ?></td>
                                        <td><?= $a["instructores_id_instructor"]; ?></td>
                                        <td><?= $a["aprendices_id_aprendiz"]; ?></td>
                                        <td><?= $a["nombre_instructor"]; ?></td>
                                        <td><?= $a["nombre_aprendiz"]; ?></td>

                                        <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                                            <td>
                                                <!-- Boton editar -->
                                                <button class="btn btn-sm btn-warning"
                                                    data-id="<?= $a["id_aprendiz"]; ?>"
                                                    data-nombre="<?= $a["nombre_aprendiz"]; ?>"
                                                    data-apellido="<?= $a["apellido_aprendiz"] ?? ''; ?>"
                                                    data-correo="<?= $a["correo_aprendiz"] ?? ''; ?>"
                                                    onclick="editarPerfil(this)">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Boton eliminar -->
                                                <button class="btn btn-sm btn-danger"
                                                    data-id="<?= $a["id_aprendiz"]; ?>"
                                                    data-nombre="<?= $a["nombre_aprendiz"]; ?>"
                                                    onclick="eliminarAprendiz(this)">
                                                    <i class="bi bi-person-x-fill"></i>
                                                </button>
                                            </td>
                                        <?php endif; ?>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                </div>

        </div>

        </main>

        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4 text-center small text-muted">
                Realizado por CodeAngels
            </div>
        </footer>

    </div>
    </div>

    <?php $mysql->desconectar(); ?>

</body>

</html>