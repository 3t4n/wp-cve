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

JHtml::fetch('jquery.framework');
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-ui.sortable.min.js');

JHtml::fetch('behavior.calendar');

$vri_app = VikRentItems::getVriApplication();
$timeopst = VikRentItems::getTimeOpenStore(true);

$openat   = array(0, 0);
$closeat  = array(0, 0);
$alwopen  = true;
if (is_array($timeopst) && $timeopst[0] != $timeopst[1]) {
	$openat  = VikRentItems::getHoursMinutes($timeopst[0]);
	$closeat = VikRentItems::getHoursMinutes($timeopst[1]);
	$alwopen = false;
}
$calendartype = VikRentItems::calendarType(true);
$aehourschbasp = VikRentItems::applyExtraHoursChargesBasp();
$nowdf = VikRentItems::getDateFormat(true);
$nowtf = VikRentItems::getTimeFormat(true);
$forcedpickdroptimes = VikRentItems::getForcedPickDropTimes(true);
$forcepickupthsel = "<select name=\"forcepickupth\" style=\"float: none;\">\n";
for($i=0; $i <= 23; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$forcepickupthsel.="<option value=\"".$i."\"".(is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 && intval($forcedpickdroptimes[0][0]) == $i ? ' selected="selected"' : '').">".$in."</option>\n";
}
$forcepickupthsel .= "</select>\n";
$forcepickuptmsel = "<select name=\"forcepickuptm\" style=\"float: none;\">\n";
for($i=0; $i <= 59; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$forcepickuptmsel.="<option value=\"".$i."\"".(is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 && intval($forcedpickdroptimes[0][1]) == $i ? ' selected="selected"' : '').">".$in."</option>\n";
}
$forcepickuptmsel .= "</select>\n";
$forcedropoffthsel = "<select name=\"forcedropoffth\" style=\"float: none;\">\n";
for($i=0; $i <= 23; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$forcedropoffthsel.="<option value=\"".$i."\"".(is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 && intval($forcedpickdroptimes[1][0]) == $i ? ' selected="selected"' : '').">".$in."</option>\n";
}
$forcedropoffthsel .= "</select>\n";
$forcedropofftmsel = "<select name=\"forcedropofftm\" style=\"float: none;\">\n";
for($i=0; $i <= 59; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$forcedropofftmsel.="<option value=\"".$i."\"".(is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 && intval($forcedpickdroptimes[1][1]) == $i ? ' selected="selected"' : '').">".$in."</option>\n";
}
$forcedropofftmsel .= "</select>\n";
$globclosingdays = VikRentItems::getGlobalClosingDays();
$currentglobclosedays = '';
if (is_array($globclosingdays)) {
	if (count($globclosingdays['singleday']) > 0) {
		foreach ($globclosingdays['singleday'] as $kgcs => $gcdsd) {
			$currentglobclosedays .= '<div id="curglobcsday'.$kgcs.'"><span class="vriconfspanclosed">'.date('Y-m-d', $gcdsd).' ('.JText::translate('VRICONFIGCLOSESINGLED').')</span><input type="hidden" name="globalclosingdays[]" value="'.date('Y-m-d', $gcdsd).':1"/><i class="' . VikRentItemsIcons::i('times-circle', 'vri-confrm-icn-small') . '" onclick="removeClosingDay(\'curglobcsday'.$kgcs.'\');"></i></div>'."\n";
		}
	}
	if (count($globclosingdays['weekly']) > 0) {
		$weekdaysarr = array(0 => JText::translate('VRISUNDAY'), 1 => JText::translate('VRIMONDAY'), 2 => JText::translate('VRITUESDAY'), 3 => JText::translate('VRIWEDNESDAY'), 4 => JText::translate('VRITHURSDAY'), 5 => JText::translate('VRIFRIDAY'), 6 => JText::translate('VRISATURDAY'));
		foreach ($globclosingdays['weekly'] as $kgcw => $gcdwd) {
			$moregcdinfo = getdate($gcdwd);
			$currentglobclosedays .= '<div id="curglobcwday'.$kgcw.'"><span class="vriconfspanclosed">'.date('Y-m-d', $gcdwd).' ('.$weekdaysarr[$moregcdinfo['wday']].')</span><input type="hidden" name="globalclosingdays[]" value="'.date('Y-m-d', $gcdwd).':2"/><i class="' . VikRentItemsIcons::i('times-circle', 'vri-confrm-icn-small') . '" onclick="removeClosingDay(\'curglobcwday'.$kgcw.'\');"></i></div>'."\n";
		}
	}
}

