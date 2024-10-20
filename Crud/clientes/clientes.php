<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener todos los clientes
$sql = "SELECT * FROM Clientes";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Clientes</h2>
        <a href="agregar_cliente.php" class="btn btn-success mb-3">Agregar Cliente</a>

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
                    <th>Nombre</th>
                    <th>Número Celular</th>
                    <th>Correo Electronico</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_cliente']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['numero_celular']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['fecha_nacimiento']; ?></td>
                            <td><?php echo $row['genero']; ?></td>
                            <td>
                                <a href="editar_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-warning">Editar</a>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?php echo $row['id_cliente']; ?>">Eliminar</button>

                                <!-- Modal de confirmación para cada cliente -->
                                <div class="modal fade" id="confirmModal<?php echo $row['id_cliente']; ?>" tabindex="-1" aria-labelledby="confirmModalLabel<?php echo $row['id_cliente']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmModalLabel<?php echo $row['id_cliente']; ?>">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar este cliente?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="eliminar_cliente.php">
                                                    <input type="hidden" name="id_cliente" value="<?php echo $row['id_cliente']; ?>">
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
                        <td colspan="7" class="text-center">No hay clientes registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
