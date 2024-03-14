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

$dbo = JFactory::getDBO();
$ord = $this->ord;
$vricart = $this->vricart;
$payment = $this->payment;
$vri_tn = $this->vri_tn;
//vikrentitems 1.1
$calcdays = $this->calcdays;
if (strlen($calcdays) > 0) {
	$origdays = $ord['days'];
	$ord['days'] = $calcdays;
}
//
$currencysymb = VikRentItems::getCurrencySymb();
$nowdf = VikRentItems::getDateFormat();
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
$nowtf = VikRentItems::getTimeFormat();

$imp = 0;
$isdue = 0;
$itemsnames = array();

foreach ($vricart as $iditem => $itemarrparent) {
	foreach ($itemarrparent as $k => $itemarr) {
		$itemsnames[] = $itemarr['info']['name'];
		$is_cust_cost = (!empty($itemarr['info']['cust_cost']) && $itemarr['info']['cust_cost'] > 0);
		$imp += $is_cust_cost ? VikRentItems::sayCustCostMinusIva($itemarr['tar']['cost'], $itemarr['info']['cust_idiva']) : VikRentItems::sayCostMinusIva($itemarr['tar']['cost'] * $itemarr['itemquant'], $itemarr['tar']['idprice'], $ord);
		$isdue += $is_cust_cost ? $itemarr['tar']['cost'] : VikRentItems::sayCostPlusIva($itemarr['tar']['cost'] * $itemarr['itemquant'], $itemarr['tar']['idprice'], $ord);
		$optbought = array();
		if (!empty($itemarr['optionals'])) {
			$stepo = explode(";", $itemarr['optionals']);
			foreach ($stepo as $one) {
				if (!empty($one)) {
					$stept = explode(":", $one);
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
							$actopt[0]['name'] .= ': '.$optspecnames[($specvar - 1)];
							$actopt[0]['quan'] = $stept[1];
							$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optspeccosts[($specvar - 1)]) * $ord['days'] * $stept[1]) : (floatval($optspeccosts[($specvar - 1)]) * $stept[1]));
						} else {
							$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $ord['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
						}
						if (!empty($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
							$realcost = $actopt[0]['maxprice'];
							if (intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
								$realcost = $actopt[0]['maxprice'] * $stept[1];
							}
						}
						$opt_item_units = $actopt[0]['onceperitem'] ? 1 : $itemarr['itemquant'];
						$imp += VikRentItems::sayOptionalsMinusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $ord);
						$tmpopr = VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $ord);
						$isdue += $tmpopr;
						array_push($optbought, array(
							'name'  => $actopt[0]['name'],
							'units' => $stept[1],
							'cost'  => $tmpopr,
						));
					}
				}
			}
		}
		$vricart[$iditem][$k]['optbought'] = $optbought;
		//custom extra costs
		$vricart[$iditem][$k]['extracosts'] = '';
		if (!empty($itemarr['info']['extracosts'])) {
			$cur_extra_costs = json_decode($itemarr['info']['extracosts'], true);
			foreach ($cur_extra_costs as $eck => $ecv) {
				$ecplustax = !empty($ecv['idtax']) ? VikRentItems::sayOptionalsPlusIva($ecv['cost'], $ecv['idtax'], $ord) : $ecv['cost'];
				$isdue += $ecplustax;
				$imp += !empty($ecv['idtax']) ? VikRentItems::sayOptionalsMinusIva($ecv['cost'], $ecv['idtax'], $ord) : $ecv['cost'];
				$vricart[$iditem][$k]['extracosts'] .= "<div><span class=\"vri-booking-pricename\">".$ecv['name']."</span> <span class=\"vri_currency\">" . $currencysymb . "</span> <span class=\"vri_price\">" . VikRentItems::numberFormat($ecplustax) . "</span></div>";
			}
		}
		//
	}
}
//delivery service
if ($ord['deliverycost'] > 0) {
	$imp += $ord['deliverycost'];
	$isdue += $ord['deliverycost'];
}
//
if (!empty($ord['idplace']) && !empty($ord['idreturnplace'])) {
	$locfee = VikRentItems::getLocFee($ord['idplace'], $ord['idreturnplace']);
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
			if (array_key_exists($ord['days'], $arrvaloverrides)) {
				$locfee['cost'] = $arrvaloverrides[$ord['days']];
			}
		}
		//end VikRentItems 1.1 - Location fees overrides
		$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $ord['days']) : $locfee['cost'];
		$locfeewithout = VikRentItems::sayLocFeeMinusIva($locfeecost, $locfee['idiva'], $ord);
		$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva'], $ord);
		$imp += $locfeewithout;
		$isdue += $locfeewith;
	}
}
$tax = $isdue - $imp;

