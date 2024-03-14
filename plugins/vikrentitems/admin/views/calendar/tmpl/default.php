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

$itemrows = $this->itemrows;
$msg = $this->msg;
$allc = $this->allc;
$payments = $this->payments;
$busy = $this->busy;
$vmode = $this->vmode;
$pickuparr = $this->pickuparr;
$dropoffarr = $this->dropoffarr;

$dbo = JFactory::getDBO();
$vri_app = VikRentItems::getVriApplication();
$vri_app->loadSelect2();

$document = JFactory::getDocument();
$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
JHtml::fetch('jquery.framework', true, true);
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.min.js');
$vri_df = VikRentItems::getDateFormat(true);
if ($vri_df == "%d/%m/%Y") {
	$df = 'd/m/Y';
	$juidf = 'dd/mm/yy';
} elseif ($vri_df == "%m/%d/%Y") {
	$df = 'm/d/Y';
	$juidf = 'mm/dd/yy';
} else {
	$df = 'Y/m/d';
	$juidf = 'yy/mm/dd';
}
$pritiro = VikRequest::getString('ritiro', '', 'request');
if (!empty($pritiro)) {
	$pritiro = date(str_replace('%', '', $vri_df), strtotime($pritiro));
}
$pconsegna = VikRequest::getString('consegna', '', 'request');
if (!empty($pconsegna)) {
	$pconsegna = date(str_replace('%', '', $vri_df), strtotime($pconsegna));
}
$ptmpl = VikRequest::getString('tmpl', '', 'request');
$poverview = VikRequest::getInt('overv', '', 'request');
$poverview_change = VikRequest::getInt('overview_change', '', 'request');
$pidprice = VikRequest::getInt('idprice', 0, 'request');
$pbooknow = VikRequest::getInt('booknow', 0, 'request');
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
});';
$document->addScriptDeclaration($ldecl);

if (strlen($msg) > 0 && intval($msg) > 0) {
	?>
<p class="successmade"><?php echo JText::translate('VRBOOKMADE'); ?> &nbsp;&nbsp;&nbsp; <a href="index.php?option=com_vikrentitems&task=editorder&cid[]=<?php echo intval($msg); ?>" class="btn"><?php VikRentItemsIcons::e('eye'); ?> <?php echo JText::translate('VRIVIEWBOOKINGDET'); ?></a></p>
	<?php
} elseif (strlen($msg) > 0 && $msg == "0") {
	?>
<p class="err" style="margin-top: -5px;"><?php echo JText::translate('VRBOOKNOTMADE'); ?></p>
	<?php
}

$timeopst = VikRentItems::getTimeOpenStore();
if (is_array($timeopst) && $timeopst[0]!=$timeopst[1]) {
	$opent = VikRentItems::getHoursMinutes($timeopst[0]);
	$closet = VikRentItems::getHoursMinutes($timeopst[1]);
	$i = $opent[0];
	$j = $closet[0];
} else {
	$i = 0;
	$j = 23;
}
$hours = $minutes = '';
while ($i <= $j) {
	if ($i < 10) {
		$i = "0".$i;
	} else {
		$i = $i;
	}
	$hours .= "<option value=\"".$i."\">".$i."</option>\n";
	$i++;
}
for ($i = 0; $i < 60; $i++) {
	if ($i < 10) {
		$i = "0".$i;
	} else {
		$i = $i;
	}
	$minutes .= "<option value=\"".$i."\">".$i."</option>\n";
}

$formatparts = explode(':', VikRentItems::getNumberFormatData());
$currencysymb = VikRentItems::getCurrencySymb(true);
$selpayments = '<select name="payment"><option value="">'.JText::translate('VRIQUICKRESNONE').'</option>';
// @wponly lite - payment gateways are not supported
$selpayments .= '</select>';

// custom fields
$all_cfields = array();
$all_countries = array();
$q = "SELECT * FROM `#__vikrentitems_custfields` ORDER BY `#__vikrentitems_custfields`.`ordering` ASC;";
$dbo->setQuery($q);
$dbo->execute();
if ($dbo->getNumRows() > 0) {
	$all_cfields = $dbo->loadAssocList();
	$q = "SELECT * FROM `#__vikrentitems_countries` ORDER BY `#__vikrentitems_countries`.`country_name` ASC;";
	$dbo->setQuery($q);
	$dbo->execute();
	$all_countries = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
}

// taxes
$wiva = "";
$q = "SELECT * FROM `#__vikrentitems_iva`;";
$dbo->setQuery($q);
$dbo->execute();
if ($dbo->getNumRows() > 0) {
	$ivas = $dbo->loadAssocList();
	foreach ($ivas as $kiv => $iv) {
		$wiva .= "<option value=\"".$iv['id']."\" data-aliqid=\"".$iv['id']."\"".($kiv < 1 ? ' selected="selected"' : '').">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']." - ".$iv['aliq']."%")."</option>\n";
	}
}

// places
$pickopts = '';
$dropopts = '';
if (count($pickuparr) && count($dropoffarr)) {
	foreach ($pickuparr as $locv) {
		$pickopts .= '<option value="'.$locv['id'].'">'.$locv['name'].'</option>'."\n";
	}
	foreach ($dropoffarr as $locv) {
		$dropopts .= '<option value="'.$locv['id'].'">'.$locv['name'].'</option>'."\n";
	}
}
?>

