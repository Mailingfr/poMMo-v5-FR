<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Location: transparent.gif");

/* Paramètres de connexion */
include '../inc/inc-connex-PDO.php';

/* Récupération des variables */
$subscriber_id = $_GET['subscriber_id'];
$mailing_id = $_GET['mailing_id'];
$ip = $_SERVER['REMOTE_ADDR'];

/* Interrogation des tables mailings et subscribers : définition des valeurs possibles de $mailing_id et $subscriber_id */
$sqlmailings = $id_connex->query("SELECT mailing_id FROM ".$prefix."mailings");
$sqlsubscribers = $id_connex->query("SELECT subscriber_id FROM ".$prefix."subscribers");

/* Extraction des valeurs possibles et fabrication des arrays */
$listemailingid = $sqlmailings->fetchAll();
$listesubscriberid = $sqlsubscribers->fetchAll();
foreach($listemailingid as $m){
    $mailingidarray[] = $m[mailing_id];
}
foreach($listesubscriberid as $s){
    $subscriberidarray[] = $s[subscriber_id];
}

/* -> Si les données reçues font partie des valeurs possibles:
 * -> On vérifie qu'elles ne sont pas déjà enregistrées dans la table opentracking 
 * -> Si ces deux conditions sont réunies: on enregistre */

if (in_array($mailing_id, $mailingidarray) and in_array($subscriber_id, $subscriberidarray))
{
    $sql = "SELECT subscriber_id, mailing_id FROM ".$prefix."opentracking WHERE subscriber_id=$subscriber_id AND mailing_id=$mailing_id";
    $result = $id_connex->query($sql);
    $num_rows = $result->rowCount();
    if ($num_rows == 0)
    {
        $insert_st = "INSERT INTO ".$prefix."opentracking VALUES ('', '".$subscriber_id."', '".$ip."', now(), '".$mailing_id."')";
        $insertion = $id_connex->query($insert_st) or die (print_r($id_connex->errorInfo()));        
    }
    else {
        echo "Attention: doublons dans la table opentracking.";
    }
}

else {
        echo "Erreur d'enregistrement";
    }

return;

/* Code à insérer dans le mail
<img src="http://www.votredomaine.com/dossier_pommo/specific/trackings/getimage.php?subscriber_id=[[!subscriber_id]]&mailing_id=[[!mailing_id]]" width="1" height="1">
*/

?>
