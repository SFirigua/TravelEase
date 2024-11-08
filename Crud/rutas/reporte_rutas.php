<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
require($_SERVER['DOCUMENT_ROOT'] . '/TravelEase/libs/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->Image($_SERVER['DOCUMENT_ROOT'] . '/TravelEase/assets/img/logo.jpeg', 10, 8, 40);  
               
        $this->SetFont('Arial', 'B', 18);
        $this->SetXY(55, 10);
        $this->Cell(50, 10, utf8_decode('TravelEase'), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 20, utf8_decode('Reporte de Rutas'), 0, 1, 'C');
        $this->Ln(5);

        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 10);
        
        // Ancho de las columnas
        $ancho_columna = 30;
        $num_columnas = 5;
        $ancho_total = $ancho_columna * $num_columnas;

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        // Establecer la posición X
        $this->SetX($pos_x);

        // Crear las celdas
        $this->Cell($ancho_columna, 10, utf8_decode('Nombre Ruta'), 1);
        $this->Cell($ancho_columna, 10, 'Origen', 1);
        $this->Cell($ancho_columna, 10, 'Destino', 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Duración'), 1);
        $this->Cell($ancho_columna, 10, 'Frecuencia', 1);
        $this->Ln();
    }

    function TablaRutas($data) {
        $this->SetFont('Arial', '', 10);
        
        // Ancho de las columnas
        $ancho_columna = 30;
        $num_columnas = 5;
        $ancho_total = $ancho_columna * $num_columnas;

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        foreach ($data as $row) {
            // Establecer la posición X
            $this->SetX($pos_x);
            $this->Cell($ancho_columna, 10, utf8_decode($row['nombre_ruta']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['origen']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['destino']), 1);
            $this->Cell($ancho_columna, 10, $row['duracion'], 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['frecuencia']), 1);
            $this->Ln();
        }
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

// Obtener las rutas de la base de datos
$sql = "SELECT nombre_ruta, origen, destino, TIME_FORMAT(duracion, '%H:%i') AS duracion, frecuencia FROM Rutas";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->TablaRutas($data);
$pdf->Output();
?>
