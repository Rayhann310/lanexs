<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Transaction;
use App\Models\Package;
use App\Models\Branch;

class FinanceController extends BaseController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }

        $transactionModel = new Transaction();
        $packageModel = new Package();
        
        $branchId = $_SESSION['branch_id'];
        $roleId = $_SESSION['role_id'];

        // Get transactions
        if ($roleId == 1 || $roleId == 2) {
            // Super Admin / Owner can see all or maybe just a specific branch. 
            // For simplicity, let's show all or group them, but we'll show all for now.
            $sql = "SELECT t.*, b.name as branch_name 
                    FROM transactions t 
                    LEFT JOIN branches b ON t.branch_id = b.id 
                    ORDER BY t.created_at DESC LIMIT 100";
            $stmt = $transactionModel->getDb()->query($sql);
            $transactions = $stmt->fetchAll();
            $balance = 0; // Global balance isn't calculated simply, skip or sum all.
            // Let's sum all for super admin
            $sqlBal = "SELECT 
                        SUM(CASE WHEN type = 'INCOME' THEN amount ELSE 0 END) -
                        SUM(CASE WHEN type = 'EXPENSE' THEN amount ELSE 0 END) as total 
                       FROM transactions";
            $balance = $transactionModel->getDb()->query($sqlBal)->fetchColumn() ?: 0;
        } else {
            // Admin Cabang
            $sql = "SELECT t.*, b.name as branch_name 
                    FROM transactions t 
                    LEFT JOIN branches b ON t.branch_id = b.id 
                    WHERE t.branch_id = :bid 
                    ORDER BY t.created_at DESC LIMIT 100";
            $stmt = $transactionModel->getDb()->prepare($sql);
            $stmt->execute(['bid' => $branchId]);
            $transactions = $stmt->fetchAll();
            $balance = $transactionModel->getBalance($branchId);
        }

        // Get COD Packages waiting for settlement (Delivered but UNPAID or COD status)
        // This is a simplified logic. Real-world COD is complex.
        $codSql = "SELECT p.*, bo.city as origin, bd.city as dest 
                   FROM packages p
                   LEFT JOIN branches bo ON p.origin_branch_id = bo.id
                   LEFT JOIN branches bd ON p.destination_branch_id = bd.id
                   WHERE p.payment_status = 'COD' AND p.status = 'DELIVERED'";
                   
        // If branch admin, only see COD collected at their branch (destination)
        if ($roleId == 3 || $roleId == 4) {
            $codSql .= " AND p.destination_branch_id = " . intval($branchId);
        }
        
        $codPackages = $packageModel->getDb()->query($codSql)->fetchAll();

        $this->view('finance/index', [
            'transactions' => $transactions,
            'balance' => $balance,
            'codPackages' => $codPackages
        ]);
    }

    public function settleCod(Request $request)
    {
        $packageId = $request->get('package_id');
        $branchId = $_SESSION['branch_id'];

        if (!$packageId) {
            $_SESSION['error'] = "Paket tidak valid.";
            Response::redirect('/finance');
        }

        $packageModel = new Package();
        $package = $packageModel->find($packageId);

        if ($package && $package['payment_status'] == 'COD') {
            // Update package
            $packageModel->update($packageId, ['payment_status' => 'PAID']);

            // Insert income transaction to branch
            $transactionModel = new Transaction();
            $transactionModel->create([
                'branch_id' => $branchId,
                'type' => 'INCOME',
                'amount' => $package['price'],
                'reference_type' => 'COD_DEPOSIT',
                'reference_id' => $package['resi'],
                'description' => "Setoran tunai COD Resi: " . $package['resi'],
                'created_by' => $_SESSION['user_id']
            ]);
            
            \App\Services\AuditLogger::log('SETTLE_COD', 'Transaction', null, ['payment_status' => 'COD'], ['payment_status' => 'PAID', 'resi' => $package['resi']]);

            $_SESSION['success'] = "Setoran COD Resi {$package['resi']} berhasil dibukukan.";
        } else {
            $_SESSION['error'] = "Gagal memproses setoran COD.";
        }

        Response::redirect('/finance');
    }

    public function export()
    {
        $transactionModel = new \App\Models\Transaction();
        $db               = $transactionModel->getDb();

        $roleId   = $_SESSION['role_id'];
        $branchId = $_SESSION['branch_id'];

        $sql    = "SELECT t.*, b.name as branch_name, u.fullname as creator
                   FROM transactions t
                   LEFT JOIN branches b ON t.branch_id = b.id
                   LEFT JOIN users u ON t.created_by = u.id
                   WHERE 1=1 ";
        $params = [];
        if ($roleId != 1 && $roleId != 2) {
            $sql .= " AND t.branch_id = :bid ";
            $params['bid'] = $branchId;
        }
        $sql .= " ORDER BY t.created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $transactions = $stmt->fetchAll();

        // Totals
        $totalIn  = array_sum(array_map(fn($t) => $t['type'] === 'INCOME' ? (float)$t['amount'] : 0, $transactions));
        $totalOut = array_sum(array_map(fn($t) => $t['type'] === 'EXPENSE' ? (float)$t['amount'] : 0, $transactions));

        ['spreadsheet' => $spreadsheet, 'sheet' => $sheet] = \App\Helpers\ExcelHelper::createWorkbook('Laporan Keuangan');

        // Title
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'LAPORAN MUTASI KAS — PT LANEX EXPRESS INDONESIA');
        $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '065f46']], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Periode: ' . date('d/m/Y H:i') . '  |  Total ' . count($transactions) . ' transaksi');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('64748b');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Headers
        $headers = ['No', 'Tanggal', 'Cabang', 'Tipe', 'Referensi', 'Deskripsi', 'Nominal (Rp)', 'Oleh'];
        \App\Helpers\ExcelHelper::writeHeaderRow($sheet, $headers, 3, '065f46');
        $sheet->freezePane('A4');

        $row = 4;
        foreach ($transactions as $i => $t) {
            $sheet->fromArray([
                $i + 1,
                date('d/m/Y H:i', strtotime($t['created_at'])),
                $t['branch_name'],
                $t['type'],
                ($t['reference_type'] ?? '') . ' #' . ($t['reference_id'] ?? ''),
                $t['description'],
                (float)$t['amount'],
                $t['creator'],
            ], null, 'A' . $row);

            // Color tipe cell
            $typeColor = $t['type'] === 'INCOME' ? 'd1fae5' : 'fee2e2';
            $sheet->getStyle('D' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($typeColor);
            $row++;
        }

        \App\Helpers\ExcelHelper::styleDataRows($sheet, 4, $row - 1, count($headers));
        \App\Helpers\ExcelHelper::autoFitColumns($sheet, count($headers));
        $sheet->getStyle('G4:G' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0');

        // Summary footer
        $sheet->mergeCells('A' . ($row + 1) . ':F' . ($row + 1));
        $sheet->setCellValue('A' . ($row + 1), 'TOTAL PEMASUKAN');
        $sheet->setCellValue('G' . ($row + 1), $totalIn);
        $sheet->getStyle('A' . ($row + 1) . ':H' . ($row + 1))->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'd1fae5']]]);
        $sheet->getStyle('G' . ($row + 1))->getNumberFormat()->setFormatCode('#,##0');

        $sheet->mergeCells('A' . ($row + 2) . ':F' . ($row + 2));
        $sheet->setCellValue('A' . ($row + 2), 'TOTAL PENGELUARAN');
        $sheet->setCellValue('G' . ($row + 2), $totalOut);
        $sheet->getStyle('A' . ($row + 2) . ':H' . ($row + 2))->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']]]);
        $sheet->getStyle('G' . ($row + 2))->getNumberFormat()->setFormatCode('#,##0');

        $sheet->mergeCells('A' . ($row + 3) . ':F' . ($row + 3));
        $sheet->setCellValue('A' . ($row + 3), 'SALDO BERSIH');
        $sheet->setCellValue('G' . ($row + 3), $totalIn - $totalOut);
        $sheet->getStyle('A' . ($row + 3) . ':H' . ($row + 3))->applyFromArray(['font' => ['bold' => true, 'size' => 11], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dbeafe']]]);
        $sheet->getStyle('G' . ($row + 3))->getNumberFormat()->setFormatCode('#,##0');

        \App\Helpers\ExcelHelper::download($spreadsheet, 'Laporan_Keuangan_' . date('Ymd_His') . '.xlsx');
    }
}
