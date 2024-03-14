<?php

/**
 * Plugin Name: Fast Flow
 * Plugin URI: https://fastflow.io
 * Description: User tagging and dashboard plugin for Fast Flow system
 * Version: 1.2.15
 * Author: FastFlow.io
 * Author URI: https://fastflow.io
 *
 */


	// get wordpress version number and fill it up to 9 digits
	$int_wp_version = preg_replace('#[^0-9]#', '', get_bloginfo('version'));
	while(strlen($int_wp_version) < 9) {
		$int_wp_version .= '0';
	}

	// get php version number and fill it up to 9 digits
	$int_php_version = preg_replace('#[^0-9]#', '', phpversion());
	while(strlen($int_php_version) < 9) {
		$int_php_version .= '0';
	}

	if ($int_wp_version >= 390000000 && 		// Wordpress version > 3.9
		$int_php_version >= 520000000 && 		// PHP version > 5.2
		defined( 'ABSPATH' ) && 			// Plugin is not loaded directly
		defined( 'WPINC' )) {

		define('FAST_FLOW_PLUGIN_NAME', 'Fast Flow');
		define('FAST_FLOW_PLUGIN_SLUG', 'fast-flow');
		define('FAST_FLOW_DIR', dirname(__FILE__));
		define('FAST_FLOW_URL', plugins_url('/', __FILE__));
		require_once(ABSPATH . 'wp-admin/includes/screen.php');
		global $fast_tagger_db_version;
		$fast_tagger_db_version = '1.0';

		if ( !class_exists( 'Fast_Flow_Main' ) ) {
					include FAST_FLOW_DIR . '/lib/class.fastflow-main.php';
				}

				if ( class_exists( 'Fast_Flow_Main' ) ) {
					register_activation_hook( __FILE__, array( 'Fast_Flow_Main', 'fastflow_activate' ) );
					add_action( 'plugins_loaded', array( 'Fast_Flow_Main', 'fast_flow_before_init' ) );
					add_action( 'init', array( 'Fast_Flow_Main', 'fast_flow_main_init' ),15);
					add_action('wp_ajax_nopriv_fastflow_get_kartra_tags', array('Fast_Flow_Main', 'fastflow_get_kartra_tags'));
		      add_action('wp_ajax_fastflow_get_kartra_tags', array('Fast_Flow_Main', 'fastflow_get_kartra_tags'));
				}

				if ( !class_exists( 'Fast_Tagger_Widget' ) ) {
					//register Fast Tags widget for FF plugin
					include FAST_FLOW_DIR . '/lib/widgets/fast_tagger_widget_class.php';
				}
				if ( !class_exists( 'Fast_Flow_Html_Widget' ) ) {
					//register Fast Tags widget for FF plugin
					include FAST_FLOW_DIR . '/lib/widgets/fast_flow_html_widget_class.php';
				}
				if ( !class_exists( 'Fast_Flow_Widgets_Interface' ) ) {
					include FAST_FLOW_DIR . '/lib/class.fastflow.widgets.interface.php';
				}
				if ( class_exists( 'Fast_Flow_Widgets_Interface' ) ) {
					$obj = new Fast_Flow_Widgets_Interface();
					add_action( 'init', array( $obj, 'fast_flow_widgets_interface_init' ),12);
				}
				if ( !class_exists( 'FF_dashboard_color_schema_screen_opt' ) ) {
					include FAST_FLOW_DIR . '/lib/class.color_schema.screen.opt.php';
				}
				if ( class_exists( 'FF_dashboard_color_schema_screen_opt' ) ) {
					FF_dashboard_color_schema_screen_opt::init();
				}


				add_filter( 'admin_body_class', 'custom_class' );

				function custom_class( $classes ) {
					$val = get_user_option(
							sprintf('default_color_schema_%s', sanitize_key(get_current_screen()->id)),
							get_current_user_id());
					$min_val = get_user_option(
							sprintf('default_is_minimal_color_schema_%s', sanitize_key(get_current_screen()->id)),
							get_current_user_id());
							if($val == 'light'){
								$classes .= " ff-d-light ";
							}else if($val == 'dark'){
								$classes .= " ff-d-dark ";
							}
							if($min_val){
								$classes .= " ff-d-minimal ";
							}
							return $classes;
				}



	} else add_action('admin_notices', 'fastflow_incomp');


	function fastflow_incomp(){
		echo '<div id="message" class="error">
		<p><b>The &quot;Fast Flow&quot; Plugin does not work on this WordPress installation!</b></p>
		<p>Please check your WordPress installation for following minimum requirements:</p>
		<p>
		- WordPress version 3.9 or higer<br />
		- PHP version 5.2 or higher<br />
		</p>
		<p>Do you need help? Contact <a href="mailto:support@fastflow.io">Support</a></p>
		</div>';
	}

	function _ft($str) {

		return __($str, 'fast-tagger');

	}

	/*check FAC plugin active*/

	function is_fac_active(){

		$plugin = 'fast-activecampaign/fast-activecampaign.php';

		if ( is_plugin_active( $plugin ) ) {

			return true;

		}else{

			return false;

		}

	}



	/* My Dream
	if ( is_admin() ) {
        //add_filter('fm_prod_third_party_int', array($this, 'fcf_third_party_int_html' ), 10, 1);
        add_filter('ff_integration', array($this, 'ff_integration_content' ), 10, 1);
		function ff_integration_content($content){
			$output = "<div id='accordion'>";
			$output.= "";
			$output.= "</div>";
		}
    }
	*/
