<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//2/23/18 9:25:02p - last saved

include(SL_INCLUDES_PATH."/top-nav.php");
sl_move_upload_directories();
sl_initialize_variables();

print "<div class='wrap'>";
print "<table class='widefat' cellpadding='0px' cellspacing='0px'>";

if (preg_match('@wordpress-store-locator-location-finder@', SL_DIR)) { 
	$icon_notification_msg="<p><div class='sl_admin_warning'>".__("<b>Note:</b> Your directory is <b>'wordpress-store-locator-location-finder'</b>. Please rename to <b>'store-locator'</b> to continue receiving notifications of future updates in your admin panel. After changing to <b>'store-locator'</b>, make sure to also update your icon URLs on the 'Map Designer' page.", "store-locator")."</div></p>"; 
	print $icon_notification_msg;
	}
	elseif ((preg_match("@wordpress-store-locator-location-finder@", $sl_vars['icon']) && preg_match("@store-locator@", SL_DIR)) || (preg_match("@wordpress-store-locator-location-finder@", $sl_vars['icon2']) && preg_match("@store-locator@", SL_DIR))) {
	$icon_notification_msg="<p><div class='sl_admin_warning'>You have switched from <strong>'wordpress-store-locator-location-finder'</strong> to <strong>'store-locator'</strong> --- great! <br>Now, please re-select your <b>'Home Icon'</b> and <b>'Destination Icon'</b> on the <a href='".SL_MAP_DESIGNER_PAGE."'>MapDesigner</a> page, so that they show up properly on your store locator map.</div></p>";
	print $icon_notification_msg;
	}

print "<tr><td valign='top' width='50%' style='padding:0px'>

<table width='100%' id='sl_news_table'><thead><tr>
<th>".
__("Latest News", "store-locator").
"</th>
</tr>
</thead>
<tr>
<td width='50%'>
<div style='overflow:scroll; height:550px; padding:7px;' id='sl_news_div'>
";

// include lastRSS library
if (!class_exists("lastRSS")) {
	include_once (SL_ACTIONS_PATH."/lastRSS.php");
}
// create lastRSS object
$rss_news = new lastRSS; 
// setup transparent cache
$rss_news->cache_dir = SL_CACHE_PATH; 
$rss_news->cache_time = 3600; // one hour

// load some RSS file
if ($rsn = $rss_news->get('https://feeds2.feedburner.com/Viadat?format=sigpro&openLinks=new&displayDate=false')) {
	//var_dump($rsn);die();

	$c=1;
	foreach ($rsn['items'] as $value) {

		if ($c<=100) {
			print "<li style='list-style-type:none; margin-top:10px; margin-bottom:0px;'><A href=\"$value[link]\" target='_blank' class='home_rss' style='font-size:18px; font-family: Georgia;'>
	<b>$value[title]</b></a></li> 
	<br>
	<div class='home_rss'> ". 
	preg_replace("@]]\>@","", $value['content:encoded'])
	. "<br><br>";
		} else {
			if ($c<=4)
				print "<li style='font-size:10px; color:black; position:relative; left:10px'><A href=\"$value[link]\" target='_blank' class='home_rss' style='font-size:11px'>$value[title]</a></li>";
		}
		$c++;
	}	
}

print "</td>
</tr></table>";

if ( (!function_exists("do_sl_hook") || (function_exists("do_sl_hook") && (!defined("SL_AP_VERSION") || version_compare(SL_AP_VERSION, '2.0')<0))) && !(false === ($sl_pricing_tables = sl_pricing_tables())) ) {

print "</td>
<td rowspan='1' valign='top' style='padding:0px'>

<table width='100%'><thead><tr>
<th width=''>".
sprintf(__("%s", "store-locator"), $sl_vars['sl_addons_page_name']).
"</th></tr></thead>
<tr>
<td><div style='overflow:scroll; height:560px; padding:7px; padding-top:0px;'>";
	print $sl_pricing_tables;

} elseif (function_exists("do_sl_hook") && defined("SL_ADDONS_PLATFORM_FILE") && !preg_match("@\-lite\.@", SL_ADDONS_PLATFORM_FILE) ) {

print "</td>
<td rowspan='1' valign='top' style='padding:0px'>

<table width='100%'><thead><tr>
<th width=''>".
__("Information", "store-locator").
"</th></tr></thead>
<tr>
<td><div style='overflow:scroll; height:560px; padding:7px; padding-top:0px;'>";
?>
<strong>1)&nbsp;<a href="#view_instructions" onclick="jQuery('#clickme').click(); jQuery('#readme_button').click(); return false;" >How to Use Store Locator</a></strong><br><br>
<strong>2)&nbsp;Your Server Capabilities:</strong><br>
<table cellpadding='4px' width='100%' class='sl_code code'>
<tr><td valign='top'><b>PHP:</b></td><td><?php include(SL_INFO_PATH."/php-ver.php"); ?></td></tr>
<tr><td valign='top'><b>Zip:</b></td><td><?php include(SL_INFO_PATH."/zip-info.php"); ?></td></tr>
<tr><td valign='top'><b>Extensions:</b></td><td><pre style='line-height:10px; font-size:11px; padding-top:0px; margin-top:0px;'><?php sl_truncate( print_r( get_loaded_extensions(), TRUE), 250, "print", 2 ); ?></pre></td></tr>
</table><br>
<strong>3)&nbsp;Environment Variables:</strong><br><pre style='line-height:12px; font-size:10px; padding-top:0px; margin-top:0px;' class='sl_code code'>
<?php $env_vars = sl_data("sl_vars"); 

$env_vars = array_filter($env_vars, "sl_env_var_filt");
print_r($env_vars) ?></pre>
<?php
} else {

	print "</td>
<td rowspan='1' valign='top' style='padding:0px'>

<table width='100%'><thead><tr>
<th width=''>".
__("Addons & Themes", "store-locator").
"</th></tr></thead>
<tr>
<td><div style='overflow:scroll; height:560px; padding:7px; padding-top:0px;'>";

// include lastRSS library
if (!class_exists("lastRSS")) {
	include_once (SL_ACTIONS_PATH."/lastRSS.php");
}
// create lastRSS object
$rss = new lastRSS; 
// setup transparent cache
$rss->cache_dir = SL_CACHE_PATH; 
$rss->cache_time = 3600; // one hour
//$rss->cache_time = 0; // one hour

// load some RSS file
if ($rs = $rss->get('http://'. SL_HOME_URL .'/index.php?rss=true&action=product_list&category_id=7')) {
	//var_dump($rs);

$c=1;
foreach ($rs['items'] as $value) {

if ($c<=100) {
	print "<li style='list-style-type:none; margin-top:10px; margin-bottom:0px;'><A href=\"$value[link]\" target='_blank' class='home_rss' style='font-size:18px; font-family: Georgia;'>
	<b>$value[title]</b></a></li>
	<!--br-->
	<div class='home_rss'> ".
	str_replace("]]>","",str_replace("</p>", "", html_entity_decode(nl2br($value['description'])))). 
	"</div><br><br>";
}
else {
	if ($c<=4)
	print "<li style='font-size:10px; color:black; position:relative; left:10px'><A href=\"$value[link]\" target='_blank' class='home_rss' style='font-size:11px'>$value[title]</a></li>";
	}
$c++;
	}	
//}

}
}

print "
</div>
</td>
</tr>
</table>


</td>
</tr>
</table>

</div>";

include(SL_INCLUDES_PATH."/sl-footer.php");
?>