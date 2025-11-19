<?php
session_start();
require_once("../models/MySQL.php");

$baseDatos = new MySQL();
$conexion = $baseDatos->conectar();

// Validar sesión
if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['roles'])) {
    die("Error: sesión no válida.");
}

$usuario_id = (int) $_SESSION['idUsuario'];
// Consulta para obtener los trabajos del aprendiz
$query = "SELECT * from aprendices
   ";

// Ejecutar la consulta
$result = $baseDatos->efectuarConsulta($query);
?>

<div class="card p-4 mb-5 shadow">
    <h3 class="mb-4">
        <i class="zmdi zmdi-assignment"></i> Mis Trabajos
    </h3>

    <div class="table-responsive mt-3">
        <table class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Instructor</th>
                    <th>Calificación</th>
                    <th>Comentario</th>
                    <th>Archivo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) {
                    while ($fila = mysqli_fetch_assoc($result)) { ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($fila['id_trabajo']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre_instructor']) ?></td>
                            <td>
                                <?php 
                                $calificacion = $fila['calificacion_trabajo'];
                                if ($calificacion !== null && $calificacion !== '') {
                                    $clase_calificacion = $calificacion >= 3.5 ? 'badge bg-success' : ($calificacion >= 3.0 ? 'badge bg-warning' : 'badge bg-danger');
                                    echo "<span class='$clase_calificacion'>" . htmlspecialchars($calificacion) . "</span>";
                                } else {
                                    echo "<span class='badge bg-secondary'>Sin calificar</span>";
                                }
                                ?>
                            </td>
                            <td class="text-start">
                                <?php 
                                $comentario = $fila['comentario_trabajo'];
                                if (!empty($comentario)) {
                                    echo htmlspecialchars($comentario);
                                } else {
                                    echo '<span class="text-muted fst-italic">Sin comentarios</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if (!empty($fila['ruta_trabajo'])) { ?>
                                    <a href="<?= htmlspecialchars($fila['ruta_trabajo']) ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-info">
                                        <i class="zmdi zmdi-file"></i> Ver archivo
                                    </a>
                                <?php } else { ?>
                                    <span class="text-muted">Sin archivo</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="zmdi zmdi-folder-outline zmdi-hc-3x"></i>
                            <p class="mt-2">No tienes trabajos registrados</p>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $baseDatos->desconectar(); ?>