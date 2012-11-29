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
<h2>Statistiques par crit&egrave;re &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a title='Exporter au format CSV' href='datas_by_criteria.php?criteria="{$criteria}"'>&gt;&gt;&gt;&nbsp;Exporter en CSV</a></h2>

<p>{$titre_aff}</p>
<p>R&eacute;sultats affich&eacute;s : {$nbre_resultats} sur un total de {$total_resultats} &nbsp;&nbsp;&nbsp; [Page N°{$page}]</p>
<div style="text-align:left;">R&eacute;sultats par page (25 par défaut) : 
    <form style="display:inline;" method="GET" action="" accept-charset="UTF-8">
        <select name = "resultatsparpage">
            <option value=25>25</option>
            <option value=50>50</option>
            <option value=75>75</option>
            <option value=100>100</option>
            <option value="Tous">Tous</option>
        </select>
        <input type="hidden" name="mailing_id" value="{$mailing_id}" />
        <input type="hidden" name="email" value="{$email}" />
        <input type="hidden" name="group_name" value="{$group_name_utf8}" />
        <input type="hidden" name="title_page" value="{$title_page}" />
        <input type="hidden" name="charset" value="utf-8" />
        <input type="submit" value="Valider" />
    </form>
</div>
<div style="text-align:right; margin:5px;">
Aller &agrave; la page : {$affichagedesliensdespages}
</div>
<table cellpadding="5" cellspacing="5" border=1>
<tr>
<td><b>ID Mail</b></td>
<td><b>Titre du message</b></td>
<td><b>Groupe</b></td>
<td><b>Heure d'envoi</b></td>
<td><b>Heure d'ouverture</b></td>
<td><b>Heure du clic</b></td>
<td><b>Latence (heures)</b></td>
<td><b>Email</b></td>
<td><b>Page&nbsp;cible</b></td>
</tr>
{$aff_resultat}
</table>
<div style="margin-top:7px;">
Aller &agrave; la page : {$affichagedesliensdespages}
</div>

{include file="inc/admin.footer.tpl"}
