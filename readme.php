<?php
include 'vendor\erusev\parsedown\Parsedown.php';

ob_start();
include 'readme.md';
$parse = ob_get_clean();

$Parsedown = new Parsedown();
echo $Parsedown->text($parse);
?>