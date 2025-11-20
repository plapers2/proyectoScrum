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

$resultado;

// ADMINISTRADOR → ve todos los aprendices
if ($_SESSION["tipoUsuario"] == "Administrador") {
    $resultado = $mysql->efectuarConsulta("SELECT * FROM aprendices WHERE estado_aprendiz = 'Activo'");
}

// APRENDIZ → ve solo su propio perfIl
if ($_SESSION["tipoUsuario"] == "Aprendiz") {
    $id = $_SESSION["idUsuario"];
    $resultado = $mysql->efectuarConsulta("SELECT * FROM aprendices WHERE id_aprendiz = $id");
}

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
    <title>Aprendices - Biblioteca ADSO</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">

    <!-- NAV -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <button class="btn btn-link btn-sm" id="sidebarToggle"><i class="fas fa-bars"></i></button>

        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item text-danger" id="btn_cerrar_sesion"><i class="bi bi-box-arrow-in-right fs-3"></i> Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">

        <!-- SIDEBAR -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">

                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <div class="sb-sidenav-menu-heading">Funcionalidades</div>

                        <!-- EDICIÓN DE PERFIL -->
                        <?php if ($_SESSION["tipoUsuario"] == "Aprendiz"): ?>
                            <a class="btn nav-link collapsed"
                                data-id="<?= $_SESSION["idUsuario"]; ?>"
                                data-nombre="<?= $_SESSION["nombreUsuario"]; ?>"
                                data-apellido="<?= $_SESSION["apellidoUsuario"]; ?>"
                                data-correo="<?= $_SESSION["emailUsuario"]; ?>"
                                onclick="editarPerfil(this)">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                Editar Perfil
                            </a>
                        <?php endif; ?>

                        <!-- ADMINISTRADOR -->
                        <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                            <a class="nav-link collapsed" href="administradores.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>Administradores
                            </a>
                            <a class="nav-link collapsed" href="cursos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>Cursos
                            </a>
                            <a class="nav-link collapsed" href="aprendices.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>Aprendices
                            </a>
                        <?php endif; ?>

                        <!-- INSTRUCTORES -->
                        <?php if ($_SESSION["tipoUsuario"] == "Administrador" || $_SESSION["tipoUsuario"] == "Instructor"): ?>
                            <a class="nav-link collapsed" href="instructores.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>Instructores
                            </a>
                        <?php endif; ?>

                        <!-- TRABAJOS -->
                        <?php if ($_SESSION["tipoUsuario"] == "Aprendiz" || $_SESSION["tipoUsuario"] == "Instructor"): ?>
                            <a class="nav-link collapsed" href="trabajos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>Trabajos
                            </a>
                        <?php endif; ?>

                    </div>
                </div>

            </nav>
        </div>

        <!-- CONTENIDO -->
        <div id="layoutSidenav_content">
            <main>

                <div class="container-fluid px-4">

                    <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                        <h1 class="mt-4">Panel de Aprendices</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Listado de aprendices</li>
                        </ol>
                    <?php endif; ?>

                    <?php if ($_SESSION["tipoUsuario"] == "Aprendiz"): ?>
                        <h1 class="mt-4">Mi Perfil de Aprendiz</h1>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header d-flex">
                            <i class="fas fa-table me-2"></i>
                            <p>Aprendiz(es)</p>
                        </div>

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
                                        <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                                            <th>Acciones</th>
                                        <?php endif; ?>
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

                                            <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                                                <td>
                                                    <button class="btn btn-sm btn-warning"
                                                        data-id="<?= $a["id_aprendiz"]; ?>"
                                                        data-nombre="<?= $a["nombre_aprendiz"]; ?>"
                                                        data-apellido="<?= $a["apellido_aprendiz"]; ?>"
                                                        data-correo="<?= $a["correo_aprendiz"]; ?>"
                                                        onclick="editarPerfil(this)">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

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
                    Realizado por <span style="color: blueviolet;">CodeAngels</span>
                </div>
            </footer>

        </div>
    </div>

    <?php $mysql->desconectar(); ?>

    <!-- JS DE APRENDICES -->
    <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
        <script src="js/aprendices/editarPerfilAprendiz.js"></script>

    <?php endif; ?>

    <script src="js/cerrar_sesion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>
</body>

</html>