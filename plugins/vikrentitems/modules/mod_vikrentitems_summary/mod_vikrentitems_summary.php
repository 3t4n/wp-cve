<?php
/**
 * @package     VikRentItems
 * @subpackage  mod_vikrentitems_summary
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once (dirname(__FILE__) . DS . 'helper.php');

$params->def('showgotosumm', 1);
$params->def('showdates', 0);

ModVikrentitemsSummaryHelper::importVriLib();
ModVikrentitemsSummaryHelper::loadFontAwesome();

// get widget id
$randid = str_replace('mod_vikrentitems_summary-', '', $params->get('widget_id', rand(1, 999)));
// get widget base URL
$baseurl = VRI_MODULES_URI;

$document = JFactory::getDocument();
$document->addStyleSheet($baseurl . 'modules/mod_vikrentitems_summary/mod_vikrentitems_summary.css');

$vrisessioncart = ModVikrentitemsSummaryHelper::getVRISessionCart();
$currencysymb = ModVikrentitemsSummaryHelper::getCurrencySymb();

require(JModuleHelper::getLayoutPath('mod_vikrentitems_summary', $params->get('layout', 'default')));
