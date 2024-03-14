<?php
//  Exit if accessed directly.
defined('ABSPATH') || exit;


//Lazyload Preloader Image
function bravepop_get_preloader(){
   return plugin_dir_url( __FILE__ ) . 'assets/images/preloader.png';
}

//Load Translation Files
add_action( 'init', 'bravepop_load_textdomain', 0 );
function bravepop_load_textdomain() {
   if ( !is_admin() ) {
     load_plugin_textdomain( 'bravepop', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
   }
}


/** Define plugin path constant */
if (!defined('BRAVEPOP_PLUGIN_PATH')) {
   define('BRAVEPOP_PLUGIN_PATH', plugin_dir_url(__FILE__));
}


/** Define plugin user country/ip constant */
add_action( 'wp_loaded', 'brave_set_user_geo_location', 0 );
function brave_set_user_geo_location(){
   global $bravepop_global;
   if (class_exists('BravePop_Geolocation') && empty($bravepop_global['user_geo_checked']) && !is_admin() ) {  
      $geolocation_instance = new BravePop_Geolocation();
      $user_ip_address = $geolocation_instance ? $geolocation_instance->get_ip_address() : '';
      $user_geolocation = $geolocation_instance->geolocate_ip( $user_ip_address );
      $country = $user_geolocation && $user_geolocation['country'] ? $user_geolocation['country'] : '';
      $country_code = $user_geolocation && $user_geolocation['iso_code'] ? $user_geolocation['iso_code'] : '';
   
      if($country){  $bravepop_global['user_country'] = $country;  }
      if($country_code){  $bravepop_global['user_country_code'] = $country_code;  }
      $bravepop_global['user_geo_checked'] = true;
   }
}


// Enqueue JS and CSS
include __DIR__ . '/lib/helpers/helpers.php';
include __DIR__ . '/lib/enqueue-scripts.php';

// Register Post types
include __DIR__ . '/lib/post-type_popup.php';

//Settings Class
include __DIR__ . '/lib/settings.php';

//Elements Init
include __DIR__ . '/lib/frontend/init.php';

// Init
include __DIR__ . '/lib/init.php';
include __DIR__ . '/lib/stats.php'; //Setup Stats DB
include __DIR__ . '/lib/rest/rest.php'; // Register Custom Rest Api routes
include __DIR__ . '/lib/render.php'; // Render Popup

// Intigrations Init
include __DIR__ . '/lib/integration/init.php';

//Submissions Init
include __DIR__ . '/lib/Submissions.php';
