<?php
require_once("../config.php");// seul chemin relatif a connaitre : depend dou est appele
//echo "<br />".PATH;

/** Toutes les fonctions utiles pour les tableaux associatifs et transformation xml, csv
 * @todo gerer les erreurs
 */	
class Utile{	

/** controler un upload
 * @param array $traite tableau $_FILES
 * @param array ctrl cibler : nom, verifier : type et poids
 * @return bool
 * @todo le cas passant ne passe pas 
 */
static function controler($traite,$ctrl){
	if  ((empty($traite)) OR (!array_key_exists("$ctrl[nom]",$traite)) OR ($traite["$ctrl[nom]"]['type']!="$ctrl[type]") OR ($traite["$ctrl[nom]"]['size']>"$ctrl[poids]")) // tourner a lenvers
	{
		return TRUE;// 1
	}
	else{
		return FALSE;// rien
	}
}

/** IMPORTER transformer un csv en array
 * @todo mettre param dans array associatif, nombre trop important BUG-TEST : detection ou pas
 * @return array ou debug BUG-TEST : mauvaise pratique
 * @param string $fichier fichier csv a traiter
 * @param string $separateur separateur entre champs
 * @param string $saut_de_ligne separateur de fin de ligne
 * @param string $nbre_colonnes nombre de colonnes attenddues et a controler
 * @param string $entete existence dentete(on) pour ignorer la premiere ligne ou sen servir de champ associatif
 * @param string $debug sortie de debug(on) pour avoir un dump et des commentaires
 */
static function csv_to_array($fichier,$separateur,$saut_de_ligne,$nbre_colonnes,$entete,$debug){
$i=0;
$debug_msg="";
$array=array();
$entete_force=array("terme","definition");// mettre en param
if (file_exists("$fichier"))// fonctionne en chemin relatif
{
	$debug_msg.="<br />le fichier existe";
	if (is_readable("$fichier"))// existence+lisible
	{
		$debug_msg.="<br />le fichier est lisible";
//-------------------------------------------		
		$fouvert = fopen("$fichier", "r");
		while (!feof($fouvert))
		{
			$ligne = fgets($fouvert,1000); // chaque ligne
			//$ligne=str_replace($saut_de_ligne,"",$ligne);// enlever saut de ligne : $saut_de_ligne = \r\n
			$tableau = explode($separateur,trim(utf8_encode($ligne)));// necessaire + encode pour accent 		
			if(sizeof($tableau)==$nbre_colonnes)
			{
				$debug_msg.="<br />la ligne $i a le bon nombre de colonnes";// " permet directement les $var, non pas '
				if($i==0 AND $entete=="on"){// BUG-TEST faire $entete="on" 0
					$debug_msg.="<br />le contenu ci dessous est l'entete :<br/>";
					$debug_msg.=print_r($tableau,TRUE);
					$tab_entete=$tableau;
					//$i--;
				}				
				elseif($i!=0 AND $entete=="on"){// $entete="on" 0
					for($a=0;$a<sizeof($tableau);$a++){// tableau associatif						
					$debug_msg.="<br /> associatif numero ".$a." avec nom num $a ".$tab_entete[$a]." : ";					
					// ANTI-PATTERN : ne pas inclure, mettre ailleurs, traiter avant				
					$taille=strlen($tab_entete[$a]);// 01 nombre de signe
					preg_match('/[a-zA-Z1-9]/', $tab_entete[$a], $regs);// 02 premier caractere
					$pos=strpos($tab_entete[$a],$regs[0]);// 03 position du premier caractere 
					$moins=$pos-$taille;// 04 position a reculons
					$tab_entete[$a]=substr($tab_entete[$a],$moins); // 05 recuperation		
						$array[$i][$tab_entete[$a]]=$tableau[$a];
					$debug_msg.=print_r($array[$i][$tab_entete[$a]],TRUE);
					}				
				}
				else{
					$debug_msg.="<br />Contenu non associatif : ";
					$debug_msg.=print_r($tableau,TRUE);
				}
				//---
			}
			else{
				$debug_msg.="<br />nombre de colonnes KO";				
			}
			$i++;
		}
		fclose($fouvert);		
		$debug_msg.="<br />Associatif final : <pre>";		
			if($entete=='on'){
				$array = array_values($array);// reindexer pour faire commencer a zero (ancien entete non retourne)
			}		
		$debug_msg.=print_r($array,TRUE);
		$debug_msg.="</pre>";		
//-------------------------------------------		
	}
	else{
		$debug_msg.="<br />lecture KO";
	}
}
else{
	$debug_msg.="<br />existence KO";
}
	if ($debug=="on"){
		$output=$debug_msg;
	}
	else{
		$output=$array;
	}
return $output;
}

/** liste des lettres pour le menu de type dictionnaire OU lettrine a la premiere apparition OU menu et lettrine
 * @param array $listeAtraiter tableau associatif a traiter
 * @param string $champAsssociatif champ dans lequel rechercher
 * @param int $option 0,1,2 = liste des lettres pour le menu de dictionnaire OU lettrine a la premiere apparition OU menu et lettrine
 * @return array tableau dependant de la "LISTE DES OPTIONS"
 */
static function liste_des_lettres($listeAtraiter,$champAsssociatif,$option=1){
	$lettres=array();
	$mixte=array();
	$error=array();
	$premiere_l_maj='';
	$taille=sizeof($listeAtraiter);

	for($i=0;$i<$taille;$i++){ 
		$premiere_l_maj=strtoupper(substr($listeAtraiter[$i][$champAsssociatif],0,1));//--- STANDARD
			if(!in_array($premiere_l_maj,$lettres)){			
				array_push($lettres,$premiere_l_maj);
					$listeAtraiter[$i] = array_merge(array('lettre' => $premiere_l_maj), $listeAtraiter[$i]);// en debut et associatif
			}
		}
		
		if ($option==0){// TEST des 3 options
			return $lettres;		
		}
		elseif($option==1){			
			return $listeAtraiter;
		}
		elseif($option==2){			
			$mixte['lettres']=$lettres;
			$mixte['liste']=$listeAtraiter;
			return $mixte;
		}
		else{
			$error['desc']="option non existante";
			return $error;
		}
	}

/** transforme un array en xml, et ajoute des balises filles lorsque non associatif, cad numeric : "_item"
 * @source 10 1 nom associatif multiple valeurs avec balise+_item
 * @param global $balise liste des balises repetees
 * @param array $data tableau associatif
 * @param xml $xml_data coquille du xml avec racine
 * @return xml xml complet avec le contenu, mais sans indentation
 */
static function array_to_xml( $data, &$xml_data ) {// TEST des 3 options XML
	
global $balise;// ajout
	
    foreach( $data as $key => $value ) {		
        if( is_array($value) ) {
            if( is_numeric($key) ){
					if(empty($balise)){
						array_push($balise,$xml_data->getName());// nom racine a premiere loop
					}
                $key = end($balise).'_item';// ajout a array non associatif multiple valeurs		
            }
//-------------
			else{
			array_push($balise,$key);	// ajout de la partie
			}
//--------------
            $subnode = $xml_data->addChild($key);
            self::array_to_xml($value, $subnode);
        } else {
				if( is_numeric($key) ){$key=end($balise).'_item';}// ajout a array non associatif multiple valeurs	
            $xml_data->addChild("$key",htmlspecialchars("$value"));			
        }
     }
}

/** indente un xml pour lecture facile
 * @param $xml xml sans indentation
 * @param $file destination et nom du xml indente
 * @return xml xml indente dans file
 */
static function xml_avec_indentation(&$xml,$file){ // TEST de l indentation espace ou comparaison

$dom = new DOMDocument("1.0");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$dom->loadXML($xml->asXML());
$dom->save($file);// emplacement, nom
}

/** EXPORTER transformer un xml en array
 * @todo rendre generique, fournir en tableau : pour l'entree les balises visees, pour la sortie les cles texte du tableau associatif
 * @param $sx flux simplexml
 * @param $new_items array cible
 * @return array tableau associatif simple
 */
static function xml_to_array($sx,$new_items){
// capture le xml en array associatif
array_push($new_items,array("terme"=>"terme","definition"=>"definition"));// entete -- option
foreach($sx->liste as $product){  // OK pour array
    foreach($product->liste_item as $item){
        array_push($new_items,array(
            "terme" => (string)$item->terme,         
            "definition" => (string)$item->definition        
        ));     
    }
	return $new_items;
}
}

/** formater un array en csv
 * @todo mettre en parametrage les echappements, separateur de champ, separateur de ligne
 * @param $new_items array tableau a traiter
 * @return tableau traite au format csv
 */
static function array_format_csv($new_items){
// prepare le formatage csv
array_walk($new_items,function($element,$key)use(&$new_items){
	$new_items[$key]=implode(";",$element)."\r\n";// aucun echappement V0 -- option $new_items[$key]="`".implode("`;`",$element)."`\r\n";
	// ajouter entete
});
return $new_items;
}

/** creer un csv depuis un array formate en type csv
 * @param $array array tableau formatte type csv
 * @param $fichier fichier csv a creer
 * @return cree un fichier csv
 */
static function array_formatCsv_versFichierCsv($new_items,$file){
// cree le fichier et pousse le flux
$fp = fopen($file, 'w');
foreach ($new_items as $fields) 
{
	fwrite($fp,$fields);
}
fclose($fp);
}

}
?>