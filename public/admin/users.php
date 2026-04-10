<?php
require_once '../../includes/init.php';
/* webmaster ne passe pas*/
verifierRole(['admin']);
$page = 'Users';
include '../../includes/templates/backoffice/layout/page.php';
?>