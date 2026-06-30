<?php

namespace App\Models;

class Setting extends BaseModel
{
    protected string $table = 'settings';
    
    /**
     * Get a setting value by key
     */
    public function get($key, $default = null)
    {
        $stmt = self::$db->prepare("SELECT key_value FROM {$this->table} WHERE key_name = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        
        if ($result) {
            return $result['key_value'];
        }
        
        return $default;
    }
    
    /**
     * Set a setting value by key
     */
    public function set($key, $value)
    {
        // Check if exists
        $stmt = self::$db->prepare("SELECT id FROM {$this->table} WHERE key_name = ?");
        $stmt->execute([$key]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            $stmt = self::$db->prepare("UPDATE {$this->table} SET key_value = ? WHERE key_name = ?");
            return $stmt->execute([$value, $key]);
        } else {
            $stmt = self::$db->prepare("INSERT INTO {$this->table} (key_name, key_value) VALUES (?, ?)");
            return $stmt->execute([$key, $value]);
        }
    }
}
