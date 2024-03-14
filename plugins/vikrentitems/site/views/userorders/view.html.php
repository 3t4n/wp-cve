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

class VikrentitemsViewUserorders extends JViewVikRentItems {
	function display($tpl = null) {
		VikRentItems::prepareViewContent();
		$islogged = VikRentItems::userIsLogged();
		$cpin = VikRentItems::getCPinIstance();
		$pconfirmnumber = VikRequest::getString('confirmnumber', '', 'request');
		$pitemid = VikRequest::getString('Itemid', '', 'request');
		$dbo = JFactory::getDBO();
		if (!empty($pconfirmnumber)) {
			$sidts = array(0, 0);
			$delimiter = strpos($pconfirmnumber, '_') !== false ? '_' : '-';
			$confirmnumber_parts = explode($delimiter, $pconfirmnumber);
			if (count($confirmnumber_parts) > 1) {
				$sidts = array(trim($confirmnumber_parts[0]), trim($confirmnumber_parts[1]));
			}
			$q = "SELECT `id`,`ts`,`sid` FROM `#__vikrentitems_orders` WHERE `sid`=".$dbo->quote($sidts[0])." AND `ts`=".$dbo->quote($sidts[1]).";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$odata = $dbo->loadAssocList();
				$mainframe = JFactory::getApplication();
				$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikrentitems&view=order&sid='.$odata[0]['sid'].'&ts='.$odata[0]['ts'].(!empty($pitemid) ? '&Itemid='.$pitemid : ''), false));
				exit;
			} else {
				if ($cpin->pinExists($pconfirmnumber)) {
					$cpin->setNewPin($pconfirmnumber);
				} else {
					VikError::raiseWarning('', JText::translate('VRINVALIDCONFIRMNUMBER'));
				}
			}
		}
		$customer_details = $cpin->loadCustomerDetails();
		$userorders = '';
		$navig = '';
		if ($islogged || count($customer_details) > 0) {
			$currentUser = JFactory::getUser();
			$lim = 10;
			$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
			$q = "SELECT SQL_CALC_FOUND_ROWS `o`.*,`co`.`idcustomer` FROM `#__vikrentitems_orders` AS `o` LEFT JOIN `#__vikrentitems_customers_orders` `co` ON `co`.`idorder`=`o`.`id` WHERE ".($islogged ? "`o`.`ujid`='".$currentUser->id."'".(count($customer_details) > 0 ? " OR " : "") : "").(count($customer_details) > 0 ? "`co`.`idcustomer`=".(int)$customer_details['id'] : "")." ORDER BY `o`.`ritiro` DESC";
			$dbo->setQuery($q, $lim0, $lim);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$userorders = $dbo->loadAssocList();
				$dbo->setQuery('SELECT FOUND_ROWS();');
				jimport('joomla.html.pagination');
				$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
				$navig = $pageNav->getPagesLinks();
			}
		}
		$this->userorders = &$userorders;
		$this->customer_details = &$customer_details;
		$this->navig = &$navig;
		//theme
		$theme = VikRentItems::getTheme();
		if ($theme != 'default') {
			$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'userorders';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
	}
}