//vikrentitems 1.1 coupon
$usedcoupon = false;
$origisdue = $isdue;
if (strlen($ord['coupon']) > 0) {
	$usedcoupon = true;
	$expcoupon = explode(";", $ord['coupon']);
	$isdue = $isdue - $expcoupon[1];
}
//

$pitemid 	= VikRequest::getInt('Itemid', '', 'request');
$printer 	= VikRequest::getInt('printer', '', 'request');
/**
 * @wponly  we try to get the best item ID from the Shortcodes configuration
 */
$model 	= JModel::getInstance('vikrentitems', 'shortcodes', 'admin');
$bestitemid = $model->best(array('order'), (!empty($ord['lang']) ? $ord['lang'] : null));

$wdays_map = array(
	JText::translate('VRIJQCALSUN'),
	JText::translate('VRIJQCALMON'),
	JText::translate('VRIJQCALTUE'),
	JText::translate('VRIJQCALWED'),
	JText::translate('VRIJQCALTHU'),
	JText::translate('VRIJQCALFRI'),
	JText::translate('VRIJQCALSAT')
);
$ts_info = getdate($ord['ts']);
$ritiro_info = getdate($ord['ritiro']);
$consegna_info = getdate($ord['consegna']);

if ($ord['status'] == 'confirmed' && $printer != 1) {
?>
<div class="vriprintdiv">
	<a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=order&sid='.$ord['sid'].'&ts='.$ord['ts'].'&printer=1&tmpl=component'.(!empty($bestitemid) ? '&Itemid='.$bestitemid : (!empty($pitemid) ? '&Itemid='.$pitemid : ''))); ?>" target="_blank" title="<?php echo addslashes(JText::translate('VRIPRINTCONFORDER')); ?>">
		<?php VikRentItemsIcons::e('print'); ?>
	</a>
</div>
<?php
}

$main_cont_class = '';
if ($ord['status'] == 'confirmed') {
	$main_cont_class = 'vri-ord-details-head-confirmed';
	?>
<h3 class="vri-ord-details-intro"><?php echo JText::sprintf('VRIYOURORDCONFAT', VikRentItems::getFrontTitle($vri_tn)); ?></h3>
	<?php
} elseif ($ord['status'] == 'cancelled') {
	$main_cont_class = 'vri-ord-details-head-cancelled';
} else {
	$main_cont_class = 'vri-ord-details-head-pending';
}

