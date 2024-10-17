<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_ruta = $_GET['id'];

// Eliminar ruta
$sql = "DELETE FROM Rutas WHERE id_ruta = $id_ruta";
if ($conn->query($sql) === TRUE) {
    echo "<div class='alert alert-success'>Ruta eliminada con éxito</div>";
} else {
    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Eliminar Ruta</h2>
        <p>La ruta ha sido eliminada. <a href="rutas.php" class="btn btn-primary">Volver a la lista de rutas</a></p>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
