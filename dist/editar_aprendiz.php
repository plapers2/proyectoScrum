<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!isset($_SESSION['id_aprendiz']) || $_SESSION[''] != 'administrador' || $_SESSION['activo'] != "activo") {
  header("Location: login.php");
  exit();
}

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$id = $_GET['id'];
$consulta = "SELECT * FROM aprendices WHERE id = $id";
$resultado = $mysql->efectuarConsulta($consulta);
$aprendiz = mysqli_fetch_assoc($resultado);
$mysql->desconectar();


?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Editar - Proyecto Scrum</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
  <div class="dashboard-container">


    <div class="sidebar">
      <div>
        <h5 class="mb-4 d-flex align-items-center"><i class="bi bi-book-half me-2"></i>Proyecto Scrum</h5>
        <nav class="nav flex-column">
          <a href="dashboard.php" class="nav-link "><i class="bi bi-house me-2"></i>Dashboard</a>

          <a href="gestionar_libros.php" class="nav-link active"><i class="bi bi-journal-bookmark me-2"></i>Aprendices</a>
          <a href="gestionar_reservas.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i>Fichas</a>
          <a href="gestionar_prestamos.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>instructores</a>
          <a href="gestionar_usuarios.php" class="nav-link"><i class="bi bi-people me-2"></i>Tarbajos</a>
        </nav>
      </div>

      <button class="btn logout-btn w-100 mt-4 btnLogout">
        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
        </a></button>
    </div>


    <div class="content">
      <div class="content-header mb-4">
        <div>
          <h2 class="fw-bold text-success mb-0">Editar Libro</h2>
          <p class="text-muted">Modifica los datos del libro seleccionado.</p>
        </div>
        <div class="user-info">
          <i class="bi bi-person-circle me-2"></i><?php echo ($_SESSION['tipo_usuario']); ?>
        </div>
      </div>


      <form id="formEditarLibro">
        <input type="hidden" id="id" value="<?php echo $libro['id']; ?>">

        <h4 class="mb-4"><i class="bi bi-journal-bookmark me-2"></i> Datos del Libro</h4>

        <div class="mb-3">
          <label for="titulo" class="form-label">Título</label>
          <input type="text" class="form-control" id="titulo" value="<?php echo htmlspecialchars($aprendiz['nombre_aprendiz']); ?>" required>
        </div>

        <div class="mb-3">
          <label for="autor" class="form-label">Autor</label>
          <input type="text" class="form-control" id="autor" value="<?php echo htmlspecialchars($aprendiz['aprellido_aprendiz']); ?>" required>
        </div>
        <div class="mb-3">
          <label for="categoria" class="form-label">Categoría</label>
          <input type="text" class="form-control" id="categoria" value="<?php echo htmlspecialchars($aprendiz['correo_aprendiz']); ?>" required>
        </div>
        <div class="mb-3">
        <div class="d-flex justify-content-between mt-4">
          <a href="gestionar_libros.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
          <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/sweetAlerts.js"></script>
</body>


</html>