?>
<div class="vri-ord-top-container">

	<div class="vri-ord-details-head <?php echo $main_cont_class; ?>">
	<?php
	if ($ord['status'] == 'confirmed') {
		?>
		<h4><?php VikRentItemsIcons::e('check-circle'); ?> <?php echo JText::translate('VRIYOURORDISCONF'); ?></h4>
		<?php
	} elseif ($ord['status'] == 'cancelled') {
		?>
		<h4><?php VikRentItemsIcons::e('times-circle'); ?> <?php echo JText::translate('VRIYOURORDISCANC'); ?></h4>
		<?php
	} else {
		?>
		<h4><?php VikRentItemsIcons::e('exclamation-triangle'); ?> <?php echo JText::translate('VRIYOURORDISPEND'); ?></h4>
		<?php
	}
	?>
	</div>

	<div class="vri-ord-mid-container">

		<div class="vri-ord-pickdrop-block">
			<h4 class="vri-medium-header"><?php echo JText::translate('VRIORDERDETAILS'); ?></h4>
		<?php
		if ($ord['status'] == 'confirmed') {
			?>
			<div class="vri-ord-det-cont">
				<span class="vri-ord-det-lbl"><?php echo JText::translate('VRIORDERNUMBER'); ?></span>
				<span class="vri-ord-det-val"><?php echo $ord['id']; ?></span>
			</div>
			<div class="vri-ord-det-cont">
				<span class="vri-ord-det-lbl"><?php echo JText::translate('VRCONFIRMNUMB'); ?></span>
				<span class="vri-ord-det-val"><?php echo $ord['sid'].'-'.$ord['ts']; ?></span>
			</div>
			<?php
		}
		?>
			<div class="vri-ord-det-cont">
				<span class="vri-ord-det-lbl"><?php echo JText::translate('VRORDEREDON'); ?></span>
				<span class="vri-ord-det-val"><?php echo $wdays_map[$ts_info['wday']].', '.date($df.' '.$nowtf, $ord['ts']); ?></span>
			</div>
			<div class="vri-ord-pickleft-block">
				<div class="vri-ord-pickdate-inner">
					<span class="vri-ord-pickupdt-lbl"><?php echo JText::translate('VRPICKUP'); ?></span>
					<span class="vri-ord-pickupdt-val"><?php echo $wdays_map[$ritiro_info['wday']].', '.date($df.' '.$nowtf, $ord['ritiro']); ?></span>
				</div>
				<?php if (!empty($ord['idplace'])) { ?>
				<div class="vri-ord-picklocation-inner">
					<span class="vri-ord-pickuploc-lbl"><?php echo JText::translate('VRRITIROITEM'); ?></span>
					<span class="vri-ord-pickuploc-val"><?php echo VikRentItems::getPlaceName($ord['idplace'], $vri_tn); ?></span>
				</div>
				<?php } ?>
			</div>
			<div class="vri-ord-dropright-block">
				<div class="vri-ord-dropdate-inner">
					<span class="vri-ord-dropoffdt-lbl"><?php echo JText::translate('VRRETURN'); ?></span>
					<span class="vri-ord-dropoffdt-val"><?php echo $wdays_map[$consegna_info['wday']].', '.date($df.' '.$nowtf, $ord['consegna']); ?></span>
				</div>
				<?php if (!empty($ord['idreturnplace'])) { ?>
				<div class="vri-ord-droplocation-inner">
					<span class="vri-ord-dropoffloc-lbl"><?php echo JText::translate('VRRETURNITEMORD'); ?></span>
					<span class="vri-ord-dropoffloc-val"><?php echo VikRentItems::getPlaceName($ord['idreturnplace'], $vri_tn); ?></span>
				</div>
				<?php } ?>
			</div>
		<?php
		// VikRentItems 1.1 Download PDF
		if ($ord['status'] == 'confirmed' && is_file(VRI_SITE_PATH . DS . "resources" . DS . "pdfs" . DS . $ord['id'].'_'.$ord['ts'].'.pdf')) {
			?>
			<div class="vri-ord-det-cont">
				<span class="vri-ord-det-lbl"></span>
				<div class="vri-ord-det-val">
					<div class="vri-ord-downpdf">
						<a href="<?php echo VRI_SITE_URI; ?>resources/pdfs/<?php echo $ord['id'].'_'.$ord['ts']; ?>.pdf" class="btn" target="_blank"><i class="fas fa-file-pdf"></i> <?php echo JText::translate('VRIDOWNLOADPDF'); ?></a>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		</div>

		<div class="vri-ord-udata">
			<h4 class="vri-medium-header"><?php echo JText::translate('VRPERSDETS'); ?></h4>
			<div class="vri-bookingdet-custdata">
			<?php
			$custdata_parts = explode("\n", $ord['custdata']);
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
				echo nl2br($ord['custdata']);
			}
			?>
			</div>
		</div>

	</div>

</div>
		
