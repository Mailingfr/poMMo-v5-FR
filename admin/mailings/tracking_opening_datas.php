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
$visitesparcampagne = $_GET['visitesparcampagne'];

/******************************************
AFFICHAGE DES RACCOURCIS VERS LES GROUPES 
*******************************************/

$requetegroupe = 
"
SELECT DISTINCT mailgroup 
FROM ".$prefix."mailings AS m 
JOIN ".$prefix."opentracking AS o 
ON m.mailing_id = o.mailing_id 
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


/*************************************************** 
DEBUT CALCUL NOMBRE DE PAGES ET AFFICHAGE DES LIENS 
****************************************************/

      if (isset($_GET['messagesparpage']))
        {
          $messagesparpage = $_GET['messagesparpage'];
          if ($messagesparpage == "Tous" OR $messagesparpage < 1)
          {
              $messagesparpage = $visitesparcampagne;
              $nbpages = 1;
          }
          else
          {
              $nbpages = ceil($visitesparcampagne/$messagesparpage);
          }
        }
      else
        {
          $messagesparpage = 25;
          $nbpages = ceil($visitesparcampagne/$messagesparpage);
        }
      
/*** ETAPE 1 - FONCTION DE FABRICATION DES LIENS DES PAGES ***/
      
      function AffichageDesLiensDesPages($npages,$messagesparpage,$mailing_id,$visitesparcampagne)
      {
          for($i=1; $i<=$npages; $i++)
        {
          $arrayliensdespages[] = '<a href="tracking_opening_datas.php?visitesparcampagne='.$visitesparcampagne.'&amp;messagesparpage='.$messagesparpage.'&amp;page='.$i.'&amp;mailing_id='.$mailing_id.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;';
        }
        
/*** LA FONCTION PLACE TOUS LES LIENS RETOURNÉS PAR LA BOUCLE FOR DANS UN ARRAY. ***/
        
          return $arrayliensdespages; 
          
      }

/*** EXECUTION --> LA FONCTION RETOURNE L'ARRAY CONTENANT LES LIENS DES PAGES. ***/     
      
      $liensdespages = AffichageDesLiensDesPages($nbpages,$messagesparpage,$mailing_id,$visitesparcampagne);
      
/*** EXÉCUTION DE LA FONCTION POUR RÉCUPÉRER LES LIENS DES PAGES. ***/
/*** LES LIENS SONT RÉAFFICHÉS L'UN APRÈS L'AUTRE SOUS FORME DE TEXTE. ***/
      
      if($liensdespages)
      {
              $affichagedesliensdespages = implode("",$liensdespages);   
      }
         
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
      
      $rangpremiermessage = ($page-1) * $messagesparpage; 

