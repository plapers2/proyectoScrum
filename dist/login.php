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
    <title>Login - Biblioteca ADSO</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
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
                                        <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary form-control">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><button id="btnRegistro" class="btn btn-primary">Registrarse</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
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
    <script src="js/sweetAlerts.js"></script>
    <script>
        document.getElementById('btnRegistro').addEventListener('click', () => {
            Swal.fire({
                title: '<h2 style="font-weight:600; color:#4a4a4a;">Registro de Usuario</h2>',
                width: '500px',
                background: '#f8f9fc',
                color: '#333',
                showCancelButton: true,
                confirmButtonText: 'Registrar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#6a5acd',
                cancelButtonColor: '#6c757d',
                html: `
            <div style="display:flex; flex-direction:column; gap:10px; margin-top:10px;">
                <input id="nombre" class="swal2-input" placeholder="Nombre">
                <input id="apellido" class="swal2-input" placeholder="Apellido">
                <input id="email" class="swal2-input" placeholder="Email" type="email">
                <input id="pass" class="swal2-input" placeholder="Contraseña" type="password">

                <select id="rol" class="swal2-input">
                    <option value="">Seleccione un rol</option>
                    <option value="aprendiz">Aprendiz</option>
                    <option value="instructor">Instructor</option>
                    <option value="administrador">Administrador</option>
                </select>

                <select id="curso" class="swal2-input" style="display:none;">
                    <option value="">Seleccione un curso</option>
                    <?php
                    require_once '../models/MySQL.php';
                    $mysql = new MySQL();
                    $mysql->conectar();
                    $resultado = $mysql->efectuarConsulta("SELECT id_curso, nombre_curso FROM cursos");
                    while ($curso = mysqli_fetch_assoc($resultado)) {
                        echo "<option value='{$curso['id_curso']}'>{$curso['nombre_curso']}</option>";
                    }
                    ?>
                </select>
            </div>
        `,
                preConfirm: () => {
                    const nombre = document.getElementById('nombre').value;
                    const apellido = document.getElementById('apellido').value;
                    const email = document.getElementById('email').value;
                    const pass = document.getElementById('pass').value;
                    const rol = document.getElementById('rol').value;
                    const curso = document.getElementById('curso').value;

                    if (!nombre || !apellido || !email || !pass || !rol) {
                        Swal.showValidationMessage('Por favor complete todos los campos requeridos');
                        return false;
                    }

                    if (rol === 'aprendiz' && !curso) {
                        Swal.showValidationMessage('Seleccione un curso para el aprendiz');
                        return false;
                    }

                    return {
                        nombre,
                        apellido,
                        email,
                        pass,
                        rol,
                        curso
                    };
                }
            }).then((result) => {
                if (!result.isConfirmed) return;

                const data = result.value;

                fetch('../controller/controllerSigUp.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `usuarioNombre=${encodeURIComponent(data.nombre)}` +
                            `&usuarioApellido=${encodeURIComponent(data.apellido)}` +
                            `&usuarioEmail=${encodeURIComponent(data.email)}` +
                            `&usuarioPass=${encodeURIComponent(data.pass)}` +
                            `&usuarioRol=${encodeURIComponent(data.rol)}` +
                            `&cursoId=${encodeURIComponent(data.curso)}`
                    })
                    .then(res => res.text().then(text => ({
                        status: res.status,
                        text
                    })))
                    .then(({
                        status,
                        text
                    }) => {

                        switch (text) {

                            case "Registrado":
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro Exitoso',
                                    text: 'El usuario ha sido registrado correctamente.',
                                    confirmButtonColor: '#6a5acd'
                                }).then(() => {
                                    window.location.href = '../dist/login.php';
                                });
                                break;

                            case "EmailRepetido":
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Correo ya registrado',
                                    text: 'Este correo ya está en uso.',
                                });
                                break;

                            case "FaltaCurso":
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Falta seleccionar curso',
                                    text: 'El rol Aprendiz requiere un curso.',
                                });
                                break;

                            case "CamposVacios":
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Campos incompletos',
                                    text: 'Faltan datos por llenar.',
                                });
                                break;

                            case "ErrorConsulta":
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error en la consulta',
                                    text: 'Hubo un problema al verificar el correo.',
                                });
                                break;

                            case "ErrorInsertando":
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al registrar',
                                    text: 'No se pudo insertar el registro.',
                                });
                                break;

                            default:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error desconocido',
                                    text: 'El servidor respondió: ' + text,
                                });
                                break;
                        }

                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo contactar con el servidor.',
                        });
                    });
            });

            // Activa el select de curso
            setTimeout(() => {
                const rolSelect = document.getElementById('rol');
                const cursoSelect = document.getElementById('curso');

                rolSelect.addEventListener('change', () => {
                    cursoSelect.style.display = (rolSelect.value === 'aprendiz') ? 'block' : 'none';
                });
            }, 80);
        });
    </script>


</body>

</html>