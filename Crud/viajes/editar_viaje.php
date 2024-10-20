<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_viaje = $_GET['id'];

// Obtener el viaje existente
$sql = "SELECT * FROM Viajes WHERE id_viaje = $id_viaje";
$viaje = $conn->query($sql)->fetch_assoc();

// Obtener transportes para el dropdown
$sql_transportes = "SELECT * FROM Transportes";
$result_transportes = $conn->query($sql_transportes);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transporte = $_POST['id_transporte'];
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];
    $fecha_salida = $_POST['fecha_salida'];
    $hora_salida = $_POST['hora_salida'];
    $fecha_llegada = $_POST['fecha_llegada'];
    $hora_llegada = $_POST['hora_llegada'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];

    $sql = "UPDATE Viajes SET id_transporte='$id_transporte', origen='$origen', destino='$destino', 
            fecha_salida='$fecha_salida', hora_salida='$hora_salida', 
            fecha_llegada='$fecha_llegada', hora_llegada='$hora_llegada', 
            precio='$precio', estado='$estado' WHERE id_viaje=$id_viaje";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Viaje actualizado con éxito.";
        header("Location: /TravelEase/crud/viajes/viajes.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/viajes/viajes.php");
        exit();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Editar Viaje</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_transporte" class="form-label">Transporte</label>
                <select class="form-select" id="id_transporte" name="id_transporte" required>
                    <?php while ($transporte = $result_transportes->fetch_assoc()): ?>
                        <option value="<?php echo $transporte['id_transporte']; ?>" 
                        <?php echo ($transporte['id_transporte'] == $viaje['id_transporte']) ? 'selected' : ''; ?>>
                            <?php echo $transporte['nombre_transporte']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen</label>
                <input type="text" class="form-control" id="origen" name="origen" value="<?php echo $viaje['origen']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input type="text" class="form-control" id="destino" name="destino" value="<?php echo $viaje['destino']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_salida" class="form-label">Fecha Salida</label>
                <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" value="<?php echo $viaje['fecha_salida']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_salida" class="form-label">Hora Salida</label>
                <input type="time" class="form-control" id="hora_salida" name="hora_salida" value="<?php echo $viaje['hora_salida']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_llegada" class="form-label">Fecha Llegada</label>
                <input type="date" class="form-control" id="fecha_llegada" name="fecha_llegada" value="<?php echo $viaje['fecha_llegada']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_llegada" class="form-label">Hora Llegada</label>
                <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" value="<?php echo $viaje['hora_llegada']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" value="<?php echo $viaje['precio']; ?>" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="Programado" <?php echo ($viaje['estado'] == 'Programado') ? 'selected' : ''; ?>>Programado</option>
                    <option value="Cancelado" <?php echo ($viaje['estado'] == 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                    <option value="En curso" <?php echo ($viaje['estado'] == 'En curso') ? 'selected' : ''; ?>>En curso</option>
                    <option value="Finalizado" <?php echo ($viaje['estado'] == 'Finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Viaje</button>
            <a href="viajes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
