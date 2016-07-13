<?php
// fichier de bootstrap
require_once(__DIR__ ."/Modele/Utile.php");
$os=Utile::winOuLin();// PHPUNIT cli ne connait pas ces var, donc constante dans phpunit.xml
define ("OS",$os);
//--- utilise par : import_act.php
		$separateur =";";
		$saut_de_ligne="\r\n";
		$nbre_colonnes=2;
		$entete="on";// off 
		$debug="off";
	$balise=array();//--- global pour array_to_xml
?>