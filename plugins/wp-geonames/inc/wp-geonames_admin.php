<?php
//
// ********* MANUAL - ADD A LOCAL FILE IN WP-CONTENT/UPLOADS *********
// $geoManual = '/tmp/US.txt'; // '/tmp/ES.txt';
// *******************************************************************
//
function wpGeonames_creation_table() {
	/*
	****** http://download.geonames.org/export/dump/readme.txt *********
	0  geonameid		: integer id of record in geonames database
	1  name			: name of geographical point (utf8) varchar(200)
	2  asciiname			: name of geographical point in plain ascii characters, varchar(200)
	3  alternatenames	: alternatenames, comma separated, ascii names automatically transliterated, convenience attribute from alternatename table, varchar(10000)
	4  latitude			: latitude in decimal degrees (wgs84)
	5  longitude			: longitude in decimal degrees (wgs84)
	6  feature class		: see http://www.geonames.org/export/codes.html, char(1)
	7  feature code		: see http://www.geonames.org/export/codes.html, varchar(10)
	8  country code		: ISO-3166 2-letter country code, 2 characters
	9  cc2				: alternate country codes, comma separated, ISO-3166 2-letter country code, 60 characters
	10 admin1 code		: fipscode (subject to change to iso code), see exceptions below, see file admin1Codes.txt for display names of this code; varchar(20)
	11 admin2 code		: code for the second administrative division, a county in the US, see file admin2Codes.txt; varchar(80) 
	12 admin3 code		: code for third level administrative division, varchar(20)
	13 admin4 code		: code for fourth level administrative division, varchar(20)
	14 population		: bigint (8 byte int) 
	15 elevation			: in meters, integer
	16 dem				: digital elevation model, srtm3 or gtopo30, average elevation of 3''x3'' (ca 90mx90m) or 30''x30'' (ca 900mx900m) area in meters, integer. srtm processed by cgiar/ciat.
	17 timezone			: the timezone id (see file timeZone.txt) varchar(40)
	18 modification date	: date of last modification in yyyy-MM-dd format
	*/
	require_once(ABSPATH.'wp-admin/includes/upgrade.php'); // dbDelta()
	global $wpdb;
	//
	$charset_collate = '';
	if(!empty($wpdb->charset)) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if(!empty($wpdb->collate)) $charset_collate .= " COLLATE $wpdb->collate";
	$nom = $wpdb->base_prefix.'geonames';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`idwpgn` bigint(20) unsigned NOT NULL auto_increment,
			`geonameid` bigint(20) unsigned NOT NULL UNIQUE,
			`name` varchar(200) NOT NULL,
			`asciiname` varchar(200) NOT NULL,
			`alternatenames` text,
			`latitude` decimal(10,5) NOT NULL,
			`longitude` decimal(10,5) NOT NULL,
			`feature_class` char(1) NOT NULL,
			`feature_code` varchar(10) NOT NULL,
			`country_code` varchar(2) NOT NULL,
			`cc2` varchar(60) NOT NULL,
			`admin1_code` varchar(20) NOT NULL,
			`admin2_code` varchar(80) NOT NULL,
			`admin3_code` varchar(20) NOT NULL,
			`admin4_code` varchar(20) NOT NULL,
			`population` bigint unsigned NOT NULL,
			`elevation` int NOT NULL,
			`dem` smallint unsigned NOT NULL,
			`timezone` varchar(40) NOT NULL,
			`modification_date` date NOT NULL,
			PRIMARY KEY (`idwpgn`),
			INDEX `index1` (`feature_class`,`feature_code`(3),`country_code`,`cc2`(2),`name`(3))
			) $charset_collate;";
		dbDelta($sql);
	}
	$nom = $wpdb->base_prefix.'geonamesPostal';
	if($wpdb->get_var("SHOW TABLES LIKE '$nom'")!=$nom) {
		$sql = "CREATE TABLE ".$nom." (
			`idwpgnp` bigint(20) unsigned NOT NULL auto_increment,
			`country_code` varchar(2) NOT NULL,
			`postal_code` varchar(20) NOT NULL,
			`place_name` varchar(180) NOT NULL,
			`admin1_name` varchar(100) NOT NULL,
			`admin1_code` varchar(20) NOT NULL,
			`admin2_name` varchar(100) NOT NULL,
			`admin2_code` varchar(20) NOT NULL,
			`admin3_name` varchar(100) NOT NULL,
			`admin3_code` varchar(20) NOT NULL,
			`latitude` decimal(10,5) NOT NULL,
			`longitude` decimal(10,5) NOT NULL,
			`accuracy` tinyint(1) unsigned NOT NULL,
			PRIMARY KEY (`idwpgnp`),
			INDEX `index1` (`country_code`,`postal_code`,`place_name`(3))
			) $charset_collate;";
		dbDelta($sql);
	}
}
//
if(is_admin()) {
	load_plugin_textdomain('wpGeonames', false, dirname(plugin_basename( __FILE__ )).'/lang/'); // language
	add_action('wp_ajax_wpgeonamesAjax', 'wpgeonamesAjax');
	add_action('wp_ajax_wpgeonameGetCity', 'wpGeonames_ajax_get_city_by_country_region');
	add_action('wp_ajax_wpGeonamesAddCountry', 'wpGeonames_ajax_wpGeonamesAddCountry');
	add_action('wp_ajax_wpGeonamesAddPostal', 'wpGeonames_ajax_wpGeonamesAddPostal');
	add_action('admin_enqueue_scripts', 'wpGeonames_enqueue_leaflet');
	add_action('admin_menu','wpGeonames');
	add_filter('plugin_action_links_'.plugin_basename( __FILE__ ), 'wpGeoname_settings_link');
	if(file_exists(dirname(__FILE__).'/patch.php')) include(dirname(__FILE__).'/patch.php');
}
function wpGeonames() {
	if(!current_user_can("administrator")) die;
	add_options_page('WP GeoNames Options', 'WP GeoNames', 'manage_options', 'wpGeonames-options', 'wpGeonames_admin');
}
function wpGeonames_admin() {
	if(!current_user_can("administrator")) die;
	global $wpdb; global $geoManual; global $geoVersion;
	$zip = '';
	$urlc = 'http://download.geonames.org/export/dump/'; // countries
	$urlp = 'http://download.geonames.org/export/zip/'; // postal codes
	if(isset($_POST['wpGeonamesAdd'])) $zip = '<p style="font-weight:700;color:#D54E21;">'.wpGeonames_addZip($urlc,$_POST).'</p>';
	else if(isset($_POST['wpGeonamesClear'])) $zip = '<p style="font-weight:700;color:#D54E21;">'.wpGeonames_clear().'</p>';
	else if(isset($_POST['wpGeonamesPostalAdd'])) $zip = '<p style="font-weight:700;color:#D54E21;">'.wpGeonames_postalAddZip($urlp,$_POST).'</p>';
	else if(isset($_POST['wpGeonamesPostalClear'])) $zip = '<p style="font-weight:700;color:#D54E21;">'.wpGeonames_postalClear().'</p>';
	$wpGeoList = get_option('wpGeonames_dataList');
	if(!$wpGeoList || !empty($_GET['checkData'])) $wpGeoList = wpGeonames_update_options();	
	$geoToka = wp_create_nonce('geoToka');
	if(!empty($wpGeoList['date'])) {
		list($year, $month, $day) = explode('-', $wpGeoList['date']);
		$old = mktime(0, 0, 0, $month, $day, $year);
		if(time()-$old>31536000) { // 1 year : 31536000 - ?>
	
	<div class="notice notice-warning is-dismissible"> 
		<p><strong><?php _e('Datas are very old. You should Clear this table and Add new datas.', 'wpGeonames'); ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
		<?php } ?>
	<?php } ?>
	
	<div class='wrap'>
		<h2 class="nav-tab-wrapper">
			<a href="options-general.php?page=wpGeonames-options" class="nav-tab<?php if(empty($_GET['geotab'])) echo ' nav-tab-active'; ?>"><?php _e('General', 'wpGeonames'); ?></a>
			<a href="options-general.php?page=wpGeonames-options&geotab=check" class="nav-tab<?php if(isset($_GET['geotab']) && $_GET['geotab']=='check') echo ' nav-tab-active'; ?>"><?php _e('Check Datas', 'wpGeonames'); ?></a>
			<a href="options-general.php?page=wpGeonames-options&geotab=edit" class="nav-tab<?php if(isset($_GET['geotab']) && $_GET['geotab']=='edit') echo ' nav-tab-active'; ?>"><?php _e('Edit Datas', 'wpGeonames'); ?></a>
			<a href="options-general.php?page=wpGeonames-options&geotab=help" class="nav-tab<?php if(isset($_GET['geotab']) && $_GET['geotab']=='help') echo ' nav-tab-active'; ?>"><?php _e('Help', 'wpGeonames'); ?></a>
		</h2>
		<?php if(!empty($_GET['geotab'])) {
			if($_GET['geotab']=='check') wpGeonames_admin_check();
			else if($_GET['geotab']=='edit') wpGeonames_admin_edit();
			else if($_GET['geotab']=='help') wpGeonames_admin_help(); ?>
			
		</div>
		<div style="clear:both;"></div>
		<?php return;
		}
		echo $zip;
		$contries = array(); $postal = array();
		if(function_exists('curl_version')) {
			$h = curl_init($urlc);
			curl_setopt($h, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($h, CURLOPT_CONNECTTIMEOUT, 5);
			$page1 = curl_exec($h);
			curl_close($h);
			$h = curl_init($urlp);
			curl_setopt($h, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($h, CURLOPT_CONNECTTIMEOUT, 5);
			$page2 = curl_exec($h);
			curl_close($h);
		}
		else {
			$page1 = @file_get_contents($urlc);
			$page2 = @file_get_contents($urlp);
		}
		$b = 0;
		if($page1) {
			$q = preg_split("/href/i", $page1);
			foreach($q as $r) {
				if(strpos($r,".zip")!==false) {
					$contries[] = substr($r,2,strpos($r,".zip")+2);
					$b = 1;
				}
			}
		}
		if(!$b) $page1 = false;
		$b = 0;
		if($page2) {
			$q = preg_split("/href/i", $page2);
			foreach($q as $r) {
				if(strpos($r,".zip")!==false) {
					$postal[] = substr($r,2,strpos($r,".zip")+2);
					$b = 1;
				}
			}
		}
		if(!$b) $page2 = false;
		?>
	
		<link rel="stylesheet" href="<?php echo plugins_url(); ?>/wp-geonames/sumoselect/sumoselect.css" type="text/css" media="all" />
		<div class='icon32' id='icon-options-general'><br/></div>
		<div>
			<a style="float:right;margin:20px;" href="http://www.geonames.org/"><img src="<?php echo plugins_url('wp-geonames/images/geonames.png'); ?>" alt="GeoNames" title="GeoNames" /></a>
		</div>
		<h2>WP GeoNames&nbsp;<span style='font-size:80%;'>v<?php echo $geoVersion; ?></span></h2>
		<p>
		<?php _e('This plugin allows to insert into the database the millions of places available free of charge on the GeoNames website.', 'wpGeonames'); ?>
		</p>
		<div id="wpGeonamesAddStatus"><img id="wpGeonameAddImg" src="<?php echo plugins_url('wp-geonames/images/loading.gif'); ?>" style="display:none;" /></div>
		<div style="clear:both;"></div>
		<hr />
		<h2><?php _e('Countries', 'wpGeonames'); ?></h2>
		<?php
		$cc = '';
		if($wpGeoList) foreach($wpGeoList as $k=>$v) {
			if(strlen($k)==2) $cc .= $k.' (<span style="color:#D54E21;">'.$v.'</span>)&nbsp;&nbsp;';
		}
		echo '<p>'.__('Number of data in this database', 'wpGeonames').' : <span style="font-weight:700;color:#D54E21;">'.$wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->base_prefix."geonames").'</span><a style="margin-left:10px;" href="options-general.php?page=wpGeonames-options&checkData=1"><img src="'.plugins_url('wp-geonames/images/reload.png').'" style="vertical-align:middle;" /></a></p>';
		echo '<p>'.__('List of countries in this database', 'wpGeonames').' : <span style="font-weight:700;font-size:11px;">'.$cc.'</span></p>';
		?>

		<form method="post" id="wpGeonames_options1" name="wpGeonames_options1" action="options-general.php?page=wpGeonames-options&geoToka=<?php echo $geoToka; ?>">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label><?php _e('Add data to WordPress', 'wpGeonames'); ?></label></th>
					<td>
					<?php if($page1 || !empty($geoManual)) { ?>
						<select name="wpGeonamesAdd" id="wpGeonamesAdd" multiple="multiple">
						<?php
						$a = array(
							'allCountries.zip'=>1,
							'alternateNames.zip'=>1,
							'hierarchy.zip'=>1,
							'adminCode5.zip'=>1,
							'userTags.zip'=>1,
							'shapes_all_low.zip'=>1,
							'shapes_simplified_low.json.zip'=>1
							);
						if(empty($geoManual)) foreach($contries as $r2) {
							if(!isset($a[$r2])) echo '<option value="'.$r2.'">'.$r2.'</option>';
						} 
						else echo '<option value="geoManual">local : '.$geoManual.'</option>'; ?>
						
						</select>
					<?php } else echo '<span style="font-weight:700;color:#D54E21;">'.__('No connection available or issue with PHP file_get_contents(url)', 'wpGeonames').'</span>'; ?>
					
					</td>
					<td><a href="https://en.wikipedia.org/wiki/ISO_3166-1" target="_blank"><?php _e('Official Country List', 'wpGeonames'); ?></a></td>
					</td><td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e('Choose columns to insert', 'wpGeonames'); ?></label></th>
					<td style="width:250px;">
						<input type="checkbox" name="wpGeo0" value="1" checked disabled /><span style="color:#bb2;"><?php _e('ID', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo1" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Name', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo2" value="1" /><?php _e('Ascii Name', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeo3" value="1" /><?php _e('Alternate Names', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeo4" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Latitude', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo5" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Longitude', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo6" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Feature Class', 'wpGeonames'); ?></span>
					</td><td>
						<input type="checkbox" name="wpGeo7" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Feature Code', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo8" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Country Code', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo9" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Country Code2', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo10" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Admin1 Code', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo11" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Admin2 Code', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeo12" value="1" /><?php _e('Admin3 Code', 'wpGeonames'); ?>
					</td><td>
						<input type="checkbox" name="wpGeo13" value="1" /><?php _e('Admin4 Code', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeo14" value="1" /><?php _e('Population', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeo15" value="1" /><?php _e('Elevation', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeo16" value="1" /><?php _e('Digital Elevation Model', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeo17" value="1" /><?php _e('Timezone', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeo18" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Modification Date', 'wpGeonames'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e('Choose type of data to insert', 'wpGeonames'); ?></label></th>
					<td style="width:250px;">
						<input type="checkbox" name="wpGeoA" value="1" checked disabled /><span style="color:#bb2;"><?php _e('A : country, state, region', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeoH" value="1" /><?php _e('H : stream, lake', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoL" value="1" /><?php _e('L : parks,area', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoR" value="1" /><?php _e('R : road, railroad', 'wpGeonames'); ?>
					</td><td>
						<input type="checkbox" name="wpGeoP" value="1" checked disabled /><span style="color:#bb2;"><?php _e('P : city, village', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeoCity" value="1" checked /><?php _e('P* : just city', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoS" value="1" /><?php _e('S : spot, building, farm', 'wpGeonames'); ?>
					</td><td>
						<input type="checkbox" name="wpGeoT" value="1" /><?php _e('T : mountain,hill,rock', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoU" value="1" /><?php _e('U : undersea', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoV" value="1" /><?php _e('V : forest,heath', 'wpGeonames'); ?>
					</td>
				</tr>
			</table>
			<?php if($page1 || !empty($geoManual)) { ?>
			
			<div class="button-primary" onclick="wpGeonames_addCountries();"><?php _e('Add','wpGeonames') ?></div>
			<?php } ?>
			
		</form>
		<form method="post" name="wpGeonames_options2" action="options-general.php?page=wpGeonames-options&geoToka=<?php echo $geoToka; ?>">
			<input type="hidden" name="wpGeonamesClear" value="1" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Clear this table (TRUNCATE)','wpGeonames') ?>" />
			</p>
		</form>
		<hr />
		<div id="wpGeonamesPostalAddStatus"><img id="wpGeonamePostalAddImg" src="<?php echo plugins_url('wp-geonames/images/loading.gif'); ?>" style="display:none;" /></div>
		<h2><?php _e('Postal codes', 'wpGeonames'); ?></h2>
		<?php
		$cc = '';
		if($wpGeoList) foreach($wpGeoList as $k=>$v) {
			if(strlen($k)==3) $cc .= substr($k,1).' (<span style="color:#D54E21;">'.$v.'</span>)&nbsp;&nbsp;';
		}
		echo '<p>'.__('Number of data in this database', 'wpGeonames').' : <span style="font-weight:700;color:#D54E21;">'.$wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->base_prefix."geonamesPostal").'</span><a style="margin-left:10px;" href="options-general.php?page=wpGeonames-options&checkData=1"><img src="'.plugins_url('wp-geonames/images/reload.png').'" style="vertical-align:middle;" /></a></p>';
		echo '<p>'.__('List of countries in this database', 'wpGeonames').' : <span style="font-weight:700;font-size:11px;">'.$cc.'</span></p>';
		?>

		<form method="post" id="wpGeonames_options3" name="wpGeonames_options3" action="options-general.php?page=wpGeonames-options&geoToka=<?php echo $geoToka; ?>">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label><?php _e('Add data to WordPress', 'wpGeonames'); ?></label></th>
					<td>
					<?php if($page2) { ?>
						<select name="wpGeonamesPostalAdd" id="wpGeonamesPostalAdd" multiple="multiple">
						<?php foreach($postal as $r2) if(strlen($r2)==6 && substr($r2,2)=='.zip') echo '<option value="'.$r2.'">'.$r2.'</option>'; ?>
						
						</select>
					<?php } else echo '<span style="font-weight:700;color:#D54E21;">'.__('No connection available or issue with PHP file_get_contents(url)', 'wpGeonames').'</span>'; ?>
					
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e('Choose columns to insert', 'wpGeonames'); ?></label></th>
					<td style="width:250px;">
						<input type="checkbox" name="wpGeoPostal0" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Country Code', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeoPostal1" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Postal Code', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeoPostal2" value="1" checked disabled /><span style="color:#bb2;"><?php _e('Name', 'wpGeonames'); ?></span><br>
						<input type="checkbox" name="wpGeoPostal3" value="1" /><?php _e('Admin1 Name', 'wpGeonames'); ?>
					</td><td>
						<input type="checkbox" name="wpGeoPostal4" value="1" /><?php _e('Admin1 Code', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoPostal5" value="1" /><?php _e('Admin2 Name', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoPostal6" value="1" /><?php _e('Admin2 Code', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoPostal7" value="1" /><?php _e('Admin3 Name', 'wpGeonames'); ?>
					</td><td>
						<input type="checkbox" name="wpGeoPostal8" value="1" /><?php _e('Admin3 Code', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoPostal9" value="1" /><?php _e('Latitude', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoPostal10" value="1" /><?php _e('Longitude', 'wpGeonames'); ?><br>
						<input type="checkbox" name="wpGeoPostal11" value="1" /><?php _e('Accuracy', 'wpGeonames'); ?>
					</td>
				</tr>
			</table>
			<div class="button-primary" onclick="wpGeonames_addPostal();"><?php _e('Add','wpGeonames') ?></div>
		</form>
		<form method="post" name="wpGeonames_options4" action="options-general.php?page=wpGeonames-options&geoToka=<?php echo $geoToka; ?>">
			<input type="hidden" name="wpGeonamesPostalClear" value="1" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Clear this table (TRUNCATE)','wpGeonames') ?>" />
			</p>
		</form>
		<hr />
		<?php _e('To know how to use the data, look at the readme.txt file.', 'wpGeonames'); ?>
	</div>
	<script type="text/javascript" src="<?php echo plugins_url(); ?>/wp-geonames/sumoselect/jquery.sumoselect.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#wpGeonamesAdd').SumoSelect({placeholder:'<?php _e('Country list', 'wpGeonames'); ?>',captionFormat:'{0} <?php _e('Selected', 'wpGeonames'); ?>'});
		jQuery('#wpGeonamesPostalAdd').SumoSelect({placeholder:'<?php _e('Postal list', 'wpGeonames'); ?>',captionFormat:'{0} <?php _e('Selected', 'wpGeonames'); ?>'});
	});
	function wpGeonames_addCountries(){
		window.scrollTo(0,0);
		var a='',b=[];
		jQuery("#wpGeonames_options1 input[type=checkbox]").each(function(i){
			if(jQuery(this).is(":checked"))a+=jQuery(this).attr('name')+',';
		});
		jQuery("#wpGeonamesAdd option:selected").each(function(i){b[i]=jQuery(this).val();});
		jQuery("#wpGeonameAddImg").show();
		wpGeonames_nextCountry(0,a,b);
	}
	function wpGeonames_nextCountry(i,a,b){
		if(i<b.length)wpGeonames_addCountry(i,a,b);
		else{
			jQuery('#wpGeonamesAdd')[0].sumo.unSelectAll();
			jQuery("#wpGeonameAddImg").hide();
			window.location.reload(); 
		}
	}
	function wpGeonames_addCountry(i,a,b){
		var p=
		jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>',{
				'action':'wpGeonamesAddCountry',
				'frm':a,
				'file':b[i],
				'url':'<?php echo $urlc; ?>',
				'geoToka':'<?php echo $geoToka; ?>'
			},function(r){
			jQuery("#wpGeonamesAddStatus").append(r.substring(0,r.length-1));
			wpGeonames_nextCountry(i+1,a,b);
		});
	}
	function wpGeonames_addPostal(){
		var a='',b=[];
		jQuery("#wpGeonames_options3 input[type=checkbox]").each(function(i){
			if(jQuery(this).is(":checked"))a+=jQuery(this).attr('name')+',';
		});
		jQuery("#wpGeonamesPostalAdd option:selected").each(function(i){b[i]=jQuery(this).val();});
		jQuery("#wpGeonamePostalAddImg").show();
		wpGeonames_nextPostal(0,a,b);
	}
	function wpGeonames_nextPostal(i,a,b){
		if(i<b.length)wpGeonames_addPost(i,a,b);
		else{
			jQuery('#wpGeonamesPostalAdd')[0].sumo.unSelectAll();
			jQuery("#wpGeonamePostalAddImg").hide();
			window.location.reload(); 
		}
	}
	function wpGeonames_addPost(i,a,b){
		jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>',{
				'action':'wpGeonamesAddPostal',
				'frm':a,
				'file':b[i],
				'url':'<?php echo $urlp; ?>',
				'geoToka':'<?php echo $geoToka; ?>'
			},function(r){
			jQuery("#wpGeonamesPostalAddStatus").append(r.substring(0,r.length-1));
			wpGeonames_nextPostal(i+1,a,b);
		});
	}
	</script>
	<?php
}
function wpGeonames_update_options() {
	if(!current_user_can("administrator")) die;
	global $wpdb;
	$a = array();
	$count = $wpdb->get_results("SELECT COUNT(*) idwpgn, country_code FROM ".$wpdb->base_prefix."geonames GROUP BY country_code");  // benchmark allCountries : 7.633 sec
	foreach($count as $r) if($r->country_code) $a[$r->country_code] = $r->idwpgn;
	//
	$postal = $wpdb->get_results("SELECT COUNT(*) idwpgnp, country_code FROM ".$wpdb->base_prefix."geonamesPostal GROUP BY country_code");
	foreach($postal as $r) if($r->country_code) $a['p'.$r->country_code] = $r->idwpgnp;
	//
	$old = $wpdb->get_var("SELECT MAX(modification_date) FROM ".$wpdb->base_prefix."geonames LIMIT 1"); // benchmark allCountries : 5.172 sec
	if($old) $a['date'] = $old;
	//
	update_option('wpGeonames_dataList', $a);
	return $a;
}
function wpGeonames_admin_check() {
	global $wpdb;
	$country = wpGeonames_get_country();
	$postalCountry = wpGeonames_get_country(1);
	$Gcountry = (!empty($_GET['country'])?sanitize_text_field($_GET['country']):'');
	$Gregion = (!empty($_GET['region'])?sanitize_text_field($_GET['region']):'');
	$Gcityid = (!empty($_GET['cityid'])?sanitize_text_field($_GET['cityid']):'');
	$Gpostal = (!empty($_GET['postal'])?sanitize_text_field($_GET['postal']):'');
	//
	$geoToka = wp_create_nonce('geoToka');
	if($Gcountry) {
		if(isset($_GET['cityid'])) $region = wpGeonames_get_region_by_country($Gcountry);
		else if(isset($_GET['postal'])) $outPostal = wpGeonames_get_postalCheck($Gcountry,$Gpostal);
	}
	?>
	<style>
	.wpgeoCity span{color:#555;font-weight:400;width:auto;}
	.wpgeoCity span:hover{color:#000;font-weight:700;}
	</style>
	<h2><?php _e('Check your Geonames datas','wpGeonames') ?> - <?php _e('Countries', 'wpGeonames'); ?></h2>
	<form name="geoCheck" action="" method="GET">
		<input type="hidden" name="page" value="wpGeonames-options" />
		<input type="hidden" name="geotab" value="check" />
		<input type="hidden" name="region" value="" />
		<input type="hidden" name="country" value="" />
		<input type="hidden" name="cityid" value="" />
		<input type="hidden" name="geoToka" value="<?php echo $geoToka; ?>" />
	</form>
	<div style="float:left;width:48%;overflow:hidden;">
		<label><?php _e('Country','wpGeonames') ?></label><br />
		<select id="geoCheckCountry" name="geoCheckCountry" onchange="document.forms['geoCheck'].elements['country'].value=this.options[this.selectedIndex].value;document.forms['geoCheck'].submit();">
			<option value=""> - </option>
		<?php foreach($country as $r) echo '<option value="'.$r->country_code.'" '.(($Gcountry==$r->country_code)?'selected':'').'>'.$r->name.'</option>'; ?>
		
		</select>
	</div>
	<div style="float:left;width:48%;overflow:hidden;">
		<label><?php _e('Region','wpGeonames') ?></label><br />
		<select id="geoCheckRegion" name="geoCheckRegion" <?php if(empty($region)) echo 'style="display:none;"'; ?> onchange="document.forms['geoCheck'].elements['country'].value=document.getElementById('geoCheckCountry').options[document.getElementById('geoCheckCountry').selectedIndex].value;document.forms['geoCheck'].elements['region'].value=this.options[this.selectedIndex].value;document.forms['geoCheck'].submit();">
			<option value=""> - </option>
		<?php if(!empty($region)) {
			foreach($region as $r) echo '<option value="'.$r->admin1_code.'" '.(($Gregion==$r->admin1_code)?'selected':'').'>'.$r->name.'</option>';
		} ?>
		
		</select>
	</div>
	<div style="clear:both;margin-bottom:40px;"></div>
	<div style="float:left;width:48%;overflow:hidden;">
		<label><?php _e('City','wpGeonames') ?></label><br />
		<input type="text" id="geoCheckCity" name="geoCheckCity" onkeyup="wpGeonameListCity(this.value,'<?php echo $Gcountry; ?>','<?php echo $Gregion; ?>');" <?php if(!$Gregion) echo 'style="display:none;"'; ?> />
		<div class="geoListCity" id="geoListCity"></div>
	</div>
	<div style="float:left;width:48%;overflow:hidden;">
		<div id="geomap" style="height:300px;max-width:400px;display:none;"></div>
	</div>
	<div style="clear:both;margin-bottom:20px;"></div>
	<hr />
	<h2><?php _e('Check your Geonames datas','wpGeonames') ?> - <?php _e('Postal codes', 'wpGeonames'); ?></h2>
	<form name="geoCheckPostal" action="" method="GET">
		<input type="hidden" name="page" value="wpGeonames-options" />
		<input type="hidden" name="geotab" value="check" />
		<input type="hidden" name="geoToka" value="<?php echo $geoToka; ?>" />
		<div style="float:left;width:48%;overflow:hidden;">
			<label><?php _e('Country','wpGeonames') ?></label><br />
			<select name="country">
				<option value=""> - </option>
			<?php foreach($postalCountry as $r) echo '<option value="'.$r->country_code.'" '.(($Gcountry==$r->country_code)?'selected':'').'>'.$r->name.'</option>'; ?>
			
			</select>
		</div>
		<div style="float:left;width:48%;overflow:hidden;">
			<label><?php _e('Postal codes', 'wpGeonames'); ?></label><br />
			<input type="text" name="postal" />
		</div>
		<div class="submit" style="clear:both;margin-top:10px;">
			<input type="submit" class="button-primary" value="<?php _e('Search','wpGeonames') ?>" />
		</div>
	</form>
	<div><?php if(!empty($outPostal)) echo $outPostal; ?></div>
	<script>
	var wpgeoajx;
	function wpGeonameListCity(ci,iso,re){
		jQuery(document).ready(function(){
			if(ci.length>2){
				wpgeoajx=null;
				wpgeoajx=jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>',{
						'action':'wpgeonameGetCity',
						'city':ci,
						'iso':iso,
						'region':re,
						'geoToka':'<?php echo $geoToka; ?>'
					},function(data){
					var r=jQuery.parseJSON(data.substring(0,data.length-1));
					jQuery('#geoListCity').empty();
					jQuery.each(r,function(k,v){
						jQuery('#geoListCity').append('<div class="wpgeoCity"><span onClick="document.forms[\'geoCheck\'].elements[\'country\'].value=\'<?php echo $_GET['country']; ?>\';document.forms[\'geoCheck\'].elements[\'region\'].value=\'<?php echo $_GET['region']; ?>\';document.forms[\'geoCheck\'].elements[\'cityid\'].value=\''+v.geonameid+'\';document.forms[\'geoCheck\'].submit();">'+v.name+'</span></div>');
					});
				});
			}
		});
	}
<?php // https://switch2osm.org/fr/utilisation-des-tuiles/debuter-avec-leaflet/ ?>
	function wpGeonameCityMap(ci,lat,lon){
		document.getElementById('geomap').style.display='block';
		var wpgeomap=new L.map('geomap').setView([lat,lon],9);
		var wpgeodata=new L.TileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{minZoom:5,maxZoom:14,attribution:'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
		wpgeomap.addLayer(wpgeodata);
		var wpgeomark=L.marker([lat,lon]).addTo(wpgeomap);
		wpgeomark.bindPopup("<b>"+ci+"</b>").openPopup();
	}
	<?php if($Gcityid) {
		$q = $wpdb->get_row("SELECT
				*
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				geonameid='".$Gcityid."'
			LIMIT 1
			");
		$a = '';
		foreach($q as $k=>$v) if(!empty($v)) $a .= '<div><strong>'.$k.'</strong> : '.$v.'</div>';
		echo "document.getElementById('geoListCity').innerHTML='".$a."';";
		echo "document.getElementById('geoCheckCity').value='".$q->name."';";
		echo "wpGeonameCityMap('".$q->name."','".$q->latitude."','".$q->longitude."');";
	} ?>
		
	</script>
	<?php
}
function wpGeonames_admin_edit() {
	global $wpdb;
	$GgeoType = (!empty($_GET['geoType'])?preg_replace("/[^a-zA-Z0-9_,-]/","", $_GET['geoType']):'');
	$geoToka = wp_create_nonce('geoToka');
	$o = '';
	if(!empty($_GET['geoid']) && !empty($_GET['geodata'])) {
		$a = stripslashes(strip_tags($_GET['geodata']));
		$id = intval($_GET['geoid']);
		$wpdb->update($wpdb->base_prefix.'geonames', array('name'=>$a), array('geonameid'=>$id));
		echo '<script>window.location.replace("options-general.php?page=wpGeonames-options&geotab=edit");</script>';
		exit;
	}
	else if(!empty($_GET['geoSearch'])) {
		$a = strip_tags($_GET['geoSearch']);
		$o = '<hr />'; $w = '';
		if($GgeoType=='region') $w = "and feature_class='A' and feature_code IN ('ADM1','ADM2','PCLD')";
		else if($GgeoType=='city') $w = "and feature_class='P'";
		$q = $wpdb->get_results("SELECT
				geonameid,
				name,
				country_code
			FROM
				".$wpdb->base_prefix."geonames
			WHERE
				name LIKE '%".$a."%'
				".$w);
		if(!empty($q)) {
			foreach($q as $v) {
				$o .= '<div>'.$v->geonameid;
				$o .= '<input type="text" name="geadata'.$v->geonameid.'" value="'.$v->name.'" style="margin: 0 10px;width:360px;" />';
				$o .= '<strong style="margin-right:10px;">'.$v->country_code.'</strong>';
				$o .= '<input type="button" class="button-primary" value="'.__('Change','wpGeonames').'" onClick="document.forms[\'geoEdit\'].elements[\'geodata\'].value=document.forms[\'geoEdit\'].elements[\'geadata'.$v->geonameid.'\'].value;document.forms[\'geoEdit\'].elements[\'geoid\'].value='.$v->geonameid.';document.forms[\'geoEdit\'].submit();" />';
				$o .= '</div>';
			}
		}
	}
	?>
	<h2><?php _e('Edit Datas', 'wpGeonames'); ?></h2>
	<form name="geoEdit" action="" method="GET">
		<input type="hidden" name="page" value="wpGeonames-options" />
		<input type="hidden" name="geotab" value="edit" />
		<input type="hidden" name="geoid" value="" />
		<input type="hidden" name="geodata" value="" />
		<input type="hidden" name="geoToka" value="<?php echo $geoToka; ?>" />
		<div style="float:left;margin-right:20px;overflow:hidden;">
			<label><?php _e('Type of data','wpGeonames') ?></label><br />
			<select id="geoType" name="geoType">
				<option value="region" <?php if($GgeoType=='region') echo 'selected'; ?>><?php _e('Region','wpGeonames') ?></option>
				<option value="city" <?php if($GgeoType=='city') echo 'selected'; ?>><?php _e('City','wpGeonames') ?></option>
			</select>
		</div>
		<div style="float:left;margin-right:20px;overflow:hidden;">
			<label><?php _e('Data','wpGeonames') ?></label><br />
			<input type="text" name="geoSearch" value="<?php if(!empty($_GET['geoSearch'])) echo $_GET['geoSearch']; ?>" />
		</div>
		<div class="submit">
			<input type="submit" class="button-primary" onClick="document.forms['geoEdit'].elements['geodata'].value='';document.forms['geoEdit'].elements['geoid'].value='';" value="<?php _e('Search','wpGeonames') ?>" />
		</div>
		
		<?php echo $o; ?>
	</form>

	<?php
}
function wpGeonames_admin_help() {
	global $wpdb;
	?>
	<h2><?php _e('Location Taxonomy Form', 'wpGeonames'); ?></h2>
	<p><?php _e('You can create a simple location taxonomy Form with the shortcode <b>[wp-geonames]</b>. The options are as follows :', 'wpGeonames'); ?></p>
	<ul style="margin-left:30px;list-style:disc;">
		<li><?php _e('Name and ID of the select Country field (default=geoCountry) : id1=country', 'wpGeonames'); ?></li>
		<li><?php _e('Name and ID of the select Region field (default=geoRegion) : id2=state', 'wpGeonames'); ?></li>
		<li><?php _e('Name and ID of the input City field (default=geoCity) : id3=city', 'wpGeonames'); ?></li>
		<li><?php _e('Name of the JSON output var (default=geoRow) : out=citydata', 'wpGeonames'); ?></li>
		<li><?php _e('Max number of proposal city (default=10) : nbcity=5', 'wpGeonames'); ?></li>
		<li><?php _e('Display the OpenStreetMap (default=0) : map=1', 'wpGeonames'); ?></li>
		<li><?php _e('OpenStreetMap initial zoom (default=9) : zoom=10', 'wpGeonames'); ?></li>
	</ul>
	<p><?php _e('Example : <b>[wp-geonames zoom=12 map=1 id1=ctr id2=reg id3=cit]</b>.', 'wpGeonames'); ?></p>
	<p><?php _e('You can also adapt the form to your style by changing the <u>templates/wp-geonames_location_taxonomy.php</u> file and moving it to your theme.', 'wpGeonames'); ?></p>
	<p>Enjoy ! <?php echo convert_smilies(';-)'); ?></p>
	<?php
}
function wpGeonames_addZip($url,$f,$t=0) {
	if(!current_user_can("administrator")) die;
	if(empty($_REQUEST['geoToka']) || !wp_verify_nonce($_REQUEST['geoToka'],'geoToka')) return;
	$PwpGeonamesAdd = (!empty($f['wpGeonamesAdd'])?strip_tags(stripslashes(filter_var($f['wpGeonamesAdd'], FILTER_SANITIZE_URL))):'');
	//
	set_time_limit(300); // default is 30
	$upl = wp_upload_dir();
	if($PwpGeonamesAdd=='geoManual') {
		global $geoManual;
		$t = $upl['basedir'].$geoManual;
	}
	if(!is_dir($upl['basedir'].'/zip/')) mkdir($upl['basedir'].'/zip/');
	if(!$t) {
		// 1. Get ZIP from URL - Copy to uploads/zip/ folder
		if(!copy($url.$PwpGeonamesAdd, $upl['basedir'].'/zip/'.$PwpGeonamesAdd)) {
			_e('Failure in the download of the zip.','wpGeonames');
			die();
		}
		// 2. Extract ZIP in uploads/zip/
		$zip = new ZipArchive;
		if($zip->open($upl['basedir'].'/zip/'.$PwpGeonamesAdd)===TRUE) {
			$zip->extractTo($upl['basedir'].'/zip/');
			$zip->close();
		}
		else {
			_e('Failure in the extraction of the zip.','wpGeonames');
			die();
		}
	// 3. Read file and put data in array
		$handle = @fopen($upl['basedir'].'/zip/'.substr($PwpGeonamesAdd,0,strlen($PwpGeonamesAdd)-4).'.txt', 'r');
	}
	else $handle = @fopen($t, 'r');
	$e = array(); $g = ''; $c = 0; $fe = 'AP';
	// if(!empty($f['wpGeoA'])) $fe .= "A";
	if(!empty($f['wpGeoH'])) $fe .= "H";
	if(!empty($f['wpGeoL'])) $fe .= "L";
	// if(!empty($f['wpGeoP'])) $fe .= "P";
	if(!empty($f['wpGeoR'])) $fe .= "R";
	if(!empty($f['wpGeoS'])) $fe .= "S";
	if(!empty($f['wpGeoT'])) $fe .= "T";
	if(!empty($f['wpGeoU'])) $fe .= "U";
	if(!empty($f['wpGeoV'])) $fe .= "V";
	if($handle) {
		$a = array('PPL'=>1,'PPLA'=>1, 'PPLA2'=>1, 'PPLA3'=>1, 'PPLA4'=>1, 'PPLC'=>1);
		while(($v = fgets($handle, 8192))!==false) {
			$b = 0;
			$v = str_replace('"',' ',$v);
			$e = explode("\t", $v);
			if(!empty($e[0]) && isset($e[18]) && !empty($e[6]) && strpos($fe,$e[6])!==false) {
				if('P'==$e[6] && !empty($f['wpGeoCity']) && !isset($a[$e[7]])) continue; // only city in Feature_code ($a)
				++$c;
				$g .= '("'.$e[0].'","'.$e[1].'","'.(isset($f['wpGeo2'])?$e[2]:'').'","'.(isset($f['wpGeo3'])?$e[3]:'').'","'.$e[4].'","'.$e[5].'","'.$e[6].'","'.$e[7].'","'.$e[8].'","'.$e[9].'","'.$e[10].'","'.$e[11].'","'.(isset($f['wpGeo12'])?$e[12]:'').'","'.(isset($f['wpGeo13'])?$e[13]:'').'","'.(isset($f['wpGeo14'])?$e[14]:'').'","'.(isset($f['wpGeo15'])?$e[15]:'').'","'.(isset($f['wpGeo16'])?$e[16]:'').'","'.(isset($f['wpGeo17'])?$e[17]:'').'","'.$e[18].'"),';
			}
			if($c>5000) {
				wpGeonames_addDb($g);
				$c = 0; $g = '';
			}
		}
		wpGeonames_addDb($g);
		fclose($handle);
	}
	@unlink($upl['basedir'].'/zip/'.substr($PwpGeonamesAdd,0,strlen($PwpGeonamesAdd)-4).'.txt');
	@unlink($upl['basedir'].'/zip/'.$PwpGeonamesAdd);
	wpGeonames_update_options();
	return __('Done, data are in base.', 'wpGeonames');
}
function wpGeonames_addDb($g) {
	if(!current_user_can("administrator")) die;
	global $wpdb;
	$wpdb->query("INSERT IGNORE INTO ".$wpdb->base_prefix."geonames
		(geonameid,
		name,
		asciiname,
		alternatenames,
		latitude,
		longitude,
		feature_class,
		feature_code,
		country_code,
		cc2,
		admin1_code,
		admin2_code,
		admin3_code,
		admin4_code,
		population,
		elevation,
		dem,
		timezone,
		modification_date) 
		VALUES".substr($g,0,strlen($g)-1));
}
function wpGeonames_clear() {
	if(!current_user_can("administrator")) die;
	if(empty($_REQUEST['geoToka']) || !wp_verify_nonce($_REQUEST['geoToka'],'geoToka')) return;
	global $wpdb;
	$q = $wpdb->query("TRUNCATE TABLE ".$wpdb->base_prefix."geonames");
	// ******* Patch V1.4 - Add INDEX **************
	$a = $wpdb->get_results("SHOW INDEX FROM ".$wpdb->base_prefix."geonames WHERE Column_name='cc2'");
	if(empty($a)) $wpdb->query("ALTER TABLE ".$wpdb->base_prefix."geonames ADD INDEX `index1` (`feature_class`,`feature_code`(3),`country_code`,`cc2`(2),`name`(3))");
	// *********************************************
	wpGeonames_update_options();
	if($q) return __('Done, table is empty.', 'wpGeonames');
	else return __('Failed !', 'wpGeonames');
}
function wpGeonames_postalClear() {
	if(!current_user_can("administrator")) die;
	if(empty($_REQUEST['geoToka']) || !wp_verify_nonce($_REQUEST['geoToka'],'geoToka')) return;
	global $wpdb;
	$q = $wpdb->query("TRUNCATE TABLE ".$wpdb->base_prefix."geonamesPostal");
	wpGeonames_update_options();
	if($q) return __('Done, table is empty.', 'wpGeonames');
	else return __('Failed !', 'wpGeonames');
}
function wpGeonames_postalAddZip($url,$f) {
	if(!current_user_can("administrator")) die;
	if(empty($_REQUEST['geoToka']) || !wp_verify_nonce($_REQUEST['geoToka'],'geoToka')) return;
	$PwpGeonamesPostalAdd = (!empty($f['wpGeonamesPostalAdd'])?strip_tags(stripslashes(filter_var($f['wpGeonamesPostalAdd'], FILTER_SANITIZE_URL))):'');
	//
	set_time_limit(300); // default is 30
	$upl = wp_upload_dir();
	if(!is_dir($upl['basedir'].'/zip/')) mkdir($upl['basedir'].'/zip/');
	// 1. Get ZIP from URL - Copy to uploads/zip/ folder
	if(!copy($url.$PwpGeonamesPostalAdd, $upl['basedir'].'/zip/'.$PwpGeonamesPostalAdd)) {
		_e('Failure in the download of the zip.','wpGeonames');
		die();
	}
	// 2. Extract ZIP in uploads/zip/
	$zip = new ZipArchive;
	if($zip->open($upl['basedir'].'/zip/'.$PwpGeonamesPostalAdd)===TRUE) {
		$zip->extractTo($upl['basedir'].'/zip/');
		$zip->close();
	}
	else {
		_e('Failure in the extraction of the zip.','wpGeonames');
		die();
	}
	// 3. Read file and put data in array
	$handle = @fopen($upl['basedir'].'/zip/'.substr($PwpGeonamesPostalAdd,0,strlen($PwpGeonamesPostalAdd)-4).'.txt', 'r');
	//
	$e = array(); $g = ''; $c = 0;
	if($handle) {
		while(($v = fgets($handle, 8192))!==false) {
			$b = 0;
			$v = str_replace('"',' ',$v);
			$e = explode("\t", $v);
			if(!empty($e[0]) && isset($e[11])) {
				++$c;
				$g .= '("'.$e[0].
					'","'.$e[1].
					'","'.$e[2].
					'","'.(isset($f['wpGeoPostal3'])?$e[3]:'').
					'","'.(isset($f['wpGeoPostal4'])?$e[4]:'').
					'","'.(isset($f['wpGeoPostal5'])?$e[5]:'').
					'","'.(isset($f['wpGeoPostal6'])?$e[6]:'').
					'","'.(isset($f['wpGeoPostal7'])?$e[7]:'').
					'","'.(isset($f['wpGeoPostal8'])?$e[8]:'').
					'","'.(isset($f['wpGeoPostal9'])?$e[9]:'').
					'","'.(isset($f['wpGeoPostal10'])?$e[10]:'').
					'","'.(isset($f['wpGeoPostal11'])?$e[11]:'').
					'"),';
			}
			if($c>5000) {
				wpGeonames_postalAddDb($g);
				$c = 0; $g = '';
			}
		}
		wpGeonames_postalAddDb($g);
		fclose($handle);
	}
	@unlink($upl['basedir'].'/zip/'.substr($PwpGeonamesPostalAdd,0,strlen($PwpGeonamesPostalAdd)-4).'.txt');
	@unlink($upl['basedir'].'/zip/'.$PwpGeonamesPostalAdd);
	wpGeonames_update_options();
	return __('Done, data are in base.', 'wpGeonames');
}
function wpGeonames_postalAddDb($g) {
	if(!current_user_can("administrator")) die;
	global $wpdb;
	$wpdb->query("INSERT IGNORE INTO ".$wpdb->base_prefix."geonamesPostal
		(country_code,
		postal_code,
		place_name,
		admin1_name,
		admin1_code,
		admin2_name,
		admin2_code,
		admin3_name,
		admin3_code,
		latitude,
		longitude,
		accuracy) 
		VALUES".substr($g,0,strlen($g)-1));
}
function wpGeonames_get_postalCheck($iso,$postal) {
	if(strlen($postal)<3) return;
	global $wpdb; $o = '';
	$q = $wpdb->get_results("SELECT *
		FROM
			".$wpdb->base_prefix."geonamesPostal
		WHERE
			country_code='".$iso."'
			and postal_code LIKE '%".$postal."%' 
		ORDER BY postal_code
		LIMIT 200
		");
	if($q) {
		$c = 0;
		$o .= '<table class="widefat">';
		foreach($q as $r) {
			if(!$c) {
				$o .= '<thead><tr>';
				foreach($r as $k=>$v) $o .= '<th>'.str_replace('_','<br>',$k).'</th>';
				$o .= '</tr></thead>';
			}
			$o .= '<tr>';
			foreach($r as $k=>$v) $o .= '<td>'.$v.'</td>';
			$o .= '</tr>';
			++$c;
		}
		$o .= '</table>';
	}
	return $o;
}
function wpGeoname_settings_link($links) {
	$links[] = '<a href="options-general.php?page=wpGeonames-options">'.__('Settings', 'wpGeonames').'</a>';
	return $links;
}
// ********* OTHER TOOLS *********
function wpGeonames_get_all_region() {
	//
	global $wpdb;
	$out = "";
	$q = $wpdb->get_results("SELECT
			country_code,
			cc2,
			name
		FROM
			".$wpdb->base_prefix."geonames
		WHERE
			feature_code='ADM1'
			and (feature_class='A' or feature_code='PCLD')
		ORDER BY cc2,country_code,name
		");
	foreach($q as $k=>$v) {
		if($v->cc2!='') $q[$k]->country_code = $v->cc2;
	}
	usort($q, "wpGeonames_sortCountry2");
	$a = array();
	foreach($q as $r) {
		if(!isset($a[$r->country_code.$r->name])) {
			$out .= "('r', '".$r->name."', '".$r->country_code."', ''),\r\n";
			$a[$r->country_code.$r->name] = 1;
		}
	}
	file_put_contents(dirname(__FILE__).'/liste_region.txt', $out);
}
function wpGeonames_sortCountry2($a,$b) {
	if($a->country_code==$b->country_code) return strcmp($a->name,$b->name);
	return strcmp($a->country_code,$b->country_code);
}
if(file_exists(dirname(__FILE__).'/other_tools.php')) include dirname(__FILE__).'/other_tools.php';
//
?>
