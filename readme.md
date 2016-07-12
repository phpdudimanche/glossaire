# Glossaire en ligne #

 **ED (Elementary Glossary)**, le bien nommé, est la version simpliste d'un glossaire correspondant à un besoin tout aussi élémentaire. Chaque projet (de Dev, de Test...) a son MOU (Memorandum Of understanding) ; autant faire dans la simplicité. Fonctionne avec excel 2007 pour un csv ayant ces contraintes :

- aucun ";" dans les champs excel,
- aucun  caractère accentué en première lettre d'un terme à définir,
- champs : "terme" et "definition" (partir de l'exemple fourni).

Le glossaire a été réalisé à des fins de démonstration : de [code](#code) (qui réinvente la roue), et de [test](#test). 


## Utilisation ##

### Importer le glossaire ###

- Depuis Excel 2007, créer une feuille avec les définitions et l'entête : terme, definition.
- Trier la feuille par "terme".
- Enregistrer sous : "CSV avec séparateur ;".
- Sur la page du site : cliquer sur "import" pour remplacer le glossaire présent (par défaut un glossaire est fourni).
- A l'issue de l'import, le glossaire a été mis à jour.

### Utiliser le glossaire ###

- Sur site, le glossaire est présenté avec un menu alphabétique dont chaque lettre amène à la première occurence d'un terme avec cette lettre en début.
- A la première occurence d'une nouvelle lettre, une lettrine apparaît afin de faciliter le repérage.
- Chaque terme est accompagné d'un bouton "retour haut" qui ramène au menu alphabétique.
- Pour effectuer uen recherche précise, un "CTRL F" dans le navigateur permet d'aller aux occurences recherchées.
- Pour modifier, ajouter, supprimer un terme, "Exporter le glossaire" et le modifier dans excel avant d'à nouveau "Importer le glossaire" (ces fonctions sont protégées par un htaccess avec chemin relatif dpuis le root serveur).

### Exporter le glossaire ###

- Sur la page du site : cliquer sur "export".
- Un lien de téléchargement  est proposé : cliquer dessus et enregistrer le fichier à l'emplacement souhaité.
- Dans l'explorateur de fichier d'ordinateur, cliquer sur le fichier ouvre directement le classeur en mode consultation et édition.

## Installation ##

Le répertoire du projet est à copier chez votre hébergeur PHP5. Aucune base de donnée n'est nécessaire. Si vous conservez la protection par .htaccess, changez ce fichier dans le dossier Admin, concernant le nom de votre répertoire racine si vous en avez un.

## Conception ##

### <a name="code"></a> Le code ###

Le code se passe des possibilités offertes par la communauté PHP :

- librairie excel, 
- librariries existantes pour traiter le csv, 
- fonctions natives en PHP de type get et put csv.

Le code s'intéresse à :

- la manipulation du XML avec XSLT et CSS,
- la manipulation de fichiers à plat et de tableau (array).

### <a name="test"></a> Les tests ###

Des tests sont réalisés sous forme : 

- PhpUnit unitaire.


La documentation (PHPdocumentor) est également générée, ainsi qu'une analyse statique de code (PHPmd), et une analyse dynamique de couverture.

### Lancer les tests ###

Les dépendances pour les tests sont dans le fichier composer.json qui nécessite d'avoir **composer**.  
Le lancement des tests et de la documentation s'effectue par le fichier build.xml qui nécessite d'avoir **ant**.
