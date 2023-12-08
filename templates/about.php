<?php
$pageTitle = 'About Us';
ob_start();

$content = ob_get_clean();
include 'layout.php';
?>