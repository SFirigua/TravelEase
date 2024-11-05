<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $id_viaje = $_POST['id_viaje'];
    $asiento = $_POST['asiento'];
    $fecha_reserva = $_POST['fecha_reserva'];
    $reservas_vendidas = $_POST['reservas_vendidas'];
    $estado = $_POST['estado'];

    $sql = "INSERT INTO Reservas (id_cliente, id_viaje, fecha_reserva, reservas_vendidas, estado, asiento) 
            VALUES ('$id_cliente', '$id_viaje', '$fecha_reserva', '$reservas_vendidas', '$estado', '$asiento')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Reserva agregada con exito.";
        header("Location: /TravelEase/crud/reservas/reservas.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/reservas/reservas.php");
        exit();
    }
}

// Obtener clientes y viajes para los select
$clientes = $conn->query("SELECT * FROM Clientes");
$viajes = $conn->query("
    SELECT v.id_viaje, t.tipo_transporte, rt.origen, rt.destino
    FROM Viajes v
    JOIN Rutas rt ON v.id_ruta = rt.id_ruta
    JOIN Transportes t ON v.id_transporte = t.id_transporte
");
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Agregar Reserva</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente</label>
                <select id="id_cliente" name="id_cliente" class="form-select" required>
                    <option value="" disabled selected>Selecciona un cliente</option>
                    <?php while ($row = $clientes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="asiento">Asiento:</label>
                <select name="asiento" id="asiento" class="form-control">
                    <option value="" disabled selected>seleccione una clase</option>
                    <option value="Economica">Económica</option>
                    <option value="Premium">Premium</option>
                    <option value="Ejecutiva">Ejecutiva</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_viaje" class="form-label">Viaje</label>
                <select id="id_viaje" name="id_viaje" class="form-select" required>
                    <option value="" disabled selected>Selecciona un Viaje</option>
                    <?php while ($row = $viajes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_viaje']; ?>">
                            <?php echo $row['tipo_transporte'] . ' - ' . $row['origen'] . ' a ' . $row['destino']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_reserva" class="form-label">Fecha de Reserva</label>
                <input type="date" class="form-control" id="fecha_reserva" name="fecha_reserva" required>
            </div>
            <div class="mb-3">
                <label for="reservas_vendidas" class="form-label">N° Asientos</label>
                <input type="number" class="form-control" id="reservas_vendidas" name="reservas_vendidas" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="" disabled selected>Selecciona un estado</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Confirmada">Confirmada</option>
                    <option value="Cancelada">Cancelada</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Reserva</button>
            <a href="reservas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
