<?php
/**
 * Class Portfolio Settings Page
 *
 * @since   1.0.5
 * @package WPZOOM_Forms
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for settings page.
 */
class WPZOOM_Forms_Settings {
	/**
	 * Option name
	 */
	public static $option = 'wpzf-settings';

	/**
	 * Store all default settings options.
	 *
	 * @static
	 */
	public static $defaults = array();

	/**
	 * Store all settings options.
	 *
	 * @static
	 */
	public static $settings = array();

	/**
	 * Active Tab.
	 */
	public static $active_tab;

	/**
	 * Class WPZOOM_Forms_Settings_Fields instance.
	 */
	public $_fields;

	/**
	 * Store Settings options.
	 */
	public static $options = array();

	/**
	 * License key
	 */
	public static $license_key = false;

	/**
	 * License status
	 */
	public static $license_status = false;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		global $pagenow;

		self::$options = get_option( self::$option );

		// Check what page we are on.
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_action( 'admin_init', array( $this, 'set_defaults' ) );

			// Include admin scripts & styles
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

			// Do ajax request
			add_action( 'wp_ajax_wpzoom_reset_settings', array( $this, 'reset_settings' ) );

			// Only load if we are actually on the settings page.
			if ( WPZOOM_FORMS_SETTINGS_PAGE === $page ) {
				add_action( 'wpzoom_forms_admin_page', array( $this, 'settings_page' ) );
			}

