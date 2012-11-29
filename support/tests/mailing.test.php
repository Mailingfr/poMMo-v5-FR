<?php
/**
 * Copyright (C) 2005, 2006, 2007, 2008  Brice Burgess <bhb@iceburg.net>
 * 
 * This file is part of poMMo (http://www.pommo.org)
 * 
 * poMMo is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published 
 * by the Free Software Foundation; either version 2, or any later version.
 * 
 * poMMo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with program; see the file docs/LICENSE. If not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA.
 */
 
/**********************************
	INITIALIZATION METHODS
 *********************************/
define('_poMMo_support', TRUE);
require ('../../bootstrap.php');
$pommo->init();

Pommo::requireOnce($pommo->_baseDir.'inc/classes/mailctl.php');

echo 'Merci de patienter quelques secondes...';
ob_flush();
flush();

$code = PommoHelper::makeCode();

if(!PommoMailCtl::spawn($pommo->_baseUrl.'support/tests/mailing.test2.php?code='.$code,true)) 
	Pommo::kill('Echec du d&eacute;clenchement du m&eacute;canisme. Vous devez corriger ce probl&egrave;me pour que poMMo puisse envoyer des e-mails.');

sleep(6);

if (!is_file($pommo->_workDir . '/mailing.test.php')) {
	// make sure we can write to the file
	if (!$handle = fopen($pommo->_workDir . '/mailing.test.php', 'w')) 
		die('Impossible d\'&eacute;crire dans le fichier de test.');
	fclose($handle);
	unlink($pommo->_workDir.'/mailing.test.php');
	
	Pommo::kill('Echec du d&eacute;clenchement du m&eacute;canisme (probl&egrave;me d\'&eacute;criture au niveau du fichier de test). Vous devez corriger ce probl&egrave;me pour que poMMo puisse envoyer des e-mails.');
}
	
$o = PommoHelper::parseConfig($pommo->_workDir . '/mailing.test.php');
unlink($pommo->_workDir.'/mailing.test.php') or die('impossible de supprimer mailing.test.php');

if(isset($o['error']))
	Pommo::kill('UNE ERREUR A ETE TROUVEE. MERCI DE VERIFIER LE RESULTAT DE \'MAILING_TEST\' DANS LE REPERTOIRE DE TRAVAIL');

if (!isset($o['code']) || $o['code'] != $code)
	Pommo::kill('Le test a &eacute;chou&eacute;. Certains codes ne correspondent pas.');
	
if (!isset($o['spawn']) || $o['spawn'] == 0)
	Pommo::kill('D&eacute;clenchement r&eacute;ussi mais &eacute;chec des envois &agrave; r&eacute;p&eacute;tition.');

Pommo::kill('D&eacute;clenchement r&eacute;ussi. Envois &agrave; r&eacute;p&eacute;tition r&eacute;ussis. La fonction d\'envoi massif est op&eacute;rationnelle.');