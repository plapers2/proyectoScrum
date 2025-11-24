function editarRutaTrabajo(boton) {
    let id = boton.getAttribute('data-id');
    document.getElementById('id_trabajo').value = id;

    let modal = new bootstrap.Modal(document.getElementById('modalEditarRuta'));
    modal.show();

    
}
