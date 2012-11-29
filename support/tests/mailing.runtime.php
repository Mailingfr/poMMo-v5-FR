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

set_time_limit(0);

$code = PommoHelper::makeCode();

if(!PommoMailCtl::spawn($pommo->_baseUrl.'support/tests/mailing.runtime2.php?code='.$code)) 
	Pommo::kill('Echec du d&eacute;clenchement du m&eacute;canisme. Vous devez corriger ce probl&egrave;me pour que poMMo puisse envoyer des e-mails.');

echo 'Temps d\'ex&eacute;cution initial : '.ini_get('max_execution_time').' secondes <br>';
echo '<br/> Cette op&eacute;ration dure en moyenne 90 secondes. Ensuite si tout se passe bien, la page affichera "TEST REUSSI". Au cas o&ugrave; cette indication n\'appara&icirc;t pas, alors le d&eacute;lai maximal d\'ex&eacute;cution du script doit &ecirc;tre renseign&eacute; avec une valeur num&eacute;rique proche du second d&eacute;lai op&eacute;rationnel le plus &eacute;lev&eacute; de la liste ci-apr&egrave;s.';

// This test takes at least 90 seconds. Upon completetion "SUCCESS" will be printed. If you do not see "SUCCESS", the max runtime should be set near the second highest "reported working" value.

echo '<hr>';
echo '<b>Liste des d&eacute;lais op&eacute;rationnels</b><br />';
ob_flush(); flush();

sleep(5);

if (!is_file($pommo->_workDir . '/mailing.test.php')) {
	// make sure we can write to the file
	if (!$handle = fopen($pommo->_workDir . '/mailing.test.php', 'w')) 
		Pommo::kill('Impossible d\'&eacute;crire dans le fichier de test.');
	fclose($handle);
	unlink($pommo->_workDir.'/mailing.test.php');
	
	Pommo::kill('Echec du d&eacute;clenchement du m&eacute;canisme (probl&egrave;me d\'&eacute;criture au niveau du fichier de test). Merci de tester le processeur d\'envoi d\'e-mails.');
}

$die = false;
$time = 0;
while(!$die) {
	sleep(10);
	$o = PommoHelper::parseConfig($pommo->_workDir . '/mailing.test.php');
	if (!isset($o['code']) || $o['code'] != $code) {
		unlink($pommo->_workDir.'/mailing.test.php');
		Pommo::kill('Le test a &eacute;chou&eacute;. Certains codes ne correspondent pas.');	
	}
	if(!isset($o['time']) || $time >= $o['time'] || $o['time'] == 90)
		$die = true;
	$time = $o['time'];
		
	echo "$time secondes <br />";
	ob_flush(); flush();
}
unlink($pommo->_workDir.'/mailing.test.php');


if($time == 90)
	Pommo::kill('TEST REUSSI');

Pommo::kill('ECHEC -- Votre serveur (ou une application tierce) refuse l\'ex&eacute;cution de ce test PHP. Il se peut que les envois soient suspendus. Si vous rencontrez des probl&egrave;mes li&eacute;s &agrave; la suspension des envois, essayez de renseigner le d&eacute;lai d\'ex&eacute;cution du script avec une valeur inf&eacute;rieure ou &eacute;gale &agrave; '.($time-10).'');

// Your webserver or a 3rd party tool is force terminating PHP. Mailings may freeze. If you are having problems with frozen mailings, try setting the Mailing Runtime Value to... 