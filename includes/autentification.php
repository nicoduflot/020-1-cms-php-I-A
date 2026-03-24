<?php

function sessionUtilisateur($identifiant){

}

function connexion($conn, $identifiant, $motdepasse){
    $identifiant = addslashes($identifiant);
    /* c'est du MYSQLI !!!!! */
    $sql = "SELECT * FROM `user` `u` where `u`.`login` = '".$identifiant."' ";

    $result = mysqli_query($conn, $sql);

    $count =  mysqli_num_rows($result);
    if($count == 1){

            $row = mysqli_fetch_assoc($result);
            $userHashedPassword = $row['motdepasse'];
            if(password_verify($motdepasse, $userHashedPassword)){
                setUserCookies($row['login'], $row['role']);
                //prePrint('OK');
            }else{
                //prePrint('PAS OK');
            }
        }else{
            return false;
        }
        
}


function checkUserConnexion($conn, $identifiant){
    $identifiant = addslashes($identifiant);
    $sql = "SELECT * FROM `user` `u` where `u`.`login` = '".$identifiant."' ";
    $result = mysqli_query($conn, $sql);
    //prePrint($result);
    $count =  mysqli_num_rows($result);

    if($count == 1){
        $row = mysqli_fetch_assoc($result);
        //prePrint('OK');
    }else{
        //prePrint('PAS OK');
    }
}


function setUserCookies($login, $role){
    /* création des cookies et des variables de sessions pour garder l'utilisateur en mémoire */
    setCookie("userConnected", true, time() + 3600*24*30*12, "/");
    
    setCookie("userLogin", $login, time() + 3600*24*30*12, "/" );
    setCookie("userRole", $role, time() + 3600*24*30*12, "/" );
    
}