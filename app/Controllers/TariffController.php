<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Tariff;
use App\Models\Branch;

class TariffController extends BaseController
{
    public function index()
    {
        // Require Super Admin
        if ($_SESSION['role_id'] != 1) {
            $_SESSION['error'] = "Akses ditolak. Anda tidak memiliki izin.";
            Response::redirect('/dashboard');
        }

        $tariffModel = new Tariff();
        $branchModel = new Branch();
        
        $tariffs = $tariffModel->all();
        $branches = $branchModel->all();

        // Enhance tariffs with branch names for view
        foreach ($tariffs as &$t) {
            if ($t['type'] === 'BRANCH') {
                $ob = $branchModel->find($t['origin_branch_id']);
                $db = $branchModel->find($t['destination_branch_id']);
                $t['origin_name'] = $ob ? $ob['name'] : 'N/A';
                $t['dest_name'] = $db ? $db['name'] : 'N/A';
            } else {
                $t['origin_name'] = $t['origin_city'];
                $t['dest_name'] = $t['destination_city'];
            }
        }
        
        $this->view('tariffs/index', [
            'tariffs' => $tariffs,
            'branches' => $branches
        ]);
    }

    public function store(Request $request)
    {
        $type = $request->get('type');
        $data = [
            'type' => $type,
            'price_per_kg' => $request->get('price_per_kg') ?: 0,
            'price_per_koli' => $request->get('price_per_koli') ?: 0,
            'price_per_volume' => $request->get('price_per_volume') ?: 0,
            'estimated_days' => $request->get('estimated_days'),
            'is_active' => $request->get('is_active', 1)
        ];

        if ($type === 'BRANCH') {
            $data['origin_branch_id'] = $request->get('origin_branch_id');
            $data['destination_branch_id'] = $request->get('destination_branch_id');
            $data['origin_city'] = null;
            $data['destination_city'] = null;
        } else {
            $data['origin_branch_id'] = null;
            $data['destination_branch_id'] = null;
            $data['origin_city'] = $request->get('origin_city');
            $data['destination_city'] = $request->get('destination_city');
        }

        $tariffModel = new Tariff();
        if ($tariffModel->create($data)) {
            $_SESSION['success'] = "Tarif berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambahkan tarif. Mungkin kombinasi rute sudah ada.";
        }

        Response::redirect('/tariffs');
    }

    public function update(Request $request, $id)
    {
        $type = $request->get('type');
        $data = [
            'type' => $type,
            'price_per_kg' => $request->get('price_per_kg') ?: 0,
            'price_per_koli' => $request->get('price_per_koli') ?: 0,
            'price_per_volume' => $request->get('price_per_volume') ?: 0,
            'estimated_days' => $request->get('estimated_days'),
            'is_active' => $request->get('is_active', 1)
        ];

        if ($type === 'BRANCH') {
            $data['origin_branch_id'] = $request->get('origin_branch_id');
            $data['destination_branch_id'] = $request->get('destination_branch_id');
            $data['origin_city'] = null;
            $data['destination_city'] = null;
        } else {
            $data['origin_branch_id'] = null;
            $data['destination_branch_id'] = null;
            $data['origin_city'] = $request->get('origin_city');
            $data['destination_city'] = $request->get('destination_city');
        }

        $tariffModel = new Tariff();
        if ($tariffModel->update($id, $data)) {
            $_SESSION['success'] = "Tarif berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui tarif.";
        }

        Response::redirect('/tariffs');
    }

    public function delete(Request $request, $id)
    {
        $tariffModel = new Tariff();
        if ($tariffModel->delete($id)) {
            $_SESSION['success'] = "Tarif berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus tarif.";
        }
        
        Response::redirect('/tariffs');
    }

