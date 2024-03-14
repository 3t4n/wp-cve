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

class ModVikRentItemsItemsHelper
{
	public static function getItems($params)
	{
		$dbo = JFactory::getDbo();
		$vri_tn = self::getTranslator();
		$showcatname = intval($params->get('showcatname')) == 1 ? true : false;
		$items = array();
		$query = $params->get('query');
		if ($query == 'price') {
			//simple order by price asc
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `avail`='1';";
			//echo $q;
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$items=$dbo->loadAssocList();
				$vri_tn->translateContents($items, '#__vikrentitems_items');
				foreach ($items as $k => $c) {
					if ($showcatname) $items[$k]['catname'] = self::getCategoryName($c['idcat']);
					if (strlen($c['startfrom']) > 0 && $c['startfrom'] > 0.00) {
						$items[$k]['cost'] = $c['startfrom'];
					} else {
						$q = "SELECT `id`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`='".$c['id']."' AND `days`='1' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 1) {
							$tar = $dbo->loadAssocList();
							$items[$k]['cost'] = $tar[0]['cost'];
						} else {
							$q = "SELECT `id`,`days`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`='".$c['id']."' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() == 1) {
								$tar = $dbo->loadAssocList();
								$items[$k]['cost'] = ($tar[0]['cost'] / $tar[0]['days']);
							} else {
								$items[$k]['cost'] = 0;
							}
						}
					}
				}
			}
			$items = self::sortItemsByPrice($items, $params);
		} elseif ($query == 'name') {
			//order by name
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `avail`='1' ORDER BY `#__vikrentitems_items`.`name` ".strtoupper($params->get('order'))." LIMIT ".$params->get('numb').";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$items=$dbo->loadAssocList();
				$vri_tn->translateContents($items, '#__vikrentitems_items');
				foreach ($items as $k => $c) {
					if ($showcatname) $items[$k]['catname'] = self::getCategoryName($c['idcat']);
					if (strlen($c['startfrom']) > 0 && $c['startfrom'] > 0.00) {
						$items[$k]['cost'] = $c['startfrom'];
					} else {
						$q = "SELECT `id`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`='".$c['id']."' AND `days`='1' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 1) {
							$tar = $dbo->loadAssocList();
							$items[$k]['cost'] = $tar[0]['cost'];
						} else {
							$q = "SELECT `id`,`days`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`='".$c['id']."' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() == 1) {
								$tar = $dbo->loadAssocList();
								$items[$k]['cost'] = ($tar[0]['cost'] / $tar[0]['days']);
							} else {
								$items[$k]['cost'] = 0;
							}
						}
					}
				}
			}
		} else {
			//sort by category
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `avail`='1' AND (`idcat`=".$dbo->quote($params->get('catid').";")." OR `idcat` LIKE '".$params->get('catid').";%' OR `idcat` LIKE '%;".$params->get('catid').";%' OR `idcat` LIKE '%;".$params->get('catid').";') ORDER BY `#__vikrentitems_items`.`name` ".strtoupper($params->get('order'))." LIMIT ".$params->get('numb').";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$items=$dbo->loadAssocList();
				$vri_tn->translateContents($items, '#__vikrentitems_items');
				foreach ($items as $k => $c) {
					if ($showcatname) $items[$k]['catname'] = self::getCategoryName($c['idcat']);
					if (strlen($c['startfrom']) > 0 && $c['startfrom'] > 0.00) {
						$items[$k]['cost'] = $c['startfrom'];
					} else {
						$q = "SELECT `id`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`='".$c['id']."' AND `days`='1' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 1) {
							$tar = $dbo->loadAssocList();
							$items[$k]['cost'] = $tar[0]['cost'];
						} else {
							$q = "SELECT `id`,`days`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`='".$c['id']."' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() == 1) {
								$tar = $dbo->loadAssocList();
								$items[$k]['cost'] = ($tar[0]['cost'] / $tar[0]['days']);
							} else {
								$items[$k]['cost'] = 0;
							}
						}
					}
				}
			}
			if ($params->get('querycat') == 'price') {
				$items = self::sortItemsByPrice($items, $params);
			}
		}
		return $items;
	}

	public static function getItemParam($params, $what)
	{
		$retparam = '';
		$parts = explode(';_;', $params);
		foreach ($parts as $p) {
			if (substr(trim($p), 0, (strlen($what) + 1)) == $what.':') {
				$pfound = explode(':', trim($p));
				unset($pfound[0]);
				$retparam = implode(':', $pfound);
				break;
			}
		}
		return $retparam;
	}
	
	public static function sortItemsByPrice($arr, $params)
	{
		$newarr = array ();
		foreach ($arr as $k => $v) {
			$newarr[$k] = $v['cost'];
		}
		asort($newarr);
		$sorted = array ();
		foreach ($newarr as $k => $v) {
			$sorted[$k] = $arr[$k];
		}
		return $params->get('order') == 'desc' ? array_reverse($sorted) : $sorted;
	}
	
	public static function getCategoryName($idcat)
	{
		$vbo_tn = self::getTranslator();
		$dbo = JFactory::getDbo();
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_categories` WHERE `id`='" . str_replace(";", "", $idcat) . "';";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() < 1) {
			return '';
		}
		$p = $dbo->loadAssocList();
		$vbo_tn->translateContents($p, '#__vikrentitems_categories');
		return $p[0]['name'];
	}
	
	public static function limitRes($items, $params)
	{
		return array_slice($items, 0, $params->get('numb'));
	}
	
	public static function numberFormat($numb)
	{
		return VikRentItems::numberFormat($numb);
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

	public static function getTranslator()
	{
		return VikRentItems::getTranslator();
	}

	public static function loadFontAwesome($force_load = false)
	{
		return VikRentItems::loadFontAwesome($force_load);
	}
}
