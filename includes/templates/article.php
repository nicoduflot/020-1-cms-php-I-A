<div>
    <article>
        <header>
            <h2 class=""><?php echo $data['titre'] ?></h2>
        </header>
        <div class="">
            <?php echo $data['body']; ?>
        </div>
        <footer class="row">
            <div class="col">
                <a href="./"><button class="btn btn-outline-secondary btn-sm">Revenir à l'accueil</button></a>
            </div>
            <?php
            if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'webmaster'])) {
            } else {
            ?>
                <div class="col">
                    <a href="./?a=modif-article&article=mod&id=<?php echo $data['id'] ?>"><button class="btn btn-outline-primary btn-sm">Modifier l'article</button></a>
                </div>
                <div class="col">
                    <a href="./?a=del-article&article=del&id=<?php echo $data['id'] ?>"><button class="btn btn-outline-danger btn-sm">Supprimer l'article</button></a>
                </div>
            <?php
            }
            ?>
        </footer>
    </article>
</div>