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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte de Transportes');

// Añadir el logo de la empresa
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo de TravelEase');
$drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/TravelEase/assets/img/logo.jpeg');
$drawing->setHeight(100); // Ajusta la altura del logo
$drawing->setCoordinates('A1'); // Posición inicial del logo
$drawing->setWorksheet($sheet);

// Agregar el nombre de la empresa debajo del logo
$sheet->setCellValue('B1', 'TravelEase');
$sheet->getStyle('B1')->applyFromArray([
    'font' => [
        'bold' => true,
        'size' => 22,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
    ],
]);

// Espacio para el encabezado de la tabla
$sheet->setCellValue('A6', 'Reporte de Transportes:');
$sheet->getStyle('A6')->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['argb' => Color::COLOR_WHITE],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF0070C0'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
    ],
]);

// Encabezados de la tabla en Excel
$headers = ['Transporte', 'Marca', 'Capacidad Max.', 'Ruta', 'Origen', 'Destino', 'Duración'];
$column = 'A';

// Mover encabezados a la fila 7
foreach ($headers as $header) {
    $sheet->setCellValue($column . '7', $header);
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
        'startColor' => ['argb' => 'FF0070C0'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];

$sheet->getStyle('A7:G7')->applyFromArray($headerStyle);

// Obtener los transportes de la base de datos
$sql = "SELECT t.tipo_transporte, t.nombre_transporte, t.num_asientos, 
        r.nombre_ruta, r.origen, r.destino,
        TIME_FORMAT(t.tiempo_duracion, '%H:%i') AS tiempo_duracion
        FROM Transportes t 
        LEFT JOIN Rutas r ON t.id_ruta = r.id_ruta";
$result = $conn->query($sql);

// Llenar los datos en la hoja de cálculo
$rowNum = 8;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $row['tipo_transporte']);
        $sheet->setCellValue('B' . $rowNum, $row['nombre_transporte']);
        $sheet->setCellValue('C' . $rowNum, $row['num_asientos']);
        $sheet->setCellValue('D' . $rowNum, $row['nombre_ruta']);
        $sheet->setCellValue('E' . $rowNum, $row['origen']);
        $sheet->setCellValue('F' . $rowNum, $row['destino']);
        $sheet->setCellValue('G' . $rowNum, $row['tiempo_duracion']);

        // Centrar el contenido de cada celda
        $sheet->getStyle('A' . $rowNum . ':G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $rowNum++;
    }
}

// Aplicar borde a todas las celdas
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

// Aplicar borde a los encabezados
$sheet->getStyle('A7:G7')->applyFromArray($styleArray);

// Aplicar borde a todos los datos
$sheet->getStyle('A8:G' . ($rowNum - 1))->applyFromArray($styleArray);

// Ajustar el ancho de las columnas
foreach (range('A', 'G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Transportes.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
