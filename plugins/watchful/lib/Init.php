<?php
/**
 * Watchful initialization.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     watchful
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful;

use Watchful\Helpers\InstalledPlugins;
use \WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class to initialize the Watchful plugin.
 */
class Init {
	/**
	 * Called when the plugin is activated.
	 *
	 * @throws \Exception The exception to throw.
	 */
	public static function activation() {
		// Generate Watchful Key.
		$watchful_key = wp_generate_password( 32, false );

		// Create the option if they don't exist.
		if ( ! get_option( 'watchfulSettings' ) ) {
			update_option(
				'watchfulSettings',
				array(
                    'watchful_version'           => WATCHFUL_VERSION,
					'watchfulSecretKey'          => $watchful_key,
					'watchful_disable_timestamp' => 0,
					'watchful_maintenance'       => 0,
                    'watchful_sso_authentication'=> 1,
                )
			);
		}

        $rest_api_in_core = class_exists( 'WP_REST_Server', false );

		if ( ! $rest_api_in_core ) {
			// Install rest-api if missing.
			$plugins = new Controller\Plugins();
			$request = new WP_REST_Request();
			$slug    = 'rest-api';
			$request->set_param( 'slug', $slug );

			// Required cause it doesn't take the default value of the route.
			$request->set_param( 'status', 1 );

			if ( ! $plugins->isInstalled( $slug ) ) {
				$plugins->install_plugin( $request );
			} else {
				$plugins->activate_plugin( $request );
			}
		}

		add_option( 'watchfulDoActivationRedirect', true );
	}

	/**
	 * Called when the plugin is uninstalled.
	 */
	public static function uninstall() {
		delete_option( 'watchfulSettings' );
	}

    /**
     * Called after WordPress has finished loading but before any headers are sent
     * @return void
     */
    public static function wordpress_init() {
        if ( is_admin() ) {
            $my_settings_page = new Settings();
            $my_settings_page->init();

            $watchful_main_plugin = new Main();
            $watchful_main_plugin->init();
        }
        // Register the REST API Routes.
        $class = new Routes();
        add_action( 'rest_api_init', array( $class, 'register_routes' ) );
        $class = new Helpers\Sso\Authenticator();
        add_filter( 'authenticate', array( $class, 'authenticate'), 25, 3 );
    }

    /**
     * Called as an admin screen or script is being initialized.
     * Used to redirect to settings page after the plugin's activation
     * @return void
     */
    public static function admin_init() {
        if ( ! get_option( 'watchfulDoActivationRedirect', false ) ) {
            return;
        }
        delete_option( 'watchfulDoActivationRedirect' );

        // Check that user can see the setting page.
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Redirect to watchful settings.
        wp_safe_redirect(
            add_query_arg(
                array(
                    'page'     => 'watchful-setting',
                    'activate' => '1',
                ),
                admin_url( 'options-general.php' )
            )
        );
        exit;
    }

    /**
     * Called once activated plugins have loaded.
     * Used to handle plugin post-upgrade actions
     * @return void
     */
    public static function plugins_loaded() {
        $settings = get_option('watchfulSettings');
        if (!$settings || (isset($settings['watchful_version']) && WATCHFUL_VERSION === $settings['watchful_version'])) {
            return;
        }

        // Delete the Watchful plugin fixer used to fix the update system for a bug in 1.6.0
        // This can be removed in a future version
        if (
            isset($settings['watchful_version']) &&
            ($settings['watchful_version'] === '1.6.0' || $settings['watchful_version'] === '1.6.1')
        ) {
            InstalledPlugins::uninstall('watchful-client-fixer/watchful-client-fixer.php');
        }

        $settings['watchful_version'] = WATCHFUL_VERSION;

        // Enable SSO authentication during plugin upgrades
        if ( !isset($settings['watchful_sso_authentication']) ) {
            $settings['watchful_sso_authentication'] = 1;
        }

        update_option('watchfulSettings', $settings);
    }
}
