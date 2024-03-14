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

class VikRentItemsViewRatesoverv extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();

		$cid = VikRequest::getVar('cid', array(0));
		$sesscids = $session->get('vriRatesOviewCids', array());
		if (empty($cid[0]) && is_array($sesscids) && count($sesscids)) {
			// load items from session only if no item IDs requested
			$cid = $sesscids;
		}

		// first item ID
		$item_id = (int)$cid[0];

		if (empty($item_id)) {
			$q = "SELECT `id` FROM `#__vikrentitems_items` ORDER BY `name` ASC LIMIT 1";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$item_id = $dbo->loadResult();
			}
		}
		if (empty($item_id)) {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
			exit;
		}
		// make sure to set at least the first index of cid[]
		$cid[0] = $item_id;
		//

		$q = "SELECT `id`,`name` FROM `#__vikrentitems_items` ORDER BY `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$all_items = $dbo->loadAssocList();
		// load item rows for all requested items
		$itemrows = array();
		$reqids = array();
		foreach ($cid as $rid) {
			if (empty($rid)) {
				continue;
			}
			array_push($reqids, (int)$rid);
		}
		$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id` IN (".implode(', ', $reqids).") ORDER BY `name`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			foreach ($rows as $row) {
				$itemrows[$row['id']] = $row;
			}
		}
		if (!(count($itemrows) > 0)) {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
			exit;
		}
		// get all requested and valid item IDs
		$req_item_ids = array_keys($itemrows);
		$session->set('vriRatesOviewCids', $req_item_ids);

		$pdays_cal = VikRequest::getVar('days_cal', array());
		$pdays_cal = VikRentItems::filterNightsSeasonsCal($pdays_cal);
		$item_days_cal = explode(',', VikRentItems::getSeasoncalNights());
		$item_days_cal = VikRentItems::filterNightsSeasonsCal($item_days_cal);
		$seasons_cal = array();
		$seasons_cal_days = array();
		if (count($pdays_cal) > 0) {
			$seasons_cal_days = $pdays_cal;
		} elseif (count($item_days_cal) > 0) {
			$seasons_cal_days = $item_days_cal;
		} else {
			$q = "SELECT `days` FROM `#__vikrentitems_dispcost` WHERE `iditem`=".intval($item_id)." ORDER BY `#__vikrentitems_dispcost`.`days` ASC LIMIT 7;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$nights_vals = $dbo->loadAssocList();
				$nights_got = array();
				foreach ($nights_vals as $night) {
					$nights_got[] = $night['days'];
				}
				$seasons_cal_days = VikRentItems::filterNightsSeasonsCal($nights_got);
			}
		}
		if (count($req_item_ids) > 1) {
			// it's useless to spend server resources to calculate the seasons calendar nights (LOS Pricing Overview) since it won't be displayed when more than 1 item
			$seasons_cal_days = array();
		}
		if (count($seasons_cal_days) > 0) {
			$q = "SELECT `p`.*,`tp`.`name`,`tp`.`attr`,`tp`.`idiva` FROM `#__vikrentitems_dispcost` AS `p` LEFT JOIN `#__vikrentitems_prices` `tp` ON `p`.`idprice`=`tp`.`id` WHERE `p`.`days` IN (".implode(',', $seasons_cal_days).") AND `p`.`iditem`=".$item_id." ORDER BY `p`.`days` ASC, `p`.`cost` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$tars = $dbo->loadAssocList();
				$arrtar = array();
				foreach ($tars as $tar) {
					$arrtar[$tar['days']][] = $tar;
				}
				$seasons_cal['nights'] = $seasons_cal_days;
				$seasons_cal['offseason'] = $arrtar;
				$q = "SELECT * FROM `#__vikrentitems_seasons` WHERE `iditems` LIKE '%-".$item_id."-%';";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$seasons = $dbo->loadAssocList();
					//Restrictions
					$all_restrictions = VikRentItems::loadRestrictions(true, array($item_id));
					$all_seasons = array();
					$curtime = time();
					foreach ($seasons as $sk => $s) {
						if (empty($s['from']) && empty($s['to'])) {
							continue;
						}
						$now_year = !empty($s['year']) ? $s['year'] : date('Y');
						list($sfrom, $sto) = VikRentItems::getSeasonRangeTs($s['from'], $s['to'], $now_year);
						if ($sto < $curtime && empty($s['year'])) {
							$now_year += 1;
							list($sfrom, $sto) = VikRentItems::getSeasonRangeTs($s['from'], $s['to'], $now_year);
						}
						if ($sto >= $curtime) {
							$s['from_ts'] = $sfrom;
							$s['to_ts'] = $sto;
							$all_seasons[] = $s;
						}
					}
					if (count($all_seasons) > 0) {
						$vri_df = VikRentItems::getDateFormat();
						$vri_df = $vri_df == "%d/%m/%Y" ? 'd/m/Y' : ($vri_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
						$hcheckin = 0;
						$mcheckin = 0;
						$hcheckout = 0;
						$mcheckout = 0;
						$timeopst = VikRentItems::getTimeOpenStore();
						if (is_array($timeopst)) {
							$opent = VikRentItems::getHoursMinutes($timeopst[0]);
							$closet = VikRentItems::getHoursMinutes($timeopst[1]);
							$hcheckin = $opent[0];
							$mcheckin = $opent[1];
							// set default drop off time equal to pick up time to avoid getting extra days of rental
							$hcheckout = $hcheckin;
							$mcheckout = $mcheckin;
						}
						$all_seasons = VikRentItems::sortSeasonsRangeTs($all_seasons);
						$seasons_cal['seasons'] = $all_seasons;
						$seasons_cal['season_prices'] = array();
						$seasons_cal['restrictions'] = array();
						// calc price changes for each season and for each num-night
						foreach ($all_seasons as $sk => $s) {
							$checkin_base_ts = $s['from_ts'];
							$is_dst = date('I', $checkin_base_ts);
							foreach ($arrtar as $numnights => $tar) {
								$checkout_base_ts = $s['to_ts'];
								for($i = 1; $i <= $numnights; $i++) {
									$checkout_base_ts += 86400;
									$is_now_dst = date('I', $checkout_base_ts);
									if ($is_dst != $is_now_dst) {
										if ((int)$is_dst == 1) {
											$checkout_base_ts += 3600;
										} else {
											$checkout_base_ts -= 3600;
										}
										$is_dst = $is_now_dst;
									}
								}
								//calc check-in and check-out ts for the two dates
								$first = VikRentItems::getDateTimestamp(date($vri_df, $checkin_base_ts), $hcheckin, $mcheckin);
								$second = VikRentItems::getDateTimestamp(date($vri_df, $checkout_base_ts), $hcheckout, $mcheckout);
								$tar = VikRentItems::applySeasonsItem($tar, $first, $second, null, $s);
								$seasons_cal['season_prices'][$sk][$numnights] = $tar;
								//Restrictions
								if (count($all_restrictions) > 0) {
									$season_restr = VikRentItems::parseSeasonRestrictions($first, $second, $numnights, $all_restrictions);
									if (count($season_restr) > 0) {
										$seasons_cal['restrictions'][$sk][$numnights] = $season_restr;
									}
								}
							}
						}
					}
				}
			}
		}
		//calendar rates
		$todayd = getdate();
		$tsstart = mktime(0, 0, 0, $todayd['mon'], $todayd['mday'], $todayd['year']);
		$startdate = VikRequest::getString('startdate', '', 'request');
		if (!empty($startdate)) {
			$startts = VikRentItems::getDateTimestamp($startdate, 0, 0);
			if (!empty($startts)) {
				$session->set('vriRatesOviewTs', $startts);
				$tsstart = $startts;
			}
		} else {
			$prevts = $session->get('vriRatesOviewTs', '');
			if (!empty($prevts)) {
				$tsstart = $prevts;
			}
		}
		$itemrates = array();
		// read the rates for the lowest number of nights for each item requested
		foreach ($req_item_ids as $nowcid) {
			$nowitemrates = array();
			/**
			 * Some types of price may not have a cost for 1 or 2 days,
			 * so joining by MIN(`days`) may exclude certain types of price.
			 * We need to manually get via PHP all types of price.
			 * 
			 * @since 	1.13
			 */
			$q = "SELECT `r`.`id`,`r`.`iditem`,`r`.`days`,`r`.`idprice`,`r`.`cost`,`p`.`name` FROM `#__vikrentitems_dispcost` AS `r` LEFT JOIN `#__vikrentitems_prices` `p` ON `p`.`id`=`r`.`idprice` WHERE `r`.`iditem`=".(int)$nowcid." ORDER BY `r`.`days` ASC, `r`.`cost` ASC LIMIT 50;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$nowitemrates = $dbo->loadAssocList();
				$parsed_item_prices = array();
				foreach ($nowitemrates as $rrk => $rrv) {
					if (isset($parsed_item_prices[$rrv['idprice']])) {
						unset($nowitemrates[$rrk]);
						continue;
					}
					$nowitemrates[$rrk]['cost'] = round(($rrv['cost'] / $rrv['days']), 2);
					$nowitemrates[$rrk]['days'] = 1;
					$parsed_item_prices[$rrv['idprice']] = 1;
				}
			}
			$nowitemrates = array_values($nowitemrates);
			// push rates for this item
			$itemrates[(int)$nowcid] = $nowitemrates;
		}
		//

		// read all the orders between these dates for all items
		$booked_dates = array();
		$MAX_DAYS = 60;
		$info_start = getdate($tsstart);
		$endts = mktime(23, 59, 59, $info_start['mon'], ($info_start['mday'] + $MAX_DAYS), $info_start['year']);
		$q = "SELECT `b`.*,`ob`.`idorder`,`o`.`closure` FROM `#__vikrentitems_busy` AS `b` LEFT JOIN `#__vikrentitems_ordersbusy` AS `ob` ON `ob`.`idbusy`=`b`.`id` LEFT JOIN `#__vikrentitems_orders` AS `o` ON `o`.`id`=`ob`.`idorder` WHERE `b`.`iditem` IN (".implode(', ', $reqids).") AND `b`.`id`=`ob`.`idbusy` AND (`b`.`ritiro`>=".$tsstart." OR `b`.`consegna`>=".$tsstart.") AND (`b`.`ritiro`<=".$endts." OR `b`.`consegna`<=".$tsstart.");";
		$dbo->setQuery($q);
		$dbo->execute();
		$rbusy = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
		$cidbusy = array();
		foreach ($rbusy as $rb) {
			if (!isset($cidbusy[$rb['iditem']])) {
				$cidbusy[$rb['iditem']] = array();
			}
			array_push($cidbusy[$rb['iditem']], $rb);
		}
		foreach ($req_item_ids as $nowcid) {
			$booked_dates[(int)$nowcid] = isset($cidbusy[(int)$nowcid]) ? $cidbusy[(int)$nowcid] : "";
		}
		
		$this->all_items = &$all_items;
		$this->itemrows = &$itemrows;
		$this->seasons_cal_days = &$seasons_cal_days;
		$this->seasons_cal = &$seasons_cal;
		$this->tsstart = &$tsstart;
		$this->itemrates = &$itemrates;
		$this->booked_dates = &$booked_dates;
		$this->req_item_ids = &$req_item_ids;
		$this->firstitem = &$item_id;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRIMAINRATESOVERVIEWTITLE'), 'vikrentitems');
		// @wponly lite - no special prices or restrictions supported
		JToolBarHelper::cancel( 'cancel', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
