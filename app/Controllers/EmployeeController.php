<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\User;
use App\Models\Role;
use App\Models\Branch;

class EmployeeController extends BaseController
{
    public function index()
    {
        $userModel = new User();
        $employees = $userModel->getAllWithRelations();
        
        $roleModel = new Role();
        $roles = $roleModel->all();
        
        $branchModel = new Branch();
        $branches = $branchModel->all();

        $this->view('employees/index', [
            'employees' => $employees,
            'roles' => $roles,
            'branches' => $branches
        ]);
    }

    public function store(Request $request)
    {
        $userModel = new User();
        
        // Basic validation
        $username = $request->get('username');
        if ($userModel->findByUsername($username)) {
            $_SESSION['error'] = "Username sudah digunakan.";
            Response::redirect('/employees');
            return;
        }

        $data = [
            'username' => $username,
            'password' => password_hash($request->get('password'), PASSWORD_BCRYPT),
            'fullname' => $request->get('fullname'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'role_id' => $request->get('role_id'),
            'branch_id' => $request->get('branch_id') ?: null,
            'is_active' => $request->get('is_active', 1)
        ];
        
        if ($userModel->create($data)) {
            $_SESSION['success'] = "Data karyawan berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambahkan karyawan.";
        }
        
        Response::redirect('/employees');
    }

    public function update(Request $request, $id)
    {
        $userModel = new User();
        
        $data = [
            'fullname' => $request->get('fullname'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'role_id' => $request->get('role_id'),
            'branch_id' => $request->get('branch_id') ?: null,
            'is_active' => $request->get('is_active', 1)
        ];
        
        // Update password if provided
        if (!empty($request->get('password'))) {
            $data['password'] = password_hash($request->get('password'), PASSWORD_BCRYPT);
        }

        if ($userModel->update($id, $data)) {
            $_SESSION['success'] = "Data karyawan berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui data karyawan.";
        }
        
        Response::redirect('/employees');
    }

    public function delete(Request $request, $id)
    {
        $userModel = new User();
        if ($userModel->delete($id)) {
            $_SESSION['success'] = "Karyawan berhasil dihapus (soft delete).";
        } else {
            $_SESSION['error'] = "Gagal menghapus karyawan.";
        }
        Response::redirect('/employees');
    }

    // ── Excel Export ──────────────────────────────────────────────────────────
    public function exportExcel()
    {
        $employees = (new User())->getAllWithRelations();

        ['spreadsheet' => $sp, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Data Karyawan');

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DATA KARYAWAN — PT LANEXS EXPRESS INDONESIA');
        $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '0f172a']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);
        $sheet->getRowDimension(1)->setRowHeight(26);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Diekspor: ' . date('d/m/Y H:i') . ' | Total: ' . count($employees) . ' karyawan');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('64748b');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headers = ['No', 'Username', 'Nama Lengkap', 'Email', 'Telepon', 'Role', 'Cabang'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 3, '0f172a');
        $sheet->freezePane('A4');

        $row = 4;
        foreach ($employees as $i => $e) {
            $sheet->fromArray([$i + 1, $e['username'], $e['fullname'], $e['email'], $e['phone'], $e['role_name'] ?? '-', $e['branch_name'] ?? 'HQ'], null, 'A' . $row);
            $row++;
        }
        \App\Helpers\ExcelHelper::styleDataRows($sheet, 4, $row - 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));

        \App\Helpers\ExcelHelper::download($sp, 'Export_Karyawan_' . date('Ymd_His') . '.xlsx');
    }

