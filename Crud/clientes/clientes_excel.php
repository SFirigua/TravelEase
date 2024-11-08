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
$sheet->setTitle('Reporte de Clientes');

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
$sheet->setCellValue('A6', 'Reporte de Clientes:');
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
$headers = ['Nombre', 'Primer Apellido', 'Segundo Apellido', 'Género', 'Tipo de Identificación', 
    'Número de Identificación', 'Número Celular', 'Correo Electrónico', 'Residencia', 'Fecha de Nacimiento'];
$column = 'A';

// Mover encabezados a la fila 7
foreach ($headers as $header) {
    $sheet->setCellValue($column . '7', $header); // Cambié '5' por '7'
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

$sheet->getStyle('A7:J7')->applyFromArray($headerStyle); // Cambié '5' por '7'

// Obtener los clientes de la base de datos
$sql = "SELECT id_cliente, nombre, primer_apellido, segundo_apellido, tipo_identificacion, numero_identificacion, numero_celular, email, direccion, fecha_nacimiento, genero FROM Clientes";
$result = $conn->query($sql);

// Llenar los datos en la hoja de cálculo
$rowNum = 8; // Cambié el valor de 6 a 8 para iniciar debajo de los encabezados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $row['nombre']);
        $sheet->setCellValue('B' . $rowNum, $row['primer_apellido']);
        $sheet->setCellValue('C' . $rowNum, $row['segundo_apellido']);
        $sheet->setCellValue('D' . $rowNum, $row['genero']);
        $sheet->setCellValue('E' . $rowNum, $row['tipo_identificacion']);
        $sheet->setCellValue('F' . $rowNum, $row['numero_identificacion']);
        $sheet->setCellValue('G' . $rowNum, $row['numero_celular']);
        $sheet->setCellValue('H' . $rowNum, $row['email']);
        $sheet->setCellValue('I' . $rowNum, $row['direccion']);
        $sheet->setCellValue('J' . $rowNum, $row['fecha_nacimiento']);
        
        // Centrar el contenido de cada celda
        $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
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
$sheet->getStyle('A7:J7')->applyFromArray($styleArray); // Cambié '5' por '7'

// Aplicar borde a todos los datos
$sheet->getStyle('A8:J' . ($rowNum - 1))->applyFromArray($styleArray); // Cambié '6' por '8'

// Ajustar el ancho de las columnas
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Clientes.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
