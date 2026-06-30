<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\AuditLog;

class AuditLogController extends BaseController
{
    public function index()
    {
        // Only Admin (1) or Owner (2) can access Audit Logs
        $roleId = $_SESSION['role_id'] ?? 0;
        if ($roleId != 1 && $roleId != 2) {
            $_SESSION['error'] = "Akses ditolak. Hanya Admin dan Owner yang dapat melihat Audit Trail.";
            Response::redirect('/dashboard');
        }

        $this->view('audit/index');
    }

    public function datatable(Request $request)
    {
        $roleId = $_SESSION['role_id'] ?? 0;
        if ($roleId != 1 && $roleId != 2) {
            Response::json(['error' => 'Unauthorized'], 403);
            return;
        }

        $auditModel = new AuditLog();
        $db = $auditModel->getDb();

        $draw   = $_GET['draw'] ?? 1;
        $start  = $_GET['start'] ?? 0;
        $length = $_GET['length'] ?? 10;
        $search = $_GET['search']['value'] ?? '';
        
        $whereSql = "1=1";
        $params = [];

        if (!empty($search)) {
            $whereSql .= " AND (a.action LIKE :search OR a.entity_type LIKE :search OR u.fullname LIKE :search OR a.ip_address LIKE :search)";
            $params['search'] = "%$search%";
        }

        $totalRecords = $db->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();

        $stmtTotal = $db->prepare("SELECT COUNT(*) FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id WHERE $whereSql");
        $stmtTotal->execute($params);
        $totalFiltered = $stmtTotal->fetchColumn();

        $sql = "SELECT a.*, u.fullname as user_name 
                FROM audit_logs a
                LEFT JOIN users u ON a.user_id = u.id
                WHERE $whereSql
                ORDER BY a.id DESC
                LIMIT " . intval($length) . " OFFSET " . intval($start);
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        // Format dates and json to be cleaner in the UI
        foreach ($data as &$row) {
            $row['created_at'] = date('d/m/Y H:i:s', strtotime($row['created_at']));
            $row['user_name'] = $row['user_name'] ?: 'System';
        }

        Response::json([
            "draw"            => intval($draw),
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        ]);
    }
}
