window.addEventListener('DOMContentLoaded', (event) => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const tablaAprendices = document.getElementById('tablaAprendices');
    if (tablaAprendices) {
        new simpleDatatables.DataTable(tablaAprendices, {
            labels: {
                placeholder: 'Buscar...',
                perPage: 'registros por página',
                noRows: 'No se encontraron resultados',
                info: 'Mostrando {start} a {end} de {rows} registros',
                noResults: 'No hay resultados para tu búsqueda',
            },
        });
    }
    const tablaTrabajos = document.getElementById('tablaTrabajos');
    if (tablaTrabajos) {
        new simpleDatatables.DataTable(tablaTrabajos, {
            labels: {
                placeholder: 'Buscar...',
                perPage: 'registros por página',
                noRows: 'No se encontraron resultados',
                info: 'Mostrando {start} a {end} de {rows} registros',
                noResults: 'No hay resultados para tu búsqueda',
            },
        });
    }
    const tablaCursos = document.getElementById('tablaCursos');
    if (tablaCursos) {
        new simpleDatatables.DataTable(tablaCursos, {
            labels: {
                placeholder: 'Buscar...',
                perPage: 'registros por página',
                noRows: 'No se encontraron resultados',
                info: 'Mostrando {start} a {end} de {rows} registros',
                noResults: 'No hay resultados para tu búsqueda',
            },
        });
    }
    const tablaInstructores = document.getElementById('tablaInstructores');
    if (tablaInstructores) {
        new simpleDatatables.DataTable(tablaInstructores, {
            labels: {
                placeholder: 'Buscar...',
                perPage: 'registros por página',
                noRows: 'No se encontraron resultados',
                info: 'Mostrando {start} a {end} de {rows} registros',
                noResults: 'No hay resultados para tu búsqueda',
            },
        });
    }
    const tablaAdministradores = document.getElementById('tablaAdministradores');
    if (tablaAdministradores) {
        new simpleDatatables.DataTable(tablaAdministradores, {
            labels: {
                placeholder: 'Buscar...',
                perPage: 'registros por página',
                noRows: 'No se encontraron resultados',
                info: 'Mostrando {start} a {end} de {rows} registros',
                noResults: 'No hay resultados para tu búsqueda',
            },
        });
    }
});
