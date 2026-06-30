<?php

namespace App\Middleware;

use App\Libraries\Response;
use App\Helpers\SecurityHelper;

class CsrfMiddleware
{
    public static function handle()
    {
        // Only check on state-changing requests
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            
            if (!SecurityHelper::verifyCsrfToken($token)) {
                // If it's an API request, return JSON
                if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
                    Response::json(['status' => 'error', 'message' => 'CSRF token mismatch or expired.'], 403);
                    exit;
                }
                
                // Otherwise, web request
                $_SESSION['error'] = "Sesi telah kedaluwarsa atau permintaan tidak sah (CSRF). Silakan coba lagi.";
                // Go back
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }
}
