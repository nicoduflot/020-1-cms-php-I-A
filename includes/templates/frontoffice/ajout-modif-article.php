<?php
$idArticle = null;
$titre = '';
$slug = '';
$body = '';
$type = '';
if (isset($_GET['a']) && 'ajout-article' === $_GET['a']) {
    /* on crée un article */
    $type = 'add';
}

if ((isset($_GET['a']) && 'modif-article' === $_GET['a']) && (isset($_GET['id']) && '' !== $_GET['id'])) {
    /* on modifie un article */
    $type = 'mod';
    $idArticle = (int)$_GET['id'];
    $data = getArticle($idArticle, 'mod');
    $titre = $data['titre'];
    $slug = makeSlug($data['titre']);
    $body = $data['body'];;
    $tagsStr   = getTagsDuPostString((int)$idArticle);
}

if (isset($_POST['article']) && $_POST['article'] === 'add') {
    $utilisateur_id = $_SESSION['user_id'];
    $titre          = $_POST['titre'];
    $slug           = $slug = makeUniqueSlug(makeSlug($titre));
    $body           = $_POST['ckcontent'];
    $tagsNormalises = decouperEtiquettes($_POST['tags'] ?? '');
    $conflits       = verifierConflitsEtiquettes($tagsNormalises);

    if (!empty($conflits)) {
        $_SESSION['pending_article'] = [
            'type'   => 'add',
            'titre'  => $titre,
            'body'   => $body,
            'publie' => 1,
            'tags'   => $tagsNormalises,
        ];
        $_SESSION['conflits_tags'] = $conflits;
        // pas de redirection, le formulaire de conflits s'affiche plus bas
    } else {
        $newId = addArticle($utilisateur_id, $titre, $slug, $body, 1, $tagsNormalises, []);
        header('Location: /?a=article&id=' . $newId);
        exit;
    }
}

if (isset($_POST['article']) && $_POST['article'] === 'mod') {
    $utilisateur_id = $_SESSION['user_id'];
    $id             = (int)$_GET['id'];
    $titre          = $_POST['titre'];
    $slug           = $slug = makeUniqueSlug(makeSlug($titre), (int)$id);
    $body           = $_POST['ckcontent'];
    $tagsNormalises = decouperEtiquettes($_POST['tags'] ?? '');
    $conflits       = verifierConflitsEtiquettes($tagsNormalises);

    if (!empty($conflits)) {
        $_SESSION['pending_article'] = [
            'type'   => 'mod',
            'id'     => $id,
            'titre'  => $titre,
            'body'   => $body,
            'publie' => 1,
            'tags'   => $tagsNormalises,
        ];
        $_SESSION['conflits_tags'] = $conflits;
    } else {
        modArticle($titre, $slug, $body, 1, $id, $tagsNormalises, []);
        header('Location: /?a=article&id=' . $id);
        exit;
    }
}

// Résolution des conflits (add et mod)
if (isset($_POST['article']) && $_POST['article'] === 'resolve') {
    $pending = $_SESSION['pending_article'];
    $choix   = $_POST['choix_tags'] ?? [];

    if ($pending['type'] === 'add') {
        $newId = addArticle(
            $_SESSION['user_id'],
            $pending['titre'],
            makeSlug($pending['titre']),
            $pending['body'],
            $pending['publie'],
            $pending['tags'],
            $choix
        );
        unset($_SESSION['pending_article'], $_SESSION['conflits_tags']);
        header('Location: /?a=article&id=' . $newId);
        exit;
    }

    if ($pending['type'] === 'mod') {
        modArticle(
            $pending['titre'],
            makeSlug($pending['titre']),
            $pending['body'],
            $pending['publie'],
            $pending['id'],
            $pending['tags'],
            $choix
        );
        unset($_SESSION['pending_article'], $_SESSION['conflits_tags']);
        header('Location: /?a=article&id=' . $pending['id']);
        exit;
    }
}

// Affichage du formulaire de conflits si nécessaire
if (!empty($_SESSION['conflits_tags'])) { ?>
    <div class="alert alert-warning">
        <h4>Des étiquettes similaires existent déjà</h4>
        <form method="POST">
            <input type="hidden" name="article" value="resolve" />
            <?php foreach ($_SESSION['conflits_tags'] as $nouveau => $existantes): ?>
                <fieldset class="mb-3 border p-3">
                    <legend>Étiquette saisie : <strong><?= htmlspecialchars($nouveau) ?></strong></legend>
                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                            name="choix_tags[<?= htmlspecialchars($nouveau) ?>]"
                            value="garder"
                            id="garder_<?= htmlspecialchars($nouveau) ?>" checked />
                        <label class="form-check-label" for="garder_<?= htmlspecialchars($nouveau) ?>">
                            Ajouter "<?= htmlspecialchars($nouveau) ?>" comme nouvelle étiquette
                        </label>
                    </div>
                    <?php foreach ($existantes as $existante): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                name="choix_tags[<?= htmlspecialchars($nouveau) ?>]"
                                value="<?= $existante['id'] ?>"
                                id="exist_<?= $existante['id'] ?>" />
                            <label class="form-check-label" for="exist_<?= $existante['id'] ?>">
                                Utiliser "<?= htmlspecialchars($existante['nom']) ?>"
                            </label>
                        </div>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Valider les choix</button>
        </form>
    </div>
<?php
} elseif (!isset($_POST['article'])) {
?>
    <form method="post" id="form-article" class="row">
        <div class="col-md-8">
            <div>
                <input type="hidden" name="ckcontent" id="ckcontent" value="<?= htmlspecialchars($body) ?>" />
            </div>
            <input type="hidden" name="article" id="article" value="<?= $type ?>" />
            <p>
                <label class="form-label" for="titre">Titre</label>
                <input class="form-control" type="text" name="titre" id="titre" value="<?= $titre ?>" placeholder="Titre" />
            </p>
            <div id="editor">

            </div>
            <div>
                <label class="form-label" for="tags">Étiquettes</label>
                <input class="form-control" type="text" name="tags" id="tags"
                    value="<?= htmlspecialchars($tagsStr ?? '') ?>"
                    placeholder="steampunk, jdr, victorien" />
                <small class="form-text text-muted">
                    Séparez les étiquettes par des virgules, en minuscules.
                </small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Étiquettes existantes</div>
                <div class="card-body">
                    <?php foreach (getAllTags() as $tag): ?>
                        <span class="badge bg-secondary me-1 mb-1">
                            <?= htmlspecialchars($tag['nom']) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-8 offset-md-2 my-2">
            <p>
                <button class="btn btn-outline-success btn-small" type="submit" id="valid">
                    <?php
                    echo ($type === 'add') ? 'Ajouter' : 'Modifier';
                    ?>
                </button>
            </p>
        </div>
    </form>
<?php
}
?>