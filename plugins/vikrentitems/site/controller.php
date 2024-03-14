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

jimport('joomla.application.component.controller');

class VikRentItemsController extends JControllerVikRentItems {
	
	function display($cachable = false, $urlparams = array()) {
		$view = VikRequest::getVar('view', '');
		switch ($view) {
			case 'itemslist':
			case 'itemdetails':
			case 'loginregister':
			case 'locationsmap':
			case 'locationslist':
			case 'userorders':
			case 'categories':
			case 'order':
				VikRequest::setVar('view', $view);
				break;
			default:
				VikRequest::setVar('view', 'vikrentitems');
		}
		parent::display();
	}

	function search() {
		VikRequest::setVar('view', 'search');
		parent::display();
	}

	function showprc() {
		VikRequest::setVar('view', 'showprc');
		parent::display();
	}
	
	function deliverymap() {
		VikRequest::setVar('view', 'deliverymap');
		parent::display();
	}
	
	function ensuredelivery() {
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$vrideliverycart = $session->get('vriDeliveryCart', '');
		$vrideliverycart = is_array($vrideliverycart) ? $vrideliverycart : array();
		$ensured = array();
		$paddress = VikRequest::getString('address', '', 'request');
		$pdistance = VikRequest::getString('distance', '', 'request');
		$pelemid = VikRequest::getString('elemid', '', 'request');
		if (!empty($paddress) && !empty($pdistance)) {
			$deliveryid = rand();
			if (array_key_exists($deliveryid, $vrideliverycart)) {
				while(array_key_exists($deliveryid, $vrideliverycart)) {
					$deliveryid = rand();
				}
			}
			$calcunit = VikRentItems::getDeliveryCalcUnit();
			$costperunit = VikRentItems::getDeliveryCostPerUnit();
			$ensured['vrideliveryglobcostperunit'] = $costperunit;
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=".intval($pelemid).";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$item = $dbo->loadAssocList();
				$overcostperunit = floatval(VikRentItems::getItemParam($item[0]['params'], 'overdelcost'));
				if (!empty($overcostperunit) && $overcostperunit > 0.00) {
					$costperunit = $overcostperunit;
				}
			}
			$maxcost = VikRentItems::getDeliveryMaxCost();
			$rounddistance = VikRentItems::getDeliveryRoundDistance();
			$roundcost = VikRentItems::getDeliveryRoundCost();
			$kmdist = (float)$pdistance / 1000;
			$milesdist = (float)$pdistance * 0.000621371192;
			$realdist = $calcunit == 'km' ? $kmdist : $milesdist;
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
			// VRI 1.6 - Delivery per Item Unit (Quantity)
			if (VikRentItems::isDeliveryPerItemUnit()) {
				$pitemquant = VikRequest::getInt('itemquant', 1, 'request');
				$deliverycost = $deliverycost * $pitemquant;
			}
			//
			$ensured['vrideliverysessid'] = $deliveryid;
			$ensured['vrideliveryelemid'] = intval($pelemid);
			$ensured['vrideliveryaddress'] = $paddress;
			$ensured['vrideliverydistance'] = $realdist;
			$ensured['vrideliverydistanceunit'] = $calcunit;
			$ensured['vrideliveryroundcost'] = ($roundcost == true ? 1 : 0);
			$ensured['vrideliverymaxcost'] = $maxcost;
			$ensured['vrideliverycost'] = $deliverycost;
			
			$vrideliverycart[$deliveryid] = $ensured;
			$session->set('vriDeliveryCart', $vrideliverycart);
			
			echo json_encode($ensured);
		} else {
			if (function_exists('http_response_code')) {
				http_response_code(404);
			}
		}
		exit;
	}
	
	function emptycart() {
		$session = JFactory::getSession();
		$vrisessioncart = $session->set('vriCart', '');
		// VRI 1.6 - unset also the delivery information
		$session->set('vriDeliveryCart', '');
		//
		$pdays = VikRequest::getString('days', '', 'request');
		$ppickup = VikRequest::getString('pickup', '', 'request');
		$prelease = VikRequest::getString('release', '', 'request');
		$pplace = VikRequest::getString('place', '', 'request');
		$preturnplace = VikRequest::getString('returnplace', '', 'request');
		$psearch = VikRequest::getInt('search', '', 'request');
		$pdetails = VikRequest::getInt('details', '', 'request');
		$pelemid = VikRequest::getInt('elemid', '', 'request');
		$pitemid = VikRequest::getString('Itemid', '', 'request');
		$mainframe = JFactory::getApplication();
		if ($psearch == 1) {
			$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=vikrentitems" . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
		} elseif ($pdetails == 1 && $pelemid > 0) {
			$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=itemdetails&elemid=" . $pelemid . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
		} else {
			$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&task=oconfirm&place=" . $pplace . "&returnplace=" . $preturnplace . "&days=" . $pdays . "&pickup=" . $ppickup . "&release=" . $prelease . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
		}
	}
	
	function rmcartitem() {
		$session = JFactory::getSession();
		$vrisessioncart = $session->get('vriCart', '');
		$vrisessioncart = is_array($vrisessioncart) ? $vrisessioncart : array();
		$pelem = VikRequest::getString('elem', '', 'request');
		if (!empty($pelem) && count($vrisessioncart) > 0) {
			$parts = explode(';', $pelem);
			if (array_key_exists($parts[0], $vrisessioncart) && array_key_exists($parts[1], $vrisessioncart[$parts[0]])) {
				unset($vrisessioncart[$parts[0]][$parts[1]]);
				if (!count($vrisessioncart[$parts[0]])) {
					unset($vrisessioncart[$parts[0]]);
				}
				$session->set('vriCart', $vrisessioncart);
				if (!count($vrisessioncart)) {
					// VRI 1.6 - unset also the delivery information if the cart is empty
					$session->set('vriDeliveryCart', '');
					//
				}
			}
		}
		$pdays = VikRequest::getString('days', '', 'request');
		$ppickup = VikRequest::getString('pickup', '', 'request');
		$prelease = VikRequest::getString('release', '', 'request');
		$pplace = VikRequest::getString('place', '', 'request');
		$preturnplace = VikRequest::getString('returnplace', '', 'request');
		$pitemid = VikRequest::getString('Itemid', '', 'request');
		$mainframe = JFactory::getApplication();
		$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&task=oconfirm&place=" . $pplace . "&returnplace=" . $preturnplace . "&days=" . $pdays . "&pickup=" . $ppickup . "&release=" . $prelease . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
	}
	
	function oconfirm() {
		$requirelogin = VikRentItems::requireLogin();
		if ($requirelogin) {
			if (VikRentItems::userIsLogged()) {
				VikRequest::setVar('view', 'oconfirm');
			} else {
				VikRequest::setVar('view', 'loginregister');
			}
		} else {
			VikRequest::setVar('view', 'oconfirm');
		}
		parent::display();
	}
	
	function register() {
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDbo();
		//user data
		$pname = VikRequest::getString('name', '', 'request');
		$plname = VikRequest::getString('lname', '', 'request');
		$pemail = VikRequest::getString('email', '', 'request');
		$pusername = VikRequest::getString('username', '', 'request');
		$ppassword = VikRequest::getString('password', '', 'request');
		$pconfpassword = VikRequest::getString('confpassword', '', 'request');
		//
		//order data
		$ppriceid = VikRequest::getString('priceid', '', 'request');
		$pplace = VikRequest::getString('place', '', 'request');
		$preturnplace = VikRequest::getString('returnplace', '', 'request');
		$pelemid = VikRequest::getString('elemid', '', 'request');
		$pdays = VikRequest::getString('days', '', 'request');
		$ppickup = VikRequest::getString('pickup', '', 'request');
		$prelease = VikRequest::getString('release', '', 'request');
		$pitemid = VikRequest::getString('Itemid', '', 'request');
		$copts = array();
		$q = "SELECT * FROM `#__vikrentitems_optionals`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$optionals = $dbo->loadAssocList();
			foreach ($optionals as $opt) {
				$tmpvar = VikRequest::getString('optid' . $opt['id'], '', 'request');
				if (!empty($tmpvar)) {
					$copts[$opt['id']] = $tmpvar;
				}
			}
		}
		$chosenopts = "";
		if (is_array($copts) && @count($copts) > 0) {
			foreach ($copts as $idopt => $quanopt) {
				$chosenopts .= "&optid".$idopt."=".$quanopt;
			}
		}
		$qstring = "priceid=".$ppriceid."&place=".$pplace."&returnplace=".$preturnplace."&elemid=".$pelemid."&days=".$pdays."&pickup=".$ppickup."&release=".$prelease.(!empty($chosenopts) ? $chosenopts : "").(!empty($pitemid) ? "&Itemid=".$pitemid : "");
		//
		if (!VikRentItems::userIsLogged()) {
			if (!empty($pname) && !empty($plname) && !empty($pusername) && VikRentItems::validEmail($pemail) && $ppassword == $pconfpassword) {
				//save user
				$newuserid=VikRentItems::addJoomlaUser($pname." ".$plname, $pusername, $pemail, $ppassword);
				if ($newuserid!=false && strlen($newuserid)) {
					//registration success
					$credentials = array('username' => $pusername, 'password' => $ppassword );
					//autologin
					/**
					 * @wponly 	the return URL should be passed within the $option array of $app->login()
					 */
					$mainframe->login($credentials, array('redirect' => JRoute::rewrite('index.php?option=com_vikrentitems&task=oconfirm&'.$qstring, false)));
					$currentUser = JFactory::getUser();
					$currentUser->setLastVisit(time());
					$currentUser->set('guest', 0);
					//
					if (!empty($ppriceid)) {
						$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikrentitems&task=oconfirm&'.$qstring, false));
					} else {
						//$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikrentitems&view=userorders', false));
						$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikrentitems&task=oconfirm&'.$qstring, false));
					}
				} else {
					//error while saving new user
					VikError::raiseWarning('', JText::translate('VRIREGERRSAVING'));
					$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikrentitems&view=loginregister&'.$qstring, false));
				}
			} else {
				//invalid data
				VikError::raiseWarning('', JText::translate('VRIREGERRINSDATA'));
				$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikrentitems&view=loginregister&'.$qstring, false));
			}
		} else {
			//user is already logged in, proceed
			$mainframe->redirect(JRoute::rewrite('index.php?option=com_vikrentitems&task=oconfirm&'.$qstring, false));
		}
	}
	
	function saveorder() {
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$vri_tn = VikRentItems::getTranslator();
		$maxdeliverycost = VikRentItems::getDeliveryMaxCost();
		$pitem = VikRequest::getVar('item', array());
		$pitemquant = VikRequest::getVar('itemquant', array());
		$pdelivery = VikRequest::getVar('delivery', array());
		$pdays = VikRequest::getString('days', '', 'request');
		//vikrentitems 1.1
		$porigdays = VikRequest::getString('origdays', '', 'request');
		$pcouponcode = VikRequest::getString('couponcode', '', 'request');
		//
		$ppickup = VikRequest::getString('pickup', '', 'request');
		$prelease = VikRequest::getString('release', '', 'request');
		$pprtar = VikRequest::getVar('prtar', array());
		$poptionals = VikRequest::getVar('optionals', array());
		$ptotdue = VikRequest::getString('totdue', '', 'request');
		$pplace = VikRequest::getString('place', '', 'request');
		$preturnplace = VikRequest::getString('returnplace', '', 'request');
		$pgpayid = VikRequest::getString('gpayid', '', 'request');
		$ppriceid = VikRequest::getVar('priceid', array());
		$phourly = VikRequest::getString('hourly', '', 'request');
		$pitemid = VikRequest::getInt('Itemid', '', 'request');
		$validtoken = true;
		if (VikRentItems::tokenForm()) {
			$validtoken = false;
			$pviktoken = VikRequest::getString('viktoken', '', 'request');
			$sesstoken = $session->get('vikrtoken', '');
			if (!empty($pviktoken) && $sesstoken == $pviktoken) {
				$session->set('vikrtoken', '');
				$validtoken = true;
			}
			if (!$validtoken) {
				$validtoken = JSession::checkToken();
			}
		}
		if ($validtoken) {
			$vrisessioncart = $session->get('vriCart', '');
			$vricart = array();
			$lastdelivery = '';
			$vrideliverycart = $session->get('vriDeliveryCart', '');
			$vrideliverycart = is_array($vrideliverycart) ? $vrideliverycart : array();
			$totitemquants = array();
			if (count($pitem) > 0) {
				foreach ($pitem as $k => $idit) {
					$vricart[$idit][$k]['itemquant'] = (int)$pitemquant[$k];
					$vricart[$idit][$k]['optionals'] = $poptionals[$k];
					$vricart[$idit][$k]['priceid'] = $ppriceid[$k];
					$vricart[$idit][$k]['prtar'] = $pprtar[$k];
					if (!empty($pdelivery[$k]) && array_key_exists($pdelivery[$k], $vrideliverycart)) {
						$vricart[$idit][$k]['delivery'] = $vrideliverycart[$pdelivery[$k]];
						$lastdelivery = $vrideliverycart[$pdelivery[$k]];
						if (array_key_exists('info', $vrisessioncart[$idit][$k])) {
							$vricart[$idit][$k]['info'] = $vrisessioncart[$idit][$k]['info'];
						}
					}
					if (!isset($totitemquants[$idit])) {
						$totitemquants[$idit] = 0;
					}
					$totitemquants[$idit] += (int)$pitemquant[$k];
				}
			}
			if (count($vricart) == 0) {
				//error, empty post cart data
				$session->set('vriCart', '');
				$session->set('vriDeliveryCart', '');
				VikError::raiseWarning('', JText::translate('VRICARTISEMPTY'));
				$mainframe = JFactory::getApplication();
				$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&task=oconfirm&place=" . $pplace . "&returnplace=" . $preturnplace . "&days=" . $pdays . "&pickup=" . $ppickup . "&release=" . $prelease . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
				exit;
			}
			$q = "SELECT * FROM `#__vikrentitems_custfields` ORDER BY `#__vikrentitems_custfields`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$cfields = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
			$vri_tn->translateContents($cfields, '#__vikrentitems_custfields');
			$suffdata = true;
			$useremail = "";
			$usercountry = '';
			$nominatives = array();
			$t_first_name = '';
			$t_last_name = '';
			$nominative_str = '';
			$phone_number = '';
			$fieldflags = array();
			if (@is_array($cfields)) {
				foreach ($cfields as $cf) {
					if (intval($cf['required']) == 1 && $cf['type'] != 'separator') {
						$tmpcfval = VikRequest::getString('vrif' . $cf['id'], '', 'request');
						if (strlen(str_replace(" ", "", trim($tmpcfval))) <= 0) {
							$suffdata = false;
							break;
						}
					}
				}
				//save user email and create custdata array
				$arrcustdata = array();
				$arrcfields = array();
				$nextorderdata = array();
				$nextorderdata['customfields'] = array();
				if (!empty($lastdelivery) && is_array($lastdelivery)) {
					$nextorderdata['delivery'] = $lastdelivery;
				}
				$emailwasfound = false;
				foreach ($cfields as $cf) {
					if (intval($cf['isemail']) == 1 && $emailwasfound == false) {
						$useremail = trim(VikRequest::getString('vrif' . $cf['id'], '', 'request'));
						$emailwasfound = true;
					}
					if ($cf['isnominative'] == 1) {
						$tmpcfval = VikRequest::getString('vrif' . $cf['id'], '', 'request');
						if (strlen(str_replace(" ", "", trim($tmpcfval))) > 0) {
							$nominatives[] = $tmpcfval;
						}
					}
					if ($cf['isphone'] == 1) {
						$tmpcfval = VikRequest::getString('vrif' . $cf['id'], '', 'request');
						if (strlen(str_replace(" ", "", trim($tmpcfval))) > 0) {
							$phone_number = $tmpcfval;
						}
					}
					if (!empty($cf['flag'])) {
						$tmpcfval = VikRequest::getString('vrif' . $cf['id'], '', 'request');
						if (strlen(str_replace(" ", "", trim($tmpcfval))) > 0) {
							$fieldflags[$cf['flag']] = $tmpcfval;
						}
					}
					if ($cf['type'] != 'separator' && $cf['type'] != 'country' && ( $cf['type'] != 'checkbox' || ($cf['type'] == 'checkbox' && intval($cf['required']) != 1) ) ) {
						$arrcustdata[JText::translate($cf['name'])] = VikRequest::getString('vrif' . $cf['id'], '', 'request');
						$arrcfields[$cf['id']] = VikRequest::getString('vrif' . $cf['id'], '', 'request');
						$nextorderdata['customfields'][$cf['id']] = VikRequest::getString('vrif' . $cf['id'], '', 'request');
					} elseif ($cf['type'] == 'country') {
						$countryval = VikRequest::getString('vrif' . $cf['id'], '', 'request');
						if (!empty($countryval) && strstr($countryval, '::') !== false) {
							$countryparts = explode('::', $countryval);
							$usercountry = $countryparts[0];
							$arrcustdata[JText::translate($cf['name'])] = $countryparts[1];
						} else {
							$arrcustdata[JText::translate($cf['name'])] = '';
						}
					}
				}
				if (count($nominatives) > 0) {
					$nominative_str = implode(" ", $nominatives);
				}
				if (count($nominatives) >= 2) {
					$t_last_name = array_pop($nominatives);
					$t_first_name = array_pop($nominatives);
				}
				//
			}
			if ($suffdata == true) {
				//vikrentitems 1.2 Customer Data for Next Order
				$currentUser = JFactory::getUser();
				if (!empty($currentUser->id) && intval($currentUser->id) > 0) {
					$storenextdata = json_encode($nextorderdata);
					$q = "SELECT `id` FROM `#__vikrentitems_usersdata` WHERE `ujid`='".(int)$currentUser->id."';";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$oldnextid = $dbo->loadAssocList();
						$q = "UPDATE `#__vikrentitems_usersdata` SET `data`=".$dbo->quote($storenextdata)." WHERE `id`='".(int)$oldnextid[0]['id']."';";
					} else {
						$q = "INSERT INTO `#__vikrentitems_usersdata` (`ujid`,`data`) VALUES('".(int)$currentUser->id."', ".$dbo->quote($storenextdata).");";
					}
					$dbo->setQuery($q);
					$dbo->execute();
				}
				//
				//vikrentitems 1.1 for dayValidTs()
				if (strlen($porigdays) > 0) {
					$calcdays = $pdays;
					$pdays = $porigdays;
				} else {
					$calcdays = $pdays;
				}
				//
				if (VikRentItems::dayValidTs($pdays, $ppickup, $prelease)) {
					$currencyname = VikRentItems::getCurrencyName();
					$secdiff = $prelease - $ppickup;
					$daysdiff = $secdiff / 86400;
					if (is_int($daysdiff)) {
						if ($daysdiff < 1) {
							$daysdiff = 1;
						}
					} else {
						if ($daysdiff < 1) {
							$daysdiff = 1;
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
					$isdue = 0;
					$totdelivery = 0;
					foreach ($vricart as $iditem => $itemarrparent) {
						foreach ($itemarrparent as $k => $itemarr) {
							$tar = '';
							if (intval($phourly) > 0) {
								$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `id`=" . $dbo->quote($itemarr['prtar']) . " AND `iditem`=" . $dbo->quote($iditem) . " AND `hours`=" . $dbo->quote($phourly) . ";";
								$usedhourly = true;
							} else {
								//extra hours charges
								if (strlen($porigdays) > 0) {
									$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=" . $dbo->quote($itemarr['prtar']) . " AND `iditem`=" . $dbo->quote($iditem) . " AND `days`=" . $dbo->quote($calcdays) . ";";
								} else {
									$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=" . $dbo->quote($itemarr['prtar']) . " AND `iditem`=" . $dbo->quote($iditem) . " AND `days`=" . $dbo->quote($pdays) . ";";
								}
								//
								$usedhourly = false;
							}
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() == 1) {
								$tar = $dbo->loadAssocList();
							} elseif (intval($phourly) > 0) {
								//one of the items chosen does not have hourly prices but leave order as hourly
								$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`='" . intval($itemarr['prtar']) . "' AND `iditem`='" . intval($iditem) . "' AND `days`='" . intval($pdays) . "';";
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() == 1) {
									$tar = $dbo->loadAssocList();
								}
							}
							if (is_array($tar)) {
								if ($usedhourly) {
									foreach ($tar as $kt => $vt) {
										$tar[$kt]['days'] = 1;
									}
								}
								if (isset($checkhourscharges) && $checkhourscharges > 0 && $aehourschbasp == true) {
									$ret = VikRentItems::applyExtraHoursChargesItem($tar, $iditem, $checkhourscharges, $daysdiff, false, true, true);
									$tar = $ret['return'];
									$calcdays = $ret['days'];
								}
								if (isset($checkhourscharges) && $checkhourscharges > 0 && $aehourschbasp == false) {
									$tar = VikRentItems::extraHoursSetPreviousFareItem($tar, $iditem, $checkhourscharges, $daysdiff, true);
									$tar = VikRentItems::applySeasonsItem($tar, $ppickup, $prelease, $pplace);
									$ret = VikRentItems::applyExtraHoursChargesItem($tar, $iditem, $checkhourscharges, $daysdiff, true, true, true);
									$tar = $ret['return'];
									$calcdays = $ret['days'];
								} else {
									$tar = VikRentItems::applySeasonsItem($tar, $ppickup, $prelease, $pplace);
								}
								$tar = VikRentItems::applyItemDiscounts($tar, $iditem, $itemarr['itemquant']);
								$vricart[$iditem][$k]['tar'] = $tar;
								$isdue += VikRentItems::sayCostPlusIva($tar[0]['cost'] * $itemarr['itemquant'], $tar[0]['idprice']);
							} else {
								//Error, no fare found for the item
								unset($vrisessioncart[$iditem][$k]);
								if (@count($vrisessioncart[$iditem]) == 0) {
									unset($vrisessioncart[$iditem]);
								}
								$session->set('vriCart', $vrisessioncart);
								VikError::raiseWarning('', JText::translate('VRIERRITEMFARENOTFOUND'));
								$mainframe = JFactory::getApplication();
								$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&task=oconfirm&place=" . $pplace . "&returnplace=" . $preturnplace . "&days=" . $pdays . "&pickup=" . $ppickup . "&release=" . $prelease . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
								break;
							}
							//Optionals
							$optstr = "";
							$optarrtaxnet = array();
							if (!empty($itemarr['optionals'])) {
								$stepo = explode(";", $itemarr['optionals']);
								foreach ($stepo as $oo) {
									if (!empty($oo)) {
										$stept = explode(":", $oo);
										$q = "SELECT * FROM `#__vikrentitems_optionals` WHERE `id`=" . $dbo->quote($stept[0]) . ";";
										$dbo->setQuery($q);
										$dbo->execute();
										if ($dbo->getNumRows() == 1) {
											$actopt = $dbo->loadAssocList();
											$vri_tn->translateContents($actopt, '#__vikrentitems_optionals');
											$specvar = '';
											if (!empty($actopt[0]['specifications']) && strstr($stept[1], '-') != false) {
												$optspeccosts = VikRentItems::getOptionSpecIntervalsCosts($actopt[0]['specifications']);
												$optspecnames = VikRentItems::getOptionSpecIntervalsNames($actopt[0]['specifications']);
												$specstept = explode('-', $stept[1]);
												$stept[1] = $specstept[0];
												$specvar = $specstept[1];
												$actopt[0]['specintv'] = $specvar;
												$actopt[0]['name'] .= ' ('.$optspecnames[($specvar - 1)].')';
												$actopt[0]['quan'] = $stept[1];
												$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optspeccosts[($specvar - 1)]) * $calcdays * $stept[1]) : (floatval($optspeccosts[($specvar - 1)]) * $stept[1]));
											} else {
												$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $calcdays * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
											}
											if (!empty($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
												$realcost = $actopt[0]['maxprice'];
												if (intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
													$realcost = $actopt[0]['maxprice'] * $stept[1];
												}
											}
											$opt_item_units = $actopt[0]['onceperitem'] ? 1 : $itemarr['itemquant'];
											$tmpopr = VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $actopt[0]['idiva']);
											$isdue += $tmpopr;
											$optnetprice = VikRentItems::sayOptionalsMinusIva($realcost * $opt_item_units, $actopt[0]['idiva']);
											$optarrtaxnet[] = $optnetprice;
											$optstr .= ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": " . $tmpopr . " " . $currencyname . "\n";
										}
									}
								}
							}
							//End Optionals
							$vricart[$iditem][$k]['optstr'] = $optstr;
							$vricart[$iditem][$k]['optarrtaxnet'] = $optarrtaxnet;
							//Time Slots
							$checktslotk = key($vrisessioncart[$iditem]);
							if (is_array($vrisessioncart) && count($vrisessioncart) > 0 && array_key_exists('timeslot', $vrisessioncart[$iditem][$checktslotk])) {
								$vricart[$iditem][$k]['timeslot']['id'] = $vrisessioncart[$iditem][$checktslotk]['timeslot']['id'];
								$vricart[$iditem][$k]['timeslot']['name'] = $vrisessioncart[$iditem][$checktslotk]['timeslot']['name'];
							}
							//
							//delivery service
							if (array_key_exists('delivery', $itemarr)) {
								$nowdelcost = $itemarr['delivery']['vrideliverycost'];
								$overcostperunit = floatval(VikRentItems::getItemParam($itemarr['info']['params'], 'overdelcost'));
								if (!empty($overcostperunit) && $overcostperunit > 0.00) {
									$nowdelcost = $itemarr['delivery']['vrideliverydistance'] * $overcostperunit;
									if ($itemarr['delivery']['vrideliveryroundcost'] == 1) {
										$nowdelcost = round($nowdelcost);
									}
									if (!empty($itemarr['delivery']['vrideliverymaxcost']) && (float)$itemarr['delivery']['vrideliverymaxcost'] > 0 && $nowdelcost > (float)$itemarr['delivery']['vrideliverymaxcost']) {
										$nowdelcost = (float)$itemarr['delivery']['vrideliverymaxcost'];
									}
									// VRI 1.6 - Delivery per Item Unit (Quantity)
									if (VikRentItems::isDeliveryPerItemUnit()) {
										$nowdelcost = $nowdelcost * $itemarr['itemquant'];
									}
									//
								} elseif ((int)$itemarr['delivery']['vrideliveryelemid'] != (int)$itemarr['info']['id'] && !VikRentItems::isDeliveryPerOrder()) {
									$nowdelcost = $itemarr['delivery']['vrideliverydistance'] * $itemarr['delivery']['vrideliveryglobcostperunit'];
									if ($itemarr['delivery']['vrideliveryroundcost'] == 1) {
										$nowdelcost = round($nowdelcost);
									}
									if (!empty($itemarr['delivery']['vrideliverymaxcost']) && (float)$itemarr['delivery']['vrideliverymaxcost'] > 0 && $nowdelcost > (float)$itemarr['delivery']['vrideliverymaxcost']) {
										$nowdelcost = (float)$itemarr['delivery']['vrideliverymaxcost'];
									}
									// VRI 1.6 - Delivery per Item Unit (Quantity)
									if (VikRentItems::isDeliveryPerItemUnit()) {
										$nowdelcost = $nowdelcost * $itemarr['itemquant'];
									}
									//
								}
								$totdelivery += $totdelivery > 0 && VikRentItems::isDeliveryPerOrder() ? 0 : $nowdelcost;
								if (!empty($maxdeliverycost) && (float)$maxdeliverycost > 0 && $totdelivery > (float)$maxdeliverycost) {
									$totdelivery = (float)$maxdeliverycost;
								}
							}
							//
						}
					}
					//delivery service
					if ($totdelivery > 0) {
						$isdue += $totdelivery;
					}
					//
					$maillocfee = "";
					$locfeewithouttax = 0;
					$validlocations = true;
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
								if (array_key_exists($calcdays, $arrvaloverrides)) {
									$locfee['cost'] = $arrvaloverrides[$calcdays];
								}
							}
							//end VikRentItems 1.1 - Location fees overrides
							$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $calcdays) : $locfee['cost'];
							$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva']);
							$isdue += $locfeewith;
							$locfeewithouttax = VikRentItems::sayLocFeeMinusIva($locfeecost, $locfee['idiva']);
							$maillocfee = $locfeewith;
						}
						//check valid locations
						$q = "SELECT `id`,`idplace`,`idretplace` FROM `#__vikrentitems_items` WHERE `id` IN (" . implode(",", array_unique($pitem)) . ");";
						$dbo->setQuery($q);
						$dbo->execute();
						$infoplaces = $dbo->loadAssocList();
						foreach ($infoplaces as $infoplace) {
							if (!empty($infoplace['idplace']) && !empty($infoplace['idretplace'])) {
								$actplaces = explode(";", $infoplace['idplace']);
								$actretplaces = explode(";", $infoplace['idretplace']);
								if (!in_array($pplace, $actplaces) || !in_array($preturnplace, $actretplaces)) {
									$validlocations = false;
								}
							}
						}
						//
					}
					//coupon
					$origtotdue = $isdue;
					$usedcoupon = false;
					$strcouponeff = '';
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
								if ($coupon['allvehicles'] == 0) {
									$allitems = array_keys($vricart);
									foreach ($allitems as $idit) {
										if (!(preg_match("/;".$idit.";/i", $coupon['iditems']))) {
											$couponitemok = false;
										}
									}
								}
								if ($couponitemok == true) {
									$coupontotok = true;
									if (strlen($coupon['mintotord']) > 0) {
										if ($isdue < $coupon['mintotord']) {
											$coupontotok = false;
										}
									}
									if ($coupontotok == true) {
										$usedcoupon = true;
										if ($coupon['percentot'] == 1) {
											//percent value
											$minuscoupon = 100 - $coupon['value'];
											$coupondiscount = $isdue * $coupon['value'] / 100;
											$isdue = $isdue * $minuscoupon / 100;
										} else {
											//total value
											$coupondiscount = $coupon['value'];
											$isdue = $isdue - $coupon['value'];
										}
										$isdue = $isdue < 0 ? 0 : $isdue;
										$strcouponeff = $coupon['id'].';'.$coupondiscount.';'.$coupon['code'];
									}
								}
							}
						}
					}
					//
					$strisdue = number_format($isdue, 2)."vikrentitems";
					$ptotdue = number_format($ptotdue, 2)."vikrentitems";
					if ($strisdue == $ptotdue || !($isdue > 0)) {
						$nowts = time();
						$checkts = $nowts;
						$today_bookings = VikRentItems::todayBookings();
						if ($today_bookings) {
							$checkts = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
						}
						if ($checkts <= $ppickup && $checkts < $prelease && $ppickup < $prelease) {
							if ($validlocations == true) {
								$itemsnotlocked = true;
								$itemsnotbooked = true;
								$erroritemname = "";
								$q = "SELECT `id`,`name`,`units` FROM `#__vikrentitems_items` WHERE `id` IN (" . implode(",", array_unique($pitem)) . ");";
								$dbo->setQuery($q);
								$dbo->execute();
								$allunits = $dbo->loadAssocList();
								foreach ($allunits as $itunits) {
									if (!VikRentItems::itemNotLocked($itunits['id'], $itunits['units'], $ppickup, $prelease, $totitemquants[$itunits['id']])) {
										$itemsnotlocked = false;
										$erroritemname = $itunits['name'];
										break;
									}
									if (!VikRentItems::itemBookable($itunits['id'], $itunits['units'], $ppickup, $prelease, $totitemquants[$itunits['id']])) {
										$itemsnotbooked = false;
										$erroritemname = $itunits['name'];
										break;
									}
								}
								if ($itemsnotlocked === true) {
									if ($itemsnotbooked === true) {
										//vikrentitems 1.1 restore $pdays to the actual days used
										if (strlen($porigdays) > 0) {
											$pdays = $calcdays;
										}
										//
										$sid = VikRentItems::getSecretLink();
										$custdata = VikRentItems::buildCustData($arrcustdata, "\n");
										$viklink = VikRentItems::externalroute("index.php?option=com_vikrentitems&view=order&sid=" . $sid . "&ts=" . $nowts . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false);
										$admail = VikRentItems::getAdminMail();
										$ftitle = VikRentItems::getFrontTitle($vri_tn);
										$extra_emails = array();
										foreach ($vricart as $iditem => $itemarrparent) {
											$iteminfo = VikRentItems::getItemInfo($iditem, $vri_tn);
											$extraemail = VikRentItems::getItemParam($iteminfo['params'], 'extraemail');
											if (!empty($extraemail) && !in_array($extraemail, $extra_emails)) {
												array_push($extra_emails, $extraemail);
											}
											foreach ($itemarrparent as $k => $itemarr) {
												$costplusiva = VikRentItems::sayCostPlusIva($itemarr['tar'][0]['cost'] * $itemarr['itemquant'], $itemarr['tar'][0]['idprice']);
												$costminusiva = VikRentItems::sayCostMinusIva($itemarr['tar'][0]['cost'] * $itemarr['itemquant'], $itemarr['tar'][0]['idprice']);
												$pricestr = VikRentItems::getPriceName($itemarr['tar'][0]['idprice'], $vri_tn) . ": " . $costplusiva . (!empty($itemarr['tar'][0]['attrdata']) ? " " . $currencyname . "\n" . VikRentItems::getPriceAttr($itemarr['tar'][0]['idprice'], $vri_tn) . ": " . $itemarr['tar'][0]['attrdata'] : "");
												$arrayinfopdf = array('days' => $pdays, 'tarminusiva' => $costminusiva, 'tartax' => ($costplusiva - $costminusiva), 'opttaxnet' => $vricart[$iditem][$k]['optarrtaxnet'], 'locfeenet' => $locfeewithouttax);
												$vricart[$iditem][$k]['pricestr'] = $pricestr;
												$vricart[$iditem][$k]['info'] = $iteminfo;
												$vricart[$iditem][$k]['infopdf'] = $arrayinfopdf;
											}
										}
										$ritplace = (!empty($pplace) ? VikRentItems::getPlaceName($pplace, $vri_tn) : "");
										$consegnaplace = (!empty($preturnplace) ? VikRentItems::getPlaceName($preturnplace, $vri_tn) : "");
										//VRI 1.1 Rev.2
										$session = JFactory::getSession();
										$locationvat = $session->get('vriLocationTaxRate', '');
										//
										//empty the cart
										$session->set('vriCart', '');
										$session->set('vriDeliveryCart', '');
										//
										// customer booking
										$cpin = VikRentItems::getCPinIstance();
										$cpin->setCustomerExtraInfo($fieldflags);
										$cpin->saveCustomerDetails($t_first_name, $t_last_name, $useremail, $phone_number, $usercountry, $arrcfields);
										//
										$langtag = $vri_tn->current_lang;
										if (VikRentItems::areTherePayments()) {
											$payment = VikRentItems::getPayment($pgpayid, $vri_tn);
											$realback = VikRentItems::getHoursItemAvail() * 3600;
											$realback += $prelease;
											if (is_array($payment)) {
												//when order total not greater than zero, set status to Confirmed
												if (!($isdue > 0) && intval($payment['setconfirmed']) != 1) {
													$payment['setconfirmed'] = 1;
												}
												//
												if (intval($payment['setconfirmed']) == 1) {
													$arrbusy = array();
													foreach ($vricart as $iditem => $itemarrparent) {
														$kit_relations = VikRentItems::getKitRelatedItems($iditem);
														$kit_key = false;
														foreach ($itemarrparent as $k => $itemarr) {
															$kit_key = $kit_key === false ? $k : $kit_key;
															for ($i = 1; $i <= $itemarr['itemquant']; $i++) {
																$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($iditem) . ", '" . $ppickup . "', '" . $prelease . "','" . $realback . "');";
																$dbo->setQuery($q);
																$dbo->execute();
																$lid = $dbo->insertid();
																$arrbusy[$iditem][$k][] = $lid;
															}
														}
														if (count($kit_relations) && $kit_key !== false) {
															//VRI 1.5 - store busy records for the children or parent items, in case of a kit (Group/Set of Items)
															foreach ($kit_relations as $kit_rel) {
																for ($i = 1; $i <= $kit_rel['units']; $i++) {
																	$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($kit_rel['iditem']) . ", '" . $ppickup . "', '" . $prelease . "','" . $realback . "');";
																	$dbo->setQuery($q);
																	$dbo->execute();
																	$lid = $dbo->insertid();
																	//current item in the cart and first key of the array ($kit_key = pair of unit/info in the cart)
																	$arrbusy[$iditem][$kit_key][] = $lid;
																}
															}
															//
														}
													}
													$q = "INSERT INTO `#__vikrentitems_orders` (`custdata`,`ts`,`status`,`days`,`ritiro`,`consegna`,`custmail`,`sid`,`idplace`,`idreturnplace`,`totpaid`,`idpayment`,`ujid`,`hourly`,`coupon`,`order_total`,`locationvat`,`deliverycost`,`lang`,`country`,`phone`,`nominative`) VALUES(" . $dbo->quote($custdata) . ",'" . $nowts . "','confirmed'," . $dbo->quote($pdays) . "," . $dbo->quote($ppickup) . "," . $dbo->quote($prelease) . "," . $dbo->quote($useremail) . ",'" . $sid . "'," . $dbo->quote($pplace) . "," . $dbo->quote($preturnplace) . "," . $dbo->quote($isdue) . "," . $dbo->quote($payment['id'] . '=' . $payment['name']) . ",'".$currentUser->id."','".($usedhourly ? "1" : "0")."', ".($usedcoupon == true ? $dbo->quote($strcouponeff) : "''").", '".$isdue."', ".(strlen($locationvat) > 0 ? "'".$locationvat."'" : "null").", '".$totdelivery."', ".$dbo->quote($langtag).", ".(!empty($usercountry) ? $dbo->quote($usercountry) : 'null').", ".$dbo->quote($phone_number).", ".$dbo->quote($nominative_str).");";
													$dbo->setQuery($q);
													$dbo->execute();
													$neworderid = $dbo->insertid();
													//Customer Booking
													$cpin->saveCustomerBooking($neworderid);
													//end Customer Booking
													foreach ($vricart as $iditem => $itemarrparent) {
														foreach ($itemarrparent as $k => $itemarr) {
															foreach ($arrbusy[$iditem][$k] as $busyid) {
																$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES('".$neworderid."', '".$busyid."');";
																$dbo->setQuery($q);
																$dbo->execute();
															}
															$q = "INSERT INTO `#__vikrentitems_ordersitems` (`idorder`,`iditem`,`idtar`,`optionals`,`itemquant`,`timeslot`,`deliveryaddr`,`deliverydist`) VALUES('".$neworderid."', '".$iditem."', '".$itemarr['tar'][0]['id']."', '".$itemarr['optionals']."', '".$itemarr['itemquant']."', ".(array_key_exists('timeslot', $itemarr) ? $dbo->quote($itemarr['timeslot']['name']) : "null").", ".(array_key_exists('delivery', $itemarr) ? $dbo->quote($itemarr['delivery']['vrideliveryaddress']) : "null").", ".(array_key_exists('delivery', $itemarr) ? "'".floatval($itemarr['delivery']['vrideliverydistance'])."'" : "null").");";
															$dbo->setQuery($q);
															$dbo->execute();
														}
													}
													if ($usedcoupon == true && $coupon['type'] == 2) {
														$q = "DELETE FROM `#__vikrentitems_coupons` WHERE `id`='".$coupon['id']."';";
														$dbo->setQuery($q);
														$dbo->execute();
													}

													// send email notification to customer and admin
													$recips = array('customer', 'admin');
													if (count($extra_emails)) {
														$recips = array_merge($recips, $extra_emails);
													}
													VikRentItems::sendOrderEmail($neworderid, $recips);
													//

													// VikRentItems::sendAdminMail($admail.';;'.$useremail, JText::translate('VRORDNOL'), $ftitle, $neworderid, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $payment['name'], $strcouponeff, $totdelivery);
													// VikRentItems::sendCustMail($useremail, strip_tags($ftitle) . " " . JText::translate('VRORDNOL'), $ftitle, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, $viklink, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $neworderid, $strcouponeff, $totdelivery);
													// if (count($extra_emails)) {
													// 	foreach ($extra_emails as $extraemail) {
													// 		VikRentItems::sendCustMail($extraemail, strip_tags($ftitle) . " " . JText::translate('VRORDNOL'), $ftitle, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, $viklink, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $neworderid, $strcouponeff, $totdelivery);
													// 	}
													// }

													$mainframe = JFactory::getApplication();
													$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=order&sid=" . $sid . "&ts=" . $nowts . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
												} else {
													$q = "INSERT INTO `#__vikrentitems_orders` (`custdata`,`ts`,`status`,`days`,`ritiro`,`consegna`,`custmail`,`sid`,`idplace`,`idreturnplace`,`idpayment`,`ujid`,`hourly`,`coupon`,`order_total`,`locationvat`,`deliverycost`,`lang`,`country`,`phone`,`nominative`) VALUES(" . $dbo->quote($custdata) . ",'" . $nowts . "','standby'," . $dbo->quote($pdays) . "," . $dbo->quote($ppickup) . "," . $dbo->quote($prelease) . "," . $dbo->quote($useremail) . ",'" . $sid . "'," . $dbo->quote($pplace) . "," . $dbo->quote($preturnplace) . "," . $dbo->quote($payment['id'] . '=' . $payment['name']) . ",'".$currentUser->id."','".($usedhourly ? "1" : "0")."', ".($usedcoupon == true ? $dbo->quote($strcouponeff) : "''").", '".$isdue."', ".(strlen($locationvat) > 0 ? "'".$locationvat."'" : "null").", '".$totdelivery."', ".$dbo->quote($langtag).", ".(!empty($usercountry) ? $dbo->quote($usercountry) : 'null').", ".$dbo->quote($phone_number).", ".$dbo->quote($nominative_str).");";
													$dbo->setQuery($q);
													$dbo->execute();
													$neworderid = $dbo->insertid();
													//Customer Booking
													$cpin->saveCustomerBooking($neworderid);
													//end Customer Booking
													foreach ($vricart as $iditem => $itemarrparent) {
														foreach ($itemarrparent as $k => $itemarr) {
															$q = "INSERT INTO `#__vikrentitems_ordersitems` (`idorder`,`iditem`,`idtar`,`optionals`,`itemquant`,`timeslot`,`deliveryaddr`,`deliverydist`) VALUES('".$neworderid."', '".$iditem."', '".$itemarr['tar'][0]['id']."', '".$itemarr['optionals']."', '".$itemarr['itemquant']."', ".(array_key_exists('timeslot', $itemarr) ? $dbo->quote($itemarr['timeslot']['name']) : "null").", ".(array_key_exists('delivery', $itemarr) ? $dbo->quote($itemarr['delivery']['vrideliveryaddress']) : "null").", ".(array_key_exists('delivery', $itemarr) ? "'".floatval($itemarr['delivery']['vrideliverydistance'])."'" : "null").");";
															$dbo->setQuery($q);
															$dbo->execute();
														}
													}
													if ($usedcoupon == true && $coupon['type'] == 2) {
														$q = "DELETE FROM `#__vikrentitems_coupons` WHERE `id`='".$coupon['id']."';";
														$dbo->setQuery($q);
														$dbo->execute();
													}
													foreach ($vricart as $iditem => $itemarrparent) {
														foreach ($itemarrparent as $k => $itemarr) {
															for($i = 1; $i <= $itemarr['itemquant']; $i++) {
																$q = "INSERT INTO `#__vikrentitems_tmplock` (`iditem`,`ritiro`,`consegna`,`until`,`realback`,`idorder`) VALUES(" . $dbo->quote($iditem) . ",'" . $ppickup . "','" . $prelease . "','" . VikRentItems::getMinutesLock(true) . "','" . $realback . "', ".(int)$neworderid.");";
																$dbo->setQuery($q);
																$dbo->execute();
															}
														}
													}

													// send email notification to customer and admin
													$recips = array('customer', 'admin');
													if (count($extra_emails)) {
														$recips = array_merge($recips, $extra_emails);
													}
													VikRentItems::sendOrderEmail($neworderid, $recips);
													//

													// VikRentItems::sendAdminMail($admail.';;'.$useremail, JText::translate('VRORDNOL'), $ftitle, $neworderid, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, JText::translate('VRINATTESA'), $ritplace, $consegnaplace, $maillocfee, $payment['name'], $strcouponeff, $totdelivery);
													// VikRentItems::sendCustMail($useremail, strip_tags($ftitle) . " " . JText::translate('VRORDNOL'), $ftitle, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, $viklink, JText::translate('VRINATTESA'), $ritplace, $consegnaplace, $maillocfee, $neworderid, $strcouponeff, $totdelivery);
													// if (count($extra_emails)) {
													// 	foreach ($extra_emails as $extraemail) {
													// 		VikRentItems::sendCustMail($extraemail, strip_tags($ftitle) . " " . JText::translate('VRORDNOL'), $ftitle, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, $viklink, JText::translate('VRINATTESA'), $ritplace, $consegnaplace, $maillocfee, $neworderid, $strcouponeff, $totdelivery);
													// 	}
													// }

													$mainframe = JFactory::getApplication();
													$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=order&sid=" . $sid . "&ts=" . $nowts . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
												}
											} else {
												VikError::raiseWarning('', JText::translate('ERRSELECTPAYMENT'));
												$mainframe = JFactory::getApplication();
												$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&place=" . $pplace . "&returnplace=" . $preturnplace . "&days=" . $pdays . "&pickup=" . $ppickup . "&release=" . $prelease . "&task=oconfirm" . (!empty($pitemid) ? "&Itemid=" . $pitemid : ""), false));
											}
										} else {
											$realback = VikRentItems::getHoursItemAvail() * 3600;
											$realback += $prelease;
											$arrbusy = array();
											foreach ($vricart as $iditem => $itemarrparent) {
												$kit_relations = VikRentItems::getKitRelatedItems($iditem);
												$kit_key = false;
												foreach ($itemarrparent as $k => $itemarr) {
													$kit_key = $kit_key === false ? $k : $kit_key;
													for($i = 1; $i <= $itemarr['itemquant']; $i++) {
														$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($iditem) . ", '" . $ppickup . "', '" . $prelease . "','" . $realback . "');";
														$dbo->setQuery($q);
														$dbo->execute();
														$lid = $dbo->insertid();
														$arrbusy[$iditem][$k][] = $lid;
													}
												}
												if (count($kit_relations) && $kit_key !== false) {
													//VRI 1.5 - store busy records for the children or parent items, in case of a kit (Group/Set of Items)
													foreach ($kit_relations as $kit_rel) {
														for ($i = 1; $i <= $kit_rel['units']; $i++) {
															$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($kit_rel['iditem']) . ", '" . $ppickup . "', '" . $prelease . "','" . $realback . "');";
															$dbo->setQuery($q);
															$dbo->execute();
															$lid = $dbo->insertid();
															//current item in the cart and first key of the array ($kit_key = pair of unit/info in the cart)
															$arrbusy[$iditem][$kit_key][] = $lid;
														}
													}
													//
												}
											}
											$q = "INSERT INTO `#__vikrentitems_orders` (`custdata`,`ts`,`status`,`days`,`ritiro`,`consegna`,`custmail`,`sid`,`idplace`,`idreturnplace`,`totpaid`,`ujid`,`hourly`,`coupon`,`order_total`,`locationvat`,`deliverycost`,`lang`,`country`,`phone`,`nominative`) VALUES(" . $dbo->quote($custdata) . ",'" . $nowts . "','confirmed'," . $dbo->quote($pdays) . "," . $dbo->quote($ppickup) . "," . $dbo->quote($prelease) . "," . $dbo->quote($useremail) . ",'" . $sid . "'," . $dbo->quote($pplace) . "," . $dbo->quote($preturnplace) . "," . $dbo->quote($isdue) . ",'".$currentUser->id."','".($usedhourly ? "1" : "0")."', ".($usedcoupon == true ? $dbo->quote($strcouponeff) : "''").", '".$isdue."', ".(strlen($locationvat) > 0 ? "'".$locationvat."'" : "null").", '".$totdelivery."', ".$dbo->quote($langtag).", ".(!empty($usercountry) ? $dbo->quote($usercountry) : 'null').", ".$dbo->quote($phone_number).", ".$dbo->quote($nominative_str).");";
											$dbo->setQuery($q);
											$dbo->execute();
											$neworderid = $dbo->insertid();
											//Customer Booking
											$cpin->saveCustomerBooking($neworderid);
											//end Customer Booking
											foreach ($vricart as $iditem => $itemarrparent) {
												foreach ($itemarrparent as $k => $itemarr) {
													foreach ($arrbusy[$iditem][$k] as $busyid) {
														$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES('".$neworderid."', '".$busyid."');";
														$dbo->setQuery($q);
														$dbo->execute();
													}
													$q = "INSERT INTO `#__vikrentitems_ordersitems` (`idorder`,`iditem`,`idtar`,`optionals`,`itemquant`,`timeslot`,`deliveryaddr`,`deliverydist`) VALUES('".$neworderid."', '".$iditem."', '".$itemarr['tar'][0]['id']."', '".$itemarr['optionals']."', '".$itemarr['itemquant']."', ".(array_key_exists('timeslot', $itemarr) ? $dbo->quote($itemarr['timeslot']['name']) : "null").", ".(array_key_exists('delivery', $itemarr) ? $dbo->quote($itemarr['delivery']['vrideliveryaddress']) : "null").", ".(array_key_exists('delivery', $itemarr) ? "'".floatval($itemarr['delivery']['vrideliverydistance'])."'" : "null").");";
													$dbo->setQuery($q);
													$dbo->execute();
												}
											}
											if ($usedcoupon == true && $coupon['type'] == 2) {
												$q = "DELETE FROM `#__vikrentitems_coupons` WHERE `id`='".$coupon['id']."';";
												$dbo->setQuery($q);
												$dbo->execute();
											}

											// send email notification to customer and admin
											$recips = array('customer', 'admin');
											if (count($extra_emails)) {
												$recips = array_merge($recips, $extra_emails);
											}
											VikRentItems::sendOrderEmail($neworderid, $recips);
											//

											// VikRentItems::sendAdminMail($admail.';;'.$useremail, JText::translate('VRORDNOL'), $ftitle, $neworderid, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $strcouponeff, $totdelivery);
											// VikRentItems::sendCustMail($useremail, strip_tags($ftitle) . " " . JText::translate('VRORDNOL'), $ftitle, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, $viklink, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $neworderid, $strcouponeff, $totdelivery);
											// if (count($extra_emails)) {
											// 	foreach ($extra_emails as $extraemail) {
											// 		VikRentItems::sendCustMail($extraemail, strip_tags($ftitle) . " " . JText::translate('VRORDNOL'), $ftitle, $nowts, $custdata, $vricart, $ppickup, $prelease, $isdue, $viklink, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $neworderid, $strcouponeff, $totdelivery);
											// 	}
											// }

											echo VikRentItems::getFullFrontTitle();
											?>
											<p class="successmade"><?php echo JText::translate('VRTHANKSONE'); ?></p>
											<br/>
											<p>&bull; <?php echo JText::translate('VRTHANKSTWO'); ?> <a href="<?php echo $viklink; ?>"><?php echo JText::translate('VRTHANKSTHREE'); ?></a></p>
											<?php
										}
									} else {
										showSelectVRI(JText::sprintf('VRIARBOOKEDBYOTHER', $erroritemname));
									}
								} else {
									showSelectVRI(JText::sprintf('VRIARISLOCKED', $erroritemname));
								}
							} else {
								showSelectVRI(JText::translate('VRINVALIDLOCATIONS'));
							}
						} else {
							showSelectVRI(JText::translate('VRINVALIDDATES'));
						}
					} else {
						showSelectVRI(JText::translate('VRINCONGRTOT'));
					}
				} else {
					showSelectVRI(JText::translate('VRINCONGRDATA'));
				}
			} else {
				showSelectVRI(JText::translate('VRINSUFDATA'));
			}
		} else {
			showSelectVRI(JText::translate('VRINVALIDTOKEN'));
		}
	}

	function vieworder() {
		VikRequest::setVar('view', 'order');
		parent::display();
	}
	
	function cancelrequest() {
		$psid = VikRequest::getString('sid', '', 'request');
		$pidorder = VikRequest::getString('idorder', '', 'request');
		$pitemid = VikRequest::getString('Itemid', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (empty($psid) || empty($pidorder)) {
			throw new Exception("No data. Order not found", 404);
		}
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".intval($pidorder)." AND `sid`=".$dbo->quote($psid).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if (!$dbo->getNumRows()) {
			throw new Exception("Order not found", 404);
		}
		$order = $dbo->loadAssocList();
		$pemail = VikRequest::getString('email', '', 'request');
		$preason = VikRequest::getString('reason', '', 'request');
		if (!empty($pemail) && !empty($preason)) {
			$to = VikRentItems::getAdminMail();
			$subject = JText::translate('VRICANCREQUESTEMAILSUBJ');
			$bestitemid = VikRentItems::findProperItemIdType(array('order'));
			$uri = VikRentItems::externalroute("index.php?option=com_vikrentitems&view=order&sid=" . $order[0]['sid'] . "&ts=" . $order[0]['ts'], false, (!empty($bestitemid) ? $bestitemid : null));
			$msg = JText::sprintf('VRICANCREQUESTEMAILHEAD', $order[0]['id'], $uri)."\n\n".$preason;
			$adsendermail = VikRentItems::getSenderMail();
			$vri_app = VikRentItems::getVriApplication();
			$vri_app->sendMail($adsendermail, $adsendermail, $to, $pemail, $subject, $msg, false);
			$mainframe->enqueueMessage(JText::translate('VRICANCREQUESTMAILSENT'));
			$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=order&sid=".$order[0]['sid']."&ts=".$order[0]['ts']."&Itemid=".$pitemid, false));
		} else {
			$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=order&sid=".$order[0]['sid']."&ts=".$order[0]['ts']."&Itemid=".$pitemid, false));
		}
	}

	function validatepin() {
		$ppin = VikRequest::getString('pin', '', 'request');
		$cpin = VikRentItems::getCPinIstance();
		$response = array();
		$customer = $cpin->getCustomerByPin($ppin);
		if (count($customer) > 0) {
			$response = $customer;
			$response['success'] = 1;
		}
		echo json_encode($response);
		exit;
	}
	
	function notifypayment() {
		$vri_tn = VikRentItems::getTranslator();
		$psid = VikRequest::getString('sid', '', 'request');
		$pts = VikRequest::getString('ts', '', 'request');
		$dbo = JFactory::getDbo();
		$nowdf = VikRentItems::getDateFormat();
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		if (strlen($psid) && strlen($pts)) {
			$admail = VikRentItems::getAdminMail();
			$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `ts`=" . $dbo->quote($pts) . " AND `sid`=" . $dbo->quote($psid) . ";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$rows = $dbo->loadAssocList();
				//check if the language in use is the same as the one used during the checkout
				if (!empty($rows[0]['lang'])) {
					$lang = JFactory::getLanguage();
					if ($lang->getTag() != $rows[0]['lang']) {
						$lang->load('com_vikrentitems', JPATH_SITE, $rows[0]['lang'], true);
						$vri_tn::$force_tolang = $rows[0]['lang'];
					}
				}
				//
				if ($rows[0]['status']!='confirmed') {
					$rows[0]['admin_email'] = $admail;
					$totdelivery = !empty($rows[0]['deliverycost']) && $rows[0]['deliverycost'] > 0 ? $rows[0]['deliverycost'] : 0;
					$exppay = explode('=', $rows[0]['idpayment']);
					$payment = VikRentItems::getPayment($exppay[0], $vri_tn);

					/**
					 * @wponly 	The payment gateway is now loaded 
					 * 			using the apposite dispatcher.
					 *
					 * @since 1.0.0
					 */
					JLoader::import('adapter.payment.dispatcher');

					$obj = JPaymentDispatcher::getInstance('vikrentitems', $payment['file'], $rows[0], $payment['params']);
					$array_result = $obj->validatePayment();
					//

					if ($array_result['verified'] == 1) {
						$newpaymentlog = date('c')."\n".$array_result['log']."\n----------\n".$rows[0]['paymentlog'];
						//valid payment
						$ritplace = (!empty($rows[0]['idplace']) ? VikRentItems::getPlaceName($rows[0]['idplace'], $vri_tn) : "");
						$consegnaplace = (!empty($rows[0]['idreturnplace']) ? VikRentItems::getPlaceName($rows[0]['idreturnplace'], $vri_tn) : "");
						$realback = VikRentItems::getHoursItemAvail() * 3600;
						$realback += $rows[0]['consegna'];
						//send mails
						$vricart = array();
						$ftitle = VikRentItems::getFrontTitle($vri_tn);
						$nowts = time();
						$viklink = VikRentItems::externalroute("index.php?option=com_vikrentitems&view=order&sid=" . $psid . "&ts=" . $pts, false);
						$checkhourscharges = 0;
						$hoursdiff = 0;
						$ppickup = $rows[0]['ritiro'];
						$prelease = $rows[0]['consegna'];
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
						$currencyname = VikRentItems::getCurrencyName();
						$isdue = 0;
						$maillocfee = "";
						$locfeewithouttax = 0;
						if (!empty($rows[0]['idplace']) && !empty($rows[0]['idreturnplace'])) {
							$locfee = VikRentItems::getLocFee($rows[0]['idplace'], $rows[0]['idreturnplace']);
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
									if (array_key_exists($rows[0]['days'], $arrvaloverrides)) {
										$locfee['cost'] = $arrvaloverrides[$rows[0]['days']];
									}
								}
								//end VikRentItems 1.1 - Location fees overrides
								$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $rows[0]['days']) : $locfee['cost'];
								$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva'], $rows[0]);
								$isdue += $locfeewith;
								$locfeewithouttax = VikRentItems::sayLocFeeMinusIva($locfeecost, $locfee['idiva'], $rows[0]);
								$maillocfee = $locfeewith;
							}
						}
						$q = "SELECT `oi`.`iditem`,`oi`.`idtar`,`oi`.`optionals`,`oi`.`itemquant`,`oi`.`timeslot`,`oi`.`deliveryaddr`,`oi`.`deliverydist`,`i`.`name`,`i`.`img`,`i`.`idcarat`,`i`.`info`,`i`.`moreimgs` FROM `#__vikrentitems_ordersitems` AS `oi`,`#__vikrentitems_items` AS `i` WHERE `oi`.`idorder`='".$rows[0]['id']."' AND `oi`.`iditem`=`i`.`id` ORDER BY `oi`.`id` ASC;";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() > 0) {
							$orderitems = $dbo->loadAssocList();
							foreach ($orderitems as $koi => $oi) {
								if ($rows[0]['hourly'] == 1) {
									$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `id`='" . $oi['idtar'] . "';";
								} else {
									$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`='" . $oi['idtar'] . "';";
								}
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() == 1) {
									$tar = $dbo->loadAssocList();
								} elseif ($rows[0]['hourly'] == 1) {
									//one of the items chosen does not have hourly prices
									$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`='" . $oi['idtar'] . "';";
									$dbo->setQuery($q);
									$dbo->execute();
									$tar = $dbo->loadAssocList();
								}
								if ($rows[0]['hourly'] == 1) {
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
									$tar = VikRentItems::applySeasonsItem($tar, $rows[0]['ritiro'], $rows[0]['consegna'], $rows[0]['idplace']);
									$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true, true, true);
									$tar = $ret['return'];
									$calcdays = $ret['days'];
								} else {
									$tar = VikRentItems::applySeasonsItem($tar, $rows[0]['ritiro'], $rows[0]['consegna'], $rows[0]['idplace']);
								}
								$tar = VikRentItems::applyItemDiscounts($tar, $oi['iditem'], $oi['itemquant']);
								$costplusiva = VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $rows[0]);
								$costminusiva = VikRentItems::sayCostMinusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $rows[0]);
								$pricestr = VikRentItems::getPriceName($tar[0]['idprice'], $vri_tn) . ": " . $costplusiva . (!empty($tar[0]['attrdata']) ? "\n" . VikRentItems::getPriceAttr($tar[0]['idprice'], $vri_tn) . ": " . $tar[0]['attrdata'] : "");
								$isdue += VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $rows[0]);
								$vricart[$oi['iditem']][$koi]['itemquant'] = $oi['itemquant'];
								$vricart[$oi['iditem']][$koi]['pricestr'] = $pricestr;
								$vricart[$oi['iditem']][$koi]['info'] = VikRentItems::getItemInfo($oi['iditem'], $vri_tn);
								if (!empty($oi['timeslot'])) {
									$vricart[$oi['iditem']][$koi]['timeslot']['name'] = $oi['timeslot'];
								}
								if (!empty($oi['deliveryaddr'])) {
									$vricart[$oi['iditem']][$koi]['delivery']['vrideliveryaddress'] = $oi['deliveryaddr'];
									$vricart[$oi['iditem']][$koi]['delivery']['vrideliverydistance'] = $oi['deliverydist'];
								}
								$optstr = "";
								$optarrtaxnet = array();
								if (!empty($oi['optionals'])) {
									$stepo = explode(";", $oi['optionals']);
									foreach ($stepo as $oo) {
										if (!empty($oo)) {
											$stept = explode(":", $oo);
											$q = "SELECT * FROM `#__vikrentitems_optionals` WHERE `id`='" . intval($stept[0]) . "';";
											$dbo->setQuery($q);
											$dbo->execute();
											if ($dbo->getNumRows() == 1) {
												$actopt = $dbo->loadAssocList();
												$vri_tn->translateContents($actopt, '#__vikrentitems_optionals');
												$specvar = '';
												if (!empty($actopt[0]['specifications']) && strstr($stept[1], '-') != false) {
													$optspeccosts = VikRentItems::getOptionSpecIntervalsCosts($actopt[0]['specifications']);
													$optspecnames = VikRentItems::getOptionSpecIntervalsNames($actopt[0]['specifications']);
													$specstept = explode('-', $stept[1]);
													$stept[1] = $specstept[0];
													$specvar = $specstept[1];
													$actopt[0]['specintv'] = $specvar;
													$actopt[0]['name'] .= ' ('.$optspecnames[($specvar - 1)].')';
													$actopt[0]['quan'] = $stept[1];
													$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optspeccosts[($specvar - 1)]) * $rows[0]['days'] * $stept[1]) : (floatval($optspeccosts[($specvar - 1)]) * $stept[1]));
												} else {
													$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $rows[0]['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
												}
												if (!empty($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
													$realcost = $actopt[0]['maxprice'];
													if (intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
														$realcost = $actopt[0]['maxprice'] * $stept[1];
													}
												}
												$opt_item_units = $actopt[0]['onceperitem'] ? 1 : $oi['itemquant'];
												$tmpopr = VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $rows[0]);
												$isdue += $tmpopr;
												$optnetprice = VikRentItems::sayOptionalsMinusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $rows[0]);
												$optarrtaxnet[] = $optnetprice;
												$optstr .= ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": " . $tmpopr . " " . $currencyname . "\n";
											}
										}
									}
								}
								$vricart[$oi['iditem']][$koi]['optstr'] = $optstr;
								$vricart[$oi['iditem']][$koi]['optarrtaxnet'] = $optarrtaxnet;
								$arrayinfopdf = array('days' => $rows[0]['days'], 'tarminusiva' => $costminusiva, 'tartax' => ($costplusiva - $costminusiva), 'opttaxnet' => $optarrtaxnet, 'locfeenet' => $locfeewithouttax);
								if (array_key_exists('tot_paid', $array_result)) {
									$arrayinfopdf['tot_paid'] = $array_result['tot_paid'];
								}
								$vricart[$oi['iditem']][$koi]['infopdf'] = $arrayinfopdf;
							}
						}
						//delivery service
						if ($totdelivery > 0) {
							$isdue += $totdelivery;
						}
						//
						//vikrentitems 1.1 coupon
						$usedcoupon = false;
						$origisdue = $isdue;
						if (strlen($rows[0]['coupon']) > 0) {
							$usedcoupon = true;
							$expcoupon = explode(";", $rows[0]['coupon']);
							$isdue = $isdue - $expcoupon[1];
						}
						//
						$shouldpay = $isdue;
						if ($payment['charge'] > 0.00) {
							if ($payment['ch_disc'] == 1) {
								//charge
								if ($payment['val_pcent'] == 1) {
									//fixed value
									$shouldpay += $payment['charge'];
								} else {
									//percent value
									$percent_to_pay = $shouldpay * $payment['charge'] / 100;
									$shouldpay += $percent_to_pay;
								}
							} else {
								//discount
								if ($payment['val_pcent'] == 1) {
									//fixed value
									$shouldpay -= $payment['charge'];
								} else {
									//percent value
									$percent_to_pay = $shouldpay * $payment['charge'] / 100;
									$shouldpay -= $percent_to_pay;
								}
							}
						}
						if (!VikRentItems::payTotal()) {
							$percentdeposit = VikRentItems::getAccPerCent();
							if ($percentdeposit > 0) {
								if (VikRentItems::getTypeDeposit() == "fixed") {
									$shouldpay = $percentdeposit;
								} else {
									$shouldpay = $shouldpay * $percentdeposit / 100;
								}
							}
						}
						//check if the total amount paid is the same as the total order
						if (isset($array_result['tot_paid'])) {
							$shouldpay = round($shouldpay, 2);
							$totreceived = round($array_result['tot_paid'], 2);
							if ($shouldpay != $totreceived) {
								//the amount paid is different than the total order
								//fares might have changed or the deposit might be different
								//Sending just an email to the admin that will check
								$vri_app = VikRentItems::getVriApplication();
								$vri_app->sendMail($admail, $admail, $admail, $admail, JText::translate('VRITOTPAYMENTINVALID'), JText::sprintf('VRITOTPAYMENTINVALIDTXT', $rows[0]['id'], $totreceived." (".$array_result['tot_paid'].")", $shouldpay), false);
							}
						}
						//
						$extra_emails = array();
						foreach ($vricart as $iditem => $itemarrparent) {
							$extraemail = VikRentItems::getItemParam($itemarrparent[0]['info']['params'], 'extraemail');
							if (!empty($extraemail) && !in_array($extraemail, $extra_emails)) {
								array_push($extra_emails, $extraemail);
							}
							$kit_relations = VikRentItems::getKitRelatedItems($iditem);
							foreach ($itemarrparent as $k => $itemarr) {
								for($i = 1; $i <= $itemarr['itemquant']; $i++) {
									$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $iditem . ", '" . $rows[0]['ritiro'] . "', '" . $rows[0]['consegna'] . "','" . $realback . "');";
									$dbo->setQuery($q);
									$dbo->execute();
									$lid = $dbo->insertid();
									$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES('".$rows[0]['id']."', '".$lid."');";
									$dbo->setQuery($q);
									$dbo->execute();
								}
							}
							if (count($kit_relations)) {
								//VRI 1.5 - store busy records for the children or parent items, in case of a kit (Group/Set of Items)
								foreach ($kit_relations as $kit_rel) {
									for ($i = 1; $i <= $kit_rel['units']; $i++) {
										$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($kit_rel['iditem']) . ", '" . $rows[0]['ritiro'] . "', '" . $rows[0]['consegna'] . "','" . $realback . "');";
										$dbo->setQuery($q);
										$dbo->execute();
										$lid = $dbo->insertid();
										$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES('".$rows[0]['id']."', '".$lid."');";
										$dbo->setQuery($q);
										$dbo->execute();
									}
								}
								//
							}
						}
						$q = "UPDATE `#__vikrentitems_orders` SET `status`='confirmed'" . ($array_result['tot_paid'] ? ", `totpaid`='" . $array_result['tot_paid'] . "'" : "") . (!empty($array_result['log']) ? ", `paymentlog`=".$dbo->quote($newpaymentlog) : "") . " WHERE `id`='" . $rows[0]['id'] . "';";
						$dbo->setQuery($q);
						$dbo->execute();
						//VRI 1.4 : unlock items for other imminent bookings
						$q = "DELETE FROM `#__vikrentitems_tmplock` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
						$dbo->setQuery($q);
						$dbo->execute();
						//

						// send email notification to customer and admin
						$recips = array('customer', 'admin');
						if (count($extra_emails)) {
							$recips = array_merge($recips, $extra_emails);
						}
						VikRentItems::sendOrderEmail($rows[0]['id'], $recips);
						//

						// VikRentItems::sendAdminMail($admail.';;'.$rows[0]['custmail'], JText::translate('VRRENTALORD'), $ftitle, $rows[0]['id'], $nowts, $rows[0]['custdata'], $vricart, $rows[0]['ritiro'], $rows[0]['consegna'], $isdue, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $payment['name'], $rows[0]['coupon'], $totdelivery);
						// VikRentItems::sendCustMail($rows[0]['custmail'], strip_tags($ftitle) . " " . JText::translate('VRRENTALORD'), $ftitle, $nowts, $rows[0]['custdata'], $vricart, $rows[0]['ritiro'], $rows[0]['consegna'], $isdue, $viklink, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $rows[0]['id'], $rows[0]['coupon'], $totdelivery);
						// if (count($extra_emails)) {
						// 	foreach ($extra_emails as $extraemail) {
						// 		VikRentItems::sendCustMail($extraemail, strip_tags($ftitle) . " " . JText::translate('VRRENTALORD'), $ftitle, $nowts, $rows[0]['custdata'], $vricart, $rows[0]['ritiro'], $rows[0]['consegna'], $isdue, $viklink, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $rows[0]['id'], $rows[0]['coupon'], $totdelivery);
						// 	}
						// }
						
						if (method_exists($obj, 'afterValidation')) {
							$obj->afterValidation(1);
						}
					} else {
						if (!array_key_exists('skip_email', $array_result) || $array_result['skip_email'] != 1) {
							$vri_app = VikRentItems::getVriApplication();
							$vri_app->sendMail($admail, $admail, $admail, $admail, JText::translate('VRPAYMENTNOTVER'), JText::translate('VRSERVRESP') . ":\n\n" . $array_result['log'], false);
						}
						if (method_exists($obj, 'afterValidation')) {
							$obj->afterValidation(0);
						}
					}
				}
			}
		}
		exit;
	}
	
	function ajaxlocopentime() {
		$dbo = JFactory::getDbo();
		$nowtf = VikRentItems::getTimeFormat();
		$pidloc = VikRequest::getInt('idloc', '', 'request');
		$ppickdrop = VikRequest::getString('pickdrop', '', 'request');
		$ret = array();
		$location = array();
		$q = "SELECT `id`,`opentime`,`defaulttime`,`wopening` FROM `#__vikrentitems_places` WHERE `id`=".$pidloc.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$location = $dbo->loadAssoc();
		}
		$opentime = isset($location['opentime']) ? $location['opentime'] : '';
		if (strlen($opentime) > 0) {
			//load location time
			$parts = explode("-", $opentime);
			$opent = VikRentItems::getHoursMinutes($parts[0]);
			$closet = VikRentItems::getHoursMinutes($parts[1]);
			if ($opent != $closet) {
				$i = $opent[0];
				$imin = $opent[1];
				$j = $closet[0];
			} else {
				$i = 0;
				$imin = 0;
				$j = 23;
			}
		} else {
			//load global time
			$timeopst = VikRentItems::getTimeOpenStore();
			if (is_array($timeopst) && $timeopst[0] != $timeopst[1]) {
				$opent = VikRentItems::getHoursMinutes($timeopst[0]);
				$closet = VikRentItems::getHoursMinutes($timeopst[1]);
				$i = $opent[0];
				$imin = $opent[1];
				$j = $closet[0];
			} else {
				$i = 0;
				$imin = 0;
				$j = 23;
			}
		}
		$hours = "";
		//VRI 1.3
		$pickhdeftime = isset($location['defaulttime']) && !empty($location['defaulttime']) ? ((int)$location['defaulttime'] / 3600) : '';
		if (!($i < $j)) {
			while (intval($i) != (int)$j) {
				$sayi = $i < 10 ? "0".$i : $i;
				if ($nowtf != 'H:i') {
					$ampm = $i < 12 ? ' am' : ' pm';
					$ampmh = $i > 12 ? ($i - 12) : $i;
					$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
				} else {
					$sayh = $sayi;
				}
				$hours .= "<option value=\"" . (int)$i . "\"".($pickhdeftime == (int)$i ? ' selected="selected"' : '').">" . $sayh . "</option>\n";
				$i++;
				$i = $i > 23 ? 0 : $i;
			}
			$sayi = $i < 10 ? "0".$i : $i;
			if ($nowtf != 'H:i') {
				$ampm = $i < 12 ? ' am' : ' pm';
				$ampmh = $i > 12 ? ($i - 12) : $i;
				$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
			} else {
				$sayh = $sayi;
			}
			$hours .= "<option value=\"" . (int)$i . "\">" . $sayh . "</option>\n";
		} else {
			while ((int)$i <= $j) {
				$sayi = $i < 10 ? "0".$i : $i;
				if ($nowtf != 'H:i') {
					$ampm = $i < 12 ? ' am' : ' pm';
					$ampmh = $i > 12 ? ($i - 12) : $i;
					$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
				} else {
					$sayh = $sayi;
				}
				$hours .= "<option value=\"" . (int)$i . "\"".($pickhdeftime == (int)$i ? ' selected="selected"' : '').">" . $sayh . "</option>\n";
				$i++;
			}
		}
		//
		$minutes = "";
		for ($i = 0; $i < 60; $i += 15) {
			if ($i < 10) {
				$i = "0" . $i;
			} else {
				$i = $i;
			}
			$minutes .= "<option value=\"" . (int)$i . "\"".((int)$i == $imin ? " selected=\"selected\"" : "").">" . $i . "</option>\n";
		}
		$suffix = $ppickdrop == 'pickup' ? 'pickup' : 'release';

		$forcedpickdroptimes = VikRentItems::getForcedPickDropTimes();
		$keycheck = $ppickdrop == 'pickup' ? 0 : 1;

		if (is_array($forcedpickdroptimes[$keycheck]) && count($forcedpickdroptimes[$keycheck]) > 0) {
			$ret['hours'] = '<input type="hidden" name="'.$suffix.'h" value="'.$forcedpickdroptimes[$keycheck][0].'"/>'.$forcedpickdroptimes[$keycheck][0];
			$ret['minutes'] = '<input type="hidden" name="'.$suffix.'m" value="'.$forcedpickdroptimes[$keycheck][1].'"/>'.$forcedpickdroptimes[$keycheck][1];
		} else {
			$ret['hours'] = '<select name="'.$suffix.'h">'.$hours.'</select>';
			$ret['minutes'] = '<select name="'.$suffix.'m">'.$minutes.'</select>';
		}

		// VRI 1.7 - opening time overrides for week days
		$wopening = array();
		if (isset($location['wopening']) && !empty($location['wopening'])) {
			$wopening = json_decode($location['wopening'], true);
			$wopening = !is_array($wopening) ? array() : $wopening;
		}
		$ret['wopening'] = $wopening;
		//
		
		echo json_encode($ret);
		exit;
	}

	function ical() {
		$dbo = JFactory::getDbo();
		$vri_tn = VikRentItems::getTranslator();
		$pelem = VikRequest::getInt('elem', '', 'request');
		$pkey = VikRequest::getString('key', '', 'request');
		$nowdf = VikRentItems::getDateFormat();
		if ($nowdf=="%d/%m/%Y") {
			$df='d/m/Y';
		} elseif ($nowdf=="%m/%d/%Y") {
			$df='m/d/Y';
		} else {
			$df='Y/m/d';
		}
		$nowtf = VikRentItems::getTimeFormat();
		$icsname = date('Y-m-d_H_i_s');
		$icscontent = "BEGIN:VCALENDAR\n";
		$icscontent .= "VERSION:2.0\n";
		$icscontent .= "PRODID:-//e4j//VikRentItems//EN\n";
		$icscontent .= "CALSCALE:GREGORIAN\n";
		$icscontent .= "X-WR-TIMEZONE:".date_default_timezone_get()."\n";
		if (!empty($pkey) && $pkey == VikRentItems::getIcalSecretKey()) {
			$icsname .= '_'.($pelem > 0 ? $pelem.'_' : '').$pkey;
			$q = "SELECT `o`.*,`oi`.`iditem`,`oi`.`itemquant`,`lp`.`name` AS `pickup_location_name`,`ld`.`name` AS `dropoff_location_name` FROM `#__vikrentitems_orders` AS `o` ".
				"LEFT JOIN `#__vikrentitems_ordersitems` `oi` ON `o`.`id`=`oi`.`idorder` ".
				"LEFT JOIN `#__vikrentitems_places` `lp` ON `o`.`idplace`=`lp`.`id` ".
				"LEFT JOIN `#__vikrentitems_places` `ld` ON `o`.`idreturnplace`=`ld`.`id` WHERE `o`.`status`='confirmed' AND `o`.`ritiro` > ".time().($pelem > 0 ? " AND `oi`.`iditem`=".$pelem : "")." ORDER BY `o`.`ritiro` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$rows = $dbo->loadAssocList();
				$icalstr = "";
				foreach ($rows as $r) {
					$uri = VikRentItems::externalroute('index.php?option=com_vikrentitems&view=order&sid=' . $r['sid'] . '&ts=' . $r['ts'], false);
					$pickloc = $r['pickup_location_name'];
					$item = VikRentItems::getItemInfo($r['iditem'], $vri_tn);
					//$custdata = preg_replace('/\s+/', ' ', trim($r['custdata']));
					$description = "x".$r['itemquant']." ".$item['name']."\\n".date($df.' '.$tf, $r['ritiro']).' - '.date($df.' '.$tf, $r['consegna']).str_replace("\n", "\\n", trim($r['custdata']))."\\n\\nID ".$r['id'];
					$icalstr .= "BEGIN:VEVENT\n";
					//End of the Event set as Pickup Date, decomment line below to have it on Drop Off Date
					//$icalstr .= "DTEND:".date('Ymd\THis\Z', $r['consegna'])."\n";
					$icalstr .= "DTEND;TZID=".date_default_timezone_get().":".date('Ymd\THis', $r['ritiro'])."\n";
					//
					$icalstr .= "UID:".$r['id'].'_'.$r['sid'].'_'.$r['iditem']."\n";
					$icalstr .= "DTSTAMP:".date('Ymd\THis\Z')."\n";
					if (!empty($pickloc)) {
						$icalstr .= "LOCATION:".preg_replace('/([\,;])/','\\\$1', $pickloc)."\n";
					}
					$icalstr .= ((strlen($description) > 0 ) ? "DESCRIPTION:".preg_replace('/([\,;])/','\\\$1', $description)."\n" : "");
					$icalstr .= "URL;VALUE=URI:".preg_replace('/([\,;])/','\\\$1', $uri)."\n";
					$icalstr .= "SUMMARY:".JText::sprintf('VRIICSEXPSUMMARY', $item['name'], date($df.' '.$tf, $r['ritiro']))."\n";
					$icalstr .= "DTSTART;TZID=".date_default_timezone_get().":".date('Ymd\THis', $r['ritiro'])."\n";
					$icalstr .= "END:VEVENT\n";
				}
				$icscontent .= $icalstr;
			}
		}
		$icscontent .= "END:VCALENDAR\n";
		header('Content-type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $icsname.'.ics');
		echo $icscontent;

		exit;
	}

	/**
	 * @wponly License Hash Ping for upgrading to Pro.
	 *
	 * This method only ensures that the Plugin is installed,
	 * no matter which version is in use (Lite/Pro).
	 * Forcing the hash to be valid is useless.
	 */
	function licenseping() {
		// get hash values
		VikRentItemsLoader::import('update.license');
		$hash = VikRentItemsLicense::getHash();
		$rq_hash = VikRequest::getString('hash', '');
		
		// validate hash
		if (!empty($rq_hash) && $rq_hash == $hash) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
			echo '1';
			exit;
		}

		// hash mismatch
		header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
		echo 'Hash Mismatch ['.$rq_hash.']';
		exit;
	}
	
}
