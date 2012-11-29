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
$title_page = $_GET['title_page'];

$requetebrute = 
"SELECT DISTINCT c.mailing_id, table_mailing_subject, table_mailing_mailgroup, DATE_FORMAT(table_mailing_started, '%d/%m/%Y %Hh%imn%ss') AS started, DATE_FORMAT(o.atime, '%d/%m/%Y %Hh%imn%ss') AS dateouverture, DATE_FORMAT(c.atime, '%d/%m/%Y %Hh%imn%ss') AS dateclic, ROUND((TIMESTAMPDIFF(SECOND,started,c.atime)/3600),2) AS latence, table_subscriber_email, u.occurrence, c.title_page
FROM table_clickedurl AS c
INNER JOIN
(SELECT subscriber_id, MIN(atime) AS minatime
FROM table_clickedurl
WHERE mailing_id = ".$mailing_id."
AND title_page LIKE \"".$title_page."\" 
GROUP BY subscriber_id) AS z
ON c.atime = z.minatime
AND c.subscriber_id = z.subscriber_id
INNER JOIN (
SELECT subscriber_id, COUNT(subscriber_id) AS occurrence
FROM table_clickedurl
WHERE mailing_id = ".$mailing_id."
AND title_page LIKE \"".$title_page."\"
GROUP BY subscriber_id) AS u
ON c.subscriber_id = u.subscriber_id
INNER JOIN table_subscribers ON (c.subscriber_id = table_subscriber_id)
INNER JOIN table_mailings ON (table_mailing_id = c.mailing_id)
LEFT JOIN table_opentracking AS o ON (c.mailing_id = o.mailing_id) AND (c.subscriber_id = o.subscriber_id)
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
   $requetefinale = str_replace(table_clickedurl, $prefix."clickedurl", $requetefinale);   
   
   // Pour vérifier la requete, décommenter la ligne ci-dessous.
   // print $requetefinale;
   // La 2nde requete est fin prete: nous allons maintenant l'exécuter...
   
   $result = NULL;   
   $result = $id_connex->query($requetefinale);
   
    if (!$result) 
        {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
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
            'Heure du 1er clic' => $d[dateclic],
            'Latence (heures)' => $d[latence],
            'Email' => $d[email],            
            'Frequence' => $d[occurrence],
            'Pages ouvertes' => utf8_decode($d[title_page])
        );
    }

require 'exportcsv.class.php';
CSV::export($datas, 'pommo_tracking_details');

// print_r($datas);

}

    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
    }

Pommo::kill();
?>
