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

$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;
$arrbusy = $this->arrbusy;
$wmonthsel = $this->wmonthsel;
$tsstart = $this->tsstart;
$all_locations = $this->all_locations;
$plocation = $this->plocation;
$plocationw = $this->plocationw;

$nowtf = VikRentItems::getTimeFormat(true);
$wdays_map = array(
	JText::translate('VRSUN'),
	JText::translate('VRMON'),
	JText::translate('VRTUE'),
	JText::translate('VRWED'),
	JText::translate('VRTHU'),
	JText::translate('VRFRI'),
	JText::translate('VRSAT')
);
$currencysymb = VikRentItems::getCurrencySymb(true);

$session = JFactory::getSession();
$show_type = $session->get('vriUnitsShowType', '');
$cookie = JFactory::getApplication()->input->cookie;
$cookie_uleft = $cookie->get('vriAovwUleft', '', 'string');
$mnum = $session->get('vriOvwMnum', '1');
$mnum = intval($mnum);
?>
<script type="text/javascript">
var hovtimer;
var hovtip = false;
var vriMessages = {
	"loadingTip": "<?php echo addslashes(JText::translate('VIKLOADING')); ?>",
	"numDays": "<?php echo addslashes(JText::translate('VRDAYS')); ?>",
	"pickupLbl": "<?php echo addslashes(JText::translate('VRPICKUPAT')); ?>",
	"dropoffLbl": "<?php echo addslashes(JText::translate('VRRELEASEAT')); ?>",
	"totalAmount": "<?php echo addslashes(JText::translate('VREDITORDERNINE')); ?>",
	"totalPaid": "<?php echo addslashes(JText::translate('VRIEXPCSVTOTPAID')); ?>",
	"currencySymb": "<?php echo $currencysymb; ?>"
};

function vriUnitsLeftOrBooked() {
	var set_to = jQuery('#uleftorbooked').val();
	if (jQuery('.vri-overview-redday').length) {
		jQuery('.vri-overview-redday').each(function() {
			jQuery(this).text(jQuery(this).attr('data-'+set_to));
		});
	}
	var nd = new Date();
	nd.setTime(nd.getTime() + (365*24*60*60*1000));
	document.cookie = "vriAovwUleft="+set_to+"; expires=" + nd.toUTCString() + "; path=/; SameSite=Lax";
}

