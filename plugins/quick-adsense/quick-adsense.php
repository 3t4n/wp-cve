<?php
/**
 * Plugin Name: Quick Adsense
 * Plugin URI: http://quickadsense.com/
 * Description: Quick Adsense offers a quicker & flexible way to insert Google Adsense or any Ads code into a blog post.
 * Author: namithjawahar
 * Author URI: https://smartlogix.co.in/
 * Version: 2.8.7
 */
require_once dirname( __FILE__ ) . '/includes/loader.php';
require_once dirname( __FILE__ ) . '/includes/countries.php';
require_once dirname( __FILE__ ) . '/includes/defaults.php';
require_once dirname( __FILE__ ) . '/includes/controls.php';
require_once dirname( __FILE__ ) . '/includes/settings.php';
require_once dirname( __FILE__ ) . '/includes/widgets.php';
require_once dirname( __FILE__ ) . '/includes/quicktags.php';
require_once dirname( __FILE__ ) . '/includes/content.php';
require_once dirname( __FILE__ ) . '/includes/adsense.php';
require_once dirname( __FILE__ ) . '/includes/class-filehandler.php';
if ( ! class_exists( 'Mobile_Detect' ) ) {
	require_once dirname( __FILE__ ) . '/includes/vendor/MobileDetect/Mobile_Detect.php';
}
if ( ! class_exists( 'iriven\\GeoIPCountry' ) ) {
	require_once dirname( __FILE__ ) . '/includes/vendor/GeoIP/GeoIPCountry.php';
}
