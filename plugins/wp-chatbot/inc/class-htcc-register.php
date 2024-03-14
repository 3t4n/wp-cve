<?php
/**
 * class htcc_register
 * 
 * this class have methods to run when plugin
 *  activate, deactivate, uninstall, update
 * 
 * add values to Database - wp_options table
 *      plugin details
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Register' ) ) :
        
class HTCC_Register {

    /**
     * When plugin activate this function will call
     * 
     * Check min wp version 
     * calls self::db_plugin_details - add plugin details to db
     * 
     * @since 1.0.0
     * @uses register_activation_hook
     * 
     * @return void
     */
    public static function activate() {

        require_once HTCC_PLUGIN_DIR .'inc/class-htcc-db.php';

        // update plugin details to wp_options table
        HTCC_db::db_plugin_details();

        // default values
        HTCC_db::db_default_values();


        // premium
        if ( 'true' == HTCC_PRO ) {
            include_once HTCC_PLUGIN_DIR . 'inc/pro/class-htcc-pro-db.php';
        }

    }

	/**
	 * After plugin activate this function will call
	 *
	 * Redirect to setting page
	 *
	 * @since 1.0.0
	 * @uses activated_plugin
	 *
	 * @return void
	 */
	public static function activate_plugin( $plugin ){
		if( $plugin == plugin_basename( HTCC_PLUGIN_FILE ) ) {
			exit( wp_redirect( admin_url( 'admin.php?page=wp-chatbot' ) ) );
		}
	}

    /**
     * When plugin deactivate
     * @since 1.0.0
     * @uses register_deactivation_hook
     * @return void
     */
    public static function deactivate() {
		$data = get_option('mobilemonkey_active_page_info');
		$test = new MobileMonkeyApi();
		$test->disconnectPage($data['page_id']);
		delete_option('mobilemonkey_token');
		delete_option('mobilemonkey_active_page_info');
    }

    /**
     * When plugin uninstall ( delete )
     * @since 1.0.0
     * @uses register_uninstall_hook
     * @return void
     */
    public static function uninstall() {
		$data = get_option('mobilemonkey_active_page_info');
		$test = new MobileMonkeyApi();
		$test->disconnectPage($data['page_id']);
		delete_option('mobilemonkey_token');
		delete_option('mobilemonkey_active_page_info');
    }
    

    /**
     * @uses action hook - plugins_loaded  
     * 
     * compare this content version with saved version in db
     * If version is different then run activate function
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public static function plugin_update() {
        
        $htcc_plugin_details = get_option('htcc_plugin_details');
    
        if ( HTCC_VERSION !== $htcc_plugin_details['version'] ) {
            //  to update the plugin - just like activate plugin
            self::activate();

        }
    }


    // add settings page links in plugins page - at plugin
    public static function plugin_action_links( $links ) {
		$new_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wp-chatbot' ) . '">' . __( 'Settings' , 'wp-chatbot' ) . '</a>',
		);

		return array_merge( $new_links, $links );
	}

    
}

endif; // END class_exists check