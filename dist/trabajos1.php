<?php
session_start();
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

if (!isset($_SESSION["idUsuario"])) {
    header("Location: login.php?error=true&message=Debes iniciar sesión&title=Acceso denegado");
    exit;
}

if ($_SESSION["tipoUsuario"] != "Administrador" && $_SESSION["tipoUsuario"] != "Instructor") {
    header("Location: index.php?error=true&message=No tienes permisos para ver esta sección&title=Permiso denegado");
    exit;
}

/* ============================
   CONSULTA PARA ADMINISTRADOR
   ============================ */
if ($_SESSION["tipoUsuario"] == "Administrador") {

    $resultado = $mysql->efectuarConsulta("
        SELECT 
            t.calificacion_trabajo,
            t.comentario_trabajo,
            t.fecha_subida,
            t.fecha_limite_trabajo,
            t.ruta_trabajo,
            a.nombre_aprendiz,
            c.nombre_curso,
            i.nombre_instructor
        FROM trabajos AS t
        LEFT JOIN aprendices AS a ON t.aprendices_id_aprendiz = a.id_aprendiz
        LEFT JOIN cursos AS c ON a.cursos_id_curso = c.id_curso
        LEFT JOIN instructores AS i ON t.instructores_id_instructor = i.id_instructor
        ORDER BY t.fecha_subida DESC
    ");
}

/* ============================
   CONSULTA PARA INSTRUCTOR
   ============================ */
if ($_SESSION["tipoUsuario"] == "Instructor") {

    $idInstructor = $_SESSION["idUsuario"];

    $resultado = $mysql->efectuarConsulta("
        SELECT 
            t.calificacion_trabajo,
            t.comentario_trabajo,
            t.fecha_subida,
            t.fecha_limite_trabajo,
            t.ruta_trabajo,
            a.nombre_aprendiz,
            c.nombre_curso,
            i.nombre_instructor
        FROM trabajos AS t
        INNER JOIN aprendices AS a ON t.aprendices_id_aprendiz = a.id_aprendiz
        INNER JOIN cursos AS c ON a.cursos_id_curso = c.id_curso
        INNER JOIN instructores AS i ON t.instructores_id_instructor = i.id_instructor
        WHERE i.id_instructor = $idInstructor
        ORDER BY t.fecha_subida DESC
    ");
}

$trabajos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $trabajos[] = $fila;
}

$mysql->desconectar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Trabajos - Biblioteca ADSO</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">

    <!-- NAVBAR -->
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
                            <i class="bi bi-box-arrow-in-right fs-3"></i> Cerrar sesión
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

                        <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                            <a class="nav-link collapsed" href="administradores.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div> Administradores
                            </a>
                            <a class="nav-link collapsed" href="cursos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div> Cursos
                            </a>
                            <a class="nav-link collapsed" href="aprendices.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div> Aprendices
                            </a>
                            <a class="nav-link collapsed" href="instructores.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div> Instructores
                            </a>
                            <a class="nav-link collapsed" href="trabajos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div> Trabajos
                            </a>
                        <?php endif; ?>

                        <?php if ($_SESSION["tipoUsuario"] == "Instructor"): ?>
                            <a class="nav-link collapsed" href="trabajos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div> Trabajos
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </nav>
        </div>

        <!-- CONTENIDO -->
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">

                <h1 class="mt-4">Listado de trabajos</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Trabajos registrados</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header d-flex">
                        <i class="fas fa-briefcase me-2"></i>
                        <p>Trabajos</p>
                    </div>

                    <div class="card-body">
                        <table id="tablaTrabajos" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Aprendiz</th>
                                    <th>Curso</th>
                                    <th>Instructor</th>
                                    <th>Calificación</th>
                                    <th>Comentario</th>
                                    <th>Fecha subida</th>
                                    <th>Fecha límite</th>
                                    <th>Archivo</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($trabajos as $t): ?>
                                    <tr>
                                        <td><?= $t["nombre_aprendiz"] ?></td>
                                        <td><?= $t["nombre_curso"] ?></td>
                                        <td><?= $t["nombre_instructor"] ?></td>
                                        <td><?= $t["calificacion_trabajo"] ?></td>
                                        <td><?= $t["comentario_trabajo"] ?></td>
                                        <td><?= $t["fecha_subida"] ?></td>
                                        <td><?= $t["fecha_limite_trabajo"] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info">
                                                <i class="bi bi-file-check"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>

            </main>

            <footer class="py-4 bg-light mt-auto text-center small text-muted">
                Realizado por CodeAngels
            </footer>

        </div>
    </div>

    <script src="js/cerrar_sesion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>

</body>

</html>