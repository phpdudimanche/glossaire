<?php
/** traiter le fichier telecharge : 
 * SI existe et taille et type OK telecharge et renomme SINON affiche erreur et lien vers formulaire
 */
//header("Content-Type: text/html; charset=UTF-8");
include("../Modele/Utile.php");
 
$traite=$_FILES;

$path=PATH."/Xml/";//@todo detection auto avec dirname (ou config : sur qualite : ou parametrage de tout) D:/www/test/Admin/
$nouveau_fichier="import.csv";// renommer
$fichier =$path.$nouveau_fichier;
$fichier_xml='../Xml/definitions.xml';
$page="../Xml/definitions.xml";// redirection

$ctrl=array('nom'=>"fichier-csv",'poids'=>2000,'type'=>"application/vnd.ms-excel");

$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><?xml-stylesheet type=\"text/css\" href=\"defintions.css\"?><?xml-stylesheet type=\"text/xsl\" href=\"definitions.xsl\"?><definitions  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"definitions.xsd\"></definitions>");
$result=Utile::controler($traite,$ctrl);

if($result==FALSE){
	move_uploaded_file($traite["$ctrl[nom]"]["tmp_name"], $path.$nouveau_fichier);
		$result=Utile::csv_to_array($fichier,$separateur,$saut_de_ligne,$nbre_colonnes,$entete,$debug);	
		$result=Utile::liste_des_lettres($result,'terme',2);
		Utile::array_to_xml($result,$xml);
		Utile::xml_avec_indentation($xml,$fichier_xml);		
	    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $page . '">';
}
else{
	echo "Le fichier doit etre un csv de moins de 2 MO : <a href='import_form.php'>a telecharger ici </a>";
}



?>