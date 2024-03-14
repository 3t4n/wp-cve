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

$row = $this->row;
$items = $this->items;
$busy = $this->busy;
$customer = $this->customer;
$payments = $this->payments;

$currencyname = VikRentItems::getCurrencyName();
$dbo = JFactory::getDBO();
$vri_app = VikRentItems::getVriApplication();
$nowdf = VikRentItems::getDateFormat(true);
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
$nowtf = VikRentItems::getTimeFormat(true);
$payment = VikRentItems::getPayment($row['idpayment']);
$pactive_tab = VikRequest::getString('vri_active_tab', 'vri-tab-details', 'request');

$totdelivery = $row['deliverycost'];
$deliverycalcunit = VikRentItems::getDeliveryCalcUnit(true);
$checkhourscharges = 0;
$hoursdiff = 0;
$ppickup = $row['ritiro'];
$prelease = $row['consegna'];
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
$vricart = array();
$isdue = 0;
$tot_items = 0;
$hours_rented = 0;
$is_cust_cost = false;
foreach ($items as $koi => $oi) {
	$tot_items += $oi['itemquant'];
	$tar = array();
	if (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0.00) {
		// custom cost set from the back-end
		$is_cust_cost = true;
		$tar = array(array(
			'id' => -1,
			'iditem' => $oi['iditem'],
			'days' => $row['days'],
			'idprice' => -1,
			'cost' => $oi['cust_cost'],
			'attrdata' => '',
		));
	} else {
		if ($row['hourly'] == 1) {
			$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `id`=".(int)$oi['idtar'].";";
		} else {
			$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=".(int)$oi['idtar'].";";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$tar = $dbo->loadAssocList();
		} elseif ($row['hourly'] == 1) {
			//one of the items chosen does not have hourly prices
			$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=".(int)$oi['idtar'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			$tar = $dbo->loadAssocList();
		}
		if ($row['hourly'] == 1) {
			foreach ($tar as $kt => $vt) {
				$tar[$kt]['days'] = 1;
				$hours_rented = $vt['hours'] > 0 ? $vt['hours'] : $hours_rented;
			}
		}
		if ($checkhourscharges > 0 && $aehourschbasp == true) {
			$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false, true, true);
			$tar = $ret['return'];
			$calcdays = $ret['days'];
		}
		if ($checkhourscharges > 0 && $aehourschbasp == false) {
			$tar = VikRentItems::extraHoursSetPreviousFareItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true);
			$tar = VikRentItems::applySeasonsItem($tar, $row['ritiro'], $row['consegna'], $row['idplace']);
			$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true, true, true);
			$tar = $ret['return'];
			$calcdays = $ret['days'];
		} else {
			$tar = VikRentItems::applySeasonsItem($tar, $row['ritiro'], $row['consegna'], $row['idplace']);
		}
	}
	//
	$tar = VikRentItems::applyItemDiscounts($tar, $oi['iditem'], $oi['itemquant']);
	$isdue += count($tar) ? VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $row) : 0;
	if (!isset($vricart[$oi['iditem']])) {
		$vricart[$oi['iditem']] = array();
	}
	if (!isset($vricart[$oi['iditem']][$koi])) {
		$vricart[$oi['iditem']][$koi] = array();
	}
	$vricart[$oi['iditem']][$koi]['itemquant'] = $oi['itemquant'];
	$vricart[$oi['iditem']][$koi]['info'] = VikRentItems::getItemInfo($oi['iditem']);
	$vricart[$oi['iditem']][$koi]['tar'] = count($tar) ? $tar[0] : array();
}
$today_lim = mktime(0, 0, 0, date('n'), date('j'), date('Y'));

if ($row['status'] == "confirmed") {
	$saystaus = '<span class="label label-success">'.JText::translate('VRCONFIRMED').'</span>';
} elseif ($row['status']=="standby") {
	$saystaus = '<span class="label label-warning">'.JText::translate('VRSTANDBY').'</span>';
} else {
	$saystaus = '<span class="label label-error" style="background-color: #d9534f;">'.JText::translate('VRCANCELLED').'</span>';
}
?>
<script type="text/javascript">
function vriToggleLog(elem) {
	var logdiv = document.getElementById('vripaymentlogdiv').style.display;
	if (logdiv == 'block') {
		document.getElementById('vripaymentlogdiv').style.display = 'none';
		jQuery(elem).parent(".vri-bookingdet-noteslogs-btn").removeClass("vri-bookingdet-noteslogs-btn-active");
	} else {
		jQuery(".vri-bookingdet-noteslogs-btn-active").removeClass("vri-bookingdet-noteslogs-btn-active");
		document.getElementById('vriadminnotesdiv').style.display = 'none';
		document.getElementById('vripaymentlogdiv').style.display = 'block';
		jQuery(elem).parent(".vri-bookingdet-noteslogs-btn").addClass("vri-bookingdet-noteslogs-btn-active");
	}
}
function changePayment() {
	var newpayment = document.getElementById('newpayment').value;
	if (newpayment != '') {
		var paymentname = document.getElementById('newpayment').options[document.getElementById('newpayment').selectedIndex].text;
		if (confirm('<?php echo addslashes(JText::translate('VRCHANGEPAYCONFIRM')); ?>' + paymentname + '?')) {
			document.adminForm.submit();
		} else {
			document.getElementById('newpayment').selectedIndex = 0;
		}
	}
}
function vriToggleNotes(elem) {
	var notesdiv = document.getElementById('vriadminnotesdiv').style.display;
	if (notesdiv == 'block') {
		document.getElementById('vriadminnotesdiv').style.display = 'none';
		jQuery(elem).parent(".vri-bookingdet-noteslogs-btn").removeClass("vri-bookingdet-noteslogs-btn-active");
	} else {
		jQuery(".vri-bookingdet-noteslogs-btn-active").removeClass("vri-bookingdet-noteslogs-btn-active");
		if (document.getElementById('vripaymentlogdiv')) {
			document.getElementById('vripaymentlogdiv').style.display = 'none';
		}
		document.getElementById('vriadminnotesdiv').style.display = 'block';
		jQuery(elem).parent(".vri-bookingdet-noteslogs-btn").addClass("vri-bookingdet-noteslogs-btn-active");
	}
}
function toggleDiscount(elem) {
	var discsp = document.getElementById('vridiscenter').style.display;
	if (discsp == 'block') {
		document.getElementById('vridiscenter').style.display = 'none';
		jQuery(elem).find('i').removeClass("fa-chevron-up").addClass("fa-chevron-down");
	} else {
		document.getElementById('vridiscenter').style.display = 'block';
		jQuery(elem).find('i').removeClass("fa-chevron-down").addClass("fa-chevron-up");
	}
}
</script>

