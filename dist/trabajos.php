<?php
session_start();
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

// Verificar acceso
if (
    !isset($_SESSION["idUsuario"]) ||
    empty($_SESSION["idUsuario"]) ||
    !in_array($_SESSION["tipoUsuario"], ["Instructor", "Administrador"])
) {
    header('Location: login.php?error=true&message=No tienes permiso para acceder a esta sección!&title=Acceso Denegado');
    exit;
}

$idInstructor = $_SESSION["idUsuario"];

// Instructores ven solo sus trabajos
if ($_SESSION["tipoUsuario"] == "Instructor") {
    $query = "
        SELECT trabajos.*, aprendices.nombre_aprendiz, aprendices.apellido_aprendiz
        FROM trabajos
        LEFT JOIN aprendices ON aprendices.id_aprendiz = trabajos.aprendices_id_aprendiz
        WHERE instructores_id_instructor = $idInstructor
    ";
}

// Administradores ven todos
if ($_SESSION["tipoUsuario"] == "Administrador") {
    $query = "
        SELECT trabajos.*, aprendices.nombre_aprendiz, aprendices.apellido_aprendiz
        FROM trabajos
        LEFT JOIN aprendices ON aprendices.id_aprendiz = trabajos.aprendices_id_aprendiz
    ";
}

$resultado = $mysql->efectuarConsulta($query);
$trabajos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $trabajos[] = $fila;
}

