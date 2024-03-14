<?php
/*
options.php, V 1.11, altm, 20.09.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
// Form URI
$location = get_option('siteurl') . '/wp-admin/admin.php?page=' . GPX_GM_PLUGIN . '/php/options.php';
// our options
$maptypes = get_option('gmap_v3_gpx_maptypes');
$defMaptype = get_option('gmap_v3_gpx_defMaptype');
$mapSizeBtn = get_option('gmap_v3_gpx_mapSizeBtn');
$gmapv3_disableDefaultUI = get_option('gmapv3_disableDefaultUI');
$gmapv3_zoomControl = get_option('gmapv3_zoomControl');
$gmapv3_restful = get_option('gmapv3_restful');
$fszIndex = get_option('gmap_v3_gpx_fszIndex');
$elevationProfile  = get_option('gmap_v3_gpx_elevationProfile');
$downloadLink = get_option('gmap_v3_gpx_downloadLink');
$distanceUnit = get_option('gmap_v3_gpx_distanceUnit');
$proKey = get_option('gmap_v3_gpx_proKey');

// update options 
if ('process' == $_POST['stage']) {
	$update_maptypes = $maptypes;
	// add new wms
	if($_POST['addNewWms'] == "add")
		array_push($update_maptypes, array("name", 0, "copy", WMS, "url" , 0, 0));
	$idx = 0; 
	foreach($update_maptypes as $maptype => $attr) {
		if($attr[3] == WMS || $attr[3] == OSM || $attr[3] == OSGEO){
			if(!$_POST['VMap'][$idx]['WMS_name']){
				$idx++;
				continue;
			}
			if( $_POST['VMap'][$idx]['WMS_delete'] == "on"){
				unset($update_maptypes[$maptype]);
				$idx++;
				continue;
			} 
			$update_maptypes[$maptype][0] = $_POST['VMap'][$idx]['WMS_name'];
			$update_maptypes[$maptype][2] = stripslashes($_POST['VMap'][$idx]['WMS_desc']);
			$update_maptypes[$maptype][3] = $_POST['VMap'][$idx]['WMS_type'];
			$update_maptypes[$maptype][4] = $_POST['VMap'][$idx]['WMS_url'];
			$update_maptypes[$maptype][5] = $_POST['VMap'][$idx]['WMS_minzoom'];
			$update_maptypes[$maptype][6] = $_POST['VMap'][$idx]['WMS_maxzoom'];
		}
		$update_maptypes[$maptype][1] = $_POST['VMap'][$idx]['WMS_visible'];
		$idx++;
	}
    update_option('gmap_v3_gpx_maptypes', $update_maptypes);
    $maptypes = $update_maptypes;
	
	$mapSizeBtn = $_POST['mapSizeBtn'];
    update_option('gmap_v3_gpx_mapSizeBtn', $mapSizeBtn);

	$gmapv3_restful = $_POST['gmapv3_restful'];
    update_option('gmapv3_restful', $gmapv3_restful);

	$gmapv3_disableDefaultUI = $_POST['gmapv3_disableDefaultUI'];
    update_option('gmapv3_disableDefaultUI', $gmapv3_disableDefaultUI);

	$gmapv3_zoomControl = $_POST['gmapv3_zoomControl'];
    update_option('gmapv3_zoomControl', $gmapv3_zoomControl);

	$fszIndex = $_POST['fszIndex'];
    update_option('gmap_v3_gpx_fszIndex', $fszIndex);

	$elevationProfile = $_POST['elevationProfile'];
    update_option('gmap_v3_gpx_elevationProfile', $elevationProfile);

	$downloadLink = $_POST['downloadLink'];
    update_option('gmap_v3_gpx_downloadLink', $downloadLink);

	$defMaptype = $_POST['defMaptype'];
    update_option('gmap_v3_gpx_defMaptype', $defMaptype);

	$distanceUnit = $_POST['distanceUnit'];
    update_option('gmap_v3_gpx_distanceUnit', $distanceUnit);

	$proKey = $_POST['proKey'];
    update_option('gmap_v3_gpx_proKey', $proKey);
 }
// default options 
elseif ('default' == $_POST['stage']) {
	delete_option('gmap_v3_gpx_maptypes');
	add_option('gmap_v3_gpx_maptypes', $default_maptypes);
	$maptypes = $default_maptypes = get_option('gmap_v3_gpx_maptypes');
	
	delete_option('gmap_v3_gpx_defMaptype');
	add_option('gmap_v3_gpx_defMaptype', "TERRAIN");
	$defMaptype = get_option('gmap_v3_gpx_defMaptype');
	
	delete_option('gmap_v3_gpx_mapSizeBtn');
	add_option('gmap_v3_gpx_mapSizeBtn', true);
	$mapSizeBtn = get_option('gmap_v3_gpx_mapSizeBtn');

	delete_option('gmapv3_restful');
	add_option('gmapv3_restful', false);
	$gmapv3_restful = get_option('gmapv3_restful');
	
	delete_option('gmap_v3_gpx_fszIndex');
	add_option('gmap_v3_gpx_fszIndex', 1);
	$fszIndex = get_option('gmap_v3_gpx_fszIndex');
	
	delete_option('gmap_v3_gpx_elevationProfile');
	add_option('gmap_v3_gpx_elevationProfile', 1);
	$elevationProfile = get_option('gmap_v3_gpx_elevationProfile');
	
	delete_option('gmap_v3_gpx_downloadLink');
	add_option('gmap_v3_gpx_downloadLink', 1);
	$downloadLink = get_option('gmap_v3_gpx_downloadLink');
	
	delete_option('gmap_v3_gpx_distanceUnit');
	add_option('gmap_v3_gpx_distanceUnit', 'meter');
	$distanceUnit = get_option('gmap_v3_gpx_distanceUnit');
	
	$proKey = get_option('gmap_v3_gpx_proKey');
}
	update_option('gmap_v3_gpx_persist', 'TYw7C8IwFIX/yuXSIaHSWMTFItahk+hQwSWmoZjQBtomxNTHv7dYB6fz4HzHed3Ivg63lmBekd2mDcFtGKM7wismYpobXMA9+GA7+9SeRPJclJei5DirPO2PBQq6gGgqaAbu75HxKhHxNflKxHAe8VRMRtm+NsMEeB1GPwBpdJDWBWMHgk1fO/lYyca9pPP2oN9IYbuFXq3Jj+RLAQng03SdgXFQoIyGKSkN6QopzT4=');


	function print_maptypes() {
		global $maptypes;
		$idx = 0;
		if (is_array($maptypes)){
			foreach($maptypes as $maptype => $attr) {
				$out =  "<div style='margin:5px;'>";
				if($attr[3] != WMS || $attr[3] != OSM || $attr[3] != OSGEO)
					$out .= "<input type='hidden' value='".$attr[0] . "' type='text' name='VMap[".$idx."][WMS_name]'/>";
				$out .= "<input style='margin-right:15px;' value='1' type='checkbox' name='VMap[".$idx."][WMS_visible]' "; 
				if($attr[1]) $out .= "checked='checked'"; 
				$out .= ">".$attr[0]." - ".stripcslashes($attr[2]);
				$out .="</div>";
				echo $out;
				$idx++;
			}
		} else {
			echo _e('There is a problem connecting the WP-Database!', GPX_GM_PLUGIN);
		}
	}
	function print_wms() {
		global $maptypes;
		function insOption($type, $comp){
			if($type ==  $comp)
				return "<option selected>".$type."</option>";
			else return "<option>".$type."</option>";
		}
		function fillConfMap($attr, $idx, $hide){
			if($hide){
				$out  =  "<div id='newWmsEnry' class='opt_hint' style='display:none; width:350px; padding:6px 6px;'><input type='hidden' id='addNewWms' name='addNewWms' />";
				$out .=  "<input type='hidden' name='VMap[".$idx."][WMS_visible]' value='1' />";
			} else {
				$out  =  "<div class='opt_hint wms_entry' style='width:350px; padding:6px 6px;'>";
				$out .=  "<p style=float:left;'> ".__('Delete', GPX_GM_PLUGIN)." <input style='' type='checkbox' name='VMap[".$idx."][WMS_delete]'/></p>";
			}
			$out .=  "<p style='text-align:right;'> ".__('WMS name:', GPX_GM_PLUGIN)." <input style='width:100px; ' value='".$attr[0] . "' type='text' name='VMap[".$idx."][WMS_name]'/></p>";
			$out .=  "<p style='text-align:right;'> ".__('WMS Type:', GPX_GM_PLUGIN)." ";
			$out .=  "<select style='width:100px;' name='VMap[".$idx."][WMS_type]'/>";
			$mTypes = array(WMS , OSM, OSGEO);
			$mt=$attr[3];
			for ($i = 0; $i < 3; $i++){
				$out .=  insOption($mTypes[$i], $mt);
			} 
			$out .=  "</select></p>";
			$out .=  "<p style='text-align:right;'> Copyright: <input style='width:267px;' value='".stripcslashes($attr[2])."' type='text' name='VMap[".$idx."][WMS_desc]'/></p>"; 
			$out .=  "<p style='text-align:right;'> URL: <input id='WMS_url_".$idx."' style='width:90%; ' value='".$attr[4]."' type='text' name='VMap[".$idx."][WMS_url]'/></p>"; 
			$out .=  "<p style='text-align:right;'> ".__('min. zoom:', GPX_GM_PLUGIN)."  <input id='WMS_minzoom_".$idx."' style='width:20%; ' value='".$attr[5]."' type='text' name='VMap[".$idx."][WMS_minzoom]'/></p>"; 
			$out .=  "<p style='text-align:right;'> ".__('max. zoom:', GPX_GM_PLUGIN)." <input id='WMS_maxzoom_".$idx."' style='width:20%; ' value='".$attr[6]."' type='text' name='VMap[".$idx."][WMS_maxzoom]'/></p>"; 
			$out .=  "</div><br />";
			echo $out;
		}
		if (is_array($maptypes)){
			$idx = 0;
			foreach($maptypes as $maptype => $attr) {
				if($attr[3] == WMS || $attr[3] == OSM || $attr[3] == OSGEO){ 
					fillConfMap($attr, $idx, false);
				}
					$idx++;
			}
			$newWmsMap = array("", "", "", "" ,"", "", "", "" );
			fillConfMap($newWmsMap, count($maptypes), true);
		}
	}
 	function print_maptypes_select() {
		global $maptypes;
		global $defMaptype;
		if (is_array($maptypes)){
			$out  =  "<p style='width:350px; padding:6px 6px;'><select style='width:100px; ' , name='defMaptype'/>";
			foreach($maptypes as $maptype => $attr) { 
				if ($defMaptype == $attr[0])
					$out .= "<option selected>". $attr[0] . "</option>";
				else
					$out .= "<option>". $attr[0] . "</option>";
			}
			$out .= "</select>";
			$out .= __('Default maptype', GPX_GM_PLUGIN) ;
			$out .= "</p>";
			echo $out;
		}
	}
 	function print_unit_select() {
		global $distanceUnit;
		$units = array('meter','miles');
			$out  =  "<select style='width:70px;' , name='distanceUnit'/>";
			foreach($units as $unit => $attr) { 
				if ($distanceUnit == $attr)
					$out .= "<option selected>". $attr . "</option>";
				else
					$out .= "<option>". $attr . "</option>";
			}
			$out .= "</select>";
			echo $out;
	} 	
 	function print_proKey() {
		global $proKey;
			$out  = "<input style='width:267px;' value='".$proKey."' type='text' name='proKey'/>";
			echo $out;
	} 	
	 
?>
<style type="text/css">
.opt_div {
	min-width:600px;
	width:auto;
	border-radius: 4px 4px 4px 4px; 
	box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.35); 
	border: 1px solid rgb(169, 187, 223);
	padding:10px;
	margin-bottom:20px;
	margin-right:20px;
	overflow:auto;
}
.opt_head {
	width:250px;
	overflow:auto;
}
.opt_head h3 {
 	margin-top:0px;
 }
.opt_hint {
	width:250px;
	padding: 6px; 
	border-radius: 4px 4px 4px 4px; 
	box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.35); 
	border: 1px solid rgb(223, 187, 169);
	overflow:auto;
}
.opt_out {
	width:480px;
	max-width:55%;
	float:right;
}
.poetry {
	width:auto;
	margin-right:20px;
	text-align:right;
}
.opt_radio {
 	margin-bottom:10px;
	width:250px;
	padding: 6px; 
	border:1px solid black;
 }
/*	
float:right;

*/
</style>
	<form name="form1" method="post" onsubmit="return checkInput();" action="<?php echo $location ?>&amp;updated=true">
	<input type="hidden" id="stage" name="stage" value="process" />
	
	<div style="padding-left:15px">
	<h2><?php _e('Google Maps - GPX Viewer Options', GPX_GM_PLUGIN); ?></h2>

	<div class="poetry"><a href="http://wordpress.org/extend/plugins/google-maps-gpx-viewer/" alt="<?php _e('Please rate', GPX_GM_PLUGIN) ?>" title="<?php _e('Please rate', GPX_GM_PLUGIN) ?>"><?php _e('Please vote the Plugin!', GPX_GM_PLUGIN) ?></a> - Plugin Home: <a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/">ATLsoft.de</a>
