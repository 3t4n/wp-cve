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

class VikrentitemsViewOrder extends JViewVikRentItems {
	function display($tpl = null) {
		$sid = VikRequest::getString('sid', '', 'request');
		$ts = VikRequest::getString('ts', '', 'request');
		$vricart = array();
		$dbo = JFactory::getDbo();
		$vri_tn = VikRentItems::getTranslator();
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `sid`=" . $dbo->quote($sid) . " AND `ts`=" . $dbo->quote($ts) . ";";
		$dbo->setQuery($q);
		$dbo->execute();
		$order = $dbo->loadAssocList();
		$checkhourscharges = 0;
		$hoursdiff = 0;
		$ppickup = $order[0]['ritiro'];
		$prelease = $order[0]['consegna'];
		$secdiff = $prelease - $ppickup;
		$daysdiff = $secdiff / 86400;
		if (is_int($daysdiff)) {
			if ($daysdiff < 1) {
				$daysdiff = 1;
			}
		} else {
			if ($daysdiff < 1) {
				$daysdiff = 1;
				$checkhourly = true;
				$ophours = $secdiff / 3600;
				$hoursdiff = intval(round($ophours));
				if ($hoursdiff < 1) {
					$hoursdiff = 1;
				}
			} else {
				$sum = floor($daysdiff) * 86400;
				$newdiff = $secdiff - $sum;
				$maxhmore = VikRentItems::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				} else {
					$daysdiff = ceil($daysdiff);
					$ehours = intval(round(($newdiff - $maxhmore) / 3600));
					$checkhourscharges = $ehours;
					if ($checkhourscharges > 0) {
						$aehourschbasp = VikRentItems::applyExtraHoursChargesBasp();
					}
				}
			}
		}
		$q = "SELECT `oi`.`iditem`,`oi`.`idtar`,`oi`.`optionals`,`oi`.`itemquant`,`oi`.`deliveryaddr`,`oi`.`deliverydist`,`oi`.`cust_cost`,`oi`.`cust_idiva`,`oi`.`extracosts`,`i`.`name`,`i`.`img`,`i`.`idcarat`,`i`.`info`,`i`.`moreimgs` FROM `#__vikrentitems_ordersitems` AS `oi`,`#__vikrentitems_items` AS `i` WHERE `oi`.`idorder`=".(int)$order[0]['id']." AND `oi`.`iditem`=`i`.`id` ORDER BY `oi`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$orderitems = $dbo->loadAssocList();
			$vri_tn->translateContents($orderitems, '#__vikrentitems_items', array('id' => 'iditem'));
			foreach ($orderitems as $koi => $oi) {
				$tar = array("");
				$is_cust_cost = (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0);
				if (!empty($oi['idtar'])) {
					if ($order[0]['hourly'] == 1) {
						$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `id`=" . (int)$oi['idtar'] . ";";
					} else {
						$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=" . (int)$oi['idtar'] . ";";
					}
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() == 1) {
						$tar = $dbo->loadAssocList();
					} elseif ($order[0]['hourly'] == 1) {
						$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=" . (int)$oi['idtar'] . ";";
						$dbo->setQuery($q);
						$dbo->execute();
						$tar = $dbo->loadAssocList();
					}
				} elseif ($is_cust_cost) {
					//Custom Rate
					$tar = array(array(
						'id' => -1,
						'iditem' => $oi['iditem'],
						'days' => $order[0]['days'],
						'idprice' => -1,
						'cost' => $oi['cust_cost'],
						'attrdata' => '',
					));
				}
				if (count($tar) && isset($tar[0]['id'])) {
					if ($order[0]['hourly'] == 1) {
						foreach ($tar as $kt => $vt) {
							$tar[$kt]['days'] = 1;
						}
					}
					if ($checkhourscharges > 0 && $aehourschbasp == true) {
						$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false, true, true);
						$tar = $ret['return'];
						$calcdays = $ret['days'];
					}
					if ($checkhourscharges > 0 && $aehourschbasp == false) {
						$tar = VikRentItems::extraHoursSetPreviousFareItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true);
						$tar = VikRentItems::applySeasonsItem($tar, $order[0]['ritiro'], $order[0]['consegna'], $order[0]['idplace']);
						$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true, true, true);
						$tar = $ret['return'];
						$calcdays = $ret['days'];
					} else {
						$tar = VikRentItems::applySeasonsItem($tar, $order[0]['ritiro'], $order[0]['consegna'], $order[0]['idplace']);
					}
					$tar = VikRentItems::applyItemDiscounts($tar, $oi['iditem'], $oi['itemquant']);
				}
				$vricart[$oi['iditem']][$koi]['info'] = $oi;
				$vricart[$oi['iditem']][$koi]['itemquant'] = $oi['itemquant'];
				$vricart[$oi['iditem']][$koi]['tar'] = $tar[0];
				$vricart[$oi['iditem']][$koi]['optionals'] = $oi['optionals'];
				if (!empty($oi['deliveryaddr'])) {
					$vricart[$oi['iditem']][$koi]['delivery']['addr'] = $oi['deliveryaddr'];
					$vricart[$oi['iditem']][$koi]['delivery']['dist'] = $oi['deliverydist'];
				}
			}
		}
		$payment = "";
		if (!empty($order[0]['idpayment'])) {
			$exppay = explode('=', $order[0]['idpayment']);
			$payment = VikRentItems::getPayment($exppay[0], $vri_tn);
		}
		//vikrentitems 1.1
		if ($checkhourscharges > 0) {
			$this->calcdays = &$calcdays;
		} else {
			$this->calcdays = '';
		}
		//
		$this->ord = $order[0];
		$this->vricart = $vricart;
		$this->payment = $payment;
		$this->vri_tn = $vri_tn;
		//theme
		$theme = VikRentItems::getTheme();
		if ($theme != 'default') {
			$thdir = VRI_SITE_PATH . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . 'order';
			if (is_dir($thdir)) {
				$this->_setPath('template', $thdir . DIRECTORY_SEPARATOR);
			}
		}
		//
		parent::display($tpl);
	}
}
