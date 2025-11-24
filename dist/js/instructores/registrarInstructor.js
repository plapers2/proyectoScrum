document.querySelector('#btn_registro_instructor').addEventListener('click', (e) => {
    Swal.fire({
        title: 'Registrar nuevo instructor',
        html: `
      <form id="frm_registro_instructor" class="needs-validation" novalidate>
        <div class="mb-4">
          <input name="nombre_instructor" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre_instructor" placeholder="Ingrese el nombre" required">
        </div>

        <div class="mb-4">
          <input name="apellido_instructor" type="text" class="form-control form-control-lg shadow-sm"
                 id="apellido_instructor" placeholder="Ingrese el apellido" required">
        </div>

        <div class="mb-4">
          <input name="correo_instructor" type="email" class="form-control form-control-lg shadow-sm"
                 id="correo_instructor" placeholder="ejemplo@correo.com" required">
        </div>

        <div class="mb-4">
          <input name="pass_instructor" type="password" class="form-control form-control-lg shadow-sm"
                 id="pass_instructor" placeholder="Ingrese la contraseña" required">
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Guardar instructor</button>
      </form>
    `,
        showConfirmButton: false,
        didOpen: () => {
            document.querySelector('#frm_registro_instructor').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const nombre = document.querySelector('#nombre_instructor').value.trim();
                const apellido = document.querySelector('#apellido_instructor').value.trim();

                const email = document.querySelector('#correo_instructor').value.trim();

                const contrasena = document.querySelector('#pass_instructor').value.trim();

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
                    Swal.fire(
                        'Campo inválido',
                        'El correo debe ser válido y no debe de contener comilla simple por cuestiones de seguridad.',
                        'warning',
                    );
                    return;
                }

                if (contrasena.length < 4) {
                    Swal.showValidationMessage('La contrasña debe de tener un minimo de 4 caracteres.');
                    return;
                }

                Swal.fire({
                    title: 'Registrando instructor...',
                    text: 'Por favor espere un momento.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });

                registroUsuario(); //* Hoisting
                async function registroUsuario() {
                    const respuesta = await fetch('../controller/instructores/agregarInstructor.php', {
                        method: 'POST',
                        body: formData,
                    });
                    const res = await respuesta.text();
                    console.log('Respuesta del servidor:', res);
                    if (res.trim() === 'ok') {
                        Swal.fire('Éxito', 'Instructor agregado correctamente', 'success').then(() => location.reload());
                    } else if (
                        res.includes('Esté correo del instructor ya existe en la base de datos. Por favor ingresa con otro')
                    ) {
                        Swal.fire('Fallo', res, 'question');
                    } else {
                        Swal.fire('Error', res, 'error');
                    }
                }
            });
        },
    });
});
