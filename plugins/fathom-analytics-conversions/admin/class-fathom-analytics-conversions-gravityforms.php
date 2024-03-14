<?php
/**
 * The GravityForms-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/gravityforms
 */

/**
 * The gravityforms-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the gravityforms-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/gravityforms
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_GravityForms {

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
	 * Check to add event id to new GravityForms form.
	 *
	 * @param array $form The form meta
	 * @param bool $is_new Returns true if this is a new form.
	 *
	 * @since    1.0.0
	 */
	public function fac_gform_after_save_form( $form, $is_new ) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] ) {
			$form_id    = $form['id'];
			$form_title = $form['title'];
			//echo '<pre>';print_r( $form );echo '</pre>';
			$settings = self::form_options( $form_id );

			// add/update event id.
			if ( empty( $settings['event_id'] ) ) {
				fa_add_event_id_to_gf( $form_id, $form_title );
			}
			else {
				// check if event id exist.
				$event = fac_get_fathom_event( $settings['event_id'] );
				if ( $event['code'] !== 200 ) {
					fa_add_event_id_to_gf( $form_id, $form_title );
				}
				else {
					fac_update_fathom_event( $settings['event_id'], $form_title );
				}
			}
		}
	}

	/**
	 * Get default values.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'event_id' => '',
		);
	}

	/**
	 * Get a specific forms options.
	 *
	 * @param $id
	 *
	 * @return array
	 */
	public static function form_options( $id ) {
		$settings = get_option( 'gforms_fac_' . $id, self::defaults() );

		return wp_parse_args( $settings, self::defaults() );
	}

	/**
	 * Add settings tab to Gravity Forms form admin.
	 *
	 * @param array $setting_tabs The settings tabs.
	 *
	 * @since    1.0.0
	 */
	public function fac_gform_form_settings_menu( $setting_tabs ) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] ) {
			$setting_tabs['50'] = array(
				'name'         => 'fac-gform',
				'label'        => __( 'Fathom Analytics', 'fathom-analytics-conversions' ),
				'icon'         => 'gform-icon--cog',
				'query'        => array( 'nid' => null ),
				'capabilities' => array( 'gravityforms_edit_forms' ),
			);
		}

		return $setting_tabs;
	}

	/**
	 * GravityForms custom setting page.
	 *
	 * @since    1.0.0
	 */
	public function fac_gform_render_settings_page() {
		global $fac4wp_options;
		if ( function_exists( 'rgget' ) ) {
			$form_id = rgget( 'id' );
		}
		else {
			$form_id = 0;
		}

		if ( $form_id && $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] && class_exists( 'GFFormSettings' ) ) {

			self::save();

			$settings = self::form_options( $form_id );

			GFFormSettings::page_header( __( 'Fathom Analytics Settings', 'fathom-analytics-conversions' ) );

			?>

            <div class="gform-settings-panel">

                <header class="gform-settings-panel__header">
                    <h4 class="gform-settings-panel__title">
                        <span><?php _e( 'Fathom Analytics Settings', 'fathom-analytics-conversions' ); ?></span></h4>
                </header>

                <div class="gform-settings-panel__content">

                    <div id="fac_gform_settings-editor">

                        <form id="fac_settings_edit_form" method="post">

                            <div id="gform_setting_event_id" class="gform-settings-field gform-settings-field__text">
                                <div class="gform-settings-field__header">
                                    <label class="gform-settings-label" for="gforms-fac-event-id">
										<?php _e( 'Event ID', 'fathom-analytics-conversions' ); ?>
                                    </label>
                                </div>
                                <span class="gform-settings-input__container">
                                    <input type="text" name="gforms-fac[event_id]"
                                           value="<?php echo esc_attr( $settings['event_id'] ); ?>"
                                           id="gforms-fac-event-id" readonly>
                                </span>
                                <p><?php _e( 'This event id is created for you automatically, and maintained by the Fathom Analytics Conversions plugin. You can refer to it in your Fathom Analytics settings.', 'fathom-analytics-conversions' ); ?></p>
                            </div>

                            <input type="hidden" id="form_id" name="form_id"
                                   value="<?php echo esc_attr( $form_id ); ?>"/>

                            <p class="gform-settings-save-container">
                                <button type="submit" name="save" value="save" class="primary button large">
									<?php _e( 'Save Settings &nbsp;→', 'fathom-analytics-conversions' ); ?>
                                </button>
                            </p>

							<?php wp_nonce_field( 'gform_fac_settings_edit', 'gform_fac_settings_edit' ); ?>

                        </form>

                    </div>

                </div>

            </div>

			<?php

			GFFormSettings::page_footer();
		}
	}

	/**
	 * Save form options.
	 */
	public static function save() {

		if ( empty( $_POST ) || ! check_admin_referer( 'gform_fac_settings_edit', 'gform_fac_settings_edit' ) || ! function_exists( 'rgget' ) ) {
			return;
		}

		$form_id = rgget( 'id' );

		if ( ! empty( $_POST['gforms-fac'] ) ) {
			$settings = fac_array_map_recursive( 'esc_attr', $_POST['gforms-fac'] );

			// Sanitize values.
			//$settings['event_id'] = ! empty( $settings['event_id'] ) ? esc_attr( $settings['event_id'] ) : '';

			update_option( 'gforms_fac_' . $form_id, $settings );
		}
		else {
			delete_option( 'gforms_fac_' . $form_id );
		}
	}

	/**
	 * Sets all forms to Ajax only.
	 *
	 * @param array $form_args The form arguments.
	 *
	 * @since 1.0.0
	 *
	 */
	public function fac_gform_ajax_only( $form_args ) {
		global $fac4wp_options;

		if ( class_exists( 'GFCommon' ) && GFCommon::is_preview() ) {
			return $form_args;
		}

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			$form_args['ajax'] = true;
		}

		return $form_args;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!

				$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS, true );
				// wp_enqueue_script( 'fac-gforms-tracker', $fac4wp_plugin_url . 'public/js/fac-gforms-tracker.js', array(), filemtime( plugin_dir_path( __FILE__ ) . 'js/fac-gforms-tracker.js' ), $in_footer );
				wp_enqueue_script( 'fac-gforms-tracker', $fac4wp_plugin_url . 'public/js/fac-gforms-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
				$gforms_data = array();
				if ( class_exists( 'GFAPI' ) ) {
					$gf_forms = GFAPI::get_forms( true, false ); // get all gforms.
					if ( $gf_forms ) {
						foreach ( $gf_forms as $form ) {
							$form_id                 = $form['id'];
							$fac_gf                  = get_option( 'gforms_fac_' . $form_id, array() );
							$fac_gf_event_id         = is_array( $fac_gf ) && isset( $fac_gf['event_id'] ) ? $fac_gf['event_id'] : '';
							$gforms_data[ $form_id ] = $fac_gf_event_id;
						}
					}
				}
				wp_localize_script( 'fac-gforms-tracker', 'gforms_data', $gforms_data );
			}
		}

	}

}
