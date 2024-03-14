<?php
/*
Flexmls® IDX Plugin
Plugin Name: Flexmls® IDX
Plugin URI: https://fbsidx.com/help
Description: Provides Flexmls&reg; Customers with Flexmls&reg; IDX features on their WordPress websites. <strong>Tips:</strong> <a href="admin.php?page=fmc_admin_settings">Activate your Flexmls&reg; IDX plugin</a> on the settings page; <a href="widgets.php">add widgets to your sidebar</a> using the Widgets Admin under Appearance; and include widgets on your posts or pages using the Flexmls&reg; IDX Widget Short-Code Generator on the Visual page editor.
Author: FBS
Version: 3.14.12
Author URI:  https://www.flexmls.com
Requires at least: 5.0
Requires PHP: 7.4
*/

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

const FMC_API_BASE = 'sparkapi.com';
const FMC_API_VERSION = 'v1';
const FMC_PLUGIN_VERSION = '3.14.12';

define( 'FMC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

global $auth_token_failures, $spark_oauth_global;
$auth_token_failures = 0;

$fmc_version = FMC_PLUGIN_VERSION;
$fmc_plugin_dir = dirname(realpath(__FILE__));
$fmc_plugin_url = plugins_url() .'/flexmls-idx';

if( defined( 'FMC_DEV' ) && FMC_DEV && WP_DEBUG ){
	ini_set( 'error_log', FMC_PLUGIN_DIR . '/debug.log' );
}


class FlexMLS_IDX {

	function __construct(){
        require_once( 'lib/functions.php' );
		require_once( 'Admin/autoloader.php' );
		require_once( 'Shortcodes/autoloader.php' );
		require_once( 'SparkAPI/autoloader.php' );
		require_once( 'Widgets/autoloader.php' );

		require_once( 'lib/base.php' );
		require_once( 'lib/flexmls-json.php' );
		require_once( 'lib/search-util.php' );
		require_once( 'lib/settings-page.php' );
		require_once( 'lib/flexmlsAPI/Core.php' );
		require_once( 'lib/flexmlsAPI/WordPressCache.php' );
		require_once( 'lib/oauth-api.php' );
		require_once( 'lib/apiauth-api.php' );
		require_once( 'lib/fmc_settings.php' );
		require_once( 'lib/fmcStandardStatus.php' );
		require_once( 'lib/account.php' );
		require_once( 'lib/idx-links.php' );
		require_once( 'pages/portal-popup.php' );
		require_once( 'components/widget.php' );
		require_once( 'components/photo_settings.php' );
		require_once( 'components/listing-map.php' );
		require_once( 'pages/core.php' );
		require_once( 'pages/full-page.php' );
		require_once( 'pages/listing-details.php' );
		require_once( 'pages/search-results.php' );
		require_once( 'pages/fmc-agents.php' );
		require_once( 'pages/next-listing.php' );
		require_once( 'pages/prev-listing.php' );
		require_once( 'pages/oauth-login.php' );
		require_once( 'components/LocationGenerator.php' );

		add_action( 'admin_enqueue_scripts', array( '\FlexMLS\Admin\Enqueue', 'admin_enqueue_scripts' ) );
		add_action( 'admin_print_footer_scripts', array( '\FlexMLS\Admin\Enqueue', 'admin_print_footer_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'flexmls_hourly_cache_cleanup', array( '\FlexMLS\Admin\Update', 'hourly_cache_cleanup' ) );
		add_action( 'init', array( $this, 'rewrite_rules' ) );
		add_action( 'parse_query', array( $this, 'parse_query' ) );
		add_action( 'plugins_loaded', array( '\FlexMLS\Admin\Settings', 'update_settings' ), 9 );
		add_action( 'plugins_loaded', array( $this, 'session_start' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		//add_action( 'wp_ajax_fmcShortcodeContainer', array( 'flexmlsConnect', 'shortcode_container' ) );
		add_action( 'wp_ajax_fmcShortcodeContainer', array( '\FlexMLS\Admin\TinyMCE', 'tinymce_shortcodes' ) );
		add_action('wp_ajax_fmcLocationGenerator', array('\FlexMLS\Admin\LocationGenerator', 'tinymce_form') );
		add_action( 'wp_ajax_tinymce_shortcodes_generate', array( '\FlexMLS\Admin\TinyMCE', 'tinymce_shortcodes_generate' ) );
        add_action( 'wp_ajax_nopriv_tinymce_shortcodes_generate', array( '\FlexMLS\Admin\TinyMCE', 'tinymce_shortcodes_generate' ) );
		add_action( 'wp_ajax_fmcleadgen_shortcode', array( '\FlexMLS\Shortcodes\LeadGeneration', 'tinymce_form' ) );
		add_action( 'wp_ajax_fmcleadgen_submit', array( '\FlexMLS\Shortcodes\LeadGeneration', 'submit_lead' ) );
		add_action( 'wp_ajax_nopriv_fmcleadgen_submit', array( '\FlexMLS\Shortcodes\LeadGeneration', 'submit_lead' ) );
		add_action( 'wp_enqueue_scripts', array( '\FlexMLS\Admin\Enqueue', 'wp_enqueue_scripts' ) );

		add_action( 'wp_ajax_flexmls_connect_save_search', array( 'flexmlsConnectPageSearchResults', 'save_user_search' ) );
		add_action( 'wp_ajax_nopriv_flexmls_connect_save_search', array( 'flexmlsConnectPageSearchResults', 'save_user_search' ) );

		add_shortcode( 'idx_frame', array( 'flexmlsConnect', 'shortcode' ) );
		add_shortcode( 'lead_generation', array( '\FlexMLS\Shortcodes\LeadGeneration', 'shortcode' ) );
		add_shortcode( 'neighborhood_page', array( 'FlexMLS\Shortcodes\NeighborhoodPage', 'shortcode' ) );
	}

	function admin_menu(){
		$SparkAPI = new \SparkAPI\Core();
		$auth_token = $SparkAPI->generate_auth_token();
		add_menu_page( 'Flexmls&reg; IDX', 'Flexmls&reg; IDX', 'edit_posts', 'fmc_admin_intro', array( '\FlexMLS\Admin\Settings', 'admin_menu_cb_intro' ), 'dashicons-location', 77 );
		/*if( $auth_token ){
			add_submenu_page( 'fmc_admin_intro', 'FlexMLS&reg; IDX: Add Neighborhood', 'Add Neighborhood', 'edit_pages', 'fmc_admin_neighborhood', array( '\FlexMLS\Admin\Settings', 'admin_menu_cb_neighborhood' ) );
		}*/
		add_submenu_page( 'fmc_admin_intro', 'Flexmls&reg; IDX: Settings', 'Settings', 'manage_options', 'fmc_admin_settings', array( '\FlexMLS\Admin\Settings', 'admin_menu_cb_settings' ) );
	}

	function admin_notices(){
		if( current_user_can( 'manage_options' ) ){
			$required_php_extensions = array();
			if( !extension_loaded( 'curl' ) ){
				$required_php_extensions[] = 'cURL';
			}
			if( !extension_loaded( 'bcmath' ) ){
				$required_php_extensions[] = 'BC Math';
			}
			if( count( $required_php_extensions ) ){
				printf(
					'<div class="notice notice-error"><p>Your website&#8217;s server does not have <em>' . implode( '</em> or <em>', $required_php_extensions ) . '</em> enabled which %1$s required for the Flexmls&reg; IDX plugin. Please contact your webmaster and have %2$s enabled on your website hosting plan.</p></div>',
					_n( 'is', 'are', count( $required_php_extensions ) ),
					_n( 'this extension', 'these extensions', count( $required_php_extensions ) )
				);
			}
			$options = get_option( 'fmc_settings' );
			if( empty( $options[ 'api_key' ] ) || empty( $options[ 'api_secret' ] ) ){
				printf(
					'<div class="notice notice-warning">
						<p>You must enter your Flexmls&reg; API Credentials. <a href="%1$s">Click here</a> to enter your API credentials, or <a href="%2$s">contact Flexmls&reg; support</a>.</p>
					</div>',
					admin_url( 'admin.php?page=fmc_admin_intro' ),
					admin_url( 'admin.php?page=fmc_admin_intro&tab=support' )
				);
			} else {
				$SparkAPI = new \SparkAPI\Core();
				$auth_token = $SparkAPI->generate_auth_token();
				if( false === $auth_token ){
					echo '<div class="notice notice-error">
					<p>There was an error connecting to the Flexmls&reg; IDX API. 
					<ul style="list-style-type: square; padding-left: 25px;">
					<li>Please check your credentials and try again. If your credentials are correct and you continue to see this error message, 
					please <a href="' . admin_url( 'admin.php?page=fmc_admin_settings&tab=support' ) . '">contact support</a>
					<p>or</p></li>
					<li> You may need to renew your plugin subscription. Please contact the Flexmls IDX Consultant Team: <a href="tel:8663209977">(866)320-9977</a> or <a href="mailto:idxsales@fbsdata.com">Email</a></li>	
					</ul>
					</p>
				</div>';
				} else {
					if( !isset( $options[ 'google_maps_api_key' ] ) || empty( $options[ 'google_maps_api_key' ] ) ){
						printf(
							'<div class="notice notice-warning is-dismissible">
								<p>You have not entered a Google Maps API Key. It&#8217;s not required for the Flexmls&reg; IDX plugin, but maps will not show on your site without a Google Maps API key. <a href="%1$s">Click here</a> to enter your Google Map API Key, or <a href="%2$s" target="_blank">generate a Google Map API Key here</a>.</p>
							</div>',
							admin_url( 'admin.php?page=fmc_admin_settings&tab=gmaps' ),
							'https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key'
						);
					}
				}
			}
		}
	}

	function parse_query(){
		global $wp_query;
		if( isset( $wp_query->query_vars[ 'oauth_tag' ] ) ){
			if( 'oauth-login' == $wp_query->query_vars[ 'oauth_tag' ] ){
				$fmc_settings = get_option( 'fmc_settings' );
				$fmc_api_portal = new flexmlsConnectPortalUser( $fmc_settings[ 'oauth_key' ], $fmc_settings[ 'oauth_secret' ] );
				//$OAuth = new \SparkAPI\OAuth();
				//$OAuth->do_login();
			}
			if( 'oauth-logout' == $wp_query->query_vars[ 'oauth_tag' ] ){
				\SparkAPI\OAuth::log_out();
				$state = isset( $_GET[ 'redirect' ] ) ? $_GET[ 'redirect' ] : home_url();
				exit( '<meta http-equiv="refresh" content="0; url=' . $state . '">' );
			}
		}
	}

	public static function plugin_activate(){
		$is_fresh_install = false;
		if( false === get_option( 'fmc_settings' ) ){
			$is_fresh_install = true;
		}
		\FlexMLS\Admin\Update::set_minimum_options( $is_fresh_install );
		add_action( 'shutdown', 'flush_rewrite_rules' );
		if( false === get_option( 'fmc_plugin_version' ) ){
			add_option( 'fmc_plugin_version', FMC_PLUGIN_VERSION, null, 'no' );
		}
	}

	public static function plugin_deactivate(){
		$SparkAPI = new \SparkAPI\Core();
		$SparkAPI->clear_cache( true );
	}

	public static function plugin_uninstall(){
		$timestamp = wp_next_scheduled( 'flexmls_hourly_cache_cleanup' );
		if( $timestamp ){
			wp_unschedule_event( $timestamp, 'flexmls_hourly_cache_cleanup' );
		}
		$SparkAPI = new \SparkAPI\Core();
		$SparkAPI->clear_cache( true );
		delete_option( 'fmc_cache_version' );
		delete_option( 'fmc_plugin_version' );
		delete_option( 'fmc_settings' );
		flush_rewrite_rules();
	}

	function rewrite_rules(){
    $fmc_settings = get_option( 'fmc_settings' );
		add_rewrite_rule( 'oauth/callback/?', 'index.php?plugin=flexmls-idx&oauth_tag=oauth-login', 'top' );
		add_rewrite_rule( 'oauth/spark-logout/?', 'index.php?plugin=flexmls-idx&oauth_tag=oauth-logout', 'top' );
		add_rewrite_tag( '%oauth_tag%', '([^&]+)' );

		add_rewrite_rule( $fmc_settings[ 'permabase' ] . '/([^/]+)?' , 'index.php?plugin=flexmls-idx&fmc_tag=$matches[1]&page_id=' . $fmc_settings[ 'destlink' ], 'top' );
		add_rewrite_rule( 'portal/([^/]+)?', 'index.php?plugin=flexmls-idx&fmc_vow_tag=$matches[1]&page_id=' . $fmc_settings[ 'destlink' ], 'top' );
		add_rewrite_tag( '%fmc_tag%', '([^&]+)' );
		add_rewrite_tag( '%fmc_vow_tag%', '([^&]+)' );
	}

	function session_start(){
		//self::write_log( json_decode( $_COOKIE[ 'spark_oauth' ] ) );
		/*
		if( !session_id() ){
			session_start();
		}
		*/
		$SparkAPI = new \SparkAPI\Core();
		$fmc_plugin_version = get_option( 'fmc_plugin_version' );
		if( false === $fmc_plugin_version || version_compare( $fmc_plugin_version, FMC_PLUGIN_VERSION, '<' ) ){
			\FlexMLS\Admin\Update::set_minimum_options();
			$did_clear_cache = $SparkAPI->clear_cache( true );
			update_option( 'fmc_plugin_version', FMC_PLUGIN_VERSION, 'no' );
		}
		if( !wp_next_scheduled( 'flexmls_hourly_cache_cleanup' ) ){
			wp_schedule_event( time(), 'hourly', 'flexmls_hourly_cache_cleanup');
		}
		$auth_token = $SparkAPI->generate_auth_token();

		global $listings_per_page;
		if( isset( $_GET[ 'Limit' ] ) ){
			$listings_per_page = intval( $_GET[ 'Limit' ] );
			setcookie( 'spark_listings_per_page', $listings_per_page, time() + 30 * DAY_IN_SECONDS, '/' );
		} elseif( isset( $_COOKIE[ 'spark_listings_per_page' ] ) ){
			$listings_per_page = intval( $_COOKIE[ 'spark_listings_per_page' ] );
		} else {
			$listings_per_page = 10;
		}

		global $listings_orderby;
		if( isset( $_GET[ 'OrderBy' ] ) ){
			$listings_orderby = sanitize_text_field( $_GET[ 'OrderBy' ] );
			setcookie( 'spark_listings_orderby', $listings_orderby, time() + 30 * DAY_IN_SECONDS, '/' );
		} elseif( isset( $_COOKIE[ 'spark_listings_orderby' ] ) ){
			$listings_orderby = $_COOKIE[ 'spark_listings_orderby' ];
		} else {
			$listings_orderby = '-ListPrice';
		}
	}

	function widgets_init(){
		global $fmc_widgets;
		$SparkAPI = new \SparkAPI\Core();
		$auth_token = $SparkAPI->generate_auth_token();

		if( $auth_token ){
			register_widget( '\\FlexMLS\\Widgets\\LeadGeneration' );
		}

		// This will come out soon once all of the widgets have been
		// rebuilt as native WordPress widgets and called using
		// register_widget above.
		if( $auth_token && $fmc_widgets ){
			foreach( $fmc_widgets as $class => $wdg ){
				if( file_exists( FMC_PLUGIN_DIR . 'components/' . $wdg[ 'component' ] ) ){
					require_once( FMC_PLUGIN_DIR . 'components/' . $wdg[ 'component' ] );
					// All widgets require a "key" or auth token so this can be removed
					/*
					$meets_key_reqs = false;
					if ($wdg['requires_key'] == false || ($wdg['requires_key'] == true && flexmlsConnect::has_api_saved())) {
						$meets_key_reqs = true;
					}
					*/
					if( class_exists( $class, false ) && true == $wdg[ 'widget' ] ){
						register_widget( $class );
					}
					if( false == $wdg[ 'widget' ] ){
						new $class();
					}
				}
			}
		}
	}

	static function write_log( $log, $title = 'Flexmls Log Item' ){
		error_log( '---------- ' . $title . ' ----------' );
		if( is_array( $log ) || is_object( $log ) ){
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}

}
$FlexMLS_IDX = new FlexMLS_IDX();

register_activation_hook( __FILE__, array( 'FlexMLS_IDX', 'plugin_activate' ) );
register_deactivation_hook( __FILE__, array( 'FlexMLS_IDX', 'plugin_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'FlexMLS_IDX', 'plugin_uninstall' ));

/*
* Define widget information
*/

global $fmc_widgets;
$fmc_widgets = array(
    'fmcMarketStats' => array(
        'component' => 'market-statistics.php',
        'title' => "Flexmls&reg;: Market Statistics",
        'description' => "Show market statistics on your blog",
        'requires_key' => true,
        'shortcode' => 'market_stats',
        'max_cache_time' => 0,
        'widget' => true
        ),
    'fmcPhotos' => array(
        'component' => 'photos.php',
        'title' => "Flexmls&reg;: IDX Slideshow",
        'description' => "Show photos of selected listings",
        'requires_key' => true,
        'shortcode' => 'idx_slideshow',
        'max_cache_time' => 0,
        'widget' => true
        ),
    'fmcSearch' => array(
        'component' => 'v2/search.php',
        'title' => "Flexmls&reg;: IDX Search",
        'description' => "Allow users to search for listings",
        'requires_key' => true,
        'shortcode' => 'idx_search',
        'max_cache_time' => 0,
        'widget' => true
        ),
    'fmcLocationLinks' => array(
        'component' => 'location-links.php',
        'title' => "Flexmls&reg;: 1-Click Location Searches",
        'description' => "Allow users to view listings from a custom search narrowed to a specific area",
        'requires_key' => true,
        'shortcode' => 'idx_location_links',
        'max_cache_time' => 0,
        'widget' => true
        ),
    'fmcIDXLinksWidget' => array(
        'component' => 'idx-links.php',
        'title' => "Flexmls&reg;: 1-Click Custom Searches",
        'description' => "Share popular searches with your users",
        'requires_key' => true,
        'shortcode' => 'idx_custom_links',
        'max_cache_time' => 0,
        'widget' => true
        ),
    /*
    'fmcLeadGen' => array(
        'component' => 'lead-generation.php',
        'title' => "Flexmls&reg;: Contact Me Form",
        'description' => "Allow users to share information with you",
        'requires_key' => true,
        'shortcode' => 'lead_generation',
        'max_cache_time' => 0,
        'widget' => true
        ),
       */
    /*
    'fmcNeighborhoods' => array(
        'component' => 'neighborhoods.php',
        'title' => "Flexmls&reg;: Neighborhood Page",
        'description' => "Create a neighborhood page from a template",
        'requires_key' => true,
        'shortcode' => 'neighborhood_page-hold',
        'max_cache_time' => 0,
        'widget' => false
        ),
    */
    'fmcListingDetails' => array(
        'component' => 'listing-details.php',
        'title' => "Flexmls&reg;: IDX Listing Details",
        'description' => "Insert listing details into a page or post",
        'requires_key' => true,
        'shortcode' => 'idx_listing_details',
        'max_cache_time' => 0,
        'widget' => false
        ),
    'fmcSearchResults' => array(
        'component' => 'v2/search-results.php',
        'title' => "Flexmls&reg;: IDX Listing Summary",
        'description' => "Insert a summary of listings into a page or post",
        'requires_key' => true,
        'shortcode' => 'idx_listing_summary',
        'max_cache_time' => 0,
        'widget' => false
        ),
    /*The agent search widget is only available to Offices and Mls's (not of usertype member)*/
    'fmcAgents' => array(
        'component' => 'fmc-agents.php',
        'title' => "Flexmls&reg;: IDX Agent List",
        'description' => "Insert agent information into a page or post",
        'requires_key' => true,
        'shortcode' => 'idx_agent_search',
        'max_cache_time' => 0,
        'widget' => false
        ),
    'fmcAccount' => array(
        'component' => 'my-account.php',
        'title' => "Flexmls&reg;: Log in",
        'description' => "Portal Login/Registration",
        'requires_key' => true,
        'shortcode' => 'idx_portal_login',
        'max_cache_time' => 0,
        'widget' => true
        ),
    );

global $fmc_widgets_integration;
$fmc_widgets_integration = $fmc_widgets;

$fmc_widgets_integration['fmcLeadGen'] = array(
	'component' => 'lead-generation.php',
	'title' => "Flexmls&reg;: Contact Me Form",
	'description' => "Allow users to share information with you",
	'requires_key' => true,
	'shortcode' => 'lead_generation',
	'max_cache_time' => 0,
	'widget' => true
);
$fmc_widgets_integration['fmcNeighborhoods'] = array(
	'component' => 'neighborhoods.php',
	'title' => "Flexmls&reg;: Neighborhood Page",
	'description' => "Create a neighborhood page from a template",
	'requires_key' => true,
	'shortcode' => 'neighborhood_page-hold',
	'max_cache_time' => 0,
	'widget' => false
);


$fmc_special_page_caught = array(
    'type' => null
);

$options = get_option('fmc_settings');
$api_key = isset( $options['api_key'] ) ? $options['api_key'] : '';
$api_secret = isset( $options['api_secret'] ) ? $options['api_secret'] : '';
$fmc_api = new flexmlsConnectUser($api_key,$api_secret);

if($options && array_key_exists('oauth_key', $options) && array_key_exists('oauth_secret', $options)) {
  $fmc_api_portal = new flexmlsConnectPortalUser($options['oauth_key'], $options['oauth_secret']);
}

$api_ini_file = $fmc_plugin_dir . '/lib/api.ini';

if (file_exists($api_ini_file)) {
  $local_settings = parse_ini_file($api_ini_file);
  if (array_key_exists('api_base', $local_settings)) {
    $fmc_api->api_base = trim($local_settings['api_base']);
    $fmc_api_portal->api_base = trim($local_settings['api_base']);
  }
}


$fmc_instance_cache = array();


/*
* register the init functions with the appropriate WP hooks
*/
//add_action('widgets_init', array('flexmlsConnect', 'widget_init') );

$active_account_check = $fmc_api->GetMyAccount();

if(!empty($api_key) && !empty($api_secret) && !empty($active_account_check) ){

	$options_int = get_option('fmc_settings');
	$options_integration = [
		'divi' => false,
		'elementor' => false,
		'wpbakery' => false
	];

	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	//------------------------------------
	//Elementor
	if (is_plugin_active('elementor/elementor.php')) {
		$options_integration['elementor'] = true;
		add_action( 'elementor/init', function() {
			require_once plugin_dir_path( __FILE__ ) . 'integration/elementor/index.php';

			\Elementor\Plugin::$instance->elements_manager->add_category('flexmls',
			[
				'title' => 'Flexmls&reg;',
				'icon' => 'fa fa-plug',
			]);

		});
	}
	//WPBackery
	if (is_plugin_active('js_composer/js_composer.php')) {
		$options_integration['wpbakery'] = true;
		require_once plugin_dir_path( __FILE__ ) . 'integration/wpbakery/index.php';
	}

	//-------------------

	$options_int['integration'] = $options_integration;
	update_option('fmc_settings', $options_int);
}

$fmc_admin = new flexmlsConnectSettings;

add_action('init', array('flexmlsConnect', 'initial_init') );


add_action('wp', array('flexmlsConnectPage', 'catch_special_request') );
add_action('wp', array('flexmlsConnect', 'wp_init') );

$fmc_search_results_loaded = false;
require_once plugin_dir_path( __FILE__ ) . 'lib/gutenberg.php';
