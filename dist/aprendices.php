<?php
session_start();
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

if (!isset($_SESSION["idUsuario"]) || empty($_SESSION["idUsuario"])) {
    header('Location: login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    $mysql->desconectar();
    exit;
}

$id = isset($_SESSION["idUsuario"]) ? $_SESSION["idUsuario"] : 0;

$resultado = null;

if ($_SESSION["tipoUsuario"] == "Administrador") {
    $resultado = $mysql->efectuarConsulta("
        SELECT * FROM aprendices 
        WHERE estado_aprendiz = 'Activo'
    ");
}

if ($_SESSION["tipoUsuario"] == "Aprendiz") {
    $resultado = $mysql->efectuarConsulta("
        SELECT * FROM aprendices 
        WHERE id_aprendiz = $id
    ");
}

$aprendices = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $aprendices[] = $fila;
}
$resultadoTrabajos = $mysql->efectuarConsulta("
    SELECT 
        trabajos.id_trabajo,
        instructores.nombre_instructor,
        instructores.apellido_instructor,
        instructores.estado_instructor,
        trabajos.calificacion_trabajo,
        trabajos.ruta_trabajo,
        trabajos.comentario_trabajo,
        trabajos.fecha_limite_trabajo,
        aprendices.nombre_aprendiz
    FROM instructores
    LEFT JOIN trabajos 
        ON trabajos.instructores_id_instructor = instructores.id_instructor
    LEFT JOIN aprendices
        ON trabajos.aprendices_id_aprendiz = aprendices.id_aprendiz
    WHERE trabajos.aprendices_id_aprendiz = $id
");


$trabajos = [];
while ($fila = mysqli_fetch_assoc($resultadoTrabajos)) {
    $trabajos[] = $fila;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Aprendices - Biblioteca ADSO</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">

        <button class="btn btn-link btn-sm" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" id="btn_cerrar_sesion">
                            <i class="bi bi-box-arrow-in-right fs-3"></i> Cerrar sesion
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    </nav>

    <div id="layoutSidenav">

        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">

                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <div class="sb-sidenav-menu-heading">Funciones</div>

                        <?php if ($_SESSION["tipoUsuario"] == "Aprendiz"): ?>
                            <a class="btn nav-link collapsed"
                                data-id="<?= $_SESSION["idUsuario"]; ?>"
                                data-nombre="<?= $_SESSION["nombreUsuario"]; ?>"
                                data-apellido="<?= $_SESSION["apellidoUsuario"]; ?>"
                                data-correo="<?= $_SESSION["emailUsuario"]; ?>"
                                onclick="editarPerfil(this)">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                Editar perfil
                            </a>
                        <?php endif; ?>

                        <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>

                            <a class="nav-link collapsed" href="administradores.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                                Administradores
                            </a>

                            <a class="nav-link collapsed" href="cursos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Cursos
                            </a>

                            <a class="nav-link collapsed" href="aprendices.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                                Aprendices
                            </a>
                            <a class="nav-link collapsed" href="trabajos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                                trabajos
                            </a>

                        <?php endif; ?>

                        <?php if ($_SESSION["tipoUsuario"] == "Administrador" || $_SESSION["tipoUsuario"] == "Instructor"): ?>
                            <a class="nav-link collapsed" href="instructores.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                Instructores
                            </a>
                        <?php endif; ?>

                        <?php if ($_SESSION["tipoUsuario"] == "Instructor"): ?>
                            <a class="nav-link collapsed" href="trabajos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                                Trabajos
                            </a>
                        <?php endif; ?>

                    </div>
                </div>

            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>

                <div class="container-fluid px-4">

                    <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                        <h1 class="mt-4">Panel de aprendices</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Listado de aprendices</li>
                        </ol>
                        <div class="text-end">
                            <button class="btn btn-success mb-2" id="btn_registro_instructor">
                                <i class="bi bi-person-add"></i> Insertar aprendiz
                            </button>

                            <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                                <!-- Modal Insertar Aprendiz -->
                                <div class="modal fade" id="modalInsertAprendiz" tabindex="-1" aria-labelledby="tituloInsertAprendiz" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="tituloInsertAprendiz">Registrar nuevo aprendiz</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <form id="formInsertAprendiz">

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nombre</label>
                                                            <input type="text" class="form-control" name="nombre_aprendiz" required>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Apellido</label>
                                                            <input type="text" class="form-control" name="apellido_aprendiz" required>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Correo</label>
                                                            <input type="email" class="form-control" name="correo_aprendiz" required>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Contraseña</label>
                                                            <input type="password" class="form-control" name="pass_aprendiz" required>
                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Estado</label>
                                                            <select class="form-control" name="estado_aprendiz" required>
                                                                <option value="">Seleccione...</option>
                                                                <option value="Activo">Activo</option>
                                                                <option value="Inactivo">Inactivo</option>
                                                                <option value="Suspendido">Suspendido</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Curso</label>
                                                            <select class="form-control" name="cursos_id_curso" id="selectCursos" required>
                                                                <option value="">Seleccione un curso</option>
                                                            </select>
                                                        </div>

                                                    </div>

                                                </form>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <button type="button" class="btn btn-success" id="btnGuardarAprendiz">
                                                    Guardar aprendiz
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>

                    <?php if ($_SESSION["tipoUsuario"] == "Aprendiz"): ?>
                        <h1 class="mt-4">Mi perfil de aprendiz</h1>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header d-flex">
                            <i class="fas fa-table me-2"></i>
                            <p>Aprendiz(es)</p>
                        </div>

                        <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                            <div class="card-body">
                                <table id="tablaAprendices">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Correo</th>
                                            <th>Curso</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($aprendices as $a): ?>
                                            <tr>
                                                <td><?= $a["id_aprendiz"]; ?></td>
                                                <td><?= $a["nombre_aprendiz"]; ?></td>
                                                <td><?= $a["apellido_aprendiz"]; ?></td>
                                                <td><?= $a["correo_aprendiz"]; ?></td>
                                                <td><?= $a["cursos_id_curso"]; ?></td>
                                                <td><?= $a["estado_aprendiz"]; ?></td>

                                                <td>
                                                    <button class="btn btn-sm btn-info"
                                                        data-id="<?= $a["id_aprendiz"]; ?>"
                                                        data-nombre="<?= $a["nombre_aprendiz"]; ?>"
                                                        data-apellido="<?= $a["apellido_aprendiz"]; ?>"
                                                        data-correo="<?= $a["correo_aprendiz"]; ?>"
                                                        onclick="editarPerfil(this)">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger"
                                                        data-id="<?= $a["id_aprendiz"]; ?>"
                                                        onclick="eliminarAprendiz(this)">
                                                        <i class="bi bi-person-x-fill"></i>
                                                    </button>

                                                </td>



                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="card mb-4 mt-4">
                        <div class="card-header d-flex">
                            <i class="fas fa-briefcase me-2"></i>
                            <p>Mis trabajos asignados</p>
                        </div>
                        <div class="modal fade" id="modalEditarRuta" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <form action="../controller/aprendices/subirTrabajo.php" method="POST" enctype="multipart/form-data">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar archivo del trabajo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <input type="hidden" name="id_trabajo" id="id_trabajo">

                                            <div class="mb-3">
                                                <label class="form-label">Selecciona nuevo archivo</label>
                                                <input type="file" class="form-control" name="nuevo_archivo" required>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Actualizar archivo</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <table id="tablaTrabajos">
                                <thead>
                                    <tr>
                                        <th>Nombre instructor</th>
                                        <th>Apellido instructor</th>
                                        <th>Estado instructor</th>
                                        <th>calificacion_trabajo</th>
                                        <th>Ruta archivo</th>
                                        <th>Comentario trabajo</th>
                                        <th>Fecha limite</th>
                                        <th>Nombre aprendiz</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trabajos as $t): ?>
                                        <tr>
                                            <td><?= $t["nombre_instructor"]; ?></td>
                                            <td><?= $t["apellido_instructor"]; ?></td>
                                            <td><?= $t["estado_instructor"]; ?></td>
                                            <td><?= $t["calificacion_trabajo"]; ?></td>
                                            <td><?= $t["ruta_trabajo"]; ?></td>
                                            <td><?= $t["comentario_trabajo"]; ?></td>
                                            <td><?= $t["fecha_limite_trabajo"]; ?></td>
                                            <td><?= $t["nombre_aprendiz"]; ?></td>
                                            <td>
                                                <?php if ($t["calificacion_trabajo"] == ""): ?>
                                                    <button class="btn btn-sm btn-warning"
                                                        data-id="<?= $t['id_trabajo']; ?>"
                                                        onclick="editarRutaTrabajo(this)">
                                                        <i class="bi bi-journal-arrow-up"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

            </main>

            <footer class="py-4 bg-light mt-auto text-center small text-muted">
                Realizado por CodeAngels
            </footer>

        </div>
    </div>

    <?php $mysql->desconectar(); ?>

    <script src="js/cerrar_sesion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>

    <?php if ($_SESSION["tipoUsuario"] == "Administrador" || $_SESSION["tipoUsuario"] == "Aprendiz"): ?>
        <script src="js/aprendices/editarPerfilAprendiz.js"></script>
    <?php endif; ?>
    <script>
        function editarRutaTrabajo(boton) {
            let id = boton.getAttribute("data-id");
            document.getElementById("id_trabajo").value = id;

            let modal = new bootstrap.Modal(document.getElementById("modalEditarRuta"));
            modal.show();
        }
    </script>
    <script>
        // leer parametros de la url
        const params = new URLSearchParams(window.location.search);

        const success = params.get("success");
        const error = params.get("error");
        const message = params.get("message");
        const title = params.get("title");

        // si hay mensaje mostrar alerta
        if (message && title) {

            let icono = "info";

            if (success === "true") {
                icono = "success";
            }

            if (error === "true") {
                icono = "error";
            }

            Swal.fire({
                title: title,
                text: message,
                icon: icono,
                confirmButtonText: "OK"
            });

            // eliminar parametros de la url
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    <script>
        document.getElementById("btn_registro_instructor").addEventListener("click", () => {
            let modal = new bootstrap.Modal(document.getElementById("modalInsertAprendiz"));
            modal.show();
        });
    </script>
    <script src="./js/aprendices/eliminarAprendiz.js"></script>

</body>

</html>