<div class="vri-admin-container">
	
	<div class="vri-config-maintab-left">

		<fieldset class="adminform">
			<div class="vri-params-wrap">
				<legend class="adminlegend">
					<div class="vri-quickres-head">
						<span><?php echo $itemrows['name'] . " - " . JText::translate('VRQUICKBOOK'); ?></span>
						<div class="vri-quickres-head-right">
							<form name="vrichitem" id="vrichitem" method="post" action="index.php?option=com_vikrentitems">
								<input type="hidden" name="task" value="calendar"/>
								<input type="hidden" name="option" value="com_vikrentitems"/>
								<select id="vri-calendar-changeitem" name="cid[]" onchange="jQuery('#vrichitem').submit();">
								<?php
								foreach ($allc as $cc) {
									echo "<option value=\"".$cc['id']."\"".($cc['id'] == $itemrows['id'] ? " selected=\"selected\"" : "").">".$cc['name']."</option>\n";
								}
								?>
								</select>
							<?php
							if ($ptmpl == 'component') {
								echo "<input type=\"hidden\" name=\"tmpl\" value=\"component\" />\n";
							}
							?>
							</form>
						</div>
					</div>
				</legend>
				<form name="newb" method="post" action="index.php?option=com_vikrentitems" onsubmit="javascript: if (!document.newb.pickupdate.value.match(/\S/)){alert('<?php echo addslashes(JText::translate('VRMSGTHREE')); ?>'); return false;} if (!document.newb.releasedate.value.match(/\S/)){alert('<?php echo addslashes(JText::translate('VRMSGFOUR')); ?>'); return false;} return true;">
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRDATEPICKUP'); ?></div>
							<div class="vri-param-setting">
								<div class="input-append">
									<input type="text" autocomplete="off" name="pickupdate" id="pickupdate" size="10" />
									<button type="button" class="btn vridatepicker-trig-icon"><span class="icon-calendar"></span></button>
								</div>
								<span class="vri-calendar-time-inline"><?php echo JText::translate('VRAT'); ?></span>
								<select name="pickuph"><?php echo $hours; ?></select> : <select name="pickupm"><?php echo $minutes; ?></select>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRDATERELEASE'); ?></div>
							<div class="vri-param-setting">
								<div class="input-append">
									<input type="text" autocomplete="off" name="releasedate" id="releasedate" size="10" />
									<button type="button" class="btn vridatepicker-trig-icon"><span class="icon-calendar"></span></button>
								</div>
								<span class="vri-calendar-time-inline"><?php echo JText::translate('VRAT'); ?></span>
								<select name="releaseh"><?php echo $hours; ?></select> : <select name="releasem"><?php echo $minutes; ?></select>
								<span style="display: inline-block; margin-left: 25px; font-weight: bold;" id="vrjstotnights"></span>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label">
								<span class="vricloseitemsp">
									<label for="setclosed-on"><?php echo JText::translate('VRICLOSEITEM'); ?> <i class="<?php echo VikRentItemsIcons::i('ban'); ?>" style="float: none;"></i></label>
								</span>
							</div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('setclosed', JText::translate('VRYES'), JText::translate('VRNO'), 0, 1, 0, 'vriCloseItem();'); ?>
							</div>
						</div>
					<?php
					if ($itemrows['units'] > 1) {
						$num_items_vals = range(1, $itemrows['units']);
						?>
						<div class="vri-param-container" id="vrspannumitems">
							<div class="vri-param-label"><?php echo JText::translate('VRIQUANTITY'); ?></div>
							<div class="vri-param-setting">
								<select name="itemquant">
								<?php
								foreach ($num_items_vals as $nrv) {
									?>
									<option value="<?php echo $nrv; ?>"><?php echo $nrv; ?></option>
									<?php
								}
								?>
								</select>
							</div>
						</div>
						<?php
					} else {
						echo '<input type="hidden" name="itemquant" value="1"/>';
					}
					if (count($pickuparr) && count($dropoffarr)) {
						?>
						<div class="vri-param-container" id="vrspanblocations">
							<div class="vri-param-label"><?php echo JText::translate('VRIQUICKRESLOCATIONS'); ?></div>
							<div class="vri-param-setting">
								<span class="vri-quickres-selwrap">
									<select name="pickuploc" id="pickuploc">
										<option></option>
										<?php echo $pickopts; ?>
									</select>
								</span>
								<span class="vri-quickres-selwrap">
									<select name="dropoffloc" id="dropoffloc">
										<option></option>
										<?php echo $dropopts; ?>
									</select>
								</span>
							</div>
						</div>
						<?php
					}
					?>
						<div class="vri-param-container" id="vrspanbstat">
							<div class="vri-param-label"><?php echo JText::translate('VRIQUICKRESORDSTATUS'); ?></div>
							<div class="vri-param-setting">
								<select name="newstatus">
									<option value="confirmed"><?php echo JText::translate('VRCONFIRMED'); ?></option>
									<option value="standby"><?php echo JText::translate('VRSTANDBY'); ?></option>
								</select>
							</div>
						</div>
						<div class="vri-param-container" id="vrspanbpay">
							<div class="vri-param-label"><?php echo JText::translate('VRIQUICKRESMETHODOFPAYMENT'); ?></div>
							<div class="vri-param-setting">
								<?php echo $selpayments; ?>
							</div>
						</div>
					<?php
					if (intval(VikRentItems::getItemParam($itemrows['params'], 'delivery')) == 1) {
						?>
						<div class="vri-param-container" id="vrspanbdeliveraddr">
							<div class="vri-param-label"><?php echo JText::translate('VRIQUICKRESDELIVERYADDR'); ?></div>
							<div class="vri-param-setting">
								<input type="text" name="deliveryaddr" size="20" value=""/>
							</div>
						</div>
						<div class="vri-param-container" id="vrspanbdeliverdist">
							<div class="vri-param-label"><?php echo JText::translate('VRIQUICKRESDELIVERYDIST'); ?></div>
							<div class="vri-param-setting">
								<input type="number" name="deliverydist" step="any" value=""/>
							</div>
						</div>
						<?php
					}
					?>
						<div class="vri-param-container" id="vrfillcustfields">
							<div class="vri-param-label">&nbsp;</div>
							<div class="vri-param-setting">
								<span class="vri-assign-customer">
									<i class="<?php echo VikRentItemsIcons::i('user-circle'); ?>"></i>
									<span><?php echo JText::translate('VRFILLCUSTFIELDS'); ?></span>
								</span>
							</div>
						</div>
						<div class="vri-param-container" id="vrspancmail">
							<div class="vri-param-label"><?php echo JText::translate('VRQRCUSTMAIL'); ?></div>
							<div class="vri-param-setting">
								<input type="text" name="custmail" id="custmailfield" value="" size="25"/>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRCUSTINFO'); ?></div>
							<div class="vri-param-setting">
								<textarea name="custdata" id="vricustdatatxtarea" rows="5" cols="70" style="min-width: 300px;"></textarea>
							</div>
						</div>
						<div class="vri-param-container" id="vri-website-rates-row" style="display: none;">
							<div class="vri-param-label"><?php echo JText::translate('VRIWEBSITERATES'); ?></div>
							<div class="vri-param-setting" id="vri-website-rates-cont"></div>
						</div>
						<div class="vri-param-container" id="vrspcustcost">
							<div class="vri-param-label"><?php echo JText::translate('VRIRENTCUSTRATEPLANADD'); ?></div>
							<div class="vri-param-setting">
								<span>
									<?php echo $currencysymb; ?> <input name="cust_cost" id="cust_cost" value="" onfocus="document.getElementById('taxid').style.display = 'inline-block';" onkeyup="vriCalcDailyCost(this.value);" onchange="vriCalcDailyCost(this.value);" type="number" step="any" min="0" style="min-width: 75px; margin: 0 5px 0 0;">
									<select name="taxid" id="taxid" style="display: none; margin: 0; max-width: 150px;">
										<option value=""><?php echo JText::translate('VRNEWOPTFOUR'); ?></option>
										<?php echo $wiva; ?>
									</select>
									<span id="avg-daycost" style="display: inline-block; margin-left: 15px;"></span>
								</span>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label">&nbsp;</div>
							<div class="vri-param-setting">
								<button type="submit" id="quickbsubmit" class="btn btn-success btn-large"><?php VikRentItemsIcons::e('save'); ?> <span><?php echo JText::translate('VRMAKERESERV'); ?></span></button>
							</div>
						</div>
					</div>
					<?php
					if ($ptmpl == 'component') {
						?>
						<input type="hidden" name="tmpl" value="component" />
						<?php
					}
					?>
					<input type="hidden" name="customer_id" value="" id="customer_id_inpfield"/>
					<input type="hidden" name="countrycode" value="" id="ccode_inpfield"/>
					<input type="hidden" name="t_first_name" value="" id="t_first_name_inpfield"/>
					<input type="hidden" name="t_last_name" value="" id="t_last_name_inpfield"/>
					<input type="hidden" name="phone" value="" id="phonefield"/>
					<input type="hidden" name="idprice" value="" id="booking-idprice"/>
					<input type="hidden" name="itemcost" value="" id="booking-itemcost"/>
					<input type="hidden" name="task" value="calendar"/>
					<input type="hidden" name="cid[]" value="<?php echo $itemrows['id']; ?>"/>
					<input type="hidden" name="option" value="com_vikrentitems" />
				</form>
			</div>
		</fieldset>

	</div>
	<div class="vri-config-maintab-right">
		<div class="vri-avcalendars-wrapper">
			<div class="vri-avcalendars-itemphoto">
			<?php
			if (is_file(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $itemrows['img'])) {
				?>
				<img alt="Item Image" src="<?php echo VRI_ADMIN_URI; ?>resources/<?php echo $itemrows['img']; ?>" />
				<?php
			} else {
				VikRentItemsIcons::e('image', 'vri-enormous-icn');
			}
			?>
			</div>
		<?php
		$check = false;
		$nowtf = VikRentItems::getTimeFormat(true);
		if (empty($busy)) {
			echo "<p class=\"warn\">".JText::translate('VRNOFUTURERES')."</p>";
		} else {
			$check = true;
			$icalurl = JURI::root().'index.php?option=com_vikrentitems&task=ical&elem='.$itemrows['id'].'&key='.VikRentItems::getIcalSecretKey();
			?>
			<p>
				<a class="vrmodelink<?php echo $vmode == 3 ? ' vrmodelink-active' : ''; ?>" href="index.php?option=com_vikrentitems&amp;task=calendar&amp;cid[]=<?php echo $itemrows['id'].($ptmpl == 'component' ? '&tmpl=component' : ''); ?>&amp;vmode=3"><?php VikRentItemsIcons::e('calendar'); ?> <span><?php echo JText::translate('VRTHREEMONTHS'); ?></span></a>
				<a class="vrmodelink<?php echo $vmode == 6 ? ' vrmodelink-active' : ''; ?>" href="index.php?option=com_vikrentitems&amp;task=calendar&amp;cid[]=<?php echo $itemrows['id'].($ptmpl == 'component' ? '&tmpl=component' : ''); ?>&amp;vmode=6"><?php VikRentItemsIcons::e('calendar'); ?> <span><?php echo JText::translate('VRSIXMONTHS'); ?></span></a>
				<a class="vrmodelink<?php echo $vmode == 12 ? ' vrmodelink-active' : ''; ?>" href="index.php?option=com_vikrentitems&amp;task=calendar&amp;cid[]=<?php echo $itemrows['id'].($ptmpl == 'component' ? '&tmpl=component' : ''); ?>&amp;vmode=12"><?php VikRentItemsIcons::e('calendar'); ?> <span><?php echo JText::translate('VRTWELVEMONTHS'); ?></span></a>
				<a class="vrmodelink" href="javascript: void(0);" onclick="jQuery('#icalsynclinkinp').attr('size', (jQuery('#icalsynclinkinp').val().length + 5)).fadeToggle().focus();"><?php VikRentItemsIcons::e('link'); ?> <span><?php echo JText::translate('VRIICALLINK'); ?></span></a>
				<input id="icalsynclinkinp" style="display: none;" type="text" value="<?php echo $icalurl; ?>" readonly="readonly" size="40" onfocus="jQuery('#icalsynclinkinp').select();"/>
			</p>
			<?php
		}
		?>
			<div class="vri-calendar-cals-container">
			<?php
			$arr = getdate();
			$mon = $arr['mon'];
			$realmon = ($mon < 10 ? "0".$mon : $mon);
			$year = $arr['year'];
			$day = $realmon."/01/".$year;
			$dayts = strtotime($day);
			$newarr = getdate($dayts);

			$firstwday = (int)VikRentItems::getFirstWeekDay(true);
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

			for ($jj = 1; $jj <= $vmode; $jj++) {
				$d_count = 0;
				echo '<div class="vri-calendar-cal-container">';
				$cal = "";
				?>
				<table class="vriadmincaltable">
					<tr class="vriadmincaltrmon">
						<td colspan="7" align="center"><?php echo VikRentItems::sayMonth($newarr['mon'])." ".$newarr['year']; ?></td>
					</tr>
					<tr class="vriadmincaltrmdays">
					<?php
					for ($i = 0; $i < 7; $i++) {
						$d_ind = ($i + $firstwday) < 7 ? ($i + $firstwday) : ($i + $firstwday - 7);
						?>
						<td><?php echo $days_labels[$d_ind]; ?></td>
						<?php
					}
					?>
					</tr>
					<tr>
					<?php
					for ($i = 0, $n = $days_indexes[$newarr['wday']]; $i < $n; $i++, $d_count++) {
						$cal .= "<td align=\"center\">&nbsp;</td>";
					}
					while ($newarr['mon'] == $mon) {
						if ($d_count > 6) {
							$d_count = 0;
							$cal .= "</tr>\n<tr>";
						}
						$dclass = "free";
						$dalt = "";
						$bid = "";
						$totfound = 0;
						if ($check) {
							foreach ($busy as $b) {
								$tmpone = getdate($b['ritiro']);
								$ritts = mktime(0, 0, 0, $tmpone['mon'], $tmpone['mday'], $tmpone['year']);
								$tmptwo = getdate($b['consegna']);
								$conts = mktime(0, 0, 0, $tmptwo['mon'], $tmptwo['mday'], $tmptwo['year']);
								if ($newarr[0] >= $ritts && $newarr[0] <= $conts) {
									$dclass = "busy";
									$bid = $b['idorder'];
									if ((int)$b['closure'] > 0) {
										$dclass .= " busy-closure";
										$dalt = JText::translate('VRDBTEXTROOMCLOSED');
									} elseif ($newarr[0] == $ritts) {
										$dalt = JText::translate('VRPICKUPAT')." ".date($nowtf, $b['ritiro']);
									} elseif ($newarr[0] == $conts) {
										$dalt = JText::translate('VRRELEASEAT')." ".date($nowtf, $b['consegna']);
									}
									$totfound++;
								}
							}
						}
						$useday = ($newarr['mday'] < 10 ? "0".$newarr['mday'] : $newarr['mday']);
						if ($totfound > 0 && $totfound < $itemrows['units']) {
							$dclass .= " vri-partially";
						}
						if ($totfound == 1) {
							/**
							 * @wponly lite - link changed to "editorder"
							 */
							$dlnk = "<a href=\"index.php?option=com_vikrentitems&task=editorder&cid[]=".$bid."\"".($ptmpl == 'component' ? ' target="_blank"' : '').">".$useday."</a>";
							//
							$cal .= "<td align=\"center\" data-daydate=\"".date($df, $newarr[0])."\" class=\"".$dclass."\"".(!empty($dalt) ? " title=\"".$dalt."\"" : "").">".$dlnk."</td>\n";
						} elseif ($totfound > 1) {
							$dlnk = "<a href=\"index.php?option=com_vikrentitems&task=choosebusy&iditem=".$itemrows['id']."&ts=".$newarr[0]."\"".($ptmpl == 'component' ? ' target="_blank"' : '').">".$useday."</a>";
							$cal .= "<td align=\"center\" data-daydate=\"".date($df, $newarr[0])."\" class=\"".$dclass."\">".$dlnk."</td>\n";
						} else {
							$dlnk = $useday;
							$cal .= "<td align=\"center\" data-daydate=\"".date($df, $newarr[0])."\" class=\"".$dclass."\">".$dlnk."</td>\n";
						}
						$next = $newarr['mday'] + 1;
						$dayts = mktime(0, 0, 0, ($newarr['mon'] < 10 ? "0".$newarr['mon'] : $newarr['mon']), ($next < 10 ? "0".$next : $next), $newarr['year']);
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
				<?php
				echo "</div>";
				if ($mon == 12) {
					$mon = 1;
					$year += 1;
					$dayts = mktime(0, 0, 0, ($mon < 10 ? "0".$mon : $mon), 01, $year);
				} else {
					$mon += 1;
					$dayts = mktime(0, 0, 0, ($mon < 10 ? "0".$mon : $mon), 01, $year);
				}
				$newarr = getdate($dayts);
			}
			?>
			</div>
		</div>
	</div>
</div>

<div class="vri-calendar-cfields-filler-overlay">
	<a class="vri-info-overlay-close" href="javascript: void(0);"></a>
	<div class="vri-calendar-cfields-filler">
		<div class="vri-calendar-cfields-topcont">
			<div class="vri-calendar-cfields-custinfo">
				<h4><?php echo JText::translate('VRCUSTINFO'); ?></h4>
			</div>
			<div class="vri-calendar-cfields-search">
				<label for="vri-searchcust"><?php echo JText::translate('VRISEARCHEXISTCUST'); ?></label>
				<span id="vri-searchcust-loading">
					<i class="vriicn-hour-glass"></i>
				</span>
				<input type="text" id="vri-searchcust" autocomplete="off" value="" placeholder="<?php echo JText::translate('VRISEARCHCUSTBY'); ?>" size="35" />
				<div id="vri-searchcust-res"></div>
			</div>
		</div>
		<div class="vri-calendar-cfields-inner">
	<?php
	$phone_field_id = '';
	foreach ($all_cfields as $cfield) {
		if ($cfield['type'] == 'text' && $cfield['isphone'] == 1) {
			$phone_field_id = 'cfield' . $cfield['id'];
			?>
			<div class="vri-calendar-cfield-entry">
				<label for="<?php echo $phone_field_id; ?>" data-fieldid="<?php echo $cfield['id']; ?>"><?php echo JText::translate($cfield['name']); ?></label>
				<span>
					<?php echo $vri_app->printPhoneInputField(array('id' => $phone_field_id, 'data-isemail' => '0', 'data-isnominative' => '0', 'data-isphone' => '1'), array('fullNumberOnBlur' => true)); ?>
				</span>
			</div>
			<?php
		} elseif ($cfield['type'] == 'text') {
			?>
			<div class="vri-calendar-cfield-entry">
				<label for="cfield<?php echo $cfield['id']; ?>" data-fieldid="<?php echo $cfield['id']; ?>"><?php echo JText::translate($cfield['name']); ?></label>
				<span>
					<input type="text" id="cfield<?php echo $cfield['id']; ?>" data-isemail="<?php echo ($cfield['isemail'] == 1 ? '1' : '0'); ?>" data-isnominative="<?php echo ($cfield['isnominative'] == 1 ? '1' : '0'); ?>" data-isphone="0" value="" size="35"/>
				</span>
			</div>
			<?php
		} elseif ($cfield['type'] == 'textarea') {
			?>
			<div class="vri-calendar-cfield-entry">
				<label for="cfield<?php echo $cfield['id']; ?>" data-fieldid="<?php echo $cfield['id']; ?>"><?php echo JText::translate($cfield['name']); ?></label>
				<span>
					<textarea id="cfield<?php echo $cfield['id']; ?>" rows="4" cols="35"></textarea>
				</span>
			</div>
			<?php
		} elseif ($cfield['type'] == 'country') {
			?>
			<div class="vri-calendar-cfield-entry">
				<label for="cfield<?php echo $cfield['id']; ?>" data-fieldid="<?php echo $cfield['id']; ?>"><?php echo JText::translate($cfield['name']); ?></label>
				<span>
					<select id="cfield<?php echo $cfield['id']; ?>"<?php echo !empty($phone_field_id) ? ' onchange="jQuery(\'#' . $phone_field_id . '\').trigger(\'vriupdatephonenumber\', jQuery(this).find(\'option:selected\').attr(\'data-c2code\'));"' : ''; ?>>
						<option value=""> </option>
					<?php
					foreach ($all_countries as $country) {
						?>
						<option value="<?php echo $country['country_name']; ?>" data-ccode="<?php echo $country['country_3_code']; ?>" data-c2code="<?php echo $country['country_2_code']; ?>"><?php echo $country['country_name']; ?></option>
						<?php
					}
					?>
					</select>
				</span>
			</div>
			<?php
		}
	}
	?>
		</div>
		<div class="vri-calendar-cfields-bottom">
			<button type="button" class="btn" onclick="hideCustomFields();"><?php echo JText::translate('VRANNULLA'); ?></button>
			<button type="button" class="btn btn-success" onclick="applyCustomFieldsContent();"><i class="icon-edit"></i> <?php echo JText::translate('VRAPPLY'); ?></button>
		</div>
	</div>
</div>

<form action="index.php?option=com_vikrentitems" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikrentitems" />
</form>

<script type="text/javascript">
<?php echo ($poverview_change > 0 ? 'window.parent.hasNewBooking = true;' . "\n" : ''); ?>
var vri_glob_sel_nights = 0;
var cfields_overlay = false;
var customers_search_vals = "";
var prev_tareat = null;
var booknowmade = false;

function vriCloseItem() {
	var ckbox = document.getElementById("setclosed") ? document.getElementById("setclosed") : document.getElementById("setclosed-on");
	if (ckbox && ckbox.checked == true) {
		if (jQuery("#vrspannumitems").length) {
			jQuery("#vrspannumitems").hide();
		}
		jQuery("#vrspanbdeliveraddr, #vrspanbdeliverdist, #vrspanblocations").hide();
		jQuery("#vrspanbstat").hide();
		jQuery("#vrspcustcost").hide();
		jQuery("#vrspancmail").hide();
		jQuery("#vrfillcustfields").hide();
		jQuery("#vrspanbpay").hide();
		jQuery("#vri-website-rates-row").hide();
		if (prev_tareat === null) {
			// save the previous customer information
			prev_tareat = jQuery('#vricustdatatxtarea').val();
		}
		jQuery("#vricustdatatxtarea").val("<?php echo addslashes(JText::translate('VRDBTEXTROOMCLOSED')); ?>");
		jQuery("#quickbsubmit").removeClass("btn-success").addClass("btn-danger").find("span").text("<?php echo addslashes(JText::translate('VRSUBMCLOSEROOM')); ?>");
	} else {
		if (jQuery("#vrspannumitems").length) {
			jQuery("#vrspannumitems").show();
		}
		jQuery("#vrspanbdeliveraddr, #vrspanbdeliverdist, #vrspanblocations").show();
		jQuery("#vrspanbstat").show();
		jQuery("#vrspcustcost").show();
		jQuery("#vrspancmail").show();
		jQuery("#vrfillcustfields").show();
		jQuery("#vrspanbpay").show();
		jQuery("#vricustdatatxtarea").val(prev_tareat + "");
		jQuery("#quickbsubmit").removeClass("btn-danger").addClass("btn-success").find("span").text("<?php echo addslashes(JText::translate('VRMAKERESERV')); ?>");
	}
}

function showCustomFields() {
	cfields_overlay = true;
	jQuery(".vri-calendar-cfields-filler-overlay, .vri-calendar-cfields-filler").fadeIn();
	setTimeout(function() {
		jQuery('#vri-searchcust').focus();
	}, 500);
}

function hideCustomFields() {
	cfields_overlay = false;
	jQuery(".vri-calendar-cfields-filler-overlay").fadeOut();
}

function applyCustomFieldsContent() {
	var cfields_cont = "";
	var cfields_labels = new Array;
	var nominatives = new Array;
	var tot_rows = 1;
	jQuery(".vri-calendar-cfields-inner .vri-calendar-cfield-entry").each(function(){
		var cfield_name = jQuery(this).find("label").text();
		var cfield_input = jQuery(this).find("span").find("input");
		var cfield_textarea = jQuery(this).find("span").find("textarea");
		var cfield_select = jQuery(this).find("span").find("select");
		var cfield_cont = "";
		if (cfield_input.length) {
			cfield_cont = cfield_input.val();
			if (cfield_input.attr("data-isemail") == "1" && cfield_cont.length) {
				jQuery("#custmailfield").val(cfield_cont);
			}
			if (cfield_input.attr("data-isphone") == "1") {
				jQuery("#phonefield").val(cfield_cont);
			}
			if (cfield_input.attr("data-isnominative") == "1") {
				nominatives.push(cfield_cont);
			}
		} else if (cfield_textarea.length) {
			cfield_cont = cfield_textarea.val();
		} else if (cfield_select.length) {
			cfield_cont = cfield_select.val();
			if (cfield_cont.length) {
				var country_code = jQuery("option:selected", cfield_select).attr("data-ccode");
				if (country_code.length) {
					jQuery("#ccode_inpfield").val(country_code);
				}
			}
		}
		if (cfield_cont.length) {
			cfields_cont += cfield_name+": "+cfield_cont+"\r\n";
			tot_rows++;
			cfields_labels.push(cfield_name+":");
		}
	});
	if (cfields_cont.length) {
		cfields_cont = cfields_cont.replace(/\r\n+$/, "");
	}
	if (nominatives.length > 1) {
		jQuery("#t_first_name_inpfield").val(nominatives[0]);
		jQuery("#t_last_name_inpfield").val(nominatives[1]);
	}
	jQuery("#vricustdatatxtarea").val(cfields_cont);
	jQuery("#vricustdatatxtarea").attr("rows", tot_rows);
	hideCustomFields();
}

function vriCalcNights() {
	vri_glob_sel_nights = 0;
	var vrritiro = document.getElementById("pickupdate").value;
	var vrconsegna = document.getElementById("releasedate").value;
	if (vrritiro.length > 0 && vrconsegna.length > 0) {
		var vrritirop = vrritiro.split("/");
		var vrconsegnap = vrconsegna.split("/");
		var vri_df = "<?php echo $vri_df; ?>";
		if (vri_df == "%d/%m/%Y") {
			var vrinmonth = parseInt(vrritirop[1]);
			vrinmonth = vrinmonth - 1;
			var vrinday = parseInt(vrritirop[0], 10);
			var vrritirod = new Date(vrritirop[2], vrinmonth, vrinday);
			var vrcutmonth = parseInt(vrconsegnap[1]);
			vrcutmonth = vrcutmonth - 1;
			var vrcutday = parseInt(vrconsegnap[0], 10);
			var vrconsegnad = new Date(vrconsegnap[2], vrcutmonth, vrcutday);
		} else if (vri_df == "%m/%d/%Y") {
			var vrinmonth = parseInt(vrritirop[0]);
			vrinmonth = vrinmonth - 1;
			var vrinday = parseInt(vrritirop[1], 10);
			var vrritirod = new Date(vrritirop[2], vrinmonth, vrinday);
			var vrcutmonth = parseInt(vrconsegnap[0]);
			vrcutmonth = vrcutmonth - 1;
			var vrcutday = parseInt(vrconsegnap[1], 10);
			var vrconsegnad = new Date(vrconsegnap[2], vrcutmonth, vrcutday);
		} else {
			var vrinmonth = parseInt(vrritirop[1]);
			vrinmonth = vrinmonth - 1;
			var vrinday = parseInt(vrritirop[2], 10);
			var vrritirod = new Date(vrritirop[0], vrinmonth, vrinday);
			var vrcutmonth = parseInt(vrconsegnap[1]);
			vrcutmonth = vrcutmonth - 1;
			var vrcutday = parseInt(vrconsegnap[2], 10);
			var vrconsegnad = new Date(vrconsegnap[0], vrcutmonth, vrcutday);
		}
		var vrdivider = 1000 * 60 * 60 * 24;
		var vrints = vrritirod.getTime();
		var vrcutts = vrconsegnad.getTime();
		if (vrcutts > vrints) {
			//var vrnights = Math.ceil((vrcutts - vrints) / (vrdivider));
			var utc1 = Date.UTC(vrritirod.getFullYear(), vrritirod.getMonth(), vrritirod.getDate());
			var utc2 = Date.UTC(vrconsegnad.getFullYear(), vrconsegnad.getMonth(), vrconsegnad.getDate());
			var vrnights = Math.ceil((utc2 - utc1) / vrdivider);
			if (vrnights > 0) {
				vri_glob_sel_nights = vrnights;
				document.getElementById("vrjstotnights").innerHTML = "<?php echo addslashes(JText::translate('VRDAYS')); ?>: "+vrnights;
				// update average cost per night
				vriCalcDailyCost(document.getElementById("cust_cost").value);
			} else {
				document.getElementById("vrjstotnights").innerHTML = "";
			}
		} else {
			document.getElementById("vrjstotnights").innerHTML = "";
		}
	} else {
		document.getElementById("vrjstotnights").innerHTML = "";
	}
}

function vriCalcDailyCost(cur_val) {
	// trigger calculation of website rates
	vriCalcWebsiteRates();
	//
	var avg_cost_str = "";
	if (cur_val.length && !isNaN(cur_val) && vri_glob_sel_nights > 0) {
		var avg_cost = (parseFloat(cur_val) / vri_glob_sel_nights).toFixed(<?php echo (int)$formatparts[0]; ?>);
		avg_cost_str = "<?php echo $currencysymb; ?> " + avg_cost + "/<?php echo addslashes(JText::translate('VRDAY')); ?>";
	}
	document.getElementById("avg-daycost").innerHTML = avg_cost_str;
}

function vriCalcWebsiteRates() {
	// unset previously selected rates, if any
	vriUnsetWebsiteRate();
	//
	var checkinfdate = jQuery("#pickupdate").val();
	var units = 1;
	if (!checkinfdate.length || vri_glob_sel_nights < 1 || jQuery("input[name=\"setclosed\"]").is(":checked")) {
		console.log('yes', checkinfdate.length, vri_glob_sel_nights, jQuery("input[name=\"setclosed\"]").is(":checked"));
		jQuery("#vri-website-rates-row").hide();
		return false;
	}
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "index.php",
		data: {
			option: "com_vikrentitems",
			task: "calc_rates",
			id_item: <?php echo $itemrows['id']; ?>,
			checkinfdate: checkinfdate,
			num_days: vri_glob_sel_nights,
			units: units,
			only_rates: 1,
			tmpl: "component"
		}
	}).done(function(resp) {
		var obj_res = null;
		try {
			obj_res = JSON.parse(resp);
		} catch(err) {
			console.error("could not parse JSON response", resp);
		}
		if (obj_res === null || !jQuery.isArray(obj_res)) {
			jQuery("#vri-website-rates-row").hide();
			console.info("invalid JSON response", resp);
			return false;
		}
		if (!obj_res[0].hasOwnProperty("idprice")) {
			jQuery("#vri-website-rates-row").hide();
			console.log("error in response");
			console.error(resp);
			return false;
		}
		// display the rates obtained
		var wrhtml = "";
		for (var i in obj_res) {
			if (!obj_res.hasOwnProperty(i)) {
				continue;
			}
			wrhtml += "<div class=\"vri-cal-wbrate-wrap\" onclick=\"vriSelWebsiteRate(this);\">";
			wrhtml += "<div class=\"vri-cal-wbrate-inner\">";
			wrhtml += "<span class=\"vri-cal-wbrate-name\" data-idprice=\"" + obj_res[i]["idprice"] + "\">" + obj_res[i]["name"] + "</span>";
			wrhtml += "<span class=\"vri-cal-wbrate-cost\" data-cost=\"" + obj_res[i]["tot"] + "\">" + obj_res[i]["ftot"] + "</span>";
			wrhtml += "</div>";
			wrhtml += "</div>";
		}
		jQuery("#vri-website-rates-cont").html(wrhtml);
		jQuery("#vri-website-rates-row").fadeIn();
		if (<?php echo $pidprice > 0 && $pbooknow > 0 ? 'true' : 'false'; ?> && !booknowmade) {
			// we get here by clicking the book-now button from the rates calculator only once
			booknowmade = true;
			// trigger the click for the requested rate plan ID
			jQuery('.vri-cal-wbrate-name[data-idprice="<?php echo $pidprice; ?>"]').closest('.vri-cal-wbrate-wrap').trigger('click');
		}
	}).fail(function(err) {
		jQuery("#vri-website-rates-row").hide();
		console.error("Error calculating the rates");
		console.error(err.responseText);
	});
}

