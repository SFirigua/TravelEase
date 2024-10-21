<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener todas las reservas
$sql = "SELECT r.id_reserva, c.nombre, v.origen, v.destino, r.fecha_reserva, r.estado, r.asiento
        FROM Reservas r 
        JOIN Clientes c ON r.id_cliente = c.id_cliente 
        JOIN Viajes v ON r.id_viaje = v.id_viaje";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Reservas</h2>
        <a href="agregar_reserva.php" class="btn btn-success mb-3">Agregar Reserva</a>

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
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Asiento</th> 
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
                            <td><?php echo $row['asiento']; ?></td>
                            <td><?php echo $row['origen']; ?></td>
                            <td><?php echo $row['destino']; ?></td>
                            <td><?php echo $row['fecha_reserva']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td>
                                <a href="editar_reserva.php?id=<?php echo $row['id_reserva']; ?>" class="btn btn-warning">Editar</a>
                                <!-- Botón para eliminar que abre el modal -->
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $row['id_reserva']; ?>">Eliminar</button>
                                
                                <!-- Modal de confirmación para cada reserva -->
                                <div class="modal fade" id="confirmModal<?php echo $row['id_reserva']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo $row['id_reserva']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel<?php echo $row['id_reserva']; ?>">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar esta reserva?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <!-- Formulario para eliminar la reserva -->
                                                <form method="POST" action="eliminar_reserva.php">
                                                    <input type="hidden" name="id_reserva" value="<?php echo $row['id_reserva']; ?>">
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
                        <td colspan="7" class="text-center">No hay reservas registradas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
