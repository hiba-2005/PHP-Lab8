<?php
namespace App\Security;

use App\Core\Request;

class Middleware
{
    private $auth; private $csrf;
    public function __construct(Auth $auth, Csrf $csrf)
    { $this->auth = $auth; $this->csrf = $csrf; }

    /** Enveloppe un handler en imposant une session admin. */
    public function requireAdmin(callable $handler): callable
    {
        return function (Request $request, array $params = []) use ($handler) {
            if (!$this->auth->isAuthenticated()) {
                header('Location: /login');
                http_response_code(302);
                return;
            }
            return call_user_func($handler, $request, $params);
        };
    }

    /** Vérifie le token CSRF pour les POST. */
    public function requireCsrfPost(callable $handler): callable
    {
        return function (Request $request, array $params = []) use ($handler) {
            if ($request->getMethod() !== 'POST') { return call_user_func($handler, $request, $params); }
            $token = $request->getBodyParam('_csrf');
            if (!$this->csrf->verify($token)) {
                http_response_code(403);
                header('Content-Type: text/plain; charset=utf-8');
                echo '403 CSRF invalid';
                return;
            }
            return call_user_func($handler, $request, $params);
        };
    }
}