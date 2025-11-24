document.querySelector('#btnAgregarAprendiz').addEventListener('click', function (e) {
    // Obtener los cursos desde el atributo data-cursos del botón
    const cursosData = this.getAttribute('data-cursos');
    const cursos = JSON.parse(cursosData);

    Swal.fire({
        title: 'Registrar nuevo aprendiz',
        html: `
            <form id="formInsertAprendiz" class="needs-validation" novalidate>
                <div class="mb-4">
                    <input name="nombre_aprendiz" type="text" class="form-control form-control-lg shadow-sm"
                           id="nombre_aprendiz" placeholder="Ingrese el nombre" required>
                </div>

                <div class="mb-4">
                    <input name="apellido_aprendiz" type="text" class="form-control form-control-lg shadow-sm"
                           id="apellido_aprendiz" placeholder="Ingrese el apellido" required>
                </div>

                <div class="mb-4">
                    <input name="correo_aprendiz" type="email" class="form-control form-control-lg shadow-sm"
                           id="correo_aprendiz" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="mb-4">
                    <input name="pass_aprendiz" type="password" class="form-control form-control-lg shadow-sm"
                           id="pass_aprendiz" placeholder="Ingrese la contraseña" required>
                </div>

                <div class="mb-4">
                    <select name="cursos_id_curso" class="form-select form-select-lg shadow-sm" 
                            id="cursos_id_curso" required>
                        <option value="" disabled selected>Seleccione un curso</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Guardar aprendiz</button>
            </form>
        `,
        showConfirmButton: false,
        didOpen: () => {
            // Cargar los cursos en el select
            const selectCursos = document.querySelector('#cursos_id_curso');

            cursos.forEach((curso) => {
                const option = document.createElement('option');
                option.value = curso.id_curso;
                option.textContent = curso.nombre_curso;
                selectCursos.appendChild(option);
            });

            // Event listener del formulario
            document.querySelector('#formInsertAprendiz').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                const nombre = document.querySelector('#nombre_aprendiz').value.trim();
                const apellido = document.querySelector('#apellido_aprendiz').value.trim();
                const email = document.querySelector('#correo_aprendiz').value.trim();
                const pass = document.querySelector('#pass_aprendiz').value.trim();
                const curso = document.querySelector('#cursos_id_curso').value;

                // Validaciones
                const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

                if (!regex.test(nombre)) {
                    Swal.showValidationMessage('El nombre debe ser válido.');
                    return;
                }

                if (!regex.test(apellido)) {
                    Swal.showValidationMessage('El apellido debe ser válido.');
                    return;
                }

                if (!email || email.includes("'")) {
                    Swal.fire(
                        'Campo inválido',
                        'El correo debe ser válido y no debe contener comilla simple por cuestiones de seguridad.',
                        'warning',
                    );
                    return;
                }

                if (pass.length < 4) {
                    Swal.showValidationMessage('La contraseña debe tener un mínimo de 4 caracteres.');
                    return;
                }

                if (!curso) {
                    Swal.showValidationMessage('Debe seleccionar un curso.');
                    return;
                }

                // Mostrar loading
                Swal.fire({
                    title: 'Registrando aprendiz...',
                    text: 'Por favor espere un momento.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });

                // Función para registrar
                registrarAprendiz();

                async function registrarAprendiz() {
                    try {
                        const respuesta = await fetch('../controller/aprendices/insertarAprendiz.php', {
                            method: 'POST',
                            body: formData,
                        });
                        const res = await respuesta.text();
                        console.log('Respuesta del servidor:', res);

                        if (res.trim() === 'ok') {
                            Swal.fire('Éxito', 'Aprendiz registrado correctamente', 'success').then(() => location.reload());
                        } else if (res.includes('ya está registrado')) {
                            Swal.fire('Fallo', res, 'question');
                        } else if (res.includes('Esté correo ya está registrado en la base de datos. Ingresa con otro')) {
                            Swal.fire('Fallo', res, 'question');
                        } else {
                            Swal.fire('Error', res, 'error');
                        }
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error en la solicitud',
                            confirmButtonText: 'OK',
                        });
                    }
                }
            });
        },
    });
});
