<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
require ($_SERVER['DOCUMENT_ROOT'] . '/TravelEase/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte de Rutas');

// Encabezados de la tabla en Excel
$headers = ['Nombre Ruta', 'Origen', 'Destino', 'Duración', 'Frecuencia'];
$column = 'A';

foreach ($headers as $header) {
    $sheet->setCellValue($column . '1', $header);
    $column++;
}

// Aplicar estilo a los encabezados
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => Color::COLOR_WHITE],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF0070C0'], // Color de fondo
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];

$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Obtener las rutas de la base de datos
$sql = "SELECT nombre_ruta, origen, destino, TIME_FORMAT(duracion, '%H:%i') AS duracion, frecuencia 
        FROM Rutas";
$result = $conn->query($sql);

// Llenar los datos en la hoja de cálculo
$rowNum = 2;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $row['nombre_ruta']);
        $sheet->setCellValue('B' . $rowNum, $row['origen']);
        $sheet->setCellValue('C' . $rowNum, $row['destino']);
        $sheet->setCellValue('D' . $rowNum, $row['duracion']);
        $sheet->setCellValue('E' . $rowNum, $row['frecuencia']);
        
        // Centrar el contenido de cada celda
        $sheet->getStyle('A' . $rowNum . ':E' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowNum++;
    }
}

// Aplicar borde a todas las celdas
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Color del borde
        ],
    ],
];

// Aplicar borde a los encabezados
$sheet->getStyle('A1:E1')->applyFromArray($styleArray);

// Aplicar borde a todos los datos
$sheet->getStyle('A2:E' . ($rowNum - 1))->applyFromArray($styleArray);

// Ajustar el ancho de las columnas
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Rutas.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>