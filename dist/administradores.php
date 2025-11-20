<?php
session_start();
if (!$_SESSION) {
    header('Location: login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
switch ($_SESSION['tipoUsuario']) {
    case 'Instructor':
        header("Location: instructores.php?error=true&message=Acceso denegado, solo se aceptan administradores!&title=Acceso denegado!");
        exit;
    case 'Aprendiz':
        header("Location: aprendices.php?error=true&message=Acceso denegado, solo se aceptan administradores!&title=Acceso denegado!");
        exit;
    default:
        break;
};
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$instructoresDB = $mysql->efectuarConsulta("SELECT * FROM instructores");
$instructores = [];
while ($fila = mysqli_fetch_assoc($instructoresDB)) {
    $instructores[] = $fila;
}

$aprendicesDB = $mysql->efectuarConsulta("SELECT * FROM aprendices;");
$aprendices = [];
while ($fila = mysqli_fetch_assoc($aprendicesDB)) {
    $aprendices[] = $fila;
}

$adminsDB = $mysql->efectuarConsulta("SELECT * FROM administradores;");
$admins = [];
while ($fila = mysqli_fetch_assoc($adminsDB)) {
    $admins[] = $fila;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Biblioteca ADSO</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">
    <!-- Barra de navegación superior -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Sidebar Toggle -->

        <!-- Buscador superior -->
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </div>
        <!-- Dropdown usuario -->
        <ul class="navbar-nav ms-100 ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item text-danger" href="../controller/controllerLogout.php"><i class="bi bi-box-arrow-in-right fs-3"></i> Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Funcionalidades</div>
                        <!-- Editar Perfil -->
                        <?php if ($_SESSION["tipoUsuario"] == "Aprendiz"): ?>
                            <a data-id="<?= $_SESSION["idUsuario"]; ?>"
                                data-nombre="<?= $_SESSION["nombreUsuario"]; ?>"
                                data-apellido="<?= $_SESSION["apellidoUsuario"]; ?>"
                                data-correo="<?= $_SESSION["emailUsuario"]; ?>"
                                class="btn nav-link collapsed" onclick="editarPerfil(this)">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                Editar Perfil
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
                        <?php endif; ?>

                        <?php if (
                            $_SESSION["tipoUsuario"] == "Administrador"
                            || $_SESSION["tipoUsuario"] == "Instructor"
                        ): ?>
                            <a class="nav-link collapsed" href="instructores.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                Instructores
                            </a>
                        <?php endif; ?>

                        <?php if (
                            $_SESSION["tipoUsuario"] == "Instructor"
                            || $_SESSION["tipoUsuario"] == "Aprendiz"
                        ): ?>
                            <a class="nav-link collapsed" href="trabajos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                                Trabajos
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                </div>
                          
            </nav>
        </div>
        <!-- Contenido principal -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Panel de Administracion</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Panel de Administracion</li>
                    </ol>
                    <button class="btn btn-success mb-4" id="administradorInsertar"><i class="bi bi-person-add"></i> Crear nuevo Administrador</button>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Administradores
                        </div>
                        <div class="card-body">
                            <table id="tablaAdministradores">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($admins as $filaUsuario) {
                                    ?>
                                        <tr>
                                            <td><?php echo $filaUsuario['id_administrador'] ?></td>
                                            <td><?php echo $filaUsuario['nombre_administrador']; ?></td>
                                            <td><?php echo $filaUsuario['apellido_administrador']; ?></td>
                                            <td><?php echo $filaUsuario['correo_administrador']; ?></td>
                                            <td>
                                                <button class="btn btn-success mb-4" id="usuarioInsertar"><i class="bi bi-person-add"></i> Editar</button>
                                                <button class="btn btn-success mb-4" id="usuarioInsertar"><i class="bi bi-person-add"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                    <?php
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button class="btn btn-success mb-4" id="instructorInsertar"><i class="bi bi-person-add"></i> Crear nuevo Instructor</button>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Instructores
                        </div>
                        <div class="card-body">
                            <table id="tablaInstructores">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($instructores as $filaInstructor) { ?>
                                        <tr>
                                            <td><?php echo $filaInstructor['id_instructor']; ?></td>
                                            <td><?php echo $filaInstructor['nombre_instructor']; ?></td>
                                            <td><?php echo $filaInstructor['apellido_instructor']; ?></td>
                                            <td><?php echo $filaInstructor['correo_instructor']; ?></td>
                                            <td></td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button class="btn btn-success mb-4" id="aprendizInsertar"><i class="bi bi-person-add"></i> Crear nuevo Aprendiz</button>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Aprendices
                        </div>
                        <div class="card-body">
                            <table id="tablaAprendices">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($aprendices as $filaAprendices) { ?>
                                        <tr>
                                            <td><?php echo $filaAprendices['id_aprendiz']; ?></td>
                                            <td><?php echo $filaAprendices['nombre_aprendiz']; ?></td>
                                            <td><?php echo $filaAprendices['apellido_aprendiz']; ?></td>
                                            <td><?php echo $filaAprendices['correo_aprendiz']; ?></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; ADSO 3064749 / 2025</div>
                        <div>
                            <button class="btn btn-link" id="politicaPrivacidad">Política &amp; Privacidad</button>
                            &middot;
                            <button class="btn btn-link" id="terminosCondiciones">Términos &amp; Condiciones</button>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>
    <script src="js/administradores/sweetAlertAdmin.js"></script>
    <script src="js/sweetAlerts.js"></script>
</body>

</html>