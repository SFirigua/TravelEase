<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener Rutas y transportes
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
    $fecha_llegada = $_POST['fecha_llegada'];
    $hora_llegada = $_POST['hora_llegada'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];

    // Obtener la fecha actual
    $fecha_actual = date('Y-m-d');

    // Validaciones
    if ($fecha_salida < $fecha_actual) {
        $_SESSION['error'] = "La fecha de salida no puede ser anterior a la fecha actual.";
        header("Location: /TravelEase/crud/viajes/viajes.php");
        exit();
    }

    if ($fecha_llegada < $fecha_actual) {
        $_SESSION['error'] = "La fecha de llegada no puede ser anterior a la fecha actual.";
        header("Location: /TravelEase/crud/viajes/viajes.php");
        exit();
    }

    if ($fecha_llegada < $fecha_salida || ($fecha_llegada == $fecha_salida && $hora_llegada < $hora_salida)) {
        $_SESSION['error'] = "La fecha y hora de llegada deben ser posteriores a la fecha y hora de salida.";
        header("Location: /TravelEase/crud/viajes/viajes.php");
        exit();
    }

    if ($fecha_llegada > $fecha_actual) {
        $_SESSION['error'] = "La fecha de regreso no puede ser posterior a la fecha actual.";
        header("Location: /TravelEase/crud/viajes/viajes.php");
        exit();
    }

    $sql_ruta = "SELECT id_ruta FROM Transportes WHERE id_transporte = $id_transporte";
    $result_ruta = $conn->query($sql_ruta);
    $ruta = $result_ruta->fetch_assoc();
    $id_ruta = $ruta['id_ruta'];

    $sql = "INSERT INTO Viajes (id_transporte, id_ruta, fecha_salida, hora_salida, 
            fecha_llegada, hora_llegada, precio, estado) 
            VALUES ($id_transporte, $id_ruta, '$fecha_salida', '$hora_salida', 
            '$fecha_llegada', '$hora_llegada', $precio, '$estado')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Viaje agregado con éxito";
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
        <h2>Agregar Viaje</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_transporte" class="form-label">Transporte</label>
                <select class="form-select" id="id_transporte" name="id_transporte" required>
                    <option value="" disabled selected>Seleccione un transporte</option>
                    <?php while ($transporte = $result_transportes->fetch_assoc()): ?>
                        <option value="<?php echo $transporte['id_transporte']; ?>">
                    <?php echo $transporte['nombre_transporte'] . ' - Ruta: ' . $transporte['origen'] . ' a ' . $transporte['destino']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_salida" class="form-label">Fecha Salida</label>
                <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" required>
            </div>
            <div class="mb-3">
                <label for="hora_salida" class="form-label">Hora Salida</label>
                <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
            </div>
            <div class="mb-3">
                <label for="fecha_llegada" class="form-label">Fecha Llegada</label>
                <input type="date" class="form-control" id="fecha_llegada" name="fecha_llegada" required>
            </div>
            <div class="mb-3">
                <label for="hora_llegada" class="form-label">Hora Llegada</label>
                <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="" disabled selected>Seleccione un estado</option>
                    <option value="Programado">Programado</option>
                    <option value="Cancelado">Cancelado</option>
                    <option value="En curso">En curso</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Viaje</button>
            <a href="viajes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
