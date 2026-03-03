<?php
/** @var array $errors */
/** @var array $old */
/** @var array $filieres */

$id = (int)($old['id'] ?? 0);
?>
<h2>Modifier un étudiant</h2>

<?php if (!empty($errors['global'])): ?>
  <p class="error"><?php echo htmlspecialchars($errors['global'], ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<form method="post" action="/etudiants/<?php echo $id; ?>/update">
  <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

  <div>
    <label>CNE</label><br>
    <input name="cne" required value="<?php echo htmlspecialchars($old['cne'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <?php if (!empty($errors['cne'])): ?><small class="error"><?php echo htmlspecialchars($errors['cne'], ENT_QUOTES, 'UTF-8'); ?></small><?php endif; ?>
  </div>

  <div>
    <label>Nom</label><br>
    <input name="nom" required value="<?php echo htmlspecialchars($old['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <?php if (!empty($errors['nom'])): ?><small class="error"><?php echo htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8'); ?></small><?php endif; ?>
  </div>

  <div>
    <label>Prénom</label><br>
    <input name="prenom" required value="<?php echo htmlspecialchars($old['prenom'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <?php if (!empty($errors['prenom'])): ?><small class="error"><?php echo htmlspecialchars($errors['prenom'], ENT_QUOTES, 'UTF-8'); ?></small><?php endif; ?>
  </div>

  <div>
    <label>Email</label><br>
    <input name="email" type="email" required value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <?php if (!empty($errors['email'])): ?><small class="error"><?php echo htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8'); ?></small><?php endif; ?>
  </div>

  <div>
    <label>Filière</label><br>
    <select name="filiere_id" required>
      <option value="0">-- Choisir --</option>
      <?php foreach ($filieres as $f): ?>
        <option value="<?php echo (int)$f['id']; ?>"
          <?php echo ((int)($old['filiere_id'] ?? 0) === (int)$f['id']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars(($f['code'] ?? '').' — '.($f['libelle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['filiere_id'])): ?><small class="error"><?php echo htmlspecialchars($errors['filiere_id'], ENT_QUOTES, 'UTF-8'); ?></small><?php endif; ?>
  </div>

  <br>
  <button type="submit">Mettre à jour</button>
  <a href="/etudiants">Retour</a>
</form>

<hr>

<form method="post" action="/etudiants/<?php echo $id; ?>/delete" onsubmit="return confirm('Supprimer ?');">
  <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
  <button type="submit" class="secondary">Supprimer</button>
</form>