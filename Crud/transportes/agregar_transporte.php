<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_transporte = $_POST['tipo_transporte'];
    $nombre_transporte = $_POST['nombre_transporte'];
    $num_asientos = $_POST['num_asientos'];

    $sql = "INSERT INTO Transportes (tipo_transporte, nombre_transporte, num_asientos) 
            VALUES ('$tipo_transporte', '$nombre_transporte', $num_asientos)";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Transporte agregado con éxito.";
        header("Location: /TravelEase/crud/transportes/transportes.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/transportes/transportes.php");
        exit();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Agregar Transporte</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="tipo_transporte" class="form-label">Tipo de Transporte</label>
                <select class="form-select" id="tipo_transporte" name="tipo_transporte" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="Avión">Avión</option>
                    <option value="Tren">Tren</option>
                    <option value="Autobús">Autobús</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nombre_transporte" class="form-label">Nombre del Transporte</label>
                <input type="text" class="form-control" id="nombre_transporte" name="nombre_transporte" required>
            </div>
            <div class="mb-3">
                <label for="num_asientos" class="form-label">Número de Asientos</label>
                <input type="number" class="form-control" id="num_asientos" name="num_asientos" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Transporte</button>
            <a href="transportes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
