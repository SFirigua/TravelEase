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
$sheet->setTitle('Reporte de Reservas');

// Encabezados de la tabla en Excel
$headers = ['Cliente', 'Asiento', 'Origen', 'Destino', 'Fecha Reserva', 'Estado'];
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

$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

// Obtener las reservas de la base de datos
$sql = "SELECT c.nombre AS cliente, r.asiento, rt.origen, rt.destino, r.fecha_reserva, r.estado
        FROM Reservas r
        JOIN Clientes c ON r.id_cliente = c.id_cliente
        JOIN Viajes v ON r.id_viaje = v.id_viaje
        JOIN Rutas rt ON v.id_ruta = rt.id_ruta";
$result = $conn->query($sql);

// Llenar los datos en la hoja de cálculo
$rowNum = 2;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNum, $row['cliente']);
        $sheet->setCellValue('B' . $rowNum, $row['asiento']);
        $sheet->setCellValue('C' . $rowNum, $row['origen']);
        $sheet->setCellValue('D' . $rowNum, $row['destino']);
        $sheet->setCellValue('E' . $rowNum, $row['fecha_reserva']);
        $sheet->setCellValue('F' . $rowNum, $row['estado']);
        
        // Aplicar alineación centrada a cada fila de datos
        $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        $rowNum++;
    }
}

// Aplicar bordes a todas las celdas con datos
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => Color::COLOR_BLACK],
        ],
    ],
];

$sheet->getStyle('A1:F' . ($rowNum - 1))->applyFromArray($styleArray);

// Ajustar automáticamente el ancho de las columnas
foreach (range('A', 'F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Configurar los encabezados para descargar el archivo como Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reservas.xlsx"');
header('Cache-Control: max-age=0');

// Escribir el archivo Excel en la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
