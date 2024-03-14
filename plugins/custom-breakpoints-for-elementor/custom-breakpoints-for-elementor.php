<?php
/**
 * Plugin Name: Custom Breakpoints for Elementor
 * Description: Elementor Custom Breakpoint Created by <a href="https://master-addons.com/demos/custom-breakpoints/" target="_blank">Master Addons</a>
 * Plugin URI:  https://master-addons.com
 * Version:     2.0.2
 * Author:      Jewel Theme
 * Author URI:  https://master-addons.com/demos/custom-breakpoints/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: master-custom-breakpoint
 * Domain Path: /languages
 * Elementor tested up to: 3.5.3
 * Elementor Pro tested up to: 3.5.0
 */


defined( 'ABSPATH' ) || exit;

define( 'JLTMA_MCB_VERSION', '1.0.6' );
define( 'JLTMA_MCB_TD', 'master-custom-breakpoint' );
define( 'JLTMA_MCB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'JLTMA_MCB_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'JLTMA_MCB_PLUGIN_DIR', plugin_basename( __FILE__ ) );


require plugin_dir_path( __FILE__ ) . 'class-master-custom-breakpoint.php';


// Activation and Deactivation hooks
if ( class_exists('\\MasterCustomBreakPoint\\JLTMA_Master_Custom_Breakpoint') ) {
    register_activation_hook( __FILE__ , array('\\MasterCustomBreakPoint\\JLTMA_Master_Custom_Breakpoint', 'jltma_mcb_plugin_activation_hook'));
}