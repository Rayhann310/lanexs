<?php

namespace App\Models;

class TrackingHistory extends BaseModel
{
    protected string $table = 'tracking_histories';

    public function __construct()
    {
        parent::__construct();
        $this->ensureProofImageColumn();
    }

    private function ensureProofImageColumn()
    {
        try {
            $stmt = self::$db->query("SHOW COLUMNS FROM {$this->table} LIKE 'proof_image'");
            if ($stmt->rowCount() === 0) {
                self::$db->exec("ALTER TABLE {$this->table} ADD COLUMN proof_image VARCHAR(255) NULL AFTER description");
            }
        } catch (\PDOException $e) {
            // ignore if it fails
        }
    }
}
