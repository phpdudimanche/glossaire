<?php
/** page dappel su script export, provenance xml (xsl)
 */
include("../Modele/Utile.php");

$xml=simplexml_load_file("../Xml/definitions.xml");
$csv="../Xml/export.csv";
$array=array();

$result=Utile::xml_to_array($xml,$array);
$result=Utile::array_format_csv($result);

//print_r($result);
Utile::array_formatCsv_versFichierCsv($result,$csv);

?>
<p>Recuperer le fichier csv <a href="<?php echo $csv; ?>">ici</a></p>
<p>Revenir au <a href="../Xml/definitions.xml">glossaire</a></p>