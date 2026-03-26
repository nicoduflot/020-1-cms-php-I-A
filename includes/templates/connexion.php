<?php
$formStatus = 0;
if ((isset($_POST['login'])) && (isset($_POST['password']))) {
    $user = getUser($_POST['login'], $_POST['password']);
    if (!$user) {
        $formStatus = 2;
    } else {
        $formStatus = 1;
        $redirect = '/'; // par défaut
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']); // on nettoie
        }
        header('Location: ' . $redirect);
        exit; // ← toujours, sans exception
    }
}
?>
<?php
if ($formStatus === 2) {
?>
    <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
        <strong>Ventredieu !</strong> Une erreur de correspondance entre login et mot de passe semble avoir eu lieu.<br />
        Veuillez retenter la connexion.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-md-6 col-lg-4 offset-md-3 offset-lg-4">
        <form method="post">
            <p>
                <label class="form-label" for="login">Login : </label>
                <input class="form-control" type="text" name="login" id="login" required placeholder="login" />
            </p>
            <p>
                <label class="form-label" for="password">Mot de passe : </label>
                <input class="form-control" type="password" name="password" id="password" required placeholder="mot de passe" />
            </p>
            <p>
                <button class="btn btn-outline-success btn-sm" type="submit">Connexion</button>
                <button class="btn btn-outline-warning btn-sm" type="reset">Effacer tout</button>
            </p>
        </form>
    </div>
</div>