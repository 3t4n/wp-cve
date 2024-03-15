<?php
/**
 * @package Unite Gallery for Joomla 1.7-3.5
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


	$filepathItemSettings = GlobalsUG::$pathSettings."item_settings.xml";

	$settingsItem = new UniteSettingsAdvancedUG();
	$settingsItem->loadXMLFile($filepathItemSettings);
	
	
?>
