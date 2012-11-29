<?php
header('Content-type: text/html; charset=UTF-8');
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

/******************************************
AFFICHAGE DES RACCOURCIS VERS LES GROUPES 
*******************************************/

$requetegroupe = 
"
SELECT DISTINCT mailgroup 
FROM ".$prefix."mailings AS m 
JOIN ".$prefix."clickedurl AS c 
ON m.mailing_id = c.mailing_id 
ORDER BY mailgroup ASC
";

$executionrequetegroupe = $id_connex->query($requetegroupe);
while($arraygroupes = $executionrequetegroupe->fetch())
{
    $listegroupes[] = "<option value=\"".utf8_encode($arraygroupes[mailgroup])."\">".utf8_encode($arraygroupes[mailgroup])."</option>";
}
if($listegroupes)
{
    $groupes = implode("",$listegroupes);
}

/**************************************
IDENTIFICATION DES VARIABLES TRANSMISES
***************************************/

/**************************************
CAS 1: PAGINATION PAR BOUTON VALIDER
***************************************/

/* Les variables du formulaire sont envoyées exclusivement en UTF-8.
 * La requete sur group_name doit donc etre traduite en ISO pour
 * correspondre avec la chaine group_name au format iso dans la BDD.
 */

if(isset($_GET['charset'])) {
    
if(isset($_GET['mailing_id']) and $_GET['mailing_id']!='/' and $_GET['mailing_id']!=NULL){
$mailing_id = $_GET['mailing_id'];
$pagecriteria = "mailing_id=".urlencode($mailing_id);
$where = $prefix."mailings.mailing_id = '".$mailing_id;
}

if(isset($_GET['group_name']) and $_GET['group_name']!='/' and $_GET['group_name']!=NULL){
    if ($_GET['group_name'] == 'touslesgroupes')
    {
        $group_name_utf8 = $_GET['group_name'];
        $group_name = utf8_decode($_GET['group_name']);        
        $pagecriteria = "group_name=".urlencode($group_name);
        $where = $prefix."subscribers.subscriber_id > '0";
        $titre_aff = "Affichage de tous les clics enregistr&eacute;s";
    }
    else
    {
        $group_name_utf8 = $_GET['group_name'];
        $group_name = utf8_decode($_GET['group_name']);
        $pagecriteria = "group_name=".urlencode($group_name);
        $where = $prefix."mailings.mailgroup LIKE '".$group_name;
        $titre_aff = "Tous les clics enregistr&eacute;s depuis le groupe <span style=\"color:orange\">".$group_name_utf8."</span>";
    }
}

if(isset($_GET['title_page']) and $_GET['title_page']!='/' and $_GET['title_page']!=NULL){      
$title_page = urldecode($_GET['title_page']);
$pagecriteria = "title_page=".urlencode($title_page);
$where = $prefix."clickedurl.title_page LIKE '".$title_page;
}

if(isset($_GET['email']) and $_GET['email']!='/' and $_GET['email']!=NULL){
$email = $_GET['email'];
$pagecriteria = "email=".urlencode($email);
$where = $prefix."subscribers.email LIKE '".$email;
}

}

/**************************************
CAS 2: PAGINATION SIMPLE
***************************************/

/* La variable $_GET['group_name'] est initialement transmise en ISO,
 * Pour l'afficher en UTF-8 il faut l'encoder avec utf8_encode.
 */

else {
    
if(isset($_GET['mailing_id'])){
$mailing_id = $_GET['mailing_id'];
$pagecriteria = "mailing_id=".urlencode($mailing_id);
$where = $prefix."mailings.mailing_id = '".$mailing_id;
}

if(isset($_GET['group_name'])){
    if ($_GET['group_name'] == 'touslesgroupes')
    {
        $group_name_utf8 = utf8_encode($_GET['group_name']);
        $group_name = $_GET['group_name'];
        $pagecriteria = "group_name=".urlencode($group_name);
        $where = $prefix."subscribers.subscriber_id > '0";
        $titre_aff = "Affichage de tous les clics enregistr&eacute;s";
    }
    else
    {
        $group_name_utf8 = utf8_encode($_GET['group_name']);
        $group_name = $_GET['group_name'];
        $pagecriteria = "group_name=".urlencode($group_name);
        $where = $prefix."mailings.mailgroup LIKE '".$group_name;
        $titre_aff = "Tous les clics enregistr&eacute;s depuis le groupe <span style=\"color:orange\">".$group_name_utf8."</span>";
    }
}

if(isset($_GET['title_page'])){   
$title_page = urldecode($_GET['title_page']);
$pagecriteria = "title_page=".urlencode($title_page);
$where = $prefix."clickedurl.title_page LIKE '".$title_page;
}

if(isset($_GET['email'])){
$email = $_GET['email'];
$pagecriteria = "email=".urlencode($email);
$where = $prefix."subscribers.email LIKE '".$email;
}    
    
}

