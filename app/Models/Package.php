<?php

namespace App\Models;

class Package extends BaseModel
{
    protected string $table = 'packages';

    public function generateResi(string $originCode): string
    {
        // Format: KTX-[ORIGIN]-[DATE]-[ID]
        // E.g. KTX-JKT-2607-000001
        
        $dateStr = date('ym'); // Year Month e.g., 2607 for July 2026
        
        // Find last resi with this prefix
        $prefix = "KTX-{$originCode}-{$dateStr}";
        
        $stmt = self::$db->prepare("SELECT resi FROM {$this->table} WHERE resi LIKE :prefix ORDER BY id DESC LIMIT 1");
        $stmt->execute(['prefix' => $prefix . '%']);
        $last = $stmt->fetch();
        
        if ($last) {
            $lastId = intval(substr($last['resi'], -6));
            $newId = str_pad((string)($lastId + 1), 6, '0', STR_PAD_LEFT);
        } else {
            $newId = '000001';
        }
        
        return "{$prefix}-{$newId}";
    }

    public function getAllWithRelations()
    {
        $sql = "
            SELECT p.*, 
                   bo.name as origin_branch_name,
                   bd.name as dest_branch_name
            FROM packages p
            LEFT JOIN branches bo ON p.origin_branch_id = bo.id
            LEFT JOIN branches bd ON p.destination_branch_id = bd.id
            ORDER BY p.id DESC
        ";
        $stmt = self::$db->query($sql);
        return $stmt->fetchAll();
    }
    public function findByResi(string $resi)
    {
        $stmt = self::$db->prepare("SELECT * FROM {$this->table} WHERE resi = :resi LIMIT 1");
        $stmt->execute(['resi' => $resi]);
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }
}
