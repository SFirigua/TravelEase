<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener todos los transportes
$sql = "SELECT * FROM Transportes";
$result = $conn->query($sql);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container mt-5">
        <h2>Lista de Transportes</h2>
        <a href="agregar_transporte.php" class="btn btn-success mb-3">Agregar Transporte</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo de Transporte</th>
                    <th>Nombre del Transporte</th>
                    <th>Número de Asientos</th>
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
                            <td>
                                <a href="editar_transporte.php?id=<?php echo $row['id_transporte']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar_transporte.php?id=<?php echo $row['id_transporte']; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay transportes registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
