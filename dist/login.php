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
    <title>Login - Proyecto Scrum ADSO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #059669 0%, #047857 100%);
            --secondary-gradient: linear-gradient(135deg, #10b981 0%, #065f46 100%);
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #layoutAuthentication {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        #layoutAuthentication_content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            border: none;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .card-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-register {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 10px;
            padding: 0.5rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.6);
            color: white;
        }

        .card-footer {
            background: rgba(248, 249, 250, 0.5);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        footer {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            margin-top: auto;
        }

        footer .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        footer .btn-link {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer .btn-link:hover {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: underline;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            z-index: 10;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.6s ease;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }
            
            .card-header {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php if (!empty($_GET['error']) && isset($_GET['error']) && $error == true) { ?>
        <button class="visually-hidden" id="alertasErrores" onclick="sweetAlertasError('<?php echo $message ?>', '<?php echo $title ?>')"></button>
    <?php } ?>
    
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main class="w-100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-7 col-sm-9">
                            <div class="card login-card">
                                <div class="card-header text-center">
                                    <i class="fas fa-book-reader"></i>
                                    <h3 class="font-weight-light">Biblioteca ADSO</h3>
                                    <p class="mb-0 small">Inicia sesión para continuar</p>
                                </div>
                                <div class="card-body">
                                    <form action="../controller/controllerLogin.php" method="post">
                                        <div class="form-floating input-icon">
                                            <input class="form-control" id="usuarioLogin" type="text" placeholder="Usuario" name="usuarioLogin" required />
                                            <label for="usuarioLogin"><i class="fas fa-user me-2"></i>Usuario</label>
                                        </div>
                                        <div class="form-floating input-icon">
                                            <input class="form-control" id="passLogin" type="password" placeholder="Contraseña" name="passLogin" required />
                                            <label for="passLogin"><i class="fas fa-lock me-2"></i>Contraseña</label>
                                        </div>
                                        <div class="form-floating">
                                            <select class="form-select" id="tipoUsuarioLogin" name="tipoUsuarioLogin" required>
                                                <option selected value="nada">Selecciona tu rol</option>
                                                <option value="Administrador">Administrador</option>
                                                <option value="Instructor">Instructor</option>
                                                <option value="Aprendiz">Aprendiz</option>
                                            </select>
                                            <label for="tipoUsuarioLogin"><i class="fas fa-user-tag me-2"></i>Rol</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary form-control">
                                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small mb-2">¿No tienes una cuenta?</div>
                                    <button id="btnRegistro" class="btn btn-register">
                                        <i class="fas fa-user-plus me-2"></i>Registrarse
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small flex-wrap">
                        <div class="text-muted mb-2 mb-md-0">
                            <i class="far fa-copyright me-1"></i>ADSO 3064749 / 2025
                        </div>
                        <div>
                            <button class="btn btn-link" id="politicaPrivacidad">Política &amp; Privacidad</button>
                            <span class="text-white-50">&middot;</span>
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