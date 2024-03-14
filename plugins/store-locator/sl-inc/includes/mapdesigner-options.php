<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//MapDesigner Options

###Data - Form Inputs###
$icon_str="";$icon2_str="";

$icon_dir=opendir(SL_ICONS_PATH);  //trailing slash removed after path constant - v3.78 2:27a
while (false !== $icon_dir && false !== ($an_icon=readdir($icon_dir))) {
	if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

		$icon_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapdesigner_form\"].icon.value=this.src;document.getElementById(\"prev\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
	}
}
if (is_dir(SL_CUSTOM_ICONS_PATH)) {
	$icon_upload_dir=opendir(SL_CUSTOM_ICONS_PATH);
	while (false !== $icon_upload_dir && false !== ($an_icon=readdir($icon_upload_dir))) {
		if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

			$icon_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_CUSTOM_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapdesigner_form\"].icon.value=this.src;document.getElementById(\"prev\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
		}
	}
}

$icon_dir=opendir(SL_ICONS_PATH); //trailing slash removed after path constant - v3.78 2:27a
while (false !== $icon_dir && false !== ($an_icon=readdir($icon_dir))) {
	if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

		$icon2_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapdesigner_form\"].icon2.value=this.src;document.getElementById(\"prev2\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
	}
}
if (is_dir(SL_CUSTOM_ICONS_PATH)) {
	$icon_upload_dir=opendir(SL_CUSTOM_ICONS_PATH);
	while (false !== $icon_upload_dir && false !== ($an_icon=readdir($icon_upload_dir))) {
		if (!preg_match("@^\.{1,2}.*$@", $an_icon) && !preg_match("@shadow@", $an_icon) && !preg_match("@\.db@", $an_icon)) {

			$icon2_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='".SL_CUSTOM_ICONS_BASE."/$an_icon' onclick='document.forms[\"mapdesigner_form\"].icon2.value=this.src;document.getElementById(\"prev2\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
		}
	}
}

if (is_dir(SL_THEMES_PATH)) {
	$theme_dir=opendir(SL_THEMES_PATH); 
	$theme_str="";
	while (false !== $theme_dir && false !== ($a_theme=readdir($theme_dir))) {
		if (!preg_match("@^\.{1,2}.*$@", $a_theme) && !preg_match("@\.(php|txt|htm(l)?)@", $a_theme)) {

			$selected=($a_theme==$sl_vars['theme'])? " selected " : "";
			$theme_str.="<option value='$a_theme' $selected>$a_theme</option>\n";
		}
	}
}

$zl_arr=array();
for ($i=0; $i<=19; $i++) {
	$zl_arr[]=$i;
}

$zoom="<select name='zoom_level'>";
foreach ($zl_arr as $value) {
	$zoom.="<option value='$value' ";
	if ($sl_vars['zoom_level']==$value){ $zoom.=" selected ";}
	$zoom.=">$value</option>";
}
$zoom.="</select>";

$checked=($sl_vars['use_city_search']==1)? " checked " : "";
$checked2="";
//$checked2=($sl_vars['use_name_search']==1)? " checked " : "";
$checked3=($sl_vars['remove_credits']==1)? " checked " : "";
$checked4=($sl_vars['load_locations_default']==1)? " checked " : "";
$checked5=($sl_vars['map_overview_control']==1)? " checked " : "";
$checked6=($sl_vars['geolocate']==1)? " checked " : "";
$checked7=($sl_vars['load_results_with_locations_default']==1)? " checked " : "";

if (isset($sl_vars['scripts_load']) && $sl_vars['scripts_load']=='all'){
	$checked_all=" checked='checked' onclick='jQuery(\"#scripts_load_selective_tr\").fadeOut()' ";
	$checked_selective="onclick='jQuery(\"#scripts_load_selective_tr\").fadeIn()'";
	$hidden_selective_tr="style='display:none;'";
} else {
	$checked_all=" onclick='jQuery(\"#scripts_load_selective_tr\").fadeOut()' "; 
	$checked_selective=" checked='checked' onclick='jQuery(\"#scripts_load_selective_tr\").fadeIn()' ";
	$hidden_selective_tr="";
}
$checked_home=(isset($sl_vars['scripts_load_home']) && $sl_vars['scripts_load_home']==1)? " checked " : "";
$checked_archives_404=(isset($sl_vars['scripts_load_archives_404']) && $sl_vars['scripts_load_archives_404']==1)? " checked " : "";

$map_type = array();

$map_type["".__("Normal", "store-locator").""]="google.maps.MapTypeId.ROADMAP";
$map_type["".__("Normal + Terrain (Physical)", "store-locator").""]="google.maps.MapTypeId.TERRAIN";
$map_type["".__("Satellite", "store-locator").""]="google.maps.MapTypeId.SATELLITE";
$map_type["".__("Satellite + Labels (Hybrid)", "store-locator").""]="google.maps.MapTypeId.HYBRID";
$map_type_options="";

foreach($map_type as $key=>$value) {
	$selected2=($sl_vars['map_type']==$value)? " selected " : "";
	$map_type_options.="<option value='$value' $selected2>$key</option>\n";
}
$icon_notification_msg=((preg_match("@wordpress-store-locator-location-finder@", $sl_vars['icon']) && preg_match("@^store-locator@", SL_DIR)) || (preg_match("@wordpress-store-locator-location-finder@",$sl_vars['icon2']) && preg_match("@^store-locator@", SL_DIR)))? "<div class='sl_admin_success' style='background-color:LightYellow;color:red'><span style='color:red'>".__("You have switched from <strong>'wordpress-store-locator-location-finder'</strong> to <strong>'store-locator'</strong> --- great!<br>Now, please re-select your <b>'Home Icon'</b> and <b>'Destination Icon'</b> below, so that they show up properly on your store locator map.", "store-locator")."</span></div>" : "" ;


/*************** MapDesigner Options - Information & Usage **************/
/* 
== Description == 
Allows one to modify the '$sl_mdo' array via the 'sl_mapdesigner_options' hook in order to add options to the MapDesigner settings page

== Available Parameters in $sl_mdo array ==
= Required =
* field_name: 
name of the key stored in the $sl_vars array when saving the value
* default: 
initial value field is set to
* input_zone: 
the area of MapDesigner setting page where new option will appear (available values [as of v3.74] - "defaults", "labels", "dimensions", "design")
* output_zone: 
the area of Store Locator's functionality your option affects (available values [as of v3.74] - "sl_dyn_js", "sl_template", "sl_xml", "sl_head_scripts")
* label:
Description of option in MapDesigner settings
* input_template: 
HTML of the form element representing the new option

= Optional =
* field_type: 
only needed if your option is a 'checkbox'
* more_info: 
HTML in pop-up showing further details about the option, if needed
* more_info_label: 
1-word label of the link clicked to display HTML in 'more_info' (should be prefixed and unique label)
* row_id:
'id' value of the 'tr' HTML tag containing your new option
* hide_row:
logical condition under which you want the option(s) contained in the row labeled by 'row_id' to be hidden. If this evaluates to TRUE, then row will be hidden when option is first displayed. You can dynamically reveal the row using: {action}='jQuery("#{row_id}").fadeIn()'  in another form element in order to determine an action that reveals the row, where {action} can be 'onclick', 'onfocus', 'onmouseover', etc.
* stripslashes:
removes any slashes that are created in a text field if it contains apostrophes, quotation marks (available value: 1)
* numbers_only:
determines whether or not a new option's value can only contain numbers.  For example, and value of '24tgrwoi6f24l' will be changed to '24624' if 'numbers_only' is set to 1 (available values: 0, 1; available types: number or array [based on type of 'field_name'])
* colspan:
can be used if creating an informational row, where you fill in 'label' value and leave 'input_template' blank (available value: 2)

== Notes: Multiple values in grouped together ==
* If your new option has multiple values that you want to store in the $sl_vars array, then you can make 'field_name', 'default', and 'output_zone' entries in $sl_mdo into arrays in which field_name[0] has a default value of default[0] and an output zone of output_zone[0], field_name[1], default[1], & output_zone[1] are associated, and so on.
* If they are arrays, 'field_name', 'default', and 'output_zone' must always be the same length, with one exception -- 'output_zone' can be a single value if the output zone of each value in the 'field_name' array is the same.  So instead of "output_zone => ('sl_template', 'sl_template')", you can simply do "output_zone => 'sl_template' "
* Optional value 'numbers_only' follows the same rules as 'field_name' & 'default value'

== Notes: Using 'label' & 'input_template' values ==
* 'label' & 'input_template' values are guides in terms of formatting your new option, but are essentially just 2 containers in which you can place necessary labeling and HTML for form elements, so they can be interchanged, the label & HTML can all be in the 'label' value or 'input_template', etc -- helpful if grouping multiple values into one option
* However, the 'labels' input zone is unique to the other 3 input zones. The 'label' and 'input_template' values are displayed in columns of 3, with 'label' shown below 'input_template'.   The 'defaults', 'dimensions', and 'design' input zones are displayed in columns of 2, with the 'label' value displayed to the left of the 'input_template' value, thus grouping values into 1 option shouldn't be done if using an 'input_zone' of 'labels'

*/
/***************************************************/

