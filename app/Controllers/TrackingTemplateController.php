<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\User;

class TrackingTemplateController extends BaseController
{
    /**
     * GET /api/tracking-templates
     * Return all templates (global + user's own private)
     */
    public function index(Request $request)
    {
        $db = (new User())->getDb();
        $userId = $_SESSION['user_id'] ?? 0;

        $stmt = $db->prepare("
            SELECT id, name, status, description, is_global, created_by
            FROM tracking_templates
            WHERE is_global = 1 OR created_by = :uid
            ORDER BY is_global DESC, status ASC, name ASC
        ");
        $stmt->execute(['uid' => $userId]);
        $templates = $stmt->fetchAll();

        Response::json(['success' => true, 'data' => $templates]);
    }

    /**
     * POST /api/tracking-templates
     * Save a new template { name, status, description, is_global }
     */
    public function store(Request $request)
    {
        $name        = trim($request->get('name', ''));
        $status      = trim($request->get('status', ''));
        $description = trim($request->get('description', ''));
        $isGlobal    = (int) $request->get('is_global', 0);
        $userId      = $_SESSION['user_id'] ?? 0;

        if (empty($name) || empty($status) || empty($description)) {
            Response::json(['success' => false, 'message' => 'Nama, status, dan deskripsi wajib diisi.']);
            return;
        }

        // Non-admin can only create private templates
        $roleId = $_SESSION['role_id'] ?? 4;
        if ($roleId > 2) {
            $isGlobal = 0;
        }

        $db = (new User())->getDb();
        $stmt = $db->prepare("
            INSERT INTO tracking_templates (name, status, description, is_global, created_by)
            VALUES (:name, :status, :description, :is_global, :created_by)
        ");
        $ok = $stmt->execute([
            'name'        => $name,
            'status'      => $status,
            'description' => $description,
            'is_global'   => $isGlobal,
            'created_by'  => $userId,
        ]);

        if ($ok) {
            Response::json(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Template berhasil disimpan.']);
        } else {
            Response::json(['success' => false, 'message' => 'Gagal menyimpan template.']);
        }
    }

    /**
     * POST /api/tracking-templates/delete/{id}
     * Delete a template (only own private or admin for global)
     */
    public function delete(Request $request, $id)
    {
        $db     = (new User())->getDb();
        $userId = $_SESSION['user_id'] ?? 0;
        $roleId = $_SESSION['role_id'] ?? 4;

        // Fetch template
        $stmt = $db->prepare("SELECT * FROM tracking_templates WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $tpl = $stmt->fetch();

        if (!$tpl) {
            Response::json(['success' => false, 'message' => 'Template tidak ditemukan.']);
            return;
        }

        // Only admin (role 1,2) can delete global templates; user can delete own templates
        if ($tpl['is_global'] && $roleId > 2) {
            Response::json(['success' => false, 'message' => 'Tidak dapat menghapus template global.']);
            return;
        }
        if (!$tpl['is_global'] && $tpl['created_by'] != $userId && $roleId > 2) {
            Response::json(['success' => false, 'message' => 'Akses ditolak.']);
            return;
        }

        $db->prepare("DELETE FROM tracking_templates WHERE id = :id")->execute(['id' => $id]);
        Response::json(['success' => true, 'message' => 'Template berhasil dihapus.']);
    }
}
