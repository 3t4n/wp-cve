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

class VikRentItemsViewItems extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();

		$pmodtar = VikRequest::getString('tarmod', '', 'request');
		$pmodtarhours = VikRequest::getString('tarmodhours', '', 'request');
		$pmodtarhourscharges = VikRequest::getString('tarmodhourscharges', '', 'request');
		$pelemid = VikRequest::getInt('elemid', '', 'request');
		if (!empty($pmodtar) && !empty($pelemid)) {
			$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `iditem`=".$dbo->quote($pelemid).";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$tars = $dbo->loadAssocList();
				foreach ($tars as $tt) {
					$tmpcost = VikRequest::getString('cost'.$tt['id'], '', 'request');
					$tmpattr = VikRequest::getString('attr'.$tt['id'], '', 'request');
					if (strlen($tmpcost)) {
						$q = "UPDATE `#__vikrentitems_dispcost` SET `cost`='".(float)$tmpcost."'".(strlen($tmpattr) ? ", `attrdata`=".$dbo->quote($tmpattr)."" : "")." WHERE `id`=".(int)$tt['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=tariffs&cid[]=".$pelemid);
			exit;
		} elseif (!empty($pmodtarhours) && !empty($pelemid)) {
			// vikrentitems 1.1 fares for hours
			$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `iditem`=".$dbo->quote($pelemid).";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$tars = $dbo->loadAssocList();
				foreach ($tars as $tt) {
					$tmpcost = VikRequest::getString('cost'.$tt['id'], '', 'request');
					$tmpattr = VikRequest::getString('attr'.$tt['id'], '', 'request');
					if (strlen($tmpcost)) {
						$q = "UPDATE `#__vikrentitems_dispcosthours` SET `cost`='".(float)$tmpcost."'".(strlen($tmpattr) ? ", `attrdata`=".$dbo->quote($tmpattr)."" : "")." WHERE `id`=".(int)$tt['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=tariffshours&cid[]=".$pelemid);
			exit;
			//
		} elseif (!empty($pmodtarhourscharges) && !empty($pelemid)) {
			// vikrentitems 1.1 extra hours charges
			$q = "SELECT * FROM `#__vikrentitems_hourscharges` WHERE `iditem`=".$dbo->quote($pelemid).";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$tars = $dbo->loadAssocList();
				foreach ($tars as $tt) {
					$tmpcost = VikRequest::getString('cost'.$tt['id'], '', 'request');
					if (strlen($tmpcost)) {
						$q = "UPDATE `#__vikrentitems_hourscharges` SET `cost`='".(float)$tmpcost."' WHERE `id`=".(int)$tt['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=hourscharges&cid[]=".$pelemid);
			exit;
			//
		}

		$filtni = $mainframe->getUserStateFromRequest("vri.items.filtni", 'filtni', '', 'string');
		$filtcateg = $mainframe->getUserStateFromRequest("vri.items.filtcateg", 'filtcateg', 0, 'int');
		//Category Filter
		$all_cats = array();
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_categories` ORDER BY `#__vikrentitems_categories`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$all_cats = $dbo->loadAssocList();
		}
		//
		$pvriorderby = VikRequest::getString('vriorderby', '', 'request');
		$pvriordersort = VikRequest::getString('vriordersort', '', 'request');
		$validorderby = array('id', 'name', 'units');
		$orderby = $session->get('vriViewItemsOrderby', 'id');
		$ordersort = $session->get('vriViewItemsOrdersort', 'DESC');
		if (!empty($pvriorderby) && in_array($pvriorderby, $validorderby)) {
			$orderby = $pvriorderby;
			$session->set('vriViewItemsOrderby', $orderby);
			if (!empty($pvriordersort) && in_array($pvriordersort, array('ASC', 'DESC'))) {
				$ordersort = $pvriordersort;
				$session->set('vriViewItemsOrdersort', $ordersort);
			}
		}
		$rows = "";
		$navbut = "";
		$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 = $mainframe->getUserStateFromRequest("vri.orders.limitstart", 'limitstart', 0, 'int');
		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikrentitems_items`";
		if (!empty($filtni)) {
			$q .= " WHERE `name` LIKE ".$dbo->quote("%".$filtni."%");
		} elseif (!empty($filtcateg)) {
			$q .= " WHERE (`idcat`='".$filtcateg.";' OR `idcat` LIKE '".$filtcateg.";%' OR `idcat` LIKE '%;".$filtcateg.";%' OR `idcat` LIKE '%;".$filtcateg.";')";
		}
		$q .= " ORDER BY `#__vikrentitems_items`.`".$orderby."` ".$ordersort;
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();

		/**
		 * Call assertListQuery() from the View class to make sure the filters set
		 * do not produce an empty result. This would reset the page in this case.
		 * 
		 * @since 	1.7
		 */
		$this->assertListQuery($lim0, $lim);
		//

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
		$this->all_cats = &$all_cats;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINDEAFULTTITLE'), 'vikrentitems');
		if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
			JToolBarHelper::addNew('newitem', JText::translate('VRMAINDEFAULTNEW'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::custom( 'calendar', 'edit', 'edit', JText::translate('VRMAINDEFAULTCAL'), true, false);
		JToolBarHelper::spacer();
		if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
			JToolBarHelper::editList('tariffs', JText::translate('VRMAINDEFAULTEDITT'));
			JToolBarHelper::spacer();
			JToolBarHelper::editList('edititem', JText::translate('VRMAINDEFAULTEDITC'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
			JToolBarHelper::custom('cloneitem', 'save-copy', 'save-copy', JText::translate('VRMAINDEFAULTCLONE'), true, false);
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikrentitems')) {
			JToolBarHelper::deleteList(JText::translate('VRIDELCONFIRM'), 'removeitem', JText::translate('VRMAINDEFAULTDEL'));
			JToolBarHelper::spacer();
		}
	}

}
