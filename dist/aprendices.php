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
    <title>Panel Adminsitardor - Proyecto Scrum</title>
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
        <a class="navbar-brand ps-3" href="<?php echo ($_SESSION['tipoUsuario'] != "Administrador") ? "libros.php" :  "dashboard.php" ?>">
            <?php echo $_SESSION['nombreUsuario'] . " " . $_SESSION['apellidoUsuario']; ?>
        </a>
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
                    <li><button class="dropdown-item text-success" id="configuracionPerfil" onclick="sweetConfiguracionPerfil('<?php echo $_SESSION['idUsuario'] ?>','<?php echo $_SESSION['tipoUsuario'] ?>')"><i class="bi bi-person-gear fs-3"></i> Configuracion de perfil</button></li>
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
                        <?php
                        if ($_SESSION['tipoUsuario'] === "Administrador") {
                            echo '<div class="sb-sidenav-menu-heading">Administracion</div>
                        <a class="nav-link" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Aprendices
                        </a>';
                        };
                        ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logueado como:</div>
                    <?php echo "<p class='text-uppercase fw-bold mb-0'> " . $_SESSION['tipoUsuario'] . "</p>"; ?>
                </div>
            </nav>
        </div>
        <!-- Contenido principal -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Panel Administrador</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Aprendices</li>
                    </ol>
                    <button class="btn btn-success mb-4" id="usuarioInsertar"><i class="bi bi-person-add"></i> Insertar Aprendiz</button>
                    <button class="btn btn-success mb-4" id="generarArchivos"><i class="bi bi-folder-plus"></i> Generar Archivos</button>
                   
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Aprendices
                        </div>
                        <div class="card-body">
                          <table id="tblAprendices">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Estado</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- recorro los aprendices  -->
                                 <?php foreach($aprendices as $filaAprediz){ ?>
                                         <tr>
                                <td><?php echo $filaAprediz['id_aprendiz'];?></td>
                                <td><?php echo $filaAprediz['nombre_aprendiz'];?></td>
                                <td><?php echo $filaAprediz['apellido_aprendiz'];?></td>
                                <td><?php echo $filaAprediz['estado_aprendiz'];?></td>
                                <td><?php echo $filaAprediz['correo_aprendiz'];?></td>
                                        <td>
                                                <button class="btn btn-warning mb-4" id="usuarioInsertar" onclick="sweetAdminEditar(<?php echo $filaAprediz['id_aprendiz'] ?>)"><i class="bi bi-person-add"></i> Editar</button>
                                                <?php if ($filaAprediz['estado_aprendiz'] == 'Activo') { ?>
                                                    <button class="btn btn-danger mb-4" id="administradorDesactivar" onclick="sweetAdminDesactivar(<?php echo $filaAprediz['id_aprendiz'] ?>)"><i class="bi bi-person-add"></i> Desactivar</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-info mb-4" id="administradorActivar" onclick="sweetAdminActivar(<?php echo $filaAprediz['id_aprendiz'] ?>)"><i class="bi bi-person-add"></i> Activar</button>
                                                <?php } ?>

                                            </td>
                            </tr>
                             <?php    }?>
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
    <script src="js/sweetAlerts.js"></script>
</body>

</html>