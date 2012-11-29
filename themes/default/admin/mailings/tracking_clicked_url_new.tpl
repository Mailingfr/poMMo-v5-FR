{capture name=head}
<link type="text/css" rel="stylesheet" href="{$url.theme.shared}css/table.css" />
{/capture}
{include file="inc/admin.header.tpl" sidebar='off'}

<div style="text-align:right;"><br /><a href="tracking_menu.php">Retour &agrave; la rubrique-parent</a></div>
<h2>Statistiques des liens cliqu&eacute;s &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="datas_url_new4_PDO.php" title="Exporter au format CSV">&gt;&gt;&gt;&nbsp;Exporter toutes les données</a></h2>

<p>Affichage - {$titre_aff}</p>
<p>R&eacute;sultats affich&eacute;s : {$nbre_resultats} sur un total de {$nbre_lignes}</p>
<div style="text-align:left;">Messages par page (25 par défaut) : 
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
<td><b>Titre du mail</b></td>
<td><b>Heure d'envoi</b></td>
<td><b>Groupe</b></td>
<td><b>Envois</b></td>
<td><b>Visites uniques</b></td>
<td><b>CTR (% clics)</b></td>
<td><b>Latence (heures)</b></td>
<td><b>Pages consultées</b></td>
</tr>
{$aff_resultat}
</table>
<div><input name="supprimer" type="submit" id="supprimer" value="Supprimer les lignes sélectionnées" /></div>
</form>
<div>
Aller &agrave; la page : {$affichagedesliensdespages}
</div>


{include file="inc/admin.footer.tpl"}
