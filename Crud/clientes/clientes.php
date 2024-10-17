<?php
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
                            <td><?php echo $row['direccion']; ?></td>
                            <td><?php echo $row['fecha_nacimiento']; ?></td>
                            <td><?php echo $row['genero']; ?></td>
                            <td>
                                <a href="editar_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar_cliente.php?id=<?php echo $row['id_cliente']; ?>" class="btn btn-danger">Eliminar</a>
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
