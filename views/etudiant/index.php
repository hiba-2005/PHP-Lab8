<?php


$base = '/etudiants?size='.(int)$size.'&q='.urlencode($q).'&filiere_id='.(int)$filiereId.'&page=';
?>

<h2>Étudiants</h2>

<form method="get" action="/etudiants">
  <input
    name="q"
    placeholder="Rechercher (nom, prénom, email, CNE)"
    value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>"
  >

  <select name="filiere_id">
    <option value="0">Toutes filières</option>
    <?php foreach ($filieres as $f): ?>
      <option value="<?php echo (int)$f['id']; ?>" <?php echo ((int)$filiereId === (int)$f['id']) ? 'selected' : ''; ?>>
        <?php echo htmlspecialchars(($f['code'] ?? '').' — '.($f['libelle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
      </option>
    <?php endforeach; ?>
  </select>

  <input type="hidden" name="size" value="<?php echo (int)$size; ?>">
  <button type="submit">Filtrer</button>
</form>

<p>
  Total: <?php echo (int)$total; ?> —
  Page <?php echo (int)$page; ?>/<?php echo (int)$totalPages; ?>
</p>

<p>
  <a href="/etudiants/create">Nouveau</a>
</p>

<?php if (empty($etudiants)): ?>
  <p>Aucun étudiant.</p>
<?php else: ?>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>CNE</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Email</th>
      <th>Filière</th>
      <th>Actions</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($etudiants as $e): ?>
      <tr>
        <td><?php echo (int)$e['id']; ?></td>
        <td><?php echo htmlspecialchars($e['cne'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($e['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($e['prenom'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($e['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars(($e['filiere_code'] ?? '').' — '.($e['filiere_libelle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
        <td>
          <a href="/etudiants/show?id=<?php echo (int)$e['id']; ?>">Voir</a>
          |
          <a href="/etudiants/edit?id=<?php echo (int)$e['id']; ?>">Éditer</a>
          |
          <form action="/etudiants/delete" method="post" class="inline" onsubmit="return confirm('Supprimer ?');">
            <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
            <button type="submit" class="secondary">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<nav class="pagination" style="margin-top:10px">
  <?php if ($page > 1): ?>
    <a href="<?php echo $base.($page-1); ?>">« Préc.</a>
  <?php endif; ?>

  <?php for ($p = 1; $p <= $totalPages; $p++): ?>
    <a href="<?php echo $base.$p; ?>" <?php echo ($p === (int)$page) ? 'aria-current="page"' : ''; ?>>
      <?php echo $p; ?>
    </a>
  <?php endfor; ?>

  <?php if ($page < $totalPages): ?>
    <a href="<?php echo $base.($page+1); ?>">Suiv. »</a>
  <?php endif; ?>
</nav>

<?php endif; ?>