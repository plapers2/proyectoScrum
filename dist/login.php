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
    <script>
        document.getElementById('btnRegistro').addEventListener('click', () => {
            Swal.fire({
                title: '<h2 style="font-weight:600; color:#1f2937; margin-bottom:0.5rem;"><i class="fas fa-user-plus" style="color:#059669; margin-right:10px;"></i>Registro de Usuario</h2>',
                width: '550px',
                background: 'linear-gradient(145deg, #ffffff 0%, #f9fafb 100%)',
                color: '#1f2937',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check-circle me-2"></i>Registrar',
                cancelButtonText: '<i class="fas fa-times-circle me-2"></i>Cancelar',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                customClass: {
                    popup: 'custom-swal-popup',
                    confirmButton: 'custom-confirm-btn',
                    cancelButton: 'custom-cancel-btn'
                },
                html: `
            <style>
                .custom-swal-popup {
                    border-radius: 20px !important;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
                }
                .swal2-input, .swal2-select {
                    border-radius: 10px !important;
                    border: 2px solid #e5e7eb !important;
                    padding: 0.75rem 1rem !important;
                    font-size: 0.95rem !important;
                    transition: all 0.3s ease !important;
                    width: 90% !important;
                    margin: 0.4rem auto !important;
                }
                .swal2-input:focus, .swal2-select:focus {
                    border-color: #059669 !important;
                    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
                    outline: none !important;
                }
                .swal2-input::placeholder {
                    color: #9ca3af;
                }
                .custom-confirm-btn, .custom-cancel-btn {
                    border-radius: 10px !important;
                    padding: 0.65rem 1.5rem !important;
                    font-weight: 600 !important;
                    font-size: 0.95rem !important;
                    transition: all 0.3s ease !important;
                    border: none !important;
                }
                .custom-confirm-btn:hover {
                    transform: translateY(-2px) !important;
                    box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4) !important;
                }
                .custom-cancel-btn:hover {
                    transform: translateY(-2px) !important;
                    box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4) !important;
                }
                .input-icon-wrapper {
                    position: relative;
                    width: 90%;
                    margin: 0.4rem auto;
                }
                .input-icon {
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #059669;
                    font-size: 0.9rem;
                }
                .input-with-icon {
                    padding-left: 2.5rem !important;
                }
            </style>
            <div style="display:flex; flex-direction:column; gap:8px; margin-top:20px;">
                <div class="input-icon-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input id="nombre" class="swal2-input input-with-icon" placeholder="Nombre" style="width:100% !important; margin:0 !important;">
                </div>
                
                <div class="input-icon-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input id="apellido" class="swal2-input input-with-icon" placeholder="Apellido" style="width:100% !important; margin:0 !important;">
                </div>
                
                <div class="input-icon-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" class="swal2-input input-with-icon" placeholder="Email" type="email" style="width:100% !important; margin:0 !important;">
                </div>
                
                <div class="input-icon-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="pass" class="swal2-input input-with-icon" placeholder="Contraseña" type="password" style="width:100% !important; margin:0 !important;">
                </div>

                <div class="input-icon-wrapper">
                    <i class="fas fa-user-tag input-icon"></i>
                    <select id="rol" class="swal2-input input-with-icon" style="width:100% !important; margin:0 !important;">
                        <option value="">Seleccione un rol</option>
                        <option value="aprendiz">Aprendiz</option>
                        <option value="instructor">Instructor</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>

                <div class="input-icon-wrapper" style="display:none;" id="curso-wrapper">
                    <i class="fas fa-book input-icon"></i>
                    <select id="curso" class="swal2-input input-with-icon" style="width:100% !important; margin:0 !important; display:block;">
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
                                    confirmButtonColor: '#059669',
                                    customClass: {
                                        popup: 'custom-swal-popup',
                                        confirmButton: 'custom-confirm-btn'
                                    }
                                }).then(() => {
                                    window.location.href = '../dist/login.php';
                                });
                                break;

                            case "EmailRepetido":
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Correo ya registrado',
                                    text: 'Este correo ya está en uso.',
                                    confirmButtonColor: '#059669',
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                                break;

                            case "FaltaCurso":
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Falta seleccionar curso',
                                    text: 'El rol Aprendiz requiere un curso.',
                                    confirmButtonColor: '#059669',
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                                break;

                            case "CamposVacios":
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Campos incompletos',
                                    text: 'Faltan datos por llenar.',
                                    confirmButtonColor: '#059669',
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                                break;

                            case "ErrorConsulta":
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error en la consulta',
                                    text: 'Hubo un problema al verificar el correo.',
                                    confirmButtonColor: '#059669',
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                                break;

                            case "ErrorInsertando":
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al registrar',
                                    text: 'No se pudo insertar el registro.',
                                    confirmButtonColor: '#059669',
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                                break;

                            default:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error desconocido',
                                    text: 'El servidor respondió: ' + text,
                                    confirmButtonColor: '#059669',
                                    customClass: {
                                        popup: 'custom-swal-popup'
                                    }
                                });
                                break;
                        }

                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo contactar con el servidor.',
                            confirmButtonColor: '#059669',
                            customClass: {
                                popup: 'custom-swal-popup'
                            }
                        });
                    });
            });

            // Activa el select de curso
            setTimeout(() => {
                const rolSelect = document.getElementById('rol');
                const cursoWrapper = document.getElementById('curso-wrapper');

                rolSelect.addEventListener('change', () => {
                    cursoWrapper.style.display = (rolSelect.value === 'aprendiz') ? 'block' : 'none';
                });
            }, 80);
        });
    </script>
</body>

</html>