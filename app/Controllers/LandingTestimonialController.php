<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\LandingTestimonial;

class LandingTestimonialController extends BaseController
{
    public function index()
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            $_SESSION['error'] = "Akses ditolak.";
            Response::redirect('/dashboard');
            return;
        }

        $model = new LandingTestimonial();
        $testimonials = $model->all();
        
        $this->view('settings/testimonials', ['testimonials' => $testimonials]);
    }

    public function store(Request $request)
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            Response::redirect('/dashboard');
            return;
        }

        $model = new LandingTestimonial();
        
        $name = $request->get('name');
        
        // Generate initials automatically
        $words = explode(" ", $name);
        $initials = "";
        foreach ($words as $w) {
            if (strlen($w) > 0) $initials .= strtoupper($w[0]);
        }
        $initials = substr($initials, 0, 2);

        $data = [
            'name' => $name,
            'position' => $request->get('position'),
            'content' => $request->get('content') ?? '',
            'rating' => $request->get('rating') ?: 5,
            'avatar_initials' => $initials,
            'display_type' => $request->get('display_type') ?? 'text'
        ];

        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/public/uploads/testimonials/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileInfo = pathinfo($_FILES['logo']['name']);
            $extension = strtolower($fileInfo['extension']);
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $fileName = 'logo_' . uniqid() . '_' . time() . '.' . $extension;
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                    $data['logo'] = '/uploads/testimonials/' . $fileName;
                }
            }
        }

        if ($model->create($data)) {
            $_SESSION['success'] = "Testimoni berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambahkan Testimoni.";
        }
        
        Response::redirect('/settings/testimonials');
    }

    public function update(Request $request, $id)
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            Response::redirect('/dashboard');
            return;
        }

        $model = new LandingTestimonial();
        $testi = $model->find($id);
        
        if (!$testi) {
            $_SESSION['error'] = "Testimoni tidak ditemukan.";
            Response::redirect('/settings/testimonials');
            return;
        }
        
        $name = $request->get('name');
        
        // Generate initials automatically
        $words = explode(" ", $name);
        $initials = "";
        foreach ($words as $w) {
            if (strlen($w) > 0) $initials .= strtoupper($w[0]);
        }
        $initials = substr($initials, 0, 2);

        $data = [
            'name' => $name,
            'position' => $request->get('position'),
            'content' => $request->get('content') ?? '',
            'rating' => $request->get('rating') ?: 5,
            'avatar_initials' => $initials,
            'display_type' => $request->get('display_type') ?? 'text'
        ];

        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/public/uploads/testimonials/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileInfo = pathinfo($_FILES['logo']['name']);
            $extension = strtolower($fileInfo['extension']);
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $fileName = 'logo_' . uniqid() . '_' . time() . '.' . $extension;
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                    $data['logo'] = '/uploads/testimonials/' . $fileName;
                    
                    // Optional: Delete old logo
                    if (!empty($testi['logo']) && file_exists(BASE_PATH . '/public' . $testi['logo'])) {
                        @unlink(BASE_PATH . '/public' . $testi['logo']);
                    }
                }
            }
        }

        if ($model->update($id, $data)) {
            $_SESSION['success'] = "Testimoni berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui Testimoni.";
        }
        
        Response::redirect('/settings/testimonials');
    }

    public function delete(Request $request, $id)
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            Response::redirect('/dashboard');
            return;
        }

        $model = new LandingTestimonial();
        $testi = $model->find($id);
        
        if ($testi) {
            if (!empty($testi['logo']) && file_exists(BASE_PATH . '/public' . $testi['logo'])) {
                @unlink(BASE_PATH . '/public' . $testi['logo']);
            }
            if ($model->delete($id)) {
                $_SESSION['success'] = "Testimoni berhasil dihapus.";
            }
        }
        
        Response::redirect('/settings/testimonials');
    }
}
