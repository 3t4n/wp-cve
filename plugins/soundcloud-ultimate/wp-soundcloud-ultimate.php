<?php
/*
Plugin Name: WP SoundCloud Ultimate
Plugin URI: http://www.wpsolutions-hq.com/
Description: A plugin which allows you to play/upload/manage your SoundCloud tracks and music directly from your WP site.
Author: WPSolutions HQ
Version: 1.5
Author URI: http://www.wpsolutions-hq.com/
*/


if(!class_exists('soundCloudUltimate')) 
{
	class soundCloudUltimate 
	{
		/*
		 * Declare keys here as well as our tabs array which 
		 * is populated when registering settings
		 */
		private $edit_scu_settings_page_key = 'edit_scu_settings_page';
		private $scu_display_page_key = 'scu_display_page';
		private $scu_display_usage_key = 'scu_display_usage';
		private $wpshq_scu_options_key = 'wpshq_scu_plugin_options';
		private $wpshq_scu_settings_tabs = array();
	
		function __construct() {
			if ( is_admin() ) {
				add_action( 'wp_ajax_nopriv_scu_ajax_notification', array(&$this, 'scu_ajax_notification' ));
				add_action( 'wp_ajax_scu_ajax_notification', array( &$this, 'scu_ajax_notification' ) );
			}
			
			$this->define_constants();
			$this->loader_operations();			
		}

		function define_constants() {
			define('WPSHQ_SCU_PLUGIN_PATH', dirname(__FILE__));
			define('WPSHQ_SCU_PLUGIN_URL', plugins_url('',__FILE__));
		}
		
		function loader_operations(){
			add_action( 'init', array( &$this, 'init_tasks' ) );
			add_action('plugins_loaded', array( &$this,'soundcloud_ultimate_plugins_loaded'));
			add_action( 'admin_init', array( &$this, 'admin_init_tasks' ) );
			add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );
			if (!is_admin())
			{
				add_filter('widget_text', 'do_shortcode');
			}
		}
		
		function load_libs() {
			wp_enqueue_script('jquery');
			if (isset($_GET['page']) && $_GET['page'] == 'wpshq_scu_plugin_options') {
			//ajax/jquery script enqueues etc
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-widget');
				wp_enqueue_script('jquery-ui-position');
				wp_enqueue_script('jquery-ui-mouse');
				wp_enqueue_script('jquery-ui-dialog');
				wp_enqueue_script( 'scu-ajax-js', WPSHQ_SCU_PLUGIN_URL.'/includes/js/soundcloud-ultimate-ajax.js', array( 'jquery-ui-dialog' ) );
	
				wp_localize_script('scu-ajax-js', 'SCU_JS', array(
														    'ajaxurl'=>admin_url('admin-ajax.php'), 
														    'scu_nonce'=>wp_create_nonce('wp_scu_nonce'), 
														    'scu_url'=>WPSHQ_SCU_PLUGIN_URL));    
				//mdeia upload stuff
				wp_enqueue_script('media-upload');
				wp_enqueue_script('thickbox');
				wp_register_script('wp-scu-media-upload', WPSHQ_SCU_PLUGIN_URL.'/includes/js/scu_file_upload.js', array('jquery','media-upload','thickbox'));
				wp_enqueue_style( 'dialogStylesheet', includes_url().'css/jquery-ui-dialog.css' );
				wp_enqueue_script('wp-scu-media-upload');	    	
				wp_enqueue_style('thickbox'); //style sheet for thickbox
			}	
		}
		
		function init_tasks()
		{
			if(is_admin()){
				$this->load_settings();
				$this->load_libs();
			}else{
				//add front end init tasks here
			}
		}		
		
		function admin_init_tasks()
		{
			$this->register_scu_settings_page();
			$this->register_scu_display_page();
			$this->register_scu_display_usage();
			$this->scu_options_setup();
		}

		function soundcloud_ultimate_plugins_loaded()
		{	
			add_shortcode('soundcloud_ultimate', array( &$this, 'soundcloud_ultimate_sc_handler') );
		}	

		
		function scu_options_setup() {
			global $pagenow;
			if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
				// Here we will customize the 'Insert into Post' Button text inside Thickbox
				add_filter( 'gettext', array($this, 'replace_thickbox_text'), 1, 2);
			}
		}
		
		function soundcloud_ultimate_sc_handler($atts) {	
			require_once 'includes/Services/Soundcloud.php';
			// this is the shortcode to display the soundcloud widget
			extract(shortcode_atts(array(
				'track' => 'no track',
				'auto_play' => 'false'
			), $atts));
		
			if($track == "no track"){
				return '<div class="error fade" style="color:red;"><p><strong>SoundCloud Ultimate Error: You need to specify a track URL in your shortcode.</strong></p></div>';
			}
			$track = strip_tags($track);
			$auto_play = strip_tags($auto_play);
			$soundcloud_widget = $this->get_soundcloud_widget_code($track, $auto_play);
			
			return $soundcloud_widget;
		}
		
		function get_soundcloud_widget_code($track, $auto_play=false) {
			//get soundcloud options
			require_once 'includes/Services/Soundcloud.php';
			$sc_options = get_option('soundcloud_settings');
			if ($sc_options) {
				$sc_id = $sc_options['sc_client_id'];
				$sc_secret = $sc_options['sc_client_secret']; 
				$sc_token = $sc_options['sc_client_access_token'];
				$sc_redirect_uri = $sc_options['sc_redirect_uri'];
			}
			
			
			$soundcloud = new Services_Soundcloud($sc_id, $sc_secret, $sc_redirect_uri);
			if ($sc_token && $sc_id && $sc_secret) {
				$soundcloud->setAccessToken($sc_token);
			} else {
				//display an error stating that the user needs to authenticate first
				return '<div style="color:red;"><p><strong>SoundCloud Ultimate Error: You need to authenticate your connection to the SoundCloud API.
				<br />Please go to the SoundCloud Ultimate plugin settings and configure your API credentials.</strong></p></div>';
			}
			//first check if track has finished uploading
			//$tracks = $soundcloud->get('tracks/');
			$soundcloud->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));
			try {
				$track_list = $soundcloud->get('me/tracks');
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				if ($e->getHttpCode() == '302') {
					//Special case - 302 received - continue to try and render the widget.....
					$track_widget = $this->track_widget($soundcloud, $track, $auto_play);
					return $track_widget;
                                    
				}else{
					return '<div style="color:red;"><p><strong>SoundCloud Ultimate Error: Could not display your SoundCloud track - Error code ('.$e->getHttpCode().').</strong></p></div>';	
				}
			}
			$tracks = json_decode($track_list, true);
			foreach ($tracks as $item) {
				if (($item['permalink_url'] == $track) && $item['state'] != 'finished'){
					return false;
				}
			}			
			
			try {
				$track_embed_data = $soundcloud->get('oembed', array('url' => $track, 'auto_play' => $auto_play));
				
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				if ($e->getHttpCode() == '302') {
					//Special case - 302 received - continue to try and render the widget.....
					$track_widget = $this->track_widget($soundcloud, $track, $auto_play);
					return $track_widget;
				}else if ($e->getHttpCode() == '404') {
					return '<div style="color:red;"><p><strong>SoundCloud Ultimate Error: The track you specified in the shortcode does not exist in your account.</strong></div>';
				}else{
					return '<div style="color:red;"><p><strong>SoundCloud Ultimate Error: Could not display your SoundCloud track - Error code ('.$e->getHttpCode().').</strong></p></div>';	
				}
				
			}
			//$embed_info = json_decode($soundcloud->get('oembed', array('url' => $track)));
			$embed_info = json_decode($track_embed_data);
			//now get the html code for the player widget
			//print $embed_info->html;
			
			$track_widget = $embed_info->html;
			return $track_widget;
			
		}
		
		function track_widget($soundcloud, $track, $auto_play=false)
		{
			$track_embed_data = $soundcloud->get('oembed', array('url' => $track, 'auto_play' => $auto_play));
			$embed_info = json_decode($track_embed_data);
			//now get the html code for the player widget
			//print $embed_info->html;
			
			$track_widget = $embed_info->html;
			return $track_widget;
		}

	function scu_ajax_notification()
	{
		$nonce = strip_tags($_REQUEST['nonce']);
	    if(!wp_verify_nonce($nonce,'wp_scu_nonce')){
	        die('Security check failed in ajax add to cart handler.');
	    }     	
		
		//$action = strip_tags($_REQUEST['action']);
		$track_url = strip_tags($_REQUEST['track_url']);
//		try {
			$widget_code = $this->get_soundcloud_widget_code($track_url);
			if ($widget_code){
				$response = json_encode( array( 'status' => 'success', 'output' => $widget_code ) );				
			}else {
		    $error_msg = '<div style="color:red;"><p style="text-align:center;"><strong>This track has not finished uploading yet.</strong></div>'; 
			$response = json_encode( array( 'status' => 'error', 'output' => $error_msg ) );
				
			}

//		} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
		    //exit($e->getMessage());
//		}
		echo $response;
		die();
	}		
		
		function replace_thickbox_text($translated_text, $text ) {	
			if ( 'Insert into Post' == $text ) {
				$referer = strpos( wp_get_referer(), 'wpshq_scu_plugin_options' );
				if ( $referer != '' ) {
					return ('Select For SoundCloud Upload');
				}
			}
			return $translated_text;
		}
		
		/*
		 * Loads both tab settings from
		 * the database into their respective arrays. Uses
		 * array_merge to merge with default values if they're
		 * missing.
		 */
		function load_settings() {
			$this->edit_scu_settings = (array) get_option( $this->edit_scu_settings_page_key );
			$this->scu_display_settings = (array) get_option( $this->scu_display_page_key );
			$this->scu_display_usage = (array) get_option( $this->scu_display_usage_key );
			// Merge with defaults
			$this->edit_scu_settings_page = array_merge( array(
				'edit_scu_option' => 'SoundCloud Settings Page'
			), $this->edit_scu_settings );
			
			$this->scu_display_settings_page = array_merge( array(
				'scu_display_option' => 'Manage Tracks'
			), $this->scu_display_settings );
		
			$this->scu_display_usage_page = array_merge( array(
				'scu_usage_option' => 'Usage Info'
			), $this->scu_display_usage );
		}
		
		/*
		 * Registers the display templates page via the Settings API,
		 * appends the setting to the tabs array of the object.
		 */
		function register_scu_settings_page() {
			$this->wpshq_scu_settings_tabs[$this->edit_scu_settings_page_key] = 'Settings';
			register_setting( $this->edit_scu_settings_page_key, $this->edit_scu_settings_page_key );
		}
		
		function register_scu_display_page() {
			$this->wpshq_scu_settings_tabs[$this->scu_display_page_key] = 'Manage Tracks';
			register_setting( $this->scu_display_page_key, $this->scu_display_page_key );
		}
		
		function register_scu_display_usage() {
			$this->wpshq_scu_settings_tabs[$this->scu_display_usage_key] = 'Usage Info';
			register_setting( $this->scu_display_usage_key, $this->scu_display_usage_key );
		}
		
		/******************************************************************************
		 * Now we just need to define an admin page.
		 ******************************************************************************/
	
		/*
		 * Called during admin_menu, adds an options
		 * page under Settings 
		*/
		
		function add_admin_menus(){
			$scu_admin_page = add_menu_page('SC Ultimate', 'SC Ultimate', 'manage_options', $this->wpshq_scu_options_key, array(&$this, 'scu_page'), WPSHQ_SCU_PLUGIN_URL.'/includes/images/soundcloud-icon.png');
			add_action( 'admin_print_styles-' .$scu_admin_page, array( &$this, 'scu_load_style' ) );
		}

		function scu_load_style() {
			/** Register */
		    wp_register_style('scu-styles', WPSHQ_SCU_PLUGIN_URL.'/includes/css/scu_style.css', array(), '1.0.0', 'all');
			
			 /** Enqueue 
			  * It will be called only on your plugin admin page, enqueue our stylesheet here*/
		    wp_enqueue_style('scu-styles');
		}		
		/*
		 * Plugin Options page rendering goes here, checks
		 * for active tab and replaces key with the related
		 * settings key. Uses the plugin_options_tabs method
		 * to render the tabs.
		 */
		function scu_page() {
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->edit_scu_settings_page_key;
			?>
			<div class="wrap">
				<?php 
				$this->plugin_options_tabs();
				if ($tab == 'edit_scu_settings_page')
				{
					include_once('wp-scu-settings.php');
					//TODO: 
					displaySCUSettings();				
				}
				else if ($tab == 'scu_display_page')
				{
					include_once('wp-scu-display-tracks.php');
					renderTracksList();				
				}
				else if ($tab == 'scu_display_usage')
				{
					include_once('wp-scu-display-usage.php');
				}
				?>
			</div>
			<?php
		}
		
		function current_tab() {
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->edit_scu_settings_page_key;
			return $tab;
		}
		
		/*
		 * Renders our tabs in the plugin options page,
		 * walks through the object's tabs array and prints
		 * them one by one. Provides the heading for the
		 * plugin_options_page method.
		 */
		function plugin_options_tabs() {
			$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->edit_scu_settings_page_key;
	
			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $this->wpshq_scu_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->wpshq_scu_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
			}
			echo '</h2>';
		}
	} //end class
}
$wpshq_scu_page = new soundCloudUltimate();
// Register for activation
//register_activation_hook( __FILE__, array( &$wpshq_scu_page, 'eStore_scu_installer' ));
?>