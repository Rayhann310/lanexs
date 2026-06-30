<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Customer;

class CustomerController extends BaseController
{
    public function index()
    {
        if ($_SESSION['role_id'] >= 4) {
            $_SESSION['error'] = "Akses ditolak.";
            Response::redirect('/dashboard');
        }
        $customers = (new Customer())->all();
        $this->view('customers/index', ['customers' => $customers]);
    }

    public function store(Request $request)
    {
        $data = [
            'company_name' => $request->get('company_name'),
            'pic_name'     => $request->get('pic_name'),
            'phone'        => $request->get('phone'),
            'email'        => $request->get('email'),
            'address'      => $request->get('address'),
            'credit_limit' => $request->get('credit_limit', 0),
            'status'       => 'ACTIVE'
        ];
        if ((new Customer())->create($data)) {
            $_SESSION['success'] = "Klien korporat berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambahkan klien.";
        }
        Response::redirect('/customers');
    }

    public function delete(Request $request, $id)
    {
        if ((new Customer())->delete($id)) {
            $_SESSION['success'] = "Klien berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus klien.";
        }
        Response::redirect('/customers');
    }

    // ── Excel Export ──────────────────────────────────────────────────────────
    public function exportExcel()
    {
        $customers = (new Customer())->all();

        ['spreadsheet' => $sp, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Data Klien B2B');

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DATA KLIEN KORPORAT (B2B) — PT LANEXS EXPRESS INDONESIA');
        $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '4338ca']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);
        $sheet->getRowDimension(1)->setRowHeight(26);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Diekspor: ' . date('d/m/Y H:i') . ' | Total: ' . count($customers) . ' klien');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('64748b');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headers = ['No', 'Nama Perusahaan', 'Nama PIC', 'Telepon', 'Email', 'Kredit Limit (Rp)', 'Status'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 3, '4338ca');
        $sheet->freezePane('A4');

        $row = 4;
        foreach ($customers as $i => $c) {
            $sheet->fromArray([$i + 1, $c['company_name'], $c['pic_name'], $c['phone'], $c['email'], (float)$c['credit_limit'], $c['status']], null, 'A' . $row);
            $row++;
        }
        \App\Helpers\ExcelHelper::styleDataRows($sheet, 4, $row - 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));
        $sheet->getStyle('F4:F' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0');

        \App\Helpers\ExcelHelper::download($sp, 'Export_Klien_B2B_' . date('Ymd_His') . '.xlsx');
    }

    // ── Excel Template ────────────────────────────────────────────────────────
    public function downloadTemplate()
    {
        ['spreadsheet' => $sp, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Template Import Klien');

        $headers = ['company_name', 'pic_name', 'phone', 'email', 'address', 'credit_limit'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 1, '4338ca');

        $examples = [
            ['PT Maju Bersama', 'Budi Handoko', '021-1234567', 'budi@majubersama.co.id', 'Jl. Sudirman No.10, Jakarta', 5000000],
            ['CV Sentosa Jaya', 'Ani Rahayu',  '022-7654321', 'ani@sentosajaya.com',   'Jl. Asia Afrika No.5, Bandung',  2500000],
        ];
        foreach ($examples as $i => $r) {
            $sheet->fromArray($r, null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::styleDataRows($sheet, 2, count($examples) + 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));

        $noteRow = count($examples) + 3;
        $sheet->mergeCells('A' . $noteRow . ':F' . $noteRow);
        $sheet->setCellValue('A' . $noteRow, '⚠ CATATAN: credit_limit diisi angka tanpa titik/koma. Kolom status akan otomatis ACTIVE.');
        $sheet->getStyle('A' . $noteRow)->applyFromArray(['font' => ['italic' => true, 'color' => ['rgb' => 'b45309']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef3c7']]]);

        \App\Helpers\ExcelHelper::download($sp, 'Template_Import_Klien_LANEXS.xlsx');
    }

    // ── Import Preview ────────────────────────────────────────────────────────
    public function importPreview(Request $request)
    {
        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            Response::json(['success' => false, 'message' => 'File tidak ditemukan.']); return;
        }
        try {
            $parsed = \App\Helpers\ExcelHelper::readUpload($_FILES['import_file']);
        } catch (\Exception $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()]); return;
        }

        $required = ['company_name', 'pic_name', 'phone', 'email', 'address', 'credit_limit'];
        $rows = []; $errors = [];

        foreach ($parsed['rows'] as $i => $rawRow) {
            $row     = \App\Helpers\ExcelHelper::mapRow($parsed['headers'], $rawRow);
            $missing = array_diff($required, array_keys($row));
            if (!empty($missing)) { $errors[] = 'Baris ' . ($i + 2) . ': Header tidak sesuai template.'; continue; }

            $row['_valid'] = !empty($row['company_name']) && !empty($row['phone']);
            $row['_line']  = $i + 2;
            $rows[] = $row;
        }

        Response::json(['success' => true, 'rows' => $rows, 'errors' => $errors, 'total' => count($rows)]);
    }

    // ── Import Process ────────────────────────────────────────────────────────
    public function importProcess(Request $request)
    {
        $rows = json_decode($request->get('rows'), true);
        if (!is_array($rows)) { Response::json(['success' => false, 'message' => 'Data tidak valid.']); return; }

        $model = new Customer();
        $success = $failed = 0;

        foreach ($rows as $row) {
            if (!($row['_valid'] ?? false)) { $failed++; continue; }
            $ok = $model->create([
                'company_name' => trim($row['company_name']),
                'pic_name'     => trim($row['pic_name']),
                'phone'        => trim($row['phone']),
                'email'        => trim($row['email']),
                'address'      => trim($row['address']),
                'credit_limit' => floatval($row['credit_limit']),
                'status'       => 'ACTIVE',
            ]);
            $ok ? $success++ : $failed++;
        }

        \App\Services\AuditLogger::log('IMPORT_EXCEL', 'Customer', null, null, ['imported' => $success]);
        Response::json(['success' => true, 'imported' => $success, 'failed' => $failed, 'message' => "$success klien berhasil diimport."]);
    }
}

