function eliminarTrabajo(btn) {
    const id = btn.dataset.id;

    Swal.fire({
        title: "¿Eliminar trabajo?",
        text: "Esto no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar"
    }).then(res => {
        if (res.isConfirmed) {
            window.location.href = `../controller/trabajos/eliminarTrabajo.php?id=${id}`;
        }
    });
}

function editarTrabajo(btn) {
    const id = btn.dataset.id;
    window.location.href = `editarTrabajo.php?id=${id}`;
}
