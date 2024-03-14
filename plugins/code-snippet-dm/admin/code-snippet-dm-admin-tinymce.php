<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       devmaverick.com
 * @since      1.0.0
 *
 * @package    Code_Snippet_Dm
 * @subpackage Code_Snippet_Dm/admin/partials
 */

class CSDM_Admin_Tinymce {

    function __construct() {
      if ( is_admin() ) {
       add_action( 'init', array(  $this, 'csdm_setup_tinymce_plugin' ) );
    }
  }



    /**
  * Check if the current user can edit Posts or Pages, and is using the Visual Editor
  * If so, add some filters so we can register our plugin
  */
  function csdm_setup_tinymce_plugin() {

  // Check if the logged in WordPress User can edit Posts or Pages
  // If not, don't register our TinyMCE plugin

  if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
              return;
  }

  // Check if the logged in WordPress User has the Visual Editor enabled
  // If not, don't register our TinyMCE plugin
  if ( get_user_option( 'rich_editing' ) !== 'true' ) {
  return;
  }

  // Setup some filters
  add_filter( 'mce_external_plugins', array( &$this, 'csdm_add_tinymce_plugin' ) );
  add_filter( 'mce_buttons', array( &$this, 'csdm_add_tinymce_toolbar_button' ) );

      }

          /**
    * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance
    *
    * @param array $plugin_array Array of registered TinyMCE Plugins
    * @return array Modified array of registered TinyMCE Plugins
    */
    function csdm_add_tinymce_plugin( $plugin_array ) {

    $plugin_array['csdm_custom_button_snippet'] = plugin_dir_url( __FILE__ ) . 'js/tinymce_button.js';
    return $plugin_array;

    }

        /**
    * Adds a button to the TinyMCE / Visual Editor which the user can click
    * to insert a link with a custom CSS class.
    *
    * @param array $buttons Array of registered TinyMCE Buttons
    * @return array Modified array of registered TinyMCE Buttons
    */
    function csdm_add_tinymce_toolbar_button( $buttons ) {

    array_push( $buttons, '|', 'csdm_custom_button_snippet' );
    return $buttons;
    }

//
}