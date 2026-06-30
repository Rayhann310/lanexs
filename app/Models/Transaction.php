<?php

namespace App\Models;

class Transaction extends BaseModel
{
    protected string $table = 'transactions';

    // Get branch balance
    public function getBalance($branchId)
    {
        $sql = "SELECT 
                    SUM(CASE WHEN type = 'INCOME' THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN type = 'EXPENSE' THEN amount ELSE 0 END) as total_expense
                FROM transactions 
                WHERE branch_id = :branch_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['branch_id' => $branchId]);
        $row = $stmt->fetch();
        
        $income = $row['total_income'] ?? 0;
        $expense = $row['total_expense'] ?? 0;
        
        return $income - $expense;
    }
}
