<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Warehouse;
use App\Models\Branch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class WarehouseController extends BaseController
{
    public function index()
    {
        $warehouseModel = new Warehouse();
        $db = $warehouseModel->getDb();
        
        $stmt = $db->query("
            SELECT w.*, b.name as branch_name, b.code as branch_code
            FROM warehouses w 
            LEFT JOIN branches b ON w.branch_id = b.id 
            ORDER BY w.id DESC
        ");
        $warehouses = $stmt->fetchAll();

        // Get all branches for dropdown
        $branchModel = new Branch();
        $branches = $branchModel->all();

        // Calculate summaries
        $totalWarehouses = count($warehouses);
        $totalActive = 0;
        foreach($warehouses as $w) {
            if ($w['is_active']) $totalActive++;
        }
        
        $this->view('warehouses/index', [
            'warehouses' => $warehouses,
            'branches' => $branches,
            'totalWarehouses' => $totalWarehouses,
            'totalActive' => $totalActive
        ]);
    }

    public function store(Request $request)
    {
        $branch_id = $request->get('branch_id');
        $code = $request->get('code');
        $name = $request->get('name');
        
        if (empty($branch_id) || empty($code) || empty($name)) {
            $_SESSION['error'] = "Cabang, Kode Gudang, dan Nama Gudang wajib diisi.";
            Response::redirect('/warehouses');
            return;
        }

        $warehouseModel = new Warehouse();
        $db = $warehouseModel->getDb();
        $stmt = $db->prepare("SELECT id FROM warehouses WHERE code = :code");
        $stmt->execute(['code' => $code]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Kode Gudang sudah digunakan.";
            Response::redirect('/warehouses');
            return;
        }

        $warehouseModel->create([
            'branch_id' => $branch_id,
            'code' => $code,
            'name' => $name,
            'is_active' => 1
        ]);

        $_SESSION['success'] = "Gudang baru berhasil ditambahkan.";
        Response::redirect('/warehouses');
    }

    public function update(Request $request, $id)
    {
        $branch_id = $request->get('branch_id');
        $code = $request->get('code');
        $name = $request->get('name');
        
        if (empty($branch_id) || empty($code) || empty($name)) {
            $_SESSION['error'] = "Cabang, Kode Gudang, dan Nama Gudang wajib diisi.";
            Response::redirect('/warehouses');
            return;
        }

        $warehouseModel = new Warehouse();
        $db = $warehouseModel->getDb();
        
        // Cek duplicate code
        $stmt = $db->prepare("SELECT id FROM warehouses WHERE code = :code AND id != :id");
        $stmt->execute(['code' => $code, 'id' => $id]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Kode Gudang sudah digunakan oleh gudang lain.";
            Response::redirect('/warehouses');
            return;
        }

        $warehouseModel->update($id, [
            'branch_id' => $branch_id,
            'code' => $code,
            'name' => $name
        ]);

        $_SESSION['success'] = "Data gudang berhasil diperbarui.";
        Response::redirect('/warehouses');
    }

    public function delete(Request $request, $id)
    {
        $warehouseModel = new Warehouse();
        $warehouseModel->delete($id);
        $_SESSION['success'] = "Gudang berhasil dihapus.";
        Response::redirect('/warehouses');
    }

    public function export()
    {
        $warehouseModel = new Warehouse();
        $db = $warehouseModel->getDb();
        $stmt = $db->query("
            SELECT w.*, b.name as branch_name, b.code as branch_code
            FROM warehouses w 
            LEFT JOIN branches b ON w.branch_id = b.id 
            ORDER BY w.id DESC
        ");
        $warehouses = $stmt->fetchAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $sheet->setCellValue('A1', 'Kode Cabang');
        $sheet->setCellValue('B1', 'Nama Cabang');
        $sheet->setCellValue('C1', 'Kode Gudang');
        $sheet->setCellValue('D1', 'Nama Gudang');
        $sheet->setCellValue('E1', 'Status');

        // Style headers
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $row = 2;
        foreach ($warehouses as $w) {
            $sheet->setCellValue('A' . $row, $w['branch_code']);
            $sheet->setCellValue('B' . $row, $w['branch_name']);
            $sheet->setCellValue('C' . $row, $w['code']);
            $sheet->setCellValue('D' . $row, $w['name']);
            $sheet->setCellValue('E' . $row, $w['is_active'] ? 'Aktif' : 'Tidak Aktif');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Export_Gudang_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
        $writer->save('php://output');
        exit;
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Kode Cabang');
        $sheet->setCellValue('B1', 'Kode Gudang');
        $sheet->setCellValue('C1', 'Nama Gudang');
        
        // Example row
        $sheet->setCellValue('A2', 'JKT01');
        $sheet->setCellValue('B2', 'WH-JKT01-01');
        $sheet->setCellValue('C2', 'Gudang Utama Jakarta');

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Gudang.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
        $writer->save('php://output');
        exit;
    }

    public function importPreview()
    {
        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            Response::json(['status' => 'error', 'message' => 'Gagal mengunggah file.']);
            return;
        }

        $fileTmpPath = $_FILES['excel_file']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Remove header row
            array_shift($rows);
            
            $previewData = [];
            $branchModel = new Branch();
            $db = $branchModel->getDb();
            
            foreach ($rows as $row) {
                // If warehouse code is empty, skip
                if (empty($row[1])) continue;
                
                $branchCode = $row[0] ?? '';
                $warehouseCode = $row[1] ?? '';
                $warehouseName = $row[2] ?? '';
                
                // Lookup branch id
                $stmt = $db->prepare("SELECT id, name FROM branches WHERE code = :code");
                $stmt->execute(['code' => $branchCode]);
                $branch = $stmt->fetch();
                
                $previewData[] = [
                    'branch_code' => $branchCode,
                    'branch_name' => $branch ? $branch['name'] : 'Tidak Ditemukan',
                    'branch_id' => $branch ? $branch['id'] : null,
                    'code' => $warehouseCode,
                    'name' => $warehouseName,
                    'status' => $branch ? 'OK' : 'Error: Cabang Tidak Ada'
                ];
            }
            
            $_SESSION['import_warehouses'] = $previewData;

            Response::json([
                'status' => 'success', 
                'data' => $previewData
            ]);
        } catch (\Exception $e) {
            Response::json(['status' => 'error', 'message' => 'Format file tidak didukung: ' . $e->getMessage()]);
        }
    }

    public function importProcess()
    {
        if (!isset($_SESSION['import_warehouses']) || empty($_SESSION['import_warehouses'])) {
            $_SESSION['error'] = "Tidak ada data yang diimpor atau sesi telah kedaluwarsa.";
            Response::redirect('/warehouses');
            return;
        }

        $warehouseModel = new Warehouse();
        $db = $warehouseModel->getDb();
        $successCount = 0;
        
        foreach ($_SESSION['import_warehouses'] as $row) {
            // Skip invalid rows
            if (!$row['branch_id']) continue;

            $code = $row['code'];
            
            // Check if exists
            $stmt = $db->prepare("SELECT id FROM warehouses WHERE code = :code");
            $stmt->execute(['code' => $code]);
            if ($stmt->fetch()) {
                continue; // Skip duplicate
            }

            $warehouseModel->create([
                'branch_id' => $row['branch_id'],
                'code' => $code,
                'name' => $row['name'],
                'is_active' => 1
            ]);
            $successCount++;
        }
        
        unset($_SESSION['import_warehouses']);
        
        $_SESSION['success'] = "$successCount gudang berhasil diimpor.";
        Response::redirect('/warehouses');
    }
}
