<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

$sql_rutas = "SELECT * FROM Rutas";
$result_rutas = $conn->query($sql_rutas);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_transporte = $_POST['tipo_transporte'];
    $nombre_transporte = $_POST['nombre_transporte'];
    $num_asientos = $_POST['num_asientos'];
    $id_ruta = $_POST['id_ruta']; 

    // Validaciones
    if (empty($nombre_transporte) || preg_match("/[0-9]/", $nombre_transporte)) {
        $_SESSION['error'] = "El nombre del transporte no puede contener números.";
        header("Location: /TravelEase/crud/transportes/transportes.php");
        exit();
    }

    // Inserción en la base de datos
    $sql = "INSERT INTO Transportes (tipo_transporte, nombre_transporte, num_asientos, id_ruta) 
            VALUES ('$tipo_transporte', '$nombre_transporte', $num_asientos, $id_ruta)";
    
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
                    <option value="" disabled selected>Seleccione un tipo</option>
                    <option value="Avión">Avión</option>
                    <option value="Tren">Tren</option>
                    <option value="Autobús">Autobús</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nombre_transporte" class="form-label">Nombre del Transporte</label>
                <input type="text" class="form-control" id="nombre_transporte" name="nombre_transporte" required pattern="^[A-Za-z\s]+$">
            </div>
            <div class="mb-3">
                <label for="id_ruta" class="form-label">Ruta</label>
                <select class="form-select" id="id_ruta" name="id_ruta" required>
                    <option value="">Seleccione una ruta</option>
                    <?php if ($result_rutas->num_rows > 0): ?>
                        <?php while ($row = $result_rutas->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_ruta']; ?>"><?php echo $row['nombre_ruta']; ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No hay rutas disponibles</option>
                    <?php endif; ?>
                </select>
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
