<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\Customer;

class B2bPortalController extends BaseController
{
    public function dashboard()
    {
        // Role 5 = B2B Client
        if ($_SESSION['role_id'] != 5) {
            Response::redirect('/dashboard');
        }

        $customerId = $_SESSION['customer_id'] ?? null;

        if (!$customerId) {
            Response::redirect('/login');
        }

        $packageModel = new Package();
        $db = $packageModel->getDb();

        // Get packages belonging to this B2B client
        $stmt = $db->prepare("
            SELECT p.*, bo.city as origin_city, bd.city as dest_city
            FROM packages p
            LEFT JOIN branches bo ON p.origin_branch_id = bo.id
            LEFT JOIN branches bd ON p.destination_branch_id = bd.id
            WHERE p.customer_id = :cid
            ORDER BY p.created_at DESC
        ");
        $stmt->execute(['cid' => $customerId]);
        $packages = $stmt->fetchAll();

        // Stats
        $total = count($packages);
        $delivered = count(array_filter($packages, fn($p) => $p['status'] === 'SELESAI'));
        $inTransit = count(array_filter($packages, fn($p) => in_array($p['status'], ['TRANSIT', 'PICKUP', 'DELIVERY'])));
        $pending = count(array_filter($packages, fn($p) => $p['status'] === 'PENDING'));

        // Outstanding invoices (UNPAID or COD packages)
        $unpaidStmt = $db->prepare("
            SELECT SUM(price) as total_unpaid, COUNT(*) as count_unpaid
            FROM packages
            WHERE customer_id = :cid AND payment_status IN ('UNPAID', 'COD')
        ");
        $unpaidStmt->execute(['cid' => $customerId]);
        $unpaidData = $unpaidStmt->fetch();

        // Get customer info
        $customerModel = new Customer();
        $customer = $customerModel->find($customerId);

        $this->view('b2b/dashboard', [
            'packages'     => $packages,
            'customer'     => $customer,
            'total'        => $total,
            'delivered'    => $delivered,
            'inTransit'    => $inTransit,
            'pending'      => $pending,
            'totalUnpaid'  => $unpaidData['total_unpaid'] ?? 0,
            'countUnpaid'  => $unpaidData['count_unpaid'] ?? 0,
        ]);
    }
}
