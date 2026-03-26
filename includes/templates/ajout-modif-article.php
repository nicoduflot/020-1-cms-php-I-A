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
    $idArticle = $_GET['id'];
    $data = getArticle($idArticle, 'mod');
    $titre = $data['titre'];
    $slug = makeSlug($data['titre']);
    $body = $data['body'];
}

if(isset($_POST['article']) && $_POST['article'] === 'add'){
    $utilisateur_id = $_SESSION['user_id'];
    $titre = $_POST['titre'];
    $slug = makeSlug($titre);
    $body = $_POST['ckcontent'];
    /*
    ?>
    <div>
        utilisateur_id : <?=  $_SESSION['user_id'] ?><br />
        titre : <?=  $titre ?><br />
        slug : <?=  $slug ?><br />
        body : <br />
        <?=  $body ?>
    </div>
    <?php
    */
    $newId = addArticle($utilisateur_id, $titre, $slug, $body, 1);
    header('Location: /?a=article&id=' . $newId);
    exit;
}
if(!isset($_POST['article'])){
?>
<form method="post" id="form-article">
    <div class="col-md-8 offset-md-2">
        <input type="hidden" name="article" id="article" value="<?= $type ?>" />
        <input type="hidden" name="ckcontent" id="ckcontent" value="<?=  $body ?>" />
        <p>
            <label class="form-label" for="titre">Titre</label>
            <input class="form-control" type="text" name="titre" id="titre" value="<?= $titre ?>" placeholder="Titre" />
        </p>
    </div>
    <div class="col-md-8 offset-md-2">
        <div id="editor">

        </div>
    </div>
    <div class="col-md-8 offset-md-2 my-2">
        <p>
            <button class="btn btn-outline-success btn-small" type="submit" id="valid">
                <?php
                echo ($type === 'add') ? 'Ajouter' : 'Modifier' ;
                ?>
            </button>
        </p>
    </div>
</form>
<?php
}
?>
