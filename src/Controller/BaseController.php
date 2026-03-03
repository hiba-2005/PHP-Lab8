<?php
namespace App\Controller;

use App\Core\Response;
use App\Core\View;
use App\Security\Csrf;

class BaseController
{
    protected $view; protected $response; protected $csrf;

    public function __construct(View $view, Response $response, ?Csrf $csrf = null)
    { $this->view = $view; $this->response = $response; $this->csrf = $csrf; }

    protected function render(string $view, array $params = []): void
    { $this->view->render($view, $params); }

    protected function redirect(string $url, int $status = 302): void
    { $this->response->redirect($url, $status); }

    protected function json($data, int $status = 200): void
    { $this->response->json($data, $status); }

    protected function e(?string $s): string
    { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

    protected function csrfField(): string
    {
        $token = $_SESSION['csrf_token'] ?? '';
        return '<input type="hidden" name="_csrf" value="' . $this->e($token) . '">';
    }
}