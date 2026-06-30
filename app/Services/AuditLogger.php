<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogger
{
    /**
     * Log an action to the audit_logs table
     *
     * @param string $action       E.g., "CREATE", "UPDATE", "DELETE", "LOGIN"
     * @param string $entityType   E.g., "Package", "Manifest", "User"
     * @param int|null $entityId   ID of the affected entity
     * @param mixed $oldData  Data before change
     * @param mixed $newData  Data after change
     */
    public static function log(string $action, string $entityType, ?int $entityId = null, $oldData = null, $newData = null)
    {
        $userId = $_SESSION['user_id'] ?? null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // Convert arrays to JSON safely
        $oldDataJson = $oldData !== null ? json_encode($oldData) : null;
        $newDataJson = $newData !== null ? json_encode($newData) : null;

        try {
            $audit = new AuditLog();
            $audit->create([
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'old_data' => $oldDataJson,
                'new_data' => $newDataJson,
                'ip_address' => $ipAddress
            ]);
        } catch (\Exception $e) {
            // Self healing: if table not found, repair and retry once
            if (strpos($e->getMessage(), '42S02') !== false || strpos($e->getMessage(), 'doesn\'t exist') !== false) {
                \App\Helpers\DatabaseHelper::repair();
                $audit = new AuditLog();
                $audit->create([
                    'user_id' => $userId,
                    'action' => $action,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                    'old_data' => $oldDataJson,
                    'new_data' => $newDataJson,
                    'ip_address' => $ipAddress
                ]);
            } else {
                throw $e;
            }
        }
    }
}
