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

class VikRentItemsViewEditorder extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		$ido = $cid[0];
		$dbo = JFactory::getDBO();
		$cpin = VikRentItems::getCPinIstance();
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".$dbo->quote($ido).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() != 1) {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=com_vikrentitems&task=orders");
		}
		$row = $dbo->loadAssoc();
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_gpayments` ORDER BY `#__vikrentitems_gpayments`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$payments = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : '';
		$customer = $cpin->getCustomerFromBooking($row['id']);
		if (count($customer) && !empty($customer['country'])) {
			if (file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'countries'.DIRECTORY_SEPARATOR.$customer['country'].'.png')) {
				$customer['country_img'] = '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$customer['country'].'.png'.'" title="'.$customer['country'].'" class="vri-country-flag vri-country-flag-left"/>';
			}
		}
		$padminnotes = VikRequest::getString('adminnotes', '', 'request');
		$pupdadmnotes = VikRequest::getString('updadmnotes', '', 'request');
		$pinvnotes = VikRequest::getString('invnotes', '', 'request', VIKREQUEST_ALLOWHTML);
		$pupdinvnotes = VikRequest::getString('updinvnotes', '', 'request');
		$pnewpayment = VikRequest::getString('newpayment', '', 'request');
		$pnewlang = VikRequest::getString('newlang', '', 'request');
		$padmindisc = VikRequest::getString('admindisc', '', 'request');
		$ptot_taxes = VikRequest::getString('tot_taxes', '', 'request');
		$ptot_city_taxes = VikRequest::getString('tot_city_taxes', '', 'request');
		$ptot_fees = VikRequest::getString('tot_fees', '', 'request');
		$pcmms = VikRequest::getString('cmms', '', 'request');
		$pcustmail = VikRequest::getString('custmail', '', 'request');
		$pcustphone = VikRequest::getString('custphone', '', 'request');
		if (!empty($padminnotes) || !empty($pupdadmnotes)) {
			$q = "UPDATE `#__vikrentitems_orders` SET `adminnotes`=".$dbo->quote($padminnotes)." WHERE `id`=".$row['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			$row['adminnotes'] = $padminnotes;
		}
		if (!empty($pnewpayment) && is_array($payments)) {
			foreach ($payments as $npay) {
				if ((int)$npay['id'] == (int)$pnewpayment) {
					$newpayvalid = $npay['id'].'='.$npay['name'];
					$q = "UPDATE `#__vikrentitems_orders` SET `idpayment`=".$dbo->quote($newpayvalid)." WHERE `id`=".$row['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					$row['idpayment'] = $newpayvalid;
					break;
				}
			}
		}
		if (!empty($pnewlang)) {
			$q = "UPDATE `#__vikrentitems_orders` SET `lang`=".$dbo->quote($pnewlang)." WHERE `id`=".$row['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			$row['lang'] = $pnewlang;
		}
		if (strlen($padmindisc) > 0) {
			if (floatval($padmindisc) > 0.00) {
				$admincoupon = '-1;'.floatval($padmindisc).';'.JText::translate('VRIADMINDISCOUNT');
			} else {
				$admincoupon = '';
			}
			$q = "UPDATE `#__vikrentitems_orders` SET `coupon`=".$dbo->quote($admincoupon)." WHERE `id`=".$row['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			$row['coupon'] = $admincoupon;
		}
		if (strlen($pcustmail) > 0) {
			$q = "UPDATE `#__vikrentitems_orders` SET `custmail`=".$dbo->quote($pcustmail)." WHERE `id`=".$row['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			$row['custmail'] = $pcustmail;
		}
		if (strlen($pcustphone) > 0) {
			$q = "UPDATE `#__vikrentitems_orders` SET `phone`=".$dbo->quote($pcustphone)." WHERE `id`=".$row['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			$row['phone'] = $pcustphone;
		}
		$q = "SELECT `oi`.*,`i`.`name`,`i`.`params` FROM `#__vikrentitems_ordersitems` AS `oi`,`#__vikrentitems_items` AS `i` WHERE `oi`.`idorder`=".(int)$row['id']." AND `oi`.`iditem`=`i`.`id` ORDER BY `oi`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$items = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
		$q = "SELECT * FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".(int)$row['id'].";";
		$dbo->setQuery($q);
		$dbo->execute();
		$busy = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
				
		$this->row = &$row;
		$this->items = &$items;
		$this->busy = &$busy;
		$this->customer = &$customer;
		$this->payments = &$payments;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINORDERTITLEEDIT'), 'vikrentitems');
		JToolBarHelper::cancel( 'canceledorder', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
