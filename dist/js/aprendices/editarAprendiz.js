function editarAprendiz(btn) {
    const id = btn.dataset.id;
    const nombre = btn.dataset.nombre;
    const apellido = btn.dataset.apellido;
    const correo = btn.dataset.correo;
    const cursoId = btn.dataset.cursoid;

    // Obtener los cursos desde el botón principal de agregar
    const btnAgregar = document.querySelector('#btnAgregarAprendiz');
    const cursosData = btnAgregar.getAttribute('data-cursos');
    const cursos = JSON.parse(cursosData);

    Swal.fire({
        title: '<h3 class="fw-bold mb-3 text-primary">Editar información del Aprendiz</h3>',
        html: `
      <form id="frm_editar_aprendiz" class="text-start mt-3">
        <input type="hidden" name="id_aprendiz" value="${id}">

        <div class="mb-3">
          <label for="nombre_aprendiz" class="form-label fw-semibold">Nombre</label>
          <input name="nombre_aprendiz" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre_aprendiz" placeholder="Ingrese el nombre" required value="${nombre}">
        </div>

        <div class="mb-3">
          <label for="apellido_aprendiz" class="form-label fw-semibold">Apellido</label>
          <input name="apellido_aprendiz" type="text" class="form-control form-control-lg shadow-sm"
                 id="apellido_aprendiz" placeholder="Ingrese el apellido" required value="${apellido}">
        </div>

        <div class="mb-3">
          <label for="correo_aprendiz" class="form-label fw-semibold">Correo electrónico</label>
          <input name="correo_aprendiz" type="email" class="form-control form-control-lg shadow-sm"
                 id="correo_aprendiz" placeholder="ejemplo@correo.com" required value="${correo}">
        </div>

        <div class="mb-3">
          <label for="cursos_id_curso" class="form-label fw-semibold">Curso</label>
          <select name="cursos_id_curso" class="form-select form-select-lg shadow-sm" 
                  id="cursos_id_curso" required>
            <option value="" disabled>Seleccione un curso</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm"
          style="border-radius:10px; background:#3b82f6; border:none;">
          Guardar cambios
        </button>
      </form>
    `,
        showConfirmButton: false,
        width: 600,
        background: '#fdfdfd',
        customClass: {
            popup: 'shadow-lg rounded-4 border-0 p-4',
        },
        didOpen: () => {
            // Cargar los cursos en el select
            const selectCursos = document.querySelector('#cursos_id_curso');

            cursos.forEach((curso) => {
                const option = document.createElement('option');
                option.value = curso.id_curso;
                option.textContent = curso.nombre_curso;
                // Marcar el curso actual como seleccionado
                if (curso.id_curso == cursoId) {
                    option.selected = true;
                }
                selectCursos.appendChild(option);
            });

            const form = document.querySelector('#frm_editar_aprendiz');

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const nombre = form.nombre_aprendiz.value.trim();
                const apellido = form.apellido_aprendiz.value.trim();
                const email = form.correo_aprendiz.value.trim();
                const curso = form.cursos_id_curso.value;

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
                    Swal.showValidationMessage(
                        'El correo debe ser válido y no se permite comilla simple por cuestiones de seguridad.',
                    );
                    return;
                }

                if (!curso) {
                    Swal.showValidationMessage('Debe seleccionar un curso.');
                    return;
                }

                // Si todo es válido
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Editando aprendiz...',
                    text: 'Por favor espere un momento.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });

                try {
                    const respuesta = await fetch('../controller/aprendices/editarAprendiz.php', {
                        method: 'POST',
                        body: formData,
                    });

                    const res = await respuesta.text();
                    console.log('Respuesta del servidor:', res);

                    if (res.trim() === 'ok') {
                        Swal.fire({
                            title: 'Actualización exitosa',
                            text: 'El aprendiz ha sido modificado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                        }).then(() => location.reload());
                    } else if (res.includes('ya está registrado en la base de datos')) {
                        Swal.fire('Fallo', res, 'question');
                    } else if (res.includes('Ya existe un aprendiz con esté correo. Por favor ingresa otro')) {
                        Swal.fire('Fallo', res, 'question');
                    } else {
                        Swal.fire('Error', res || 'No se pudo actualizar el aprendiz.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                }
            });
        },
    });
}
