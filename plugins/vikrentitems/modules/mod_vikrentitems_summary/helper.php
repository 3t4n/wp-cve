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

class ModVikrentitemsSummaryHelper
{
	public static function getVRISessionCart()
	{
		$session = JFactory::getSession();
		$vrisessioncart = $session->get('vriCart', '');
		$vrisessioncart = is_array($vrisessioncart) ? $vrisessioncart : array();
		return $vrisessioncart;
	}
	
	public static function getDateFormat()
	{
		$session = JFactory::getSession();
		$sval = $session->get('getDateFormat', '');
		if (!empty($sval)) {
			$dateformat = $sval;
		} else {
			$dbo = JFactory::getDBO();
			$q="SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='dateformat';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$df = $dbo->loadAssocList();
				$dateformat = $df[0]['setting'];
			} else {
				$dateformat = "%d/%m/%Y";
			}
		}
		return $dateformat;
	}
	
	public static function getCurrencySymb()
	{
		$session = JFactory::getSession();
		$sval = $session->get('getCurrencySymb', '');
		if (!empty($sval)) {
			return $sval;
		} else {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='currencysymb';";
			$dbo->setQuery($q);
			$dbo->execute();
			$ft = $dbo->loadAssocList();
			$session->set('getCurrencySymb', $ft[0]['setting']);
			return $ft[0]['setting'];
		}
	}

	public static function getTimeFormat()
	{
		$session = JFactory::getSession();
		$sval = $session->get('getTimeFormat', '');
		if (!empty($sval)) {
			return $sval;
		} else {
			$dbo = JFactory::getDBO();
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='timeformat';";
			$dbo->setQuery($q);
			$dbo->execute();
			$s = $dbo->loadAssocList();
			$session->set('getTimeFormat', $s[0]['setting']);
			return $s[0]['setting'];
		}
	}

	/**
	 * This method is compatible with both Joomla and WordPress.
	 * 
	 * @since 	1.7
	 */
	public static function importVriLib()
	{
		if (class_exists('VikRentItems')) {
			return;
		}
		if (defined('ABSPATH') && is_file(VRI_SITE_PATH . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php')) {
			require_once(VRI_SITE_PATH . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php');
		} elseif (defined('JPATH_SITE') && is_file(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_vikrentitems' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php')) {
			require_once(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_vikrentitems' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php');
		}
	}

	public static function numberFormat($num)
	{
		return VikRentItems::numberFormat($num);
	}

	public static function getTranslator()
	{
		return VikRentItems::getTranslator();
	}

	public static function loadFontAwesome($force_load = false)
	{
		return VikRentItems::loadFontAwesome($force_load);
	}
	
}
