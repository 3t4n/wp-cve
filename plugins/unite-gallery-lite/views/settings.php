<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');

$headerTitle = __("General Settings", "unitegallery");

$operations = new UGOperations();

$objSettings = $operations->getGeneralSettingsObject();

$objOutput = new UniteSettingsProductUG();
$objOutput->init($objSettings);
$objOutput->setShowSaps(false);

require HelperUG::getPathTemplate("settings");

