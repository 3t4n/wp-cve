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

$dbo = JFactory::getDbo();
$session = JFactory::getSession();
$document = JFactory::getDocument();
if (VikRentItems::loadJquery()) {
	JHtml::fetch('jquery.framework', true, true);
	JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-1.12.4.min.js');
}
$vri_app = VikRentItems::getVriApplication();
$vrisessioncart = $this->vrisessioncart;
$maxdeliverycost = VikRentItems::getDeliveryMaxCost();
$days = $this->days;
$calcdays = $this->calcdays;
if ((int)$days != (int)$calcdays) {
	$origdays = $days;
	$days = $calcdays;
}
$coupon = $this->coupon;
$first = $this->first;
$second = $this->second;
$ftitle = $this->ftitle;
$place = $this->place;
$returnplace = $this->returnplace;
$payments = $this->payments;
$cfields = $this->cfields;
$customer_details = $this->customer_details;
$countries = $this->countries;
$vri_tn = $this->vri_tn;

$pitemid = VikRequest::getInt('Itemid', '', 'request');

$price = @count($vrisessioncart) ? $vrisessioncart[key($vrisessioncart)][0]['price'] : null;

$relations = array();
if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
	$relations = VikRentItems::loadRelatedItems(array_keys($vrisessioncart));
	$dailyandhourlyrates = false;
	$dailyrates = false;
	$hourlyrates = false;
	$usedhours = '';
	foreach ($vrisessioncart as $iditem => $itemarrparent) {
		foreach ($itemarrparent as $k => $itemarr) {
			if (array_key_exists('hours', $itemarr['price'])) {
				$hourlyrates = true;
				$usedhours = $itemarr['price']['hours'];
			} else {
				$dailyrates = true;
			}
		}
	}
	if ($dailyrates === true && $hourlyrates === true) {
		$dailyandhourlyrates = true;
	}
}

if (count($cfields)) {
	foreach ($cfields as $cf) {
		if (!empty($cf['poplink'])) {
			$mbox_opts = '{
				type: "iframe",
				iframe: {
					css: {
						width: "70%",
						height: "75%"
					}
				}
			}';
			$vri_app->prepareModalBox('.vrimodal', $mbox_opts);
			break;
		}
	}
}
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
if (VikRentItems::tokenForm()) {
	$vikt = uniqid(rand(17, 1717), true);
	$session->set('vikrtoken', $vikt);
	$tok = "<input type=\"hidden\" name=\"viktoken\" value=\"" . $vikt . "\"/>\n";
} else {
	$tok = "";
}

$imp = 0;
$totdue = 0;
$totdelivery = 0;
$delivery_per_itunit = VikRentItems::isDeliveryPerItemUnit();
$delivery_per_order = VikRentItems::isDeliveryPerOrder();
$wop = array();

if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
	foreach ($vrisessioncart as $iditem => $itemarrparent) {
		foreach ($itemarrparent as $ind => $itemarr) {
			$imp += VikRentItems::sayCostMinusIva($itemarr['price']['cost'] * $itemarr['units'], $itemarr['price']['idprice']);
			$totdue += VikRentItems::sayCostPlusIva($itemarr['price']['cost'] * $itemarr['units'], $itemarr['price']['idprice']);
			//delivery service
			if (array_key_exists('delivery', $itemarr) && is_array($itemarr['delivery']) && count($itemarr['delivery']) > 0) {
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
					if ($delivery_per_itunit) {
						$nowdelcost = $nowdelcost * $itemarr['units'];
					}
					//
				} elseif ((int)$itemarr['delivery']['vrideliveryelemid'] != (int)$itemarr['info']['id']) {
					$nowdelcost = $itemarr['delivery']['vrideliverydistance'] * $itemarr['delivery']['vrideliveryglobcostperunit'];
					if ($itemarr['delivery']['vrideliveryroundcost'] == 1) {
						$nowdelcost = round($nowdelcost);
					}
					if (!empty($itemarr['delivery']['vrideliverymaxcost']) && (float)$itemarr['delivery']['vrideliverymaxcost'] > 0 && $nowdelcost > (float)$itemarr['delivery']['vrideliverymaxcost']) {
						$nowdelcost = (float)$itemarr['delivery']['vrideliverymaxcost'];
					}
					// VRI 1.6 - Delivery per Item Unit (Quantity)
					if ($delivery_per_itunit) {
						$nowdelcost = $nowdelcost * $itemarr['units'];
					}
					//
				}
				$vrisessioncart[$iditem][$ind]['delivery']['vrideliverysaycost'] = $nowdelcost;
				$totdelivery += $totdelivery > 0 && $delivery_per_order ? 0 : $nowdelcost;
				if (!empty($maxdeliverycost) && (float)$maxdeliverycost > 0 && $totdelivery > (float)$maxdeliverycost) {
					$totdelivery = (float)$maxdeliverycost;
					$vrisessioncart[$iditem][$ind]['delivery']['vrideliverymaxcostreached'] = 1;
				}
			}
			//
			$wopstr = "";
			if (is_array($itemarr['options'])) {
				foreach ($itemarr['options'] as $selo) {
					$wopstr .= $selo['id'] . ":" . $selo['quan'] . (array_key_exists('specintv', $selo) ? '-'.$selo['specintv'] : '') . ";";
					$realcost = (intval($selo['perday']) == 1 ? ($selo['cost'] * $days * $selo['quan']) : ($selo['cost'] * $selo['quan']));
					if (!empty($selo['maxprice']) && $selo['maxprice'] > 0 && $realcost > $selo['maxprice']) {
						$realcost = $selo['maxprice'];
						if (intval($selo['hmany']) == 1 && intval($selo['quan']) > 1) {
							$realcost = $selo['maxprice'] * $selo['quan'];
						}
					}
					$opt_item_units = $selo['onceperitem'] ? 1 : $itemarr['units'];
					$imp += VikRentItems::sayOptionalsMinusIva($realcost * $opt_item_units, $selo['idiva']);
					$totdue += VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $selo['idiva']);
				}
			}
			$wop[$iditem][$ind] = $wopstr;
		}
	}
}

//delivery service
if ($totdelivery > 0) {
	$imp += VikRentItems::sayDeliveryMinusIva($totdelivery);
	$totdue += $totdelivery;
}
//
?>
<h3 class="vri-rental-summary-title"><?php echo JText::translate('VRRIEPILOGOORD'); ?></h3>

<?php
// itinerary
$pickloc = VikRentItems::getPlaceInfo($place, $vri_tn);
$droploc = VikRentItems::getPlaceInfo($returnplace, $vri_tn);
?>

