<?php

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Gestion Étudiants</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    nav { display:flex; justify-content:space-between; align-items:center; padding:10px; background:#f3f3f3; border-radius:8px; }
    nav ul { list-style:none; display:flex; gap:10px; margin:0; padding:0; align-items:center; }
    nav a { text-decoration:none; padding:6px 10px; border-radius:6px; }
    nav a:hover { background:#e6e6e6; }
    .secondary { padding:6px 10px; border-radius:6px; border:1px solid #999; background:#fff; cursor:pointer; }
    .secondary:hover { background:#eee; }
    .container { margin-top: 14px; }
    .error { color:#b00020; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th, td { border:1px solid #ddd; padding:8px; text-align:left; }
    th { background:#fafafa; }
    .pagination a { margin:0 3px; padding:4px 8px; border:1px solid #ddd; border-radius:6px; text-decoration:none; }
    .pagination a[aria-current="page"] { font-weight:bold; background:#eee; }
    form.inline { display:inline; }
  </style>
</head>
<body>

<nav>
  <ul>
    <li><strong>Gestion Étudiants</strong></li>
  </ul>

  <ul>
    <li><a href="/etudiants">Liste</a></li>
    <li><a href="/etudiants/create">Ajouter</a></li>

    <?php if (!empty($_SESSION['admin_id'])): ?>
      <li>
        <form method="post" action="/logout" class="inline">
          <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <button type="submit" class="secondary">Se déconnecter</button>
        </form>
      </li>
    <?php else: ?>
      <li><a href="/login">Se connecter</a></li>
    <?php endif; ?>
  </ul>
</nav>

<div class="container">
  <?php echo $content ?? ''; ?>
</div>

</body>
</html>