    /**
     * API Endpoint for calculating price
     */
    public function calculate(Request $request)
    {
        $origin = $request->get('origin_branch_id') ? (int)$request->get('origin_branch_id') : null;
        $dest   = $request->get('destination_branch_id') ? (int)$request->get('destination_branch_id') : null;
        $originCity = $request->get('origin_city');
        $destCity   = $request->get('destination_city');
        $routeMode = $request->get('route_mode', 'branch');
        
        $weight = (float)$request->get('weight', 1.0);
        $volume = (float)$request->get('volume', 0.0);
        $koli   = (int)$request->get('koli', 1);

        if ($routeMode === 'branch' && (!$origin || !$dest)) {
            Response::json(['status' => 'error', 'message' => 'Cabang Asal dan Tujuan harus diisi.']);
            return;
        }
        if ($routeMode === 'city' && (!$originCity || !$destCity)) {
            Response::json(['status' => 'error', 'message' => 'Kota Asal dan Tujuan harus diisi.']);
            return;
        }

        $tariffModel = new Tariff();
        $result = $tariffModel->calculate($origin, $dest, $weight, $volume, $koli, $originCity, $destCity);

        if ($result) {
            Response::json(['status' => 'success', 'data' => [
                'price_per_kg'  => $result['price_per_kg'],
                'price_per_koli' => $result['price_per_koli'],
                'price_per_volume' => $result['price_per_volume'],
                'total_price'   => $result['total_price'],
                'calculated_details' => $result['calculated_details'],
                'estimated_days' => $result['estimated_days'],
                'type_used'     => $result['type']
            ]]);
        } else {
            Response::json(['status' => 'error', 'message' => 'Tarif untuk rute tersebut belum tersedia.']);
        }
    }

    // ── Excel Export ──────────────────────────────────────────────────────────
    public function exportExcel()
    {
        $tariffModel = new Tariff();
        $branchModel = new Branch();
        $tariffs     = $tariffModel->all();

        foreach ($tariffs as &$t) {
            if ($t['type'] === 'BRANCH') {
                $ob = $branchModel->find($t['origin_branch_id']);
                $db = $branchModel->find($t['destination_branch_id']);
                $t['origin_name'] = $ob ? $ob['name'] : 'N/A';
                $t['dest_name']   = $db ? $db['name'] : 'N/A';
            } else {
                $t['origin_name'] = $t['origin_city'];
                $t['dest_name']   = $t['destination_city'];
            }
        }

        ['spreadsheet' => $sp, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Data Tarif');

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DATA TARIF PENGIRIMAN — PT LANEXS EXPRESS INDONESIA');
        $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '7c3aed']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);
        $sheet->getRowDimension(1)->setRowHeight(26);
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Diekspor: ' . date('d/m/Y H:i') . ' | Total: ' . count($tariffs) . ' tarif');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('64748b');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headers = ['No', 'Tipe', 'Asal', 'Tujuan', 'Harga/Kg', 'Harga/Koli', 'Harga/Vol', 'Est. Hari', 'Aktif'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 3, '7c3aed');
        $sheet->freezePane('A4');

