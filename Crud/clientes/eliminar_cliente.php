<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];

    $sql = "DELETE FROM Clientes WHERE id_cliente = $id_cliente";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "cliente eliminado con éxito.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    }
}
?>
