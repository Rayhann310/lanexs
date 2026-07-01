<?php

use App\Libraries\Router;
use App\Libraries\Request;
use App\Controllers\DashboardController;
use App\Controllers\AuthController;
use App\Controllers\LandingController;
use App\Controllers\PackageController;
use App\Controllers\TrackingController;
use App\Controllers\BranchController;
use App\Controllers\TrackingTemplateController;
use App\Middleware\AuthMiddleware;

// Landing Page
Router::get('/', [LandingController::class, 'index']);
Router::get('/docs', [LandingController::class, 'docs']);

// Public Tracking Routes
Router::get('/tracking', [TrackingController::class, 'search']);
Router::get('/api/tracking/stream/{id}', [TrackingController::class, 'stream']);

// Authentication
Router::get('/login', [AuthController::class, 'showLogin']);
Router::middleware([\App\Middleware\CsrfMiddleware::class], function() {
    Router::post('/login', [AuthController::class, 'processLogin']);
});
Router::get('/logout', [AuthController::class, 'logout']);

// Protected Routes
Router::middleware([AuthMiddleware::class], function() {
    
    // Dashboard Route
    Router::get('/dashboard', [DashboardController::class, 'index']);

    // Analytics
    Router::get('/analytics', [\App\Controllers\AnalyticsController::class, 'index']);

    // Master Data: Branches
    Router::get('/branches', [BranchController::class, 'index']);
    Router::post('/branches', [BranchController::class, 'store']);
    Router::post('/branches/update/{id}', [BranchController::class, 'update']);
    Router::post('/branches/delete/{id}', [BranchController::class, 'delete']);
    
    // Branches Excel
    Router::get('/branches/export', [BranchController::class, 'export']);
    Router::get('/branches/template', [BranchController::class, 'template']);
    Router::post('/branches/import-preview', [BranchController::class, 'importPreview']);
    Router::post('/branches/import', [BranchController::class, 'importProcess']);

    // Master Data: Warehouses
    Router::get('/warehouses', [\App\Controllers\WarehouseController::class, 'index']);
    Router::post('/warehouses', [\App\Controllers\WarehouseController::class, 'store']);
    Router::post('/warehouses/update/{id}', [\App\Controllers\WarehouseController::class, 'update']);
    Router::post('/warehouses/delete/{id}', [\App\Controllers\WarehouseController::class, 'delete']);
    
    // Warehouses Excel
    Router::get('/warehouses/export', [\App\Controllers\WarehouseController::class, 'export']);
    Router::get('/warehouses/template', [\App\Controllers\WarehouseController::class, 'template']);
    // Master Data: Tariffs
    Router::get('/tariffs', [\App\Controllers\TariffController::class, 'index']);
    Router::post('/tariffs', [\App\Controllers\TariffController::class, 'store']);
    Router::post('/tariffs/update/{id}', [\App\Controllers\TariffController::class, 'update']);
    Router::post('/tariffs/delete/{id}', [\App\Controllers\TariffController::class, 'delete']);
    Router::get('/tariffs/export', [\App\Controllers\TariffController::class, 'exportExcel']);
    Router::get('/tariffs/template', [\App\Controllers\TariffController::class, 'downloadTemplate']);
    Router::post('/tariffs/import-preview', [\App\Controllers\TariffController::class, 'importPreview']);
    Router::post('/tariffs/import-process', [\App\Controllers\TariffController::class, 'importProcess']);

    // Packages Routes
    Router::get('/packages', [PackageController::class, 'index']);
    Router::get('/packages/export', [PackageController::class, 'export']);
    Router::get('/packages/template', [PackageController::class, 'downloadTemplate']);
    Router::post('/packages/import-preview', [PackageController::class, 'importPreview']);
    Router::post('/packages/import-process', [PackageController::class, 'importProcess']);
    Router::post('/packages', [PackageController::class, 'store']);
    Router::post('/packages/update/{id}', [PackageController::class, 'update']);
    Router::post('/packages/delete/{id}', [PackageController::class, 'delete']);
    Router::get('/packages/print/{id}', [PackageController::class, 'print']);
    Router::post('/packages/update-status', [PackageController::class, 'updateStatus']);
    Router::post('/packages/mass', [PackageController::class, 'storeMass']);
    Router::post('/packages/print-mass', [PackageController::class, 'printMass']);
    
    // Manifests & Bagging
    Router::get('/manifests', [\App\Controllers\ManifestController::class, 'index']);
    Router::post('/manifests/bag', [\App\Controllers\ManifestController::class, 'createBag']);
    Router::post('/manifests/create', [\App\Controllers\ManifestController::class, 'createManifest']);
    Router::get('/manifests/print/{id}', [\App\Controllers\ManifestController::class, 'print']);

    // Scanner
    Router::get('/scan', [\App\Controllers\ScanController::class, 'index']);
    Router::post('/scan/process', [\App\Controllers\ScanController::class, 'processScan']);

    // Fleet Management
    Router::get('/fleet', [\App\Controllers\FleetController::class, 'index']);
    Router::post('/fleet/vehicles', [\App\Controllers\FleetController::class, 'storeVehicle']);
    Router::post('/fleet/vehicles/delete/{id}', [\App\Controllers\FleetController::class, 'deleteVehicle']);
    Router::post('/fleet/drivers', [\App\Controllers\FleetController::class, 'storeDriver']);
    Router::post('/fleet/drivers/delete/{id}', [\App\Controllers\FleetController::class, 'deleteDriver']);

    // Finance & Cash Flow
    Router::get('/finance', [\App\Controllers\FinanceController::class, 'index']);
    Router::get('/finance/export', [\App\Controllers\FinanceController::class, 'export']);
    Router::post('/finance/cod/settle', [\App\Controllers\FinanceController::class, 'settleCod']);

    // B2B Customer Management (Admin)
    Router::get('/customers', [\App\Controllers\CustomerController::class, 'index']);
    Router::post('/customers', [\App\Controllers\CustomerController::class, 'store']);
    Router::post('/customers/delete/{id}', [\App\Controllers\CustomerController::class, 'delete']);
    Router::get('/customers/export', [\App\Controllers\CustomerController::class, 'exportExcel']);
    Router::get('/customers/template', [\App\Controllers\CustomerController::class, 'downloadTemplate']);
    Router::post('/customers/import-preview', [\App\Controllers\CustomerController::class, 'importPreview']);
    Router::post('/customers/import-process', [\App\Controllers\CustomerController::class, 'importProcess']);

    // B2B Portal (for client role)
    Router::get('/b2b/dashboard', [\App\Controllers\B2bPortalController::class, 'dashboard']);

    // Trackings Routes
    Router::post('/packages/update-status/{id}', [PackageController::class, 'updateStatus']);

    // Employee Management Routes
    Router::get('/employees', [\App\Controllers\EmployeeController::class, 'index']);
    Router::post('/employees', [\App\Controllers\EmployeeController::class, 'store']);
    Router::post('/employees/update/{id}', [\App\Controllers\EmployeeController::class, 'update']);
    Router::post('/employees/delete/{id}', [\App\Controllers\EmployeeController::class, 'delete']);
    Router::get('/employees/export', [\App\Controllers\EmployeeController::class, 'exportExcel']);
    Router::get('/employees/template', [\App\Controllers\EmployeeController::class, 'downloadTemplate']);
    Router::post('/employees/import-preview', [\App\Controllers\EmployeeController::class, 'importPreview']);
    Router::post('/employees/import-process', [\App\Controllers\EmployeeController::class, 'importProcess']);

    // Settings Routes
    Router::get('/settings/profile', [\App\Controllers\SettingsController::class, 'profile']);
    Router::post('/settings/profile', [\App\Controllers\SettingsController::class, 'updateProfile']);
    Router::get('/settings/system', [\App\Controllers\SettingsController::class, 'system']);
    Router::post('/settings/system', [\App\Controllers\SettingsController::class, 'updateSystem']);
    Router::post('/settings/repair-db', [\App\Controllers\SettingsController::class, 'repairDatabase']);
    Router::post('/settings/factory-reset', [\App\Controllers\SettingsController::class, 'factoryReset']);
    Router::post('/settings/generate-dummy', [\App\Controllers\SettingsController::class, 'generateDummyData']);
    Router::get('/settings/landing', [\App\Controllers\SettingsController::class, 'landing']);
    Router::post('/settings/landing', [\App\Controllers\SettingsController::class, 'updateLanding']);
    Router::post('/settings/migrate-sireslan', [\App\Controllers\SettingsController::class, 'migrateSireslan']);

    // Audit Logs
    Router::get('/audit-logs', [\App\Controllers\AuditLogController::class, 'index']);

    // Tracking Templates (API)
    Router::get('/api/tracking-templates', [TrackingTemplateController::class, 'index']);
    Router::post('/api/tracking-templates', [TrackingTemplateController::class, 'store']);
    Router::post('/api/tracking-templates/delete/{id}', [TrackingTemplateController::class, 'delete']);
});

// API Routes
Router::get('/api/ping', function(Request $request) {
    \App\Libraries\Response::json(['status' => 'ok', 'message' => 'API is running']);
});
Router::get('/api/tariffs/calculate', [\App\Controllers\TariffController::class, 'calculate']);
Router::get('/api/packages/datatable', [PackageController::class, 'datatable']);
Router::get('/api/audit-logs/datatable', [\App\Controllers\AuditLogController::class, 'datatable']);

// Mobile API Routes (No Auth for Login)
Router::post('/api/mobile/login', [\App\Controllers\Api\MobileController::class, 'login']);

// Mobile API Routes (With Auth)
Router::middleware([\App\Middleware\ApiAuthMiddleware::class], function() {
    Router::get('/api/mobile/tasks', [\App\Controllers\Api\MobileController::class, 'getTasks']);
    Router::post('/api/mobile/update-status', [\App\Controllers\Api\MobileController::class, 'updateStatus']);
});
