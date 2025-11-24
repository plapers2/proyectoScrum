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

if ($_SESSION["tipoUsuario"] == "Instructor") {

    $idInstructor = $_SESSION["idUsuario"];
    $resultado = $mysql->efectuarConsulta("
        SELECT *
    FROM trabajos
    JOIN aprendices ON aprendices.id_aprendiz = trabajos.aprendices_id_aprendiz
    JOIN instructores ON instructores.id_instructor = trabajos.instructores_id_instructor
    JOIN cursos ON cursos.id_curso = aprendices.cursos_id_curso
    WHERE instructores.id_instructor = 
    $idInstructor AND instructores.estado_instructor = 'Activo' AND aprendices.estado_aprendiz = 'Activo';
    ");
} else {

    // Administrador ve TODO sin filtro
    $resultado = $mysql->efectuarConsulta("
        SELECT 
            *
        FROM trabajos AS t
        LEFT JOIN aprendices AS a ON t.aprendices_id_aprendiz = a.id_aprendiz
        LEFT JOIN cursos AS c ON a.cursos_id_curso = c.id_curso
        LEFT JOIN instructores AS i ON t.instructores_id_instructor = i.id_instructor
        WHERE i.estado_instructor = 'Activo' AND a.estado_aprendiz = 'Activo';
    ");
}

$trabajos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $trabajos[] = $fila;
}

// Lista de aprendices
$aprendices_result = $mysql->efectuarConsulta("
    SELECT id_aprendiz, nombre_aprendiz as nombre, apellido_aprendiz as apellido 
    FROM aprendices WHERE aprendices.estado_aprendiz = 'Activo';
");

$aprendices = [];
while ($valor = $aprendices_result->fetch_assoc()) {
    $aprendices[] = $valor;
}

$aprendices_json = htmlspecialchars(
    json_encode($aprendices, JSON_UNESCAPED_UNICODE),
    ENT_QUOTES,
    'UTF-8'
);
$fechaActualConsulta = $mysql->efectuarConsulta("SELECT now() as fecha");
$fechaActual = '';
while ($valor = $fechaActualConsulta->fetch_assoc()) {
    $fechaActual = $valor;
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

    <!-- NAVBAR ORIGINAL -->
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

        <!-- SIDEBAR ORIGINAL -->
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
                            <a class="nav-link collapsed active" href="trabajos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                                Trabajos
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

        <!-- CONTENIDO -->
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">

                <h1 class="mt-4">Listado de trabajos</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Trabajos registrados</li>
                </ol>
                <?php if ($_SESSION["tipoUsuario"] == "Instructor"): ?>
                    <div class="text-end mb-2">
                        <button data-id="<?= $aprendices_json ?>"
                            class="btn btn-success" id="btn_registro_trabajo"><i class="bi bi-person-add mx-1"></i>Agregar</button>
                    </div>
                <?php endif; ?>

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
                                            <a
                                                href="../uploads/trabajos/<?= $t['ruta_trabajo_instructor'] ?>"
                                                target="_blank"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-book"></i>
                                            </a>
                                            <?php if ($t['ruta_trabajo_aprendiz']): ?>
                                                <a
                                                    href="../uploads/trabajos/<?= $t['ruta_trabajo_aprendiz'] ?>"
                                                    target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-file-earmark-person-fill"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($_SESSION["tipoUsuario"] == "Instructor"): ?>
                                                <?php if ($t['fecha_limite_trabajo'] < $fechaActual['fecha'] || $t['ruta_trabajo_aprendiz']): ?>
                                                    <a data-id-trabajo="<?= $t["id_trabajo"]; ?>"
                                                        class="btn btn-success btn-sm"
                                                        onclick="agregarCalificacion(this)">
                                                        <i class="bi bi-journal-check"></i>
                                                    </a>
                                            <?php endif;
                                            endif; ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>

            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-center small">
                        <div class="text-muted">Realizado por <span style="color: blueviolet;">CodeÁngels</span></div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!--JS trabajos-->
    <?php if ($_SESSION["tipoUsuario"] == "Instructor"): ?>
        <script src="js/trabajos/agregarTrabajo.js"></script>
        <script src="js/trabajos/eliminarTrabajo.js"></script>
        <script src="js/trabajos/agregarCalificacion.js"></script>
    <?php endif; ?>
    <script src="js/cerrar_sesion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>

</body>

</html>