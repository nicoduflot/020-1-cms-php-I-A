<?php
session_start();
require_once './includes/sql.php';
require_once './includes/functions.php';
require_once './includes/cms.php';
require_once './includes/autentification.php';

$conn = openConn();

//checkUserConnexion($conn, 'nikko');

//connexion($conn, 'nikko', 'administrateur');

$a = null;
if(isset($_GET['a'])){
    $a = $_GET['a'];
}

//prePrint($_COOKIE);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS</title>
    <link rel="stylesheet" href="./css/bootstrap.css" />
    <script src="./js/bootstrap.bundle.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><h1>Mon CMS</h1></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./">Accueil</a>
                    </li>
                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </a>
                        <ul class="dropdown-menu">
                            <!---
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            -->
                            <li><a class="dropdown-item" href="./?a=connexion">connexion</a></li>
                        </ul>
                    </li>
                    <!--
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                    -->
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <main class="container my-3">
        <section>
            <?php
            switch($a){
                case 'connexion':
                    include './includes/templates/connexion.php';
                break;
                case 'article':
                    getArticle($_GET['id']);
                break;
                default :
                    getIndex();
            }
            ?>
        </section>
    </main>
    <footer class="bg-dark text-white" data-bs-theme="dark">
        <div class="container">
            &copy; DAWAN - 2026
        </div>
    </footer>
</body>

</html>