<?php
/*
   Plugin Name: Featured Image Pro
   Description: Featured Image & Post Masonry Grid Widget & Shortcode
   Version: 5.15
   Author: A. R. Jones
   Author URI: http://shooflysolutions.com
 */
/*
   Copyright (C)  2017 Shoofly Solutions
   Contact me at http://www.shooflysolutions.com
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.
   You should have received a copy of the GNU General Public License
   along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
register_activation_hook(__FILE__, 'featured_image_pro_database_activate');

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !function_exists( 'is_admin_user' ) )
{
	function is_admin_user(){
	  require_once(ABSPATH.'wp-includes/pluggable.php'); return ( current_user_can(  'administrator' ) ) ; //or 'manage_options'
	}
}
$rootdir = plugin_dir_path( __FILE__ );

    // Signal that parent SDK was initiated.
do_action( '_loaded' );

$coredir = $rootdir . 'core/';
$advanceddir = $rootdir . 'advanced/';
$plugindir = plugin_dir_path( __FILE__ );
define( "PROTO_ROOT_DIR", $rootdir );
define( "PROTO_CORE_DIR", $coredir );

require_once $coredir . 'featured-image-pro-exec.php';
require_once $coredir . 'featured-image-pro-widget.php';
require_once $coredir . 'functions/proto-global.php';
//if ( is_admin_user() ) {
	require_once $coredir . 'functions/featured-image-pro-notices.php';
//}
 $options = get_option( 'featured_image_pro_settings' );
 $advanced = isset( $options['advanced'] ) ? true : false;
if ( $advanced )
{
	require_once $advanceddir . 'proto-options3.php';
	require_once $advanceddir . 'proto-client.php';
	require_once $advanceddir . 'featured-image-pro-grow.php';
	require_once $advanceddir . 'wp-list-local.php';
}
require_once $rootdir . 'featured-image-pro-admin.php';
$settings = get_option( 'featured_image_pro_settings');


/**
 * featured_image_pro_database_activate function.
 * Activate the database and load sample grids
 * @access public
 * @return void
 */
function featured_image_pro_database_activate() {
	global $wpdb;

	$db_name = $wpdb->prefix . 'proto_masonry_grids';
	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$db_name'") != $db_name)
	{
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$sql = "CREATE TABLE $db_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  description tinytext,
		  options text NOT NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql );

	}
}
/*
	 * Add Query Vars
	 */
if ( !function_exists( 'featured_image_pro_post_add_query_vars' ) ) {
	/**
	 * featured_image_pro_post_add_query_vars function.
	 * Add a query var for single posts (not guaranteed to work)
	 *
	 * @access public
	 * @param mixed   $aVars
	 * @return void
	 */
	function featured_image_pro_post_add_query_vars( $aVars ) {
		$aVars[] .= 'psp_masonry_page';
		return $aVars;
	}
	add_filter( 'query_vars', 'featured_image_pro_post_add_query_vars' );
}