<div class="vri-summary-itinerary">
	<div class="vrirentforlocs">
		<div class="vrirentalfor">
		<?php
		if (is_array($price) && array_key_exists('hours', $price)) {
			?>
			<h3 class="vrirentalforone"><?php echo JText::translate('VRIRENTALFOR'); ?> <?php echo (intval($price['hours']) == 1 ? "1 ".JText::translate('VRIHOUR') : $price['hours']." ".JText::translate('VRIHOURS')); ?></h3>
			<?php
		} else {
			?>
			<h3 class="vrirentalforone"><?php echo JText::translate('VRIRENTALFOR'); ?> <?php echo (intval($days) == 1 ? "1 ".JText::translate('VRDAY') : $days." ".JText::translate('VRDAYS')); ?></h3>
			<?php
		}
		?>
		</div>

		<div class="vri-itinerary-confirmation">
			<div class="vri-itinerary-pickup">
				<h4><?php echo JText::translate('VRPICKUP'); ?></h4>
			<?php
			if (count($pickloc)) {
				?>
				<div class="vri-itinerary-pickup-location">
					<?php VikRentItemsIcons::e('location-arrow'); ?>
					<div class="vri-itinerary-pickup-locdet">
						<span class="vri-itinerary-pickup-locname"><?php echo $pickloc['name']; ?></span>
					</div>
				</div>
				<?php
			}
			?>
				<div class="vri-itinerary-pickup-date">
					<?php VikRentItemsIcons::e('calendar'); ?>
					<span class="vri-itinerary-pickup-date-day"><?php echo date($df, $first); ?></span>
					<span class="vri-itinerary-pickup-date-time"><?php echo date($nowtf, $first); ?></span>
				</div>
			</div>
			<div class="vri-itinerary-dropoff">
				<h4><?php echo JText::translate('VRRETURN'); ?></h4>
			<?php
			if (count($droploc)) {
				?>
				<div class="vri-itinerary-dropoff-location">
					<?php VikRentItemsIcons::e('location-arrow'); ?>
					<div class="vri-itinerary-dropfff-locdet">
						<span class="vri-itinerary-dropoff-locname"><?php echo $droploc['name']; ?></span>
					</div>
				</div>
				<?php
			}
			?>
				<div class="vri-itinerary-dropoff-date">
					<?php VikRentItemsIcons::e('calendar'); ?>
					<span class="vri-itinerary-dropoff-date-day"><?php echo !is_array($price) || !array_key_exists('hours', $price) ? date($df, $second) : ''; ?></span>
					<span class="vri-itinerary-dropoff-date-time"><?php echo date($nowtf, $second); ?></span>
				</div>
			</div>
		</div>
		
	</div>

</div>

