<?php

namespace App\Models;

class BukuKeuangan extends BaseModel
{
    protected string $table = 'buku_keuangan';

    public function __construct()
    {
        parent::__construct();
        $this->checkAndCreateTable();
    }

    private function checkAndCreateTable()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tanggal DATE NOT NULL,
            no_invoice VARCHAR(100) NOT NULL,
            customer VARCHAR(150) NOT NULL,
            tujuan VARCHAR(150) NOT NULL,
            tagihan_invoice DECIMAL(15,2) DEFAULT 0,
            vendor DECIMAL(15,2) DEFAULT 0,
            operasional DECIMAL(15,2) DEFAULT 0,
            is_pph BOOLEAN DEFAULT 0,
            is_ppn BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        try {
            self::$db->exec($sql);
        } catch (\PDOException $e) {
            // Ignore error or log it
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY tanggal DESC, id DESC";
        return self::$db->query($sql)->fetchAll();
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (tanggal, no_invoice, customer, tujuan, tagihan_invoice, vendor, operasional, is_pph, is_ppn) 
                VALUES (:tanggal, :no_invoice, :customer, :tujuan, :tagihan_invoice, :vendor, :operasional, :is_pph, :is_ppn)";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute([
            'tanggal' => $data['tanggal'],
            'no_invoice' => $data['no_invoice'],
            'customer' => $data['customer'],
            'tujuan' => $data['tujuan'],
            'tagihan_invoice' => $data['tagihan_invoice'] ?? 0,
            'vendor' => $data['vendor'] ?? 0,
            'operasional' => $data['operasional'] ?? 0,
            'is_pph' => $data['is_pph'] ?? 0,
            'is_ppn' => $data['is_ppn'] ?? 0
        ]);
    }

    public function updateRecord(int $id, array $data)
    {
        $sql = "UPDATE {$this->table} 
                SET tanggal = :tanggal, no_invoice = :no_invoice, customer = :customer, tujuan = :tujuan, 
                    tagihan_invoice = :tagihan_invoice, vendor = :vendor, operasional = :operasional, 
                    is_pph = :is_pph, is_ppn = :is_ppn 
                WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'tanggal' => $data['tanggal'],
            'no_invoice' => $data['no_invoice'],
            'customer' => $data['customer'],
            'tujuan' => $data['tujuan'],
            'tagihan_invoice' => $data['tagihan_invoice'] ?? 0,
            'vendor' => $data['vendor'] ?? 0,
            'operasional' => $data['operasional'] ?? 0,
            'is_pph' => $data['is_pph'] ?? 0,
            'is_ppn' => $data['is_ppn'] ?? 0
        ]);
    }

    public function deleteRecord(int $id)
    {
        $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL LIMIT 1";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