        $row = 4;
        foreach ($tariffs as $i => $t) {
            $sheet->fromArray([$i + 1, $t['type'], $t['origin_name'], $t['dest_name'], (float)$t['price_per_kg'], (float)$t['price_per_koli'], (float)$t['price_per_volume'], (int)$t['estimated_days'], $t['is_active'] ? 'Ya' : 'Tidak'], null, 'A' . $row);
            $row++;
        }
        \App\Helpers\ExcelHelper::styleDataRows($sheet, 4, $row - 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));
        $sheet->getStyle('E4:E' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0');

        \App\Helpers\ExcelHelper::download($sp, 'Export_Tarif_' . date('Ymd_His') . '.xlsx');
    }

    // ── Excel Template ────────────────────────────────────────────────────────
    public function downloadTemplate()
    {
        $branches = (new Branch())->all();

        ['spreadsheet' => $sp, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Template Import Tarif');

        $headers = ['type', 'origin_branch_id', 'destination_branch_id', 'origin_city', 'destination_city', 'price_per_kg', 'price_per_koli', 'price_per_volume', 'estimated_days', 'is_active'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 1, '7c3aed');

        $examples = [
            ['BRANCH', 1, 2, '', '', 8000, 15000, 50000, 2, 1],
            ['CITY',   '', '', 'Jakarta', 'Surabaya', 12000, 20000, 75000, 3, 1],
        ];
        foreach ($examples as $i => $r) {
            $sheet->fromArray($r, null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::styleDataRows($sheet, 2, count($examples) + 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));

        $noteRow = count($examples) + 3;
        $sheet->mergeCells('A' . $noteRow . ':H' . $noteRow);
        $sheet->setCellValue('A' . $noteRow, '⚠ CATATAN: Jika type=BRANCH isi origin_branch_id & destination_branch_id, biarkan origin_city & destination_city kosong. Sebaliknya untuk type=CITY.');
        $sheet->getStyle('A' . $noteRow)->applyFromArray(['font' => ['italic' => true, 'color' => ['rgb' => 'b45309']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef3c7']]]);

        $branchSheet = $sp->createSheet();
        $branchSheet->setTitle('Daftar Cabang (ID)');
        \App\Helpers\ExcelHelper::writeHeaderRow($branchSheet, ['ID Cabang', 'Nama Cabang', 'Kota'], 1, '065f46');
        foreach ($branches as $i => $b) {
            $branchSheet->fromArray([$b['id'], $b['name'], $b['city'] ?? '-'], null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::autoFitColumns($branchSheet, 3);

        $sp->setActiveSheetIndex(0);
        \App\Helpers\ExcelHelper::download($sp, 'Template_Import_Tarif_LANEXS.xlsx');
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

        $branchModel = new Branch();
        $branchMap   = array_column($branchModel->all(), 'name', 'id');

        $required = ['type', 'price_per_kg', 'estimated_days', 'is_active'];
        $rows = []; $errors = [];

        foreach ($parsed['rows'] as $i => $rawRow) {
            $row  = \App\Helpers\ExcelHelper::mapRow($parsed['headers'], $rawRow);
            $type = strtoupper(trim($row['type'] ?? ''));

            $row['origin_display'] = $type === 'BRANCH'
                ? ($branchMap[$row['origin_branch_id']] ?? '⚠ ID ' . ($row['origin_branch_id'] ?? '?') . ' tdk ditemukan')
                : ($row['origin_city'] ?? '-');
            $row['dest_display'] = $type === 'BRANCH'
                ? ($branchMap[$row['destination_branch_id']] ?? '⚠ ID ' . ($row['destination_branch_id'] ?? '?') . ' tdk ditemukan')
                : ($row['destination_city'] ?? '-');

            $validBranch = $type !== 'BRANCH' || (isset($branchMap[$row['origin_branch_id']]) && isset($branchMap[$row['destination_branch_id']]));
            $validCity   = $type !== 'CITY' || (!empty($row['origin_city']) && !empty($row['destination_city']));

            $row['_valid'] = in_array($type, ['BRANCH', 'CITY']) && $validBranch && $validCity && !empty($row['price_per_kg']);
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

        $model   = new Tariff();
        $success = $failed = 0;

        foreach ($rows as $row) {
            if (!($row['_valid'] ?? false)) { $failed++; continue; }
            $type = strtoupper(trim($row['type']));
            $data = [
                'type'           => $type,
                'price_per_kg'   => floatval($row['price_per_kg'] ?? 0),
                'price_per_koli' => floatval($row['price_per_koli'] ?? 0),
                'price_per_volume' => floatval($row['price_per_volume'] ?? 0),
                'estimated_days' => intval($row['estimated_days']),
                'is_active'      => intval($row['is_active'] ?? 1),
                'origin_branch_id'      => $type === 'BRANCH' ? intval($row['origin_branch_id']) : null,
                'destination_branch_id' => $type === 'BRANCH' ? intval($row['destination_branch_id']) : null,
                'origin_city'      => $type === 'CITY' ? trim($row['origin_city']) : null,
                'destination_city' => $type === 'CITY' ? trim($row['destination_city']) : null,
            ];
            $model->create($data) ? $success++ : $failed++;
        }

        \App\Services\AuditLogger::log('IMPORT_EXCEL', 'Tariff', null, null, ['imported' => $success]);
        Response::json(['success' => true, 'imported' => $success, 'failed' => $failed, 'message' => "$success tarif berhasil diimport."]);
    }
}

