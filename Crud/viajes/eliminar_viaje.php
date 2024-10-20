<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_viaje = $_POST['id_viaje'];

    // Verificar si hay reservas asociadas al viaje
    $check_reservas_sql = "SELECT COUNT(*) as count FROM Reservas WHERE id_viaje = $id_viaje";
    $result = $conn->query($check_reservas_sql);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Si hay reservas, se muestra un mensaje
        $_SESSION['error'] = "No se puede eliminar el viaje porque hay reservas asociadas.";
        header("Location: /TravelEase/crud/viajes/viajes.php");
        exit();
        } else {
        $sql = "DELETE FROM Viajes WHERE id_viaje = $id_viaje";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Viaje eliminado con éxito.";
            header("Location: /TravelEase/crud/viajes/viajes.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
            header("Location: /TravelEase/crud/viajes/viajes.php");
            exit();
        }
    }
}
?>
