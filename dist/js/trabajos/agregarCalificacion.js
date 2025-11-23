function agregarCalificacion(elemento) {
    const id_trabajo = elemento.dataset.idTrabajo;

    Swal.fire({
        title: 'Agregar Calificación',
        html: `
            <div class="text-start">
                <label class="fw-bold mb-1">
                    <i class="bi bi-check-circle text-success"></i> Calificación (A o D)
                </label>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-award"></i>
                    </span>
                    <input id="calificacion_trabajo" name="calificacion_trabajo" 
                           class="form-control" 
                           maxlength="1" 
                           placeholder="A o D">
                </div>
                <label class="fw-bold mb-1">
                    <i class="bi bi-chat-text text-primary"></i> Comentario
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-pencil-square"></i>
                    </span>
                    <textarea id="comentario_trabajo" name="comentario_trabajo"
                              class="form-control" 
                              placeholder="Escribe un comentario..."
                              rows="3"></textarea>
                </div>
            </div>
        `,
        confirmButtonText: 'Guardar',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const calificacion_trabajo = document.querySelector('#calificacion_trabajo').value.toUpperCase().trim();
            const comentario_trabajo = document.querySelector('#comentario_trabajo').value.trim();

            if (calificacion_trabajo !== 'A' && calificacion_trabajo !== 'D') {
                Swal.showValidationMessage('La calificación debe ser A o D');
                return false;
            }

            if (comentario_trabajo.length === 0) {
                Swal.showValidationMessage('El comentario no puede estar vacío');
                return false;
            }

            return {
                id_trabajo,
                calificacion_trabajo,
                comentario_trabajo,
            };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            enviarCalificacion(result.value);
        }
    });
}

async function enviarCalificacion(data) {
    try {
        // Crear FormData en lugar de JSON
        const form_data = new FormData();
        form_data.append('id_trabajo', data.id_trabajo);
        form_data.append('calificacion_trabajo', data.calificacion_trabajo);
        form_data.append('comentario_trabajo', data.comentario_trabajo);

        const respuesta = await fetch('../controller/trabajos/agregarCalificacion.php', {
            method: 'POST',
            body: form_data,
        });

        const resultado = await respuesta.text();

        if (resultado === 'ok') {
            Swal.fire({
                icon: 'success',
                title: 'Calificación guardada',
                text: 'La calificación fue registrada correctamente.',
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resultado || 'No se pudo registrar la calificación.',
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de servidor',
            text: 'No fue posible conectar con el servidor.',
        });
    }
}
