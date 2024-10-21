<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_transporte = $_GET['id'];

$sql_rutas = "SELECT * FROM Rutas";
$result_rutas = $conn->query($sql_rutas);

// Obtener el transporte existente
$sql = "SELECT * FROM Transportes WHERE id_transporte = $id_transporte";
$transporte = $conn->query($sql)->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_transporte = $_POST['tipo_transporte'];
    $nombre_transporte = $_POST['nombre_transporte'];
    $num_asientos = $_POST['num_asientos'];

    $sql = "UPDATE Transportes SET tipo_transporte='$tipo_transporte', nombre_transporte='$nombre_transporte', num_asientos=$num_asientos 
            WHERE id_transporte = $id_transporte";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Transporte actualizado con éxito.";
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
        <h2>Editar Transporte</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="tipo_transporte" class="form-label">Tipo de Transporte</label>
                <select class="form-select" id="tipo_transporte" name="tipo_transporte" required>
                    <option value="Avión" <?php echo ($transporte['tipo_transporte'] == 'Avión') ? 'selected' : ''; ?>>Avión</option>
                    <option value="Tren" <?php echo ($transporte['tipo_transporte'] == 'Tren') ? 'selected' : ''; ?>>Tren</option>
                    <option value="Autobús" <?php echo ($transporte['tipo_transporte'] == 'Autobús') ? 'selected' : ''; ?>>Autobús</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nombre_transporte" class="form-label">Nombre del Transporte</label>
                <input type="text" class="form-control" id="nombre_transporte" name="nombre_transporte" value="<?php echo $transporte['nombre_transporte']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="id_ruta" class="form-label">Ruta</label>
                <select class="form-select" id="id_ruta" name="id_ruta" required>
                        <option value="">Seleccione una ruta</option>
                        <?php if ($result_rutas->num_rows > 0): ?>
                        <?php while ($row = $result_rutas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_ruta']; ?>" <?php echo $row['id_ruta'] == $transporte['id_ruta'] ? 'selected' : ''; ?>>
                        <?php echo $row['nombre_ruta']; ?>
                        </option>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <option value="">No hay rutas disponibles</option>
                        <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="num_asientos" class="form-label">Número de Asientos</label>
                <input type="number" class="form-control" id="num_asientos" name="num_asientos" value="<?php echo $transporte['num_asientos']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Transporte</button>
            <a href="transportes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
