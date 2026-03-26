<?php
// Sécurisation des paramètres de session
ini_set('session.cookie_httponly', 1);  // inaccessible au JS
//ini_set('session.cookie_secure', 1);    // HTTPS uniquement (à activer en prod)
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