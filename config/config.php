<?php

/*Constantes de connexion à la bdd*/
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', '20-1-cms-php-i-a');
define('DBPORT', '3306');

// Sécurisation des paramètres de session
ini_set('session.cookie_httponly', 1);  // inaccessible au JS
ini_set('session.cookie_secure', 1);    // HTTPS uniquement (à activer en prod)
ini_set('session.cookie_samesite', 'Strict'); // protection CSRF

session_set_cookie_params([
    'lifetime' => 0,            // cookie de session (détruit à la fermeture du navigateur)
    'path'     => '/',
    'domain'   => 'cms-demo.local', // le point devant est inutile depuis PHP 7.3
    'secure'   => false,        // passer à true en HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();