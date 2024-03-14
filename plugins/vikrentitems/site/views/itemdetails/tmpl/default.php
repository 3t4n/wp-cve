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

$item = $this->item;
$busy = $this->busy;
$discounts = $this->discounts;
$timeslots = $this->timeslots;
$vri_tn = $this->vri_tn;

$pitemid = VikRequest::getInt('Itemid', '', 'request');

$document = JFactory::getDocument();
$calendartype = VikRentItems::calendarType();
//load jQuery lib e jQuery UI
if (VikRentItems::loadJquery()) {
	JHtml::fetch('jquery.framework', true, true);
	JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-1.12.4.min.js');
}
if ($calendartype == "jqueryui") {
	$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
	//load jQuery UI
	JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.min.js');
}
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery.fancybox.css');
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery.fancybox.js');
$navdecl = '
jQuery(document).ready(function() {
	jQuery(\'.vrimodal[data-fancybox="gallery"]\').fancybox({});
	jQuery(".vrimodalframe").fancybox({
		type: "iframe",
		iframe: {
			css: {
				width: "75%",
				height: "75%"
			}
		}
	});
});';
$document->addScriptDeclaration($navdecl);
//

$currencysymb = VikRentItems::getCurrencySymb();
$showpartlyres = VikRentItems::showPartlyReserved();
$numcalendars = VikRentItems::numCalendars();
$item_params = !empty($item['jsparams']) ? json_decode($item['jsparams'], true) : array();
$carats = VikRentItems::getItemCaratOriz($item['idcarat'], $vri_tn);

$session = JFactory::getSession();
$vrisessioncart = $session->get('vriCart', '');
$vrisesspickup = $session->get('vripickupts', '');
$vrisessdropoff = $session->get('vrireturnts', '');
$vrisessdays = $session->get('vridays', '');
$vrisesspickuploc = $session->get('vriplace', '');
$vrisessdropoffloc = $session->get('vrireturnplace', '');

$vridateformat = VikRentItems::getDateFormat();
$nowtf = VikRentItems::getTimeFormat();
if ($vridateformat == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($vridateformat == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
$nowts = mktime(0, 0, 0, date('n'), date('j'), date('Y'));

?>
<div class="vri-page-content">
<?php
echo VikRentItems::getFullFrontTitle($vri_tn);
?>
	<div class="vri-itemdet-groupblocks">
		<div class="vri-itemdet-groupleft">
			<div class="vri-itemdet-imagesblock">
<?php
if (!empty($item['img'])) {
	?>
				<div class="vri-itemdet-mainimage">
					<img src="<?php echo VRI_ADMIN_URI.'resources/'.$item['img']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"/>
				</div>
	<?php
}
if (strlen($item['moreimgs']) > 0) {
	$moreimages = explode(';;', $item['moreimgs']);
	?>
				<div class="vri-itemdet-extraimages">
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

		<div class="vri-itemdet-groupright">
			<div class="vri-itemdet-infoblock">
				<div class="vri-itemdet-infocat">
					<span><?php echo VikRentItems::sayCategory($item['idcat'], $vri_tn); ?></span>
				</div>
				<div class="vri-itemdet-infoname">
					<span><?php echo $item['name']; ?></span>
				</div>
			</div>
			<div class="vri-itemdet-descr">
<?php
if (!empty($item['info'])) {
	/**
	 * @wponly 	we try to parse any shortcode inside the HTML description of the item
	 */
	echo do_shortcode(wpautop($item['info']));
}
?>
			</div>
<?php
if (strlen($carats)) {
	?>
			<div class="vri-itemdet-carats"><?php echo $carats; ?></div>
	<?php
}
if ($item['isgroup'] > 0 && count($this->kit_relations) > 0) {
	?>
			<div class="vri-itemdet-kitrelations">
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
if ($item['cost'] > 0) {
	?>
			<div class="vri-itemdet-priceblock">
				<span class="vri-itemdet-price-startfrom"><?php echo JText::translate('VRILISTSFROM'); ?></span>
				<span class="vri-itemdet-price-cost"><?php echo $currencysymb; ?> <?php echo strlen($item['startfrom']) > 0 ? VikRentItems::numberFormat($item['startfrom']) : VikRentItems::numberFormat($item['cost']); ?></span>
				<span class="vri-itemdet-price-fromtext"><?php echo JText::translate(VikRentItems::getItemParam($item['params'], 'startfromtext')); ?></span>
			</div>
	<?php
}
?>
		</div>
	</div>
<?php

$pmonth = VikRequest::getInt('month', '', 'request');
$pday = VikRequest::getInt('dt', '', 'request');

$viewingdayts = !empty($pday) && $pday >= $nowts ? $pday : $nowts;
$show_hourly_cal = (intval(VikRentItems::getItemParam($item['params'], 'hourlycalendar')) == 1);

$arr = getdate();
$mon = $arr['mon'];
$realmon = ($mon < 10 ? "0".$mon : $mon);
$year = $arr['year'];
$day = $realmon."/01/".$year;
$dayts = strtotime($day);
$validmonth = false;
if ($pmonth > 0 && $pmonth >= $dayts) {
	$validmonth = true;
}
$moptions = "";
for ($i = 0; $i < 12; $i++) {
	$moptions .= "<option value=\"".$dayts."\"".($validmonth && $pmonth == $dayts ? " selected=\"selected\"" : "").">".VikRentItems::sayMonth($arr['mon'])." ".$arr['year']."</option>\n";
	$next = $arr['mon'] + 1;
	$dayts = mktime(0, 0, 0, $next, 1, $arr['year']);
	$arr = getdate($dayts);
}

if ($numcalendars > 0) {
?>
	<div id="vri-bookingpart-init"></div>

	<div class="vri-itemdet-monthslegend">
		<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=itemdetails&elemid='.$item['id'], false); ?>" method="post" name="vrimonths">
			<select name="month" onchange="javascript: document.vrimonths.submit();" class="vriselectm"><?php echo $moptions; ?></select>
		</form>

		<div class="vrilegendediv">
			<span class="vrilegenda">
				<span class="vrilegfree">&nbsp;</span>
				<span class="vri-leg-text"><?php echo JText::translate('VRLEGFREE'); ?></span>
			</span>
			<?php
			if ($showpartlyres) {
			?>
			<span class="vrilegenda">
				<span class="vrilegwarning">&nbsp;</span>
				<span class="vri-leg-text"><?php echo JText::translate('VRLEGWARNING'); ?></span>
			</span>
			<?php
			}
			?>
			<span class="vrilegenda">
				<span class="vrilegbusy">&nbsp;</span>
				<span class="vri-leg-text"><?php echo JText::translate(($show_hourly_cal ? 'VRLEGBUSYCHECKH' : 'VRLEGBUSY')); ?></span>
			</span>
		</div>

	</div>
<?php
}

$check = false;
if (is_array($busy)) {
	$check = true;
}
if ($validmonth) {
	$arr = getdate($pmonth);
	$mon = $arr['mon'];
	$realmon = ($mon < 10 ? "0".$mon : $mon);
	$year = $arr['year'];
	$day = $realmon."/01/".$year;
	$dayts = strtotime($day);
	$newarr = getdate($dayts);
} else {
	$arr = getdate();
	$mon = $arr['mon'];
	$realmon = ($mon < 10 ? "0".$mon : $mon);
	$year = $arr['year'];
	$day = $realmon."/01/".$year;
	$dayts = strtotime($day);
	$newarr = getdate($dayts);
}

$firstwday = (int)VikRentItems::getFirstWeekDay();
$days_labels = array(
	JText::translate('VRSUN'),
	JText::translate('VRMON'),
	JText::translate('VRTUE'),
	JText::translate('VRWED'),
	JText::translate('VRTHU'),
	JText::translate('VRFRI'),
	JText::translate('VRSAT')
);
$days_indexes = array();
for ($i = 0; $i < 7; $i++) {
	$days_indexes[$i] = (6-($firstwday-$i)+1)%7;
}

$push_disabled_in = array();
$push_disabled_out = array();
$previousdayclass = "";
$lastdropoff = 0;
$unitsadjuster = 0;

?>
	<div class="vri-avcals-container">
<?php
$mindaysadv = VikRentItems::getMinDaysAdvance();
$lim_mindays = $mindaysadv > 0 ? ($mindaysadv > 1 ? strtotime("+$mindaysadv days", $nowts) : strtotime("+1 day", $nowts)) : $nowts;
for ($jj = 1; $jj <= $numcalendars; $jj++) {
	$d_count = 0;
	$cal = "";
	?>
		<div class="vricaldivcont">
			<table class="vrical">
				<tr><td colspan="7" align="center" class="vriitdetmonthnam"><strong><?php echo VikRentItems::sayMonth($newarr['mon'])." ".$newarr['year']; ?></strong></td></tr>
				<tr class="vricaldays">
	<?php
	for ($i = 0; $i < 7; $i++) {
		$d_ind = ($i + $firstwday) < 7 ? ($i + $firstwday) : ($i + $firstwday - 7);
		echo '<td>'.$days_labels[$d_ind].'</td>';
	}
	?>
				</tr>
				<tr>
	<?php
	for ($i=0, $n = $days_indexes[$newarr['wday']]; $i < $n; $i++, $d_count++) {
		$cal .= "<td align=\"center\">&nbsp;</td>";
	}
	while ($newarr['mon']==$mon) {
		if ($d_count > 6) {
			$d_count = 0;
			$cal .= "</tr>\n<tr>";
		}
		$dclass = "vritdfree";
		$dalt = "";
		$bid = "";
		$totfound = 0;
		if ($check) {
			$ischeckinday = false;
			$ischeckoutday = false;
			$lastfoundritts = 0;
			$lastfoundconts = -1;
			$lasttotfound = 0;
			foreach ($busy as $b) {
				$tmpone = getdate($b['ritiro']);
				$ritts = mktime(0, 0, 0, $tmpone['mon'], $tmpone['mday'], $tmpone['year']);
				$tmptwo = getdate($b['consegna']);
				$conts = mktime(0, 0, 0, $tmptwo['mon'], $tmptwo['mday'], $tmptwo['year']);
				if ($newarr[0] >= $ritts && $newarr[0] <= $conts) {
					$totfound++;
					if ($newarr[0] == $ritts) {
						$lastfoundritts = $ritts;
						$lastfoundconts = $conts;
						if ($lastfoundritts != $lastfoundconts) {
							$lasttotfound++;
						}
						$ischeckinday = true;
					} elseif ($newarr[0] == $conts) {
						$ischeckoutday = true;
						$lastdropoff = $b['realback'];
					}
					/**
					 * Situation for an item with 2 units:
					 * Order #1: 2020-04-03 09:00:00  -  2020-04-10 10:00:00
					 * Order #2: 2020-04-10 12:00:00  -  2020-04-15 13:00:00
					 * We should not disable April 10th for pick up as we have
					 * a possible pick up window if made i.e. at 2020-04-10 11:00
					 * This is only valid for the datepicker calendars, the availability
					 * calendars should keep displaying the date in red for the full day.
					 * 
					 * @since 	1.7
					 */
					if ($ischeckinday && !empty($lastdropoff) && $lastdropoff <= $b['ritiro']) {
						$unitsadjuster++;
					}
					//
					if (!empty($b['closure'])) {
						$totfound = $item['units'];
						$unitsadjuster = 0;
						break;
					}
				}
			}
			if ($totfound >= $item['units']) {
				$dclass = "vritdbusy";
				if ($ischeckinday || !$ischeckoutday) {
					// VRI 1.7 hourly rentals: do not disable the day from the date-picker if units are 1 and pick up base ts & drop off base ts are equal because another hourly rental may be allowed.
					if ($lasttotfound > 1 || $lastfoundritts != $lastfoundconts) {
						/**
						 * Situation for an item with 2 units:
						 * Order #1: 2020-04-03 09:00:00  -  2020-04-10 10:00:00
						 * Order #2: 2020-04-10 12:00:00  -  2020-04-15 13:00:00
						 * We should not disable April 10th for pick up as we have
						 * a possible pick up window if made i.e. at 2020-04-10 11:00
						 * This is only valid for the datepicker calendars, the availability
						 * calendars should keep displaying the date in red for the full day.
						 * 
						 * @since 	1.7
						 */
						if (($totfound - $unitsadjuster) >= $item['units']) {
							$push_disabled_in[] = '"'.date('Y-m-d', $newarr[0]).'"';
						}
					}
					//
				}
				if ($ischeckinday && $previousdayclass != "vritdbusy") {
					$dclass = "vritdbusy vritdbusyforcheckin";
				}
				if (!$ischeckinday && !$ischeckoutday) {
					$push_disabled_out[] = '"'.date('Y-m-d', $newarr[0]).'"';
				}
			} elseif ($totfound > 0) {
				if ($showpartlyres) {
					$dclass = "vritdwarning";
				}
			}
		}
		$previousdayclass = $dclass;
		$useday = ($newarr['mday'] < 10 ? "0".$newarr['mday'] : $newarr['mday']);
		//link for opening the hourly availability of the day
		if ($newarr[0] >= $nowts && $newarr[0] >= $lim_mindays) {
			if ($show_hourly_cal) {
				$useday = '<a href="'.JRoute::rewrite('index.php?option=com_vikrentitems&view=itemdetails&elemid='.$item['id'].'&dt='.$newarr[0].(!empty($pmonth) && $validmonth ? '&month='.$pmonth : '').(!empty($pitemid) ? '&Itemid='.$pitemid : '')).'">'.$useday.'</a>';
			} else {
				/**
				 * With no hourly calendar it is useless to reload the page just to select a pick up date. We use JS instead.
				 * 
				 * @since 	1.7
				 */
				$useday = '<span class="vri-idetails-cal-pickday" data-daydate="' . date($df, $newarr[0]) . '">' . $useday . '</span>';
			}
		} else {
			$useday = '<span class="vri-avcal-spday">'.$useday.'</span>';
		}
		//
		if ($totfound == 1) {
			$cal .= "<td align=\"center\" data-fulldate=\"".date('Y-n-j', $newarr[0])."\" data-weekday=\"".$newarr['wday']."\" class=\"".$dclass."\">".$useday."</td>\n";
		} elseif ($totfound > 1) {
			$cal .= "<td align=\"center\" data-fulldate=\"".date('Y-n-j', $newarr[0])."\" data-weekday=\"".$newarr['wday']."\" class=\"".$dclass."\">".$useday."</td>\n";
		} else {
			$cal .= "<td align=\"center\" data-fulldate=\"".date('Y-n-j', $newarr[0])."\" data-weekday=\"".$newarr['wday']."\" class=\"".$dclass."\">".$useday."</td>\n";
		}
		$next = $newarr['mday'] + 1;
		$dayts = mktime(0, 0, 0, $newarr['mon'], $next, $newarr['year']);
		$newarr = getdate($dayts);
		$d_count++;
	}

	for ($i = $d_count; $i <= 6; $i++) {
		$cal .= "<td align=\"center\">&nbsp;</td>";
	}
	
	echo $cal;
	?>
				</tr>
			</table>
		</div>
	<?php
	if ($mon == 12) {
		$mon = 1;
		$year += 1;
		$dayts = mktime(0, 0, 0, $mon, 1, $year);
	} else {
		$mon += 1;
		$dayts = mktime(0, 0, 0, $mon, 1, $year);
	}
	$newarr = getdate($dayts);
	
	if (($jj % 3) == 0) {
		echo "";
	}
}
?>
	</div>

<?php
if ($show_hourly_cal) {
	// VRI 1.6 - Allow pick ups on drop offs
	$picksondrops = VikRentItems::allowPickOnDrop();
	//
?>
	<div class="vri-hourlycal-container">
		<h4 class="vri-medium-header"><?php echo JText::sprintf('VRIAVAILSINGLEDAY', date($df, $viewingdayts)); ?></h4>
		<div class="table-responsive">
			<table class="vrical table">
				<tr>
					<td style="text-align: center;"><?php echo JText::translate('VRILEGH'); ?></td>
<?php
for ($h = 0; $h <= 23; $h++) {
	if ($nowtf == 'H:i') {
		$sayh = $h < 10 ? "0".$h : $h;
	} else {
		$ampm = $h < 12 ? ' am' : ' pm';
		$ampmh = $h > 12 ? ($h - 12) : $h;
		$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
	}
	?>
					<td style="text-align: center;"><?php echo $sayh; ?></td>
	<?php
}
?>
				</tr>
				<tr>
					<td style="text-align: center;"><?php echo JText::translate('VRILEGU'); ?></td>
<?php
for ($h = 0; $h <= 23; $h++) {
	$checkhourts = ($viewingdayts + ($h * 3600));
	$dclass = "vritdfree";
	$dalt = "";
	$bid = "";
	if ($check) {
		$totfound = 0;
		foreach ($busy as $b) {
			$tmpone = getdate($b['ritiro']);
			$rit = ($tmpone['mon'] < 10 ? "0".$tmpone['mon'] : $tmpone['mon'])."/".($tmpone['mday'] < 10 ? "0".$tmpone['mday'] : $tmpone['mday'])."/".$tmpone['year'];
			$ritts = strtotime($rit);
			$tmptwo = getdate($b['consegna']);
			$con = ($tmptwo['mon'] < 10 ? "0".$tmptwo['mon'] : $tmptwo['mon'])."/".($tmptwo['mday'] < 10 ? "0".$tmptwo['mday'] : $tmptwo['mday'])."/".$tmptwo['year'];
			$conts = strtotime($con);
			if ($viewingdayts >= $ritts && $viewingdayts <= $conts) {
				if ($checkhourts >= $b['ritiro'] && $checkhourts <= $b['consegna']) {
					if ($picksondrops && !($checkhourts > $b['ritiro'] && $checkhourts < $b['consegna']) && $checkhourts == $b['consegna']) {
						// VRI 1.6 - pick ups on drop offs allowed
						continue;
					}
					$totfound++;
				}
			}
		}
		if ($totfound >= $item['units']) {
			$dclass = "vritdbusy";
		} elseif ($totfound > 0) {
			if ($showpartlyres) {
				$dclass = "vritdwarning";
			}
		}
		$hourlydisp = $item['units'] - $totfound;
		$hourlydisp = $hourlydisp < 0 ? 0 : $hourlydisp;
	} else {
		$hourlydisp = $item['units'];
	}
	?>
					<td style="text-align: center;" class="<?php echo $dclass; ?>"><?php echo $hourlydisp; ?></td>
	<?php
}
?>
				</tr>
			</table>
		</div>
	</div>
<?php
}
?>
	<div class="vri-bookform-container">
		<h4 class="vri-medium-header"><?php echo JText::translate('VRISELECTPDDATES'); ?></h4>
<?php

if (count($discounts) > 0) {
	?>
		<div class="vridiscsquantsdiv">
			<table class="vridiscsquantstable">
				<tr class="vridiscsquantstrfirst"><td><?php echo JText::translate('VRIDISCSQUANTSQ'); ?></td><td><?php echo JText::translate('VRIDISCSQUANTSSAVE'); ?></td></tr>
				<?php
				foreach ($discounts as $kd => $disc) {
					$discval = substr($disc['diffcost'], -2) == '00' ? number_format($disc['diffcost'], 0) : VikRentItems::numberFormat($disc['diffcost']);
					$savedisc = $disc['val_pcent'] == 1 ? $discval.' '.$currencysymb : $discval.'%';
					$disc_keys = array_keys($discounts);
					?>
				<tr class="vridiscsquantstrentry">
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

if (VikRentItems::allowRent()) {
	$dbo = JFactory::getDbo();

	$restrictions = VikRentItems::loadRestrictions(true, array($item['id']));
	$def_min_los = VikRentItems::setDropDatePlus();
	
	$deliveryservicetext = '';
	if (intval(VikRentItems::getItemParam($item['params'], 'delivery')) == 1) {
		$deliveryservicetext = '<span class="vrideliveryservicespan">'.JText::translate('VRIDELIVERYSERVICEAVLB').'</span>';
	}
	
	$coordsplaces = array();
	/**
	 * @wponly 	we use the POST method for the form
	 */
	$selform = "<div class=\"vridivsearch vri-main-search-form\">".$deliveryservicetext."<form action=\"".JRoute::rewrite('index.php?option=com_vikrentitems'.(!empty($pitemid) ? '&Itemid='.$pitemid : ''))."\" method=\"post\" onsubmit=\"return vriValidateSearch();\"><div class=\"vricalform\">\n";
	$selform .= "<input type=\"hidden\" name=\"option\" value=\"com_vikrentitems\"/>\n";
	$selform .= "<input type=\"hidden\" name=\"task\" value=\"search\"/>\n";
	$selform .= "<input type=\"hidden\" name=\"itemdetail\" value=\"".$item['id']."\"/>\n";

	$diffopentime = false;
	$declglobclosingdays = '';
	$globalclosingdays = VikRentItems::getGlobalClosingDays();
	if (is_array($globalclosingdays)) {
		if (count($globalclosingdays['singleday']) > 0) {
			$gscdarr = array();
			foreach ($globalclosingdays['singleday'] as $kgcs => $gcdsd) {
				$gscdarr[] = '"'.date('Y-n-j', $gcdsd).'"';
			}
			$gscdarr = array_unique($gscdarr);
			$declglobclosingdays .= 'var vriglobclosingsdays = ['.implode(", ", $gscdarr).'];'."\n";
		} else {
			$declglobclosingdays .= 'var vriglobclosingsdays = ["-1"];'."\n";
		}
		if (count($globalclosingdays['weekly']) > 0) {
			$gwcdarr = array();
			foreach ($globalclosingdays['weekly'] as $kgcw => $gcdwd) {
				$moregcdinfo = getdate($gcdwd);
				$gwcdarr[] = '"'.$moregcdinfo['wday'].'"';
			}
			$gwcdarr = array_unique($gwcdarr);
			$declglobclosingdays .= 'var vriglobclosingwdays = ['.implode(", ", $gwcdarr).'];'."\n";
		} else {
			$declglobclosingdays .= 'var vriglobclosingwdays = ["-1"];'."\n";
		}
		$declglobclosingdays .= '
jQuery(document).ready(function() {
	var gscdlen = vriglobclosingsdays.length;
	var gwcdlen = vriglobclosingwdays.length;
	for (var l = 0; l < gscdlen; l++) {
		var tdcheck = jQuery("td[data-fulldate=\'"+vriglobclosingsdays[l]+"\']");
		if (tdcheck.length) {
			tdcheck.addClass("vritdclosedday").attr("title", "'.addslashes(JText::translate('VRIGLOBDAYCLOSED')).'");
			if (tdcheck.find("a").length) {
				tdcheck.find("a").attr("href", "Javascript: void(0);");
			}
		}
	}
	for (var l = 0; l < gwcdlen; l++) {
		var tdcheck = jQuery("td[data-weekday=\'"+vriglobclosingwdays[l]+"\']");
		if (tdcheck.length) {
			tdcheck.addClass("vritdclosedday").attr("title", "'.addslashes(JText::translate('VRIGLOBDAYCLOSED')).'");
			tdcheck.each(function() {
				if (jQuery(this).find("a").length) {
					jQuery(this).find("a").attr("href", "Javascript: void(0);");
				}
			});
		}
	}
});
function vriGlobalClosingDays(date) {
	var gdmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var gwd = date.getDay();
	gwd = gwd.toString();
	var checksdarr = window["vriglobclosingsdays"];
	var checkwdarr = window["vriglobclosingwdays"];
	if (jQuery.inArray(gdmy, checksdarr) == -1 && jQuery.inArray(gwd, checkwdarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::translate('VRIGLOBDAYCLOSED')).'"];
	}
}';
		$document->addScriptDeclaration($declglobclosingdays);
	}

	if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
		$selform .= "<div class=\"vrisfentry vri-search-sessvals\"><label class=\"vripickdroplab\">" . JText::translate('VRPICKUPITEM') . "</label><span class=\"vridtsp\"><input type=\"hidden\" name=\"pickupdate\" value=\"".date($df, $vrisesspickup)."\"/>".date($df, $vrisesspickup)." " . (!empty($nowtf) ? JText::translate('VRALLE') : '') . " <input type=\"hidden\" name=\"pickuph\" value=\"".date('H', $vrisesspickup)."\"/>".(!empty($nowtf) ? date('H', $vrisesspickup).":" : '')."<input type=\"hidden\" name=\"pickupm\" value=\"".date('i', $vrisesspickup)."\"/>".(!empty($nowtf) ? date('i', $vrisesspickup) : '')."</span></div>\n";
		$selform .= "<div class=\"vrisfentry vri-search-sessvals\"><label class=\"vripickdroplab\">" . JText::translate('VRRETURNITEM') . "</label><span class=\"vridtsp\"><input type=\"hidden\" name=\"releasedate\" value=\"".date($df, $vrisessdropoff)."\"/>".date($df, $vrisessdropoff)." " . (!empty($nowtf) ? JText::translate('VRALLE') : '') . " <input type=\"hidden\" name=\"releaseh\" value=\"".date('H', $vrisessdropoff)."\"/>".(!empty($nowtf) ? date('H', $vrisessdropoff).":" : '')."<input type=\"hidden\" name=\"releasem\" value=\"".date('i', $vrisessdropoff)."\"/>".(!empty($nowtf) ? date('i', $vrisessdropoff) : '')."</span></div>";
	}

	if (VikRentItems::showPlacesFront()) {
		$actlocs = explode(";", $item['idplace']);
		$actretlocs = explode(";", $item['idretplace']);
		$actlocsall = array_merge($actlocs, $actretlocs);
		$actlocsall = array_unique($actlocsall);
		$clauselocs = array();
		foreach ($actlocsall as $ala) {
			if (!empty($ala)) {
				$clauselocs[] = $ala;
			}
		}
		if (count($clauselocs)) {
			$q = "SELECT * FROM `#__vikrentitems_places` WHERE `id` IN (".implode(",", $clauselocs).") ORDER BY `#__vikrentitems_places`.`name` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$places = $dbo->loadAssocList();
				$vri_tn->translateContents($places, '#__vikrentitems_places');
				//check if some place has a different opening time (1.1)
				foreach ($places as $pla) {
					if (!empty($pla['opentime'])) {
						$diffopentime = true;
						break;
					}
				}
				$onchangeplaces = $diffopentime == true ? " onchange=\"javascript: vriSetLocOpenTime(this.value, 'pickup');\"" : " onchange=\"javascript: vriSetSameDropLoc(this.value);\"";
				$onchangeplacesdrop = $diffopentime == true ? " onchange=\"javascript: vriSetLocOpenTime(this.value, 'dropoff');\"" : "";
				if ($diffopentime == true) {
					$onchangedecl = '
function vriSetLocOpenTime(loc, where) {
	jQuery.ajax({
		type: "POST",
		url: "'.JRoute::rewrite('index.php?option=com_vikrentitems&task=ajaxlocopentime&tmpl=component').'",
		data: { idloc: loc, pickdrop: where }
	}).done(function(res) {
		var vriobj = JSON.parse(res);
		if (where == "pickup") {
			jQuery("#vricomselph").html(vriobj.hours);
			jQuery("#vricomselpm").html(vriobj.minutes);
		} else {
			jQuery("#vricomseldh").html(vriobj.hours);
			jQuery("#vricomseldm").html(vriobj.minutes);
		}
	});
}';
					$document->addScriptDeclaration($onchangedecl);
				} else {
					$onchangedecl = '
function vriSetSameDropLoc(loc) {
	var droplocsel = document.getElementById("returnplace");
	for (var i = 0; i < droplocsel.length; i++) {
		if (parseInt(droplocsel.options[i].value) == parseInt(loc)) {
			droplocsel.options[i].selected = true;
			break;
		}
	}
}';
					$document->addScriptDeclaration($onchangedecl);
				}
				//end check if some place has a different opningtime (1.1)
				$selform .= "<div class=\"vrisfentry\"><label for=\"place\">" . JText::translate('VRPPLACE') . "</label><span class=\"vriplacesp\"><select name=\"place\" id=\"place\"".$onchangeplaces.">";
				foreach ($places as $pla) {
					if (in_array($pla['id'], $actlocs)) {
						$selform .= "<option value=\"" . $pla['id'] . "\" id=\"place".$pla['id']."\">" . $pla['name'] . "</option>\n";
						if (!empty($pla['lat']) && !empty($pla['lng'])) {
							$coordsplaces[] = $pla;
						}
					}
				}
				$selform .= "</select></span></div>\n";
			}
		}
	}
	
	if ($diffopentime == true && is_array($places) && strlen($places[0]['opentime']) > 0) {
		$parts = explode("-", $places[0]['opentime']);
		if (is_array($parts) && $parts[0] != $parts[1]) {
			$opent = VikRentItems::getHoursMinutes($parts[0]);
			$closet = VikRentItems::getHoursMinutes($parts[1]);
			$i = $opent[0];
			$imin = $opent[1];
			$j = $closet[0];
		} else {
			$i = 0;
			$imin = 0;
			$j = 23;
		}
	} else {
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
	while ($i <= $j) {
		//VRI 1.3
		$sayi = $i < 10 ? "0".$i : $i;
		if ($nowtf != 'H:i') {
			$ampm = $i < 12 ? ' am' : ' pm';
			$ampmh = $i > 12 ? ($i - 12) : $i;
			$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
		} else {
			$sayh = $i;
		}
		$hours .= "<option value=\"" . $sayi . "\">" . $sayh . "</option>\n";
		//
		$i++;
	}
	$minutes = "";
	for ($i = 0; $i < 60; $i += 15) {
		if ($i < 10) {
			$i = "0" . $i;
		} else {
			$i = $i;
		}
		$minutes .= "<option value=\"" . $i . "\"".((int)$i == $imin ? " selected=\"selected\"" : "").">" . $i . "</option>\n";
	}
	
	//vikrentitems 1.2
	$forcedpickdroptimes = VikRentItems::getForcedPickDropTimes();
	if ($calendartype == "jqueryui" || true) {
		if ($vridateformat == "%d/%m/%Y") {
			$juidf = 'dd/mm/yy';
		} elseif ($vridateformat == "%m/%d/%Y") {
			$juidf = 'mm/dd/yy';
		} else {
			$juidf = 'yy/mm/dd';
		}
		//lang for jQuery UI Calendar
		$ldecl = '
jQuery(function($) {'."\n".'
	$.datepicker.regional["vikrentitems"] = {'."\n".'
		closeText: "'.JText::translate('VRIJQCALDONE').'",'."\n".'
		prevText: "'.JText::translate('VRIJQCALPREV').'",'."\n".'
		nextText: "'.JText::translate('VRIJQCALNEXT').'",'."\n".'
		currentText: "'.JText::translate('VRIJQCALTODAY').'",'."\n".'
		monthNames: ["'.JText::translate('VRMONTHONE').'","'.JText::translate('VRMONTHTWO').'","'.JText::translate('VRMONTHTHREE').'","'.JText::translate('VRMONTHFOUR').'","'.JText::translate('VRMONTHFIVE').'","'.JText::translate('VRMONTHSIX').'","'.JText::translate('VRMONTHSEVEN').'","'.JText::translate('VRMONTHEIGHT').'","'.JText::translate('VRMONTHNINE').'","'.JText::translate('VRMONTHTEN').'","'.JText::translate('VRMONTHELEVEN').'","'.JText::translate('VRMONTHTWELVE').'"],'."\n".'
		monthNamesShort: ["'.mb_substr(JText::translate('VRMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWELVE'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNames: ["'.JText::translate('VRIJQCALSUN').'", "'.JText::translate('VRIJQCALMON').'", "'.JText::translate('VRIJQCALTUE').'", "'.JText::translate('VRIJQCALWED').'", "'.JText::translate('VRIJQCALTHU').'", "'.JText::translate('VRIJQCALFRI').'", "'.JText::translate('VRIJQCALSAT').'"],'."\n".'
		dayNamesShort: ["'.mb_substr(JText::translate('VRIJQCALSUN'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALMON'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALTUE'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALWED'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALTHU'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALFRI'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALSAT'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNamesMin: ["'.mb_substr(JText::translate('VRIJQCALSUN'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALMON'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALTUE'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALWED'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALTHU'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALFRI'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIJQCALSAT'), 0, 2, 'UTF-8').'"],'."\n".'
		weekHeader: "'.JText::translate('VRIJQCALWKHEADER').'",'."\n".'
		dateFormat: "'.$juidf.'",'."\n".'
		firstDay: '.VikRentItems::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikrentitems"]);'."\n".'
});
function vriGetDateObject(dstring) {
	var dparts = dstring.split("-");
	return new Date(dparts[0], (parseInt(dparts[1]) - 1), parseInt(dparts[2]), 0, 0, 0, 0);
}
function vriFullObject(obj) {
	var jk;
	for(jk in obj) {
		return obj.hasOwnProperty(jk);
	}
}
var vrirestrctarange, vrirestrctdrange, vrirestrcta, vrirestrctd;';
		$document->addScriptDeclaration($ldecl);
		//
		// VRI 1.7 - Restrictions Start
		$totrestrictions = count($restrictions);
		$wdaysrestrictions = array();
		$wdaystworestrictions = array();
		$wdaysrestrictionsrange = array();
		$wdaysrestrictionsmonths = array();
		$ctarestrictionsrange = array();
		$ctarestrictionsmonths = array();
		$ctdrestrictionsrange = array();
		$ctdrestrictionsmonths = array();
		$monthscomborestr = array();
		$minlosrestrictions = array();
		$minlosrestrictionsrange = array();
		$maxlosrestrictions = array();
		$maxlosrestrictionsrange = array();
		$notmultiplyminlosrestrictions = array();
		if ($totrestrictions > 0) {
			foreach ($restrictions as $rmonth => $restr) {
				if ($rmonth != 'range') {
					if (strlen($restr['wday']) > 0) {
						$wdaysrestrictions[] = "'".($rmonth - 1)."': '".$restr['wday']."'";
						$wdaysrestrictionsmonths[] = $rmonth;
						if (strlen($restr['wdaytwo']) > 0) {
							$wdaystworestrictions[] = "'".($rmonth - 1)."': '".$restr['wdaytwo']."'";
							$monthscomborestr[($rmonth - 1)] = VikRentItems::parseJsDrangeWdayCombo($restr);
						}
					} elseif (!empty($restr['ctad']) || !empty($restr['ctdd'])) {
						if (!empty($restr['ctad'])) {
							$ctarestrictionsmonths[($rmonth - 1)] = explode(',', $restr['ctad']);
						}
						if (!empty($restr['ctdd'])) {
							$ctdrestrictionsmonths[($rmonth - 1)] = explode(',', $restr['ctdd']);
						}
					}
					if ($restr['multiplyminlos'] == 0) {
						$notmultiplyminlosrestrictions[] = $rmonth;
					}
					$minlosrestrictions[] = "'".($rmonth - 1)."': '".$restr['minlos']."'";
					if (!empty($restr['maxlos']) && $restr['maxlos'] > 0 && $restr['maxlos'] > $restr['minlos']) {
						$maxlosrestrictions[] = "'".($rmonth - 1)."': '".$restr['maxlos']."'";
					}
				} else {
					foreach ($restr as $kr => $drestr) {
						if (strlen($drestr['wday']) > 0) {
							$wdaysrestrictionsrange[$kr][0] = date('Y-m-d', $drestr['dfrom']);
							$wdaysrestrictionsrange[$kr][1] = date('Y-m-d', $drestr['dto']);
							$wdaysrestrictionsrange[$kr][2] = $drestr['wday'];
							$wdaysrestrictionsrange[$kr][3] = $drestr['multiplyminlos'];
							$wdaysrestrictionsrange[$kr][4] = strlen($drestr['wdaytwo']) > 0 ? $drestr['wdaytwo'] : -1;
							$wdaysrestrictionsrange[$kr][5] = VikRentItems::parseJsDrangeWdayCombo($drestr);
						} elseif (!empty($drestr['ctad']) || !empty($drestr['ctdd'])) {
							$ctfrom = date('Y-m-d', $drestr['dfrom']);
							$ctto = date('Y-m-d', $drestr['dto']);
							if (!empty($drestr['ctad'])) {
								$ctarestrictionsrange[$kr][0] = $ctfrom;
								$ctarestrictionsrange[$kr][1] = $ctto;
								$ctarestrictionsrange[$kr][2] = explode(',', $drestr['ctad']);
							}
							if (!empty($drestr['ctdd'])) {
								$ctdrestrictionsrange[$kr][0] = $ctfrom;
								$ctdrestrictionsrange[$kr][1] = $ctto;
								$ctdrestrictionsrange[$kr][2] = explode(',', $drestr['ctdd']);
							}
						}
						$minlosrestrictionsrange[$kr][0] = date('Y-m-d', $drestr['dfrom']);
						$minlosrestrictionsrange[$kr][1] = date('Y-m-d', $drestr['dto']);
						$minlosrestrictionsrange[$kr][2] = $drestr['minlos'];
						if (!empty($drestr['maxlos']) && $drestr['maxlos'] > 0 && $drestr['maxlos'] > $drestr['minlos']) {
							$maxlosrestrictionsrange[$kr] = $drestr['maxlos'];
						}
					}
					unset($restrictions['range']);
				}
			}
			
			$resdecl = "
var vrirestrmonthswdays = [".implode(", ", $wdaysrestrictionsmonths)."];
var vrirestrmonths = [".implode(", ", array_keys($restrictions))."];
var vrirestrmonthscombojn = JSON.parse('".json_encode($monthscomborestr)."');
var vrirestrminlos = {".implode(", ", $minlosrestrictions)."};
var vrirestrminlosrangejn = JSON.parse('".json_encode($minlosrestrictionsrange)."');
var vrirestrmultiplyminlos = [".implode(", ", $notmultiplyminlosrestrictions)."];
var vrirestrmaxlos = {".implode(", ", $maxlosrestrictions)."};
var vrirestrmaxlosrangejn = JSON.parse('".json_encode($maxlosrestrictionsrange)."');
var vrirestrwdaysrangejn = JSON.parse('".json_encode($wdaysrestrictionsrange)."');
var vrirestrcta = JSON.parse('".json_encode($ctarestrictionsmonths)."');
var vrirestrctarange = JSON.parse('".json_encode($ctarestrictionsrange)."');
var vrirestrctd = JSON.parse('".json_encode($ctdrestrictionsmonths)."');
var vrirestrctdrange = JSON.parse('".json_encode($ctdrestrictionsrange)."');
var vricombowdays = {};
function vriRefreshDropoff(darrive) {
	if (vriFullObject(vricombowdays)) {
		var vritosort = new Array();
		for(var vrii in vricombowdays) {
			if (vricombowdays.hasOwnProperty(vrii)) {
				var vriusedate = darrive;
				vritosort[vrii] = vriusedate.setDate(vriusedate.getDate() + (vricombowdays[vrii] - 1 - vriusedate.getDay() + 7) % 7 + 1);
			}
		}
		vritosort.sort(function(da, db) {
			return da > db ? 1 : -1;
		});
		for(var vrinext in vritosort) {
			if (vritosort.hasOwnProperty(vrinext)) {
				var vrifirstnextd = new Date(vritosort[vrinext]);
				jQuery('#releasedate').datepicker( 'option', 'minDate', vrifirstnextd );
				jQuery('#releasedate').datepicker( 'setDate', vrifirstnextd );
				break;
			}
		}
	}
}
var vriDropMaxDateSet = false;
function vriSetMinDropoffDate () {
	var vriDropMaxDateSetNow = false;
	var minlos = ".(intval($def_min_los) > 0 ? $def_min_los : '0').";
	var maxlosrange = 0;
	var nowpickup = jQuery('#pickupdate').datepicker('getDate');
	var nowd = nowpickup.getDay();
	var nowpickupdate = new Date(nowpickup.getTime());
	vricombowdays = {};
	if (vriFullObject(vrirestrminlosrangejn)) {
		for (var rk in vrirestrminlosrangejn) {
			if (vrirestrminlosrangejn.hasOwnProperty(rk)) {
				var minldrangeinit = vriGetDateObject(vrirestrminlosrangejn[rk][0]);
				if (nowpickupdate >= minldrangeinit) {
					var minldrangeend = vriGetDateObject(vrirestrminlosrangejn[rk][1]);
					if (nowpickupdate <= minldrangeend) {
						minlos = parseInt(vrirestrminlosrangejn[rk][2]);
						if (vriFullObject(vrirestrmaxlosrangejn)) {
							if (rk in vrirestrmaxlosrangejn) {
								maxlosrange = parseInt(vrirestrmaxlosrangejn[rk]);
							}
						}
						if (rk in vrirestrwdaysrangejn && nowd in vrirestrwdaysrangejn[rk][5]) {
							vricombowdays = vrirestrwdaysrangejn[rk][5][nowd];
						}
					}
				}
			}
		}
	}
	var nowm = nowpickup.getMonth();
	if (vriFullObject(vrirestrmonthscombojn) && vrirestrmonthscombojn.hasOwnProperty(nowm)) {
		if (nowd in vrirestrmonthscombojn[nowm]) {
			vricombowdays = vrirestrmonthscombojn[nowm][nowd];
		}
	}
	if (jQuery.inArray((nowm + 1), vrirestrmonths) != -1) {
		minlos = parseInt(vrirestrminlos[nowm]);
	}
	nowpickupdate.setDate(nowpickupdate.getDate() + minlos);
	jQuery('#releasedate').datepicker( 'option', 'minDate', nowpickupdate );
	if (maxlosrange > 0) {
		var diffmaxminlos = maxlosrange - minlos;
		var maxdropoffdate = new Date(nowpickupdate.getTime());
		maxdropoffdate.setDate(maxdropoffdate.getDate() + diffmaxminlos);
		jQuery('#releasedate').datepicker( 'option', 'maxDate', maxdropoffdate );
		vriDropMaxDateSet = true;
		vriDropMaxDateSetNow = true;
	}
	if (nowm in vrirestrmaxlos) {
		var diffmaxminlos = parseInt(vrirestrmaxlos[nowm]) - minlos;
		var maxdropoffdate = new Date(nowpickupdate.getTime());
		maxdropoffdate.setDate(maxdropoffdate.getDate() + diffmaxminlos);
		jQuery('#releasedate').datepicker( 'option', 'maxDate', maxdropoffdate );
		vriDropMaxDateSet = true;
		vriDropMaxDateSetNow = true;
	}
	if (!vriFullObject(vricombowdays)) {
		jQuery('#releasedate').datepicker( 'setDate', nowpickupdate );
		if (!vriDropMaxDateSetNow && vriDropMaxDateSet === true) {
			// unset maxDate previously set
			jQuery('#releasedate').datepicker( 'option', 'maxDate', null );
		}
	} else {
		vriRefreshDropoff(nowpickup);
	}
}";
			
			if (count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0) {
				$resdecl .= "
var vrirestrwdays = {".implode(", ", $wdaysrestrictions)."};
var vrirestrwdaystwo = {".implode(", ", $wdaystworestrictions)."};
function vriIsDayDisabled(date) {
	if (!vriValidateCta(date)) {
		return [false];
	}
	var actd = jQuery.datepicker.formatDate('yy-mm-dd', date);
	".(strlen($declglobclosingdays) > 0 ? "var loc_closing = vriGlobalClosingDays(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	".(count($push_disabled_in) ? "var vri_fulldays = [".implode(', ', $push_disabled_in)."]; if (jQuery.inArray(actd, vri_fulldays) >= 0) {return [false];}" : "")."
	var m = date.getMonth(), wd = date.getDay();
	if (vriFullObject(vrirestrwdaysrangejn)) {
		for (var rk in vrirestrwdaysrangejn) {
			if (vrirestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrwdaysrangejn[rk][0]);
				if (date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrwdaysrangejn[rk][1]);
					if (date <= wdrangeend) {
						if (wd != vrirestrwdaysrangejn[rk][2]) {
							if (vrirestrwdaysrangejn[rk][4] == -1 || wd != vrirestrwdaysrangejn[rk][4]) {
								return [false];
							}
						}
					}
				}
			}
		}
	}
	if (vriFullObject(vrirestrwdays)) {
		if (jQuery.inArray((m+1), vrirestrmonthswdays) == -1) {
			return [true];
		}
		if (wd == vrirestrwdays[m]) {
			return [true];
		}
		if (vriFullObject(vrirestrwdaystwo)) {
			if (wd == vrirestrwdaystwo[m]) {
				return [true];
			}
		}
		return [false];
	}
	return [true];
}
function vriIsDayDisabledDropoff(date) {
	if (!vriValidateCtd(date)) {
		return [false];
	}
	var actd = jQuery.datepicker.formatDate('yy-mm-dd', date);
	".(strlen($declglobclosingdays) > 0 ? "var loc_closing = vriGlobalClosingDays(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	".(count($push_disabled_out) ? "var vri_fulldays = [".implode(', ', $push_disabled_out)."]; if (jQuery.inArray(actd, vri_fulldays) >= 0) {return [false];}" : "")."
	var m = date.getMonth(), wd = date.getDay();
	if (vriFullObject(vricombowdays)) {
		if (jQuery.inArray(wd, vricombowdays) != -1) {
			return [true];
		} else {
			return [false];
		}
	}
	if (vriFullObject(vrirestrwdaysrangejn)) {
		for (var rk in vrirestrwdaysrangejn) {
			if (vrirestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrwdaysrangejn[rk][0]);
				if (date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrwdaysrangejn[rk][1]);
					if (date <= wdrangeend) {
						if (wd != vrirestrwdaysrangejn[rk][2] && vrirestrwdaysrangejn[rk][3] == 1) {
							return [false];
						}
					}
				}
			}
		}
	}
	if (vriFullObject(vrirestrwdays)) {
		if (jQuery.inArray((m+1), vrirestrmonthswdays) == -1 || jQuery.inArray((m+1), vrirestrmultiplyminlos) != -1) {
			return [true];
		}
		if (wd == vrirestrwdays[m]) {
			return [true];
		}
		return [false];
	}
	return [true];
}";
			}
			$document->addScriptDeclaration($resdecl);
		}
		// VRI 1.7 - Restrictions End

		$dropdayplus = VikRentItems::getItemParam($item['params'], 'dropdaysplus');
		$forcedropday = "jQuery('#releasedate').datepicker( 'option', 'minDate', selectedDate );";
		if (!empty($dropdayplus) && intval($dropdayplus) > 0) {
			$forcedropday = "
var nowpick = jQuery(this).datepicker('getDate');
if (nowpick) {
	var nowpickdate = new Date(nowpick.getTime());
	nowpickdate.setDate(nowpickdate.getDate() + ".$dropdayplus.");
	jQuery('#releasedate').datepicker( 'option', 'minDate', nowpickdate );
	jQuery('#releasedate').datepicker( 'setDate', nowpickdate );
}";
		}
		
		$sdecl = "
var vri_fulldays_in = [".implode(', ', $push_disabled_in)."];
var vri_fulldays_out = [".implode(', ', $push_disabled_out)."];
function vriIsDayFullIn(date) {
	if (!vriValidateCta(date)) {
		return [false];
	}
	var actd = jQuery.datepicker.formatDate('yy-mm-dd', date);
	if (jQuery.inArray(actd, vri_fulldays_in) == -1) {
		return ".(strlen($declglobclosingdays) > 0 ? 'vriGlobalClosingDays(date)' : '[true]').";
	}
	return [false];
}
function vriIsDayFullOut(date) {
	if (!vriValidateCtd(date)) {
		return [false];
	}
	var actd = jQuery.datepicker.formatDate('yy-mm-dd', date);
	if (jQuery.inArray(actd, vri_fulldays_out) == -1) {
		return ".(strlen($declglobclosingdays) > 0 ? 'vriGlobalClosingDays(date)' : '[true]').";
	}
	return [false];
}
function vriLocationWopening(mode) {
	if (typeof vri_wopening_pick === 'undefined') {
		return true;
	}
	if (mode == 'pickup') {
		vri_mopening_pick = null;
	} else {
		vri_mopening_drop = null;
	}
	var loc_data = mode == 'pickup' ? vri_wopening_pick : vri_wopening_drop;
	var def_loc_hours = mode == 'pickup' ? vri_hopening_pick : vri_hopening_drop;
	var sel_d = jQuery((mode == 'pickup' ? '#pickupdate' : '#releasedate')).datepicker('getDate');
	if (!sel_d) {
		return true;
	}
	var sel_wday = sel_d.getDay();
	if (!vriFullObject(loc_data) || !loc_data.hasOwnProperty(sel_wday) || !loc_data[sel_wday].hasOwnProperty('fh')) {
		if (def_loc_hours !== null) {
			// populate the default opening time dropdown
			jQuery((mode == 'pickup' ? '#vricomselph' : '#vricomseldh')).html(def_loc_hours);
		}
		return true;
	}
	if (mode == 'pickup') {
		vri_mopening_pick = new Array(loc_data[sel_wday]['fh'], loc_data[sel_wday]['fm'], loc_data[sel_wday]['th'], loc_data[sel_wday]['tm']);
	} else {
		vri_mopening_drop = new Array(loc_data[sel_wday]['th'], loc_data[sel_wday]['tm'], loc_data[sel_wday]['fh'], loc_data[sel_wday]['fm']);
	}
	var hlim = loc_data[sel_wday]['fh'] < loc_data[sel_wday]['th'] ? loc_data[sel_wday]['th'] : (24 + loc_data[sel_wday]['th']);
	hlim = loc_data[sel_wday]['fh'] == 0 && loc_data[sel_wday]['th'] == 0 ? 23 : hlim;
	var hopts = '';
	var def_hour = jQuery((mode == 'pickup' ? '#vricomselph' : '#vricomseldh')).find('select').val();
	def_hour = def_hour.length > 1 && def_hour.substr(0, 1) == '0' ? def_hour.substr(1) : def_hour;
	def_hour = parseInt(def_hour);
	for (var h = loc_data[sel_wday]['fh']; h <= hlim; h++) {
		var viewh = h > 23 ? (h - 24) : h;
		hopts += '<option value=\''+viewh+'\''+(viewh == def_hour ? ' selected' : '')+'>'+(viewh < 10 ? '0'+viewh : viewh)+'</option>';
	}
	jQuery((mode == 'pickup' ? '#vricomselph' : '#vricomseldh')).find('select').html(hopts);
	if (mode == 'pickup') {
		setTimeout(function() {
			vriLocationWopening('dropoff');
		}, 750);
	}
}
function vriValidateCta(date) {
	var m = date.getMonth(), wd = date.getDay();
	if (vriFullObject(vrirestrctarange)) {
		for (var rk in vrirestrctarange) {
			if (vrirestrctarange.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrctarange[rk][0]);
				if (date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrctarange[rk][1]);
					if (date <= wdrangeend) {
						if (jQuery.inArray('-'+wd+'-', vrirestrctarange[rk][2]) >= 0) {
							return false;
						}
					}
				}
			}
		}
	}
	if (vriFullObject(vrirestrcta)) {
		if (vrirestrcta.hasOwnProperty(m) && jQuery.inArray('-'+wd+'-', vrirestrcta[m]) >= 0) {
			return false;
		}
	}
	return true;
}
function vriValidateCtd(date) {
	var m = date.getMonth(), wd = date.getDay();
	if (vriFullObject(vrirestrctdrange)) {
		for (var rk in vrirestrctdrange) {
			if (vrirestrctdrange.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrctdrange[rk][0]);
				if (date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrctdrange[rk][1]);
					if (date <= wdrangeend) {
						if (jQuery.inArray('-'+wd+'-', vrirestrctdrange[rk][2]) >= 0) {
							return false;
						}
					}
				}
			}
		}
	}
	if (vriFullObject(vrirestrctd)) {
		if (vrirestrctd.hasOwnProperty(m) && jQuery.inArray('-'+wd+'-', vrirestrctd[m]) >= 0) {
			return false;
		}
	}
	return true;
}
function vriInitElems() {
	if (typeof vri_wopening_pick === 'undefined') {
		return true;
	}
	vri_hopening_pick = jQuery('#vricomselph').find('select').clone();
	vri_hopening_drop = jQuery('#vricomseldh').find('select').clone();
}
jQuery(function() {
	vriInitElems();
	jQuery('#pickupdate').datepicker({
		showOn: 'focus',".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vriIsDayDisabled,\n" : "\nbeforeShowDay: vriIsDayFullIn,\n")."
		onSelect: function( selectedDate ) {
			".($totrestrictions > 0 ? "vriSetMinDropoffDate();" : $forcedropday)."
			vriLocationWopening('pickup');
		}
	});
	jQuery('#pickupdate').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#pickupdate').datepicker( 'option', 'minDate', '".VikRentItems::getMinDaysAdvance()."d');
	jQuery('#pickupdate').datepicker( 'option', 'maxDate', '".VikRentItems::getMaxDateFuture()."');
	jQuery('#releasedate').datepicker({
		showOn: 'focus',".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vriIsDayDisabledDropoff,\n" : "\nbeforeShowDay: vriIsDayFullOut,\n")."
		onSelect: function( selectedDate ) {
			vriLocationWopening('dropoff');
		}
	});
	jQuery('#releasedate').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#releasedate').datepicker( 'option', 'minDate', '".VikRentItems::getMinDaysAdvance()."d');
	jQuery('#releasedate').datepicker( 'option', 'maxDate', '".VikRentItems::getMaxDateFuture()."');
	jQuery('#pickupdate').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentitems' ] );
	jQuery('#releasedate').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentitems' ] );
	jQuery('.vri-cal-img, .vri-caltrigger').click(function() {
		var jdp = jQuery(this).prev('input.hasDatepicker');
		if (jdp.length) {
			jdp.focus();
		}
	});
});";
		if (!is_array($vrisessioncart) || !count($vrisessioncart)) {
			$document->addScriptDeclaration($sdecl);
			$selform .= "<div class=\"vrisfentry\"><label class=\"vripickdroplab\" for=\"pickupdate\">" . JText::translate('VRPICKUPITEM') . "</label><div class=\"vri-sf-input-wrap vri-sf-input-pickup\"><span><input type=\"text\" name=\"pickupdate\" id=\"pickupdate\" size=\"10\" autocomplete=\"off\" onfocus=\"this.blur();\" readonly/><i class=\"" . VikRentItemsIcons::i('calendar', 'vri-caltrigger') . "\"></i></span></div>";
			if (is_array($timeslots) && count($timeslots) > 0) {
				$selform .= "<div class=\"vrisfentrytimeslot\"><label for=\"vri-timeslot\">".JText::translate('VRIFOR') . "</label>";
				$wseltimeslots = "<span><select name=\"timeslot\" id=\"vri-timeslot\">\n";
				foreach ($timeslots as $times) {
					$wseltimeslots .= "<option value=\"".$times['id']."\">".$times['tname']."</option>\n";
				}
				$wseltimeslots .= "</select></span></div>\n";
				$selform .= $wseltimeslots . "</div>\n";
			} else {
				$selpickh = is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 ? '<input type="hidden" name="pickuph" value="'.$forcedpickdroptimes[0][0].'"/><span class="vriforcetime">'.$forcedpickdroptimes[0][0].'</span>' : '<select name="pickuph" id="pickuph">' . $hours . '</select>';
				$selpickm = is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 ? '<input type="hidden" name="pickupm" value="'.$forcedpickdroptimes[0][1].'"/><span class="vriforcetime">'.$forcedpickdroptimes[0][1].'</span>' : '<select name="pickupm">' . $minutes . '</select>';
				$selform .= "<div class=\"vrisfentrytime\"><div class=\"vri-sf-entrytime-inner\"><label for=\"pickuph\">".JText::translate('VRALLE') . "</label><span id=\"vricomselph\">".$selpickh."</span><label class=\"vritimedots\">:</label><span id=\"vricomselpm\">".$selpickm."</span></div></div></div>\n";
				$seldroph = is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 ? '<input type="hidden" name="releaseh" value="'.$forcedpickdroptimes[1][0].'"/><span class="vriforcetime">'.$forcedpickdroptimes[1][0].'</span>' : '<select name="releaseh" id="releaseh">' . $hours . '</select>';
				$seldropm = is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 ? '<input type="hidden" name="releasem" value="'.$forcedpickdroptimes[1][1].'"/><span class="vriforcetime">'.$forcedpickdroptimes[1][1].'</span>' : '<select name="releasem">' . $minutes . '</select>';
				$selform .= "<div class=\"vrisfentry\"><label class=\"vripickdroplab\" for=\"releasedate\">" . JText::translate('VRRETURNITEM') . "</label><div class=\"vri-sf-input-wrap vri-sf-input-pickup\"><span><input type=\"text\" name=\"releasedate\" id=\"releasedate\" size=\"10\" autocomplete=\"off\" onfocus=\"this.blur();\" readonly/><i class=\"" . VikRentItemsIcons::i('calendar', 'vri-caltrigger') . "\"></i></span></div><div class=\"vrisfentrytime\"><div class=\"vri-sf-entrytime-inner\"><label for=\"releaseh\">" . JText::translate('VRALLE') . "</label><span id=\"vricomseldh\">".$seldroph."</span><label class=\"vritimedots\">:</label><span id=\"vricomseldm\">".$seldropm."</span></div></div></div>\n";
			}
		}
	} else {
		// default Joomla Calendar
		/**
		 * Deprecation Notice: Joomla Calendar is no longer supported. Only the jQuery UI is supported.
		 * 
		 * @since 	1.6
		 */
	}
	//
	if(@ is_array($places)) {
		$selform .= "<div class=\"vrisfentry\"><label for=\"returnplace\">" . JText::translate('VRRETURNITEMORD') . "</label><span class=\"vriplacesp\"><select name=\"returnplace\" id=\"returnplace\"".(strlen($onchangeplacesdrop) > 0 ? $onchangeplacesdrop : "").">";
		foreach ($places as $pla) {
			if (in_array($pla['id'], $actretlocs)) {
				$selform .= "<option value=\"" . $pla['id'] . "\" id=\"returnplace".$pla['id']."\">" . $pla['name'] . "</option>\n";
			}
		}
		$selform .= "</select></span></div>\n";
	}
	if ((int)$item['askquantity'] == 1) {
		$selform .= "<div class=\"vrisfentry\"><label for=\"itemquant\">".JText::translate('VRIQUANTITYITEM')."</label><span><input type=\"number\" name=\"itemquant\" id=\"itemquant\" value=\"".(!array_key_exists('minquant', $item_params) || empty($item_params['minquant']) ? '1' : (int)$item_params['minquant'])."\" min=\"".(!array_key_exists('minquant', $item_params) || empty($item_params['minquant']) ? '1' : (int)$item_params['minquant'])."\" max=\"" . $item['units'] . "\" class=\"vri-numbinput\"/></span></div>\n";
	}
	$selform .= "<div class=\"vrisfentrysubmit\"><input type=\"submit\" name=\"search\" value=\"" . JText::translate('VRIBOOKTHISITEM') . "\" class=\"vridetbooksubmit\"/></div>\n";
	$selform .= "</div>\n";
	$selform .= (!empty($pitemid) ? "<input type=\"hidden\" name=\"Itemid\" value=\"" . $pitemid . "\"/>" : "") . "</form></div>";
	//locations on google map
	if (count($coordsplaces) > 0) {
		$selform = '<div class="vrilocationsbox"><div class="vrilocationsmapdiv"><a href="'.JRoute::rewrite('index.php?option=com_vikrentitems&view=locationsmap&elemid='.$item['id'].'&tmpl=component').'" class="vrimodalframe" target="_blank"><i class="' . VikRentItemsIcons::i('map-marked') . '"></i><span>'.JText::translate('VRILOCATIONSMAP').'</span></a></div></div>'.$selform;
	}
	//
	echo $selform;
	?>
	</div>
	<?php

	/**
	 * Form submit JS validation (mostly used for the opening/closing minutes).
	 * This piece of code should be always printed in the DOM as the main form
	 * calls this function when going on submit.
	 * 
	 * @since 	1.7
	 */
	?>
	<script type="text/javascript">
	function vriCleanNumber(snum) {
		if (snum.length > 1 && snum.substr(0, 1) == '0') {
			return parseInt(snum.substr(1));
		}
		return parseInt(snum);
	}

	function vriValidateSearch() {
		if (typeof jQuery === 'undefined' || typeof vri_wopening_pick === 'undefined') {
			return true;
		}
		if (vri_mopening_pick !== null) {
			// pickup time
			var pickh = jQuery('#vricomselph').find('select').val();
			var pickm = jQuery('#vricomselpm').find('select').val();
			if (!pickh || !pickh.length || !pickm) {
				return true;
			}
			pickh = vriCleanNumber(pickh);
			pickm = vriCleanNumber(pickm);
			if (pickh == vri_mopening_pick[0]) {
				if (pickm < vri_mopening_pick[1]) {
					// location is still closed at this time
					jQuery('#vricomselpm').find('select').html('<option value="'+vri_mopening_pick[1]+'">'+(vri_mopening_pick[1] < 10 ? '0'+vri_mopening_pick[1] : vri_mopening_pick[1])+'</option>').val(vri_mopening_pick[1]);
				}
			}
			if (pickh == vri_mopening_pick[2]) {
				if (pickm > vri_mopening_pick[3]) {
					// location is already closed at this time for a pick up
					jQuery('#vricomselpm').find('select').html('<option value="'+vri_mopening_pick[3]+'">'+(vri_mopening_pick[3] < 10 ? '0'+vri_mopening_pick[3] : vri_mopening_pick[3])+'</option>').val(vri_mopening_pick[3]);
				}
			}
		}

		if (vri_mopening_drop !== null) {
			// dropoff time
			var droph = jQuery('#vricomseldh').find('select').val();
			var dropm = jQuery('#vricomseldm').find('select').val();
			if (!droph || !droph.length || !dropm) {
				return true;
			}
			droph = vriCleanNumber(droph);
			dropm = vriCleanNumber(dropm);
			if (droph == vri_mopening_drop[0]) {
				if (dropm > vri_mopening_drop[1]) {
					// location is already closed at this time
					jQuery('#vricomseldm').find('select').html('<option value="'+vri_mopening_drop[1]+'">'+(vri_mopening_drop[1] < 10 ? '0'+vri_mopening_drop[1] : vri_mopening_drop[1])+'</option>').val(vri_mopening_drop[1]);
				}
			}
			if (droph == vri_mopening_drop[2]) {
				if (dropm < vri_mopening_drop[3]) {
					// location is still closed at this time for a drop off
					jQuery('#vricomseldm').find('select').html('<option value="'+vri_mopening_drop[3]+'">'+(vri_mopening_drop[3] < 10 ? '0'+vri_mopening_drop[3] : vri_mopening_drop[3])+'</option>').val(vri_mopening_drop[3]);
				}
			}
		}

		return true;
	}

	jQuery(document).ready(function() {
	<?php
	if (!empty($ppickup)) {
		?>
		jQuery("#pickupdate").datepicker("setDate", new Date(<?php echo date('Y', $ppickup); ?>, <?php echo ((int)date('n', $ppickup) - 1); ?>, <?php echo date('j', $ppickup); ?>));
		jQuery(".ui-datepicker-current-day").click();
		<?php
	}
	if (!empty($viewingdayts) && !empty($pday) && $viewingdayts >= $nowts) {
		if (!count($push_disabled_in) || !in_array('"'.date('Y-m-d', $viewingdayts).'"', $push_disabled_in)) {
		?>
		jQuery("#pickupdate").datepicker("setDate", new Date(<?php echo date('Y', $viewingdayts); ?>, <?php echo ((int)date('n', $viewingdayts) - 1); ?>, <?php echo date('j', $viewingdayts); ?>));
		<?php
		}
		?>
		if (jQuery(".vri-hourlycal-container").length) {
			jQuery('html,body').animate({ scrollTop: (jQuery(".vri-hourlycal-container").offset().top - 5) }, { duration: 'slow' });	
		}
		<?php
	}
	?>

		jQuery(document.body).on('click', '.vri-idetails-cal-pickday', function() {
			if (!jQuery("#pickupdate").length) {
				return;
			}
			var tdday = jQuery(this).attr('data-daydate');
			if (!tdday || !tdday.length) {
				return;
			}
			// set pick-up date in datepicker
			jQuery('#pickupdate').datepicker('setDate', tdday);
			// animate to datepickers position
			if (jQuery("#vri-bookingpart-init").length) {
				jQuery('html,body').animate({
					scrollTop: (jQuery('#vri-bookingpart-init').offset().top - 5)
				}, 600, function() {
					// animation-complete callback should simulate the onSelect event of the pick-up datepicker
					if (typeof vriSetMinDropoffDate !== "undefined") {
						vriSetMinDropoffDate();
					}
					if (typeof vriLocationWopening !== "undefined") {
						vriLocationWopening('pickup');
					}
					// give focus to drop-off datepicker
					if (jQuery('#releasedate').length) {
						jQuery('#releasedate').focus();
					}
				});
			}
		});
	});
	</script>
	<?php
	//
} else {
	echo VikRentItems::getDisabledRentMsg($vri_tn);
}

?>
</div>
