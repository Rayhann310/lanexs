<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\User;

class SettingsController extends BaseController
{
    public function profile()
    {
        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);
        
        $this->view('settings/profile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $userModel = new User();
        $userId = $_SESSION['user_id'];
        
        $data = [
            'fullname' => $request->get('fullname'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
        ];
        
        if (!empty($request->get('password'))) {
            $data['password'] = password_hash($request->get('password'), PASSWORD_BCRYPT);
        }
        
        if ($userModel->update($userId, $data)) {
            $_SESSION['fullname'] = $data['fullname'];
            $_SESSION['success'] = "Profil berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui profil.";
        }
        
        Response::redirect('/settings/profile');
    }

    public function system()
    {
        $this->view('settings/system');
    }

    public function updateSystem(Request $request)
    {
        // For now, this is a placeholder. 
        // Real implementations would save to a `settings` table or `.env` file.
        $_SESSION['success'] = "Pengaturan sistem berhasil disimpan.";
        Response::redirect('/settings/system');
    }

    // Database Repair / Self Healing function
    public function repairDatabase()
    {
        $result = \App\Helpers\DatabaseHelper::repair();
        
        if ($result['status']) {
            $_SESSION['success'] = "Perbaikan Database (Self-Healing) selesai dijalankan.";
            $_SESSION['db_log'] = $result['log'];
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat memperbaiki database: " . $result['error'];
        }
        
        Response::redirect('/settings/system');
    }

    public function generateDummyData()
    {
        $dummyModel = new \App\Models\User();
        $db = $dummyModel->getDb();
        
        try {
            $db->beginTransaction();

            $roles = [
                1 => ['username' => 'budi_super', 'fullname' => 'Budi Santoso (Super Admin)'],
                2 => ['username' => 'andi_owner', 'fullname' => 'Andi Wijaya (Owner)'],
                3 => ['username' => 'siti_manager', 'fullname' => 'Siti Rahayu (Manager)'],
                4 => ['username' => 'eko_admin', 'fullname' => 'Eko Prasetyo (Admin Cabang)'],
                6 => ['username' => 'joko_kurir', 'fullname' => 'Joko Suprianto (Kurir/Sopir)']
            ];

            $pwd = password_hash('password123', PASSWORD_BCRYPT);
            
            foreach ($roles as $roleId => $u) {
                // Check if exists
                $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->execute([$u['username']]);
                if (!$stmt->fetch()) {
                    $stmt = $db->prepare("INSERT INTO users (role_id, branch_id, username, password, fullname, email, phone) VALUES (?, 1, ?, ?, ?, ?, ?)");
                    $stmt->execute([$roleId, $u['username'], $pwd, $u['fullname'], $u['username'].'@lanex.com', '08123456789']);
                }
            }
            
            // Get all branches
            $stmt = $db->query("SELECT id FROM branches");
            $branches = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            if (count($branches) < 2) {
                // Fallback if not enough branches
                $branches = [1, 2];
            }

            // Generate Dummy Packages
            $senders = ['PT Maju Jaya', 'Toko Laris', 'CV Berkah Abadi', 'Ahmad Dani', 'Rina Marlina'];
            $receivers = ['PT Kargo Sukses', 'UD Makmur', 'Bambang S', 'Diana Putri', 'Fajar Nugraha'];
            $addresses = ['Jl. Sudirman No. 10', 'Jl. Merdeka No. 45', 'Gedung Kencana Lt. 3', 'Komp. Harmoni Blok B/2', 'Jl. Pahlawan No. 99'];
            $statusList = ['PENDING', 'PICKUP', 'GUDANG_ASAL', 'TRANSIT', 'GUDANG_TUJUAN', 'DELIVERY', 'SELESAI'];
            
            for ($i = 1; $i <= 10; $i++) {
                $resi = 'DUMMY-' . date('ymd') . '-' . sprintf('%04d', rand(1, 9999));
                $status = $statusList[array_rand($statusList)];
                
                $origin = $branches[array_rand($branches)];
                $dest = $branches[array_rand($branches)];
                while ($origin == $dest && count($branches) > 1) {
                    $dest = $branches[array_rand($branches)];
                }
                
                $sName = $senders[array_rand($senders)];
                $rName = $receivers[array_rand($receivers)];
                $sAddr = $addresses[array_rand($addresses)];
                $rAddr = $addresses[array_rand($addresses)];

                $stmt = $db->prepare("INSERT INTO packages (resi, sender_name, sender_phone, sender_address, receiver_name, receiver_phone, receiver_address, origin_branch_id, destination_branch_id, weight, price, payment_type, payment_status, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'CASH', 'PAID', ?, 1)");
                $stmt->execute([
                    $resi, 
                    $sName, 
                    '081' . rand(10000000, 99999999), 
                    $sAddr, 
                    $rName, 
                    '082' . rand(10000000, 99999999), 
                    $rAddr, 
                    $origin, 
                    $dest, 
                    rand(1, 25), 
                    rand(15, 150) * 1000, 
                    $status
                ]);
            }

            $db->commit();
            $_SESSION['success'] = "Data dummy (Karyawan & Paket) berhasil dibuat.";
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Gagal membuat data dummy: " . $e->getMessage();
        }

        Response::redirect('/settings/system');
    }

    public function factoryReset()
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            $_SESSION['error'] = "Hanya Super Admin yang dapat mereset sistem.";
            Response::redirect('/settings/system');
            return;
        }

        $dummyModel = new \App\Models\User();
        $db = $dummyModel->getDb();
        
        try {
            // Disable foreign key checks for truncation
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');
            
            $tables = [
                'manifest_bag_items',
                'manifest_bags',
                'manifests',
                'tracking_history',
                'packages',
                'transactions',
                'audit_logs'
            ];
            
            foreach ($tables as $table) {
                $db->exec("TRUNCATE TABLE $table");
            }
            
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            
            \App\Services\AuditLogger::log('FACTORY_RESET', 'System', null, null, ['status' => 'success']);
            $_SESSION['success'] = "Pabrik Reset berhasil. Seluruh data transaksi, paket, manifest, dan operasional telah dihapus bersih.";
        } catch (\Exception $e) {
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            $_SESSION['error'] = "Gagal melakukan Factory Reset: " . $e->getMessage();
        }

        Response::redirect('/settings/system');
    }

    public function landing()
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            $_SESSION['error'] = "Akses ditolak.";
            Response::redirect('/dashboard');
            return;
        }

        $settingModel = new \App\Models\Setting();
        
        $settings = [
            'landing_hero_title' => $settingModel->get('landing_hero_title', 'Solusi Pengiriman Cepat, Aman, & Terpercaya'),
            'landing_hero_subtitle' => $settingModel->get('landing_hero_subtitle', 'Tingkatkan efisiensi bisnis Anda dengan layanan logistik terintegrasi dari '.APP_NAME.'. Kami hadir untuk memastikan setiap paket sampai tepat waktu.'),
        ];
        
        $this->view('settings/landing', ['settings' => $settings]);
    }

    public function updateLanding(Request $request)
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            $_SESSION['error'] = "Akses ditolak.";
            Response::redirect('/dashboard');
            return;
        }

        $settingModel = new \App\Models\Setting();
        
        $settingModel->set('landing_hero_title', $request->get('landing_hero_title'));
        $settingModel->set('landing_hero_subtitle', $request->get('landing_hero_subtitle'));
        
        $_SESSION['success'] = "Pengaturan Landing Page berhasil disimpan.";
        Response::redirect('/settings/landing');
    }
}

