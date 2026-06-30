<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Package;

class AnalyticsController extends BaseController
{
    public function index()
    {
        // Only for Super Admin (1) and Owner (2)
        if (!in_array($_SESSION['role_id'], [1, 2])) {
            $_SESSION['error'] = "Akses ditolak.";
            Response::redirect('/dashboard');
        }

        $db = (new Package())->getDb();

        // ─── Top KPI Cards ───────────────────────────────────────────────
        $kpiSql = "
            SELECT
                COUNT(*)                                        AS total_packages,
                SUM(CASE WHEN status = 'SELESAI' THEN 1 END)   AS delivered,
                SUM(CASE WHEN status = 'PENDING' THEN 1 END)   AS pending,
                SUM(CASE WHEN status = 'RETUR'   THEN 1 END)   AS retur,
                COALESCE(SUM(price), 0)                         AS gross_revenue,
                COALESCE(SUM(CASE WHEN payment_status = 'PAID' THEN price END), 0) AS paid_revenue,
                COALESCE(SUM(CASE WHEN payment_status IN ('UNPAID','COD') THEN price END), 0) AS unpaid_revenue
            FROM packages
        ";
        $kpi = $db->query($kpiSql)->fetch();

        // ─── Revenue trend last 30 days ──────────────────────────────────
        $revTrendSql = "
            SELECT DATE(created_at) as day, COALESCE(SUM(price),0) as revenue, COUNT(*) as pkgs
            FROM packages
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY day ASC
        ";
        $revTrend = $db->query($revTrendSql)->fetchAll();

        // ─── Packages by status ──────────────────────────────────────────
        $statusSql = "
            SELECT status, COUNT(*) as total
            FROM packages
            GROUP BY status
            ORDER BY total DESC
        ";
        $byStatus = $db->query($statusSql)->fetchAll();

        // ─── Top 5 origin branches (volume) ─────────────────────────────
        $topBranchSql = "
            SELECT b.name, COUNT(p.id) as total, COALESCE(SUM(p.price),0) as revenue
            FROM packages p
            JOIN branches b ON p.origin_branch_id = b.id
            GROUP BY p.origin_branch_id, b.name
            ORDER BY total DESC
            LIMIT 5
        ";
        $topBranches = $db->query($topBranchSql)->fetchAll();

        // ─── Payment method split ─────────────────────────────────────────
        $payMethodSql = "
            SELECT payment_type, COUNT(*) as total, COALESCE(SUM(price),0) as amount
            FROM packages
            GROUP BY payment_type
        ";
        $payMethods = $db->query($payMethodSql)->fetchAll();

        // ─── Monthly comparison (current vs last month) ──────────────────
        $monthlySql = "
            SELECT
                SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN price ELSE 0 END) as this_month,
                SUM(CASE WHEN MONTH(created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) THEN price ELSE 0 END) as last_month,
                COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) as this_month_pkgs,
                COUNT(CASE WHEN MONTH(created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) THEN 1 END) as last_month_pkgs
            FROM packages
        ";
        $monthly = $db->query($monthlySql)->fetch();

        $this->view('analytics/index', [
            'kpi'         => $kpi,
            'revTrend'    => $revTrend,
            'byStatus'    => $byStatus,
            'topBranches' => $topBranches,
            'payMethods'  => $payMethods,
            'monthly'     => $monthly,
        ]);
    }
}
