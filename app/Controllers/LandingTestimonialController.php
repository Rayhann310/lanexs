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
            'content' => $request->get('content'),
            'rating' => $request->get('rating'),
            'avatar_initials' => $initials
        ];

        if ($model->create($data)) {
            $_SESSION['success'] = "Testimoni berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambahkan Testimoni.";
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
        
        if ($model->delete($id)) {
            $_SESSION['success'] = "Testimoni berhasil dihapus.";
        }
        
        Response::redirect('/settings/testimonials');
    }
}
