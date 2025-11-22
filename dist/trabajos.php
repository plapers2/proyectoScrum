<?php
session_start();
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

if (!isset($_SESSION["idUsuario"]) || $_SESSION["tipoUsuario"] != "Instructor") {
    header("Location: login.php?error=true&message=Acceso denegado");
    exit;
}

$idInstructor = $_SESSION["idUsuario"];

// Obtener trabajos de este instructor
$resultado = $mysql->efectuarConsulta("
    SELECT 
        t.id_trabajo,
        t.ruta_trabajo,
        t.fecha_subida,
        t.fecha_limite_trabajo,
        a.nombre_aprendiz,
        a.apellido_aprendiz
    FROM trabajos t
    INNER JOIN aprendices a ON a.id_aprendiz = t.aprendices_id_aprendiz
    WHERE t.instructores_id_instructor = $idInstructor
");

$trabajos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $trabajos[] = $fila;
}

// Obtener aprendices para asignar trabajos
$apres = $mysql->efectuarConsulta("SELECT id_aprendiz, nombre_aprendiz, apellido_aprendiz FROM aprendices");

$listaAprendices = [];
while ($filaA = mysqli_fetch_assoc($apres)) {
    $listaAprendices[] = $filaA;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Trabajos - Instructores</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body class="sb-nav-fixed">

    <?php include 'navbar.php'; ?>

    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">

                <h1 class="mt-4">Administrar Trabajos</h1>
                <p class="mb-4">Asigna trabajos a aprendices y gestiona los ya existentes.</p>

                <!-- Botón abrir modal -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarTrabajo">
                    <i class="bi bi-plus-circle"></i> Asignar nuevo trabajo
                </button>

                <!-- Tabla de trabajos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-briefcase"></i> Trabajos asignados
                    </div>

                    <div class="card-body">
                        <table id="tablaTrabajos">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Aprendiz</th>
                                    <th>Archivo</th>
                                    <th>Fecha de subida</th>
                                    <th>Fecha límite</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($trabajos as $t): ?>
                                    <tr>
                                        <td><?= $t["id_trabajo"]; ?></td>
                                        <td><?= $t["nombre_aprendiz"] . " " . $t["apellido_aprendiz"]; ?></td>
                                        <td><a href="../<?= $t["ruta_trabajo"]; ?>" target="_blank">Ver archivo</a></td>
                                        <td><?= $t["fecha_subida"]; ?></td>
                                        <td><?= $t["fecha_limite_trabajo"]; ?></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="eliminarTrabajo(<?= $t['id_trabajo'] ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                </div>

            </main>
        </div>
    </div>

    <!-- Modal agregar -->
    <div class="modal fade" id="modalAgregarTrabajo">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="../controller/aprendices/insertarTrabajo.php" method="POST" enctype="multipart/form-data">

                    <div class="modal-header">
                        <h5 class="modal-title">Asignar nuevo trabajo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Aprendiz -->
                        <label class="form-label">Selecciona Aprendiz</label>
                        <select class="form-control" name="id_aprendiz" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($listaAprendices as $ap): ?>
                                <option value="<?= $ap["id_aprendiz"]; ?>">
                                    <?= $ap["nombre_aprendiz"] . " " . $ap["apellido_aprendiz"]; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Fecha límite -->
                        <label class="form-label mt-2">Fecha límite</label>
                        <input type="date" name="fecha_limite" class="form-control" required>

                        <!-- Archivo -->
                        <label class="form-label mt-2">Archivo</label>
                        <input type="file" name="archivo" class="form-control" required>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Asignar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function eliminarTrabajo(id) {
            Swal.fire({
                icon: "warning",
                title: "¿Eliminar trabajo?",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar"
            }).then((res) => {
                if (res.isConfirmed) {
                    window.location.href = "../controller/aprendices/eliminarTrabajo.php?id=" + id;
                }
            });
        }
    </script>

</body>

</html>
