<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Models\Package;
use App\Models\Transaction;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        $branchId = $_SESSION['branch_id'] ?? null;
        $roleId   = $_SESSION['role_id']   ?? null;

        try {
            $db = (new Package())->getDb();

            // Build branch filter for non-superadmin
            $branchFilter = ($roleId == 1) ? '' : "WHERE p.origin_branch_id = " . intval($branchId);

            $sql = "
                SELECT
                    COUNT(*)                                         AS total_paket,
                    SUM(CASE WHEN DATE(p.created_at) = CURDATE() THEN 1 ELSE 0 END)  AS hari_ini,
                    SUM(CASE WHEN p.status IN ('TRANSIT','PICKUP','DELIVERY') THEN 1 ELSE 0 END) AS transit,
                    SUM(CASE WHEN p.status = 'SELESAI' THEN 1 ELSE 0 END)            AS selesai,
                    SUM(CASE WHEN p.status = 'PENDING' THEN 1 ELSE 0 END)            AS pending,
                    COALESCE(SUM(CASE WHEN p.payment_status = 'PAID' THEN p.price END), 0) AS pendapatan
                FROM packages p
                $branchFilter
            ";
            $row = $db->query($sql)->fetch();

            // Recent 5 packages
            $recentSql = "
                SELECT p.resi, p.sender_name, p.receiver_name, p.status, p.created_at,
                       bo.name as origin, bd.name as dest
                FROM packages p
                LEFT JOIN branches bo ON p.origin_branch_id = bo.id
                LEFT JOIN branches bd ON p.destination_branch_id = bd.id
                " . ($roleId != 1 ? "WHERE p.origin_branch_id = " . intval($branchId) : "") . "
                ORDER BY p.created_at DESC LIMIT 5
            ";
            $recentPackages = $db->query($recentSql)->fetchAll();

            // Cash balance (current branch)
            $balanceSQL = ($roleId == 1)
                ? "SELECT COALESCE(SUM(CASE WHEN type='INCOME' THEN amount ELSE -amount END),0) as bal FROM transactions"
                : "SELECT COALESCE(SUM(CASE WHEN type='INCOME' THEN amount ELSE -amount END),0) as bal FROM transactions WHERE branch_id = " . intval($branchId);
            $balance = $db->query($balanceSQL)->fetchColumn();

            $data = [
                'totalPaket'     => number_format((float)($row['total_paket'] ?? 0)),
                'paketHariIni'   => number_format((float)($row['hari_ini'] ?? 0)),
                'paketTransit'   => number_format((float)($row['transit'] ?? 0)),
                'paketSelesai'   => number_format((float)($row['selesai'] ?? 0)),
                'paketPending'   => number_format((float)($row['pending'] ?? 0)),
                'pendapatan'     => 'Rp ' . number_format((float)($row['pendapatan'] ?? 0), 0, ',', '.'),
                'saldoKas'       => 'Rp ' . number_format((float)($balance ?? 0), 0, ',', '.'),
                'recentPackages' => $recentPackages,
            ];
        } catch (\Exception $e) {
            // Tables might not exist yet — use safe defaults
            $data = [
                'totalPaket'     => '—',
                'paketHariIni'   => '—',
                'paketTransit'   => '—',
                'paketSelesai'   => '—',
                'paketPending'   => '—',
                'pendapatan'     => '—',
                'saldoKas'       => '—',
                'recentPackages' => [],
            ];
        }

        return $this->view('dashboard/index', $data);
    }
}
