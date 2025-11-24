document.querySelector('#btnAgregarAprendiz').addEventListener('click', function () {
    let form = document.querySelector('#formInsertAprendiz');
    let formData = new FormData(form);

    fetch('../controller/aprendices/insertarAprendiz.php', {
        method: 'POST',
        body: formData,
    })
        .then((res) => res.text())
        .then((res) => {
            res = res.trim(); // eliminar espacios
            if (res === 'ok') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Aprendiz registrado correctamente',
                    confirmButtonText: 'OK',
                }).then(() => {
                    location.reload(); // recarga la página para actualizar la tabla
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res,
                    confirmButtonText: 'OK',
                });
            }
        })
        .catch((err) => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error en la solicitud',
                confirmButtonText: 'OK',
            });
        });
});
