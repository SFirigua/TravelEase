<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener todas las rutas
$sql = "SELECT * FROM Rutas";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Rutas</h2>
        <a href="agregar_ruta.php" class="btn btn-success mb-3">Agregar Ruta</a>

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
                            <td><?php echo $row['id_ruta']; ?></td>
                            <td><?php echo $row['nombre_ruta']; ?></td>
                            <td><?php echo $row['origen']; ?></td>
                            <td><?php echo $row['destino']; ?></td>
                            <td><?php echo $row['duracion']; ?></td>
                            <td><?php echo $row['frecuencia']; ?></td>
                            <td>
                                <a href="editar_ruta.php?id=<?php echo $row['id_ruta']; ?>" class="btn btn-warning">Editar</a>
                                
                                <!-- Botón para eliminar que abre el modal -->
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $row['id_ruta']; ?>">Eliminar</button>
                                
                                <!-- Modal de confirmación para cada ruta -->
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
                                                <!-- Formulario para eliminar la ruta -->
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
                        <td colspan="9" class="text-center">No hay rutas registradas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
