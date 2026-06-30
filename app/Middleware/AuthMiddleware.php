<?php

namespace App\Middleware;

use App\Libraries\Response;

class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
            exit;
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $roleId = (int)$_SESSION['role_id']; // 1=SuperAdmin, 2=Owner, 3=Manager, 4=AdminCabang
        
        // RBAC Rules
        $isSettings = strpos($uri, '/settings/system') !== false || strpos($uri, '/settings/repair-db') !== false;
        $isEmployees = strpos($uri, '/employees') !== false;
        $isBranches = strpos($uri, '/branches') !== false;
        $isWarehouses = strpos($uri, '/warehouses') !== false;
        
        // Master Data Protection (Branches, Warehouses)
        if ($isBranches || $isWarehouses) {
            // Only Super Admin and Owner can access Master Data fully? 
            // Wait, maybe manager too? The instruction says Master Data is for Super Admin.
            // Let's restrict Master Data strictly to Super Admin (1).
            if ($roleId !== 1) {
                $_SESSION['error'] = "Anda tidak memiliki akses ke Master Data.";
                Response::redirect('/dashboard');
                exit;
            }
        }
        
        // Settings System Protection
        if ($isSettings) {
            // Only Super Admin (1)
            if ($roleId !== 1) {
                $_SESSION['error'] = "Anda tidak memiliki akses ke Pengaturan Sistem.";
                Response::redirect('/dashboard');
                exit;
            }
        }
        
        // Employee Management Protection
        if ($isEmployees) {
            // Only Super Admin (1) and Owner (2)
            if (!in_array($roleId, [1, 2])) {
                $_SESSION['error'] = "Anda tidak memiliki akses ke Data Karyawan.";
                Response::redirect('/dashboard');
                exit;
            }
        }
    }
}
