<?php
/*
	Plugin Name: Campaign Monitor Forms by Optin Cat
	Plugin URI: https://fatcatapps.com/optincat
	Description: Campaign Monitor Optin Cat Helps You Get More Email Subscribers. Create Beautiful Campaign Monitor Opt-In Forms In Less Than 2 Minutes.
	Author: Fatcat Apps
	Version: 2.5.6
	Author URI: https://fatcatapps.com/
*/

if ( ! function_exists( 'is_admin' ) ) {
	exit();
}

define( 'FCA_EOI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FCA_EOI_PLUGIN_FILE', __FILE__ );
define( 'FCA_EOI_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'FCA_EOI_PLUGIN_SLUG', 'campaign-monitor-wp' );
define( 'FCA_EOI_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'FCA_EOI_VER', '2.5.6' );

if( ! defined ( 'FCA_EOI_DEBUG' ) ) {
	define( 'FCA_EOI_DEBUG', false );
}

if( ! class_exists( 'DhEasyOptIns' ) ) {
	class DhEasyOptIns {

		var $distro = '';
		var $shortcode = 'optin-cat';
		var $shortcode_aliases = array(
			'easy-opt-in',
			'optincat',
			'opt-in-cat',
		);
		var $settings;
		var $provider = '';
		var $providers = array();
		var $post_type_obj = '';

		function __construct() {
			require_once FCA_EOI_PLUGIN_DIR . 'includes/classes/k/k.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-powerups.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-subscribers.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-post-types.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-layout.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-shortcode.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-widget.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-activity.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-functions.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-block.php';
			require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-uninstall.php';

			global $fca_eoi_shortcodes;
		
			// Settings
			$this->settings['shortcode']  = $this->shortcode;
			$this->settings['shortcode_aliases']  = $this->shortcode_aliases;
			$this->settings['provider']	  = $this->provider;

			// Load all providers
			foreach ( glob( FCA_EOI_PLUGIN_DIR . 'providers/*', GLOB_ONLYDIR ) as $provider_path ) {  
				$provider_id = basename(  $provider_path );
				require_once "$provider_path/provider.php";
				$this->settings[ 'providers' ][ $provider_id ] = call_user_func( "provider_$provider_id" );
			}

			// Add provider to settings
			$providers_available = array_keys( $this->settings[ 'providers' ] );
			
			//set current post type to setting array
			$this->settings[ 'post_type' ] = get_post_type();

			// If there is only one provider, use it
			if( 1 == count( $providers_available ) ) {
				$this->provider = $this->settings[ 'provider' ] = $providers_available[ 0 ];
				$this->distro = 'free';
				$this->settings['distribution'] = 'free';
			} else {
				$this->distro = 'premium';
				$this->settings['distribution'] = 'premium';			
			}

			// Include provider helper class(es)
			foreach ( $providers_available as $provider ) {
				require_once FCA_EOI_PLUGIN_DIR . "providers/$provider/functions.php";
			}

			// Load extensions
			$this->post_type_obj = new EasyOptInsPostTypes($this->settings);
			$fca_eoi_shortcodes = new EasyOptInsShortcodes($this->settings);
			$widget = new EasyOptInsWidgetHelper($this->settings);
			EasyOptInsActivity::get_instance()->settings = $this->settings;

			// Load subscribing banner for free users
			if( $this->distro == 'free' ) {
				//load EasyOptIns Upgrade notifications
				require plugin_dir_path( __FILE__ ) . 'includes/eoi-upgrade.php';
				new EasyOptInsUpgrade( $this->settings );
			}
		}
	}
}

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
	function fca_eoi_fail_activation( $message ) {
		wp_die( sprintf(
			'<h2>%s</h2><p>%s</p><p><a class="button button-large" href="%s">%s</a></p>'
			, __( 'Ooops!' )
			, __( $message )
			, admin_url( 'plugins.php' )
			, __( 'Go to plugins page' )
		) );
	}

	function fca_eoi_activation() {
		$plugins = get_plugins();
		
		// Fail to activate the plugin if other Optin Cat plugins are already active
		foreach ( $plugins as $file => $plugin ) {
			if ( stripos( $plugin['PluginURI'], 'fatcatapps.com/optincat' ) !== false && is_plugin_active( $file ) ) {
				$current_plugin = $plugins[ plugin_basename( __FILE__ ) ];
				fca_eoi_fail_activation(
					'Only one Optin Cat plugin can be active at a time, but you already have ' .
					htmlspecialchars( $plugin['Name'] ) . ' active. ' .
					'Please deactivate it before activating ' .
					htmlspecialchars( $current_plugin['Name'] ) . '.' );
			}
		}

		// Fail to activate the plugin if the providers or layouts directories are empty
		$providers	= glob( FCA_EOI_PLUGIN_DIR . 'providers/*', GLOB_ONLYDIR );
		$layouts	= glob( FCA_EOI_PLUGIN_DIR . 'layouts/*', GLOB_ONLYDIR );

		if ( empty( $providers ) || empty( $layouts ) ) {
			fca_eoi_fail_activation( 'Something went wrong. Please delete the plugin and install it again.' );
		}
		
		require FCA_EOI_PLUGIN_DIR . 'includes/eoi-functions.php';

		//convert everyone to new Post Meta Format if they are on OLD
		fca_eoi_convert_post_meta();
		//convert options from 'paf' to 'fca_eoi_settings'
		fca_eoi_convert_option_save();
		
		//check for users of custom form, otherwise that feature is disabled
		$opt = get_option ( 'fca_eoi_allow_customform', '' );
		if ( empty ( $opt ) ) {
			fca_eoi_set_custom_form_depreciation();
		}
				
		// If everything went well, continue with the activation setup
		require FCA_EOI_PLUGIN_DIR . 'includes/eoi-activity.php';
		EasyOptInsActivity::get_instance()->setup();
	}

	// If the plugin is not yet active, check for any obstacles in activation
	register_activation_hook( __FILE__, 'fca_eoi_activation' );
	return;
}

$dh_easy_opt_ins_plugin = new DhEasyOptIns();
