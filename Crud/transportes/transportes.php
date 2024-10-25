<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

$transportes_por_pagina = 10;

$sql_total = "SELECT COUNT(*) as total FROM Transportes";
$result_total = $conn->query($sql_total);
$total_transportes = $result_total->fetch_assoc()['total'];

$total_paginas = ceil($total_transportes / $transportes_por_pagina);

$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) {
    $pagina_actual = 1;
} elseif ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
}

$offset = ($pagina_actual - 1) * $transportes_por_pagina;

$sql = "SELECT t.*, r.nombre_ruta, r.origen, r.destino 
        FROM Transportes t 
        LEFT JOIN Rutas r ON t.id_ruta = r.id_ruta
        LIMIT $offset, $transportes_por_pagina";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Transportes</h2>
        <a href="agregar_transporte.php" class="btn btn-success mb-3">Agregar Transporte</a>

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
                    <th>ID</th>
                    <th>Tipo de Transporte</th>
                    <th>Nombre del Transporte</th>
                    <th>N° Asientos</th>
                    <th>Ruta</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_transporte']; ?></td>
                            <td><?php echo $row['tipo_transporte']; ?></td>
                            <td><?php echo $row['nombre_transporte']; ?></td>
                            <td><?php echo $row['num_asientos']; ?></td>
                            <td><?php echo $row['nombre_ruta']; ?></td>
                            <td><?php echo $row['origen']; ?></td>
                            <td><?php echo $row['destino']; ?></td>
                            <td>
                                <a href="editar_transporte.php?id=<?php echo $row['id_transporte']; ?>" class="btn btn-warning"> <i class="fas fa-edit"></i> </a>
                                
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $row['id_transporte']; ?>"> <i class="fas fa-trash-alt"></i> </button>
                                
                                <div class="modal fade" id="confirmModal<?php echo $row['id_transporte']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo $row['id_transporte']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel<?php echo $row['id_transporte']; ?>">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar este transporte?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="eliminar_transporte.php">
                                                    <input type="hidden" name="id_transporte" value="<?php echo $row['id_transporte']; ?>">
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
                        <td colspan="8" class="text-center">No hay transportes registrados</td>
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
