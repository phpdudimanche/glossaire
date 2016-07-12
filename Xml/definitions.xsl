<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/> 
	<xsl:template match="/">  
		<html>
			<head>
				<title>liste de definitions</title>
				<link rel="stylesheet" href="definitions.css" type="text/css" media="screen"/><!-- style appele bis ? -->
			</head>
			<body>
			
			<a name="top"></a>
			<p class="menu">
			<xsl:for-each select="definitions/lettres/lettres_item">			
				<!-- parce que meme balise --><a href="#{text()}"><xsl:value-of select="text()"/></a> | 			
			</xsl:for-each>	
			- <a href="../Admin/import_form.php">import</a> - <a href="../Publique/export_act.php">export</a>  - <a href="../readme.php">aide</a>
			</p>
			
			<!-- ne permet pas de boucler sur une liste de balises identiques : lettres -->
			<xsl:for-each select="definitions/liste/liste_item"><a name="{lettre}"></a>
					<h1 id="xsl:value-of select='lettre'">
						<xsl:value-of select="lettre" />						
					</h1>
					<h2>
						<xsl:value-of select="terme" />						
					</h2>
					<a href="#top" class="top">retour haut</a>
					<p>
						<xsl:value-of select="definition" />
					</p>
			</xsl:for-each>			
			
			</body>
		</html>
	</xsl:template> 
	



	
	
	
</xsl:stylesheet>