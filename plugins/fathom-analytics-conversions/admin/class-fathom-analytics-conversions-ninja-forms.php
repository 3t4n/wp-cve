<?php

/**
 * The ninja-forms-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.3
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/ninja-forms
 */

/**
 * The ninja-forms-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the ninja-forms-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/ninja-forms
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_Ninja_Forms {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.3
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.3
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.3
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Adds setting menu on Ninja Forms.
		add_filter( 'ninja_forms_localize_forms_settings', array( $this, 'fac_ninja_forms_localize_forms_settings' ) );

		// Check to add event id to new form.
		add_action( 'ninja_forms_save_form', array( $this, 'fac_ninja_forms_save_form' ), 10, 2 );

		// Add js to track the form submission.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Form settings
	 *
	 * @param array $form_settings Menus.
	 *
	 * @since    1.3
	 */
	public function fac_ninja_forms_localize_forms_settings( $form_settings ) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_NINJAFORMS ] ) {
			$form_settings['display']['fathom_analytics'] = array(
				'name'  => 'fathom_analytics',
				'type'  => 'textbox',
				'label' => esc_html__( 'Fathom Analytics', 'fathom-analytics-conversions' ),
				'width' => 'full',
				'group' => 'primary',
				'value' => '',
				'help'  => esc_html__( 'This event id is created for you automatically, and maintained by the Fathom Analytics Conversions plugin. You can refer to it in your Fathom Analytics settings.', 'fathom-analytics-conversions' ),
			);
		}

		return $form_settings;
	}

	/**
	 * Check to add event id to new form
	 *
	 * @param int $form_id The form id.
	 *
	 * @since    1.0.0
	 */
	public function fac_ninja_forms_save_form( $form_id ) {
		global $fac4wp_options;
		if ( $form_id && $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_NINJAFORMS ] ) {
			// Add/update event id.
			fac_update_event_id_to_nj( $form_id );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_NINJAFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!

				$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_NINJAFORMS, true );
				wp_enqueue_script( 'fac-njforms-tracker', $fac4wp_plugin_url . 'public/js/fac-njf-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
			}
		}
	}

}
