<?php

function prePrint($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function getUser($login, $password): bool
{
    $link = openConn();
    $stmt = $link->prepare("
        SELECT 
            u.id, u.nom, u.prenom, u.motdepasse, r.nom AS role 
        FROM 
            utilisateur u 
        JOIN role r ON r.id = u.role_id
        WHERE 
            u.login = ?
    ");
    $stmt->bind_param("s", $login);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();
    if (!$row || !password_verify($password, $row['motdepasse'])) {
        return false;
    }

    $_SESSION['user_id']  = $row['id'];
    $_SESSION['nom']      = $row['nom'];
    $_SESSION['prenom']   = $row['prenom'];
    $_SESSION['role']     = $row['role'];

    // Regénérer l'ID de session pour éviter la fixation de session
    session_regenerate_id(true);

    return true;
}

function deconnecterUtilisateur(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function makeSlug($text)
{
    /* convertir en minuscule */
    $text = strtolower($text);

    /* remplacer les caractères accentués */
    $text = preg_replace(
        array('/&.*;/', '/\W/'),
        '-',
        preg_replace(
            '/&([A-Za-z]{1,2})(grave|acute|circ|cedil|uml|lig);/',
            '$1',
            htmlentities($text, ENT_NOQUOTES, 'UTF-8')
        )
    );

    /* tirets multiples */
    $text = preg_replace('/-+/', '-', $text);

    /* retirer les tirets en début et fin de chaîne */
    $text = trim($text, '-');

    return $text;
}

function verifierRole(array $rolesAutorises): void {
    if(!isset($_SESSION['user_id']) || 
       !in_array($_SESSION['role'], $rolesAutorises)){
        header('Location: /');
        exit;
    }
}

function normaliserEtiquette(string $tag): string {
    $tag = strtolower(trim($tag));
    // Supprimer les accents
    $tag = transliterator_transliterate('Any-Latin; Latin-ASCII', $tag);
    // Ne garder que lettres, chiffres et tirets
    $tag = preg_replace('/[^a-z0-9\-]/', '-', $tag);
    $tag = preg_replace('/-+/', '-', $tag); // tirets multiples
    return trim($tag, '-');
}

function decouperEtiquettes(string $saisie): array {
    $tags = explode(',', $saisie);
    $result = [];
    foreach($tags as $tag){
        $tag = normaliserEtiquette($tag);
        if($tag !== ''){
            $result[] = $tag;
        }
    }
    return array_unique($result);
}

