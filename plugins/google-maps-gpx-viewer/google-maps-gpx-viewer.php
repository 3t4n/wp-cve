<?php
/*
Plugin Name: Google-Maps-GPX-Viewer
Plugin URI: http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/
Description: Google Maps V3, full screen buttton, OpenStreeMap, Web Map Service (WMS) support, GPX and/or/mixed KML file, Marker/Infowin: lat-lon or address, multiple maps/post, traffic layer, streetview, android
Version: 3.6
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
*/

define('GMAPX_VERSION', 3.6);
define('GPX_GM_PLUGIN', 'google-maps-gpx-viewer');
define('WMS', 'wms');
define('OSM', 'osm');
define('OSGEO', 'osgeo');
define('PLUGIN_ROOT', dirname(__FILE__));
require ( dirname(__FILE__) . "/php/map_functions.php");

// Main function to generate google map
$instance_gmap_gpx = 0; // our scripts only once 
$map_0_atts = null;
$gpx_gmap_visual = false;
$gpx_gmap_elevation = false;
function gmapv3($attr) {
	global $post, $instance_gmap_gpx, $map_0_atts, $gpx_gmap_visual, $gpx_gmap_elevation;
	$post_ID = $post->ID; // get DB pois
	// default atts
	$attr = shortcode_atts(array('lat'   => '', 'lon'    => '',	'z' => '', 'x' => 0, 'y' => 0,
									'maptype' => ''.get_option("gmap_v3_gpx_defMaptype", "TERRAIN").'',
									'address' => '', 'marker' => '', 'markerimage' => '', 'infowindow' => '', 'mtoggle' => '',
									'gpx' => '', 'kml' => '', 'elevation' => '', 'download' => '',
									'traffic' => 'no', 'bike' => 'no', 'fusion' => '', 'pano' => '', 'panotag' => '',  'places' => '',  'p_search' => '',
									// 'poi_db' => '',
									'style' => 'width:600px; height:400px; border:1px solid gray;'
							), $attr);
	require ( dirname(__FILE__) . "/php/load_jsapi.php");
	//  first map may affect our widgets
	if ($instance_gmap_gpx == 1){
		$map_0_atts  = $attr;
	}
	// users have access to special content 
	$access = 0;								
	if (current_user_can("subscriber") || current_user_can("editor") || current_user_can("contributor") || current_user_can("administrator")){
		$access = 1;								
	}
	require ( dirname(__FILE__) . "/php/insert_div.php");
	
	$retval .= '
	<script type="text/javascript">
	';
		if ($instance_gmap_gpx == 1){
			require ( dirname(__FILE__) . "/php/init_maps.php");
		}
		
		$retval .= '
			var ' . $map_id . '; 
			google.setOnLoadCallback(function() {		';
		
		//init map
		$retval .= '
			' . $map_id . ' = init_map("' . $attr['maptype'] . '", "' . $map_id . '", ' . $access . ');
			load_map(' . $map_id . ', "'. $attr['lat'] . '", "' . $attr['lon'] . '", "' . $attr['z'] . '");	
			';

		// elevation profile
		$elevationProfile = "true";
		if ((get_option("gmap_v3_gpx_elevationProfile") != 1 || $attr['elevation'] == 'no') && $attr['elevation'] != 'yes')
			$elevationProfile = "false";
		$retval .= '
			' . $map_id . '["elevation"] = ' . $elevationProfile . '; ';

		// download link
		$downloadLink = "true";
		if ((get_option("gmap_v3_gpx_downloadLink") != 1 || $attr['download'] == 'no') && $attr['download'] != 'yes')
			$downloadLink = "false";
		$retval .= '
			' . $map_id . '["download"] = ' . $downloadLink . '; ';
		// marker manager poi-db/gpx-file	
		if($attr['mtoggle'] != '')
		$retval .= '
		' . $map_id . '.g_mToggle = true; // marker manager
		';
		// check for gpx
		if($attr['gpx'] != '') 
		{
			$gpxfile = htmlspecialchars_decode($attr['gpx']);		
			$retval .= '
			' . $map_id . '.g_seCookie = false; // no cookie
			' . $map_id . '.g_showCnt++;
			showGPX(' . $map_id . ', "' . $gpxfile . '");
			';

		}
		// check for kml
		if($attr['kml'] != '') 
		{
			require( dirname(__FILE__) . "/php/kml_layer.php");
		}
		// fusion layer
		if($attr['fusion'] != '')
		{
			require( dirname(__FILE__) . "/php/fusion_layer.php");
		}
		// address
		if($attr['address'] != '')
		{
			require( dirname(__FILE__) . "/php/adr_layer.php");
		}

		// lat-lon with marker/infowin 
		if ($attr['marker'] != '' && !($attr['lat'] == "" || $attr['lon'] == ""))
		{
			$markerPosition = $map_id . '.g_latlon';
			require( dirname(__FILE__) . "/php/marker_layer.php");
		}

		// traffic layer
		if($attr['traffic'] == 'yes')
		{
			$retval .= '
			var trafficLayer = new google.maps.TrafficLayer();
			trafficLayer.setMap(' . $map_id . ');
			';
		}
		// bike layer
		if($attr['bike'] == 'yes')
		{
			$retval .= '
				var bikeLayer = new google.maps.BicyclingLayer();
				bikeLayer.setMap(' . $map_id . ');
			';
		}
		// panoramio layer
		if($attr['pano'] == 'yes')
		{
			require( dirname(__FILE__) . "/php/pano_layer.php");
		}		
		// offset
		require( dirname(__FILE__) . "/php/set_offset.php");

		// if we have POI_DB enabled load them
 		if(get_post_meta( $post->ID, 'gmap_gpx_map_switch', true) == 'on' && $instance_gmap_gpx == 1){ 
			require_once ( WIDGET_ROOT . "/poi/gm_pois_onload.php"); // poi/post js loader
			$retval .= '
				' . $map_id . '.gmap_poi_db = true;
				OnLoadGpxPoiDb(' . $map_id . ');
			';
		} else {
			$retval .= '		
				' . $map_id . '.gmap_poi_db = false; 
			';
		}
	$retval .= '
			post_init(' . $map_id . ');
		';
		
		// places layer
		if($attr['places'] == 'yes')
		{
			if(file_exists ( PLUGIN_ROOT . '/php/places_layer.php')){
				require( dirname(__FILE__) . "/php/places_layer.php");
				$retval .= '
					places_LoadCallback();
				';			
			} else {
				$retval .= '
					alert("'.__("Google Places failed!", GPX_GM_PLUGIN).'\n'.__("Licence required!", GPX_GM_PLUGIN).' - '.__("Please contact the Plugin Autor.", GPX_GM_PLUGIN).'");
				';						
			}
		}
		$retval .= '
			});
		';
		$retval .= '
		</script>
		';
	return $retval;
}
	//Add links on the plugin page
	add_filter('plugin_row_meta', 'add_plugin_links', 10, 2);
	function add_plugin_links($links, $file) {
		if ( $file == plugin_basename( __FILE__ )) {
			$links[] = '<a href="options-general.php?page=google-maps-gpx-viewer/php/options.php">' . __('Settings', GPX_GM_PLUGIN) . '</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q8PALXTRBLFP8">' . __('Donate', GPX_GM_PLUGIN) . '</a>';
		}
		return $links;
	}
?>