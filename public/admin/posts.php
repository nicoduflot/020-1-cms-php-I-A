<?php
require_once '../../includes/init.php';
/* bloque et redirige si non autorisé*/
verifierRole(['admin', 'webmaster']);
$page = 'Posts';
include '../../includes/templates/backoffice/layout/page.php';
?>