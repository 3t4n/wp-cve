<?php

/**
 * The smart Attachment Page Remove core plugin class
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The core plugin class
 */
if ( !class_exists( 'PP_Smart_Attachment_Page_Remove' ) ) { 

  class PP_Smart_Attachment_Page_Remove extends PPF08_Plugin {
    
     /**
     * Admin Class
     *
     * @see    class-smart-attachment-page-remove-admin.php
     * @since  4.0.0
     * @var    object
     * @access private
     */
    private $admin;
    
    
    /**
     * Init the Class 
     *
     * @since 4.0.0
     */
    public function plugin_init() {
      
      // settings defaults
      $defaults = array(
        'send_http_410' => false
      );
      
      // since 4.0.0 we use add_settings_class() to load the settings
      $this->add_settings_class( 'PP_Smart_Attachment_Page_Remove_Settings', 'class-smart-attachment-page-remove-settings', $this, $defaults );
      
       $this->add_actions( array( 
        'init'
      ) );
      
      add_action( 'wp', array( $this, 'remove_attachment_page' ) );
      
    }
    
    /**
     * do plugin init 
     */
    function action_init() {
      
      load_plugin_textdomain( 'smart-attachment-page-remove' );
      
      // since v 5.0.0
      $this->admin      = $this->add_sub_class_backend( 'PP_Smart_Attachment_Page_Remove_Admin',     'class-smart-attachment-page-remove-admin', $this, $this->settings() );
      
    }
    
    
    /**
     * send an 404 error if accessing an attachment page
     * @since 3 - alternatively send an 410
     */
    function remove_attachment_page() {
      global $wp_query;
      if ( is_attachment() ) {
        $wp_query->set_404();
        status_header( $this->get_current_setting_do_410() ? 410 : 404 );
      }
    }
    


    /**
	   * get current setting for 404/410
     *
     * @since 3
     * @access public
     * @return int bool
     */
    public function get_current_setting_do_410() {
      
      return (bool)$this->settings()->get( 'send_http_410' );
      
    }

    
    /**
     * uninstall plugin
     */
    function uninstall() {
      
      if( is_multisite() ) {
        
        $this->uninstall_network();
        
      } else {
        
        $this->uninstall_single();
        
      }
      
    }
    
    
    /**
     * uninstall network wide
     */
    function uninstall_network() {
      
      global $wpdb;
      $activeblog = $wpdb->blogid;
      $blogids = $wpdb->get_col( esc_sql( 'SELECT blog_id FROM ' . $wpdb->blogs ) );
      
      foreach ( $blogids as $blogid ) {
        
        switch_to_blog( $blogid );
        $this->uninstall_single();
        
      }
      
      switch_to_blog( $activeblog );
      
    }
    
    
    /**
     * uninstall for a single blog
     */
    function uninstall_single() {
      
      $this->data_remove();
      $this->settings()->remove();
      
    }

  }
}
 
?>