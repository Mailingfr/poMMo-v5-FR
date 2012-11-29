{capture name=head}
<link type="text/css" rel="stylesheet" href="{$url.theme.shared}css/table.css" />
{/capture}
{include file="inc/admin.header.tpl" sidebar='off'}

<div style="text-align:right;"><br /><a href="tracking_menu.php">Retour &agrave; la rubrique-parent</a></div>
<h2>Statistiques d'ouverture des courriers</h2>

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
<td class="style1"><b>Email</b></td>
<td class="style1"><b>Heure d'ouverture</b></td>
<td class="style1"><b>Objet du message</b></td>
<td class="style1"><b>Heure d'envoi</b></td>
<td class="style1"><b>Groupe</b></td>
</tr>
{$aff_resultat}
</table>


{include file="inc/admin.footer.tpl"}
