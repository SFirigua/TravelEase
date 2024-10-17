<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_viaje = $_GET['id'];

// Eliminar viaje
$sql = "DELETE FROM Viajes WHERE id_viaje = $id_viaje";
if ($conn->query($sql) === TRUE) {
    echo "<div class='alert alert-success'>Viaje eliminado con éxito</div>";
} else {
    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Eliminar Viaje</h2>
        <p>El viaje ha sido eliminado. <a href="viajes.php" class="btn btn-primary">Volver a la lista de viajes</a></p>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
