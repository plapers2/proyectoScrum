$(document).ready(function () {
    cargarCategorias();
});

function cargarCategorias() {
    $.ajax({
        url: '../controller/controllerObtenerCategorias.php',
        type: 'GET',
        dataType: 'json',
        success: function (categorias) {
            var select = $('#categoriaFiltro');
            select.empty();
            select.append('<option value="todas">Todas las categorías</option>');

            categorias.forEach(function (categoria) {
                select.append('<option value="' + categoria.nombreCategoria + '">' + categoria.nombreCategoria + '</option>');
            });
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las categorías',
            });
        },
    });
}

function filtrarLibros(tipoUsuario) {
    var busqueda = $('#busquedaLibro').val();
    var categoria = $('#categoriaFiltro').val();
    var disponibilidad = $('#disponibilidadFiltro').val();

    $.ajax({
        url: '../controller/controllerBuscarLibros.php',
        type: 'POST',
        data: {
            busqueda: busqueda,
            categoria: categoria,
            disponibilidad: disponibilidad,
        },
        dataType: 'json',
        success: function (libros) {
            actualizarTablaLibros(libros, tipoUsuario);
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda',
            });
        },
    });
}

function actualizarTablaLibros(libros, tipoUsuario) {
    var tbody = $('#tablaLibros tbody');
    tbody.empty();

    if (libros.length === 0) {
        tbody.append('<tr><td colspan="7" class="text-center">No se encontraron libros</td></tr>');
        return;
    }

    libros.forEach(function (libro) {
        let disponibilidadBadge;
        switch (libro.disponibilidadLibro) {
            case 'Disponible':
                disponibilidadBadge = '<span class="badge bg-success">Disponible</span>';
                break;
            case 'No disponible':
                disponibilidadBadge = '<span class="badge bg-warning">Agotado</span>';
                break;
            case 'Desactivado':
                disponibilidadBadge = '<span class="badge bg-danger">Desactivado</span>';
                break;

            default:
                break;
        }
        var fila =
            '<tr>' +
            '<td>' +
            libro.tituloLibro +
            '</td>' +
            '<td>' +
            libro.autorLibro +
            '</td>' +
            '<td>' +
            libro.isbnLibro +
            '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-info text-white" onclick="sweetLibroVerCategoria(' +
            libro.idLibro +
            ')"><i class="bi bi-eye"></i> Detalles </button>' +
            '</td>' +
            '<td>' +
            disponibilidadBadge +
            '</td>' +
            '<td>' +
            libro.cantidadLibro +
            '</td>';
        if (tipoUsuario === 'Administrador') {
            fila +=
                '<td class="d-flex justify-content-between w-100">' +
                '<button class="btn btn-warning btn-sm" onclick="sweetLibroEditar(' +
                libro.idLibro +
                ')">' +
                '<i class="bi bi-pencil-square"></i> Editar' +
                '</button>';
            switch (libro.disponibilidadLibro) {
                case 'Desactivado':
                    fila +=
                        '<button class="btn btn-success btn-sm w-50" onclick="sweetLibroActivar(' +
                        libro.idLibro +
                        ')"><i class="bi bi-check-circle"></i> Activar</button>';

                    break;
                default:
                    fila +=
                        '<button class="btn btn-danger btn-sm" onclick="sweetLibroEliminar(' +
                        libro.idLibro +
                        ')"><i class="bi bi-trash"></i> Eliminar</button>';

                    break;
            }
            fila += '</td></tr>';
        }

        tbody.append(fila);
    });
}

function limpiarFiltros(tipoUsuario) {
    $('#busquedaLibro').val('');
    $('#categoriaFiltro').val('todas');
    $('#disponibilidadFiltro').val('todos');

    filtrarLibros(tipoUsuario);
}

$('#busquedaLibro').on('keypress', function (e) {
    if (e.which === 13) {
        e.preventDefault();
        filtrarLibros();
    }
});
