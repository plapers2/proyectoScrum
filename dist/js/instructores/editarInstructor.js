function editarInstructor(btn) {
  const id = btn.dataset.id;
  const nombre = btn.dataset.nombre;
  const apellido = btn.dataset.apellido;
  const correo = btn.dataset.correo;

  Swal.fire({
    title:
      '<h3 class="fw-bold mb-3 text-primary">Editar información del Instructor</h3>',
    html: `
      <form id="frm_editar_instructor" class="text-start mt-3">
        <input type="hidden" name="id_instructor" value="${id}">

        <div class="mb-3">
          <label for="nombre_instructor" class="form-label fw-semibold">Nombre</label>
          <input name="nombre_instructor" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre_instructor" placeholder="Ingrese el nombre" required value="${nombre}">
        </div>

        <div class="mb-3">
          <label for="apellido_instructor" class="form-label fw-semibold">Apellido</label>
          <input name="apellido_instructor" type="text" class="form-control form-control-lg shadow-sm"
                 id="apellido_instructor" placeholder="Ingrese el apellido" required value="${apellido}">
        </div>

        <div class="mb-3">
          <label for="correo_instructor" class="form-label fw-semibold">Correo electrónico</label>
          <input name="correo_instructor" type="email" class="form-control form-control-lg shadow-sm"
                 id="correo_instructor" placeholder="ejemplo@correo.com" required value="${correo}">
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm"
          style="border-radius:10px; background:#3b82f6; border:none;">
          Guardar cambios
        </button>
      </form>
    `,
    showConfirmButton: false,
    width: 600,
    background: "#fdfdfd",
    customClass: {
      popup: "shadow-lg rounded-4 border-0 p-4",
    },
    didOpen: () => {
      const form = document.querySelector("#frm_editar_instructor");

      form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const nombre = form.nombre_instructor.value.trim();
        const apellido = form.apellido_instructor.value.trim();
        const email = form.correo_instructor.value.trim();

        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!regex.test(nombre)) {
          Swal.showValidationMessage("El nombre debe ser válido.");
          return;
        }

        if (!regex.test(apellido)) {
          Swal.showValidationMessage("El apellido debe ser válido.");
          return;
        }

        if (!email || email.includes("'")) {
          Swal.showValidationMessage(
            "El correo debe ser válido y no se permite comilla simple por cuestiones de seguridad."
          );
          return;
        }

        // Si todo es válido
        const formData = new FormData(form);

        Swal.fire({
          title: "Editando instructor...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        try {
          const respuesta = await fetch(
            "../controller/instructores/editarInstructor.php",
            {
              method: "POST",
              body: formData,
            }
          );

          const res = await respuesta.text();
          console.log("Respuesta del servidor:", res);

          if (res.trim() === "ok") {
            Swal.fire({
              title: "Actualización exitosa",
              text: "El usuario ha sido modificado correctamente.",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => location.reload());
          } else if (
            res.includes("Esté correo ya está registrado en la base de datos!")
          ) {
            Swal.fire("Fallo", res, "question");
          } else {
            Swal.fire(
              "Error",
              res || "No se pudo actualizar el usuario.",
              "error"
            );
          }
        } catch (error) {
          console.error(error);
          Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
        }
      });
    },
  });
}
