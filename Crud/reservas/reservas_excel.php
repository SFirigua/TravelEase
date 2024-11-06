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

// Obtener los viajes y las reservas de la base de datos
$sql = "SELECT v.id_viaje, rt.origen, rt.destino, t.tipo_transporte, t.nombre_transporte
        FROM Viajes v
        JOIN Rutas rt ON v.id_ruta = rt.id_ruta
        JOIN Transportes t ON v.id_transporte = t.id_transporte";
$result_viajes = $conn->query($sql);

// Llenar los datos en la hoja de cálculo
$rowNum = 1; // Cambiar a 1 para incluir encabezados después
if ($result_viajes->num_rows > 0) {
    while ($row_viaje = $result_viajes->fetch_assoc()) {
        // Obtener las reservas para este viaje
        $id_viaje = $row_viaje['id_viaje'];
        $sql_reservas = "SELECT c.nombre AS cliente, r.asiento, 
        DATE_FORMAT(r.fecha_reserva, '%Y-%m-%d %H:%i') as fecha_reserva, 
         r.estado, r.reservas_vendidas
                        FROM Reservas r
                        JOIN Clientes c ON r.id_cliente = c.id_cliente
                        WHERE r.id_viaje = $id_viaje";
        $result_reservas = $conn->query($sql_reservas);
        
        // Encabezado del viaje
        $viajeTexto = 'Viaje: ' . $row_viaje['origen'] . ' a ' . $row_viaje['destino'] . ' - Transporte: ' . $row_viaje['tipo_transporte'] . ' ' . $row_viaje['nombre_transporte'];
        $sheet->setCellValue('A' . $rowNum, $viajeTexto);
        $sheet->mergeCells('A' . $rowNum . ':E' . $rowNum); // Merge cells for the travel header
        $sheet->getStyle('A' . $rowNum)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'font' => ['bold' => true]
        ]);
        $rowNum++;

        // Definir los encabezados de la tabla en Excel
        $headers = ['Cliente', 'Asiento', 'N° Asientos', 'Fecha Reserva', 'Estado'];
        $column = 'A';

        foreach ($headers as $header) {
            $sheet->setCellValue($column . $rowNum, $header);
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

        $sheet->getStyle('A' . $rowNum . ':E' . $rowNum)->applyFromArray($headerStyle);
        $rowNum++;

        // Si hay reservas
        if ($result_reservas->num_rows > 0) {
            while ($row_reserva = $result_reservas->fetch_assoc()) {
                $sheet->setCellValue('A' . $rowNum, $row_reserva['cliente']);
                $sheet->setCellValue('B' . $rowNum, $row_reserva['asiento']);
                $sheet->setCellValue('C' . $rowNum, $row_reserva['reservas_vendidas']);
                $sheet->setCellValue('D' . $rowNum, $row_reserva['fecha_reserva']);
                $sheet->setCellValue('E' . $rowNum, $row_reserva['estado']);

                // Aplicar alineación centrada a cada fila de datos
                $sheet->getStyle('B' . $rowNum . ':E' . $rowNum)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $rowNum++;
            }
        } else {
            // Mensaje si no hay reservas
            $sheet->setCellValue('A' . $rowNum, 'No se han hecho reservas para este viaje.');
            $sheet->mergeCells('A' . $rowNum . ':E' . $rowNum);
            $sheet->getStyle('A' . $rowNum)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
            $rowNum++;
        }
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

$sheet->getStyle('A1:E' . ($rowNum - 1))->applyFromArray($styleArray);

// Ajustar automáticamente el ancho de las columnas
foreach (range('A', 'E') as $columnID) {
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
