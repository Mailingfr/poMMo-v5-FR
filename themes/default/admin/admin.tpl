{include file="inc/admin.header.tpl"}

<h2>{t}Admin Menu{/t}</h2>

<div id="language" class="right">
<form method="POST" action="" id="language">
<select name="lang" onChange="this.form.submit();">
<option value="en">English (en)</option>
<option value="en-uk" {if $lang == 'en-uk'}SELECTED{/if}>british english (en-uk)</option>
<option value="bg" {if $lang == 'bg'}SELECTED{/if}>български (bg)</option>
<option value="da" {if $lang == 'da'}SELECTED{/if}>dansk (da)</option>
<option value="de" {if $lang == 'de'}SELECTED{/if}>deutsch (de)</option>
<option value="es" {if $lang == 'es'}SELECTED{/if}>español (es)</option>
<option value="fr" {if $lang == 'fr'}SELECTED{/if}>français (fr)</option>
<option value="it" {if $lang == 'it'}SELECTED{/if}>italiano (it)</option>
<option value="nl" {if $lang == 'nl'}SELECTED{/if}>nederlands (nl)</option>
<option value="pl" {if $lang == 'pl'}SELECTED{/if}>polski (pl)</option>
<option value="pt" {if $lang == 'pt'}SELECTED{/if}>português (pt)</option>
<option value="pt-br" {if $lang == 'pt-br'}SELECTED{/if}>brasil português (pt-br)</option>
<option value="ro" {if $lang == 'ro'}SELECTED{/if}>română (ro)</option>
<option value="ru" {if $lang == 'ru'}SELECTED{/if}>русский язык (ru)</option>
</select>
</form>
</div>

{include file="inc/messages.tpl"}

<div id="boxMenu">

<div><a href="{$url.base}admin/mailings/admin_mailings.php"><img src="{$url.theme.shared}images/icons/mailing.png" alt="envelope icon" class="navimage" /> {t}Mailings{/t}</a> - {t}Send mailings to the entire list or to a subset of subscribers. Mailing status and history can also be viewed from here.{/t}</div>

<div><a href="{$url.base}admin/subscribers/admin_subscribers.php"><img src="{$url.theme.shared}images/icons/subscribers.png" alt="people icon" class="navimage" /> {t}Subscribers{/t}</a> - {t}Here you can list, add, delete, import, export, and update your subscribers. You can also create groups (subsets) of your subsribers from here.{/t}</div>

<div><a href="{$url.base}admin/setup/admin_setup.php"><img src="{$url.theme.shared}images/icons/settings.png" alt="hammer and screw icon" class="navimage" /> {t}Setup{/t}</a> - {t}This area allows you to configure poMMo. Set mailing list parameters, choose the information you'd like to collect from subscribers, and generate subscription forms from here.{/t}</div>

<br />
<p>
{t}Pour aller plus loin dans l'apprentissage de poMMo, lisez ceci{/t}: <br />
<a href="http://www.mailingfr.com/apprendre-pommo/" title="Gérer soi-même sa liste avec poMMo">Gérer soi-même sa liste avec poMMo</a>
</p>

</div>


{include file="inc/admin.footer.tpl"}