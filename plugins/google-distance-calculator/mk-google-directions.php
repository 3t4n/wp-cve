<?php
/*
  Plugin Name: MK Google Directions
  Plugin URI: http://www.webtamarin.com/wordpress-plugins/mk-google-directions/
  Description: MK Google Direction uses Google Directions API. It enables use of Google Directions in your WordPress blog. It also give details of distance between two locations and also shows driving direction between two places. Use shortcode [MKGD] in page/post to use this plugin
  Version: 3.1  
  Author: Web Tamarin
  Author URI: http://www.webtamarin.com/
  Tags: Google Directions, Google Distance Calculator, Google Distance, Google Maps, Google Maps API
 */

global $wp_version;

// Wordppress Version Check
if (version_compare($wp_version, '6.0', '<')) {
  exit($exit_msg . " Please upgrade your wordpress.");
}


/*
 * Add Stylesheet & Scripts for the plugin
 */

add_action('wp_enqueue_scripts', 'mkgd_scripts');

function mkgd_scripts() {
  $lang = cmb2_get_option('mkgd_settings', 'mkgd_language');
  $gmap_key = cmb2_get_option('mkgd_settings', 'mkgd_gmaps_api_key');

  $google_api_js = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&language=' . $lang.'&key=' . $gmap_key;
  wp_enqueue_script('mkgd-google-map-places', $google_api_js, array('jquery'));
  wp_enqueue_script( 'mkgd-js', plugins_url('/js/mkgd.js', __FILE__), array('jquery'), '1.0.0', false );
  wp_register_style('mkgd-css', plugins_url('/css/mkgd-styles.css', __FILE__));
  wp_enqueue_style('mkgd-css');
}

require_once("mkgd-bootstrap.php");

//AIzaSyC6U9vzwdE03xSwtijIAesaWaTHoRtcISg

// Shortcode
// [MKGD id="12"]
function mkgd_stortcode( $atts ) {
	$a = shortcode_atts( array(
		'id' => ''
	), $atts );

  $settings = get_map_values($a['id']);
  //var_dump($settings);
  $mapID = $settings->id.rand(1,100);

  $html ='<div class="mkgd-wrap"><div class="mkg-header">';
  $html .= ($settings->hide_origin == 'on')?'<input type="hidden" id="txtSource-'.$mapID.'" value="'.$settings->origin.'" />':'Origin: <input type="text" id="txtSource-'.$mapID.'" value="'.$settings->origin.'" />';

          //$html .= '&nbsp; ';
          $html .= ($settings->hide_destination == 'on')?'<input type="hidden" id="txtDestination-'.$mapID.'" value="'.$settings->destination.'" />':'&nbsp;Destination: <input type="text" id="txtDestination-'.$mapID.'" value="'.$settings->destination.'" />';
          $html .= ($settings->hide_origin == 'on' && $settings->hide_destination == 'on')?'<strong>Origin:</strong> '.$settings->origin.' <strong></br>Destination:</strong> '.$settings->destination : '&nbsp;<input type="button" value="Get Route" onclick="GetRoute('.$mapID.', \''.$settings->unit_system.'\')" />';
          $html .= '<hr />';
          $html .= '<div class="mkgd-body">
          <div class="mkgdMap" id="dvMap-'.$mapID.'" style="width: '.$settings->width.'px; height: '.$settings->height.'px"></div>
          <div class="mkgdDirections" id="dvPanel-'.$mapID.'" class="dvPanel" style="width: '.$settings->width.'px; height: '.$settings->height.'px;overflow-y:scroll;"></div>
          </div>';
          $html .= '</div>';
          $html .= '</div>';
$html .='<script>
  google.maps.event.addDomListener(window, "load", function () {
  new google.maps.places.SearchBox(document.getElementById("txtSource-'.$mapID.'"));
  new google.maps.places.SearchBox(document.getElementById("txtDestination-'.$mapID.'"));
  directionsDisplay = new google.maps.DirectionsRenderer({ "draggable": true });
  GetRoute("'.$mapID.'", "'.$settings->unit_system.'");
});
</script>';

	return $html;
}
add_shortcode( 'MKGD', 'mkgd_stortcode' );