<?php

/**
 * The fluentform-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.3
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/fluentform
 */

/**
 * The fluentform-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the fluentform-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/fluentform
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_Fluent_Form {

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

		// Adds setting menu on Fluent Form.
		add_action( 'fluentform_form_settings_menu', array( $this, 'fac_fluentform_form_settings_menu' ) );
		// Save the meta when post is saved.
		add_action( 'fluentform_form_settings_container_fac_ff', array(
			$this,
			'fac_fluentform_form_settings_container_fac_ff'
		) );

		// Check to add event id to new form.
		add_action( 'fluentform_after_save_form_settings', array(
			$this,
			'fac_fluentform_after_save_form_settings'
		), 10, 2 );

		// Save/update form
		add_filter( 'fluentform_form_fields_update', array( $this, 'fac_fluentform_form_fields_update' ), 10, 2 );

		// Add hidden field to FF form - frontend.
		add_action( 'fluentform_form_element_start', array( $this, 'fac_fluentform_form_element_start' ) );

		// Add js to track the form submission.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

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
		$settings = get_option( 'fac_fform_' . $id, self::defaults() );

		return wp_parse_args( $settings, self::defaults() );
	}

	/**
	 * Register meta box(es)
	 *
	 * @param array $settingsMenus Menus.
	 *
	 * @since    1.3
	 */
	public function fac_fluentform_form_settings_menu( $settingsMenus ) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] ) {
			$settingsMenus['fac_fluentform'] = array(
				'title' => __( 'Fathom Analytics', 'fathom-analytics-conversions' ),
				'slug'  => 'fac_ff',
				//'hash' => 'fac_fluentform',
				//'route' => '/fac-ff'
			);
		}

		return $settingsMenus;
	}

	/**
	 * Register meta box(es)
	 *
	 * @param int $form_id Form ID.
	 *
	 * @since    1.3
	 */
	public function fac_fluentform_form_settings_container_fac_ff( $form_id ) {
		global $fac4wp_options;
		if ( $form_id && $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] ) {
			self::save();

			$settings = self::form_options( $form_id );
			?>
            <div id="ff_form_settings_fac">
                <form action="" method="post">
                    <div class="setting_header el-row">
                        <div class="el-col el-col-24 el-col-md-12">
                            <h2><?php _e( 'Fathom Analytics Settings', 'fathom-analytics-conversions' ); ?></h2>
                        </div>
                    </div>
                    <div class="ff_settings_section">
                        <div class="ff_settings_body">
                            <div class="el-form-item">
                                <label class="el-form-item__label" style="width: 205px; text-align: left;">
									<?php _e( 'Event ID', 'fathom-analytics-conversions' ); ?>
                                </label>
                                <div class="el-form-item__content" style="margin-left: 205px;">
                                    <div class="el-input el-input--small">
                                        <input type="text" autocomplete="off" class="el-input__inner"
                                               name="fac-fform[event_id]"
                                               value="<?php echo esc_attr( $settings['event_id'] ); ?>" readonly>

                                        <p><?php _e( 'This event id is created for you automatically, and maintained by the Fathom Analytics Conversions plugin. You can refer to it in your Fathom Analytics settings.', 'fathom-analytics-conversions' ); ?></p>
                                    </div>
                                </div>
                            </div>
                            <!--<p><br>This feature is only available in pro version of Fluent Forms</p></div>-->
                        </div>
                    </div>

                    <input type="hidden" id="form_id" name="form_id" value="<?php echo esc_attr( $form_id ); ?>"/>
					<?php
					submit_button();
					wp_nonce_field( 'fform_fac_settings_edit', 'fform_fac_settings_edit' );
					?>
                </form>
            </div>
			<?php
		}
	}

	/**
	 * Save form options.
	 */
	public static function save() {

		if ( empty( $_POST ) || ! check_admin_referer( 'fform_fac_settings_edit', 'fform_fac_settings_edit' ) ) {
			return;
		}

		if ( empty( $_POST['form_id'] ) ) {
			return;
		}

		$form_id = sanitize_text_field( $_POST['form_id'] );

		if ( ! empty( $_POST['fac-fform'] ) ) {
			$settings = fac_array_map_recursive( 'esc_attr', $_POST['fac-fform'] );

			update_option( 'fac_fform_' . $form_id, $settings );
		}
		else {
			delete_option( 'fac_fform_' . $form_id );
		}
	}

	/**
	 * Check to add event id to new form
	 *
	 * @param int $form_id The form id.
	 * @param array $allSettings The form settings.
	 *
	 * @since    1.0.0
	 */
	function fac_fluentform_after_save_form_settings( $form_id, $allSettings ) {
		global $fac4wp_options, $wpdb;
		if ( $form_id && $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] ) {

			$formsTable = $wpdb->prefix . 'fluentform_forms';
			$fForm      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$formsTable} WHERE id=%d", $form_id ), ARRAY_A );

			if ( $fForm ) {
				$title = $fForm['title'];
				// Add/update event id.
				fac_update_event_id_to_ff( $form_id, $title );
			}
		}
	}

	function fac_fluentform_form_fields_update( $formFields, $form_id ) {
		global $fac4wp_options, $wpdb;
		if ( $form_id && $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] ) {

			$formsTable = $wpdb->prefix . 'fluentform_forms';
			$fForm      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$formsTable} WHERE id=%d", $form_id ), ARRAY_A );

			if ( $fForm ) {
				$title = $fForm['title'];
				// Add/update event id.
				fac_update_event_id_to_ff( $form_id, $title );
			}
		}

		return $formFields;
	}

	/**
	 * Add hidden field to FF - frontend
	 *
	 * @param \StdClass $form The form entry.
	 *
	 * @since    1.0.0
	 */
	function fac_fluentform_form_element_start( $form ) {
		global $fac4wp_options;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] ) {

			$form_id         = $form->id;
			$fac_ff          = get_option( 'fac_fform_' . $form_id, array() );
			$fac_ff_event_id = is_array( $fac_ff ) && isset( $fac_ff['event_id'] ) ? $fac_ff['event_id'] : '';

			if ( ! empty( $fac_ff_event_id ) ) {
				echo '<input type="hidden" name="_fac_ff_event_id" value="' . esc_attr( $fac_ff_event_id ) . '">';
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!

				$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_FLUENTFORMS, true );
				wp_enqueue_script( 'fac-fforms-tracker', $fac4wp_plugin_url . 'public/js/fac-ff-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
			}
		}
	}

}
