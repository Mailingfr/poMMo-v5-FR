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


/*********************************
PARAMÈTRES DE CONNEXION À LA BASE 
**********************************/

include '../../specific/inc/inc-connex-PDO.php';

$titre_page="";

if(isset($_GET['criteria'])){
$where = $_GET['criteria'];
$where = substr($where, 0, -1);
$where = substr($where, 1);
$where = $where."'";

//print_r($where);

$sql = 
"SELECT 
".$prefix."subscribers.email,
DATE_FORMAT(".$prefix."opentracking.atime, '%d/%m/%Y %Hh%imn%ss') AS openingtime,
DATE_FORMAT(".$prefix."clickedurl.atime, '%d/%m/%Y %Hh%imn%ss') AS clicktime,
".$prefix."clickedurl.title_page,
".$prefix."mailings.subject,
".$prefix."mailings.mailing_id, 
DATE_FORMAT(".$prefix."mailings.started, '%d/%m/%Y %Hh%imn%ss') AS started,
ROUND((TIMESTAMPDIFF(SECOND,started,".$prefix."clickedurl.atime)/3600),2) AS latence,
".$prefix."mailings.mailgroup 
FROM ".$prefix."clickedurl
INNER JOIN ".$prefix."subscribers ON (".$prefix."clickedurl.subscriber_id = ".$prefix."subscribers.subscriber_id) 
INNER JOIN ".$prefix."mailings ON (".$prefix."mailings.mailing_id = ".$prefix."clickedurl.mailing_id) 
LEFT JOIN ".$prefix."opentracking ON (".$prefix."opentracking.mailing_id = ".$prefix."clickedurl.mailing_id) AND (".$prefix."opentracking.subscriber_id = ".$prefix."clickedurl.subscriber_id)
WHERE $where
ORDER BY latence DESC";

//echo $sql;
      
   $result = NULL;   
   $result = $id_connex->query($sql);
   
   if (!$result) 
        {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
        }

    $data = $result->fetchAll();
    //print_r($data);
    $datas = array();
    foreach($data as $d){
        $datas[] = array(
            'ID Mail' => $d[mailing_id],
            'Titre du message' => $d[subject],
            'Groupe' => $d[mailgroup],            
            'Heure d\'envoi' => $d[started],
            'Heure d\'ouverture' => $d[openingtime],
            'Heure du clic' => $d[clicktime],
            'Latence (heures)' => $d[latence],
            'Emails' => $d[email],            
            'Pages ouvertes' => utf8_decode($d[title_page])
        );
    }

require 'exportcsv.class.php';
CSV::export($datas, 'pommo_tracking_par_critere');

//print_r($datas);

}

    else
    {
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
    }

Pommo::kill();
?>
