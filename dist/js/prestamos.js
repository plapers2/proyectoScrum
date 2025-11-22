$(document).ready(function () {
    cargarEstadisticas();
});

function cargarEstadisticas() {
    $.ajax({
        url: '../controller/controllerEstadisticasPrestamos.php',
        type: 'GET',
        dataType: 'json',
        success: function (estadisticas) {
            $('#prestamosActivos').text(estadisticas.prestamosActivos);
            $('#devolucionesHoy').text(estadisticas.devolucionesHoy);
            $('#prestamosVencidos').text(estadisticas.prestamosVencidos);
            $('#totalMes').text(estadisticas.totalMes);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar estadísticas:', error);
        },
    });
}

function filtrarPrestamos(tipoUsuario) {
    var busqueda = $('#busquedaPrestamo').val();
    var estado = $('#estadoPrestamo').val();
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();

    $.ajax({
        url: '../controller/controllerBuscarPrestamos.php',
        type: 'POST',
        data: {
            busqueda: busqueda,
            estado: estado,
            fechaDesde: fechaDesde,
            fechaHasta: fechaHasta,
        },
        dataType: 'json',
        success: function (prestamos) {
            actualizarTablaPrestamos(prestamos, tipoUsuario);
        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo realizar la búsqueda',
            });
        },
    });
}

function actualizarTablaPrestamos(prestamos, tipoUsuario) {
    var tbody = $('#tablaPrestamos tbody');
    tbody.empty();

    if (prestamos.length === 0) {
        tbody.append('<tr><td colspan="8" class="text-center">No se encontraron préstamos</td></tr>');
        return;
    }

    prestamos.forEach(function (prestamo) {
        var fechaDevolucion = prestamo.fechaDevolucion ? prestamo.fechaDevolucion : '<span class="text-muted">Pendiente</span>';

        var estadoBadge = '';
        if (prestamo.fechaDevolucion) {
            estadoBadge = '<span class="badge bg-success">Devuelto</span>';
        } else {
            var fechaLimite = new Date(prestamo.fechaPrestamo);
            fechaLimite.setDate(fechaLimite.getDate() + 15);
            var hoy = new Date();

            if (hoy > fechaLimite) {
                estadoBadge = '<span class="badge bg-danger">Vencido</span>';
            } else {
                estadoBadge = '<span class="badge bg-primary">Activo</span>';
            }
        }

        var botonesAccion = '';
        if (!prestamo.fechaDevolucion && tipoUsuario === 'Administrador') {
            botonesAccion +=
                '<button class="btn btn-sm btn-success mb-2" onclick="sweetPrestamoRegistrarDevolucion(' +
                prestamo.idPrestamo +
                ')">' +
                '<i class="bi bi-check-circle"></i> Devolver</button> ';
        }
        botonesAccion +=
            '<button class="btn btn-sm btn-info text-white" onclick="sweetPrestamoVerDetalles(' +
            prestamo.idPrestamo +
            ')">' +
            '<i class="bi bi-eye"></i> Detalles</button>';

        var fila =
            '<tr>' +
            '<td>' +
            prestamo.idPrestamo +
            '</td>' +
            '<td>' +
            prestamo.idReserva +
            '</td>' +
            '<td>' +
            prestamo.nombreUsuario +
            ' ' +
            prestamo.apellidoUsuario +
            '</td>' +
            '<td>' +
            prestamo.fechaPrestamo +
            '</td>' +
            '<td>' +
            prestamo.fechaLimite +
            '</td>' +
            '<td>' +
            fechaDevolucion +
            '</td>' +
            '<td>' +
            estadoBadge +
            '</td>' +
            '<td>' +
            botonesAccion +
            '</td>' +
            '</tr>';

        tbody.append(fila);
    });
}

function limpiarFiltros(tipoUsuario) {
    $('#busquedaPrestamo').val('');
    $('#estadoPrestamo').val('todos');
    $('#fechaDesde').val('');
    $('#fechaHasta').val('');
    console.log('a');
    filtrarPrestamos(tipoUsuario);
}