function vriSelWebsiteRate(elem) {
	var rate = jQuery(elem);
	var idprice = rate.find('.vri-cal-wbrate-name').attr('data-idprice');
	var cost = rate.find('.vri-cal-wbrate-cost').attr('data-cost');
	var prev_idprice = jQuery('#booking-idprice').val();
	// reset all selected classes
	jQuery('.vri-cal-wbrate-wrap').removeClass('vri-cal-wbrate-wrap-selected');
	if (prev_idprice.length && prev_idprice == idprice) {
		// rate plan has been de-selected
		jQuery('#booking-idprice').val("");
		jQuery('#booking-itemcost').val("");
		jQuery('#cust_cost').attr('readonly', false);
	} else {
		// rate plan has been selected
		rate.addClass('vri-cal-wbrate-wrap-selected');
		jQuery('#booking-idprice').val(idprice);
		jQuery('#booking-itemcost').val(cost);
		jQuery('#cust_cost').attr('readonly', true);
	}
}

function vriUnsetWebsiteRate() {
	jQuery('#booking-idprice').val("");
	jQuery('#booking-itemcost').val("");
	jQuery('.vri-cal-wbrate-wrap').removeClass('vri-cal-wbrate-wrap-selected');
	jQuery('#cust_cost').attr('readonly', false);
}

