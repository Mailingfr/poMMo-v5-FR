<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Location: transparent.gif");

/* Paramètres de connexion */
include '../inc/inc-connex-PDO.php';

/* Récupération des valeurs des cookies */
$subscriber_id = $_GET['subscriber_id'];
$mailing_id = $_GET['mailing_id'];
$title_page = utf8_encode(addslashes($_GET['title_page']));
$ip = $_SERVER['REMOTE_ADDR'];

/* Interrogation de la base */
$sqlmailings = $id_connex->query("SELECT mailing_id FROM ".$prefix."mailings");
$sqlsubscribers = $id_connex->query("SELECT subscriber_id FROM ".$prefix."subscribers");

/* Extraction des valeurs de référence et fabrication des arrays */
$listemailingid = $sqlmailings->fetchAll();
$listesubscriberid = $sqlsubscribers->fetchAll();
foreach($listemailingid as $m){
    $mailingidarray[] = $m[mailing_id];
}
foreach($listesubscriberid as $s){
    $subscriberidarray[] = $s[subscriber_id];
}

/* Si les données reçues sont vraies -> intégration dans la base */
if (in_array($mailing_id, $mailingidarray) and in_array($subscriber_id, $subscriberidarray))
{
  $insert_st = "INSERT INTO ".$prefix."clickedurl VALUES ('', '".$subscriber_id."', '".$mailing_id."', '".$title_page."', '".$ip."', now()) ";
  $insertion = $id_connex->query($insert_st) or die (print_r($id_connex->errorInfo()));
}
else
{
  echo "Erreur d'enregistrement";
}

return;

/* Code à insérer dans les pages
<!--clickedurl -->
<script type="text/javascript">
<!--
var base_url_script_clickedurl = "http://www.votredomaine.com/dossier_pommo/specific/trackings/clickerurl.php";
//-->
</script>
<script type="text/javascript" src="http://www.votredomaine.com/dossier_pommo/specific/trackings/clickedurl.js"></script>
<!--clickedurl fin -->
*/
?>
