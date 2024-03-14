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

class VikRentItemsViewOrders extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$rows = "";
		$navbut = "";
		$all_locations = '';
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 = $mainframe->getUserStateFromRequest("vri.orders.limitstart", 'limitstart', 0, 'int');

		$q = "SELECT `id`,`name` FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$all_locations = $dbo->loadAssocList();
		}
		$allitems = array();
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_items` ORDER BY `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$allitems = $dbo->loadAssocList();
		}
		$plocation = $mainframe->getUserStateFromRequest("vri.orders.location", 'location', 0, 'int');
		$plocationw = $mainframe->getUserStateFromRequest("vri.orders.locationw", 'locationw', '', 'string');
		$plocationw = empty($plocationw) || !in_array($plocationw, array('pickup', 'dropoff', 'both')) ? 'pickup' : $plocationw;
		$pvriorderby = VikRequest::getString('vriorderby', '', 'request');
		$pvriordersort = VikRequest::getString('vriordersort', '', 'request');
		$pfiltnc = $mainframe->getUserStateFromRequest("vri.orders.filtnc", 'filtnc', '', 'string');
		$validorderby = array('id', 'ts', 'pickupts', 'dropoffts', 'days', 'total', 'status');
		$orderby = $session->get('vriViewOrdersOrderby', 'id');
		$ordersort = $session->get('vriViewOrdersOrdersort', 'DESC');
		if (!empty($pvriorderby) && in_array($pvriorderby, $validorderby)) {
			$orderby = $pvriorderby;
			$session->set('vriViewOrdersOrderby', $orderby);
			if (!empty($pvriordersort) && in_array($pvriordersort, array('ASC', 'DESC'))) {
				$ordersort = $pvriordersort;
				$session->set('vriViewOrdersOrdersort', $ordersort);
			}
		}

		$piditem = $mainframe->getUserStateFromRequest("vri.orders.iditem", 'iditem', 0, 'int');
		$pcust_id = $mainframe->getUserStateFromRequest("vri.orders.cust_id", 'cust_id', 0, 'int');
		$pdatefilt = $mainframe->getUserStateFromRequest("vri.orders.datefilt", 'datefilt', 0, 'int');
		$pdatefiltfrom = $mainframe->getUserStateFromRequest("vri.orders.datefiltfrom", 'datefiltfrom', '', 'string');
		$pdatefiltto = $mainframe->getUserStateFromRequest("vri.orders.datefiltto", 'datefiltto', '', 'string');
		$dates_filter = '';
		if (!empty($pdatefilt) && (!empty($pdatefiltfrom) || !empty($pdatefiltto))) {
			$dates_filter_field = '`o`.`ts`';
			if ($pdatefilt == 2) {
				$dates_filter_field = '`o`.`ritiro`';
			} elseif ($pdatefilt == 3) {
				$dates_filter_field = '`o`.`consegna`';
			}
			$dates_filter_clauses = array();
			if (!empty($pdatefiltfrom)) {
				$dates_filter_clauses[] = $dates_filter_field.'>='.VikRentItems::getDateTimestamp($pdatefiltfrom, '0', '0');
			}
			if (!empty($pdatefiltto)) {
				$dates_filter_clauses[] = $dates_filter_field.'<='.VikRentItems::getDateTimestamp($pdatefiltto, 23, 60);
			}
			$dates_filter = implode(' AND ', $dates_filter_clauses);
		}
		$pstatus = $mainframe->getUserStateFromRequest("vri.orders.status", 'status', '', 'string');
		$status_filter = !empty($pstatus) && in_array($pstatus, array('confirmed', 'standby', 'cancelled')) ? "`o`.`status`='".$pstatus."'" : '';
		$status_filter = !empty($pstatus) && $pstatus == 'closure' ? "`o`.`closure`=1" : $status_filter;
		$pidpayment = $mainframe->getUserStateFromRequest("vri.orders.idpayment", 'idpayment', 0, 'int');
		$payment_filter = '';
		if (!empty($pidpayment)) {
			$payment_filter = "`o`.`idpayment` LIKE '".$pidpayment."=%'";
		}
		$ordersfound = false;

		$orderby_col = '`o`.`'.$orderby.'`';
		if ($orderby == 'pickupts') {
			$orderby_col = '`o`.`ritiro`';
		} elseif ($orderby == 'dropoffts') {
			$orderby_col = '`o`.`consegna`';
		} elseif ($orderby == 'total') {
			$orderby_col = '`o`.`order_total`';
		}

		if (!empty($pfiltnc)) {
			$q = "SELECT SQL_CALC_FOUND_ROWS `o`.* FROM `#__vikrentitems_orders` AS `o` WHERE (CONCAT_WS('_', `o`.`sid`, `o`.`ts`) = ".$dbo->quote($pfiltnc)." OR `o`.`id`=".$dbo->quote($pfiltnc)." OR `o`.`sid`=".$dbo->quote(str_replace('_', '', trim($pfiltnc)))." OR `o`.`custdata` LIKE ".$dbo->quote('%'.$pfiltnc.'%')." OR `o`.`nominative` LIKE ".$dbo->quote('%'.$pfiltnc.'%').") ORDER BY ".$orderby_col." ".$ordersort;
			$dbo->setQuery($q, $lim0, $lim);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$rows = $dbo->loadAssocList();
				$dbo->setQuery('SELECT FOUND_ROWS();');
				$totres = $dbo->loadResult();
				if ($totres == 1 && count($rows) == 1) {
					$mainframe->redirect("index.php?option=com_vikrentitems&task=editorder&cid[]=".$rows[0]['id']);
					exit;
				} else {
					$ordersfound = true;
					jimport('joomla.html.pagination');
					$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
					$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
				}
			}
		}

		$where_clauses = array();
		if ($plocation > 0) {
			if ($plocationw == 'both') {
				$where_clauses[] = '(`o`.`idplace`='.$plocation.' OR `o`.`idreturnplace`='.$plocation.")";
			} elseif ($plocationw == 'dropoff') {
				$where_clauses[] = '`o`.`idreturnplace`='.$plocation;
			} elseif ($plocationw == 'pickup') {
				$where_clauses[] = '`o`.`idplace`='.$plocation;
			}
		}
		if (!empty($pidcar)) {
			$where_clauses[] = '`o`.`idcar`='.$pidcar;
		}
		if (!empty($dates_filter)) {
			$where_clauses[] = $dates_filter;
		}
		if (!empty($payment_filter)) {
			$where_clauses[] = $payment_filter;
		}
		if (!empty($status_filter)) {
			$where_clauses[] = $status_filter;
		}

		if (!$ordersfound) {
			if (!empty($pcust_id)) {
				$q = "SELECT SQL_CALC_FOUND_ROWS `o`.*,`co`.`idcustomer`,CONCAT_WS(' ', `cust`.`first_name`, `cust`.`last_name`) AS `customer_fullname` FROM `#__vikrentitems_orders` AS `o` LEFT JOIN `#__vikrentitems_customers_orders` `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikrentitems_customers` `cust` ON `cust`.`id`=`co`.`idcustomer` AND `cust`.`id`=".$pcust_id." WHERE ".(!empty($dates_filter) ? $dates_filter.' AND ' : '').(!empty($payment_filter) ? $payment_filter.' AND ' : '').(!empty($status_filter) ? $status_filter.' AND ' : '')."`co`.`idcustomer`=".$pcust_id." ORDER BY ".$orderby_col." ".$ordersort;
			} elseif (!empty($piditem)) {
				//ONLY_FULL_GROUP_BY safe
				$q = "SELECT SQL_CALC_FOUND_ROWS DISTINCT `o`.*,`oi`.`idorder` FROM `#__vikrentitems_orders` AS `o` LEFT JOIN `#__vikrentitems_ordersitems` `oi` ON `o`.`id`=`oi`.`idorder` WHERE ".(!empty($dates_filter) ? $dates_filter.' AND ' : '').(!empty($payment_filter) ? $payment_filter.' AND ' : '').(!empty($status_filter) ? $status_filter.' AND ' : '')."`oi`.`iditem`=".$piditem." ORDER BY ".$orderby_col." ".$ordersort;
			} else {
				$q = "SELECT SQL_CALC_FOUND_ROWS `o`.* FROM `#__vikrentitems_orders` AS `o`".(count($where_clauses) > 0 ? " WHERE ".implode(' AND ', $where_clauses) : "")." ORDER BY ".$orderby_col." ".$ordersort.($orderby == 'ts' && $ordersort == 'DESC' ? ', `o`.`id` DESC' : '');
			}
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
		}
		
		$this->rows = &$rows;
		$this->lim0 = &$lim0;
		$this->navbut = &$navbut;
		$this->all_locations = &$all_locations;
		$this->plocation = &$plocation;
		$this->plocationw = &$plocationw;
		$this->orderby = &$orderby;
		$this->ordersort = &$ordersort;
		$this->allitems = &$allitems;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINORDERTITLE'), 'vikrentitems');
		if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
			JToolBarHelper::custom( 'export', 'download', 'download', JText::translate('VRMAINORDERSEXPORT'), false, false);
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
			JToolBarHelper::editList('editorder', JText::translate('VRMAINORDEREDIT'));
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikrentitems')) {
			JToolBarHelper::deleteList(JText::translate('VRIDELCONFIRM'), 'removeorders', JText::translate('VRMAINORDERDEL'));
		}
	}

}