/************************************************* 
FIN CALCUL NOMBRE DE PAGES ET AFFICHAGE DES LIENS
 * DEBUT FABRICATION DE LA REQUETE PRINCIPALE *
*************************************************/
      
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
";

   // echo $requetebrute;
   
   $requetegenerale = str_replace(table_subscribers, $prefix."subscribers", $requetebrute);
   $requetegenerale = str_replace(table_subscriber_id, $prefix."subscribers.subscriber_id", $requetegenerale);
   $requetegenerale = str_replace(table_subscriber_email, $prefix."subscribers.email", $requetegenerale);
   $requetegenerale = str_replace(table_mailings, $prefix."mailings", $requetegenerale);
   $requetegenerale = str_replace(table_mailing_id, $prefix."mailings.mailing_id", $requetegenerale);
   $requetegenerale = str_replace(table_mailing_started, $prefix."mailings.started", $requetegenerale);
   $requetegenerale = str_replace(table_mailing_sent, $prefix."mailings.sent", $requetegenerale);
   $requetegenerale = str_replace(table_mailing_subject, $prefix."mailings.subject", $requetegenerale);
   $requetegenerale = str_replace(table_mailing_mailgroup, $prefix."mailings.mailgroup", $requetegenerale);
   $requetegenerale = str_replace(table_opentracking, $prefix."opentracking", $requetegenerale);   
   $requetefinale = $requetegenerale." ORDER BY latence DESC LIMIT ".$rangpremiermessage.",".$messagesparpage."";

   // Pour vérifier la requete (générale/finale), décommenter la ligne ci-dessous.
   // print_r($requetefinale);
   // La 2nde requete est fin prete: nous allons maintenant l'exécuter...
   
   $result = NULL;   
   $result = $id_connex->query($requetefinale);
   
    if (!$result) 
        {
            exit('<p><strong>Erreur: Votre requête comporte des erreurs... Revenez sur la <a href="tracking_menu.php" title="Statistiques/Tracking">page principale des statistiques</a> et précisez votre requête.</strong><br /><br /> (' . mysql_error() .
')</p>');
        }

   $nbre_resultats = $result->rowCount();

    if ($nbre_resultats < 1)
        {
            $titre_aff = "Aucun résultat à afficher";
        }

    if ($nbre_resultats >= 1)
        {
            $aff_resultat="";
            while($row = $result->fetch())
                {               
                    $aff_resultat.="<tr>";
                    $aff_resultat.="
                    <td style=\"text-align:center;\">$row[mailing_id]</td>
                    <td><span style=\"color:none;\">".$row[subject]."</span></td>
                    <td><a href='tracking_opening_by_criteria.php?group_name=".urlencode(addslashes($row[mailgroup]))."' title=\"Voir les messages ouverts depuis ce groupe\">".$row[mailgroup]."</a></td>
                    <td>$row[started]</td>
                    <td>$row[dateouverture]</td>
                    <td>$row[latence]</td>
                    <td><a href='tracking_opening_by_criteria.php?email=$row[email]' title=\"Voir les messages ouverts depuis cette adresse\">$row[email]</td>
                    </tr>";
                    $titre_aff = "Statistiques d'ouverture du message intitul&eacute; <span style=\"color:orange\">".$row[subject]."</span> (ID-".$row[mailing_id].").";                    
                }
           
        }
    
    /*************************************************** 
    PROCEDURE D'EXPORT VERS UN GROUPE 
    ****************************************************/
    
        /* 1- Affichage de la liste des champs passifs */
         
           $requetechamp = "SELECT field_id, field_name FROM ".$prefix."fields WHERE field_active = 'off'";
           $resultatrequetechamp = $id_connex->query($requetechamp);
           $nombrechamps = $resultatrequetechamp->rowCount();
         
           while($arraychamps = $resultatrequetechamp->fetch())
           {
              $listechamps[] = "<option value=\"".$arraychamps[field_id]."\">".utf8_encode($arraychamps[field_name])."</option>";
           }
         
           if($listechamps)
           {
               $champs = implode("",$listechamps);
           }
           $processus = "<div style=\"margin-top:7px; margin-bottom:7px;\">(Pour exporter vers un groupe: 1- Sélectionner un champ 2- Choisir une valeur de champ 3- Cliquer sur Exporter)</div>";
         
        /* 2- Affichage des valeurs de champs lorsqu'un champ est sélectionné - Transmission de variables par $_POST */
           
        if(isset($_POST['champ']) and $_POST['champ'] != NULL)
        {
           $champ = $_POST['champ'];
           $requetevaleurdechamp = "SELECT field_array, field_name FROM ".$prefix."fields WHERE field_id = '".$champ."'";
           $resultatrequetevaleurdechamp = $id_connex->query($requetevaleurdechamp);
           while($arrayvaleursdechamps = $resultatrequetevaleurdechamp->fetch())
           {
               $valeursdechamps = unserialize(utf8_encode($arrayvaleursdechamps[field_array]));
               $nomduchamp = utf8_encode($arrayvaleursdechamps[field_name]);
           }
           for($z=0; $z<count($valeursdechamps); $z++)
           {
               $listevaleursdechamps[] = "<option value=\"".$valeursdechamps[$z]."\">".$valeursdechamps[$z]."</option>";
           }
           if(isset($listevaleursdechamps))
           {
               $valeursduchamp = implode("",$listevaleursdechamps);
           }
           $processus = "<div style=\"margin-top:7px; margin-bottom:7px;\"><span style=\"color:green\">1- Champ sélectionné = [".$nomduchamp."] : OK </span><span style=\"color:red\">&gt;&gt;&gt; 2- Choisir une valeur de champ &gt;&gt;&gt; 3- Cliquer sur Exporter</span></div>";
        }
           
        /* 3- Insertion des données dans une table intermédiaire lorsque la valeur de champ est définie */
        
        if(isset($_POST['valeurdechamp']) and $_POST['valeurdechamp'] != NULL)
        {
           $valeurdechamp = utf8_decode($_POST['valeurdechamp']);
           $idchamp = $_POST['idchamp'];
           $nomduchamp = $_POST['nomduchamp'];
           
        /* 3.1- Afin d'éviter les doublons, on vide la table intermédiaire avant d'introduire les données */
           
           $supprimer_donnees_intermediaires = "DELETE FROM ".$prefix."insertions";
           $suppression_donnees_intermediaires = $id_connex->query($supprimer_donnees_intermediaires);            
           
        /* 3.2- On insère les données */
           
           $preinsertion = $id_connex->query($requetegenerale);        
           while($arraysubscriberid = $preinsertion->fetch())
           {
               $arrayrequeteinsertion[] = "INSERT INTO ".$prefix."insertions VALUES ('','".$idchamp."','".$arraysubscriberid[subscriber_id]."','".$valeurdechamp."')";
           }
           $requeteinsertion = implode(";", $arrayrequeteinsertion);
           $insertions = $id_connex->query($requeteinsertion);
           $processus = "<div style=\"margin-top:7px; margin-bottom:7px;\"><span style=\"color:green\">1- Champ sélectionné = [".$nomduchamp."] : OK &gt;&gt;&gt; 2- Valeur de champ = [".utf8_encode($valeurdechamp)."] : OK </span><span style=\"color:red\">&gt;&gt;&gt; 3- Cliquer sur Exporter</span></div>";
        }
        
        /* 4- Export des données de la table insertions vers la table subscriber_data 
         * Règle d'enregistrement : un id membre ne doit pas apparaitre plus d'une fois au niveau d'un champ donné
         */
        
        if(isset($_POST['selectvaleur']) and $_POST['selectvaleur'] != NULL)
        {
           $selectvaleur = utf8_decode($_POST['selectvaleur']);
           $selectchamp = $_POST['selectchamp'];
           $requeteexportgroupe = "INSERT INTO ".$prefix."subscriber_data SELECT DISTINCT '', field_id, subscriber_id, value FROM ".$prefix."insertions WHERE subscriber_id NOT IN (SELECT subscriber_id FROM ".$prefix."subscriber_data WHERE field_id = '".$selectchamp."')";    
           $exportgroupe = $id_connex->query($requeteexportgroupe);
           $supprimer_donnees_intermediaires = "DELETE FROM ".$prefix."insertions";
           $suppression_donnees_intermediaires = $id_connex->query($supprimer_donnees_intermediaires); 
           $processus = "<div style=\"margin-top:7px; margin-bottom:7px;\"><span style=\"color:green; font-weight:bold;\">Opération terminée avec succès !</span></div>";
         }
         
        /* 5- Procédure d'annulation */
         
         if(isset($_POST['annuler']) and $_POST['annuler'] == 1)
         {
           $supprimer_donnees_intermediaires = "DELETE FROM ".$prefix."insertions";
           $suppression_donnees_intermediaires = $id_connex->query($supprimer_donnees_intermediaires);
           $processus = "<div style=\"margin-top:7px; margin-bottom:7px;\"><span style=\"color:green; font-weight:bold;\">Annulation de tous les processus en cours : OK.</span></div>";
         }
         
    }
    
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_opening_menu.php\">";
    }
    
    // Si les variables $_GET ne sont pas définies, revenir sur la rubrique principale...


$smarty->assign_by_ref('visitesparcampagne',$visitesparcampagne);    
$smarty->assign_by_ref('nbre_resultats',$nbre_resultats);	
$smarty->assign_by_ref('titre_aff',$titre_aff);
$smarty->assign_by_ref('aff_resultat',$aff_resultat);
$smarty->assign_by_ref('affichagedesliensdespages',$affichagedesliensdespages);
$smarty->assign_by_ref('urlpage',$urlpage);
$smarty->assign_by_ref('title_page',$title_page);
$smarty->assign_by_ref('mailing_id',$mailing_id);
$smarty->assign_by_ref('page',$page);
$smarty->assign_by_ref('groupes',$groupes);
$smarty->assign_by_ref('champs',$champs);
$smarty->assign_by_ref('champ',$champ);
$smarty->assign_by_ref('idchamp',$idchamp);
$smarty->assign_by_ref('nomduchamp',$nomduchamp);
$smarty->assign_by_ref('valeurdechamp',$valeurdechamp);
$smarty->assign_by_ref('valeursduchamp',$valeursduchamp);
$smarty->assign_by_ref('processus',$processus);
$smarty->display('admin/mailings/tracking_opening_datas.tpl');
Pommo::kill();
?>