<div class="vrivordcosts">
<?php
foreach ($vricart as $iditem => $itemarrparent) {
	foreach ($itemarrparent as $k => $itemarr) {
		$is_cust_cost = (!empty($itemarr['info']['cust_cost']) && $itemarr['info']['cust_cost'] > 0);
		?>
	<div class="vri-ord-item-block">
		<div class="vri-ord-item-entry-main">
		<?php
		if (!empty($itemarr['info']['img'])) {
			?>
			<div class="vri-ord-item-entry-img">
				<img src="<?php echo VRI_ADMIN_URI; ?>resources/vthumb_<?php echo $itemarr['info']['img']; ?>" alt="<?php echo htmlspecialchars($itemarr['info']['name']); ?>"/>
			</div>
			<?php
		}
		?>
			<div class="vri-ord-item-entry-main-content">
				<div class="vri-ord-item-entry-name">
					<span><?php echo $itemarr['info']['name'].($itemarr['itemquant'] > 1 ? " x".$itemarr['itemquant'] : ""); ?></span>
				</div>
				<?php
				if (is_array($itemarr['tar'])) {
					?>
				<div class="vri-ord-item-entry-tariff">
					<span><?php echo $is_cust_cost ? JText::translate('VRIRENTCUSTRATEPLAN') : VikRentItems::getPriceName($itemarr['tar']['idprice'], $vri_tn); ?></span> <span><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat(($is_cust_cost ? $itemarr['tar']['cost'] : VikRentItems::sayCostPlusIva($itemarr['tar']['cost'] * $itemarr['itemquant'], $itemarr['tar']['idprice'], $ord))); ?></span>
				</div>
					<?php
				}
				if (count($itemarr['optbought']) || isset($locfeewith) || array_key_exists('delivery', $itemarr) || !empty($itemarr['extracosts'])) {
				?>
				<div class="vri-ord-item-entry-extras-list">
					<?php
					if (count($itemarr['optbought'])) {
						?>
					<div class="vri-ord-item-entry-options">
						<span class="vrivordcoststitle"><?php echo JText::translate('VROPTS'); ?></span>
						<div class="vrivordcostsoptionals">
						<?php
						foreach ($itemarr['optbought'] as $optbought) {
							?>
							<div class="vri-ord-optbought-entry">
							<?php
							if ($optbought['units'] > 1) {
								?>
								<span class="vri-ord-optbought-entry-quant"><?php echo $optbought['units']; ?></span>
								<?php
							}
							?>
								<span class="vri-ord-optbought-entry-name"><?php echo $optbought['name']; ?></span>
							<?php
							if ($optbought['cost'] > 0) {
								?>
								<span class="vri-ord-optbought-entry-cost"><?php echo $currencysymb . " " . VikRentItems::numberFormat($optbought['cost']); ?></span>
								<?php
							}
							?>
							</div>
							<?php
						}
						?>
						</div>
					</div>
						<?php
					}
					if (isset($locfeewith)) {
						?>
					<div class="vri-ord-item-entry-options vri-ord-item-entry-locfee">
						<span class="vrivordcoststitle"><?php echo JText::translate('VRLOCFEETOPAY'); ?></span> <span><?php echo $currencysymb; ?> <?php echo $locfeewith; ?></span>
					</div>
						<?php
					}
					if (array_key_exists('delivery', $itemarr)) {
						?>
					<div class="vri-ord-item-entry-options vri-ord-item-entry-delivery">
						<span class="vrivordcoststitle"><?php echo JText::translate('VRISUMMARYDELIVERYTO'); ?></span> <span><?php echo $itemarr['delivery']['addr']; ?></span>
					</div>
						<?php
					}
					if (!empty($itemarr['extracosts'])) {
						?>
					<div class="vri-ord-item-entry-options vri-ord-item-entry-extracosts">
						<span class="vrivordcoststitle"><?php echo JText::translate('VRIEXTRASERVICES'); ?></span>
						<div class="vbvordextraservices"><?php echo $itemarr['extracosts']; ?></div>
					</div>
						<?php
					}
					?>
				</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
	}
}
?>
	<div class="vri-ord-coststot-container">
	<?php
	if ($ord['deliverycost'] > 0) {
		?>
		<div class="vrivordcostsdelivery">
			<span class="vrivordcoststitle"><?php echo JText::translate('VRISUMMARYDELIVERYSERVICE'); ?></span>
			<span class="vri-ord-tot-cost vri-ord-delivery-cost"><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat($ord['deliverycost']); ?></span>
		</div>
		<?php
	}
	if ($usedcoupon) {
		?>
		<div class="vri-ord-coupon">
			<span class="vrivordcoststitle"><?php echo JText::translate('VRICOUPON').' '.$expcoupon[2]; ?></span>
			<span class="vri-ord-tot-cost vri-ord-coupon-disc">- <?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat($expcoupon[1]); ?></span>
		</div>
		<?php
	}
	?>
		<div class="vrivordcoststot">
			<span class="vrivordcoststitle"><?php echo JText::translate('VRTOTAL'); ?></span>
			<span class="vri-ord-tot-cost"><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat(($ord['status'] == 'confirmed' ? $ord['order_total'] : $isdue)); ?></span>
		</div>
	<?php
	if ($ord['status'] == 'confirmed' && $ord['totpaid'] > 0 && $ord['totpaid'] < $ord['order_total']) {
		?>
		<div class="vri-ord-totpaid">
			<span class="vrivordcoststitle"><?php echo JText::translate('VRIAMOUNTPAID'); ?></span>
			<span class="vri-ord-tot-cost vri-ord-tot-cost-paid"><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat($ord['totpaid']); ?></span>
		</div>
		<div class="vri-ord-totremaining">
			<span class="vrivordcoststitle"><?php echo JText::translate('VRITOTALREMAINING'); ?></span>
			<span class="vri-ord-tot-cost vri-ord-tot-cost-remaining"><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat(($ord['order_total'] - $ord['totpaid'])); ?></span>
		</div>
		<?php
	}
	?>
	</div>
</div>

<?php
if ($ord['status'] == 'confirmed') {
	// payment notes
	if (is_array($payment) && intval($payment['shownotealw']) == 1) {
		if (!empty($payment['note'])) {
			?>
<div class="vrivordpaynote">
	<?php echo $payment['note']; ?>
</div>
			<?php
		}
	}

	// auto-print command or cancellation request
	if ($printer == 1) {
		?>
<script type="text/javascript">
window.print();
</script>
		<?php
	} else {
		// cancellation request
		?>
<script type="text/javascript">
function vriOpenCancOrdForm() {
	document.getElementById('vriopencancform').style.display = 'none';
	document.getElementById('vriordcancformbox').style.display = 'block';
}
function vriValidateCancForm() {
	var vrvar = document.vricanc;
	if (!document.getElementById('vricancemail').value.match(/\S/)) {
		document.getElementById('vriformcancemail').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vriformcancemail').style.color='';
	}
	if (!document.getElementById('vricancreason').value.match(/\S/)) {
		document.getElementById('vriformcancreason').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vriformcancreason').style.color='';
	}
	return true;
}
</script>

<div class="vriordcancbox">
	<h3><?php echo JText::translate('VRIREQUESTCANCMOD'); ?></h3>
	<a href="javascript: void(0);" id="vriopencancform" onclick="javascript: vriOpenCancOrdForm();"><?php echo JText::translate('VRIREQUESTCANCMODOPENTEXT'); ?></a>
	<div class="vriordcancformbox" id="vriordcancformbox">
		<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems'); ?>" name="vricanc" method="post" onsubmit="javascript: return vriValidateCancForm();">
			<div class="vri-ord-reqcanc-container">
				<div class="vri-ord-reqcanc-entry">
					<label id="vriformcancemail" for="vricancemail"><?php echo JText::translate('VRIREQUESTCANCMODEMAIL'); ?></label>
					<input type="text" class="vriinput" name="email" id="vricancemail" value="<?php echo $ord['custmail']; ?>"/>
				</div>
				<div class="vri-ord-reqcanc-entry vri-ord-reqcanc-entry-reason">
					<label id="vriformcancreason" for="vricancreason"><?php echo JText::translate('VRIREQUESTCANCMODREASON'); ?></label>
					<textarea name="reason" id="vricancreason" rows="7" cols="30" class="vritextarea"></textarea>
				</div>
				<div class="vri-ord-reqcanc-entry vri-ord-reqcanc-entry-sbmt">
					<input type="submit" name="sendrequest" value="<?php echo JText::translate('VRIREQUESTCANCMODSUBMIT'); ?>" class="btn"/>
				</div>
			</div>
			<input type="hidden" name="sid" value="<?php echo $ord['sid']; ?>"/>
			<input type="hidden" name="idorder" value="<?php echo $ord['id']; ?>"/>
			<input type="hidden" name="option" value="com_vikrentitems"/>
			<input type="hidden" name="task" value="cancelrequest"/>
		</form>
	</div>
</div>
		<?php
	}
}

if (is_array($payment) && $ord['status'] == 'standby') {
	/**
	 * @wponly 	do not use require_once to load the payment
	 *
	 * @since 	1.0.0
	 */
	// require_once(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . "payments" . DIRECTORY_SEPARATOR . $payment['file']);

	$return_url = VikRentItems::externalroute("index.php?option=com_vikrentitems&view=order&sid=" . $ord['sid'] . "&ts=" . $ord['ts'], false, (!empty($bestitemid) ? $bestitemid : null));
	$error_url = VikRentItems::externalroute("index.php?option=com_vikrentitems&view=order&sid=" . $ord['sid'] . "&ts=" . $ord['ts'], false, (!empty($bestitemid) ? $bestitemid : null));
	$notify_url = VikRentItems::externalroute("index.php?option=com_vikrentitems&task=notifypayment&sid=" . $ord['sid'] . "&ts=" . $ord['ts'] . "&tmpl=component", false);	

	$transaction_name = VikRentItems::getPaymentName();
	$leave_deposit = 0;
	$percentdeposit = "";
	$array_order = array ();
	$array_order['order'] = $ord;
	$array_order['account_name'] = VikRentItems::getPaypalAcc();
	$array_order['transaction_currency'] = VikRentItems::getCurrencyCodePp();
	$array_order['items_name'] = implode(", ", array_unique($itemsnames));
	$array_order['transaction_name'] = !empty($transaction_name) ? $transaction_name : implode(", ", array_unique($itemsnames));
	$array_order['order_total'] = $isdue;
	$array_order['currency_symb'] = $currencysymb;
	$array_order['net_price'] = $imp;
	$array_order['tax'] = $tax;
	$array_order['return_url'] = $return_url;
	$array_order['error_url'] = $error_url;
	$array_order['notify_url'] = $notify_url;
	$array_order['total_to_pay'] = $isdue;
	$array_order['total_net_price'] = $imp;
	$array_order['total_tax'] = $tax;
	$totalchanged = false;
	if ($payment['charge'] > 0.00) {
		$totalchanged = true;
		if ($payment['ch_disc'] == 1) {
			//charge
			if ($payment['val_pcent'] == 1) {
				//fixed value
				$array_order['total_net_price'] += $payment['charge'];
				$array_order['total_tax'] += $payment['charge'];
				$array_order['total_to_pay'] += $payment['charge'];
				$newtotaltopay = $array_order['total_to_pay'];
			} else {
				//percent value
				$percent_net = $array_order['total_net_price'] * $payment['charge'] / 100;
				$percent_tax = $array_order['total_tax'] * $payment['charge'] / 100;
				$percent_to_pay = $array_order['total_to_pay'] * $payment['charge'] / 100;
				$array_order['total_net_price'] += $percent_net;
				$array_order['total_tax'] += $percent_tax;
				$array_order['total_to_pay'] += $percent_to_pay;
				$newtotaltopay = $array_order['total_to_pay'];
			}
		} else {
			//discount
			if ($payment['val_pcent'] == 1) {
				//fixed value
				$array_order['total_net_price'] -= $payment['charge'];
				$array_order['total_tax'] -= $payment['charge'];
				$array_order['total_to_pay'] -= $payment['charge'];
				$newtotaltopay = $array_order['total_to_pay'];
			} else {
				//percent value
				$percent_net = $array_order['total_net_price'] * $payment['charge'] / 100;
				$percent_tax = $array_order['total_tax'] * $payment['charge'] / 100;
				$percent_to_pay = $array_order['total_to_pay'] * $payment['charge'] / 100;
				$array_order['total_net_price'] -= $percent_net;
				$array_order['total_tax'] -= $percent_tax;
				$array_order['total_to_pay'] -= $percent_to_pay;
				$newtotaltopay = $array_order['total_to_pay'];
			}
		}
	}
	if (!VikRentItems::payTotal()) {
		$percentdeposit = (float)VikRentItems::getAccPerCent();
		if ($percentdeposit > 0) {
			$leave_deposit = 1;
			if (VikRentItems::getTypeDeposit() == "fixed") {
				$array_order['total_to_pay'] = $percentdeposit;
				$array_order['total_net_price'] = $percentdeposit;
				$array_order['total_tax'] = ($array_order['total_to_pay'] - $array_order['total_net_price']);
			} else {
				$array_order['total_to_pay'] = $array_order['total_to_pay'] * $percentdeposit / 100;
				$array_order['total_net_price'] = $array_order['total_net_price'] * $percentdeposit / 100;
				$array_order['total_tax'] = ($array_order['total_to_pay'] - $array_order['total_net_price']);
			}
		}
	}
	$array_order['leave_deposit'] = $leave_deposit;
	$array_order['percentdeposit'] = $percentdeposit;
	$array_order['payment_info'] = $payment;
	
	?>
<div class="vrivordpaybutton">
	<?php	
	if ($totalchanged) {
		$chdecimals = $payment['charge'] - (int)$payment['charge'];
		?>
	<p class="vripaymentchangetot">
		<?php echo $payment['name']; ?> 
		(<?php echo ($payment['ch_disc'] == 1 ? "+" : "-").($chdecimals > 0.00 ? $payment['charge'] : number_format($payment['charge'], 0))." ".($payment['val_pcent'] == 1 ? $currencysymb : "%"); ?>) 
		<span class="vriorddiffpayment"><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat($newtotaltopay); ?></span>
	</p>
		<?php
	}
	
	/**
	 * @wponly 	The payment gateway is now loaded 
	 * 			using the apposite dispatcher.
	 *
	 * @since 1.0.0
	 */
	JLoader::import('adapter.payment.dispatcher');

	$obj = JPaymentDispatcher::getInstance('vikrentitems', $payment['file'], $array_order, $payment['params']);
	// remember to echo the payment
	echo $obj->showPayment();
	
	?>
</div>
<?php
}
