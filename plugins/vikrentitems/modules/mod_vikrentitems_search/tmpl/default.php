<?php
/**
 * @package     VikRentItems
 * @subpackage  mod_vikrentitems_search
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$dbo = JFactory::getDbo();
$session = JFactory::getSession();
$input = JFactory::getApplication()->input;
$document = JFactory::getDocument();
$vri_tn = ModVikrentitemsSearchHelper::getTranslator();
$svriplace = $session->get('vriplace', '');
$indvriplace = 0;
$svrireturnplace = $session->get('vrireturnplace', '');
$indvrireturnplace = 0;
$dateformat = ModVikrentitemsSearchHelper::getDateFormat();
$nowtf = ModVikrentitemsSearchHelper::getTimeFormat();

/**
 * @wponly 	the AJAX requests below require the Itemid for JRoute
 */

$restrictions = VikRentItems::loadRestrictions();
$def_min_los = VikRentItems::setDropDatePlus();

$diffopentime = false;
$closingdays = array();
$declclosingdays = '';
$declglobclosingdays = '';
$globalclosingdays = ModVikrentitemsSearchHelper::getGlobalClosingDays();
if (is_array($globalclosingdays)) {
	if (count($globalclosingdays['singleday']) > 0) {
		$gscdarr = array();
		foreach($globalclosingdays['singleday'] as $kgcs => $gcdsd) {
			$gscdarr[] = '"'.date('Y-n-j', $gcdsd).'"';
		}
		$gscdarr = array_unique($gscdarr);
		$declglobclosingdays .= 'var vrimodglobclosingsdays = ['.implode(", ", $gscdarr).'];'."\n";
	} else {
		$declglobclosingdays .= 'var vrimodglobclosingsdays = ["-1"];'."\n";
	}
	if (count($globalclosingdays['weekly']) > 0) {
		$gwcdarr = array();
		foreach($globalclosingdays['weekly'] as $kgcw => $gcdwd) {
			$moregcdinfo = getdate($gcdwd);
			$gwcdarr[] = '"'.$moregcdinfo['wday'].'"';
		}
		$gwcdarr = array_unique($gwcdarr);
		$declglobclosingdays .= 'var vrimodglobclosingwdays = ['.implode(", ", $gwcdarr).'];'."\n";
	} else {
		$declglobclosingdays .= 'var vrimodglobclosingwdays = ["-1"];'."\n";
	}
	$declglobclosingdays .= '
function vrimodGlobalClosingDays(date) {
	var gdmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var gwd = date.getDay();
	gwd = gwd.toString();
	var checksdarr = window["vrimodglobclosingsdays"];
	var checkwdarr = window["vrimodglobclosingwdays"];
	if (jQuery.inArray(gdmy, checksdarr) == -1 && jQuery.inArray(gwd, checkwdarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::translate('VRIMGLOBDAYCLOSED')).'"];
	}
}';
	$document->addScriptDeclaration($declglobclosingdays);
}

