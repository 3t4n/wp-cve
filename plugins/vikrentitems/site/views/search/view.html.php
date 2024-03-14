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

class VikrentitemsViewSearch extends JViewVikRentItems {

	/**
	 * Response array for the request.
	 * 
	 * @var 	array
	 * 
	 * @since 	1.7
	 */
	protected $response = array('e4j.error' => 'No items found.');

	public function display($tpl = null) {
		// allow back button navigation even if the previous page was rendered via POST request
		JFactory::getApplication()->setHeader('Cache-Control', 'max-age=300, must-revalidate');

		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$vri_tn = VikRentItems::getTranslator();
		$getjson = VikRequest::getInt('getjson', 0, 'request');
		if ($getjson) {
			// request integrity check before sending to output a JSON
			if (md5('vri.e4j.vri') != VikRequest::getString('e4jauth', '', 'request', VIKREQUEST_ALLOWRAW)) {
				$this->setVriError('Invalid Authentication.');
				return;
			}
		}
		if ($getjson || VikRentItems::allowRent()) {
			$pplace = VikRequest::getString('place', '', 'request');
			$returnplace = VikRequest::getString('returnplace', '', 'request');
			$ppickupdate = VikRequest::getString('pickupdate', '', 'request');
			$ppickupm = VikRequest::getString('pickupm', '', 'request');
			$ppickuph = VikRequest::getString('pickuph', '', 'request');
			$preleasedate = VikRequest::getString('releasedate', '', 'request');
			$preleasem = VikRequest::getString('releasem', '', 'request');
			$preleaseh = VikRequest::getString('releaseh', '', 'request');
			$pcategories = VikRequest::getString('categories', '', 'request');
			//itemdetails
			$pitemquant = VikRequest::getInt('itemquant', '', 'request');
			$pitemquant = empty($pitemquant) || $pitemquant < 1 ? 1 : $pitemquant;
			$pitemdetail = VikRequest::getInt('itemdetail', '', 'request');
			$pitemid = VikRequest::getInt('Itemid', '', 'request');
			//time slots
			$nowdf = VikRentItems::getDateFormat();
			if ($nowdf == "%d/%m/%Y") {
				$df = 'd/m/Y';
			} elseif ($nowdf == "%m/%d/%Y") {
				$df = 'm/d/Y';
			} else {
				$df = 'Y/m/d';
			}
			$nowtf = VikRentItems::getTimeFormat();
			$ptimeslot = VikRequest::getString('timeslot', '', 'request');
			$usetimeslot = '';
			if (strlen($ptimeslot) > 0) {
				$usetimeslot = VikRentItems::loadTimeSlot($ptimeslot, $vri_tn);
				if (is_array($usetimeslot) && count($usetimeslot) > 0) {
					$usefirst = VikRentItems::getDateTimestamp($ppickupdate, 0, 0);
					$usefirst += 86400 * $usetimeslot['days'];
					$ppickuph = $usetimeslot['fromh'] < 10 ? '0'.$usetimeslot['fromh'] : $usetimeslot['fromh'];
					$ppickupm = $usetimeslot['fromm'] < 10 ? '0'.$usetimeslot['fromm'] : $usetimeslot['fromm'];
					$preleaseh = $usetimeslot['toh'] < 10 ? '0'.$usetimeslot['toh'] : $usetimeslot['toh'];
					$preleasem = $usetimeslot['tom'] < 10 ? '0'.$usetimeslot['tom'] : $usetimeslot['tom'];
					if ($usetimeslot['fromh'] > $usetimeslot['toh']) {
						//day after
						$preleasedate = date($df, $usefirst + 86400);
					} else {
						$preleasedate = date($df, $usefirst);
					}
				}
			}
			//
			if (!empty($ppickupdate) && !empty($preleasedate)) {
				if (VikRentItems::dateIsValid($ppickupdate) && VikRentItems::dateIsValid($preleasedate)) {
					$first = VikRentItems::getDateTimestamp($ppickupdate, $ppickuph, $ppickupm);
					$second = VikRentItems::getDateTimestamp($preleasedate, $preleaseh, $preleasem);
					$actnow = time();
					$today_bookings = VikRentItems::todayBookings();
					if ($today_bookings) {
						$actnow = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
					}
					$mindaysadv = VikRentItems::getMinDaysAdvance();
					$lim_mindays = $actnow;
					if ($mindaysadv > 0) {
						$todaybasets = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
						$lim_mindays = $mindaysadv > 1 ? strtotime("+$mindaysadv days", $todaybasets) : strtotime("+1 day", $todaybasets);
					}
					$checkhourly = false;
					//vikrentitems 1.1
					$checkhourscharges = 0;
					//
					$hoursdiff = 0;
					if ($second > $first && $first >= $actnow && $first >= $lim_mindays) {
						$secdiff = $second - $first;
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

						// VRI 1.7 - Restrictions
						$allrestrictions = VikRentItems::loadRestrictions(false);
						$restrictions = VikRentItems::globalRestrictions($allrestrictions);
						$restrcheckin = getdate($first);
						$restrcheckout = getdate($second);
						$restrictionsvalid = true;
						$restrictions_affcount = 0;
						$restrictionerrmsg = '';
						if (count($restrictions) > 0) {
							if (array_key_exists($restrcheckin['mon'], $restrictions)) {
								//restriction found for this month, checking:
								$restrictions_affcount++;
								if (strlen($restrictions[$restrcheckin['mon']]['wday']) > 0) {
									$rvalidwdays = array($restrictions[$restrcheckin['mon']]['wday']);
									if (strlen($restrictions[$restrcheckin['mon']]['wdaytwo']) > 0) {
										$rvalidwdays[] = $restrictions[$restrcheckin['mon']]['wdaytwo'];
									}
									if (!in_array($restrcheckin['wday'], $rvalidwdays)) {
										$restrictionsvalid = false;
										$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYARRIVAL', VikRentItems::sayMonth($restrcheckin['mon']), VikRentItems::sayWeekDay($restrictions[$restrcheckin['mon']]['wday']).(strlen($restrictions[$restrcheckin['mon']]['wdaytwo']) > 0 ? '/'.VikRentItems::sayWeekDay($restrictions[$restrcheckin['mon']]['wdaytwo']) : ''));
									} elseif ($restrictions[$restrcheckin['mon']]['multiplyminlos'] == 1) {
										if (($daysdiff % $restrictions[$restrcheckin['mon']]['minlos']) != 0) {
											$restrictionsvalid = false;
											$restrictionerrmsg = JText::sprintf('VRRESTRERRMULTIPLYMINLOS', VikRentItems::sayMonth($restrcheckin['mon']), $restrictions[$restrcheckin['mon']]['minlos']);
										}
									}
									$comborestr = VikRentItems::parseJsDrangeWdayCombo($restrictions[$restrcheckin['mon']]);
									if (count($comborestr) > 0) {
										if (array_key_exists($restrcheckin['wday'], $comborestr)) {
											if (!in_array($restrcheckout['wday'], $comborestr[$restrcheckin['wday']])) {
												$restrictionsvalid = false;
												$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYCOMBO', VikRentItems::sayMonth($restrcheckin['mon']), VikRentItems::sayWeekDay($comborestr[$restrcheckin['wday']][0]).(count($comborestr[$restrcheckin['wday']]) == 2 ? '/'.VikRentItems::sayWeekDay($comborestr[$restrcheckin['wday']][1]) : ''), VikRentItems::sayWeekDay($restrcheckin['wday']));
											}
										}
									}
								} elseif (!empty($restrictions[$restrcheckin['mon']]['ctad']) || !empty($restrictions[$restrcheckin['mon']]['ctdd'])) {
									if (!empty($restrictions[$restrcheckin['mon']]['ctad'])) {
										$ctarestrictions = explode(',', $restrictions[$restrcheckin['mon']]['ctad']);
										if (in_array('-'.$restrcheckin['wday'].'-', $ctarestrictions)) {
											$restrictionsvalid = false;
											$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYCTAMONTH', VikRentItems::sayWeekDay($restrcheckin['wday']), VikRentItems::sayMonth($restrcheckin['mon']));
										}
									}
									if (!empty($restrictions[$restrcheckin['mon']]['ctdd'])) {
										$ctdrestrictions = explode(',', $restrictions[$restrcheckin['mon']]['ctdd']);
										if (in_array('-'.$restrcheckout['wday'].'-', $ctdrestrictions)) {
											$restrictionsvalid = false;
											$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYCTDMONTH', VikRentItems::sayWeekDay($restrcheckout['wday']), VikRentItems::sayMonth($restrcheckin['mon']));
										}
									}
								}
								if (!empty($restrictions[$restrcheckin['mon']]['maxlos']) && $restrictions[$restrcheckin['mon']]['maxlos'] > 0 && $restrictions[$restrcheckin['mon']]['maxlos'] > $restrictions[$restrcheckin['mon']]['minlos']) {
									if ($daysdiff > $restrictions[$restrcheckin['mon']]['maxlos']) {
										$restrictionsvalid = false;
										$restrictionerrmsg = JText::sprintf('VRRESTRERRMAXLOSEXCEEDED', VikRentItems::sayMonth($restrcheckin['mon']), $restrictions[$restrcheckin['mon']]['maxlos']);
									}
								}
								if ($daysdiff < $restrictions[$restrcheckin['mon']]['minlos']) {
									$restrictionsvalid = false;
									$restrictionerrmsg = JText::sprintf('VRRESTRERRMINLOSEXCEEDED', VikRentItems::sayMonth($restrcheckin['mon']), $restrictions[$restrcheckin['mon']]['minlos']);
								}
							} elseif (array_key_exists('range', $restrictions)) {
								foreach ($restrictions['range'] as $restr) {
									if ($restr['dfrom'] <= $first && ($restr['dto'] + 82799) >= $first) {
										//restriction found for this date range, checking:
										$restrictions_affcount++;
										if (strlen($restr['wday']) > 0) {
											$rvalidwdays = array($restr['wday']);
											if (strlen($restr['wdaytwo']) > 0) {
												$rvalidwdays[] = $restr['wdaytwo'];
											}
											if (!in_array($restrcheckin['wday'], $rvalidwdays)) {
												$restrictionsvalid = false;
												$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYARRIVALRANGE', VikRentItems::sayWeekDay($restr['wday']).(strlen($restr['wdaytwo']) > 0 ? '/'.VikRentItems::sayWeekDay($restr['wdaytwo']) : ''));
											} elseif ($restr['multiplyminlos'] == 1) {
												if (($daysdiff % $restr['minlos']) != 0) {
													$restrictionsvalid = false;
													$restrictionerrmsg = JText::sprintf('VRRESTRERRMULTIPLYMINLOSRANGE', $restr['minlos']);
												}
											}
											$comborestr = VikRentItems::parseJsDrangeWdayCombo($restr);
											if (count($comborestr) > 0) {
												if (array_key_exists($restrcheckin['wday'], $comborestr)) {
													if (!in_array($restrcheckout['wday'], $comborestr[$restrcheckin['wday']])) {
														$restrictionsvalid = false;
														$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYCOMBORANGE', VikRentItems::sayWeekDay($comborestr[$restrcheckin['wday']][0]).(count($comborestr[$restrcheckin['wday']]) == 2 ? '/'.VikRentItems::sayWeekDay($comborestr[$restrcheckin['wday']][1]) : ''), VikRentItems::sayWeekDay($restrcheckin['wday']));
													}
												}
											}
										} elseif (!empty($restr['ctad']) || !empty($restr['ctdd'])) {
											if (!empty($restr['ctad'])) {
												$ctarestrictions = explode(',', $restr['ctad']);
												if (in_array('-'.$restrcheckin['wday'].'-', $ctarestrictions)) {
													$restrictionsvalid = false;
													$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYCTARANGE', VikRentItems::sayWeekDay($restrcheckin['wday']));
												}
											}
											if (!empty($restr['ctdd'])) {
												$ctdrestrictions = explode(',', $restr['ctdd']);
												if (in_array('-'.$restrcheckout['wday'].'-', $ctdrestrictions)) {
													$restrictionsvalid = false;
													$restrictionerrmsg = JText::sprintf('VRRESTRERRWDAYCTDRANGE', VikRentItems::sayWeekDay($restrcheckout['wday']));
												}
											}
										}
										if (!empty($restr['maxlos']) && $restr['maxlos'] > 0 && $restr['maxlos'] > $restr['minlos']) {
											if ($daysdiff > $restr['maxlos']) {
												$restrictionsvalid = false;
												$restrictionerrmsg = JText::sprintf('VRRESTRERRMAXLOSEXCEEDEDRANGE', $restr['maxlos']);
											}
										}
										if ($daysdiff < $restr['minlos']) {
											$restrictionsvalid = false;
											$restrictionerrmsg = JText::sprintf('VRRESTRERRMINLOSEXCEEDEDRANGE', $restr['minlos']);
										}
										if ($restrictionsvalid == false) {
											break;
										}
									}
								}
							}
						}
						if (!(count($restrictions) > 0) || $restrictions_affcount <= 0) {
							//Check global MinLOS (only in case there are no restrictions affecting these dates or no restrictions at all)
							$globminlos = (int)VikRentItems::setDropDatePlus();
							if ($globminlos > 1 && $daysdiff < $globminlos) {
								$restrictionsvalid = false;
								$restrictionerrmsg = JText::sprintf('VRRESTRERRMINLOSEXCEEDEDRANGE', $globminlos);
							}
							//
						}
						//

						if ($restrictionsvalid === true) {
							$q = "SELECT `p`.*,`tp`.`name` as `pricename` FROM `#__vikrentitems_dispcost` AS `p` LEFT JOIN `#__vikrentitems_prices` AS `tp` ON `p`.`idprice`=`tp`.`id` WHERE `p`.`days`='" . $daysdiff . "' ORDER BY `p`.`cost` ASC, `p`.`iditem` ASC;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() > 0) {
								$tars = $dbo->loadAssocList();
								$arrtar = array();
								foreach ($tars as $tar) {
									$arrtar[$tar['iditem']][] = $tar;
								}
								//vikrentitems 1.1
								if ($checkhourly) {
									$arrtar = VikRentItems::applyHourlyPrices($arrtar, $hoursdiff);
								}
								//
								//vikrentitems 1.1
								if ($checkhourscharges > 0 && $aehourschbasp == true) {
									$arrtar = VikRentItems::applyExtraHoursChargesPrices($arrtar, $checkhourscharges, $daysdiff);
								}
								//
								// VRI 1.7 - Closed rate plans on these dates
								$itemrpclosed = VikRentItems::getItemRplansClosedInDates(array_keys($arrtar), $first, $daysdiff);
								if (count($itemrpclosed) > 0) {
									foreach ($arrtar as $kk => $tt) {
										if (array_key_exists($kk, $itemrpclosed)) {
											foreach ($tt as $tk => $tv) {
												if (array_key_exists($tv['idprice'], $itemrpclosed[$kk])) {
													unset($arrtar[$kk][$tk]);
												}
											}
											if (!(count($arrtar[$kk]) > 0)) {
												unset($arrtar[$kk]);
											} else {
												$arrtar[$kk] = array_values($arrtar[$kk]);
											}
										}
									}
								}
								//
								$filterplace = (!empty($pplace) ? true : false);
								$filtercat = (!empty($pcategories) && $pcategories != "all" ? true : false);
								//vikrentitems 1.1
								$groupdays = VikRentItems::getGroupDays($first, $second, $daysdiff);
								$morehst = VikRentItems::getHoursItemAvail() * 3600;
								//
								//vikrentitems 1.1 location closing days
								$errclosingdays = '';
								if ($filterplace) {
									$errclosingdays = VikRentItems::checkValidClosingDays($groupdays, $pplace, $returnplace);
								}
								if (empty($errclosingdays)) {
									$errclosingdays = VikRentItems::checkValidGlobalClosingDays($groupdays);
								}
								if (empty($errclosingdays)) {
									// VRI 1.6 - Allow pick ups on drop offs
									$picksondrops = VikRentItems::allowPickOnDrop();
									//
									foreach ($arrtar as $kk => $tt) {
										$check = "SELECT `id`,`idcat`,`idplace`,`avail`,`units`,`idretplace`,`askquantity` FROM `#__vikrentitems_items` WHERE `id`=" . (int)$kk . ";";
										$dbo->setQuery($check);
										$dbo->execute();
										$item = $dbo->loadAssocList();
										if (intval($item[0]['avail']) == 0 || $pitemquant > $item[0]['units']) {
											unset($arrtar[$kk]);
											continue;
										} else {
											if ($filterplace) {
												$actplaces = explode(";", $item[0]['idplace']);
												if (!in_array($pplace, $actplaces)) {
													unset($arrtar[$kk]);
													continue;
												}
												$actretplaces = explode(";", $item[0]['idretplace']);
												if (!in_array($returnplace, $actretplaces)) {
													unset($arrtar[$kk]);
													continue;
												}
											}
											if ($filtercat) {
												$cats = explode(";", $item[0]['idcat']);
												if (!in_array($pcategories, $cats)) {
													unset($arrtar[$kk]);
													continue;
												}
											}
										}
										$check = "SELECT `b`.`id`,`b`.`ritiro`,`b`.`consegna`,`ob`.`idorder` FROM `#__vikrentitems_busy` AS `b` LEFT JOIN `#__vikrentitems_ordersbusy` AS `ob` ON `b`.`id`=`ob`.`idbusy` WHERE `b`.`iditem`=" . (int)$kk . " AND `b`.`consegna` > ".time().";";
										$dbo->setQuery($check);
										$dbo->execute();
										if ($dbo->getNumRows() > 0) {
											$busy = $dbo->loadAssocList();
											foreach ($groupdays as $kgd => $gday) {
												$bfound = 0;
												$bfoundpool = array();
												foreach ($busy as $bu) {
													if ($gday >= $bu['ritiro'] && $gday <= ($morehst + $bu['consegna'])) {
														if ($picksondrops && !($gday > $bu['ritiro'] && $gday < ($morehst + $bu['consegna'])) && $gday != $bu['ritiro']) {
															// VRI 1.6 - pick ups on drop offs allowed
															continue;
														}
														$bfound++;
														array_push($bfoundpool, array(
															'ritiro' 	=> $bu['ritiro'],
															'consegna' 	=> ($morehst + $bu['consegna']),
														));
													} elseif (count($groupdays) == 2 && $gday == $groupdays[0]) {
														// VRI 1.1
														if ($groupdays[0] < $bu['ritiro'] && $groupdays[0] < ($morehst + $bu['consegna']) && $groupdays[1] > $bu['ritiro'] && $groupdays[1] > ($morehst + $bu['consegna'])) {
															$bfound++;
															array_push($bfoundpool, array(
																'ritiro' 	=> $bu['ritiro'],
																'consegna' 	=> ($morehst + $bu['consegna']),
															));
														} elseif ($groupdays[0] < $bu['ritiro'] && $groupdays[0] < ($morehst + $bu['consegna']) && $groupdays[1] > $bu['ritiro'] && $groupdays[1] <= ($morehst + $bu['consegna'])) {
															// VRI 1.0.1 (WP) - 1.7.1 (J) - rentals lasting one day or less touching other hourly/daily rentals with different pickup/dropoff times
															$bfound++;
															array_push($bfoundpool, array(
																'ritiro' 	=> $bu['ritiro'],
																'consegna' 	=> ($morehst + $bu['consegna']),
															));
														}
													} elseif (!empty($groupdays[($kgd + 1)]) && (($bu['consegna'] - $bu['ritiro']) < 86400) && $gday < $bu['ritiro'] && $groupdays[($kgd + 1)] > $bu['consegna']) {
														// VRI 1.3 availability check whith hourly rentals
														$bfound++;
														array_push($bfoundpool, array(
															'ritiro' 	=> $bu['ritiro'],
															'consegna' 	=> ($morehst + $bu['consegna']),
														));
													} elseif (count($groupdays) > 2 && array_key_exists(($kgd - 1), $groupdays) && array_key_exists(($kgd + 1), $groupdays)) {
														// VRI 1.4 gday is at midnight and the pickup for this date may be at a later time
														if ($groupdays[($kgd - 1)] < $bu['ritiro'] && $groupdays[($kgd - 1)] < ($morehst + $bu['consegna']) && $gday < $bu['ritiro'] && $groupdays[($kgd + 1)] > $bu['ritiro'] && $gday <= ($morehst + $bu['consegna'])) {
															// VRI 1.6 - daily rentals with a pickup hour after the drop off hour should check the time
															// ex. 3 units in total, 2 orders from April 16 22:00 to April 23 08:00,
															// and 1 order from April 23 22:00 to April 30 08:00
															// must give availability on April 23 to orders like April 17 22:00 to April 24 08:00
															$pickinfo = getdate($groupdays[0]);
															$dropinfo = getdate($groupdays[($kgd + 1)]);
															// needed to check if drop off hours and minutes is earlier than pickup hours and minutes
															$pickseconds = ($pickinfo['hours'] * 3600) + ($pickinfo['minutes'] * 60);
															$dropseconds = ($dropinfo['hours'] * 3600) + ($dropinfo['minutes'] * 60);
															if ($dropseconds < $pickseconds && $bu['ritiro'] > $gday) {
																// this pickup is at a later time than midnight, so this loop should not occupy a unit
																continue;
															}
															$bfound++;
															if ($item[0]['units'] > 1 && count($bfoundpool)) {
																foreach ($bfoundpool as $bfp) {
																	if ($bu['ritiro'] > $bfp['consegna'] || $bu['consegna'] < $bfp['ritiro']) {
																		/**
																		 * Given a case of an item with 2 units as full inventory and the following orders:
																		 * #1: Pickup September 3rd 10:00, Dropoff September 4th 10:00
																		 * #2: Pickup September 4th 17:00, Dropoff September 6th 17:00
																		 * A new rental order for the following dates should be allowed:
																		 * #3: Pickup September 2nd 10:00, Dropoff September 7th 10:00
																		 * This is because the order #1 will return the item at a time before the pick up
																		 * of the rental order #2, and so one unit will be free. Basically, the order #2 will
																		 * take the item returned by the order #1 and the order #3 will have the second item.
																		 * 
																		 * @since 	1.0.1 (WP) - 1.7.1 (J)
																		 */
																		$bfound--;
																	}
																}
															}
															array_push($bfoundpool, array(
																'ritiro' 	=> $bu['ritiro'],
																'consegna' 	=> ($morehst + $bu['consegna']),
															));
														}
													} elseif (count($groupdays) > 2 && isset($groupdays[($kgd + 1)])) {
														// VRI 1.7.1 gday time is prior to pickup time, but next day is inside this range of busy dates
														if ($gday < $bu['ritiro'] && $groupdays[($kgd + 1)] >= $bu['ritiro'] && $groupdays[($kgd + 1)] <= ($morehst + $bu['consegna'])) {
															$bfound++;
															array_push($bfoundpool, array(
																'ritiro' 	=> $bu['ritiro'],
																'consegna' 	=> ($morehst + $bu['consegna']),
															));
														}
													}
												}
												if (($bfound + $pitemquant) > $item[0]['units']) {
													unset($arrtar[$kk]);
													break;
												}
											}
										}
										if (!VikRentItems::itemNotLocked($kk, $item[0]['units'], $first, $second, $pitemquant)) {
											unset($arrtar[$kk]);
											continue;
										}

										// single item restrictions
										if (count($allrestrictions) > 0 && array_key_exists($kk, $arrtar)) {
											$carrestr = VikRentItems::itemRestrictions($kk, $allrestrictions);
											if (count($carrestr) > 0) {
												$restrictionerrmsg = VikRentItems::validateItemRestriction($carrestr, $restrcheckin, $restrcheckout, $daysdiff);
												if (strlen($restrictionerrmsg) > 0) {
													unset($arrtar[$kk]);
													continue;
												}
											}
										}
									}
									if (count($arrtar)) {
										if (VikRentItems::allowStats()) {
											$q = "INSERT INTO `#__vikrentitems_stats` (`ts`,`ip`,`place`,`cat`,`ritiro`,`consegna`,`res`) VALUES('" . time() . "','" . getenv('REMOTE_ADDR') . "'," . $dbo->quote($pplace . ';' . $returnplace) . "," . $dbo->quote($pcategories) . ",'" . $first . "','" . $second . "','" . count($arrtar) . "');";
											$dbo->setQuery($q);
											$dbo->execute();
										}
										if (VikRentItems::sendMailStats()) {
											$admsg = VikRentItems::getFrontTitle() . ", " . JText::translate('VRSRCHNOTM') . "\n\n";
											$admsg .= JText::translate('VRDATE') . ": " . date($df . ' '.$nowtf) . "\n";
											$admsg .= JText::translate('VRIP') . ": " . getenv('REMOTE_ADDR') . "\n";
											$admsg .= (!empty($pplace) ? JText::translate('VRPLACE') . ": " . VikRentItems::getPlaceName($pplace) : "") . (!empty($returnplace) ? " - " . VikRentItems::getPlaceName($returnplace) : "") . "\n";
											if (!empty($pcategories)) {
												$admsg .= ($pcategories == "all" ? JText::translate('VRIAT') . ": " . JText::translate('VRANY') : JText::translate('VRIAT') . ": " . VikRentItems::getCategoryName($pcategories)) . "\n";
											}
											$admsg .= JText::translate('VRPICKUP') . ": " . date($df . ' '.$nowtf, $first) . "\n";
											$admsg .= JText::translate('VRRETURN') . ": " . date($df . ' '.$nowtf, $second) . "\n";
											$admsg .= JText::translate('VRSRCHRES') . ": " . count($arrtar);
											$adsubj = JText::translate('VRSRCHNOTM') . ' ' . VikRentItems::getFrontTitle();
											// $adsubj = '=?UTF-8?B?' . base64_encode($adsubj) . '?=';
											@mail(VikRentItems::getAdminMail(), $adsubj, $admsg, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8");
										}
										//vikrentitems 1.1
										if ($checkhourscharges > 0 && $aehourschbasp == false) {
											$arrtar = VikRentItems::extraHoursSetPreviousFare($arrtar, $checkhourscharges, $daysdiff);
											$arrtar = VikRentItems::applySeasonalPrices($arrtar, $first, $second, $pplace);
											$arrtar = VikRentItems::applyExtraHoursChargesPrices($arrtar, $checkhourscharges, $daysdiff, true);
										} else {
											$arrtar = VikRentItems::applySeasonalPrices($arrtar, $first, $second, $pplace);
										}
										//
										//apply locations fee and store it in session
										if (!empty($pplace) && !empty($returnplace)) {
											$session->set('vriplace', $pplace);
											$session->set('vrireturnplace', $returnplace);
											//VRI 1.1 Rev.2
											VikRentItems::registerLocationTaxRate($pplace);
											//
											$locfee = VikRentItems::getLocFee($pplace, $returnplace);
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
													if (array_key_exists($daysdiff, $arrvaloverrides)) {
														$locfee['cost'] = $arrvaloverrides[$daysdiff];
													}
												}
												//end VikRentItems 1.1 - Location fees overrides
												$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $daysdiff) : $locfee['cost'];
												$lfarr = array ();
												foreach ($arrtar as $kat => $at) {
													$newcost = $at[0]['cost'] + $locfeecost;
													$at[0]['cost'] = $newcost;
													$lfarr[$kat] = $at;
												}
												$arrtar = $lfarr;
											}
										}
										//
										//save in session pickup and drop off timestamps
										$session->set('vripickupts', $first);
										$session->set('vrireturnts', $second);
										$session->set('vridays', $daysdiff);
										//
										$arrtar = VikRentItems::sortResults($arrtar);

										if ($getjson) {
											// send content-type headers
											if (!headers_sent()) {
												header('Content-Type: application/json');
											}
											// return the JSON string and exit process
											$this->response = $arrtar;
											echo json_encode($this->response);
											exit;
										}

										//check wether the user is coming from itemdetails
										if (!$getjson && !empty($pitemdetail) && array_key_exists($pitemdetail, $arrtar)) {
											$returnplace = VikRequest::getInt('returnplace', '', 'request');
											$mainframe=JFactory::getApplication();
											$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&task=showprc&itemopt=" . $pitemdetail . "&days=" . $daysdiff . "&pickup=" . $first . "&release=" . $second . "&place=" . $pplace . "&returnplace=" . $returnplace . "&itemquant=" . $pitemquant . (is_array($usetimeslot) && count($usetimeslot) > 0 ? "&timeslot=".$usetimeslot['id'] : "") . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
										} else {
											if (!$getjson && !empty($pitemdetail)) {
												$q="SELECT `id`,`name` FROM `#__vikrentitems_items` WHERE `id`=".$dbo->quote($pitemdetail).";";
												$dbo->setQuery($q);
												$dbo->execute();
												if ($dbo->getNumRows() > 0) {
													$cdet=$dbo->loadAssocList();
													$vri_tn->translateContents($cdet, '#__vikrentitems_items');
													VikError::raiseWarning('', $cdet[0]['name']." ".JText::translate('VRIDETAILCNOTAVAIL'));
												}
											}
											//pagination
											$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', $mainframe->get('list_limit'), 'int'); //results limit
											$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
											jimport('joomla.html.pagination');
											$pageNav = new JPagination(count($arrtar), $lim0, $lim);

											/**
											 * @wponly 	forms in WP use POST values, so we need to set additional URL params to the navigation links of the pages
											 */
											$req_vals_diff = array_diff(JFactory::getApplication()->input->post->getArray(), JFactory::getApplication()->input->get->getArray());
											foreach ($req_vals_diff as $pkey => $pval) {
												$pageNav->setAdditionalUrlParam($pkey, $pval);
											}
											//
											
											$navig = $pageNav->getPagesLinks();
											$this->navig = $navig;
											$tot_res = count($arrtar);
											$arrtar = array_slice($arrtar, $lim0, $lim, true);
											//
											$this->res = $arrtar;
											$this->days = $daysdiff;
											$this->hours = $hoursdiff;
											$this->pickup = $first;
											$this->release = $second;
											$this->place = $pplace;
											$this->timeslot = $usetimeslot;
											$this->tot_res = $tot_res;
											$this->vri_tn = $vri_tn;
											//theme
											$theme = VikRentItems::getTheme();
											if ($theme != 'default') {
												$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'search';
												if (is_dir($thdir)) {
													$this->_setPath('template', $thdir.DS);
												}
											}
											//
											parent::display($tpl);
										}
										//
									} else {
										if (VikRentItems::allowStats()) {
											$q = "INSERT INTO `#__vikrentitems_stats` (`ts`,`ip`,`place`,`cat`,`ritiro`,`consegna`,`res`) VALUES('" . time() . "','" . getenv('REMOTE_ADDR') . "'," . $dbo->quote($pplace . ';' . $returnplace) . "," . $dbo->quote($pcategories) . ",'" . $first . "','" . $second . "','0');";
											$dbo->setQuery($q);
											$dbo->execute();
										}
										if (VikRentItems::sendMailStats()) {
											$admsg = VikRentItems::getFrontTitle() . ", " . JText::translate('VRSRCHNOTM') . "\n\n";
											$admsg .= JText::translate('VRDATE') . ": " . date($df . ' '.$nowtf) . "\n";
											$admsg .= JText::translate('VRIP') . ": " . getenv('REMOTE_ADDR') . "\n";
											$admsg .= (!empty($pplace) ? JText::translate('VRPLACE') . ": " . VikRentItems::getPlaceName($pplace) : "") . (!empty($returnplace) ? " - " . VikRentItems::getPlaceName($returnplace) : "") . "\n";
											if (!empty($pcategories)) {
												$admsg .= ($pcategories == "all" ? JText::translate('VRIAT') . ": " . JText::translate('VRANY') : JText::translate('VRIAT') . ": " . VikRentItems::getCategoryName($pcategories)) . "\n";
											}
											$admsg .= JText::translate('VRPICKUP') . ": " . date($df . ' '.$nowtf, $first) . "\n";
											$admsg .= JText::translate('VRRETURN') . ": " . date($df . ' '.$nowtf, $second) . "\n";
											$admsg .= JText::translate('VRSRCHRES') . ": 0";
											$adsubj = JText::translate('VRSRCHNOTM') . ' ' . VikRentItems::getFrontTitle();
											// $adsubj = '=?UTF-8?B?' . base64_encode($adsubj) . '?=';
											@mail(VikRentItems::getAdminMail(), $adsubj, $admsg, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8");
										}
										$this->setVriError((isset($restrictionerrmsg) && !empty($restrictionerrmsg) ? $restrictionerrmsg : JText::translate('VRNOITEMSINDATE')));
									}
								} else {
									//closing days error
									$this->setVriError($errclosingdays);
								}
							} else {
								if (VikRentItems::allowStats()) {
									$q = "INSERT INTO `#__vikrentitems_stats` (`ts`,`ip`,`place`,`cat`,`ritiro`,`consegna`,`res`) VALUES('" . time() . "','" . getenv('REMOTE_ADDR') . "'," . $dbo->quote($pplace . ';' . $returnplace) . "," . $dbo->quote($pcategories) . ",'" . $first . "','" . $second . "','0');";
									$dbo->setQuery($q);
									$dbo->execute();
								}
								if (VikRentItems::sendMailStats()) {
									$admsg = VikRentItems::getFrontTitle() . ", " . JText::translate('VRSRCHNOTM') . "\n\n";
									$admsg .= JText::translate('VRDATE') . ": " . date($df . ' '.$nowtf) . "\n";
									$admsg .= JText::translate('VRIP') . ": " . getenv('REMOTE_ADDR') . "\n";
									$admsg .= (!empty($pplace) ? JText::translate('VRPLACE') . ": " . VikRentItems::getPlaceName($pplace) : "") . (!empty($returnplace) ? " - " . VikRentItems::getPlaceName($returnplace) : "") . "\n";
									if (!empty($pcategories)) {
										$admsg .= ($pcategories == "all" ? JText::translate('VRIAT') . ": " . JText::translate('VRANY') : JText::translate('VRIAT') . ": " . VikRentItems::getCategoryName($pcategories)) . "\n";
									}
									$admsg .= JText::translate('VRPICKUP') . ": " . date($df . ' '.$nowtf, $first) . "\n";
									$admsg .= JText::translate('VRRETURN') . ": " . date($df . ' '.$nowtf, $second) . "\n";
									$admsg .= JText::translate('VRSRCHRES') . ": 0";
									$adsubj = JText::translate('VRSRCHNOTM') . ' ' . VikRentItems::getFrontTitle();
									// $adsubj = '=?UTF-8?B?' . base64_encode($adsubj) . '?=';
									@mail(VikRentItems::getAdminMail(), $adsubj, $admsg, "MIME-Version: 1.0" . "\r\n" . "Content-type: text/plain; charset=UTF-8");
								}
								$errormess = JText::translate('VRNOITEMAVFOR') . " " . $daysdiff . " " . ($daysdiff > 1 ? JText::translate('VRDAYS') : JText::translate('VRDAY'));
								if (!empty($pitemdetail)) {
									VikError::raiseWarning('', $errormess);
									$mainframe = JFactory::getApplication()->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=itemdetails&elemid=" . $pitemdetail . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
									exit;
								}
								$this->setVriError($errormess);
							}
						} else {
							$this->setVriError($restrictionerrmsg);
						}
					} else {
						if ($first <= $actnow) {
							if (date('d/m/Y', $first) == date('d/m/Y', $actnow)) {
								$errormess = JText::translate('VRIERRPICKPASSED');
							} else {
								$errormess = JText::translate('VRPICKINPAST');
							}
						} elseif ($first < $lim_mindays) {
							//error with minimum days in advance for bookings
							$errormess = JText::sprintf('VRIERRMINDAYSADV', $mindaysadv);
						} else {
							$errormess = JText::translate('VRPICKBRET');
						}
						$this->setVriError($errormess);
					}
				} else {
					$this->setVriError(JText::translate('VRWRONGDF') . ": " . VikRentItems::sayDateFormat());
				}
			} else {
				$this->setVriError(JText::translate('VRSELPRDATE'));
			}
		} else {
			echo VikRentItems::getDisabledRentMsg();
		}
	}

	/**
	 * Handles errors with the search results.
	 * 
	 * @param 	string 	$err 	the error message to be displayed or returned.
	 * 
	 * @return 	void
	 * 
	 * @since 	1.7
	 */
	protected function setVriError($err) {
		$getjson = VikRequest::getInt('getjson', 0, 'request');
		
		if ($getjson) {
			if (!empty($err)) {
				$this->response['e4j.error'] = $err;
			}
			// send content-type headers
			if (!headers_sent()) {
				header('Content-Type: application/json');
			}

			// print the JSON response and exit
			echo json_encode($this->response);
			exit;
		}
		
		showSelectVRI($err);
	}
}
