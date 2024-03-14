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

$orderitems = $this->orderitems;
$ord = $this->ord;
$all_items = $this->all_items;
$customer = $this->customer;
$locations = $this->locations;

$totdelivery = $ord[0]['deliverycost'];
$deliverycalcunit = VikRentItems::getDeliveryCalcUnit(true);

$dbo = JFactory::getDBO();
$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();
$pgoto = VikRequest::getString('goto', '', 'request');
$currencysymb = VikRentItems::getCurrencySymb(true);
$nowdf = VikRentItems::getDateFormat(true);
if ($nowdf == "%d/%m/%Y") {
	$rit = date('d/m/Y', $ord[0]['ritiro']);
	$con = date('d/m/Y', $ord[0]['consegna']);
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$rit = date('m/d/Y', $ord[0]['ritiro']);
	$con = date('m/d/Y', $ord[0]['consegna']);
	$df = 'm/d/Y';
} else {
	$rit = date('Y/m/d', $ord[0]['ritiro']);
	$con = date('Y/m/d', $ord[0]['consegna']);
	$df = 'Y/m/d';
}
$nowtf = VikRentItems::getTimeFormat(true);
$arit = getdate($ord[0]['ritiro']);
$acon = getdate($ord[0]['consegna']);
$ritho = '';
$conho = '';
$ritmi = '';
$conmi = '';
for ($i = 0; $i < 24; $i++) {
	$ritho .= "<option value=\"".$i."\"".($arit['hours'] == $i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
	$conho .= "<option value=\"".$i."\"".($acon['hours'] == $i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
}
for ($i = 0; $i < 60; $i++) {
	$ritmi .= "<option value=\"".$i."\"".($arit['minutes'] == $i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
	$conmi .= "<option value=\"".$i."\"".($acon['minutes'] == $i ? " selected=\"selected\"" : "").">".($i < 10 ? "0".$i : $i)."</option>\n";
}
$hours_rented = 0;
if ($ord[0]['hourly'] == 1) {
	$secdiff = $ord[0]['consegna'] - $ord[0]['ritiro'];
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
			$hours_rented = $hoursdiff;
		}
	}
}
$checkhourscharges = 0;
$ppickup = $ord[0]['ritiro'];
$prelease = $ord[0]['consegna'];
$secdiff = $prelease - $ppickup;
$daysdiff = $secdiff / 86400;
if (is_int($daysdiff)) {
	if ($daysdiff < 1) {
		$daysdiff = 1;
	}
} else {
	if ($daysdiff < 1) {
		$daysdiff = 1;
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
$enter_delivery = false;
$tot_items = 0;
foreach ($orderitems as $oi) {
	$tot_items += $oi['itemquant'];
	if (!empty($oi['deliveryaddr']) && !empty($oi['deliverydist'])) {
		$enter_delivery = true;
	}
}
if ($ord[0]['status'] == "confirmed") {
	$saystaus = '<span class="label label-success">'.JText::translate('VRCONFIRMED').'</span>';
} elseif ($ord[0]['status']=="standby") {
	$saystaus = '<span class="label label-warning">'.JText::translate('VRSTANDBY').'</span>';
} else {
	$saystaus = '<span class="label label-error" style="background-color: #d9534f;">'.JText::translate('VRCANCELLED').'</span>';
}
// Custom rate
$is_cust_cost = false;
foreach ($orderitems as $koi => $oi) {
	if (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0.00) {
		$is_cust_cost = true;
		break;
	}
}
$ivas = array();
$wiva = "";
$jstaxopts = '<option value=\"\">'.JText::translate('VRNEWOPTFOUR').'</option>';
$q = "SELECT * FROM `#__vikrentitems_iva`;";
$dbo->setQuery($q);
$dbo->execute();
if ($dbo->getNumRows() > 0) {
	$ivas = $dbo->loadAssocList();
	$wiva = "<select name=\"aliq%s\"><option value=\"\">".JText::translate('VRNEWOPTFOUR')."</option>\n";
	foreach ($ivas as $iv) {
		$wiva .= "<option value=\"".$iv['id']."\" data-aliqid=\"".$iv['id']."\">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']." - ".$iv['aliq']."%")."</option>\n";
		$jstaxopts .= '<option value=\"'.$iv['id'].'\">'.(empty($iv['name']) ? $iv['aliq']."%" : addslashes($iv['name'])." - ".$iv['aliq']."%").'</option>';
	}
	$wiva .= "</select>\n";
}
//
// VRI 1.6 item switching
$switching = false;
$switcher = '';
if (is_array($ord) && count($all_items) > 1 && (!empty($orderitems[0]['idtar']) || $is_cust_cost)) {
	$switching = true;
	// @wponly lite - item switching not supported
}
//
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if ( task == 'removebusy' ) {
		if (confirm('<?php echo addslashes(JText::translate('VRJSDELBUSY')); ?>')) {
			Joomla.submitform(task, document.adminForm);
		} else {
			return false;
		}
	} else {
		Joomla.submitform(task, document.adminForm);
	}
}
function vriIsSwitchable(toid, fromid, orid) {
	if (parseInt(toid) == parseInt(fromid)) {
		document.getElementById('vriswr'+orid).value = '';
		return false;
	}
	return true;
}
var vriMessages = {
	"jscurrency": "<?php echo $currencysymb; ?>",
	"extracnameph": "<?php echo addslashes(JText::translate('VRPEDITBUSYEXTRACNAME')); ?>",
	"taxoptions" : "<?php echo $jstaxopts; ?>",
	"cantadditem": "<?php echo addslashes(JText::translate('VRIBOOKCANTADDITEM')); ?>"
};
var vri_overlay_on = false,
	vri_can_add_item = false;
jQuery(document).ready(function() {
	jQuery('#vri-add-item').click(function() {
		jQuery(".vri-info-overlay-block").fadeToggle(400, function() {
			if (jQuery(".vri-info-overlay-block").is(":visible")) {
				vri_overlay_on = true;
			} else {
				vri_overlay_on = false;
			}
		});
	});
	jQuery(document).mouseup(function(e) {
		if (!vri_overlay_on) {
			return false;
		}
		var vri_overlay_cont = jQuery(".vri-info-overlay-content");
		if (!vri_overlay_cont.is(e.target) && vri_overlay_cont.has(e.target).length === 0) {
			vriAddItemCloseModal();
		}
	});
	jQuery(document).keyup(function(e) {
		if (e.keyCode == 27 && vri_overlay_on) {
			vriAddItemCloseModal();
		}
	});
	jQuery(".vri-rswitcher-select").select2({placeholder: '<?php echo addslashes(JText::translate('VRSWITCHCWITH')); ?>'});
});
function vriAddItemId(itid) {
	document.getElementById('add_item_id').value = itid;
	var fdate = document.getElementById('ritiro').value;
	var fh = document.getElementById('pickuph').value;
	var fm = document.getElementById('pickupm').value;
	var tdate = document.getElementById('consegna').value;
	var th = document.getElementById('dropoffh').value;
	var tm = document.getElementById('dropoffm').value;
	if (itid.length && fdate.length && tdate.length) {
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikrentitems", task: "isitembookable", tmpl: "component", itid: itid, fdate: fdate, fh: fh, fm: fm, tdate: tdate, th: th, tm: tm }
		}).done(function(res) {
			var obj_res = JSON.parse(res);
			if (obj_res['status'] != 1) {
				vri_can_add_item = false;
				alert(obj_res['err']);
				document.getElementById('add-item-status').style.color = 'red';
			} else {
				vri_can_add_item = true;
				document.getElementById('add-item-status').style.color = 'green';
			}
		}).fail(function() {
			console.log("isitembookable Request Failed");
			alert('Generic Error');
		});
	} else {
		vri_can_add_item = false;
		document.getElementById('add-item-status').style.color = '#333333';
	}
}
function vriAddItemSubmit() {
	if (vri_can_add_item && document.getElementById('add_item_id').value.length) {
		document.adminForm.task.value = 'updatebusy';
		document.adminForm.submit();
	} else {
		alert(vriMessages.cantadditem);
	}
}
function vriAddItemCloseModal() {
	document.getElementById('add_item_id').value = '';
	vri_can_add_item = false;
	jQuery(".vri-info-overlay-block").fadeOut();
	vri_overlay_on = false;
}
function vriConfirmRmItem(roid) {
	document.getElementById('rm_item_oid').value = '';
	if (!roid.length) {
		return false;
	}
	if (confirm('<?php echo addslashes(JText::translate('VRIBOOKRMITEMCONFIRM')); ?>')) {
		document.getElementById('rm_item_oid').value = roid;
		document.adminForm.task.value = 'updatebusy';
		document.adminForm.submit();
	}
}
</script>
<script type="text/javascript">
/* custom extra services for each item */
function vriAddExtraCost(rnum) {
	var telem = jQuery("#vri-ebusy-extracosts-"+rnum);
	if (telem.length > 0) {
		var extracostcont = "<div class=\"vri-editbooking-item-extracost\">"+"\n"+
			"<div class=\"vri-ebusy-extracosts-cellname\"><input type=\"text\" name=\"extracn["+rnum+"][]\" value=\"\" placeholder=\""+vriMessages.extracnameph+"\" size=\"25\" /></div>"+"\n"+
			"<div class=\"vri-ebusy-extracosts-cellcost\"><span class=\"vri-ebusy-extracosts-currency\">"+vriMessages.jscurrency+"</span> <input type=\"number\" step=\"any\" name=\"extracc["+rnum+"][]\" value=\"0.00\" size=\"5\" /></div>"+"\n"+
			"<div class=\"vri-ebusy-extracosts-celltax\"><select name=\"extractx["+rnum+"][]\">"+vriMessages.taxoptions+"</select></div>"+"\n"+
			"<div class=\"vri-ebusy-extracosts-cellrm\"><button class=\"btn btn-danger\" type=\"button\" onclick=\"vriRemoveExtraCost(this);\">X</button></div>"+"\n"+
		"</div>";
		telem.find(".vri-editbooking-item-extracosts-wrap").append(extracostcont);
	}
}
function vriRemoveExtraCost(elem) {
	var parel = jQuery(elem).closest(".vri-editbooking-item-extracost");
	if (parel.length > 0) {
		parel.remove();
	}
}
</script>

