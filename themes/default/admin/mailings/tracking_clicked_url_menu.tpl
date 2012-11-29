{capture name=head}
<link type="text/css" rel="stylesheet" href="{$url.theme.shared}css/table.css" />
{/capture}
{include file="inc/admin.header.tpl" sidebar='off'}
<div style="text-align:right;"><br /><a href="tracking_menu.php">Retour &agrave; la rubrique-parent</a></div>
<h2>Liens cliqu&eacute;s - Donn&eacute;es disponibles</h2>
<table cellpadding="5" cellspacing="5" border=1>
<tr>
<td class="style1"><b>Objet du message</b></td>
<td class="style1"><b>Date</b></td>
</tr>
{$menu_sujet}
</table>

<table cellpadding="5" cellspacing="5" border=1>
<tr>
<td class="style1"><b>Groupes</b></td>
</tr>
{$menu_groupe}
</table>

<table cellpadding="5" cellspacing="5" border=1>
<tr>
<td class="style1"><b>Tous</b></td>
</tr>
<tr>
<td class="style1"><a href="tracking_clicked_url.php?tous=tous">Voir tous les liens cliqu&eacute;s</a></td>
</tr>
</table>


{include file="inc/admin.footer.tpl"}