/* Hover Tooltip functions */
function registerHoveringTooltip(that) {
	if (hovtip) {
		return false;
	}
	if (hovtimer) {
		clearTimeout(hovtimer);
		hovtimer = null;
	}
	var elem = jQuery(that);
	var cellheight = elem.outerHeight();
	var celldata = new Array();
	if (elem.hasClass('subitem-busy')) {
		celldata.push(elem.parent('tr').attr('data-subitemid'));
		celldata.push(elem.attr('data-day'));
	}
	hovtimer = setTimeout(function() {
		hovtip = true;
		jQuery(
			"<div class=\"vri-overview-tipblock\">"+
				"<div class=\"vri-overview-tipinner\"><span class=\"vri-overview-tiploading\">"+vriMessages.loadingTip+"</span></div>"+
			"</div>"
		).appendTo(elem);
		jQuery(".vri-overview-tipblock").css("bottom", "+="+cellheight);
		loadTooltipBookings(elem.attr('data-bids'), celldata);
	}, 900);
}
function unregisterHoveringTooltip() {
	clearTimeout(hovtimer);
	hovtimer = null;
}
function adjustHoveringTooltip() {
	setTimeout(function() {
		var difflim = 35;
		var otop = jQuery(".vri-overview-tipblock").offset().top;
		if (otop < difflim) {
			jQuery(".vri-overview-tipblock").css("bottom", "-="+(difflim - otop));
		}
	}, 100);
}
function hideVriTooltip() {
	jQuery('.vri-overview-tipblock').remove();
	hovtip = false;
}
function loadTooltipBookings(bids, celldata) {
	if (!bids || bids === undefined || !bids.length) {
		hideVriTooltip();
		return false;
	}
	var subitemdata = celldata.length ? celldata[0] : '';
	//ajax request
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "index.php",
		data: { option: "com_vikrentitems", task: "getordersinfo", tmpl: "component", idorders: bids, subitem: subitemdata }
	}).done(function(res) {
		if (res.indexOf('e4j.error') >= 0 ) {
			console.log(res);
			alert(res.replace("e4j.error.", ""));
			//restore
			hideVriTooltip();
			//
		} else {
			var obj_res = JSON.parse(res);
			jQuery('.vri-overview-tiploading').remove();
			var container = jQuery('.vri-overview-tipinner');
			jQuery(obj_res).each(function(k, v) {
				var bcont = "<div class=\"vri-overview-tip-bookingcont\">";
				bcont += "<div class=\"vri-overview-tip-bookingcont-left\">";
				/**
				 * @wponly lite - link changed to "editorder"
				 */
				bcont += "<div class=\"vri-overview-tip-bid\"><span class=\"vri-overview-tip-lbl\"><?php echo addslashes(JText::translate('VRIDASHUPRESONE')); ?> <span class=\"vri-overview-tip-lbl-innerleft\"><a href=\"index.php?option=com_vikrentitems&task=editorder&goto=overv&cid[]="+v.id+"\"><i class=\"<?php echo VikRentItemsIcons::i('edit'); ?>\"></i></a></span></span><span class=\"vri-overview-tip-cnt\">"+v.id+"</span></div>";
				//
				bcont += "<div class=\"vri-overview-tip-bstatus\"><span class=\"vri-overview-tip-lbl\"><?php echo addslashes(JText::translate('VRPVIEWORDERSEIGHT')); ?></span><span class=\"vri-overview-tip-cnt\"><div class=\"label "+(v.status == 'confirmed' ? 'label-success' : 'label-warning')+"\">"+v.status_lbl+"</div></span></div>";
				bcont += "<div class=\"vri-overview-tip-bdate\"><span class=\"vri-overview-tip-lbl\"><?php echo addslashes(JText::translate('VRPVIEWORDERSONE')); ?></span><span class=\"vri-overview-tip-cnt\"><a href=\"index.php?option=com_vikrentitems&task=editorder&goto=overv&cid[]="+v.id+"\">"+v.ts+"</a></span></div>";
				bcont += "</div>";
				bcont += "<div class=\"vri-overview-tip-bookingcont-right\">";
				bcont += "<div class=\"vri-overview-tip-bcustomer\"><span class=\"vri-overview-tip-lbl\"><?php echo addslashes(JText::translate('VRPVIEWORDERSTWO')); ?></span><span class=\"vri-overview-tip-cnt\">"+v.cinfo+"</span></div>";
				bcont += "<div class=\"vri-overview-tip-bguests\"><span class=\"vri-overview-tip-lbl\">"+vriMessages.numDays+"</span><span class=\"vri-overview-tip-cnt hasTooltip\" title=\""+vriMessages.pickupLbl+" "+v.pickup+" - "+vriMessages.dropoffLbl+" "+v.dropoff+"\">" + v.days + (v.pickup_place !== null && v.pickup_place.length ? ", " + v.pickup_place + (v.dropoff_place !== null && v.dropoff_place.length && v.dropoff_place != v.pickup_place ? " - " + v.dropoff_place : "") : "") + "</span></div>";
				if (v.hasOwnProperty('cindexes')) {
					for (var cindexk in v.cindexes) {
						if (v.cindexes.hasOwnProperty(cindexk)) {
							bcont += "<div class=\"vri-overview-tip-bcindexes\"><span class=\"vri-overview-tip-lbl\">"+cindexk+"</span><span class=\"vri-overview-tip-cnt\">"+v.cindexes[cindexk]+"</span></div>";
						}
					}
				}
				bcont += "<div class=\"vri-overview-tip-pickdt\"><span class=\"vri-overview-tip-lbl\"><?php echo addslashes(JText::translate('VRPVIEWORDERSFOUR')); ?></span><span class=\"vri-overview-tip-cnt\">"+v.pickup+"</span></div>";
				bcont += "<div class=\"vri-overview-tip-dropdt\"><span class=\"vri-overview-tip-lbl\"><?php echo addslashes(JText::translate('VRPVIEWORDERSFIVE')); ?></span><span class=\"vri-overview-tip-cnt\">"+v.dropoff+"</span></div>";
				bcont += "<div class=\"vri-overview-tip-bookingcont-total\">";
				bcont += "<div class=\"vri-overview-tip-btot\"><span class=\"vri-overview-tip-lbl\">"+vriMessages.totalAmount+"</span><span class=\"vri-overview-tip-cnt\">"+vriMessages.currencySymb+" "+v.format_tot+"</span></div>";
				if (v.totpaid > 0.00) {
					bcont += "<div class=\"vri-overview-tip-btot\"><span class=\"vri-overview-tip-lbl\">"+vriMessages.totalPaid+"</span><span class=\"vri-overview-tip-cnt\">"+vriMessages.currencySymb+" "+v.format_totpaid+"</span></div>";
				}
				var getnotes = v.adminnotes;
				if (getnotes !== null && getnotes.length) {
					bcont += "<div class=\"vri-overview-tip-notes\"><span class=\"vri-overview-tip-lbl\"><span class=\"vri-overview-tip-notes-inner\"><i class=\"vriicn-info hasTooltip\" title=\""+getnotes+"\"></i></span></span></div>";
				}
				bcont += "</div>";
				bcont += "</div>";
				bcont += "</div>";
				container.append(bcont);
			});
			// adjust the position so that it won't go under other contents
			adjustHoveringTooltip()
			//
			jQuery(".hasTooltip").tooltip();
		}
	}).fail(function() { 
		console.error('Request Failed');
		//restore
		hideVriTooltip();
		//
	});
	//
}

