<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

$id_reserva = $_GET['id'];

$sql = "DELETE FROM Reservas WHERE id_reserva = $id_reserva";

if ($conn->query($sql) === TRUE) {
    header('Location: reservas.php?msg=Reserva eliminada con éxito');
} else {
    echo "Error al eliminar reserva: " . $conn->error;
}
?>
