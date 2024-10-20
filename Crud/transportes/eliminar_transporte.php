<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transporte = $_POST['id_transporte'];

    $sql = "DELETE FROM Transportes WHERE id_transporte = $id_transporte";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Transporte actualizado con éxito.";
        header("Location: /TravelEase/crud/transportes/transportes.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/transportes/transportes.php");
        exit();
    }
}
?>
