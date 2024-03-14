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

class VikRentItemsViewEditbusy extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		$oid = $cid[0];

		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		if (empty($oid)) {
			VikError::raiseWarning('', 'Not Found');
			$mainframe->redirect("index.php?option=com_vikrentitems&task=orders");
			exit;
		}
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".(int)$oid.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() != 1) {
			VikError::raiseWarning('', JText::translate('VRPEDITBUSYONE'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=orders");
			exit;
		}
		$ord = $dbo->loadAssocList();
		$orderitems = array();
		$q = "SELECT `oi`.*,`i`.`name`,`i`.`img`,`i`.`idopt`,`i`.`askquantity`,`i`.`params` FROM `#__vikrentitems_ordersitems` AS `oi`,`#__vikrentitems_items` AS `i` WHERE `oi`.`idorder`='".$ord[0]['id']."' AND `oi`.`iditem`=`i`.`id` ORDER BY `oi`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$orderitems = $dbo->loadAssocList();
		}
		$all_items = array();
		$q = "SELECT * FROM `#__vikrentitems_items` ORDER BY `avail` DESC, `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$all_items = $dbo->loadAssocList();
		}
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$locations = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
		$cpin = VikRentItems::getCPinIstance();
		$customer = $cpin->getCustomerFromBooking($ord[0]['id']);
		if (count($customer) && !empty($customer['country'])) {
			if (file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'countries'.DIRECTORY_SEPARATOR.$customer['country'].'.png')) {
				$customer['country_img'] = '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$customer['country'].'.png'.'" title="'.$customer['country'].'" class="vri-country-flag vri-country-flag-left"/>';
			}
		}
		
		$this->orderitems = &$orderitems;
		$this->ord = &$ord;
		$this->all_items = &$all_items;
		$this->customer = &$customer;
		$this->locations = &$locations;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINEBUSYTITLE'), 'vikrentitems');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
			JToolBarHelper::apply( 'updatebusy', JText::translate('VRSAVE'));
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikrentitems')) {
			JToolBarHelper::custom( 'removebusy', 'delete', 'delete', JText::translate('VRMAINEBUSYDEL'), false, false);
		}
		$pgoto = VikRequest::getString('goto', '', 'request');
		if($pgoto == 'overv') {
			JToolBarHelper::custom( 'cancelbusy', 'back', 'back', JText::translate('VRIVIEWBOOKINGDET'), false, false);
		}
		JToolBarHelper::cancel( ($pgoto == 'overv' ? 'canceloverv' : 'cancelbusy'), JText::translate('VRBACK'));
	}

}
