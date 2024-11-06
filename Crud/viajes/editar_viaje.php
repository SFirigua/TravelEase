<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_viaje = $_GET['id'];

// Obtener el viaje existente
$sql = "SELECT V.*, R.origen, R.destino FROM Viajes V JOIN Rutas R ON V.id_ruta = R.id_ruta WHERE V.id_viaje = $id_viaje";
$viaje = $conn->query($sql)->fetch_assoc();

// Obtener transportes para el dropdown
$sql_transportes = "
    SELECT T.*, R.origen, R.destino 
    FROM Transportes T
    JOIN Rutas R ON T.id_ruta = R.id_ruta
";
$result_transportes = $conn->query($sql_transportes);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transporte = $_POST['id_transporte'];
    $fecha_salida = $_POST['fecha_salida'];
    $hora_salida = $_POST['hora_salida'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];

    // Obtener la duración del transporte seleccionado
    $sql_duracion = "SELECT tiempo_duracion FROM Transportes WHERE id_transporte = $id_transporte";
    $result_duracion = $conn->query($sql_duracion);
    $duracion = $result_duracion->fetch_assoc()['tiempo_duracion'];

    // Convertir fecha y hora de salida a un formato manejable
    $fecha_hora_salida = new DateTime("$fecha_salida $hora_salida");
    
    // Añadir la duración
    $interval = new DateInterval('PT' . explode(":", $duracion)[0] . 'H' . explode(":", $duracion)[1] . 'M');
    $fecha_hora_salida->add($interval);

    // Separar fecha y hora de llegada
    $fecha_llegada = $fecha_hora_salida->format('Y-m-d');
    $hora_llegada = $fecha_hora_salida->format('H:i:s');

    // Actualizar el viaje en la base de datos
    $sql = "UPDATE Viajes SET id_transporte='$id_transporte', 
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
                            <?php echo $transporte['tipo_transporte'] . ' - Ruta: ' . $transporte['origen'] . ' a ' . $transporte['destino']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen</label>
                <input type="text" class="form-control" id="origen" name="origen" value="<?php echo $viaje['origen']; ?>" required readonly>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input type="text" class="form-control" id="destino" name="destino" value="<?php echo $viaje['destino']; ?>" required readonly>
            </div>
            <div class="mb-3">
                <label for="fecha_salida" class="form-label">Fecha Salida</label>
                <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" value="<?php echo $viaje['fecha_salida']; ?>" 
                min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_salida" class="form-label">Hora Salida</label>
                <input type="time" class="form-control" id="hora_salida" name="hora_salida" value="<?php echo $viaje['hora_salida']; ?>" required>
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
