<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
require($_SERVER['DOCUMENT_ROOT'] . '/TravelEase/libs/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Reporte de Viajes'), 0, 1, 'C');
        $this->Ln(10);

        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 10);
        
        // Ancho de las columnas
        $ancho_columna = 25;
        $num_columnas = 8;
        $ancho_total = $ancho_columna * $num_columnas;

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        // Establecer la posición X
        $this->SetX($pos_x);

        // Crear las celdas
        $this->Cell($ancho_columna, 10, 'Transporte', 1);
        $this->Cell($ancho_columna, 10, 'Origen', 1);
        $this->Cell($ancho_columna, 10, 'Destino', 1);
        $this->Cell($ancho_columna, 10, 'Fech. Salida', 1);
        $this->Cell($ancho_columna, 10, 'Hora Salida', 1);
        $this->Cell($ancho_columna, 10, 'Fech. Llegada', 1);
        $this->Cell($ancho_columna, 10, 'Hora Llegada', 1);
        $this->Cell($ancho_columna, 10, 'Precio', 1);
        $this->Ln();
    }

    function TablaViajes($data) {
        $this->SetFont('Arial', '', 10);
        
        // Ancho de las columnas
        $ancho_columna = 25;
        $num_columnas = 8;
        $ancho_total = $ancho_columna * $num_columnas;

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        foreach ($data as $row) {
            // Establecer la posición X
            $this->SetX($pos_x);
            $this->Cell($ancho_columna, 10, utf8_decode($row['nombre_transporte']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['origen']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['destino']), 1);
            $this->Cell($ancho_columna, 10, $row['fecha_salida'], 1);
            $this->Cell($ancho_columna, 10, $row['hora_salida'], 1);
            $this->Cell($ancho_columna, 10, $row['fecha_llegada'], 1);
            $this->Cell($ancho_columna, 10, $row['hora_llegada'], 1);
            $this->Cell($ancho_columna, 10, $row['precio'], 1);
            $this->Ln();
        }
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

// Obtener los viajes de la base de datos
$sql = "SELECT V.*, 
               T.nombre_transporte, 
               R.origen, 
               R.destino,
               DATE_FORMAT(V.fecha_salida, '%Y-%m-%d') AS fecha_salida,
               TIME_FORMAT(V.hora_salida, '%H:%i') AS hora_salida,
               DATE_FORMAT(V.fecha_llegada, '%Y-%m-%d') AS fecha_llegada,
               TIME_FORMAT(V.hora_llegada, '%H:%i') AS hora_llegada
        FROM Viajes V 
        JOIN Transportes T ON V.id_transporte = T.id_transporte
        JOIN Rutas R ON V.id_ruta = R.id_ruta";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->TablaViajes($data);
$pdf->Output();
?>
