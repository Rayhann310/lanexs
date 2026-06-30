<?php

namespace App\Models;

class AuditLog extends BaseModel
{
    protected string $table = 'audit_logs';
    protected bool $useCompanyScope = false;
    protected bool $useBranchScope = false;
}
