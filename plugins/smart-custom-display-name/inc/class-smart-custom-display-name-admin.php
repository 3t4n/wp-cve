<?php

/**
 * The Smart Custom Display Name admin plugin class
 *
 * @since 5.0.0
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin plugin class
 */
if ( !class_exists( 'PP_Smart_Custom_Display_Name_Admin' ) ) {
  
  class PP_Smart_Custom_Display_Name_Admin extends PPF08_Admin {

    
    /**
	   * Do Init
     *
     * @since 5.0.0
     * @access public
     */
    public function init() {
      
      $this->add_actions( array( 
        'admin_init',
        'admin_menu'
      ) );
    
    }
    
    
    /**
     * init admin 
     * moved to PP_Smart_Custom_Display_Name_Admin in v 5.0.0
     */
    function action_admin_init() {
      
      $this->add_setting_sections(
      
        array(
          
          array(
        
            'section' => 'info',
            'order'   => 10,
            'title'   => esc_html__( 'Info', 'smart-custom-display-name' ),
            'icon'    => 'info',
            'html' => '<p>' . esc_html__( 'This plugin allows you to change the users Display Name to anything you like', 'smart-custom-display-name' ) . '</p>
                       <p>' . esc_html__( 'There are no settings. When activated the plugin changes the "Display name publicly as" field on the user settings page from a select box where you only can choose from maximum 6 possible values to a  regular text input field where you can type in anything you like.', 'smart-custom-display-name' ) . '</p>' .
					   '<h2>PLEASE NOTE</h2><p>Development, maintenance and support of this plugin has been retired. You can use this plugin as long as is works for you. Thanks for your understanding.<br />Regards, Peter</p>',					   
            'nosubmit' => true
        
          )
          
        )
        
      );
      
    }
    
    
    /**
     * create the menu entry
     * moved to PP_Smart_Custom_Display_Name_Admin in v 5.0.0
     */
    function action_admin_menu() {
      
      $screen_id = add_options_page( $this->core()->get_plugin_shortname(), $this->core()->get_plugin_shortname(), 'manage_options', 'smartcustomdisplaynamesettings', array( $this, 'show_admin' ) );
      $this->set_screen_id( $screen_id );
      
    }
    
   
    /**
     * show admin page
     * moved to PP_Smart_Custom_Display_Name_Admin in v 5.0.0
     */
    function show_admin() {
      
      $this->show( 'manage_options' );
      
    }
    
    
    /**
     * create nonce
     *
     * @since  4.0.0
     * @access private
     * @return string Nonce
     */
    private function get_nonce() {
      
      return wp_create_nonce( 'pp_smart_custom_display_name_dismiss_admin_notice' );
      
    }
    
    
    /**
     * check nonce
     *
     * @since  4.0.0
     * @access private
     * @return boolean
     */
    private function check_nonce() {
      
      return check_ajax_referer( 'pp_smart_custom_display_name_dismiss_admin_notice', 'securekey', false );
      
    }

  }
  
}

?>