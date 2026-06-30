<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Package;
use App\Models\Branch;
use App\Models\TrackingHistory;
use App\Models\Customer;

class PackageController extends BaseController
{
    public function index()
    {
        $packageModel = new Package();
        $packages = $packageModel->getAllWithRelations();
        
        $branchModel = new Branch();
        $branches = $branchModel->all();
        
        $customerModel = new Customer();
        $customers = $customerModel->all();
        
        $this->view('packages/index', [
            'packages' => $packages,
            'branches' => $branches,
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
        $packageModel = new Package();
        $branchModel = new Branch();
        
        // Find origin branch code for Resi
        $originBranch = $branchModel->find($request->get('origin_branch_id'));
        $originCode = $originBranch ? explode('-', $originBranch['code'])[0] : 'XXX';
        
        // Generate Resi
        $resi = $packageModel->generateResi($originCode);
        
        $paymentType = $request->get('payment_type', 'CASH');
        $paymentStatus = $request->get('payment_status', 'UNPAID');

        $data = [
            'resi' => $resi,
            'customer_id' => $request->get('customer_id') ?: null,
            'sender_name' => $request->get('sender_name'),
            'sender_phone' => $request->get('sender_phone'),
            'sender_address' => $request->get('sender_address'),
            'receiver_name' => $request->get('receiver_name'),
            'receiver_phone' => $request->get('receiver_phone'),
            'receiver_address' => $request->get('receiver_address'),
            'origin_branch_id' => $request->get('origin_branch_id'),
            'destination_branch_id' => $request->get('destination_branch_id'),
            'item_type' => $request->get('item_type') ?: 'UMUM',
            'koli' => $request->get('koli') ?: 1,
            'length' => $request->get('length') ?: 0,
            'width' => $request->get('width') ?: 0,
            'height' => $request->get('height') ?: 0,
            'volume' => ($request->get('length') * $request->get('width') * $request->get('height')) / 1000000,
            'weight' => $request->get('weight', 1.0),
            'price' => $request->get('price', 0),
            'payment_type' => $paymentType,
            'payment_status' => $paymentStatus,
            'status' => 'PENDING',
            'created_by' => $_SESSION['user_id']
        ];
        
        $packageId = $packageModel->create($data);
        
        if ($packageId) {
            // Log creation
            \App\Services\AuditLogger::log('CREATE', 'Package', $packageId, null, $data);
            
            // Finance Integration: Auto insert Transaction if PAID
            if ($paymentStatus == 'PAID' && in_array($paymentType, ['CASH', 'TRANSFER'])) {
                $transactionModel = new \App\Models\Transaction();
                $transactionModel->create([
                    'branch_id' => $request->get('origin_branch_id'),
                    'type' => 'INCOME',
                    'amount' => $request->get('price', 0),
                    'reference_type' => 'PACKAGE',
                    'reference_id' => $resi,
                    'description' => "Pembayaran Paket $paymentType - Resi: $resi",
                    'created_by' => $_SESSION['user_id']
                ]);
            }

            // Add Tracking History
            $trackingModel = new TrackingHistory();
            $trackingModel->create([
                'package_id' => $packageId,
                'branch_id' => $request->get('origin_branch_id'),
                'user_id' => $_SESSION['user_id'],
                'status' => 'PENDING',
                'description' => 'Paket dibuat dan menunggu penjemputan/pengiriman'
            ]);
            
            // Send WA Notification
            \App\Services\NotificationService::sendResiCreated($data);
            
            $_SESSION['success'] = "Paket berhasil dibuat dengan Resi: " . $resi;
        } else {
            $_SESSION['error'] = "Gagal membuat paket.";
        }
        
        Response::redirect('/packages');
    }

    public function storeMass(Request $request)
    {
        $payload = json_decode($request->get('payload'), true);
        if (!$payload || !is_array($payload)) {
            $_SESSION['error'] = "Data paket masal tidak valid.";
            Response::redirect('/packages');
            return;
        }

        $packageModel = new Package();
        $branchModel = new Branch();
        $trackingModel = new TrackingHistory();
        $transactionModel = new \App\Models\Transaction();

        $successCount = 0;
        foreach ($payload as $pkg) {
            $originBranch = $branchModel->find($pkg['origin_branch_id']);
            $originCode = $originBranch ? explode('-', $originBranch['code'])[0] : 'XXX';
            $resi = $packageModel->generateResi($originCode);

            $paymentType = $pkg['payment_type'] ?? 'CASH';
            $paymentStatus = $pkg['payment_status'] ?? 'UNPAID';

            $data = [
                'resi' => $resi,
                'customer_id' => $pkg['customer_id'] ?: null,
                'sender_name' => $pkg['sender_name'],
                'sender_phone' => $pkg['sender_phone'],
                'sender_address' => $pkg['sender_address'],
                'receiver_name' => $pkg['receiver_name'],
                'receiver_phone' => $pkg['receiver_phone'],
                'receiver_address' => $pkg['receiver_address'],
                'origin_branch_id' => $pkg['origin_branch_id'],
                'destination_branch_id' => $pkg['destination_branch_id'],
                'item_type' => $pkg['item_type'] ?: 'UMUM',
                'koli' => $pkg['koli'] ?: 1,
                'length' => $pkg['length'] ?: 0,
                'width' => $pkg['width'] ?: 0,
                'height' => $pkg['height'] ?: 0,
                'volume' => (($pkg['length'] ?: 0) * ($pkg['width'] ?: 0) * ($pkg['height'] ?: 0)) / 1000000,
                'weight' => $pkg['weight'] ?: 1.0,
                'price' => $pkg['price'] ?: 0,
                'payment_type' => $paymentType,
                'payment_status' => $paymentStatus,
                'status' => 'PENDING',
                'created_by' => $_SESSION['user_id']
            ];

            $packageId = $packageModel->create($data);

            if ($packageId) {
                $successCount++;
                \App\Services\AuditLogger::log('CREATE_MASS', 'Package', $packageId, null, $data);

                if ($paymentStatus == 'PAID' && in_array($paymentType, ['CASH', 'TRANSFER'])) {
                    $transactionModel->create([
                        'branch_id' => $pkg['origin_branch_id'],
                        'type' => 'INCOME',
                        'amount' => $pkg['price'],
                        'reference_type' => 'PACKAGE',
                        'reference_id' => $resi,
                        'description' => "Pembayaran Paket Masal $paymentType - Resi: $resi",
                        'created_by' => $_SESSION['user_id']
                    ]);
                }

                $trackingModel->create([
                    'package_id' => $packageId,
                    'branch_id' => $pkg['origin_branch_id'],
                    'user_id' => $_SESSION['user_id'],
                    'status' => 'PENDING',
                    'description' => 'Paket masal dibuat dan menunggu penjemputan/pengiriman'
                ]);
            }
        }

        if ($successCount > 0) {
            $_SESSION['success'] = "$successCount Paket masal berhasil dibuat.";
        } else {
            $_SESSION['error'] = "Gagal membuat paket masal.";
        }

        Response::redirect('/packages');
    }

    public function update(Request $request, $id)
    {
        $packageModel = new Package();
        $data = [
            'sender_name' => $request->get('sender_name'),
            'sender_phone' => $request->get('sender_phone'),
            'sender_address' => $request->get('sender_address'),
            'receiver_name' => $request->get('receiver_name'),
            'receiver_phone' => $request->get('receiver_phone'),
            'receiver_address' => $request->get('receiver_address'),
            'origin_branch_id' => $request->get('origin_branch_id'),
            'destination_branch_id' => $request->get('destination_branch_id'),
            'item_type' => $request->get('item_type') ?: 'UMUM',
            'koli' => $request->get('koli') ?: 1,
            'length' => $request->get('length') ?: 0,
            'width' => $request->get('width') ?: 0,
            'height' => $request->get('height') ?: 0,
            'volume' => ($request->get('length') * $request->get('width') * $request->get('height')) / 1000000,
            'weight' => $request->get('weight', 1.0),
            'price' => $request->get('price', 0),
        ];

        $oldData = $packageModel->find($id);

        if ($packageModel->update($id, $data)) {
            \App\Services\AuditLogger::log('UPDATE', 'Package', $id, $oldData, $data);
            $_SESSION['success'] = "Data paket berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui data paket.";
        }

        Response::redirect('/packages');
    }

    public function delete(Request $request, $id)
    {
        $packageModel = new Package();
        $oldData = $packageModel->find($id);
        
        if ($packageModel->delete($id)) {
            \App\Services\AuditLogger::log('DELETE', 'Package', $id, $oldData, null);
            $_SESSION['success'] = "Paket berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus paket.";
        }
        
        Response::redirect('/packages');
    }

    public function updateStatus(Request $request, $id)
    {
        $status = $request->get('status');
        $description = $request->get('description');
        $branchId = $request->get('branch_id') ?: null;

        if ($status && $description) {
            $packageModel = new Package();
            $oldData = $packageModel->find($id);
            $packageModel->update($id, ['status' => $status]);
            
            \App\Services\AuditLogger::log('UPDATE_STATUS', 'Package', $id, ['status' => $oldData['status']], ['status' => $status, 'description' => $description]);

            $trackingModel = new TrackingHistory();
            $trackingModel->create([
                'package_id' => $id,
                'branch_id' => $branchId,
                'user_id' => $_SESSION['user_id'],
                'status' => $status,
                'description' => $description
            ]);
            
            if ($status === 'SELESAI') {
                $pkgData = $packageModel->find($id);
                if ($pkgData) {
                    \App\Services\NotificationService::sendStatusUpdated($pkgData, $status);
                }
            }
            
            $_SESSION['success'] = "Status paket berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Status dan Deskripsi wajib diisi.";
        }

        Response::redirect('/packages');
    }

    public function print(Request $request, $id)
    {
        $packageModel = new Package();
        $db = $packageModel->getDb();
        
        $sql = "
            SELECT p.*, 
                   bo.name as origin_branch_name, bo.city as origin_city,
                   bd.name as dest_branch_name, bd.city as dest_city,
                   c.company_name as customer_name
            FROM packages p
            LEFT JOIN branches bo ON p.origin_branch_id = bo.id
            LEFT JOIN branches bd ON p.destination_branch_id = bd.id
            LEFT JOIN customers c ON p.customer_id = c.id
            WHERE p.id = :id LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $package = $stmt->fetch();

        if (!$package) {
            $_SESSION['error'] = "Paket tidak ditemukan.";
            Response::redirect('/packages');
        }

        ob_start();
        $this->view('packages/print', ['package' => $package, 'isPdf' => true]);
        $html = ob_get_clean();

        if (class_exists(\Dompdf\Dompdf::class)) {
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            // Label resi usually is smaller or thermal, but we use A4 portrait as default for now
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("Resi_{$package['resi']}.pdf", ["Attachment" => false]);
            exit;
        } else {
            echo $html;
        }
    }

    public function datatable(Request $request)
    {
        $packageModel = new Package();
        $db = $packageModel->getDb();

        // Datatable parameters
        $draw   = $_GET['draw'] ?? 1;
        $start  = $_GET['start'] ?? 0;
        $length = $_GET['length'] ?? 10;
        $search = $_GET['search']['value'] ?? '';
        
        $branchId = $_SESSION['branch_id'];
        $roleId   = $_SESSION['role_id'];

        // Base query
        $whereSql = "1=1";
        $params = [];

        // Role restriction
        if ($roleId != 1 && $roleId != 2 && $roleId != 5) {
            $whereSql .= " AND (p.origin_branch_id = :bid OR p.destination_branch_id = :bid)";
            $params['bid'] = $branchId;
        } elseif ($roleId == 5) { // B2B Client
            $whereSql .= " AND p.customer_id = :cid";
            $params['cid'] = $_SESSION['customer_id'] ?? 0;
        }

        // Search
        if (!empty($search)) {
            $whereSql .= " AND (p.resi LIKE :search OR p.sender_name LIKE :search OR p.receiver_name LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        // Filter Status
        $statusFilter = $_GET['status_filter'] ?? '';
        if (!empty($statusFilter)) {
            $whereSql .= " AND p.status = :status_filter";
            $params['status_filter'] = $statusFilter;
        }

        // Total records without filter
        $totalRecords = $db->query("SELECT COUNT(*) FROM packages")->fetchColumn();

        // Total records with filter
        $stmtTotal = $db->prepare("SELECT COUNT(*) FROM packages p WHERE $whereSql");
        $stmtTotal->execute($params);
        $totalFiltered = $stmtTotal->fetchColumn();

        // Fetch data
        $sql = "SELECT p.*, bo.name as origin_branch_name, bd.name as dest_branch_name 
                FROM packages p
                LEFT JOIN branches bo ON p.origin_branch_id = bo.id
                LEFT JOIN branches bd ON p.destination_branch_id = bd.id
                WHERE $whereSql
                ORDER BY p.id DESC
                LIMIT " . intval($length) . " OFFSET " . intval($start);
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        // Return JSON
        Response::json([
            "draw"            => intval($draw),
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            "role_id"         => $roleId
        ]);
    }

    public function export()
    {
        $packageModel = new Package();
        $db = $packageModel->getDb();

        $roleId   = $_SESSION['role_id'];
        $branchId = $_SESSION['branch_id'];

        $whereSql = '1=1';
        $params   = [];
        if ($roleId != 1 && $roleId != 2 && $roleId != 5) {
            $whereSql .= ' AND (p.origin_branch_id = :bid OR p.destination_branch_id = :bid)';
            $params['bid'] = $branchId;
        } elseif ($roleId == 5) {
            $whereSql .= ' AND p.customer_id = :cid';
            $params['cid'] = $_SESSION['customer_id'] ?? 0;
        }

        $stmt = $db->prepare("SELECT p.*, bo.name as origin_branch_name, bd.name as dest_branch_name
                               FROM packages p
                               LEFT JOIN branches bo ON p.origin_branch_id = bo.id
                               LEFT JOIN branches bd ON p.destination_branch_id = bd.id
                               WHERE $whereSql ORDER BY p.id DESC");
        $stmt->execute($params);
        $packages = $stmt->fetchAll();

        ['spreadsheet' => $spreadsheet, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Export Paket');

        // Title row
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'LAPORAN DATA PAKET / RESI — PT LANEXS EXPRESS INDONESIA');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '1e3a5f']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Sub-title
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Diekspor: ' . date('d/m/Y H:i:s') . '  |  Total: ' . count($packages) . ' paket');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('64748b');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(16);

        // Header
        $headers = ['No', 'No. Resi', 'Nama Pengirim', 'Nama Penerima', 'Cabang Asal', 'Cabang Tujuan', 'Berat (Kg)', 'Harga (Rp)', 'Status Bayar', 'Status Kiriman'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 3);
        $sheet->freezePane('A4');

        // Data
        $row = 4;
        foreach ($packages as $i => $p) {
            $sheet->fromArray([
                $i + 1,
                $p['resi'],
                $p['sender_name'],
                $p['receiver_name'],
                $p['origin_branch_name'],
                $p['dest_branch_name'],
                (float)$p['weight'],
                (float)$p['price'],
                $p['payment_status'],
                $p['status'],
            ], null, 'A' . $row);
            $row++;
        }

        \App\Helpers\ExcelHelper::styleDataRows($sheet, 4, $row - 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));

        // Format currency column H
        $sheet->getStyle('H4:H' . ($row - 1))
              ->getNumberFormat()->setFormatCode('#,##0');

        \App\Helpers\ExcelHelper::download($spreadsheet, 'Export_Paket_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Download Excel template for bulk import
     */
    public function downloadTemplate()
    {
        // Load branches to populate the info sheet
        $branchModel = new Branch();
        $branches    = $branchModel->all();

        ['spreadsheet' => $spreadsheet, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Template Import Paket');

        // ── Sheet 1: Template ──────────────────────────────────────────
        $headers = ['sender_name','sender_phone','sender_address','receiver_name','receiver_phone','receiver_address','origin_branch_id','destination_branch_id','weight','price','payment_type','payment_status'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 1, '2563a1');

        // Example rows
        $examples = [
            ['Budi Santoso','081234567890','Jl. Merdeka No.1, Jakarta','Siti Rahayu','082345678901','Jl. Pahlawan No.5, Bogor',1,2,2.5,45000,'CASH','PAID'],
            ['Ahmad Fauzi','083456789012','Jl. Sudirman No.10, Bandung','Dewi Lestari','084567890123','Jl. Diponegoro No.20, Surabaya',1,2,5.0,85000,'COD','COD'],
        ];
        foreach ($examples as $i => $row) {
            $sheet->fromArray($row, null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::styleDataRows($sheet, 2, count($examples) + 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));

        // Add notes row
        $noteRow = count($examples) + 3;
        $sheet->mergeCells('A' . $noteRow . ':L' . $noteRow);
        $sheet->setCellValue('A' . $noteRow, '⚠ CATATAN: Isi data di baris 2 ke bawah. Kolom payment_type: CASH/TRANSFER/COD. Kolom payment_status: PAID/UNPAID/COD.');
        $sheet->getStyle('A' . $noteRow)->applyFromArray(['font' => ['italic' => true, 'color' => ['rgb' => 'b45309']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef3c7']]]);

        // ── Sheet 2: Daftar Cabang ─────────────────────────────────────
        $branchSheet = $spreadsheet->createSheet();
        $branchSheet->setTitle('Daftar Cabang (ID)');
        \App\Helpers\ExcelHelper::writeHeaderRow($branchSheet, ['ID Cabang', 'Nama Cabang', 'Kota', 'Kode'], 1, '065f46');
        foreach ($branches as $i => $b) {
            $branchSheet->fromArray([$b['id'], $b['name'], $b['city'] ?? '-', $b['code'] ?? '-'], null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::autoFitColumns($branchSheet, 4);

        $spreadsheet->setActiveSheetIndex(0);
        \App\Helpers\ExcelHelper::download($spreadsheet, 'Template_Import_Paket_LANEXS.xlsx');
    }

    /**
     * Preview imported Excel/CSV file before saving to DB
     */
    public function importPreview(Request $request)
    {
        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            Response::json(['success' => false, 'message' => 'File tidak ditemukan atau gagal diunggah.']);
            return;
        }

        try {
            $parsed = \App\Helpers\ExcelHelper::readUpload($_FILES['import_file']);
        } catch (\Exception $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()]);
            return;
        }

        $branchModel = new Branch();
        $branchMap   = [];
        foreach ($branchModel->all() as $b) {
            $branchMap[$b['id']] = $b['name'];
        }

        $expectedKeys = ['sender_name','sender_phone','sender_address','receiver_name','receiver_phone',
                         'receiver_address','origin_branch_id','destination_branch_id','weight','price','payment_type','payment_status'];
        $rows   = [];
        $errors = [];

        foreach ($parsed['rows'] as $i => $rawRow) {
            $row = \App\Helpers\ExcelHelper::mapRow($parsed['headers'], $rawRow);

            // Check required keys exist
            $missing = array_diff($expectedKeys, array_keys($row));
            if (!empty($missing)) {
                $errors[] = 'Baris ' . ($i + 2) . ': Kolom tidak lengkap — pastikan header template tidak diubah.';
                continue;
            }

            $row['origin_branch_name'] = $branchMap[$row['origin_branch_id']] ?? '⚠️ TIDAK DITEMUKAN';
            $row['dest_branch_name']   = $branchMap[$row['destination_branch_id']] ?? '⚠️ TIDAK DITEMUKAN';
            $row['_valid']             = isset($branchMap[$row['origin_branch_id']]) && isset($branchMap[$row['destination_branch_id']]);
            $row['_line']              = $i + 2;
            $rows[]                    = $row;
        }

        if (empty($rows) && empty($errors)) {
            Response::json(['success' => false, 'message' => 'File kosong atau tidak ada data setelah baris header.']);
            return;
        }

        Response::json(['success' => true, 'rows' => $rows, 'errors' => $errors, 'total' => count($rows)]);
    }

    /**
     * Process confirmed import: generate resi & save to DB
     */
    public function importProcess(Request $request)
    {
        $jsonRows = $request->get('rows');
        if (empty($jsonRows)) {
            Response::json(['success' => false, 'message' => 'Tidak ada data untuk diproses.']);
            return;
        }

        $rows = json_decode($jsonRows, true);
        if (!is_array($rows)) {
            Response::json(['success' => false, 'message' => 'Data tidak valid.']);
            return;
        }

        $packageModel   = new Package();
        $branchModel    = new Branch();
        $trackingModel  = new TrackingHistory();
        $userId         = $_SESSION['user_id'];

        $success = 0;
        $failed  = 0;
        $resiList = [];

        foreach ($rows as $row) {
            try {
                $originBranch = $branchModel->find($row['origin_branch_id']);
                $originCode   = $originBranch ? explode('-', $originBranch['code'])[0] : 'XXX';
                $resi         = $packageModel->generateResi($originCode);

                $paymentType   = strtoupper(trim($row['payment_type'] ?? 'CASH'));
                $paymentStatus = strtoupper(trim($row['payment_status'] ?? 'UNPAID'));

                $data = [
                    'resi'                  => $resi,
                    'sender_name'           => trim($row['sender_name']),
                    'sender_phone'          => trim($row['sender_phone']),
                    'sender_address'        => trim($row['sender_address']),
                    'receiver_name'         => trim($row['receiver_name']),
                    'receiver_phone'        => trim($row['receiver_phone']),
                    'receiver_address'      => trim($row['receiver_address']),
                    'origin_branch_id'      => intval($row['origin_branch_id']),
                    'destination_branch_id' => intval($row['destination_branch_id']),
                    'item_type'             => trim($row['item_type'] ?? 'UMUM'),
                    'koli'                  => intval($row['koli'] ?? 1),
                    'length'                => floatval($row['length'] ?? 0),
                    'width'                 => floatval($row['width'] ?? 0),
                    'height'                => floatval($row['height'] ?? 0),
                    'volume'                => (floatval($row['length'] ?? 0) * floatval($row['width'] ?? 0) * floatval($row['height'] ?? 0)) / 1000000,
                    'weight'                => floatval($row['weight']),
                    'price'                 => floatval($row['price']),
                    'payment_type'          => $paymentType,
                    'payment_status'        => $paymentStatus,
                    'status'                => 'PENDING',
                    'created_by'            => $userId
                ];

                $packageId = $packageModel->create($data);

                if ($packageId) {
                    // Auto-create tracking history
                    $trackingModel->create([
                        'package_id' => $packageId,
                        'branch_id'  => $row['origin_branch_id'],
                        'user_id'    => $userId,
                        'status'     => 'PENDING',
                        'description' => 'Paket dibuat via Import Massal (CSV)'
                    ]);

                    // Auto-create finance transaction if PAID
                    if ($paymentStatus === 'PAID' && in_array($paymentType, ['CASH', 'TRANSFER'])) {
                        $transactionModel = new \App\Models\Transaction();
                        $transactionModel->create([
                            'branch_id'      => $row['origin_branch_id'],
                            'type'           => 'INCOME',
                            'amount'         => floatval($row['price']),
                            'reference_type' => 'PACKAGE',
                            'reference_id'   => $resi,
                            'description'    => "Import Massal - Resi: $resi",
                            'created_by'     => $userId
                        ]);
                    }

                    \App\Services\AuditLogger::log('IMPORT_EXCEL', 'Package', $packageId, null, $data);

                    $resiList[] = $resi;
                    $success++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
            }
        }

        Response::json([
            'success'   => true,
            'imported'  => $success,
            'failed'    => $failed,
            'resi_list' => $resiList,
            'message'   => "$success paket berhasil diimport, $failed gagal."
        ]);
    }
}

