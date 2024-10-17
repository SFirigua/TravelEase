<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_cliente = $_GET['id'];

// Obtener el cliente existente
$sql = "SELECT * FROM Clientes WHERE id_cliente = $id_cliente";
$cliente = $conn->query($sql)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $numero_celular = $_POST['numero_celular'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];

    $sql = "UPDATE Clientes SET nombre='$nombre', numero_celular='$numero_celular', direccion='$direccion', fecha_nacimiento='$fecha_nacimiento', genero='$genero' 
            WHERE id_cliente = $id_cliente";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Cliente actualizado con éxito</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Editar Cliente</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="numero_celular" class="form-label">Número Celular</label>
                <input type="text" class="form-control" id="numero_celular" name="numero_celular" value="<?php echo $cliente['numero_celular']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="email" class="form-control" id="direccion" name="direccion" value="<?php echo $cliente['direccion']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $cliente['fecha_nacimiento']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select id="genero" name="genero" class="form-select" required>
                    <option value="M" <?php echo $cliente['genero'] == 'M' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="F" <?php echo $cliente['genero'] == 'F' ? 'selected' : ''; ?>>Femenino</option>
                    <option value="Otro" <?php echo $cliente['genero'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
            <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
