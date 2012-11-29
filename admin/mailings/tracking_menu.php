<?php
/**
 * Mod Tracking - Pommo V0.1 G.Lengy - www.artaban.fr -
 */

/**********************************
	INITIALIZATION METHODS
 *********************************/
require('../../bootstrap.php');
$pommo->init();
$logger = & $pommo->_logger;
$dbo = & $pommo->_dbo;

/**********************************
	SETUP TEMPLATE, PAGE
 *********************************/
Pommo::requireOnce($pommo->_baseDir.'inc/classes/template.php');
$smarty = new PommoTemplate();
	
//$smarty->display('admin/tracking/tracking_menu.tpl');
$smarty->display('admin/mailings/tracking_menu.tpl');
Pommo::kill();
?>