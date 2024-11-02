<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origen = $_POST['origen'];
    $destino = $_POST['destino'];
    $duracion = $_POST['duracion'];
    $frecuencia = $_POST['frecuencia'];
    $nombre_ruta = $_POST['nombre_ruta'];

    // Validaciones
    if (empty($nombre_ruta) || !preg_match("/^[a-zA-Z0-9 ]+$/", $nombre_ruta)) {
        $_SESSION['error'] = "El nombre de la ruta no es válido.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    }

    if (empty($origen) || !preg_match("/^[a-zA-Z ]+$/", $origen)) {
        $_SESSION['error'] = "El origen no es válido.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    }

    if (empty($destino) || !preg_match("/^[a-zA-Z ]+$/", $destino)) {
        $_SESSION['error'] = "El destino no es válido.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    }

    if (empty($duracion) || !preg_match("/^([0-9]{1,2}):([0-5][0-9])$/", $duracion)) {
        $_SESSION['error'] = "La duración debe ser en formato HH:MM.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    }

    if (empty($frecuencia) || !in_array($frecuencia, ['Diaria', 'Semanal', 'Mensual'])) {
        $_SESSION['error'] = "Frecuencia no válida.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    }

    // Inserción en la base de datos
    $sql = "INSERT INTO Rutas (nombre_ruta, origen, destino, duracion, frecuencia) 
            VALUES ('$nombre_ruta', '$origen', '$destino', '$duracion', '$frecuencia')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Ruta agregada con éxito.";
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
        <h2>Agregar Ruta</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre_ruta" class="form-label">Nombre Ruta</label>
                <input type="text" class="form-control" id="nombre_ruta" name="nombre_ruta" required pattern="^[a-zA-Z0-9 ]+$">
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen</label>
                <input type="text" class="form-control" id="origen" name="origen" required pattern="^[a-zA-Z ]+$">
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input type="text" class="form-control" id="destino" name="destino" required pattern="^[a-zA-Z ]+$">
            </div>
            <div class="mb-3">
                <label for="duracion" class="form-label">Duración (HH:MM)</label>
                <input type="text" class="form-control" id="duracion" name="duracion" placeholder="HH:MM" pattern="^([0-9]{1,2}):([0-5][0-9])$" required>
            </div>
            <div class="mb-3">
                <label for="frecuencia" class="form-label">Frecuencia</label>
                <select class="form-select" id="frecuencia" name="frecuencia" required>
                    <option value="" disabled selected>Selecciona la frecuencia de la ruta</option>
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