jQuery(document).ready(function() {
	
	jQuery("#vri-calendar-changeitem").select2();
	jQuery("#pickuploc").select2({placeholder: '<?php echo addslashes(JText::translate('VRRITIROITEM')); ?>'});
	jQuery("#dropoffloc").select2({placeholder: '<?php echo addslashes(JText::translate('VRRETURNITEMORD')); ?>'});

	jQuery('td.free').click(function() {
		var indate = jQuery('#pickupdate').val();
		var outdate = jQuery('#releasedate').val();
		var clickdate = jQuery(this).attr('data-daydate');
		if (!(indate.length > 0)) {
			jQuery('#pickupdate').val(clickdate);
		} else if (!(outdate.length > 0) && clickdate != indate) {
			jQuery('#releasedate').val(clickdate);
		} else {
			jQuery('#pickupdate').val(clickdate);
			jQuery('#releasedate').val('');
		}
	});

	jQuery("#vrfillcustfields").click(function(){
		showCustomFields();
	});

	jQuery(document).mouseup(function(e) {
		if (!cfields_overlay) {
			return false;
		}
		var vrdialogcf_cont = jQuery(".vri-calendar-cfields-filler");
		if (!vrdialogcf_cont.is(e.target) && vrdialogcf_cont.has(e.target).length === 0) {
			hideCustomFields();
		}
	});
	
	//Search customer - Start
	var vricustsdelay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();

	function vriCustomerSearch(words) {
		jQuery("#vri-searchcust-res").hide().html("");
		jQuery("#vri-searchcust-loading").show();
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikrentitems", task: "searchcustomer", kw: words, tmpl: "component" }
		}).done(function(cont) {
			if (cont.length) {
				var obj_res = JSON.parse(cont);
				customers_search_vals = obj_res[0];
				jQuery("#vri-searchcust-res").html(obj_res[1]);
			} else {
				customers_search_vals = "";
				jQuery("#vri-searchcust-res").html("----");
			}
			jQuery("#vri-searchcust-res").show();
			jQuery("#vri-searchcust-loading").hide();
		}).fail(function() {
			jQuery("#vri-searchcust-loading").hide();
			alert("Error Searching.");
		});
	}

	jQuery("#vri-searchcust").keyup(function(event) {
		vricustsdelay(function() {
			var keywords = jQuery("#vri-searchcust").val();
			var chars = keywords.length;
			if (chars > 1) {
				if ((event.which > 96 && event.which < 123) || (event.which > 64 && event.which < 91) || event.which == 13) {
					vriCustomerSearch(keywords);
				}
			} else {
				if (jQuery("#vri-searchcust-res").is(":visible")) {
					jQuery("#vri-searchcust-res").hide();
				}
			}
		}, 600);
	});
	//Search customer - End

	//Datepickers - Start
	jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "" ] );
	jQuery("#pickupdate").datepicker({
		showOn: "focus",
		dateFormat: "<?php echo $juidf; ?>",
		numberOfMonths: 1,
		onSelect: function( selectedDate ) {
			var nowritiro = jQuery("#pickupdate").datepicker("getDate");
			var nowpickupdate = new Date(nowritiro.getTime());
			jQuery("#releasedate").datepicker( "option", "minDate", nowpickupdate );
			vriCalcNights();
		}
	});
	jQuery("#releasedate").datepicker({
		showOn: "focus",
		dateFormat: "<?php echo $juidf; ?>",
		numberOfMonths: 1,
		onSelect: function( selectedDate ) {
			vriCalcNights();
		}
	});
	jQuery(".vridatepicker-trig-icon").click(function(){
		var jdp = jQuery(this).prev("input.hasDatepicker");
		if (jdp.length) {
			jdp.focus();
		}
	});
	//Datepickers - End
	<?php echo (!empty($ppickup) ? 'jQuery("#pickupdate").datepicker("setDate", "'.$ppickup.'");'."\n" : ''); ?>
	<?php echo (!empty($pdropoff) ? 'jQuery("#releasedate").datepicker("setDate", "'.$pdropoff.'");'."\n" : ''); ?>
	<?php echo (!empty($ppickup) || !empty($pdropoff) ? 'jQuery(".ui-datepicker-current-day").click();'."\n" : ''); ?>
});

