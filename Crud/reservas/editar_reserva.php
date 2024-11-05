<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_reserva = $_GET['id'];

// Obtener la reserva existente
$sql = "SELECT * FROM Reservas WHERE id_reserva = $id_reserva";
$reserva = $conn->query($sql)->fetch_assoc();

// Obtener clientes y viajes para los select
$clientes = $conn->query("SELECT * FROM Clientes");
$viajes = $conn->query("
    SELECT v.id_viaje, t.tipo_transporte, rt.origen, rt.destino
    FROM Viajes v
    JOIN Rutas rt ON v.id_ruta = rt.id_ruta
    JOIN Transportes t ON v.id_transporte = t.id_transporte
");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $id_viaje = $_POST['id_viaje'];
    $asiento = $_POST['asiento'];
    $fecha_reserva = $_POST['fecha_reserva'];
    $reservas_vendidas = $_POST['reservas_vendidas'];
    $estado = $_POST['estado'];

    $sql = "UPDATE Reservas SET id_cliente='$id_cliente', id_viaje='$id_viaje', fecha_reserva='$fecha_reserva', reservas_vendidas='$reservas_vendidas', estado='$estado', asiento='$asiento' 
            WHERE id_reserva = $id_reserva";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Reserva actualizada con exito.";
        header("Location: /TravelEase/crud/reservas/reservas.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/reservas/reservas.php");
        exit();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Editar Reserva</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente</label>
                <select id="id_cliente" name="id_cliente" class="form-select" required>
                    <option value="">Selecciona un Cliente</option>
                    <?php while ($row = $clientes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_cliente']; ?>" <?php echo ($row['id_cliente'] == $reserva['id_cliente']) ? 'selected' : ''; ?>>
                            <?php echo $row['nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_viaje" class="form-label">Viaje</label>
                <select id="id_viaje" name="id_viaje" class="form-select" required>
                    <option value="" disabled selected>Selecciona un Viaje</option>
                    <?php while ($row = $viajes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_viaje']; ?>" <?php echo ($row['id_viaje'] == $reserva['id_viaje']) ? 'selected' : ''; ?>>
                            <?php echo $row['tipo_transporte'] . ' - ' . $row['origen'] . ' a ' . $row['destino']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="asiento" class="form-label">Asiento</label>
                <select id="asiento" name="asiento" class="form-select" required>
                    <option value="Economica" <?php echo ($reserva['asiento'] == 'Economica') ? 'selected' : ''; ?>>Económica</option>
                    <option value="Premium" <?php echo ($reserva['asiento'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                    <option value="Ejecutiva" <?php echo ($reserva['asiento'] == 'Ejecutiva') ? 'selected' : ''; ?>>Ejecutiva</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_reserva" class="form-label">Fecha de Reserva</label>
                <input type="date" class="form-control" id="fecha_reserva" name="fecha_reserva" value="<?php echo $reserva['fecha_reserva']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="reservas_vendidas" class="form-label">N° Asientos</label>
                <input type="number" class="form-control" id="reservas_vendidas" name="reservas_vendidas" value="<?php echo $reserva['reservas_vendidas']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="Pendiente" <?php echo ($reserva['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Confirmada" <?php echo ($reserva['estado'] == 'Confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                    <option value="Cancelada" <?php echo ($reserva['estado'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
            <a href="reservas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
