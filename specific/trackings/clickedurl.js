// JavaScript Document

// Script de récupération des cookies : http://www.commentcamarche.net/forum/affich-5743744-cookie-php-et-cookie-javascript
function getCookieVal(offset)
{
var endstr=document.cookie.indexOf (";", offset);
if (endstr==-1) endstr=document.cookie.length;
return unescape(document.cookie.substring(offset, endstr)); 
}
function LireCookie(nom)
{
var arg=nom+"=";
var alen=arg.length;
var clen=document.cookie.length;
var i=0;
while (i<clen)
{
var j=i+alen;
if (document.cookie.substring(i, j)==arg) return getCookieVal(j);
i=document.cookie.indexOf(" ",i)+1;
if (i==0) break;

}
return null; 
}

// Variables
var title_page = escape(document.location.protocol + '//' + document.location.host + document.location.pathname);
var subscriber_id = LireCookie("pommosubscriberid");
var mailing_id = LireCookie("pommomailingid");

// Si la variable subscriber_id existe alors, il s'agit d'un lien cliqué depuis un mail et on remplit donc la base
if(subscriber_id){
   document.write("<img src='"+base_url_script_clickedurl+"?subscriber_id="+subscriber_id+"&mailing_id="+mailing_id+"&title_page="+title_page+"' width='1' height='1'>");
}