<div class="vri-oconfirm-summary-container">
<?php
$sf = 2;
if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
	foreach ($vrisessioncart as $iditem => $itemarrparent) {
		foreach ($itemarrparent as $k => $itemarr) {
			$saywithout = VikRentItems::sayCostMinusIva($itemarr['price']['cost'] * $itemarr['units'], $itemarr['price']['idprice']);
			$saywith = VikRentItems::sayCostPlusIva($itemarr['price']['cost'] * $itemarr['units'], $itemarr['price']['idprice']);
			?>
	<div class="vri-oconfirm-summary-item-wrapper">
		<div class="vri-oconfirm-summary-item-head">
			<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-descr">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-days">
				<span><?php echo is_array($price) && array_key_exists('hours', $price) ? JText::translate('VRIHOURS') : JText::translate('ORDDD'); ?></span>
			</div>
			<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-net">
				<span><?php echo JText::translate('ORDNOTAX'); ?></span>
			</div>
			<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-tax">
				<span><?php echo JText::translate('ORDTAX'); ?></span>
			</div>
			<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-tot">
				<span><?php echo JText::translate('ORDWITHTAX'); ?></span>
			</div>
		</div>

		<div class="vri-oconfirm-summary-item-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<div class="vri-oconfirm-iteminfo-wrap">
					<div class="vri-oconfirm-itemname">
						<?php echo $itemarr['info']['name']; ?>
					<?php
					if ($itemarr['units'] > 1) {
						?>
						<span class="vri-oconfirm-itemname-units"><?php echo '(x ' . $itemarr['units'] . ')'; ?></span>
						<?php
					}
					?>
					</div>
					<div class="vri-oconfirm-priceinfo">
					<?php
						echo VikRentItems::getPriceName($itemarr['price']['idprice'], $vri_tn).(!empty($itemarr['price']['attrdata']) ? "<br/>".VikRentItems::getPriceAttr($itemarr['price']['idprice'], $vri_tn).": ".$itemarr['price']['attrdata'] : "");
					?>
					</div>
				</div>
				<div class="vri-oconfirm-item-action">
					<a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=rmcartitem&elem='.$iditem.';'.$k.'&place='.$place.'&returnplace='.$returnplace.'&days='.$days.'&pickup='.$first.'&release='.$second); ?>" onclick="return confirm('<?php echo addslashes(JText::translate('VRICARTCONFRMITEM')); ?>');"><?php VikRentItemsIcons::e('trash-alt'); ?></a>
				</div>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo is_array($price) && array_key_exists('hours', $price) ? JText::translate('VRIHOURS') : JText::translate('ORDDD'); ?></span>
				</div>
				<span><?php echo (array_key_exists('timeslot', $itemarr) ? $itemarr['timeslot']['name'] : (array_key_exists('hours', $itemarr['price']) ? $itemarr['price']['hours'].($dailyandhourlyrates === true ? " (".JText::translate('VRIHOURS').")" : "") : $days.($dailyandhourlyrates === true ? " (".JText::translate('VRDAY').")" : ""))); ?></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDNOTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($saywithout); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($saywith - $saywithout); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDWITHTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($saywith); ?></span>
				</span>
			</div>
		</div>

		<?php
			if (is_array($itemarr['options'])) {
				foreach ($itemarr['options'] as $aop) {
					if (intval($aop['perday']) == 1) {
						$thisoptcost = ($aop['cost'] * $aop['quan']) * $days;
					} else {
						$thisoptcost = $aop['cost'] * $aop['quan'];
					}
					if (!empty($aop['maxprice']) && $aop['maxprice'] > 0 && $thisoptcost > $aop['maxprice']) {
						$thisoptcost = $aop['maxprice'];
						if (intval($aop['hmany']) == 1 && intval($aop['quan']) > 1) {
							$thisoptcost = $aop['maxprice'] * $aop['quan'];
						}
					}
					$opt_item_units = $aop['onceperitem'] ? 1 : $itemarr['units'];
					$optwithout = VikRentItems::sayOptionalsMinusIva($thisoptcost * $opt_item_units, $aop['idiva']);
					$optwith = VikRentItems::sayOptionalsPlusIva($thisoptcost * $opt_item_units, $aop['idiva']);
					$opttax = VikRentItems::numberFormat($optwith - $optwithout);
					$aop['quan'] = $opt_item_units > 1 ? ($aop['quan'] * $opt_item_units) : $aop['quan'];
					?>
		<div class="vri-oconfirm-summary-item-row vri-oconfirm-summary-option-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<div class="vri-oconfirm-optname"><?php echo $aop['name'].($aop['quan'] > 1 ? " <small>(x ".$aop['quan'].")</small>" : ""); ?></div>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo is_array($price) && array_key_exists('hours', $price) ? JText::translate('VRIHOURS') : JText::translate('ORDDD'); ?></span>
				</div>
				<span><?php echo (array_key_exists('timeslot', $itemarr) ? $itemarr['timeslot']['name'] : (array_key_exists('hours', $itemarr['price']) ? $itemarr['price']['hours'].($dailyandhourlyrates === true ? " (".JText::translate('VRIHOURS').")" : "") : $days.($dailyandhourlyrates === true ? " (".JText::translate('VRDAY').")" : ""))); ?></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDNOTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($optwithout); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($opttax); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDWITHTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($optwith); ?></span>
				</span>
			</div>
		</div>
					<?php
					$sf++;
				}
			}
			//delivery service
			if (array_key_exists('delivery', $itemarr) && is_array($itemarr['delivery']) && count($itemarr['delivery']) > 0) {
				$delnetcost = VikRentItems::sayDeliveryMinusIva($itemarr['delivery']['vrideliverysaycost']);
				$deltotcost = $itemarr['delivery']['vrideliverysaycost'];
				$deltottax = $deltotcost - $delnetcost;
				$deliverystrokeclass = (array_key_exists('vrideliverymaxcostreached', $itemarr['delivery']) && $itemarr['delivery']['vrideliverymaxcostreached'] == 1 ? ' vripricestroke' : '');
				?>
		<div class="vri-oconfirm-summary-item-row vri-oconfirm-summary-extracost-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<div class="vri-oconfirm-extracostname">
					<span class="vri-summary-deliveryto-lbl"><?php echo JText::translate('VRISUMMARYDELIVERYTO'); ?></span>
					<span class="vri-summary-deliveryto-addr"><?php echo $itemarr['delivery']['vrideliveryaddress']; ?></span>
				</div>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<?php
				if (!$delivery_per_order || count($vrisessioncart) < 2) {
				?>
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDNOTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice<?php echo $deliverystrokeclass; ?>">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($delnetcost); ?></span>
				</span>
				<?php
				} else {
					?>
				<span></span>
					<?php
				}
				?>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<?php
				if (!$delivery_per_order || count($vrisessioncart) < 2) {
				?>
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice<?php echo $deliverystrokeclass; ?>">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($deltottax); ?></span>
				</span>
				<?php
				} else {
					?>
				<span></span>
					<?php
				}
				?>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<?php
				if (!$delivery_per_order || count($vrisessioncart) < 2) {
				?>
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDWITHTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice<?php echo $deliverystrokeclass; ?>">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($deltotcost); ?></span>
				</span>
				<?php
				} else {
					?>
				<span></span>
					<?php
				}
				?>
			</div>
		</div>
				<?php
			}
			//end delivery service
	// end item wrapper
	?>
	</div>
	<?php
		}
	}
}

	if (!empty($place) && !empty($returnplace) && is_array($vrisessioncart) && count($vrisessioncart) > 0) {
		$locfee = VikRentItems::getLocFee($place, $returnplace);
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
				if (array_key_exists($days, $arrvaloverrides)) {
					$locfee['cost'] = $arrvaloverrides[$days];
				}
			}
			//end VikRentItems 1.1 - Location fees overrides
			$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $days) : $locfee['cost'];
			$locfeewithout = VikRentItems::sayLocFeeMinusIva($locfeecost, $locfee['idiva']);
			$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva']);
			$locfeetax = VikRentItems::numberFormat($locfeewith - $locfeewithout);
			$imp += $locfeewithout;
			$totdue += $locfeewith;
			?>
	<div class="vri-oconfirm-summary-item-wrapper vri-oconfirm-summary-locfees-wrapper">
		<div class="vri-oconfirm-summary-item-row vri-oconfirm-summary-extracost-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<div class="vri-oconfirm-extracostname"><?php echo JText::translate('VRLOCFEETOPAY'); ?></div>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo is_array($price) && array_key_exists('hours', $price) ? JText::translate('VRIHOURS') : JText::translate('ORDDD'); ?></span>
				</div>
				<span><?php echo (is_array($price) && array_key_exists('hours', $price) ? $price['hours'] : $days); ?></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDNOTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($locfeewithout); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($locfeetax); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-head-cell-responsive">
					<span><?php echo JText::translate('ORDWITHTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($locfeewith); ?></span>
				</span>
			</div>
		</div>
	</div>
			<?php

		}
	}

	//store Order Total in session for modules
	$session->set('vikrentitems_ordertotal', $totdue);
	//

	//vikrentitems 1.1
	$origtotdue = $totdue;
	$usedcoupon = false;
	if (is_array($coupon)) {
		//check min tot ord
		$coupontotok = true;
		if (strlen($coupon['mintotord']) > 0) {
			if ($totdue < $coupon['mintotord']) {
				$coupontotok = false;
			}
		}
		if ($coupontotok == true) {
			$usedcoupon = true;
			if ($coupon['percentot'] == 1) {
				//percent value
				$minuscoupon = 100 - $coupon['value'];
				$couponsave = $totdue * $coupon['value'] / 100;
				$totdue = $totdue * $minuscoupon / 100;
			} else {
				//total value
				$couponsave = $coupon['value'];
				$totdue = $totdue - $coupon['value'];
			}
		} else {
			VikError::raiseWarning('', JText::translate('VRICOUPONINVMINTOTORD'));
		}
	}
	//
	?>
	<div class="vri-oconfirm-summary-total-wrapper">
	<?php
	if ($totdelivery > 0) {
		$totdeliverynet = VikRentItems::sayDeliveryMinusIva($totdelivery);
		$totdeliverytax = $totdelivery - $totdeliverynet;
		?>
		<div class="vri-oconfirm-summary-item-row vri-oconfirm-summary-total-row vri-oconfirm-summary-delivery-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<div class="vri-oconfirm-total-block vri-oconfirm-total-delivery-block"><?php echo JText::translate('VRISUMMARYDELIVERYSERVICE'); ?></div>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-net">
					<span><?php echo JText::translate('ORDNOTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($totdeliverynet); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-tax">
					<span><?php echo JText::translate('ORDTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($totdeliverytax); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-tot">
					<span><?php echo JText::translate('ORDWITHTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($totdelivery); ?></span>
				</span>
			</div>
		</div>
		<?php
	}
	?>
		<div class="vri-oconfirm-summary-item-row vri-oconfirm-summary-total-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<div class="vri-oconfirm-total-block"><?php echo JText::translate('VRTOTAL'); ?></div>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-net">
					<span><?php echo JText::translate('ORDNOTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($imp); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-tax">
					<span><?php echo JText::translate('ORDTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat(($origtotdue - $imp)); ?></span>
				</span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<div class="vri-oconfirm-summary-item-head-cell vri-oconfirm-summary-item-cell-tot">
					<span><?php echo JText::translate('ORDWITHTAX'); ?></span>
				</div>
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($origtotdue); ?></span>
				</span>
			</div>
		</div>
	<?php
	if ($usedcoupon === true) {
		?>
		<div class="vri-oconfirm-summary-item-row vri-oconfirm-summary-total-row vri-oconfirm-summary-coupon-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<span><?php echo JText::translate('VRICOUPON'); ?> <?php echo $coupon['code']; ?></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<span class="vricurrency">- <span class="vri_currency"><?php echo $currencysymb; ?></span></span> 
				<span class="vriprice"><span class="vri_price"><?php echo VikRentItems::numberFormat($couponsave); ?></span></span>
			</div>
		</div>

		<div class="vri-oconfirm-summary-item-row vri-oconfirm-summary-total-row vri-oconfirm-summary-coupon-newtot-row">
			<div class="vri-oconfirm-summary-item-cell-descr">
				<div class="vri-oconfirm-total-block"><?php echo JText::translate('VRINEWTOTAL'); ?></div>
			</div>
			<div class="vri-oconfirm-summary-item-cell-days">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-net">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tax">
				<span></span>
			</div>
			<div class="vri-oconfirm-summary-item-cell-tot">
				<span class="vricurrency">
					<span class="vri_currency"><?php echo $currencysymb; ?></span>
				</span> 
				<span class="vriprice">
					<span class="vri_price"><?php echo VikRentItems::numberFormat($totdue); ?></span>
				</span>
			</div>
		</div>
		<?php
	}
	?>
	</div>

</div>

<script type="text/javascript">
function vriConfirmEmptyCart() {
	document.getElementById('vriemptycartconfirmbox').style.display = 'block';
	document.getElementById('vrichangedatesconfirmbox').style.display = 'none';
}
function vriCancelEmptyCart() {
	document.getElementById('vriemptycartconfirmbox').style.display = 'none';
	document.getElementById('vrichangedatesconfirmbox').style.display = 'none';
}
function vriConfirmChangeDates() {
	document.getElementById('vrichangedatesconfirmbox').style.display = 'block';
	document.getElementById('vriemptycartconfirmbox').style.display = 'none';
}
function vriCancelChangeDates() {
	document.getElementById('vrichangedatesconfirmbox').style.display = 'none';
	document.getElementById('vriemptycartconfirmbox').style.display = 'none';
}
function vriGotoConfirmForm() {
	jQuery.noConflict();
	jQuery('html,body').animate({ scrollTop: (jQuery("#vriconfordformanchor").offset().top - 2) }, { duration: 'slow' });
}
</script>

<div class="vrioconfirmbuttonsdiv">
	<div class="vriemptycartdivcontainer">
		<div class="vriemptycartdiv">
			<a href="javascript: void(0);" onclick="vriConfirmEmptyCart();"><?php echo JText::translate('VRIEMPTYCART'); ?></a>
		</div>
		<div class="vriemptycartconfirmbox" id="vriemptycartconfirmbox">
			<span><?php echo JText::translate('VRIEMPTYCARTCONFIRM'); ?></span>
			<a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=emptycart&place='.$place.'&returnplace='.$returnplace.'&days='.$days.'&pickup='.$first.'&release='.$second); ?>" class="vri-summary-emptybut vri-summary-yes"><?php echo JText::translate('VRIYES'); ?></a>
			<a href="javascript: void(0);" class="vri-summary-emptybut vri-summary-no" onclick="vriCancelEmptyCart();"><?php echo JText::translate('VRINO'); ?></a>
		</div>
	</div>
	<div class="vrichangedatesdivcontainer">
		<div class="vrichangedatesdiv">
			<a href="javascript: void(0);" onclick="vriConfirmChangeDates();"><?php echo JText::translate('VRICHANGEDATES'); ?></a>
		</div>
		<div class="vrichangedatesconfirmbox" id="vrichangedatesconfirmbox">
			<span><?php echo JText::translate('VRICHANGEDATESCONFIRM'); ?></span>
			<a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=emptycart&place='.$place.'&returnplace='.$returnplace.'&days='.$days.'&pickup='.$first.'&release='.$second); ?>" class="vri-summary-chdbut vri-summary-yes"><?php echo JText::translate('VRIYES'); ?></a>
			<a href="javascript: void(0);" class="vri-summary-chdbut vri-summary-no" onclick="vriCancelChangeDates();"><?php echo JText::translate('VRINO'); ?></a>
		</div>
	</div>
	<?php
	if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
	?>
	<div class="vricompleteorderdiv">
		<a href="javascript: void(0);" onclick="vriGotoConfirmForm();" class="btn"><?php echo JText::translate('VRICOMPLETEYOURORDER'); ?></a>
	</div>
	<?php
	}
	?>
</div>

<div class="vri-oconfirm-middlep">
<?php
// coupon code
if (VikRentItems::couponsEnabled() && !is_array($coupon)) {
	?>
	<div class="vri-coupon-outer">
		<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems'); ?>" method="post">
			<div class="vrientercoupon">
			<span class="vrihaveacoupon"><?php echo JText::translate('VRIHAVEACOUPON'); ?></span><input type="text" name="couponcode" value="" size="20" class="vriinputcoupon"/><input type="submit" class="btn vrisubmitcoupon" name="applyacoupon" value="<?php echo JText::translate('VRISUBMITCOUPON'); ?>"/>
			</div>
			<input type="hidden" name="place" value="<?php echo $place; ?>"/>
			<input type="hidden" name="returnplace" value="<?php echo $returnplace; ?>"/>
			<input type="hidden" name="days" value="<?php echo $days; ?>"/>
			<input type="hidden" name="pickup" value="<?php echo $first; ?>"/>
			<input type="hidden" name="release" value="<?php echo $second; ?>"/>
			<input type="hidden" name="task" value="oconfirm"/>
		</form>
	</div>
	<?php
}

// Customers PIN
if (VikRentItems::customersPinEnabled() && !VikRentItems::userIsLogged() && !(count($customer_details) > 0)) {
	?>
	<div class="vri-enterpin-block">
		<div class="vri-enterpin-top">
			<span><span><?php echo JText::translate('VRRETURNINGCUSTOMER'); ?></span><?php echo JText::translate('VRENTERPINCODE'); ?></span>
			<input type="text" id="vri-pincode-inp" value="" size="6"/>
			<button type="button" class="btn vri-pincode-sbmt"><?php echo JText::translate('VRAPPLYPINCODE'); ?></button>
		</div>
		<div class="vri-enterpin-response"></div>
	</div>
	<script>
	jQuery(document).ready(function() {
		jQuery(".vri-pincode-sbmt").click(function() {
			var pin_code = jQuery("#vri-pincode-inp").val();
			jQuery(this).prop('disabled', true);
			jQuery(".vri-enterpin-response").hide();
			jQuery.ajax({
				type: "POST",
				url: "<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=validatepin&tmpl=component'.(!empty($pitemid) ? '&Itemid='.$pitemid : ''), false); ?>",
				data: { pin: pin_code }
			}).done(function(res) {
				var pinobj = JSON.parse(res);
				if (pinobj.hasOwnProperty('success')) {
					jQuery(".vri-enterpin-top").hide();
					jQuery(".vri-enterpin-response").removeClass("vri-enterpin-error").addClass("vri-enterpin-success").html("<span class=\"vri-enterpin-welcome\"><?php echo addslashes(JText::translate('VRWELCOMEBACK')); ?></span><span class=\"vri-enterpin-customer\">"+pinobj.first_name+" "+pinobj.last_name+"</span>").fadeIn();
					jQuery.each(pinobj.cfields, function(k, v) {
						if (jQuery("#vrif-inp"+k).length) {
							jQuery("#vrif-inp"+k).val(v);
						}						
					});
					var user_country = pinobj.country;
					if (jQuery(".vrif-countryinp").length && user_country.length) {
						jQuery(".vrif-countryinp option").each(function(i){
							var opt_country = jQuery(this).val();
							if (opt_country.substring(0, 3) == user_country) {
								jQuery(this).prop("selected", true);
								return false;
							}
						});
					}
				} else {
					jQuery(".vri-enterpin-response").addClass("vri-enterpin-error").html("<p><?php echo addslashes(JText::translate('VRINVALIDPINCODE')); ?></p>").fadeIn();
					jQuery(".vri-pincode-sbmt").prop('disabled', false);
				}
			}).fail(function(){
				alert('Error validating the PIN. Request failed.');
				jQuery(".vri-pincode-sbmt").prop('disabled', false);
			});
		});
	});
	</script>
	<?php
}
?>
</div>

<?php
//Continue Renting Items
	?>
<div class="vricontinuerentdiv">
	<h4><?php echo JText::translate('VRICONTINUERENTING'); ?></h4>
	<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems'); ?>" method="post">
	<?php
	if (VikRentItems::showCategoriesFront()) {
		$q = "SELECT * FROM `#__vikrentitems_categories` ORDER BY `#__vikrentitems_categories`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$categories = $dbo->loadAssocList();
			$vri_tn->translateContents($categories, '#__vikrentitems_categories');
			$catform = "<select name=\"categories\" id=\"continuecategories\">\n";
			$catform .= "<option value=\"all\">" . JText::translate('VRALLCAT') . "</option>\n";
			foreach ($categories as $cat) {
				$catform .= "<option value=\"" . $cat['id'] . "\">" . $cat['name'] . "</option>\n";
			}
			$catform .= "</select>\n";
		?>
		<div class="vricontinuecategory">
			<label for="continuecategories"><?php echo JText::translate('VRICONTINUECATEGSEARCH'); ?></label>
			<?php echo $catform; ?>
		</div>
		<?php
		}
	}
	$continuetimeslot = '';
	if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
		foreach ($vrisessioncart as $iditem => $itemarrparent) {
			foreach ($itemarrparent as $k => $itemarr) {
				if (array_key_exists('timeslot', $itemarr)) {
					$continuetimeslot = '<input type="hidden" name="timeslot" value="'.$itemarr['timeslot']['id'].'"/>'."\n";
					break;
				}
			}
		}
	}
	echo $continuetimeslot;
	?>
		<input type="hidden" name="place" value="<?php echo $place; ?>"/>
		<input type="hidden" name="returnplace" value="<?php echo $returnplace; ?>"/>
		<input type="hidden" name="pickupdate" value="<?php echo date($df, $first); ?>"/>
		<input type="hidden" name="pickuph" value="<?php echo date('H', $first); ?>"/>
		<input type="hidden" name="pickupm" value="<?php echo date('i', $first); ?>"/>
		<input type="hidden" name="releasedate" value="<?php echo date($df, $second); ?>"/>
		<input type="hidden" name="releaseh" value="<?php echo date('H', $second); ?>"/>
		<input type="hidden" name="releasem" value="<?php echo date('i', $second); ?>"/>
		<input type="hidden" name="task" value="search"/>
		<input type="hidden" name="option" value="com_vikrentitems"/>
		<input type="submit" name="searchmore" value="<?php echo JText::translate('VRICONTINUESEARCH'); ?>" class="btn booknow"/>
		<?php
		if (!empty($pitemid)) {
		?>
			<input type="hidden" name="Itemid" value="<?php echo $pitemid; ?>"/>
			<?php
		}
		?>
	</form>
</div>
	<?php
//

//Related Items
if (is_array($relations) && count($relations) > 0) {
	shuffle($relations);
	?>
<div class="vri-summary-interested">
	<h4 class="vriyoumightintp"><?php echo JText::translate('VRIMIGHTINTEREST'); ?></h4>
	
	<div class="vrirelateditemsdivscroll">
		<ul class="vrirelateditems" id="vrirelateditemsulscroll">
		<?php
		foreach ($relations as $rel) {
			$item_params = !empty($rel['jsparams']) ? json_decode($rel['jsparams'], true) : array();
			$imgpath = is_file(VRI_ADMIN_PATH.DS.'resources'.DS.'vthumb_'.$rel['img']) ? VRI_ADMIN_URI.'resources/vthumb_'.$rel['img'] : VRI_ADMIN_URI.'resources/'.$rel['img'];
			?>
			<li class="vrirelitemdiv">
				<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems'); ?>" method="post">
					<input type="hidden" name="option" value="com_vikrentitems"/>
		  			<input type="hidden" name="itemopt" value="<?php echo $rel['id']; ?>"/>
		  			<input type="hidden" name="days" value="<?php echo $days; ?>"/>
		  			<input type="hidden" name="pickup" value="<?php echo $first; ?>"/>
		  			<input type="hidden" name="release" value="<?php echo $second; ?>"/>
		  			<input type="hidden" name="place" value="<?php echo $place; ?>"/>
		  			<input type="hidden" name="returnplace" value="<?php echo $returnplace; ?>"/>
		  			<input type="hidden" name="task" value="showprc"/>
		  			<div class="vri-oconfirm-relitem-top">
			  			<div class="vrirelitemimgdiv">
			  			<?php
			  			if (!empty($rel['img'])) {
			  				?>
			  				<img class="vrirelitemimg" alt="<?php echo htmlspecialchars($rel['name']); ?>" src="<?php echo $imgpath; ?>"/>
			  				<?php
			  			}
			  			?>
			  			</div>
			  			<span class="vrirelitemname"><?php echo $rel['name']; ?></span>
			  		</div>
			  		<div class="vri-oconfirm-relitem-bottom">
			  			<?php
						if ($rel['askquantity'] == 1) {
							if (intval(VikRentItems::getItemParam($rel['params'], 'discsquantstab')) == 1) {
								$q = "SELECT * FROM `#__vikrentitems_discountsquants` WHERE `iditems` LIKE '%-".$rel['id']."-%' ORDER BY `#__vikrentitems_discountsquants`.`quantity` ASC;";
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() > 0) {
									$discounts = $dbo->loadAssocList();
									?>
									<div class="vridiscsquantsdivsearchrel">
										<table class="vridiscsquantstablesearchrel">
											<tr class="vridiscsquantstrfirstsearchrel"><td><?php echo JText::translate('VRIDISCSQUANTSQ'); ?></td><td><?php echo JText::translate('VRIDISCSQUANTSSAVE'); ?></td></tr>
											<?php
											foreach ($discounts as $kd => $disc) {
												$discval = substr($disc['diffcost'], -2) == '00' ? number_format($disc['diffcost'], 0) : VikRentItems::numberFormat($disc['diffcost']);
												$savedisc = $disc['val_pcent'] == 1 ? $currencysymb.' '.$discval : $discval.'%';
												$disc_keys = array_keys($discounts);
												?>
											<tr class="vridiscsquantstrentrysearchrel">
												<td><?php echo $disc['quantity'].(end($disc_keys) == $kd && $disc['ifmorequant'] == 1 ? ' '.JText::translate('VRIDISCSQUANTSORMORE') : ''); ?></td>
												<td><?php echo $savedisc; ?></td>
											</tr>	
												<?php
											}
											?>
										</table>
									</div>
									<?php
								}
							}
							?>
						<div class="vriselectquantitydivrelmain">
							<div class="vriselectquantitydivrel">
								<label for="itemquant-<?php echo $rel['id']; ?>"><?php echo JText::translate('VRIQUANTITYX'); ?></label>
								<input type="number" name="itemquant" id="itemquant-<?php echo $rel['id']; ?>" value="<?php echo (!array_key_exists('minquant', $item_params) || empty($item_params['minquant']) ? '1' : (int)$item_params['minquant']); ?>" min="<?php echo (!array_key_exists('minquant', $item_params) || empty($item_params['minquant']) ? '1' : (int)$item_params['minquant']); ?>" max="<?php echo $rel['units']; ?>" class="vrismallinput vri-numbinput"/>
							</div>
						<?php
						} else {
							?>
						<div class="vriselectquantitydivrelmain">	
							<?php
						}
						?>
							<div class="vrirelitemsubmitdiv">
								<button type="submit" class="btn vrirelitemsubmit"><?php echo JText::translate('VRIMIGHTINTERESTBOOK'); ?></button>
							</div>
						</div>
					</div>
				</form>
			</li>
			<?php
		}
		?>
		</ul>
	</div>
</div>

<script type="text/javascript">
document.getElementById('vrirelateditemsulscroll').style.width = '<?php echo ((count($relations) * 220) + 100); ?>px';
</script>

<?php
}
//End Related Items

?>
<script type="text/javascript">
function vriValidateEmail(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function checkvriFields() {
	var vrvar = document.vri;
	<?php
if (count($cfields)) {
	foreach ($cfields as $cf) {
		if (intval($cf['required']) == 1) {
			if ($cf['type'] == "text" || $cf['type'] == "textarea" || $cf['type'] == "date" || $cf['type'] == "country") {
			?>
	if (!vrvar.vrif<?php echo $cf['id']; ?>.value.match(/\S/)) {
		document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='';
	}
			<?php
				if ($cf['isemail'] == 1) {
				?>
			if (!vriValidateEmail(vrvar.vrif<?php echo $cf['id']; ?>.value)) {
				document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='#ff0000';
				return false;
			} else {
				document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='';
			}
				<?php
				}
			} elseif ($cf['type'] == "select") {
			?>
	if (!vrvar.vrif<?php echo $cf['id']; ?>.value.match(/\S/)) {
		document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='';
	}
			<?php
			} elseif ($cf['type'] == "checkbox") {
				//checkbox
			?>
	if (vrvar.vrif<?php echo $cf['id']; ?>.checked) {
		document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='';
	} else {
		document.getElementById('vrif<?php echo $cf['id']; ?>').style.color='#ff0000';
		return false;
	}
			<?php
			}
		}
	}
}
?>
	return true;
}
</script>

<?php
if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
	?>	
<a name="vriconfordformanchor" id="vriconfordformanchor"></a>

<div class="vri-oconfirm-mainf-cont">
	<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems'); ?>" name="vri" method="post" onsubmit="javascript: return checkvriFields();">
	<?php
if (count($cfields)) {
	?>
		<div class="vricustomfields">
	<?php
	$currentUser = JFactory::getUser();
	$useremail = !empty($currentUser->email) ? $currentUser->email : "";
	$useremail = array_key_exists('email', $customer_details) ? $customer_details['email'] : $useremail;
	$previousdata = VikRentItems::loadPreviousUserData($currentUser->id);
	$nominatives = array();
	if (count($customer_details) > 0) {
		$nominatives[] = $customer_details['first_name'];
		$nominatives[] = $customer_details['last_name'];
	}
	foreach ($cfields as $cf) {
		if (intval($cf['required']) == 1) {
			$isreq = "<span class=\"vrirequired\"><sup>*</sup></span> ";
		} else {
			$isreq = "";
		}
		if (!empty($cf['poplink'])) {
			$fname = "<a href=\"" . $cf['poplink'] . "\" id=\"vrif" . $cf['id'] . "\" target=\"_blank\" class=\"vrimodal\">" . JText::translate($cf['name']) . "</a>";
		} else {
			$fname = "<label id=\"vrif" . $cf['id'] . "\" for=\"vrif-inp" . $cf['id'] . "\">" . JText::translate($cf['name']) . "</label>";
		}
		if ($cf['type'] == "text") {
			$def_textval = isset($previousdata['customfields']) && isset($previousdata['customfields'][$cf['id']]) ? $previousdata['customfields'][$cf['id']] : '';
			if ($cf['isemail'] == 1) {
				$def_textval = $useremail;
			} elseif ($cf['isphone'] == 1) {
				if (array_key_exists('phone', $customer_details)) {
					$def_textval = $customer_details['phone'];
				}
			} elseif ($cf['isnominative'] == 1) {
				if (count($nominatives) > 0) {
					$def_textval = array_shift($nominatives);
				}
			} elseif (array_key_exists('cfields', $customer_details)) {
				if (array_key_exists($cf['id'], $customer_details['cfields'])) {
					$def_textval = $customer_details['cfields'][$cf['id']];
				}
			}
			?>
			<div class="vridivcustomfield vri-oconfirm-cfield-entry">
				<div class="vri-customfield-label vri-oconfirm-cfield-label">
					<?php echo $isreq; ?>
					<?php echo $fname; ?>
				</div>
				<div class="vri-customfield-input vri-oconfirm-cfield-input">
				<?php
				if ($cf['isphone'] == 1) {
					echo $vri_app->printPhoneInputField(array('name' => 'vrif' . $cf['id'], 'id' => 'vrif-inp' . $cf['id'], 'value' => $def_textval, 'class' => 'vriinput', 'size' => '40'));
				} else {
					?>
					<input type="text" name="vrif<?php echo $cf['id']; ?>" id="vrif-inp<?php echo $cf['id']; ?>" value="<?php echo $def_textval; ?>" size="40" class="vriinput"/>
					<?php
				}
				?>
				</div>
			</div>
			<?php
		} elseif ($cf['type'] == "textarea") {
			$def_textval = isset($previousdata['customfields']) && isset($previousdata['customfields'][$cf['id']]) ? $previousdata['customfields'][$cf['id']] : '';
			if (isset($customer_details['cfields']) && array_key_exists($cf['id'], $customer_details['cfields'])) {
				$def_textval = $customer_details['cfields'][$cf['id']];
			}
		?>
			<div class="vridivcustomfield vri-oconfirm-cfield-entry vri-oconfirm-cfield-entry-textarea">
				<div class="vri-customfield-label vri-oconfirm-cfield-label">
					<?php echo $isreq; ?>
					<?php echo $fname; ?>
				</div>
				<div class="vri-customfield-input vri-oconfirm-cfield-input">
					<textarea name="vrif<?php echo $cf['id']; ?>" id="vrif-inp<?php echo $cf['id']; ?>" rows="5" cols="30" class="vritextarea"><?php echo $def_textval; ?></textarea>
				</div>
			</div>
		<?php
		} elseif ($cf['type'] == "date") {
			$def_textval = isset($previousdata['customfields']) && isset($previousdata['customfields'][$cf['id']]) ? $previousdata['customfields'][$cf['id']] : '';
			?>
			<div class="vridivcustomfield vri-oconfirm-cfield-entry">
				<div class="vri-customfield-label vri-oconfirm-cfield-label">
					<?php echo $isreq; ?>
					<?php echo $fname; ?>
				</div>
				<div class="vri-customfield-input vri-oconfirm-cfield-input">
					<?php echo $vri_app->getCalendar('', 'vrif'.$cf['id'], 'vrif-inp'.$cf['id'], $nowdf, array('class' => 'vriinput', 'size' => '10', 'value' => $def_textval, 'maxlength' => '19')); ?>
				</div>
			</div>
			<?php
			if (!empty($def_textval)) {
			?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#vrif-inp<?php echo $cf['id']; ?>').val('<?php echo addslashes($def_textval); ?>');
			});
			</script>
			<?php
			}
			?>
		<?php
		} elseif ($cf['type'] == "country" && is_array($countries)) {
			$usercountry = isset($previousdata['customfields']) && isset($previousdata['customfields'][$cf['id']]) ? $previousdata['customfields'][$cf['id']] : '';
			if (array_key_exists('country', $customer_details)) {
				$usercountry = !empty($customer_details['country']) ? substr($customer_details['country'], 0, 3) : '';
			}
			$countries_sel = '<select name="vrif'.$cf['id'].'" class="vrif-countryinp"><option value=""></option>'."\n";
			foreach ($countries as $country) {
				$countries_sel .= '<option value="'.$country['country_3_code'].'::'.$country['country_name'].'"'.($country['country_3_code'] == $usercountry ? ' selected="selected"' : '').'>'.$country['country_name'].'</option>'."\n";
			}
			$countries_sel .= '</select>';
			?>
			<div class="vridivcustomfield vri-oconfirm-cfield-entry">
				<div class="vri-customfield-label vri-oconfirm-cfield-label">
					<?php echo $isreq; ?>
					<?php echo $fname; ?>
				</div>
				<div class="vri-customfield-input vri-oconfirm-cfield-input">
					<?php echo $countries_sel; ?>
				</div>
			</div>
			<?php
		} elseif ($cf['type'] == "select") {
			$def_textval = isset($previousdata['customfields']) && isset($previousdata['customfields'][$cf['id']]) ? $previousdata['customfields'][$cf['id']] : '';
			$answ = explode(";;__;;", $cf['choose']);
			$wcfsel = "<select name=\"vrif" . $cf['id'] . "\">\n";
			foreach ($answ as $aw) {
				if (!empty($aw)) {
					$wcfsel .= "<option value=\"" . JText::translate($aw) . "\"".($def_textval == JText::translate($aw) ? ' selected="selected"' : '').">" . JText::translate($aw) . "</option>\n";
				}
			}
			$wcfsel .= "</select>\n";
			?>
			<div class="vridivcustomfield vri-oconfirm-cfield-entry">
				<div class="vri-customfield-label vri-oconfirm-cfield-label">
					<?php echo $isreq; ?>
					<?php echo $fname; ?>
				</div>
				<div class="vri-customfield-input vri-oconfirm-cfield-input">
					<?php echo $wcfsel; ?>
				</div>
			</div>
			<?php
		} elseif ($cf['type'] == "separator") {
			$cfsepclass = strlen(JText::translate($cf['name'])) > 30 ? "vriseparatorcflong" : "vriseparatorcf";
			?>
			<div class="vri-oconfirm-cfield-entry vri-oconfirm-cfield-entry-separator">
				<div class="vri-oconfirm-cfield-separator <?php echo $cfsepclass; ?>">
					<?php echo JText::translate($cf['name']); ?>
				</div>
			</div>
			<?php
		} else {
			?>
			<div class="vridivcustomfield vri-oconfirm-cfield-entry vri-oconfirm-cfield-entry-checkbox">
				<div class="vri-customfield-label vri-oconfirm-cfield-label">
					<?php echo $isreq; ?>
					<?php echo $fname; ?>
				</div>
				<div class="vri-customfield-input vri-oconfirm-cfield-input">
					<input type="checkbox" name="vrif<?php echo $cf['id']; ?>" id="vrif-inp<?php echo $cf['id']; ?>" value="<?php echo JText::translate('VRYES'); ?>" <?php echo !(bool)$cf['required'] && isset($customer_details['cfields']) && isset($customer_details['cfields'][$cf['id']]) ? 'checked="checked"' : ''; ?>/>
				</div>
			</div>
			<?php
		}
	}
	?>
		</div>
	<?php
}
?>
		<input type="hidden" name="days" value="<?php echo $days; ?>"/>
	<?php
	//vikrentitems 1.1
	if (isset($origdays)) {
		?>
		<input type="hidden" name="origdays" value="<?php echo $origdays; ?>"/>
		<?php
	}
	//
	?>
		<input type="hidden" name="pickup" value="<?php echo $first; ?>"/>
		<input type="hidden" name="release" value="<?php echo $second; ?>"/>
		<input type="hidden" name="totdue" value="<?php echo $totdue; ?>"/>
		<input type="hidden" name="place" value="<?php echo $place; ?>"/>
		<input type="hidden" name="returnplace" value="<?php echo $returnplace; ?>"/>
	<?php
  	if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
		foreach ($vrisessioncart as $iditem => $itemarrparent) {
			foreach ($itemarrparent as $ind => $itemarr) {
				echo '<input type="hidden" name="item[]" value="'.$iditem.'"/>'."\n";
				echo '<input type="hidden" name="itemquant[]" value="'.$itemarr['units'].'"/>'."\n";
				echo '<input type="hidden" name="prtar[]" value="'.$itemarr['price']['id'].'"/>'."\n";
				echo '<input type="hidden" name="priceid[]" value="'.$itemarr['price']['idprice'].'"/>'."\n";
				echo '<input type="hidden" name="optionals[]" value="'.$wop[$iditem][$ind].'"/>'."\n";
				if (array_key_exists('timeslot', $itemarr)) {
					echo '<input type="hidden" name="timeslot[]" value="'.$itemarr['timeslot']['id'].'"/>'."\n";
				} else {
					echo '<input type="hidden" name="timeslot[]" value=""/>'."\n";
				}
				if (array_key_exists('delivery', $itemarr) && is_array($itemarr['delivery']) && count($itemarr['delivery']) > 0) {
					echo '<input type="hidden" name="delivery[]" value="'.$itemarr['delivery']['vrideliverysessid'].'"/>'."\n";
				} else {
					echo '<input type="hidden" name="delivery[]" value=""/>'."\n";
				}
			}
		}
  	}
	if ((is_array($price) && array_key_exists('hours', $price)) || $hourlyrates === true) {
		?>
		<input type="hidden" name="hourly" value="<?php echo $usedhours; ?>"/>	
		<?php
	}
	if ($usedcoupon == true && is_array($coupon)) {
		?>
		<input type="hidden" name="couponcode" value="<?php echo $coupon['code']; ?>"/>
		<?php
	}
	?>
		<?php echo !empty($tok) ? $tok . JHtml::fetch('form.token') : ''; ?>
		<input type="hidden" name="task" value="saveorder"/>
	<?php
	if (@is_array($payments)) {
	?>
		<div class="vri-oconfirm-paym-block">
			<h4 class="vri-medium-header"><?php echo JText::translate('VRIHOOSEPAYMENT'); ?></h4>
			<ul class="vri-noliststyletype">
		<?php
		foreach ($payments as $pk => $pay) {
			$rcheck = $pk == 0 ? " checked=\"checked\"" : "";
			$saypcharge = "";
			if ($pay['charge'] > 0.00) {
				$decimals = $pay['charge'] - (int)$pay['charge'];
				if ($decimals > 0.00) {
					$okchargedisc = VikRentItems::numberFormat($pay['charge']);
				} else {
					$okchargedisc = number_format($pay['charge'], 0);
				}
				$saypcharge .= " (".($pay['ch_disc'] == 1 ? "+" : "-");
				$saypcharge .= "<span class=\"vriprice\">" . $okchargedisc . "</span> <span class=\"vricurrency\">" . ($pay['val_pcent'] == 1 ? $currencysymb : "%") . "</span>";
				$saypcharge .= ")";
			}
			?>
				<li class="vri-gpay-licont<?php echo $pk == 0 ? ' vri-gpay-licont-active' : ''; ?>">
					<input type="radio" name="gpayid" value="<?php echo $pay['id']; ?>" id="gpay<?php echo $pay['id']; ?>"<?php echo $rcheck; ?> onclick="vriToggleActiveGpay(this);"/>
					<label for="gpay<?php echo $pay['id']; ?>"><?php echo $pay['name'].$saypcharge; ?></label>
			<?php
			$pay_img_name = '';
			if (strpos($pay['file'], '.') !== false) {
				$fparts = explode('.', $pay['file']);
				$pay_img_name = array_shift($fparts);
			}

			/**
			 * @wponly  Since the payments may be loaded from external plugins,
			 * 			the logos MUST be retrieved using an apposite filter.
			 *
			 * @since 	1.0.0
			 */
			$logo = array(
				'name' => $pay_img_name,
				'path' => VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'payments' . DIRECTORY_SEPARATOR . $pay_img_name . '.png',
				'uri'  => VRI_ADMIN_URI . 'payments/' . $pay_img_name . '.png',
			);

			/**
			 * Hook used to filter the array containing the logo's information.
			 * By default, the array contains the standard path and URI, related
			 * to the payment folder of the plugin.
			 *
			 * Plugins attached to this hook are able to filter the payment logo in case
			 * the image is stored somewhere else.
			 *
			 * @param 	array 	An array containing the following keys:
			 * 					- name 	the payment name;
			 * 					- path 	the payment logo absolute path;
			 * 					- uri 	the payment logo image URI.
			 *
			 * @since 	1.0.0
			 */
			$logo = apply_filters('vikrentitems_oconfirm_payment_logo', $logo);

			if (!empty($pay_img_name) && file_exists($logo['path'])) {
				?>
					<span class="vri-payment-image">
						<label for="gpay<?php echo $pay['id']; ?>"><img src="<?php echo $logo['uri']; ?>" alt="<?php echo htmlspecialchars($pay['name']); ?>"/></label>
					</span>
				<?php
			}
			?>
				</li>
			<?php
		}
		?>
			</ul>
		</div>
		<script type="text/javascript">
		function vriToggleActiveGpay(elem) {
			jQuery('.vri-gpay-licont').removeClass('vri-gpay-licont-active');
			jQuery(elem).parent('li').addClass('vri-gpay-licont-active');
		}
		</script>
		<?php
	}
	?>
		<div class="vri-save-order-block">
			<input type="submit" name="saveorder" value="<?php echo JText::translate('VRORDCONFIRM'); ?>" class="btn btn-large booknow"/>
		</div>
	<?php
	if (!empty($pitemid)) {
		?>
		<input type="hidden" name="Itemid" value="<?php echo $pitemid; ?>"/>
		<?php
	}
	?>
	</form>
</div>
<?php
}
