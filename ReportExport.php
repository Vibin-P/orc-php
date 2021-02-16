<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
// header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// header("Pragma: no-cache"); 
// header("Expires: 0");

require 'db_connection.php';
require 'vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

define("COMPANY_NAME", "ENERTEK ORC");

// POST DATA
// $data = json_decode(file_get_contents("php://input"));
if(isset($_GET['start_date']) 
    && isset($_GET['end_date']) && isset($_GET['type'])
    && !empty(trim($_GET['start_date'])) 
    && !empty(trim($_GET['end_date']))
    ){
        date_default_timezone_set("Asia/Calcutta");
        $report_type = $_GET['type'];
        $start_date = mysqli_real_escape_string($db_conn, trim($_GET['start_date']));
        $end_date = mysqli_real_escape_string($db_conn, trim($_GET['end_date']));
        $start_date_formatted = date_format(date_create($start_date), 'Y-m-d H:i:s');
        $end_date_formatted = date_format(date_create($end_date), 'Y-m-d H:i:s');
        $insertUser = mysqli_query($db_conn,"SELECT * FROM data WHERE date_Time BETWEEN '" . $start_date_formatted . "' AND  '" . $end_date_formatted . "' ORDER by data_id DESC");

        $count = mysqli_num_rows($insertUser);

        if($report_type == "excel") {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $writer = new Xlsx($spreadsheet);
            $row = 1;
            $sheet->getStyle('A1')->getFont()->setBold(true);
            $sheet->setCellValueByColumnAndRow(1, $row, COMPANY_NAME);
            $row++;
            $sheet->setCellValueByColumnAndRow(1, $row, 'From:');
            $sheet->setCellValueByColumnAndRow(2, $row, date_format(date_create($start_date), 'd-m-Y H:i:s A'));
            $sheet->setCellValueByColumnAndRow(4, $row, 'Print By');
            $sheet->setCellValueByColumnAndRow(5, $row, '');
            $row++;
            $sheet->setCellValueByColumnAndRow(1, $row, 'To:');
            $sheet->setCellValueByColumnAndRow(2, $row, date_format(date_create($end_date), 'd-m-Y H:i:s A'));
            $sheet->setCellValueByColumnAndRow(4, $row, 'Print Date');
            $sheet->setCellValueByColumnAndRow(5, $row, date("d-m-Y H:i:s A"));
            $row++;
            $sheet->getStyle('A4:E4')->getFont()->setBold(true);
            foreach(range('A4','E4') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            $sheet->setCellValueByColumnAndRow(1, $row, 'Turbine RPM');
            $sheet->setCellValueByColumnAndRow(2, $row, 'Temperature 1');
            $sheet->setCellValueByColumnAndRow(3, $row, 'Temperature 2');
            $sheet->setCellValueByColumnAndRow(4, $row, 'Temperature 9');
            $sheet->setCellValueByColumnAndRow(5, $row, 'Pressure 2');
            $row++;
            if($count > 0){
                while ($value  = mysqli_fetch_assoc($insertUser)) {
                    $sheet->setCellValueByColumnAndRow(1, $row, $value['RPM']);
                    $sheet->setCellValueByColumnAndRow(2, $row, $value['T1']);
                    $sheet->setCellValueByColumnAndRow(3, $row, $value['T2']);
                    $sheet->setCellValueByColumnAndRow(4, $row, $value['T9']);
                    $sheet->setCellValueByColumnAndRow(5, $row, $value['P2']);
                    $row++;
                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename=download.xls');
                $writer->save('php://output');
                exit;
            }
        } else if($report_type == "pdf") {
            $mpdf = new \Mpdf\Mpdf();
            $html = '
            <html>
            <head>
            <style>
            table, th, td{
                border-collapse: collapse;
                cellspacing: 0;
                padding: 10x;
                text-align: center;
            }
            </style>
            </head>
            <body>
            <b>'.COMPANY_NAME.'</b>
            <table style="width: 100%">
            <tr>
            <td>
            From:'.date_format(date_create($start_date), 'd-m-Y H:i:s A').'
            </td>
            <td>
            Print By:
            </td>
            </tr>
            <tr>
            <td>
            To:'.date_format(date_create($end_date), 'd-m-Y H:i:s A').'
            </td>
            <td>
            Print Date:'.date("d-m-Y H:i:s A").'
            </td>
            </tr>
            </table>
            <table style="width: 100%;">
            <thead>
            <tr>
            <th style="border: 1px solid">Turbine RPM</th>
            <th style="border: 1px solid">Temperature 1</th>
            <th style="border: 1px solid">Temperature 2</th>
            <th style="border: 1px solid">Temperature 9</th>
            <th style="border: 1px solid">Pressure 2</th>
            </tr>
            </thead>
            <tbody>';
            
            if($count > 0){
                while ($value  = mysqli_fetch_assoc($insertUser)) {
                    $html .= '<tr><td style="border: 1px solid">'.$value['RPM'].'</td>';
                    $html .= '<td style="border: 1px solid">'.$value['T1'].'</td>';
                    $html .= '<td style="border: 1px solid">'.$value['T2'].'</td>';
                    $html .= '<td style="border: 1px solid">'.$value['T9'].'</td>';
                    $html .= '<td style="border: 1px solid">'.$value['P2'].'</td>>/tr>';
                }
            }
           
            $html .= '</tbody>
            </table>
            </body>
            </html>
            ';

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=download.pdf');
            $mpdf->Output('php://output');
            exit;
        } else {
            echo json_encode(["Error"]);
        } 
    }