<a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/"><img src="http://www.atlsoft.de/programmierung/wp-content/uploads/bugfree.png" class="poetry"></a> <?php echo "<small>Version ".GMAPX_VERSION."</small>";  ?></div>

	<div class="opt_div">		
		<div class="opt_out"><?php print_proKey();?></div>
		<div class="opt_head"><h3><?php	_e('Pro Key', GPX_GM_PLUGIN) ?>:</h3></div>
	</div>
    <div class="opt_div">
		<div class="opt_out"><?php print_maptypes();?><br /><?php print_maptypes_select();?></div>
		<div class="opt_head"><h3><?php	_e('Maptype listbox', GPX_GM_PLUGIN) ?></h3></div>
		<div class="opt_hint"><p>
		<?php 
		_e('Select what the user should see inside the maptype listbox.', GPX_GM_PLUGIN) ;
				echo "</p><p>";
		_e('If you select less than two items no listbox will appear.', GPX_GM_PLUGIN) ;
		?>
		</p><p><strong>
		<?php 
		_e('Default maptype', GPX_GM_PLUGIN) ;
		?>: </strong><?php
		_e('This map appears if no maptype ShortCode is given.', GPX_GM_PLUGIN) ;
		?>
		</p></div>
   </div>

	<div class="opt_div">
		<div class="opt_out">
			<input style="margin:0px 15px 0px 6px;" type="checkbox" name="gmapv3_restful" value="1" <?php if($gmapv3_restful) echo "checked"; ?> />
			<?php _e('Mobile Apps support', GPX_GM_PLUGIN) ?> - <a href="http://www.atlsoft.de/poi-database/" target="_blank"><?php _e('Tell me more...', GPX_GM_PLUGIN) ?></a> <br />
			<input style="margin:0px 15px 0px 6px;" type="checkbox" name="elevationProfile" value="1" <?php if($elevationProfile) echo "checked"; ?> />
			<?php _e('Elevation profile', GPX_GM_PLUGIN) ?><br />
			<input style="margin:0px 15px 0px 6px;" type="checkbox" name="downloadLink" value="1" <?php if($downloadLink) echo "checked"; ?> />
			<?php _e('Download link', GPX_GM_PLUGIN) ?><br /><br />
			<span style='margin:0px 6px 0px 4px'><?php print_unit_select(); ?></span><?php _e('Distance Units', GPX_GM_PLUGIN) ?>
		</div>
		<div class="opt_head"><h3><?php	_e('Elevation/Download', GPX_GM_PLUGIN) ?></h3></div>
		<div class="opt_hint"><p>
		<?php 
		_e('Elevation profile and track download will be available on maps containing GPX and KML track data by default.', GPX_GM_PLUGIN) ;
		?>
		</p></div>
	</div>

	<div class="opt_div">
		<div class="opt_out">
			<input style="margin:0px 15px 0px 6px;" type="checkbox" name="gmapv3_disableDefaultUI" value="1" <?php if($gmapv3_disableDefaultUI) echo "checked"; ?> />
			<?php _e('Show default map UI', GPX_GM_PLUGIN) ?><br />
			<input style="margin:0px 15px 0px 6px;" type="checkbox" name="gmapv3_zoomControl" value="1" <?php if($gmapv3_zoomControl) echo "checked"; ?> />
			<?php _e('Show map zoom control', GPX_GM_PLUGIN) ?><br />
			<input style="margin:0px 15px 0px 6px;" type="checkbox" name="mapSizeBtn" value="1" <?php if($mapSizeBtn) echo "checked"; ?> />
			<?php _e('Show size button', GPX_GM_PLUGIN) ?><br />
			<input style="margin:10px 6px 0px 6px; width:60px;" type="text" name="fszIndex" id="fszIndex" value="<?php echo $fszIndex; ?>"  />
			<?php _e('full size z-index', GPX_GM_PLUGIN) ?>
		</div>
		<div class="opt_head"><h3><?php	_e('Map UI options', GPX_GM_PLUGIN) ?></h3></div>
		<div class="opt_hint"><p>
		<?php 
		_e('Enables/Disables default map UI and zoom control.', GPX_GM_PLUGIN) ;
				echo "</p><p>";
		_e('Some older browser have problems to handle full size mode.', GPX_GM_PLUGIN) ;
				echo "</p><p>";
		_e('Disable this option will hide the size button.', GPX_GM_PLUGIN) ;
				echo "</p><p>";
		_e('Full size map z-position can be from background (-1) up to topmost (max).', GPX_GM_PLUGIN) ;
		?>
		</p></div>
	</div>

	<div class="opt_div">
		<div class="opt_out"><?php print_wms();?>
			<div style=" style="margin:-20px 0 20px 0;"">
			</div>
			<input type="button" id="New_WMS" name="New_WMS" onclick="showNewWms();" value="<?php _e('New WMS Server', GPX_GM_PLUGIN) ?>" />
		</div>
		<div class="opt_head"><h3><?php _e('WMS interface', GPX_GM_PLUGIN) ?></h3></div>
			<div class="opt_hint"><p>
			<?php _e('Setup extern Web Map Service to work with this plugin, there are thousands of WMS Server out there waiting to be connected.', GPX_GM_PLUGIN);
				echo "</p>";
				_e("If you don't know how to use WMS, read this first:", GPX_GM_PLUGIN);
			?> <a href="http://www.atlsoft.de/programmierung/web-map-service/" target="_blank">WMS Documentation</a>
			<?php
				echo " ";
				_e("or try a google search...", GPX_GM_PLUGIN) ;
			?>
		</div>
    </div>
