<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Branch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BranchController extends BaseController
{
    public function index()
    {
        $branchModel = new Branch();
        $db = $branchModel->getDb();
        
        $stmt = $db->query("
            SELECT b.*, c.name as company_name 
            FROM branches b 
            LEFT JOIN companies c ON b.company_id = c.id 
            ORDER BY b.id DESC
        ");
        $branches = $stmt->fetchAll();

        // Calculate summaries
        $totalBranches = count($branches);
        $totalHubs = 0;
        $totalAgents = 0;
        foreach($branches as $b) {
            if ($b['type'] === 'HUB') $totalHubs++;
            if ($b['type'] === 'AGEN') $totalAgents++;
        }
        
        $this->view('branches/index', [
            'branches' => $branches,
            'totalBranches' => $totalBranches,
            'totalHubs' => $totalHubs,
            'totalAgents' => $totalAgents
        ]);
    }

    public function store(Request $request)
    {
        $code = $request->get('code');
        $name = $request->get('name');
        $type = $request->get('type');
        $city = $request->get('city');
        $address = $request->get('address');
        $phone = $request->get('phone');
        
        if (empty($code) || empty($name) || empty($type)) {
            $_SESSION['error'] = "Kode, Nama, dan Tipe Cabang wajib diisi.";
            Response::redirect('/branches');
            return;
        }

        $branchModel = new Branch();
        $db = $branchModel->getDb();
        $stmt = $db->prepare("SELECT id FROM branches WHERE code = :code");
        $stmt->execute(['code' => $code]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Kode Cabang sudah digunakan.";
            Response::redirect('/branches');
            return;
        }

        $branchModel->create([
            'company_id' => 1,
            'code' => $code,
            'name' => $name,
            'type' => $type,
            'city' => $city,
            'address' => $address,
            'phone' => $phone
        ]);

        $_SESSION['success'] = "Cabang baru berhasil ditambahkan.";
        Response::redirect('/branches');
    }

    public function update(Request $request, $id)
    {
        $code = $request->get('code');
        $name = $request->get('name');
        $type = $request->get('type');
        $city = $request->get('city');
        $address = $request->get('address');
        $phone = $request->get('phone');
        
        if (empty($code) || empty($name) || empty($type)) {
            $_SESSION['error'] = "Kode, Nama, dan Tipe Cabang wajib diisi.";
            Response::redirect('/branches');
            return;
        }

        $branchModel = new Branch();
        $db = $branchModel->getDb();
        
        // Cek duplicate code untuk cabang lain
        $stmt = $db->prepare("SELECT id FROM branches WHERE code = :code AND id != :id");
        $stmt->execute(['code' => $code, 'id' => $id]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Kode Cabang sudah digunakan oleh cabang lain.";
            Response::redirect('/branches');
            return;
        }

        $branchModel->update($id, [
            'code' => $code,
            'name' => $name,
            'type' => $type,
            'city' => $city,
            'address' => $address,
            'phone' => $phone
        ]);

        $_SESSION['success'] = "Data cabang berhasil diperbarui.";
        Response::redirect('/branches');
    }

    public function delete(Request $request, $id)
    {
        $branchModel = new Branch();
        $branchModel->delete($id);
        $_SESSION['success'] = "Cabang berhasil dihapus.";
        Response::redirect('/branches');
    }

    public function export()
    {
        $branchModel = new Branch();
        $db = $branchModel->getDb();
        $stmt = $db->query("SELECT * FROM branches ORDER BY id DESC");
        $branches = $stmt->fetchAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $sheet->setCellValue('A1', 'Kode');
        $sheet->setCellValue('B1', 'Nama Cabang');
        $sheet->setCellValue('C1', 'Tipe');
        $sheet->setCellValue('D1', 'Kota');
        $sheet->setCellValue('E1', 'Telepon');
        $sheet->setCellValue('F1', 'Alamat');

        // Style headers
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($branches as $b) {
            $sheet->setCellValue('A' . $row, $b['code']);
            $sheet->setCellValue('B' . $row, $b['name']);
            $sheet->setCellValue('C' . $row, $b['type']);
            $sheet->setCellValue('D' . $row, $b['city']);
            $sheet->setCellValue('E' . $row, $b['phone']);
            $sheet->setCellValue('F' . $row, $b['address']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Export_Cabang_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
        $writer->save('php://output');
        exit;
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Kode');
        $sheet->setCellValue('B1', 'Nama Cabang');
        $sheet->setCellValue('C1', 'Tipe');
        $sheet->setCellValue('D1', 'Kota');
        $sheet->setCellValue('E1', 'Telepon');
        $sheet->setCellValue('F1', 'Alamat');
        
        // Example row
        $sheet->setCellValue('A2', 'BDG01');
        $sheet->setCellValue('B2', 'Cabang Bandung Pusat');
        $sheet->setCellValue('C2', 'HUB');
        $sheet->setCellValue('D2', 'Bandung');
        $sheet->setCellValue('E2', '081234567890');
        $sheet->setCellValue('F2', 'Jl. Braga No 1');

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Cabang.xlsx';

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
            foreach ($rows as $row) {
                // If code is empty, skip
                if (empty($row[0])) continue;
                
                $previewData[] = [
                    'code' => $row[0] ?? '',
                    'name' => $row[1] ?? '',
                    'type' => $row[2] ?? '',
                    'city' => $row[3] ?? '',
                    'phone' => $row[4] ?? '',
                    'address' => $row[5] ?? ''
                ];
            }
            
            // Simpan ke session sementara agar bisa diproses nanti
            $_SESSION['import_branches'] = $previewData;

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
        if (!isset($_SESSION['import_branches']) || empty($_SESSION['import_branches'])) {
            $_SESSION['error'] = "Tidak ada data yang diimpor atau sesi telah kedaluwarsa.";
            Response::redirect('/branches');
            return;
        }

        $branchModel = new Branch();
        $db = $branchModel->getDb();
        $successCount = 0;
        
        foreach ($_SESSION['import_branches'] as $row) {
            $code = $row['code'];
            
            // Check if exists
            $stmt = $db->prepare("SELECT id FROM branches WHERE code = :code");
            $stmt->execute(['code' => $code]);
            if ($stmt->fetch()) {
                continue; // Skip duplicate
            }

            $branchModel->create([
                'company_id' => 1,
                'code' => $code,
                'name' => $row['name'],
                'type' => $row['type'],
                'city' => $row['city'],
                'phone' => $row['phone'],
                'address' => $row['address']
            ]);
            $successCount++;
        }
        
        unset($_SESSION['import_branches']);
        
        $_SESSION['success'] = "$successCount cabang berhasil diimpor.";
        Response::redirect('/branches');
    }
}
