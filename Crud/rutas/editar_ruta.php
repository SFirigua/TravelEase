<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_ruta = $_GET['id'];

// Obtener la ruta existente
$sql = "SELECT * FROM Rutas WHERE id_ruta = $id_ruta";
$ruta = $conn->query($sql)->fetch_assoc();

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

    $sql = "UPDATE Rutas SET id_transporte='$id_transporte', origen='$origen', destino='$destino', 
            duracion='$duracion', paradas_intermedias='$paradas_intermedias', frecuencia='$frecuencia' 
            WHERE id_ruta = $id_ruta";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Ruta actualizada con éxito.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Editar Ruta</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="id_transporte" class="form-label">Transporte</label>
                <select class="form-select" id="id_transporte" name="id_transporte" required>
                    <?php while ($transporte = $result_transportes->fetch_assoc()): ?>
                        <option value="<?php echo $transporte['id_transporte']; ?>" 
                            <?php echo ($transporte['id_transporte'] == $ruta['id_transporte']) ? 'selected' : ''; ?>>
                            <?php echo $transporte['nombre_transporte']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen</label>
                <input type="text" class="form-control" id="origen" name="origen" value="<?php echo $ruta['origen']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input type="text" class="form-control" id="destino" name="destino" value="<?php echo $ruta['destino']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="duracion" class="form-label">Duración</label>
                <input type="time" class="form-control" id="duracion" name="duracion" value="<?php echo $ruta['duracion']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="paradas_intermedias" class="form-label">Paradas Intermedias</label>
                <input type="text" class="form-control" id="paradas_intermedias" name="paradas_intermedias" 
                    value="<?php echo $ruta['paradas_intermedias']; ?>">
            </div>
            <div class="mb-3">
                <label for="frecuencia" class="form-label">Frecuencia</label>
                <select class="form-select" id="frecuencia" name="frecuencia">
                    <option value="Diaria" <?php echo ($ruta['frecuencia'] == 'Diaria') ? 'selected' : ''; ?>>Diaria</option>
                    <option value="Semanal" <?php echo ($ruta['frecuencia'] == 'Semanal') ? 'selected' : ''; ?>>Semanal</option>
                    <option value="Mensual" <?php echo ($ruta['frecuencia'] == 'Mensual') ? 'selected' : ''; ?>>Mensual</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Ruta</button>
            <a href="rutas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
