<?php
session_start();
if (!$_SESSION) {
    header('Location: login.php');
    exit;
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$idUsuario = $_SESSION['idUsuario'];
// consulta para obtener préstamos con información relacionada
$resultado = $mysql->efectuarConsulta('SELECT *
    FROM prestamo
    INNER JOIN reserva ON prestamo.fkReservaPrestamo = reserva.idReserva
    INNER JOIN usuario ON reserva.fkUsuarioReserva = usuario.idUsuario
    INNER JOIN libro_has_reserva ON reserva.idReserva = libro_has_reserva.fkReservaPivote
    INNER JOIN libro ON libro_has_reserva.fkLibroPivote = libro.idLibro
    ' . (($_SESSION['tipoUsuario'] === 'Cliente') ? " WHERE usuario.idUsuario = $idUsuario " : "") . 'GROUP BY reserva.idReserva ORDER BY prestamo.idPrestamo ASC;');

$prestamos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $prestamos[] = $fila;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Gestión de Préstamos - Biblioteca ADSO</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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

    <!-- sidebar -->
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

                                <div class="collapse" id="collapseReservas" data-bs-parent="#collapseLibros">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="reservasPendientes.php">Reservas Pendientes</a>
                                        <a class="nav-link" href="reservasHistorial.php">Historial de Reservas</a>
                                    </nav>
                                </div>
                                <a class="nav-link active" href="prestamos.php">Préstamos</a>
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

        <!-- contenido principal -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Gestión de Préstamos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Préstamos</li>
                    </ol>

                    <!-- estadísticas rápidas -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <h4 id="prestamosActivos">0</h4>
                                    <small>Préstamos Activos</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <small>Total en curso</small>
                                    <i class="fas fa-hand-holding"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <h4 id="devolucionesHoy">0</h4>
                                    <small>Devoluciones Hoy</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <small>Entregas programadas</small>
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">
                                    <h4 id="prestamosVencidos">0</h4>
                                    <small>Préstamos Vencidos</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <small>Requieren atención</small>
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body">
                                    <h4 id="totalMes">0</h4>
                                    <small>Total del Mes</small>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <small>Noviembre 2025</small>
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- filtros de búsqueda -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-filter me-1"></i>
                            Filtros de Búsqueda
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="busquedaPrestamo" class="form-label">Buscar</label>
                                    <input type="text" class="form-control" id="busquedaPrestamo"
                                        placeholder="Cliente, Libro o ISBN">
                                </div>
                                <div class="col-md-3">
                                    <label for="estadoPrestamo" class="form-label">Estado</label>
                                    <select class="form-select" id="estadoPrestamo">
                                        <option value="todos">Todos los estados</option>
                                        <option value="activo">Activos</option>
                                        <option value="devuelto">Devueltos</option>
                                        <option value="vencido">Vencidos</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="fechaDesde" class="form-label">Desde</label>
                                    <input type="date" class="form-control" id="fechaDesde">
                                </div>
                                <div class="col-md-2">
                                    <label for="fechaHasta" class="form-label">Hasta</label>
                                    <input type="date" class="form-control" id="fechaHasta">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100" onclick="filtrarPrestamos('<?php echo $_SESSION['tipoUsuario'] ?>')">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-secondary w-100" onclick="limpiarFiltros('<?php echo $_SESSION['tipoUsuario'] ?>')">
                                        <i class="fas fa-times me-1"></i>Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- botones de acción -->
                    <?php if ($_SESSION['tipoUsuario'] === 'Administrador') { ?>
                        <div class="mb-4">
                            <button class="btn btn-success" id="registrarPrestamo">
                                <i class="fas fa-plus me-1"></i>Registrar Préstamo
                            </button>
                        </div>
                    <?php } ?>
                    <!-- tabla de préstamos -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Préstamos Registrados
                        </div>
                        <div class="card-body">
                            <table id="tablaPrestamos" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Prestamo</th>
                                        <th>ID Reserva</th>
                                        <th>Cliente</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Fecha Limite Devolucion</th>
                                        <th>Fecha Devolución</th>
                                        <th>Estado</th>
                                        <?php if ($_SESSION['tipoUsuario'] === 'Administrador') { ?>
                                            <th>Acciones</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($prestamos) > 0) {
                                        foreach ($prestamos as $prestamo) {
                                            $fechaPrestamo = strtotime($prestamo['fechaPrestamo']);
                                            $fechaLimite = strtotime('+15 days', $fechaPrestamo);
                                            $hoy = time();
                                    ?>
                                            <tr>
                                                <td><?= $prestamo['idPrestamo']; ?></td>
                                                <td><?= htmlspecialchars($prestamo['idReserva']) ?></td>
                                                <td><?= htmlspecialchars($prestamo['nombreUsuario'] . ' ' . $prestamo['apellidoUsuario']); ?></td>
                                                <td><?= htmlspecialchars($prestamo['fechaPrestamo']); ?></td>
                                                <td><?= htmlspecialchars($prestamo['fechaLimite']); ?></td>
                                                <td>
                                                    <?php
                                                    if ($prestamo['fechaDevolucion']) {
                                                        echo $prestamo['fechaDevolucion'];
                                                    } else {
                                                        echo '<span class="text-muted">Pendiente</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($prestamo['fechaDevolucion']) {
                                                        echo '<span class="badge bg-success">Devuelto</span>';
                                                    } else {
                                                        if ($hoy > $fechaLimite) {
                                                            echo '<span class="badge bg-danger">Vencido</span>';
                                                        } else {
                                                            echo '<span class="badge bg-primary">Activo</span>';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!$prestamo['fechaDevolucion'] && $_SESSION['tipoUsuario'] === 'Administrador') { ?>
                                                        <button class="btn btn-sm btn-success mb-2" onclick="sweetPrestamoRegistrarDevolucion(<?= $prestamo['idPrestamo']; ?>)">
                                                            <i class="bi bi-check-circle"></i> Devolver
                                                        </button>
                                                    <?php } ?>
                                                    <button class="btn btn-sm btn-info text-white" onclick="sweetPrestamoVerDetalles(<?= $prestamo['idPrestamo']; ?>)">
                                                        <i class="bi bi-eye"></i> Detalles
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No hay préstamos registrados</td>
                                        </tr>
                                    <?php } ?>
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

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/sweetAlerts.js"></script>
    <script src="js/prestamos.js"></script>
</body>

</html>