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

$res = $this->res;
$days = $this->days;
$hours = $this->hours;
$pickup = $this->pickup;
$release = $this->release;
$place = $this->place;
$navig = $this->navig;
$timeslot = $this->timeslot;
$vri_tn = $this->vri_tn;

$document = JFactory::getDocument();
//load jQuery lib e jQuery UI
if (VikRentItems::loadJquery()) {
	JHtml::fetch('jquery.framework', true, true);
	JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-1.12.4.min.js');
}
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
//load jQuery UI
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.min.js');

$dbo = JFactory::getDbo();
$currencysymb = VikRentItems::getCurrencySymb();
$returnplace = VikRequest::getInt('returnplace', '', 'request');
$pitemid = VikRequest::getInt('Itemid', '', 'request');
$vridateformat = VikRentItems::getDateFormat();
$nowtf = VikRentItems::getTimeFormat();
if ($vridateformat == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($vridateformat == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}

/**
 * We take from the cookie the default layout type (list or grid).
 * If empty and more than 6 results, grid is used by default.
 * If not more than 3 results, list layout is forced by default.
 * 
 * @since 	1.7
 */
$cookie = JFactory::getApplication()->input->cookie;
$cookie_slayout = $cookie->get('vriSearchLayout', 'list', 'string');
$cookie_slayout = empty($cookie_slayout) && count($res) > 6 ? 'grid' : $cookie_slayout;
$cookie_slayout = empty($cookie_slayout) ? 'list' : $cookie_slayout;
$cookie_slayout = $cookie_slayout != 'list' && !(count($res) > 3) ? 'list' : $cookie_slayout;
?>

<div class="vri-page-content">
<?php

// itinerary
$pickloc = VikRentItems::getPlaceInfo($place, $vri_tn);
$droploc = VikRentItems::getPlaceInfo($returnplace, $vri_tn);
?>
	<div class="vri-itinerary-summary">
		<div class="vri-itinerary-pickup">
			<h4><?php echo JText::translate('VRPICKUP'); ?></h4>
		<?php
		if (count($pickloc)) {
			?>
			<div class="vri-itinerary-pickup-location">
				<i class="fa fa-location-arrow"></i>
				<div class="vri-itinerary-pickup-locdet">
					<span class="vri-itinerary-pickup-locname"><?php echo $pickloc['name']; ?></span>
				</div>
			</div>
			<?php
		}
		?>
			<div class="vri-itinerary-pickup-date">
				<i class="fas fa-calendar-alt"></i>
				<span class="vri-itinerary-pickup-date-day"><?php echo date($df, $pickup); ?></span>
				<span class="vri-itinerary-pickup-date-time"><?php echo date($nowtf, $pickup); ?></span>
			</div>
		</div>
		<div class="vri-itinerary-dropoff">
			<h4><?php echo JText::translate('VRRETURN'); ?></h4>
		<?php
		if (count($droploc)) {
			?>
			<div class="vri-itinerary-dropoff-location">
				<i class="fa fa-location-arrow"></i>
				<div class="vri-itinerary-dropfff-locdet">
					<span class="vri-itinerary-dropoff-locname"><?php echo $droploc['name']; ?></span>
				</div>
			</div>
			<?php
		}
		?>
			<div class="vri-itinerary-dropoff-date">
				<i class="fas fa-calendar-alt"></i>
				<span class="vri-itinerary-dropoff-date-day"><?php echo date($df, $release); ?></span>
				<span class="vri-itinerary-dropoff-date-time"><?php echo date($nowtf, $release); ?></span>
				<span class="vri-itinerary-duration"><?php echo $hours > 0 ? ($hours . ' ' . strtolower(JText::translate(($hours > 1 ? 'VRIHOURS' : 'VRIHOUR')))) : ($days . ' ' . strtolower(JText::translate(($days > 1 ? 'VRDAYS' : 'VRDAY')))); ?></span>
			</div>
		</div>
	</div>

	<div class="vri-search-results-top">
		<div class="vri-search-results-top-inner">
			<h3 class="vri-big-header"><?php echo JText::translate('VRIARSFND'); ?>: <?php echo $this->tot_res; ?></h3>
		</div>
	<?php
	if (count($res) > 3) {
		/**
		 * We display the buttons to switch from the list to grid layouts
		 * when there are at least 4 items found.
		 * 
		 * @since 	1.7
		 */
		?>
		<div class="vri-search-results-top-outer">
			<div class="vri-search-results-gridorlist">
				<span class="vri-search-results-chlayout vri-search-results-set-list<?php echo $cookie_slayout == 'list' ? ' vri-search-results-chlayout-active' : ''; ?>"><?php VikRentItemsIcons::e('th-list'); ?></span>
				<span class="vri-search-results-chlayout vri-search-results-set-grid<?php echo $cookie_slayout == 'grid' ? ' vri-search-results-chlayout-active' : ''; ?>"><?php VikRentItemsIcons::e('th'); ?></span>
			</div>
		</div>
		<?php
	}
	?>
	</div>

	<div class="vri-search-results-container<?php echo $cookie_slayout == 'grid' ? ' vri-search-results-container-grid' : ''; ?>">
<?php
foreach ($res as $k => $r) {
	$getitem = VikRentItems::getItemInfo($k, $vri_tn);
	$item_params = !empty($getitem['jsparams']) ? json_decode($getitem['jsparams'], true) : array();
	$carats = VikRentItems::getItemCaratOriz($getitem['idcarat'], $vri_tn);
	$imgpath = is_file(VRI_ADMIN_PATH.DS.'resources'.DS.'vthumb_'.$getitem['img']) ? VRI_ADMIN_URI.'resources/vthumb_'.$getitem['img'] : VRI_ADMIN_URI.'resources/'.$getitem['img'];
	$vcategory = VikRentItems::sayCategory($getitem['idcat'], $vri_tn);
	$has_promotion = array_key_exists('promotion', $r[0]);
	$item_cost = VikRentItems::sayCostPlusIva($r[0]['cost'], $r[0]['idprice']);
	$discounts = array();
	if ($getitem['askquantity'] == 1 && intval(VikRentItems::getItemParam($getitem['params'], 'discsquantstab')) == 1) {
		$q = "SELECT * FROM `#__vikrentitems_discountsquants` WHERE `iditems` LIKE '%-".$getitem['id']."-%' ORDER BY `#__vikrentitems_discountsquants`.`quantity` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$discounts = $dbo->loadAssocList();
		}
	}
	$res_extra_classes = array();
	if ($getitem['isgroup'] > 0) {
		array_push($res_extra_classes, 'vri-result-item-kit');
	}
	if ($cookie_slayout == 'grid') {
		array_push($res_extra_classes, 'vri-search-result-block-grid');
	}
	?>
		<div class="vri-search-result-block<?php echo count($res_extra_classes) ? (' ' . implode(' ', $res_extra_classes)) : ''; ?>">
			<?php
			/**
			 * @wponly 	if the Itemid is missing, maybe because of a redirect, then using JRoute::rewrite('index.php?option=com_vikrentitems')
			 * 			generated an empty URL (the home page), by losing the navigation and by rendering an invalid page.
			 * 			So we need to use JRoute::rewrite('index.php?option=com_vikrentitems&view=vikrentitems') like the link above.
			 * 			Also, the method of the form has to be POST.
			 */
			?>
			<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=vikrentitems'.(!empty($pitemid) ? '&Itemid='.$pitemid : '')); ?>" method="post">
				<input type="hidden" name="option" value="com_vikrentitems"/>
				<input type="hidden" name="itemopt" value="<?php echo $k; ?>"/>
				<input type="hidden" name="days" value="<?php echo $days; ?>"/>
				<input type="hidden" name="pickup" value="<?php echo $pickup; ?>"/>
				<input type="hidden" name="release" value="<?php echo $release; ?>"/>
				<input type="hidden" name="place" value="<?php echo $place; ?>"/>
				<input type="hidden" name="returnplace" value="<?php echo $returnplace; ?>"/>
				<input type="hidden" name="task" value="showprc"/>
			<?php
			if (is_array($timeslot) && count($timeslot) > 0) {
				?>
				<input type="hidden" name="timeslot" value="<?php echo $timeslot['id']; ?>"/>
				<?php
			}
			?>
				<div class="vri-result-item-inner">
				<?php
				if (!empty($getitem['img'])) {
				?>
					<div class="vri-result-item-img">
						<img src="<?php echo $imgpath; ?>" alt="<?php $getitem['name']; ?>"/>
					</div>
				<?php
				}
				?>
					<div class="vri-result-item-details">
						<div class="vri-result-item-descr">
							<span class="vrilistitemname"><?php echo $getitem['name']; ?></span>
						<?php
						if (strlen($vcategory) > 0) {
							?>
							<span class="vrilistitemcat"><?php echo $vcategory; ?></span>
							<?php
						}
						?>
							<div class="vri-result-itemdescr"><?php echo $getitem['shortdesc']; ?></div>
						</div>
						<div class="vri-result-item-caratdisc">
						<?php
						if (!empty($carats)) {
							?>
							<div class="vri-result-itemcarats"><?php echo $carats; ?></div>
							<?php
						}
						if ($has_promotion === true && !empty($r[0]['promotion']['promotxt'])) {
							?>
							<div class="vri-promotion-block">
								<div class="vri-promotion-icon"><?php VikRentItemsIcons::e('percentage'); ?></div>
								<div class="vri-promotion-description">
									<?php echo $r[0]['promotion']['promotxt']; ?>
								</div>
							</div>
							<?php
						}
						if ($getitem['askquantity'] == 1 && intval(VikRentItems::getItemParam($getitem['params'], 'discsquantstab')) == 1 && count($discounts) > 0) {
							?>
							<div class="vri-result-itemdiscquants-container">
								<div class="vri-result-itemdiscquants-inner">
									<div class="vri-result-itemdiscquants-firstrow">
										<div class="vri-result-itemdiscquants-firstrow-left"><?php echo JText::translate('VRIDISCSQUANTSQ'); ?></div>
										<div class="vri-result-itemdiscquants-firstrow-right"><?php echo JText::translate('VRIDISCSQUANTSSAVE'); ?></div>
									</div>
									<div class="vri-result-itemdiscquants-listrows">
								<?php
								foreach ($discounts as $kd => $disc) {
									$discval = substr($disc['diffcost'], -2) == '00' ? number_format($disc['diffcost'], 0) : VikRentItems::numberFormat($disc['diffcost']);
									$savedisc = $disc['val_pcent'] == 1 ? $currencysymb.' '.$discval : $discval.'%';
									$disc_keys = array_keys($discounts);
									?>
										<div class="vri-result-itemdiscquants-row">
											<div class="vri-result-itemdiscquants-row-left"><?php echo $disc['quantity'].(end($disc_keys) == $kd && $disc['ifmorequant'] == 1 ? ' '.JText::translate('VRIDISCSQUANTSORMORE') : ''); ?></div>
											<div class="vri-result-itemdiscquants-row-right"><?php echo $savedisc; ?></div>
										</div>
									<?php
								}
								?>
									</div>
								</div>
							</div>
						<?php
						}
						?>
						</div>
					</div>
				</div>
				<div class="vri-result-item-cont">
					<div class="vri-result-costdivcont">
						<div class="vri-result-cost-wrap">
							<div class="vri-result-divcost<?php echo $has_promotion === true ? ' vri-promotion-price' : ''; ?>">
								<span class="vriliststartfrom"><?php echo JText::translate('VRSTARTFROM'); ?></span>
								<span class="item_cost"><?php echo $currencysymb; ?> <?php echo VikRentItems::numberFormat($item_cost); ?></span>
							</div>
						<?php
						if (isset($r[0]['promotion']) && isset($r[0]['promotion']['discount'])) {
							if ($r[0]['promotion']['discount']['pcent']) {
								/**
								 * Do not make an upper-cent operation, but rather calculate the original price proportionally:
								 * final price : (100 - discount amount) = x : 100
								 * 
								 * @since 	1.7
								 */
								$prev_amount = $item_cost * 100 / (100 - $r[0]['promotion']['discount']['amount']);
							} else {
								$prev_amount = $item_cost + $r[0]['promotion']['discount']['amount'];
							}
							if ($prev_amount > 0) {
								?>
							<div class="vri-item-result-price-before-discount">
								<span class="item_cost">
									<span class="vri_currency"><?php echo $currencysymb; ?></span> 
									<span class="vri_price"><?php echo VikRentItems::numberFormat($prev_amount); ?></span>
								</span>
							</div>
								<?php
								if ($r[0]['promotion']['discount']['pcent']) {
									// hide by default the DIV containing the percent of discount
									?>
							<div class="vri-item-result-price-before-discount-percent" style="display: none;">
								<span class="item_cost">
									<span><?php echo '-' . (float)$r[0]['promotion']['discount']['amount'] . ' %'; ?></span>
								</span>
							</div>
									<?php
								}
							}
						}
						?>
						</div>
					<?php
					if ($getitem['askquantity'] == 1) {
						?>
						<div class="vri-search-selectquantity">
							<label for="itemquant-<?php echo $k; ?>"><?php echo JText::translate('VRIQUANTITYX'); ?></label>
							<input type="number" name="itemquant" id="itemquant-<?php echo $k; ?>" value="<?php echo (!array_key_exists('minquant', $item_params) || empty($item_params['minquant']) ? '1' : (int)$item_params['minquant']); ?>" min="<?php echo (!array_key_exists('minquant', $item_params) || empty($item_params['minquant']) ? '1' : (int)$item_params['minquant']); ?>" max="<?php echo $getitem['units']; ?>" class="vri-numbinput"/>
						</div>
						<?php
					}
					?>
						<div class="vri-search-subdiv">
							<button type="submit" class="btn vricontinue"><?php echo JText::translate('VRPROSEGUI'); ?></button>
						</div>
					</div>
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
?>
	</div>
<?php
//pagination
if (strlen($navig) > 0) {
	?>
	<div class="vri-pagination"><?php echo $navig; ?></div>
	<?php
}
?>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.vri-search-results-chlayout').click(function() {
		var vri_search_layout = 'list';
		if (jQuery(this).hasClass('vri-search-results-set-list')) {
			jQuery('.vri-search-result-block').removeClass('vri-search-result-block-grid');
			jQuery('.vri-search-results-container').removeClass('vri-search-results-container-grid');
			jQuery('.vri-search-results-set-grid').removeClass('vri-search-results-chlayout-active');
			jQuery('.vri-search-results-set-list').addClass('vri-search-results-chlayout-active');
		} else {
			jQuery('.vri-search-result-block').addClass('vri-search-result-block-grid');
			jQuery('.vri-search-results-container').addClass('vri-search-results-container-grid');
			jQuery('.vri-search-results-set-list').removeClass('vri-search-results-chlayout-active');
			jQuery('.vri-search-results-set-grid').addClass('vri-search-results-chlayout-active');
			vri_search_layout = 'grid';
		}
		var nd = new Date();
		nd.setTime(nd.getTime() + (365*24*60*60*1000));
		document.cookie = "vriSearchLayout="+vri_search_layout+"; expires=" + nd.toUTCString() + "; path=/; SameSite=Lax";
	});
});
</script>
