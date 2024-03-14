<?php
/**
 * PeachPay Extension Admin Trait.
 *
 * @package PeachPay/Admin
 *
 * @phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;


/**
 * Base for creating a PeachPay admin tab page.
 */
abstract class PeachPay_Admin_Tab {

	/**
	 * The plugin ID. Used for option names.
	 *
	 * @var string
	 */
	public $plugin_id = 'peachpay_';

	/**
	 * The id to store the admin settings with. (This should be unique).
	 *
	 * @var string
	 */
	public $id = '';

	/**
	 * Validation errors.
	 *
	 * @var array of strings
	 */
	public $errors = array();

	/**
	 * Setting values.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Form option fields.
	 *
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * The posted settings data. When empty, $_POST data will be used.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Initializes the settings tab.
	 *
	 * @param boolean $settings_only Ensures rendering logic is not initialized when the class is being instantiated only for settings use.
	 *
	 * @throws Exception If the $id field is not set.
	 */
	final public function __construct( $settings_only = false ) {
		$this->plugin_id = 'peachpay_';
		if ( ! $this->id ) {
			throw new Exception( 'Developer Error: Tab id must be defined.' );
		}
		$this->id = $this->id . '_admin_settings';

		$this->form_fields = $this->register_form_fields();
		$this->init_settings();

		if ( ! $this->is_active() || $settings_only ) {
			return;
		}

		$this->hooks();
		$this->includes();
	}

	/**
	 * Initialize actions and filters . This should not be attempted to be overridden . Any custom hooks
	 * should be defined in a hooks . php file and loaded in in $this->includes() of the parent integration file .
	 * */
	private function hooks() {
		$tab_view = $this;

		add_action(
			'admin_enqueue_scripts',
			function () use ( $tab_view ) {
				$tab_view->enqueue_admin_scripts();
			}
		);
	}

	/**
	 * Register your form fields in this class.
	 */
	protected function register_form_fields() {
		return array();
	}

	/**
	 * Load extension specific public scripts here.
	 */
	protected function enqueue_admin_scripts() { }

	/**
	 * Gets the tab key.
	 */
	abstract public function get_tab();

	/**
	 * Gets the tab key.
	 */
	abstract public function get_section();

	/**
	 * Gets the title of the tab.
	 */
	abstract public function get_title();

	/**
	 * Gets the description of the tab.
	 */
	abstract public function get_description();


	/**
	 * Initialize classes and functions. This is probably the best place to
	 * load utility functions and admin settings related code.
	 */
	abstract protected function includes();

