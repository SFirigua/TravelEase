<?php
include '../includes/header.php';
include '../includes/conexion.php';

// Obtener todas las reservas
$sql = "SELECT r.id_reserva, c.nombre, v.origen, v.destino, r.fecha_reserva, r.estado 
        FROM Reservas r 
        JOIN Clientes c ON r.id_cliente = c.id_cliente 
        JOIN Viajes v ON r.id_viaje = v.id_viaje";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Reservas</h2>
        <a href="agregar_reserva.php" class="btn btn-success mb-3">Agregar Reserva</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Fecha Reserva</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_reserva']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['origen']; ?></td>
                            <td><?php echo $row['destino']; ?></td>
                            <td><?php echo $row['fecha_reserva']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td>
                                <a href="editar_reserva.php?id=<?php echo $row['id_reserva']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar_reserva.php?id=<?php echo $row['id_reserva']; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay reservas registradas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
