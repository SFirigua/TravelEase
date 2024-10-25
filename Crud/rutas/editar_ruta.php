<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_ruta = $_GET['id'];

// Obtener la ruta existente
$sql = "SELECT * FROM Rutas WHERE id_ruta = $id_ruta";
$ruta = $conn->query($sql)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_ruta = $_POST['nombre_ruta'];
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];
    $duracion = $_POST['duracion'];
    $frecuencia = $_POST['frecuencia'];

    $sql = "UPDATE Rutas SET 
            nombre_ruta='$nombre_ruta', 
            origen='$origen', 
            destino='$destino', 
            duracion='$duracion', 
            frecuencia='$frecuencia' 
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
                <label for="nombre_ruta" class="form-label">Nombre Ruta</label>
                <input type="text" class="form-control" id="nombre_ruta" name="nombre_ruta" value="<?php echo htmlspecialchars($ruta['nombre_ruta']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen</label>
                <input type="text" class="form-control" id="origen" name="origen" value="<?php echo htmlspecialchars($ruta['origen']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input type="text" class="form-control" id="destino" name="destino" value="<?php echo htmlspecialchars($ruta['destino']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="duracion" class="form-label">Duración</label>
                <input type="text" class="form-control" id="duracion" name="duracion" placeholder="HH:MM" pattern="^([0-9]{1,2}):([0-5][0-9])$" value="<?php echo htmlspecialchars($ruta['duracion']); ?>" required>
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
