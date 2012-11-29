{include file="inc/admin.header.tpl"}

<h2>{t}Support Page{/t}</h2>

<p><a href="support.lib.php">Voir les outils de maintenance</a></p>

<p>Version de poMMo : v5-FR (dérivé de {$version} +{$revision})</p>

<p><i>Un conseil : ne touchez &agrave; rien &agrave; moins de savoir pr&eacute;cis&eacute;ment ce que vous faites !</i></p>

<h3>NOTES DE VERSION</h3>

<pre>
Notes de version poMMo v5-FR (Décembre 2011)

* Compatible PHP5 
* Module de tracking intégré 
* Support optimisé des envois SMTP avec PHP Mailer v2.3
* Possibilité de personnalisation du titre des emails
* Interface entièrement en français

* Meilleure prise en charge des accents en UTF-8
* Enregistrement des commentaires dans la base
* Nouveau Datepicker sur le formulaire par défaut
* Jusqu'à 255 caractères dans le titre (avant: 60)
* Nombreux bugs résolus
</pre>

{include file="inc/admin.footer.tpl"}
