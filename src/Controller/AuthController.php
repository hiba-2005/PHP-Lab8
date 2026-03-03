<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Security\Auth;
use App\Security\Csrf;

class AuthController extends BaseController
{
    private Auth $auth;

    public function __construct($view, $response, Auth $auth, Csrf $csrf)
    {
        parent::__construct($view, $response, $csrf);
        $this->auth = $auth;
    }

    public function loginForm(): void
    {
        $this->render('auth/login.php', [
            'errors' => [],
            'old' => []
        ]);
    }

    public function login(Request $request): void
    {
        $username = trim((string)$request->getBodyParam('username'));
        $password = (string)$request->getBodyParam('password');

        if ($username === '' || $password === '') {
            $this->render('auth/login.php', [
                'errors' => ['global' => 'Identifiants requis.'],
                'old' => ['username' => $username]
            ]);
            return;
        }

        if ($this->auth->login($username, $password)) {
            $this->redirect('/etudiants');
            return;
        }

        $this->render('auth/login.php', [
            'errors' => ['global' => 'Login ou mot de passe invalide.'],
            'old' => ['username' => $username]
        ]);
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->redirect('/login');
    }
}