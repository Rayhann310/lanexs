<?php

namespace App\Models;

class LandingPage extends BaseModel
{
    protected string $table = 'landing_pages';

    public function findBySlug($slug)
    {
        $stmt = self::$db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
