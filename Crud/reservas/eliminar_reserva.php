<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_reserva = $_POST['id_reserva'];

    $sql = "DELETE FROM Reservas WHERE id_reserva = $id_reserva";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Reserva eliminada con exito.";
        header("Location: /TravelEase/crud/reservas/reservas.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/reservas/reservas.php");
        exit();
    }
}
?>
