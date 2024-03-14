<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/**
 * 	Contributors: mooveagency
 *  Plugin Name: Change Taxonomy Buttons
 *  Plugin URI: http://www.mooveagency.com
 *  Description: Allows to change taxonomy metabox buttons type to Radio, Checkbox, Checkbox with Select All button.
 *  Version: 1.3.5
 *  Author: Moove Agency
 *  Author URI: http://www.mooveagency.com
 *  License: GPLv2
 *  Text Domain: moove
 */
define( 'MOOVE_RADIOSELECT_VERSION', '1.3.5' );

/**
 * Star rating on the plugin listing page
 */
if ( ! function_exists('moove_radioselect_add_plugin_meta_links') ) {
function moove_radioselect_add_plugin_meta_links($meta_fields, $file) {
  if ( plugin_basename(__FILE__) == $file ) :
    $plugin_url = "https://wordpress.org/support/plugin/select-all-categories-and-taxonomies-change-checkbox-to-radio-buttons/reviews/?rate=5#new-post";
    $meta_fields[] = "<a href='" . esc_url($plugin_url) ."' target='_blank' title='" . esc_html__('Rate', 'moove') . "'>
          <i class='moove-radioselect-star-rating'>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "</i></a>";      
     
  endif;
  return $meta_fields;
  }
}
add_filter('plugin_row_meta' , 'moove_radioselect_add_plugin_meta_links', 10, 2);

include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-view.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-options.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-controller.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-actions.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-functions.php' );

