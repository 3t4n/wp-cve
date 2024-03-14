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

$tars = $this->tars;
$item = $this->item;
$pickup = $this->pickup;
$release = $this->release;
$place = $this->place;
$itemquant = $this->itemquant;
$timeslot = $this->timeslot;
$lastdelivery = $this->lastdelivery;
$vri_tn = $this->vri_tn;

$pitemid = VikRequest::getInt('Itemid', '', 'request');

//load jQuery lib and navigation
$document = JFactory::getDocument();
if (VikRentItems::loadJquery()) {
	JHtml::fetch('jquery.framework', true, true);
	JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-1.12.4.min.js');
}
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
//load jQuery UI
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.min.js');
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery.fancybox.css');
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery.fancybox.js');
$navdecl = '
jQuery(document).ready(function() {
	jQuery(\'.vrimodal[data-fancybox="gallery"]\').fancybox({});
});';
$document->addScriptDeclaration($navdecl);
//
?>
<div class="vri-page-content">
<?php

$preturnplace = VikRequest::getString('returnplace', '', 'request');
$carats = VikRentItems::getItemCaratOriz($item['idcarat'], $vri_tn);
$currencysymb = VikRentItems::getCurrencySymb();
$optionals = "";
if (!empty($item['idopt'])) {
	$optionals = VikRentItems::getItemOptionals($item['idopt'], $vri_tn);
}
$discl = VikRentItems::getDisclaimer($vri_tn);

$sayquantity = $itemquant > 1 ? '(x '.$itemquant.')' : '';
?>
	<div class="vri-showprc-groupblocks">
	<?php
	if (is_array($timeslot) && count($timeslot) > 0) {
		?>
		<h4 class="vri-medium-header vri-header-attract"><?php echo JText::translate('VRRENTAL'); ?> <?php echo $item['name']; ?> <?php echo $sayquantity; ?> <?php echo JText::translate('VRFOR'); ?> <?php echo $timeslot['tname']; ?></h4>
		<?php
	} else {
		if (array_key_exists('hours', $tars[0])) {
		?>
		<h4 class="vri-medium-header vri-header-attract"><?php echo JText::translate('VRRENTAL'); ?> <?php echo $item['name']; ?> <?php echo $sayquantity; ?> <?php echo JText::translate('VRFOR'); ?> <?php echo (intval($tars[0]['hours']) == 1 ? "1 ".JText::translate('VRIHOUR') : $tars[0]['hours']." ".JText::translate('VRIHOURS')); ?></h4>
		<?php
		} else {
		?>
		<h4 class="vri-medium-header vri-header-attract"><?php echo JText::translate('VRRENTAL'); ?> <?php echo $item['name']; ?> <?php echo $sayquantity; ?> <?php echo JText::translate('VRFOR'); ?> <?php echo (intval($tars[0]['days']) == 1 ? "1 ".JText::translate('VRDAY') : $tars[0]['days']." ".JText::translate('VRDAYS')); ?></h4>
		<?php
		}
	}
	?>
		<div class="vri-showprc-groupleft">
			<div class="vri-showprc-imagesblock">
				<div class="vri-showprc-mainimage">
					<img src="<?php echo VRI_ADMIN_URI; ?>resources/<?php echo $item['img']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"/>
				</div>
			<?php
			if (strlen($item['moreimgs']) > 0) {
				$moreimages = explode(';;', $item['moreimgs']);
				?>
				<div class="vri-showprc-extraimages">
				<?php
				foreach ($moreimages as $mimg) {
					if (!empty($mimg)) {
					?>
					<a href="<?php echo VRI_ADMIN_URI; ?>resources/big_<?php echo $mimg; ?>" rel="vrigroup<?php echo $item['id']; ?>" target="_blank" class="vrimodal" data-fancybox="gallery"><img src="<?php echo VRI_ADMIN_URI; ?>resources/thumb_<?php echo $mimg; ?>" alt="<?php echo htmlspecialchars(substr($mimg, 0, ((int)strpos($mimg, '.') + 1))); ?>"/></a>
					<?php
					}
				}
				?>
				</div>
				<?php
			}
			?>
			</div>
		</div>


		<div class="vri-showprc-groupright">
			<div class="vri-showprc-descr">
<?php
if (!empty($item['info'])) {
	/**
	 * @wponly 	we try to parse any shortcode inside the HTML description of the room
	 */
	echo do_shortcode(wpautop($item['info']));
}
?>
			</div>
<?php
if (strlen($carats)) {
	?>
			<div class="vri-showprc-carats"><?php echo $carats; ?></div>
	<?php
}
if ($item['isgroup'] > 0 && count($this->kit_relations) > 0) {
	?>
			<div class="vri-showprc-kitrelations">
				<span class="vri-kit-expl"><?php echo JText::translate('VRIKITITEMSINCL'); ?></span>
				<table class="vri-kitrelations-tbl">
				<?php
				foreach ($this->kit_relations as $kitrel) {
					?>
					<tr>
						<td><a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=itemdetails&elemid='.$kitrel['childid'].(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>" target="_blank"><?php echo $kitrel['name']; ?></a></td>
						<td>x<?php echo $kitrel['units']; ?></td>
					</tr>
					<?php
				}
				?>
				</table>
			</div>
	<?php
}
?>
		</div>

	</div>
		
	<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems'); ?>" method="post">
		<div class="item_prices table-responsive">
			<h4 class="vri-medium-header"><?php echo JText::translate('VRPRICE'); ?></h4>
			<table class="table">
			<?php
			foreach ($tars as $k => $t) {
				// VRI 1.6 - if location fees and multiple units, the price must be adjusted as it's not "each unit"
				$has_locfees = false;
				if (isset($t['locfee']) && $t['locfee'] > 0 && $itemquant > 1) {
					$t['cost'] = $t['cost'] - $t['locfee'];
					$has_locfees = true;
				}
				//
				$has_promotion = array_key_exists('promotion', $t);
				?>
				<tr>
					<td class="vri-showprc-pricetbl-name">
						<label for="pid<?php echo $t['idprice']; ?>"<?php echo $has_promotion === true ? ' class="vri-label-promo-price"' : ''; ?>>
							<strong><?php echo VikRentItems::getPriceName($t['idprice'], $vri_tn); ?></strong>
						<?php
						if (strlen($t['attrdata'])) {
							?>
							<br/>
							<?php
							echo VikRentItems::getPriceAttr($t['idprice'], $vri_tn) . ": " . $t['attrdata'];
						}
						?>
						</label>
					</td>
					<td class="vri-showprc-pricetbl-cost">
						<label for="pid<?php echo $t['idprice']; ?>">
							<strong><?php echo $currencysymb . ' ' . VikRentItems::numberFormat(VikRentItems::sayCostPlusIva($t['cost'], $t['idprice'])); ?></strong>
						<?php
						if ($itemquant > 1) {
							?>
							<span class="vri-showprc-pricetbl-cost-info"><?php echo JText::translate('VRIEACHUNIT'); ?></span>
							<?php
						}
						?>
						</label>
					</td>
					<td class="vri-showprc-pricetbl-radio">
						<input type="radio" name="priceid" id="pid<?php echo $t['idprice']; ?>" value="<?php echo $t['idprice']; ?>"<?php echo ($k == 0 ? " checked=\"checked\"" : ""); ?>/>
					</td>
				</tr>
			<?php
				if ($has_locfees) {
					?>
				<tr class="vri-showprc-item-pickdropfees">
					<td class="vri-showprc-pricetbl-sub-name">
						<label for="pid<?php echo $t['idprice']; ?>"><?php echo JText::translate('VRLOCFEETOPAY'); ?></label>
					</td>
					<td class="vri-showprc-pricetbl-sub-cost">
						<label for="pid<?php echo $t['idprice']; ?>">
							<strong><?php echo $currencysymb . ' ' . VikRentItems::numberFormat($t['locfee']); ?></strong>
						</label>
					</td>
					<td>&nbsp;</td>
				</tr>
					<?php
				}
			}
			?>
			</table>
			<?php
			//BEGIN: Item Specifications
			if (!empty($item['idopt']) && is_array($optionals)) {
				list($optionals, $specifications) = VikRentItems::loadOptionSpecifications($optionals);
				if (is_array($specifications) && count($specifications) > 0) {
					?>
				<div class="vrispecifications">
					<?php
					foreach ($specifications as $specification) {
						$specselect = '<select name="optid'.$specification['id'].'">'."\n";
						$intervals = explode(';;', $specification['specifications']);
						foreach ($intervals as $kintv => $intv) {
							if (empty($intv)) continue;
							$intvparts = explode('_', $intv);
							$intvparts[1] = intval($specification['perday']) == 1 ? ($intvparts[1] * $tars[0]['days']) : $intvparts[1];
							if (!empty($specification['maxprice']) && $specification['maxprice'] > 0 && $intvparts[1] > $specification['maxprice']) {
								$intvparts[1] = $specification['maxprice'];
							}
							$intvparts[1] = VikRentItems::sayOptionalsPlusIva($intvparts[1], $specification['idiva']);
							$pricestr = floatval($intvparts[1]) >= 0 ? '+ '.$currencysymb.''.VikRentItems::numberFormat($intvparts[1]) : '- '.$currencysymb.''.VikRentItems::numberFormat($intvparts[1]);
							$specselect .= '<option value="'.($kintv + 1).'">'.$intvparts[0].(VikRentItems::numberFormat(($intvparts[1] * 1)) != '0.00' ? ' ('.$pricestr.')' : '').'</option>'."\n";
						}
						$specselect .= '</select>'."\n";
					?>
					<div class="vrispecificationopt">
						<?php
						if (strlen($specification['descr']) > 0) {
							echo $specification['descr'];
						}
						?>
						<span class="vrispecificationoptname"><?php echo $specification['name']; ?></span>
						<span class="vrispecificationoptselect"><?php echo $specselect; ?></span>
					</div>
					<?php
					}
					?>
				</div>
					<?php
				}
			}
			//END: Item Specifications
			?>
		</div>
		
		<?php
		//check options to be applied only-once
		if (count($this->vrisessioncart) && !empty($item['idopt']) && is_array($optionals)) {
			foreach ($optionals as $k => $o) {
				if ($o['onlyonce'] > 0) {
					//check if already in the cart
					foreach ($this->vrisessioncart as $items) {
						foreach ($items as $cartitem) {
							if (isset($cartitem['options']) && is_array($cartitem['options'])) {
								foreach ($cartitem['options'] as $cartitemopt) {
									if ($cartitemopt['id'] == $o['id']) {
										unset($optionals[$k]);
										break 3;
									}
								}
							}
						}
					}
				}
			}
			if (!count($optionals)) {
				$optionals = "";
			}
		}
		//
		if (!empty($item['idopt']) && is_array($optionals)) {
		?>
		<div class="item_options table-responsive">
			<h4 class="vri-medium-header"><?php echo JText::translate('VRACCOPZ'); ?></h4>
			<table class="table">
			<?php
			foreach ($optionals as $k => $o) {
				$optcost = intval($o['perday']) == 1 ? ($o['cost'] * $tars[0]['days']) : $o['cost'];
				if (!empty($o['maxprice']) && $o['maxprice'] > 0 && $optcost > $o['maxprice']) {
					$optcost = $o['maxprice'];
				}
				$optcost = $optcost * 1;
				//vikrentitems 1.1
				if (intval($o['forcesel']) == 1) {
					//VRI 1.1 Rev.2
					if ((int)$tars[0]['days'] > (int)$o['forceifdays']) {
						$forcedquan = 1;
						$forceperday = false;
						if (strlen($o['forceval']) > 0) {
							$forceparts = explode("-", $o['forceval']);
							$forcedquan = intval($forceparts[0]);
							$forceperday = intval($forceparts[1]) == 1 ? true : false;
						}
						$setoptquan = $forceperday == true ? $forcedquan * $tars[0]['days'] : $forcedquan;
						if (intval($o['hmany']) == 1) {
							$optquaninp = "<input type=\"hidden\" name=\"optid".$o['id']."\" value=\"".$setoptquan."\"/><span class=\"vrioptionforcequant\"><small>x</small> ".$setoptquan."</span>";
						} else {
							$optquaninp = "<input type=\"hidden\" name=\"optid".$o['id']."\" value=\"".$setoptquan."\"/><span class=\"vrioptionforcequant\"><small>x</small> ".$setoptquan."</span>";
						}
					} else {
						continue;
					}
					//
				} else {
					if (intval($o['hmany']) == 1) {
						$optquaninp = "<input type=\"number\" min=\"0\" name=\"optid".$o['id']."\" value=\"0\" size=\"3\" class=\"vri-inp-numb\"/>";
					} else {
						$optquaninp = "<input type=\"checkbox\" name=\"optid".$o['id']."\" value=\"1\"/>";
					}
				}
				//
				?>
				<tr>
					<td class="vri-showprc-opttbl-name">
					<?php
					if (!empty($o['img'])) {
						?>
						<div class="vri-showprc-opt-img-wrap">
							<img class="maxthirty" src="<?php echo VRI_ADMIN_URI; ?>resources/<?php echo $o['img']; ?>" />
						</div>
						<?php
					}
					?>
						<strong><?php echo $o['name']; ?></strong>
					<?php
					if (strlen(strip_tags(trim($o['descr'])))) {
						?>
						<div class="vrioptionaldescr"><?php echo $o['descr']; ?></div>
						<?php
					}
					?>
					</td>
					<td class="vri-showprc-opttbl-price">
						<strong><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat(VikRentItems::sayOptionalsPlusIva($optcost, $o['idiva'])); ?></strong> 
						<?php echo ($itemquant > 1 && !$o['onceperitem'] ? JText::translate('VRIEACHUNIT') : ''); ?>
					</td>
					<td class="vri-showprc-opttbl-input">
						<?php echo $optquaninp; ?>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
		</div>
		<?php
		}
		//VikRent Items 1.2 - Delivery Service
		$baseaddress = VikRentItems::getDeliveryBaseAddress();
		$prevdelivery = is_array($lastdelivery) && count($lastdelivery) > 0 ? true : false;
		if (intval(VikRentItems::getItemParam($item['params'], 'delivery')) == 1 && !empty($baseaddress)) {
			$currentUser = JFactory::getUser();
			$previousdata = VikRentItems::loadPreviousUserData($currentUser->id);
			$overcostperunit = floatval(VikRentItems::getItemParam($item['params'], 'overdelcost'));
			if ($prevdelivery === true && !empty($overcostperunit) && $overcostperunit > 0.00) {
				$lastdelivery['vrideliverycost'] = $lastdelivery['vrideliverydistance'] * $overcostperunit;
				if ($lastdelivery['vrideliveryroundcost'] == 1) {
					$lastdelivery['vrideliverycost'] = round($lastdelivery['vrideliverycost']);
				}
				if (!empty($lastdelivery['vrideliverymaxcost']) && (float)$lastdelivery['vrideliverymaxcost'] > 0 && $lastdelivery['vrideliverycost'] > (float)$lastdelivery['vrideliverymaxcost']) {
					$lastdelivery['vrideliverycost'] = (float)$lastdelivery['vrideliverymaxcost'];
				}
				// VRI 1.6 - Delivery per Item Unit (Quantity)
				if (VikRentItems::isDeliveryPerItemUnit()) {
					$lastdelivery['vrideliverycost'] = $lastdelivery['vrideliverycost'] * $itemquant;
				}
				//
			} elseif ($prevdelivery === true && (int)$lastdelivery['vrideliveryelemid'] != (int)$item['id']) {
				$lastdelivery['vrideliverycost'] = $lastdelivery['vrideliverydistance'] * $lastdelivery['vrideliveryglobcostperunit'];
				if ($lastdelivery['vrideliveryroundcost'] == 1) {
					$lastdelivery['vrideliverycost'] = round($lastdelivery['vrideliverycost']);
				}
				if (!empty($lastdelivery['vrideliverymaxcost']) && (float)$lastdelivery['vrideliverymaxcost'] > 0 && $lastdelivery['vrideliverycost'] > (float)$lastdelivery['vrideliverymaxcost']) {
					$lastdelivery['vrideliverycost'] = (float)$lastdelivery['vrideliverymaxcost'];
				}
				// VRI 1.6 - Delivery per Item Unit (Quantity)
				if (VikRentItems::isDeliveryPerItemUnit()) {
					$lastdelivery['vrideliverycost'] = $lastdelivery['vrideliverycost'] * $itemquant;
				}
				//
			}
			?>
		<div class="item_delivery">
			<h4 class="vri-medium-header"><?php echo JText::translate('VRIDELIVERYSERVICETITLE'); ?></h4>
			<div class="vrideliveryidlike">
				<label for="vridelidlk"><?php echo JText::translate('VRIDELIVERYIDLIKE'); ?></label> <input type="checkbox" name="delivery" id="vridelidlk" value="1"/>
			</div>
			<div class="vrideliverycont" id="vrideliverycont">
				<div>
					<label><?php echo JText::translate('VRIDELIVERYADDRESS'); ?></label>
					<span id="vrideliveryaddress"><?php echo ($prevdelivery === true ? $lastdelivery['vrideliveryaddress'] : ''); ?></span>
				<?php
				if (!$prevdelivery || ($prevdelivery && !VikRentItems::isDeliveryPerOrder())) {
					// VRI 1.6 - Change address only allowed if delivery set and cost is per item, not per order
				?>
					<a class="btn vrichangedeliveryaddress" id="vrichangedeliveryaddress"><i class="fas fa-map-marker"></i> <?php echo JText::translate('VRIDELIVERYADDRESSCHANGE'); ?></a>
				<?php
				}
				?>
				</div>
				<div>
					<label><?php echo JText::translate('VRIDELIVERYDISTANCE'); ?></label>
					<span id="vrideliverydistance"><?php echo ($prevdelivery === true ? $lastdelivery['vrideliverydistance'].' '.$lastdelivery['vrideliverydistanceunit'] : ''); ?></span>
				</div>
				<?php
				if (!$prevdelivery || ($prevdelivery && !VikRentItems::isDeliveryPerOrder())) {
					// VRI 1.6 - Show the delivery cost only if no items with delivery already in the cart when charge once per order
				?>
				<div>
					<label><?php echo JText::translate('VRIDELIVERYCOST'); ?></label>
					<span id="vrideliverycost"><?php echo ($prevdelivery === true ? $currencysymb.' '.$lastdelivery['vrideliverycost'] : ''); ?></span>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		<input type="hidden" name="deliveryaddress" id="deliveryaddressinp" value="<?php echo ($prevdelivery === true ? $lastdelivery['vrideliveryaddress'] : (count($previousdata) > 0 && array_key_exists('delivery', $previousdata) ? $previousdata['delivery']['vrideliveryaddress'] : '')); ?>"/>
		<input type="hidden" name="deliverydistance" id="deliverydistanceinp" value="<?php echo ($prevdelivery === true ? $lastdelivery['vrideliverydistance'] : ''); ?>"/>
		<input type="hidden" name="deliverysessionval" id="deliverysessionval" value="<?php echo ($prevdelivery === true ? $lastdelivery['vrideliverysessid'] : ''); ?>"/>
		<script type="text/javascript">
		var actdelivaddr = jQuery("#deliveryaddressinp").val();
		function vriShowDeliveryMap() {
			/**
			 * @wponly 	we should not use &tmpl=component for the modal window, or some scripts may be removed from the DOM
			 */
			var baseaddrmaplink = "<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=deliverymap&elemid='.$item['id'], false); ?>";
			jQuery.fancybox.open({
				beforeLoad: function(instance, current) {
					current.src = baseaddrmaplink+(baseaddrmaplink.indexOf('?') > 0 ? '&' : '?')+"delto="+jQuery("#deliveryaddressinp").val()+"&itemquant="+jQuery("#itemquant").val();
				},
				type: "iframe",
				iframe: {
					css: {
						width: "100%",
						height: "500px"
					}
				}
			});
		}
		jQuery(document).ready(function() {
			jQuery("#vridelidlk").change(function() {
				if (jQuery(this).is(":checked")) {
					if (jQuery("#deliveryaddressinp").val().length > 0 && jQuery("#deliverysessionval").val().length > 0) {
						jQuery(".vrideliverycont").fadeIn();
					} else {
						vriShowDeliveryMap();
					}
				} else {
					jQuery(".vrideliverycont").hide();
				}
			});
			jQuery("#vrichangedeliveryaddress").click(function() {
				vriShowDeliveryMap();
			});
		});
		</script>
			<?php
		}
		//VikRent Items 1.2 - Delivery Service
		?>
		<input type="hidden" name="place" value="<?php echo $place; ?>"/>
		<input type="hidden" name="returnplace" value="<?php echo $preturnplace; ?>"/>
		<input type="hidden" name="elemid" value="<?php echo $item['id']; ?>"/>
  		<input type="hidden" name="days" value="<?php echo $tars[0]['days']; ?>"/>
  		<input type="hidden" name="pickup" value="<?php echo $pickup; ?>"/>
  		<input type="hidden" name="release" value="<?php echo $release; ?>"/>
  		<input type="hidden" name="itemquant" id="itemquant" value="<?php echo $itemquant; ?>"/>
  		<input type="hidden" name="task" value="oconfirm"/>
  		<?php
		if (is_array($timeslot) && count($timeslot) > 0) {
			?>
			<input type="hidden" name="timeslot" value="<?php echo $timeslot['id']; ?>"/>
			<?php
		}
		if (!empty($pitemid)) {
			?>
			<input type="hidden" name="Itemid" value="<?php echo $pitemid; ?>"/>
			<?php
		}
		?>
		<br clear="all">
  		<?php
  		if (strlen($discl)) { 
			?>	
			<div class="item_disclaimer"><?php echo $discl; ?></div>
			<?php
  		}
		?>
		
		<div class="item_buttons_box">
			<button type="submit" class="btn booknow"><?php echo JText::translate('VRBOOKNOW'); ?></button>
			<div class="goback">
				<a href="javascript: void(0);" onclick="javascript: window.history.back();"><?php echo JText::translate('VRBACK'); ?></a>
			</div>
		</div>
		
	</form>
</div>