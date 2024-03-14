<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

jimport('joomla.application.component.view');

class VikrentitemsViewItemslist extends JViewVikRentItems {
	function display($tpl = null) {
		VikRentItems::prepareViewContent();
		$dbo = JFactory::getDBO();
		$vri_tn = VikRentItems::getTranslator();
		$pcategory_id = VikRequest::getInt('category_id', '', 'request');
		$psortby = VikRequest::getString('sortby', '', 'request');
		$psortby = !in_array($psortby, array('price', 'name', 'id', 'random')) ? 'price' : $psortby;
		$psorttype = VikRequest::getString('sorttype', '', 'request');
		$psorttype = $psorttype == 'desc' ? 'DESC' : 'ASC';
		$preslim = VikRequest::getInt('reslim', '', 'request');
		$preslim = empty($preslim) || $preslim < 1 ? 20 : $preslim;
		$category = "";
		if ($pcategory_id > 0) {
			$q="SELECT * FROM `#__vikrentitems_categories` WHERE `id`='".$pcategory_id."';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$category = $dbo->loadAssocList();
				$category = $category[0];
				$vri_tn->translateContents($category, '#__vikrentitems_categories');
			}
		}
		$ordbyclause = '';
		if ($psortby == 'name') {
			$ordbyclause = ' ORDER BY `#__vikrentitems_items`.`name` '.$psorttype;
		} elseif ($psortby == 'id') {
			$ordbyclause = ' ORDER BY `#__vikrentitems_items`.`id` '.$psorttype;
		}
		if (is_array($category)) {
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `avail`='1' AND (`idcat`='".$category['id'].";' OR `idcat` LIKE '".$category['id'].";%' OR `idcat` LIKE '%;".$category['id'].";%' OR `idcat` LIKE '%;".$category['id'].";')".$ordbyclause.";";
		} else {
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `avail`='1'".$ordbyclause.";";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$items=$dbo->loadAssocList();
			$vri_tn->translateContents($items, '#__vikrentitems_items');
			foreach ($items as $k=>$c) {
				$q="SELECT `id`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`=".$dbo->quote($c['id'])." AND `days`='1' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$tar=$dbo->loadAssocList();
					$items[$k]['cost']=$tar[0]['cost'];
				} else {
					$q="SELECT `id`,`days`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`=".$dbo->quote($c['id'])." ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() == 1) {
						$tar=$dbo->loadAssocList();
						$items[$k]['cost']=($tar[0]['cost'] / $tar[0]['days']);
					} else {
						$items[$k]['cost']=0;
					}
				}
			}
			if ($psortby == 'random') {
				$keys = array_keys($items);
				shuffle($keys);
				$new = array();
				foreach ($keys as $key) {
					$new[$key] = $items[$key];
				}
				$items = $new;
			} elseif ($psortby == 'price') {
				$items = VikRentItems::sortItemPrices($items);
				if ($psorttype == 'DESC') {
					$items = array_reverse($items, true);
				}
			}
			//pagination
			$lim=$preslim; //results limit
			$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination(count($items), $lim0, $lim);
			$navig = $pageNav->getPagesLinks();
			$this->navig = $navig;
			$items = array_slice($items, $lim0, $lim, true);
			//
			
			$this->items = $items;
			$this->category = $category;
			$this->vri_tn = $vri_tn;
			//theme
			$theme = VikRentItems::getTheme();
			if ($theme != 'default') {
				$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'itemslist';
				if (is_dir($thdir)) {
					$this->_setPath('template', $thdir.DS);
				}
			}
			//
			parent::display($tpl);
		}
	}
}
