{capture name=head}
<link type="text/css" rel="stylesheet" href="{$url.theme.shared}css/table.css" />
{/capture}
{include file="inc/admin.header.tpl" sidebar='off'}

<div style="text-align:right;"><br /><a href="tracking_menu.php">Retour &agrave; la rubrique-parent</a></div>
<h2>Statistiques des liens cliqu&eacute;s </h2>

<p>Affichage - {$titre_aff}</p>
<p>Nombre de r&eacute;sultats : {$nbre_resultats}</p>
<table cellpadding="5" cellspacing="5" border=1>
<tr>
{if $field_name_val_1 != '0'}
<td><b>{$field_name_val_1}</b></td>
{/if}
{if $field_name_val_2 != '0'}
<td><b>{$field_name_val_2}</b></td>
{/if}
<td><b>Objet du message</b></td>
<td><b>Groupe</b></td>
<td><b>Heure d'envoi</b></td>
<td><b>Heure du premier clic</b></td>
<td><b>Latence (heures)</b></td>
<td><b>Email</b></td>
<td><b>Page cible</b></td>
</tr>
{$aff_resultat}
</table>


{include file="inc/admin.footer.tpl"}
