{capture name=head}
<link type="text/css" rel="stylesheet" href="{$url.theme.shared}css/table.css" />
{/capture}
{include file="inc/admin.header.tpl" sidebar='off'}

<div style="text-align:right;"><br /><a href="tracking_menu.php">Page d'accueil des statistiques</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tracking_opening_menu.php">Messages ouverts</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tracking_url_menu.php">Liens cliqués</a></div>
<div style="text-align:right; margin:7px;">
    <form method="GET" action="tracking_opening_by_criteria.php" accept-charset="UTF-8">
        <select name = "group_name">
            <option selected="selected" value="">--Statistiques par groupe--</option>
            <option value="touslesgroupes">&lt;Tous les groupes réunis&gt;</option>
            {$groupes}
        </select>
        <input type="hidden" name="charset" value="utf-8" />        
        <input type="submit" value="OK" />
    </form>
</div>
<h2>Statistiques par campagne &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a title='Exporter au format CSV' href='datas_opening_export.php?mailing_id={$mailing_id}'>&gt;&gt;&gt;&nbsp;Exporter en CSV</a></h2>
<p>{$titre_aff}</p>
<div>
<p>R&eacute;sultats affich&eacute;s : {$nbre_resultats} sur un total de {$visitesparcampagne} &nbsp;[Page N°{$page}]</p>
<div style="text-align:left;">R&eacute;sultats par page (25 par défaut) : 
    <form style="display:inline;" method="GET" action="">
        <input type="hidden" name="visitesparcampagne" value={$visitesparcampagne} />
        <select name = "messagesparpage">
            <option value=25>25</option>
            <option value=50>50</option>
            <option value=75>75</option>
            <option value=100>100</option>
            <option value="Tous">Tous</option>
        </select>
        <input type="hidden" name="mailing_id" value={$mailing_id} />
        <input type="submit" value="Valider" />
    </form>
</div>
</div>  
<div style="text-align:left; margin-top:9px; margin-bottom: 8px;">Exporter les adresses vers un groupe :</div>
<div>
    <form style="display:inline;" method="POST" action="" accept-charset="UTF-8">
        <select name = "champ">
            <option value="">--Choisir un champ--</option>
            {$champs}
        </select>
        <input type="submit" value="OK" />
    </form>
    <form style="display:inline;" method="POST" action="" accept-charset="UTF-8">
        <select name = "valeurdechamp">
            <option value="">--Choisir une valeur--</option>
            {$valeursduchamp}
        </select>
        <input type="hidden" name="idchamp" value="{$champ}" />
        <input type="hidden" name="nomduchamp" value="{$nomduchamp}" />
        <input type="submit" value="OK" />
    </form>
    <form style="display:inline;" method="POST" action="" accept-charset="UTF-8">
        <input type="hidden" name="annuler" value="1" />
        <input type="submit" value="Annuler" />
    </form>      
    <form style="display:inline;" method="POST" action="" accept-charset="UTF-8">
        <input type="hidden" name="selectchamp" value="{$idchamp}" />
        <input type="hidden" name="selectvaleur" value="{$valeurdechamp}" />
        <input type="submit" value="Exporter" />
    </form>  
</div>
        
{$processus}
        
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
<td><b>Latence (heures)</b></td>
<td><b>Email</b></td>
</tr>
{$aff_resultat}
</table>
<div style="margin-top:7px;">
Aller &agrave; la page : {$affichagedesliensdespages}
</div>

{include file="inc/admin.footer.tpl"}
