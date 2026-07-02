<?php

namespace App\Models;

class Package extends BaseModel
{
    protected string $table = 'packages';

    public function generateResi(string $originCode = ''): string
    {
        // Format: LNX[YYYY][ID]
        // E.g. LNX20260017
        
        $year = date('Y');
        $prefix = "LNX{$year}";
        
        $stmt = self::$db->prepare("SELECT resi FROM {$this->table} WHERE resi LIKE :prefix ORDER BY id DESC LIMIT 1");
        $stmt->execute(['prefix' => $prefix . '%']);
        $last = $stmt->fetch();
        
        if ($last) {
            $lastId = intval(substr($last['resi'], strlen($prefix)));
            $newIdStr = str_pad((string)($lastId + 1), 4, '0', STR_PAD_LEFT);
        } else {
            // Start from 17 as requested (since last was 16 before migration)
            $newIdStr = '0017';
        }
        
        return "{$prefix}{$newIdStr}";
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
