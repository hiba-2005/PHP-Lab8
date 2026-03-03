<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Dao\EtudiantDao;
use App\Dao\FiliereDao;
use App\Security\Sanitizer;
use App\Security\Validator;

class EtudiantController extends BaseController
{
    private EtudiantDao $etudiantDao;
    private FiliereDao $filiereDao;

    public function __construct(View $view, Response $response, EtudiantDao $etudiantDao, FiliereDao $filiereDao)
    {
        parent::__construct($view, $response);
        $this->etudiantDao = $etudiantDao;
        $this->filiereDao = $filiereDao;
    }

    /** LISTE + RECHERCHE + PAGINATION */
    public function index(Request $request, array $params = []): void
    {
        $q = Sanitizer::string((string)$request->getQueryParam('q', ''), 100);
        $filiereId = (int)$request->getQueryParam('filiere_id', 0);
        $page = (int)$request->getQueryParam('page', 1);
        $size = (int)$request->getQueryParam('size', 5);

        $page = max(1, $page);
        $size = max(1, min(100, $size));

        $total = $this->etudiantDao->countSearch($q, $filiereId ?: null);
        $items = $this->etudiantDao->searchPaginated($q, $filiereId ?: null, $page, $size);
        $totalPages = max(1, (int)ceil($total / max(1, $size)));

        $filieres = $this->filiereDao->findAll();

        $this->render('etudiant/index.php', [
            'etudiants' => $items,
            'q' => $q,
            'filiereId' => $filiereId,
            'filieres' => $filieres,
            'page' => $page,
            'size' => $size,
            'total' => $total,
            'totalPages' => $totalPages,
        ]);
    }

    /** FORM CREATE */
    public function create(Request $request, array $params = []): void
    {
        $this->render('etudiant/create.php', [
            'errors' => [],
            'old' => [],
            'filieres' => $this->filiereDao->findAll(),
        ]);
    }

    /** POST CREATE */
    public function store(Request $request, array $params = []): void
    {
        $data = $this->sanitize($_POST);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->render('etudiant/create.php', [
                'errors' => $errors,
                'old' => $data,
                'filieres' => $this->filiereDao->findAll(),
            ]);
            return;
        }

        $this->etudiantDao->insert($data);
        $this->redirect('/etudiants');
    }

    /** SHOW */
    public function show(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $etudiant = $this->etudiantDao->findById($id);

        if (!$etudiant) {
            http_response_code(404);
            echo "Etudiant introuvable";
            return;
        }

        $this->render('etudiant/show.php', ['etudiant' => $etudiant]);
    }

    /** FORM EDIT */
    public function edit(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $etudiant = $this->etudiantDao->findById($id);

        if (!$etudiant) {
            http_response_code(404);
            echo "Etudiant introuvable";
            return;
        }

        $this->render('etudiant/edit.php', [
            'errors' => [],
            'old' => $etudiant,
            'filieres' => $this->filiereDao->findAll(),
        ]);
    }

    /** POST UPDATE */
    public function update(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo "ID invalide";
            return;
        }

        $data = $this->sanitize($_POST);
        $errors = $this->validate($data, $id);

        if (!empty($errors)) {
            $data['id'] = $id;
            $this->render('etudiant/edit.php', [
                'errors' => $errors,
                'old' => $data,
                'filieres' => $this->filiereDao->findAll(),
            ]);
            return;
        }

        $this->etudiantDao->update($id, $data);
        $this->redirect('/etudiants');
    }

    /** POST DELETE */
    public function delete(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo "ID invalide";
            return;
        }

        $this->etudiantDao->delete($id);
        $this->redirect('/etudiants');
    }

    /** Helpers */
    private function sanitize(array $data): array
    {
        $data = Sanitizer::trimArray($data);

        return [
            'cne' => strtoupper(Sanitizer::string($data['cne'] ?? '', 20)),
            'nom' => Sanitizer::string($data['nom'] ?? '', 50),
            'prenom' => Sanitizer::string($data['prenom'] ?? '', 50),
            'email' => Sanitizer::email($data['email'] ?? ''),
            'filiere_id' => (int)($data['filiere_id'] ?? 0),
        ];
    }

    private function validate(array $data, ?int $id = null): array
    {
        $errors = [];

        if (!Validator::cne($data['cne'])) {
            $errors['cne'] = 'CNE requis (A-Z, 0-9, 6-20).';
        }

        if ($data['nom'] === '' || !Validator::maxLen($data['nom'], 50)) {
            $errors['nom'] = 'Nom requis (<=50).';
        }

        if ($data['prenom'] === '' || !Validator::maxLen($data['prenom'], 50)) {
            $errors['prenom'] = 'Prénom requis (<=50).';
        }

        if (!Validator::email($data['email']) || !Validator::maxLen($data['email'], 100)) {
            $errors['email'] = 'Email invalide (<=100).';
        }

        $fid = (int)$data['filiere_id'];
        if ($fid <= 0 || !$this->filiereDao->findById($fid)) {
            $errors['filiere_id'] = 'Filière invalide.';
        }

        return $errors;
    }
}