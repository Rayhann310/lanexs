<?php

namespace App\Models;

class Manifest extends BaseModel
{
    protected string $table = 'manifests';

    public function generateManifestCode(string $originCode, string $destCode)
    {
        $date = date('ymd');
        $prefix = "MNF-{$originCode}-{$destCode}-{$date}";
        
        $sql = "SELECT manifest_code FROM {$this->table} WHERE manifest_code LIKE :prefix ORDER BY id DESC LIMIT 1";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['prefix' => $prefix . '%']);
        $lastManifest = $stmt->fetch();

        if ($lastManifest) {
            $lastNum = (int)substr($lastManifest['manifest_code'], -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }

        return "{$prefix}-{$newNum}";
    }
    
    public function updateStatusCascade(int $manifestId, string $status, int $userId, int $branchId)
    {
        // Update manifest status
        $this->update($manifestId, ['status' => $status]);
        
        // Fetch all items inside manifest
        $sql = "SELECT * FROM manifest_items WHERE manifest_id = :manifest_id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['manifest_id' => $manifestId]);
        $items = $stmt->fetchAll();
        
        $packageModel = new Package();
        $bagModel = new Bag();
        $trackingModel = new TrackingHistory();
        
        foreach ($items as $item) {
            if ($item['item_type'] === 'PACKAGE') {
                $packageModel->update($item['item_id'], ['status' => $status]);
                $trackingModel->create([
                    'package_id' => $item['item_id'],
                    'branch_id' => $branchId,
                    'user_id' => $userId,
                    'status' => $status,
                    'description' => "Paket dipindai dalam Surat Jalan menuju status: {$status}"
                ]);
            } else if ($item['item_type'] === 'BAG') {
                $bagModel->updateStatusCascade($item['item_id'], $status, $userId, $branchId);
            }
        }
    }
}