$titre_page="";
$criteria = urlencode($where);

//print_r($criteria);

/************************************************* 
 * FABRICATION DE LA REQUETE PRINCIPALE *
*************************************************/

$sql1 = 
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
WHERE ".$where."'
ORDER BY latence DESC";

// print_r($sql1);

/**************************************
 * EXECUTION DE LA REQUETE PRINCIPALE
 **************************************/

$totalresult = NULL;   
$totalresult = $id_connex->query($sql1);

if (!$totalresult) 
    {
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
    } 

/**************************************
 * NOMBRE TOTAL DE RESULTATS
 **************************************/

$total_resultats = $totalresult->rowCount();

/*************************************************** 
DEBUT CALCUL NOMBRE DE PAGES ET AFFICHAGE DES LIENS 
****************************************************/

      if (isset($_GET['resultatsparpage']))
        {
          $resultatsparpage = $_GET['resultatsparpage'];
          if ($resultatsparpage == "Tous" OR $resultatsparpage < 1)
          {
              $resultatsparpage = $total_resultats;
              $nbpages = 1;
          }
          else
          {
              $nbpages = ceil($total_resultats/$resultatsparpage);
          }
        }
      else
        {
          $resultatsparpage = 25;
          $nbpages = ceil($total_resultats/$resultatsparpage);
        }
      
/*** ETAPE 1 - FONCTION DE FABRICATION DES LIENS DES PAGES ***/
      
      function AffichageDesLiensDesPages($npages,$resultatsparpage,$pagecriteria)
      {
          for($i=1; $i<=$npages; $i++)
        {
          $arrayliensdespages[] = '<a href="tracking_url_by_criteria.php?resultatsparpage='.$resultatsparpage.'&amp;page='.$i.'&amp;'.$pagecriteria.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;';
        }
        
/*** LA FONCTION PLACE TOUS LES LIENS RETOURNÉS PAR LA BOUCLE FOR DANS UN ARRAY. ***/
        
          return $arrayliensdespages; 
          
      }

/*** EXECUTION --> LA FONCTION RETOURNE L'ARRAY CONTENANT LES LIENS DES PAGES. ***/     
      
      $liensdespages = AffichageDesLiensDesPages($nbpages,$resultatsparpage,$pagecriteria);
      //print_r($affichagedesliensdespages);
      
/*** EXÉCUTION DE LA FONCTION POUR RÉCUPÉRER LES LIENS DES PAGES. ***/
/*** LES LIENS SONT RÉAFFICHÉS L'UN APRÈS L'AUTRE SOUS FORME DE TEXTE. ***/
      
      if($liensdespages)
      {
                $affichagedesliensdespages = implode("",$liensdespages); 
      }

      //print_r($affichagedesliensdespages);
         
/*** ETAPE 2- INTRODUCTION DES LIENS DES PAGES DANS LA REQUETE - PROCÉDURE CLASSIQUE : CF. DOCUMENTATION SUR LE SITE DU ZÉRO. ***/
/*** ON RÉCUPÈRE LE NUMÉRO DE LA PAGE INDIQUÉE DANS L'ADRESSE ***/
      
      if (isset($_GET['page']))
      {
          $page = $_GET['page']; 
      }

/*** SI LA VARIABLE $_GET['page'] N'EST PAS DÉFINIE = PREMIER CHARGEMENT DE LA PAGE --> ON AFFICHE LA 1ERE PAGE ***/
      
      else 
      {
          $page=1; 
      }

/*** ON CALCULE LE RANG DU 1ER MESSAGE À METTRE DANS LA REQUETE --> FIN ***/
      
      $rangpremierresultat = ($page-1) * $resultatsparpage; 


/**************************************
 * REQUETE PAGINATION --> EXECUTION
 **************************************/

