function eliminarInstructor(btn) {
  const id = btn.dataset.id;
  const nombre = btn.dataset.nombre;
  Swal.fire({
    title: `Estás seguro de inactivar el instructor ${nombre}?`,
    text: "Esta acción inactiva el usuario del sistema.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    if (result.isConfirmed) {
      const fd = new FormData();
      fd.append("id_instructor", id);

      Swal.fire({
        title: "Eliminando usuario...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "../controller/instructores/eliminarInstructor.php",
        {
          method: "POST",
          body: fd,
        }
      );
      const res = await respuesta.text();
      if (res.trim() === "ok") {
        Swal.fire(
          "Eliminado",
          "Instructor eliminado correctamente.",
          "success"
        ).then(() => location.reload());
      } else if (
        res.trim() ==
        `No se puede inactivar el instructor ${nombre} ya que está asociado a una reserva`
      ) {
        Swal.fire("Fallo", res, "question");
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
