<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener todos los viajes
$sql = "SELECT V.*, T.nombre_transporte FROM Viajes V JOIN Transportes T ON V.id_transporte = T.id_transporte";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Viajes</h2>
        <a href="agregar_viaje.php" class="btn btn-success mb-3">Agregar Viaje</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transporte</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Fecha Salida</th>
                    <th>Hora Salida</th>
                    <th>Fecha Llegada</th>
                    <th>Hora Llegada</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_viaje']; ?></td>
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
                                <a href="editar_viaje.php?id=<?php echo $row['id_viaje']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar_viaje.php?id=<?php echo $row['id_viaje']; ?>" class="btn btn-danger">Eliminar</a>
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
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
