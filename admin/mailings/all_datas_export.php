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

$sql = 
"SELECT DISTINCT mailing_id, title_page 
    FROM ".$prefix."clickedurl
    ORDER BY mailing_id ASC
";

$result = $id_connex->query($sql);

if (!$result) 
    {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
    }

while ($row=$result->fetch())
        {
           $mailingid[]=$row[mailing_id];
           $titlepage[]=addslashes($row[title_page]);
           $donnees_initiales = array_map(create_function('$idmail, $pagetitle', 'return "SELECT c.mailing_id, table_mailing_subject, table_mailing_mailgroup, table_mailing_sent, DATE_FORMAT(table_mailing_started, \'%d/%m/%Y %Hh%imn%ss\') AS started, ROUND((TIMESTAMPDIFF(SECOND,started,FROM_UNIXTIME(AVG(DISTINCT(UNIX_TIMESTAMP(c.atime)))))/3600),2) AS latence, COUNT(DISTINCT(c.subscriber_id)) AS clics_uniques, ROUND(((COUNT(DISTINCT(o.subscriber_id))/table_mailing_sent)*100),2) AS taux_ouvertures, ROUND(((COUNT(DISTINCT(c.subscriber_id))/table_mailing_sent)*100),2) AS taux_clics, c.title_page FROM table_clickedurl AS c INNER JOIN (SELECT subscriber_id, MIN(atime) AS minatime FROM table_clickedurl WHERE mailing_id = ".$idmail." AND title_page LIKE \"".$pagetitle."\" GROUP BY subscriber_id) AS z ON c.atime = z.minatime AND c.subscriber_id = z.subscriber_id INNER JOIN table_subscribers ON (c.subscriber_id = table_subscriber_id) INNER JOIN table_mailings ON (table_mailing_id = c.mailing_id) LEFT JOIN table_opentracking AS o ON (c.mailing_id = o.mailing_id) UNION ";'), array_values($mailingid), array_values($titlepage));
        }
      
   $requetebrute = implode($donnees_initiales);
   $requeteintermediaire1 = substr($requetebrute, 0, -7);
   $requeteintermediaire2 = $requeteintermediaire1." ORDER BY mailing_id DESC";    
   $requetefinale = str_replace(table_subscribers, $prefix."subscribers", $requeteintermediaire2);
   $requetefinale = str_replace(table_subscriber_id, $prefix."subscribers.subscriber_id", $requetefinale);
   $requetefinale = str_replace(table_mailings, $prefix."mailings", $requetefinale);
   $requetefinale = str_replace(table_mailing_id, $prefix."mailings.mailing_id", $requetefinale);
   $requetefinale = str_replace(table_mailing_started, $prefix."mailings.started", $requetefinale);
   $requetefinale = str_replace(table_mailing_sent, $prefix."mailings.sent", $requetefinale);
   $requetefinale = str_replace(table_mailing_subject, $prefix."mailings.subject", $requetefinale);
   $requetefinale = str_replace(table_mailing_mailgroup, $prefix."mailings.mailgroup", $requetefinale);
   $requetefinale = str_replace(table_opentracking, $prefix."opentracking", $requetefinale);   
   $requetefinale = str_replace(table_clickedurl, $prefix."clickedurl", $requetefinale);      
      
   $result = NULL;   
   $allclicktracking = $id_connex->query($requetefinale);
   
    if (!$allclicktracking) 
        {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
        }

    $data = $allclicktracking->fetchAll();
    // print_r($data);
    $datas = array();
    foreach($data as $d){
        $datas[] = array(
            'ID Mail' => $d[mailing_id],
            'Titre du message' => $d[subject],
            'Heure d\'envoi' => $d[started],
            'Groupe' => $d[mailgroup],
            'Envois' => $d[sent],
            'Visites uniques' => $d[clics_uniques],
            'CTR-1 (% ouvertures)' => $d[taux_ouvertures],
            'CTR-2 (% clics)' => $d[taux_clics],
            'Latence (heures)' => $d[latence],
            'Pages ouvertes' => utf8_decode($d[title_page])
        );
    }

require 'exportcsv.class.php';
CSV::export($datas, 'pommo_tracking_url');

// print_r($datas);

Pommo::kill();
?>
