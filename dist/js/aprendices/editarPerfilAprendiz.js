function editarPerfil(btn) {
  const id = btn.dataset.id;
  const nombre = btn.dataset.nombre;
  const apellido = btn.dataset.apellido;
  const correo = btn.dataset.correo;
  Swal.fire({
    title: "<i class='fa-solid fa-user-pen'></i> Actualizar perfil",
    html: `
    <div class="mb-4">
          <input name="nombre_aprendiz" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre_aprendiz" placeholder="Ingrese su nombre" required value="${nombre}">
        </div>
        <div class="mb-4">
          <input name="apellido_aprendiz" type="text" class="form-control form-control-lg shadow-sm"
                 id="apellido_aprendiz" placeholder="Ingrese su apellido" required value="${apellido}">
        </div>
        <div class="mb-4">
          <input name="correo_aprendiz" type="email" class="form-control form-control-lg shadow-sm"
                 id="correo_aprendiz" placeholder="ejemplo@correo.com" required value="${correo}">
        </div>
      <hr>
      <div class="mb-4">
      <input id="pass1" name="pass1" type="password" class="form-control form-control-lg shadow-sm" 
      placeholder="Nueva contraseña (opcional)">
      </div>
      <div class="mb-4">
      <input id="pass2" name="pass2" type="password" class="form-control form-control-lg shadow-sm" 
      placeholder="Confirmar contraseña">
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: "Guardar cambios",
    cancelButtonText: "Cancelar",
    preConfirm: () => {
      const nombre = document.querySelector("#nombre_aprendiz").value.trim();
      const apellido = document.querySelector("#apellido_aprendiz").value.trim();
      const email = document.querySelector("#correo_aprendiz").value.trim();
      const pass1 = document.querySelector("#pass1").value;
      const pass2 = document.querySelector("#pass2").value;

      if (!nombre || !apellido || !email) {
        Swal.showValidationMessage(
          "Por favor completa todos los campos obligatorios"
        );
        return false;
      }

      const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
      if (!regex.test(nombre) || !regex.test(apellido) || email.includes("'")) {
        Swal.showValidationMessage(
          "Por favor, ingrese campos válidos para poder actualizar el perfil."
        );
        return false;
      }

      if (pass1 && pass1 !== pass2) {
        Swal.showValidationMessage("Las contraseñas no coinciden");
        return false;
      }

      return { nombre, apellido, email, pass1 };
    },
  }).then(async (result) => {
    if (result.isConfirmed) {
      const datos = new FormData();
      datos.append("id_aprendiz", id);
      datos.append("nombre_aprendiz", result.value.nombre);
      datos.append("apellido_aprendiz", result.value.apellido);
      datos.append("correo_aprendiz", result.value.email);
      datos.append("pass_aprendiz", result.value.pass1);

      Swal.fire({
        title: "Actualizando perfil...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "../controller/aprendices/editarPerfilAprendiz.php",
        {
          method: "POST",
          body: datos,
        }
      );
      const res = await respuesta.text();
      console.log(res);
      if (res.trim() === "ok") {
        Swal.fire({
          icon: "success",
          title: "Perfil actualizado",
          text: "Los cambios se han guardado correctamente.",
          timer: 2000,
          showConfirmButton: false,
        }).then(() => location.reload());
      }
    }
  });
}
