var beds24dayname = "Day";


jQuery(document).ready(function($) {
	$("#datepicker").datepicker({
	buttonImage: WPURLS.siteurl + '/wp-content/plugins/beds24-online-booking/theme-files/include/ic_calendar2.png',
	buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	showOn: 'both',
	dateFormat: 'yy-mm-dd',
	beforeShow: readdatepicker, 
	onSelect: updatedatepicker
});
	
var beds24lang = jQuery('#fdate_lang').val();
var beds24getscript = false;
switch (beds24lang) {
case "da":  
  beds24dayname="Dag";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-da.js";
  break;
case "de":  //german
  beds24dayname="Tag";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-de.js";
  break;
case "el":  //greek
  beds24dayname="Ημέρα";
  beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-el.js";
  break;
case "es":  //spanish
  beds24dayname="Dia";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-es.js";
  break;
case "fi":  
  beds24dayname="Päivä";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-fi.js";
  break;
case "fr":  //french
  beds24dayname="Jour";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-fr.js";
  break;
case "it":  //italian
  beds24dayname="Giorno";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-it.js";
  break;        
case "ja":  
  beds24dayname="日";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-ja.js";
  break;
case "lt":  
  beds24dayname="Diena";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-lt.js";
  break;
case "nl":  
  beds24dayname="Dag";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-nl.js";
  break;
case "no":  
  beds24dayname="Dag";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-no.js";
  break;
case "pl":  
  beds24dayname="Dzień";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-pl.js";
  break;
case "pt":  //portuges
  beds24dayname="Dia";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-pt.js";
  break;
case "ru":  
  beds24dayname="День";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-ru.js";
  break;
case "sk":  
  beds24dayname="Deň";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-sk.js";
  break;
case "sl":  
  beds24dayname="Dan";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-sl.js";
  break;
case "sv":  
  beds24dayname="dag";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-sv.js";
  break;
case "tr":  
  beds24dayname="Gün";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-tr.js";
  break;
case "zh":  
  beds24dayname="日";
	beds24getscript = WPURLS.siteurl + "/wp-content/plugins/beds24-online-booking/js/ui/i18n/datepicker-zh-CN.js";
  break;
default: //default english
	beds24dayname="Day";
	if(document.getElementById("fdate_monthyear")) {fdate_monthyear_changed ();}
break;
}
if (beds24getscript) {
	$.getScript(beds24getscript).done(function() {if(document.getElementById("fdate_monthyear")) {fdate_monthyear_changed();}});
}

$( "#fdate_monthyear" ).change(function() {
	fdate_monthyear_changed();
});
});

function readdatepicker() {
	jQuery('#datepicker').val(jQuery('#fdate_monthyear').val() + '-' + jQuery('#fdate_date').val());
	return {};
}

function updatedatepicker(date) {
	jQuery('#fdate_monthyear').val(date.substring(0, 7));
	fdate_monthyear_changed ();
	jQuery('#fdate_date').val(parseInt(date.substring(8),10));
}

function fdate_monthyear_changed () {
	var dd = jQuery('#fdate_date').val();
	var mm = jQuery('#fdate_monthyear').val();
	jQuery('#fdate_date').empty();
	var options = new Array();
	options [0] = beds24dayname;
	if (mm==0) {
		for (i=1; i<=31; i++) {
			options[i] = i;
		}
	} else {
		year = parseInt(jQuery('#fdate_monthyear').val().substring(0, 4),10);
		month = parseInt(jQuery('#fdate_monthyear').val().substring(5, 7),10);
		for (i=1; i<=31; i++) {
			var d = new Date(year, month-1, i);	
			var n = d.getDay();
			var w = jQuery("#datepicker").datepicker("option","dayNamesShort");
			if (w == undefined) {
				w = jQuery("#datepicker").datepicker("option","dayNamesMin");
			}
			if (typeof w[n] == 'string' || w[n] instanceof String) {
				options[i] = w[n] + ' ' + i;
			}	else {
				options[i] =  i;
			}
		}
	}
	jQuery.each(options, function(value, key) {
		jQuery('#fdate_date').append(jQuery("<option></option>").attr("value", value).html(key));
	});
	jQuery('#fdate_date').val(dd);
}


