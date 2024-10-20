<?php
session_start();
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
                                
                                <!-- Botón para eliminar que abre el modal -->
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $row['id_viaje']; ?>">Eliminar</button>
                                
                                <!-- Modal de confirmación para cada viaje -->
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
                                                <!-- Formulario para eliminar el viaje -->
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
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
