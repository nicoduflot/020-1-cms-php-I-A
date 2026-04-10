<?php
if ((isset($_GET['a']) && $_GET['a'] === 'del-article') && isset($_GET['id']) && !isset($_POST['article'])) {
    $idArticle = $_GET['id'];
    $data = getArticle($idArticle, 'mod');
    $type = 'del';
    ?>
    <div class="row">
        <form method="post" class="col-lg-6 offset-lg-3">
            <input type="hidden" name="article" id="article" value="<?= $type ?>" />
            <input type="hidden" name="id" id="id" value="<?= $idArticle ?>" />
            <p>
                <i>Êtes-vous sûr de vouloir supprimer l'article <b><?php echo $data['titre'] ?></b></i>
            </p>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-outline-primary btn-sm">Valider</button>
                </div>
                <div class="col">
                    <a href="./?a=article&id=<?php echo $idArticle ?>"><button type="button" class="btn btn-outline-warning btn-sm">Annuler</button></a>
                </div>
            </div>
        </form>
    </div>
    <?php
}
if(isset($_POST['id']) &&  $_POST['id'] !== ''){
    delArticle($_POST['id']);
    header('Location: /');
    exit;
}
?>
