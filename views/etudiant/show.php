<?php

$id = (int)($etudiant['id'] ?? 0);
?>

<h2>Détails étudiant</h2>

<ul>
  <li><strong>ID:</strong> <?php echo $id; ?></li>
  <li><strong>CNE:</strong> <?php echo htmlspecialchars($etudiant['cne'] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
  <li><strong>Nom:</strong> <?php echo htmlspecialchars($etudiant['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
  <li><strong>Prénom:</strong> <?php echo htmlspecialchars($etudiant['prenom'] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
  <li><strong>Email:</strong> <?php echo htmlspecialchars($etudiant['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
  <li><strong>Filière:</strong>
    <?php echo htmlspecialchars(($etudiant['filiere_code'] ?? '').' — '.($etudiant['filiere_libelle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
  </li>
</ul>

<p>
  <a href="/etudiants">Retour liste</a>
  |
  <a href="/etudiants/<?php echo $id; ?>/edit">Éditer</a>
</p>

<form action="/etudiants/<?php echo $id; ?>/delete"
      method="post"
      onsubmit="return confirm('Supprimer ?');"
      style="display:inline">
  <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
  <button type="submit" class="secondary">Supprimer</button>
</form>