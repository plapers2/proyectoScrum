<?php
session_start();
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

/* VALIDAR SESIÓN */
if (!isset($_SESSION['tipoUsuario'])) {
    header('Location: login.php?error=true&message=Debes iniciar sesión primero&title=Acceso denegado');
    exit;
}

/* SOLO APRENDICES PUEDEN ENTRAR */
if ($_SESSION['tipoUsuario'] !== 'aprendiz') {
    header("Location: libros.php?error=true&message=Acceso solo para aprendices&title=Acceso denegado");
    exit;
}

/* CONSULTA APRENDICES */
$resultado = $mysql->efectuarConsulta("SELECT * FROM aprendices");
$usuarios = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $usuarios[] = $fila;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Panel de Aprendiz - Biblioteca ADSO</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="libros.php">
            <?= $_SESSION['nombreUsuario'] . " " . $_SESSION['apellidoUsuario']; ?>
        </a>

        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item text-success" 
                            onclick="sweetConfiguracionPerfil('<?= $_SESSION['idUsuario'] ?>','<?= $_SESSION['tipoUsuario'] ?>')">
                            <i class="bi bi-person-gear fs-3"></i> Configuración de perfil
                        </button>
                    </li>

                    <li><hr class="dropdown-divider" /></li>

                    <li><a class="dropdown-item text-danger" href="../controller/controllerLogout.php">
                        <i class="bi bi-box-arrow-in-right fs-3"></i> Cerrar Sesión
                    </a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">

        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">

                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Aprendiz</div>

                        <a class="nav-link" href="libros.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                            Buscar Libros
                        </a>

                        <a class="nav-link" href="reservasPendientes.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                            Mis Reservas
                        </a>

                        <a class="nav-link" href="prestamos.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                            Mis Préstamos
                        </a>
                    </div>

                </div>

                <div class="sb-sidenav-footer">
                    <div class="small">Logueado como:</div>
                    <p class="text-uppercase fw-bold mb-0"><?= $_SESSION['tipoUsuario']; ?></p>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
                <h1 class="mt-4">Panel de Aprendiz</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Listado de Aprendices</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-table me-1"></i> Aprendices Registrados</div>

                    <div class="card-body">
                        <table id="tablaEmpleados">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo</th>
                                    <th>Curso</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($usuarios as $valor): ?>
                                    <tr>
                                        <td><?= $valor['id_aprendiz']; ?></td>
                                        <td><?= $valor['nombre_aprendiz']; ?></td>
                                        <td><?= $valor['apellido_aprendiz']; ?></td>
                                        <td><?= $valor['correo_aprendiz']; ?></td>
                                        <td><?= $valor['cursos_id_curso']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between small">
                        <div class="text-muted">Copyright ADSO 3064749 – 2025</div>

                        <div>
                            <button class="btn btn-link" id="politicaPrivacidad">Política & Privacidad</button>
                            &middot;
                            <button class="btn btn-link" id="terminosCondiciones">Términos & Condiciones</button>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <script src="js/scripts.js"></script>
    <script src="js/sweetAlerts.js"></script>
</body>

</html>

<?php $mysql->desconectar(); ?>
