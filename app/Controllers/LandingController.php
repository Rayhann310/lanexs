<?php

namespace App\Controllers;

use App\Libraries\Request;

use App\Models\LandingPartner;
use App\Models\LandingTestimonial;
use App\Models\LandingPage;

class LandingController extends BaseController
{
    public function index(Request $request)
    {
        $settingModel = new \App\Models\Setting();
        $heroTitle = $settingModel->get('landing_hero_title', 'Best of The Best Service.');
        $heroSubtitle = $settingModel->get('landing_hero_subtitle', 'Solusi ekspedisi terpercaya dengan komitmen penuh pada keamanan, kecepatan, dan kepuasan pelanggan.');
        
        $contactAddress = $settingModel->get('landing_contact_address', 'Gedung LANEXS Center<br>Jl. Jend. Sudirman Kav 21, Jakarta');
        $contactPhone = $settingModel->get('landing_contact_phone', '1500-LNX (569)<br>+62 811 2233 4455 (WA)');
        $contactEmail = $settingModel->get('landing_contact_email', 'support@lanex.co.id');
        $contactMap = $settingModel->get('landing_contact_map', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126914.86989441113!2d106.74108821948523!3d-6.251458931102941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid');

        // Self-Healing Logic for Hero Images
        $rawImages = json_decode($settingModel->get('landing_hero_images', '[]'), true) ?: [];
        $heroImages = [];
        $publicDir = dirname($_SERVER['SCRIPT_FILENAME']);
        foreach ($rawImages as $img) {
            $heroImages[] = BASE_URL . $img;
        }
        // Fallback to default if empty
        if (empty($heroImages)) {
            $heroImages = [
                'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop'
            ];
        }

        try {
            $partnerModel = new LandingPartner();
            $partners = $partnerModel->all();
        } catch (\Exception $e) {
            $partners = [];
        }

        try {
            $testimonialModel = new LandingTestimonial();
            $testimonials = $testimonialModel->all();
        } catch (\Exception $e) {
            $testimonials = [];
        }

        $this->view('landing/index', [
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle,
            'heroImages' => $heroImages,
            'contactAddress' => $contactAddress,
            'contactPhone' => $contactPhone,
            'contactEmail' => $contactEmail,
            'contactMap' => $contactMap,
            'partners' => $partners,
            'testimonials' => $testimonials
        ]);
    }
    
    public function page(Request $request, $slug)
    {
        $page = null;
        try {
            $model = new LandingPage();
            $page = $model->findBySlug($slug);
        } catch (\Exception $e) {
            // Table doesn't exist yet, fallback to null
        }
        
        if (!$page) {
            http_response_code(404);
            $page = ['id' => 0, 'slug' => $slug, 'title' => 'Halaman Tidak Ditemukan', 'content' => '<p>Halaman yang Anda cari tidak tersedia.</p>'];
        }
        // Direct require — page.php uses ob_start/layout pattern
        require BASE_PATH . '/app/Views/landing/page.php';
    }

    public function docs(Request $request)
    {
        $this->view('landing/docs');
    }
}
