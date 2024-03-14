<?php
/**
 * @package     VikRentItems
 * @subpackage  mod_vikrentitems_items
 * @author      Alessio Gaggii - e4j srl - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2020 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once (dirname(__FILE__) . DS . 'helper.php');

ModVikRentItemsItemsHelper::importVriLib();

$params->def('numb', 4);
$params->def('query', 'price');
$params->def('order', 'asc');
$params->def('catid', 0);
$params->def('querycat', 'price');
$params->def('currency', '&euro;');
$params->def('showcatname', 1);
$showcatname = intval($params->get('showcatname')) == 1 ? true : false;

// get widget id
$randid = str_replace('mod_vikrentitems_items-', '', $params->get('widget_id', rand(1, 999)));
// get widget base URL
$baseurl = VRI_MODULES_URI;

$items = ModVikRentItemsItemsHelper::getItems($params);
$items = ModVikRentItemsItemsHelper::limitRes($items, $params);

require JModuleHelper::getLayoutPath('mod_vikrentitems_items', $params->get('layout', 'default'));
