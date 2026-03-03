<?php
declare(strict_types=1);

namespace App\Container;

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\View;

use App\Controller\EtudiantController;
use App\Controller\AuthController;

use App\Dao\DBConnection;
use App\Dao\Logger;
use App\Dao\EtudiantDao;
use App\Dao\FiliereDao;
use App\Dao\AdminDao;

use App\Security\Auth;
use App\Security\Csrf;
use App\Security\Middleware;

class AppFactory
{
    public function create(): array
    {
        // ===== DB config =====
        $dbHost = '127.0.0.1';
        $dbName = 'gestion_etudiants_pdo';
        $dbUser = 'root';
        $dbPass = '';
        $charset = 'utf8mb4';

        $logger = new Logger(__DIR__ . '/../../logs/app.log');
        $pdo = DBConnection::create($dbHost, $dbName, $dbUser, $dbPass, $charset, $logger);

        // ===== Security services =====
        $adminDao = new AdminDao($pdo, $logger);

        $auth = new Auth($adminDao);
        $auth->startSession();

        $csrf = new Csrf();
        $csrf->token();

        $mw = new Middleware($auth, $csrf);

        // ===== MVC core =====
        $etudiantDao = new EtudiantDao($pdo, $logger);
        $filiereDao  = new FiliereDao($pdo, $logger);

        $view     = new View(__DIR__ . '/../../views');
        $response = new Response();
        $request  = new Request();
        $router   = new Router();

        // ===== Controllers =====
        $etudiantController = new EtudiantController($view, $response, $etudiantDao, $filiereDao);
        $authController     = new AuthController($view, $response, $auth, $csrf);

        
        // Routes publiques
       

        $router->get('/', function(Request $req, array $params = []) use ($response) {
            $response->redirect('/etudiants');
        });

        $router->get('/login', function(Request $req, array $params = []) use ($authController) {
            $authController->loginForm();
        });

        $router->post('/login',
            $mw->requireCsrfPost(function(Request $req, array $params = []) use ($authController) {
                $authController->login($req);
            })
        );

        // Logout = POST + CSRF + (recommandé) admin
        $router->post('/logout',
            $mw->requireAdmin(
                $mw->requireCsrfPost(function(Request $req, array $params = []) use ($authController) {
                    $authController->logout();
                })
            )
        );

       
        // 1) Liste (public)
        $router->get('/etudiants', function(Request $req, array $params = []) use ($etudiantController) {
            $etudiantController->index($req);
        });

        // 2) Create (admin)
        $router->get('/etudiants/create',
            $mw->requireAdmin(function(Request $req, array $params = []) use ($etudiantController) {
                $etudiantController->create($req);
            })
        );

        // 3) Store (admin + CSRF)
        $router->post('/etudiants/store',
            $mw->requireAdmin(
                $mw->requireCsrfPost(function(Request $req, array $params = []) use ($etudiantController) {
                    $etudiantController->store($req);
                })
            )
        );

        // 4) Edit (admin)   avant /etudiants/{id}
        $router->get('/etudiants/{id}/edit',
            $mw->requireAdmin(function(Request $req, array $params = []) use ($etudiantController) {
                $id = (int)($params[0] ?? 0);
                $etudiantController->edit($req, ['id' => $id]);
            })
        );

        // 5) Update (admin + CSRF)
        $router->post('/etudiants/{id}/update',
            $mw->requireAdmin(
                $mw->requireCsrfPost(function(Request $req, array $params = []) use ($etudiantController) {
                    $id = (int)($params[0] ?? 0);
                    $etudiantController->update($req, ['id' => $id]);
                })
            )
        );

        // 6) Delete (admin + CSRF)
        $router->post('/etudiants/{id}/delete',
            $mw->requireAdmin(
                $mw->requireCsrfPost(function(Request $req, array $params = []) use ($etudiantController) {
                    $id = (int)($params[0] ?? 0);
                    $etudiantController->delete($req, ['id' => $id]);
                })
            )
        );

        // 7) Show (public)   
        $router->get('/etudiants/{id}', function(Request $req, array $params = []) use ($etudiantController) {
            $id = (int)($params[0] ?? 0);
            $etudiantController->show($req, ['id' => $id]);
        });
         $router->get('/api/etudiants', function(Request $req, array $params = []) use ($etudiantController) {
            $etudiantController->apiList($req);
        });

        return [$router, $request];
    }
}