<div class="vri-bookingdet-topcontainer">
	<form name="adminForm" id="adminForm" action="index.php" method="post">	
		<div class="vri-bookdet-container">
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span>ID</span>
				</div>
				<div class="vri-bookdet-foot">
					<span><?php echo $row['id']; ?></span>
				</div>
			</div>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERONE'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<span><?php echo date($df.' '.$nowtf, $row['ts']); ?></span>
				</div>
			</div>
		<?php
		if (count($customer)) {
		?>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VRCUSTOMERNOMINATIVE'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<!-- @wponly lite - customer editing not supported -->
					<?php echo (isset($customer['country_img']) ? $customer['country_img'].' ' : '').'<span>'.ltrim($customer['first_name'].' '.$customer['last_name']).'</span>'; ?>
				</div>
			</div>
		<?php
		}
		?>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERITEMSNUM'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<?php echo $tot_items; ?>
				</div>
			</div>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERFOUR'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<?php echo ($row['hourly'] == 1 && $hours_rented > 0 ? $hours_rented.' '.JText::translate('VRIHOURS') : $row['days']); ?>
				</div>
			</div>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERFIVE'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
				<?php
				$ritiro_info = getdate($row['ritiro']);
				$short_wday = JText::translate('VR'.strtoupper(substr($ritiro_info['weekday'], 0, 3)));
				?>
					<?php echo $short_wday.', '.date($df.' '.$nowtf, $row['ritiro']); ?>
				</div>
			</div>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERSIX'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
				<?php
				$consegna_info = getdate($row['consegna']);
				$short_wday = JText::translate('VR'.strtoupper(substr($consegna_info['weekday'], 0, 3)));
				?>
					<?php echo $short_wday.', '.date($df.' '.$nowtf, $row['consegna']); ?>
				</div>
			</div>
			<?php
		if (!empty($row['idplace'])) {
			$pickup_place = VikRentItems::getPlaceName($row['idplace']);
			?>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VRRITIROITEM'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<?php echo $pickup_place; ?>
				</div>
			</div>
			<?php
		}
		if (!empty($row['idreturnplace'])) {
			$dropoff_place = VikRentItems::getPlaceName($row['idreturnplace']);
			?>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VRRETURNITEMORD'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<?php echo $dropoff_place; ?>
				</div>
			</div>
			<?php
		}
		?>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VRSTATUS'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<span><?php echo $saystaus; ?></span>
				</div>
			</div>
		</div>

		<div class="vri-bookingdet-innertop">
			<div class="vri-bookingdet-commands">
			<?php
			if (is_array($busy) || $row['status'] == "standby") {
			/**
			 * @wponly lite - edit reservation not supported (alert message displayed)
			 */
				?>
				<div class="vri-bookingdet-command">
					<button onclick="alert('This function is only available with the Pro version.');" class="btn btn-secondary" type="button"><i class="icon-pencil"></i> <?php echo JText::translate('VRMODRES'); ?></button>
				</div>
				<?php
			}
			if ((is_array($items) && !empty($items[0]['idtar'])) || $is_cust_cost) {
				/**
				 * @wponly 	Rewrite order view URI
				 */
				$model 		= JModel::getInstance('vikrentitems', 'shortcodes');
				$itemid 	= $model->best('order');
				$order_uri 	= '';
				if ($itemid) {
					$order_uri = JRoute::rewrite("index.php?option=com_vikrentitems&Itemid={$itemid}&view=order&sid={$row['sid']}&ts={$row['ts']}");
				} else {
					VikError::raiseWarning('', 'No Shortcodes of type Order Details found, or no Shortcodes of this type are being used in Pages/Posts.');
				}
				?>
				<div class="vri-bookingdet-command">
					<button onclick="window.open('<?php echo $order_uri; ?>', '_blank');" type="button" class="btn btn-secondary"><i class="icon-eye"></i> <?php echo JText::translate('VRIVIEWORDFRONT'); ?></button>
				</div>
				<?php
			}
			if (($row['status'] == "confirmed" || ($row['status'] == "standby" && !empty($row['custmail']))) && ((is_array($items) && !empty($items[0]['idtar'])) || ($is_cust_cost))) {
				?>
				<div class="vri-bookingdet-command">
					<button class="btn btn-success" type="button" onclick="document.location.href='index.php?option=com_vikrentitems&task=resendordemail&cid[]=<?php echo $row['id']; ?>';"><i class="icon-mail"></i> <?php echo JText::translate('VRIRESENDORDEMAIL'); ?></button>
				</div>
				<?php
				if ($row['status'] == "confirmed") {
				?>
				<div class="vri-bookingdet-command">
					<button class="btn btn-success" type="button" onclick="document.location.href='index.php?option=com_vikrentitems&task=resendordemail&sendpdf=1&cid[]=<?php echo $row['id']; ?>';"><i class="icon-mail"></i> <?php echo JText::translate('VRIRESENDORDEMAILANDPDF'); ?></button>
				</div>
				<?php
				}
			}
			if ($row['status'] == "standby") {
				?>
				<div class="vri-bookingdet-command">
					<button class="btn btn-success" type="button" onclick="if (confirm('<?php echo addslashes(JText::translate('VRSETORDCONFIRMED')); ?> ?')) {document.location.href='index.php?option=com_vikrentitems&task=setordconfirmed&cid[]=<?php echo $row['id']; ?>';}"><i class="vriicn-checkmark"></i> <?php echo JText::translate('VRSETORDCONFIRMED'); ?></button>
				</div>
				<?php
			}
			if ($row['status'] != 'confirmed' || $row['closure'] > 0) {
				?>
				<div class="vri-bookingdet-command">
					<button type="button" class="btn btn-danger" onclick="if (confirm('<?php echo addslashes(JText::translate('VRIDELCONFIRM')); ?>')){document.location.href='index.php?option=com_vikrentitems&task=removeorders&cid[]=<?php echo $row['id']; ?>';}"><i class="vriicn-bin"></i> <?php echo JText::translate('VRMAINEBUSYDEL'); ?></button>
				</div>
				<?php
			}
			?>
			</div>

			<div class="vri-bookingdet-tabs">
				<div class="vri-bookingdet-tab vri-bookingdet-tab-active" data-vritab="vri-tab-details"><?php echo JText::translate('VRIBOOKDETTABDETAILS'); ?></div>
				<div class="vri-bookingdet-tab" data-vritab="vri-tab-admin"><?php echo JText::translate('VRIBOOKDETTABADMIN'); ?></div>
			</div>
		</div>

		<div class="vri-bookingdet-tab-cont" id="vri-tab-details" style="display: block;">
			<div class="vri-bookingdet-innercontainer">
				<div class="vri-bookingdet-customer">
					<div class="vri-bookingdet-detcont<?php echo $row['closure'] > 0 ? ' vri-bookingdet-closure' : ''; ?>">
					<?php
					$custdata_parts = explode("\n", $row['custdata']);
					if (count($custdata_parts) > 2 && strpos($custdata_parts[0], ':') !== false && strpos($custdata_parts[1], ':') !== false) {
						//attempt to format labels and values
						foreach ($custdata_parts as $custdet) {
							if (strlen($custdet) < 1) {
								continue;
							}
							$custdet_parts = explode(':', $custdet);
							$custd_lbl = '';
							$custd_val = '';
							if (count($custdet_parts) < 2) {
								$custd_val = $custdet;
							} else {
								$custd_lbl = $custdet_parts[0];
								unset($custdet_parts[0]);
								$custd_val = trim(implode(':', $custdet_parts));
							}
							?>
						<div class="vri-bookingdet-userdetail">
							<?php
							if (strlen($custd_lbl)) {
								?>
							<span class="vri-bookingdet-userdetail-lbl"><?php echo $custd_lbl; ?></span>
								<?php
							}
							if (strlen($custd_val)) {
								?>
							<span class="vri-bookingdet-userdetail-val"><?php echo $custd_val; ?></span>
								<?php
							}
							?>
						</div>
							<?php
						}
					} else {
						if ($row['closure'] > 0) {
							?>
						<div class="vri-bookingdet-userdetail">
							<span class="vri-bookingdet-userdetail-val"><?php echo nl2br($row['custdata']); ?></span>
						</div>
							<?php
						} else {
							echo nl2br($row['custdata']);
							?>
						<div class="vri-bookingdet-userdetail">
							<span class="vri-bookingdet-userdetail-val">&nbsp;</span>
						</div>
							<?php
						}
					}
					if (!empty($row['ujid'])) {
						$orig_user = JFactory::getUser($row['ujid']);
						$author_name = is_object($orig_user) && property_exists($orig_user, 'name') && !empty($orig_user->name) ? $orig_user->name : '';
						?>
						<div class="vri-bookingdet-userdetail">
							<span class="vri-bookingdet-userdetail-val"><?php echo JText::sprintf('VRIBOOKINGCREATEDBY', $row['ujid'].(!empty($author_name) ? ' ('.$author_name.')' : '')); ?></span>
						</div>
						<?php
					}
					?>
					</div>
				<?php
				$contracted = file_exists(VRI_SITE_PATH.DS.'resources'.DS.'pdfs'.DS.$row['id'].'_'.$row['ts'].'.pdf');
				if ($contracted) {
					?>
					<div class="vri-bookingdet-detcont vri-hidein-print">
					<?php
					if ($row['status'] == "confirmed") {
						?>
						<div>
							<span class="label label-success"><?php echo JText::translate('VRICONFIRMATIONNUMBER'); ?> <span class="badge"><?php echo $row['sid'].'_'.$row['ts']; ?></span></span>
						</div>
						<?php
					}
					?>
						<div>
							<span class="label label-success"><span class="badge"><a href="<?php echo VRI_SITE_URI; ?>resources/pdfs/<?php echo $row['id'].'_'.$row['ts']; ?>.pdf" target="_blank"><i class="vriicn-file-text2"></i><?php echo JText::translate('VRIDOWNLOADPDF'); ?></a></span></span>
						</div>
					</div>
					<?php
				}
				if ($row['closure'] < 1) {
				?>
					<div class="vri-bookingdet-detcont vri-hidein-print">
						<label for="custmail"><?php echo JText::translate('VRQRCUSTMAIL'); ?></label>
						<input type="text" name="custmail" id="custmail" value="<?php echo $row['custmail']; ?>" size="25"/>
						<?php if (!empty($row['custmail'])) : ?> <button type="button" class="btn vri-config-btn" onclick="vriToggleSendEmail();" style="vertical-align: top;"><i class="vriicn-envelop"></i><?php echo JText::translate('VRSENDEMAILACTION'); ?></button><?php endif; ?>
					</div>
				<?php
				}
				?>
				</div>

				<?php
				$isdue = 0;
				$all_id_prices = array();
				$used_indexes_map = array();
				?>
				<div class="vri-bookingdet-summary">
					<div class="table-responsive">
						<table class="table">
						<?php
						foreach ($items as $koi => $oi) {
							$num = $koi + 1;
							if ((!empty($oi['cust_cost']) && $oi['cust_cost'] > 0.00)) {
								// cust_cost should always be inclusive of taxes
								$item_cost = $oi['cust_cost'];
								$isdue += $item_cost;
							} else {
								$item_cost = isset($vricart[$oi['iditem']][$koi]['tar']['idprice']) ? VikRentItems::sayCostPlusIva($vricart[$oi['iditem']][$koi]['tar']['cost'] * $vricart[$oi['iditem']][$koi]['itemquant'], $vricart[$oi['iditem']][$koi]['tar']['idprice'], $row) : 0;
								$isdue += $item_cost;
							}
							?>
							<tr class="vri-bookingdet-summary-item">
								<td class="vri-bookingdet-summary-item-firstcell">
									<div class="vri-bookingdet-summary-itemnum"><i class="vriicn-stack"></i> <?php echo JText::translate('VREDITORDERTHREE').' '.$num; ?></div>
									<?php
									if (!empty($pickup_place) && !empty($dropoff_place)) {
									?>
									<div class="vri-bookingdet-summary-locations">
										<i class="fa fa-location-arrow"></i>
										<span><?php echo $pickup_place; ?></span>
										<?php
										if ($dropoff_place != $pickup_place) {
										?>
										<span class="vri-bookingdet-location-divider">-&gt;</span>
										<span><?php echo $dropoff_place; ?></span>
										<?php
										}
										?>
									</div>
									<?php
									}
									if (!empty($row['nominative'])) {
									?>
									<div class="vri-bookingdet-summary-guestname">
										<span><?php echo $row['nominative']; ?></span>
									</div>
									<?php
									}
									?>
								</td>
								<td>
									<div class="vri-bookingdet-summary-itemname"><?php echo $oi['name'].($oi['itemquant'] > 1 ? " x".$oi['itemquant'] : ""); ?></div>
									<div class="vri-bookingdet-summary-itemrate">
									<?php
									$is_cust_cost = (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0.00);
									if ((!empty($oi['cust_cost']) && $oi['cust_cost'] > 0.00)) {
										echo JText::translate('VRIRENTCUSTRATEPLAN');
									} elseif (isset($vricart[$oi['iditem']][$koi]['tar']['idprice']) && !empty($vricart[$oi['iditem']][$koi]['tar']['idprice'])) {
										$all_id_prices[] = $vricart[$oi['iditem']][$koi]['tar']['idprice'];
										echo VikRentItems::getPriceName($vricart[$oi['iditem']][$koi]['tar']['idprice']);
										if (!empty($vricart[$oi['iditem']][$koi]['tar']['attrdata'])) {
											?>
										<div>
											<?php echo VikRentItems::getPriceAttr($vricart[$oi['iditem']][$koi]['tar']['idprice']).": ".$vricart[$oi['iditem']][$koi]['tar']['attrdata']; ?>
										</div>
											<?php
										}
									}
									?>
									</div>
								</td>
								<td>
									<div class="vri-bookingdet-summary-price">
									<?php
									if ((isset($vricart[$oi['iditem']][$koi]['tar']['idprice']) && !empty($vricart[$oi['iditem']][$koi]['tar']['idprice'])) || $is_cust_cost) {
										echo $currencyname . ' ' . VikRentItems::numberFormat($item_cost);
									} else {
										echo $currencyname . ' -----';
									}
									?>
									</div>
								</td>
							</tr>
							<?php
							//Options
							if (!empty($oi['optionals'])) {
								$stepo = explode(";", $oi['optionals']);
								$counter = 0;
								foreach ($stepo as $oo) {
									if (empty($oo)) {
										continue;
									}
									$stept=explode(":", $oo);
									$q = "SELECT * FROM `#__vikrentitems_optionals` WHERE `id`='".$stept[0]."';";
									$dbo->setQuery($q);
									$dbo->execute();
									if ($dbo->getNumRows() != 1) {
										continue;
									}
									$counter++;
									$actopt = $dbo->loadAssocList();
									$specvar = '';
									if (!empty($actopt[0]['specifications']) && strstr($stept[1], '-') != false) {
										$optspeccosts = VikRentItems::getOptionSpecIntervalsCosts($actopt[0]['specifications']);
										$optspecnames = VikRentItems::getOptionSpecIntervalsNames($actopt[0]['specifications']);
										$specstept = explode('-', $stept[1]);
										$stept[1] = $specstept[0];
										$specvar = $specstept[1];
										$actopt[0]['specintv'] = $specvar;
										$actopt[0]['name'] .= ': '.$optspecnames[($specvar - 1)];
										$actopt[0]['quan'] = $stept[1];
										$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optspeccosts[($specvar - 1)]) * $row['days'] * $stept[1]) : (floatval($optspeccosts[($specvar - 1)]) * $stept[1]));
									} else {
										$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $row['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
									}
									if ($actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
										$realcost = $actopt[0]['maxprice'];
										if (intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
											$realcost = $actopt[0]['maxprice'] * $stept[1];
										}
									}
									$opt_item_units = $actopt[0]['onceperitem'] ? 1 : $vricart[$oi['iditem']][$koi]['itemquant'];
									$tmpopr = VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $row);
									$isdue += $tmpopr;
									?>
							<tr class="vri-bookingdet-summary-options">
								<td class="vri-bookingdet-summary-options-title"><?php echo $counter == 1 ? JText::translate('VREDITORDEREIGHT') : '&nbsp;'; ?></td>
								<td>
									<span class="vri-bookingdet-summary-lbl"><?php echo ($stept[1] > 1 ? $stept[1]." " : "").$actopt[0]['name']; ?></span>
								</td>
								<td>
									<span class="vri-bookingdet-summary-cost"><?php echo $currencyname." ".VikRentItems::numberFormat($tmpopr); ?></span>
								</td>
							</tr>
								<?php
								}
							}
							// Delivery service
							if (!empty($oi['deliveryaddr'])) {
								?>
							<tr class="vri-bookingdet-summary-custcosts">
								<td class="vri-bookingdet-summary-custcosts-title"><?php echo JText::translate('VRIMAILDELIVERYTO'); ?></td>
								<td>
									<span class="vri-bookingdet-summary-lbl"><?php echo $oi['deliveryaddr']; ?></span>
								</td>
								<td>
									<span class="vri-bookingdet-summary-cost"><?php echo $oi['deliverydist'].' '.$deliverycalcunit; ?></span>
								</td>
							</tr>
								<?php
							}
							//Custom extra costs
							if (!empty($oi['extracosts'])) {
								$counter = 0;
								$cur_extra_costs = json_decode($oi['extracosts'], true);
								foreach ($cur_extra_costs as $eck => $ecv) {
									$counter++;
									?>
							<tr class="vri-bookingdet-summary-custcosts">
								<td class="vri-bookingdet-summary-custcosts-title"><?php echo $counter == 1 ? JText::translate('VRPEDITBUSYEXTRACOSTS') : '&nbsp;'; ?></td>
								<td>
									<span class="vri-bookingdet-summary-lbl"><?php echo $ecv['name']; ?></span>
								</td>
								<td>
									<span class="vri-bookingdet-summary-cost"><?php echo $currencyname." ".VikRentItems::numberFormat(VikRentItems::sayOptionalsPlusIva($ecv['cost'], $ecv['idtax'])); ?></span>
								</td>
							</tr>
									<?php
								}
							}
						}
						// Location fees
						if (!empty($row['idplace']) && !empty($row['idreturnplace'])) {
							$locfee = VikRentItems::getLocFee($row['idplace'], $row['idreturnplace']);
							if ($locfee) {
								//Location fees overrides
								if (strlen($locfee['losoverride']) > 0) {
									$arrvaloverrides = array();
									$valovrparts = explode('_', $locfee['losoverride']);
									foreach ($valovrparts as $valovr) {
										if (!empty($valovr)) {
											$ovrinfo = explode(':', $valovr);
											$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
										}
									}
									if (array_key_exists($row['days'], $arrvaloverrides)) {
										$locfee['cost'] = $arrvaloverrides[$row['days']];
									}
								}
								//
								$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $row['days']) : $locfee['cost'];
								$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva'], $row);
								$isdue += $locfeewith;
								?>
							<tr class="vri-bookingdet-summary-custcosts">
								<td class="vri-bookingdet-summary-custcosts-title">&nbsp;</td>
								<td>
									<span class="vri-bookingdet-summary-lbl"><?php echo JText::translate('VREDITORDERTEN'); ?></span>
								</td>
								<td>
									<span class="vri-bookingdet-summary-cost"><?php echo $currencyname." ".VikRentItems::numberFormat($locfeewith); ?></span>
								</td>
							</tr>
								<?php
							}
						}
						//
						// Delivery service
						if (!empty($totdelivery) && $totdelivery > 0) {
							$isdue += $totdelivery;
							?>
							<tr class="vri-bookingdet-summary-custcosts">
								<td class="vri-bookingdet-summary-custcosts-title">&nbsp;</td>
								<td>
									<span class="vri-bookingdet-summary-lbl"><?php echo JText::translate('VRIMAILTOTDELIVERY'); ?></span>
								</td>
								<td>
									<span class="vri-bookingdet-summary-cost"><?php echo $currencyname." ".VikRentItems::numberFormat($totdelivery); ?></span>
								</td>
							</tr>
							<?php
						}
						//vikrentitems 1.1 coupon
						$usedcoupon = false;
						$origisdue = $isdue;
						if (strlen($row['coupon']) > 0) {
							$usedcoupon = true;
							$expcoupon = explode(";", $row['coupon']);
							$isdue = $isdue - $expcoupon[1];
							?>
							<tr class="vri-bookingdet-summary-coupon">
								<td><?php echo JText::translate('VRICOUPON'); ?></td>
								<td>
									<span class="vri-bookingdet-summary-lbl"><?php echo $expcoupon[2]; ?></span>
								</td>
								<td>
									<span class="vri-bookingdet-summary-cost">- <?php echo $currencyname; ?> <?php echo VikRentItems::numberFormat($expcoupon[1]); ?></span>
								</td>
							</tr>
							<?php
						}
						//Order Total
						?>
							<tr class="vri-bookingdet-summary-total">
								<td>
									<span class="vrapplydiscsp" onclick="toggleDiscount(this);">
										<i class="fa fa-chevron-down" title="<?php echo JText::translate('VRIAPPLYDISCOUNT'); ?>"></i>
									</span>
								</td>
								<td>
									<span class="vri-bookingdet-summary-lbl"><?php echo JText::translate('VREDITORDERNINE'); ?></span>

									<div class="vridiscenter" id="vridiscenter" style="display: none;">
										<div class="vridiscenter-entry">
											<span class="vridiscenter-label"><?php echo JText::translate('VRIAPPLYDISCOUNT'); ?>:</span><span class="vridiscenter-value"><?php echo $currencyname; ?> <input type="number" step="any" name="admindisc" value="" size="4"/></span>
										</div>
										<div class="vridiscenter-entrycentered">
											<button type="submit" class="btn btn-success"><?php echo JText::translate('VRIAPPLYDISCOUNTSAVE'); ?></button>
										</div>
									</div>
								</td>
								<td>
									<span class="vri-bookingdet-summary-cost"><?php echo $currencyname; ?> <?php echo VikRentItems::numberFormat($row['order_total']); ?></span>
								</td>
							</tr>
						<?php
						if (!empty($row['totpaid']) && $row['totpaid'] > 0) {
							$diff_to_pay = $row['order_total'] - $row['totpaid'];
							?>
							<tr class="vri-bookingdet-summary-totpaid">
								<td>&nbsp;</td>
								<td><?php echo JText::translate('VRIAMOUNTPAID'); ?></td>
								<td><?php echo $currencyname.' '.VikRentItems::numberFormat($row['totpaid']); ?></td>
							</tr>
							<?php
							if ($diff_to_pay > 1) {
							?>
							<tr class="vri-bookingdet-summary-totpaid vri-bookingdet-summary-totremaining">
								<td>&nbsp;</td>
								<td>
									<div><?php echo JText::translate('VRITOTALREMAINING'); ?></div>
								</td>
								<td><?php echo $currencyname.' '.VikRentItems::numberFormat($diff_to_pay); ?></td>
							</tr>
							<?php
							}
						}
						?>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="vri-bookingdet-tab-cont" id="vri-tab-admin" style="display: none;">
			<div class="vri-bookingdet-innercontainer">
				<div class="vri-bookingdet-admindata">
					<div class="vri-bookingdet-admin-entry">
						<label for="newpayment"><?php echo JText::translate('VRPAYMENTMETHOD'); ?></label>
					<?php
					if (is_array($payment)) {
						?>
						<span><?php echo $payment['name']; ?></span>
						<?php
					}
					$chpayment = '';
					if (is_array($payments)) {
						$chpayment = '<div><select name="newpayment" id="newpayment" onchange="changePayment();"><option value="">'.JText::translate('VRICHANGEPAYLABEL').'</option>';
						// @wponly lite - payment methods not supported
						$chpayment .= '</select></div>';
					}
					echo $chpayment;
					?>
					</div>
				<?php
				$tn = VikRentItems::getTranslator();
				$all_langs = $tn->getLanguagesList();
				if (count($all_langs) > 1) {
				?>
					<div class="vri-bookingdet-admin-entry">
						<label for="newlang"><?php echo JText::translate('VRIBOOKINGLANG'); ?></label>
						<select name="newlang" id="newlang" onchange="document.adminForm.submit();">
						<?php
						foreach ($all_langs as $lk => $lv) {
							?>
							<option value="<?php echo $lk; ?>"<?php echo $row['lang'] == $lk ? ' selected="selected"' : ''; ?>><?php echo isset($lv['nativeName']) ? $lv['nativeName'] : $lv['name']; ?></option>
							<?php
						}
						?>
						</select>
					</div>
				<?php
				}
				?>
				</div>
				<div class="vri-bookingdet-noteslogs">
					<div class="vri-bookingdet-noteslogs-btns">
						<div class="vri-bookingdet-noteslogs-btn vri-bookingdet-noteslogs-btn-active">
							<a href="javascript: void(0);" onclick="javascript: vriToggleNotes(this);"><?php echo JText::translate('VRIADMINNOTESTOGGLE'); ?></a>
						</div>
					<?php
					if (!empty($row['paymentlog'])) {
						?>
						<div class="vri-bookingdet-noteslogs-btn">
							<a href="javascript: void(0);" id="vri-trig-paylogs" onclick="javascript: vriToggleLog(this);"><?php echo JText::translate('VRIPAYMENTLOGTOGGLE'); ?></a>
							<a name="paymentlog" href="javascript: void(0);"></a>
						</div>
						<?php
					}
					?>
					</div>
					<div class="vri-bookingdet-noteslogs-cont">
						<div id="vriadminnotesdiv" style="display: block;">
							<textarea name="adminnotes" class="vriadminnotestarea"><?php echo strip_tags($row['adminnotes']); ?></textarea>
							<br clear="all"/>
							<input class="btn btn-success vri-config-btn" type="submit" name="updadmnotes" value="<?php echo JText::translate('VRIADMINNOTESUPD'); ?>" />
						</div>
					<?php
					if (!empty($row['paymentlog'])) {
						?>
						<div id="vripaymentlogdiv" style="display: none;">
							<pre style="min-height: 100%;"><?php echo htmlspecialchars($row['paymentlog']); ?></pre>
						</div>
						<script type="text/javascript">
						if (window.location.hash == '#paymentlog') {
							vriToggleLog(document.getElementById('vri-trig-paylogs'));
							jQuery(".vri-bookingdet-tab[data-vritab='vri-tab-admin']").trigger('click');
						}
						</script>
						<?php
					}
					?>
					</div>
				</div>
			</div>
		</div>

		<input type="hidden" name="task" value="editorder">
		<input type="hidden" name="vri_active_tab" id="vri_active_tab" value="">
		<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="cid[]" value="<?php echo $row['id']; ?>">
		<input type="hidden" name="option" value="com_vikrentitems">
		<?php
		$tmpl = VikRequest::getVar('tmpl');
		if ($tmpl == 'component') {
			echo '<input type="hidden" name="tmpl" value="component">';
		}
		$pgoto = VikRequest::getString('goto', '', 'request');
		if ($pgoto == 'overv') {
			echo '<input type="hidden" name="goto" value="overv">';
		}
		?>
	</form>
