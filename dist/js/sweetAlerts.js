'use strict';

// #region //* Eventos Botones
//! /////////////////////////////////////////////////////////
//! Eventos de Botones
//! /////////////////////////////////////////////////////////

// #region //* Usuario Insertar
//TODO Inicio Usuario Insertar
//? Se capturan todos los botones en un arreglo (creado por defecto)
let usuarioInsertar = document.querySelectorAll('#usuarioInsertar');
usuarioInsertar.forEach((element) => {
    //? Se añade el evento click a cada boton capturado
    element.addEventListener('click', () => {
        //? Se llama el SweetAlert correspondiente
        sweetUsuarioInsertar();
    });
});
//TODO Fin Usuario Insertar
// #endregion

// #region //* Configuracion Perfil

//TODO Inicio Configuracion Perfil
//? Se capturan todos los botones en un arreglo (creado por defecto)
let configuracionPerfil = document.querySelectorAll('#configuracionPerfil');
configuracionPerfil.forEach((element) => {
    //? Se añade el evento click a cada boton capturado
    element.addEventListener('click', () => {
        //? Se llama el SweetAlert correspondiente
        sweetConfiguracionPerfil(element.name);
    });
});
//TODO Fin Configuracion Perfil
// #endregion

// #region //* Politica & Privacidad
//TODO Inicio Politica & Privacidad
//? Se capturan todos los botones en un arreglo (creado por defecto)
let politicaPrivacidad = document.querySelectorAll('#politicaPrivacidad');
politicaPrivacidad.forEach((element) => {
    //? Se añade el evento click a cada boton capturado
    element.addEventListener('click', () => {
        //? Se llama el SweetAlert correspondiente
        sweetPoliticaPrivacidad();
    });
});
//TODO Fin Politica & Privacidad
// #endregion

// #region //* Terminos & Condiciones
//TODO Inicio Terminos & Condiciones
//? Se capturan todos los botones en un arreglo (creado por defecto)
let terminosCondiciones = document.querySelectorAll('#terminosCondiciones');
terminosCondiciones.forEach((element) => {
    //? Se añade el evento click a cada boton capturado
    element.addEventListener('click', () => {
        //? Se llama el SweetAlert correspondiente
        sweetTerminosCondiciones();
    });
});
//TODO Fin Terminos & Condiciones
// #endregion

// #region //* Reserva Libros Insertar
//TODO Inicio Reserva Libros Insertar
const reservaInsertar = document.querySelectorAll('#reservaLibros');
reservaInsertar.forEach((element) => {
    element.addEventListener('click', () => {
        sweetReservaLibros(element.name);
    });
});
//TODO Fin Reserva Libros Insertar
// #endregion

// #region //* Generar Archivos
//TODO Inicio Generar Archivos
const generarArchivos = document.querySelectorAll('#generarArchivos');
generarArchivos.forEach((element) => {
    element.addEventListener('click', () => {
        sweetGenerarArchivos();
    });
});
//TODO Fin Generar Archivos
// #endregion

// #region //* Agregar Libro
const btnAgregarLibro = document.querySelectorAll('#agregarLibro');
btnAgregarLibro.forEach((element) => {
    element.addEventListener('click', () => {
        sweetLibroInsertar();
    });
});
// #endregion

// #region //* Prestamo registro
//TODO Inicio Prestamo Registro
const registrarPrestamo = document.querySelectorAll('#registrarPrestamo');
registrarPrestamo.forEach((element) => {
    element.addEventListener('click', () => {
        sweetRegistrarPrestamo();
    });
});
//TODO Fin Prestamo Registro
// #endregion

// #region //* Lanzar sweet errores
//TODO Inicio Lanzar sweet errores
window.onload = function () {
    if (document.querySelector("#alertasErrores")) {
        const a = document.querySelector("#alertasErrores").click();
    }
};
//TODO Fin Lanzar sweet errores
// #endregion

//! /////////////////////////////////////////////////////////
//! FIN Eventos de Botones
//! /////////////////////////////////////////////////////////

// #endregion

// #region //* Funciones Generales
//! /////////////////////////////////////////////////////////
//! Funciones Generales
//! /////////////////////////////////////////////////////////

