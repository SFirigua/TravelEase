<?php
session_start();
date_default_timezone_set('America/Bogota');
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_reserva = $_GET['id'];

// Obtener la reserva existente
$sql = "SELECT * FROM Reservas WHERE id_reserva = $id_reserva";
$reserva = $conn->query($sql)->fetch_assoc();

// Obtener clientes y viajes para los select
$clientes = $conn->query("SELECT * FROM Clientes");
$viajes = $conn->query("
    SELECT v.id_viaje, t.tipo_transporte, rt.origen, rt.destino,
                DATE_FORMAT(V.fecha_salida, '%d-%m-%Y') AS fecha_salida,
                TIME_FORMAT(V.hora_salida, '%H:%i') AS hora_salida,
                DATE_FORMAT(V.fecha_llegada, '%d-%m-%Y') AS fecha_llegada,
                TIME_FORMAT(V.hora_llegada, '%H:%i') AS hora_llegada
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

    $fecha_reserva = date('Y-m-d H:i:s');

    // Obtener capacidad máxima y reservas actuales
    $query = "
        SELECT t.num_asientos AS capacidad_maxima, 
               COALESCE(SUM(r.reservas_vendidas), 0) AS reservas_actuales 
        FROM Transportes t
        JOIN Viajes v ON t.id_transporte = v.id_transporte
        LEFT JOIN Reservas r ON r.id_viaje = v.id_viaje
        WHERE v.id_viaje = '$id_viaje'
        GROUP BY t.num_asientos";
    $result = $conn->query($query);
    $datos_viaje = $result->fetch_assoc();

    $capacidad_maxima = $datos_viaje['capacidad_maxima'];
    $reservas_actuales = $datos_viaje['reservas_actuales'];

    // Verificar si el cambio en la reserva excede la capacidad
    $nuevas_reservas_total = $reservas_actuales - $reserva['reservas_vendidas'] + $reservas_vendidas;
    if ($nuevas_reservas_total > $capacidad_maxima) {
        $_SESSION['error'] = "Error: La cantidad de asientos solicitada excede la capacidad máxima disponible. 
                             Quedan " . ($capacidad_maxima - $reservas_actuales + $reserva['reservas_vendidas']) . " asientos disponibles.";
        header("Location: /TravelEase/crud/reservas/editar_reserva.php?id=$id_reserva");
        exit();
    }

    // Validar el estado del viaje
    $consulta_estado = "SELECT estado FROM Viajes WHERE id_viaje = '$id_viaje'";
    $result_estado = $conn->query($consulta_estado);
    $estado_viaje = $result_estado->fetch_assoc()['estado'] ?? null;

    if ($estado_viaje === 'En curso' || $estado_viaje === 'Finalizado') {
        $_SESSION['error'] = "Error: No se pueden realizar modificaciones en reservas de viajes con estado '$estado_viaje'.";
        header("Location: /TravelEase/crud/reservas/editar_reserva.php?id=$id_reserva");
        exit();
    }

    // Actualizar la reserva si pasa las validaciones
    $sql = "UPDATE Reservas SET id_cliente='$id_cliente', id_viaje='$id_viaje', fecha_reserva='$fecha_reserva', reservas_vendidas='$reservas_vendidas', estado='$estado', asiento='$asiento' 
            WHERE id_reserva = $id_reserva";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Reserva actualizada con éxito.";
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
                        <?php echo $row['tipo_transporte'] . ' - ' . $row['origen'] . ' a ' . $row['destino'] 
                        . ' - ' . $row['fecha_salida'] . ' ' . $row['hora_salida'] . ' : ' . $row['fecha_llegada'] . ' ' . $row['hora_llegada']; ?>
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