<div class="vri-bookingdet-topcontainer vri-editbooking-topcontainer">
	<form name="adminForm" id="adminForm" action="index.php" method="post">
		
		<div class="vri-info-overlay-block">
			<a class="vri-info-overlay-close" href="javascript: void(0);"></a>
			<div class="vri-info-overlay-content">
				<h3><?php echo JText::translate('VRIBOOKADDITEM'); ?></h3>
				<div class="vri-add-item-overlay">
					<div class="vri-add-item-entry">
						<label for="add-item-id"><?php echo JText::translate('VRPVIEWORDERSTHREE'); ?> <span id="add-item-status" style="color: #333333;"><i class="vriicn-checkmark"></i></span></label>
						<select id="add-item-id" onchange="vriAddItemId(this.value);">
							<option value=""></option>
						<?php
						$some_disabled = isset($all_items[(count($all_items) - 1)]['avail']) && !$all_items[(count($all_items) - 1)]['avail'];
						$optgr_enabled = false;
						foreach ($all_items as $ar) {
							if ($some_disabled && !$optgr_enabled && $ar['avail']) {
								$optgr_enabled = true;
								?>
							<optgroup label="<?php echo addslashes(JText::translate('VRPVIEWITEMSIX')); ?>">
								<?php
							} elseif ($some_disabled && $optgr_enabled && !$ar['avail']) {
								$optgr_enabled = false;
								?>
							</optgroup>
								<?php
							}
							?>
							<option value="<?php echo $ar['id']; ?>"><?php echo $ar['name']; ?></option>
							<?php
						}
						?>
						</select>
						<input type="hidden" name="add_item_id" id="add_item_id" value="" />
					</div>
					<div class="vri-add-item-entry">
						<div class="vri-add-item-entry-inline">
							<label for="add_item_quantity"><?php echo JText::translate('VRIQUANTITY'); ?></label>
							<input type="number" min="1" name="add_item_quantity" id="add_item_quantity" value="1" />
						</div>
					</div>
					<div class="vri-add-item-entry">
						<div class="vri-add-item-entry-inline">
							<label for="add_item_price"><?php echo JText::translate('VRIRENTCUSTRATEPLAN'); ?> (<?php echo $currencysymb; ?>)</label>
							<input type="number" step="any" min="0" name="add_item_price" id="add_item_price" value="" />
						</div>
					<?php
					if (!empty($wiva)) :
					?>
						<div class="vri-add-item-entry-inline">
							<label>&nbsp;</label>
							<?php echo str_replace('%s', '_add_item', $wiva); ?>
						</div>
					<?php
					endif;
					?>
					</div>
					<div class="vri-center">
						<br />
						<button type="button" class="btn btn-large btn-success" onclick="vriAddItemSubmit();"><i class="vriicn-checkmark"></i> <?php echo JText::translate('VRIBOOKADDITEM'); ?></button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="vri-bookdet-container">
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span>ID</span>
				</div>
				<div class="vri-bookdet-foot">
					<span><?php echo $ord[0]['id']; ?></span>
				</div>
			</div>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERONE'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
					<span><?php echo date($df.' '.$nowtf, $ord[0]['ts']); ?></span>
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
					<?php echo ($ord[0]['hourly'] == 1 && $hours_rented > 0 ? $hours_rented.' '.JText::translate('VRIHOURS') : $ord[0]['days']); ?>
				</div>
			</div>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERFIVE'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
				<?php
				$ritiro_info = getdate($ord[0]['ritiro']);
				$short_wday = JText::translate('VR'.strtoupper(substr($ritiro_info['weekday'], 0, 3)));
				?>
					<?php echo $short_wday.', '.date($df.' '.$nowtf, $ord[0]['ritiro']); ?>
				</div>
			</div>
			<div class="vri-bookdet-wrap">
				<div class="vri-bookdet-head">
					<span><?php echo JText::translate('VREDITORDERSIX'); ?></span>
				</div>
				<div class="vri-bookdet-foot">
				<?php
				$consegna_info = getdate($ord[0]['consegna']);
				$short_wday = JText::translate('VR'.strtoupper(substr($consegna_info['weekday'], 0, 3)));
				?>
					<?php echo $short_wday.', '.date($df.' '.$nowtf, $ord[0]['consegna']); ?>
				</div>
			</div>
			<?php
		if (!empty($ord[0]['idplace'])) {
			$pickup_place = VikRentItems::getPlaceName($ord[0]['idplace']);
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
		if (!empty($ord[0]['idreturnplace'])) {
			$dropoff_place = VikRentItems::getPlaceName($ord[0]['idreturnplace']);
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
			<div class="vri-bookingdet-tabs">
				<div class="vri-bookingdet-tab vri-bookingdet-tab-active" data-vbotab="vri-tab-details"><?php echo JText::translate('VRMODRES'); ?></div>
			</div>
		</div>

		<div class="vri-bookingdet-tab-cont" id="vri-tab-details" style="display: block;">
			<div class="vri-bookingdet-innercontainer">
				<div class="vri-bookingdet-customer">
					<div class="vri-bookingdet-detcont<?php echo $ord[0]['closure'] > 0 ? ' vri-bookingdet-closure' : ''; ?>">
						<div class="vri-editbooking-custarea-lbl">
							<?php echo JText::translate('VREDITORDERTWO'); ?>
						</div>
						<div class="vri-editbooking-custarea">
							<textarea name="custdata"><?php echo htmlspecialchars($ord[0]['custdata']); ?></textarea>
						</div>
					</div>
					<div class="vri-bookingdet-detcont">
						<div class="vri-bookingdet-checkdt">
							<label for="ritiro"><?php echo JText::translate('VRPEDITBUSYFOUR'); ?></label>
							<?php echo $vri_app->getCalendar($rit, 'ritiro', 'ritiro', $nowdf, array('class'=>'', 'size'=>'10', 'maxlength'=>'19', 'todayBtn' => 'true')); ?>
							<span class="vri-time-selects">
								<select name="pickuph" id="pickuph"><?php echo $ritho; ?></select>
								<span class="vri-time-selects-divider">:</span>
								<select name="pickupm" id="pickupm"><?php echo $ritmi; ?></select>
							</span>
						</div>
						<div class="vri-bookingdet-checkdt">
							<label for="consegna"><?php echo JText::translate('VRPEDITBUSYSIX'); ?></label>
							<?php echo $vri_app->getCalendar($con, 'consegna', 'consegna', $nowdf, array('class'=>'', 'size'=>'10', 'maxlength'=>'19', 'todayBtn' => 'true')); ?>
							<span class="vri-time-selects">
								<select name="dropoffh" id="dropoffh"><?php echo $conho; ?></select>
								<span class="vri-time-selects-divider">:</span>
								<select name="dropoffm" id="dropoffm"><?php echo $conmi; ?></select>
							</span>
						</div>
					</div>
				</div>
				<div class="vri-editbooking-summary">
			<?php
			if (is_array($ord) && (!empty($orderitems[0]['idtar']) || $is_cust_cost)) {
				//order from front end or correctly saved - start
				$wselplace = '<select class="vri-locations-select" name="idplace" id="idplace"><option value=""> ----- </option>'."\n";
				foreach ($locations as $lk => $lv) {
					$wselplace .= '<option value="'.$lv['id'].'"'.($lv['id'] == $ord[0]['idplace'] ? ' selected="selected"' : '').'>'.$lv['name'].'</option>'."\n";
				}
				$wselplace .= '</select>'."\n";
				$wselreturnplace = '<select class="vri-locations-select" name="idreturnplace" id="idreturnplace"><option value=""> ----- </option>'."\n";
				foreach ($locations as $lk => $lv) {
					$wselreturnplace .= '<option value="'.$lv['id'].'"'.($lv['id'] == $ord[0]['idreturnplace'] ? ' selected="selected"' : '').'>'.$lv['name'].'</option>'."\n";
				}
				$wselreturnplace .= '</select>'."\n";
				$proceedtars = true;
				$tars = array();
				foreach ($orderitems as $koi => $oi) {
					$num = $koi + 1;
					if ($ord[0]['hourly'] == 1) {
						$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `hours`=".(int)$hoursdiff." AND `iditem`=".(int)$oi['iditem']." ORDER BY `#__vikrentitems_dispcosthours`.`cost` ASC;";
					} else {
						$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `days`=".(int)$ord[0]['days']." AND `iditem`=".(int)$oi['iditem']." ORDER BY `#__vikrentitems_dispcost`.`cost` ASC;";
					}
					$dbo->setQuery($q);
					$dbo->execute();
					$tottars = $dbo->getNumRows();
					$proceedtars = false;
					if ($tottars == 0) {
						if ($ord[0]['hourly'] == 1) {
							//there are no hourly prices
							$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `days`=".(int)$ord[0]['days']." AND `iditem`=".(int)$oi['iditem']." ORDER BY `#__vikrentitems_dispcost`.`cost` ASC;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() > 0) {
								$proceedtars = true;
							}
						}
					} else {
						$proceedtars = true;
					}
					if ($proceedtars) {
						$tar = $dbo->loadAssocList();
						if ($ord[0]['hourly'] == 1) {
							foreach ($tar as $kt => $vt) {
								$tar[$kt]['days'] = 1;
							}
						}
						if ($checkhourscharges > 0 && $aehourschbasp == true) {
							$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false, false, true);
							$tar = $ret['return'];
							$calcdays = $ret['days'];
						}
						if ($checkhourscharges > 0 && $aehourschbasp == false) {
							$tar = VikRentItems::extraHoursSetPreviousFareItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false);
							$tar = VikRentItems::applySeasonsItem($tar, $ord[0]['ritiro'], $ord[0]['consegna'], $ord[0]['idplace']);
							$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true, false, true);
							$tar = $ret['return'];
							$calcdays = $ret['days'];
						} else {
							$tar = VikRentItems::applySeasonsItem($tar, $ord[0]['ritiro'], $ord[0]['consegna'], $ord[0]['idplace']);
						}
						$tar = VikRentItems::applyItemDiscounts($tar, $oi['iditem'], $oi['itemquant']);
					} else {
						break;
					}
					$tars[$num] = $tar;
				}
				if ($proceedtars) {
					?>
					<input type="hidden" name="areprices" value="yes"/>
					<input type="hidden" name="rm_item_oid" id="rm_item_oid" value="" />
					<div class="vri-editbooking-tbl">
					<?php
					// Items Loop Start
					foreach ($orderitems as $koi => $oi) {
						$num = $koi + 1;
						?>
						<div class="vri-bookingdet-summary-item vri-editbooking-summary-item">
							<div class="vri-editbooking-summary-item-head">
								<div class="vri-bookingdet-summary-itemnum"><i class="vriicn-stack"></i> <?php echo $oi['name'].($oi['itemquant'] > 1 ? " x".$oi['itemquant'] : ""); ?></div>
							<?php
							if (count($orderitems) > 1) {
								?>
								<div class="vri-editbooking-item-remove">
									<button type="button" class="btn btn-danger" onclick="vriConfirmRmItem('<?php echo $oi['id']; ?>');"><i class="fa fa-times-circle"></i> <?php echo JText::translate('VRIREMOVEITEM'); ?></button>
								</div>
								<?php
							}
							$switch_code = '';
							if ($switching) {
								$switch_code = sprintf($switcher, 'switch_'.$oi['id'], $oi['id'], $oi['iditem'], $oi['id']);
								?>
								<div class="vri-editbooking-item-switch">
									<?php echo $switch_code; ?>
								</div>
								<?php
							}
							if ((int)$oi['askquantity'] == 1) {
								?>
								<div class="vri-editbooking-item-quantity">
									<label for="itemquant<?php echo $koi; ?>"><?php echo JText::translate('VRINEWITEMQUANT'); ?></label>
									<input type="number" min="1" name="itemquant<?php echo $koi; ?>" id="itemquant<?php echo $koi; ?>" value="<?php echo $oi['itemquant']; ?>"/>
								</div>
								<?php
							}
							?>
							</div>
							<?php
							if (count($locations)) {
							?>
							<div class="vri-editbooking-item-traveler">
								<h4><?php echo JText::translate('VRPEDITBUSYLOCATIONS'); ?></h4>
								<div class="vri-editbooking-item-traveler-guestsinfo">
									<div class="vri-editbooking-item-traveler-name">
										<label for="idplace"><?php echo JText::translate('VRPEDITBUSYPICKPLACE'); ?></label>
										<?php echo $wselplace; ?>
									</div>
									<div class="vri-editbooking-item-traveler-name">
										<label for="idreturnplace"><?php echo JText::translate('VRPEDITBUSYDROPPLACE'); ?></label>
										<?php echo $wselreturnplace; ?>
									</div>
								</div>
							</div>
							<?php
							}
							?>
							<div class="vri-editbooking-item-pricetypes">
								<h4><?php echo JText::translate('VRPEDITBUSYSEVEN'); ?></h4>
								<div class="vri-editbooking-item-pricetypes-wrap">
							<?php
							$is_cust_cost = (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0.00);
							if ($is_cust_cost) {
								//custom rate
								?>
									<div class="vri-editbooking-item-pricetype vri-editbooking-item-pricetype-active">
										<div class="vri-editbooking-item-pricetype-inner">
											<label for="pid<?php echo $num.$oi['id']; ?>" class="hasTooltip" title="<?php echo JText::translate('VRIRENTCUSTRATETAXHELP'); ?>">
												<?php echo JText::translate('VRIRENTCUSTRATEPLAN'); ?>
											</label>
											<div class="vri-editbooking-item-pricetype-cost">
												<?php echo $currencysymb; ?> <input type="number" step="any" name="cust_cost<?php echo $num; ?>" value="<?php echo $oi['cust_cost']; ?>" size="4" onchange="if (this.value.length) {document.getElementById('pid<?php echo $num.$oi['id']; ?>').checked = true; jQuery('#pid<?php echo $num.$oi['id']; ?>').trigger('change');}"/>
												<div class="vri-editbooking-item-pricetype-seltax" id="tax<?php echo $num; ?>" style="display: block;">
													<?php echo (!empty($wiva) ? str_replace('%s', $num, str_replace('data-aliqid="'.(int)$oi['cust_idiva'].'"', 'selected="selected"', $wiva)) : ''); ?>
												</div>
											</div>
										</div>
										<div class="vri-editbooking-item-pricetype-check">
											<input class="vri-pricetype-radio" type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$oi['id']; ?>" value="" checked="checked" />
										</div>
									</div>
								<?php
								//print the standard rates anyway
								foreach ($tars[$num] as $k => $t) {
									?>
									<div class="vri-editbooking-item-pricetype">
										<div class="vri-editbooking-item-pricetype-inner">
											<label for="pid<?php echo $num.$t['idprice']; ?>"><?php echo VikRentItems::getPriceName($t['idprice']).(strlen($t['attrdata']) ? " - ".VikRentItems::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label>
											<div class="vri-editbooking-item-pricetype-cost">
												<?php echo $currencysymb." ".VikRentItems::numberFormat(VikRentItems::sayCostPlusIva($t['cost'] * $oi['itemquant'], $t['idprice'], $ord[0])); ?>
											</div>
										</div>
										<div class="vri-editbooking-item-pricetype-check">
											<input class="vri-pricetype-radio" type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$t['idprice']; ?>" value="<?php echo $t['idprice']; ?>" />
										</div>
									</div>
								<?php
								}
							} else {
								$sel_rate_changed = false;
								foreach ($tars[$num] as $k => $t) {
									$cur_cost = VikRentItems::sayCostPlusIva($t['cost'] * $oi['itemquant'], $t['idprice'], $ord[0]);
									$sel_rate_changed = $t['id'] == $oi['idtar'] ? $cur_cost : $sel_rate_changed;
									?>
									<div class="vri-editbooking-item-pricetype<?php echo $t['id'] == $oi['idtar'] ? ' vri-editbooking-item-pricetype-active' : ''; ?>">
										<div class="vri-editbooking-item-pricetype-inner">
											<label for="pid<?php echo $num.$t['idprice']; ?>"><?php echo VikRentItems::getPriceName($t['idprice']).(strlen($t['attrdata']) ? " - ".VikRentItems::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label>
											<div class="vri-editbooking-item-pricetype-cost">
												<?php echo $currencysymb." ".VikRentItems::numberFormat($cur_cost); ?>
											</div>
										</div>
										<div class="vri-editbooking-item-pricetype-check">
											<input class="vri-pricetype-radio" type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$t['idprice']; ?>" value="<?php echo $t['idprice']; ?>"<?php echo ($t['id'] == $oi['idtar'] ? " checked=\"checked\"" : ""); ?>/>
										</div>
									</div>
									<?php
								}
								//print the set custom rate anyway
								?>
									<div class="vri-editbooking-item-pricetype">
										<div class="vri-editbooking-item-pricetype-inner">
											<label for="cust_cost<?php echo $num; ?>" class="vri-custrate-lbl-add hasTooltip" title="<?php echo JText::translate('VRIRENTCUSTRATETAXHELP'); ?>"><?php echo JText::translate('VRIRENTCUSTRATEPLANADD'); ?></label>
											<div class="vri-editbooking-item-pricetype-cost">
												<?php echo $currencysymb; ?> <input type="number" step="any" name="cust_cost<?php echo $num; ?>" id="cust_cost<?php echo $num; ?>" value="" placeholder="<?php echo VikRentItems::numberFormat(($sel_rate_changed !== false ? $sel_rate_changed : 0)); ?>" size="4" onchange="if (this.value.length) {document.getElementById('priceid<?php echo $num; ?>').checked = true; jQuery('#priceid<?php echo $num; ?>').trigger('change');document.getElementById('tax<?php echo $num; ?>').style.display = 'block';}" />
												<div class="vri-editbooking-item-pricetype-seltax" id="tax<?php echo $num; ?>" style="display: none;">
													<?php echo (!empty($wiva) ? str_replace('%s', $num, $wiva) : ''); ?>
												</div>
											</div>
										</div>
										<div class="vri-editbooking-item-pricetype-check">
											<input class="vri-pricetype-radio" type="radio" name="priceid<?php echo $num; ?>" id="priceid<?php echo $num; ?>" value="" onclick="document.getElementById('tax<?php echo $num; ?>').style.display = 'block';" />
										</div>
									</div>
								<?php
							}
							?>
								</div>
							</div>
						<?php
						// @wponly lite - items options not supported
						$arropt = array();
						$optionals = empty($oi['idopt']) ? '' : VikRentItems::getItemOptionals($oi['idopt']);
						$specifications = '';
						//Item Options Start
						//Item Options End

						// Delivery service
						if (intval(VikRentItems::getItemParam($oi['params'], 'delivery')) == 1) {
							?>
							<div class="vri-editbooking-item-options vri-editbooking-item-delivery">
								<h4><?php echo JText::translate('VRIUSEDELIVERY'); ?></h4>
								<div class="vri-editbooking-item-options-wrap">
									<div class="vri-editbooking-item-option">
										<div class="vri-editbooking-item-option-inner">
											<label for="deliveryaddr<?php echo $oi['id']; ?>"><?php echo JText::translate('VRIMAILDELIVERYTO'); ?></label>
										</div>
										<div class="vri-editbooking-item-option-check">
											<input type="text" name="deliveryaddr<?php echo $oi['id']; ?>" size="30" value="<?php echo $oi['deliveryaddr']; ?>"<?php echo empty($oi['deliveryaddr']) ? ' placeholder="'.addslashes(JText::translate('VRIQUICKRESDELIVERYADDR')).'"' : ''; ?>/> - <input type="number" step="any" name="deliverydist<?php echo $oi['id']; ?>" value="<?php echo $oi['deliverydist']; ?>" min="0"/> <?php echo $deliverycalcunit; ?>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						//

						//custom extra services for each item Start
						// @wponly lite - extra fees not supported
						//custom extra services for each item End
						?>
						</div>
						<?php
					}
					//Items Loop End
					?>
						<div class="vri-bookingdet-summary-item vri-editbooking-summary-item vri-editbooking-summary-totpaid">
							<div class="vri-editbooking-summary-item-head">
								<div class="vri-editbooking-additem">
									<button class="btn btn-success" type="button" id="vri-add-item"><i class="icon-new"></i><?php echo JText::translate('VRIBOOKADDITEM'); ?></button>
								</div>
							<?php
							if ($totdelivery > 0 || $enter_delivery) {
								?>
								<div class="vri-editbooking-totdelivery">
									<label for="deliverycost"><?php echo JText::translate('VRIMAILTOTDELIVERY'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" id="deliverycost" name="deliverycost" value="<?php echo $ord[0]['deliverycost']; ?>" style="margin-right: 20px; width: 80px !important;"/>
								</div>
								<?php
							}
							?>
								<div class="vri-editbooking-totpaid">
									<label for="totpaid"><?php echo JText::translate('VRIPEDITBUSYTOTPAID'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" name="totpaid" value="<?php echo $ord[0]['totpaid']; ?>" style="margin: 0; width: 80px !important;"/>
								</div>
							</div>
						</div>
					</div>
					<?php
				} else {
					?>
					<p class="err"><?php echo JText::translate('VRPEDITBUSYERRNOFARES'); ?></p>
					<?php
				}
				//order from front end or correctly saved - end
			} elseif (is_array($ord) && empty($orderitems[0]['idtar'])) {
				//order is a quick reservation from administrator - start
				$proceedtars = true;
				$tars = array();
				foreach ($orderitems as $koi => $oi) {
					$num = $koi + 1;
					if ($ord[0]['hourly'] == 1) {
						$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `hours`=".(int)$hoursdiff." AND `iditem`=".(int)$oi['iditem']." ORDER BY `#__vikrentitems_dispcosthours`.`cost` ASC;";
					} else {
						$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `days`=".(int)$ord[0]['days']." AND `iditem`=".(int)$oi['iditem']." ORDER BY `#__vikrentitems_dispcost`.`cost` ASC;";
					}
					$dbo->setQuery($q);
					$dbo->execute();
					$tottars = $dbo->getNumRows();
					$proceedtars = false;
					if ($tottars == 0) {
						if ($ord[0]['hourly'] == 1) {
							$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `days`=".(int)$ord[0]['days']." AND `iditem`=".(int)$oi['iditem']." ORDER BY `#__vikrentitems_dispcost`.`cost` ASC;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() > 0) {
								$proceedtars = true;
							}
						}
					} else {
						$proceedtars = true;
					}
					if ($proceedtars) {
						$tar = $dbo->loadAssocList();
						$tars[$num] = $tar;
					} else {
						break;
					}
				}
				if ($proceedtars) {
					?>
					<input type="hidden" name="areprices" value="quick"/>
					<div class="vri-editbooking-tbl">
					<?php
					//Items Loop Start
					foreach ($orderitems as $koi => $oi) {
						$num = $koi + 1;
						?>
						<div class="vri-bookingdet-summary-item vri-editbooking-summary-item">
							<div class="vri-editbooking-summary-item-head">
								<div class="vri-bookingdet-summary-itemnum"><i class="vriicn-stack"></i> <?php echo $oi['name']; ?></div>
							</div>
							<div class="vri-editbooking-item-pricetypes">
								<h4><?php echo JText::translate('VRPEDITBUSYSEVEN'); ?><?php echo $ord[0]['closure'] < 1 && $ord[0]['status'] != 'cancelled' ? '&nbsp;&nbsp; '.$vri_app->createPopover(array('title' => JText::translate('VRPEDITBUSYSEVEN'), 'content' => JText::translate('VRIMISSPRTYPEITH'))) : ''; ?></h4>
								<div class="vri-editbooking-item-pricetypes-wrap">
								<?php
								//print the standard rates
								foreach ($tars[$num] as $k => $t) {
									?>
									<div class="vri-editbooking-item-pricetype">
										<div class="vri-editbooking-item-pricetype-inner">
											<label for="pid<?php echo $num.$t['idprice']; ?>"><?php echo VikRentItems::getPriceName($t['idprice']).(strlen($t['attrdata']) ? " - ".VikRentItems::getPriceAttr($t['idprice']).": ".$t['attrdata'] : ""); ?></label>
											<div class="vri-editbooking-item-pricetype-cost">
												<?php echo $currencysymb." ".VikRentItems::numberFormat(VikRentItems::sayCostPlusIva($t['cost'] * $oi['itemquant'], $t['idprice'], $ord[0])); ?>
											</div>
										</div>
										<div class="vri-editbooking-item-pricetype-check">
											<input class="vri-pricetype-radio" type="radio" name="priceid<?php echo $num; ?>" id="pid<?php echo $num.$t['idprice']; ?>" value="<?php echo $t['idprice']; ?>" />
										</div>
									</div>
									<?php
								}
								//print the custom cost
								?>
									<div class="vri-editbooking-item-pricetype">
										<div class="vri-editbooking-item-pricetype-inner">
											<label for="cust_cost<?php echo $num; ?>" class="vri-custrate-lbl-add hasTooltip" title="<?php echo JText::translate('VRIRENTCUSTRATETAXHELP'); ?>"><?php echo JText::translate('VRIRENTCUSTRATEPLANADD'); ?></label>
											<div class="vri-editbooking-item-pricetype-cost">
												<?php echo $currencysymb; ?> <input type="number" step="any" name="cust_cost<?php echo $num; ?>" id="cust_cost<?php echo $num; ?>" value="" placeholder="<?php echo VikRentItems::numberFormat(0); ?>" size="4" onchange="if (this.value.length) {document.getElementById('priceid<?php echo $num; ?>').checked = true; jQuery('#priceid<?php echo $num; ?>').trigger('change'); document.getElementById('tax<?php echo $num; ?>').style.display = 'block';}" />
												<div class="vri-editbooking-item-pricetype-seltax" id="tax<?php echo $num; ?>" style="display: none;"><?php echo (!empty($wiva) ? str_replace('%s', $num, $wiva) : ''); ?></div>
											</div>
										</div>
										<div class="vri-editbooking-item-pricetype-check">
											<input class="vri-pricetype-radio" type="radio" name="priceid<?php echo $num; ?>" id="priceid<?php echo $num; ?>" value="" onclick="document.getElementById('tax<?php echo $num; ?>').style.display = 'block';" />
										</div>
									</div>
								<?php
								//
								?>
								</div>
							</div>
						<?php
						$arropt = array();
						$optionals = empty($oi['idopt']) ? '' : VikRentItems::getItemOptionals($oi['idopt']);
						$specifications = '';
						//Item Options Start
						if (is_array($optionals)) {
							// parse specifications first
							list($optionals, $specifications) = VikRentItems::loadOptionSpecifications($optionals);
							?>
							<div class="vri-editbooking-item-options">
								<h4><?php echo JText::translate('VRPEDITBUSYEIGHT'); ?></h4>
								<div class="vri-editbooking-item-options-wrap">
							<?php
							if (!empty($oi['optionals'])) {
								$haveopt = explode(";", $oi['optionals']);
								foreach ($haveopt as $ho) {
									if (!empty($ho)) {
										$havetwo = explode(":", $ho);
										$havethree = explode("-", $havetwo[1]);
										$arropt[$havetwo[0]] = isset($havethree[1]) ? $havethree[1] : 0;
									}
								}
							}
							if (is_array($specifications) && count($specifications) > 0) {
								foreach ($specifications as $specification) {
									$specselect = '<select name="optid'.$num.$specification['id'].'" id="optid'.$num.$specification['id'].'"><option value=""></option>'."\n";
									$intervals = explode(';;', $specification['specifications']);
									foreach ($intervals as $kintv => $intv) {
										if (empty($intv)) continue;
										$intvparts = explode('_', $intv);
										$intvparts[1] = intval($specification['perday']) == 1 ? ($intvparts[1] * $tars[$num]['days']) : $intvparts[1];
										if (!empty($specification['maxprice']) && $specification['maxprice'] > 0 && $intvparts[1] > $specification['maxprice']) {
											$intvparts[1] = $specification['maxprice'];
										}
										$intvparts[1] = VikRentItems::sayOptionalsPlusIva($intvparts[1], $specification['idiva'], $ord[0]);
										$pricestr = floatval($intvparts[1]) >= 0 ? '+ '.VikRentItems::numberFormat($intvparts[1]) : '- '.VikRentItems::numberFormat($intvparts[1]);
										$specselect .= '<option value="'.($kintv + 1).'"'.(array_key_exists($specification['id'], $arropt) && $arropt[$specification['id']] == ($kintv + 1) ? ' selected="selected"' : '').'>'.$intvparts[0].(VikRentItems::numberFormat(($intvparts[1] * 1)) != '0.00' ? ' ('.$pricestr.' '.$currencysymb.')' : '').'</option>'."\n";
									}
									$specselect .= '</select>'."\n";
									?>
									<div class="vri-editbooking-item-option vri-editbooking-item-option-specifications">
										<div class="vri-editbooking-item-option-inner">
											<label for="optid<?php echo $num.$specification['id']; ?>"><?php echo $specification['name']; ?></label>
										</div>
										<div class="vri-editbooking-item-option-spec">
											<?php echo $specselect; ?>
										</div>
									</div>
									<?php
								}
							}
							if (is_array($optionals)) {
								// parse regular optionals
								foreach ($optionals as $k => $o) {
									?>
									<div class="vri-editbooking-item-option">
										<div class="vri-editbooking-item-option-inner">
											<label for="optid<?php echo $num.$o['id']; ?>"><?php echo $o['name']; ?></label>
											<div class="vri-editbooking-item-option-check">
												<?php echo (intval($o['hmany'])==1 ? "<input type=\"number\" name=\"optid".$num.$o['id']."\" id=\"optid".$num.$o['id']."\" value=\"\" min=\"0\" size=\"4\" style=\"width: 50px !important;\"/>" : "<input type=\"checkbox\" name=\"optid".$num.$o['id']."\" id=\"optid".$num.$o['id']."\" value=\"1\" />"); ?>
											</div>
										</div>
									</div>
									<?php
								}
							}
							?>
								</div>
							</div>
							<?php
						}
						//Item Options End
						// Delivery service
						if (intval(VikRentItems::getItemParam($oi['params'], 'delivery')) == 1) {
							?>
							<div class="vri-editbooking-item-options vri-editbooking-item-delivery">
								<h4><?php echo JText::translate('VRIUSEDELIVERY'); ?></h4>
								<div class="vri-editbooking-item-options-wrap">
									<div class="vri-editbooking-item-option">
										<div class="vri-editbooking-item-option-inner">
											<label for="deliveryaddr<?php echo $oi['id']; ?>"><?php echo JText::translate('VRIMAILDELIVERYTO'); ?></label>
										</div>
										<div class="vri-editbooking-item-option-check">
											<input type="text" name="deliveryaddr<?php echo $oi['id']; ?>" size="30" value="<?php echo $oi['deliveryaddr']; ?>"<?php echo empty($oi['deliveryaddr']) ? ' placeholder="'.addslashes(JText::translate('VRIQUICKRESDELIVERYADDR')).'"' : ''; ?>/> - <input type="number" step="any" name="deliverydist<?php echo $oi['id']; ?>" value="<?php echo $oi['deliverydist']; ?>" min="0"/> <?php echo $deliverycalcunit; ?>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						//
						?>
						</div>
						<?php
					}
					//Items Loop End
					?>
						<div class="vri-bookingdet-summary-item vri-editbooking-summary-item vri-editbooking-summary-totpaid">
							<div class="vri-editbooking-summary-item-head">
							<?php
							if ($totdelivery > 0 || $enter_delivery) {
								?>
								<div class="vri-editbooking-totdelivery">
									<label for="deliverycost"><?php echo JText::translate('VRIMAILTOTDELIVERY'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" id="deliverycost" name="deliverycost" value="<?php echo $ord[0]['deliverycost']; ?>" style="margin-right: 20px; width: 80px !important;"/>
								</div>
								<?php
							}
							?>
								<div class="vri-editbooking-totpaid">
									<label for="totpaid"><?php echo JText::translate('VRIPEDITBUSYTOTPAID'); ?></label>
									<?php echo $currencysymb; ?> <input type="number" min="0" step="any" name="totpaid" value="<?php echo $ord[0]['totpaid']; ?>" style="margin: 0; width: 80px !important;"/>
								</div>
							</div>
						</div>
					</div>
					<?php
				} else {
					?>
					<p class="err"><?php echo JText::translate('VRPEDITBUSYERRNOFARES'); ?></p>
					<?php
				}
				//order is a quick reservation from administrator - end
			}
			?>
				</div>
			</div>
		</div>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="idorder" value="<?php echo $ord[0]['id']; ?>">
		<input type="hidden" name="option" value="com_vikrentitems">
		<?php
		$pfrominv = VikRequest::getInt('frominv', '', 'request');
		echo $pfrominv == 1 ? '<input type="hidden" name="frominv" value="1">' : '';
		echo $pgoto == 'overv' ? '<input type="hidden" name="goto" value="overv">' : '';
		?>
	</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#ritiro').val('<?php echo $rit; ?>').attr('data-alt-value', '<?php echo $rit; ?>');
	jQuery('#consegna').val('<?php echo $con; ?>').attr('data-alt-value', '<?php echo $con; ?>');
	jQuery('.vri-pricetype-radio').change(function() {
		jQuery(this).closest('.vri-editbooking-item-pricetypes').find('.vri-editbooking-item-pricetype.vri-editbooking-item-pricetype-active').removeClass('vri-editbooking-item-pricetype-active');
		jQuery(this).closest('.vri-editbooking-item-pricetype').addClass('vri-editbooking-item-pricetype-active');
	});
});
if (jQuery.isFunction(jQuery.fn.tooltip)) {
	jQuery(".hasTooltip").tooltip();
}
</script>