// locations
$vrloc = "";
if (intval($params->get('showloc')) == 0) {
	$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='placesfront';";
	$dbo->setQuery($q);
	$dbo->execute();
	if ($dbo->getNumRows() == 1) {
		$sl = $dbo->loadAssocList();
		if (intval($sl[0]['setting']) == 1) {
			$q = "SELECT * FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$places = $dbo->loadAssocList();
				$vri_tn->translateContents($places, '#__vikrentitems_places');
				//check if some place has a different opening time (1.6)
				foreach ($places as $kpla => $pla) {
					if (!empty($pla['opentime'])) {
						$diffopentime = true;
					}
					//check if some place has closing days
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
				//locations closing days (1.7)
				if (count($closingdays) > 0) {
					foreach($closingdays as $idpla => $clostr) {
						$jsclosingdstr = ModVikrentitemsSearchHelper::formatLocationClosingDays($clostr);
						if (count($jsclosingdstr) > 0) {
							$declclosingdays .= 'var modloc'.$idpla.'closingdays = ['.implode(", ", $jsclosingdstr).'];'."\n";
						}
					}
				}
				$onchangeplaces = $diffopentime == true ? " onchange=\"javascript: vriSetLocOpenTimeModule(this.value, 'pickup');\"" : "";
				$onchangeplacesdrop = $diffopentime == true ? " onchange=\"javascript: vriSetLocOpenTimeModule(this.value, 'dropoff');\"" : "";
				if ($diffopentime == true) {
					$onchangedecl = '
var vrimod_location_change = false;
var vrimod_wopening_pick = '.json_encode($wopening_pick).';
var vrimod_wopening_drop = '.json_encode($wopening_drop).';
var vrimod_hopening_pick = null;
var vrimod_hopening_drop = null;
var vrimod_mopening_pick = null;
var vrimod_mopening_drop = null;
function vriSetLocOpenTimeModule(loc, where) {
	if (where == "dropoff") {
		vrimod_location_change = true;
	}
	jQuery.ajax({
		type: "POST",
		url: "'.JRoute::rewrite('index.php?option=com_vikrentitems&task=ajaxlocopentime&tmpl=component&Itemid=' . $params->get('itemid', 0)).'",
		data: { idloc: loc, pickdrop: where }
	}).done(function(res) {
		var vriobj = JSON.parse(res);
		if (where == "pickup") {
			jQuery("#vrimodselph").html(vriobj.hours);
			jQuery("#vrimodselpm").html(vriobj.minutes);
			if (vriobj.hasOwnProperty("wopening")) {
				vrimod_wopening_pick = vriobj.wopening;
				vrimod_hopening_pick = vriobj.hours;
			}
		} else {
			jQuery("#vrimodseldh").html(vriobj.hours);
			jQuery("#vrimodseldm").html(vriobj.minutes);
			if (vriobj.hasOwnProperty("wopening")) {
				vrimod_wopening_drop = vriobj.wopening;
				vrimod_hopening_drop = vriobj.hours;
			}
		}
		if (where == "pickup" && vrimod_location_change === false) {
			jQuery("#modreturnplace").val(loc).trigger("change");
			vrimod_location_change = false;
		}
	});
}';
					$document->addScriptDeclaration($onchangedecl);
				}
				// end check if some place has a different openingtime
				
				$vrloc .= "<div class=\"vrisfentrymod\"><label for=\"modplace\">".JText::translate('VRMPPLACE')."</label><span class=\"vriplacesp\"><select name=\"place\" id=\"modplace\"".$onchangeplaces.">";
				foreach($places as $pla) {
					$vrloc .= "<option value=\"".$pla['id']."\"".(!empty($svriplace) && $svriplace == $pla['id'] ? " selected=\"selected\"" : "").">".$pla['name']."</option>\n";
				}
				$vrloc .= "</select></span></div>\n";
			}
		}
	}
} elseif (intval($params->get('showloc')) == 1) {
	$q = "SELECT * FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->execute();
	if ($dbo->getNumRows() > 0) {
		$places = $dbo->loadAssocList();
		$vri_tn->translateContents($places, '#__vikrentitems_places');
		// check if some place has a different opening time
		foreach ($places as $kpla=>$pla) {
			if (!empty($pla['opentime'])) {
				$diffopentime = true;
			}
			//check if some place has closing days
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
		// locations closing days
		if (count($closingdays) > 0) {
			foreach($closingdays as $idpla => $clostr) {
				$jsclosingdstr = ModVikrentitemsSearchHelper::formatLocationClosingDays($clostr);
				if (count($jsclosingdstr) > 0) {
					$declclosingdays .= 'var modloc'.$idpla.'closingdays = ['.implode(", ", $jsclosingdstr).'];'."\n";
				}
			}
		}
		$onchangeplaces = $diffopentime == true ? " onchange=\"javascript: vriSetLocOpenTimeModule(this.value, 'pickup');\"" : "";
		$onchangeplacesdrop = $diffopentime == true ? " onchange=\"javascript: vriSetLocOpenTimeModule(this.value, 'dropoff');\"" : "";
		if ($diffopentime == true) {
			$onchangedecl = '
var vrimod_location_change = false;
var vrimod_wopening_pick = '.json_encode($wopening_pick).';
var vrimod_wopening_drop = '.json_encode($wopening_drop).';
var vrimod_hopening_pick = null;
var vrimod_hopening_drop = null;
var vrimod_mopening_pick = null;
var vrimod_mopening_drop = null;
function vriSetLocOpenTimeModule(loc, where) {
	if (where == "dropoff") {
		vrimod_location_change = true;
	}
	jQuery.ajax({
		type: "POST",
		url: "'.JRoute::rewrite('index.php?option=com_vikrentitems&task=ajaxlocopentime&tmpl=component&Itemid=' . $params->get('itemid', 0)).'",
		data: { idloc: loc, pickdrop: where }
	}).done(function(res) {
		var vriobj = JSON.parse(res);
		if (where == "pickup") {
			jQuery("#vrimodselph").html(vriobj.hours);
			jQuery("#vrimodselpm").html(vriobj.minutes);
			if (vriobj.hasOwnProperty("wopening")) {
				vrimod_wopening_pick = vriobj.wopening;
				vrimod_hopening_pick = vriobj.hours;
			}
		} else {
			jQuery("#vrimodseldh").html(vriobj.hours);
			jQuery("#vrimodseldm").html(vriobj.minutes);
			if (vriobj.hasOwnProperty("wopening")) {
				vrimod_wopening_drop = vriobj.wopening;
				vrimod_hopening_drop = vriobj.hours;
			}
		}
		if (where == "pickup" && vrimod_location_change === false) {
			jQuery("#modreturnplace").val(loc).trigger("change");
			vrimod_location_change = false;
		}
	});
}';
			$document->addScriptDeclaration($onchangedecl);
		}
		// end check if some place has a different opningtime
		
		$vrloc .= "<div class=\"vrisfentrymod\"><label for=\"modplace\">".JText::translate('VRMPPLACE')."</label><span class=\"vriplacesp\"><select name=\"place\" id=\"modplace\"".$onchangeplaces.">";
		foreach($places as $pla) {
			$vrloc .= "<option value=\"".$pla['id']."\"".(!empty($svriplace) && $svriplace == $pla['id'] ? " selected=\"selected\"" : "").">".$pla['name']."</option>\n";
		}
		$vrloc .= "</select></span></div>\n";
	}
}
//

// prepare jQuery UI calendar
if ($dateformat == "%d/%m/%Y") {
	$juidf = 'dd/mm/yy';
} elseif ($dateformat == "%m/%d/%Y") {
	$juidf = 'mm/dd/yy';
} else {
	$juidf = 'yy/mm/dd';
}
$document->addStyleSheet(VRI_SITE_URI . 'resources/jquery-ui.min.css');
//load jQuery UI
JHtml::fetch('script', VRI_SITE_URI . 'resources/jquery-ui.min.js');
//
//lang for jQuery UI Calendar
$ldecl = '
jQuery(function($) {'."\n".'
	$.datepicker.regional["vikrentitemsmod"] = {'."\n".'
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
		firstDay: '.ModVikrentitemsSearchHelper::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikrentitemsmod"]);'."\n".'
});
function vriGetDateObject'.$randid.'(dstring) {
	var dparts = dstring.split("-");
	return new Date(dparts[0], (parseInt(dparts[1]) - 1), parseInt(dparts[2]), 0, 0, 0, 0);
}
function vriFullObject'.$randid.'(obj) {
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
					$monthscomborestr[($rmonth - 1)] = ModVikrentitemsSearchHelper::parseJsDrangeWdayCombo($restr);
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
					$wdaysrestrictionsrange[$kr][5] = ModVikrentitemsSearchHelper::parseJsDrangeWdayCombo($drestr);
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
function vriRefreshDropoff".$randid."(darrive) {
	if(vriFullObject".$randid."(vricombowdays)) {
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
				jQuery('#releasedatemod".$randid."').datepicker( 'option', 'minDate', vrifirstnextd );
				jQuery('#releasedatemod".$randid."').datepicker( 'setDate', vrifirstnextd );
				break;
			}
		}
	}
}
var vriDropMaxDateSet".$randid." = false;
function vriSetMinDropoffDate".$randid." () {
	var vriDropMaxDateSetNow".$randid." = false;
	var minlos = ".(intval($def_min_los) > 0 ? $def_min_los : '0').";
	var maxlosrange = 0;
	var nowpickup = jQuery('#pickupdatemod".$randid."').datepicker('getDate');
	var nowd = nowpickup.getDay();
	var nowpickupdate = new Date(nowpickup.getTime());
	vricombowdays = {};
	if(vriFullObject".$randid."(vrirestrminlosrangejn)) {
		for (var rk in vrirestrminlosrangejn) {
			if(vrirestrminlosrangejn.hasOwnProperty(rk)) {
				var minldrangeinit = vriGetDateObject".$randid."(vrirestrminlosrangejn[rk][0]);
				if(nowpickupdate >= minldrangeinit) {
					var minldrangeend = vriGetDateObject".$randid."(vrirestrminlosrangejn[rk][1]);
					if(nowpickupdate <= minldrangeend) {
						minlos = parseInt(vrirestrminlosrangejn[rk][2]);
						if(vriFullObject".$randid."(vrirestrmaxlosrangejn)) {
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
	if(vriFullObject".$randid."(vrirestrmonthscombojn) && vrirestrmonthscombojn.hasOwnProperty(nowm)) {
		if(nowd in vrirestrmonthscombojn[nowm]) {
			vricombowdays = vrirestrmonthscombojn[nowm][nowd];
		}
	}
	if(jQuery.inArray((nowm + 1), vrirestrmonths) != -1) {
		minlos = parseInt(vrirestrminlos[nowm]);
	}
	nowpickupdate.setDate(nowpickupdate.getDate() + minlos);
	jQuery('#releasedatemod".$randid."').datepicker( 'option', 'minDate', nowpickupdate );
	if(maxlosrange > 0) {
		var diffmaxminlos = maxlosrange - minlos;
		var maxdropoffdate = new Date(nowpickupdate.getTime());
		maxdropoffdate.setDate(maxdropoffdate.getDate() + diffmaxminlos);
		jQuery('#releasedatemod".$randid."').datepicker( 'option', 'maxDate', maxdropoffdate );
		vriDropMaxDateSet".$randid." = true;
		vriDropMaxDateSetNow".$randid." = true;
	}
	if(nowm in vrirestrmaxlos) {
		var diffmaxminlos = parseInt(vrirestrmaxlos[nowm]) - minlos;
		var maxdropoffdate = new Date(nowpickupdate.getTime());
		maxdropoffdate.setDate(maxdropoffdate.getDate() + diffmaxminlos);
		jQuery('#releasedatemod".$randid."').datepicker( 'option', 'maxDate', maxdropoffdate );
		vriDropMaxDateSet".$randid." = true;
		vriDropMaxDateSetNow".$randid." = true;
	}
	if(!vriFullObject".$randid."(vricombowdays)) {
		jQuery('#releasedatemod".$randid."').datepicker( 'setDate', nowpickupdate );
		if (!vriDropMaxDateSetNow".$randid." && vriDropMaxDateSet".$randid." === true) {
			// unset maxDate previously set
			jQuery('#releasedatemod".$randid."').datepicker( 'option', 'maxDate', null );
		}
	} else {
		vriRefreshDropoff".$randid."(nowpickup);
	}
}";
			
	if (count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0) {
		$resdecl .= "
var vrirestrwdays = {".implode(", ", $wdaysrestrictions)."};
var vrirestrwdaystwo = {".implode(", ", $wdaystworestrictions)."};
function vriIsDayDisabled".$randid."(date) {
	if(!vriValidateCta".$randid."(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = modpickupClosingDays".$randid."(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject".$randid."(vrirestrwdaysrangejn)) {
		for (var rk in vrirestrwdaysrangejn) {
			if(vrirestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject".$randid."(vrirestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject".$randid."(vrirestrwdaysrangejn[rk][1]);
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
	if(vriFullObject".$randid."(vrirestrwdays)) {
		if(jQuery.inArray((m+1), vrirestrmonthswdays) == -1) {
			return [true];
		}
		if(wd == vrirestrwdays[m]) {
			return [true];
		}
		if(vriFullObject".$randid."(vrirestrwdaystwo)) {
			if(wd == vrirestrwdaystwo[m]) {
				return [true];
			}
		}
		return [false];
	}
	return [true];
}
function vriIsDayDisabledDropoff".$randid."(date) {
	if(!vriValidateCtd".$randid."(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = moddropoffClosingDays".$randid."(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject".$randid."(vricombowdays)) {
		if(jQuery.inArray(wd, vricombowdays) != -1) {
			return [true];
		} else {
			return [false];
		}
	}
	if(vriFullObject".$randid."(vrirestrwdaysrangejn)) {
		for (var rk in vrirestrwdaysrangejn) {
			if(vrirestrwdaysrangejn.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject".$randid."(vrirestrwdaysrangejn[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject".$randid."(vrirestrwdaysrangejn[rk][1]);
					if(date <= wdrangeend) {
						if(wd != vrirestrwdaysrangejn[rk][2] && vrirestrwdaysrangejn[rk][3] == 1) {
							return [false];
						}
					}
				}
			}
		}
	}
	if(vriFullObject".$randid."(vrirestrwdays)) {
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
//locations closing days (1.7)
if (strlen($declclosingdays) > 0) {
	$declclosingdays .= '
function modpickupClosingDays'.$randid.'(date) {
	dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var arrlocclosd = jQuery("#modplace").val();
	var checklocarr = window["modloc"+arrlocclosd+"closingdays"];
	if (jQuery.inArray(dmy, checklocarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::translate('VRIMLOCDAYCLOSED')).'"];
	}
}
function moddropoffClosingDays'.$randid.'(date) {
	dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var arrlocclosd = jQuery("#modreturnplace").val();
	var checklocarr = window["modloc"+arrlocclosd+"closingdays"];
	if (jQuery.inArray(dmy, checklocarr) == -1) {
		return [true, ""];
	} else {
		return [false, "", "'.addslashes(JText::translate('VRIMLOCDAYCLOSED')).'"];
	}
}';
	$document->addScriptDeclaration($declclosingdays);
}
//
//Minimum Num of Days of Rental (VRI 1.4)
$dropdayplus = $def_min_los;
$forcedropday = "jQuery('#releasedatemod').datepicker( 'option', 'minDate', selectedDate );";
if (strlen($dropdayplus) > 0 && intval($dropdayplus) > 0) {
	$forcedropday = "
var nowpick = jQuery(this).datepicker('getDate');
if (nowpick) {
	var nowpickdate = new Date(nowpick.getTime());
	nowpickdate.setDate(nowpickdate.getDate() + ".$dropdayplus.");
	jQuery('#releasedatemod".$randid."').datepicker( 'option', 'minDate', nowpickdate );
	jQuery('#releasedatemod".$randid."').datepicker( 'setDate', nowpickdate );
}";
}
//
$sdecl = "
function vriCheckClosingDatesIn".$randid."(date) {
	if(!vriValidateCta".$randid."(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = modpickupClosingDays".$randid."(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	return [true];
}
function vriCheckClosingDatesOut".$randid."(date) {
	if(!vriValidateCtd".$randid."(date)) {
		return [false];
	}
	".(strlen($declclosingdays) > 0 ? "var loc_closing = moddropoffClosingDays".$randid."(date); if (!loc_closing[0]) {return loc_closing;}" : "")."
	return [true];
}
function vriValidateCta".$randid."(date) {
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject".$randid."(vrirestrctarange)) {
		for (var rk in vrirestrctarange) {
			if(vrirestrctarange.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject".$randid."(vrirestrctarange[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject".$randid."(vrirestrctarange[rk][1]);
					if(date <= wdrangeend) {
						if(jQuery.inArray('-'+wd+'-', vrirestrctarange[rk][2]) >= 0) {
							return false;
						}
					}
				}
			}
		}
	}
	if(vriFullObject".$randid."(vrirestrcta)) {
		if(vrirestrcta.hasOwnProperty(m) && jQuery.inArray('-'+wd+'-', vrirestrcta[m]) >= 0) {
			return false;
		}
	}
	return true;
}
function vriValidateCtd".$randid."(date) {
	var m = date.getMonth(), wd = date.getDay();
	if(vriFullObject".$randid."(vrirestrctdrange)) {
		for (var rk in vrirestrctdrange) {
			if(vrirestrctdrange.hasOwnProperty(rk)) {
				var wdrangeinit = vriGetDateObject".$randid."(vrirestrctdrange[rk][0]);
				if(date >= wdrangeinit) {
					var wdrangeend = vriGetDateObject".$randid."(vrirestrctdrange[rk][1]);
					if(date <= wdrangeend) {
						if(jQuery.inArray('-'+wd+'-', vrirestrctdrange[rk][2]) >= 0) {
							return false;
						}
					}
				}
			}
		}
	}
	if(vriFullObject".$randid."(vrirestrctd)) {
		if(vrirestrctd.hasOwnProperty(m) && jQuery.inArray('-'+wd+'-', vrirestrctd[m]) >= 0) {
			return false;
		}
	}
	return true;
}
function vriLocationWopening".$randid."(mode) {
	if (typeof vrimod_wopening_pick === 'undefined') {
		return true;
	}
	if (mode == 'pickup') {
		vrimod_mopening_pick = null;
	} else {
		vrimod_mopening_drop = null;
	}
	var loc_data = mode == 'pickup' ? vrimod_wopening_pick : vrimod_wopening_drop;
	var def_loc_hours = mode == 'pickup' ? vrimod_hopening_pick : vrimod_hopening_drop;
	var sel_d = jQuery((mode == 'pickup' ? '#pickupdatemod".$randid."' : '#releasedatemod".$randid."')).datepicker('getDate');
	if (!sel_d) {
		return true;
	}
	var sel_wday = sel_d.getDay();
	if (!vriFullObject".$randid."(loc_data) || !loc_data.hasOwnProperty(sel_wday) || !loc_data[sel_wday].hasOwnProperty('fh')) {
		if (def_loc_hours !== null) {
			// populate the default opening time dropdown
			jQuery((mode == 'pickup' ? '#vrimodselph' : '#vrimodseldh')).html(def_loc_hours);
		}
		return true;
	}
	if (mode == 'pickup') {
		vrimod_mopening_pick = new Array(loc_data[sel_wday]['fh'], loc_data[sel_wday]['fm']);
	} else {
		vrimod_mopening_drop = new Array(loc_data[sel_wday]['th'], loc_data[sel_wday]['tm']);
	}
	var hlim = loc_data[sel_wday]['fh'] < loc_data[sel_wday]['th'] ? loc_data[sel_wday]['th'] : (24 + loc_data[sel_wday]['th']);
	hlim = loc_data[sel_wday]['fh'] == 0 && loc_data[sel_wday]['th'] == 0 ? 23 : hlim;
	var hopts = '';
	var def_hour = jQuery((mode == 'pickup' ? '#vrimodselph' : '#vrimodseldh')).find('select').val();
	def_hour = def_hour.length > 1 && def_hour.substr(0, 1) == '0' ? def_hour.substr(1) : def_hour;
	def_hour = parseInt(def_hour);
	for (var h = loc_data[sel_wday]['fh']; h <= hlim; h++) {
		var viewh = h > 23 ? (h - 24) : h;
		hopts += '<option value=\''+viewh+'\''+(viewh == def_hour ? ' selected' : '')+'>'+(viewh < 10 ? '0'+viewh : viewh)+'</option>';
	}
	jQuery((mode == 'pickup' ? '#vrimodselph' : '#vrimodseldh')).find('select').html(hopts);
	if (mode == 'pickup') {
		setTimeout(function() {
			vriLocationWopening".$randid."('dropoff');
		}, 750);
	}
}
function vriInitElems".$randid."() {
	if (typeof vrimod_wopening_pick === 'undefined') {
		return true;
	}
	vrimod_hopening_pick = jQuery('#vrimodselph').find('select').clone();
	vrimod_hopening_drop = jQuery('#vrimodseldh').find('select').clone();
}
jQuery(function() {
	vriInitElems".$randid."();
	if (!jQuery('#pickupdatemod".$randid."').length) {
		return;
	}
	jQuery('#pickupdatemod".$randid."').datepicker({
		showOn: 'focus',".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vriIsDayDisabled".$randid.",\n" : "\nbeforeShowDay: vriCheckClosingDatesIn".$randid.",\n")."
		onSelect: function( selectedDate ) {
			".($totrestrictions > 0 ? "vriSetMinDropoffDate".$randid."();" : $forcedropday)."
			vriLocationWopening".$randid."('pickup');
		}
	});
	jQuery('#pickupdatemod".$randid."').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#pickupdatemod".$randid."').datepicker( 'option', 'minDate', '".ModVikrentitemsSearchHelper::getMinDaysAdvance()."d');
	jQuery('#pickupdatemod".$randid."').datepicker( 'option', 'maxDate', '".ModVikrentitemsSearchHelper::getMaxDateFuture()."');
	jQuery('#releasedatemod".$randid."').datepicker({
		showOn: 'focus',".(count($wdaysrestrictions) > 0 || count($wdaysrestrictionsrange) > 0 ? "\nbeforeShowDay: vriIsDayDisabledDropoff".$randid.",\n" : "\nbeforeShowDay: vriCheckClosingDatesOut".$randid.",\n")."
		onSelect: function( selectedDate ) {
			vriLocationWopening".$randid."('dropoff');
		}
	});
	jQuery('#releasedatemod".$randid."').datepicker( 'option', 'dateFormat', '".$juidf."');
	jQuery('#releasedatemod".$randid."').datepicker( 'option', 'minDate', '".ModVikrentitemsSearchHelper::getMinDaysAdvance()."d');
	jQuery('#releasedatemod".$randid."').datepicker( 'option', 'maxDate', '".ModVikrentitemsSearchHelper::getMaxDateFuture()."');
	jQuery('#pickupdatemod".$randid."').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentitemsmod' ] );
	jQuery('#releasedatemod".$randid."').datepicker( 'option', jQuery.datepicker.regional[ 'vikrentitemsmod' ] );
	jQuery('.vri-cal-img, .vri-caltrigger').click(function() {
		var jdp = jQuery(this).prev('input.hasDatepicker');
		if (jdp.length) {
			jdp.focus();
		}
	});
});";
$document->addScriptDeclaration($sdecl);
//

/**
 * @wponly 	the heading text is no longer formatted with tags and classes
 */
$heading_text = $params->get('heading_text');

?>

<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
	<div class="vikrentitemsrcsdiv">
	<?php
	if (!empty($heading_text)) {
		?>
		<div class="vri-searchmod-heading"><?php echo $heading_text; ?></div>
		<?php
	}
	?>
		<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&Itemid='.$params->get('itemid', 0)); ?>" method="post" onsubmit="return vriValidateSearch<?php echo $randid; ?>();">
			<input type="hidden" name="task" value="search"/>
			<div class="vrimodcalform">
				<?php
				// session values + load Timeslots
				$vrisessioncart = $session->get('vriCart', '');
				$vrisesspickup = $session->get('vripickupts', '');
				$vrisessdropoff = $session->get('vrireturnts', '');
				$vrisessdays = $session->get('vridays', '');
				$vrisesspickuploc = $session->get('vriplace', '');
				$vrisessdropoffloc = $session->get('vrireturnplace', '');
				$timeslots = ModVikrentitemsSearchHelper::loadGlobalTimeSlots($vri_tn);
				if ($dateformat == "%d/%m/%Y") {
					$jsdf = 'd/m/Y';
				} elseif ($dateformat == "%m/%d/%Y") {
					$jsdf = 'm/d/Y';
				} else {
					$jsdf = 'Y/m/d';
				}
				if (is_array($vrisessioncart) && count($vrisessioncart) > 0) {
					echo "<div class=\"vrisfentrymodsum vri-modsearch-sessvals\"><div class=\"vrisearchmoddivpickup\"><span class=\"vrisearchmodspanone\">" . JText::translate('VRMPICKUPCAR') . "</span><span class=\"vrisearchmodspantwo\"><input type=\"hidden\" name=\"pickupdate\" id=\"pickupdatemod".$randid."\" value=\"".date($jsdf, $vrisesspickup)."\"/>".date($jsdf, $vrisesspickup)." " . (!empty($nowtf) ? JText::translate('VRMALLE') : '') . " <input type=\"hidden\" name=\"pickuph\" value=\"".date('H', $vrisesspickup)."\"/>".(!empty($nowtf) ? date('H', $vrisesspickup).":" : '')."<input type=\"hidden\" name=\"pickupm\" value=\"".date('i', $vrisesspickup)."\"/>".(!empty($nowtf) ? date('i', $vrisesspickup) : '')."</span></div></div>\n";
					echo "<div class=\"vrisfentrymodsum vri-modsearch-sessvals\"><div class=\"vrisearchmoddivdropoff\"><span class=\"vrisearchmodspanone\">" . JText::translate('VRMRETURNCAR') . "</span><span class=\"vrisearchmodspantwo\"><input type=\"hidden\" name=\"releasedate\" id=\"releasedatemod".$randid."\" value=\"".date($jsdf, $vrisessdropoff)."\"/>".date($jsdf, $vrisessdropoff)." " . (!empty($nowtf) ? JText::translate('VRMALLE') : '') . " <input type=\"hidden\" name=\"releaseh\" value=\"".date('H', $vrisessdropoff)."\"/>".(!empty($nowtf) ? date('H', $vrisessdropoff).":" : '')."<input type=\"hidden\" name=\"releasem\" value=\"".date('i', $vrisessdropoff)."\"/>".(!empty($nowtf) ? date('i', $vrisessdropoff) : '')."</span></div></div>";
				}
				//

				// print locations selection (if any)
				echo $vrloc;
				//
				
				$i = 0;
				$imin = 0;
				$j = 23;
				
				if ($diffopentime === true && is_array($places) && strlen($places[$indvriplace]['opentime']) > 0) {
					$parts = explode("-", $places[$indvriplace]['opentime']);
					if (is_array($parts) && $parts[0] != $parts[1]) {
						$opent = ModVikrentitemsSearchHelper::mgetHoursMinutes($parts[0]);
						$closet = ModVikrentitemsSearchHelper::mgetHoursMinutes($parts[1]);
						$i = $opent[0];
						$imin = $opent[1];
						$j = $closet[0];
					} else {
						$i = 0;
						$imin = 0;
						$j = 23;
					}
					//change dates drop off location opening time (1.6)
					$iret = $i;
					$iminret = $imin;
					$jret = $j;
					if ($indvriplace != $indvrireturnplace) {
						if (strlen($places[$indvrireturnplace]['opentime']) > 0) {
							//different opening time for drop off location
							$parts = explode("-", $places[$indvrireturnplace]['opentime']);
							if (is_array($parts) && $parts[0] != $parts[1]) {
								$opent = ModVikrentitemsSearchHelper::mgetHoursMinutes($parts[0]);
								$closet = ModVikrentitemsSearchHelper::mgetHoursMinutes($parts[1]);
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
							$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='timeopenstore';";
							$dbo->setQuery($q);
							$dbo->execute();
							$timeopst = $dbo->loadResult();
							$timeopst = explode("-", $timeopst);
							if (is_array($timeopst) && $timeopst[0] != $timeopst[1]) {
								$opent = ModVikrentitemsSearchHelper::mgetHoursMinutes($timeopst[0]);
								$closet = ModVikrentitemsSearchHelper::mgetHoursMinutes($timeopst[1]);
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
					$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='timeopenstore';";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() == 1) {
						$n = $dbo->loadAssocList();
						if (!empty($n[0]['setting'])) {
							$timeopst = explode("-", $n[0]['setting']);
							if (is_array($timeopst) && $timeopst[0] != $timeopst[1]) {
								if ($timeopst[0] >= 3600) {
									$op = $timeopst[0] / 3600;
									$hoursop = floor($op);
								} else {
									$hoursop = "0";
								}
								$i = $hoursop;
								$opent = ModVikrentitemsSearchHelper::mgetHoursMinutes($timeopst[0]);
								$imin = $opent[1];
								if ($timeopst[1] >= 3600) {
									$op = $timeopst[1] / 3600;
									$hourscl = floor($op);
								} else {
									$hourscl = "0";
								}
								$j = $hourscl;
							}
						}
					}
					$iret = $i;
					$iminret = $imin;
					$jret = $j;
				}
				
				$hours = "";
				$pickhdeftime = !empty($places[$indvriplace]['defaulttime']) ? ((int)$places[$indvriplace]['defaulttime'] / 3600) : '';
				if (!($i < $j)) {
					while (intval($i) != (int)$j) {
						$sayi = $i < 10 ? "0".$i : $i;
						if ($nowtf != 'H:i') {
							$ampm = $i < 12 ? ' am' : ' pm';
							$ampmh = $i > 12 ? ($i - 12) : $i;
							$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
						} else {
							$sayh = $sayi;
						}
						$hours .= "<option value=\"" . (int)$i . "\"".($pickhdeftime == (int)$i ? ' selected="selected"' : '').">" . $sayh . "</option>\n";
						$i++;
						$i = $i > 23 ? 0 : $i;
					}
					$sayi = $i < 10 ? "0".$i : $i;
					if ($nowtf != 'H:i') {
						$ampm = $i < 12 ? ' am' : ' pm';
						$ampmh = $i > 12 ? ($i - 12) : $i;
						$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
					} else {
						$sayh = $sayi;
					}
					$hours .= "<option value=\"" . (int)$i . "\">" . $sayh . "</option>\n";
				} else {
					while ($i <= $j) {
						$sayi = $i < 10 ? "0".$i : $i;
						if ($nowtf != 'H:i') {
							$ampm = $i < 12 ? ' am' : ' pm';
							$ampmh = $i > 12 ? ($i - 12) : $i;
							$sayh = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
						} else {
							$sayh = $sayi;
						}
						$hours .= "<option value=\"" . (int)$i . "\"".($pickhdeftime == (int)$i ? ' selected="selected"' : '').">" . $sayh . "</option>\n";
						$i++;
					}
				}

				$hoursret = "";
				$drophdeftime = !empty($places[$indvrireturnplace]['defaulttime']) ? ((int)$places[$indvrireturnplace]['defaulttime'] / 3600) : '';
				if (!($iret < $jret)) {
					while (intval($iret) != (int)$jret) {
						$sayiret = $iret < 10 ? "0".$iret : $iret;
						if ($nowtf != 'H:i') {
							$ampm = $iret < 12 ? ' am' : ' pm';
							$ampmh = $iret > 12 ? ($iret - 12) : $iret;
							$sayhret = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
						} else {
							$sayhret = $sayiret;
						}
						$hoursret .= "<option value=\"" . (int)$iret . "\"".($drophdeftime == (int)$iret ? ' selected="selected"' : '').">" . $sayhret . "</option>\n";
						$iret++;
						$iret = $iret > 23 ? 0 : $iret;
					}
					$sayiret = $iret < 10 ? "0".$iret : $iret;
					if ($nowtf != 'H:i') {
						$ampm = $iret < 12 ? ' am' : ' pm';
						$ampmh = $iret > 12 ? ($iret - 12) : $iret;
						$sayhret = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
					} else {
						$sayhret = $sayiret;
					}
					$hoursret .= "<option value=\"" . (int)$iret . "\">" . $sayhret . "</option>\n";
				} else {
					while ((int)$iret <= $jret) {
						$sayiret = $iret < 10 ? "0".$iret : $iret;
						if ($nowtf != 'H:i') {
							$ampm = $iret < 12 ? ' am' : ' pm';
							$ampmh = $iret > 12 ? ($iret - 12) : $iret;
							$sayhret = $ampmh < 10 ? "0".$ampmh.$ampm : $ampmh.$ampm;
						} else {
							$sayhret = $sayiret;
						}
						$hoursret .= "<option value=\"" . (int)$iret . "\"".($drophdeftime == (int)$iret ? ' selected="selected"' : '').">" . $sayhret . "</option>\n";
						$iret++;
					}
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
				
				$forcedpickdroptimes = ModVikrentitemsSearchHelper::getForcedPickDropTimes();
				if (true) {
					if (!is_array($vrisessioncart) || !count($vrisessioncart)) {
						echo "<div class=\"vrisfentrymod vrisfentrymod-dpicker\"><label class=\"vripickdroplab\" for=\"pickupdatemod".$randid."\">" . JText::translate('VRMPICKUPCAR') . "</label><div class=\"vrisfentrydate\"><input type=\"text\" name=\"pickupdate\" id=\"pickupdatemod".$randid."\" size=\"10\" autocomplete=\"off\"/><i class=\"fas fa-calendar-alt vri-caltrigger\"></i></div>";
						if (is_array($timeslots) && count($timeslots) > 0) {
							echo "<div class=\"vrisfentrymodtimeslot\"><label for=\"timeslotsmod\">".JText::translate('VRIMFOR') . "</label>";
							$wseltimeslots = "<span><select name=\"timeslot\" id=\"timeslotsmod\">\n";
							foreach($timeslots as $times) {
								$wseltimeslots .= "<option value=\"".$times['id']."\">".$times['tname']."</option>\n";
							}
							$wseltimeslots .= "</select></span></div>\n";
							echo $wseltimeslots . "</div>\n";
						} else {
							$selpickh = is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 ? '<input type="hidden" name="pickuph" value="'.$forcedpickdroptimes[0][0].'"/><span class="vrimodforcetime">'.$forcedpickdroptimes[0][0].'</span>' : '<select name="pickuph" id="modpickuph">' . $hours . '</select>';
							$selpickm = is_array($forcedpickdroptimes[0]) && count($forcedpickdroptimes[0]) > 0 ? '<input type="hidden" name="pickupm" value="'.$forcedpickdroptimes[0][1].'"/><span class="vrimodforcetime">'.$forcedpickdroptimes[0][1].'</span>' : '<select name="pickupm">' . $minutes . '</select>';
							echo "<div class=\"vrisfentrymodtime\"><label for=\"modpickuph\">".JText::translate('VRMALLE') . "</label><span id=\"vrimodselph\">" . $selpickh . "</span><label class=\"vritimedots\">:</label><span id=\"vrimodselpm\">".$selpickm."</span></div></div>\n";
							$seldroph = is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 ? '<input type="hidden" name="releaseh" value="'.$forcedpickdroptimes[1][0].'"/><span class="vrimodforcetime">'.$forcedpickdroptimes[1][0].'</span>' : '<select name="releaseh" id="modreleaseh">' . $hoursret . '</select>';
							$seldropm = is_array($forcedpickdroptimes[1]) && count($forcedpickdroptimes[1]) > 0 ? '<input type="hidden" name="releasem" value="'.$forcedpickdroptimes[1][1].'"/><span class="vrimodforcetime">'.$forcedpickdroptimes[1][1].'</span>' : '<select name="releasem">' . $minutesret . '</select>';
							echo "<div class=\"vrisfentrymod vrisfentrymod-dpicker\"><label class=\"vripickdroplab\" for=\"releasedatemod".$randid."\">" . JText::translate('VRMRETURNCAR') . "</label><div class=\"vrisfentrydate\"><input type=\"text\" name=\"releasedate\" id=\"releasedatemod".$randid."\" size=\"10\" autocomplete=\"off\"/><i class=\"fas fa-calendar-alt vri-caltrigger\"></i></div><div class=\"vrisfentrymodtime\"><label for=\"modreleaseh\">" . JText::translate('VRMALLE') . "</label><span id=\"vrimodseldh\">".$seldroph."</span><label class=\"vritimedots\">:</label><span id=\"vrimodseldm\">".$seldropm."</span></div></div>";
						}
					}
				} else {
					/**
					 * Deprecated: Joomla Calendar no longer supported.
					 * jQuery UI is the only supported calendar type.
					 * 
					 * @since   1.6
					 */
				}
				
				$vricats = "";
				
				if (@is_array($places)) {
					$vrlocreturn="";
					$vrlocreturn .= "<div class=\"vrisfentrymod\"><label for=\"modreturnplace\">".JText::translate('VRMPLACERET')."</label><span class=\"vriplacesp\"><select name=\"returnplace\" id=\"modreturnplace\"".(strlen($onchangeplacesdrop) > 0 ? $onchangeplacesdrop : "").">";
					foreach($places as $pla) {
						$vrlocreturn .= "<option value=\"".$pla['id']."\"".(!empty($svrireturnplace) && $svrireturnplace == $pla['id'] ? " selected=\"selected\"" : "").">".$pla['name']."</option>\n";
					}
					$vrlocreturn .= "</select></span></div>\n";
					echo $vrlocreturn;
				}
				
				if (intval($params->get('showcat')) == 1) {
					$q = "SELECT * FROM `#__vikrentitems_categories` ORDER BY `#__vikrentitems_categories`.`name` ASC;";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$categories = $dbo->loadAssocList();
						$vri_tn->translateContents($categories, '#__vikrentitems_categories');
						$vricats .= "<div class=\"vrisfentrymod\"><label for=\"modcategories\">".JText::translate('VRMCARCAT')."</label><span class=\"vriplacesp\"><select name=\"categories\" id=\"modcategories\">";
						$vricats .= "<option value=\"all\">".JText::translate('VRMALLCAT')."</option>\n";
						foreach($categories as $cat) {
							$vricats .= "<option value=\"".$cat['id']."\">".$cat['name']."</option>\n";
						}
						$vricats .= "</select></span></div>\n";
					}
				}  elseif (intval($params->get('category_id')) > 0) {
					$q = "SELECT * FROM `#__vikrentitems_categories` WHERE `id`=".(int)$params->get('category_id').";";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$categories = $dbo->loadAssocList();
						$vri_tn->translateContents($categories, '#__vikrentitems_categories');
						?>
						<input type="hidden" name="categories" value="<?php echo $categories[0]['id']; ?>" />
						<?php
					}
				}
				echo $vricats;
				?>
				<div class="vrisfentrymodsubmit">
					<button type="submit" class="btn vrisearch"><?php echo JText::translate('SEARCHD'); ?></button>
				</div>
			</div>
		</form>
		
		<?php
		if (intval($params->get('showgotosumm')) == 1 && is_array($vrisessioncart) && count($vrisessioncart) > 0) {
			?>
		<div class="vrisearchmodgosummarydiv">
			<a class="btn" href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&task=oconfirm&place='.$vrisesspickuploc.'&returnplace='.$vrisessdropoffloc.'&days='.$vrisessdays.'&pickup='.$vrisesspickup.'&release='.$vrisessdropoff.'&Itemid='.$params->get('itemid', 0)); ?>"><?php echo JText::translate('VRIMGOTOSUMMARY'); ?></a>
		</div>
			<?php
		}
		?>
	</div>
</div>

<?php
// populate default values
$sespickupts = $session->get('vripickupts', '');
$sesdropoffts = $session->get('vrireturnts', '');
$ptask = $input->getString('task', '');
if ($ptask == 'search' && !empty($sespickupts) && !empty($sesdropoffts)) {
	$sespickuph = date('H', $sespickupts);
	$sespickupm = date('i', $sespickupts);
	$sesdropoffh = date('H', $sesdropoffts);
	$sesdropoffm = date('i', $sesdropoffts);
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		document.getElementById('pickupdatemod<?php echo $randid; ?>').value = '<?php echo date($jsdf, $sespickupts); ?>';
		document.getElementById('releasedatemod<?php echo $randid; ?>').value = '<?php echo date($jsdf, $sesdropoffts); ?>';
		var modf = jQuery("#pickupdatemod<?php echo $randid; ?>").closest("form");
		if (modf) {
			if (modf.find("select[name='pickuph']").length) {
				modf.find("select[name='pickuph']").val("<?php echo $sespickuph; ?>");
				modf.find("select[name='pickupm']").val("<?php echo $sespickupm; ?>");
			}
			if (modf.find("select[name='releaseh']").length) {
				modf.find("select[name='releaseh']").val("<?php echo $sesdropoffh; ?>");
				modf.find("select[name='releasem']").val("<?php echo $sesdropoffm; ?>");
			}
		}
	});
	</script>
	<?php
}

/**
 * Form submit JS validation (mostly used for the opening/closing minutes).
 * This piece of code should be always printed in the DOM as the main form
 * calls this function when going on submit.
 * 
 * @since 	1.7
 */
?>
<script type="text/javascript">
function vriCleanNumber<?php echo $randid; ?>(snum) {
	if (snum.length > 1 && snum.substr(0, 1) == '0') {
		return parseInt(snum.substr(1));
	}
	return parseInt(snum);
}
function vriValidateSearch<?php echo $randid; ?>() {
	if (typeof jQuery === 'undefined' || typeof vrimod_wopening_pick === 'undefined') {
		return true;
	}
	if (vrimod_mopening_pick !== null) {
		// pickup time
		var pickh = jQuery('#vrimodselph').find('select').val();
		var pickm = jQuery('#vrimodselpm').find('select').val();
		if (!pickh || !pickh.length || !pickm) {
			return true;
		}
		pickh = vriCleanNumber<?php echo $randid; ?>(pickh);
		pickm = vriCleanNumber<?php echo $randid; ?>(pickm);
		if (pickh == vrimod_mopening_pick[0]) {
			if (pickm < vrimod_mopening_pick[1]) {
				// location is still closed at this time
				jQuery('#vrimodselpm').find('select').html('<option value="'+vrimod_mopening_pick[1]+'">'+(vrimod_mopening_pick[1] < 10 ? '0'+vrimod_mopening_pick[1] : vrimod_mopening_pick[1])+'</option>').val(vrimod_mopening_pick[1]);
			}
		}
	}

	if (vrimod_mopening_drop !== null) {
		// dropoff time
		var droph = jQuery('#vrimodseldh').find('select').val();
		var dropm = jQuery('#vrimodseldm').find('select').val();
		if (!droph || !droph.length || !dropm) {
			return true;
		}
		droph = vriCleanNumber<?php echo $randid; ?>(droph);
		dropm = vriCleanNumber<?php echo $randid; ?>(dropm);
		if (droph == vrimod_mopening_drop[0]) {
			if (dropm > vrimod_mopening_drop[1]) {
				// location is already closed at this time
				jQuery('#vrimodseldm').find('select').html('<option value="'+vrimod_mopening_drop[1]+'">'+(vrimod_mopening_drop[1] < 10 ? '0'+vrimod_mopening_drop[1] : vrimod_mopening_drop[1])+'</option>').val(vrimod_mopening_drop[1]);
			}
		}
	}

	return true;
}
</script>
<?php
//
