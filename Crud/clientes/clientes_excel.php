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
$sheet->setTitle('Reporte de Clientes');

// Encabezados de la tabla en Excel
$headers = ['Nombre', 'Primer Apellido', 'Segundo Apellido', 'Género', 'Tipo de Identificación', 
    'Número de Identificación', 'Número Celular', 'Correo Electrónico', 'Residencia', 'Fecha de Nacimiento', ];
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
        'startColor' => ['argb' => 'FF0070C0'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];

$sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

// Obtener los clientes de la base de datos
$sql = "SELECT id_cliente, nombre, primer_apellido, segundo_apellido, tipo_identificacion, numero_identificacion, numero_celular, email, direccion, fecha_nacimiento, genero FROM Clientes";
$result = $conn->query($sql);

// Llenar los datos en la hoja de cálculo
$rowNum = 2;
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
$sheet->getStyle('A1:J1')->applyFromArray($styleArray);

// Aplicar borde a todos los datos
$sheet->getStyle('A2:J' . ($rowNum - 1))->applyFromArray($styleArray);

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
