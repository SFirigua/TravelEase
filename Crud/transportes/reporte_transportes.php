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
        $this->Cell(0, 20, utf8_decode('Reporte de Transportes'), 0, 1, 'C');
        $this->Ln(5);

        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 10);
        
        // Ancho de las columnas
        $ancho_columna = 25;
        $num_columnas = 7;  
        $ancho_total = $ancho_columna * $num_columnas;

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        // Establecer la posición X
        $this->SetX($pos_x);

        // Crear las celdas
        $this->Cell($ancho_columna, 10, utf8_decode('Transporte'), 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Marca'), 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Cap. Max.'), 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Duración V.'), 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Ruta'), 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Origen'), 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Destino'), 1);
        $this->Ln();
    }

    function TablaTransportes($data) {
        $this->SetFont('Arial', '', 10);
        
        // Ancho de las columnas
        $ancho_columna = 25;
        $num_columnas = 7;  
        $ancho_total = $ancho_columna * $num_columnas;

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        foreach ($data as $row) {
            // Establecer la posición X
            $this->SetX($pos_x);
            $this->Cell($ancho_columna, 10, utf8_decode($row['tipo_transporte']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['nombre_transporte']), 1);
            $this->Cell($ancho_columna, 10, $row['num_asientos'], 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['tiempo_duracion']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['nombre_ruta']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['origen']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['destino']), 1);
            $this->Ln();
        }
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

// Obtener los transportes de la base de datos
$sql = "SELECT t.*, r.nombre_ruta, r.origen, r.destino,
        TIME_FORMAT(t.tiempo_duracion, '%H:%i') AS tiempo_duracion
        FROM Transportes t 
        LEFT JOIN Rutas r ON t.id_ruta = r.id_ruta";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->TablaTransportes($data);
$pdf->Output();
?>
