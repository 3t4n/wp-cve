<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/public
 * @author     Your Name <email@example.com>
 */
class GeoTarget_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $GeoTarget    The ID of this plugin.
	 */
	private $GeoTarget;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin functions
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    Plugin functions
	 */
	private $functions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $GeoTarget       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $GeoTarget, $version, $functions ) {

		$this->GeoTarget 	= $GeoTarget;
		$this->version 		= $version;
		$this->functions 	= $functions;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in GeoTarget_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GeoTarget_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'css/geotarget-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {


		wp_enqueue_script( $this->GeoTarget, plugin_dir_url( __FILE__ ) . 'js/geotarget-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'geot-slick', plugin_dir_url( __FILE__ ) . 'js/ddslick.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the popup rules fields
	 */
	function register_popup_fields() {
		if( class_exists( 'Spu_Helper') ) {
			add_action( 'spu/rules/print_geot_country_field', array( 'Spu_Helper', 'print_select'), 10, 2 );
		}
	}

	/**
	 * Add rules to Popups plugin
	 * @param $choices
	 */
	public function add_popups_rules( $choices ) {
		$choices['Geotargeting'] = array(
			'geot_country'  => 'Country'
		);
		return $choices;
	}

	/**
	 * Return countries for popup rules
	 *
	 * @param $choices
	 *
	 * @return mixed
	 */
	public function add_popups_rules_choices($choices) {
		$countries = apply_filters('geot/get_countries', array());
		foreach( $countries as $c ) {
			$choices[$c->iso_code] = $c->country;
		}
		return $choices;
	}

	/**
	 * [rule_match_logged_user description]
	 * @param  bool $match false default
	 * @param  array $rule rule to compare
	 * @return boolean true if match
	 */
	function popup_match_rules( $match, $rule ) {

		if ( $rule['operator'] == "==" ) {

			return geot_target( $rule['value'] );

		} else {

			return !geot_target( $rule['value'] );

		}

	}
	/**
	 * Print current user data in footer
	 */
	public function print_debug_info() {
		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
		if( empty( $opts['debug_mode'] ) )
			return;
		$user_data = $this->functions->calculateUserCountry();
		if( empty( $user_data ) )
			return;
		?>
		<!-- Geotargeting plugin Debug Info START-->
		<div id="geot-debug-info" style="display: none;"><!--
		Country: <?php echo @$user_data->name . PHP_EOL;?>
		Country code: <?php echo @$user_data->isoCode . PHP_EOL;?>
		IP: <?php echo $this->functions->getUserIP() . PHP_EOL;?>
		Geot Version: <?php echo GEOT_VERSION . PHP_EOL;?>
		PHP Version: <?php echo phpversion() . PHP_EOL;?>
		-->
		</div>
		<!-- Geotargeting plugin Debug Info END-->
		<?php
	}
}
