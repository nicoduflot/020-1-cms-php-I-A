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
                /*prePrint($data);*/
                include '../includes/templates/front-article.php';
            }
            ?>
        </div>
        <?php
    }else{

    }
}

function getArticle($id){
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
                /*prePrint($data);*/
                include '../includes/templates/article.php';
            }
            ?>
        </div>
        <?php
    }else{

    }
}