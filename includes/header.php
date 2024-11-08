<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelEase - Sistema de Reservas</title>
    <link rel="icon" href="/TravelEase/assets/img/logo.jpeg" type="image/jpeg">
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/TravelEase/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container-fluid flex-grow-1">
        <div class="row">
            <!-- Barra de Navegación Vertical -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse vh-100">
                <div class="position-sticky">
                <h2 class="d-flex align-items-center py-4">
                        <img src="/TravelEase/assets/img/logo.jpeg" class="img-fluid rounded" alt="Logo" style="width: 30px; height: 30px; margin-right: 10px;">
                        TravelEase
                    </h2>                    
                    <ul class="nav flex-column">
                        <li class="nav-item mb-3">
                            <a class="nav-link active" aria-current="page" href="/TravelEase/index.php">
                                <i class="fas fa-home"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="/TravelEase/crud/reservas/reservas.php">
                                <i class="fas fa-ticket-alt"></i> Reservas
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="/TravelEase/crud/viajes/viajes.php">
                                <i class="fas fa-plane"></i> Viajes
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="/TravelEase/crud/transportes/transportes.php">
                                <i class="fas fa-bus-alt"></i> Transportes
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="/TravelEase/crud/clientes/clientes.php">
                                <i class="fas fa-users"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="/TravelEase/crud/rutas/rutas.php">
                                <i class="fas fa-map-marked-alt"></i> Rutas
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

