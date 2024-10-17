<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_cliente = $_GET['id'];

// Eliminar el cliente
$sql = "DELETE FROM Clientes WHERE id_cliente = $id_cliente";
if ($conn->query($sql) === TRUE) {
    echo "<div class='alert alert-success'>Cliente eliminado con éxito</div>";
} else {
    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
}

echo "<a href='clientes.php' class='btn btn-primary'>Volver a la lista de clientes</a>";
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
