<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener todas las rutas
$sql = "SELECT R.*, T.nombre_transporte FROM Rutas R JOIN Transportes T ON R.id_transporte = T.id_transporte";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Rutas</h2>
        <a href="agregar_ruta.php" class="btn btn-success mb-3">Agregar Ruta</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transporte</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Duración</th>
                    <th>Paradas Intermedias</th>
                    <th>Frecuencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_ruta']; ?></td>
                            <td><?php echo $row['nombre_transporte']; ?></td>
                            <td><?php echo $row['origen']; ?></td>
                            <td><?php echo $row['destino']; ?></td>
                            <td><?php echo $row['duracion']; ?></td>
                            <td><?php echo $row['paradas_intermedias']; ?></td>
                            <td><?php echo $row['frecuencia']; ?></td>
                            <td>
                                <a href="editar_ruta.php?id=<?php echo $row['id_ruta']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar_ruta.php?id=<?php echo $row['id_ruta']; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay rutas registradas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