			$this->_fields = new WPZOOM_Forms_Settings_Fields();
		}
	}

	/**
	 * Set default values for setting options.
	 */
	public function set_defaults() {
		// Set active tab
		self::$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'tab-general';

		self::$defaults = self::get_defaults();

		if ( empty( self::$defaults ) ) {
			return false;
		}

		// If 'wpzoom-forms-settings' is empty update option with defaults values
		if ( empty( self::$options ) ) {
			self::update_option( self::$defaults );
		}

		// If new setting is added, update 'wpzoom-forms-settings' option
		if ( ! empty( self::$options ) ) {
			$new_settings = array_diff_key( self::$defaults, self::$options );
			if ( ! empty( $new_settings ) ) {
				self::update_option( array_merge( self::$options, $new_settings ) );
			}
		}

		return apply_filters( 'wpzoom_forms_set_settings_defaults', self::$defaults );
	}

	/**
	 * Update option value
	 *
	 * @param string|array $value
	 * @param string       $option
	 */
	public static function update_option( $value, $option = '', $autoload = null ) {
		if ( empty( $option ) ) {
			$option = self::$option;
		}

		if ( self::$options !== false ) {
			// The option already exists, so we just update it.
			update_option( $option, $value, $autoload );
		} else {
			// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
			$deprecated = null;
			$autoload   = 'no';
			add_option( $option, $value, $deprecated, $autoload );
		}
	}

	/**
	 * Get default values of setting options.
	 *
	 * @static
	 */
	public static function get_defaults() {
		$defaults = array();

		foreach ( self::$settings as $key => $setting ) {
			if ( isset( $setting['sections'] ) && is_array( $setting['sections'] ) ) {
				foreach ( $setting['sections'] as $section ) {
					if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							if ( isset( $field['args']['default'] ) ) {
								$defaults[ $field['id'] ] = (string) $field['args']['default'];
							}
						}
					}
				}
			}
		}

		return $defaults;
	}

	/**
	 * Get default value by option name
	 *
	 * @param string $option_name
	 * @static
	 * @return boolean
	 */
	public static function get_default_option_value( $option_name ) {
		return isset( self::$defaults[ $option_name ] ) ? self::$defaults[ $option_name ] : false;
	}

	/**
	 * Get license key
	 *
	 * @since 1.2.0
	 * @return string The License key
	 */
	public static function get_license_key() {
		return self::$license_key;
	}

	/**
	 * Get license status
	 *
	 * @since 1.2.0
	 * @return string The License status
	 */
	public static function get_license_status() {
		return self::$license_status;
	}

	/**
	 * Get setting options
	 *
	 * @since 1.2.0
	 * @return array
	 */
	public static function get_settings() {
		return apply_filters( 'wpzoom_forms_get_settings', self::$options );
	}

	/**
	 * Get setting option value
	 *
	 * @since 1.2.0
	 * @param string $option  Option name
	 * @return string|boolean
	 */
	public static function get( $option ) {
		return isset( self::$options[ $option ] ) ? self::$options[ $option ] : false;
	}

	/**
	 * Initilize all settings
	 */
	public function settings_init() {
		$premium_badge = '<span class="wpzoom-forms-badge wpzoom-forms-field-is_premium">' . __( 'Premium', 'wpzoom-forms' ) . '</span>';
		$soon_badge    = '<span class="wpzoom-forms-badge wpzoom-forms-field-is_coming_soon">' . __( 'Coming Soon', 'wpzoom-forms' ) . '</span>';

		self::$settings = array(
			'general'     => array(
				'tab_id'       => 'tab-general',
				'tab_title'    => __( 'General', 'wpzoom-forms' ),
				'option_group' => 'wpzoom-forms-settings-general',
				'option_name'  => self::$option,
				'sections'     => array(
					array(
						'id'       => 'wpzoom_section_general',
						'title'    => __( 'Styling', 'wpzoom-forms' ),
						'page'     => 'wpzoom-forms-settings-general',
						'callback' => array( $this, 'section_general_cb' ),
						'fields'   => array(
							array(
								'id'    => 'wpzf_use_theme_styles',
								'title' => esc_html__( 'Load default styling for forms', 'wpzoom-forms' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzf_use_theme_styles',
									'class'       => 'wpzoom-forms-field',
									'description' => esc_html__( 'Uncheck this option if you want your current theme to handle the styling for forms.', 'wpzoom-forms' ),
									'default'     => true,
								),
							),
							array(
								'id'    => 'wpzf_global_assets_load',
								'title' => esc_html__( 'Load plugin assets globally', 'wpzoom-forms' ),
								'type'  => 'checkbox',
								'args'  => array(
									'label_for'   => 'wpzf_global_assets_load',
									'class'       => 'wpzoom-forms-field',
									'description' => esc_html__( 'If you want to embed a form using shortcodes in a page builder, enable this option to ensure all the needed assets are loaded.', 'wpzoom-forms' ),
									'default'     => true,
									
								),
							),
						),
					),
					array(
						'id'       => 'wpzoom_section_recaptcha',
						'title'    => __( 'CAPTCHA', 'wpzoom-forms' ),
						'page'     => 'wpzoom-forms-settings-general',
						'callback' => array( $this, 'section_recaptcha_cb' ),
						'fields'   => array(
							array(
								'id'    => 'wpzf_global_captcha_service',
								'title' => esc_html__( 'reCAPTCHA', 'wpzoom-forms' ),
								'type'  => 'radio',
								'args'  => array(
									'label_for'   => 'wpzf_global_captcha_service',
									'class'       => 'wpzoom-forms-field',
									'description' => '',
									'default'     => 'none',
									'options'     => array(
										'none'      => esc_html__( 'None', 'wpzoom-forms' ),
										'recaptcha' => esc_html__( 'reCAPTCHA', 'wpzoom-forms' )
									)
								),
							),
							array(
								'id'    => 'wpzf_global_captcha_type',
								'title' => esc_html__( 'Type', 'wpzoom-forms' ),
								'type'  => 'radio',
								'args'  => array(
									'label_for'   => 'wpzf_global_captcha_type',
									'class'       => 'wpzoom-forms-field required-recaptcha',
									'description' => '',
									'default'     => 'v2',
									'options'     => array(
										'v2'      => esc_html__( 'Invisible reCAPTCHA v2', 'wpzoom-forms' ),
										'v3'      => esc_html__( 'reCAPTCHA v3', 'wpzoom-forms' )
									)
								),
							),
							array(
								'id'    => 'wpzf_global_captcha_site_key',
								'title' => __( 'Site Key', 'wpzoom-forms' ),
								'type'  => 'input',
								'args'  => array(
									'label_for'   => 'wpzf_global_captcha_site_key',
									'class'       => 'wpzoom-forms-field required-recaptcha',
									'default'     => '',
									'description' => '',
									'type'        => 'text',
								),
							),
							array(
								'id'    => 'wpzf_global_captcha_secret_key',
								'title' => __( 'Secret Key', 'wpzoom-forms' ),
								'type'  => 'input',
								'args'  => array(
									'label_for'   => 'wpzf_global_captcha_secret_key',
									'class'       => 'wpzoom-forms-field required-recaptcha',
									'default'     => '',
									'description' => '',
									'type'        => 'text',
								),
							),
						),
					),
				),
			),
		);

		$this->register_settings();
	}

	/**
	 * Register all Setting options
	 *
	 * @since 1.1.0
	 * @return boolean
	 */
	public function register_settings() {
		// filter hook
		self::$settings = apply_filters( 'wpzoom_forms_before_register_settings', self::$settings );

		if ( empty( self::$settings ) ) {
			return;
		}

		foreach ( self::$settings as $key => $setting ) {
			$this->register_setting( $setting );
		}

		return true;
	}

	/**
	 * Register Setting
	 *
	 * @since 2.3.0
	 * @param array $setting
	 * @return void
	 */
	public function register_setting( $setting ) {
		$setting['sanitize_callback'] = isset( $setting['sanitize_callback'] ) ? $setting['sanitize_callback'] : array();
		register_setting( $setting['option_group'], $setting['option_name'], $setting['sanitize_callback'] );

		if ( isset( $setting['sections'] ) && is_array( $setting['sections'] ) ) {
			foreach ( $setting['sections'] as $section ) {
				if ( ! isset( $section['id'] ) ) {
					return;
				}
				add_settings_section( $section['id'], $section['title'], $section['callback'], $section['page'] );

				if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						if ( ! isset( $field['id'] ) ) {
							return;
						}

						if ( method_exists( $this->_fields, $field['type'] ) ) {
							$field['callback'] = array( $this->_fields, $field['type'] );
						} else {
							$field['callback'] = '__return_false';
						}

						add_settings_field( $field['id'], $field['title'], $field['callback'], $section['page'], $section['id'], $field['args'] );
					}
				}
			}
		}
	}

	/**
	 * HTML output for Setting page
	 */
	public function settings_page() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$reset_settings   = isset( $_GET['wpzoom_reset_settings'] ) ? sanitize_text_field( $_GET['wpzoom_reset_settings'] ) : false;
		$settings_updated = isset( $_GET['settings-updated'] ) ? sanitize_text_field( $_GET['settings-updated'] ) : false;
		?>
		<div class="wrap">

			<?php settings_errors(); ?>

			<?php if ( $reset_settings && ! $settings_updated ) : ?>
				<div class="updated settings-error notice is-dismissible">
					<p><strong>Settings have been successfully reset.</strong></p>
				</div>
			<?php endif; ?>

			<form id="wpzf-settings" action="options.php" method="post">
				<ul class="wp-tab-bar">
					<?php foreach ( self::$settings as $setting ) : ?>
						<?php if ( self::$active_tab === $setting['tab_id'] ) : ?>
							<li class="wp-tab-active"><a href="?post_type=wpzf-form&page=wpzf-settings&tab=<?php echo esc_attr( $setting['tab_id'] ); ?>"><?php echo esc_html( $setting['tab_title'] ); ?></a></li>
						<?php else : ?>
							<li><a href="?post_type=wpzf-form&page=wpzf-settings&tab=<?php echo esc_attr( $setting['tab_id'] ); ?>"><?php echo esc_html( $setting['tab_title'] ); ?></a></li>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
				<?php foreach ( self::$settings as $setting ) : ?>
					<?php if ( self::$active_tab === $setting['tab_id'] ) : ?>
						<div class="wp-tab-panel" id="<?php echo esc_attr( $setting['tab_id'] ); ?>">
							<?php
								settings_fields( $setting['option_group'] );
								do_settings_sections( $setting['option_group'] );
							?>
						</div>
					<?php else : ?>
						<div class="wp-tab-panel" id="<?php echo esc_attr( $setting['tab_id'] ); ?>" style="display: none;">
							<?php
								settings_fields( $setting['option_group'] );
								do_settings_sections( $setting['option_group'] );
							?>
						</div>
					<?php endif ?>
				<?php endforeach ?>
				<span class="wpzoom_forms_settings_save"><?php submit_button( 'Save Settings', 'primary', 'wpzoom_forms_settings_save', false ); ?></span>
				<span class="wpzoom_forms_reset_settings"><input type="button" class="button button-secondary" name="wpzoom_forms_reset_settings" id="wpzoom_forms_reset_settings" value="Reset Settings"></span>

			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @param string $hook
	 */
	public function scripts( $hook ) {

		$pos = strpos( $hook, WPZOOM_FORMS_SETTINGS_PAGE );

		wp_enqueue_style(
			'wpzoom-forms-admin-css',
			untrailingslashit( WPZOOM_FORMS_URL ) . '/dist/assets/admin/css/admin.css',
			array(),
			WPZOOM_FORMS_VERSION
		);

		if ( $pos !== false ) {
			// Add the color picker css file
			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_style(
				'wpzoom-forms-admin-style',
				untrailingslashit( WPZOOM_FORMS_URL ) . '/dist/assets/admin/css/style.css',
				array(),
				WPZOOM_FORMS_VERSION
			);

			wp_enqueue_script(
				'wpzoom-forms-admin-script',
				untrailingslashit( WPZOOM_FORMS_URL ) . '/dist/assets/admin/js/script.js',
				array( 'jquery', 'wp-color-picker' ),
				WPZOOM_FORMS_VERSION
			);

			wp_localize_script(
				'wpzoom-forms-admin-script',
				'WPZOOM_Settings',
				array(
					'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'wpzoom-reset-settings-nonce' ),
				)
			);
		}
	}

	/**
	 * Reset settings to default values
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function reset_settings() {
		check_ajax_referer( 'wpzoom-reset-settings-nonce', 'security' );

		$defaults = self::get_defaults();

		if ( empty( $defaults ) ) {
			$response = array(
				'status'  => '304',
				'message' => 'NOT',
			);

			wp_send_json_error( $response );
		}

		$response = array(
			'status'  => '200',
			'message' => 'OK',
		);

		self::update_option( $defaults );

		wp_send_json_success( $response );
	}

	public function get_image_sizes() {

		global $_wp_additional_image_sizes;

		$sizes = array();
		$sizes['full'] = 'Full';

		$wp_image_sizes = get_intermediate_image_sizes();

		foreach( $wp_image_sizes as $size ) {
			$sizes[$size] = $size;
		}

		return $sizes;

	}

	// section callbacks can accept an $args parameter, which is an array.
	// $args have the following keys defined: title, id, callback.
	// the values are defined at the add_settings_section() function.
	public function section_general_cb( $args ) {
		?>
		 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'General configuration for WPZOOM Forms', 'wpzoom-forms' ); ?></p>
		<?php
	}
	public function section_recaptcha_cb( $args ) {
		?>
		 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo wp_kses_post( __( 'reCAPTCHA is a popular tool used to prevent spam and automated bots from accessing websites. <a target="_blank" href="https://www.google.com/recaptcha/admin/create">Click here</a> to generate your reCAPTCHA keys and enter them below', 'wpzoom-forms' ) ); ?></p>
		<?php
	}
}

new WPZOOM_Forms_Settings();