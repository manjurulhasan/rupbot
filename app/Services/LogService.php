<?php

namespace App\Services;

use App\Models\Log;
use Exception;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LogService
{
     public function downloadLogs($site_id, $filter)
     {
        try
        {
            $logs = $this->getLogs($site_id, $filter);
            $spreadsheet = new Spreadsheet();
            $sheet       = $spreadsheet->getActiveSheet();
            $styleGlobal = [
                'font' => [
                    'size' => 16,
                    'bold' => true,
                ],
            ];

            $allBorder = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                        'color'       => ['argb' => '000000'],
                    ],
                ],
            ];
            $spreadsheet->getDefaultStyle()->getFont()->setSize(14);
            $sheet->getStyle('A1:G1')->applyFromArray($styleGlobal);
            $sheet->getColumnDimension('A')->setWidth(35);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(10);
            $sheet->getColumnDimension('G')->setWidth(40);

            $sheet->setCellValue('A1', 'URL');
            $sheet->setCellValue('B1', 'Check At');
            $sheet->setCellValue('C1', 'Up At');
            $sheet->setCellValue('D1', 'Down At');
            $sheet->setCellValue('E1', 'Status');
            $sheet->setCellValue('F1', 'Code');
            $sheet->setCellValue('G1', 'Message');

            $row = 2;
            foreach ($logs as $key => $log){
                $status = null;
                $sheet->setCellValue('A'.$row, $log->url);
                $sheet->setCellValue('B'.$row, $log->last_check);
                $sheet->setCellValue('C'.$row, $log->up_at);
                $sheet->setCellValue('D'.$row, $log->down_at);
                if($log->status == 1){
                    $status = 'Up';
                }else{
                    $status = 'Down';
                }
                $sheet->setCellValue('E'.$row, $status);
                $sheet->setCellValue('F'.$row, $log->code);
                $sheet->setCellValue('G'.$row, $log->message);
                $row++;
            }

            $fileName = $site_id.'_logs.xlsx';
            $file_dir = 'public'.DIRECTORY_SEPARATOR.'excels'.DIRECTORY_SEPARATOR.$fileName;

            $headers = [
                'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition: attachment; filename="'.urlencode($fileName).'"',
            ];

            $writer  = IOFactory::createWriter($spreadsheet, 'Xlsx');
            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();
            Storage::disk('local')->put($file_dir, $content);

            return Storage::download($file_dir, $fileName, $headers);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
     }

    private function getLogs($site_id, $filter)
    {
        return Log::query()
            ->when($filter['dates'], function($q) use ($filter) {
                $date   = explode(' to ', $filter['dates']);
                $start  = date('Y-m-d', strtotime($date[0])) . ' 00:00:00';
                $end    = date('Y-m-d', strtotime(end($date))) . ' 23:59:59';
                return $q->whereBetween('created_at',[$start, $end]);
            })
            ->where('site_id', $site_id)
            ->latest()
            ->get();
    }
}
