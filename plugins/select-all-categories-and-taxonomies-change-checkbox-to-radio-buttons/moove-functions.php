<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/**
 * Moove_Functions File Doc Comment
 *
 * @category 	Moove_Functions
 * @package   moove-radio-select
 * @author    Moove Agency
 */


if ( ! function_exists( 'ctb_get_plugin_directory_url' ) ) :
  /**
   * Relative path of the User Activity plugin
   */
  function ctb_get_plugin_directory_url() {
    return plugin_dir_url( __FILE__ );
  }
endif;

if ( ! function_exists( 'ctb_get_plugin_directory' ) ) :
  /**
   * Relative path of the User Activity plugin
   */
  function ctb_get_plugin_directory() {
    return dirname( __FILE__ );
  }
endif;