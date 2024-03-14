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

class VikrentitemsViewOconfirm extends JViewVikRentItems {
	function display($tpl = null) {
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$app = JFactory::getApplication();
		$vri_tn = VikRentItems::getTranslator();
		$vrisessioncart = $session->get('vriCart', '');
		$vrisessioncart = is_array($vrisessioncart) ? $vrisessioncart : array();
		$pelemid = VikRequest::getString('elemid', '', 'request');
		$pdays = VikRequest::getString('days', '', 'request');
		$ppickup = VikRequest::getString('pickup', '', 'request');
		$prelease = VikRequest::getString('release', '', 'request');
		$ppriceid = VikRequest::getInt('priceid', 0, 'request');
		$pplace = VikRequest::getString('place', '', 'request');
		$preturnplace = VikRequest::getString('returnplace', '', 'request');
		$pitemquant = VikRequest::getInt('itemquant', '', 'request');
		$pitemquant = empty($pitemquant) || $pitemquant < 1 ? 1 : $pitemquant;
		$ptimeslot = VikRequest::getString('timeslot', '', 'request');
		$pdelivery = VikRequest::getString('delivery', '', 'request');
		$pdeliverysessionval = VikRequest::getInt('deliverysessionval', '', 'request');
		$pitemid = VikRequest::getInt('Itemid', 0, 'request');
		$vrideliverycart = $session->get('vriDeliveryCart', '');
		$vrideliverycart = is_array($vrideliverycart) ? $vrideliverycart : array();
		$usetimeslot = '';
		if (strlen($ptimeslot) > 0) {
			$usetimeslot = VikRentItems::loadTimeSlot($ptimeslot, $vri_tn);
		}
		$nowdf = VikRentItems::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		//vikrentitems 1.1
		$checkhourly = false;
		//vikrentitems 1.1
		$checkhourscharges = 0;
		$calcdays = $pdays;
		//
		$hoursdiff = 0;
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
					//vikrentitems 1.1
					$ehours = intval(round(($newdiff - $maxhmore) / 3600));
					$checkhourscharges = $ehours;
					if ($checkhourscharges > 0) {
						$aehourschbasp = VikRentItems::applyExtraHoursChargesBasp();
					}
					//
				}
			}
		}
		$ftitle = VikRentItems::getFullFrontTitle($vri_tn);
		$q = "SELECT * FROM `#__vikrentitems_gpayments` WHERE `published`='1' ORDER BY `#__vikrentitems_gpayments`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$payments = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		if (!empty($payments)) {
			$vri_tn->translateContents($payments, '#__vikrentitems_gpayments');
		}
		$q = "SELECT * FROM `#__vikrentitems_custfields` ORDER BY `#__vikrentitems_custfields`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$cfields = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
		if (count($cfields)) {
			$vri_tn->translateContents($cfields, '#__vikrentitems_custfields');
		}
		$countries = '';
		if (count($cfields)) {
			foreach ($cfields as $cf) {
				if ($cf['type'] == 'country') {
					$q = "SELECT * FROM `#__vikrentitems_countries` ORDER BY `#__vikrentitems_countries`.`country_name` ASC;";
					$dbo->setQuery($q);
					$dbo->execute();
					$countries = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
					break;
				}
			}
		}
		$pcouponcode = VikRequest::getString('couponcode', '', 'request');
		$coupon = "";
		if (strlen($pcouponcode) > 0) {
			$coupon = VikRentItems::getCouponInfo($pcouponcode);
			if (is_array($coupon)) {
				$coupondateok = true;
				if (strlen($coupon['datevalid']) > 0) {
					$dateparts = explode("-", $coupon['datevalid']);
					$pickinfo = getdate($ppickup);
					$dropinfo = getdate($prelease);
					$checkpick = mktime(0, 0, 0, $pickinfo['mon'], $pickinfo['mday'], $pickinfo['year']);
					$checkdrop = mktime(0, 0, 0, $dropinfo['mon'], $dropinfo['mday'], $dropinfo['year']);
					if (!($checkpick >= $dateparts[0] && $checkpick <= $dateparts[1] && $checkdrop >= $dateparts[0] && $checkdrop <= $dateparts[1])) {
						$coupondateok = false;
					}
				}
				if ($coupondateok == true) {
					$couponitemok = true;
					if ( $coupon['allvehicles'] == 0) {
						if (!empty($pelemid)) {
							if (!(preg_match("/;".$pelemid.";/i", $coupon['iditems']))) {
								$couponitemok = false;
							}
						} elseif (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
							$allitems = array_keys($vrisessioncart);
							foreach ($allitems as $iid) {
								if (!(preg_match("/;".$iid.";/i", $coupon['iditems']))) {
									$couponitemok = false;
								}
							}
						}
					}
					if ($couponitemok !== true) {
						VikError::raiseWarning('', JText::translate('VRICOUPONINVITEM'));
						$coupon = "";
					}
				} else {
					VikError::raiseWarning('', JText::translate('VRICOUPONINVDATES'));
					$coupon = "";
				}
			} else {
				VikError::raiseWarning('', JText::translate('VRICOUPONNOTFOUND'));
				$coupon = "";
			}
		}
		$this->coupon = $coupon;
		//Customer Details
		$cpin = VikRentItems::getCPinIstance();
		$customer_details = $cpin->loadCustomerDetails();
		//
		/**
		 * The request variable 'elemid' may not be empty if the URL
		 * is rewritten and it's of type Item Details. Therefore, we
		 * display the cart if this is empty or 'priceid' is empty.
		 * 
		 * @since 	March 4th 2019
		 */
		if (empty($pelemid) || empty($ppriceid)) {
			//set valid $calcdays if hourly charges
			if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
				$price = array($vrisessioncart[key($vrisessioncart)][0]['price']);
				$checkiditem = key($vrisessioncart);
				if ($checkhourscharges > 0 && $aehourschbasp == true) {
					$ret = VikRentItems::applyExtraHoursChargesItem($price, $checkiditem, $checkhourscharges, $daysdiff, false, true, true);
					$price = $ret['return'];
					$calcdays = $ret['days'];
				}
				if ($checkhourscharges > 0 && $aehourschbasp == false) {
					$price = VikRentItems::extraHoursSetPreviousFareItem($price, $checkiditem, $checkhourscharges, $daysdiff, true);
					$price = VikRentItems::applySeasonsItem($price, $ppickup, $prelease, $pplace);
					$ret = VikRentItems::applyExtraHoursChargesItem($price, $checkiditem, $checkhourscharges, $daysdiff, true, true, true);
					$price = $ret['return'];
					$calcdays = $ret['days'];
				}
				if ($checkhourscharges > 0) {
					$pdays = $daysdiff;
				}
			}
			//
			$this->days = $pdays;
			$this->vrisessioncart = $vrisessioncart;
			$this->calcdays = $calcdays;
			$this->first = $ppickup;
			$this->second = $prelease;
			$this->ftitle = $ftitle;
			$this->place = $pplace;
			$this->returnplace = $preturnplace;
			$this->payments = $payments;
			$this->cfields = $cfields;
			$this->customer_details = $customer_details;
			$this->countries = $countries;
			$this->vri_tn = $vri_tn;
			//theme
			$theme = VikRentItems::getTheme();
			if ($theme != 'default') {
				$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'oconfirm';
				if (is_dir($thdir)) {
					$this->_setPath('template', $thdir.DS);
				}
			}
			//
			parent::display($tpl);
		} else {
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=" . $dbo->quote($pelemid) . (!empty($pplace) ? " AND `idplace` LIKE ".$dbo->quote("%".$pplace.";%") : "") . ";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$item = $dbo->loadAssocList();
				$vri_tn->translateContents($item, '#__vikrentitems_items');
				$groupdays = VikRentItems::getGroupDays($ppickup, $prelease, $daysdiff);
				$morehst = VikRentItems::getHoursItemAvail() * 3600;
				// VRI 1.6 - Allow pick ups on drop offs
				$picksondrops = VikRentItems::allowPickOnDrop();
				//
				$validtime = true;
				$check = "SELECT `id`,`ritiro`,`consegna` FROM `#__vikrentitems_busy` WHERE `iditem`='" . $item[0]['id'] . "';";
				$dbo->setQuery($check);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$busy = $dbo->loadAssocList();
					foreach ($groupdays as $gday) {
						$bfound = 0;
						foreach ($busy as $bu) {
							if ($gday >= $bu['ritiro'] && $gday <= ($morehst + $bu['consegna'])) {
								if ($picksondrops && !($gday > $bu['ritiro'] && $gday < ($morehst + $bu['consegna'])) && $gday != $bu['ritiro']) {
									// VRI 1.6 - pick ups on drop offs allowed
									continue;
								}
								$bfound++;
							}
						}
						if (($bfound + $pitemquant) > $item[0]['units']) {
							$validtime = false;
							break;
						}
					}
				}
				//
				if ($validtime == true) {
					if (!empty($ppriceid)) {
						$price = array();
						$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `iditem`='" . $item[0]['id'] . "' AND `days`=" . $dbo->quote($pdays) . " AND `idprice`=" . $dbo->quote($ppriceid) . ";";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 1) {
							$price = $dbo->loadAssocList();
							if ($checkhourly) {
								$price = VikRentItems::applyHourlyPricesItem($price, $hoursdiff, $item[0]['id'], true);
							}
						} elseif ($checkhourly) {
							$price = VikRentItems::applyHourlyPricesItem(array(0 => array('idprice' => $ppriceid)), $hoursdiff, $item[0]['id'], true);
						}
						if (count($price) > 0 && array_key_exists('cost', $price[0])) {
							//vikrentitems 1.1
							if ($checkhourscharges > 0 && $aehourschbasp == true) {
								$ret = VikRentItems::applyExtraHoursChargesItem($price, $item[0]['id'], $checkhourscharges, $daysdiff, false, true, true);
								$price = $ret['return'];
								$calcdays = $ret['days'];
							}
							if ($checkhourscharges > 0 && $aehourschbasp == false) {
								$price = VikRentItems::extraHoursSetPreviousFareItem($price, $item[0]['id'], $checkhourscharges, $daysdiff, true);
								$price = VikRentItems::applySeasonsItem($price, $ppickup, $prelease, $pplace);
								$ret = VikRentItems::applyExtraHoursChargesItem($price, $item[0]['id'], $checkhourscharges, $daysdiff, true, true, true);
								$price = $ret['return'];
								$calcdays = $ret['days'];
							} else {
								$price = VikRentItems::applySeasonsItem($price, $ppickup, $prelease, $pplace);
							}
							$price = VikRentItems::applyItemDiscounts($price, $item[0]['id'], $pitemquant);
							//set $pdays as the regular calculation for dayValidTs()
							if ($checkhourscharges > 0) {
								$pdays = $daysdiff;
							}
							//
							$selopt = array();
							$q = "SELECT * FROM `#__vikrentitems_optionals`;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() > 0) {
								$optionals = $dbo->loadAssocList();
								$vri_tn->translateContents($optionals, '#__vikrentitems_optionals');
								foreach ($optionals as $opt) {
									$tmpvar = VikRequest::getString('optid' . $opt['id'], '', 'request');
									if (!empty($opt['specifications'])) {
										if (!empty($tmpvar)) {
											$opt['quan'] = 1;
											$optspeccosts = VikRentItems::getOptionSpecIntervalsCosts($opt['specifications']);
											$optspecnames = VikRentItems::getOptionSpecIntervalsNames($opt['specifications']);
											$optorigname = $opt['name'];
											$opt['cost'] = $optspeccosts[($tmpvar - 1)];
											$opt['name'] = $optorigname.': '.$optspecnames[($tmpvar - 1)];
											$opt['specintv'] = $tmpvar;
											$selopt[] = $opt;
										}
									} else {
										if (!empty($tmpvar)) {
											//check options to be applied only-once
											$can_apply_option = true;
											if ($opt['onlyonce'] > 0 && count($vrisessioncart)) {
												foreach ($vrisessioncart as $items) {
													foreach ($items as $cartitem) {
														if (isset($cartitem['options']) && is_array($cartitem['options'])) {
															foreach ($cartitem['options'] as $cartitemopt) {
																if ($cartitemopt['id'] == $opt['id']) {
																	$can_apply_option = false;
																	break 3;
																}
															}
														}
													}
												}
											}
											//
											if ($can_apply_option) {
												$opt['quan'] = $tmpvar;
												$selopt[] = $opt;
											}
										}
									}
								}
							}
							if (!count($selopt)) {
								$selopt = "";
							}
							if (VikRentItems::dayValidTs($pdays, $ppickup, $prelease)) {
								//add session values
								if (array_key_exists($item[0]['id'], $vrisessioncart)) {
									$equalfound = false;
									foreach ($vrisessioncart[$item[0]['id']] as $ind => $curitval) {
										if ($curitval['price'] == $price[0] && $curitval['options'] == $selopt) {
											$equalfound = true;
											$newitemunit = $vrisessioncart[$item[0]['id']][$ind]['units'] + $pitemquant;
											$newind = $ind;
											break;
										}
									}
									if (!$equalfound) {
										$newitemunit = $pitemquant;
										$newind = count($vrisessioncart[$item[0]['id']]);
									}
								} else {
									$newitemunit = $pitemquant;
									$newind = 0;
								}
								//delivery service
								if (intval($pdelivery) == 1 && (int)$pdeliverysessionval > 0 && count($vrideliverycart) > 0) {
									if (array_key_exists($pdeliverysessionval, $vrideliverycart)) {
										$vrideliverycart[$pdeliverysessionval]['vricartid'][0] = $item[0]['id'];
										$vrideliverycart[$pdeliverysessionval]['vricartid'][1] = $newind;
										$vrisessioncart[$item[0]['id']][$newind]['delivery'] = $vrideliverycart[$pdeliverysessionval];
										$session->set('vriDeliveryCart', $vrideliverycart);
									} else {
										VikError::raiseWarning('', JText::translate('VRIDELIVERYSESSERR'));
									}
								}
								//end delivery service
								$vrisessioncart[$item[0]['id']][$newind]['info'] = $item[0];
								$vrisessioncart[$item[0]['id']][$newind]['units'] = $newitemunit;
								$vrisessioncart[$item[0]['id']][$newind]['options'] = $selopt;
								$vrisessioncart[$item[0]['id']][$newind]['price'] = $price[0];
								if (is_array($usetimeslot) && count($usetimeslot) > 0) {
									$tsparts = explode(',', $usetimeslot['iditems']);
									if (in_array('-'.$item[0]['id'].'-', $tsparts)) {
										$vrisessioncart[$item[0]['id']][$newind]['timeslot']['id'] = $usetimeslot['id'];
										$vrisessioncart[$item[0]['id']][$newind]['timeslot']['name'] = $usetimeslot['tname'];
									}
								}
								$session->set('vriCart', $vrisessioncart);
								//end add session values

								// VRI 1.6 - to avoid allowing the page refresh to re-submit data, we redirect to this same View
								$app->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&task=oconfirm&place=" . $pplace . "&returnplace=" . $preturnplace . "&days=" . $pdays . "&pickup=" . $ppickup . "&release=" . $prelease . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
								exit;
								//
								
								/**
								 * @deprecated 	we no longer render the View to avoid POST values to be re-submitted with a page refresh
								 *
								 * @since 1.6
								$this->days = $pdays;
								$this->vrisessioncart = $vrisessioncart;
								$this->calcdays = $calcdays;
								$this->first = $ppickup;
								$this->second = $prelease;
								$this->ftitle = $ftitle;
								$this->place = $pplace;
								$this->returnplace = $preturnplace;
								$this->payments = $payments;
								$this->cfields = $cfields;
								$this->customer_details = $customer_details;
								$this->countries = $countries;
								$this->vri_tn = $vri_tn;
								//theme
								$theme = VikRentItems::getTheme();
								if ($theme != 'default') {
									$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'oconfirm';
									if (is_dir($thdir)) {
										$this->_setPath('template', $thdir.DS);
									}
								}
								//
								parent::display($tpl);
								 */
							} else {
								showSelectVRI(JText::translate('VRERRCALCTAR'));
							}
						} else {
							showSelectVRI(JText::translate('VRTARNOTFOUND'));
						}
					} else {
						showSelectVRI(JText::translate('VRNOTARSELECTED'));
					}
				} else {
					showSelectVRI(JText::translate('VRIARNOTCONS') . " " . date($df . ' H:i', $ppickup) . " " . JText::translate('VRIARNOTCONSTO') . " " . date($df . ' H:i', $prelease));
				}
			} else {
				showSelectVRI(JText::translate('VRIARNOTFND'));
			}
		}
	}
}
