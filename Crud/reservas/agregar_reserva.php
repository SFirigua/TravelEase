<?php
session_start();
date_default_timezone_set('America/Bogota');
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Recuperar datos del formulario y mensajes de error si existen
$form_data = $_SESSION['form_data'] ?? [];
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;

// Limpiar la sesión para evitar persistencia de datos en recargas
unset($_SESSION['form_data'], $_SESSION['error'], $_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar datos del formulario
    $id_cliente = $_POST['id_cliente'];
    $id_viaje = $_POST['id_viaje'];
    $asiento = $_POST['asiento'];
    $fecha_reserva = $_POST['fecha_reserva'] ?? null; // Por si hay campos opcionales
    $reservas_vendidas = $_POST['reservas_vendidas'];
    $estado = $_POST['estado'];

    // Guardar los datos en sesión para reutilizarlos en caso de error
    $_SESSION['form_data'] = $_POST;

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
        $reservas_actuales = $row['reservas_actuales'] ?? 0;
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

    // Insertar la nueva reserva si pasa las validaciones
    $sql = "INSERT INTO Reservas (id_cliente, id_viaje, reservas_vendidas, estado, asiento) 
            VALUES ('$id_cliente', '$id_viaje', '$reservas_vendidas', '$estado', '$asiento')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Reserva agregada con éxito.";
        header("Location: /TravelEase/crud/reservas/reservas.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/reservas/agregar_reserva.php");
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

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="id_cliente" class="form-label">Cliente</label>
                <select id="id_cliente" name="id_cliente" class="form-select" required>
                    <option value="" disabled <?php echo empty($form_data['id_cliente'] ?? '') ? 'selected' : ''; ?>>Selecciona un cliente</option>
                <?php while ($row = $clientes->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_cliente']; ?>" <?php echo ($form_data['id_cliente'] ?? '') == $row['id_cliente'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['nombre']); ?>
                    </option>
                <?php endwhile; ?>
                </select>   
            </div>
            <div class="mb-3">
                <label for="asiento">Asiento:</label>
                <select name="asiento" id="asiento" class="form-control" required>
                    <option value="" disabled <?php echo empty($form_data['asiento'] ?? '') ? 'selected' : ''; ?>>Seleccione una clase</option>
                    <option value="Economica" <?php echo ($form_data['asiento'] ?? '') == 'Economica' ? 'selected' : ''; ?>>Económica</option>
                    <option value="Premium" <?php echo ($form_data['asiento'] ?? '') == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                    <option value="Ejecutiva" <?php echo ($form_data['asiento'] ?? '') == 'Ejecutiva' ? 'selected' : ''; ?>>Ejecutiva</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_viaje" class="form-label">Viaje</label>
                <select id="id_viaje" name="id_viaje" class="form-select" required>
                    <option value="" disabled <?php echo empty($form_data['id_viaje'] ?? '') ? 'selected' : ''; ?>>Selecciona un viaje</option>
                    <?php while ($row = $viajes->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_viaje']; ?>" <?php echo ($form_data['id_viaje'] ?? '') == $row['id_viaje'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['tipo_transporte'] . ' - ' . $row['origen'] . ' a ' . $row['destino'] . ' - ' . $row['fecha_salida'] . ' ' . $row['hora_salida'] . ' : ' . $row['fecha_llegada'] . ' ' . $row['hora_llegada']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="reservas_vendidas" class="form-label">N° Asientos</label>
                <input type="number" class="form-control" id="reservas_vendidas" name="reservas_vendidas" required 
                min="1" max="10" 
                value="<?php echo htmlspecialchars($form_data['reservas_vendidas'] ?? ''); ?>" 
                title="El número de asientos debe estar entre 1 y 10.">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="" disabled <?php echo empty($form_data['estado'] ?? '') ? 'selected' : ''; ?>>Selecciona un estado</option>
                    <option value="Pendiente" <?php echo ($form_data['estado'] ?? '') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Confirmada" <?php echo ($form_data['estado'] ?? '') == 'Confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                    <option value="Cancelada" <?php echo ($form_data['estado'] ?? '') == 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/TravelEase/crud/reservas/reservas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
