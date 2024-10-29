<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

$viajes_por_pagina = 5;

$sql_total = "SELECT COUNT(*) as total FROM Viajes";
$result_total = $conn->query($sql_total);
$total_viajes = $result_total->fetch_assoc()['total'];

$total_paginas = ceil($total_viajes / $viajes_por_pagina);

$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
} elseif ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
}

$offset = max(0, ($pagina_actual - 1) * $viajes_por_pagina);

$sql = "SELECT V.*, 
               T.nombre_transporte, 
               R.origen, 
               R.destino,
               DATE_FORMAT(V.fecha_salida, '%Y-%m-%d') AS fecha_salida,
               TIME_FORMAT(V.hora_salida, '%H:%i') AS hora_salida,
               DATE_FORMAT(V.fecha_llegada, '%Y-%m-%d') AS fecha_llegada,
               TIME_FORMAT(V.hora_llegada, '%H:%i') AS hora_llegada
        FROM Viajes V 
        JOIN Transportes T ON V.id_transporte = T.id_transporte
        JOIN Rutas R ON V.id_ruta = R.id_ruta
        LIMIT $offset, $viajes_por_pagina";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Viajes</h2>
        <a href="agregar_viaje.php" class="btn btn-success mb-3">Agregar Viaje</a>
        <a href="reporte_viajes.php" class="btn btn-primary mb-3 ms-2" target="_blank">Reporte PDF</a>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

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
                    <th class="align-middle">Transporte</th>
                    <th class="align-middle">Origen</th>
                    <th class="align-middle">Destino</th>
                    <th class="align-middle">Fecha Salida</th>
                    <th class="align-middle">Hora Salida</th>
                    <th class="align-middle">Fecha Llegada</th>
                    <th class="align-middle">Hora Llegada</th>
                    <th class="align-middle">Precio</th>
                    <th class="align-middle">Estado</th>
                    <th class="align-middle">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nombre_transporte']; ?></td>
                            <td><?php echo $row['origen']; ?></td>
                            <td><?php echo $row['destino']; ?></td>
                            <td><?php echo $row['fecha_salida']; ?></td>
                            <td><?php echo $row['hora_salida']; ?></td>
                            <td><?php echo $row['fecha_llegada']; ?></td>
                            <td><?php echo $row['hora_llegada']; ?></td>
                            <td><?php echo $row['precio']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td>
                                <a href="editar_viaje.php?id=<?php echo $row['id_viaje']; ?>" class="btn btn-warning"> <i class="fas fa-edit"></i> </a>
                                
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $row['id_viaje']; ?>"> <i class="fas fa-trash-alt"></i> </button>
                                
                                <div class="modal fade" id="confirmModal<?php echo $row['id_viaje']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo $row['id_viaje']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel<?php echo $row['id_viaje']; ?>">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar este viaje?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="eliminar_viaje.php">
                                                    <input type="hidden" name="id_viaje" value="<?php echo $row['id_viaje']; ?>">
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
                        <td colspan="11" class="text-center">No hay viajes registrados</td>
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
