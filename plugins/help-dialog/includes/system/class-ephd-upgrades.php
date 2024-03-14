<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check if plugin upgrade to a new version requires any actions like database upgrade
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
class EPHD_Upgrades {

	public function __construct() {
		// will run after plugin is updated but not always like front-end rendering
		add_action( 'admin_init', array( 'EPHD_Upgrades', 'update_plugin_version' ) );
		add_filter( 'ephd_plugin_upgrade_message', array( 'EPHD_Upgrades', 'display_upgrade_message' ) );
		add_action( 'ephd_remove_upgrade_message', array( 'EPHD_Upgrades', 'remove_upgrade_message' ) );

		// show initial page after install
		add_action( 'admin_init', array( 'EPHD_Upgrades', 'initial_setup' ), 20 );

		// show additional messages on the plugins page
		add_action( 'in_plugin_update_message-help-dialog/echo-help-dialog.php',  array( $this, 'in_plugin_update_message' ) );
	}

	/**
	 * Trigger display of wizard setup screen on plugin first activation or upgrade; does NOT work if multiple plugins installed at the same time
	 */
	public static function initial_setup() {

		$hd_version = EPHD_Utilities::get_wp_option( 'ephd_version', null );
		if ( empty( $hd_version ) ) {
			return;
		}

		// return if activating from network or doing bulk activation
		if ( is_network_admin() || isset($_GET['activate-multi']) ) {
			return;
		}

		// did setup run already?
		$run_setup = EPHD_Utilities::get_wp_option( 'ephd_run_setup', null );
		if ( empty( $run_setup ) ) {
			return;
		}

		delete_option( 'ephd_run_setup' );

		// create default Widget
		EPHD_Help_Dialog_Handler::add_default_faqs();

		// redirect to Getting Started
		wp_safe_redirect( admin_url( 'admin.php?page=ephd-help-dialog#getting-started' ) );
		exit;
	}

    /**
     * If necessary run plugin database updates
     */
    public static function update_plugin_version() {

        $last_version = EPHD_Utilities::get_wp_option( 'ephd_version', null );
		if ( empty( $last_version ) ) {
			EPHD_Utilities::save_wp_option( 'ephd_version', Echo_Help_Dialog::$version );
			EPHD_Utilities::save_wp_option( 'ephd_version_first', Echo_Help_Dialog::$version );
			return;
		}

        // if plugin is up-to-date then return
        if ( version_compare( $last_version, Echo_Help_Dialog::$version, '>=' ) ) {
            return;
        }

        /**
		// since we need to upgrade this plugin, on the Overview Page show an upgrade message
	    EPHD_Utilities::save_wp_option( 'ephd_show_upgrade_message', true );

	    $global_config = ephd_get_instance()->global_config_obj->get_config();
	    $widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

        // upgrade the plugin
		$update_config = self::run_upgrade(  $global_config, $widgets_config, $last_version );

		// store the updated configs
	    if ( $update_config ) {
		    ephd_get_instance()->global_config_obj->update_config( $global_config );
	    	ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );

		    // remove designs config - deprecated since HD version 2.0.0
		    if ( ! empty( $designs_config ) ) {
			    delete_option( 'ephd_designs_config' );
		    }

		    // remove contact forms config - deprecated since HD version 2.0.0
		    if ( ! empty( $contact_forms_config ) ) {
			    delete_option( 'ephd_contact_forms_config' );
		    }
	    }

        // update the plugin version
        $result = EPHD_Utilities::save_wp_option( 'ephd_version', Echo_Help_Dialog::$version );
        if ( is_wp_error( $result ) ) {
	        EPHD_Logging::add_log( 'Could not update plugin version', $result );
            return;
        }
        */
    }

	/**
	 * Run upgrade for provided configs
	 *
	 * @param $global_config
	 * @param $widgets_config
	 * @param $last_version
	 * @return bool
	 */
    public static function run_upgrade( &$global_config, &$widgets_config, $last_version ) {

	    $update_config = false;

	    return $update_config;
	}


    /**
     * Show upgrade message on Overview Page.
     *
     * @param $output
     * @return string
     */
	public static function display_upgrade_message( $output ) {

		if ( EPHD_Utilities::get_wp_option( 'ephd_show_upgrade_message', false ) ) {
			
			$plugin_name = '<strong>' . esc_html__('Help Dialog', 'help-dialog') . '</strong>';
			$output .= '<p>' . esc_html( $plugin_name ) . ' ' . sprintf( esc_html( _x( 'plugin was updated to version %s.',' version number, link to what is new page', 'help-dialog' ) ),
									Echo_Help_Dialog::$version ) . '</p>';
		}

		return $output;
	}
    
    public static function remove_upgrade_message() {
        delete_option('ephd_show_upgrade_message');
    }

	/**
	 * Function for major updates
	 *
	 * @param $args
	 */
	public function in_plugin_update_message( $args ) {

		$current_version = Echo_Help_Dialog::$version;
		$new_version = empty( $args['new_version'] ) ? $current_version : $args['new_version'];

		// versions x.y0.z are major releases
		if ( ! preg_match( '/.*\.\d0\..*/', $new_version ) ) {
			return;
		}

		echo '<style> .ephd-update-warning+p { opacity: 0; height: 0;} </style> ';
		echo '<hr style="clear:left"><div class="ephd-update-warning"><span class="dashicons dashicons-info" style="float:left;margin-right: 6px;color: #d63638;"></span>';
		echo '<div class="ephd-update-warning__title">' . esc_html__( 'We highly recommend you back up your site before upgrading, and make sure you first update in a staging environment.', 'help-dialog' ) . '</div>';
		echo '<div class="ephd-update-warning__message">' .	esc_html__( 'The latest update includes some substantial changes across different areas of the plugin', 'help-dialog' ) . '</div></div>';
	}
}
