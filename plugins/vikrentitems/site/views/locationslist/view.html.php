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

class VikrentitemsViewLocationslist extends JViewVikRentItems {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
		$vri_tn = VikRentItems::getTranslator();
		$locations = array();
		$q = "SELECT * FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$places = $dbo->loadAssocList();
			$vri_tn->translateContents($places, '#__vikrentitems_places');
			foreach ($places as $pla) {
				if (!empty($pla['lat']) && !empty($pla['lng'])) {
					$locations[] = $pla;
				}
			}
		}
		$this->locations = $locations;
		$this->alllocations = $places;
		//theme
		$theme = VikRentItems::getTheme();
		if ($theme != 'default') {
			$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'locationslist';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
	}
}
