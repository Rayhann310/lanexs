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
        if ($model->update($id, $data)) {
            $_SESSION['success'] = "Halaman berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui halaman.";
        }
        Response::redirect('/settings/pages/edit/' . $id);
    }
}