###Defaults###
$sl_mdo[] = array("field_name" => "map_type", "default" => "google.maps.MapTypeId.ROADMAP", "input_zone" => "defaults", "output_zone" => "sl_dyn_js", "label" => __("Default Map Type", "store-locator"), "input_template" => "<select name='sl_map_type'>\n$map_type_options</select>");

$sl_mdo[] = array("field_name" => "num_initial_displayed", "default" => "500", "input_zone" => "defaults", "output_zone" => "sl_xml", "label" =>  __("Locations in Results", "store-locator"), "input_template" => "<input name='sl_num_initial_displayed' value='$sl_vars[num_initial_displayed]'>");

$sl_mdo[] = array("field_name" => "scripts_load", "default" => "selective", "input_zone" => "defaults", "output_zone" => "sl_head_scripts", "label" => __("JS & CSS Loading", "store-locator"), "input_template" => "<input name='sl_scripts_load' value='selective' type='radio' $checked_selective>Selective&nbsp;Loading&nbsp;&nbsp;<input name='sl_scripts_load' value='all' type='radio' $checked_all>All&nbsp;Pages", "more_info" => __("<h2 style='margin-top:0px'>JavaScript & Cascading Style Sheets Loading</h2><b>Selective Loading:</b><br>Attempts to detect where Store Locator JS & CSS scripts are needed and only loads them on those necessary pages. <br><br><b>All Pages:</b><br>Loads JS & CSS scripts on every page of your website.<br><br><div class='sl_code code'><b>Note:</b>&nbsp;\"Selective Loading\" will work for 99% of sites, however, if you experience map loading issues or missing CSS styling on your Store Locator or addon-generated pages, choose the \"All Pages\" option.</div>", "store-locator"), "more_info_label" => "info_js_css_load");

$sl_mdo[] = array("field_name" => array("scripts_load_home", "scripts_load_archives_404"), "default" => array("1", "1"), "field_type" =>"checkbox", "input_zone" => "defaults", "output_zone" => array("sl_head_scripts", "sl_head_scripts"), "label" => "", "input_template" => __("Also Load On", "store-locator")." .. <input name='sl_scripts_load_home' value='1' type='checkbox' {$checked_home}>&nbsp;".__("Home", "store-locator")."&nbsp;&nbsp;<input name='sl_scripts_load_archives_404' value='1' type='checkbox' {$checked_archives_404}>&nbsp;".__("Archives", "store-locator")." / 404", "row_id" => "scripts_load_selective_tr", "hide_row" => (isset($sl_vars['scripts_load']) && $sl_vars['scripts_load'] == "all") );

$sl_mdo[] = array("field_name" => array("use_city_search", "map_overview_control"), "default" => array("1", "0"), "field_type" =>"checkbox", "input_zone" => "defaults", "output_zone" => array("sl_template", "sl_dyn_js"), "label" => "<input name='sl_use_city_search' value='1' type='checkbox' $checked>&nbsp;".__("Search By City", "store-locator"), "input_template" => "<input name='sl_map_overview_control' value='1' type='checkbox' $checked5>&nbsp;".__("Show Map Inset Box", "store-locator"));

$sl_mdo[] = array("field_name" => array("geolocate", "load_locations_default", "load_results_with_locations_default"), "default" => array("0", "1", "1"), "field_type" => "checkbox", "input_zone" => "defaults", "output_zone" => array("sl_dyn_js", "sl_dyn_js", "sl_dyn_js"), "label" => "<input name='sl_geolocate' value='1' type='checkbox' $checked6>&nbsp;".__("Auto-Locate User", "store-locator"), "input_template" => "<input name='sl_load_locations_default' value='1' type='checkbox' $checked4>&nbsp;".__("Auto-Load Locations", "store-locator")."&nbsp;&nbsp;(<input name='sl_load_results_with_locations_default' value='1' type='checkbox' $checked7>&nbsp;&amp;&nbsp;".__("Listing", "store-locator")."&nbsp;(<a href='#info_load_results_default' rel='sl_pop'>?</a>)<div style='display:none;' id='info_load_results_default'>".__("<h2 style='margin-top:0px'>Search Results Listing By Default</h2>Determine whether or not both the map icons and the results listing show when loading locations by default. <br><Br>No results listings are shown even if this is checked, but 'Auto-Load Locations' is unchecked", "store-locator").".</div>)");

/*<!--tr><td>".__("Allow User Search By Name of Location?", "store-locator").":</td>
<td><input name='sl_use_name_search' value='1' type='checkbox' $checked2></td></tr-->
<!--/table-->*/
//$sl_vars['use_name_search']=($_POST['sl_use_name_search']==="")? 0 : $_POST['sl_use_name_search'];
###End Defaults###

###Labels###
$sl_mdo[] = array("field_name" => "search_label", "default" => "Address", "input_zone" => "labels", "output_zone" => "sl_template", "label" => __("Address Input", "store-locator"), "input_template" => "<input name='search_label' value=\"$sl_vars[search_label]\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "radius_label", "default" => "Radius", "input_zone" => "labels", "output_zone" => "sl_template", "label" => __("Radius Dropdown", "store-locator"), "input_template" => "<input name='sl_radius_label' value=\"$sl_vars[radius_label]\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "website_label", "default" => "Website", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("Website URL", "store-locator"), "input_template" => "<input name='sl_website_label' value=\"$sl_vars[website_label]\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "directions_label", "default" => "Directions", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("Directions URL", "store-locator"), "input_template" => "<input name='sl_directions_label' value=\"$sl_vars[directions_label]\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "map_link_label", "default" => "Map", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("Map Link URL", "store-locator"), "input_template" => "<input name='sl_map_link_label' value=\"$sl_vars[map_link_label]\" size='10'>", "stripslashes" => 1, "more_info"=>__("<h2 style='margin-top:0px'>Map Link Label</h2>This label shows for each location's Google Map link in the search results list if 'Auto-Load Locations' is on, but before a search is performed", "store-locator"), "more_info_label"=>"info_map_link_label"); //v3.88

