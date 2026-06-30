<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Bag;
use App\Models\Manifest;
use App\Models\Branch;
use App\Models\Package;

class ManifestController extends BaseController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }

        $bagModel = new Bag();
        $manifestModel = new Manifest();
        $branchModel = new Branch();
        $packageModel = new Package();
        $vehicleModel = new \App\Models\Vehicle();
        $driverModel = new \App\Models\Driver();

        // Get active branch
        $branchId = $_SESSION['branch_id'];
        $roleId = $_SESSION['role_id'];

        $bags = $bagModel->all();
        // Custom query to get driver and vehicle names for manifests
        $sql = "SELECT m.*, d.name as driver_name, v.plate_number as vehicle_plate 
                FROM manifests m 
                LEFT JOIN drivers d ON m.driver_id = d.id 
                LEFT JOIN vehicles v ON m.vehicle_id = v.id";
        $stmt = $manifestModel->getDb()->query($sql);
        $manifests = $stmt->fetchAll();
        
        $pendingPackages = $packageModel->all();
        $branches = $branchModel->all();
        $vehicles = $vehicleModel->all();
        $drivers = $driverModel->all();

        $this->view('manifests/index', [
            'bags' => $bags,
            'manifests' => $manifests,
            'branches' => $branches,
            'packages' => $pendingPackages,
            'vehicles' => $vehicles,
            'drivers' => $drivers
        ]);
    }

    public function createBag(Request $request)
    {
        $origin = $request->get('origin_branch_id');
        $dest = $request->get('destination_branch_id');
        $packageIds = $request->get('package_ids'); // array
        
        if (!$origin || !$dest || empty($packageIds)) {
            $_SESSION['error'] = "Data tidak lengkap.";
            Response::redirect('/manifests');
        }
        
        $bagModel = new Bag();
        
        // Quick lookup codes
        $branchModel = new Branch();
        $oBranch = $branchModel->find($origin);
        $dBranch = $branchModel->find($dest);
        
        $bagCode = $bagModel->generateBagCode(strtoupper(substr($oBranch['name'], 0, 3)), strtoupper(substr($dBranch['name'], 0, 3)));
        
        $bagId = $bagModel->create([
            'bag_code' => $bagCode,
            'origin_branch_id' => $origin,
            'destination_branch_id' => $dest,
            'status' => 'BAGGED',
            'created_by' => $_SESSION['user_id']
        ]);
        
        if ($bagId) {
            // Insert items
            foreach ($packageIds as $pid) {
                $sql = "INSERT INTO bag_items (bag_id, package_id) VALUES (:bid, :pid)";
                $stmt = $bagModel->getDb()->prepare($sql);
                $stmt->execute(['bid' => $bagId, 'pid' => $pid]);
                
                // Update package status to BAGGED
                $pkgModel = new Package();
                $pkgModel->update($pid, ['status' => 'BAGGED']);
            }
            \App\Services\AuditLogger::log('CREATE_BAG', 'Bag', $bagId, null, ['bag_code' => $bagCode, 'package_ids' => $packageIds]);
            $_SESSION['success'] = "Karung {$bagCode} berhasil dibuat!";
        } else {
            $_SESSION['error'] = "Gagal membuat karung.";
        }
        
        Response::redirect('/manifests');
    }

    public function createManifest(Request $request)
    {
        $origin = $request->get('origin_branch_id');
        $dest = $request->get('destination_branch_id');
        $driverId = $request->get('driver_id');
        $vehicleId = $request->get('vehicle_id');
        $bagIds = $request->get('bag_ids'); // array
        
        if (!$origin || !$dest || !$driverId || !$vehicleId || empty($bagIds)) {
            $_SESSION['error'] = "Data tidak lengkap.";
            Response::redirect('/manifests');
        }
        
        $manifestModel = new Manifest();
        $branchModel = new Branch();
        $oBranch = $branchModel->find($origin);
        $dBranch = $branchModel->find($dest);
        
        $manifestCode = $manifestModel->generateManifestCode(strtoupper(substr($oBranch['name'], 0, 3)), strtoupper(substr($dBranch['name'], 0, 3)));
        
        $manifestId = $manifestModel->create([
            'manifest_code' => $manifestCode,
            'driver_id' => $driverId,
            'vehicle_id' => $vehicleId,
            'origin_branch_id' => $origin,
            'destination_branch_id' => $dest,
            'status' => 'MANIFESTED',
            'created_by' => $_SESSION['user_id']
        ]);
        
        if ($manifestId) {
            foreach ($bagIds as $bid) {
                $sql = "INSERT INTO manifest_items (manifest_id, item_type, item_id) VALUES (:mid, 'BAG', :bid)";
                $stmt = $manifestModel->getDb()->prepare($sql);
                $stmt->execute(['mid' => $manifestId, 'bid' => $bid]);
                
                // Update bag status to MANIFESTED
                $bagModel = new Bag();
                $bagModel->update($bid, ['status' => 'MANIFESTED']);
            }
            \App\Services\AuditLogger::log('CREATE_MANIFEST', 'Manifest', $manifestId, null, ['manifest_code' => $manifestCode, 'bag_ids' => $bagIds]);
            $_SESSION['success'] = "Surat Jalan {$manifestCode} berhasil dibuat!";
        } else {
            $_SESSION['error'] = "Gagal membuat Surat Jalan.";
        }
        
        Response::redirect('/manifests');
    }

    public function print(Request $request, $id)
    {
        $manifestModel = new Manifest();
        $db = $manifestModel->getDb();
        
        $sql = "
            SELECT m.*, 
                   bo.name as origin_branch_name, bo.city as origin_city,
                   bd.name as dest_branch_name, bd.city as dest_city
            FROM manifests m
            LEFT JOIN branches bo ON m.origin_branch_id = bo.id
            LEFT JOIN branches bd ON m.destination_branch_id = bd.id
            WHERE m.id = :id LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $manifest = $stmt->fetch();
        $stmt->closeCursor();

        if (!$manifest) {
            $_SESSION['error'] = "Manifest tidak ditemukan.";
            Response::redirect('/manifests');
        }

        // Fetch Bags inside this manifest
        $bagSql = "
            SELECT b.bag_code, COUNT(bi.package_id) as total_packages,
                   COALESCE(SUM(p.weight), 0) as weight
            FROM manifest_items mi
            JOIN bags b ON mi.item_id = b.id
            LEFT JOIN bag_items bi ON b.id = bi.bag_id
            LEFT JOIN packages p ON bi.package_id = p.id
            WHERE mi.manifest_id = :mid AND mi.item_type = 'BAG'
            GROUP BY b.id, b.bag_code
        ";
        $stmt = $db->prepare($bagSql);
        $stmt->execute(['mid' => $id]);
        $bags = $stmt->fetchAll();
        $stmt->closeCursor();

        ob_start();
        $this->view('manifests/print', [
            'manifest' => $manifest,
            'bags' => $bags,
            'isPdf' => true
        ]);
        $html = ob_get_clean();

        if (class_exists(\Dompdf\Dompdf::class)) {
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("Manifest_{$manifest['manifest_code']}.pdf", ["Attachment" => false]);
            exit;
        } else {
            echo $html;
        }
    }
}
