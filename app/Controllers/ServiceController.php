<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Service;

class ServiceController extends BaseController
{
    public function index()
    {
        $model = new Service();
        $db = $model->getDb();
        $services = $db->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
        $this->view('services/index', ['services' => $services]);
    }

    public function store(Request $request)
    {
        $code = strtoupper(trim($request->get('code')));
        $name = trim($request->get('name'));
        $description = trim($request->get('description'));
        $estimated_days = (int)$request->get('estimated_days') ?: 1;

        if (empty($code) || empty($name)) {
            $_SESSION['error'] = "Kode dan Nama Layanan wajib diisi.";
            Response::redirect('/services');
            return;
        }

        $model = new Service();
        $db = $model->getDb();

        $stmt = $db->prepare("SELECT id FROM services WHERE code = :code");
        $stmt->execute(['code' => $code]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Kode layanan sudah digunakan.";
            Response::redirect('/services');
            return;
        }

        $model->create([
            'code' => $code,
            'name' => $name,
            'description' => $description,
            'estimated_days' => $estimated_days,
            'is_active' => 1,
        ]);

        $_SESSION['success'] = "Layanan berhasil ditambahkan.";
        Response::redirect('/services');
    }

    public function update(Request $request, $id)
    {
        $code = strtoupper(trim($request->get('code')));
        $name = trim($request->get('name'));
        $description = trim($request->get('description'));
        $estimated_days = (int)$request->get('estimated_days') ?: 1;
        $is_active = $request->get('is_active') ? 1 : 0;

        if (empty($code) || empty($name)) {
            $_SESSION['error'] = "Kode dan Nama Layanan wajib diisi.";
            Response::redirect('/services');
            return;
        }

        $model = new Service();
        $db = $model->getDb();

        $stmt = $db->prepare("SELECT id FROM services WHERE code = :code AND id != :id");
        $stmt->execute(['code' => $code, 'id' => $id]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Kode layanan sudah digunakan oleh layanan lain.";
            Response::redirect('/services');
            return;
        }

        $model->update($id, [
            'code' => $code,
            'name' => $name,
            'description' => $description,
            'estimated_days' => $estimated_days,
            'is_active' => $is_active,
        ]);

        $_SESSION['success'] = "Layanan berhasil diperbarui.";
        Response::redirect('/services');
    }

    public function delete(Request $request, $id)
    {
        $model = new Service();
        $model->delete($id);
        $_SESSION['success'] = "Layanan berhasil dihapus.";
        Response::redirect('/services');
    }

    public function apiList()
    {
        $model = new Service();
        $db = $model->getDb();
        $services = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
        Response::json(['status' => 'success', 'data' => $services]);
    }
}
