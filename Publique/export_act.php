<?php
/** page dappel su script export, provenance xml (xsl)
 */
include("../Modele/Utile.php");

$xml=simplexml_load_file("../Xml/definitions.xml");
$csv="../Xml/export.csv";
$array=array();

$result=Utile::xml_to_array($xml,$array);

$test=OS;//Utile::winOuLin();
($test=="OUI")?$finDeLigne="\r\n":$finDeLigne="\n";// capable ou pas de passer en variable ?
$separateur=";";
$format=['findeligne'=>"$finDeLigne",'separateur'=>"$separateur"];

$result=Utile::array_format_csv($result,$format);

//print_r($result);
Utile::array_formatCsv_versFichierCsv($result,$csv);

?>
<p>Recuperer le fichier csv <a href="<?php echo $csv; ?>">ici</a></p>
<p>Revenir au <a href="../Xml/definitions.xml">glossaire</a></p>