// #region //* Verificar Email
//TODO Inicio Funcion verificarEmail
async function verificarEmail(email) {
    try {
        //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
        const formData = new FormData();
        formData.append('email', email);
        //? Solicitud de datos a controller
        const response = await fetch('../controller/controllerVerifyEmail.php', {
            method: 'POST',
            body: formData,
        });
        //? Conversion a JSON valido
        const resultadoEmailVerify = await response.json();
        //? Retorno de datos
        return resultadoEmailVerify;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Funcion verificarEmail
// #endregion

// #region //* Verificar ISBN
//TODO Funcion verificarISBN INICIO
async function verificarISBN(isbn) {
    try {
        if (!isbn || isbn.trim() === '') {
            return true; //revisar si se debe dejar el isbn como opcional.
        }
        //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
        const formData = new FormData();
        formData.append('isbnLibro', isbn);
        //? Solicitud de datos a controller
        let resultadoISBNVerify = await fetch('../controller/controllerVerifyISBN.php', {
            method: 'POST',
            body: formData,
        });
        const datos = resultadoISBNVerify.json();
        return datos;
    } catch (e) {
        console.log(e);
        return false;
    }
}
//TODO Funcion verificarISBN FIN
// #endregion

// #region //* Traer Datos Usuario (por id)
//TODO Inicio Funcion Traer Datos Usuario (por id)
async function traerDatosUsuarioPorID(id, tipoUsuario) {
    try {
        //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
        const formData = new FormData();
        formData.append('id', id);
        //? Solicitud de datos a controller
        const json = await fetch(`../controller/datosGenerales/controllerDatosUsuarioPorID.php`, {
            method: 'POST',
            body: formData,
        });
        //? Conversion a JSON valido
        const datos = await json.json();
        //? Retorno de datos
        return datos;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Funcion Traer Datos Usuario (por id)
// #endregion

// #region //* Traer Datos Tabla Pivote Libro Has Reservas (por id)
//TODO Inicio Traer Datos Tabla Pivote (por id)
async function traerDatosLibrosHasReservasPorID(id) {
    try {
        //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
        const formData = new FormData();
        formData.append('id', id);
        //? Solicitud de datos a controller
        const response = await fetch('../controller/controllerDatosLibroHasReservaPorID.php', {
            method: 'POST',
            body: formData,
        });
        //? Conversion a JSON valido
        const datos = await response.json();
        //? Retorno de datos
        return datos;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Tabla Pivote (por id)
// #endregion

// #region //* Traer Datos Tabla Pivote Libro Has Categorias (por id)
//TODO Inicio Traer Datos Tabla Pivote (por id)
async function traerDatosLibrosHasCategoriaPorID(id) {
    try {
        //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
        const formData = new FormData();
        formData.append('id', id);
        //? Solicitud de datos a controller
        const response = await fetch('../controller/controllerDatosLibroHasCategoriaPorID.php', {
            method: 'POST',
            body: formData,
        });
        //? Conversion a JSON valido
        const datos = await response.json();
        //? Retorno de datos
        return datos;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Tabla Pivote (por id)
// #endregion

// #region //* Traer Datos Libros
//TODO Inicio Traer Datos Libros
async function traerDatosLibros() {
    try {
        //? Solicitud de datos a controller
        const response = await fetch(`../controller/controllerDatosLibros.php`);
        //? Conversion a JSON valido
        const datos = await response.json();
        //? Retorno de datos
        return datos;
    } catch (e) {
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Libros
// #endregion

// #region //* Traer Datos Libro (por id)
//TODO Inicio Traer Datos Libro (por id)
async function traerDatosLibroPorID(id) {
    try {
        //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
        const formData = new FormData();
        formData.append('idLibro', id);
        //? Solicitud de datos a controller
        const response = await fetch(`../controller/controllerLibroObtener.php`, {
            method: 'POST',
            body: formData,
        });
        //? Conversion a JSON valido
        const datos = await response.json();
        //? Retorno de datos
        return datos;
    } catch (e) {
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Libro (por id)
// #endregion

// #region //* Traer Datos Ultima Reserva
//TODO Inicio Traer Datos Ultima Reserva
async function traerDatosUltimaReserva() {
    try {
        //? Solicitud de datos a controller
        const response = await fetch('../controller/controllerDatosUltimaReserva.php');
        //? Conversion a JSON valido
        const datos = await response.json();
        //* console.log(datos);
        //? Retorno de datos (Al usar un "Alias" en la consulta, toca acceder a un tipo de array (estudiar el objeto retornado))
        return datos[0].idReserva;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Ultima Reserva
// #endregion

// #region //* Traer Datos Ultimo LibroHasCategoria
//TODO Inicio Traer Datos Ultima LibroHasCategoria
async function traerDatosUltimoLibro() {
    try {
        //? Solicitud de datos a controller
        const response = await fetch('../controller/controllerDatosUltimoLibro.php');
        //? Conversion a JSON valido
        const datos = await response.json();
        // console.log(datos);
        //? Retorno de datos (Al usar un "Alias" en la consulta, toca acceder a un tipo de array (estudiar el objeto retornado))
        return datos[0].idLibro;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Ultimo LibroHasCategoria
// #endregion

// #region //* Traer Datos Reserva (por id)
//TODO Inicio Traer Datos Reserva (por id)
async function traerDatosReservaPorID(id) {
    try {
        //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
        const formData = new FormData();
        formData.append('idReserva', id);
        //? Solicitud de datos a controller
        const response = await fetch('../controller/controllerDatosReservaPorID.php', {
            method: 'POST',
            body: formData,
        });
        //? Conversion a JSON valido
        const datos = await response.json();
        //? Retorno de datos
        return datos;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Reserva (por id)
// #endregion

// #region //* Traer Datos Categorias
//TODO Inicio Traer Datos Categorias
async function traerDatosCategorias() {
    try {
        //? Solicitud de datos a controller
        const response = await fetch('../controller/controllerDatosCategorias.php', {
            method: 'POST',
        });
        //? Conversion a JSON valido
        // console.log(response);
        const datos = await response.json();
        //? Retorno de datos
        return datos;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Categorias
// #endregion

// #region //* Traer Datos Reservas Aprobadas
//TODO Inicio Traer Datos Reservas Aprobadas
async function traerDatosReservasAprobadas() {
    try {
        const response = await fetch('../controller/controllerDatosReservasAprobadas.php');
        const datos = await response.json();
        return datos;
    } catch (e) {
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Reservas Aprobadas
// #endregion

// #region //* Traer Datos Prestamo (por id)
//TODO Inicio Traer Datos Prestamo (por id)
async function traerDatosPrestamoPorID(idPrestamo) {
    try {
        const formData = new FormData();
        formData.append('idPrestamo', idPrestamo);
        const response = await fetch('../controller/controllerDatosPrestamoPorID.php', {
            method: 'POST',
            body: formData,
        });
        const datos = await response.json();
        return datos;
    } catch (e) {
        console.log(e);
        return false;
    }
}
//TODO Fin Traer Datos Prestamo (por id)
// #endregion

// #region //* Filtar Reservas Modal
//TODO Funcion filtrar reservas en el modal
function filtrarReservasModal() {
    const input = document.getElementById('buscarReservaModal');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('tablaReservasAprobadas');
    const rows = table.getElementsByClassName('reserva-row');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent || row.innerText;

        if (text.toLowerCase().indexOf(filter) > -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}
//TODO Fin filtrar reservas
// #endregion

// #region //* Enviar Email Reserva
//TODO Funcion enviar email de reserva
async function enviarEmailReserva(idReserva, email) {
    try {
        Swal.fire({
            title: 'Enviando correo...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        const response = await $.ajax({
            url: '../controller/controllerEnviarEmailReserva.php',
            type: 'POST',
            data: {
                idReserva: idReserva,
                email: email,
            },
            dataType: 'json',
        });

        if (response.success) {
            Swal.fire({
                title: 'Correo Enviado!',
                text: response.message || 'El correo fue enviado exitosamente',
                icon: 'success',
                confirmButtonColor: '#28a745',
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: response.message || 'No se pudo enviar el correo',
                icon: 'error',
                confirmButtonColor: '#dc3545',
            });
        }
    } catch (e) {
        console.log(e);
        Swal.fire({
            title: 'Error!',
            text: 'Error al procesar la solicitud',
            icon: 'error',
            confirmButtonColor: '#dc3545',
        });
    }
}
//TODO Fin enviar email
// #endregion

//! /////////////////////////////////////////////////////////
//! FIN Funciones Generales
//! /////////////////////////////////////////////////////////
// #endregion

// #region //* Definicion de Funciones createElement
//! /////////////////////////////////////////////////////////
//! Definicion de Funciones createElement
//! /////////////////////////////////////////////////////////

// #region //* Crear Form
//TODO Inicio form
function crearForm() {
    try {
        //? Creacion de elemento Form
        let form = document.createElement('form');
        //? Retorno de elemento
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin form
// #endregion

// #region //* Crear LabelForm
//TODO Inicio label
function crearLabelForm(labelFor, text) {
    try {
        //? Creacion de elemento Label
        let label = document.createElement('label');
        label.classList.add('form-label');
        label.setAttribute('for', labelFor);
        label.textContent = text;
        //? Retorno de elemento
        return label;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin label
// #endregion

// #region //* Crear InputForm
//TODO Inicio Input
function crearInputForm(inputId, type, value) {
    try {
        //? Creacion de elemento Input
        let input = document.createElement('input');
        input.classList.add('form-control');
        input.setAttribute('type', type);
        input.setAttribute('id', inputId);
        input.setAttribute('value', value);
        //? Retorno de elemento
        return input;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Input
// #endregion

// #region //* Crear DivForm
//TODO Inicio Div Form
function crearDivForm() {
    try {
        //? Creacion de elemento DivForm
        let div = document.createElement('div');
        div.classList.add('mb-3');
        //? Retorno de elemento
        return div;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Div Form
// #endregion

// #region //* Crear SelectForm
//TODO Inicio Select Form
function crearSelectForm(selectId) {
    try {
        //? Creacion de elemento Select
        let select = document.createElement('select');
        select.classList.add('form-select');
        select.setAttribute('id', selectId);
        //? Retorno de elemento
        return select;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Select Form
// #endregion

// #region //* Crear OptionForm
//TODO Inicio Option
function crearOptionForm(value, text, selected) {
    try {
        //? Creacion de elemento Option
        let option = document.createElement('option');
        option.setAttribute('value', value);
        option.textContent = text;
        //? True: Se aplica selected al option // False: No se aplica nada
        if (selected == true) {
            option.setAttribute('selected', 'selected');
        }
        //? Retorno de elemento
        return option;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Option
// #endregion

// #region //* Crear Button
//TODO Inicio Button
function crearButton(type, text) {
    try {
        //? Creacion de elemento Button
        let button = document.createElement('button');
        button.classList.add('btn', 'btn-primary');
        button.setAttribute('type', type);
        button.textContent = text;
        //? Retorno de elemento
        return button;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Button
// #endregion

// #region //* Crear Parrafo
//TODO Inicio Button
function crearParrafo(text) {
    try {
        //? Creacion de elemento P (parrafo)
        let p = document.createElement('p');
        p.textContent = text;
        //? Retorno de elemento
        return p;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Button
// #endregion

// #region //* Crear Div Personalizado
//TODO Inicio Div Personalizado
function crearDivPersonalizado(id, ...clase) {
    try {
        //? Se usa parametro tipo rest (...),
        //? basicamente para que ese parametro no tenga limite y se pueda repetir mediante comas ","
        //? en la invocacion de la funcion

        //? Creacion de elemento Div
        const div = document.createElement('div');
        //? ID
        div.setAttribute('id', id);
        //? Se agregan las clases (con parametro tipo rest)
        div.classList.add(...clase);
        //? Retorno de elemento
        return div;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Div Personalizado
// #endregion

// #region //* Crear Lista
//TODO Inicio Crear Lista
function crearLista(tipoLista, id) {
    try {
        //? Creacion de elemento Lista
        const lista = document.createElement(`${tipoLista}`);
        //? ID
        lista.setAttribute('id', id);
        //? Retorno de elemento
        return lista;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Crear Lista
// #endregion

// #region //* Crear Li (lista)
//TODO Inicio Crear Li (lista)
function crearLi(text) {
    try {
        //? Creacion de elemento Lista
        const li = document.createElement('li');
        //? Texto li
        li.textContent = text;
        //? Retorno de elemento
        return li;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Crear Lista
// #endregion

//! /////////////////////////////////////////////////////////
//! FIN Definicion de Funciones createElement
//! /////////////////////////////////////////////////////////

// #endregion

// #region //* Contenidos de HTML para los SweetAlert
//! /////////////////////////////////////////////////////////
//! Contenidos de HTML para los SweetAlert
//! /////////////////////////////////////////////////////////

// #region //* Contenido Usuario Insertar
//TODO Inicio Contenido Usuario Insertar
async function contenidoUsuarioInsertar() {
    try {
        //? Se traen todos los tipos de usuario disponibles
        const jsonTipoUsuario = await fetch('../controller/controllerDatosTipoUsuario.php');
        const datosTipoUsuario = await jsonTipoUsuario.json();
        //? Inicio Formulario
        const form = crearForm();
        //? Nombre
        const nombreDiv = crearDivForm();
        const nombreLabel = crearLabelForm('nombreUsuario', 'Nombre');
        const nombreInput = crearInputForm('nombreUsuario', 'text', '');
        nombreDiv.append(nombreLabel);
        nombreDiv.append(nombreInput);
        //? Apellido
        const apellidoDiv = crearDivForm();
        const apellidoLabel = crearLabelForm('apellidoUsuario', 'Apellido');
        const apellidoInput = crearInputForm('apellidoUsuario', 'text', '');
        apellidoDiv.append(apellidoLabel);
        apellidoDiv.append(apellidoInput);
        //? Email
        const emailDiv = crearDivForm();
        const emailLabel = crearLabelForm('emailUsuario', 'Email');
        const emailInput = crearInputForm('emailUsuario', 'email', '');
        emailDiv.append(emailLabel);
        emailDiv.append(emailInput);
        //? Pass
        const passDiv = crearDivForm();
        const passLabel = crearLabelForm('passUsuario', 'Contraseña');
        const passInput = crearInputForm('passUsuario', 'password', '');
        passDiv.append(passLabel);
        passDiv.append(passInput);
        //? Select
        const selectDiv = crearDivForm();
        const select = crearSelectForm('tipoUsuario');
        const optionSelected = crearOptionForm('', 'Seleccione una opcion', true);
        select.append(optionSelected);
        //? Option
        datosTipoUsuario.forEach((tipoUsuario) => {
            const option = crearOptionForm(tipoUsuario.idTipoUsuario, tipoUsuario.tipoUsuario, false);
            select.append(option);
        });
        selectDiv.append(select);
        //? Asignacion final Form
        form.append(nombreDiv);
        form.append(apellidoDiv);
        form.append(emailDiv);
        form.append(passDiv);
        form.append(selectDiv);
        //? Retorno de HTML
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Usuario Insertar
// #endregion

// #region //* Contenido Usuario Editar
//TODO Inicio Contenido Usuario Editar
async function contenidoUsuarioEditar(id, tipoUsuario) {
    try {
        //? Se traen datos de usuario por ID
        const datosUsuario = await traerDatosUsuarioPorID(id, tipoUsuario);
        //? Se traen todos los tipos de usuario disponibles
        const jsonTipoUsuario = await fetch(`../controller/controllerDatosTipoUsuario.php`);
        const datosTipoUsuario = await jsonTipoUsuario.json();
        //? Inicio Formulario
        const form = crearForm();
        //? Nombre
        const nombreDiv = crearDivForm();
        const nombreLabel = crearLabelForm('nombreUsuario', 'Nombre');
        const nombreInput = crearInputForm('nombreUsuario', 'text', datosUsuario[0].nombreUsuario);
        nombreDiv.append(nombreLabel);
        nombreDiv.append(nombreInput);
        //? Apellido
        const apellidoDiv = crearDivForm();
        const apellidoLabel = crearLabelForm('apellidoUsuario', 'Apellido');
        const apellidoInput = crearInputForm('apellidoUsuario', 'text', datosUsuario[0].apellidoUsuario);
        apellidoDiv.append(apellidoLabel);
        apellidoDiv.append(apellidoInput);
        //? Email
        const emailDiv = crearDivForm();
        const emailLabel = crearLabelForm('emailUsuario', 'Correo');
        const emailInput = crearInputForm('emailUsuario', 'email', datosUsuario[0].emailUsuario);
        emailDiv.append(emailLabel);
        emailDiv.append(emailInput);
        //? Password
        const passDiv = crearDivForm();
        const passLabel = crearLabelForm('passUsuario', 'Contraseña');
        const passInput = crearInputForm('passUsuario', 'password', '');
        passDiv.append(passLabel);
        passDiv.append(passInput);
        //? Tipo Usuario
        const selectDiv = crearDivForm();
        const select = crearSelectForm('tipoUsuario');
        datosTipoUsuario.forEach((tipoUsuario) => {
            if (tipoUsuario.idTipoUsuario == datosUsuario[0].fkTipoUsuario) {
                const optionSelected = crearOptionForm(tipoUsuario.idTipoUsuario, tipoUsuario.tipoUsuario, true);
                select.append(optionSelected);
            } else {
                const option = crearOptionForm(tipoUsuario.idTipoUsuario, tipoUsuario.tipoUsuario, false);
                select.append(option);
            }
        });
        selectDiv.append(select);
        //? Asignacion final Form

        form.append(nombreDiv);
        form.append(apellidoDiv);
        form.append(emailDiv);
        form.append(passDiv);
        form.append(selectDiv);
        //? Retorno de HTML
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Usuario Editar
// #endregion

// #region //* Contenido Usuario Activar
//TODO Inicio Contenido Usuario Activar
async function contenidoUsuarioActivar(id, tipoUsuario) {
    try {
        //? Se traen datos de usuario por ID
        const usuario = await traerDatosUsuarioPorID(id, tipoUsuario);
        //? Inicio Formulario
        const form = crearForm();
        //? Label (texto)
        const labelDiv = crearDivForm();
        const label = crearLabelForm('', `¿Desea activar el usuario ${usuario[0].nombreUsuario} con ID ${id}?`);
        labelDiv.append(label);
        //? Asignacion final Form
        form.append(labelDiv);
        //? Retorno de HTML
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Usuario Activar
// #endregion

// #region //* Contenido Usuario Desctivar
//TODO Inicio Contenido Usuario Desactivar
async function contenidoUsuarioDesctivar(id, tipoUsuario) {
    try {
        //? Se traen datos de usuario por ID
        const usuario = await traerDatosUsuarioPorID(id, tipoUsuario);
        //? Inicio Formulario
        const form = crearForm();
        //? Label (texto)
        const labelDiv = crearDivForm();
        const label = crearLabelForm('', `¿Desea desactivar el usuario ${usuario[0].nombreUsuario} con ID ${id}?`);
        labelDiv.append(label);
        //? Asignacion final Form
        form.append(labelDiv);
        //? Retorno de HTML
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Usuario Desactivar
// #endregion

// #region //* Contenido Configuracion Perfil
//TODO Inicio Contenido Configuracion Perfil
async function contenidoConfiguracionPerfil(id, tipoUsuario) {
    try {
        //? Se traen datos de usuario por ID
        const usuario = await traerDatosUsuarioPorID(id, tipoUsuario);
        //? Inicio Formulario
        const form = crearForm();
        //? Nombre
        const nombreDiv = crearDivForm();
        const nombreLabel = crearLabelForm('nombreUsuarioPerfil', 'Nombre:');
        const nombreInput = crearInputForm('nombreUsuarioPerfil', 'text', usuario[0].nombreUsuario);
        nombreDiv.append(nombreLabel);
        nombreDiv.append(nombreInput);
        //? Apellido
        const apellidoDiv = crearDivForm();
        const apellidoLabel = crearLabelForm('apellidoUsuarioPerfil', 'Apellido:');
        const apellidoInput = crearInputForm('apellidoUsuarioPerfil', 'text', usuario[0].apellidoUsuario);
        apellidoDiv.append(apellidoLabel);
        apellidoDiv.append(apellidoInput);
        //? Email
        const emailDiv = crearDivForm();
        const emailLabel = crearLabelForm('emailUsuarioPerfil', 'Email:');
        const emailInput = crearInputForm('emailUsuarioPerfil', 'email', usuario[0].emailUsuario);
        emailDiv.append(emailLabel);
        emailDiv.append(emailInput);
        //? Password
        const passDiv = crearDivForm();
        const passLabel = crearLabelForm('passUsuarioPerfil', 'Contraseña:');
        const passInput = crearInputForm('passUsuarioPerfil', 'password', '');
        passDiv.append(passLabel);
        passDiv.append(passInput);
        //? Asignacion final Form
        form.append(nombreDiv);
        form.append(apellidoDiv);
        form.append(emailDiv);
        form.append(passDiv);
        //? Retorno de HTML
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Configuracion Perfil
// #endregion

// #region //* Contenido Politica & Privacidad
//TODO Inicio Contenido Politica Privacidad
async function contenidoPoliticaPrivacidad() {
    try {
        //? Texto a mostrar
        const text = `
        En BackLog - Adso, respetamos tu privacidad.
        Los datos personales que puedas proporcionar (como nombre o correo electrónico) se usarán únicamente para responder consultas o mejorar el servicio.
        Usamos cookies solo para fines estadísticos y de personalización.
        No compartimos tu información con terceros.
        Puedes solicitar en cualquier momento la eliminación o modificación de tus datos escribiendo a contacto@backlogadso.com
        Última actualización: 11 de noviembre de 2025.
        `;
        //? Inicio Formulario
        const form = crearForm();
        //? Parrafo (texto)
        const parrafoDiv = crearDivForm();
        const parrafo = crearParrafo(text);
        parrafoDiv.append(parrafo);
        //? Asignacion final Form
        form.append(parrafoDiv);
        //? Retorno de HTML
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Politica Privacidad
// #endregion

// #region //* Contenido Terminos & Condiciones
//TODO Inicio Contenido Terminos & Condiciones
async function contenidoTerminosCondiciones() {
    try {
        //? Texto a mostrar
        const text = `El acceso y uso de BackLog - Adso implica la aceptación de estos términos. 
        El contenido de esta página es únicamente con fines informativos y educativos.
        No se permite la reproducción o distribución del material sin autorización del autor o fuente original.
        No garantizamos la disponibilidad continua del sitio ni nos hacemos responsables por el uso indebido del contenido.
        Estos términos pueden modificarse sin previo aviso.
        Ley aplicable: Colombia`;
        //? Inicio Formulario
        const form = crearForm();
        //? Parrafo (texto)
        const parrafoDiv = crearDivForm();
        const parrafo = crearParrafo(text);
        parrafoDiv.append(parrafo);
        //? Asignacion final Form
        form.append(parrafoDiv);
        //? Retorno de HTML
        return form;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Terminos & Condiciones
// #endregion

// #region //* Contenido Alertas Error
//TODO Inicio Contenido Alertas Error
async function contenidoAlertasError(message) {
    try {
        //? Texto
        const div = crearDivForm();
        const p = crearParrafo(message);
        div.append(p);
        return div;
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin Contenido Alertas Error
// #endregion

//! /////////////////////////////////////////////////////////
//! FIN Contenidos de HTML para los SweetAlert
//! /////////////////////////////////////////////////////////

// #endregion

// #region //* Funciones SweetAlert Main
//! /////////////////////////////////////////////////////////
//! Funciones SweetAlert (SweetAlert2 Principales)
//! /////////////////////////////////////////////////////////

// #region //* Sweet Usuario Insertar
//TODO Inicio SweetAlert Usuario Insertar
async function sweetUsuarioInsertar() {
    try {
        Swal.fire({
            title: 'Crear Nuevo Usuario', //? Titulo Modal
            showLoaderOnConfirm: true, //? Muestra loader mientras espera el preConfirm
            html: await contenidoUsuarioInsertar(), //? Contenido HTML
            confirmButtonText: 'Confirmar', //? Texto boton confirmar
            showCancelButton: true, //? Mostrar boton cancelar
            cancelButtonText: 'Cancelar', //? Texto boton cancelar
            focusConfirm: false, //? Desactivar focus al boton confirmar
            confirmButtonColor: '#007bff', //? Color boton confirmar
            cancelButtonColor: '#dc3545', //? Color boton cancelar
            preConfirm: async () => {
                //? Se capturan los datos del formulario
                const nombre = document.querySelector('#nombreUsuario').value.trim();
                const apellido = document.querySelector('#apellidoUsuario').value.trim();
                const email = document.querySelector('#emailUsuario').value.trim();
                //* Validar formato de correo antes de continuar
                const emailValue = email;
                const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regexCorreo.test(emailValue)) {
                    Swal.showValidationMessage('Formato de correo no válido');
                    return false;
                }
                const pass = document.querySelector('#passUsuario').value.trim();
                const tipoUsuario = document.querySelector('#tipoUsuario').value.trim();
                //? Verificar que los campos esten llenos
                if (!nombre || !apellido || !email || !pass || !tipoUsuario) {
                    Swal.showValidationMessage('¡Todos los campos son requeridos!');
                    return false;
                }
                //? Verificacion de Email del Usuario
                let boolEmail = await verificarEmail(email);
                if (boolEmail == false) {
                    Swal.showValidationMessage('¡Email ya existente, intenta con otro email!');
                    return false;
                }
                //? Retornar valores finales
                return {
                    nombre,
                    apellido,
                    email,
                    pass,
                    tipoUsuario,
                };
            },
        }).then(async (result) => {
            //? Verificar click en boton confirmar
            if (result.isConfirmed) {
                //? Traer datos retornados del preConfirm
                const datos = result.value;
                //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
                let formData = new FormData();
                formData.append('nombre', datos.nombre);
                formData.append('apellido', datos.apellido);
                formData.append('email', datos.email);
                formData.append('pass', datos.pass);
                formData.append('tipoUsuario', datos.tipoUsuario);
                //? Solicitud de datos a controller
                const json = await fetch('../controller/controllerUsuarioInsertar.php', {
                    method: 'POST',
                    body: formData,
                });
                //? Conversion a JSON valido
                const response = await json.json();
                //? Verificacion de proceso (success = True: Exito, success = False: Error)
                if (response.success) {
                    Swal.fire({ title: '¡Éxito!', text: response.message, icon: 'success', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                } else {
                    Swal.fire({ title: '¡Error!', text: response.message, icon: 'error', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                }
            }
        });
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin SweetAlert Usuario Insertar
// #endregion

// #region //* Sweet Usuario Editar
//TODO Inicio SweetAlert Usuario Editar
async function sweetUsuarioEditar(id, tipoUsuario) {
    try {
        Swal.fire({
            title: 'Editar Usuario', //? Titulo Modal
            showLoaderOnConfirm: true, //? muestra loader mientras espera el preConfirm
            html: await contenidoUsuarioEditar(id), //? Contenido HTML
            confirmButtonText: 'Confirmar', //? Texto boton confirmar
            showCancelButton: true, //? Mostrar boton cancelar
            cancelButtonText: 'Cancelar', //? Texto boton cancelar
            focusConfirm: false, //? Desactivar focus al boton crear
            confirmButtonColor: '#007bff', //? Color boton confirmar
            cancelButtonColor: '#dc3545', //? Color boton cancelar
            preConfirm: async () => {
                //? Se traen datos de usuario por ID
                const datosUsuario = await traerDatosUsuarioPorID(id, tipoUsuario);
                //? Se capturan los datos del formulario
                const nombre = document.querySelector('#nombreUsuario').value.trim();
                const apellido = document.querySelector('#apellidoUsuario').value.trim();
                const email = document.querySelector('#emailUsuario').value.trim();
                //* Validar formato de correo antes de continuar
                const emailValue = email;
                const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regexCorreo.test(emailValue)) {
                    Swal.showValidationMessage('Formato de correo no válido');
                    return false;
                }
                let pass = document.querySelector('#passUsuario').value.trim();
                const tipoUsuario = document.querySelector('#tipoUsuario').value.trim();
                //? Se verifica si se escribio una password nueva o se dejo vacio
                let bool = false;
                if (pass == null || pass == '') {
                    //? Si se dejo vacio se asigna la contraseña anterior
                    pass = datosUsuario[0].passUsuario;
                    bool = true;
                }
                //? Verificar que los campos esten llenos
                if (!nombre || !apellido || !email || !pass || !tipoUsuario) {
                    Swal.showValidationMessage('¡Todos los campos son requeridos!');
                    return false;
                }
                //? Verificacion de Email del Usuario
                if (email != datosUsuario[0].emailUsuario) {
                    let boolEmail = await verificarEmail(email);
                    if (boolEmail == false) {
                        Swal.showValidationMessage('¡Email ya existente, intenta con otro email!');
                        return false;
                    }
                }
                //? Retornar valores finales
                return {
                    nombre,
                    apellido,
                    email,
                    pass,
                    tipoUsuario,
                    bool,
                    id,
                };
            },
        }).then(async (result) => {
            //? Verificar click en boton confirmar
            if (result.isConfirmed) {
                //? Traer datos retornados del preConfirm
                const datos = result.value;
                //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
                let formData = new FormData();
                formData.append('nombre', datos.nombre);
                formData.append('apellido', datos.apellido);
                formData.append('email', datos.email);
                formData.append('pass', datos.pass);
                formData.append('tipoUsuario', datos.tipoUsuario);
                formData.append('bool', datos.bool);
                formData.append('id', datos.id);
                //? Solicitud de datos a controller
                const json = await fetch('../controller/controllerUsuarioEditar.php', {
                    method: 'POST',
                    body: formData,
                });
                //? Conversion a JSON valido
                const response = await json.json();
                //? Verificacion de proceso (success = True: Exito, success = False: Error)
                if (response.success) {
                    Swal.fire({ title: '¡Éxito!', text: response.message, icon: 'success', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                } else {
                    Swal.fire({ title: '¡Error!', text: response.message, icon: 'error', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                }
            }
        });
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin SweetAlert Usuario Editar
// #endregion

// #region //* Sweet Usuario Activar
//TODO Inicio SweetAlert Activar Usuario
async function sweetUsuarioActivar(id) {
    try {
        Swal.fire({
            title: 'Activar Usuario', //? Titulo Modal
            icon: 'question', //? Icono Modal
            showLoaderOnConfirm: true, //? muestra loader mientras espera el preConfirm
            html: await contenidoUsuarioActivar(id), //? Contenido HTML
            confirmButtonText: 'Confirmar', //? Texto boton confirmar
            showCancelButton: true, //? Mostrar boton cancelar
            cancelButtonText: 'Cancelar', //? Texto boton cancelar
            focusConfirm: false, //? Desactivar focus al boton crear
            confirmButtonColor: '#007bff', //? Color boton confirmar
            cancelButtonColor: '#dc3545', //? Color boton cancelar
            preConfirm: () => {
                //? Retornar valores finales
                return id;
            },
        }).then(async (result) => {
            //? Verificar click en boton confirmar
            if (result.isConfirmed) {
                //? Traer datos retornados del preConfirm
                const datos = result.value;
                //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
                let formData = new FormData();
                formData.append('id', datos);
                //? Solicitud de datos a controller
                const json = await fetch('../controller/controllerUsuarioActivar.php', {
                    method: 'POST',
                    body: formData,
                });
                //? Conversion a JSON valido
                const response = await json.json();
                //? Verificacion de proceso (success = True: Exito, success = False: Error)
                if (response.success) {
                    Swal.fire({ title: '¡Éxito!', text: response.message, icon: 'success', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                } else {
                    Swal.fire({ title: '¡Error!', text: response.message, icon: 'error', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                }
            }
        });
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin SweetAlert Activar Usuario
// #endregion

// #region //* Sweet Usuario Desactivar
//TODO Inicio SweetAlert Desactivar Usuario
async function sweetUsuarioDesactivar(id) {
    try {
        Swal.fire({
            title: 'Desactivar Usuario', //? Titulo Modal
            icon: 'warning', //? Icono Modal
            showLoaderOnConfirm: true, //? muestra loader mientras espera el preConfirm
            html: await contenidoUsuarioDesctivar(id), //? Contenido HTML
            confirmButtonText: 'Confirmar', //? Texto boton confirmar
            showCancelButton: true, //? Mostrar boton cancelar
            cancelButtonText: 'Cancelar', //? Texto boton cancelar
            focusConfirm: false, //? Desactivar focus al boton crear
            confirmButtonColor: '#007bff', //? Color boton confirmar
            cancelButtonColor: '#dc3545', //? Color boton cancelar
            preConfirm: () => {
                //? Retornar valores finales
                return id;
            },
        }).then(async (result) => {
            //? Verificar click en boton confirmar
            if (result.isConfirmed) {
                //? Traer datos retornados del preConfirm
                const datos = result.value;
                //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
                let formData = new FormData();
                formData.append('id', datos);
                //? Solicitud de datos a controller
                const json = await fetch('../controller/controllerUsuarioDesactivar.php', {
                    method: 'POST',
                    body: formData,
                });
                //? Conversion a JSON valido
                const response = await json.json();
                //? Verificacion de proceso (success = True: Exito, success = False: Error)
                if (response.success) {
                    Swal.fire({ title: '¡Éxito!', text: response.message, icon: 'success', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                } else {
                    Swal.fire({ title: '¡Error!', text: response.message, icon: 'error', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                }
            }
        });
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin SweetAlert Desactivar Usuario
// #endregion

// #region //* Sweet Configuracion Perfil
//TODO Inicio SweetAlert Editar Perfil
async function sweetConfiguracionPerfil(id, tipoUsuario) {
    try {
        Swal.fire({
            title: 'Editar Perfil', //? Titulo Modal
            showLoaderOnConfirm: true, //? muestra loader mientras espera el preConfirm
            html: await contenidoConfiguracionPerfil(id), //? Contenido HTML
            confirmButtonText: 'Confirmar', //? Texto boton confirmar
            showCancelButton: true, //? Mostrar boton cancelar
            cancelButtonText: 'Cancelar', //? Texto boton cancelar
            focusConfirm: false, //? Desactivar focus al boton crear
            confirmButtonColor: '#007bff', //? Color boton confirmar
            cancelButtonColor: '#dc3545', //? Color boton cancelar
            preConfirm: async () => {
                //? Se traen datos de usuario por ID
                const datosUsuario = await traerDatosUsuarioPorID(id, tipoUsuario);
                //? Se capturan los datos del formulario
                const nombre = document.querySelector('#nombreUsuarioPerfil').value.trim();
                const apellido = document.querySelector('#apellidoUsuarioPerfil').value.trim();
                const email = document.querySelector('#emailUsuarioPerfil').value.trim();
                let pass = document.querySelector('#passUsuarioPerfil').value.trim();
                //? Se verifica si se escribio una password nueva o se dejo vacio
                let bool = false;
                if (pass == null || pass == '') {
                    //? Si se dejo vacio se asigna la contraseña anterior
                    pass = datosUsuario[0].passUsuario;
                    bool = true;
                }
                //? Verificar que los campos esten llenos
                if (!nombre || !apellido || !email || !pass) {
                    Swal.showValidationMessage('¡Todos los campos son requeridos!');
                    return false;
                }
                //? Verificacion de Email del Usuario
                if (email != datosUsuario[0].emailUsuario) {
                    let boolEmail = await verificarEmail(email);
                    if (boolEmail == false) {
                        Swal.showValidationMessage('¡Email ya existente, intenta con otro email!');
                        return false;
                    }
                }
                //? Retornar valores finales
                return {
                    nombre,
                    apellido,
                    email,
                    pass,
                    bool,
                    id,
                };
            },
        }).then(async (result) => {
            //? Verificar click en boton confirmar
            if (result.isConfirmed) {
                //? Traer datos retornados del preConfirm
                const datos = result.value;
                //? Se añaden Datos a FormData (Se usa para que el fetch acepte los datos correctamente)
                let formData = new FormData();
                formData.append('nombre', datos.nombre);
                formData.append('apellido', datos.apellido);
                formData.append('email', datos.email);
                formData.append('pass', datos.pass);
                formData.append('bool', datos.bool);
                formData.append('id', datos.id);
                //? Solicitud de datos a controller
                const json = await fetch('../controller/controllerUsuarioEditarPerfil.php', {
                    method: 'POST',
                    body: formData,
                });
                //? Conversion a JSON valido
                const response = await json.json();
                //? Verificacion de proceso (success = True: Exito, success = False: Error)
                if (response.success) {
                    Swal.fire({ title: '¡Éxito!', text: response.message, icon: 'success', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                } else {
                    Swal.fire({ title: '¡Error!', text: response.message, icon: 'error', confirmButtonColor: '#007bff' }).then(
                        () => {
                            location.reload();
                        },
                    );
                }
            }
        });
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin SweetAlert Editar Perfil
// #endregion

// #region //* Sweet Politica & Privacidad
//TODO Inicio SweetAlert Activar Usuario
async function sweetPoliticaPrivacidad() {
    try {
        Swal.fire({
            title: 'Politica & Privacidad', //? Titulo Modal
            html: await contenidoPoliticaPrivacidad(), //? Contenido HTML
            confirmButtonText: 'Aceptar', //? Texto boton confirmar
            focusConfirm: false, //? Desactivar focus al boton crear
            confirmButtonColor: '#007bff', //? Color boton confirmar
        });
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}
//TODO Fin SweetAlert Activar Usuario
// #endregion

// #region //* Sweet Terminos & Condiciones
//TODO Inicio SweetAlert Activar Usuario
async function sweetTerminosCondiciones() {
    try {
        Swal.fire({
            title: 'Terminos & Condiciones', //? Titulo Modal
            html: await contenidoTerminosCondiciones(), //? Contenido HTML
            confirmButtonText: 'Aceptar', //? Texto boton confirmar
            focusConfirm: false, //? Desactivar focus al boton crear
            confirmButtonColor: '#007bff', //? Color boton confirmar
        });
    } catch (e) {
        //? Control de errores
        console.log(e);
        return false;
    }
}

//TODO Fin SweetAlert Activar Usuario
// #endregion

// #region //* Sweet Alertas Error
//TODO Inicio Sweet Alertas Error
async function sweetAlertasError(message, title) {
    Swal.fire({
        title: title,
        html: await contenidoAlertasError(message),
        icon: 'error',
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Aceptar',
        showLoaderOnConfirm: true,
    });
}
//TODO Fin Sweet Alertas Error
// #endregion

//! /////////////////////////////////////////////////////////
//! FIN Funciones SweetAlert (SweetAlert2 Principales)
//! /////////////////////////////////////////////////////////
// #endregion
