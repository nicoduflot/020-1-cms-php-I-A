<div class="col-md-6 col-lg-4 my-3">
    <article>
        <header>
            <h2 class="text-truncate"><?php echo $data['titre'] ?></h2>
        </header>
        <div class="overflow-y-hidden" style="height: 150px;">
            <?php echo $data['body']; ?>
        </div>
        <footer>
            <a href="./?a=article&id=<?php echo $data['id'] ?>"><button class="btn btn-outline-secondary btn-sm">Lire la suite</button></a>
        </footer>
    </article>
</div>