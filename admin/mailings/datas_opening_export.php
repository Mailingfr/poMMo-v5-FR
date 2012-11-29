<?php

/**
 * Mod Tracking - Pommo V0.1 G.Lengy - www.artaban.fr -
 * Modifications par Ariel Elyah - www.mailingfr.com -
 */

/**********************************
	INITIALIZATION METHODS
 *********************************/
require('../../bootstrap.php');
$pommo->init();
$logger = & $pommo->_logger;
$dbo = & $pommo->_dbo;

/**********************************
	SETUP TEMPLATE, PAGE
 *********************************/
Pommo::requireOnce($pommo->_baseDir.'inc/classes/template.php');
$smarty = new PommoTemplate();

/**********************************
PARAMÈTRES DE CONNEXION À LA BASE 
**********************************/

include '../../specific/inc/inc-connex-PDO.php';

$titre_page="";

/************************************ 
CONDITION D'AFFICHAGE DES RESULTATS 
*************************************/

if(isset($_GET['mailing_id'])){
$mailing_id = $_GET['mailing_id'];

$requetebrute = 
"SELECT DISTINCT o.mailing_id, o.subscriber_id, table_mailing_subject, table_mailing_mailgroup, DATE_FORMAT(table_mailing_started, '%d/%m/%Y %Hh%imn%ss') AS started, DATE_FORMAT(o.atime, '%d/%m/%Y %Hh%imn%ss') AS dateouverture, ROUND((TIMESTAMPDIFF(SECOND,started,o.atime)/3600),2) AS latence, table_subscriber_email 
FROM table_opentracking AS o 
INNER JOIN 
(SELECT subscriber_id, MIN(atime) AS minatime 
FROM table_opentracking 
WHERE mailing_id = ".$mailing_id." 
GROUP BY subscriber_id) AS z 
ON (o.atime = z.minatime) AND (o.subscriber_id = z.subscriber_id) 
INNER JOIN table_subscribers ON (o.subscriber_id = table_subscriber_id) 
INNER JOIN table_mailings ON (table_mailing_id = o.mailing_id) 
ORDER BY latence DESC
";

// echo $requetebrute;
   
   $requetefinale = str_replace(table_subscribers, $prefix."subscribers", $requetebrute);
   $requetefinale = str_replace(table_subscriber_id, $prefix."subscribers.subscriber_id", $requetefinale);
   $requetefinale = str_replace(table_subscriber_email, $prefix."subscribers.email", $requetefinale);
   $requetefinale = str_replace(table_mailings, $prefix."mailings", $requetefinale);
   $requetefinale = str_replace(table_mailing_id, $prefix."mailings.mailing_id", $requetefinale);
   $requetefinale = str_replace(table_mailing_started, $prefix."mailings.started", $requetefinale);
   $requetefinale = str_replace(table_mailing_sent, $prefix."mailings.sent", $requetefinale);
   $requetefinale = str_replace(table_mailing_subject, $prefix."mailings.subject", $requetefinale);
   $requetefinale = str_replace(table_mailing_mailgroup, $prefix."mailings.mailgroup", $requetefinale);
   $requetefinale = str_replace(table_opentracking, $prefix."opentracking", $requetefinale);      
  
   
   // Pour vérifier la requete, décommenter la ligne ci-dessous.
   // print $requetefinale;
   // La 2nde requete est fin prete: nous allons maintenant l'exécuter...
   
   $result = NULL;   
   $result = $id_connex->query($requetefinale);
   
    if (!$result) 
        {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_opening_menu.php\">";
        }

    $data = $result->fetchAll();
    // print_r($data);
    $datas = array();
    foreach($data as $d){
        $datas[] = array(
            'ID Mail' => $d[mailing_id],
            'Titre du message' => $d[subject],
            'Groupe' => $d[mailgroup],            
            'Heure d\'envoi' => $d[started],
            'Heure d\'ouverture' => $d[dateouverture],
            'Latence (heures)' => $d[latence],
            'Email' => $d[email]
        );
    }

require 'exportcsv.class.php';
CSV::export($datas, 'pommo_ouvertures_details');

// print_r($datas);

}

    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_opening_menu.php\">";
    }

Pommo::kill();
?>
