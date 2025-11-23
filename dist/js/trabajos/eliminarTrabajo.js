function eliminarTrabajo(btn) {
    const id_trabajo = btn.dataset.id;
    Swal.fire({
        title: '¿Eliminar trabajo?',
        text: 'Esta acción inactiva el usuario del sistema.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(async (result) => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('id_trabajo', id_trabajo);

            Swal.fire({
                title: 'Eliminando trabajo...',
                text: 'Por favor espere un momento.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            const respuesta = await fetch('../controller/trabajos/eliminarTrabajo.php', {
                method: 'POST',
                body: fd,
            });
            const res = await respuesta.text();
            if (res.trim() === 'ok') {
                Swal.fire('Eliminado', 'Trabajo eliminado correctamente.', 'success').then(() => location.reload());
            } else if (res.trim() == "No se puede inactivar el trabajo ya que está asociado a una reserva") {
                Swal.fire('Fallo', res, 'question');
            } else {
                Swal.fire('Error', res, 'error');
            }
        }
    });
}
