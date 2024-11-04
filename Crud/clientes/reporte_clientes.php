<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
require($_SERVER['DOCUMENT_ROOT'] . '/TravelEase/libs/fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Reporte de Clientes'), 0, 1, 'C');
        $this->Ln(10);
        
        // Cabecera de la tabla
        $this->SetFont('Arial', 'B', 10);

        // Ancho de las columnas
        $ancho_columna = [30, 20, 40, 25, 50, 30]; // Modificar aquí para ajustar los anchos
        $num_columnas = count($ancho_columna);
        $ancho_total = array_sum($ancho_columna);

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        // Establecer la posición X
        $this->SetX($pos_x);

        // Crear las celdas de la cabecera
        $this->Cell($ancho_columna[0], 10, utf8_decode('Nombre'), 1);
        $this->Cell($ancho_columna[1], 10, utf8_decode('Género'), 1);
        $this->Cell($ancho_columna[2], 10, utf8_decode('N° Identificación'), 1);
        $this->Cell($ancho_columna[3], 10, utf8_decode('Celular'), 1);
        $this->Cell($ancho_columna[4], 10, utf8_decode('Email'), 1);
        $this->Cell($ancho_columna[5], 10, utf8_decode('Fecha Nac.'), 1);
        $this->Ln();
    }
    
    function TablaClientes($data) {
        $this->SetFont('Arial', '', 10);

        // Ancho de las columnas
        $ancho_columna = [30, 20, 40, 25, 50, 30];
        $num_columnas = count($ancho_columna);
        $ancho_total = array_sum($ancho_columna); 

        // Calcular la posición X para centrar
        $pos_x = (210 - $ancho_total) / 2;

        foreach ($data as $row) {
            // Establecer la posición X
            $this->SetX($pos_x);
            $this->Cell($ancho_columna[0], 10, utf8_decode($row['nombre']), 1);
            $this->Cell($ancho_columna[1], 10, utf8_decode($row['genero']), 1);
            $this->Cell($ancho_columna[2], 10, utf8_decode($row['numero_identificacion']), 1);
            $this->Cell($ancho_columna[3], 10, utf8_decode($row['numero_celular']), 1);
            $this->Cell($ancho_columna[4], 10, utf8_decode($row['email']), 1);
            $this->Cell($ancho_columna[5], 10, utf8_decode($row['fecha_nacimiento']), 1);
            $this->Ln();
        }
    }   

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    } 
}

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AddPage();

// Obtener datos de clientes
$sql = "SELECT * FROM Clientes";
$result = $conn->query($sql);
$data = $result->fetch_all(MYSQLI_ASSOC);

// Llamar a la función que llena la tabla
$pdf->TablaClientes($data);

// Salida del PDF
$pdf->Output();
?>
