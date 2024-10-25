<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

$rutas_por_pagina = 10;

$sql_total = "SELECT COUNT(*) as total FROM Rutas";
$result_total = $conn->query($sql_total);
$total_rutas = $result_total->fetch_assoc()['total'];

$total_paginas = ceil($total_rutas / $rutas_por_pagina);

$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
} elseif ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
}

$offset = ($pagina_actual - 1) * $rutas_por_pagina;

$sql = "SELECT id_ruta, nombre_ruta, origen, destino, TIME_FORMAT(duracion, '%H:%i') AS duracion, frecuencia 
        FROM Rutas LIMIT $offset, $rutas_por_pagina";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Rutas</h2>
        <a href="agregar_ruta.php" class="btn btn-success mb-3">Agregar Ruta</a>
        <a href="reporte_rutas.php" class="btn btn-primary mb-3 ms-2" target="_blank">Reporte PDF</a>

        <!-- Mostrar mensaje de error -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre Ruta</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Duración</th>
                    <th>Frecuencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nombre_ruta']; ?></td>
                            <td><?php echo $row['origen']; ?></td>
                            <td><?php echo $row['destino']; ?></td>
                            <td><?php echo $row['duracion']; ?></td>
                            <td><?php echo $row['frecuencia']; ?></td>
                            <td>
                                <a href="editar_ruta.php?id=<?php echo $row['id_ruta']; ?>" class="btn btn-warning"> <i class="fas fa-edit"></i> </a>
                                
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $row['id_ruta']; ?>"> <i class="fas fa-trash-alt"></i> </button>
                                
                                <div class="modal fade" id="confirmModal<?php echo $row['id_ruta']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo $row['id_ruta']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel<?php echo $row['id_ruta']; ?>">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar esta ruta?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="eliminar_ruta.php">
                                                    <input type="hidden" name="id_ruta" value="<?php echo $row['id_ruta']; ?>">
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay rutas registradas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <nav aria-label="Paginación">
            <ul class="pagination justify-content-center">
                <?php if ($pagina_actual > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php if ($i == $pagina_actual) echo 'active'; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Siguiente">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
