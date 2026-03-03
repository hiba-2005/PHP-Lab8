<?php
namespace App\Core;

class Request
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return parse_url($uri, PHP_URL_PATH);
    }

    public function getQueryParam(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function getBodyParam(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    public function getBody(): array
    {
        return $_POST;
    }
}