{include file="inc/admin.header.tpl" sidebar='off'}
<div style="text-align:right;"><br /><a href="tracking_menu.php">Page d'accueil des statistiques</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tracking_opening_menu.php">Messages ouverts</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tracking_url_menu.php">Liens cliqués</a></div>
<h2>{t}Statistiques/Tracking{/t}</h2>

<div id="boxMenu">

<div><a href="{$url.base}admin/mailings/tracking_opening_menu.php"><img src="{$url.theme.shared}images/icons/trackings_opened_mails.png" alt="trackings opened mails icon" class="navimage" />Voir les statistiques des messages ouverts</a> - Liste des adresses emails ayant ouvert vos messages.<br /><br />
Pour traquer les courriers ouverts, insérez le code suivant dans le corps de vos messages HTML en prenant soin de remplacer <span style="font-style:italic;">http://www.votredomaine.com/dossier_pommo</span> par l'adresse du dossier d'installation de poMMo.<br /><br />
<table style="border:solid 1px green;"><tr><td style="color:orange; background-color: black; padding:7px;">&lt;img src="<span style="color:yellow;">http://www.votredomaine.com/dossier_pommo</span>/specific/trackings/getimage.php?subscriber_id=[[!subscriber_id]]&mailing_id=[[!mailing_id]]" width="1" height="1" /&gt;</td></tr></table>
</div>	

<div><a href="{$url.base}admin/mailings/tracking_url_menu.php"><img src="{$url.theme.shared}images/icons/trackings_clicked_urls.png" alt="trackings clicked urls icon" class="navimage" />Voir les statistiques des liens cliqués</a> - Informations détaillées sur l'ensemble des liens cliqués depuis vos messages, avec de nombreuses options d'export des données.<br /><br />
Pour traquer les liens ouverts depuis vos messages, procédez en quelques étapes simples:<br /><br />

a) Composez une page de redirection en PHP (exemple: redirection.php) suivant le modèle ci-après et transférez-la sur votre site, en prenant soin de remplacer <span style="font-style:italic;">http://www.votredomaine.com/dossier_pommo</span> par l'adresse du dossier d'installation de poMMo.<br /><br />

<table style="border:solid 1px green;">
    <tr>
        <td style="color:green; background-color: black; padding:7px;">
&lt;?php <br />
$mailing_id = htmlspecialchars($_GET['mid']);<br />
$subscriber_id = htmlspecialchars($_GET['sid']);<br />
setcookie("pommosubscriberid", $subscriber_id, time() + 365*24*3600);<br />
setcookie("pommomailingid", $mailing_id, time() + 365*24*3600);<br />
?&gt;<br />
&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"&gt;<br />
&lt;head&gt;<br />
&lt;title&gt;<span style="color:yellow;">Choisissez un titre significatif pour cette page</span>&lt;/title&gt;<br />
&lt;meta http-equiv="REFRESH" content="3; URL=<span style="color:yellow;">http://mettez-ici-l-adresse-exacte-de-la-page-de-destination</span>" /&gt;<br />
&lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8" /&gt;
&lt;meta name="robots" content="noindex,follow" /&gt;<br />
&lt;/head&gt;<br />
&lt;body&gt;<br />
<span style="color:orange;">&lt;script type="text/javascript"&gt; var base_url_script_clickedurl="<span style="color:yellow;">http://www.votredomaine.com/dossier_pommo</span>/specific/trackings/clickerurl.php"; &lt;/script&gt;&lt;script type="text/javascript" src="<span style="color:yellow;">http://www.votredomaine.com/dossier_pommo</span>/specific/trackings/clickedurl.js"&gt;&lt;/script&gt;</span><br />
&lt;div style="text-align:center; border: solid 1px black; padding:7px;"&gt;
Ouverture de la page en cours &lt;br /&gt;
Merci de patienter quelques secondes...
&lt;/div&gt;<br />
&lt;/body&gt;<br />
&lt;/html&gt;
        </td>
    </tr>
</table>

<br />

b) Composez les liens dans vos messages HTML suivant l'exemple ci-dessous et en prenant soin de remplacer <span style="font-style:italic;">http://www.votredomaine.com/redirection.php</span> par l'adresse de la page de redirection précédemment créée.

<br /><br />
<table style="border:solid 1px green;"><tr><td style="color:orange; background-color: black; padding:7px;">&lt;a href="<span style="color:yellow;">http://www.votredomaine.com/redirection.php</span>?mid=[[!mailing_id]]&sid=[[!subscriber_id]]"><span style="color:yellow;">Texte du lien</span>&lt;/a&gt;</td></tr></table>

<br />

c) Optionnel : pour traquer plusieurs pages successives à partir d'un même message, insérez le script ci-dessous entre les balises &lt;body&gt; et &lt;/body&gt; de chaque page à traquer, en prenant soin de remplacer <span style="font-style:italic;">http://www.votredomaine.com/dossier_pommo</span> par l'adresse du dossier d'installation de poMMo.

<br /><br />
<table style="border:solid 1px green;"><tr><td style="color:orange; background-color: black; padding:7px;">&lt;script type="text/javascript"&gt; var base_url_script_clickedurl="<span style="color:yellow;">http://www.votredomaine.com/dossier_pommo</span>/specific/trackings/clickerurl.php"; &lt;/script&gt;&lt;script type="text/javascript" src="<span style="color:yellow;">http://www.votredomaine.com/dossier_pommo</span>/specific/trackings/clickedurl.js"&gt;&lt;/script&gt;</td></tr></table>

</div>

</div>

{include file="inc/admin.footer.tpl"}