    // ── Excel Template ────────────────────────────────────────────────────────
    public function downloadTemplate()
    {
        $roles    = (new Role())->all();
        $branches = (new Branch())->all();

        ['spreadsheet' => $sp, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Template Import Karyawan');

        $headers = ['username', 'fullname', 'email', 'phone', 'password', 'role_id', 'branch_id'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 1, '0f172a');

        $examples = [
            ['joko.susilo', 'Joko Susilo', 'joko@lanex.com', '081200001111', 'password123', 3, 1],
            ['ani.rahayu',  'Ani Rahayu',  'ani@lanex.com',  '081200002222', 'password123', 4, 2],
        ];
        foreach ($examples as $i => $r) {
            $sheet->fromArray($r, null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::styleDataRows($sheet, 2, count($examples) + 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));

        $noteRow = count($examples) + 3;
        $sheet->mergeCells('A' . $noteRow . ':G' . $noteRow);
        $sheet->setCellValue('A' . $noteRow, '⚠ CATATAN: password akan di-hash otomatis. Lihat sheet "Daftar Role" dan "Daftar Cabang" untuk ID yang valid.');
        $sheet->getStyle('A' . $noteRow)->applyFromArray(['font' => ['italic' => true, 'color' => ['rgb' => 'b45309']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef3c7']]]);

        // Sheet 2: Daftar Role
        $roleSheet = $sp->createSheet();
        $roleSheet->setTitle('Daftar Role (ID)');
        \App\Helpers\ExcelHelper::writeHeaderRow($roleSheet, ['ID Role', 'Nama Role'], 1, '0f172a');
        foreach ($roles as $i => $r) {
            $roleSheet->fromArray([$r['id'], $r['name']], null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::autoFitColumns($roleSheet, 2);

        // Sheet 3: Daftar Cabang
        $branchSheet = $sp->createSheet();
        $branchSheet->setTitle('Daftar Cabang (ID)');
        \App\Helpers\ExcelHelper::writeHeaderRow($branchSheet, ['ID Cabang', 'Nama Cabang'], 1, '065f46');
        foreach ($branches as $i => $b) {
            $branchSheet->fromArray([$b['id'], $b['name']], null, 'A' . ($i + 2));
        }
        \App\Helpers\ExcelHelper::autoFitColumns($branchSheet, 2);

        $sp->setActiveSheetIndex(0);
        \App\Helpers\ExcelHelper::download($sp, 'Template_Import_Karyawan_LANEXS.xlsx');
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

        $required = ['username', 'fullname', 'email', 'phone', 'password', 'role_id', 'branch_id'];
        $rows = []; $errors = [];

        $existingUsernames = array_column((new User())->all(), 'username');

        foreach ($parsed['rows'] as $i => $rawRow) {
            $row     = \App\Helpers\ExcelHelper::mapRow($parsed['headers'], $rawRow);
            $missing = array_diff($required, array_keys($row));
            if (!empty($missing)) { $errors[] = 'Baris ' . ($i + 2) . ': Header tidak sesuai template.'; continue; }

            $row['_duplicate'] = in_array($row['username'], $existingUsernames);
            $row['_valid']     = !empty($row['username']) && !empty($row['fullname']) && !$row['_duplicate'];
            $row['_line']      = $i + 2;
            $rows[] = $row;
        }

        Response::json(['success' => true, 'rows' => $rows, 'errors' => $errors, 'total' => count($rows)]);
    }

    // ── Import Process ────────────────────────────────────────────────────────
    public function importProcess(Request $request)
    {
        $rows = json_decode($request->get('rows'), true);
        if (!is_array($rows)) { Response::json(['success' => false, 'message' => 'Data tidak valid.']); return; }

        $model = new User();
        $success = $failed = 0;

        foreach ($rows as $row) {
            if (!($row['_valid'] ?? false)) { $failed++; continue; }
            $ok = $model->create([
                'username'  => trim($row['username']),
                'fullname'  => trim($row['fullname']),
                'email'     => trim($row['email']),
                'phone'     => trim($row['phone']),
                'password'  => password_hash($row['password'], PASSWORD_BCRYPT),
                'role_id'   => intval($row['role_id']),
                'branch_id' => !empty($row['branch_id']) ? intval($row['branch_id']) : null,
                'is_active' => 1,
            ]);
            $ok ? $success++ : $failed++;
        }

        \App\Services\AuditLogger::log('IMPORT_EXCEL', 'Employee', null, null, ['imported' => $success]);
        Response::json(['success' => true, 'imported' => $success, 'failed' => $failed, 'message' => "$success karyawan berhasil diimport."]);
    }
}

