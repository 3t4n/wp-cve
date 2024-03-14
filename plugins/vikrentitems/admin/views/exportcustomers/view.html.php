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

class VikRentItemsViewExportcustomers extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();
		
		$cid = VikRequest::getVar('cid', array(0));
		
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikrentitems_countries` ORDER BY `#__vikrentitems_countries`.`country_name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$countries = $dbo->loadAssocList();
		
		$this->cid = &$cid;
		$this->countries = &$countries;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRIMAINEXPCUSTOMERSTITLE'), 'vikrentitems');
		JToolBarHelper::custom('exportcustomerslaunch', 'download', 'download', JText::translate('VRICSVEXPCUSTOMERSGET'), false);
		JToolBarHelper::spacer();
		JToolBarHelper::cancel( 'cancelcustomer', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