$sql = $sql1." LIMIT ".$rangpremierresultat.",".$resultatsparpage;
$result = NULL;
$result = $id_connex->query($sql);

if (!$result) 
    {
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
    }

/**************************************
 * NOMBRE DE RESULTATS A AFFICHER
 **************************************/

$nbre_resultats = $result->rowCount();    
    
/**************************************
 * CAS TITRE DU MAIL <-- ID MAIL 
 **************************************/

if(isset($mailing_id)){
$sql_titre = 
"SELECT
subject, mailing_id 
FROM ".$prefix."mailings
WHERE mailing_id = ".$mailing_id;

/**************************************
 * EXECUTION DE LA REQUETE TITRE
 **************************************/

$result_titre = $id_connex->query($sql_titre);

/**************************************
 * VERIFICATION DU RESULTAT
 **************************************/

if (!$result_titre) 
    {
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
    }

/**************************************
 * AFFICHAGE DU TITRE - FIN
 **************************************/

while($rowtitre = $result_titre->fetch())
	{
	$titre_aff = "Tous les clics enregistr&eacute;s depuis le message intitul&eacute; <span style=\"color:orange\">".utf8_encode($rowtitre[subject])."</span> (ID-".$rowtitre[mailing_id].").";
	}
$result_titre = NULL;
}

/**************************************
 * AFFICHAGE PAR GROUPE D'ENVOI
 **************************************/

/* if(isset($group_name)){
$titre_aff = "Tous les clics enregistr&eacute;s depuis les messages envoy&eacute;s au groupe <span style=\"color:orange\">".utf8_encode($group_name)."</span>";
} */

/**************************************
 * AFFICHAGE PAR PAGE CIBLE
 **************************************/

if(isset($title_page)){
$titre_aff = "Tous les clics enregistr&eacute;s sur la page <span style=\"color:orange\">".stripslashes(stripslashes(stripslashes($title_page)))."</span>";
}

/**************************************
 * AFFICHAGE PAR EMAIL MEMBRE
 **************************************/

if(isset($email)){
$titre_aff = "Tous les clics enregistr&eacute;s depuis l'adresse <span style=\"color:orange\">".$email."</span>";
}

/**************************************
 * TABLEAU DES RESULTATS
 **************************************/

$aff_resultat="";
while($row = $result->fetch())
{
    if($row[openingtime] == NULL)
    {
        $openingtime = "ND";
    }
    else{
        $openingtime = $row[openingtime];
    }
$aff_resultat.="<tr>";
$aff_resultat.="<td>$row[mailing_id]</td><td><a href='tracking_url_by_criteria.php?mailing_id=$row[mailing_id]' title=\"Voir tous les clics enregistrés depuis ce message\">".utf8_encode($row[subject])."</a></td><td><a href='tracking_url_by_criteria.php?group_name=".urlencode(addslashes($row[mailgroup]))."' title=\"Voir tous les clics enregistrés depuis ce groupe\">".utf8_encode($row[mailgroup])."</a></td><td>$row[started]</td><td>$openingtime</td><td>$row[clicktime]</td><td>$row[latence]</td><td><a href='tracking_url_by_criteria.php?email=$row[email]' title=\"Voir tous les clics enregistrés depuis cette adresse\">$row[email]</a></td><td><a href='tracking_url_by_criteria.php?title_page=".urlencode(addslashes($row[title_page]))."' title=\"Voir tous les clics enregistrés sur cette page\">".stripcslashes($row[title_page])."</a></td></tr>";
}

$result = NULL;

$smarty->assign_by_ref('nbre_resultats',$nbre_resultats);	
$smarty->assign_by_ref('titre_aff',$titre_aff);
$smarty->assign_by_ref('aff_resultat',$aff_resultat);
$smarty->assign_by_ref('criteria',$criteria);
$smarty->assign_by_ref('mailing_id',$mailing_id);
$smarty->assign_by_ref('email',$email);
$smarty->assign_by_ref('group_name_utf8',$group_name_utf8);
$smarty->assign_by_ref('title_page',$title_page);
$smarty->assign_by_ref('total_resultats',$total_resultats);
$smarty->assign_by_ref('page',$page);
$smarty->assign_by_ref('groupes',$groupes);
$smarty->assign_by_ref('affichagedesliensdespages',$affichagedesliensdespages);
$smarty->display('admin/mailings/tracking_url_by_criteria.tpl');
Pommo::kill();
?>
