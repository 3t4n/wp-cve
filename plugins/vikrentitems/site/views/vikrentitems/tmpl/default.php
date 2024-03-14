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
$vri_tn = VikRentItems::getTranslator();

if (VikRentItems::allowRent()) {
	$session = JFactory::getSession();
	$svriplace = $session->get('vriplace', '');
	$indvriplace = 0;
	$svrireturnplace = $session->get('vrireturnplace', '');
	$indvrireturnplace = 0;
	$timeslots = VikRentItems::loadGlobalTimeSlots($vri_tn);
	$calendartype = VikRentItems::calendarType();
	$restrictions = VikRentItems::loadRestrictions();
	$def_min_los = VikRentItems::setDropDatePlus();
	$document = JFactory::getDocument();
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
	//
	$ppickup = VikRequest::getInt('pickup', '', 'request');
	$preturn = VikRequest::getInt('return', '', 'request');
	$pitemid = VikRequest::getInt('Itemid', '', 'request');
	$pval = "";
	$rval = "";
	$vridateformat = VikRentItems::getDateFormat();
	$nowtf = VikRentItems::getTimeFormat();
	if ($vridateformat == "%d/%m/%Y") {
		$df = 'd/m/Y';
	} elseif ($vridateformat == "%m/%d/%Y") {
		$df = 'm/d/Y';
	} else {
		$df = 'Y/m/d';
	}
	if (!empty($ppickup)) {
		$dp = date($df, $ppickup);
		if (VikRentItems::dateIsValid($dp)) {
			$pval = $dp;
		}
	}
	if (!empty($preturn)) {
		$dr = date($df, $preturn);
		if (VikRentItems::dateIsValid($dr)) {
			$rval = $dr;
		}
	}
	$coordsplaces = array();
	/**
	 * @wponly - we use POST as form method
	 */
	$selform = "<div class=\"vridivsearch vri-main-search-form\"><form action=\"".JRoute::rewrite('index.php?option=com_vikrentitems'.(!empty($pitemid) ? '&Itemid='.$pitemid : ''))."\" method=\"post\" onsubmit=\"return vriValidateSearch();\"><div class=\"vricalform\">\n";
	$selform .= "<input type=\"hidden\" name=\"option\" value=\"com_vikrentitems\"/>\n";
	$selform .= "<input type=\"hidden\" name=\"task\" value=\"search\"/>\n";
	$diffopentime = false;
	$closingdays = array();
	$declclosingdays = '';
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

	$vrisessioncart = $session->get('vriCart', '');
	$vrisesspickup = $session->get('vripickupts', '');
	$vrisessdropoff = $session->get('vrireturnts', '');
	$vrisessdays = $session->get('vridays', '');
	$vrisesspickuploc = $session->get('vriplace', '');
	$vrisessdropoffloc = $session->get('vrireturnplace', '');

	if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
		$selform .= "<div class=\"vrisfentry vri-search-sessvals\"><label class=\"vripickdroplab\">" . JText::translate('VRPICKUPITEM') . "</label><span class=\"vridtsp\"><input type=\"hidden\" name=\"pickupdate\" value=\"".date($df, $vrisesspickup)."\"/>".date($df, $vrisesspickup)." " . JText::translate('VRALLE') . " <input type=\"hidden\" name=\"pickuph\" value=\"".date('H', $vrisesspickup)."\"/>".date('H', $vrisesspickup).":<input type=\"hidden\" name=\"pickupm\" value=\"".date('i', $vrisesspickup)."\"/>".date('i', $vrisesspickup)."</span></div>\n";
		$selform .= "<div class=\"vrisfentry vri-search-sessvals\"><label class=\"vripickdroplab\">" . JText::translate('VRRETURNITEM') . "</label><span class=\"vridtsp\"><input type=\"hidden\" name=\"releasedate\" value=\"".date($df, $vrisessdropoff)."\"/>".date($df, $vrisessdropoff)." " . JText::translate('VRALLE') . " <input type=\"hidden\" name=\"releaseh\" value=\"".date('H', $vrisessdropoff)."\"/>".date('H', $vrisessdropoff).":<input type=\"hidden\" name=\"releasem\" value=\"".date('i', $vrisessdropoff)."\"/>".date('i', $vrisessdropoff)."</span></div>";
		$selform .= "<div class=\"vrisearchemptycartdiv\"><a href=\"".JRoute::rewrite('index.php?option=com_vikrentitems&task=emptycart&search=1')."\" class=\"btn\">".JText::translate('VRIEMPTYCARTCHANGEDATES')."</a></div>\n";
	}

	if (VikRentItems::showPlacesFront()) {
		$q = "SELECT * FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$places = $dbo->loadAssocList();
			$vri_tn->translateContents($places, '#__vikrentitems_places');

			// check if any place has a different opening time (1.1)
			foreach ($places as $kpla => $pla) {
				if (!empty($pla['opentime'])) {
					$diffopentime = true;
				}
				// check if any place has closing days
				if (!empty($pla['closingdays'])) {
					$closingdays[$pla['id']] = $pla['closingdays'];
				}
				if (!empty($svriplace) && !empty($svrireturnplace)) {
					if ($pla['id'] == $svriplace) {
						$indvriplace = $kpla;
					}
					if ($pla['id'] == $svrireturnplace) {
						$indvrireturnplace = $kpla;
					}
				}
			}

			// VRI 1.7 - location override opening time on some weekdays
			$wopening_pick = array();
			if (isset($places[$indvriplace]) && !empty($places[$indvriplace]['wopening'])) {
				$wopening_pick = json_decode($places[$indvriplace]['wopening'], true);
				$wopening_pick = !is_array($wopening_pick) ? array() : $wopening_pick;
			}
			$wopening_drop = array();
			if (isset($places[$indvrireturnplace]) && !empty($places[$indvrireturnplace]['wopening'])) {
				$wopening_drop = json_decode($places[$indvrireturnplace]['wopening'], true);
				$wopening_drop = !is_array($wopening_drop) ? array() : $wopening_drop;
			}
			//

			// locations closing days (1.1)
			if (count($closingdays) > 0) {
				foreach ($closingdays as $idpla => $clostr) {
					$jsclosingdstr = VikRentItems::formatLocationClosingDays($clostr);
					if (count($jsclosingdstr) > 0) {
						$declclosingdays .= 'var loc'.$idpla.'closingdays = ['.implode(", ", $jsclosingdstr).'];'."\n";
					}
				}
			}
			$onchangeplaces = $diffopentime === true ? " onchange=\"javascript: vriSetLocOpenTime(this.value, 'pickup');\"" : "";
			$onchangeplacesdrop = $diffopentime === true ? " onchange=\"javascript: vriSetLocOpenTime(this.value, 'dropoff');\"" : "";
			if ($diffopentime === true) {
				$onchangedecl = '
var vri_location_change = false;
var vri_wopening_pick = '.json_encode($wopening_pick).';
var vri_wopening_drop = '.json_encode($wopening_drop).';
var vri_hopening_pick = null;
var vri_hopening_drop = null;
var vri_mopening_pick = null;
var vri_mopening_drop = null;
function vriSetLocOpenTime(loc, where) {
	if (where == "dropoff") {
		vri_location_change = true;
	}
	jQuery.ajax({
		type: "POST",
		url: "'.JRoute::rewrite('index.php?option=com_vikrentitems&task=ajaxlocopentime&tmpl=component', false).'",
		data: { idloc: loc, pickdrop: where }
	}).done(function(res) {
		var vriobj = JSON.parse(res);
		if (where == "pickup") {
			jQuery("#vricomselph").html(vriobj.hours);
			jQuery("#vricomselpm").html(vriobj.minutes);
			if (vriobj.hasOwnProperty("wopening")) {
				vri_wopening_pick = vriobj.wopening;
				vri_hopening_pick = vriobj.hours;
			}
		} else {
			jQuery("#vricomseldh").html(vriobj.hours);
			jQuery("#vricomseldm").html(vriobj.minutes);
			if (vriobj.hasOwnProperty("wopening")) {
				vri_wopening_drop = vriobj.wopening;
				vri_hopening_drop = vriobj.hours;
			}
		}
		if (where == "pickup" && vri_location_change === false) {
			jQuery("#returnplace").val(loc).trigger("change");
			vri_location_change = false;
		}
	});
}';
				$document->addScriptDeclaration($onchangedecl);
			}
			// end check if any place has a different opningtime (1.1)
			$selform .= "<div class=\"vrisfentry\"><label for=\"place\">" . JText::translate('VRPPLACE') . "</label><span class=\"vriplacesp\"><select name=\"place\" id=\"place\"".$onchangeplaces.">";
			foreach ($places as $pla) {
				$selform .= "<option value=\"" . $pla['id'] . "\" id=\"place".$pla['id']."\"".(!empty($svriplace) && $svriplace == $pla['id'] ? " selected=\"selected\"" : "").">" . $pla['name'] . "</option>\n";
				if (!empty($pla['lat']) && !empty($pla['lng'])) {
					$coordsplaces[] = $pla;
				}
			}
			$selform .= "</select></span></div>\n";
		}
	}
	
	if ($diffopentime === true && is_array($places) && strlen($places[$indvriplace]['opentime']) > 0) {
		$parts = explode("-", $places[$indvriplace]['opentime']);
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
		//change dates drop off location opening time (1.1)
		$iret = $i;
		$iminret = $imin;
		$jret = $j;
		if ($indvriplace != $indvrireturnplace) {
			if (strlen($places[$indvrireturnplace]['opentime']) > 0) {
				//different opening time for drop off location
				$parts = explode("-", $places[$indvrireturnplace]['opentime']);
				if (is_array($parts) && $parts[0] != $parts[1]) {
					$opent = VikRentItems::getHoursMinutes($parts[0]);
					$closet = VikRentItems::getHoursMinutes($parts[1]);
					$iret = $opent[0];
					$iminret = $opent[1];
					$jret = $closet[0];
				} else {
					$iret = 0;
					$iminret = 0;
					$jret = 23;
				}
			} else {
				//global opening time
				$timeopst = VikRentItems::getTimeOpenStore();
				if (is_array($timeopst) && $timeopst[0] != $timeopst[1]) {
					$opent = VikRentItems::getHoursMinutes($timeopst[0]);
					$closet = VikRentItems::getHoursMinutes($timeopst[1]);
					$iret = $opent[0];
					$iminret = $opent[1];
					$jret = $closet[0];
				} else {
					$iret = 0;
					$iminret = 0;
					$jret = 23;
				}
			}
		}
		//
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
		$iret = $i;
		$iminret = $imin;
		$jret = $j;
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
	$hoursret = "";
	while ($iret <= $jret) {
		//VRI 1.3
		$sayiret = $iret < 10 ? "0".$iret : $iret;
		if ($nowtf != 'H:i') {
			$ampm = $iret < 12 ? ' am' : ' pm';
			$ampmh = $iret > 12 ? ($iret - 12) : $iret;
			$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
		} else {
			$sayh = $iret;
		}
		$hoursret .= "<option value=\"" . $sayiret . "\">" . $sayh . "</option>\n";
		//
		$iret++;
	}
	$minutes = "";
	for ($i = 0; $i < 60; $i += 15) {
		if ($i < 10) {
			$i = "0" . $i;
		}
		$minutes .= "<option value=\"" . $i . "\"".((int)$i == $imin ? " selected=\"selected\"" : "").">" . $i . "</option>\n";
	}
	$minutesret = "";
	for ($iret = 0; $iret < 60; $iret += 15) {
		if ($iret < 10) {
			$iret = "0" . $iret;
		}
		$minutesret .= "<option value=\"" . $iret . "\"".((int)$iret == $iminret ? " selected=\"selected\"" : "").">" . $iret . "</option>\n";
	}

	// vikrentitems 1.2
	$forcedpickdroptimes = VikRentItems::getForcedPickDropTimes();
	if ($calendartype == "jqueryui") {
		if ($vridateformat == "%d/%m/%Y") {
			$juidf = 'dd/mm/yy';
		} elseif ($vridateformat == "%m/%d/%Y") {
			$juidf = 'mm/dd/yy';
		} else {
			$juidf = 'yy/mm/dd';
		}
		//lang for jQuery UI Calendar
		$ldecl = '
jQuery(function($){'."\n".'
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
							if(!empty($drestr['ctad'])) {
								$ctarestrictionsrange[$kr][0] = $ctfrom;
								$ctarestrictionsrange[$kr][1] = $ctto;
								$ctarestrictionsrange[$kr][2] = explode(',', $drestr['ctad']);
							}
							if(!empty($drestr['ctdd'])) {
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
	if(vriFullObject(vricombowdays)) {
		var vritosort = new Array();
		for(var vrii in vricombowdays) {
			if(vricombowdays.hasOwnProperty(vrii)) {
				var vriusedate = darrive;
				vritosort[vrii] = vriusedate.setDate(vriusedate.getDate() + (vricombowdays[vrii] - 1 - vriusedate.getDay() + 7) % 7 + 1);
			}
		}
		vritosort.sort(function(da, db) {
			return da > db ? 1 : -1;
		});
		for(var vrinext in vritosort) {
			if(vritosort.hasOwnProperty(vrinext)) {
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
	if(vriFullObject(vrirestrminlosrangejn)) {
		for (var rk in vrirestrminlosrangejn) {
			if(vrirestrminlosrangejn.hasOwnProperty(rk)) {
				var minldrangeinit = vriGetDateObject(vrirestrminlosrangejn[rk][0]);
				if(nowpickupdate >= minldrangeinit) {
					var minldrangeend = vriGetDateObject(vrirestrminlosrangejn[rk][1]);
					if(nowpickupdate <= minldrangeend) {
						minlos = parseInt(vrirestrminlosrangejn[rk][2]);
						if(vriFullObject(vrirestrmaxlosrangejn)) {
							if(rk in vrirestrmaxlosrangejn) {
								maxlosrange = parseInt(vrirestrmaxlosrangejn[rk]);
							}
						}
						if(rk in vrirestrwdaysrangejn && nowd in vrirestrwdaysrangejn[rk][5]) {
							vricombowdays = vrirestrwdaysrangejn[rk][5][nowd];
						}
					}
				}
			}
		}
	}
	var nowm = nowpickup.getMonth();
	if(vriFullObject(vrirestrmonthscombojn) && vrirestrmonthscombojn.hasOwnProperty(nowm)) {
		if(nowd in vrirestrmonthscombojn[nowm]) {
			vricombowdays = vrirestrmonthscombojn[nowm][nowd];
		}
	}
	if(jQuery.inArray((nowm + 1), vrirestrmonths) != -1) {
		minlos = parseInt(vrirestrminlos[nowm]);
	}
	nowpickupdate.setDate(nowpickupdate.getDate() + minlos);
	jQuery('#releasedate').datepicker( 'option', 'minDate', nowpickupdate );
	if(maxlosrange > 0) {
		var diffmaxminlos = maxlosrange - minlos;
		var maxdropoffdate = new Date(nowpickupdate.getTime());
		maxdropoffdate.setDate(maxdropoffdate.getDate() + diffmaxminlos);
		jQuery('#releasedate').datepicker( 'option', 'maxDate', maxdropoffdate );
		vriDropMaxDateSet = true;
		vriDropMaxDateSetNow = true;
	}
	if(nowm in vrirestrmaxlos) {
		var diffmaxminlos = parseInt(vrirestrmaxlos[nowm]) - minlos;
		var maxdropoffdate = new Date(nowpickupdate.getTime());
		maxdropoffdate.setDate(maxdropoffdate.getDate() + diffmaxminlos);
		jQuery('#releasedate').datepicker( 'option', 'maxDate', maxdropoffdate );
		vriDropMaxDateSet = true;
		vriDropMaxDateSetNow = true;
	}
	if(!vriFullObject(vricombowdays)) {
		jQuery('#releasedate').datepicker( 'setDate', nowpickupdate );
		if (!vriDropMaxDateSetNow && vriDropMaxDateSet === true) {
			// unset maxDate previously set
			jQuery('#releasedate').datepicker( 'option', 'maxDate', null );
		}
	} else {
		vriRefreshDropoff(nowpickup);
	}
}";
			
			if(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0) {
				$resdecl .= "
var vrirestrwdays = {".implode(", ", $wdaysrestrictions)."};
var vrirestrwdaystwo = {".implode(", ", $wdaystworestrictions)."};
function vriIsDayDisabled(date) {
	if(!vriValidateCta(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = pickupClosingDays(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject(vrirestrwdaysrangejn)) {
		for (var rk in vrirestrwdaysrangejn) {
			if(vrirestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrwdaysrangejn[rk][1]);
					if(date <= wdrangeend) {
						if(wd != vrirestrwdaysrangejn[rk][2]) {
							if(vrirestrwdaysrangejn[rk][4] == -1 || wd != vrirestrwdaysrangejn[rk][4]) {
								return [false];
							}
						}
					}
				}
			}
		}
	}
	if(vriFullObject(vrirestrwdays)) {
		if(jQuery.inArray((m+1), vrirestrmonthswdays) == -1) {
			return [true];
		}
		if(wd == vrirestrwdays[m]) {
			return [true];
		}
		if(vriFullObject(vrirestrwdaystwo)) {
			if(wd == vrirestrwdaystwo[m]) {
				return [true];
			}
		}
		return [false];
	}
	return [true];
}
function vriIsDayDisabledDropoff(date) {
	if(!vriValidateCtd(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = dropoffClosingDays(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject(vricombowdays)) {
		if(jQuery.inArray(wd, vricombowdays) != -1) {
			return [true];
		} else {
			return [false];
		}
	}
	if(vriFullObject(vrirestrwdaysrangejn)) {
		for (var rk in vrirestrwdaysrangejn) {
			if(vrirestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrwdaysrangejn[rk][1]);
					if(date <= wdrangeend) {
						if(wd != vrirestrwdaysrangejn[rk][2] && vrirestrwdaysrangejn[rk][3] == 1) {
							return [false];
						}
					}
				}
			}
		}
	}
	if(vriFullObject(vrirestrwdays)) {
		if(jQuery.inArray((m+1), vrirestrmonthswdays) == -1 || jQuery.inArray((m+1), vrirestrmultiplyminlos) != -1) {
			return [true];
		}
		if(wd == vrirestrwdays[m]) {
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
		//locations closing days (1.1)
		if (strlen($declclosingdays) > 0) {
			$declclosingdays .= '
function pickupClosingDays(date) {
	dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var arrlocclosd = jQuery("#place").val();
	var checklocarr = window["loc"+arrlocclosd+"closingdays"];
	if (jQuery.inArray(dmy, checklocarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::translate('VRILOCDAYCLOSED')).'"];
	}
}
function dropoffClosingDays(date) {
	dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var arrlocclosd = jQuery("#returnplace").val();
	var checklocarr = window["loc"+arrlocclosd+"closingdays"];
	if (jQuery.inArray(dmy, checklocarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::translate('VRILOCDAYCLOSED')).'"];
	}
}';
			$document->addScriptDeclaration($declclosingdays);
		}
		//
		//Minimum Num of Days of Rental (VRI 1.4)
		$dropdayplus = $def_min_los;
		$forcedropday = "jQuery('#releasedate').datepicker( 'option', 'minDate', selectedDate );";
		if (strlen($dropdayplus) > 0 && intval($dropdayplus) > 0) {
			$forcedropday = "
var vridate = jQuery(this).datepicker('getDate');
if (vridate) {
	vridate.setDate(vridate.getDate() + ".$dropdayplus.");
	jQuery('#releasedate').datepicker( 'option', 'minDate', vridate );
	jQuery('#releasedate').val(jQuery.datepicker.formatDate('".$juidf."', vridate));
}";
		}
		//
		$sdecl = "
function vriCheckClosingDatesIn(date) {
	if(!vriValidateCta(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = pickupClosingDays(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	return [true];
}
function vriCheckClosingDatesOut(date) {
	if(!vriValidateCtd(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = dropoffClosingDays(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	return [true];
}
function vriValidateCta(date) {
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject(vrirestrctarange)) {
		for (var rk in vrirestrctarange) {
			if(vrirestrctarange.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrctarange[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrctarange[rk][1]);
					if(date <= wdrangeend) {
						if(jQuery.inArray('-'+wd+'-', vrirestrctarange[rk][2]) >= 0) {
							return false;
						}
					}
				}
			}
		}
	}
	if(vriFullObject(vrirestrcta)) {
		if(vrirestrcta.hasOwnProperty(m) && jQuery.inArray('-'+wd+'-', vrirestrcta[m]) >= 0) {
			return false;
		}
	}
	return true;
}
function vriValidateCtd(date) {
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject(vrirestrctdrange)) {
		for (var rk in vrirestrctdrange) {
			if(vrirestrctdrange.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject(vrirestrctdrange[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject(vrirestrctdrange[rk][1]);
					if(date <= wdrangeend) {
						if(jQuery.inArray('-'+wd+'-', vrirestrctdrange[rk][2]) >= 0) {
							return false;
						}
					}
				}
			}
		}
	}
	if(vriFullObject(vrirestrctd)) {
		if(vrirestrctd.hasOwnProperty(m) && jQuery.inArray('-'+wd+'-', vrirestrctd[m]) >= 0) {
			return false;
		}
	}
	return true;
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
function vriInitElems() {
	if (typeof vri_wopening_pick === 'undefined') {
		return true;
	}
	vri_hopening_pick = jQuery('#vricomselph').find('select').clone();
	vri_hopening_drop = jQuery('#vricomseldh').find('select').clone();
}
jQuery(function(){
	vriInitElems();
	jQuery('#pickupdate').datepicker({
		showOn: 'focus',".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vriIsDayDisabled,\n" : "\nbeforeShowDay: vriCheckClosingDatesIn,\n")."
		onSelect: function( selectedDate ) {
			".($totrestrictions > 0 ? "vriSetMinDropoffDate();" : $forcedropday)."
			vriLocationWopening('pickup');
		}
	});
	jQuery('#pickupdate').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#pickupdate').datepicker( 'option', 'minDate', '".VikRentItems::getMinDaysAdvance()."d');
	jQuery('#pickupdate').datepicker( 'option', 'maxDate', '".VikRentItems::getMaxDateFuture()."');
	jQuery('#releasedate').datepicker({
		showOn: 'focus',".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vriIsDayDisabledDropoff,\n" : "\nbeforeShowDay: vriCheckClosingDatesOut,\n")."
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
				$selform .= "<div class=\"vrisfentrytimeslot\"><div class=\"vri-sf-timeslot-inner\"><label for=\"vri-timeslot\">".JText::translate('VRIFOR') . "</label>";
				$wseltimeslots = "<span><select name=\"timeslot\" id=\"vri-timeslot\">\n";
				foreach ($timeslots as $times) {
					$wseltimeslots .= "<option value=\"".$times['id']."\">".$times['tname']."</option>\n";
				}
				$wseltimeslots .= "</select></span></div></div>\n";
				$selform .= $wseltimeslots . "</div>\n";
			} else {
				$selpickh = is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 ? '<input type="hidden" name="pickuph" value="'.$forcedpickdroptimes[0][0].'"/><span class="vriforcetime">'.$forcedpickdroptimes[0][0].'</span>' : '<select name="pickuph" id="pickuph">' . $hours . '</select>';
				$selpickm = is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 ? '<input type="hidden" name="pickupm" value="'.$forcedpickdroptimes[0][1].'"/><span class="vriforcetime">'.$forcedpickdroptimes[0][1].'</span>' : '<select name="pickupm">' . $minutes . '</select>';
				$selform .= "<div class=\"vrisfentrytime\"><div class=\"vri-sf-entrytime-inner\"><label for=\"pickuph\">".JText::translate('VRALLE') . "</label><span id=\"vricomselph\">".$selpickh."</span><label class=\"vritimedots\">:</label><span id=\"vricomselpm\">".$selpickm."</span></div></div></div>\n";
				$seldroph = is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 ? '<input type="hidden" name="releaseh" value="'.$forcedpickdroptimes[1][0].'"/><span class="vriforcetime">'.$forcedpickdroptimes[1][0].'</span>' : '<select name="releaseh" id="releaseh">' . $hoursret . '</select>';
				$seldropm = is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 ? '<input type="hidden" name="releasem" value="'.$forcedpickdroptimes[1][1].'"/><span class="vriforcetime">'.$forcedpickdroptimes[1][1].'</span>' : '<select name="releasem">' . $minutesret . '</select>';
				$selform .= "<div class=\"vrisfentry\"><label class=\"vripickdroplab\" for=\"releasedate\">" . JText::translate('VRRETURNITEM') . "</label><div class=\"vri-sf-input-wrap vri-sf-input-dropoff\"><span><input type=\"text\" name=\"releasedate\" id=\"releasedate\" size=\"10\" autocomplete=\"off\" onfocus=\"this.blur();\" readonly/><i class=\"" . VikRentItemsIcons::i('calendar', 'vri-caltrigger') . "\"></i></span></div><div class=\"vrisfentrytime\"><div class=\"vri-sf-entrytime-inner\"><label for=\"releaseh\">" . JText::translate('VRALLE') . "</label><span id=\"vricomseldh\">".$seldroph."</span><label class=\"vritimedots\">:</label><span id=\"vricomseldm\">".$seldropm."</span></div></div></div>\n";
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
	if (@is_array($places)) {
		$selform .= "<div class=\"vrisfentry\"><label for=\"returnplace\">" . JText::translate('VRRETURNITEMORD') . "</label><span class=\"vriplacesp\"><select name=\"returnplace\" id=\"returnplace\"".(strlen($onchangeplacesdrop) > 0 ? $onchangeplacesdrop : "").">";
		foreach ($places as $pla) {
			$selform .= "<option value=\"" . $pla['id'] . "\" id=\"returnplace".$pla['id']."\"".(!empty($svrireturnplace) && $svrireturnplace == $pla['id'] ? " selected=\"selected\"" : "").">" . $pla['name'] . "</option>\n";
		}
		$selform .= "</select></span></div>\n";
	}
	if (VikRentItems::showCategoriesFront()) {
		$q = "SELECT * FROM `#__vikrentitems_categories` ORDER BY `#__vikrentitems_categories`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$categories = $dbo->loadAssocList();
			$vri_tn->translateContents($categories, '#__vikrentitems_categories');
			$selform .= "<div class=\"vrisfentry\"><label for=\"vri-categories\">" . JText::translate('VRIARCAT') . "</label><span class=\"vriplacesp\"><select name=\"categories\" id=\"vri-categories\">";
			$selform .= "<option value=\"all\">" . JText::translate('VRALLCAT') . "</option>\n";
			foreach ($categories as $cat) {
				$selform .= "<option value=\"" . $cat['id'] . "\">" . $cat['name'] . "</option>\n";
			}
			$selform .= "</select></span></div>\n";
		}
	}
	$selform .= "<div class=\"vrisfentrysubmit\"><input type=\"submit\" name=\"search\" value=\"" . JText::translate('VRISEARCHBUTTON') . "\" class=\"btn vri-search-btn\"/></div>\n";
	$selform .= "</div>\n";
	//locations on google map
	if (count($coordsplaces) > 0) {
		$selform .= '<div class="vrilocationsbox"><div class="vrilocationsmapdiv"><a href="'.JRoute::rewrite('index.php?option=com_vikrentitems&view=locationsmap&tmpl=component').'" class="vrimodal" target="_blank"><i class="' . VikRentItemsIcons::i('map-marked') . '"></i><span>'.JText::translate('VRILOCATIONSMAP').'</span></a></div></div>';
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery(".vrimodal").fancybox({
				type: "iframe",
				iframe: {
					css: {
						width: "75%",
						height: "75%"
					}
				}
			});
		});
		</script>
		<?php
	}
	//
	$selform .= (!empty($pitemid) ? "<input type=\"hidden\" name=\"Itemid\" value=\"" . $pitemid . "\"/>" : "") . "</form></div>";
	echo VikRentItems::getFullFrontTitle($vri_tn);
	echo VikRentItems::getIntroMain($vri_tn);
	
	echo $selform;
	
	if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
		?>
		<div class="vrisearchgosummarydiv">
			<a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=oconfirm&place='.$vrisesspickuploc.'&returnplace='.$vrisessdropoffloc.'&days='.$vrisessdays.'&pickup='.$vrisesspickup.'&release='.$vrisessdropoff); ?>" class="btn"><?php echo JText::translate('VRIGOTOSUMMARY'); ?></a>
		</div>
		<?php
	}
	
	echo '<div class="vri-search-closingtext">'.VikRentItems::getClosingMain($vri_tn).'</div>';

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
	</script>
	<?php
	//

	//echo javascript to fill the date values
	if (!empty($pval) && !empty($rval)) {
		if ($calendartype == "jqueryui") {
			?>
			<script type="text/javascript">
			jQuery(function(){
				jQuery('#pickupdate').val('<?php echo $pval; ?>');
				jQuery('#releasedate').val('<?php echo $rval; ?>');
			});
			</script>
			<?php
		} else {
			?>
			<script type="text/javascript">
			document.getElementById('pickupdate').value='<?php echo $pval; ?>';
			document.getElementById('releasedate').value='<?php echo $rval; ?>';
			</script>
			<?php
		}
	}
	//
} else {
	echo VikRentItems::getDisabledRentMsg($vri_tn);
}
