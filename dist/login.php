<?php
if (!empty($_GET['error']) && isset($_GET['error'])) {
    $error = $_GET['error'];
    $message = $_GET['message'];
    $title = $_GET['title'];
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

    <title>Login - Proyecto Scrum</title>

    <title>Login - BACKLOG ADSO</title>

    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!--Estilo personalizado-->
    <link href="css/1-estilo.css" rel="stylesheet">
</head>

<body class="min-vh-100">
    <?php if (!empty($_GET['error']) && isset($_GET['error']) && $error == true) { ?>
        <button class="visually-hidden" id="alertasErrores" onclick="sweetAlertasError('<?php echo $message ?>', '<?php echo $title ?>')"></button>
    <?php } ?>

    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Inicio de Sesion</h3>
                                </div>
                                <div class="card-body">
                                    <form action="../controller/controllerLogin.php" method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="usuarioLogin" type="text" placeholder="Usuario" name="usuarioLogin" />
                                            <label for="usuarioLogin">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="passLogin" type="password" placeholder="Contraseña" name="passLogin" />
                                            <label for="passLogin">Contraseña</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="tipoUsuarioLogin" name="tipoUsuarioLogin">
                                                <option selected value="nada">Selecciona tu rol</option>
                                                <option value="Administrador">Administrador</option>
                                                <option value="Instructor">Instructor</option>
                                                <option value="Aprendiz">Aprendiz</option>
                                            </select>
                                            <label for="tipoUsuarioLogin" class="form-label">Rol</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary form-control">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="registro.php">¿Necesitas una cuenta? Registrate</a></div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-7">
                <div class="text-center mb-4">
                    <div class="icon-circle rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 65px; height: 65px;">
                        <i class="fas fa-tasks fa-2x text-white"></i>
                    </div>
                    <h1 class="h2 fw-bold text-white mb-1">BACKLOG ADSO</h1>
                    <p class="text-white opacity-75 mb-0 small">Sistema de Gestiónes</p>
                </div>
                <div class="card login-card border-0 shadow-lg rounded-3">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="text-center mb-4 fw-semibold text-dark">Iniciar Sesión</h5>
                        <form action="../controller/controllerLogin.php" method="post">
                            <div class="mb-3">
                                <label for="usuarioLogin" class="form-label small fw-semibold text-secondary">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fas fa-user text-secondary"></i>
                                    </span>
                                    <input class="form-control border-start-0 ps-0" id="usuarioLogin" type="text" placeholder="Ingresa tu usuario" name="usuarioLogin" required />

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="passLogin" class="form-label small fw-semibold text-secondary">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fas fa-lock text-secondary"></i>
                                    </span>
                                    <input class="form-control border-start-0 ps-0" id="passLogin" type="password" placeholder="Ingresa tu contraseña" name="passLogin" required />
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="tipoUsuarioLogin" class="form-label small fw-semibold text-secondary">Rol</label>
                                <select class="form-select" id="tipoUsuarioLogin" name="tipoUsuarioLogin" required>
                                    <option selected value="nada">Selecciona tu rol</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Instructor">Instructor</option>
                                    <option value="Aprendiz">Aprendiz</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-login text-white py-2 fw-semibold">Ingresar</button>
                            </div>
                        </form>
                        <hr class="my-4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/sweetAlerts.js"></script>
</body>

</html>