</div>
<div class="vri-info-overlay-block">
	<a class="vri-info-overlay-close" href="javascript: void(0);"></a>
	<div class="vri-info-overlay-content vri-info-overlay-content-sendsms">
		<div id="vri-overlay-email-cont" style="display: none;">
			<h4><?php echo JText::translate('VRSENDEMAILACTION'); ?>: <span id="emailto-lbl"><?php echo $row['custmail']; ?></span></h4>
			<form action="index.php?option=com_vikrentitems" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bid" value="<?php echo $row['id']; ?>" />
			<?php
			$cur_emtpl = array();
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='customemailtpls';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$cur_emtpl = $dbo->loadResult();
				$cur_emtpl = empty($cur_emtpl) ? array() : json_decode($cur_emtpl, true);
				$cur_emtpl = is_array($cur_emtpl) ? $cur_emtpl : array();
			}
			if (count($cur_emtpl) > 0) {
				?>
				<div style="float: right;">
					<select id="emtpl-customemail" onchange="vriLoadEmailTpl(this.value);">
						<option value=""><?php echo JText::translate('VREMAILCUSTFROMTPL'); ?></option>
					<?php
					foreach ($cur_emtpl as $emk => $emv) {
						?>
						<optgroup label="<?php echo $emv['emailsubj']; ?>">
							<option value="<?php echo $emk; ?>"><?php echo JText::translate('VREMAILCUSTFROMTPLUSE'); ?></option>
							<option value="rm<?php echo $emk; ?>"><?php echo JText::translate('VREMAILCUSTFROMTPLRM'); ?></option>
						</optgroup>
						<?php
					}
					?>
					</select>
				</div>
				<?php
			}
			?>
				<div class="vri-calendar-cfield-entry">
					<label for="emailsubj"><?php echo JText::translate('VRSENDEMAILCUSTSUBJ'); ?></label>
					<span><input type="text" name="emailsubj" id="emailsubj" value="" size="30" /></span>
				</div>
				<div class="vri-calendar-cfield-entry">
					<label for="emailcont"><?php echo JText::translate('VRSENDEMAILCUSTCONT'); ?></label>
					<textarea name="emailcont" id="emailcont" style="width: 99%; min-width: 99%; max-width: 99%; height: 120px; margin-bottom: 1px;"></textarea>
					<div class="btn-group pull-left vri-smstpl-bgroup vri-custmail-bgroup">
						<button onclick="setSpecialTplTag('emailcont', '{customer_name}');" class="btn btn-secondary btn-small" type="button">{customer_name}</button>
						<button onclick="setSpecialTplTag('emailcont', '{pickup_date}');" class="btn btn-secondary btn-small" type="button">{pickup_date}</button>
						<button onclick="setSpecialTplTag('emailcont', '{dropoff_date}');" class="btn btn-secondary btn-small" type="button">{dropoff_date}</button>
						<button onclick="setSpecialTplTag('emailcont', '{pickup_place}');" class="btn btn-secondary btn-small" type="button">{pickup_place}</button>
						<button onclick="setSpecialTplTag('emailcont', '{dropoff_place}');" class="btn btn-secondary btn-small" type="button">{dropoff_place}</button>
						<button onclick="setSpecialTplTag('emailcont', '{num_days}');" class="btn btn-secondary btn-small" type="button">{num_days}</button>
						<button onclick="setSpecialTplTag('emailcont', '{items_name}');" class="btn btn-secondary btn-small" type="button">{items_name}</button>
						<button onclick="setSpecialTplTag('emailcont', '{total}');" class="btn btn-secondary btn-small" type="button">{total}</button>
						<button onclick="setSpecialTplTag('emailcont', '{total_paid}');" class="btn btn-secondary btn-small" type="button">{total_paid}</button>
						<button onclick="setSpecialTplTag('emailcont', '{remaining_balance}');" class="btn btn-secondary btn-small" type="button">{remaining_balance}</button>
						<button onclick="setSpecialTplTag('emailcont', '{order_id}');" class="btn btn-secondary btn-small" type="button">{order_id}</button>
					</div>
				</div>
				<div class="vri-calendar-cfield-entry">
					<label for="emailattch"><?php echo JText::translate('VRSENDEMAILCUSTATTCH'); ?></label>
					<span><input type="file" name="emailattch" id="emailattch" /></span>
				</div>
				<div class="vri-calendar-cfield-entry">
					<label for="emailfrom"><?php echo JText::translate('VRSENDEMAILCUSTFROM'); ?></label>
					<span><input type="text" name="emailfrom" id="emailfrom" value="<?php echo VikRentItems::getSenderMail(); ?>" size="30" /></span>
				</div>
				<br clear="all" />
				<div class="vri-calendar-cfields-bottom">
					<button type="submit" class="btn"><i class="vriicn-envelop"></i><?php echo JText::translate('VRSENDEMAILACTION'); ?></button>
				</div>
				<input type="hidden" name="email" id="emailto" value="<?php echo $row['custmail']; ?>" />
				<input type="hidden" name="goto" value="<?php echo urlencode('index.php?option=com_vikrentitems&task=editorder&cid[]='.$row['id']); ?>" />
				<input type="hidden" name="task" value="sendcustomemail" />
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
var vri_overlay_on = false;
if (jQuery.isFunction(jQuery.fn.tooltip)) {
	jQuery(".hasTooltip").tooltip();
}
function vriToggleSendEmail() {
	var cur_email = jQuery("#emailto").val();
	var email_set = jQuery("#custmail").val();
	if (email_set.length && email_set != cur_email) {
		jQuery("#emailto").val(email_set);
		jQuery("#emailto-lbl").text(email_set);
	}
	jQuery("#vri-overlay-email-cont").show();
	jQuery(".vri-info-overlay-block").fadeToggle(400, function() {
		if (jQuery(".vri-info-overlay-block").is(":visible")) {
			vri_overlay_on = true;
		} else {
			vri_overlay_on = false;
		}
	});
}
function setSpecialTplTag(taid, tpltag) {
	var tplobj = document.getElementById(taid);
	if (tplobj != null) {
		var start = tplobj.selectionStart;
		var end = tplobj.selectionEnd;
		tplobj.value = tplobj.value.substring(0, start) + tpltag + tplobj.value.substring(end);
		tplobj.selectionStart = tplobj.selectionEnd = start + tpltag.length;
		tplobj.focus();
	}
}
jQuery(document).ready(function(){
	jQuery(document).mouseup(function(e) {
		if (!vri_overlay_on) {
			return false;
		}
		var vri_overlay_cont = jQuery(".vri-info-overlay-content");
		if (!vri_overlay_cont.is(e.target) && vri_overlay_cont.has(e.target).length === 0) {
			jQuery(".vri-info-overlay-block").fadeOut();
			vri_overlay_on = false;
		}
	});
	jQuery(document).keyup(function(e) {
		if (e.keyCode == 27 && vri_overlay_on) {
			jQuery(".vri-info-overlay-block").fadeOut();
			vri_overlay_on = false;
		}
	});
	jQuery(".vri-bookingdet-tab").click(function() {
		var newtabrel = jQuery(this).attr('data-vritab');
		var oldtabrel = jQuery(".vri-bookingdet-tab-active").attr('data-vritab');
		if (newtabrel == oldtabrel) {
			return;
		}
		jQuery(".vri-bookingdet-tab").removeClass("vri-bookingdet-tab-active");
		jQuery(this).addClass("vri-bookingdet-tab-active");
		jQuery("#"+oldtabrel).hide();
		jQuery("#"+newtabrel).fadeIn();
		jQuery("#vri_active_tab").val(newtabrel);
	});
	jQuery(".vri-bookingdet-tab[data-vritab='<?php echo $pactive_tab; ?>']").trigger('click');
});
var cur_emtpl = <?php echo json_encode($cur_emtpl); ?>;
function vriLoadEmailTpl(tplind) {
	if (!(tplind.length > 0)) {
		jQuery('#emailsubj').val('');
		jQuery('#emailcont').val('');
		return true;
	}
	if (tplind.substr(0, 2) == 'rm') {
		if (confirm('<?php echo addslashes(JText::translate('VRIDELCONFIRM')); ?>')) {
			document.location.href = 'index.php?option=com_vikrentitems&task=rmcustomemailtpl&cid[]=<?php echo $row['id']; ?>&tplind='+tplind.substr(2);
		}
		return false;
	}
	if (!cur_emtpl.hasOwnProperty(tplind)) {
		jQuery('#emailsubj').val('');
		jQuery('#emailcont').val('');
		return true;
	}
	jQuery('#emailsubj').val(cur_emtpl[tplind]['emailsubj']);
	jQuery('#emailcont').val(cur_emtpl[tplind]['emailcont']);
	jQuery('#emailfrom').val(cur_emtpl[tplind]['emailfrom']);
	return true;
}
<?php
$pcustomemail = VikRequest::getInt('customemail', '', 'request');
if ($pcustomemail > 0) {
	?>
	vriToggleSendEmail();
	<?php
}
?>
</script>
