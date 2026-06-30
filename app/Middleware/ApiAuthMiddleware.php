<?php

namespace App\Middleware;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\User;

class ApiAuthMiddleware
{
    public static function handle()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        
        if (empty($authHeader) || strpos($authHeader, 'Bearer ') !== 0) {
            Response::json(['status' => 'error', 'message' => 'Unauthorized. Missing or invalid Bearer token.'], 401);
            exit;
        }

        $token = substr($authHeader, 7);
        
        // Lookup user by token
        $userModel = new User();
        $stmt = $userModel->getDb()->prepare("SELECT * FROM users WHERE api_token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();

        if (!$user) {
            Response::json(['status' => 'error', 'message' => 'Invalid or expired token.'], 401);
            exit;
        }

        // Set basic session-like globals for API context
        $_SESSION['api_user_id'] = $user['id'];
        $_SESSION['api_role_id'] = $user['role_id'];
        $_SESSION['api_branch_id'] = $user['branch_id'];
    }
}
