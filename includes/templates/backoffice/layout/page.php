<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon CMS - DWWM - Backoffice - <?= $page  ?></title>
    <link rel="stylesheet" href="../css/bootstrap.css" />
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/dfd-script.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-icons.min.css" />
</head>

<body>
    <header class="bg-dark text-white" data-bs-theme="dark">
        <div class="container py-1">
            <h1>Mon CMS - <?= $page  ?></h1>
        </div>
        <div class="container">
            <?php
            include '../../includes/templates/backoffice/layout/navbar.php';
            ?>
        </div>
    </header>
    <div class="container">

    </div>
    <footer class="bg-dark text-white" data-bs-theme="dark">
        <div class="container py-1">
            &copy; DAWAN - TP DWWM
        </div>
    </footer>
</body>

</html>