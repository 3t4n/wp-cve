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

$all_items = $this->all_items;
$itemrows = $this->itemrows;
$seasoncal_days = $this->seasons_cal_days;
$seasons_cal = $this->seasons_cal;
$tsstart = $this->tsstart;
$itemrates = $this->itemrates;
$booked_dates = $this->booked_dates;

$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();
$pdebug = VikRequest::getInt('e4j_debug', '', 'request');
$document = JFactory::getDocument();
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
JHtml::fetch('jquery.framework', true, true);
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.min.js');
$currencysymb = VikRentItems::getCurrencySymb();
$vri_df = VikRentItems::getDateFormat();
$df = $vri_df == "%d/%m/%Y" ? 'd/m/Y' : ($vri_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
$juidf = $vri_df == "%d/%m/%Y" ? 'dd/mm/yy' : ($vri_df == "%m/%d/%Y" ? 'mm/dd/yy' : 'yy/mm/dd');
$ldecl = '
jQuery(function($){'."\n".'
	$.datepicker.regional["vikrentitems"] = {'."\n".'
		closeText: "'.JText::translate('VRIJQCALDONE').'",'."\n".'
		prevText: "'.JText::translate('VRIJQCALPREV').'",'."\n".'
		nextText: "'.JText::translate('VRIJQCALNEXT').'",'."\n".'
		currentText: "'.JText::translate('VRIJQCALTODAY').'",'."\n".'
		monthNames: ["'.JText::translate('VRMONTHONE').'","'.JText::translate('VRMONTHTWO').'","'.JText::translate('VRMONTHTHREE').'","'.JText::translate('VRMONTHFOUR').'","'.JText::translate('VRMONTHFIVE').'","'.JText::translate('VRMONTHSIX').'","'.JText::translate('VRMONTHSEVEN').'","'.JText::translate('VRMONTHEIGHT').'","'.JText::translate('VRMONTHNINE').'","'.JText::translate('VRMONTHTEN').'","'.JText::translate('VRMONTHELEVEN').'","'.JText::translate('VRMONTHTWELVE').'"],'."\n".'
		monthNamesShort: ["'.mb_substr(JText::translate('VRMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWELVE'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNames: ["'.JText::translate('VRISUNDAY').'", "'.JText::translate('VRIMONDAY').'", "'.JText::translate('VRITUESDAY').'", "'.JText::translate('VRIWEDNESDAY').'", "'.JText::translate('VRITHURSDAY').'", "'.JText::translate('VRIFRIDAY').'", "'.JText::translate('VRISATURDAY').'"],'."\n".'
		dayNamesShort: ["'.mb_substr(JText::translate('VRISUNDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIMONDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITUESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIWEDNESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITHURSDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIFRIDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRISATURDAY'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNamesMin: ["'.mb_substr(JText::translate('VRISUNDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIMONDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRITUESDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIWEDNESDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRITHURSDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIFRIDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRISATURDAY'), 0, 2, 'UTF-8').'"],'."\n".'
		weekHeader: "'.JText::translate('VRIJQCALWKHEADER').'",'."\n".'
		dateFormat: "'.$juidf.'",'."\n".'
		firstDay: '.VikRentItems::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikrentitems"]);'."\n".'
});
var vriMapWdays = ["'.mb_substr(JText::translate('VRISUNDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIMONDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITUESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIWEDNESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITHURSDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIFRIDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRISATURDAY'), 0, 3, 'UTF-8').'"];
var vriMapMons = ["'.mb_substr(JText::translate('VRMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWELVE'), 0, 3, 'UTF-8').'"];';
$document->addScriptDeclaration($ldecl);
$price_types_show = true;
$los_show = true;
$cookie = JFactory::getApplication()->input->cookie;
$cookie_tab = $cookie->get('vriRovwRab', 'cal', 'string');
?>
<div class="vri-ratesoverview-top-container">
	<div class="vri-ratesoverview-itemsel-block">
		<form method="get" action="index.php?option=com_vikrentitems" name="vriratesovwform">
			<input type="hidden" name="option" value="com_vikrentitems" />
			<input type="hidden" name="task" value="ratesoverv" />
			<div class="vri-ratesoverview-itemsel-entry vri-ratesoverview-itemsel-entry-chitems">
				<label for="itemsel"><?php echo JText::translate('VRIRATESOVWITEM'); ?></label>
				<select name="cid[]" <?php echo count($all_items) > 1 ? 'multiple="multiple"' : 'onchange="document.vriratesovwform.submit();"' ?> id="itemsel" style="min-width: 160px; max-width: 250px;">
				<?php
				foreach ($all_items as $item) {
					?>
					<option value="<?php echo $item['id']; ?>"<?php echo in_array($item['id'], $this->req_item_ids) ? ' selected="selected"' : ''; ?>><?php echo $item['name']; ?></option>
					<?php
				}
				?>
				</select>
				<button type="button" class="btn vri-config-btn" onclick="document.vriratesovwform.submit();"><i class="vriicn-loop2"></i></button>
			</div>
			<div class="vri-ratesoverview-itemsel-entry vri-ratesoverview-itemsel-entry-calc">
				<div class="vri-ratesoverview-itemsel-entry-calc-inner">
					<label for="itemselcalc"><?php echo JText::translate('VRIRATESOVWRATESCALCULATOR'); ?></label>
					<span class="vri-ratesoverview-entryinline vri-ratesoverview-rcalc-sitems">
						<select name="itemselcalc" id="itemselcalc" style="max-width: 250px;">
						<?php
						foreach ($all_items as $item) {
							?>
							<option value="<?php echo $item['id']; ?>"<?php echo $item['id'] == $itemrows[$this->firstitem]['id'] ? ' selected="selected"' : ''; ?>><?php echo $item['name']; ?></option>
							<?php
						}
						?>
						</select>
					</span>
					<span class="vri-ratesoverview-entryinline">
						<?php echo $vri_app->getCalendar('', 'pickupdate', 'pickupdate', '%Y-%m-%d', array('class'=>'', 'size'=>'10', 'maxlength'=>'19', 'todayBtn' => 'true', 'placeholder'=>JText::translate('VRPICKUPAT'))); ?>
					</span>
					<span class="vri-ratesoverview-entryinline">
						<span><?php echo JText::translate('VRDAYS'); ?></span> <input type="number" id="vri-numdays" value="1" min="1" max="999" step="1" />
					</span>
					<span class="vri-ratesoverview-entryinline">
						<button type="button" class="btn vri-config-btn" id="vri-ratesoverview-calculate"><?php echo JText::translate('VRIRATESOVWRATESCALCULATORCALC'); ?></button>
					</span>
				</div>

				<div class="vri-ratesoverview-calculation-response"></div>

			</div>
			<div class="vri-ratesoverview-itemsel-entry vri-ratesoverview-itemsel-entry-los"<?php echo (!empty($cookie_tab) && $cookie_tab == 'cal' ? ' style="display: none;"' : ''); ?>>
				<label><?php echo JText::translate('VRIRATESOVWNUMNIGHTSACT'); ?></label>
			<?php
			foreach ($seasoncal_days as $numdays) {
				?>
				<span class="vri-ratesoverview-numday" id="numdays<?php echo $numdays; ?>"><?php echo $numdays; ?></span>
				<input type="hidden" name="days_cal[]" id="inpnumdays<?php echo $numdays; ?>" value="<?php echo $numdays; ?>" />
				<?php
			}
			?>
				<input type="number" id="vri-addnumnight" value="<?php echo ($numdays + 1); ?>" min="1"/>
				<span id="vri-addnumnight-act"><?php VikRentItemsIcons::e('plus-square'); ?></span>
				<button type="button" class="btn vri-config-btn vri-apply-los-btn" onclick="document.vriratesovwform.submit();"><?php echo JText::translate('VRIRATESOVWAPPLYLOS'); ?></button>
			</div>
		</form>
	</div>
	<div class="vri-ratesoverview-right-block">
		<div class="vri-ratesoverview-right-inner"></div>
	</div>
</div>

<div class="vri-ratesoverview-bottom-container">
	<?php
	foreach ($itemrows as $rid => $itemrow) {
		if (count($this->req_item_ids) < 2) {
			?>
	<div class="vri-ratesoverview-bottom-head">
		<div class="vri-ratesoverview-itemdetails">
			<h3 class="vri-ratesoverview-itemname"><?php echo $itemrow['name']; ?></h3>
		</div>
		<div class="vri-ratesoverview-tabscont">
			<div class="vri-ratesoverview-tab-cal <?php echo (!empty($cookie_tab) && $cookie_tab == 'cal' ? 'vri-ratesoverview-tab-active' : 'vri-ratesoverview-tab-unactive'); ?>"><i class="vriicn-calendar"></i> <?php echo JText::translate('VRIRATESOVWTABCALENDAR'); ?></div>
			<div class="vri-ratesoverview-tab-los <?php echo (!empty($cookie_tab) && $cookie_tab == 'cal' ? 'vri-ratesoverview-tab-unactive' : 'vri-ratesoverview-tab-active'); ?>"><i class="vriicn-clock"></i> <?php echo JText::translate('VRIRATESOVWTABLOS'); ?></div>
		</div>
	</div>
			<?php
		}
		?>

	<div class="vri-ratesoverview-caltab-cont" style="display: <?php echo count($this->req_item_ids) > 1 || (!empty($cookie_tab) && $cookie_tab == 'cal') ? 'block' : 'none'; ?>;">
		<?php
		if (count($this->req_item_ids) > 1) {
			// display item name here when multiple items
			?>
		<div class="vri-ratesoverview-itemdetails">
			<h3><?php VikRentItemsIcons::e('layer-group'); ?> <?php echo $itemrow['name']; ?></h3>
		</div>
			<?php
		}
		?>
		<div class="vri-ratesoverview-caltab-wrapper">
			<div class="vri-table-responsive">
				<table class="vriverviewtable vriratesoverviewtable vri-table" data-iditem="<?php echo $rid; ?>">
					<tbody>
						<tr class="vri-roverviewrowone">
							<td class="bluedays skip-bluedays-click">
								<form name="vriratesoverview" method="post" action="index.php?option=com_vikrentitems&amp;task=ratesoverv">
									<div class="vri-roverview-datecmd-top">
										<div class="vri-roverview-datecmd-date">
											<span>
												<?php VikRentItemsIcons::e('calendar'); ?>
												<input type="text" autocomplete="off" value="<?php echo date($df, $tsstart); ?>" class="vridatepicker" name="startdate" />
											</span>
										</div>
									</div>
								</form>
							</td>
						<?php
						$nowts = getdate($tsstart);
						$days_labels = array(
							JText::translate('VRSUN'),
							JText::translate('VRMON'),
							JText::translate('VRTUE'),
							JText::translate('VRWED'),
							JText::translate('VRTHU'),
							JText::translate('VRFRI'),
							JText::translate('VRSAT')
						);
						$long_days_labels = array(
							JText::translate('VRISUNDAY'),
							JText::translate('VRIMONDAY'),
							JText::translate('VRITUESDAY'),
							JText::translate('VRIWEDNESDAY'),
							JText::translate('VRITHURSDAY'),
							JText::translate('VRIFRIDAY'),
							JText::translate('VRISATURDAY')
						);
						$months_labels = array(
							JText::translate('VRMONTHONE'),
							JText::translate('VRMONTHTWO'),
							JText::translate('VRMONTHTHREE'),
							JText::translate('VRMONTHFOUR'),
							JText::translate('VRMONTHFIVE'),
							JText::translate('VRMONTHSIX'),
							JText::translate('VRMONTHSEVEN'),
							JText::translate('VRMONTHEIGHT'),
							JText::translate('VRMONTHNINE'),
							JText::translate('VRMONTHTEN'),
							JText::translate('VRMONTHELEVEN'),
							JText::translate('VRMONTHTWELVE')
						);
						$long_months_labels = $months_labels;
						foreach ($months_labels as $i => $v) {
							$months_labels[$i] = function_exists('mb_substr') ? mb_substr($v, 0, 3, 'UTF-8') : substr($v, 0, 3);
						}
						$cell_count = 0;
						$MAX_DAYS = 60;
						$pcheckinh = 0;
						$pcheckinm = 0;
						$pcheckouth = 0;
						$pcheckoutm = 0;
						$timeopst = VikRentItems::getTimeOpenStore();
						if (is_array($timeopst)) {
							$opent = VikRentItems::getHoursMinutes($timeopst[0]);
							$closet = VikRentItems::getHoursMinutes($timeopst[1]);
							$pcheckinh = $opent[0];
							$pcheckinm = $opent[1];
							// set default drop off time equal to pick up time to avoid getting extra days of rental
							$pcheckouth = $pcheckinh;
							$pcheckoutm = $pcheckinm;
						}
						$weekend_arr = array(0, 6);
						while ($cell_count < $MAX_DAYS) {
							$style = '';
							$curdayymd = date('Y-m-d', $nowts[0]);
							$read_day  = $days_labels[$nowts['wday']] . ' ' . $nowts['mday'] . ' ' . $months_labels[$nowts['mon']-1] . ' ' . $nowts['year'];
							?>
							<td data-ymd="<?php echo $curdayymd; ?>" data-readymd="<?php echo $read_day; ?>" class="bluedays <?php echo 'cell-'.$nowts['mday'].'-'.$nowts['mon']; ?><?php echo in_array((int)$nowts['wday'], $weekend_arr) ? ' vri-roverw-tablewday-wend' : ''; ?>" <?php echo $style; ?>>
								<span class="vri-roverw-tablewday"><?php echo $days_labels[$nowts['wday']]; ?></span>
								<span class="vri-roverw-tablemday"><?php echo $nowts['mday']; ?></span>
								<span class="vri-roverw-tablemonth"><?php echo $months_labels[$nowts['mon']-1]; ?></span>
							</td>
							<?php
							$next = $nowts['mday'] + 1;
							$dayts = mktime(0, 0, 0, $nowts['mon'], $next, $nowts['year']);
							$nowts = getdate($dayts);
							$cell_count++;
						}
						?>
						</tr>
					<?php
					$closed_itemrateplans = VikRentItems::getItemRplansClosingDates($itemrow['id']);
					foreach ($itemrates[$rid] as $itemrate) {
						$nowts = getdate($tsstart);
						$cell_count = 0;
						?>
						<tr class="vri-roverviewtablerow" id="vri-roverw-<?php echo $itemrate['id']; ?>">
							<td data-defrate="<?php echo $itemrate['cost']; ?>" data-itemname="<?php echo htmlspecialchars($itemrow['name']); ?>"><span class="vri-rplan-name"><?php echo $itemrate['name']; ?></span></td>
						<?php
						while ($cell_count < $MAX_DAYS) {
							$style = '';
							$dclass = "vri-roverw-rplan-on";
							if (count($closed_itemrateplans) > 0 && array_key_exists($itemrate['idprice'], $closed_itemrateplans) && in_array(date('Y-m-d', $nowts[0]), $closed_itemrateplans[$itemrate['idprice']])) {
								$dclass = "vri-roverw-rplan-off";
							}
							$id_block = "cell-".$nowts['mday'].'-'.$nowts['mon']."-".$nowts['year']."-".$itemrate['idprice']."-".$itemrate['iditem'];
							$dclass .= ' day-block';

							$today_tsin = mktime($pcheckinh, $pcheckinm, 0, $nowts['mon'], $nowts['mday'], $nowts['year']);
							$today_tsout = mktime($pcheckouth, $pcheckoutm, 0, $nowts['mon'], ($nowts['mday'] + 1), $nowts['year']);

							$tars = VikRentItems::applySeasonsItem(array($itemrate), $today_tsin, $today_tsout);

							?>
							<td align="center" class="<?php echo $dclass.' cell-'.$nowts['mday'].'-'.$nowts['mon']; ?>" id="<?php echo $id_block; ?>" data-vriprice="<?php echo $tars[0]['cost']; ?>" data-vridate="<?php echo date('Y-m-d', $nowts[0]); ?>" data-vridateread="<?php echo $days_labels[$nowts['wday']].', '.$months_labels[$nowts['mon']-1].' '.$nowts['mday']; ?>" data-vrispids="<?php echo (array_key_exists('spids', $tars[0]) && count($tars[0]['spids']) > 0 ? implode('-', $tars[0]['spids']) : ''); ?>"<?php echo $style; ?>>
								<span class="vri-rplan-currency"><?php echo $currencysymb; ?></span>
								<span class="vri-rplan-price"><?php echo $tars[0]['cost']; ?></span>
							</td>
							<?php

							$next = $nowts['mday'] + 1;
							$dayts = mktime(0, 0, 0, $nowts['mon'], $next, $nowts['year']);
							$nowts = getdate($dayts);
							
							$cell_count++;
						}
						?>
						</tr>
						<?php
					}
					?>
						<tr class="vri-roverviewtableavrow">
							<td><span class="vri-roverview-itemunits"><?php echo $itemrow['units']; ?></span><span class="vri-roverview-uleftlbl"><?php echo JText::translate('VRPCHOOSEBUSYCAVAIL'); ?></span></td>
						<?php
						$nowts = getdate($tsstart);
						$cell_count = 0;
						while ($cell_count < $MAX_DAYS) {
							$style = '';
							$dclass = "vri-roverw-daynotbusy";
							$id_block = "cell-".$nowts['mday'].'-'.$nowts['mon']."-".$nowts['year']."-".$nowts['wday']."-".$rid."-avail";

							$totfound = 0;
							$last_bid = 0;
							if (array_key_exists($itemrow['id'], $booked_dates) && is_array($booked_dates[$itemrow['id']])) {
								foreach ($booked_dates[$itemrow['id']] as $b) {
									$tmpone = getdate($b['ritiro']);
									$rit = ($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
									$ritts = strtotime($rit);
									$tmptwo = getdate($b['consegna']);
									$con = ($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
									$conts = strtotime($con);
									if ($nowts[0] >= $ritts && $nowts[0] < $conts) {
										$dclass = "vri-roverw-daybusy";
										$last_bid = $b['idorder'];
										if ($b['closure'] > 0) {
											$totfound = $itemrow['units'];
										} else {
											$totfound++;
										}
									}
								}
							}
							$units_remaining = $itemrow['units'] - $totfound;
							if ($units_remaining > 0 && $units_remaining < $itemrow['units'] && $itemrow['units'] > 1) {
								$dclass .= " vri-roverw-daybusypartially";
							} elseif ($units_remaining <= 0 && $itemrow['units'] <= 1 && !empty($last_bid)) {
								// no booking color tag.
							}

							?>
							<td align="center" class="<?php echo $dclass.' cell-'.$nowts['mday'].'-'.$nowts['mon']; ?>" id="<?php echo $id_block; ?>" data-vridateread="<?php echo $days_labels[$nowts['wday']].', '.$months_labels[$nowts['mon']-1].' '.$nowts['mday']; ?>"<?php echo $style; ?>>
								<span class="vri-roverw-curunits"><?php echo $units_remaining; ?></span>
							</td>
							<?php

							$next = $nowts['mday'] + 1;
							$dayts = mktime(0, 0, 0, ($nowts['mon'] < 10 ? "0".$nowts['mon'] : $nowts['mon']), ($next < 10 ? "0".$next : $next), $nowts['year']);
							$nowts = getdate($dayts);
							
							$cell_count++;
						}
						?>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="vri-ratesoverview-period-container">
				<div class="vri-ratesoverview-period-inner">
					<div class="vri-ratesoverview-period-lbl">
						<span><?php echo JText::translate('VRIROVWSELPERIOD'); ?></span>
					</div>
					<div class="vri-ratesoverview-period-boxes">
						<div class="vri-ratesoverview-period-boxes-inner">
							<div class="vri-ratesoverview-period-box-left">
								<div class="vri-ratesoverview-period-box-lbl">
									<span><?php echo JText::translate('VRIROVWSELPERIODFROM'); ?></span>
								</div>
								<div class="vri-ratesoverview-period-box-val">
									<div class="vri-ratesoverview-period-from">
										<span class="vri-ratesoverview-period-wday"></span>
										<span class="vri-ratesoverview-period-mday"></span>
										<span class="vri-ratesoverview-period-month"></span>
									</div>
									<span class="vri-ratesoverview-period-from-icon"><?php VikRentItemsIcons::e('calendar'); ?></span>
								</div>
							</div>
							<div class="vri-ratesoverview-period-box-right">
								<div class="vri-ratesoverview-period-box-lbl">
									<span><?php echo JText::translate('VRIROVWSELPERIODTO'); ?></span>
								</div>
								<div class="vri-ratesoverview-period-box-val">
									<div class="vri-ratesoverview-period-to">
										<span class="vri-ratesoverview-period-wday"></span>
										<span class="vri-ratesoverview-period-mday"></span>
										<span class="vri-ratesoverview-period-month"></span>
									</div>
									<span class="vri-ratesoverview-period-to-icon"><?php VikRentItemsIcons::e('calendar'); ?></span>
								</div>
							</div>
						</div>
						<div class="vri-ratesoverview-period-box-cals" style="display: none;">
							<div class="vri-ratesoverview-period-box-cals-inner">
								<div class="vri-ratesoverview-period-cal-left">
									<h4><?php echo JText::translate('VRIROVWSELPERIODFROM'); ?></h4>
									<div class="vri-period-from" data-iditem="<?php echo $rid; ?>" data-itemname="<?php echo htmlspecialchars($itemrow['name']); ?>"></div>
									<input type="hidden" class="vri-period-from-val" value="" />
								</div>
								<div class="vri-ratesoverview-period-cal-right">
									<h4><?php echo JText::translate('VRIROVWSELPERIODTO'); ?></h4>
									<div class="vri-period-to" data-iditem="<?php echo $rid; ?>" data-itemname="<?php echo htmlspecialchars($itemrow['name']); ?>"></div>
									<input type="hidden" class="vri-period-to-val" value="" />
								</div>
								<div class="vri-ratesoverview-period-cal-cmd">
									<h4><?php echo JText::translate('VRIROVWSELRPLAN'); ?></h4>
									<div class="vri-ratesoverview-period-cal-cmd-inner">
										<select class="vri-selperiod-rplanid" onchange="vriUpdateRplan(this);">
										<?php
										foreach ($itemrates[$rid] as $krr => $itemrate) {
											?>
											<option value="<?php echo $itemrate['idprice']; ?>" data-defrate="<?php echo $itemrate['cost']; ?>"<?php echo $krr < 1 ? ' selected="selected"' : ''; ?>><?php echo $itemrate['name']; ?></option>
											<?php
										}
										?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
		// start los pricing overview IF statement for just 1 item
		if (count($this->req_item_ids) < 2) :
	?>
	<div class="vri-ratesoverview-lostab-cont"<?php echo (!empty($cookie_tab) && $cookie_tab == 'cal' ? ' style="display: none;"' : ''); ?>>
		<?php
		if (count($seasons_cal) > 0) {
			//Special Prices Timeline
			if (isset($seasons_cal['seasons']) && count($seasons_cal['seasons'])) {
				?>
		<div class="vri-timeline-container">
			<ul id="vri-timeline">
				<?php
				foreach ($seasons_cal['seasons'] as $ks => $timeseason) {
					$s_val_diff = '';
					if ($timeseason['val_pcent'] == 2) {
						//percentage
						$s_val_diff = (($timeseason['diffcost'] - abs($timeseason['diffcost'])) > 0.00 ? VikRentItems::numberFormat($timeseason['diffcost']) : intval($timeseason['diffcost']))." %";
					} else {
						//absolute
						$s_val_diff = $currencysymb.''.VikRentItems::numberFormat($timeseason['diffcost']);
					}
					$s_explanation = array();
					if (empty($timeseason['year'])) {
						$s_explanation[] = JText::translate('VRISEASONANYYEARS');
					}
					if (!empty($timeseason['losoverride'])) {
						$s_explanation[] = JText::translate('VRISEASONBASEDLOS');
					}
					?>
				<li data-fromts="<?php echo $timeseason['from_ts']; ?>" data-tots="<?php echo $timeseason['to_ts']; ?>">
					<input type="radio" name="timeline" class="vri-timeline-radio" id="vri-timeline-dot<?php echo $ks; ?>" <?php echo $ks === 0 ? 'checked="checked"' : ''; ?>/>
					<div class="vri-timeline-relative">
						<label class="vri-timeline-label" for="vri-timeline-dot<?php echo $ks; ?>"><?php echo $timeseason['spname']; ?></label>
						<span class="vri-timeline-date"><?php echo VikRentItems::formatSeasonDates($timeseason['from_ts'], $timeseason['to_ts']); ?></span>
						<span class="vri-timeline-circle" onclick="Javascript: jQuery('#vri-timeline-dot<?php echo $ks; ?>').trigger('click');"></span>
					</div>
					<div class="vri-timeline-content">
						<p>
							<span class="vri-seasons-calendar-slabel vri-seasons-calendar-season-<?php echo $timeseason['type'] == 2 ? 'discount' : 'charge'; ?>"><?php echo $timeseason['type'] == 2 ? '-' : '+'; ?> <?php echo $s_val_diff; ?> <?php echo JText::translate('VRISEASONPERDAY'); ?></span>
							<br/>
							<?php
							if (count($s_explanation) > 0) {
								echo implode(' - ', $s_explanation);
							}
							?>
						</p>
					</div>
				</li>
					<?php
				}
				?>
			</ul>
		</div>
		<script>
		jQuery(document).ready(function(){
			jQuery('.vri-timeline-container').css('min-height', (jQuery('.vri-timeline-container').outerHeight() + 20));
		});
		</script>
				<?php
			}
			//
			//Begin Seasons Calendar
			?>
		<div class="table-responsive">
			<table class="table vri-seasons-calendar-table">
				<tr class="vri-seasons-calendar-nightsrow">
					<td>&nbsp;</td>
				<?php
				foreach ($seasons_cal['offseason'] as $numdays => $ntars) {
					?>
					<td><span><?php echo JText::sprintf(($numdays > 1 ? 'VRISEASONCALNUMDAYS' : 'VRISEASONCALNUMDAY'), $numdays); ?></span></td>
					<?php
				}
				?>
				</tr>
				<tr class="vri-seasons-calendar-offseasonrow">
					<td>
						<span class="vri-seasons-calendar-offseasonname"><?php echo JText::translate('VRISEASONSCALOFFSEASONPRICES'); ?></span>
					</td>
				<?php
				foreach ($seasons_cal['offseason'] as $numdays => $tars) {
					?>
					<td>
						<div class="vri-seasons-calendar-offseasoncosts">
							<?php
							foreach ($tars as $tar) {
								?>
							<div class="vri-seasons-calendar-offseasoncost">
								<?php
								if ($price_types_show) {
								?>
								<span class="vri-seasons-calendar-pricename"><?php echo $tar['name']; ?></span>
								<?php
								}
								?>
								<span class="vri-seasons-calendar-pricecost">
									<span class="vri_currency"><?php echo $currencysymb; ?></span><span class="vri_price"><?php echo VikRentItems::numberFormat($tar['cost']); ?></span>
								</span>
							</div>
								<?php
								if (!$price_types_show) {
									break;
								}
							}
							?>
						</div>
					</td>
					<?php
				}
				?>
				</tr>
				<?php
				if (!isset($seasons_cal['seasons'])) {
					$seasons_cal['seasons'] = array();
				}
				foreach ($seasons_cal['seasons'] as $s_id => $s) {
					$restr_diff_nights = array();
					if ($los_show && array_key_exists($s_id, $seasons_cal['restrictions'])) {
						$restr_diff_nights = VikRentItems::compareSeasonRestrictionsNights($seasons_cal['restrictions'][$s_id]);
					}
					$s_val_diff = '';
					if ($s['val_pcent'] == 2) {
						//percentage
						$s_val_diff = (($s['diffcost'] - abs($s['diffcost'])) > 0.00 ? VikRentItems::numberFormat($s['diffcost']) : intval($s['diffcost']))." %";
					} else {
						//absolute
						$s_val_diff = $currencysymb.''.VikRentItems::numberFormat($s['diffcost']);
					}
					?>
				<tr class="vri-seasons-calendar-seasonrow">
					<td>
						<div class="vri-seasons-calendar-seasondates">
							<span class="vri-seasons-calendar-seasonfrom"><?php echo date($df, $s['from_ts']); ?></span>
							<span class="vri-seasons-calendar-seasondates-separe">-</span>
							<span class="vri-seasons-calendar-seasonto"><?php echo date($df, $s['to_ts']); ?></span>
						</div>
						<div class="vri-seasons-calendar-seasonchargedisc">
							<span class="vri-seasons-calendar-slabel vri-seasons-calendar-season-<?php echo $s['type'] == 2 ? 'discount' : 'charge'; ?>"><span class="vri-seasons-calendar-operator"><?php echo $s['type'] == 2 ? '-' : '+'; ?></span><?php echo $s_val_diff; ?></span>
						</div>
						<span class="vri-seasons-calendar-seasonname"><a href="index.php?option=com_vikrentitems&amp;task=editseason&amp;cid[]=<?php echo $s['id']; ?>" target="_blank"><?php echo $s['spname']; ?></a></span>
					<?php
					if ($los_show && array_key_exists($s_id, $seasons_cal['restrictions']) && count($restr_diff_nights) == 0) {
						//Season Restrictions
						$season_restrictions = array();
						foreach ($seasons_cal['restrictions'][$s_id] as $restr) {
							$season_restrictions = $restr;
							break;
						}
						?>
						<div class="vri-seasons-calendar-restrictions">
						<?php
						if ($season_restrictions['minlos'] > 1) {
							?>
							<span class="vri-seasons-calendar-restriction-minlos"><?php echo JText::translate('VRIRESTRMINLOS'); ?><span class="vri-seasons-calendar-restriction-minlos-badge"><?php echo $season_restrictions['minlos']; ?></span></span>
							<?php
						}
						if (array_key_exists('maxlos', $season_restrictions) && $season_restrictions['maxlos'] > 1) {
							?>
							<span class="vri-seasons-calendar-restriction-maxlos"><?php echo JText::translate('VRIRESTRMAXLOS'); ?><span class="vri-seasons-calendar-restriction-maxlos-badge"><?php echo $season_restrictions['maxlos']; ?></span></span>
							<?php
						}
						if (array_key_exists('wdays', $season_restrictions) && count($season_restrictions['wdays']) > 0) {
							?>
							<div class="vri-seasons-calendar-restriction-wdays">
								<label><?php echo JText::translate((count($season_restrictions['wdays']) > 1 ? 'VRIRESTRARRIVWDAYS' : 'VRIRESTRARRIVWDAY')); ?></label>
							<?php
							foreach ($season_restrictions['wdays'] as $wday) {
								?>
								<span class="vri-seasons-calendar-restriction-wday"><?php echo VikRentItems::sayWeekDay($wday); ?></span>
								<?php
							}
							?>
							</div>
							<?php
						} elseif ((array_key_exists('cta', $season_restrictions) && count($season_restrictions['cta']) > 0) || (array_key_exists('ctd', $season_restrictions) && count($season_restrictions['ctd']) > 0)) {
							if (array_key_exists('cta', $season_restrictions) && count($season_restrictions['cta']) > 0) {
								?>
							<div class="vri-seasons-calendar-restriction-wdays vri-seasons-calendar-restriction-cta">
								<label><?php echo JText::translate('VRIRESTRWDAYSCTA'); ?></label>
								<?php
								foreach ($season_restrictions['cta'] as $wday) {
									?>
								<span class="vri-seasons-calendar-restriction-wday"><?php echo VikRentItems::sayWeekDay(str_replace('-', '', $wday)); ?></span>
									<?php
								}
								?>
							</div>
								<?php
							}
							if (array_key_exists('ctd', $season_restrictions) && count($season_restrictions['ctd']) > 0) {
								?>
							<div class="vri-seasons-calendar-restriction-wdays vri-seasons-calendar-restriction-ctd">
								<label><?php echo JText::translate('VRIRESTRWDAYSCTD'); ?></label>
								<?php
								foreach ($season_restrictions['ctd'] as $wday) {
									?>
								<span class="vri-seasons-calendar-restriction-wday"><?php echo VikRentItems::sayWeekDay(str_replace('-', '', $wday)); ?></span>
									<?php
								}
								?>
							</div>
								<?php
							}
						}
						?>
						</div>
						<?php
					}
					?>
					</td>
					<?php
					if (array_key_exists($s_id, $seasons_cal['season_prices']) && count($seasons_cal['season_prices'][$s_id]) > 0) {
						foreach ($seasons_cal['season_prices'][$s_id] as $numdays => $tars) {
							$show_day_cost = true;
							if ($los_show && array_key_exists($s_id, $seasons_cal['restrictions']) && array_key_exists($numdays, $seasons_cal['restrictions'][$s_id])) {
								if ($seasons_cal['restrictions'][$s_id][$numdays]['allowed'] === false) {
									$show_day_cost = false;
								}
							}
							?>
					<td>
						<?php
						if ($show_day_cost) {
						?>
						<div class="vri-seasons-calendar-seasoncosts">
							<?php
							foreach ($tars as $tar) {
								//print the types of price that are not being modified by this special price with opacity
								$not_affected = (!array_key_exists('origdailycost', $tar));
								//
								?>
							<div class="vri-seasons-calendar-seasoncost<?php echo ($not_affected ? ' vri-seasons-calendar-seasoncost-notaffected' : ''); ?>">
								<?php
								if ($price_types_show) {
								?>
								<span class="vri-seasons-calendar-pricename"><?php echo $tar['name']; ?></span>
								<?php
								}
								?>
								<span class="vri-seasons-calendar-pricecost">
									<span class="vri_currency"><?php echo $currencysymb; ?></span><span class="vri_price"><?php echo VikRentItems::numberFormat($tar['cost']); ?></span>
								</span>
							</div>
								<?php
								if (!$price_types_show) {
									break;
								}
							}
							?>
						</div>
						<?php
						} else {
							?>
							<div class="vri-seasons-calendar-seasoncosts-disabled"></div>
							<?php
						}
						?>
					</td>
							<?php
						}
					}
					?>
				</tr>
					<?php
				}
				?>
			</table>
		</div>
			<?php
			//End Seasons Calendar
		} else {
			?>
		<p class="vri-warning"><?php echo JText::translate('VRNOPRICESFOUND'); ?></p>
			<?php
		}
		?>
	</div>
	<?php
		// end los pricing overview IF statement for just 1 item
		endif;
	}
	?>
</div>

<div class="vri-info-overlay-block">
	<a class="vri-info-overlay-close" href="javascript: void(0);"></a>
	<div class="vri-info-overlay-content vri-info-overlay-content-rovervw">
		<div class="vri-roverw-infoblock">
			<span id="rovervw-itemname"></span>
			<div class="vri-roverw-inforates"><span id="rovervw-rplan"></span><span id="rovervw-fromdate"></span> - <span id="rovervw-todate"></span></div>
		</div>
		<div class="vri-roverw-alldays">
			<div class="vri-roverw-alldays-inner"></div>
		</div>
		<div class="vri-roverw-setnewrate">
			<div class="vri-roverw-newrwrap">
				<h4><i class="vriicn-calculator"></i><?php echo JText::translate('VRIRATESOVWSETNEWRATE'); ?></h4>
				<div class="vri-roverw-setnewrate-inner">
					<span class="vri-roverw-setnewrate-currency"><?php echo $currencysymb; ?></span> 
					<input type="number" step="any" min="0" id="roverw-newrate" value="" placeholder="" size="7" />
				</div>
			</div>
			<div class="vri-roverw-setnewrate-btns">
				<button type="button" class="btn btn-danger" onclick="hideVriDialog();"><?php echo JText::translate('VRANNULLA'); ?></button>
				<button type="button" class="btn btn-success" onclick="setNewRates();"><i class="vriicn-checkmark"></i><?php echo JText::translate('VRAPPLY'); ?></button>
			</div>
		</div>
		<div class="vri-roverw-closeopenrp">
			<h4><i class="vriicn-switch"></i><?php echo JText::translate('VRIRATESOVWCLOSEOPENRRP'); ?> <span id="rovervw-closeopen-rplan"></span></h4>
			<div class="vri-roverw-closeopenrp-btns">
				<button type="button" class="btn btn-danger" onclick="modItemRatePlan('close');"><i class="vriicn-exit"></i><?php echo JText::translate('VRIRATESOVWCLOSERRP'); ?></button>
				<button type="button" class="btn btn-success" onclick="modItemRatePlan('open');"><i class="vriicn-enter"></i><?php echo JText::translate('VRIRATESOVWOPENRRP'); ?></button>
				<br clear="all" /><br />
				<button type="button" class="btn btn-danger" onclick="hideVriDialog();"><?php echo JText::translate('VRANNULLA'); ?></button>
			</div>
		</div>
	</div>
	<div class="vri-info-overlay-loading">
		<div><?php echo JText::translate('VIKLOADING'); ?></div>
	</div>
</div>

<form name="adminForm" id="adminForm" action="index.php" method="post">
	<input type="hidden" name="task" value="">
	<input type="hidden" name="option" value="com_vikrentitems">
</form>

<a id="vri-base-booknow-link" style="display: none;" href="index.php?option=com_vikrentitems&task=calendar&cid[]=&pickup=&dropoff=&idprice=&booknow=1"></a>

<script type="text/Javascript">
function vriFormatCalDate(elem, idc) {
	var vri_period = elem.parent().find('.vri-'+idc+'-val').val();
	if (!vri_period || !vri_period.length) {
		return false;
	}
	var vri_period_parts = vri_period.split("/");
	if ('%d/%m/%Y' == '<?php echo $vri_df; ?>') {
		var period_date = new Date(vri_period_parts[2], (parseInt(vri_period_parts[1]) - 1), parseInt(vri_period_parts[0], 10), 0, 0, 0, 0);
		var data = [parseInt(vri_period_parts[0], 10), parseInt(vri_period_parts[1]), vri_period_parts[2]];
	} else if ('%m/%d/%Y' == '<?php echo $vri_df; ?>') {
		var period_date = new Date(vri_period_parts[2], (parseInt(vri_period_parts[0]) - 1), parseInt(vri_period_parts[1], 10), 0, 0, 0, 0);
		var data = [parseInt(vri_period_parts[1], 10), parseInt(vri_period_parts[0]), vri_period_parts[2]];
	} else {
		var period_date = new Date(vri_period_parts[0], (parseInt(vri_period_parts[1]) - 1), parseInt(vri_period_parts[2], 10), 0, 0, 0, 0);
		var data = [parseInt(vri_period_parts[2], 10), parseInt(vri_period_parts[1]), vri_period_parts[0]];
	}
	var elcont = elem.closest('.vri-ratesoverview-period-boxes').find('.vri-ratesoverview-'+idc);
	elcont.find('.vri-ratesoverview-period-wday').text(vriMapWdays[period_date.getDay()]);
	elcont.find('.vri-ratesoverview-period-mday').text(period_date.getDate());
	elcont.find('.vri-ratesoverview-period-month').text(vriMapMons[period_date.getMonth()]);
	elem.closest('.vri-ratesoverview-period-boxes').find('.vri-ratesoverview-'+idc+'-icon').hide();
	data.push(elem.closest('.vri-ratesoverview-period-boxes').find('.vri-selperiod-rplanid').val());
	data.push(elem.closest('.vri-ratesoverview-period-boxes').find('.vri-selperiod-rplanid option:selected').text());
	data.push(elem.closest('.vri-ratesoverview-period-boxes').find('.vri-selperiod-rplanid option:selected').attr('data-defrate'));
	data.push(elem.attr('data-iditem'));
	data.push(elem.attr('data-itemname'));
	var struct = getPeriodStructure(data);
	if (idc.indexOf('from') >= 0) {
		//period from date selected
		if (!vrilistener.pickFirst(struct)) {
			//first already picked: update it
			vrilistener.first = struct;
		}
	}
	if (idc.indexOf('to') >= 0) {
		//period to date selected
		if (!vrilistener.pickFirst(struct)) {
			//first already picked
			if ((vrilistener.first.isBeforeThan(struct) || vrilistener.first.isSameDay(struct)) && vrilistener.first.isSameRplan(struct) && vrilistener.first.isSameItem(struct)) {
				//last > first: pick last
				if (vrilistener.pickLast(struct)) {
					showVriDialogPeriod();
				}
			}
		}
	}
}
jQuery(document).ready(function() {
	jQuery('.vridatepicker').datepicker({
		showOn: 'focus',
		dateFormat: '<?php echo $juidf; ?>',
		minDate: '0d',
		numberOfMonths: 2,
		changeMonth: true,
		changeYear: true,
		yearRange: '<?php echo date('Y').':'.(date('Y') + 3); ?>',
		onSelect: function(selectedDate) {
			var parentform = jQuery(this).closest('form');
			var itemsids = jQuery('#itemsel').val();
			if (itemsids) {
				if (!Array.isArray(itemsids)) {
					// if there is just one item type, the select is not multiple, so this is a string
					itemsids = [jQuery('#itemsel').val()];
				}
				jQuery.each(itemsids, function(k, v) {
					parentform.append('<input type="hidden" name="cid[]" value="'+v+'" />');
				});
			}
			parentform.submit();
		}
	});
	jQuery('.vri-period-from').datepicker({
		dateFormat: '<?php echo $juidf; ?>',
		minDate: '0d',
		altField: '.vri-period-from-val',
		onSelect: function(selectedDate) {
			jQuery(this).parent().find('.vri-period-from-val').val(selectedDate);
			jQuery(this).closest('.vri-ratesoverview-period-box-cals').find('.vri-period-to').datepicker("option", "minDate", selectedDate);
			vriFormatCalDate(jQuery(this), 'period-from');
		}
	});
	jQuery('.vri-period-to').datepicker({
		dateFormat: '<?php echo $juidf; ?>',
		minDate: '0d',
		altField: '.vri-period-to-val',
		onSelect: function( selectedDate ) {
			jQuery(this).parent().find('.vri-period-to-val').val(selectedDate);
			jQuery(this).closest('.vri-ratesoverview-period-box-cals').find('.vri-period-from').datepicker("option", "maxDate", selectedDate);
			vriFormatCalDate(jQuery(this), 'period-to');
		}
	});
	jQuery('.vri-ratesoverview-period-box-left, .vri-ratesoverview-period-box-right').click(function() {
		jQuery(this).closest('.vri-ratesoverview-period-boxes').find('.vri-ratesoverview-period-box-cals').fadeToggle();
	});
	jQuery("#itemsel, #itemselcalc").select2();
});
<?php
if ($df == "Y/m/d") {
	?>
Date.prototype.format = "yy/mm/dd";
	<?php
} elseif ($df == "m/d/Y") {
	?>
Date.prototype.format = "mm/dd/yy";
	<?php
} else {
	?>
Date.prototype.format = "dd/mm/yy";
	<?php
}
?>
var currencysymb = '<?php echo $currencysymb; ?>';
var debug_mode = '<?php echo $pdebug; ?>';
var roverw_messages = {
	"setNewRatesMissing": "<?php echo addslashes(JText::translate('VRIRATESOVWERRNEWRATE')); ?>",
	"modRplansMissing": "<?php echo addslashes(JText::translate('VRIRATESOVWERRMODRPLANS')); ?>",
	"openSpLink": "<?php echo addslashes(JText::translate('VRIRATESOVWOPENSPL')); ?>"
};
</script>
<script type="text/Javascript">
/* Dates selection - Start */
var vrilistener = null;
var vridialog_on = false;
jQuery(document).ready(function() {
	vrilistener = new CalendarListener();
	jQuery('.day-block').click(function() {
		pickBlock(jQuery(this).attr('id'));
	});
	jQuery('.day-block').hover(
		function() {
			if (vrilistener.isFirstPicked() && !vrilistener.isLastPicked()) {
				var struct = initBlockStructure(jQuery(this).attr('id'));
				var all_blocks = getAllBlocksBetween(vrilistener.first, struct, false);
				if (all_blocks !== false) {
					jQuery.each(all_blocks, function(k, v) {
						if (!v.hasClass('block-picked-middle')) {
							v.addClass('block-picked-middle');
						}
					});
					jQuery(this).addClass('block-picked-end');
				}
			}
		},
		function() {
			if (!vrilistener.isLastPicked()) {
				jQuery('.day-block').removeClass('block-picked-middle block-picked-end');
			}
		}
	);
	jQuery(document).keydown(function(e) {
		if (e.keyCode == 27) {
			hideVriDialog();
		}
	});
	jQuery(document).mouseup(function(e) {
		if (!vridialog_on) {
			return false;
		}
		var vri_overlay_cont = jQuery(".vri-info-overlay-content");
		if (!vri_overlay_cont.is(e.target) && vri_overlay_cont.has(e.target).length === 0) {
			hideVriDialog();
		}
	});
	jQuery("body").on("click", ".vri-roverw-daymod-infospids", function() {
		var helem = jQuery(this).next('.vri-roverw-daymod-infospids-outcont');
		if (helem.length && helem.is(":visible")) {
			jQuery(this).removeClass("vri-roverw-daymod-infospids-on");
			helem.hide();
		} else {
			jQuery(".vri-roverw-daymod-infospids-on").removeClass("vri-roverw-daymod-infospids-on");
			jQuery(".vri-roverw-daymod-infospids-outcont").hide();
			jQuery(this).addClass("vri-roverw-daymod-infospids-on");
			helem.show();
		}
	});
	jQuery('.vri-roverw-closeopenrp h4').click(function() {
		jQuery('.vri-roverw-closeopenrp-btns').fadeToggle();
	});
});

function showVriDialog() {
	var format = new Date().format;
	jQuery("#rovervw-itemname").html(vrilistener.first.itemName);
	jQuery("#rovervw-rplan").html(vrilistener.first.rplanName);
	jQuery("#rovervw-closeopen-rplan").html('"'+vrilistener.first.rplanName+'"');
	jQuery("#rovervw-fromdate").html(vrilistener.first.toDate(format));
	jQuery("#rovervw-todate").html(vrilistener.last.toDate(format));
	jQuery(".vri-roverw-alldays-inner").html("");
	var all_blocks = getAllBlocksBetween(vrilistener.first, vrilistener.last, true);
	if (all_blocks !== false) {
		var newdayscont = '';
		jQuery.each(all_blocks, function(k, v) {
			var spids = jQuery(v).attr("data-vrispids").split("-");
			var spids_det = '';
			if (jQuery(v).attr("data-vrispids").length > 0 && spids.length > 0) {
				spids_det += "<div class=\"vri-roverw-daymod-infospids\"><span><i class=\"<?php echo VikRentItemsIcons::i('info-circle'); ?>\"></i></span></div>";
				spids_det += "<div class=\"vri-roverw-daymod-infospids-outcont\">";
				spids_det += "<div class=\"vri-roverw-daymod-infospids-incont\"><ul>";
				for(var x = 0; x < spids.length; x++) {
					spids_det += "<li><a target=\"_blank\" href=\"index.php?option=com_vikrentitems&task=editseason&cid[]="+spids[x]+"\">"+roverw_messages.openSpLink.replace("%d", spids[x])+"</a></li>";
				}
				spids_det += "</ul></div></div>";
			}
			newdayscont += "<div class=\"vri-roverw-daymod\"><div class=\"vri-roverw-daymod-inner\"><div class=\"vri-roverw-daymod-innercell\"><span class=\"vri-roverw-daydate\">"+jQuery(v).attr("data-vridateread")+"</span><span class=\"vri-roverw-dayprice\">"+v.html()+"</span>"+spids_det+"</div></div></div>";
		});
		jQuery(".vri-roverw-alldays-inner").html(newdayscont);
		//jQuery("#roverw-newrate").attr("placeholder", vrilistener.first.defRate);
		jQuery("#roverw-newrate").val(vrilistener.first.defRate);
	}

	jQuery(".vri-info-overlay-block").fadeIn();
	vridialog_on = true;
}

function showVriDialogPeriod() {
	var format = new Date().format;
	jQuery('.vri-ratesoverview-period-box-cals').fadeOut();
	jQuery("#rovervw-itemname").html(vrilistener.first.itemName);
	jQuery("#rovervw-rplan").html(vrilistener.first.rplanName);
	jQuery("#rovervw-closeopen-rplan").html('"'+vrilistener.first.rplanName+'"');
	jQuery("#rovervw-fromdate").html(vrilistener.first.toDate(format));
	jQuery("#rovervw-todate").html(vrilistener.last.toDate(format));
	jQuery(".vri-roverw-alldays-inner").html("");
	// reset default new price and placeholder
	jQuery("#roverw-newrate").attr("placeholder", "").val("");
	// check if all selected blocks are closed
	var all_blocks = getAllBlocksBetween(vrilistener.first, vrilistener.last, true);
	if (all_blocks !== false) {
		var allblocksclosed = true;
		jQuery.each(all_blocks, function(k, v) {
			if (!v.hasClass('vri-roverw-rplan-off')) {
				allblocksclosed = false;
				return false;
			}
		});
		if (allblocksclosed) {
			jQuery("#rovervw-rplan").html('<span style="color: #f00"><i class="<?php echo VikRentItemsIcons::i('ban'); ?>"></i> '+vrilistener.first.rplanName+'</span>');
		}
	}
	//

	jQuery(".vri-info-overlay-block").fadeIn();
	vridialog_on = true;
}

function hideVriDialog() {
	vrilistener.clear();
	jQuery('.day-block').removeClass('block-picked-start block-picked-middle block-picked-end');
	if (vridialog_on === true) {
		jQuery(".vri-info-overlay-block").fadeOut(400, function () {
			jQuery(".vri-info-overlay-content").show();
		});
		//reset period selection
		jQuery('.vri-ratesoverview-period-from').find('span').text('');
		jQuery('.vri-ratesoverview-period-from-icon').show();
		jQuery('.vri-ratesoverview-period-to').find('span').text('');
		jQuery('.vri-ratesoverview-period-to-icon').show();
		//
		vridialog_on = false;
	}
}

jQuery(document.body).on('click', '.vri-ratesoverview-vcmwarn-close', function() {
	jQuery('.vri-ratesoverview-right-inner').hide().html('');
});

function setNewRates() {
	var all_blocks = getAllBlocksBetween(vrilistener.first, vrilistener.last, true);
	var toval = jQuery("#roverw-newrate").val();
	var tovalint = parseFloat(toval);
	var closerplan = 0;
	if (all_blocks !== false && toval.length > 0 && !isNaN(tovalint) && tovalint > 0.00) {
		// check whether all blocks have closed the rate plan
		var allblocksclosed = true;
		jQuery.each(all_blocks, function(k, v) {
			if (!v.hasClass('vri-roverw-rplan-off')) {
				allblocksclosed = false;
				// break
				return false;
			}
		});
		closerplan = allblocksclosed ? 1 : closerplan;
		//
		jQuery(".vri-info-overlay-content").hide();
		jQuery(".vri-info-overlay-loading").prepend('<i class="<?php echo VikRentItemsIcons::i('refresh', 'fa-spin fa-3x fa-fw'); ?>"></i>').fadeIn();
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikrentitems", task: "setnewrates", tmpl: "component", e4j_debug: debug_mode, id_item: vrilistener.first.iditem, id_price: vrilistener.first.rplan, rate: toval, fromdate: vrilistener.first.toDate("yy-mm-dd"), todate: vrilistener.last.toDate("yy-mm-dd"), rateclosed: closerplan }
		}).done(function(res) {
			if (res.indexOf('e4j.error') >= 0) {
				console.log(res);
				alert(res.replace("e4j.error.", ""));
				jQuery(".vri-info-overlay-content").show();
				jQuery(".vri-info-overlay-loading").hide().find("i").remove();
			} else {
				//display new rates in all_blocks IDs
				var obj_res = JSON.parse(res);
				jQuery.each(obj_res, function(k, v) {
					if (k == 'vcm') {
						return true;
					}
					var elem = jQuery("#cell-"+k+"-"+vrilistener.first.iditem);
					if (elem.length) {
						elem.find(".vri-rplan-price").html(v.cost);
						var spids = '';
						if (v.hasOwnProperty('spids')) {
							jQuery.each(v.spids, function(spk, spv) {
								spids += spv+'-';
							});
							//right trim dash
							spids = spids.replace(/-+$/, '');
						}
						elem.attr('data-vrispids', spids);
					}
				});
				jQuery(".vri-info-overlay-loading").hide().find("i").remove();
				hideVriDialog();
			}
		}).fail(function() { 
			alert("Request Failed");
			jQuery(".vri-info-overlay-content").show();
			jQuery(".vri-info-overlay-loading").hide().find("i").remove();
		});
	} else {
		alert(roverw_messages.setNewRatesMissing);
		return false;
	}
}

function modItemRatePlan(mode) {
	var all_blocks = getAllBlocksBetween(vrilistener.first, vrilistener.last, true);
	if (all_blocks !== false && mode.length > 0) {
		jQuery(".vri-info-overlay-content").hide();
		jQuery(".vri-info-overlay-loading").prepend('<i class="fas fa-sync fa-spin fa-3x fa-fw"></i>').fadeIn();
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikrentitems", task: "moditemrateplans", tmpl: "component", e4j_debug: debug_mode, id_item: vrilistener.first.iditem, id_price: vrilistener.first.rplan, type: mode, fromdate: vrilistener.first.toDate("yy-mm-dd"), todate: vrilistener.last.toDate("yy-mm-dd") }
		}).done(function(res) {
			if (res.indexOf('e4j.error') >= 0 ) {
				console.log(res);
				alert(res.replace("e4j.error.", ""));
				jQuery(".vri-info-overlay-content").show();
				jQuery(".vri-info-overlay-loading").hide().find("i").remove();
			} else {
				//apply new classes in all_blocks IDs
				var obj_res = JSON.parse(res);
				jQuery.each(obj_res, function(k, v) {
					var elem = jQuery("#cell-"+k+"-"+vrilistener.first.iditem);
					if (elem.length) {
						elem.removeClass(v.oldcls).addClass(v.newcls);
					}
				});
				jQuery(".vri-info-overlay-loading").hide().find("i").remove();
				hideVriDialog();
			}
		}).fail(function() { 
			alert("Request Failed");
			jQuery(".vri-info-overlay-content").show();
			jQuery(".vri-info-overlay-loading").hide().find("i").remove();
		});
	} else {
		alert(roverw_messages.modRplansMissing);
		return false;
	}
}

function vriUpdateRplan(that) {
	if (vrilistener === null || vrilistener.first === null) {
		return true;
	}
	vrilistener.first.rplan = jQuery(that).val();
	vrilistener.first.rplanName = jQuery(that).find('option:selected').text();
	vrilistener.first.defRate = jQuery(that).find('option:selected').attr('data-defrate');
}

function pickBlock(id) {
	var struct = initBlockStructure(id);
	
	if (!vrilistener.pickFirst(struct)) {
		// first already picked
		if ((vrilistener.first.isBeforeThan(struct) || vrilistener.first.isSameDay(struct)) && vrilistener.first.isSameRplan(struct) && vrilistener.first.isSameItem(struct)) {
			// last > first : pick last
			if (vrilistener.pickLast(struct)) {
				var all_blocks = getAllBlocksBetween(vrilistener.first, vrilistener.last, false);
				if (all_blocks !== false) {
					jQuery.each(all_blocks, function(k, v){
						if (!v.hasClass('block-picked-middle')) {
							v.addClass('block-picked-middle');
						}
					});
					jQuery('#'+vrilistener.last.id).addClass('block-picked-end');
					showVriDialog();
				}
			}
		} else {
			// last < first : clear selection
			vrilistener.clear();
			jQuery('.day-block').removeClass('block-picked-start block-picked-middle block-picked-end');
		}
	} else {
		// first picked
		jQuery('#'+vrilistener.first.id).addClass('block-picked-start');
	}
}

function getAllBlocksBetween(start, end, outers_included) {
	if (!start.isSameRplan(end) || !start.isSameItem(end)) {
		return false;
	}
	
	if (start.isAfterThan(end)) {
		return false;
	}
	
	var queue = new Array();
	
	if (outers_included) {
		queue.push(jQuery('#'+start.id));
	}
	
	if (start.isSameDay(end)) {
		return queue;
	}

	var node = jQuery('#'+start.id).next();
	var end_id = jQuery('#'+end.id).attr('id');
	while (node.length > 0 && node.attr('id') != end_id) {
		queue.push(node);
		node = node.next();
	}
	
	if (outers_included) {
		queue.push(jQuery('#'+end.id));
	}
	
	return queue;
}

function getPeriodStructure(data) {
	return {
		"day": parseInt(data[0]),
		"month": parseInt(data[1]),
		"year": parseInt(data[2]),
		"rplan": data[3],
		"iditem": data[6],
		"itemName": data[7],
		"rplanName": data[4],
		"defRate": data[5],
		"id": "cell-"+parseInt(data[0])+"-"+parseInt(data[1])+"-"+parseInt(data[2])+"-"+data[3]+"-"+data[6],
		"isSameDay": function(block) {
			return (this.month == block.month && this.day == block.day && this.year == block.year);
		},
		"isBeforeThan": function(block) {
			return ( 
				(this.year < block.year) || 
				(this.year == block.year && this.month < block.month) || 
				(this.year == block.year && this.month == block.month && this.day < block.day)
			);
		},
		"isAfterThan": function(block) {
			return ( 
				(this.year > block.year) || 
				(this.year == block.year && this.month > block.month) || 
				(this.year == block.year && this.month == block.month && this.day > block.day)
			);
		},
		"isSameRplan": function(block) {
			return (this.rplan == block.rplan);
		},
		"isSameItem": function(block) {
			return (this.iditem == block.iditem);
		},
		"toDate": function(format) {
			return format.replace(
				'dd', ( this.day < 10 ? '0' : '' )+this.day
			).replace(
				'mm', ( this.month < 10 ? '0' : '' )+this.month
			).replace(
				'yy', this.year
			);
		}
	};
}

function initBlockStructure(id) {
	var s = id.split("-");
	if (s.length != 6) {
		return {};
	}
	var elem = jQuery("#"+id);
	return {
		"day":parseInt(s[1]),
		"month":parseInt(s[2]),
		"year":parseInt(s[3]),
		"rplan":s[4],
		"iditem": s[5],
		"itemName": elem.parent("tr").find("td").first().attr("data-itemname"),
		"rplanName": elem.parent("tr").find("td").first().text(),
		"defRate": elem.parent("tr").find("td").first().attr("data-defrate"),
		"id":id,
		"isSameDay": function(block) {
			return (this.month == block.month && this.day == block.day && this.year == block.year);
		},
		"isBeforeThan": function(block) {
			return (
				(this.year < block.year) || 
				(this.year == block.year && this.month < block.month) || 
				(this.year == block.year && this.month == block.month && this.day < block.day)
			);
		},
		"isAfterThan": function(block) {
			return (
				(this.year > block.year) || 
				(this.year == block.year && this.month > block.month) || 
				(this.year == block.year && this.month == block.month && this.day > block.day)
			);
		},
		"isSameRplan": function(block) {
			return (this.rplan == block.rplan);
		},
		"isSameItem": function(block) {
			return (this.iditem == block.iditem);
		},
		"toDate": function(format) {
			return format.replace(
				'dd', ( this.day < 10 ? '0' : '' )+this.day
			).replace(
				'mm', ( this.month < 10 ? '0' : '' )+this.month
			).replace(
				'yy', this.year
			);
		}
	};
}

function CalendarListener() {
	this.first = null;
	this.last = null;
}

CalendarListener.prototype.pickFirst = function(struct) {
	if (!this.isFirstPicked()) {
		this.first = struct;
		return true;
	}
	return false;
}

CalendarListener.prototype.pickLast = function(struct) {
	if (!this.isLastPicked() && this.isFirstPicked()) {
		this.last = struct;
		return true;
	}
	return false;
}

CalendarListener.prototype.clear = function() {
	this.first = null;
	this.last = null;
}

CalendarListener.prototype.isFirstPicked = function() {
	return this.first != null;
}

CalendarListener.prototype.isLastPicked = function() {
	return this.last != null;
}

/* Dates selection - End */
var timeline_height_set = false;
jQuery(document).ready(function() {
	jQuery(".vri-ratesoverview-tab-los").click(function() {
		var nd = new Date();
		nd.setTime(nd.getTime() + (365*24*60*60*1000));
		document.cookie = "vriRovwRab=los; expires=" + nd.toUTCString() + "; path=/; SameSite=Lax";
		jQuery(this).removeClass("vri-ratesoverview-tab-unactive").addClass("vri-ratesoverview-tab-active");
		jQuery(".vri-ratesoverview-tab-cal").removeClass("vri-ratesoverview-tab-active").addClass("vri-ratesoverview-tab-unactive");
		jQuery(".vri-ratesoverview-itemsel-entry-los").show();
		jQuery(".vri-ratesoverview-caltab-cont").hide();
		jQuery(".vri-ratesoverview-lostab-cont").fadeIn();
		if (!timeline_height_set) {
			jQuery('.vri-timeline-container').css('min-height', (jQuery('.vri-timeline-container').outerHeight() + 20));
			timeline_height_set = true;
		}
	});
	jQuery(".vri-ratesoverview-tab-cal").click(function() {
		var nd = new Date();
		nd.setTime(nd.getTime() + (365*24*60*60*1000));
		document.cookie = "vriRovwRab=cal; expires=" + nd.toUTCString() + "; path=/; SameSite=Lax";
		jQuery(this).removeClass("vri-ratesoverview-tab-unactive").addClass("vri-ratesoverview-tab-active");
		jQuery(".vri-ratesoverview-tab-los").removeClass("vri-ratesoverview-tab-active").addClass("vri-ratesoverview-tab-unactive");
		jQuery(".vri-ratesoverview-itemsel-entry-los").hide();
		jQuery(".vri-ratesoverview-lostab-cont").hide();
		jQuery(".vri-ratesoverview-caltab-cont").fadeIn();
	});
	if (window.location.hash == '#tabcal') {
		jQuery(".vri-ratesoverview-tab-cal").trigger("click");
	}
	jQuery("body").on("click", ".vri-ratesoverview-numday", function() {
		var inpday = jQuery(this).attr('id');
		if (jQuery('.vri-ratesoverview-numday').length > 1) {
			jQuery('#inp'+inpday).remove();
			jQuery(this).remove();
		}
	});
	jQuery("body").on("dblclick", ".vri-calcrates-rateblock", function() {
		if (jQuery(this).parent('.vri-ratesoverview-calculation-response-item').find('.vri-calcrates-rateblock').length < 2) {
			// remove the whole container as there is just one rate plan
			jQuery(this).parent('.vri-ratesoverview-calculation-response-item').remove();
		} else {
			// remove only this rate plan
			jQuery(this).remove();
		}
	});
	jQuery('#vri-addnumnight-act').click(function() {
		var setdays = jQuery('#vri-addnumnight').val();
		if (parseInt(setdays) > 0) {
			var los_exists = false;
			jQuery('.vri-ratesoverview-numday').each(function() {
				if (parseInt(jQuery(this).text()) == parseInt(setdays)) {
					los_exists = true;
				}
			});
			if (!los_exists) {
				jQuery('.vri-ratesoverview-numday').last().after("<span class=\"vri-ratesoverview-numday\" id=\"numdays"+setdays+"\">"+setdays+"</span><input type=\"hidden\" name=\"days_cal[]\" id=\"inpnumdays"+setdays+"\" value=\""+setdays+"\" />");
			} else {
				jQuery('#vri-addnumnight').val((parseInt(setdays) + 1));
			}
		}
	});
	jQuery('#vri-ratesoverview-calculate').click(function() {
		jQuery(this).text('<?php echo addslashes(JText::translate('VRIRATESOVWRATESCALCULATORCALCING')); ?>').prop('disabled', true);
		var pickupdate = jQuery("#pickupdate").val();
		if (!(pickupdate.length > 0)) {
			pickupdate = '<?php echo date('Y-m-d') ?>';
			jQuery("#pickupdate").val(pickupdate);
		}
		var days = jQuery("#vri-numdays").val();
		var iditem = jQuery("#itemselcalc").val();
		// always remove warning messages
		jQuery(".vri-ratesoverview-calculation-response").find('.vri-warning').remove();
		if (jQuery("#vri-ratesoverview-calculation-response-item"+iditem).length) {
			// remove previous containers for this item
			jQuery("#vri-ratesoverview-calculation-response-item"+iditem).remove();
		}
		if (!jQuery(".vri-ratesoverview-calculation-response-item").length) {
			// if no items responses, empty the whole container
			jQuery('.vri-ratesoverview-calculation-response').html('');
		}
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikrentitems", task: "calc_rates", tmpl: "component", id_item: iditem, pickup: pickupdate, num_days: days }
		}).done(function(res) {
			res = JSON.parse(res);
			res = res[0];
			if (res.indexOf('e4j.error') >= 0 ) {
				jQuery(".vri-ratesoverview-calculation-response").html("<p class='vri-warning'>" + res.replace("e4j.error.", "") + "</p>").fadeIn();
			} else {
				var titlecont = '<span class="vri-ratesoverview-calculation-response-item-name">'+jQuery("#itemselcalc option:selected").text() + '</span> ' + pickupdate + ', ' + days + ' <?php echo addslashes(JText::translate('VRDAYS')); ?>';
				var newcont = '<div class="vri-ratesoverview-calculation-response-item" id="vri-ratesoverview-calculation-response-item'+iditem+'"><h4>'+titlecont+'</h4>'+res+'</div>';
				// check whether the content should be appended
				if (jQuery(".vri-ratesoverview-calculation-response").find('.vri-ratesoverview-calculation-response-item').length) {
					newcont = jQuery(".vri-ratesoverview-calculation-response").html() + newcont;
				}
				//
				jQuery(".vri-ratesoverview-calculation-response").html(newcont).fadeIn();
				// loop over every item response and pricing to append the book-now button for the page calendar
				var base_booknow_link_orig = jQuery('#vri-base-booknow-link').attr('href');
				jQuery('.vri-calcrates-rateblock').each(function(k, v) {
					var elem = jQuery(v);
					var base_booknow_link = base_booknow_link_orig;
					// remove existing button
					elem.find('.vri-item-booknow-rct').remove();
					//
					var b_idprice = elem.attr('data-idprice');
					base_booknow_link = base_booknow_link.replace('idprice=', 'idprice=' + b_idprice);
					var b_iditem = elem.attr('data-iditem');
					base_booknow_link = base_booknow_link.replace('cid[]=', 'cid[]=' + b_iditem);
					var b_pickup = elem.attr('data-pickup');
					base_booknow_link = base_booknow_link.replace('pickup=', 'pickup=' + b_pickup);
					var b_dropoff = elem.attr('data-dropoff');
					base_booknow_link = base_booknow_link.replace('dropoff=', 'dropoff=' + b_dropoff);
					var booknow = '<a href="' + base_booknow_link + '" class="btn btn-primary vri-item-booknow-rct" target="_blank"><?php echo addslashes(JText::translate('VRIBOOKNOW')); ?></a>';
					elem.append(booknow);
				});
				//
			}
			jQuery('#vri-ratesoverview-calculate').text('<?php echo addslashes(JText::translate('VRIRATESOVWRATESCALCULATORCALC')); ?>').prop('disabled', false);
		}).fail(function() { 
			jQuery(".vri-ratesoverview-calculation-response").fadeOut();
			jQuery('#vri-ratesoverview-calculate').text('<?php echo addslashes(JText::translate('VRIRATESOVWRATESCALCULATORCALC')); ?>').prop('disabled', false);
			alert("Error Performing Ajax Request"); 
		});
	});
});
</script>