$sl_mdo[] = array("field_name" => "instruction_message", "default" => "Enter Your Zip Code or Address Above.", "input_zone" => "labels", "output_zone" => "sl_template", "label" => __("Instruction to Users", "store-locator"), "input_template" => "<input name='sl_instruction_message' value=\"".$sl_vars['instruction_message']."\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "city_dropdown_label", "default" => "--Search By City--", "input_zone" => "labels", "output_zone" => "sl_template", "label" => __("City Dropdown", "store-locator"), "input_template" => "<input name='sl_city_dropdown_label' value=\"".$sl_vars['city_dropdown_label']."\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "location_not_found_message", "default" => "", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("Location Doesn't Exist", "store-locator"), "input_template" => "<input name='sl_location_not_found_message' value=\"".$sl_vars['location_not_found_message']."\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "no_results_found_message", "default" => "No Results Found", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("No Results Are Found", "store-locator"), "input_template" => "<input name='sl_no_results_found_message' value=\"".$sl_vars['no_results_found_message']."\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "hours_label", "default" => "Hours", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("Hours", "store-locator"), "input_template" => "<input name='sl_hours_label' value=\"".$sl_vars['hours_label']."\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "phone_label", "default" => "Phone", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("Phone", "store-locator"), "input_template" => "<input name='sl_phone_label' value=\"".$sl_vars['phone_label']."\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "fax_label", "default" => "Fax", "input_zone" => "labels", "output_zone" => "sl_dyn_js", "label" => __("Fax", "store-locator"), "input_template" => "<input name='sl_fax_label' value=\"".$sl_vars['fax_label']."\" size='10'>", "stripslashes" => 1);

$sl_mdo[] = array("field_name" => "email_label", "default" => "Email", "input_zone" => "labels", "output_zone" => "sl_dyn_js",  "label" => __("Email", "store-locator"), "input_template" => "<input name='sl_email_label' value=\"".$sl_vars['email_label']."\" size='10'>", "stripslashes" => 1);
###End Labels###

###Dimensions###
$sl_mdo[] = array("field_name" => "zoom_level", "default" => "4", "input_zone" => "dimensions", "output_zone" => "sl_dyn_js", "label" => "<nobr>".__("Zoom Level", "store-locator")."</nobr>", "input_template" => $zoom);

$sl_mdo[] = array("field_name" => array("height", "width", "height_units", "width_units"), "default" => array("350", "100", "px", "%"), "input_zone" => "dimensions", "output_zone" => "sl_template", "label" => "<nobr>".__("Map Dimensions (H x W)", "store-locator")."</nobr>", "input_template" => "<input name='height' value='$sl_vars[height]' size='1'>&nbsp;".sl_choose_units($sl_vars['height_units'], "height_units")." <span style='font-size:1.2em; vertical-align:middle'>X</span> <input name='width' value='$sl_vars[width]' size='1'>&nbsp;".sl_choose_units($sl_vars['width_units'], "width_units", ""), "numbers_only" => array(1, 1, 0, 0)
);

$the_distance_unit["".__("Km", "store-locator").""]="km";
$the_distance_unit["".__("Miles", "store-locator").""]="miles";
$radii_select = "";
foreach ($the_distance_unit as $key=>$value) {
	$selected = ($sl_vars['distance_unit']==$value)?" selected " : "";
	$radii_select .= "<option value='$value' $selected>$key</option>\n";
}
$sl_mdo[] = array("field_name" => array("distance_unit", "radii"),  "default" => array("miles", "1,5,10,25,(50),100,200,500"), "input_zone" => "dimensions", "output_zone" => array("sl_dyn_js", "sl_template"), "label" => "<nobr>".__("Radii Options", "store-locator")." (".__("in", "store-locator")." <select name='sl_distance_unit'>$radii_select</select>) </nobr>", "input_template" => "<input  name='radii' value='$sl_vars[radii]' size='15'><br><span style='font-size:80%'>(".__("Parentheses '( )' are for the default radius</span>", "store-locator").")");
###End Dimensions###

###Design###
$sl_mdo[] = array("field_name" => "theme", "default" =>"", "input_zone" => "design", "output_zone" => "sl_template", "label" => __("Choose Theme", "store-locator"), "input_template" => "<select name='theme' onchange=\"\"><option value=''>".__("No Theme Selected", "store-locator")."</option>$theme_str</select>&nbsp;&nbsp;&nbsp;<a href='http://www.viadat.com/products-page/store-locator-themes/' target='_blank'>".__("Get&nbsp;Themes", "store-locator")." &raquo;</a>");

$sl_mdo[] = array("field_name" => "remove_credits", "default" =>"0", "field_type" =>"checkbox", "input_zone" => "design", "output_zone" => "sl_template", "label" => __("Remove Credits", "store-locator"), "input_template" => "<input name='sl_remove_credits' value='1' type='checkbox' $checked3>");

$sl_mdo[] = array("field_name" => array("icon", "icon2"), "default" => array(SL_ICONS_BASE."/droplet_green.png", SL_ICONS_BASE."/droplet_red.png"), "input_zone" => "design", "output_zone" => array("sl_dyn_js", "sl_dyn_js"), "label" => "<input name='icon' size='10' value='$sl_vars[icon]' onchange=\"document.getElementById('prev').src=this.value\"><img id='prev' src='$sl_vars[icon]' align='top' rel='sl_pop' href='#home_icon' style='cursor:pointer;cursor:hand;height:60%;'> <br><a rel='sl_pop' href='#home_icon'><span style='font-size:80%'>".__("Choose", "store-locator")." ".__("Home Icon", "store-locator")."</span></a><div id='home_icon' style='display:none;'><h2 style='margin-top:0px'>".__("Choose", "store-locator")." ".__("Home Icon", "store-locator")."</h2>$icon_str</div>", "input_template" => "<input name='icon2' size='10' value='$sl_vars[icon2]' onchange=\"document.getElementById('prev2').src=this.value\"><img id='prev2' src='$sl_vars[icon2]' align='top' rel='sl_pop' href='#end_icon' style='cursor:pointer;cursor:hand;height:60%;'> <br><div id='end_icon' style='display:none;'><h2 style='margin-top:0px'>".__("Choose", "store-locator")." ".__("Destination Icon", "store-locator")."</h2>$icon2_str</div><a rel='sl_pop' href='#end_icon'><span style='font-size:80%'>".__("Choose", "store-locator")." ".__("Destination Icon", "store-locator")." </span></a>");


###End Design###

/*
$sl_mdo[] = array("input_zone" => "defaults", "label" => "Locations in Results", "input_template" => '
<input name=\'sl_num_initial_displayed\' value=\'' . $sl_vars['num_initial_displayed'] . '\'>
'
);*/

//if (function_exists("do_sl_hook") && defined("SL_AP_VERSION") && version_compare(SL_AP_VERSION, 1.4) > 0 ){
if (function_exists("do_sl_hook") && empty($_GET['via_platform'])) { //v3.75 - probably easiest way to prevent it from interfering with remote installs // v3.75.1 - don't forget "function_exists("do_sl_hook")"!
	do_sl_hook('sl_mapdesigner_options', "", "", "", "", FALSE); //, '', array(&$sl_mdo)); 
} //- removed v3.70 - reassess - add: defined('SL_IN_..MODE') checks to AP


$sl_mdo[] = array("input_zone" => "design", "label" => "<div class=''><b>".__("For more unique icons, visit", "store-locator")." <a href='http://code.google.com/p/google-maps-icons/' target='_blank'>Map Icons Collection</a>, <a href='https://www.iconfinder.com/search/?q=map&price=free' target='_blank'>Iconfinder</a>, & <a href='http://www.iconarchive.com/search?q=map' target='_blank'>IconArchive</a></b></div>", "input_template" => "", "colspan" => 2);
$sl_j_flag=0;
if (empty($sl_vars['jfxn_ver']) || version_compare($sl_vars['jfxn_ver'], SL_VERSION) != 0) {
	$sl_vars['jfxn_ver'] = SL_VERSION; $sl_j_flag=1;
	$sl_vars['jfxn'] = '
var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function encode64(input) {
  var output = "";
  var chr1, chr2, chr3;
  var enc1, enc2, enc3, enc4;
  var i = 0;
  do {
    chr1 = input.charCodeAt(i++);
    chr2 = input.charCodeAt(i++);
    chr3 = input.charCodeAt(i++);
    enc1 = chr1 >> 2;
    enc2 = (chr1 & 3) << 4 | chr2 >> 4;
    enc3 = (chr2 & 15) << 2 | chr3 >> 6;
    enc4 = chr3 & 63;
    if (isNaN(chr2)) {
      enc3 = enc4 = 64;
    } else {
      if (isNaN(chr3)) {
        enc4 = 64;
      }
    }
    output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4);
  } while (i < input.length);
  return output;
}
function decode64(input) {
  var output = "";
  var chr1, chr2, chr3;
  var enc1, enc2, enc3, enc4;
  var i = 0;
  input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
  do {
    enc1 = keyStr.indexOf(input.charAt(i++));
    enc2 = keyStr.indexOf(input.charAt(i++));
    enc3 = keyStr.indexOf(input.charAt(i++));
    enc4 = keyStr.indexOf(input.charAt(i++));
    chr1 = enc1 << 2 | enc2 >> 4;
    chr2 = (enc2 & 15) << 4 | enc3 >> 2;
    chr3 = (enc3 & 3) << 6 | enc4;
    output = output + String.fromCharCode(chr1);
    if (enc3 != 64) {
      output = output + String.fromCharCode(chr2);
    }
    if (enc4 != 64) {
      output = output + String.fromCharCode(chr3);
    }
  } while (i < input.length);
  return output;
}
function anim2(imgObj, url) {
  imgObj.src = url;
}
function anim(name, type) {
  if (type == 0) {
    document.images[name].src = "/images/" + name + ".gif";
  }
  if (type == 1) {
    document.images[name].src = "/images/" + name + "_over.gif";
  }
  if (type == 2) {
    document.images[name].src = "/images/" + name + "_down.gif";
  }
}
function checkAll(cbox, formObj) {
  var i = 0;
  if (cbox.checked == true) {
    cbox.checked == false;
  } else {
    cbox.checked == true;
  }
  while (formObj.elements[i] != null) {
    formObj.elements[i].checked = cbox.checked;
    i++;
  }
}
function checkEvent(formObj) {
  var key = -1;
  var shift;
  key = event.keyCode;
  shift = event.shiftKey;
  if (!shift && key == 13) {
    formObj.submit();
  }
}
function show(block) {
  theBlock = document.getElementById(block);
  if (theBlock.style.display == "none") {
    theBlock.style.display = "block";
  } else {
    theBlock.style.display = "none";
  }
}
function confirmClick(message, href) {
  if (confirm(message)) {
    location.href = href;
  } else {
    return false;
  }
}
function showLoadImg(cmd, img_id) {
  loadImg = document.getElementById(img_id);
  if (cmd == "show") {
    loadImg.style.opacity = 1;
    loadImg.style.filter = "alpha(opacity=100)";
  } else {
    loadImg.style.opacity = 0;
    loadImg.style.filter = "alpha(opacity=0)";
  }
}
if (typeof jQuery != "undefined") {
  var tk_twitter_pop = function(text, event) {
    window.open("http://twitter.com/intent/tweet?text=" + text, "", "width=400px,height=550px,left=" + (event.pageX - 300) + "px,top=" + (event.pageY - 300) + "px");
  };
  var validate_addons = function(formObj) {
    var e = formObj.elements;
    var val_arr = "";
    for (x in e) {
      if (typeof e[x].value != "undefined" && e[x].value != "") {
        val_arr += "&" + e[x].name + "=" + e[x].value;
      }
    }
    jQuery.get(sl_siteurl + "/?sl_engine=sl-inc_includes_update-keys" + val_arr, function(data) { /*updated: v3.98.3 | 12/4/18 4:05p*/
      jQuery("#validation_status div").html(data);
      jQuery("#validation_status_link").click();
      showLoadImg("stop", "module-keys");
      setTimeout(function() {
        location.reload();
      }, 1E4);
    });
  };
  var level3_links = function(sublink_obj, mainlink_id, show) {
    if (typeof tsn_link_arr[sublink_obj.id] != "undefined") {
      l3n = document.getElementById("level3_nav");
      i_d_width = document.getElementById("inner_div").offsetWidth;
      if (show == "show") {
        jQuery("#level3_nav").hover(function() {
          l3n.style.position = "absolute";
          l3n.style.width = ((i_d_width < 200)? 200 : i_d_width) + "px";
          l3n.style.left = document.getElementById(mainlink_id).offsetLeft + "px";
          l3n.style.top = document.getElementById(mainlink_id).offsetTop + document.getElementById(mainlink_id).offsetHeight + document.getElementById("top_sub_nav").offsetHeight - 0 + "px";
          l3n.style.visibility = "visible";
          jQuery("#level3_nav").html(tsn_link_arr[sublink_obj.id]).fadeIn();
        }, function() {
          jQuery("#level3_nav").html(tsn_link_arr[sublink_obj.id]).fadeOut();
        });
        l3n.style.position = "absolute";
        l3n.style.width = ((i_d_width < 200)? 200 : i_d_width) + "px";
        l3n.style.left = document.getElementById(mainlink_id).offsetLeft + "px";
        l3n.style.top = document.getElementById(mainlink_id).offsetTop + document.getElementById(mainlink_id).offsetHeight + document.getElementById("top_sub_nav").offsetHeight - 0 + "px";
        l3n.style.visibility = "visible";
        jQuery("#level3_nav").html(tsn_link_arr[sublink_obj.id]).fadeIn();
      } else {
      }
    }
  };
  var sl_top_nav = function(sl_admin_page, tab_obj, link_array, the_action) {
    if (link_array[sl_admin_page] != "") {
      tsnfC = document.getElementById("top_sub_nav").firstChild;
      tsnfC.style.visibility = "hidden";
      if (the_action == "show") {
        jQuery("#top_sub_nav div").hover(function() {
          tsnfC.style.position = "absolute";
          tsnfC.style.left = tab_obj.offsetLeft + "px";
          tsnfC.style.visibility = "visible";
          tsnfC.innerHTML = link_array[sl_admin_page];
        }, function() {
        });
        tsnfC.style.position = "absolute";
        tsnfC.style.left = tab_obj.offsetLeft + "px";
        tsnfC.style.visibility = "visible";
        tsnfC.innerHTML = link_array[sl_admin_page];
      }
    }
  };
  var sl_top_nav_init = function(link_arr) {
    if (typeof document.getElementById("top_sub_nav") != "undefined") {
      tsnfC = document.getElementById("top_sub_nav").firstChild;
      tsnfC.style.visibility = "hidden";
      tsnfC.style.position = "absolute";
      tsnfC.style.left = document.getElementById("current_top_link").offsetLeft + "px";
      tsnfC.style.visibility = "visible";
    }
    $tnl = jQuery(".top_nav_li");
    jQuery.each($tnl, function($key, $val) {
      $val.onmouseover = function() {
        sl_top_nav($val.className.replace("top_nav_li ", ""), this, link_arr, "show");
      };
    });
  };
  jQuery(document).ready(function() {
    if (typeof tsn_link_arr != "undefined") {
      sl_top_nav_init(tsn_link_arr);
    }
  });
  jQuery(document).ready(function() {
    if (jQuery("#clickme").length > 0) {
      jQuery("#clickme").click(function() {
        if (jQuery("#slidecontent").width() == 0 || jQuery("#slidecontent").css("display") == "none") {
           jQuery("#slideout").animate({width:"+=880px", height:"+=630px"}, {queue:false, duration:500});
           jQuery("#slidecontent").animate({width:"+=880px"}, {queue:false, duration:500, complete:function() {
             jQuery("#slidecontent div").fadeIn();
           }});
         } else {
           jQuery("#slidecontent div").fadeOut(function() {
             jQuery("#slideout").animate({width:"-=880px", height:"-=630px"}, {queue:false, duration:500});
             jQuery("#slidecontent").animate({width:"-=880px"}, {queue:false, duration:500});
           });
         }
      });
    }
  });
  jQuery(document).ready(function() {
    if (jQuery("#slidecontainer").length > 0) {
      orig_html = document.getElementById("slidecontainer").innerHTML;
      jQuery(document).on("click", "#readme_button", function() {
        jQuery.get(sl_siteurl + "/?sl_engine=sl-admin_pages_readme", function(readme_data) { /*updated: v3.98.3 | 12/4/18 4:07p | prev: 5/6/17 5:40:35p*/
          jQuery("#slidecontainer").fadeOut(function() {
            jQuery(this).html("<div style=\'overflow:scroll; height:616px; padding:7px; background-color:white; border:solid silver 1px\'><a href=\'#\' class=\'sl_back_to_dashboard\'><b>&larr;&nbsp;Back to Dashboard</b></a><br>" + readme_data + "<br><a href=\'#\' class=\'sl_back_to_dashboard\'><b>&larr;&nbsp;Back to Dashboard</b></a></div>").fadeIn();
          });
        });
      });
      jQuery(document).on("click", ".sl_back_to_dashboard", function() {
        jQuery("#slidecontainer").fadeOut(function() {
          jQuery(this).html(orig_html).fadeIn();
        });
      });
      jQuery(document).on("click", "#server_caps_button", function() {
        jQuery.prettyPhoto.open("#server_caps");
      });
      jQuery(document).on("click", "#shortcode_params_button", function() {
        jQuery.prettyPhoto.open("#shortcode_params");
      });
      jQuery(document).on("click", "#env_vars_button", function() {
        jQuery.prettyPhoto.open("#env_vars");
      });
    }
  });
  if (typeof jQuery.prettyPhoto != "undefined") {
    jQuery(document).ready(function($) {
      $("a[href$=\'.jpg\'], a[href$=\'.jpeg\'], a[href$=\'.gif\'], a[href$=\'.png\'], a[rel^=\'sl_pop\'], input[rel^=\'sl_pop\'], img[rel^=\'sl_pop\']").prettyPhoto({animationSpeed:"fast", padding:40, opacity:.5, showTitle:true, deeplinking:false, social_tools:false});
    });
  }
  jQuery(document).ready(function() {
    jQuery(document).on("click", ".twitter_button", function(e) {
      tk_twitter_pop(jQuery(this).attr("rel"), e);
    });
    jQuery(document).on("click", ".star_button", function() {
      window.open("http://wordpress.org/support/view/plugin-reviews/store-locator?filter=5#postform");
    });
    jQuery(\'#loc_table tr[id^="sl_tr-"]\').mousedown(function(e) {
      $this = jQuery(this);
      $curr_id = $this.attr("id");
      $loc_id = $curr_id.split("-")[1];
      $curr_cbx = jQuery("#" + $curr_id + " input[type=\'checkbox\']");
      if (e.target == jQuery("#edit_loc-" + $loc_id)[0] || e.target == jQuery("#del_loc-" + $loc_id)[0]) {
      } else {
        if (e.target != $curr_cbx[0]) {
          $this.toggleClass("location_selected");
          $curr_cbx[0].checked = $this.hasClass("location_selected") ? true : false;
        } else {
          if ($curr_cbx[0].checked == false) {
            $this.addClass("location_selected");
          } else {
            $this.removeClass("location_selected");
          }
        }
      }
    });
    jQuery(document).keyup(function(e) {
      $act_elem = jQuery(":focus");
      text_field_is_focused = $act_elem.attr("name") == jQuery("#mgmt_bar input:text").attr("name") || $act_elem.attr("name") == jQuery(".mng_loc_forms_links input:text").attr("name");
      if (e.keyCode == 68 && jQuery(\'input[name="sl_id[]"]:checked\').length > 0 && location.search.indexOf("edit=") == -1 && !text_field_is_focused) {
        jQuery("#mgmt_bar input[type=\'button\']")[0].click();
 }});
  });
};
'; #<- v3.98.5 - heredoc to single quotes
	$sl_vars['jfxn'] = preg_replace("@\n|\r|\t|[[:space:]]{2,}@s", "", $sl_vars['jfxn']);
}
if (empty($sl_vars['jsl_ver']) || version_compare($sl_vars['jsl_ver'], SL_VERSION) != 0) {
	$sl_vars['jsl_ver'] = SL_VERSION; $sl_j_flag=1;
	$sl_vars['jsl'] = '
var sl_map;
var sl_geocoder;
var sl_info_window;
var sl_marker_array = [];
var sl_marker_type;
var sl_geo_flag = 0;
if (!is_array(sl_categorization_array)) {
  var sl_categorization_array = []
}
var sl_marker_categorization_field = is_array(sl_categorization_array) && sl_categorization_array.length > 0 ? sl_categorization_array[0] : "type";
var sl_ccTLD = sl_google_map_domain.match(/\.([^\.]+)$/);
var sl_ccTLD_not_set = typeof sl_ccTLD[1] == "undefined" || sl_ccTLD[1] == "null" || sl_ccTLD[1] == "";
if (sl_ccTLD_not_set || sl_ccTLD[1] == "com") {
  var sl_ccTLD = sl_ccTLD_not_set || sl_google_map_domain.indexOf("ditu") == -1 ? "us" : "cn"
} else {
  var sl_ccTLD = sl_ccTLD[1]
}
var sl_mvc_instances = [];
if (typeof sl_map_params != "undefined") {
  sl_map_params = sl_map_params.split("=").join("[]=");
}
if (!function_exists("sl_details_filter")) {
  var sl_details_filter = function(sl_details) {
    return sl_details;
  }
}
if (window.location.host.indexOf("www.") != -1 && sl_base.indexOf("www.") == -1) {
  sl_base = sl_base.split("http://").join("http://www.");
} else {
  if (window.location.host.indexOf("www.") == -1 && sl_base.indexOf("www.") != -1) {
    sl_base = sl_base.split("http://www.").join("http://");
  }
}
function sl_load() {
  map_type_check();
  sl_geocoder = new google.maps.Geocoder;
  if ("undefined" != typeof document.getElementById("sl_map") && null != document.getElementById("sl_map")) {
    sl_map = new google.maps.Map(document.getElementById("sl_map"));
    if (typeof sl_infowindow_type != "undefined") {
      sl_info_window = new sl_infowindow_type;
    } else {
      sl_info_window = new google.maps.InfoWindow;
    }
    if (function_exists("start_sl_load")) {
      start_sl_load();
    }
    if (sl_geolocate == "1") {
      showLoadImg("show", "loadImg");
      try {
        if (typeof navigator.geolocation === "undefined") {
          ng = google.gears.factory.create("beta.geolocation");
        } else {
          ng = navigator.geolocation;
        }
      } catch (e) {
      }
      if (ng) {
        if (sl_geo_flag != 1) {
          do_load_options();
        }
        ng.getCurrentPosition(sl_geo_success, sl_geo_error);
      } else {
        do_load_options();
      }
    } else {
      do_load_options();
    }
    if (function_exists("end_sl_load")) {
      google.maps.event.addDomListenerOnce(sl_map, "tilesloaded", end_sl_load);
    }
  }
}
google.maps.event.addDomListener(window, "load", sl_load);
function sl_geo_success(point) {
  sl_geo_flag = 1;
  var the_coords = new google.maps.LatLng(point.coords.latitude, point.coords.longitude);
  sl_geocoder.geocode({"location":the_coords}, function(results) {
    aI = document.getElementById("addressInput");
    aI.value = results[0].formatted_address;
    searchLocationsNear(the_coords, aI.value);
  });
}
function sl_geo_error(err) {
  sl_geo_flag = 1;
  do_load_options();
}
function do_load_options() {
  if (sl_load_locations_default == "0") {
    sl_geocoder.geocode({"address":sl_google_map_country}, function(results, status) {
      var myOptions = {center:results[0].geometry.location, zoom:sl_zoom_level, mapTypeId:sl_map_type_v3, overviewMapControl:sl_map_overview_control, disableDefaultUI:false, mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}};
      if (typeof sl_map_options != "undefined" && typeof sl_map_options === "object") {
        myOptions = mergeArray(sl_map_options, myOptions);
      }
      sl_map.setOptions(myOptions);
    });
  }
  if (sl_load_locations_default == "1") {
    var bounds = new google.maps.LatLngBounds;
    var searchUrl = sl_siteurl + "/?sl_engine=sl-xml";
    if (typeof sl_map_params != "undefined") {
      searchUrl += "&" + sl_map_params;
    }
    retrieveData(searchUrl, function(data) {
      var xml = data.responseXML;
      var markerNodes = xml.documentElement.getElementsByTagName("marker");
      var sidebar = document.getElementById("map_sidebar");
      sidebar.innerHTML = "";
      for (var i = 0;i < markerNodes.length;i++) {
        var sl_details = buildDetails(markerNodes[i]);
        sl_marker_type = markerNodes[i].getAttribute(sl_marker_categorization_field);
        if (sl_marker_type == "" || sl_marker_type == null) {
          sl_marker_type = "sl_map_end_icon";
        }
        var icon = typeof sl_icons != "undefined" && typeof sl_icons[sl_marker_type] != "undefined" ? sl_icons[sl_marker_type] : {url:sl_map_end_icon, name:"Default"};
        var marker = createMarker(sl_details, sl_marker_type, icon);
        if (sl_load_results_with_locations_default == "1") {
          var sidebarEntry = createSidebarEntry(marker, sl_details);
          sidebarEntry.id = "sidebar_div_" + i;
          sidebar.appendChild(sidebarEntry);
        }
        bounds.extend(sl_details["point"]);
      }
      if (markerNodes.length == 0) {
        sl_geocoder.geocode({"address":sl_google_map_country}, function(results, status) {
          var myOptions = {center:results[0].geometry.location, zoom:sl_zoom_level, mapTypeId:sl_map_type_v3, overviewMapControl:sl_map_overview_control, disableDefaultUI:false, mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}};
          if (typeof sl_map_options != "undefined" && typeof sl_map_options === "object") {
            myOptions = mergeArray(sl_map_options, myOptions);
          }
          if (typeof sl_mvc_objects != "undefined" && typeof sl_mvc_objects["type"] != "undefined" && typeof sl_mvc_objects["options"] != "undefined") {
            for (mvc in sl_mvc_objects["type"]) {
              sl_mvc_instances[mvc] = new sl_mvc_objects["type"][mvc](sl_mvc_objects["options"][mvc]);
              sl_mvc_instances[mvc].setMap(sl_map);
            }
          }
          sl_map.setOptions(myOptions);
        });
      } else {
        var myOptions = {center:bounds.getCenter(), mapTypeId:sl_map_type_v3, overviewMapControl:sl_map_overview_control, disableDefaultUI:false, mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}};
        if (typeof sl_map_options != "undefined" && typeof sl_map_options === "object") {
          myOptions = mergeArray(sl_map_options, myOptions);
        }
        if (typeof sl_mvc_objects != "undefined" && typeof sl_mvc_objects["type"] != "undefined" && typeof sl_mvc_objects["options"] != "undefined") {
          for (mvc in sl_mvc_objects["type"]) {
            sl_mvc_instances[mvc] = new sl_mvc_objects["type"][mvc](sl_mvc_objects["options"][mvc]);
            sl_mvc_instances[mvc].setMap(sl_map);
          }
        }
        sl_map.setOptions(myOptions);
        sl_map.fitBounds(bounds);
      }
      if (sl_map.getZoom() > 16) {
        sl_map.setZoom(9);
      }
    });
  }
  showLoadImg("stop", "loadImg");
}
function searchLocations() {
  if (function_exists("start_searchLocations")) {
    start_searchLocations();
  }
  var address = document.getElementById("addressInput").value;
  sl_geocoder.geocode({"address":address, "region":sl_ccTLD}, function(results, status) {
    if (status != google.maps.GeocoderStatus.OK) {
      showLoadImg("stop", "loadImg");
      if (sl_location_not_found_message.split(" ").join("") != "") {
        alert(sl_location_not_found_message);
      } else {
        alert(address + " Not Found");
      }
    } else {
      searchLocationsNear(results[0].geometry.location, address);
    }
  });
  if (function_exists("end_searchLocations")) {
    end_searchLocations();
  }
}
function searchLocationsNear(center, homeAddress) {
  if (function_exists("start_searchLocationsNear")) {
    start_searchLocationsNear();
  }
  var radius = document.getElementById("radiusSelect").value;
  var searchUrl = sl_siteurl + "/?sl_engine=sl-xml&mode=gen&lat=" + center.lat() + "&lng=" + center.lng() + "&radius=" + radius;
  if (typeof sl_map_params != "undefined") {
    searchUrl += sl_map_params;
  }
  retrieveData(searchUrl, function(data) {
    var xml = data.responseXML;
    var markerNodes = xml.documentElement.getElementsByTagName("marker");
    clearLocations();
    var bounds = new google.maps.LatLngBounds;
    var point = new google.maps.LatLng(center.lat(), center.lng());
    var markerOpts = {map:sl_map, position:point, icon:sl_map_home_icon};
    if (typeof sl_marker_options != "undefined" && typeof sl_marker_options === "object") {
      markerOpts = mergeArray(sl_marker_options, markerOpts);
    }
    var icon = {url:sl_map_home_icon};
    bounds.extend(point);
    var homeMarker = new google.maps.Marker(markerOpts);
    determineShadow(icon, homeMarker);
    var html = \'<div id="sl_info_bubble"><span class="your_location_label">Your Location:</span> <br/>\' + homeAddress + "</div>";
    bindInfoWindow(homeMarker, sl_map, sl_info_window, html);
    var sidebar = document.getElementById("map_sidebar");
    sidebar.innerHTML = "";
    if (markerNodes.length == 0) {
      showLoadImg("stop", "loadImg");
      sidebar.innerHTML = \'<div class="text_below_map">\' + sl_no_results_found_message + "</div>";
      sl_marker_array.push(homeMarker);
      sl_map.setCenter(point);
      return;
    }
    for (var i = 0;i < markerNodes.length;i++) {
      var sl_details = buildDetails(markerNodes[i]);
      sl_marker_type = markerNodes[i].getAttribute(sl_marker_categorization_field);
      if (sl_marker_type == "" || sl_marker_type == null) {
        sl_marker_type = "sl_map_end_icon";
      }
      var icon = typeof sl_icons != "undefined" && typeof sl_icons[sl_marker_type] != "undefined" ? sl_icons[sl_marker_type] : {url:sl_map_end_icon, name:"Default"};
      var marker = createMarker(sl_details, sl_marker_type, icon);
      var sidebarEntry = createSidebarEntry(marker, sl_details);
      sidebarEntry.id = "sidebar_div_" + i;
      sidebar.appendChild(sidebarEntry);
      bounds.extend(sl_details["point"]);
    }
    sl_marker_array.push(homeMarker);
    sl_map.setCenter(bounds.getCenter());
    sl_map.fitBounds(bounds);
    showLoadImg("stop", "loadImg");
  });
  if (function_exists("end_searchLocationsNear")) {
    end_searchLocationsNear();
  }
}
function createMarker(sl_details, type, icon) {
  var markerOpts = {map:sl_map, position:sl_details["point"], icon:icon.url};
  if (typeof sl_marker_options != "undefined" && typeof sl_marker_options === "object") {
    markerOpts = mergeArray(sl_marker_options, markerOpts);
  }
  var marker = new google.maps.Marker(markerOpts);
  determineShadow(icon, marker);
  if (function_exists("start_createMarker")) {
    start_createMarker();
  }
  html = buildMarkerHTML(sl_details);
  bindInfoWindow(marker, sl_map, sl_info_window, html);
  sl_marker_array.push(marker);
  if (function_exists("end_createMarker")) {
    end_createMarker();
  }
  return marker;
}
var resultsDisplayed = 0;
var bgcol = "white";
function createSidebarEntry(marker, sl_details) {
  if (function_exists("start_createSidebarEntry")) {
    start_createSidebarEntry();
  }
  if (document.getElementById("map_sidebar_td") != null) {
    document.getElementById("map_sidebar_td").style.display = "block";
  }
  var div = document.createElement("div");
  var html = buildSidebarHTML(sl_details);
  div.innerHTML = html;
  div.className = "results_entry";
  div.setAttribute("name", "results_entry");
  resultsDisplayed++;
  google.maps.event.addDomListener(div, "click", function() {
    google.maps.event.trigger(marker, "click");
  });
  if (function_exists("end_createSidebarEntry")) {
    end_createSidebarEntry();
  }
  return div;
}
function retrieveData(url, callback) {
  var request = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest;
  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
      if (function_exists("end_retrieveData")) {
        end_retrieveData();
      }
    }
  };
  request.open("GET", url, true);
  request.send(null);
}
function doNothing() {
}
function bindInfoWindow(marker, map, infoWindow, html) {
  var infowindow_click_function = function() {
    infoWindow.close();
    infoWindow.setContent(html);
    if (typeof sl_infowindow_options != "undefined" && typeof sl_infowindow_options === "object") {
      infoWindow.setOptions(sl_infowindow_options);
    }
    infoWindow.open(map, marker);
  };
  google.maps.event.addListener(marker, "click", infowindow_click_function);
  google.maps.event.addListener(marker, "visible_changed", function() {
    infoWindow.close();
  });
}
function clearLocations() {
  sl_info_window.close();
  for (var i = 0;i < sl_marker_array.length;i++) {
    sl_marker_array[i].setMap(null);
  }
  sl_marker_array.length = 0;
}
function determineShadow(icon, marker) {
  if (icon.url.indexOf("flag") != "-1") {
    marker.setShadow(sl_base + "/icons/flag_shadow_v3.png");
  } else {
    if (icon.url.indexOf("arrow") != "-1") {
      marker.setShadow(sl_base + "/icons/arrow_shadow_v3.png");
    } else {
      if (icon.url.indexOf("bubble") != "-1") {
        marker.setShadow(sl_base + "/icons/bubble_shadow_v3.png");
      } else {
        if (icon.url.indexOf("marker") != "-1") {
          marker.setShadow(sl_base + "/icons/marker_shadow_v3.png");
        } else {
          if (icon.url.indexOf("sign") != "-1") {
            marker.setShadow(sl_base + "/icons/sign_shadow_v3.png");
          } else {
            if (icon.url.indexOf("droplet") != "-1") {
              marker.setShadow(sl_base + "/icons/droplet_shadow_v3.png");
            } else {
              marker.setShadow(sl_base + "/icons/blank.png");
            }
          }
        }
      }
    }
  }
}
function map_type_check() {
  if (sl_map_type == "G_NORMAL_MAP") {
    sl_map_type_v3 = google.maps.MapTypeId.ROADMAP;
  } else {
    if (sl_map_type == "G_SATELLITE_MAP") {
      sl_map_type_v3 = google.maps.MapTypeId.SATELLITE;
    } else {
      if (sl_map_type == "G_HYBRID_MAP") {
        sl_map_type_v3 = google.maps.MapTypeId.HYBRID;
      } else {
        if (sl_map_type == "G_PHYSICAL_MAP") {
          sl_map_type_v3 = google.maps.MapTypeId.TERRAIN;
        } else {
          if (sl_map_type != google.maps.MapTypeId.ROADMAP && sl_map_type != google.maps.MapTypeId.SATELLITE && sl_map_type != google.maps.MapTypeId.HYBRID && sl_map_type != google.maps.MapTypeId.TERRAIN) {
            sl_map_type_v3 = google.maps.MapTypeId.ROADMAP;
          } else {
            sl_map_type_v3 = sl_map_type;
          }
        }
      }
    }
  }
}
function function_exists(func) {
  return eval("typeof window." + func + " === \'function\'");
}
function is_array(arr) {
  return eval(typeof arr === "object" && arr instanceof Array);
}
function empty(value) {
  return eval(typeof value === "undefined");
}
function isset(value) {
  return eval(typeof value !== "undefined");
}
function mergeArray(array1, array2) {
  for (item in array1) {
    array2[item] = array1[item];
  }
  return array2;
}
function determineDirectionsLink(sl_details, html) {
  var homeAddress = sl_details["homeAddress"];
  if (homeAddress.split(" ").join("") != "") {
    html = html.split("sl_details[\'sl_directions_link\']").join("\'<a href=\"http://" + sl_google_map_domain + "/maps?saddr=" + encodeURIComponent(homeAddress) + "&daddr=" + encodeURIComponent(sl_details["fullAddress"]) + \'" target="_blank" class="storelocatorlink">\' + sl_directions_label + "</a>\'");
  } else {
    html = html.split("sl_details[\'sl_directions_link\']").join("\'<a href=\"http://" + sl_google_map_domain + "/maps?q=" + encodeURIComponent(sl_details["fullAddress"]) + \'" target="_blank" class="storelocatorlink">\' + sl_map_link_label + "</a>\'");
  }
  return html;
}
function sl_nl2br(str, is_xhtml) {
  var breakTag = is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
  return str.replace(/\|\|sl-nl\|\|/g, breakTag);
}
if (!function_exists("buildSidebarHTML")) {
  var buildSidebarHTML = function(sl_details) {
    var street = sl_details["sl_address"];
    if (street.split(" ").join("") != "") {
      street += "<br/>";
    } else {
      street = "";
    }
    if (sl_details["sl_address2"].split(" ").join("") != "") {
      street += sl_details["sl_address2"] + "<br/>";
    }
    var city = sl_details["sl_city"];
    if (city.split(" ").join("") != "") {
      city += ", ";
    } else {
      city = "";
    }
    var state_zip = sl_details["sl_state"] + " " + sl_details["sl_zip"];
    if (sl_details["fullAddress"].split(",").join("").split(" ").join("") == "") {
      sl_details["fullAddress"] = sl_details["sl_latitude"] + "," + sl_details["sl_longitude"];
    }
    var homeAddress = sl_details["homeAddress"];
    var name = sl_details["sl_store"];
    var distance = sl_details["sl_distance"];
    var url = sl_details["sl_url"];
    if (url.search(/^https?\:\/\//i) != -1 && url.indexOf(".") != -1) {
      link = "&nbsp;|&nbsp;<a href=\'" + url + "\' target=\'_blank\' class=\'storelocatorlink\'><nobr>" + sl_website_label + "</nobr></a>";
    } else {
      url = "";
      link = "";
    }
    sl_details["sl_distance_unit"] = sl_distance_unit;
    sl_details["sl_google_map_domain"] = sl_google_map_domain;
    if (function_exists("sl_results_template") && sl_results_template(sl_details)) {
      var html = decode64(sl_results_template(sl_details));
      html = determineDirectionsLink(sl_details, html);
      html = eval("\'" + html + "\'");
    } else {
      var distance_display = distance.toFixed(1) != "" && distance.toFixed(1) != "null" && distance.toFixed(1) != "NaN" ? "<br>" + distance.toFixed(1) + " " + sl_distance_unit : "";
      var html = \'<center><table class="searchResultsTable"><tr><td class="results_row_left_column"><span class="location_name">\' + name + "</span>" + distance_display + \'</td><td class="results_row_center_column">\' + street + city + state_zip + \' </td><td class="results_row_right_column"> <a href="http://\' + sl_google_map_domain + "/maps?saddr=" + encodeURIComponent(homeAddress) + "&daddr=" + encodeURIComponent(sl_details["fullAddress"]) + \'" target="_blank" class="storelocatorlink">\' + sl_directions_label + 
      "</a> " + link + "</td></tr></table></center>";
    }
    return html;
  }
}
if (function_exists("buildMarkerHTML") != true) {
  var buildMarkerHTML = function(sl_details) {
    var street = sl_details["sl_address"];
    if (street.split(" ").join("") != "") {
      street += "<br/>";
    } else {
      street = "";
    }
    if (sl_details["sl_address2"].split(" ").join("") != "") {
      street += sl_details["sl_address2"] + "<br/>";
    }
    var city = sl_details["sl_city"];
    if (city.split(" ").join("") != "") {
      city += ", ";
    } else {
      city = "";
    }
    var state_zip = sl_details["sl_state"] + " " + sl_details["sl_zip"];
    if (sl_details["fullAddress"].split(",").join("").split(" ").join("") == "") {
      sl_details["fullAddress"] = sl_details["sl_latitude"] + "," + sl_details["sl_longitude"];
    }
    var homeAddress = sl_details["homeAddress"];
    var name = sl_details["sl_store"];
    var distance = sl_details["sl_distance"];
    var url = sl_details["sl_url"];
    var image = sl_details["sl_image"];
    var description = sl_details["sl_description"];
    var hours = sl_details["sl_hours"];
    var phone = sl_details["sl_phone"];
    var fax = sl_details["sl_fax"];
    var email = sl_details["sl_email"];
    var more_html = "";
    if (url.search(/^https?\:\/\//i)!=-1 && url.indexOf(".")!=-1) {more_html+="| <a href=\'"+url+"\' target=\'_blank\' class=\'storelocatorlink\'><nobr>" + sl_website_label +"</nobr></a>";} else {url="";}
	if (image.indexOf(".")!=-1) {more_html+="<br/><img src=\'"+image+"\' class=\'sl_info_bubble_main_image\'>";} else {image="";}
	if (description!="") {more_html+="<br/>"+description+"";} else {description="";}
	if (hours!="") {more_html+="<br/><span class=\'location_detail_label\'>"+sl_hours_label+":</span> "+hours;} else {hours="";}
	if (phone!="") {more_html+="<br/><span class=\'location_detail_label\'>"+sl_phone_label+":</span> "+phone;} else {phone="";}
	if (fax!="") {more_html+="<br/><span class=\'location_detail_label\'>"+sl_fax_label+":</span> "+fax;} else {fax="";}
	if (email!="") {more_html+="<br/><span class=\'location_detail_label\'>"+sl_email_label+":</span> "+email;} else {email="";}
    sl_details["sl_more_html"] = more_html;
    sl_details["sl_distance_unit"] = sl_distance_unit;
    sl_details["sl_google_map_domain"] = sl_google_map_domain;
    if (function_exists("sl_bubble_template") && sl_bubble_template(sl_details)) {
      sl_details["sl_distance"] = distance.toFixed(1);
      var html = decode64(sl_bubble_template(sl_details));
      html = determineDirectionsLink(sl_details, html);
      html = eval("\'" + html + "\'");
    } else {
      var html = \'<div id="sl_info_bubble"><strong>\' + name + "</strong><br>" + street + city + state_zip + \'<br/> <a href="http://\' + sl_google_map_domain + "/maps?saddr=" + encodeURIComponent(homeAddress) + "&daddr=" + encodeURIComponent(sl_details["fullAddress"]) + \'" target="_blank" class="storelocatorlink">\' + sl_directions_label + "</a> " + more_html + "<br/></div>"
    }
    return html;
  }
}
if (function_exists("buildDetails") != true) {
  var buildDetails = function(markerNode) {
    var details_array = {"fullAddress":markerNode.getAttribute("address"), "sl_address":markerNode.getAttribute("street"), "sl_address2":markerNode.getAttribute("street2"), "sl_city":markerNode.getAttribute("city"), "sl_state":markerNode.getAttribute("state"), "sl_zip":markerNode.getAttribute("zip"), "sl_latitude":markerNode.getAttribute("lat"), "sl_longitude":markerNode.getAttribute("lng"), "sl_store":markerNode.getAttribute("name"), "sl_description":sl_nl2br(markerNode.getAttribute("description")), 
    "sl_url":markerNode.getAttribute("url"), "sl_hours":sl_nl2br(markerNode.getAttribute("hours")), "sl_phone":markerNode.getAttribute("phone"), "sl_fax":markerNode.getAttribute("fax"), "sl_email":markerNode.getAttribute("email"), "sl_image":markerNode.getAttribute("image"), "sl_tags":markerNode.getAttribute("tags"), "sl_distance":parseFloat(markerNode.getAttribute("distance")), "homeAddress":document.getElementById("addressInput").value, "point":new google.maps.LatLng(parseFloat(markerNode.getAttribute("lat")), 
    parseFloat(markerNode.getAttribute("lng")))};
    if (typeof sl_xml_properties_array != "undefined") {
      if (is_array(sl_xml_properties_array)) {
        for (key in sl_xml_properties_array) {
          details_array[sl_xml_properties_array[key]] = sl_nl2br(markerNode.getAttribute(sl_xml_properties_array[key]));
        }
      }
    }
    details_array = sl_details_filter(details_array);
    return details_array;
  }
};	
'; #<- v3.98.5 - heredoc to single quotes
	$sl_vars['jsl'] = preg_replace("@\n|\r|\t|[[:space:]]{2,}@", "", $sl_vars['jsl']);
}
if ($sl_j_flag==1){sl_data('sl_vars', 'update', $sl_vars);}
?>