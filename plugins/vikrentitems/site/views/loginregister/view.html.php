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

class VikrentitemsViewLoginregister extends JViewVikRentItems {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
		$ppriceid = VikRequest::getString('priceid', '', 'request');
		$pplace = VikRequest::getString('place', '', 'request');
		$preturnplace = VikRequest::getString('returnplace', '', 'request');
		$pelemid = VikRequest::getString('elemid', '', 'request');
		$pdays = VikRequest::getString('days', '', 'request');
		$ppickup = VikRequest::getString('pickup', '', 'request');
		$prelease = VikRequest::getString('release', '', 'request');
		$copts = array();
		$q = "SELECT * FROM `#__vikrentitems_optionals`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$optionals = $dbo->loadAssocList();
			foreach ($optionals as $opt) {
				$tmpvar = VikRequest::getString('optid' . $opt['id'], '', 'request');
				if (!empty($tmpvar)) {
					$copts[$opt['id']] = $tmpvar;
				}
			}
		}
		$this->priceid = $ppriceid;
		$this->place = $pplace;
		$this->returnplace = $preturnplace;
		$this->elemid = $pelemid;
		$this->days = $pdays;
		$this->pickup = $ppickup;
		$this->release = $prelease;
		$this->copts = $copts;
		//theme
		$theme = VikRentItems::getTheme();
		if ($theme != 'default') {
			$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'loginregister';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
	}
}
