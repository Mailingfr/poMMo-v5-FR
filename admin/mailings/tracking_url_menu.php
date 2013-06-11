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

/***********************************
CONDITION D'AFFICHAGE DES RESULTATS 
************************************/

$sql = 
"SELECT DISTINCT mailing_id, title_page 
    FROM ".$prefix."clickedurl
    ORDER BY mailing_id ASC
";

//echo $sql;

$result = $id_connex->query($sql);

if (!$result) 
    {
        exit('<p><strong>Erreur: Votre requête comporte des erreurs... Revenez sur la <a href="tracking_menu.php" title="Statistiques/Tracking">page principale des statistiques</a> et précisez votre requête.</strong><br /><br /> (' . mysql_error() .
        ')</p>');
    }

// Nombre de résultats
    
$nbre_lignes = $result->rowCount();

if ($nbre_lignes < 1)
    {
        $titre_aff = "Aucun résultat à afficher";        
    }

if ($nbre_lignes >= 1)
    {

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
    $listegroupes[] = "<option value=\"".utf8_encode($arraygroupes[mailgroup])."\">".$arraygroupes[mailgroup]."</option>";
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
              $nbpages = 1;
              $messagesparpage = $nbre_lignes;
          }
          else
          {
              $nbpages = ceil($nbre_lignes/$messagesparpage);
          }
        }
      else
        {
          $messagesparpage = 25;
          $nbpages = ceil($nbre_lignes/$messagesparpage);
        }
      
/*** ETAPE 1 - FABRICATION DES LIENS DES PAGES ***/
      
      function AffichageDesLiensDesPages($npages,$messagesparpage)
      {
          for($i=1; $i<=$npages; $i++)
        {
          $arrayliensdespages[] = '<a href="tracking_url_menu.php?messagesparpage='.$messagesparpage.'&amp;page='.$i.'">'.$i.'</a>&nbsp;&nbsp;&nbsp;';
        }
        
/*** ON PLACE TOUS LES LIENS RETOURNÉS PAR LA BOUCLE FOR DANS UN ARRAY. ***/
        
          return $arrayliensdespages; 
          
      }

/*** LA FONCTION RETOURNE L'ARRAY CONTENANT LES LIENS DES PAGES. ***/     
      
      $liensdespages = AffichageDesLiensDesPages($nbpages,$messagesparpage); 
      
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
 * DEBUT FABRICATION DE LA REQUETE *
*************************************************/

      while ($row=$result->fetch())
        {
           $mailingid[]=$row[mailing_id];
           $titlepage[]=addslashes($row[title_page]);
           $donnees_initiales = array_map(create_function('$idmail, $pagetitle', 'return "SELECT c.mailing_id, table_mailing_subject, table_mailing_mailgroup, table_mailing_sent, DATE_FORMAT(table_mailing_started, \'%d/%m/%Y %Hh%imn%ss\') AS started, COUNT(DISTINCT(c.subscriber_id)) AS clics_uniques, ROUND(((COUNT(DISTINCT(o.subscriber_id))/table_mailing_sent)*100),2) AS taux_ouvertures, ROUND(((COUNT(DISTINCT(c.subscriber_id))/table_mailing_sent)*100),2) AS taux_clics, c.title_page FROM table_clickedurl AS c INNER JOIN (SELECT subscriber_id, MIN(atime) AS minatime FROM table_clickedurl WHERE mailing_id = ".$idmail." AND title_page LIKE \"".$pagetitle."\" GROUP BY subscriber_id) AS z ON c.atime = z.minatime AND c.subscriber_id = z.subscriber_id INNER JOIN table_subscribers ON (c.subscriber_id = table_subscriber_id) INNER JOIN table_mailings ON (table_mailing_id = c.mailing_id) LEFT JOIN table_opentracking AS o ON (c.mailing_id = o.mailing_id) UNION ";'), array_values($mailingid), array_values($titlepage));
        }
   
