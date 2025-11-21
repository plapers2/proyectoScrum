<?php
session_start();
if (!$_SESSION) {
    header('Location: login.php?error=true&message=No puedes acceder a esta pagina, inicia sesion con un usuario valido!&title=Acceso denegado');
    exit;
}
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
$resultado = '';
switch ($_SESSION['tipoUsuario']) {
    case 'Aprendiz':
        header("Location: aprendices.php?error=true&message=Acceso denegado, solo se acepta personal autorizado!&title=Acceso denegado!");
        exit;
    case 'Administrador':
        $resultado = $mysql->efectuarConsulta("SELECT * FROM cursos ORDER BY estado_curso ASC, id_curso ASC;");
        break;
    case 'Instructor':
        $resultado = $mysql->efectuarConsulta("SELECT * FROM cursos_has_instructores as p 
        JOIN cursos as c ON c.id_curso = p.cursos_id_curso 
        WHERE c.estado_curso = 'Activo' AND p.cursos_id_curso = " . $_SESSION['idUsuario'] . " GROUP BY c.id_curso ORDER BY c.estado_curso DESC;");
        break;
    default:
        break;
};
$datos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila;
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
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@10.2.0/dist/style.min.css" rel="stylesheet" />
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

                            <a class="nav-link collapsed" href="aprendices.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                                Aprendices
                            </a>
                        <?php endif; ?>

                        <?php if (
                            $_SESSION["tipoUsuario"] == "Administrador"
                            || $_SESSION["tipoUsuario"] == "Instructor"
                        ): ?>
                            <a class="nav-link collapsed" href="cursos.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Cursos
                            </a>
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
                    <h1 class="mt-4">Cursos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Panel de Administracion</li>
                    </ol>
                    <?php if ($_SESSION['tipoUsuario'] == 'Administrador') { ?>
                        <button class="btn btn-success mb-4 fs-4" id="cursoInsertar"><i class="bi bi-journal-plus"></i> Crear nuevo Curso</button>
                    <?php } ?>
                    <div class="card mb-4 border-black">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Cursos
                        </div>
                        <div class="card-body">
                            <table id="tablaCursos" class="table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Descripcion</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Descripcion</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($datos as $datoFila) { ?>
                                        <tr>
                                            <td class="fs-6"><?php echo $datoFila['id_curso']; ?></td>
                                            <td class="fs-6"><?php echo $datoFila['nombre_curso']; ?></td>
                                            <td class="fs-6"><?php echo $datoFila['descripcion_curso']; ?></td>
                                            <td class="d-flex justify-content-center gap-1">
                                                <?php if ($_SESSION['tipoUsuario'] == 'Administrador') { ?>
                                                    <?php if ($datoFila['estado_curso'] == 'Activo') { ?>
                                                        <button class="btn btn-warning btn-sm fs-6" id="cursoVerInstructores" onclick="sweetCursoEditar(<?php echo $datoFila['id_curso'] ?>)"><i class="bi bi-pencil-square"></i> Editar</button>
                                                        <button class="btn btn-danger btn-sm fs-6" id="cursoDesactivar" onclick="sweetCursoDesactivar(<?php echo $datoFila['id_curso'] ?>)"><i class="bi bi-trash"></i> Desactivar</button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-success btn-sm fs-6" id="cursoActivar" onclick="sweetCursoActivar(<?php echo $datoFila['id_curso'] ?>)"><i class="bi bi-check-circle"></i> Activar</button>
                                                    <?php } ?>
                                                    <button class="btn btn-primary btn-sm fs-6" id="cursoVerInstructores" onclick="sweetCursoVerInstructores(<?php echo $datoFila['id_curso'] ?>,'<?php echo $datoFila['estado_curso'] ?>')"><i class="bi bi-eye"></i> Instructores</button>
                                                <?php } ?>
                                                <button class="btn btn-primary btn-sm fs-6" id="cursoVerAprendices" onclick="sweetCursoVerAprendices(<?php echo $datoFila['id_curso'] ?>)"><i class="bi bi-eye"></i> Aprendices</button>
                                            </td>
                                        </tr>
                                    <?php }
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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@10.2.0/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>
    <script src="js/cursos/sweetAlertCursos.js"></script>
    <script src="js/sweetAlerts.js"></script>
</body>

</html>