	/**
	 * Renders the Actual admin tab.
	 */
	public function do_admin_view() {
		?>
		<form method="POST" action="" enctype="multipart/form-data">
			<table class="form-table">
				<?php $this->generate_settings_html( $this->get_form_fields(), true ); ?>
			</table>
			<p class="submit">
				<button name="save" class="button-primary pp-button-primary"" type="submit" value="<?php esc_attr_e( 'Save changes', 'peachpay-for-woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'peachpay-for-woocommerce' ); ?></button>
				<?php wp_nonce_field( 'peachpay-settings' ); ?>
			</p>
		</form>
		<?php
	}

	/**
	 * Indicates if URL is visiting this tab.
	 */
	public function is_active() {
		// PHPCS:disable WordPress.Security.NonceVerification.Recommended
		$page_key    = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$section_key = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';
		$tab_key     = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		// PHPCS:enable

		return is_admin() && 'peachpay' === $page_key && $this->get_section() === $section_key && $this->get_tab() === $tab_key;
	}

	/**
	 * Gets the URL of a tabbed view in this section.
	 */
	public static function get_url() {
		$settings_instance = new static( true );

		return admin_url( 'admin.php?page=peachpay&section=' . $settings_instance->get_section() . '&tab=' . $settings_instance->get_tab() );
	}

	/**
	 * Gets the desired settings value.
	 *
	 * @param string $setting The key of the setting to retrieve a value for.
	 */
	public static function get_setting( $setting = '' ) {
		$settings_instance = new static( true );
		return $settings_instance->get_option( $setting );
	}

	/**
	 * Sets the value for a specific setting.
	 *
	 * @param string $setting The key of the setting to set a value for.
	 * @param mixed  $value The value to set for the specified setting.
	 */
	public static function update_setting( $setting, $value ) {
		$settings_instance = new static( true );
		return $settings_instance->update_option( $setting, $value );
	}

	/**
	 * Get the form fields after they are initialized.
	 *
	 * @return array of options
	 */
	public function get_form_fields() {
		return apply_filters( 'woocommerce_settings_api_form_fields_' . $this->id, array_map( array( $this, 'set_defaults' ), $this->form_fields ) );
	}

	/**
	 * Set default required properties for each field.
	 *
	 * @param array $field Setting field array.
	 * @return array
	 */
	protected function set_defaults( $field ) {
		if ( ! isset( $field['default'] ) ) {
			$field['default'] = '';
		}
		return $field;
	}

	/**
	 * Output the admin options table.
	 */
	public function admin_options() {
		echo '<table class="form-table">' . $this->generate_settings_html( $this->get_form_fields(), false ) . '</table>'; // phpcs:ignore
	}

	/**
	 * Initialise settings form fields.
	 *
	 * Add an array of fields to be displayed on the gateway's settings screen.
	 *
	 * @since  1.0.0
	 */
	public function init_form_fields() {}

	/**
	 * Return the name of the option in the WP DB.
	 *
	 * @since 2.6.0
	 * @return string
	 */
	public function get_option_key() {
		return $this->plugin_id . $this->id . '_settings';
	}

	/**
	 * Get a fields type. Defaults to "text" if not set.
	 *
	 * @param  array $field Field key.
	 * @return string
	 */
	public function get_field_type( $field ) {
		return empty( $field['type'] ) ? 'text' : $field['type'];
	}

	/**
	 * Get a fields default value. Defaults to "" if not set.
	 *
	 * @param  array $field Field key.
	 * @return string
	 */
	public function get_field_default( $field ) {
		return empty( $field['default'] ) ? '' : $field['default'];
	}

	/**
	 * Get a field's posted and validated value.
	 *
	 * @param string $key Field key.
	 * @param array  $field Field array.
	 * @param array  $post_data Posted data.
	 * @return string
	 */
	public function get_field_value( $key, $field, $post_data = array() ) {
		$type      = $this->get_field_type( $field );
		$field_key = $this->get_field_key( $key );
		$post_data = empty( $post_data ) ? $_POST : $post_data; // phpcs:ignore
		$value     = isset( $post_data[ $field_key ] ) ? $post_data[ $field_key ] : null;

		if ( isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ) {
			return call_user_func( $field['sanitize_callback'], $value );
		}

		// Look for a validate_FIELDID_field method for special handling.
		if ( is_callable( array( $this, 'validate_' . $key . '_field' ) ) ) {
			return $this->{'validate_' . $key . '_field'}( $key, $value );
		}

		// Look for a validate_FIELDTYPE_field method.
		if ( is_callable( array( $this, 'validate_' . $type . '_field' ) ) ) {
			return $this->{'validate_' . $type . '_field'}( $key, $value );
		}

		// Fallback to text.
		return $this->validate_text_field( $key, $value );
	}

	/**
	 * Sets the POSTed data. This method can be used to set specific data, instead of taking it from the $_POST array.
	 *
	 * @param array $data Posted data.
	 */
	public function set_post_data( $data = array() ) {
		$this->data = $data;
	}

	/**
	 * Returns the POSTed data, to be used to save the settings.
	 *
	 * @return array
	 */
	public function get_post_data() {
		if ( ! empty( $this->data ) && is_array( $this->data ) ) {
			return $this->data;
		}
		return $_POST; // phpcs:ignore
	}

	/**
	 * Update a single option.
	 *
	 * @since 3.4.0
	 * @param string $key Option key.
	 * @param mixed  $value Value to set.
	 * @return bool was anything saved?
	 */
	public function update_option( $key, $value = '' ) {
		if ( empty( $this->settings ) ) {
			$this->init_settings();
		}

		$this->settings[ $key ] = $value;

		return update_option( $this->get_option_key(), apply_filters( 'woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings ), 'yes' );
	}

	/**
	 * Processes and saves options.
	 * If there is an error thrown, will continue to save and validate fields, but will leave the erroring field out.
	 *
	 * @return bool was anything saved?
	 */
	public function process_admin_options() {
		$this->init_settings();

		$post_data = $this->get_post_data();

		foreach ( $this->get_form_fields() as $key => $field ) {
			if ( 'title' !== $this->get_field_type( $field ) ) {
				try {
					$this->settings[ $key ] = $this->get_field_value( $key, $field, $post_data );
				} catch ( Exception $e ) {
					$this->add_error( $e->getMessage() );
				}
			}
		}

		$option_key = $this->get_option_key();
		do_action( 'woocommerce_update_option', array( 'id' => $option_key ) );
		return update_option( $option_key, apply_filters( 'woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings ), 'yes' );
	}

	/**
	 * Add an error message for display in admin on save.
	 *
	 * @param string $error Error message.
	 */
	public function add_error( $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Get admin error messages.
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Display admin error messages.
	 */
	public function display_errors() {
		if ( $this->get_errors() ) {
			echo '<div id="woocommerce_errors" class="error notice is-dismissible">';
			foreach ( $this->get_errors() as $error ) {
				echo '<p>' . wp_kses_post( $error ) . '</p>';
			}
			echo '</div>';
		}
	}

	/**
	 * Initialise Settings.
	 *
	 * Store all settings in a single database entry
	 * and make sure the $settings array is either the default
	 * or the settings stored in the database.
	 *
	 * @since 1.0.0
	 * @uses get_option(), add_option()
	 */
	public function init_settings() {
		$this->settings = get_option( $this->get_option_key(), null );

		// If there are no settings defined, use defaults.
		if ( ! is_array( $this->settings ) ) {
			$form_fields    = $this->get_form_fields();
			$this->settings = array_merge( array_fill_keys( array_keys( $form_fields ), '' ), wp_list_pluck( $form_fields, 'default' ) );
		}
	}

	/**
	 * Get option from DB.
	 *
	 * Gets an option from the settings API, using defaults if necessary to prevent undefined notices.
	 *
	 * @param  string $key Option key.
	 * @param  mixed  $empty_value Value when empty.
	 * @return string The value specified for the option or a default value for the option.
	 */
	public function get_option( $key, $empty_value = null ) {
		if ( empty( $this->settings ) ) {
			$this->init_settings();
		}

		// Get option default if unset.
		if ( ! isset( $this->settings[ $key ] ) ) {
			$form_fields            = $this->get_form_fields();
			$this->settings[ $key ] = isset( $form_fields[ $key ] ) ? $this->get_field_default( $form_fields[ $key ] ) : '';
		}

		if ( ! is_null( $empty_value ) && '' === $this->settings[ $key ] ) {
			$this->settings[ $key ] = $empty_value;
		}

		return $this->settings[ $key ];
	}

	/**
	 * Prefix key for settings.
	 *
	 * @param  string $key Field key.
	 * @return string
	 */
	public function get_field_key( $key ) {
		return $this->plugin_id . $this->id . '_' . $key;
	}

	/**
	 * Generate Settings HTML.
	 *
	 * Generate the HTML for the fields on the "settings" screen.
	 *
	 * @param array $form_fields (default: array()) Array of form fields.
	 * @param bool  $echo Echo or return.
	 * @return string the html for the settings
	 * @since  1.0.0
	 * @uses   method_exists()
	 */
	public function generate_settings_html( $form_fields = array(), $echo = true ) {
		if ( empty( $form_fields ) ) {
			$form_fields = $this->get_form_fields();
		}

		$html = '';
		foreach ( $form_fields as $k => $v ) {
			$type = $this->get_field_type( $v );

			if ( method_exists( $this, 'generate_' . $type . '_html' ) ) {
				$html .= $this->{'generate_' . $type . '_html'}( $k, $v );
			} elseif ( has_filter( 'woocommerce_generate_' . $type . '_html' ) ) {
				/**
				 * Allow the generation of custom field types on the settings screen.
				 *
				 * The dynamic portion of the hook name refers to the slug of the custom field type.
				 * For instance, to introduce a new field type `fancy_lazy_dropdown` you would use
				 * the hook `woocommerce_generate_fancy_lazy_dropdown_html`.
				 *
				 * @since 6.5.0
				 *
				 * @param string $field_html The markup of the field being generated (initiated as an empty string).
				 * @param string $key The key of the field.
				 * @param array  $data The attributes of the field as an associative array.
				 * @param object $wc_settings The current WC_Settings_API object.
				 */
				$html .= apply_filters( 'woocommerce_generate_' . $type . '_html', '', $k, $v, $this );
			} else {
				$html .= $this->generate_text_html( $k, $v );
			}
		}

		if ( $echo ) {
			echo $html; // phpcs:ignore
		} else {
			return $html;
		}
	}

	/**
	 * Get HTML for tooltips.
	 *
	 * @param  array $data Data for the tooltip.
	 * @return string
	 */
	public function get_tooltip_html( $data ) {
		if ( true === $data['desc_tip'] ) {
			$tip = $data['description'];
		} elseif ( ! empty( $data['desc_tip'] ) ) {
			$tip = $data['desc_tip'];
		} else {
			$tip = '';
		}

		return $tip ? wc_help_tip( $tip, true ) : '';
	}

	/**
	 * Get HTML for descriptions.
	 *
	 * @param  array $data Data for the description.
	 * @return string
	 */
	public function get_description_html( $data ) {
		if ( true === $data['desc_tip'] ) {
			$description = '';
		} elseif ( ! empty( $data['desc_tip'] ) ) {
			$description = $data['description'];
		} elseif ( ! empty( $data['description'] ) ) {
			$description = $data['description'];
		} else {
			$description = '';
		}

		return $description ? '<p class="description">' . wp_kses_post( $description ) . '</p>' . "\n" : '';
	}

	/**
	 * Get custom attributes.
	 *
	 * @param  array $data Field data.
	 * @return string
	 */
	public function get_custom_attribute_html( $data ) {
		$custom_attributes = array();

		if ( ! empty( $data['custom_attributes'] ) && is_array( $data['custom_attributes'] ) ) {
			foreach ( $data['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		return implode( ' ', $custom_attributes );
	}

	/**
	 * Generate Text Input HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_text_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?> />
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generates HTML for the 'safe_text' input type (mostly used for gateway-related settings).
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @return string
	 *
	 * @since 7.6.0
	 */
	public function generate_safe_text_html( $key, $data ) {
		$data['type'] = 'text';
		return $this->generate_text_html( $key, $data );
	}

	/**
	 * Generate Price Input HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_price_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<input class="wc_input_price input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( wc_format_localized_price( $this->get_option( $key ) ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?> />
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Decimal Input HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_decimal_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<input class="wc_input_decimal input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( wc_format_localized_decimal( $this->get_option( $key ) ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?> />
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Password Input HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_password_html( $key, $data ) {
		$data['type'] = 'password';
		return $this->generate_text_html( $key, $data );
	}

	/**
	 * Generate Color Picker Input HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_color_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<span class="colorpickpreview" style="background:<?php echo esc_attr( $this->get_option( $key ) ); ?>;">&nbsp;</span>
					<input class="colorpick <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?> />
					<div id="colorPickerDiv_<?php echo esc_attr( $field_key ); ?>" class="colorpickdiv" style="z-index: 100; background: #eee; border: 1px solid #ccc; position: absolute; display: none;"></div>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Textarea HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_textarea_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<textarea rows="3" cols="20" class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?>><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Checkbox HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_checkbox_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'label'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data = wp_parse_args( $data, $defaults );

		if ( ! $data['label'] ) {
			$data['label'] = $data['title'];
		}

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<label for="<?php echo esc_attr( $field_key ); ?>">
					<input <?php disabled( $data['disabled'], true ); ?> class="<?php echo esc_attr( $data['class'] ); ?>" type="checkbox" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="1" <?php checked( $this->get_option( $key ), 'yes' ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?> /> <?php echo wp_kses_post( $data['label'] ); ?></label><br/>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Select HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_select_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'options'           => array(),
		);

		$data  = wp_parse_args( $data, $defaults );
		$value = $this->get_option( $key );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<select class="select <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?>>
						<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
							<?php if ( is_array( $option_value ) ) : ?>
								<optgroup label="<?php echo esc_attr( $option_key ); ?>">
									<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
										<option value="<?php echo esc_attr( $option_key_inner ); ?>" <?php selected( (string) $option_key_inner, esc_attr( $value ) ); ?>><?php echo esc_html( $option_value_inner ); ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php else : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( (string) $option_key, esc_attr( $value ) ); ?>><?php echo esc_html( $option_value ); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Multiselect HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_multiselect_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'select_buttons'    => false,
			'options'           => array(),
		);

		$data  = wp_parse_args( $data, $defaults );
		$value = (array) $this->get_option( $key, array() );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<select multiple="multiple" class="multiselect <?php echo esc_attr( $data['class'] ); ?>" name="<?php echo esc_attr( $field_key ); ?>[]" id="<?php echo esc_attr( $field_key ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?>>
						<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
							<?php if ( is_array( $option_value ) ) : ?>
								<optgroup label="<?php echo esc_attr( $option_key ); ?>">
									<?php foreach ( $option_value as $option_key_inner => $option_value_inner ) : ?>
										<option value="<?php echo esc_attr( $option_key_inner ); ?>" <?php selected( in_array( (string) $option_key_inner, $value, true ), true ); ?>><?php echo esc_html( $option_value_inner ); ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php else : ?>
								<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( in_array( (string) $option_key, $value, true ), true ); ?>><?php echo esc_html( $option_value ); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
					<?php if ( $data['select_buttons'] ) : ?>
						<br/><a class="select_all button" href="#"><?php esc_html_e( 'Select all', 'peachpay-for-woocommerce' ); ?></a> <a class="select_none button" href="#"><?php esc_html_e( 'Select none', 'peachpay-for-woocommerce' ); ?></a>
					<?php endif; ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Title HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_title_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title' => '',
			'class' => '',
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
			</table>
			<h3 class="wc-settings-sub-title <?php echo esc_attr( $data['class'] ); ?>" id="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></h3>
			<?php if ( ! empty( $data['description'] ) ) : ?>
				<p><?php echo wp_kses_post( $data['description'] ); ?></p>
			<?php endif; ?>
			<table class="form-table">
		<?php

		return ob_get_clean();
	}

	/**
	 * Validate Text Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string
	 */
	public function validate_text_field( $key, $value ) {
		$value = is_null( $value ) ? '' : $value;
		return wp_kses_post( trim( stripslashes( $value ) ) );
	}

	/**
	 * Sanitize 'Safe Text' fields.
	 *
	 * These fields are similar to regular text fields, but a much  smaller set of HTML tags are allowed. By default,
	 * this means `<br>`, `<img>`, `<p>` and `<span>` tags.
	 *
	 * Note: this is a sanitization method, rather than a validation method (the name is due to some historic naming
	 * choices).
	 *
	 * @param  string $key   Field key (currently unused).
	 * @param  string $value Posted Value.
	 *
	 * @return string
	 */
	public function validate_safe_text_field( $key, $value ) {
		return wc_get_container()->get( PeachPay_HtmlSanitizer::class )->sanitize( (string) $value, PeachPay_HtmlSanitizer::LOW_HTML_BALANCED_TAGS_NO_LINKS );
	}

	/**
	 * Validate Price Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string
	 */
	public function validate_price_field( $key, $value ) {
		$value = is_null( $value ) ? '' : $value;
		return ( '' === $value ) ? '' : wc_format_decimal( trim( stripslashes( $value ) ) );
	}

	/**
	 * Validate Decimal Field.
	 *
	 * Make sure the data is escaped correctly, etc.
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string
	 */
	public function validate_decimal_field( $key, $value ) {
		$value = is_null( $value ) ? '' : $value;
		return ( '' === $value ) ? '' : wc_format_decimal( trim( stripslashes( $value ) ) );
	}

	/**
	 * Validate Password Field. No input sanitization is used to avoid corrupting passwords.
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string
	 */
	public function validate_password_field( $key, $value ) {
		$value = is_null( $value ) ? '' : $value;
		return trim( stripslashes( $value ) );
	}

	/**
	 * Validate Textarea Field.
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string
	 */
	public function validate_textarea_field( $key, $value ) {
		$value = is_null( $value ) ? '' : $value;
		return wp_kses(
			trim( stripslashes( $value ) ),
			array_merge(
				array(
					'iframe' => array(
						'src'   => true,
						'style' => true,
						'id'    => true,
						'class' => true,
					),
				),
				wp_kses_allowed_html( 'post' )
			)
		);
	}

	/**
	 * Validate Checkbox Field.
	 *
	 * If not set, return "no", otherwise return "yes".
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string
	 */
	public function validate_checkbox_field( $key, $value ) {
		return ! is_null( $value ) ? 'yes' : 'no';
	}

	/**
	 * Validate Select Field.
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string
	 */
	public function validate_select_field( $key, $value ) {
		$value = is_null( $value ) ? '' : $value;
		return wc_clean( stripslashes( $value ) );
	}

	/**
	 * Validate Multiselect Field.
	 *
	 * @param  string $key Field key.
	 * @param  string $value Posted Value.
	 * @return string|array
	 */
	public function validate_multiselect_field( $key, $value ) {
		return is_array( $value ) ? array_map( 'wc_clean', array_map( 'stripslashes', $value ) ) : '';
	}
}

/**
 * Utility for re-using WP Kses-based sanitization rules.
 *
 * Code copied from WooCommerce core due to being required before WooCommerce is garunteed to be loaded.
 */
class PeachPay_HtmlSanitizer { // PHPCS:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound, Generic.Classes.OpeningBraceSameLine.ContentAfterBrace
	/**
	 * Rules for allowing minimal HTML (breaks, images, paragraphs and spans) without any links.
	 */
	const LOW_HTML_BALANCED_TAGS_NO_LINKS = array(
		'pre_processors' => array(
			'stripslashes',
			'force_balance_tags',
		),
		'wp_kses_rules'  => array(
			'br'   => true,
			'img'  => array(
				'alt'   => true,
				'class' => true,
				'src'   => true,
				'title' => true,
			),
			'p'    => array(
				'class' => true,
			),
			'span' => array(
				'class' => true,
				'title' => true,
			),
		),
	);

	/**
	 * Sanitizes the HTML according to the provided rules.
	 *
	 * @see wp_kses()
	 *
	 * @param string $html HTML string to be sanitized.
	 * @param array  $sanitizer_rules {
	 *     Optional and defaults to self::TRIMMED_BALANCED_LOW_HTML_NO_LINKS. Otherwise, one or more of the following
	 *     keys should be set.
	 *
	 *     @type array $pre_processors  Callbacks to run before invoking `wp_kses()`.
	 *     @type array $wp_kses_rules   Element names and attributes to allow, per `wp_kses()`.
	 * }
	 *
	 * @return string
	 */
	public function sanitize( string $html, array $sanitizer_rules = self::LOW_HTML_BALANCED_TAGS_NO_LINKS ): string {
		if ( isset( $sanitizer_rules['pre_processors'] ) && is_array( $sanitizer_rules['pre_processors'] ) ) {
			$html = $this->apply_string_callbacks( $sanitizer_rules['pre_processors'], $html );
		}

		// If no KSES rules are specified, assume all HTML should be stripped.
		$kses_rules = isset( $sanitizer_rules['wp_kses_rules'] ) && is_array( $sanitizer_rules['wp_kses_rules'] )
			? $sanitizer_rules['wp_kses_rules']
			: array();

		return wp_kses( $html, $kses_rules );
	}

	/**
	 * Applies callbacks used to process the string before and after wp_kses().
	 *
	 * If a callback is invalid we will short-circuit and return an empty string, on the grounds that it is better to
	 * output nothing than risky HTML. We also call the problem out via _doing_it_wrong() to highlight the problem (and
	 * increase the chances of this being caught during development).
	 *
	 * @param callable[] $callbacks The callbacks used to mutate the string.
	 * @param string     $string    The string being processed.
	 *
	 * @return string
	 */
	private function apply_string_callbacks( array $callbacks, string $string ): string {
		foreach ( $callbacks as $callback ) {
			if ( ! is_callable( $callback ) ) {
				_doing_it_wrong( __CLASS__ . '::apply', esc_html__( 'String processors must be an array of valid callbacks.', 'peachpay-for-woocommerce' ), esc_html( WC()->version ) );
				return '';
			}

			$string = (string) $callback( $string );
		}

		return $string;
	}
}