/* 1) Les résultats de la 1ère requete (cf. ligne 33) sont récupérés en 2 arrays numérotés distincts $mailingid[] et $titlepage[].
 * 2) La fonction array_map(create_function('$idmail, $pagetitle', 'return "[texte-1...] $idmail [texte-2...] $pagetitle [texte-3]";'), array_values($mailingid), array_values($titlepage)) assemble par ligne, dans un texte, les valeurs des arrays $mailingid et $titlepage.
 * 3) Les lignes de texte sont groupées par IMPLODE pour former une 2nde requete.
 * 4) On retranche par substr (-7) le texte 'UNION ' à la fin de la requete brute et on y rajoute l'instruction SQL 'ORDER BY'.
 * 5) Enfin dans $requetefinale, les mots-clés sont remplacés par les noms réels des tables et champs à l'aide de str_replace.
 */
   
   $requetebrute = implode($donnees_initiales);
   $requeteintermediaire1 = substr($requetebrute, 0, -7);
   $requeteintermediaire2 = $requeteintermediaire1." ORDER BY mailing_id DESC LIMIT ".$rangpremiermessage.",".$messagesparpage;    
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
   
   // Pour vérifier la requete, décommenter la ligne ci-dessous.
   // print $requetefinale;
   // La 2nde requete est fin prete: nous allons maintenant l'exécuter...
   
   $result = NULL;   
   $allclicktracking = $id_connex->query($requetefinale);
   
    if (!$allclicktracking) 
        {
            exit('<p><strong>Erreur2: Votre requête comporte des erreurs... Revenez sur la <a href="tracking_menu.php" title="Statistiques/Tracking">page principale des statistiques</a> et précisez votre requête.</strong><br /><br /> (' . mysql_error() .
')</p>');
        }

   $nbre_resultats = $allclicktracking->rowCount();

    if ($nbre_resultats < 1)
        {
            $titre_aff = "Aucun résultat à afficher";
        }

    if ($nbre_resultats >= 1)
        {
            $titre_aff = "Tableau récapitulatif des pages consultées par campagne";
            $aff_resultat="";
            while($row2 = $allclicktracking->fetch())
                {
                    $titredelapage = addslashes($row2[title_page]);
                    if($row2[taux_ouvertures] == 0)
                    {
                        $taux_ouvertures = "ND";
                    }
                    else {
                        $taux_ouvertures = $row2[taux_ouvertures]."%";
                    }
                    $aff_resultat.="<tr>";
                    $aff_resultat.="
                    <td style=\"text-align:center;\">$row2[mailing_id]<br /><input name=\"checkbox[]\" type=\"checkbox\" value=\"mailing_id=$row2[mailing_id] AND title_page LIKE '$titredelapage'\" /></td>
                    <td>".$row2[subject]."</td>
                    <td>$row2[started]</td>
                    <td>".$row2[mailgroup]."</td>
                    <td>$row2[sent]</td>
                    <td>$row2[clics_uniques]</td>
                    <td>$taux_ouvertures</td>
                    <td>$row2[taux_clics]%</td>
                    <td><a href='tracking_url_datas.php?title_page=".urlencode(addslashes($row2[title_page]))."&amp;mailing_id=$row2[mailing_id]&amp;visitesparcampagne=$row2[clics_uniques]' title=\"Voir les détails de cette campagne\">".stripcslashes($row2[title_page])."</a></td>
                    </tr>";
                }
            // print_r (array_keys($idligne));
        }
    
    /* Initialisation de la requete de suppression de lignes */
    
    if (isset($_POST['supprimer']))
        {
           $checkbox = $_POST['checkbox'];
           for ($i=0; $i<count($checkbox); $i++)
             {
               $requete_supprimer = "DELETE FROM ".$prefix."clickedurl WHERE ".$checkbox[$i];
               $resultat_suppression = $id_connex->query($requete_supprimer);
             }
             
             if ($resultat_suppression)
                {
                    echo "<meta http-equiv=\"refresh\" content=\"0;URL=tracking_url_menu.php\">";
                }
        }
    }
    
    /* Si la requete réussit, revenir sur la meme page... */


$smarty->assign_by_ref('nbre_lignes',$nbre_lignes);    
$smarty->assign_by_ref('nbre_resultats',$nbre_resultats);
$smarty->assign_by_ref('titre_aff',$titre_aff);
$smarty->assign_by_ref('aff_resultat',$aff_resultat);
$smarty->assign_by_ref('page',$page);
$smarty->assign_by_ref('groupes',$groupes);
$smarty->assign_by_ref('affichagedesliensdespages',$affichagedesliensdespages);
$smarty->display('admin/mailings/tracking_url_menu.tpl');
Pommo::kill();
?>