// Lista de aprendices
$apresSQL = $mysql->efectuarConsulta("SELECT * FROM aprendices WHERE estado_aprendiz='Activo'");
$aprendices = [];
while ($a = mysqli_fetch_assoc($apresSQL)) {
    $aprendices[] = $a;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Trabajos</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f3f4f7;
        }

        #layoutSidenav_nav {
            width: 220px;
            background: #343a40;
            color: white;
            min-height: 100vh;
        }

        .nav-link {
            color: white;
            font-size: 15px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sb-nav-link-icon {
            margin-right: 8px;
        }

        main {
            margin-left: 230px;
        }
    </style>
</head>

<body>

    <!-- NAV SUPERIOR -->
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand">Gestión de Trabajos</span>

        <ul class="navbar-nav ms-auto flex-row">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fs-4"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" id="btn_cerrar_sesion">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav" class="d-flex">

        <!-- SIDEBAR -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark p-3">

                <h6 class="text-uppercase text-white-50">Menú</h6>

                <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                    <a class="nav-link" href="administradores.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div> Administradores
                    </a>

                    <a class="nav-link" href="cursos.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div> Cursos
                    </a>

                    <a class="nav-link" href="aprendices.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div> Aprendices
                    </a>
                <?php endif; ?>

                <a class="nav-link" href="instructores.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div> Instructores
                </a>

                <?php if ($_SESSION["tipoUsuario"] == "Instructor"): ?>
                    <a class="nav-link" href="trabajos.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div> Trabajos
                    </a>
                <?php endif; ?>
            </nav>
        </div>

        <!-- CONTENIDO -->
        <main class="container-fluid px-4 mt-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold">Gestión de Trabajos</h2>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInsertarTrabajo">
                    <i class="bi bi-file-earmark-plus"></i> Crear Trabajo
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-briefcase me-1"></i> Lista de trabajos
                </div>

                <div class="card-body bg-white">
                    <table id="tablaTrabajos" class="table table-striped text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Aprendiz</th>
                                <th>Archivo</th>
                                <th>Fecha subida</th>
                                <th>Fecha límite</th>
                                <th>Comentario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($trabajos as $t): ?>
                                <tr>
                                    <td><?= $t["id_trabajo"]; ?></td>
                                    <td><?= $t["nombre_aprendiz"] . " " . $t["apellido_aprendiz"]; ?></td>

                                    <td>
                                        <?php if ($t["ruta_trabajo"]): ?>
                                            <a href="<?= $t["ruta_trabajo"]; ?>" target="_blank" class="btn btn-sm btn-info text-white">
                                                Ver archivo
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Sin archivo</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= $t["fecha_subida"]; ?></td>
                                    <td><?= $t["fecha_limite_trabajo"]; ?></td>
                                    <td><?= $t["comentario_trabajo"]; ?></td>

                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm" data-id="<?= $t['id_trabajo']; ?>" onclick="editarTrabajo(this)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm" data-id="<?= $t['id_trabajo']; ?>" onclick="eliminarTrabajo(this)">
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

    <footer class="py-3 bg-light text-center small mt-4 text-muted">
        Realizado por <b style="color: blueviolet;">CodeAngels</b>
    </footer>

    <!-- MODAL INSERTAR -->
    <div class="modal fade" id="modalInsertarTrabajo">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="../controller/aprendices/insertarTrabajo.php" method="POST" enctype="multipart/form-data">

                    <div class="modal-header">
                        <h5 class="modal-title">Crear Trabajo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id_instructor" value="<?= $idInstructor ?>">

                        <label class="mt-2">Asignar a aprendiz:</label>
                        <select name="id_aprendiz" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($aprendices as $a): ?>
                                <option value="<?= $a["id_aprendiz"] ?>">
                                    <?= $a["nombre_aprendiz"] . " " . $a["apellido_aprendiz"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="mt-3">Fecha límite:</label>
                        <input type="date" name="fecha_limite" class="form-control" required>

                        <label class="mt-3">Archivo:</label>
                        <input type="file" name="archivo" class="form-control" required>

                        <label class="mt-3">Comentario (opcional):</label>
                        <textarea name="comentario" class="form-control"></textarea>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="js/cerrar_sesion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>
    <script src="js/trabajos/accionesTrabajos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            document.querySelectorAll(".editar-btn").forEach(btn => {
                btn.addEventListener("click", function() {

                    let id = this.dataset.id;

                    // 1. Traer datos del trabajo con AJAX
                    fetch("ajax/getTrabajo.php?id=" + id)
                        .then(res => res.json())
                        .then(data => {

                            Swal.fire({
                                title: "Editar Trabajo",
                                html: `
                            <form id="formEditar" enctype="multipart/form-data">

                                <input type="hidden" name="id_trabajo" value="${data.id_trabajo}">

                                <label>Aprendiz:</label>
                                <select class="swal2-input" name="id_aprendiz">
                                    ${data.aprendices.map(a =>
                                        `<option value="${a.id}" ${a.id == data.id_aprendiz ? "selected" : ""}>
                                            ${a.nombre}
                                        </option>`
                                    ).join("")}
                                </select>

                                <label>Fecha límite:</label>
                                <input type="date" class="swal2-input"
                                       name="fecha_limite" value="${data.fecha_limite}">

                                <label>Comentario:</label>
                                <textarea class="swal2-textarea" name="comentario">${data.comentario}</textarea>

                                <label>Archivo nuevo (opcional):</label>
                                <input type="file" class="swal2-input" name="archivo">

                                ${data.archivo ? `<a href="${data.archivo}" target="_blank">Ver archivo actual</a>` : ""}
                            </form>
                        `,
                                confirmButtonText: "Guardar",
                                showCancelButton: true,
                                preConfirm: () => {

                                    let form = document.getElementById("formEditar");
                                    let formData = new FormData(form);

                                    return fetch("../controller/trabajos/actualizarTrabajo.php", {
                                            method: "POST",
                                            body: formData
                                        })
                                        .then(r => r.text())
                                        .then(r => {
                                            if (r.includes("OK")) {
                                                return true;
                                            } else {
                                                Swal.showValidationMessage("Error: " + r);
                                            }
                                        });
                                }
                            }).then(res => {
                                if (res.isConfirmed) {
                                    Swal.fire("Guardado", "El trabajo se actualizó", "success")
                                        .then(() => location.reload());
                                }
                            });
                        });
                });
            });

        });
    </script>

</body>

</html>