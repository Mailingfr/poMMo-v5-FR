<?php 

// Paramètres de connexion.
$host="localhost"; // Nom du serveur MySql
$username="root"; // Nom d'utilisateur avec lequel poMMo se connectera à la base de données
$password="password"; // Mot de passe avec lequel poMMo se connectera à la base de données
$db_name="database"; // Nom de la base de données MySql qui sera utilisée par poMMo
$prefix = "pommov5fr_"; // Préfixe des tables pour cette installation, à modifier si vous avez plusieurs installations de poMMo dans la même base de données.
try
{
	$id_connex = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}


?>
