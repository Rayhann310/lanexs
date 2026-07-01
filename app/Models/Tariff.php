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
    public function calculate($originBranchId, $destBranchId, $weight, $volume = 0, $koli = 1, $originCity = null, $destCity = null)
    {
        $this->ensureTableExists();
        
        $tariff = null;

        // Mode 1: Branch to Branch priority
        if ($originBranchId && $destBranchId) {
            $branchModel = new Branch();
            $originBranch = $branchModel->find($originBranchId);
            $destBranch = $branchModel->find($destBranchId);
            
            if ($originBranch && $destBranch) {
                // Determine city from branches if not explicitly provided
                $originCity = $originCity ?: $originBranch['city'];
                $destCity = $destCity ?: $destBranch['city'];

                $sql = "SELECT * FROM {$this->table} WHERE type = 'BRANCH' AND origin_branch_id = :origin AND destination_branch_id = :dest AND is_active = 1 LIMIT 1";
                $stmt = self::$db->prepare($sql);
                $stmt->execute(['origin' => $originBranchId, 'dest' => $destBranchId]);
                $tariff = $stmt->fetch();
            }
        }

        // Mode 2: City to City (either directly requested or fallback from branch)
        if (!$tariff && $originCity && $destCity) {
            $sql = "SELECT * FROM {$this->table} WHERE type = 'CITY' AND origin_city = :origin_city AND destination_city = :dest_city AND is_active = 1 LIMIT 1";
            $stmt = self::$db->prepare($sql);
            $stmt->execute(['origin_city' => $originCity, 'dest_city' => $destCity]);
            $tariff = $stmt->fetch();
        }

        // Mode 3: Smart Fallback Tariff (Auto-Generated on the fly if not found)
        // User requested automatic tariffs from any city to any city.
        if (!$tariff) {
            $tariff = [
                'type' => 'CITY',
                'origin_city' => $originCity,
                'destination_city' => $destCity,
                'price_per_kg' => 15000, // Rp 15.000 / kg default
                'price_per_volume' => 30000,
                'price_per_koli' => 50000,
                'estimated_days' => 3
            ];
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
