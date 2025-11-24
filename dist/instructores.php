<?php
session_start();
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
if (
    !isset($_SESSION["idUsuario"])
    || empty($_SESSION["idUsuario"])
) {
    header('Location: login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    $mysql->desconectar();
    exit;
}
/* switch ($_SESSION['tipoUsuario']) {
    case 'Administrador':
        header("Location: administradores.php?error=true&message=Acceso denegado, solo se aceptan instructores!&title=Acceso denegado!");
        exit;
    case 'Aprendiz':
        header("Location: instructores.php?error=true&message=Acceso denegado, solo se aceptan instructores!&title=Acceso denegado!");
        exit;
    default:
        break;
} */
$resultado;
if ($_SESSION["tipoUsuario"] == "Administrador") {
    $resultado = $mysql->efectuarConsulta("SELECT * FROM instructores WHERE estado_instructor = 'Activo'");
} else if ($_SESSION["tipoUsuario"] == "Instructor") {
    $id = $_SESSION["idUsuario"];
    $resultado = $mysql->efectuarConsulta("SELECT * FROM instructores WHERE estado_instructor = 'Activo'
                                            AND id_instructor = $id");
}

$instructores = [];
while ($valor = mysqli_fetch_assoc($resultado)) {
    $instructores[] = $valor;
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
    <title>Instructores - Proyecto Scrum ADSO</title>
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
                                <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                                Trabajos
                            </a>

                        <?php endif; ?>

                        <?php if ($_SESSION["tipoUsuario"] == "Administrador" || $_SESSION["tipoUsuario"] == "Instructor"): ?>
                            <a class="nav-link collapsed active" href="instructores.php">
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
        <!-- Contenido principal -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                        <h1 class="mt-4">Panel de Instructores</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Panel de Instructores</li>
                        </ol>
                        <div class="text-end">
                            <button class="btn btn-success mb-2" id="btn_registro_instructor"><i class="bi bi-person-add"></i> Insertar Instructor</button>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION["tipoUsuario"] == "Instructor"): ?>
                        <h1 class="mt-4">Panel de Instructor</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Panel de Instructor</li>
                        </ol>
                    <?php endif; ?>
                    <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                    <?php endif; ?>
                    <div class="card mb-4">
                        <div class="card-header d-flex">
                            <i class="fas fa-table me-2"></i>
                            <p>Instructor</p>
                            <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                                <span>es</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <table id="tablaInstructores">
                                <thead>
                                    <tr>
                                        <th>ID instructor</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Correo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($instructores as $valor): ?>
                                        <tr>
                                            <td><?php echo $valor['id_instructor']; ?></td>
                                            <td><?php echo $valor['nombre_instructor']; ?></td>
                                            <td><?php echo $valor['apellido_instructor']; ?></td>
                                            <td><?php echo $valor['correo_instructor']; ?></td>
                                            <td><?php echo $valor["estado_instructor"]; ?></td>
                                            <td>
                                                <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
                                                    <button data-id="<?= $valor["id_instructor"]; ?>"
                                                        data-nombre="<?= $valor["nombre_instructor"]; ?>"
                                                        data-apellido="<?= $valor["apellido_instructor"]; ?>"
                                                        data-correo="<?= $valor["correo_instructor"]; ?>"
                                                        class="btn btn-sm btn-warning"
                                                        onclick="editarInstructor(this)">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button data-id="<?= $valor["id_instructor"]; ?>"
                                                        data-nombre="<?= $valor["nombre_instructor"]; ?>"
                                                        class="btn btn-sm btn-danger" onclick="eliminarInstructor(this)">
                                                        <i class="bi bi-person-x-fill"></i>
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
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-center small">
                        <div class="text-muted">Realizado por <span style="color: blueviolet;">CodeÁngels</span></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php $mysql->desconectar(); ?>
    <!--JS instructores-->
    <?php if ($_SESSION["tipoUsuario"] == "Administrador"): ?>
        <script src="js/instructores/registrarInstructor.js"></script>
        <script src="js/instructores/editarInstructor.js"></script>
        <script src="js/instructores/eliminarInstructor.js"></script>
    <?php endif; ?>

    <script src="js/cerrar_sesion.js"></script>

    <!--CDNS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>
    <script src="js/sweetAlerts.js"></script>
</body>

</html>