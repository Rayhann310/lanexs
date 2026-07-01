<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\LandingPartner;

class LandingPartnerController extends BaseController
{
    public function index()
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            $_SESSION['error'] = "Akses ditolak.";
            Response::redirect('/dashboard');
            return;
        }

        $model = new LandingPartner();
        $partners = $model->all();
        
        $this->view('settings/partners', ['partners' => $partners]);
    }

    public function store(Request $request)
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            Response::redirect('/dashboard');
            return;
        }

        $model = new LandingPartner();
        
        $data = [
            'name' => $request->get('name')
        ];

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $filename = 'partner_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $uploadDir = dirname($_SERVER['SCRIPT_FILENAME']) . '/assets/images/partners/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $filename)) {
                $data['logo_path'] = '/assets/images/partners/' . $filename;
            }
        }

        if ($model->create($data)) {
            $_SESSION['success'] = "Mitra/Klien berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambahkan Mitra/Klien.";
        }
        
        Response::redirect('/settings/partners');
    }

    public function delete(Request $request, $id)
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            Response::redirect('/dashboard');
            return;
        }

        $model = new LandingPartner();
        $partner = $model->find($id);
        
        if ($partner) {
            $logoPath = dirname($_SERVER['SCRIPT_FILENAME']) . $partner['logo_path'];
            if (!empty($partner['logo_path']) && file_exists($logoPath)) {
                unlink($logoPath);
            }
            $model->delete($id);
            $_SESSION['success'] = "Mitra/Klien berhasil dihapus.";
        }
        
        Response::redirect('/settings/partners');
    }
}
