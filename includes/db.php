<?php
/**
 * 
 * Script pour : 
 * - le paramétrage de connexion à la bdd
 * - les fonction d'ouverture et de fermeture de la connexion
 * 
 */


/*Constante requêtes sql */ 
$allPosts = "
SELECT
    p.id, 
    p.titre, 
    p.body, 
    p.publie, p.cree, p.modifie,
    u.nom          AS auteur_nom,
    u.prenom       AS auteur_prenom,
    u.id           AS auteur_id,
    GROUP_CONCAT(t.id   ORDER BY t.nom SEPARATOR ';') AS tags_ids,
    GROUP_CONCAT(t.nom  ORDER BY t.nom SEPARATOR ';') AS tags_noms

FROM post p

JOIN utilisateur u
    ON u.id = p.utilisateur_id

LEFT JOIN post_tag pt
    ON pt.post_id = p.id

LEFT JOIN tag t
    ON t.id = pt.tag_id

GROUP BY
    p.id,
    u.id 
ORDER BY 
    p.cree DESC; 
";
$article = "
SELECT
    p.id, 
    p.titre, 
    p.body, 
    p.publie, p.cree, p.modifie,
    u.nom          AS auteur_nom,
    u.prenom       AS auteur_prenom,
    u.id           AS auteur_id,
    GROUP_CONCAT(t.id   ORDER BY t.nom SEPARATOR ';') AS tags_ids,
    GROUP_CONCAT(t.nom  ORDER BY t.nom SEPARATOR ';') AS tags_noms

FROM post p

JOIN utilisateur u
    ON u.id = p.utilisateur_id

LEFT JOIN post_tag pt
    ON pt.post_id = p.id

LEFT JOIN tag t
    ON t.id = pt.tag_id
WHERE p.id = ? 
GROUP BY
    p.id,
    u.id;
";

$addArticle ="
INSERT INTO `post` 
(`utilisateur_id`, `titre`, `slug`, `body`, `publie`) 
VALUES 
(?, ?, ?, ?, ?);
";

define("RQALLPOSTS", $allPosts);
define("RQPOST", $article);
define("PUTPOST", $addArticle);

/*fontction de connexion à la bdd*/
function openConn(){
    // la fonction php mysqli_connect() a besoin des informations de connexion pour créer un objet qui servira à toutes les manipulations bdd
    /*$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME, DBPORT);*/
    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
    /*
    var_dump($conn);
    mysqli_close($conn);
    */
    if( !$conn ){
        echo 'Erreur de connexion : <br />' . mysqli_connect_error() . '<br />';
    }else{
        //echo 'Connexion bdd : ' . DBNAME . '<br />';
    }

    // ouverture de connexion en utilisant PDO (Php Data Object)
    /*
    $conn = new PDO('mysql:host='.DBHOST.';dbname:'.DBNAME, DBUSER, DBPASS);
    */

    return $conn;
}

// fermeture de la connexion 
function closeConn($conn){
    // autres traitement avant la fermeture de la connexion
    /**
     * 
     * 
     * 
     */

     // fermeture de la connexion
    mysqli_close($conn);
}