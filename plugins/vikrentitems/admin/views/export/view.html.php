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

// import Joomla view library
jimport('joomla.application.component.view');

class VikRentItemsViewExport extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$dbo = JFactory::getDBO();
		$oids = VikRequest::getVar('cid', array(0));
		$oids = count($oids) > 0 && intval($oids[key($oids)]) > 0 ? $oids : array();
		$locations = '';
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$locations = $dbo->loadAssocList();
		}
		
		$this->oids = &$oids;
		$this->locations = &$locations;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINEXPORTTITLE'), 'vikrentitems');
		JToolBarHelper::cancel( 'canceledorder', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