$maxdatefuture = VikRentItems::getMaxDateFuture(true);
$maxdate_val = intval(substr($maxdatefuture, 1, (strlen($maxdatefuture) - 1)));
$maxdate_interval = substr($maxdatefuture, -1, 1);

$vrisef = file_exists(VRI_SITE_PATH.DS.'router.php');
?>

<script type="text/javascript">
var _DAYS = new Array();
_DAYS.push('<?php echo addslashes(JText::translate('VRISUNDAY')); ?>');
_DAYS.push('<?php echo addslashes(JText::translate('VRIMONDAY')); ?>');
_DAYS.push('<?php echo addslashes(JText::translate('VRITUESDAY')); ?>');
_DAYS.push('<?php echo addslashes(JText::translate('VRIWEDNESDAY')); ?>');
_DAYS.push('<?php echo addslashes(JText::translate('VRITHURSDAY')); ?>');
_DAYS.push('<?php echo addslashes(JText::translate('VRIFRIDAY')); ?>');
_DAYS.push('<?php echo addslashes(JText::translate('VRISATURDAY')); ?>');

var daysindxcount = 0;

function addClosingDay() {
	var dayadd = document.getElementById('globdayclose').value;
	var frequency = document.getElementById('vrifrequencyclose').value;
	var freqexpl = '';
	if ( dayadd.length > 0 ) {
		if ( parseInt(frequency) == 1 ) {
			freqexpl = '<?php echo addslashes(JText::translate('VRICONFIGCLOSESINGLED')); ?>';
		} else {
			var dateparts = dayadd.split("-");
			var anlzdate = new Date( dateparts[0], (dateparts[1] - 1), dateparts[2] );
			freqexpl = _DAYS[anlzdate.getDay()];
		}
		addHiddenClosingDay(dayadd, frequency, freqexpl);
	}
}

function addHiddenClosingDay(cday, cfreq, cfreqexpl) {
	var ni = document.getElementById('vriglobclosedaysdiv');
	var num = (daysindxcount -1)+ 2;
	daysindxcount = num;
	var newdiv = document.createElement('div');
	var divIdName = 'cday'+num+'Div';
	newdiv.setAttribute('id',divIdName);
	newdiv.innerHTML = '<span class=\'vriconfspanclosed\'>'+cday+' ('+cfreqexpl+')</span><input type=\'hidden\' name=\'globalclosingdays[]\' value=\''+cday+':'+cfreq+'\'/><i class=\'<?php echo VikRentItemsIcons::i('times-circle', 'vri-confrm-icn-small'); ?>\' onclick=\'removeClosingDay("'+divIdName+'");\'></i>';
	ni.appendChild(newdiv);
}

function removeClosingDay(idtorm) {
	return (elem=document.getElementById(idtorm)).parentNode.removeChild(elem);
}

function toggleForcePickup() {
	if (jQuery('input[name="forcepickupt"]').is(':checked')) {
		jQuery('#forcepickuptdiv').show();
	} else {
		jQuery('#forcepickuptdiv').hide();
	}
}

function toggleForceDropoff() {
	if (jQuery('input[name="forcedropofft"]').is(':checked')) {
		jQuery('#forcedropofftdiv').show();
	} else {
		jQuery('#forcedropofftdiv').hide();
	}
}
</script>

