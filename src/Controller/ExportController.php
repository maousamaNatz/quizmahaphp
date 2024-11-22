<?php
namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Controller\AnswerController;
use App\Controller\QuestionController;

class ExportController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function fetchData($table) {
        $query = "SELECT * FROM $table";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function writeSheet($spreadsheet, $sheetIndex, $title, $data) {
        $sheet = $spreadsheet->createSheet($sheetIndex);
        $sheet->setTitle(substr($title, 0, 31)); // Limit sheet name to 31 characters

        if (!empty($data)) {
            $headers = array_keys($data[0]);
            foreach ($headers as $colIndex => $header) {
                $col = Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue($col . '1', $header);
                $sheet->getColumnDimension($col)->setWidth(20);
            }

            $rowIndex = 2;
            foreach ($data as $row) {
                $colIndex = 1;
                foreach ($row as $value) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($colIndex++) . $rowIndex, $value);
                }
                $rowIndex++;
            }
        } else {
            $sheet->setCellValue('A1', "No data found in table '$title'.");
        }
    }

    public function exportToExcel() {
        try {
            $spreadsheet = new Spreadsheet();
            $tables = ['login', 'users', 'questions', 'sub_questions', 'question_options', 'user_answers'];

            foreach ($tables as $index => $table) {
                $data = $this->fetchData($table);
                $this->writeSheet($spreadsheet, $index, $table, $data);
            }

            $spreadsheet->removeSheetByIndex(0); // Remove default sheet

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="tracer_data.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            error_log("Error exporting to Excel: " . $e->getMessage());
            die("Terjadi kesalahan saat mengekspor data");
        }
    }
} 