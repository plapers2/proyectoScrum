function eliminarAprendiz(btn) {
    let id = btn.getAttribute('data-id');

    Swal.fire({
        title: '¿Eliminar aprendiz?',
        text: 'Esta accion no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('id_aprendiz', id);

            fetch('../controller/aprendices/eliminarAprendizes.php', {
                method: 'POST',
                body: formData,
            })
                .then((r) => r.text())
                .then((res) => {
                    if (res.trim() === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'El aprendiz ha sido eliminado',
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar',
                        });
                    }
                });
        }
    });
}
