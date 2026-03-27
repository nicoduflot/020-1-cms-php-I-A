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
                include '../includes/templates/front-article.php';
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
                    include '../includes/templates/article.php';
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

function addArticle($utilisateur_id, $titre, $slug, $body, $publie = 1){
    $link = openConn();

    $stmt = $link->prepare(PUTPOST);
    $stmt->bind_param("isssi",$utilisateur_id, $titre, $slug, $body, $publie);
    $stmt->execute();
    $newId = $link->insert_id; // récupère l'id du post créé

    closeConn($link);
    return $newId;             // utile pour rediriger vers le post après création
}

function modArticle($titre, $slug, $body, $publie, $id): bool
{
    $link = openConn();

    $stmt = $link->prepare(MODPOST);
    $stmt->bind_param("sssii", $titre, $slug, $body, $publie, $id);
    $stmt->execute();

    closeConn($link);
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