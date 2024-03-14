<?php
/*
 * Plugin Name:       Gutenberg Blocks and Page Layouts - Attire Blocks
 * Plugin URI:        https://wpattire.com/gutenberg-blocks-and-page-layouts/
 * Description:       A collection of beautifully designed, highly customisable gutenberg UI blocks and layouts to make your website look exactly the way you want.
 * Version: 		  1.9.2
 * Author:            WP Attire
 * Author URI:        https://wpattire.com
 * Text Domain:       attire-blocks
 * Requires at least: 5.0
 * Tested up to: 	  6.4
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Attire\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require_once "lib/ATBSCustomCss.php";
require_once "lib/Api.php";
require_once "admin/AttireBlocksSettings.php";

require 'lib/auto-upload-images/functions.php';
require 'lib/auto-upload-images/AttireImageAutoUpload.php';

$wp_aui = new AttireImageAutoUpload();
$wp_aui->run();

// Define Version
define( 'ATTIRE_BLOCKS_VERSION', '1.9.2' );

// Define Dir URL
define( 'ATTIRE_BLOCKS_DIR_URL', plugin_dir_url( __FILE__ ) );

// Define Physical Path
define( 'ATTIRE_BLOCKS_DIR_PATH', plugin_dir_path( __FILE__ ) );

// Language Load;
add_action( 'init', function () {
	load_plugin_textdomain( 'attire-blocks', false, basename( dirname( __FILE__ ) ) . '/languages/' );
} );

//Category register
add_filter( 'block_categories_all', function ( $categories, $post ) {

	$categories = array_merge(
		array(
			array(
				'slug'  => 'attire-blocks',
				'title' => __( 'Attire', 'attire-blocks' ),
			),
		),
		$categories
	);

	return $categories;
}, 10, 2 );

add_action(
	'plugins_loaded',
	function () {
		if ( class_exists( '\Attire\Blocks\ATBSCustomCss' ) ) {
			\Attire\Blocks\ATBSCustomCss::instance();
		}
	}
);


include __DIR__ . '/lib/__.php';
include __DIR__ . '/lib/Crypt.php';
include __DIR__ . '/lib/Session.php';
include __DIR__ . '/lib/Util.php';

// Enqueue JS and CSS
include __DIR__ . '/enqueue-scripts.php';

// Extend attire theme options
include __DIR__ . '/admin/theme/color-scheme.php';

//Dynamic blocks
include __DIR__ . '/blocks/dynamic/post-carousel/index.php';
include __DIR__ . '/blocks/dynamic/product-category/index.php';
include __DIR__ . '/blocks/dynamic/post-grid/index.php';
include __DIR__ . '/blocks/dynamic/table-of-content/index.php';
include __DIR__ . '/blocks/dynamic/voting/index.php';


if ( is_admin() ) {
	$settings = AttireBlocksSettings::getInstance();
}