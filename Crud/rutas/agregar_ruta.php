<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

// Obtener transportes para el dropdown
$sql_transportes = "SELECT * FROM Transportes";
$result_transportes = $conn->query($sql_transportes);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transporte = $_POST['id_transporte'];
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];
    $duracion = $_POST['duracion'];
    $paradas_intermedias = $_POST['paradas_intermedias'];
    $frecuencia = $_POST['frecuencia'];

    $sql = "INSERT INTO Rutas (id_transporte, origen, destino, duracion, paradas_intermedias, frecuencia) 
            VALUES ($id_transporte, '$origen', '$destino', '$duracion', '$paradas_intermedias', '$frecuencia')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Ruta agregada con éxito</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Agregar Ruta</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_transporte" class="form-label">Transporte</label>
                <select class="form-select" id="id_transporte" name="id_transporte" required>
                    <option value="">Seleccione un transporte</option>
                    <?php while ($transporte = $result_transportes->fetch_assoc()): ?>
                        <option value="<?php echo $transporte['id_transporte']; ?>"><?php echo $transporte['nombre_transporte']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen</label>
                <input type="text" class="form-control" id="origen" name="origen" required>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input type="text" class="form-control" id="destino" name="destino" required>
            </div>
            <div class="mb-3">
                <label for="duracion" class="form-label">Duración</label>
                <input type="time" class="form-control" id="duracion" name="duracion" required>
            </div>
            <div class="mb-3">
                <label for="paradas_intermedias" class="form-label">Paradas Intermedias</label>
                <input type="text" class="form-control" id="paradas_intermedias" name="paradas_intermedias">
            </div>
            <div class="mb-3">
                <label for="frecuencia" class="form-label">Frecuencia</label>
                <select class="form-select" id="frecuencia" name="frecuencia">
                    <option value="Diaria">Diaria</option>
                    <option value="Semanal">Semanal</option>
                    <option value="Mensual">Mensual</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Ruta</button>
            <a href="rutas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
