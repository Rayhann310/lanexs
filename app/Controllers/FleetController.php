<?php

namespace App\Controllers;

use App\Libraries\Request;
use App\Libraries\Response;
use App\Models\Vehicle;
use App\Models\Driver;

class FleetController extends BaseController
{
    public function index()
    {
        // Require Super Admin or Manager/Owner
        if ($_SESSION['role_id'] == 4) {
            $_SESSION['error'] = "Akses ditolak. Hanya Manajemen yang dapat mengakses Master Armada.";
            Response::redirect('/dashboard');
        }

        $vehicleModel = new Vehicle();
        $driverModel = new Driver();

        $vehicles = $vehicleModel->all();
        $drivers = $driverModel->all();

        $this->view('fleet/index', [
            'vehicles' => $vehicles,
            'drivers' => $drivers
        ]);
    }

    public function storeVehicle(Request $request)
    {
        $vehicleModel = new Vehicle();
        $data = [
            'plate_number' => strtoupper($request->get('plate_number')),
            'vehicle_type' => $request->get('vehicle_type'),
            'capacity_kg' => $request->get('capacity_kg'),
            'status' => $request->get('status', 'AVAILABLE')
        ];

        if ($vehicleModel->create($data)) {
            $_SESSION['success'] = "Kendaraan berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambah kendaraan. Pastikan Plat Nomor unik.";
        }
        Response::redirect('/fleet?tab=vehicles');
    }

    public function deleteVehicle(Request $request, $id)
    {
        $vehicleModel = new Vehicle();
        if ($vehicleModel->delete($id)) {
            $_SESSION['success'] = "Kendaraan berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus kendaraan.";
        }
        Response::redirect('/fleet?tab=vehicles');
    }

    public function storeDriver(Request $request)
    {
        $driverModel = new Driver();
        $data = [
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'license_number' => strtoupper($request->get('license_number')),
            'status' => $request->get('status', 'AVAILABLE')
        ];

        if ($driverModel->create($data)) {
            $_SESSION['success'] = "Supir berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gagal menambah supir. Pastikan No. SIM unik.";
        }
        Response::redirect('/fleet?tab=drivers');
    }

    public function deleteDriver(Request $request, $id)
    {
        $driverModel = new Driver();
        if ($driverModel->delete($id)) {
            $_SESSION['success'] = "Supir berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus supir.";
        }
        Response::redirect('/fleet?tab=drivers');
    }
}