jQuery(document).ready(function() {
	/**
	 * Render the units view mode
	 */
	vriUnitsLeftOrBooked();
	
	/* Hover Tooltip Start */
	jQuery('td.busy, td.busytmplock').hover(function() {
		registerHoveringTooltip(this);
	}, unregisterHoveringTooltip);
	jQuery(document).keydown(function(e) {
		if (e.keyCode == 27) {
			if (hovtip === true) {
				hideVriTooltip();
			}
		}
	});
	jQuery(document).mouseup(function(e) {
		if (!hovtip) {
			return false;
		}
		if (hovtip) {
			var vri_overlay_cont = jQuery(".vri-overview-tipblock");
			if (!vri_overlay_cont.is(e.target) && vri_overlay_cont.has(e.target).length === 0) {
				hideVriTooltip();
				return true;
			}
		}
	});
	/* Hover Tooltip End */
});
</script>
<form class="vri-avov-form" action="index.php?option=com_vikrentitems&amp;task=overv" method="post" name="vroverview">
	<div class="btn-toolbar vri-avov-toolbar" id="filter-bar" style="width: 100%; display: inline-block;">
		<div class="btn-group pull-left">
			<?php echo $wmonthsel; ?>
		</div>
		<div class="btn-group pull-left">
			<select name="mnum" onchange="document.vroverview.submit();">
			<?php
			for ($i = 1; $i <= 12; $i++) { 
				?>
				<option value="<?php echo $i; ?>"<?php echo $i == $mnum ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRCONFIGMAXDATEMONTHS').': '.$i; ?></option>
				<?php
			}
			?>
			</select>
		</div>
		<div class="btn-group pull-right">
			<select name="units_show_type" id="uleftorbooked" onchange="vriUnitsLeftOrBooked();">
				<option value="units-booked"<?php echo (!empty($cookie_uleft) && $cookie_uleft == 'units-booked' ? ' selected="selected"' : ''); ?>><?php echo JText::translate('VRISHOWUNITSBOOKED'); ?></option>
				<option value="units-left"<?php echo $show_type == 'units-left' || (!empty($cookie_uleft) && $cookie_uleft == 'units-left') ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRISHOWUNITSLEFT'); ?></option>
			</select>
		</div>
	<?php
	if (is_array($all_locations)) {
		$loc_options = '<option value="">'.JText::translate('VRIORDERSLOCFILTERANY').'</option>'."\n";
		foreach ($all_locations as $location) {
			$loc_options .= '<option value="'.$location['id'].'"'.($location['id'] == $plocation ? ' selected="selected"' : '').'>'.$location['name'].'</option>'."\n";
		}
		?>
		<div class="btn-group pull-right">
			<button type="submit" class="btn btn-secondary"><?php echo JText::translate('VRIORDERSLOCFILTERBTN'); ?></button>
		</div>
		<div class="btn-group pull-right">
			<select name="locationw" id="locwfilter">
				<option value="pickup"><?php echo JText::translate('VRIORDERSLOCFILTERPICK'); ?></option>
				<option value="dropoff"<?php echo $plocationw == 'dropoff' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIORDERSLOCFILTERDROP'); ?></option>
				<option value="both"<?php echo $plocationw == 'both' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRIORDERSLOCFILTERPICKDROP'); ?></option>
			</select>
		</div>
		<div class="btn-group pull-right">
			<label for="locfilter" style="display: inline-block; margin-right: 5px;"><?php echo JText::translate('VRIORDERSLOCFILTER'); ?></label>
			<select name="location" id="locfilter"><?php echo $loc_options; ?></select>
		</div>
		<?php
	}
	?>
	</div>
