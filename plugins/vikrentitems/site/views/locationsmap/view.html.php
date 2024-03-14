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

class VikrentitemsViewLocationsmap extends JViewVikRentItems {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
		$vri_tn = VikRentItems::getTranslator();
		$pelemid = VikRequest::getInt('elemid', '', 'request');
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
		//filter locations by ID Item
		if (count($locations) && $pelemid > 0) {
			$item = VikRentItems::getItemInfo($pelemid, $vri_tn);
			if (is_array($item) && !empty($item['idplace'])) {
				$actlocs = explode(";", $item['idplace']);
				$actretlocs = explode(";", $item['idretplace']);
				$actlocsall = array_merge($actlocs, $actretlocs);
				$actlocsall = array_unique($actlocsall);
				$clauselocs = array();
				foreach ($actlocsall as $ala) {
					if (!empty($ala)) {
						$clauselocs[] = $ala;
					}
				}
				if (count($clauselocs)) {
					foreach ($locations as $k => $v) {
						if (!in_array((string)$v['id'], $clauselocs)) {
							unset($locations[$k]);
						}
					}
				}
			}
		}
		//
		$this->locations = $locations;
		//theme
		$theme = VikRentItems::getTheme();
		if ($theme != 'default') {
			$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'locationsmap';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
	}
}
