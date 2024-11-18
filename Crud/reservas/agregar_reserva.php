<?php
session_start();
date_default_timezone_set('America/Bogota');
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];
    $id_viaje = $_POST['id_viaje'];
    $asiento = $_POST['asiento'];
    $fecha_reserva = $_POST['fecha_reserva'];
    $reservas_vendidas = $_POST['reservas_vendidas'];
    $estado = $_POST['estado'];

    // Consultar la capacidad máxima del transporte y las reservas actuales
    $query = "
        SELECT t.num_asientos AS capacidad_maxima, 
               SUM(r.reservas_vendidas) AS reservas_actuales 
        FROM Transportes t
        JOIN Viajes v ON t.id_transporte = v.id_transporte
        LEFT JOIN Reservas r ON r.id_viaje = v.id_viaje
        WHERE v.id_viaje = '$id_viaje'
        GROUP BY t.num_asientos";
        
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    
    if ($row) {
        $capacidad_maxima = $row['capacidad_maxima'];
        $reservas_actuales = $row['reservas_actuales'] ?? 0; // Puede ser null si no hay reservas
        $asientos_disponibles = $capacidad_maxima - $reservas_actuales;
        
        // Validar si el número de asientos solicitados supera la capacidad
        if ($reservas_vendidas > $asientos_disponibles) {
            $_SESSION['error'] = "Error: Solo quedan $asientos_disponibles asientos disponibles.";
            header("Location: /TravelEase/crud/reservas/agregar_reserva.php");
            exit();
        }
    }

    // Validar el estado del viaje
    $consulta_estado = "SELECT estado FROM Viajes WHERE id_viaje = '$id_viaje'";
    $result_estado = $conn->query($consulta_estado);
    $estado_viaje = $result_estado->fetch_assoc()['estado'] ?? null;

    if ($estado_viaje === 'En curso' || $estado_viaje === 'Finalizado') {
        $_SESSION['error'] = "Error: No se pueden realizar reservas en viajes con estado '$estado_viaje'.";
        header("Location: /TravelEase/crud/reservas/agregar_reserva.php");
        exit();
    }

    // Insertar la nueva reserva si pasa las validación
    $sql = "INSERT INTO Reservas (id_cliente, id_viaje, reservas_vendidas, estado, asiento) 
            VALUES ('$id_cliente', '$id_viaje', '$reservas_vendidas', '$estado', '$asiento')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Reserva agregada con éxito.";
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
    SELECT v.id_viaje, t.tipo_transporte, rt.origen, rt.destino,
                DATE_FORMAT(V.fecha_salida, '%d-%m-%Y') AS fecha_salida,
                TIME_FORMAT(V.hora_salida, '%H:%i') AS hora_salida,
                DATE_FORMAT(V.fecha_llegada, '%d-%m-%Y') AS fecha_llegada,
                TIME_FORMAT(V.hora_llegada, '%H:%i') AS hora_llegada
    FROM Viajes v
    JOIN Rutas rt ON v.id_ruta = rt.id_ruta
    JOIN Transportes t ON v.id_transporte = t.id_transporte
");
?>


<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Agregar Reserva</h2>

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
            <option value="" disabled selected>Selecciona un cliente</option>
            <?php while ($row = $clientes->fetch_assoc()): ?>
                <option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="asiento">Asiento:</label>
        <select name="asiento" id="asiento" class="form-control" required>
            <option value="" disabled selected>Seleccione una clase</option>
            <option value="Economica">Económica</option>
            <option value="Premium">Premium</option>
            <option value="Ejecutiva">Ejecutiva</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="id_viaje" class="form-label">Viaje</label>
        <select id="id_viaje" name="id_viaje" class="form-select" required>
            <option value="" disabled selected>Selecciona un viaje</option>
            <?php while ($row = $viajes->fetch_assoc()): ?>
                <option value="<?php echo $row['id_viaje']; ?>">
                    <?php echo $row['tipo_transporte'] . ' - ' . $row['origen'] . ' a ' . $row['destino'] 
                    . ' - ' . $row['fecha_salida'] . ' ' . $row['hora_salida'] . ' : ' . $row['fecha_llegada'] . ' ' . $row['hora_llegada']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="reservas_vendidas" class="form-label">N° Asientos</label>
        <input type="number" class="form-control" id="reservas_vendidas" name="reservas_vendidas" required 
               min="1" max="10" 
               title="El número de asientos debe estar entre 1 y 10.">
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
