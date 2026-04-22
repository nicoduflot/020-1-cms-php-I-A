<?php
function getIndex($option = null){
    getAllPosts();
    return true;
}

function getAllPosts(){
    $link = openConn();
    $stmt = $link->prepare(RQALLPOSTS);
    $stmt->execute();
    $result = $stmt->get_result();
    $nbRows = mysqli_stmt_affected_rows($stmt);
    if($nbRows > 0){
        ?>
        <div class="row">
            <?php
            while($data = $result->fetch_assoc()){
                include '../includes/templates/frontoffice/front-article.php';
            }
            ?>
        </div>
        <?php
    }else{

    }
    closeConn($link);
}

function getArticle($id, $type){
    $link = openConn();
    $stmt = $link->prepare(RQPOST);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $nbRows = mysqli_stmt_affected_rows($stmt);
    if($nbRows > 0){
        ?>
        <div class="row">
            <?php
            while($data = $result->fetch_assoc()){
                if('read' === $type){
                    include '../includes/templates/frontoffice/article.php';
                }else{
                    return $data;
                }
            }
            ?>
        </div>
        <?php
    }else{

    }
    
    closeConn($link);
}

function addArticle($utilisateur_id, $titre, $slug, $body, $publie = 1, $tags = [], $choix = []){
    $link = openConn();

    $stmt = $link->prepare(PUTPOST);
    $stmt->bind_param("isssi",$utilisateur_id, $titre, $slug, $body, $publie);

    $stmt->execute();
    $newId = $link->insert_id; // récupère l'id du post créé

    closeConn($link);

    traiterTags((int)$newId, $tags, $choix);

    return $newId;             // utile pour rediriger vers le post après création
}

function modArticle($titre, $slug, $body, $publie,  $id, $tags = [], $choix = []): bool
{
    $link = openConn();

    $stmt = $link->prepare(MODPOST);
    $stmt->bind_param("sssii", $titre, $slug, $body, $publie, $id);
    $stmt->execute();

    closeConn($link);

    supprimerTagsDuPost((int)$id);

    traiterTags((int)$id, $tags, $choix);

    return true;
}

function delArticle($id): bool
{
    $link = openConn();
    $stmt = $link->prepare(DELPOST);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    closeConn($link);
    return true;
}

function verifierConflitsEtiquettes(array $tagsNormalises): array {
    $link = openConn();
    $conflits = [];

    foreach($tagsNormalises as $tag){
        $stmt = $link->prepare("
            SELECT id, nom FROM tag
            WHERE LOWER(nom) LIKE ?
            AND nom != ?
        ");
        $recherche = '%' . $tag . '%';
        $stmt->bind_param("ss", $recherche, $tag);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()){
            $conflits[$tag][] = $row; // étiquettes similaires trouvées
        }
    }

    closeConn($link);
    return $conflits; // vide = pas de conflit
}

function supprimerTagsDuPost(int $postId): bool
{
    $link = openConn();
    $stmt = $link->prepare("DELETE FROM post_tag WHERE post_id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $nbSupprimes = $stmt->affected_rows; // debug : combien de lignes supprimées
    closeConn($link);
    return $nbSupprimes;
}

function traiterTags(int $postId, array $tags, array $choix): void
{
    $link = openConn();

    foreach($tags as $tag){

        if(isset($choix[$tag]) && $choix[$tag] !== 'garder'){
            $tagId = (int)$choix[$tag];
        } else {
            $stmt = $link->prepare("SELECT id FROM tag WHERE nom = ?");
            $stmt->bind_param("s", $tag);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            
            if($row){
                $tagId = (int)$row['id'];
            } else {
                $stmt2 = $link->prepare("INSERT INTO tag (nom) VALUES (?)");
                $stmt2->bind_param("s", $tag);
                $stmt2->execute();
                $tagId = $link->insert_id;
            }
        }

        $stmt3 = $link->prepare("
            INSERT IGNORE INTO post_tag (post_id, tag_id) VALUES (?, ?)
        ");
        $stmt3->bind_param("ii", $postId, $tagId);
        $result = $stmt3->execute();
    }

    closeConn($link);
}

function getTagsDuPostString(int $postId): string
{
    $link = openConn();
    $stmt = $link->prepare("
        SELECT t.nom FROM tag t
        JOIN post_tag pt ON pt.tag_id = t.id
        WHERE pt.post_id = ?
        ORDER BY t.nom
    ");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $tags = [];
    while($row = $result->fetch_assoc()){
        $tags[] = $row['nom'];
    }
    closeConn($link);
    return implode(', ', $tags);
}

function getAllTags(): array
{
    $link = openConn();
    $stmt = $link->prepare("SELECT id, nom FROM tag ORDER BY nom");
    $stmt->execute();
    $result = $stmt->get_result();
    $tags = [];
    while($row = $result->fetch_assoc()){
        $tags[] = $row;
    }
    closeConn($link);
    return $tags;
}