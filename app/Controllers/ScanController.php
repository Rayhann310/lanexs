<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Package;
use App\Models\Bag;
use App\Models\Manifest;
use App\Models\TrackingHistory;

class ScanController extends BaseController
{
    public function index()
    {
        // Require at least Admin Cabang
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }
        
        $this->view('scan/index');
    }

    public function processScan(Request $request)
    {
        $barcode = trim($request->get('barcode'));
        $status = $request->get('status'); // e.g., 'TRANSIT', 'GUDANG_TUJUAN', 'DELIVERED'
        $branchId = $_SESSION['branch_id'];
        $userId = $_SESSION['user_id'];
        
        if (empty($barcode) || empty($status)) {
            Response::json(['status' => 'error', 'message' => 'Barcode dan Status tujuan harus diisi.']);
        }

        $packageModel = new Package();
        $bagModel = new Bag();
        $manifestModel = new Manifest();
        $trackingModel = new TrackingHistory();

        try {
            // 1. Is it a Package Resi?
            $package = $packageModel->findByResi($barcode);
            if ($package) {
                $packageModel->update($package['id'], ['status' => $status]);
                $trackingModel->create([
                    'package_id' => $package['id'],
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                    'status' => $status,
                    'description' => "Paket dipindai: {$status}"
                ]);
                Response::json(['status' => 'success', 'message' => "✅ Paket {$barcode} berhasil diupdate ke {$status}."]);
                return;
            }

            // 2. Is it a Bag?
            $db = $bagModel->getDb();
            $stmt = $db->prepare("SELECT id FROM bags WHERE bag_code = :code LIMIT 1");
            $stmt->execute(['code' => $barcode]);
            $bag = $stmt->fetch(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($bag) {
                $bagModel->updateStatusCascade($bag['id'], $status, $userId, $branchId);
                Response::json(['status' => 'success', 'message' => "✅ Karung {$barcode} beserta isinya berhasil diupdate ke {$status}."]);
                return;
            }

            // 3. Is it a Manifest?
            $stmt = $manifestModel->getDb()->prepare("SELECT id FROM manifests WHERE manifest_code = :code LIMIT 1");
            $stmt->execute(['code' => $barcode]);
            $manifest = $stmt->fetch(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($manifest) {
                $manifestModel->updateStatusCascade($manifest['id'], $status, $userId, $branchId);
                Response::json(['status' => 'success', 'message' => "✅ Surat Jalan {$barcode} beserta isinya berhasil diupdate ke {$status}."]);
                return;
            }

            Response::json(['status' => 'error', 'message' => "❌ Barcode '{$barcode}' tidak ditemukan di sistem. Pastikan resi/kode karung/surat jalan benar."]);

        } catch (\Exception $e) {
            Response::json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }
}
