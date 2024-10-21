<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_POST['id_cliente'];

    // Verificar si el cliente está en uso en la tabla Reservas
    $checkReservas = "SELECT COUNT(*) AS total FROM Reservas WHERE id_cliente = $id_cliente";
    $resultReservas = $conn->query($checkReservas);
    $rowReservas = $resultReservas->fetch_assoc();

    if ($rowReservas['total'] > 0) {
        $_SESSION['error'] = "No puedes eliminar este cliente porque está asociado a una o más reservas.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    } else {
        // Si no está en uso, procede a eliminar el cliente
        $sql = "DELETE FROM Clientes WHERE id_cliente = $id_cliente";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Cliente eliminado con éxito.";
            header("Location: /TravelEase/crud/clientes/clientes.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
            header("Location: /TravelEase/crud/clientes/clientes.php");
            exit();
        }
    }
}
?>