<div class="vri-config-maintab-left">
	<fieldset class="adminform">
		<div class="vri-params-wrap">
			<legend class="adminlegend"><?php echo JText::translate('VRICONFIGBOOKINGPART'); ?></legend>
			<div class="vri-params-container">
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONEFIVE'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('allowrent', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::allowRent(), 1, 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONESIX'); ?></div>
					<div class="vri-param-setting"><textarea name="disabledrentmsg" rows="5" cols="50"><?php echo VikRentItems::getDisabledRentMsg(); ?></textarea></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONETENSIX'); ?></div>
					<div class="vri-param-setting"><input type="text" name="adminemail" value="<?php echo VikRentItems::getAdminMail(); ?>" size="30"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRISENDEREMAIL'); ?></div>
					<div class="vri-param-setting"><input type="text" name="senderemail" value="<?php echo VikRentItems::getSenderMail(); ?>" size="30"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONESEVEN'); ?></div>
					<div class="vri-param-setting">&nbsp;</div>
				</div>
				<div class="vri-param-container vri-param-nested">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONEONE'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('timeopenstorealw', JText::translate('VRYES'), JText::translate('VRNO'), ($alwopen ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container vri-param-nested">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONETWO'); ?></div>
					<div class="vri-param-setting">
						<div style="display: block; margin-bottom: 3px;">
							<span class="vrirestrdrangesp"><?php echo JText::translate('VRIONFIGONETHREE'); ?></span>
							<select name="timeopenstorefh">
							<?php
							for ($i = 0; $i <= 23; $i++) {
								$in = $i < 10 ? ("0" . $i) : $i;
								?>
								<option value="<?php echo $i; ?>"<?php echo $openat[0] == $i ? ' selected="selected"' : ''; ?>><?php echo $in; ?></option>
								<?php
							}
							?>
							</select>
							&nbsp;
							<select name="timeopenstorefm">
							<?php
							for ($i = 0; $i <= 59; $i++) {
								$in = $i < 10 ? ("0" . $i) : $i;
								?>
								<option value="<?php echo $i; ?>"<?php echo $openat[1] == $i ? ' selected="selected"' : ''; ?>><?php echo $in; ?></option>
								<?php
							}
							?>
							</select>
						</div>
						<div style="display: block; margin-bottom: 3px;">
							<span class="vrirestrdrangesp"><?php echo JText::translate('VRIONFIGONEFOUR'); ?></span>
							<select name="timeopenstoreth">
							<?php
							for ($i = 0; $i <= 23; $i++) {
								$in = $i < 10 ? ("0" . $i) : $i;
								?>
								<option value="<?php echo $i; ?>"<?php echo $closeat[0] == $i ? ' selected="selected"' : ''; ?>><?php echo $in; ?></option>
								<?php
							}
							?>
							</select>
							&nbsp;
							<select name="timeopenstoretm">
							<?php
							for ($i = 0; $i <= 59; $i++) {
								$in = $i < 10 ? ("0" . $i) : $i;
								?>
								<option value="<?php echo $i; ?>"<?php echo $closeat[1] == $i ? ' selected="selected"' : ''; ?>><?php echo $in; ?></option>
								<?php
							}
							?>
							</select>
						</div>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGFORCEPICKUP'); ?></div>
					<div class="vri-param-setting">
						<?php echo $vri_app->printYesNoButtons('forcepickupt', JText::translate('VRYES'), JText::translate('VRNO'), (is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) ? 1 : 0), 1, 0, 'toggleForcePickup();'); ?>
						<div id="forcepickuptdiv" style="display: <?php echo (is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 ? 'block' : 'none'); ?>;">
							<?php echo $forcepickupthsel.' : '.$forcepickuptmsel; ?>
						</div>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGFORCEDROPOFF'); ?></div>
					<div class="vri-param-setting">
						<?php echo $vri_app->printYesNoButtons('forcedropofft', JText::translate('VRYES'), JText::translate('VRNO'), (is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) ? 1 : 0), 1, 0, 'toggleForceDropoff();'); ?>
						<div id="forcedropofftdiv" style="display: <?php echo (is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 ? 'block' : 'none'); ?>;">
							<?php echo $forcedropoffthsel.' : '.$forcedropofftmsel; ?>
						</div>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONEELEVEN'); ?></div>
					<div class="vri-param-setting">
						<select name="dateformat">
							<option value="%d/%m/%Y"<?php echo ($nowdf == "%d/%m/%Y" ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIONFIGONETWELVE'); ?></option>
							<option value="%Y/%m/%d"<?php echo ($nowdf == "%Y/%m/%d" ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIONFIGONETENTHREE'); ?></option>
							<option value="%m/%d/%Y"<?php echo ($nowdf == "%m/%d/%Y" ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIONFIGUSDATEFORMAT'); ?></option>
						</select>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRICONFIGTIMEFORMAT'); ?></div>
					<div class="vri-param-setting">
						<select name="timeformat">
							<option value="h:i A"<?php echo ($nowtf=="h:i A" ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRICONFIGTIMEFUSA'); ?></option>
							<option value="H:i"<?php echo ($nowtf=="H:i" ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRICONFIGTIMEFEUR'); ?></option>
							<option value=""<?php echo (empty($nowtf) ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRICONFIGTIMEFNONE'); ?></option>
						</select>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONEEIGHT'); ?></div>
					<div class="vri-param-setting"><input type="number" step="any" name="hoursmorerentback" value="<?php echo VikRentItems::getHoursMoreRb(); ?>" min="0"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGEHOURSBASP'); ?></div>
					<div class="vri-param-setting">
						<select name="ehourschbasp">
							<option value="1"<?php echo ($aehourschbasp == true ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIONFIGEHOURSBEFORESP'); ?></option>
							<option value="0"<?php echo ($aehourschbasp == false ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIONFIGEHOURSAFTERSP'); ?></option>
						</select>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONENINE'); ?></div>
					<div class="vri-param-setting"><input type="number" name="hoursmoreitemavail" value="<?php echo VikRentItems::getHoursItemAvail(); ?>" min="0"/> <?php echo JText::translate('VRIONFIGONETENEIGHT'); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIPICKONDROP'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRIPICKONDROP'), 'content' => JText::translate('VRIPICKONDROPHELP'))); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('pickondrop', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::allowPickOnDrop(true), 1, 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRITODAYBOOKINGS'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('todaybookings', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::todayBookings(), 1, 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONECOUPONS'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('enablecoupons', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::couponsEnabled(), 1, 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGENABLECUSTOMERPIN'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('enablepin', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::customersPinEnabled(), 1, 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONETENFIVE'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('tokenform', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::tokenForm() ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGREQUIRELOGIN'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('requirelogin', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::requireLogin(), 1, 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIICALKEY'); ?></div>
					<div class="vri-param-setting"><input type="text" name="icalkey" value="<?php echo VikRentItems::getIcalSecretKey(); ?>" size="10"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONETENSEVEN'); ?></div>
					<div class="vri-param-setting"><input type="number" name="minuteslock" value="<?php echo VikRentItems::getMinutesLock(); ?>" min="0"/></div>
				</div>
			</div>
		</div>
	</fieldset>
</div>

<div class="vri-config-maintab-right">
	<fieldset class="adminform">
		<div class="vri-params-wrap">
			<legend class="adminlegend"><?php echo JText::translate('VRICONFIGSEARCHPART'); ?></legend>
			<div class="vri-params-container">
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGONEDROPDPLUS'); ?></div>
					<div class="vri-param-setting"><input type="number" name="setdropdplus" value="<?php echo VikRentItems::setDropDatePlus(true); ?>" min="0"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGMINDAYSADVANCE'); ?></div>
					<div class="vri-param-setting"><input type="number" name="mindaysadvance" value="<?php echo VikRentItems::getMinDaysAdvance(true); ?>" min="0"/></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRCONFIGMAXDATEFUTURE'); ?></div>
					<div class="vri-param-setting"><input type="number" name="maxdate" value="<?php echo $maxdate_val; ?>" min="0" style="float: none; vertical-align: top; max-width: 50px;"/> <select name="maxdateinterval" style="float: none; margin-bottom: 0;"><option value="d"<?php echo $maxdate_interval == 'd' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRCONFIGMAXDATEDAYS'); ?></option><option value="w"<?php echo $maxdate_interval == 'w' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRCONFIGMAXDATEWEEKS'); ?></option><option value="m"<?php echo $maxdate_interval == 'm' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRCONFIGMAXDATEMONTHS'); ?></option><option value="y"<?php echo $maxdate_interval == 'y' ? ' selected="selected"' : ''; ?>><?php echo JText::translate('VRCONFIGMAXDATEYEARS'); ?></option></select></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRICONFIGGLOBCLOSEDAYS'); ?></div>
					<div class="vri-param-setting"><?php echo JHtml::fetch('calendar', '', 'globdayclose', 'globdayclose', '%Y-%m-%d', array('class'=>'', 'size'=>'8',  'maxlength'=>'8', 'todayBtn' => 'true')); ?> <select style="float: none;" id="vrifrequencyclose"><option value="1"><?php echo JText::translate('VRICONFIGCLOSESINGLED'); ?></option><option value="2"><?php echo JText::translate('VRICONFIGCLOSEWEEKLY'); ?></option></select> <button type="button" class="btn vri-config-btn" onclick="addClosingDay();" style="margin-bottom: 9px;"><?php echo JText::translate('VRICONFIGADDCLOSEDAY'); ?></button><div id="vriglobclosedaysdiv" style="display: block;"><?php echo $currentglobclosedays; ?></div></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONETEN'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('placesfront', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::showPlacesFront(true) ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONETENFOUR'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('showcategories', JText::translate('VRYES'), JText::translate('VRNO'), (VikRentItems::showCategoriesFront(true) ? 'yes' : 0), 'yes', 0); ?></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label">
						<?php echo JText::translate('VRIPREFCOUNTRIESORD'); ?> 
						<?php echo $vri_app->createPopover(array('title' => JText::translate('VRIPREFCOUNTRIESORD'), 'content' => JText::translate('VRIPREFCOUNTRIESORDHELP'))); ?>
						<div class="vri-preferred-countries-edit-wrap">
							<span onclick="vriDisplayCustomPrefCountries();"><?php VikRentItemsIcons::e('edit'); ?></span>
						</div>
					</div>
					<div class="vri-param-setting">
						<ul class="vri-preferred-countries-sortlist">
						<?php
						$preferred_countries = VikRentItems::preferredCountriesOrdering(true);
						foreach ($preferred_countries as $ccode => $langname) {
							?>
							<li class="vri-preferred-countries-elem">
								<span><?php VikRentItemsIcons::e('ellipsis-v'); ?> <?php echo $langname; ?></span>
								<input type="hidden" name="pref_countries[]" value="<?php echo $ccode; ?>" />
							</li>
							<?php
						}
						?>
						</ul>
						<script type="text/javascript">
						function vriDisplayCustomPrefCountries() {
							var all_countries = new Array;
							jQuery('input[name="pref_countries[]"]').each(function() {
								all_countries.push(jQuery(this).val());
							});
							var current_countries = all_countries.join(', ');
							var custom_countries = prompt("<?php echo addslashes(JText::translate('VRIPREFCOUNTRIESORD')); ?>", current_countries);
							if (custom_countries != null && custom_countries != current_countries) {
								jQuery('.vri-preferred-countries-edit-wrap').append('<input type="hidden" name="cust_pref_countries" value="' + custom_countries + '"/>');
								jQuery('#adminForm').find('input[name="task"]').val('saveconfig');
								jQuery('#adminForm').submit();
							}
						}
						jQuery(document).ready(function() {
							jQuery('.vri-preferred-countries-sortlist').sortable();
							jQuery('.vri-preferred-countries-sortlist').disableSelection();
						});
						</script>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset class="adminform">
		<div class="vri-params-wrap">
			<legend class="adminlegend"><?php echo JText::translate('VRICONFIGSYSTEMPART'); ?></legend>
			<div class="vri-params-container">
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRICONFIGCRONKEY'); ?></div>
					<div class="vri-param-setting"><input type="text" name="cronkey" value="<?php echo VikRentItems::getCronKey(); ?>" size="6" /></div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRICONFENMULTILANG'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('multilang', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::allowMultiLanguage(), 1, 0); ?></div>
				</div>
				<!-- @wponly  we cannot display the setting for the SEF Router -->
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRILOADFA'); ?></div>
					<div class="vri-param-setting"><?php echo $vri_app->printYesNoButtons('usefa', JText::translate('VRYES'), JText::translate('VRNO'), (int)VikRentItems::isFontAwesomeEnabled(true), 1, 0); ?></div>
				</div>
				<!-- @wponly  jQuery main library should not be loaded as it's already included by WP -->
				<div class="vri-param-container">
					<div class="vri-param-label"><?php echo JText::translate('VRIONFIGONECALENDAR'); ?></div>
					<div class="vri-param-setting">
						<select name="calendar">
							<option value="jqueryui"<?php echo ($calendartype == "jqueryui" ? " selected=\"selected\"" : ""); ?>>jQuery UI</option>
						</select>
					</div>
				</div>
				<div class="vri-param-container">
					<div class="vri-param-label">Google Maps API Key</div>
					<div class="vri-param-setting"><input type="text" name="gmapskey" value="<?php echo VikRentItems::getGoogleMapsKey(); ?>" size="30" /></div>
				</div>
			</div>
		</div>
	</fieldset>
</div>
