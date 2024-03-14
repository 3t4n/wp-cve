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

class VikrentitemsViewShowprc extends JViewVikRentItems {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
		$session = JFactory::getSession();
		$vri_tn = VikRentItems::getTranslator();
		$pitemopt = VikRequest::getInt('itemopt', '', 'request');
		$pdays = VikRequest::getString('days', '', 'request');
		$ppickup = VikRequest::getString('pickup', '', 'request');
		$prelease = VikRequest::getString('release', '', 'request');
		$pplace = VikRequest::getInt('place', '', 'request');
		$preturnplace = VikRequest::getInt('returnplace', '', 'request');
		$pitemquant = VikRequest::getInt('itemquant', '', 'request');
		$pitemquant = empty($pitemquant) || $pitemquant < 1 ? 1 : $pitemquant;
		$ptimeslot = VikRequest::getString('timeslot', '', 'request');
		$usetimeslot = '';
		if (strlen($ptimeslot) > 0) {
			$usetimeslot = VikRentItems::loadTimeSlot($ptimeslot, $vri_tn);
			if (is_array($usetimeslot) && count($usetimeslot) > 0) {
				$tsparts = explode(',', $usetimeslot['iditems']);
				if (!in_array('-'.$pitemopt.'-', $tsparts)) {
					$usetimeslot = '';
				}
			}
		}
		$nowdf = VikRentItems::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$q = "SELECT `units` FROM `#__vikrentitems_items` WHERE `id`=" . $dbo->quote($pitemopt) . ";";
		$dbo->setQuery($q);
		$dbo->execute();
		$units = $dbo->loadResult();
		//vikrentitems 1.1
		$checkhourly = false;
		//vikrentitems 1.1
		$checkhourscharges = 0;
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
		$groupdays = VikRentItems::getGroupDays($ppickup, $prelease, $daysdiff);
		$morehst = VikRentItems::getHoursItemAvail() * 3600;
		// VRI 1.6 - Allow pick ups on drop offs
		$picksondrops = VikRentItems::allowPickOnDrop();
		//
		$goonunits = true;
		$check = "SELECT `id`,`ritiro`,`consegna` FROM `#__vikrentitems_busy` WHERE `iditem`=" . $dbo->quote($pitemopt) . ";";
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
				if (($bfound + $pitemquant) > $units) {
					$goonunits = false;
					break;
				}
			}
		}
		if ($pitemquant > $units) {
			$goonunits = false;
		}
		//
		if ($goonunits) {
			// VRI 1.7 - Closed rate plans on these dates
			$itemrpclosed = VikRentItems::getItemRplansClosedInDates(array($pitemopt), $ppickup, $daysdiff);
			//
			$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `days`=" . $dbo->quote($pdays) . " AND `iditem`=" . $dbo->quote($pitemopt) . " ORDER BY `#__vikrentitems_dispcost`.`cost` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$tars = $dbo->loadAssocList();
				// VRI 1.7 - Closed rate plans on these dates
				if (count($itemrpclosed) > 0 && array_key_exists($pitemopt, $itemrpclosed)) {
					foreach ($tars as $kk => $tt) {
						if (array_key_exists('idprice', $tt) && array_key_exists($tt['idprice'], $itemrpclosed[$pitemopt])) {
							unset($tars[$kk]);
						}
					}
				}
				//vikrentitems 1.1
				if ($checkhourly) {
					$tars = VikRentItems::applyHourlyPricesItem($tars, $hoursdiff, $pitemopt);
				}
				//
				//vikrentitems 1.1
				if ($checkhourscharges > 0 && $aehourschbasp == true) {
					$tars = VikRentItems::applyExtraHoursChargesItem($tars, $pitemopt, $checkhourscharges, $daysdiff);
				}
				//
				$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=" . $dbo->quote($pitemopt) . "" . (!empty($pplace) ? " AND `idplace` LIKE ".$dbo->quote("%".$pplace.";%") : "") . ";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$item = $dbo->loadAssocList();
					$vri_tn->translateContents($item, '#__vikrentitems_items');
					$item_params = !empty($item[0]['jsparams']) ? json_decode($item[0]['jsparams'], true) : array();
					$quantity_allowed = true;
					if (intval($item_params['minquant']) > 1 && intval($item_params['minquant']) < $pitemquant) {
						$quantity_allowed = false;
					}
					$kit_relations = array();
					if (intval($item[0]['avail']) == 1 && $quantity_allowed === true) {
						if (VikRentItems::dayValidTs($pdays, $ppickup, $prelease)) {
							//vikrentitems 1.1
							if ($checkhourscharges > 0 && $aehourschbasp == false) {
								$tars = VikRentItems::extraHoursSetPreviousFareItem($tars, $pitemopt, $checkhourscharges, $daysdiff);
								$tars = VikRentItems::applySeasonsItem($tars, $ppickup, $prelease, $pplace);
								$tars = VikRentItems::applyExtraHoursChargesItem($tars, $pitemopt, $checkhourscharges, $daysdiff, true);
							} else {
								$tars = VikRentItems::applySeasonsItem($tars, $ppickup, $prelease, $pplace);
							}
							//
							$tars = VikRentItems::applyItemDiscounts($tars, $pitemopt, $pitemquant);
							//apply locations fee
							if (!empty($pplace) && !empty($preturnplace)) {
								$locfee = VikRentItems::getLocFee($pplace, $preturnplace);
								if ($locfee) {
									//VikRentItems 1.1 - Location fees overrides
									if (strlen($locfee['losoverride']) > 0) {
										$arrvaloverrides = array();
										$valovrparts = explode('_', $locfee['losoverride']);
										foreach ($valovrparts as $valovr) {
											if (!empty($valovr)) {
												$ovrinfo = explode(':', $valovr);
												$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
											}
										}
										if (array_key_exists($pdays, $arrvaloverrides)) {
											$locfee['cost'] = $arrvaloverrides[$pdays];
										}
									}
									//end VikRentItems 1.1 - Location fees overrides
									$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $pdays) : $locfee['cost'];
									$lfarr = array ();
									foreach ($tars as $kat => $at) {
										$newcost = $at['cost'] + $locfeecost;
										$at['cost'] = $newcost;
										$at['locfee'] = $locfeecost;
										$lfarr[$kat] = $at;
									}
									$tars = $lfarr;
								}
							}
							//
							//delivery service
							$vrideliverycart = $session->get('vriDeliveryCart', '');
							$vrideliverycart = is_array($vrideliverycart) ? $vrideliverycart : array();
							$lastdelivery = array();
							if (count($vrideliverycart) > 0) {
								$lastdelivery = end($vrideliverycart);
							}
							//
							//current cart
							$vrisessioncart = $session->get('vriCart', '');
							$vrisessioncart = is_array($vrisessioncart) ? $vrisessioncart : array();
							//
							//VRI 1.5
							if ($item[0]['isgroup'] > 0) {
								$q="SELECT `g`.`childid`,`g`.`units`,`i`.`name`,`i`.`units` AS `maxunits` FROM `#__vikrentitems_groupsrel` AS `g` LEFT JOIN `#__vikrentitems_items` AS `i` ON `g`.`childid`=`i`.`id` WHERE `g`.`parentid`=".(int)$item[0]['id'].";";
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() > 0) {
									$kit_relations = $dbo->loadAssocList();
								}
							}
							//
							$this->tars = $tars;
							$this->item = $item[0];
							$this->kit_relations = $kit_relations;
							$this->pickup = $ppickup;
							$this->release = $prelease;
							$this->place = $pplace;
							$this->itemquant = $pitemquant;
							$this->timeslot = $usetimeslot;
							$this->lastdelivery = $lastdelivery;
							$this->vrisessioncart = $vrisessioncart;
							$this->vri_tn = $vri_tn;
							//theme
							$theme = VikRentItems::getTheme();
							if ($theme != 'default') {
								$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'showprc';
								if (is_dir($thdir)) {
									$this->_setPath('template', $thdir.DS);
								}
							}
							//
							parent::display($tpl);
						} else {
							showSelectVRI(JText::translate('VRERRCALCTAR'));
						}
					} else {
						showSelectVRI(JText::translate('VRIARNOTAV'));
					}
				} else {
					showSelectVRI(JText::translate('VRIARNOTFND'));
				}
			} else {
				showSelectVRI(JText::translate('VRNOTARFNDSELO'));
			}
		} else {
			showSelectVRI(JText::sprintf('VRIALLUNITSNOTRIT', $pitemquant) . " " . date($df . ' H:i', $ppickup) . " " . JText::translate('VRIARNOTCONSTO') . " " . date($df . ' H:i', $prelease));
		}
	}
}
