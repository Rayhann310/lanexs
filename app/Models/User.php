<?php

namespace App\Models;

class User extends BaseModel
{
    protected string $table = 'users';

    public function findByUsername(string $username)
    {
        $stmt = self::$db->prepare("SELECT * FROM {$this->table} WHERE username = :username AND is_active = 1 LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function updateLastLogin(int $userId, string $ipAddress)
    {
        $stmt = self::$db->prepare("UPDATE {$this->table} SET last_login = NOW(), last_ip = :ip WHERE id = :id");
        $stmt->execute(['ip' => $ipAddress, 'id' => $userId]);
    }

    public function getAllWithRelations()
    {
        $sql = "
            SELECT u.*, r.name as role_name, b.name as branch_name 
            FROM {$this->table} u
            LEFT JOIN roles r ON u.role_id = r.id
            LEFT JOIN branches b ON u.branch_id = b.id
            WHERE u.deleted_at IS NULL
            ORDER BY u.created_at DESC
        ";
        return self::$db->query($sql)->fetchAll();
    }
}
