<?php

namespace App\Libraries;

class Response
{
    public static function json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function redirect(string $url)
    {
        // Use BASE_URL dynamically defined in public/index.php
        if (!str_starts_with($url, 'http')) {
            $baseUrl = defined('BASE_URL') ? BASE_URL : '';
            $url = rtrim($baseUrl, '/') . '/' . ltrim($url, '/');
        }
        
        header("Location: $url");
        exit;
    }
}
