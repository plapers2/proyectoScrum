<?php
session_start();
if (!$_SESSION) {
    header('Location: login.php');
    exit;
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$resultado = $mysql->efectuarConsulta("SELECT * FROM libro_has_reserva AS pivote
JOIN libro ON libro.idLibro = pivote.fkLibroPivote
JOIN reserva ON reserva.idReserva = pivote.fkReservaPivote
JOIN usuario ON usuario.idUsuario = reserva.fkUsuarioReserva
JOIN tipoUsuario ON usuario.fkTipoUsuario = tipoUsuario.idTipoUsuario
" . (($_SESSION['tipoUsuario'] != 'Administrador') ? "WHERE usuario.idUsuario =" . $_SESSION['idUsuario'] : "")
    . " GROUP BY pivote.fkReservaPivote;");
$datos = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $datos[] = $row;
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
    <title>Gestión de Libros - Biblioteca ADSO</title>
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
                    <li><button class="dropdown-item text-success" id="configuracionPerfil" name="<?php echo $_SESSION['idUsuario'] ?>"><i class="bi bi-person-gear fs-3"></i> Configuracion de perfil</button></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item text-danger" href="../controller/controllerLogout.php"><i class="bi bi-box-arrow-in-right fs-3"></i> Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Sidebar lateral-->
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
                            Panel de Administracion
                        </a>';
                        };
                        ?>
                        <div class="sb-sidenav-menu-heading">Libros</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages"
                            aria-expanded="true" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Gestión de Libros
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapsePages" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="libros.php">Búsqueda de Libros</a>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReservas"
                                    aria-expanded="true" aria-controls="collapseReservas">
                                    Reservas
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>

                                <div class="collapse show" id="collapseReservas" data-bs-parent="#collapseLibros">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="reservasPendientes.php">Reservas Pendientes</a>
                                        <a class="nav-link active" href="reservasHistorial.php">Historial de Reservas</a>
                                    </nav>
                                </div>
                                <a class="nav-link" href="prestamos.php">Préstamos</a>
                            </nav>
                        </div>

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
                    <h1 class="mt-4">Gestión de Libros</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Gestión de Libros</li>
                        <li class="breadcrumb-item">Reservas</li>
                        <li class="breadcrumb-item fw-bold">Historial de reservas</li>
                    </ol>
                    <!-- Tabla -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Empleados
                        </div>
                        <div class="card-body">
                            <table id="tablaEmpleados">
                                <thead>
                                    <tr>

                                        <th>ID Reserva</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Estado</th>
                                        <th>Libros</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID Reserva</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Estado</th>
                                        <th>Libros</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($datos as $row):  ?>
                                        <tr>
                                            <td><?php echo $row['idReserva'] ?></td>
                                            <td><?php echo $row['nombreUsuario'] ?></td>
                                            <td><?php echo $row['apellidoUsuario'] ?></td>
                                            <?php
                                            $claseEstado;
                                            $icon;
                                            switch ($row['estadoReserva']) {
                                                case 'En espera':
                                                    $claseEstado = "bg-warning";
                                                    $icon = '<i class="bi bi-clock"></i>';
                                                    break;
                                                //? Cuando se necesita evaluar 2 expresiones para un mismo codigo se ponen de la siguiente manera:
                                                case 'Cancelada':
                                                case 'Solicitud rechazada':
                                                    $claseEstado = "bg-danger";
                                                    $icon = '<i class="bi bi-x-circle"></i>';
                                                    break;
                                                case 'Aprobada':
                                                    $claseEstado = "bg-success";
                                                    $icon = '<i class="bi bi-check-circle"></i>';
                                                    break;
                                                case 'Activo':
                                                    $claseEstado = "bg-info";
                                                    $icon = '<i class="bi bi-check-circle"></i>';
                                                    break;
                                                case 'Devuelto':
                                                    $claseEstado = "bg-primary";
                                                    $icon = '<i class="bi bi-check-circle"></i>';
                                                    break;
                                                default:
                                                    $claseEstado = "";
                                                    break;
                                            }
                                            ?>
                                            <td><?php echo '<span class="badge ' . $claseEstado . ' text-black fs-6 p-2">' . $icon . " " . $row['estadoReserva'] . '</span>' ?></td>
                                            <td><?php echo '<button class="btn btn-success" onclick="sweetLibrosInfo(' . $row['idReserva'] . ',`' . $row['tipoUsuario'] . '`)"><i class="bi bi-card-list"></i> Listado de libros</button>' ?></td>
                                        </tr>
                                    <?php endforeach ?>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/sweetAlerts.js"></script>
</body>

</html>