</form>

<?php
$todayymd = date('Y-m-d');
$nowts = getdate($tsstart);
$curts = $nowts;
for ($mind = 1; $mind <= $mnum; $mind++) {
?>
<div class="vri-overv-table-container">
	<table class="table vrioverviewtable">
		<tr class="vrioverviewtablerow">
			<td class="bluedays vrioverviewtdone"><strong><?php echo VikRentItems::sayMonth($curts['mon'])." ".$curts['year']; ?></strong></td>
		<?php
		$moncurts = $curts;
		$mon = $moncurts['mon'];
		while ($moncurts['mon'] == $mon) {
			$curdayymd = date('Y-m-d', $moncurts[0]);
			echo '<td align="center" class="bluedays'.($todayymd == $curdayymd ? ' vri-overv-todaycell' : '').'"><span class="vri-overv-mday">'.$moncurts['mday'].'</span><span class="vri-overv-wday">'.$wdays_map[$moncurts['wday']].'</td>';
			$moncurts = getdate(mktime(0, 0, 0, $moncurts['mon'], ($moncurts['mday'] + 1), $moncurts['year']));
		}
		?>
		</tr>
		<?php
		foreach ($rows as $item) {
			$moncurts = $curts;
			$mon = $moncurts['mon'];
			echo '<tr class="vrioverviewtablerow">';
			echo '<td class="itemname"><span class="vri-overview-itemname">'.$item['name'].'</span> <span class="vri-overview-itemunits">'.$item['units'].'</span></td>';
			while ($moncurts['mon'] == $mon) {
				$dclass = "notbusy";
				$dalt = "";
				$bid = "";
				$bids_pool = array();
				$totfound = 0;
				$cur_day_key = date('Y-m-d', $moncurts[0]);
				if (is_array($arrbusy[$item['id']])) {
					foreach ($arrbusy[$item['id']] as $b) {
						$tmpone = getdate($b['ritiro']);
						$ritts = mktime(0, 0, 0, $tmpone['mon'], $tmpone['mday'], $tmpone['year']);
						$tmptwo = getdate($b['consegna']);
						$conts = mktime(0, 0, 0, $tmptwo['mon'], $tmptwo['mday'], $tmptwo['year']);
						if ($moncurts[0] >= $ritts && $moncurts[0] <= $conts) {
							$dclass = "busy";
							$bid = $b['idorder'];
							if (!in_array($bid, $bids_pool)) {
								$bids_pool[] = '-'.$bid.'-';
							}
							if ($moncurts[0] == $ritts) {
								$dalt = JText::translate('VRPICKUPAT')." ".date($nowtf, $b['ritiro']);
							} elseif ($moncurts[0] == $conts) {
								$dalt = JText::translate('VRRELEASEAT')." ".date($nowtf, $b['consegna']);
							}
							$totfound += $b['closure'] > 0 ? $item['units'] : 1;
						}
					}
				}
				$useday = ($moncurts['mday'] < 10 ? "0".$moncurts['mday'] : $moncurts['mday']);
				$dclass .= ($totfound < $item['units'] && $totfound > 0 ? ' vri-partially' : '');
				$write_units = $show_type == 'units-left' || (!empty($cookie_uleft) && $cookie_uleft == 'units-left') ? ($item['units'] - $totfound) : $totfound;
				// check today's date
				$curdayymd = date('Y-m-d', $moncurts[0]);
				if ($todayymd == $curdayymd) {
					$dclass .= ' vri-overv-todaycell';
				}
				//
				if ($totfound == 1) {
					/**
					 * @wponly lite - link changed to "editorder"
					 */
					$dlnk = "<a href=\"index.php?option=com_vikrentitems&task=editorder&goto=overv&cid[]=".$bid."\" class=\"vri-overview-redday\" style=\"color: #ffffff;\" data-units-booked=\"".$totfound."\" data-units-left=\"".($item['units'] - $totfound)."\">".$write_units."</a>";
					//
					$cal = "<td align=\"center\" class=\"".$dclass."\"".(!empty($dalt) ? " title=\"".$dalt."\"" : "")." data-day=\"".$cur_day_key."\" data-bids=\"".(strpos($dclass, "subitem-busy") !== false ? '-'.$bid.'-' : implode(',', $bids_pool))."\">".$dlnk."</td>\n";
				} elseif ($totfound > 1) {
					$dlnk = "<a href=\"index.php?option=com_vikrentitems&task=choosebusy&goto=overv&iditem=".$item['id']."&ts=".$moncurts[0]."\" class=\"vri-overview-redday\" style=\"color: #ffffff;\" data-units-booked=\"".$totfound."\" data-units-left=\"".($item['units'] - $totfound)."\">".$write_units."</a>";
					$cal = "<td align=\"center\" class=\"".$dclass."\" data-day=\"".$cur_day_key."\" data-bids=\"".implode(',', $bids_pool)."\">".$dlnk."</td>\n";
				} else {
					$dlnk = $useday;
					$cal = "<td align=\"center\" class=\"".$dclass."\" data-day=\"".$cur_day_key."\" data-bids=\"\">&nbsp;</td>\n";
				}
				echo $cal;
				$moncurts = getdate(mktime(0, 0, 0, $moncurts['mon'], ($moncurts['mday'] + 1), $moncurts['year']));
			}
			echo '</tr>';
		}
		?>
	</table>
</div>
<?php echo ($mind + 1) <= $mnum ? '<br/>' : ''; ?>
<?php
	$curts = getdate(mktime(0, 0, 0, ($nowts['mon'] + $mind), $nowts['mday'], $nowts['year']));
}
?>

<form action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="task" value="overv" />
	<input type="hidden" name="month" value="<?php echo $tsstart; ?>" />
	<input type="hidden" name="mnum" value="<?php echo $mnum; ?>" />
	<?php echo '<br/>'.$navbut; ?>
</form>
