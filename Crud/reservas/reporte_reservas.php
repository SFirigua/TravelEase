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
        $this->Cell(0, 20, utf8_decode('Reporte de Reservas'), 0, 1, 'C');
        $this->Ln(5);
    }

    function CabeceraReservas() {
        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 10, utf8_decode('Cliente'), 1 );
        $this->Cell(30, 10, 'Asiento', 1);
        $this->Cell(30, 10, 'N. Asientos', 1);
        $this->Cell(30, 10, 'Estado', 1);
        $this->Cell(35, 10, 'Fecha Reserva', 1);
        $this->Ln();
    }

    function TablaReservas($viajes) {
        $this->SetFont('Arial', '', 10);
        foreach ($viajes as $viaje) {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, utf8_decode('Viaje: ' . $viaje['origen'] . ' a ' . $viaje['destino'] . ' - Transporte: ' . $viaje['tipo_transporte'] . ' ' . $viaje['nombre_transporte']), 0, 1, 'L');
            $this->Ln(1);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, utf8_decode('Salida: ' . $viaje['fecha_salida'] . ' ' . $viaje['hora_salida'] . ' - Llegada: ' . $viaje['fecha_llegada'] . ' ' . $viaje['hora_llegada']), 0, 1, 'L');
            $this->Ln(5);
            
        // Verificar si hay reservas
        if (!empty($viaje['reservas'])) {
            // Mostrar la cabecera de la tabla
            $this->CabeceraReservas();

            // Mostrar los datos de las reservas
            foreach ($viaje['reservas'] as $reserva) {
                $this->Cell(30, 10, utf8_decode($reserva['nombre']), 1);
                $this->Cell(30, 10, $reserva['asiento'], 1);
                $this->Cell(30, 10, $reserva['reservas_vendidas'], 1);
                $this->Cell(30, 10, $reserva['estado'], 1);
                $this->Cell(35, 10, $reserva['fecha_reserva'], 1);
                $this->Ln();
            }
        } else {
            $this->Cell(0, 10, utf8_decode('No se han hecho reservas para este viaje.'), 0, 1, 'C');
        }
        $this->Ln(5);
    }
}
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }
}

// Obtener los viajes y las reservas de la base de datos
$sql = "SELECT v.id_viaje, rt.origen, rt.destino, t.tipo_transporte, t.nombre_transporte,
               DATE_FORMAT(v.fecha_salida, '%d-%m-%Y') AS fecha_salida,
               TIME_FORMAT(v.hora_salida, '%H:%i') AS hora_salida,
               DATE_FORMAT(v.fecha_llegada, '%d-%m-%Y') AS fecha_llegada,
               TIME_FORMAT(v.hora_llegada, '%H:%i') AS hora_llegada
        FROM Viajes v
        JOIN Rutas rt ON v.id_ruta = rt.id_ruta
        JOIN Transportes t ON v.id_transporte = t.id_transporte";
$result_viajes = $conn->query($sql);

$viajes = [];
if ($result_viajes->num_rows > 0) {
    while ($row_viaje = $result_viajes->fetch_assoc()) {
        // Obtener las reservas para este viaje
        $id_viaje = $row_viaje['id_viaje'];
        $sql_reservas = "SELECT r.id_reserva, c.nombre, r.asiento, 
        DATE_FORMAT(r.fecha_reserva, '%d-%m-%Y - %H:%i') as fecha_reserva, 
         r.reservas_vendidas, r.estado
                        FROM Reservas r
                        JOIN Clientes c ON r.id_cliente = c.id_cliente
                        WHERE r.id_viaje = $id_viaje";
        $result_reservas = $conn->query($sql_reservas);
        
        $reservas = [];
        if ($result_reservas->num_rows > 0) {
            while ($row_reserva = $result_reservas->fetch_assoc()) {
                $reservas[] = $row_reserva;
            }
        }

        $row_viaje['reservas'] = $reservas;
        $viajes[] = $row_viaje;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->TablaReservas($viajes);
$pdf->Output();
?>
