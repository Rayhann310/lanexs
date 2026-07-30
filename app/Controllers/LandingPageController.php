<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\LandingPage;

class LandingPageController extends BaseController
{
    private function checkAdmin()
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            $_SESSION['error'] = "Akses ditolak.";
            Response::redirect('/dashboard');
            exit;
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $model = new LandingPage();
        $pages = $model->all();
        $this->view('settings/pages/index', ['pages' => $pages]);
    }

    public function edit(Request $request, $id)
    {
        $this->checkAdmin();
        $model = new LandingPage();
        $page = $model->find($id);
        if (!$page) {
            $_SESSION['error'] = "Halaman tidak ditemukan.";
            Response::redirect('/settings/pages');
            return;
        }
        $this->view('settings/pages/edit', ['page' => $page]);
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $model = new LandingPage();
        $data = [
            'title'   => $request->get('title'),
            'content' => $request->get('content'), // raw HTML from QuillJS
        ];

        // Save extra settings for specific pages (like sejarah-perusahaan or page_xxx)
        $settingModel = new \App\Models\Setting();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'sejarah_') === 0 || strpos($key, 'page_') === 0) {
                $settingModel->set($key, $value);
            }
        }

        // Handle File Uploads
        $uploadDir = __DIR__ . '/../../public/uploads/pages/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        foreach ($_FILES as $key => $file) {
            if ($file['error'] === UPLOAD_ERR_OK && (strpos($key, 'sejarah_') === 0 || strpos($key, 'page_') === 0)) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                if (in_array($ext, $allowed)) {
                    $filename = $key . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $settingModel->set($key, BASE_URL . '/uploads/pages/' . $filename);
                    }
                }
            }
        }
        if ($model->update($id, $data)) {
            $_SESSION['success'] = "Halaman berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui halaman.";
        }
        Response::redirect('/settings/pages/edit/' . $id);
    }
}
