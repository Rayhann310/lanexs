<?php

namespace App\Models;

class Bag extends BaseModel
{
    protected string $table = 'bags';

    public function generateBagCode(string $originCode, string $destCode)
    {
        $date = date('ymd');
        $prefix = "BAG-{$originCode}-{$destCode}-{$date}";
        
        $sql = "SELECT bag_code FROM {$this->table} WHERE bag_code LIKE :prefix ORDER BY id DESC LIMIT 1";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['prefix' => $prefix . '%']);
        $lastBag = $stmt->fetch();

        if ($lastBag) {
            $lastNum = (int)substr($lastBag['bag_code'], -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }

        return "{$prefix}-{$newNum}";
    }
    
    public function getItems(int $bagId)
    {
        $sql = "SELECT p.* FROM packages p 
                JOIN bag_items bi ON p.id = bi.package_id 
                WHERE bi.bag_id = :bag_id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['bag_id' => $bagId]);
        return $stmt->fetchAll();
    }
    
    public function updateStatusCascade(int $bagId, string $status, int $userId, int $branchId)
    {
        // Update bag status
        $this->update($bagId, ['status' => $status]);
        
        // Cascade to items
        $items = $this->getItems($bagId);
        $packageModel = new Package();
        $trackingModel = new TrackingHistory();
        
        foreach ($items as $pkg) {
            $packageModel->update($pkg['id'], ['status' => $status]);
            $trackingModel->create([
                'package_id' => $pkg['id'],
                'branch_id' => $branchId,
                'user_id' => $userId,
                'status' => $status,
                'description' => "Paket dipindai dalam Karung menuju status: {$status}"
            ]);
        }
    }
}