</div>
    <p style="padding-left:15px">
      <input type="submit" name="Submit" value="<?php _e('Save changes', GPX_GM_PLUGIN) ?>" />
    </p>
    <p style="padding-left:15px">
      <input type="button" name="Reset" onclick="return askforReset();" value="<?php _e('Set to default', GPX_GM_PLUGIN) ?>" />
    </p>
</form>

<script type="text/javascript">
	poetry = "Code is poetry \nbut if you find bugs \nplease report me :-)"
	jQuery(".poetry").attr("alt",poetry);
	jQuery(".poetry").attr("title",poetry);

	function showNewWms(){
	var h = jQuery('#newWmsEnry').css('display');
		if(h == 'block'){
			jQuery('#newWmsEnry').removeClass('wms_entry');
			jQuery('#New_WMS').attr("value", "<?php _e('New WMS Server', GPX_GM_PLUGIN) ?>")
			jQuery('#addNewWms').attr("value", "")
			jQuery('#newWmsEnry').slideUp(500);
		} else {
			jQuery('#newWmsEnry').addClass('wms_entry');
			jQuery('#New_WMS').attr("value", "<?php _e('Hide new WMS Server', GPX_GM_PLUGIN) ?>")
			jQuery('#addNewWms').attr("value", "add")
			jQuery('#newWmsEnry').slideDown(500);
		}
	}

	function checkInput(){
		var msg = '<?php	_e('z-index must be a number!', GPX_GM_PLUGIN) ?>';
		var val = jQuery("#fszIndex").attr("value");
		if (isNaN(val)) {
			alert(msg);
			return false;
		}
		var wmsIface = '<?php	_e('WMS interface', GPX_GM_PLUGIN) ?>';
		
		var msg = '<?php	_e('Missing name!', GPX_GM_PLUGIN) ?>';
		val = jQuery('.wms_entry').find('input[id^="WMS_name"]'); 
		var err = false;
		for(var i = 0; i < val.length; i++){
			if (jQuery(val[i]).attr("value") == "") {
				err = true;
				break;
			}	
		}
		if(err){
			i++;
			alert(wmsIface + ' #' + i + '\r\n' + msg);
			return false;
		}


		var msg = '<?php	_e('min./max zoom must be a number!', GPX_GM_PLUGIN) ?>';

		val = jQuery('.wms_entry').find('input[id^="WMS_minzoom"]'); 
		var err = false;
		for(var i = 0; i < val.length; i++){
			if (isNaN(parseInt(jQuery(val[i]).attr("value")))) {
				err = true;
				break;
			}	
		}
		if(err){
			i++;
			alert(wmsIface + ' #' + i + '\r\n' + msg);
			return false;
		}

		val = jQuery('.wms_entry').find('input[id^="WMS_maxzoom"]'); 
		var err = false;
		for(var i = 0; i < val.length; i++){
			if (isNaN(parseInt(jQuery(val[i]).attr("value")))) {
				err = true;
				break;
			}	
		}
		if(err){
			i++;
			alert(wmsIface + ' #' + i + '\r\n' + msg);
			return false;
		}	
		msg = '<?php	_e('URL must start with \"http://\"...', GPX_GM_PLUGIN) ?>';
		val = jQuery('.wms_entry').find('input[id^="WMS_url"]'); 
		var err = false;
		for(var i = 0; i < val.length; i++){
			if (jQuery(val[i]).attr("value").substr(0, 4).toLowerCase() != 'http') {
				err = true;
				break;
			}	
		}
		if(err){
			i++;
			alert(wmsIface + ' #' + i + '\r\n' + msg);
			return false;
		}	
	}
	function askforReset(){
		var conf = window.confirm('<?php	_e('Are you sure?', GPX_GM_PLUGIN) ?>');
		if(conf) {
			jQuery("#stage").attr("value","default");
			document.form1.submit();
		}
	}
</script>