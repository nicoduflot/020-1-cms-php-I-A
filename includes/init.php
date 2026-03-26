<?php
// Chemins
define('ROOT',     dirname(__DIR__));
define('CONFIG',   ROOT . '/config/');
define('INCLUDES', ROOT . '/includes/');
// Config et fonctions
require_once CONFIG   . 'session.php';
require_once CONFIG   . 'config.php';
require_once INCLUDES . 'db.php';
require_once INCLUDES . 'fonctions.php';
require_once INCLUDES . 'cms.php';