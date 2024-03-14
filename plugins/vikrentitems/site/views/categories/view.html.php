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

class VikrentitemsViewCategories extends JViewVikRentItems {
	function display($tpl = null) {
		VikRentItems::prepareViewContent();
		$dbo = JFactory::getDbo();
		$vri_tn = VikRentItems::getTranslator();
		$psortby = VikRequest::getString('sortby', '', 'request');
		$psortby = !in_array($psortby, array('auto', 'name', 'id')) ? 'auto' : $psortby;
		$psortbymap = array('auto' => 'ordering');
		$psortby = isset($psortbymap[$psortby]) ? $psortbymap[$psortby] : $psortby;
		$psorttype = VikRequest::getString('sorttype', '', 'request');
		$psorttype = $psorttype == 'desc' ? 'DESC' : 'ASC';
		$preslim = VikRequest::getInt('reslim', 0, 'request');
		$preslim = empty($preslim) || $preslim < 1 ? 20 : $preslim;

		$lim = $preslim; //results limit
		$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
		$categories = array();
		$navig = null;
		
		$q = "SELECT `c`.* FROM `#__vikrentitems_categories` AS `c` ORDER BY `c`.{$psortby} {$psorttype}";
		// we splice the array of records manually, we do not use limits in the query
		$dbo->setQuery($q);
		//
		$dbo->execute();
		if ($dbo->getNumRows()) {
			$categories = $dbo->loadAssocList();
			$vri_tn->translateContents($categories, '#__vikrentitems_categories');
	
			// pagination
			jimport('joomla.html.pagination');
			$pageNav = new JPagination(count($categories), $lim0, $lim);
			$navig = $pageNav->getPagesLinks();
			$categories = array_slice($categories, $lim0, $lim, true);
			//
		}

		$this->categories = $categories;
		$this->vri_tn = $vri_tn;
		$this->navig = $navig;

		// theme
		$theme = VikRentItems::getTheme();
		if ($theme != 'default') {
			$thdir = VRI_SITE_PATH . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'itemslist';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir . DIRECTORY_SEPARATOR);
			}
		}
		//
		parent::display($tpl);
	}
}
