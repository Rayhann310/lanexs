<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\User;
use App\Models\Package;

class MobileController extends BaseController
{
    /**
     * Mobile Login to get Bearer Token
     */
    public function login(Request $request)
    {
        // Read JSON input since mobile apps typically send application/json
        $input = json_decode(file_get_contents('php://input'), true);
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($username) || empty($password)) {
            Response::json(['status' => 'error', 'message' => 'Username and password required'], 400);
            return;
        }

        $userModel = new User();
        $db = $userModel->getDb();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            
            // Only allow Drivers/Couriers (Role 4) and Admins to use Mobile App for now
            if (!in_array($user['role_id'], [1, 3, 4])) {
                Response::json(['status' => 'error', 'message' => 'Akses ditolak untuk role ini.'], 403);
                return;
            }

            // Generate Token if not exists
            $token = $user['api_token'];
            if (empty($token)) {
                $token = bin2hex(random_bytes(32));
                $updateStmt = $db->prepare("UPDATE users SET api_token = :token WHERE id = :id");
                $updateStmt->execute(['token' => $token, 'id' => $user['id']]);
            }

            Response::json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user['id'],
                        'fullname' => $user['fullname'],
                        'role_id' => $user['role_id'],
                        'branch_id' => $user['branch_id']
                    ]
                ]
            ]);
        } else {
            Response::json(['status' => 'error', 'message' => 'Invalid username or password'], 401);
        }
    }

    /**
     * Get tasks (Packages to be delivered/picked up)
     */
    public function getTasks()
    {
        // Using context set by ApiAuthMiddleware
        $userId = $_SESSION['api_user_id'];
        $branchId = $_SESSION['api_branch_id'];

        $packageModel = new Package();
        $db = $packageModel->getDb();

        // Get packages in active state (Not SELESAI/RETUR)
        $sql = "SELECT id, resi, sender_name, sender_phone, sender_address, 
                       receiver_name, receiver_phone, receiver_address, status, payment_status, price 
                FROM packages 
                WHERE status NOT IN ('SELESAI', 'RETUR') 
                AND (origin_branch_id = :bid OR destination_branch_id = :bid)
                ORDER BY updated_at DESC";
                
        $stmt = $db->prepare($sql);
        $stmt->execute(['bid' => $branchId]);
        $tasks = $stmt->fetchAll();

        Response::json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }

    /**
     * Update package status from mobile app
     */
    public function updateStatus(Request $request)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $packageId = $input['package_id'] ?? null;
        $status = $input['status'] ?? null;
        $description = $input['description'] ?? 'Update via Mobile App';

        if (!$packageId || !$status) {
            Response::json(['status' => 'error', 'message' => 'Package ID and Status required'], 400);
            return;
        }

        $packageModel = new Package();
        $package = $packageModel->find($packageId);

        if (!$package) {
            Response::json(['status' => 'error', 'message' => 'Package not found'], 404);
            return;
        }

        // Update status
        $packageModel->update($packageId, ['status' => $status]);

        // Insert Tracking History
        $trackingModel = new \App\Models\TrackingHistory();
        $trackingModel->create([
            'package_id' => $packageId,
            'branch_id' => $_SESSION['api_branch_id'],
            'user_id' => $_SESSION['api_user_id'],
            'status' => $status,
            'description' => $description
        ]);

        // Send Notification if completed
        if ($status === 'SELESAI') {
            \App\Services\NotificationService::sendStatusUpdated($package, $status);
        }

        Response::json([
            'status' => 'success',
            'message' => 'Status updated successfully'
        ]);
    }
}
