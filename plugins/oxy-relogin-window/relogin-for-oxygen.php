<?php
/*
Plugin Name: OXY Re-Login Window
Author: The OxyPowerPack Team
Author URI: https://oxypowerpack.com
Description: This free plugin presents a login window right inside Oxygen Builder when the WordPress session expires, to avoid losing your work because of the infamous Oxygen "Error 200" while saving.
Version: 1.1
*/
defined('ABSPATH') or die();

class ReLoginForOxygen
{
    function __construct()
    {
        add_action( 'plugins_loaded', array($this, 'detect_oxygen' ) );
        add_action( 'init', array($this, 'init') );
    }

    function detect_oxygen()
    {
        if( !defined("CT_VERSION") || version_compare(CT_VERSION, '3.0', '<') || version_compare(get_bloginfo('version'), '4.7', '<') )
        {
            add_action( 'admin_notices', function()
            {
                ?>
                <div class="notice notice-warning">
                    <p><?php _e( '<strong>Requirements not met.</strong> OXY Re-Login Window needs Oxygen Builder 3.0+ to run.', 'oxy-relogin-window' ); ?></p>
                </div>
                <?php
            } );
        }

        if( defined("OXYPOWERPACK_VERSION") )
        {
            add_action( 'admin_notices', function()
            {
                ?>
                <div class="notice notice-warning">
                    <p><?php _e( '<strong>OxyPowerPack is installed.</strong> OXY Re-Login Window can be safely uninstalled, OxyPowerPack has you covered with this and other powerful features.', 'oxy-relogin-window' ); ?></p>
                </div>
                <?php
            } );
        }
    }

    function enqueue_scripts()
    {
        if ( defined("SHOW_CT_BUILDER" ) && !defined( "OXYGEN_IFRAME" ) ) {
            wp_enqueue_style('oxy-relogin-window-css', plugin_dir_url(__FILE__) . "assets/oxy-relogin-window.css");
            wp_register_script("oxy-relogin-window-js", plugin_dir_url(__FILE__) . "assets/oxy-relogin-window.js");
            wp_localize_script('oxy-relogin-window-js', 'OxyReloginWindowBEData', array(
                'admin_url' => admin_url( 'admin-ajax.php' ),
                'loginIframeSrc' => add_query_arg(array('interim-login' => '1'), wp_login_url())
            ));
            wp_enqueue_script('oxy-relogin-window-js');
        }
    }

    function inject_relogin_window()
    {
        if ( defined("SHOW_CT_BUILDER" ) && !defined( "OXYGEN_IFRAME" ) ):?>
            <template id="opp-login-template">
                <div id="opp-login">
                    <iframe id="opp-floating-login-iframe" src="IFRAMESRC" frameBorder="0"></iframe>
                </div>
            </template>
        <?php
        endif;
    }

    function heartbeat()
    {
		$result = is_user_logged_in() ? wp_create_nonce( 'oxygen-nonce-' . $_GET['post_id'] ) : 'expired';
		header('Content-Type: application/json');
		echo json_encode( array( 'session_status' => $result ) );
		die();
	}

    function init()
    {
        // Do nothing in presence of OxyPowerPack
        if( defined("OXYPOWERPACK_VERSION") ) return;

        add_action("ct_before_builder", array($this, "inject_relogin_window"));

        add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));

		add_action( 'wp_ajax_oxy-relogin-window-heartbeat', array( $this, 'heartbeat' ) );
		add_action( 'wp_ajax_nopriv_oxy-relogin-window-heartbeat', array( $this, 'heartbeat' ) );
    }
}

new ReLoginForOxygen();
