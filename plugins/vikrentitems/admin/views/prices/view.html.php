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

class VikRentItemsViewPrices extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$rows = "";
		$navbut = "";
		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikrentitems_prices`";
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		}
		
		$this->rows = &$rows;
		$this->lim0 = &$lim0;
		$this->navbut = &$navbut;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINPRICETITLE'), 'vikrentitems');
		if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
			JToolBarHelper::addNew('newprice', JText::translate('VRMAINPRICENEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
			JToolBarHelper::editList('editprice', JText::translate('VRMAINPRICEEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikrentitems')) {
			JToolBarHelper::deleteList(JText::translate('VRIDELCONFIRM'), 'removeprice', JText::translate('VRMAINPRICEDEL'));
			JToolBarHelper::spacer();
		}
	}

}
