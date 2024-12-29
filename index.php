<?php
session_start();
include './TravelEase/includes/header.php';
include './TravelEase/includes/conexion.php';

// Obtener número total de clientes y desglose por tipo de identificación
$stmtClientes = $conn->query("SELECT COUNT(*) AS total_clientes,
    SUM(tipo_identificacion = 'Cédula de Ciudadanía') AS total_cedula_ciudadania,
    SUM(tipo_identificacion = 'Cédula de Extranjería') AS total_cedula_extranjeria
    FROM Clientes");
$clientesData = $stmtClientes->fetch_assoc();

// Obtener número total de transportes y desglose por tipo
$stmtTransportes = $conn->query("SELECT COUNT(*) AS total_transportes,
    SUM(tipo_transporte = 'Avión') AS total_avion,
    SUM(tipo_transporte = 'Tren') AS total_tren,
    SUM(tipo_transporte = 'Autobús') AS total_autobus
    FROM Transportes");
$transportesData = $stmtTransportes->fetch_assoc();

// Obtener número total de viajes y desglose por estado
$stmtViajes = $conn->query("SELECT COUNT(*) AS total_viajes,
    SUM(estado = 'Programado') AS total_programado,
    SUM(estado = 'Cancelado') AS total_cancelado,
    SUM(estado = 'En curso') AS total_en_curso,
    SUM(estado = 'Finalizado') AS total_finalizado
    FROM Viajes");
$viajesData = $stmtViajes->fetch_assoc();

// Obtener los 3 viajes con más reservas (de menor a mayor)
$stmtTopViajes = $conn->query("SELECT T.tipo_transporte, R.origen, R.destino, SUM(RS.reservas_vendidas) AS total_reservas
    FROM Viajes V
    JOIN Transportes T ON V.id_transporte = T.id_transporte
    JOIN Rutas R ON V.id_ruta = R.id_ruta
    JOIN Reservas RS ON V.id_viaje = RS.id_viaje
    GROUP BY V.id_viaje
    ORDER BY total_reservas DESC
    LIMIT 3");

$topViajes = [];
while ($row = $stmtTopViajes->fetch_assoc()) {
    $topViajes[] = $row;
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-header">
                        <h2>¡Bienvenido a TravelEase!</h2>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Gestión de Reservas de Viajes</h5>
                        <p class="card-text">Con TravelEase, puedes gestionar fácilmente tus reservas de viajes, desde la selección de transporte hasta la confirmación de tu reserva. Explora nuestros servicios y comienza tu aventura hoy mismo.</p>
                        <a href="/TravelEase/crud/reservas/reservas.php" class="btn btn-primary">Ver Reservas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Tarjeta para Clientes -->
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-header">Clientes</div>
                    <div class="card-body"> 
                        <h5 class="card-title">Total Clientes: <?= $clientesData['total_clientes'] ?></h5>
                        <p>Cédula de Ciudadanía: <?= $clientesData['total_cedula_ciudadania'] ?></p>
                        <p>Cédula de Extranjería: <?= $clientesData['total_cedula_extranjeria'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para Transportes -->
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-header">Transportes</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Transportes: <?= $transportesData['total_transportes'] ?></h5>
                        <p>Avión: <?= $transportesData['total_avion'] ?></p>
                        <p>Tren: <?= $transportesData['total_tren'] ?></p>
                        <p>Autobús: <?= $transportesData['total_autobus'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta para Viajes -->
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-header">Viajes</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Viajes: <?= $viajesData['total_viajes'] ?></h5>
                        <p>Programado: <?= $viajesData['total_programado'] ?></p>
                        <p>Cancelado: <?= $viajesData['total_cancelado'] ?></p>
                        <p>En curso: <?= $viajesData['total_en_curso'] ?></p>
                        <p>Finalizado: <?= $viajesData['total_finalizado'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Top 3 Viajes con más Reservas -->
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-header">Top 3 Viajes con más Reservas</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($topViajes as $viaje): ?>
                                <li class="list-group-item">
                                    <?= "{$viaje['tipo_transporte']}, {$viaje['origen']} - {$viaje['destino']}: {$viaje['total_reservas']} reservas" ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>

