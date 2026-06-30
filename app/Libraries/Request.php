<?php

namespace App\Libraries;

class Request
{
    private array $data;
    private string $method;
    private string $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $this->parseUri();
        $this->data = array_merge($_GET, $_POST);
        
        // Handle JSON payloads
        if ($this->method === 'POST' || $this->method === 'PUT') {
            $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if (str_contains($contentType, 'application/json')) {
                $content = trim(file_get_contents("php://input"));
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $this->data = array_merge($this->data, $decoded);
                }
            }
        }
    }

    private function parseUri(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Find the base path, accounting for the /public folder
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = str_replace('\\', '/', $scriptName);
        if (str_ends_with($basePath, '/public')) {
            $basePath = substr($basePath, 0, -7);
        }
        
        if ($basePath !== '/' && $basePath !== '') {
            if (str_starts_with($uri, $basePath)) {
                $uri = substr($uri, strlen($basePath));
            }
        }
        
        return rtrim($uri, '/') ?: '/';
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }
}
