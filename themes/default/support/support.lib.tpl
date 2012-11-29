{capture name=head}{* used to inject content into the HTML <head> *}
{include file="inc/ui.dialog.tpl"}
{/capture}
{include file="inc/admin.header.tpl"}

<h2>Rubrique de maintenance v0.02 de poMMo</h2>

<ul>
<li><a href="tests/file.clearWork.php" title="Vider le r&eacute;pertoire de travail" class="modal">Vider le r&eacute;pertoire de travail</a></li>
<li><a href="tests/mailing.test.php" onclick="return !window.open(this.href)">Tester le processeur d'envoi d'e-mails</a></li>
<li><a href="tests/mailing.kill.php" title="Terminer les envois en cours" class="modal">Terminer les envois en cours</a></li>
<li><a href="tests/mailing.runtime.php"  onclick="return !window.open(this.href)">Tester le d&eacute;lai maximal d'ex&eacute;cution: Max Runtime (ceci peut prendre 90 secondes)</a></li>
<li><a class="warn" href="util/db.clear.php" title="R&eacute;initialiser la base de donn&eacute;es">R&eacute;initialiser la base de donn&eacute;es (suppression de tous les comptes, groupes et champs)</a></li>
<li><a class="warn" href="util/db.subscriberClear.php" title="R&eacute;initialiser la liste de diffusion">R&eacute;initialiser la liste de diffusion (suppression de tous les comptes d'abonn&eacute;s)</a></li>
<li><a class="warn" href="util/db.sample.php" title="Utiliser des donn&eacute;es de test">Donn&eacute;es de test (r&eacute;initialisation de la base et chargement de donn&eacute;es de test)</a></li>
</ul>

{literal}
<script type="text/javascript">
$().ready(function() {
	$('a.warn').click(function() {
		var str = this.innerHTML;
		return confirm("{/literal}{t}Confirm your action.{/t}{literal}\n"+str+"?");
	});
	
	// Setup Modal Dialogs
	PommoDialog.init();
	
});
</script>
{/literal}

{capture name=dialogs}
{include file="inc/dialog.tpl" id=dialog}
{/capture}

{include file="inc/admin.footer.tpl"}