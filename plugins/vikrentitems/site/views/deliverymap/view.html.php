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

class VikrentitemsViewDeliverymap extends JViewVikRentItems {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
		$vri_tn = VikRentItems::getTranslator();
		$pelemid = VikRequest::getInt('elemid', '', 'request');
		$item = '';
		$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=".intval($pelemid).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$item = $dbo->loadAssocList();
			$item = $item[0];
			$vri_tn->translateContents($item, '#__vikrentitems_items');
		}
		$this->item = $item;
		//theme
		$theme = VikRentItems::getTheme();
		if ($theme != 'default') {
			$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'deliverymap';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
		
	}
}
