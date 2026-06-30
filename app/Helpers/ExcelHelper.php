<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Csv as CsvReader;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelHelper
{
    // ─────────────────────────────────────────────────────────────────────────
    // EXPORT helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Create a new Spreadsheet with LANEXS branding on the first sheet.
     *
     * @param  string $title  Sheet / document title
     * @return array{spreadsheet: Spreadsheet, sheet: \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet}
     */
    public static function createWorkbook(string $title): array
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('LANEXS ERP')
            ->setTitle($title)
            ->setCompany('PT LANEXS EXPRESS INDONESIA');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($title, 0, 31)); // Excel tab name max 31 chars

        return ['spreadsheet' => $spreadsheet, 'sheet' => $sheet];
    }

    /**
     * Write a styled header row starting at $row.
     *
     * @param  \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param  string[]  $columns  Column labels
     * @param  int       $row      Row number (1-based)
     * @param  string    $bgColor  Hex background, default LANEXS navy
     */
    public static function writeHeaderRow($sheet, array $columns, int $row = 1, string $bgColor = '1e3a5f'): void
    {
        $col = 'A';
        foreach ($columns as $label) {
            $cell = $col . $row;
            $sheet->setCellValue($cell, $label);
            $sheet->getStyle($cell)->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
            $col++;
        }
        $sheet->getRowDimension($row)->setRowHeight(22);
    }

    /**
     * Auto-fit all column widths based on content, with min/max caps.
     */
    public static function autoFitColumns($sheet, int $totalCols, int $minWidth = 10, int $maxWidth = 50): void
    {
        foreach (range('A', chr(ord('A') + $totalCols - 1)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Style alternating data rows for readability.
     */
    public static function styleDataRows($sheet, int $startRow, int $endRow, int $colCount): void
    {
        for ($r = $startRow; $r <= $endRow; $r++) {
            $lastCol = chr(ord('A') + $colCount - 1);
            $range   = "A{$r}:{$lastCol}{$r}";
            $fill    = ($r % 2 === 0) ? 'F8FAFC' : 'FFFFFF';
            $sheet->getStyle($range)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $fill]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($r)->setRowHeight(18);
        }
    }

    /**
     * Stream a Spreadsheet as a downloadable .xlsx file and exit.
     */
    public static function download(Spreadsheet $spreadsheet, string $filename): void
    {
        // Clean any previous output
        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // IMPORT helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Read an uploaded Excel (.xlsx) or CSV (.csv) file and return rows as arrays.
     * The first row is treated as the header and returned separately.
     *
     * @return array{headers: string[], rows: array[]}
     * @throws \Exception on unsupported format or empty file
     */
    public static function readUpload(array $file): array
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($ext === 'xlsx' || $ext === 'xls') {
            $reader = new XlsxReader();
        } elseif ($ext === 'csv' || $ext === 'txt') {
            $reader = new CsvReader();
            $reader->setDelimiter(',');
            $reader->setEnclosure('"');
        } else {
            throw new \Exception('Format file tidak didukung. Gunakan .xlsx atau .csv');
        }

        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file['tmp_name']);
        $sheet       = $spreadsheet->getActiveSheet();
        $data        = $sheet->toArray(null, true, true, false);

        if (count($data) < 2) {
            throw new \Exception('File kosong atau hanya berisi header.');
        }

        // First row = headers, rest = data
        $headers = array_map('trim', array_shift($data));

        // Filter completely empty rows
        $rows = array_filter($data, function ($row) {
            return !empty(array_filter($row, fn($v) => $v !== null && $v !== ''));
        });

        return ['headers' => $headers, 'rows' => array_values($rows)];
    }

    /**
     * Map a raw spreadsheet row (indexed array) to an associative array using headers.
     */
    public static function mapRow(array $headers, array $row): array
    {
        $mapped = [];
        foreach ($headers as $i => $key) {
            $mapped[$key] = isset($row[$i]) ? trim((string)$row[$i]) : '';
        }
        return $mapped;
    }
}
