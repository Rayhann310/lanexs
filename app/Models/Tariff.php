<?php

namespace App\Models;

use PDOException;

class Tariff extends BaseModel
{
    protected string $table = 'tariffs';
    
    /**
     * Calculate price based on Origin and Destination Branch, considering both BRANCH and CITY tariffs.
     * Returns the matched tariff record (with calculated total_price added).
     */
    public function calculate(int $originBranchId, int $destBranchId, float $weight, float $volume = 0, int $koli = 1)
    {
        $this->ensureTableExists();
        
        // Get branch details to know their cities
        $branchModel = new Branch();
        $originBranch = $branchModel->find($originBranchId);
        $destBranch = $branchModel->find($destBranchId);
        
        if (!$originBranch || !$destBranch) {
            return null;
        }
        
        $originCity = $originBranch['city'];
        $destCity = $destBranch['city'];
        
        // 1. Check for specific BRANCH to BRANCH tariff (Highest priority)
        $sql = "SELECT * FROM {$this->table} WHERE type = 'BRANCH' AND origin_branch_id = :origin AND destination_branch_id = :dest AND is_active = 1 LIMIT 1";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['origin' => $originBranchId, 'dest' => $destBranchId]);
        $tariff = $stmt->fetch();
        
        // 2. If not found, check CITY to CITY tariff
        if (!$tariff) {
            $sql = "SELECT * FROM {$this->table} WHERE type = 'CITY' AND origin_city = :origin_city AND destination_city = :dest_city AND is_active = 1 LIMIT 1";
            $stmt = self::$db->prepare($sql);
            $stmt->execute(['origin_city' => $originCity, 'dest_city' => $destCity]);
            $tariff = $stmt->fetch();
        }
        
        if ($tariff) {
            $priceKg = $tariff['price_per_kg'] * $weight;
            $priceVol = $tariff['price_per_volume'] * $volume;
            $priceKoli = $tariff['price_per_koli'] * $koli;
            
            // Logika Bisnis: Ambil harga tertinggi di antara berat, volume, atau jumlah koli
            $tariff['total_price'] = max($priceKg, $priceVol, $priceKoli);
            
            // Tambahkan rincian untuk frontend
            $tariff['calculated_details'] = [
                'price_by_weight' => $priceKg,
                'price_by_volume' => $priceVol,
                'price_by_koli' => $priceKoli,
                'applied_method' => ($tariff['total_price'] == $priceKg) ? 'WEIGHT' : (($tariff['total_price'] == $priceVol) ? 'VOLUME' : 'KOLI')
            ];
            
            return $tariff;
        }
        
        return null;
    }
    
    /**
     * Self-healing: Ensure table exists, if not, run the migration.
     */
    private function ensureTableExists()
    {
        try {
            self::$db->query("SELECT 1 FROM {$this->table} LIMIT 1");
        } catch (PDOException $e) {
            // Table doesn't exist, self-heal
            $migrationFile = dirname(__DIR__, 2) . '/database/migrations/03_tariffs_schema.sql';
            $seederFile = dirname(__DIR__, 2) . '/database/seeders/02_tariffs_seed.sql';
            
            if (file_exists($migrationFile)) {
                $sql = file_get_contents($migrationFile);
                self::$db->exec($sql);
            }
            if (file_exists($seederFile)) {
                $sql = file_get_contents($seederFile);
                self::$db->exec($sql);
            }
        }
    }
}
