<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\User;

class AuthController extends BaseController
{
    public function showLogin()
    {
        // Self-Healing on first load (if DB exists but tables don't)
        try {
            $db = new \PDO("mysql:host=".(getenv('DB_HOST')?:'127.0.0.1').";dbname=".(getenv('DB_DATABASE')?:'lanex_db'), getenv('DB_USERNAME')?:'root', getenv('DB_PASSWORD')?:'');
            $db->query("SELECT 1 FROM users LIMIT 1");
        } catch (\PDOException $e) {
            // Tables missing, run self-healing
            try {
                \App\Helpers\DatabaseHelper::repair();
            } catch (\Exception $ex) {
                // Ignore, might be DB connection issue
            }
        }

        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            Response::redirect('/dashboard');
        }
        
        $this->view('auth/login');
    }

    public function processLogin(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username dan Password wajib diisi.";
            Response::redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            // Setup Session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['role_id']   = $user['role_id'];
            $_SESSION['branch_id'] = $user['branch_id'];
            $_SESSION['fullname']  = $user['fullname'];
            $_SESSION['customer_id'] = $user['customer_id'] ?? null;
            
            // Update last login
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $userModel->updateLastLogin($user['id'], $ip);
            
            // Self-Healing on Login
            \App\Helpers\DatabaseHelper::repair();
            
            // Role 5 = B2B Client, redirect to their exclusive portal
            if ($user['role_id'] == 5) {
                Response::redirect('/b2b/dashboard');
            }
            
            Response::redirect('/dashboard');
        } else {
            $_SESSION['error'] = "Kredensial tidak valid atau akun tidak aktif.";
            Response::redirect('/login');
        }
    }

    public function logout()
    {
        session_destroy();
        Response::redirect('/login');
    }
}
