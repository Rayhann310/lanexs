<?php

namespace App\Models;

use PDO;
use PDOException;

abstract class BaseModel
{
    protected static ?PDO $db = null;
    protected string $table;
    protected string $primaryKey = 'id';
    
    // Multi-tenant scopes
    protected bool $useCompanyScope = false;
    protected bool $useBranchScope = false;
    
    public function __construct()
    {
        if (self::$db === null) {
            $this->connect();
        }
    }

    private function connect()
    {
        $config = require BASE_PATH . '/config/database.php';
        
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
        
        try {
            self::$db = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            ]);
        } catch (PDOException $e) {
            die("Database connection failed. Please create database '{$config['database']}' first.");
        }
    }

    public function getDb(): PDO
    {
        return self::$db;
    }

    /**
     * Find a record by Primary Key
     */
    public function find($id)
    {
        $stmt = self::$db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get all records
     */
    public function all()
    {
        $stmt = self::$db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    /**
     * Insert a new record
     */
    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = self::$db->prepare($sql);
        
        if ($stmt->execute($data)) {
            return self::$db->lastInsertId();
        }
        return false;
    }

    /**
     * Update an existing record by Primary Key
     */
    public function update($id, array $data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setClause = implode(', ', $set);
        
        $sql = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = :id";
        $stmt = self::$db->prepare($sql);
        
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Delete a record by Primary Key
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
