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
"SELECT DISTINCT mailing_id
    FROM ".$prefix."opentracking
    ORDER BY mailing_id ASC
";

$result = $id_connex->query($sql);

if (!$result) 
    {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_opening_menu.php\">";
    }

while ($row=$result->fetch())
        {
           $mailingid[]=$row[mailing_id];
           $donnees_initiales = array_map(create_function('$idmail', 'return "SELECT o.mailing_id, table_mailing_subject, table_mailing_mailgroup, table_mailing_sent, DATE_FORMAT(table_mailing_started, \'%d/%m/%Y %Hh%imn%ss\') AS started, ROUND((TIMESTAMPDIFF(SECOND,started,FROM_UNIXTIME(AVG(DISTINCT(UNIX_TIMESTAMP(o.atime)))))/3600),2) AS latence, COUNT(DISTINCT(o.subscriber_id)) AS messages_ouverts, ROUND(((COUNT(DISTINCT(o.subscriber_id))/table_mailing_sent)*100),2) AS taux_ouvertures FROM table_opentracking AS o INNER JOIN (SELECT subscriber_id, MIN(atime) AS minatime FROM table_opentracking WHERE mailing_id = ".$idmail." GROUP BY subscriber_id) AS z ON (o.atime = z.minatime) AND (o.subscriber_id = z.subscriber_id) INNER JOIN table_subscribers ON (o.subscriber_id = table_subscriber_id) INNER JOIN table_mailings ON (table_mailing_id = o.mailing_id) UNION ";'), array_values($mailingid));
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
   
      
   $result = NULL;   
   $allclicktracking = $id_connex->query($requetefinale);
   
    if (!$allclicktracking) 
        {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_opening_menu.php\">";
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
            'Messages ouverts' => $d[messages_ouverts],
            'CTR (% ouvertures)' => $d[taux_ouvertures],
            'Latence (heures)' => $d[latence]
        );
    }

require 'exportcsv.class.php';
CSV::export($datas, 'pommo_tracking_ouvertures');

// print_r($datas);

Pommo::kill();
?>
