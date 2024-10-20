<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ruta = $_POST['id_ruta'];

    $sql = "DELETE FROM Rutas WHERE id_ruta = $id_ruta";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Ruta eliminada con éxito.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();    }
}
?>
