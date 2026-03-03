<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/\\');
    }

    public function render(string $view, array $params = []): void
    {
        $viewFile = $this->basePath . '/' . ltrim($view, '/\\');

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vue introuvable: " . htmlspecialchars($viewFile, ENT_QUOTES, 'UTF-8');
            return;
        }

        extract($params, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layout = $this->basePath . '/layout.php';

        if (file_exists($layout)) {
            require $layout;
        } else {
            echo $content;
        }
    }
}