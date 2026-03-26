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
    if(!$row || !password_verify($password, $row['motdepasse'])){
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


