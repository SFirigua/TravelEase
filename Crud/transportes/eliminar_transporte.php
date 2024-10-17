<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_transporte = $_GET['id'];

// Eliminar el transporte
$sql = "DELETE FROM Transportes WHERE id_transporte = $id_transporte";
if ($conn->query($sql) === TRUE) {
    echo "<div class='alert alert-success'>Transporte eliminado con éxito</div>";
} else {
    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

echo "<a href='transportes.php' class='btn btn-primary'>Volver a la lista de transportes</a>";
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
