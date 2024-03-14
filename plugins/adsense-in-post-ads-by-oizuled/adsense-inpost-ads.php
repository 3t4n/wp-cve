<?php
   /*
   Plugin Name: In-Post Ads
   Plugin URI: https://amplifyplugins.com
   Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6UHZNUWTHW9W2
   Description: A plugin to display a shortcode to insert ads inside your posts.
   Version: 2.0.4
   Author: AMP-MODE
   Author URI: https://scottdeluzio.com
   License: GPL2
   Text Domain: adsense-inpost-ads
   */

	/*  Copyright 2016  Scott DeLuzio  (email : me (at) scottdeluzio.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
require_once __DIR__ . '/blocks/adsense-ad-a.php';
require_once __DIR__ . '/blocks/adsense-ad-b.php';
require_once __DIR__ . '/blocks/adsense-ad-c.php';
/* Load Text Domain */
add_action('plugins_loaded', 'adsense_inpost_ads_plugin_init');
function adsense_inpost_ads_plugin_init() {
  load_plugin_textdomain( 'adsense-inpost-ads', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

/*
 * Includes for Adsense Inpost Ads
 */
if ( ! defined( 'ADSENSE_INPOST_ADS' ) ) {
	define( 'ADSENSE_INPOST_ADS', __FILE__ );
}
if( ! defined( 'ADSENSE_INPOST_ADS_PLUGIN_DIR' ) ) {
	define( 'ADSENSE_INPOST_ADS_PLUGIN_DIR', dirname( __FILE__ ) );
}
if( ! defined( 'ADSENSE_INPOST_ADS_PLUGIN_URL' ) ) {
  define( 'ADSENSE_INPOST_ADS_PLUGIN_URL', plugins_url( '', __FILE__ ) );
}
if ( ! defined( 'ADSENSE_INPOST_ADS_PLUGIN_BASENAME' ) ) {
  define( 'ADSENSE_INPOST_ADS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'ADSENSE_INPOST_ADS_VERSION' ) ) {
  define( 'ADSENSE_INPOST_ADS_VERSION', '2.0.4' );
}

$aip_options = get_option( 'aip_settings' );

include( ADSENSE_INPOST_ADS_PLUGIN_DIR . '/includes/admin-settings-page.php' );
include( ADSENSE_INPOST_ADS_PLUGIN_DIR . '/includes/shortcodes.php' );
include( ADSENSE_INPOST_ADS_PLUGIN_DIR . '/includes/updater.php' );