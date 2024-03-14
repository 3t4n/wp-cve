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

class VikRentItemsViewCalendar extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		$aid = $cid[0];

		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$vid = $session->get('vriCalVid', '');
		$aid = !empty($vid) && empty($aid) ? $vid : $aid;
		if (empty($aid)) {
			$q = "SELECT `id` FROM `#__vikrentitems_items` ORDER BY `#__vikrentitems_items`.`name` ASC LIMIT 1";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$aid = $dbo->loadResult();
			}
		}
		if (empty($aid)) {
			VikError::raiseWarning('', 'No Items.');
			$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
			exit;
		}

		$session->set('vriCalVid', $aid);
		$pvmode = VikRequest::getString('vmode', '', 'request');
		$cur_vmode = $session->get('vikrentitemsvmode', "");
		if (!empty($pvmode) && ctype_digit($pvmode)) {
			$session->set('vikrentitemsvmode', $pvmode);
		} elseif (empty($cur_vmode)) {
			$session->set('vikrentitemsvmode', "12");
		}
		$vmode = (int)$session->get('vikrentitemsvmode', "12");
		$q = "SELECT `id`,`name`,`img`,`idplace`,`units`,`idretplace`,`params` FROM `#__vikrentitems_items` WHERE `id`=".$dbo->quote($aid).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() != 1) {
			VikError::raiseWarning('', 'No Items.');
			$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
			exit;
		}
		$itemrows = $dbo->loadAssoc();
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_gpayments` ORDER BY `#__vikrentitems_gpayments`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$payments = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : '';
		$msg = "";
		$actnow = time();
		$ppickupdate = VikRequest::getString('pickupdate', '', 'request');
		$preleasedate = VikRequest::getString('releasedate', '', 'request');
		$ppickuph = VikRequest::getString('pickuph', '', 'request');
		$ppickupm = VikRequest::getString('pickupm', '', 'request');
		$preleaseh = VikRequest::getString('releaseh', '', 'request');
		$preleasem = VikRequest::getString('releasem', '', 'request');
		$pcustdata = VikRequest::getString('custdata', '', 'request');
		$pcustmail = VikRequest::getString('custmail', '', 'request');
		$psetclosed = VikRequest::getInt('setclosed', 0, 'request');
		$pordstatus = VikRequest::getString('newstatus', '', 'request');
		$pordstatus = (empty($pordstatus) || !in_array($pordstatus, array('confirmed', 'standby')) ? 'confirmed' : $pordstatus);
		$pordstatus = $psetclosed > 0 ? 'confirmed' : $pordstatus;
		$pcountrycode = VikRequest::getString('countrycode', '', 'request');
		$pt_first_name = VikRequest::getString('t_first_name', '', 'request');
		$pt_last_name = VikRequest::getString('t_last_name', '', 'request');
		$pphone = VikRequest::getString('phone', '', 'request');
		$pcustomer_id = VikRequest::getString('customer_id', '', 'request');
		$ppaymentid = VikRequest::getString('payment', '', 'request');
		$pcust_cost = VikRequest::getFloat('cust_cost', 0, 'request');
		$ptaxid = VikRequest::getInt('taxid', '', 'request');
		$ppickuploc = VikRequest::getInt('pickuploc', '', 'request');
		$pdropoffloc = VikRequest::getInt('dropoffloc', '', 'request');
		$pitemquant = VikRequest::getInt('itemquant', 0, 'request');
		$pitemquant = empty($pitemquant) || $pitemquant <= 0 ? 1 : $pitemquant;
		$pdeliveryaddr = VikRequest::getString('deliveryaddr', '', 'request');
		$pdeliverydist = VikRequest::getString('deliverydist', '', 'request');
		$pitemcost = VikRequest::getFloat('itemcost', 0, 'request');
		$pidprice = VikRequest::getInt('idprice', 0, 'request');
		$pidtar = 0;
		$paymentmeth = '';
		if (!empty($ppaymentid) && is_array($payments)) {
			foreach ($payments as $pay) {
				if (intval($pay['id']) == intval($ppaymentid)) {
					$paymentmeth = $pay['id'].'='.$pay['name'];
					break;
				}
			}
		}
		if (!empty($ppickupdate) && !empty($preleasedate)) {
			if (VikRentItems::dateIsValid($ppickupdate) && VikRentItems::dateIsValid($preleasedate)) {
				$first = VikRentItems::getDateTimestamp($ppickupdate, $ppickuph, $ppickupm);
				$second = VikRentItems::getDateTimestamp($preleasedate, $preleaseh, $preleasem);
				$checkhourly = false;
				$hoursdiff = 0;
				if ($second > $first) {
					$secdiff = $second - $first;
					$daysdiff = $secdiff / 86400;
					if (is_int($daysdiff)) {
						if ($daysdiff < 1) {
							$daysdiff = 1;
						}
					} else {
						if ($daysdiff < 1) {
							$daysdiff=1;
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
							}
						}
					}
					// if rate plan ID selected, get the tariff ID
					if (!empty($pidprice) && $pitemcost > 0 && !$psetclosed) {
						$q = "SELECT `id` FROM `#__vikrentitems_dispcost` WHERE `iditem`={$itemrows['id']} AND `days`={$daysdiff} AND `idprice`={$pidprice};";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows()) {
							$pidtar = $dbo->loadResult();
						}
					}
					//if the item is totally booked or locked because someone is paying, the administrator is not able to make a reservation for that item  
					$checknunits = $psetclosed > 0 ? 1 : $pitemquant;
					if ($itemrows['units'] >= $pitemquant && VikRentItems::itemBookable($itemrows['id'], $itemrows['units'], $first, $second, $checknunits) && VikRentItems::itemNotLocked($itemrows['id'], $itemrows['units'], $first, $second, $checknunits)) {
						//Customer
						$q = "SELECT * FROM `#__vikrentitems_custfields` ORDER BY `ordering` ASC;";
						$dbo->setQuery($q);
						$dbo->execute();
						$all_cfields = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
						$customer_cfields = array();
						$customer_extrainfo = array();
						$custdata_parts = explode("\n", $pcustdata);
						foreach ($custdata_parts as $cdataline) {
							if (!(strlen(trim($cdataline)) > 0)) {
								continue;
							}
							$cdata_parts = explode(':', $cdataline);
							if (!(strlen(trim($cdata_parts[0])) > 0) || count($cdata_parts) < 2 || !(strlen(trim($cdata_parts[1])) > 0)) {
								continue;
							}
							foreach ($all_cfields as $cf) {
								if (strpos($cdata_parts[0], JText::translate($cf['name'])) !== false && !array_key_exists($cf['id'], $customer_cfields) && $cf['type'] != 'country') {
									$customer_cfields[$cf['id']] = trim($cdata_parts[1]);
									if (!empty($cf['flag'])) {
										$customer_extrainfo[$cf['flag']] = trim($cdata_parts[1]);
									}
									break;
								}
							}
						}
						$cpin = VikRentItems::getCPinIstance();
						$cpin->is_admin = true;
						$cpin->setCustomerExtraInfo($customer_extrainfo);
						$cpin->saveCustomerDetails($pt_first_name, $pt_last_name, $pcustmail, $pphone, $pcountrycode, $customer_cfields);
						//
						$realback = VikRentItems::getHoursItemAvail() * 3600;
						$realback += $second;
						//Calculate the order total if not empty cust_cost and > 0.00. Add taxes (if not empty), and consider the setting prices tax excluded to increase the total
						$set_total = 0;
						if (!empty($pcust_cost) && $pcust_cost > 0.00 && !$psetclosed) {
							$set_total = $pcust_cost;
							if (!VikRentItems::ivaInclusa() && $ptaxid > 0) {
								$q = "SELECT `i`.`aliq` FROM `#__vikrentitems_iva` AS `i` WHERE `i`.`id`=" . (int)$ptaxid . ";";
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() > 0) {
									$aliq = $dbo->loadResult();
									if (floatval($aliq) > 0.00) {
										$subt = 100 + (float)$aliq;
										$set_total = ($set_total * $subt / 100);
									}
								}
							}
						} elseif (!empty($pidprice) && $pitemcost > 0.00 && !$psetclosed) {
							// one website rate plan was selected, so we calculate total and taxes
							$set_total = $pitemcost;
							// find tax rate assigned to this rate plan
							$q = "SELECT `p`.`id`,`p`.`idiva`,`i`.`aliq` FROM `#__vikrentitems_prices` AS `p` LEFT JOIN `#__vikrentitems_iva` AS `i` ON `p`.`idiva`=`i`.`id` WHERE `p`.`id`=" . $pidprice . ";";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows()) {
								$taxdata = $dbo->loadAssoc();
								$aliq = $taxdata['aliq'];
								if (floatval($aliq) > 0.00) {
									if (!VikRentItems::ivaInclusa()) {
										// add tax to the total amount
										$subt = 100 + (float)$aliq;
										$set_total = ($set_total * $subt / 100);
										// calculate tax
										$set_taxes = $set_total - $pitemcost;
									} else {
										// calculate tax
										$cost_minus_tax = VikRentItems::sayCustCostMinusIva($pitemcost, $taxdata['idiva']);
										$set_taxes += ($pitemcost - $cost_minus_tax);
									}
								}
							}
						}
						//
						$allnewbusy = array();
						$forend = $psetclosed > 0 ? $itemrows['units'] : $pitemquant;
						if ($pordstatus == 'confirmed') {
							for($i = 1; $i <= $forend; $i++) {
								$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES('".$itemrows['id']."','".$first."','".$second."','".$realback."');";
								$dbo->setQuery($q);
								$dbo->execute();
								$lid = $dbo->insertid();
								$allnewbusy[] = $lid;
							}
							$kit_relations = VikRentItems::getKitRelatedItems($itemrows['id']);
							if (count($kit_relations)) {
								//VRI 1.5 - store busy records for the children or parent items, in case of a kit (Group/Set of Items)
								foreach ($kit_relations as $kit_rel) {
									for ($i = 1; $i <= $kit_rel['units']; $i++) {
										$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($kit_rel['iditem']) . ", '" . $first . "', '" . $second . "','" . $realback . "');";
										$dbo->setQuery($q);
										$dbo->execute();
										$lid = $dbo->insertid();
										$allnewbusy[] = $lid;
									}
								}
								//
							}
						}
						$sid = VikRentItems::getSecretLink();
						//VRI 1.1 Rev.2
						$locationvat = '';
						$q = "SELECT `p`.`name`,`i`.`aliq` FROM `#__vikrentitems_places` AS `p` LEFT JOIN `#__vikrentitems_iva` `i` ON `p`.`idiva`=`i`.`id` WHERE `p`.`id`='".intval($ppickuploc)."';";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() > 0) {
							$getdata = $dbo->loadAssocList();
							if (!empty($getdata[0]['aliq'])) {
								$locationvat = $getdata[0]['aliq'];
							}
						}
						//
						//delivery service
						$deliverycost = '';
						if (!empty($pdeliveryaddr)) {
							$pdeliverydist = floatval($pdeliverydist);
							$calcunit = VikRentItems::getDeliveryCalcUnit();
							$costperunit = VikRentItems::getDeliveryCostPerUnit();
							$overcostperunit = floatval(VikRentItems::getItemParam($itemrows['params'], 'overdelcost'));
							if (!empty($overcostperunit) && $overcostperunit > 0.00) {
								$costperunit = $overcostperunit;
							}
							$maxcost = VikRentItems::getDeliveryMaxCost();
							$rounddistance = VikRentItems::getDeliveryRoundDistance();
							$roundcost = VikRentItems::getDeliveryRoundCost();
							$realdist = $pdeliverydist;
							if ($rounddistance == true) {
								$realdist = round($realdist);
							}
							if (!($realdist > 0)) {
								$realdist = 1;
							}
							$deliverycost = $realdist * $costperunit;
							if ($roundcost == true) {
								$deliverycost = round($deliverycost);
							}
							if (!empty($maxcost) && (float)$maxcost > 0 && $deliverycost > (float)$maxcost) {
								$deliverycost = (float)$maxcost;
							}
							if (!($deliverycost > 0)) {
								$deliverycost = $costperunit;
							}
						}
						//
						$pordstatus = $psetclosed > 0 ? 'confirmed' : $pordstatus;
						$q = "INSERT INTO `#__vikrentitems_orders` (`custdata`,`ts`,`status`,`days`,`ritiro`,`consegna`,`custmail`,`sid`,`idplace`,`idreturnplace`,`idpayment`,`hourly`,`order_total`,`locationvat`,`deliverycost`,`closure`) VALUES(".$dbo->quote($pcustdata).",'".$actnow."','".$pordstatus."','".$daysdiff."','".$first."','".$second."',".$dbo->quote($pcustmail).",'".$sid."',".(!empty($ppickuploc) ? "'".$ppickuploc."'" : "NULL").",".(!empty($pdropoffloc) ? "'".$pdropoffloc."'" : "NULL").",".$dbo->quote($paymentmeth).",'".($checkhourly ? "1" : "0")."', ".($set_total > 0 ? $dbo->quote($set_total) : "NULL").", ".(strlen($locationvat) > 0 ? "'".$locationvat."'" : "NULL").", ".(!empty($deliverycost) ? (float)$deliverycost : "NULL").", " . (int)$psetclosed . ");";
						$dbo->setQuery($q);
						$dbo->execute();
						$newoid = $dbo->insertid();
						$msg = $newoid;
						foreach ($allnewbusy as $nbusy) {
							$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES(".(int)$newoid.", ".(int)$nbusy.");";
							$dbo->setQuery($q);
							$dbo->execute();
						}
						$pitemquant = $psetclosed > 0 ? $itemrows['units'] : $pitemquant;
						$q = "INSERT INTO `#__vikrentitems_ordersitems` (`idorder`,`iditem`,`idtar`,`itemquant`,`deliveryaddr`,`deliverydist`,`cust_cost`,`cust_idiva`) VALUES(".(int)$newoid.",".(int)$itemrows['id'].", " . (!empty($pidtar) ? $dbo->quote($pidtar) : "NULL") . ",".(int)$pitemquant.",".(!empty($pdeliveryaddr) ? $dbo->quote($pdeliveryaddr) : "NULL").",".(!empty($pdeliverydist) ? floatval($pdeliverydist) : "NULL").", ".($pcust_cost > 0 ? $dbo->quote($pcust_cost) : "NULL").", ".($pcust_cost > 0 && !empty($ptaxid) ? $dbo->quote($ptaxid) : "NULL").");";
						$dbo->setQuery($q);
						$dbo->execute();
						//Customer Booking
						if (!(intval($cpin->getNewCustomerId()) > 0) && !empty($pcustomer_id) && !empty($pcustomer_pin)) {
							$cpin->setNewPin($pcustomer_pin);
							$cpin->setNewCustomerId($pcustomer_id);
						}
						$cpin->saveCustomerBooking($newoid);
						//end Customer Booking
						if ($pordstatus == 'standy') {
							$q = "INSERT INTO `#__vikrentitems_tmplock` (`iditem`,`ritiro`,`consegna`,`until`,`realback`,`idorder`) VALUES(" . $itemrows['id'] . "," . $first . "," . $second . ",'" . VikRentItems::getMinutesLock(true) . "','" . $realback . "', ".(int)$newoid.");";
							$dbo->setQuery($q);
							$dbo->execute();
							$mainframe->enqueueMessage(JText::translate('VRIQUICKRESWARNSTANDBY'));
							$mainframe->redirect("index.php?option=com_vikrentitems&task=editbusy&cid[]=".$newoid."&standbyquick=1");
						}
					} else {
						$msg = "0";
					}
				} else {
					VikError::raiseWarning('', 'Invalid Dates: current server time is '.date('Y-m-d H:i', $actnow).'. Reservation requested from '.date('Y-m-d H:i', $first).' to '.date('Y-m-d H:i', $second));
				}
			} else {
				VikError::raiseWarning('', 'Invalid Dates');
			}
		}
		
		$busy = "";
		$mints = mktime(0, 0, 0, date('m'), 1, date('Y'));
		$q = "SELECT `b`.*,`ob`.`idorder`,`o`.`closure` FROM `#__vikrentitems_busy` AS `b` LEFT JOIN `#__vikrentitems_ordersbusy` `ob` ON `ob`.`idbusy`=`b`.`id` LEFT JOIN `#__vikrentitems_orders` `o` ON `o`.`id`=`ob`.`idorder` WHERE `b`.`iditem`='".$itemrows['id']."' AND (`b`.`ritiro`>=".$mints." OR `b`.`consegna`>=".$mints.");";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$busy = $dbo->loadAssocList();
		}

		$q = "SELECT `id`,`name` FROM `#__vikrentitems_items` ORDER BY `#__vikrentitems_items`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$allc = $dbo->loadAssocList();

		$pickuparr = array();
		$dropoffarr = array();
		$pickupids = explode(";", $itemrows['idplace']);
		$dropoffids = explode(";", $itemrows['idretplace']);
		if (count($pickupids) > 0) {
			foreach ($pickupids as $k => $pick) {
				if (empty($pick)) {
					unset($pickupids[$k]);
				}
			}
			if (count($pickupids) > 0) {
				$q = "SELECT `id`,`name` FROM `#__vikrentitems_places` WHERE `id` IN (".implode(", ", $pickupids).");";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$pickuparr = $dbo->loadAssocList();
				}
			}
		}
		if (count($dropoffids) > 0) {
			foreach ($dropoffids as $k => $drop) {
				if (empty($drop)) {
					unset($dropoffids[$k]);
				}
			}
			if (count($dropoffids) > 0) {
				$q = "SELECT `id`,`name` FROM `#__vikrentitems_places` WHERE `id` IN (".implode(", ", $dropoffids).");";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$dropoffarr = $dbo->loadAssocList();
				}
			}
		}

		$this->itemrows = &$itemrows;
		$this->msg = &$msg;
		$this->allc = &$allc;
		$this->payments = &$payments;
		$this->busy = &$busy;
		$this->vmode = &$vmode;
		$this->pickuparr = &$pickuparr;
		$this->dropoffarr = &$dropoffarr;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINCALTITLE'), 'vikrentitems');
		JToolBarHelper::cancel( 'cancel', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
