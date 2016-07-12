<?php
// inclusion ici (pose pb en relatif) ou dans lanceur 
include("../Modele/Utile.php");// ../Modele/Utile.php
// execution 
class UtileTest extends PHPUnit_Framework_TestCase
{
	public function testControlerPassant(){
		// ARRANGE
		$ctrl=array('nom'=>"fichier-csv",'poids'=>2000,'type'=>"application/vnd.ms-excel");
		$traite=['fichier-csv'=>['type'=>'application/vnd.ms-excel','size'=>1000]];
		$expected=FALSE;
		// ACT
		$actual=Utile::controler($traite,$ctrl);
		// ASSERT
		$this->assertEquals($expected, $actual);
	}
	
	public function testControlerNonPassant(){
		// ARRANGE
		$ctrl=array('nom'=>"fichier-csv",'poids'=>2000,'type'=>"application/excel");
		$traite=['fichier-csv'=>['type'=>'application/vnd.ms-excel','size'=>1000]];
		$expected=TRUE;
		// ACT
		$actual=Utile::controler($traite,$ctrl);
		// ASSERT
		$this->assertEquals($expected, $actual);
	}// TODO behat, table de decision en outline avec array eclate en tableau

	/**
     * @dataProvider tableau
     */	
	public function testControler($a,$b,$c,$expected){
		// ARRANGE
		$ctrl=array('nom'=>"fichier-csv",'poids'=>2000,'type'=>"application/vnd.ms-excel");
		$traite=["$a"=>['type'=>"$b",'size'=>$c]];
		// ACT
		$actual=Utile::controler($traite,$ctrl);
		// ASSERT
		$this->assertEquals($expected, $actual);		
	}
	
	public function tableau(){
		return array( 		  
          'non passant type' => array("fichier-csv", 1000,"ras",TRUE),
		  'non passant poids' => array("fichier-csv", 3000,"application/vnd.ms-excel",TRUE),
		  'non passant nom' => array("fichier-xsv", 1000,"application/vnd.ms-excel",TRUE),
          'non passant poids et type' => array("fichier-csv", 3000,"ras",TRUE),	
		  'non passant nom et type' => array("ext", 1000,"ras",TRUE),	
		  'non passant nom et poids' => array("ext", 3000,"application/vnd.ms-excel",TRUE),
		  'non passant tout' => array("ext", 3000,"excel",TRUE)	  
        ); /* 'passant' => array("fichier-csv", 1000,"application/vnd.ms-excel",FALSE), 
		--- alors que fonctionne seul !!!!
		'non passant nom' => array("fichier-xsv", 1000,"application/vnd.ms-excel",TRUE)
		--- non testable tel quel si index inconnu : refactorer avec array_key_exists		
		*/
	}
	

	
	public	function testLettrines(){
		// ARRANGE
		$tab=[0=>['terme'=>"aristide",'definition'=>"coucou"],1=>['terme'=>"Attention",'definition'=>"coucou"],2=>['terme'=>"bac",'definition'=>"exam"],3=>['terme'=>"baptist",'definition'=>"bebete"],4=>['terme'=>"Elephant",'definition'=>"gros animal"],5=>['terme'=>"excitation",'definition'=>"agitation"],6=>['terme'=>"illusion",'definition'=>"Reve"],7=>['terme'=>"zorro",'definition'=>"heros"]];
		$expected=array('A','B','E','I','Z');
		// ACT		
		$actual=Utile::liste_des_lettres($tab,'terme',0);
		// ASSERT
		$this->assertEquals($expected, $actual);
	}
	
	public function testCsv2array(){
		// ARRANGE
		$fichier = "../Tests-data/import.csv";// BUG-TEST definition sans s -- BUG  '´╗┐terme' enlever tout caractere autre que alphabetique
		$separateur =";";
		$saut_de_ligne="\r\n";
		$nbre_colonnes=2;
		$entete="on";// "on" 1
		$debug="off";
		$expected=[0=>['terme' => "ascenseur",'definition' => "monte"],1=>['terme' => "ecolier",'definition' => "travail"],2=>['terme' => "zorro",'definition' => "heros"]];
		// ACT
		$actual=Utile::csv_to_array($fichier,$separateur,$saut_de_ligne,$nbre_colonnes,$entete,$debug);
		// ASSERT	
		$this->assertEquals($expected, $actual);
	}	
	
	public function Array2xml(){//  return implicite par reference, pb object
		// ARRANGE
		$result=[
'lettres' => 
[0 => 'A', 1 => 'E', 2 => 'Z'],
'liste' =>
[0 => ['lettre' => 'A','terme' => 'ascenseur' ,'definition' => 'monte'], 
1 => ['lettre' => 'E','terme' => 'ecolier','definition' => 'travail'], 
2 => ['lettre' => 'Z','terme' => 'zorro', 'definition' => 'heros']]
];
$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><?xml-stylesheet type=\"text/css\" href=\"defintions.css\"?><?xml-stylesheet type=\"text/xsl\" href=\"definitions.xsl\"?><definitions  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"definitions.xsd\"></definitions>");
$expected=[
'lettres' => 
['lettres_item'=>[0 => 'A', 1 => 'E', 2 => 'Z']],
'liste'=>
['liste_item'=>
[0 => ['lettre' => 'A','terme' => 'ascenseur' ,'def' => 'monte'], 
1 => ['lettre' => 'E','terme' => 'ecolier','def' => 'travail'], 
2 => ['lettre' => 'Z','terme' => 'zorro', 'def' => 'heros']]
]
];	// BUG pour tester : object is not array	
		// ACT
		Utile::array_to_xml($result,$xml);
		$actual=$xml;
		// ASSERT
		$this->assertEquals($expected, $actual);
	}

	// non testable en unitaire : xml_avec_indentation
	
	public function testXml2array(){// export
		// ARRANGE
		$xml=simplexml_load_file("../Tests-data/definitions.xml");
		$array=array();
		$expected=[0=>['terme' => "terme",'definition' => "definition"],1=>['terme' => "ascenseur",'definition' => "monte"],2=>['terme' => "ecolier",'definition' => "travail"],3=>['terme' => "zorro",'definition' => "heros"]];		
		// ACT
		$actual=Utile::xml_to_array($xml,$array);
		// ASSERT
		$this->assertEquals($expected, $actual);
	}
	
	public function testArray2csv(){// test avec saut de ligne
		// ARRANGE
		$data=[0=>['terme' => "terme",'definition' => "definition"],1=>['terme' => "ascenseur",'definition' => "monte"],2=>['terme' => "ecolier",'definition' => "travail"],3=>['terme' => "zorro",'definition' => "heros"]];
		$expected=[0=>"terme;definition\r\n",1=>"ascenseur;monte\r\n",2=>"ecolier;travail\r\n",3=>"zorro;heros\r\n"]; 
		// ACT
		$actual=Utile::array_format_csv($data);
		// ASSERT
		$this->assertEquals($expected, $actual);
	}
	
	public function testarrayCsv2file(){// test de fichier
		// ARRANGE
		$expected='../Tests-data/import.csv';
		$actual='../Tests-data/export.csv';
		if (file_exists($actual)){
		unlink($actual);
		}
		$data=[0=>"terme;definition\r\n",1=>"ascenseur;monte\r\n",2=>"ecolier;travail\r\n",3=>"zorro;heros\r\n"];
		// ACT		
		Utile::array_formatCsv_versFichierCsv($data,$actual);
		// ASSERT
		$this->assertFileEquals($expected, $actual);
	}
}
?>