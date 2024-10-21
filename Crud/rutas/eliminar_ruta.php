<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ruta = $_POST['id_ruta'];

    // Verificar si la ruta está en uso en la tabla Transportes
    $checkTransportes = "SELECT COUNT(*) AS total FROM Transportes WHERE id_ruta = $id_ruta";
    $resultTransportes = $conn->query($checkTransportes);
    $rowTransportes = $resultTransportes->fetch_assoc();

    if ($rowTransportes['total'] > 0) {
        $_SESSION['error'] = "No puedes eliminar esta ruta porque está asociada a uno o más transportes.";
        header("Location: /TravelEase/crud/rutas/rutas.php");
        exit();
    } else {
        // Si no está en uso, procede a eliminar la ruta
        $sql = "DELETE FROM Rutas WHERE id_ruta = $id_ruta";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Ruta eliminada con éxito.";
            header("Location: /TravelEase/crud/rutas/rutas.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
            header("Location: /TravelEase/crud/rutas/rutas.php");
            exit();
        }
    }
}
?>
