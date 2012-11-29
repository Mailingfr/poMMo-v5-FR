<?php die(); /* NE SUPPRIMEZ PAS CETTE LIGNE ! FAILLE DE SECURITE IMPORTANTE SI SUPPRIMEE ! */ ?>
====================================================================
Bienvenue dans le fichier de configuration de poMMo. C'est ici que 
s'effectuent la configuration de l'accès à la base de données MySQL 
et les différents parametrages de poMMo.
  
IMPORTANT: Le présent fichier doit se nommer "config.php" et placé 
à la racine du dossier d'installation de poMMo 
(là où se trouve le fichier bootstrap.php).
  
Voir config.simple.sample.php si vous souhaitez un fichier de 
configuration plus simple et lisible.
====================================================================

::: Informations sur la base de données MySQL :::

[db_hostname] = "localhost"
	Nom du serveur MySQL (souvent localhost)
	NOTE: Il est possible d'utiliser des serveurs MySQL distants (par exemple sql31.free-h.org)

[db_username] = "root"
	Le nom d'utilisateur (ou identifiant) que poMMo utilisera pour se connecter au serveur MySQL

[db_password] = "password"
	Le mot de passe de connexion au serveur MySQL
	
[db_database] = "database"
	Le nom de la base MySQL 
	
[db_prefix] = "pommov5fr_"
	A modifier si vous envisagez de mettre plusieurs installations de poMMo au sein d'une même base

 	
::: Informations sur la langue :::

[lang] = fr
	Configuration de la langue de poMMo. Les langues disponibles sont
	 bg - Bulgarian			it - Italian				
	 da - Danish			nl - Dutch
	 de - German			pl - Polish
	 en - English			pt - Portuguese
	 en-uk - British		pt-br - Brazilian Portuguese
	 es - Spanish			ro - Romanian
	 fr - French			ru - Russian			
	 		
::: Paramètres optionnels :::

====================================================================
Les options ci-dessous concernent le déboguage et la modification 
de la configuration automatique de poMMo.
====================================================================

[debug] = off
	Activer (on) ou desactiver (off). Le mode debug fournit 
	des informations utiles aux programmeurs
  
[verbosity] = 3
	Configuration de l'affichage d'informations de deboguage
	1: Mode deboguage complet - *TOUS LES EVENEMENTS* font l'objet de rapports 
	2: Mode informationnel - *LA PLUPART DES EVENEMENTS* font l'objet de rapports
	3: Mode silencieux - *LES EVENEMENTS IMPORTANTS* seulement font l'objet de rapports [mode par défaut]
	
[date_format] = 3
	Configuration du format des dates.
	Les formats disponibles sont
	 1: YYYY/MM/DD (e.g. 1969/12/15) [par défaut]
	 2: MM/DD/YYYY
	 3: DD/MM/YYYY
	
::: Paramètres avancés :::
  Décommenter (enlever les balises "**") pour activer les réglages ci-dessous.
  NOTE: poMMo détecte automatiquement ces paramètres, il est donc préférable de ne rien modifier.
 
** [baseURL] = "/mysite/newsletter/"
	Configurer la base URL (le chemin relatif de poMMo a partir du navigateur), par exemple
	  (Emplacement de poMMo)					(Valeur de base URL)
  	   http://newsletter.mysite.com/			/
 	   http://www.mysite.com/me/pommo			/me/pommo/
  
  	NOTE: N'oubliez pas d'inclure le slash de fin
	
** [workDir] = "/path/to/pommoCache"
	Définir le dossier de travail de l'application. poMMo écrit des fichiers temporaires dans ce dossier.  
  	Par défaut, poMMo utilise un dossier qui se nomme “cache” pour ses opérations.
  	
  	Pour augmenter la sécurité de l'application vous pouvez aussi déplacer ce dossier vers un emplacement 
  	non accessible via le navigateur web (par exemple /home/brice/work au lieu de /home/brice/public_html/work)
  	
  	Faites toujours en sorte que le dossier de travail soit accessible en écriture (CHMOD 777) 
	
** [hostname] = www.mysite.com
	Définir le nom du serveur d'hébergement
	
** [hostport] = 8080
	Définir le port à utiliser pour l'application [d'habitude 80, 443, or 8080]
