<nav>
    <ul class="nav text-white" data-bs-theme="dark">
        <li class="nav-item"><a class="nav-link" href="../" title="Page d'accueil du site"><i class="bi bi-house-door-fill"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="./" title="Page d'accueil de l'admin"><i class="bi bi-speedometer"></i></a></li>
        <?php
        if (isset($_SESSION['user_id'])) {
            if (isset($_SESSION['user_id']) && in_array($_SESSION['role'], ['admin'])) {
        ?>
                <li class="nav-item"><a class="nav-link" href="./users.php" title="Gérer les utilisateurs"><i class="bi bi-person-circle"></i></a></li>
        <?php
            }
        }
        ?>
        <li class="nav-item"><a class="nav-link" href="./posts.php" title="Gérer les posts"><i class="bi bi-journal-richtext"></i></a></li>
    </ul>
</nav>