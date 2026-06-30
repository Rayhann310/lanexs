<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Package;
use App\Models\TrackingHistory;

class TrackingController extends BaseController
{
    public function search(Request $request)
    {
        $resi = $request->get('resi');
        
        if (!$resi) {
            $this->view('tracking/index');
            return;
        }
        
        $packageModel = new Package();
        $db = $packageModel->getDb();
        
        $stmt = $db->prepare("
            SELECT p.*, bo.city as origin_city, bd.city as dest_city
            FROM packages p
            LEFT JOIN branches bo ON p.origin_branch_id = bo.id
            LEFT JOIN branches bd ON p.destination_branch_id = bd.id
            WHERE p.resi = :resi
        ");
        $stmt->execute(['resi' => $resi]);
        $package = $stmt->fetch();
        
        if (!$package) {
            // Package not found
            $this->view('tracking/not_found', ['resi' => $resi]);
            return;
        }

        // Get Histories
        $histstmt = $db->prepare("
            SELECT th.*, b.city as location
            FROM tracking_histories th
            LEFT JOIN branches b ON th.branch_id = b.id
            WHERE th.package_id = :id
            ORDER BY th.created_at DESC
        ");
        $histstmt->execute(['id' => $package['id']]);
        $histories = $histstmt->fetchAll();

        $this->view('tracking/result', [
            'package' => $package,
            'histories' => $histories
        ]);
    }

    public function stream(Request $request, $packageId)
    {
        // Set headers for Server-Sent Events (SSE)
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        $packageModel = new Package();
        $db = $packageModel->getDb();
        
        // Disable output buffering
        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        // Send a ping first to establish connection
        echo "data: ping\n\n";
        flush();

        // Get the latest history ID on connection
        $stmt = $db->prepare("SELECT MAX(id) as last_id FROM tracking_histories WHERE package_id = :pid");
        $stmt->execute(['pid' => $packageId]);
        $lastId = $stmt->fetchColumn() ?: 0;

        // Loop for SSE
        $startTime = time();
        while (true) {
            // End the script if it runs for more than 1 minute to avoid PHP timeouts, 
            // client EventSource will auto-reconnect.
            if (time() - $startTime > 60) {
                break;
            }

            // Check if connection is aborted by client
            if (connection_aborted()) {
                break;
            }

            // Check for new histories
            $checkStmt = $db->prepare("
                SELECT th.*, b.city as location
                FROM tracking_histories th
                LEFT JOIN branches b ON th.branch_id = b.id
                WHERE th.package_id = :pid AND th.id > :lastId
                ORDER BY th.id ASC
            ");
            $checkStmt->execute(['pid' => $packageId, 'lastId' => $lastId]);
            $newHistories = $checkStmt->fetchAll();

            if (!empty($newHistories)) {
                foreach ($newHistories as $history) {
                    $lastId = $history['id'];
                    $payload = json_encode($history);
                    echo "data: {$payload}\n\n";
                }
                flush();
            }

            // Polling interval 3 seconds (as requested in prompt for Tracking)
            sleep(3);
        }
    }
}
