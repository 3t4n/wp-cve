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

class VikRentItemsViewStats extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$rows = "";
		$navbut = "";
		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');

		$session = JFactory::getSession();
		$pvrorderby = VikRequest::getString('vrorderby', '', 'request');
		$pvrordersort = VikRequest::getString('vrordersort', '', 'request');
		$validorderby = array('ts','pickup', 'dropoff', 'res');
		$orderbykeymap = array('pickup' => 'ritiro', 'dropoff' => 'consegna'); 
		$orderby = $session->get('vriViewStatsOrderby', 'ts');
		$ordersort = $session->get('vriViewStatsOrdersort', 'DESC');
		if (!empty($pvrorderby) && in_array($pvrorderby, $validorderby)) {
			$orderby = $pvrorderby;
			$session->set('vriViewStatsOrderby', $orderby);
			if (!empty($pvrordersort) && in_array($pvrordersort, array('ASC', 'DESC'))) {
				$ordersort = $pvrordersort;
				$session->set('vriViewStatsOrdersort', $ordersort);
			}
		}
		$q = "SELECT SQL_CALC_FOUND_ROWS `s`.* FROM `#__vikrentitems_stats` AS `s` ORDER BY `s`.`" . (isset($orderbykeymap[$orderby]) ? $orderbykeymap[$orderby] : $orderby) . "` " . $ordersort;
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
		$this->orderby = &$orderby;
		$this->ordersort = &$ordersort;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINSTATSTITLE'), 'vikrentitems');
		if (JFactory::getUser()->authorise('core.delete', 'com_vikrentitems')) {
			JToolBarHelper::deleteList(JText::translate('VRIDELCONFIRM'), 'removestats', JText::translate('VRELIMINA'));
			JToolBarHelper::spacer();
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
