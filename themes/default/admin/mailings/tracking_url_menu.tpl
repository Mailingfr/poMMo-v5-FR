{capture name=head}
<link type="text/css" rel="stylesheet" href="{$url.theme.shared}css/table.css" />
{/capture}
{include file="inc/admin.header.tpl" sidebar='off'}

<div style="text-align:right;"><br /><a href="tracking_menu.php">Page d'accueil des statistiques</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tracking_opening_menu.php">Messages ouverts</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tracking_url_menu.php">Liens cliqués</a></div>
<div style="text-align:right; margin:7px;">
    <form method="GET" action="tracking_url_by_criteria.php" accept-charset="UTF-8">
        <select name = "group_name">
            <option selected="selected" value="">--Statistiques par groupe--</option>
            <option value="touslesgroupes">&lt;Tous les groupes réunis&gt;</option>
            {$groupes}
        </select>
        <input type="hidden" name="charset" value="utf-8" />        
        <input type="submit" value="OK" />
    </form>
</div>
<h2>Liens cliqu&eacute;s : Vue d'ensemble &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="all_datas_export.php" title="Exporter au format CSV">&gt;&gt;&gt;&nbsp;Exporter toutes les donn&eacute;es en CSV</a></h2>

<p style="color:orange;">{$titre_aff}</p>
<p>R&eacute;sultats affich&eacute;s : {$nbre_resultats} sur un total de {$nbre_lignes} &nbsp;[Page N°{$page}]</p>
<div style="text-align:left;">R&eacute;sultats par page (25 par défaut) : 
    <form style="display:inline;" method="GET" action="">
        <select name = "messagesparpage">
            <option value=25>25</option>
            <option value=50>50</option>
            <option value=75>75</option>
            <option value=100>100</option>
            <option value="Tous">Tous</option>
        </select>
        <input type="submit" value="Valider" />
    </form>
</div>
<div style="text-align:right; margin:5px;">
Aller &agrave; la page : {$affichagedesliensdespages}
</div>
<form name="gerer_les_resultats" method="post" action="">
<table cellpadding="5" cellspacing="5" border=1>
<tr>
<td><b>ID mail</b></td>
<td><b>Titre du message</b></td>
<td><b>Heure d'envoi</b></td>
<td><b>Groupe</b></td>
<td><b>Envois</b></td>
<td><b>Clics uniques</b></td>
<td><b>CTR-1 (% ouvertures)</b></td>
<td><b>CTR-2 (%&nbsp;clics)</b></td>
<td><b>Latence (heures)</b></td>
<td><b>Pages consultées</b></td>
</tr>
{$aff_resultat}
</table>
<div><input name="supprimer" type="submit" id="supprimer" value="Supprimer les campagnes sélectionnées" /></div>
</form>
<div>
Aller &agrave; la page : {$affichagedesliensdespages}
</div>


{include file="inc/admin.footer.tpl"}