jQuery("body").on("click", ".vri-custsearchres-entry", function() {
	var custid = jQuery(this).attr("data-custid");
	var custemail = jQuery(this).attr("data-email");
	var custphone = jQuery(this).attr("data-phone");
	var custcountry = jQuery(this).attr("data-country");
	var custfirstname = jQuery(this).attr("data-firstname");
	var custlastname = jQuery(this).attr("data-lastname");
	jQuery("#customer_id_inpfield").val(custid);
	if (customers_search_vals.hasOwnProperty(custid)) {
		jQuery.each(customers_search_vals[custid], function(cfid, cfval) {
			var fill_field = jQuery("#cfield"+cfid);
			if (fill_field.length) {
				fill_field.val(cfval);
			}
		});
	} else {
		jQuery("input[data-isnominative=\"1\"]").each(function(k, v) {
			if (k == 0) {
				jQuery(this).val(custfirstname);
				return true;
			}
			if (k == 1) {
				jQuery(this).val(custlastname);
				return true;
			}
			return false;
		});
		jQuery("input[data-isemail=\"1\"]").val(custemail);
		jQuery("input[data-isphone=\"1\"]").val(custphone);
		//Populate main calendar form
		jQuery("#custmailfield").val(custemail);
		jQuery("#t_first_name_inpfield").val(custfirstname);
		jQuery("#t_last_name_inpfield").val(custlastname);
		//
	}
	applyCustomFieldsContent();
	if (custcountry.length) {
		jQuery("#ccode_inpfield").val(custcountry);
	}
	if (custphone.length) {
		jQuery("#phonefield").val(custphone);
	}
});
</script>
