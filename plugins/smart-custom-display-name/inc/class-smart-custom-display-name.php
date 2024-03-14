<?php

/**
 * The Smart Custom Display Name core plugin class
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The core plugin class
 */
if ( !class_exists( 'PP_Smart_Custom_Display_Name' ) ) { 

  class PP_Smart_Custom_Display_Name extends PPF08_Plugin {
    
	/**
     * Admin Class
     *
     * @see    class-smart-custom-display-name-admin.php
     * @since  5.0.0
     * @var    object
     * @access private
     */
    private $admin;
    
    
    /**
     * do plugin init 
     */
    function plugin_init() {
		
		$this->add_actions( array( 
			'init'
		) );
      
      // we only need to do something if we're in admin
      if ( is_admin() ) {
       
        add_action( 'admin_footer-user-edit.php', array( $this, 'display_name_customizer_js' ) );
        add_action( 'admin_footer-profile.php', array( $this, 'display_name_customizer_js' ) );
        add_action( 'admin_head-user-edit.php', array( $this, 'display_name_customizer_css' ) );
        add_action( 'admin_head-profile.php', array( $this, 'display_name_customizer_css' ) );
        
      }
    }
	
	
	/**
     * init action
     */
    function action_init() {
      
      load_plugin_textdomain( 'smart-custom-display-name' );
      
      // since v 5.0.0
      $this->admin = $this->add_sub_class_backend( 'PP_Smart_Custom_Display_Name_Admin', 'class-smart-custom-display-name-admin', $this );

    }

    
    /*
     * add javascript to change display name field from a select box to an input field
     */
    function display_name_customizer_js() {
      ?>
      <script type="text/javascript">
        jQuery( document ).ready( function() { jQuery( '#display_name' ).parent().html( '<input type="text" name="display_name" id="display_name" value="' + jQuery( '#display_name' ).val() + '" class="regular-text" />' ); jQuery( '#first_name, #last_name, #nickname' ).unbind( 'blur' ); jQuery( 'label[for="display_name"]' ).html( jQuery( 'label[for="display_name"]' ).html() + '&nbsp;<a class="dashicons dashicons-editor-help" href="<?php echo admin_url( 'options-general.php?page=smartcustomdisplaynamesettings' ); ?>"></a>' ); });
      </script>
      <?php
    }
    
    
    /*
     * add css for the javascript generated input field
     */
    function display_name_customizer_css() {
      ?>
      <style type="text/css">
        #display_name { width: 25em; } @media screen and ( max-width: 782px ) { #display_name { width: 100%; } }
      </style>
      <?php
    }
    
  }

}