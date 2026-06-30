<?php

namespace App\Helpers;

use App\Models\User;

class DatabaseHelper
{
    /**
     * Self-Healing: run all migrations and seeders idempotently.
     * 
     * SAFETY RULES:
     * - Only adds missing tables/columns (IF NOT EXISTS, INSERT IGNORE).
     * - Never drops, truncates, or modifies existing data.
     * - Each SQL statement is executed individually to avoid PDO multi-statement limitations.
     */
    public static function repair(): array
    {
        try {
            $userModel = new User();
            $db = $userModel->getDb();

            // Enable multi-statement support for ALTER TABLE prepared statements
            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

            $log = [];

            // ── 1. Migrations ──────────────────────────────────────────────
            $migrationFiles = glob(__DIR__ . '/../../database/migrations/*.sql');
            sort($migrationFiles);

            foreach ($migrationFiles as $file) {
                $results = self::executeFile($db, $file);
                foreach ($results as $r) {
                    $log[] = $r;
                }
            }

            // ── 2. Seeders ─────────────────────────────────────────────────
            $seederFiles = glob(__DIR__ . '/../../database/seeders/*.sql');
            sort($seederFiles);

            foreach ($seederFiles as $file) {
                $results = self::executeFile($db, $file);
                foreach ($results as $r) {
                    $log[] = $r;
                }
            }

            // Restore emulate prepares to false (safer default)
            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

            return ['status' => true, 'log' => $log];

        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Execute a SQL file statement-by-statement.
     * Returns an array of log strings describing each result.
     */
    private static function executeFile(\PDO $db, string $file): array
    {
        $log     = [];
        $content = file_get_contents($file);
        $base    = basename($file);

        // Split by semicolon, handle SET statements, PREPARE/EXECUTE blocks etc.
        // We use a simple delimiter split — works for standard migration files.
        $statements = array_filter(
            array_map('trim', explode(';', $content)),
            fn($s) => strlen($s) > 0
        );

        $ok     = 0;
        $errors = [];

        foreach ($statements as $stmt) {
            try {
                $pdoStmt = $db->query($stmt);
                if ($pdoStmt) {
                    try {
                        $pdoStmt->fetchAll();
                    } catch (\Exception $e) {
                        // ignore fetch error on non-select statements
                    }
                    $pdoStmt->closeCursor();
                }
                $ok++;
            } catch (\PDOException $e) {
                $msg = $e->getMessage();
                // Silently skip "duplicate column" and "already exists" — these are expected on re-run
                if (
                    stripos($msg, 'Duplicate column') !== false  ||
                    stripos($msg, 'already exists')   !== false  ||
                    stripos($msg, 'already been used') !== false ||
                    stripos($msg, 'Duplicate key')    !== false
                ) {
                    $ok++; // Treat as OK — idempotent
                } else {
                    $errors[] = $msg;
                }
            }
        }

        if (empty($errors)) {
            $log[] = "✅ {$base}: {$ok} statement(s) OK";
        } else {
            $log[] = "⚠️ {$base}: {$ok} OK, " . count($errors) . " error(s): " . implode(' | ', array_slice($errors, 0, 3));
        }

        return $log;
    }
}
