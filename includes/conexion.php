<?php
$servername = "bx6trrrrjktyornnblry-mysql.services.clever-cloud.com"; // Host de Clever Cloud
$username = "ufmp57l1pdelylfo"; // Usuario proporcionado por Clever Cloud
$password = "WvIWHTc06mSSi1P71lXB"; // Contraseña proporcionada por Clever Cloud
$dbname = "bx6trrrrjktyornnblry"; // Nombre de la base de datos proporcionada por Clever Cloud
$port = 3306; // Puerto de conexión

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
