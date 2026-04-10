<?php
require_once '../../includes/init.php';
/* bloque et redirige si non autorisé*/
verifierRole(['admin', 'webmaster']);
$page = 'Dashboard';
include '../../includes/templates/backoffice/layout/page.php';
?>
