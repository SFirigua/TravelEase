<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
require($_SERVER['DOCUMENT_ROOT'] . '/TravelEase/libs/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Reporte de Reservas'), 0, 1, 'C');
        $this->Ln(10);

        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 10);

        // Ancho de las columnas
        $ancho_columna = 30;
        $num_columnas = 5;
        $ancho_total = $ancho_columna * 5;

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        // Establecer la posición X
        $this->SetX($pos_x);

        // Crear las celdas de la cabecera
        $this->Cell($ancho_columna, 10, utf8_decode('Cliente'), 1);
        $this->Cell($ancho_columna, 10, 'Asiento', 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Origen'), 1);
        $this->Cell($ancho_columna, 10, utf8_decode('Destino'), 1);
        $this->Cell($ancho_columna, 10, 'Fecha Reserva', 1);
        $this->Ln();
    }

    function TablaReservas($data) {
        $this->SetFont('Arial', '', 10);
        
        // Ancho de las columnas
        $ancho_columna = 30;
        $num_columnas = 5;
        $ancho_total = $ancho_columna * 5; // Cambiar a 5 columnas

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        foreach ($data as $row) {
            // Establecer la posición X
            $this->SetX($pos_x);
            $this->Cell($ancho_columna, 10, utf8_decode($row['nombre']), 1);
            $this->Cell($ancho_columna, 10, $row['asiento'], 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['origen']), 1);
            $this->Cell($ancho_columna, 10, utf8_decode($row['destino']), 1);
            $this->Cell($ancho_columna, 10, $row['fecha_reserva'], 1);
            $this->Ln();
        }
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

// Obtener las reservas de la base de datos
$sql = "SELECT r.id_reserva, c.nombre, r.asiento, rt.origen, rt.destino, r.fecha_reserva, r.estado
        FROM Reservas r
        JOIN Clientes c ON r.id_cliente = c.id_cliente
        JOIN Viajes v ON r.id_viaje = v.id_viaje
        JOIN Rutas rt ON v.id_ruta = rt.id_ruta";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->TablaReservas($data);
$pdf->Output();
?>
