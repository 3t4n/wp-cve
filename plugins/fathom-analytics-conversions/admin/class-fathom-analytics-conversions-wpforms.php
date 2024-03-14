<?php

/**
 * The WPForms-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpforms
 */

/**
 * The wpforms-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the wpforms-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpforms
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_WPForms {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Add js to track the form submission.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Check to add event id to new WPForms form
	 *
	 * @param int $post_ID The post's ID.
	 * @param object $post The post data.
	 *
	 * @since    1.0.0
	 */
	public function fac_wp_insert_post_wpforms( $post_ID, $post ) {
		// check if is a WPForms form
		if ( isset( $post->post_type ) && $post->post_type === 'wpforms' ) {
			// get form content
			$form_content = $post->post_content;

			// get form data
			if ( ! $form_content || empty( $form_content ) ) {
				$form_data = false;
			} else {
				$form_data = wp_unslash( json_decode( $form_content, true ) );
			}

			// get form setting
			$form_settings    = $form_data['settings'];
			$wpforms_event_id = isset( $form_settings['fac_wpforms_event_id'] ) ? $form_settings['fac_wpforms_event_id'] : '';
			$title            = $post->post_title;

			// add/update event id
			if ( empty( $wpforms_event_id ) ) {
				fa_add_event_id_to_wpforms( $post_ID, $title );
			} else {
				// check if event id exist
				$event = fac_get_fathom_event( $wpforms_event_id );
				if ( $event['code'] !== 200 ) {
					fa_add_event_id_to_wpforms( $post_ID, $title );
				} else {
					fac_update_fathom_event( $wpforms_event_id, $title );
				}
			}
		}
	}

	/**
	 * Add settings section to WPForms form admin
	 *
	 * @param array $sections The form's sections.
	 *
	 * @since    1.0.0
	 */
	public function fac_wpforms_builder_settings_sections( $sections ) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] ) {
			$sections['fac-wpforms'] = __( 'Fathom Analytics', 'fathom-analytics-conversions' );
		}

		return $sections;
	}

	/**
	 * WPForms custom panel
	 *
	 * @param object $settings The form's settings.
	 *
	 * @since    1.0.0
	 */
	public function fac_wpforms_form_settings_panel_content( $settings ) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] ) {
			echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-fac-wpforms">';
			echo '<div class="wpforms-panel-content-section-title">';
			esc_html_e( 'Fathom Analytics', 'fathom-analytics-conversions' );
			echo '</div>';
			if ( function_exists( 'wpforms_panel_field' ) ) {
				wpforms_panel_field(
					'text',
					'settings',
					'fac_wpforms_event_id',
					$settings->form_data,
					esc_html__( 'Event ID', 'fathom-analytics-conversions' ),
					array(
						/*'input_id'    => 'wpforms-panel-field-confirmations-redirect-' . $id,
						'input_class' => 'wpforms-panel-field-confirmations-redirect',
						'parent'      => 'settings',
						'subsection'  => $id,*/
						'readonly' => 'readonly',
						'after'    => '<p class="note">This event id is created for you automatically, and maintained by the Fathom Analytics Conversions plugin. You can refer to it in your Fathom Analytics settings.</p>',
					)
				);
			}

			do_action( 'wpforms_form_settings_fac-wpforms', $this );

			echo '</div>';
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!

				$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_WPFORMS, true );
				wp_enqueue_script( 'fac-wpforms-tracker', $fac4wp_plugin_url . 'public/js/fac-wpforms-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
			}
		}
	}

}
