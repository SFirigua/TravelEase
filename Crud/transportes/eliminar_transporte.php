<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transporte = $_POST['id_transporte'];

    // Verificar si el transporte está en uso en la tabla Viajes
    $checkViajes = "SELECT COUNT(*) AS total FROM Viajes WHERE id_transporte = $id_transporte";
    $resultViajes = $conn->query($checkViajes);
    $rowViajes = $resultViajes->fetch_assoc();

    // Verificar si el transporte está en uso en la tabla Transportes (si pertenece a una Ruta)
    $checkRutas = "SELECT COUNT(*) AS total FROM Transportes WHERE id_transporte = $id_transporte AND id_ruta IS NOT NULL";
    $resultRutas = $conn->query($checkRutas);
    $rowRutas = $resultRutas->fetch_assoc();

    if ($rowViajes['total'] > 0) {
        $_SESSION['error'] = "No puedes eliminar este transporte porque está asociado a uno o más viajes.";
        header("Location: /TravelEase/crud/transportes/transportes.php");
        exit();
    } elseif ($rowRutas['total'] > 0) {
        $_SESSION['error'] = "No puedes eliminar este transporte porque está asociado a una ruta.";
        header("Location: /TravelEase/crud/transportes/transportes.php");
        exit();
    } else {
        // Si no está en uso, procede a eliminar el transporte
        $sql = "DELETE FROM Transportes WHERE id_transporte = $id_transporte";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Transporte eliminado con éxito.";
            header("Location: /TravelEase/crud/transportes/transportes.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
            header("Location: /TravelEase/crud/transportes/transportes.php");
            exit();
        }
    }
}
?>
