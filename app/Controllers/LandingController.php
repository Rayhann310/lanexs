<?php

namespace App\Controllers;

use App\Libraries\Request;

class LandingController extends BaseController
{
    public function index(Request $request)
    {
        $settingModel = new \App\Models\Setting();
        $heroTitle = $settingModel->get('landing_hero_title', 'Solusi Pengiriman Cepat, Aman, & Terpercaya');
        $heroSubtitle = $settingModel->get('landing_hero_subtitle', 'Tingkatkan efisiensi bisnis Anda dengan layanan logistik terintegrasi dari '.APP_NAME.'. Kami hadir untuk memastikan setiap paket sampai tepat waktu.');
        
        $this->view('landing/index', [
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle
        ]);
    }
    
    public function docs(Request $request)
    {
        $this->view('landing/docs');
    }
}
