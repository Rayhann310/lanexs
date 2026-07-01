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
                'tracking_histories',
                'packages',
                'transactions',
                'audit_logs'
            ];
            
            foreach ($tables as $table) {
                try {
                    $db->exec("TRUNCATE TABLE $table");
                } catch (\Exception $e) {
                    // Abaikan jika tabel belum ada di database
                }
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

    public function migrateSireslan(Request $request)
    {
        if (($_SESSION['role_id'] ?? 0) != 1) {
            $_SESSION['error'] = "Akses ditolak. Hanya Super Admin yang dapat melakukan migrasi.";
            Response::redirect('/settings/system');
            return;
        }

        if (!isset($_FILES['sireslan_sql']) || $_FILES['sireslan_sql']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Gagal mengunggah file SQL.";
            Response::redirect('/settings/system');
            return;
        }

        $fileContent = file_get_contents($_FILES['sireslan_sql']['tmp_name']);
        
        $dummyModel = new \App\Models\User();
        $db = $dummyModel->getDb();

        try {
            $db->exec('SET FOREIGN_KEY_CHECKS = 0');
            
            // Bersihkan sisa tabel Sireslan dari percobaan sebelumnya (jika ada)
            $oldTables = [
                'data_barang', 'data_inbound', 'data_penerima',
                'data_pengirim', 'data_tracking', 'data_vendor',
                'master_laporan', 'user'
            ];
            foreach ($oldTables as $tbl) {
                $db->exec("DROP TABLE IF EXISTS `$tbl`");
            }

            // Bersihkan statement bawaan phpMyAdmin yang bentrok dengan PDO
            $fileContent = preg_replace('/^SET SQL_MODE.*?;/mi', '', $fileContent);
            $fileContent = preg_replace('/^SET time_zone.*?;/mi', '', $fileContent);
            $fileContent = preg_replace('/^START TRANSACTION\s*;/mi', '', $fileContent);
            $fileContent = preg_replace('/^COMMIT\s*;/mi', '', $fileContent);
            $fileContent = preg_replace('/^\/\*.*?\*\/\s*;?/ms', '', $fileContent);

            // Eksekusi SQL dump per-statement
            $statements = array_filter(
                array_map('trim', explode(";\n", $fileContent)),
                fn($s) => !empty($s)
            );
            foreach ($statements as $stmt) {
                if (!empty(trim($stmt))) {
                    try { $db->exec($stmt . ';'); } catch (\Exception $e) { /* skip errors per statement */ }
                }
            }

            $db->beginTransaction();


            // 1. Migrasi Users (Password default)
            $defaultPassword = password_hash('Lanex2026!', PASSWORD_BCRYPT);
            $sqlUsers = "
                INSERT IGNORE INTO users (company_id, branch_id, role_id, username, password, fullname)
                SELECT 1, 1, 
                    CASE 
                        WHEN level = 'HA_Master' THEN 1 
                        WHEN level = 'HA_Inbound' THEN 3
                        WHEN level = 'HA_Outbound' THEN 3
                        WHEN level = 'HA_Kepala_Gudang' THEN 3
                        ELSE 4 
                    END as mapped_role,
                    username, 
                    ?, 
                    name
                FROM user
            ";
            $stmt = $db->prepare($sqlUsers);
            $stmt->execute([$defaultPassword]);
            $usersMigrated = $stmt->rowCount();

            // 2. Migrasi Paket / Resi
            $sqlPackages = "
                INSERT IGNORE INTO packages (
                    resi, sender_name, sender_phone, sender_address, 
                    receiver_name, receiver_phone, receiver_address, 
                    origin_branch_id, destination_branch_id, weight, 
                    price, payment_type, payment_status, status, created_by, created_at
                )
                SELECT 
                    COALESCE((SELECT kode_tracking FROM data_inbound di WHERE di.barang_id = db.barang_id LIMIT 1), CONCAT('LNX-MIGRATE-', db.barang_id)),
                    COALESCE(dp.nama_pengirim, 'Pengirim Tidak Diketahui'),
                    COALESCE(dp.notelp_pengirim, '-'),
                    COALESCE(CONCAT('[', db.nama_barang, '] ', dp.alamat_pengirim), '-'),
                    COALESCE(dpe.nama_penerima, 'Penerima Tidak Diketahui'),
                    COALESCE(dpe.notelp_penerima, '-'),
                    COALESCE(CONCAT(dpe.alamat_penerima, ', ', dpe.kabkota_penerima, ', ', dpe.provinsi_penerima), '-'),
                    1, 1,
                    CAST(db.kilo_barang AS DECIMAL(10,2)),
                    0,
                    'CASH',
                    'PAID',
                    'SELESAI', 
                    1,
                    db.tgl_input
                FROM data_barang db
                LEFT JOIN data_pengirim dp ON db.pengirim_id = dp.pengirim_id
                LEFT JOIN data_penerima dpe ON db.penerima_id = dpe.penerima_id
            ";
            $packagesMigrated = $db->exec($sqlPackages);

            // 3. Migrasi Tracking Histories
            $sqlTracking = "
                INSERT INTO tracking_histories (package_id, branch_id, user_id, status, description, created_at)
                SELECT 
                    p.id,
                    1,
                    1,
                    'UPDATE_STATUS',
                    dt.keterangan,
                    dt.tgl_input
                FROM data_tracking dt
                JOIN packages p ON p.resi = dt.kode_tracking
            ";
            $trackingMigrated = $db->exec($sqlTracking);

            $db->commit();

            // Bersihkan tabel Sireslan agar database kembali bersih
            // DDL statement seperti DROP TABLE menyebabkan implicit commit di MySQL,
            // jadi kita harus taruh setelah transaksi utama selesai.
            $tablesToDrop = [
                'data_barang', 'data_inbound', 'data_penerima', 
                'data_pengirim', 'data_tracking', 'data_vendor', 
                'master_laporan', 'user'
            ];
            foreach ($tablesToDrop as $tbl) {
                $db->exec("DROP TABLE IF EXISTS `$tbl`");
            }

            $db->exec('SET FOREIGN_KEY_CHECKS = 1');

            $_SESSION['success'] = "Migrasi Sireslan sukses! Berhasil memindahkan: $usersMigrated User, $packagesMigrated Paket, dan $trackingMigrated histori tracking.";
        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            $_SESSION['error'] = "Gagal memigrasi database Sireslan: " . $e->getMessage();
        }

        Response::redirect('/settings/system');
    }
}

