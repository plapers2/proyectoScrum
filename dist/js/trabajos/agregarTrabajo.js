document.querySelector('#btn_registro_trabajo').addEventListener('click', (e) => {
    const btn = e.currentTarget;

    // Obtener los aprendices del atributo data-id
    const aprendicesJSON = btn.getAttribute('data-id');
    const aprendices = JSON.parse(aprendicesJSON);

    // Generar HTML de la lista de aprendices
    let aprendicesHTML = '';

    if (aprendices && aprendices.length > 0) {
        aprendicesHTML = `
            <div class="mb-3">
                <label class="form-label fw-bold">Asignar a aprendices:</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="seleccionar_todos">
                    <label class="form-check-label fw-bold" for="seleccionar_todos">
                        Seleccionar todos
                    </label>
                </div>
                <div id="lista_aprendices" style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                    ${aprendices
                        .map(
                            (aprendiz) => `
                        <div class="form-check">
                            <input class="form-check-input aprendiz-checkbox" type="checkbox" value="${aprendiz.id_aprendiz}" id="aprendiz_${aprendiz.id_aprendiz}">
                            <label class="form-check-label" for="aprendiz_${aprendiz.id_aprendiz}">
                                ${aprendiz.nombre} ${aprendiz.apellido}
                            </label>
                        </div>
                    `,
                        )
                        .join('')}
                </div>
            </div>
        `;
    } else {
        aprendicesHTML = '<p class="text-warning">No hay aprendices disponibles para asignar.</p>';
    }

    Swal.fire({
        title: 'Registrar nuevo trabajo',
        html: `
            <form id="frm_registro_trabajo" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="ruta_trabajo" class="form-label">Archivo del trabajo</label>
                    <input type="file" class="form-control" id="ruta_trabajo" name="ruta_trabajo" required>
                    <div class="invalid-feedback">Por favor selecciona un archivo.</div>
                </div>

                <div class="mb-3">
                    <label for="fecha_limite_trabajo" class="form-label">Fecha límite</label>
                    <input type="date" class="form-control" id="fecha_limite_trabajo" name="fecha_limite_trabajo" required>
                    <div class="invalid-feedback">Por favor selecciona una fecha límite.</div>
                </div>

                ${aprendicesHTML}

                <button type="submit" class="btn btn-primary w-100 py-2">Guardar trabajo</button>
            </form>
        `,
        width: '600px',
        showConfirmButton: false,
        didOpen: () => {
            // Funcionalidad para seleccionar todos los aprendices
            const seleccionarTodos = document.querySelector('#seleccionar_todos');
            const checkboxesAprendices = document.querySelectorAll('.aprendiz-checkbox');

            if (seleccionarTodos) {
                seleccionarTodos.addEventListener('change', function () {
                    checkboxesAprendices.forEach((checkbox) => {
                        checkbox.checked = this.checked;
                    });
                });

                // Si se desmarca algún checkbox individual, desmarcar "Seleccionar todos"
                checkboxesAprendices.forEach((checkbox) => {
                    checkbox.addEventListener('change', function () {
                        if (!this.checked) {
                            seleccionarTodos.checked = false;
                        } else {
                            // Si todos están marcados, marcar "Seleccionar todos"
                            const todosSeleccionados = Array.from(checkboxesAprendices).every((cb) => cb.checked);
                            seleccionarTodos.checked = todosSeleccionados;
                        }
                    });
                });
            }

            // Establecer fecha mínima como hoy
            const fechaLimite = document.querySelector('#fecha_limite_trabajo');
            const hoy = new Date().toISOString().split('T')[0];
            fechaLimite.setAttribute('min', hoy);

            // Manejar el envío del formulario
            document.querySelector('#frm_registro_trabajo').addEventListener('submit', function (e) {
                e.preventDefault();

                const archivo = document.querySelector('#ruta_trabajo').files[0];
                const fechaLimite = document.querySelector('#fecha_limite_trabajo').value.trim();
                const aprendicesSeleccionados = Array.from(document.querySelectorAll('.aprendiz-checkbox:checked'));

                // Validaciones
                if (!archivo) {
                    Swal.showValidationMessage('Por favor selecciona un archivo.');
                    return;
                }

                if (!fechaLimite) {
                    Swal.showValidationMessage('Por favor selecciona una fecha límite.');
                    return;
                }

                const fechaSeleccionada = new Date(fechaLimite);
                const fechaActual = new Date();
                fechaActual.setHours(0, 0, 0, 0);

                if (fechaSeleccionada < fechaActual) {
                    Swal.showValidationMessage('La fecha límite no puede ser anterior a hoy.');
                    return;
                }

                if (aprendicesSeleccionados.length === 0) {
                    Swal.showValidationMessage('Por favor selecciona al menos un aprendiz.');
                    return;
                }

                // Crear FormData SOLO con archivo y fecha
                const formData = new FormData();
                formData.append('ruta_trabajo', archivo);
                formData.append('fecha_limite_trabajo', fechaLimite);

                // Agregar los IDs de los aprendices seleccionados
                aprendicesSeleccionados.forEach((checkbox) => {
                    formData.append('aprendices[]', checkbox.value);
                });

                Swal.fire({
                    title: 'Registrando trabajo...',
                    text: 'Por favor espera un momento.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => Swal.showLoading(),
                });

                registroTrabajo(); //* Hoisting

                async function registroTrabajo() {
                    try {
                        const respuesta = await fetch('../controller/trabajos/agregarTrabajo.php', {
                            method: 'POST',
                            body: formData,
                        });

                        if (!respuesta.ok) {
                            throw new Error(`HTTP error! status: ${respuesta.status}`);
                        }

                        const res = await respuesta.text();
                        console.log('Respuesta del servidor:', res);

                        if (res.trim() === 'ok') {
                            Swal.fire('Éxito', 'Trabajo registrado correctamente', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', res || 'Ocurrió un error al registrar el trabajo', 'error');
                        }
                    } catch (error) {
                        console.error('Error en la solicitud:', error);
                        Swal.fire('Error de conexión', 'Ocurrió un error al intentar registrar el trabajo.', 'error');
                    }
                }
            });
        },
    });
});
