<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0.9
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/admin
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 * @since    1.0.0
	 * @access   private
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

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fathom_Analytics_Conversions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fathom_Analytics_Conversions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fathom-analytics-conversions-admin.css', [], $this->version );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fathom_Analytics_Conversions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fathom_Analytics_Conversions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fathom-analytics-conversions-admin.js', [ 'jquery' ], '1.0.7', FALSE );

	}

	// admin output section
	public function fac4wp_admin_output_section( $args ) {
		echo '<span class="tabinfo">';

		switch ( $args['id'] ) {

			case FAC4WP_ADMIN_GROUP_INTEGRATION:
			{
				//_e( 'Fathom Analytics Conversions can integrate with several popular plugins. Please check the plugins you would like to integrate with:', 'fathom-analytics-conversions' );

				break;
			}
		}

		echo '</span>';
	}

	// admin output field
	public function fac4wp_admin_output_field( $args ) {
		global $fac4wp_options;
		$_site_id     = $fac4wp_options[ FAC_OPTION_SITE_ID ];
		$installed_tc = $fac4wp_options[ FAC_OPTION_INSTALLED_TC ];

		switch ( $args['label_for'] ) {
			case FAC4WP_ADMIN_GROUP_API_KEY:
			{
				$_api_key        = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
				$_input_readonly = '';

				echo '<input type="text" id="' . esc_attr( FAC4WP_OPTIONS . '[' . FAC4WP_OPTION_API_KEY_CODE . ']' ) . '" name="' . esc_attr( FAC4WP_OPTIONS . '[' . FAC4WP_OPTION_API_KEY_CODE . ']' ) . '" value="' . esc_attr( $_api_key ) . '" ' . esc_html( $_input_readonly ) . ' class="regular-text" />';
				$result = fac_api_key();
				//echo '<pre>';print_r( $fac4wp_options );echo '</pre>';
				if ( isset( $result['code'] ) && $result['code'] === 200 ) {
					$body = isset( $result['body'] ) ? json_decode( $result['body'], TRUE ) : [];
					//echo '<pre>';print_r($body);echo '</pre>';
					$r_site_id = isset( $body['id'] ) ? $body['id'] : '';
					/*$r_site_name = isset( $body['name'] ) ? $body['name'] : '';
					$site_name   = get_site_url();
					$site_name   = preg_replace( '#^https?://#i', '', $site_name );
					$no_w_s_name = ltrim( $site_name, 'www.' );*/
					//if ( $_site_id !== $r_site_id || ( $r_site_name !== $site_name && $r_site_name !== $no_w_s_name ) ) {
					if ( $_site_id !== $r_site_id ) {
						$result['error'] = __( 'ERROR: The API Key you have entered does not have access to this site.', 'fathom-analytics-conversions' );
					} else {
						echo '<span class="fac_connected">';
						echo esc_html( __( 'Connected', 'fathom-analytics-conversions' ) );
						echo '</span>';
					}
				}
				echo '<br>';
				echo wp_kses( $args['description'],
					[
						'a' => [
							'href'   => TRUE,
							'target' => TRUE,
							'rel'    => TRUE,
						],
					] );

				//if(get_current_user_id() === 2) {
				if ( isset( $result['error'] ) && ! empty( $result['error'] ) ) {
					echo '<p class="fac_error">' . esc_html( $result['error'] ) . '</p>';
				} else {
					// check forms.
					fac_check_cf7_forms();
					fac_check_wpforms_forms();
					fac_check_gf_forms();
					fac_check_ff_forms();
					fac_check_nj_forms();
				}
				//}

				break;
			}

			case FAC4WP_ADMIN_GROUP_SITE_ID:
			{
				if ( empty( $installed_tc ) ) {
					$_input_readonly = ' readonly="readonly"';
				} else {
					$_input_readonly = '';
				}

				echo '<input type="text" id="' . esc_attr( FAC4WP_OPTIONS . '_' . FAC_OPTION_SITE_ID ) . '" name="' . esc_attr( FAC4WP_OPTIONS . '[' . FAC_OPTION_SITE_ID . ']' ) . '" value="' . esc_attr( $_site_id ) . '" ' . esc_html( $_input_readonly ) . ' class="regular-text" />';

				echo '<label for="' . esc_attr( FAC4WP_OPTIONS . '_' . FAC_OPTION_INSTALLED_TC ) . '" class="installed_tc_elsewhere">';
				echo '<input type="checkbox" id="' . esc_attr( FAC4WP_OPTIONS . '_' . FAC_OPTION_INSTALLED_TC ) . '" name="' . esc_attr( FAC4WP_OPTIONS . '[' . FAC_OPTION_INSTALLED_TC . ']' ) . '"  value="1"  ' . checked( 1, $installed_tc, FALSE ) . ' >';
				echo '<span>';
				echo esc_html( __( 'I installed my tracking code elsewhere.', 'fathom-analytics-conversions' ) );
				echo '</span></label>';

				echo '<br />' . esc_html( $args['description'] );
				if ( empty( $_site_id ) ) {
					echo '<p class="fac_error">' .
					     sprintf(
						     wp_kses(
							     __( 'Please enter site ID on <a href="%s" target="_blank" rel="noopener">Fathom Analytics settings page</a>.', 'fathom-analytics-conversions' ),
							     [
								     'a' => [
									     'href'   => TRUE,
									     'target' => TRUE,
									     'rel'    => TRUE,
								     ],
							     ]
						     ),
						     '?page=fathom-analytics'
					     )
					     . '</p>';
				}

				break;
			}

			default:
			{
				$opt_val = $fac4wp_options[ $args['option_field_id'] ];

				switch ( gettype( $opt_val ) ) {
					case 'boolean':
					{
						echo '<input type="checkbox" id="' . esc_attr( FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']' ) . '" name="' . esc_attr( FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']' ) . '" value="1" ' . checked( 1, $opt_val, FALSE ) . ' /><br />' . esc_html( $args['description'] );

						if ( isset( $args['plugin_to_check'] ) && ( $args['plugin_to_check'] != '' ) ) {
							$is_plugin_active = 0;
							if ( is_array( $args['plugin_to_check'] ) ) {
								foreach ( $args['plugin_to_check'] as $plugin ) {
									if ( is_plugin_active( $plugin ) ) {
										$is_plugin_active = 1;
									}
								}
							} elseif ( is_plugin_active( $args['plugin_to_check'] ) ) {
								$is_plugin_active = 1;
							}
							if ( $is_plugin_active ) {
								echo '<br />';
								echo wp_kses(
									__( 'This plugin is <strong class="fac4wp-plugin-active">active</strong>, it is strongly recommended to enable this integration!', 'fathom-analytics-conversions' ),
									[
										'strong' => [
											'class' => TRUE,
										],
									]
								);
							} else {
								echo '<br />';
								echo sprintf(
									wp_kses(
										__( 'This plugin (%s) is <strong class="fac4wp-plugin-not-active">not active</strong>, enabling this integration could cause issues on frontend!', 'fathom-analytics-conversions' ),
										[
											'strong' => [
												'class' => TRUE,
											],
										]
									),
									is_array( $args['plugin_to_check'] ) ? implode( ' or ', $args['plugin_to_check'] ) : $args['plugin_to_check']
								);
							}
						}

						break;
					}

					default:
					{
						echo '<input type="text" id="' . esc_attr( FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']' ) . '" name="' . esc_attr( FAC4WP_OPTIONS . '[' . $args['option_field_id'] . ']' ) . '" value="' . esc_attr( $opt_val ) . '" size="80" /><br />' . esc_html( $args['description'] );

						if ( isset( $args['plugin_to_check'] ) && ( $args['plugin_to_check'] != '' ) ) {
							if ( is_plugin_active( $args['plugin_to_check'] ) ) {
								echo '<br />';
								echo wp_kses(
									__( 'This plugin is <strong class="fac4wp-plugin-active">active</strong>, it is strongly recommended to enable this integration!', 'fathom-analytics-conversions' ),
									[
										'strong' => [
											'class' => TRUE,
										],
									]
								);
							} else {
								echo '<br />';
								echo wp_kses(
									__( 'This plugin is <strong class="fac4wp-plugin-not-active">not active</strong>, enabling this integration could cause issues on frontend!', 'fathom-analytics-conversions' ),
									[
										'strong' => [
											'class' => TRUE,
										],
									]
								);
							}
						}
					}
				}
			}
		}
	}

	public function fac4wp_sanitize_options( $options ) {
		$output = fac4wp_reload_options();

		foreach ( $output as $optionname => $optionvalue ) {
			if ( isset( $options[ $optionname ] ) ) {
				$newoptionvalue = $options[ $optionname ];
			} else {
				$newoptionvalue = '';
			}

			// site ID
			if ( $optionname === FAC_OPTION_SITE_ID ) {

				if ( empty( $output[ FAC_OPTION_INSTALLED_TC ] ) ) {
					unset( $output[ $optionname ] );
				} else {
					$output[ $optionname ] = $newoptionvalue;
				}
			} elseif ( substr( $optionname, 0, 10 ) == 'integrate-' ) {
				$output[ $optionname ] = (bool) $newoptionvalue;

				// anything else
			} else {
				switch ( gettype( $optionvalue ) ) {
					case 'boolean':
					{
						$output[ $optionname ] = (bool) $newoptionvalue;

						break;
					}

					case 'integer':
					{
						$output[ $optionname ] = (int) $newoptionvalue;

						break;
					}

					default:
					{
						$output[ $optionname ] = $newoptionvalue;
					}
				}
			}

		}

		return $output;
	}

	// admin settings sections and fields
	public function fac4wp_admin_init() {
		$GLOBALS['fac4wp_integrate_field_texts'] = [
			FAC4WP_OPTION_INTEGRATE_WPCF7        => [
				'label'           => __( 'Contact Form 7', 'fathom-analytics-conversions' ),
				'description'     => __( 'Check this to add a conversion from a successful form submission.', 'fathom-analytics-conversions' ),
				'phase'           => FAC4WP_PHASE_STABLE,
				'plugin_to_check' => 'contact-form-7/wp-contact-form-7.php',
			],
			FAC4WP_OPTION_INTEGRATE_WPFORMS      => [
				'label'           => __( 'WPForms', 'fathom-analytics-conversions' ),
				'description'     => __( 'Check this to add a conversion from a successful form submission.', 'fathom-analytics-conversions' ),
				'phase'           => FAC4WP_PHASE_STABLE,
				'plugin_to_check' => [
					'wpforms/wpforms.php',
					'wpforms-lite/wpforms.php',
				],
			],
			FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS => [
				'label'           => __( 'Gravity Forms', 'fathom-analytics-conversions' ),
				'description'     => __( 'Check this to add a conversion from a successful form submission. NOTE: Only works with forms set to AJAX submissions.', 'fathom-analytics-conversions' ),
				'phase'           => FAC4WP_PHASE_STABLE,
				'plugin_to_check' => 'gravityforms/gravityforms.php',
			],
			FAC4WP_OPTION_INTEGRATE_FLUENTFORMS  => [
				'label'           => __( 'Fluent Form', 'fathom-analytics-conversions' ),
				'description'     => __( 'Check this to add a conversion from a successful form submission.', 'fathom-analytics-conversions' ),
				'phase'           => FAC4WP_PHASE_STABLE,
				'plugin_to_check' => [
					'fluentform/fluentform.php',
					'fluentformpro/fluentformpro.php',
				],
			],
			FAC4WP_OPTION_INTEGRATE_NINJAFORMS   => [
				'label'           => __( 'Ninja Forms', 'fathom-analytics-conversions' ),
				'description'     => __( 'Check this to add a conversion from a successful form submission.', 'fathom-analytics-conversions' ),
				'phase'           => FAC4WP_PHASE_STABLE,
				'plugin_to_check' => 'ninja-forms/ninja-forms.php',
			],
			FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE  => [
				'label'           => __( 'Woocommerce', 'fathom-analytics-conversions' ),
				'description'     => __( 'Check this to add a conversion from a successful order. NOTE: Fires on the order complete page.', 'fathom-analytics-conversions' ),
				'phase'           => FAC4WP_PHASE_STABLE,
				'plugin_to_check' => 'woocommerce/woocommerce.php',
			],

			'integrate-wp-login'         => [
				'label'       => __( 'Login', 'fathom-analytics-conversions' ),
				'description' => __( 'Check this to add a conversion from a login to your site.', 'fathom-analytics-conversions' ),
				'phase'       => FAC4WP_PHASE_STABLE,
				//'plugin_to_check' => 'woocommerce/woocommerce.php',
			],
			'integrate-wp-registration'  => [
				'label'       => __( 'Registration', 'fathom-analytics-conversions' ),
				'description' => __( 'Check this to add a conversion from a registration on your site.', 'fathom-analytics-conversions' ),
				'phase'       => FAC4WP_PHASE_STABLE,
				//'plugin_to_check' => 'woocommerce/woocommerce.php',
			],
			'integrate-wp-lost-password' => [
				'label'       => __( 'Lost password', 'fathom-analytics-conversions' ),
				'description' => __( 'Check this to add a conversion from a lost password on your site.', 'fathom-analytics-conversions' ),
				'phase'       => FAC4WP_PHASE_STABLE,
				//'plugin_to_check' => 'woocommerce/woocommerce.php',
			],
		];
		global $fac4wp_integrate_field_texts;

		register_setting( FAC4WP_ADMIN_GROUP, FAC4WP_OPTIONS, [
				'sanitize_callback' => [ $this, 'fac4wp_sanitize_options' ],
			]
		);

		add_settings_section(
			FAC4WP_ADMIN_GROUP_GENERAL,
			__( 'General', 'fathom-analytics-conversions' ),
			[ $this, 'fac4wp_admin_output_section' ],
			FAC4WP_ADMINSLUG
		);

		add_settings_field(
			FAC4WP_ADMIN_GROUP_API_KEY,
			__( 'API Key', 'fathom-analytics-conversions' ),
			[ $this, 'fac4wp_admin_output_field' ],
			FAC4WP_ADMINSLUG,
			FAC4WP_ADMIN_GROUP_GENERAL,
			[
				'label_for'   => FAC4WP_ADMIN_GROUP_API_KEY,
				'description' => __( 'Enter your Fathom API key here.', 'fathom-analytics-conversions' ) . ' Get API key <a href="https://app.usefathom.com/api" target="_blank">here</a>.',
			]
		);

		add_settings_field(
			FAC4WP_ADMIN_GROUP_SITE_ID,
			__( 'Site ID', 'fathom-analytics-conversions' ),
			[ $this, 'fac4wp_admin_output_field' ],
			FAC4WP_ADMINSLUG,
			FAC4WP_ADMIN_GROUP_GENERAL,
			[
				'label_for'   => FAC4WP_ADMIN_GROUP_SITE_ID,
				'description' => __( 'Site ID from Fathom Analytics.', 'fathom-analytics-conversions' ),
			]
		);

		do_action( 'fac4wp_settings_field_after_general_section' );

		add_settings_section(
			FAC4WP_ADMIN_GROUP_INTEGRATION,
			__( 'Integration', 'fathom-analytics-conversions' ),
			[ $this, 'fac4wp_admin_output_section' ],
			FAC4WP_ADMINSLUG
		);

		$fac4wp_integrate_field_texts = apply_filters( 'fac4wp_integrate_field_texts', $fac4wp_integrate_field_texts );
		foreach ( $fac4wp_integrate_field_texts as $field_id => $field_data ) {
			$phase = isset( $field_data['phase'] ) ? $field_data['phase'] : FAC4WP_PHASE_STABLE;

			add_settings_field(
				'fac4wp-admin-' . $field_id . '-id',
				$field_data['label'] . '<span class="' . $phase . '"></span>',
				[ $this, 'fac4wp_admin_output_field' ],
				FAC4WP_ADMINSLUG,
				FAC4WP_ADMIN_GROUP_INTEGRATION,
				[
					'label_for'       => 'fac4wp-options[' . $field_id . ']',
					'description'     => $field_data['description'],
					'option_field_id' => $field_id,
					'plugin_to_check' => isset( $field_data['plugin_to_check'] ) ? $field_data['plugin_to_check'] : '',
				]
			);
		}

		do_action( 'fac4wp_settings_field_after_integration_section' );
	}

	/**
	 * Adds a submenu page to the Settings main menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function fac_admin_menu() {
		add_options_page(
			__( 'Fathom Analytics Conversions settings', 'fathom-analytics-conversions' ),
			__( 'Fathom Analytics Conversions', 'fathom-analytics-conversions' ),
			'manage_options',
			FAC4WP_ADMINSLUG,
			[ $this, 'fac4wp_show_admin_page' ],
			11
		);

	}


	public function fac4wp_show_admin_page() {
		//global $gtp4wp_plugin_url;
		?>
        <div class="wrap">
            <div id="fac4wp-icon" class="icon32"
                 style="background-image: url(<?php //echo $gtp4wp_plugin_url; ?>admin/images/tag_manager-32.png);">
                <br/></div>
            <h2><?php _e( 'Fathom Analytics Conversions options', 'fathom-analytics-conversions' ); ?></h2>
            <form action="options.php" method="post">

                <?php settings_fields( FAC4WP_ADMIN_GROUP ); ?>

                <?php do_settings_sections( FAC4WP_ADMINSLUG ); ?>

                <?php do_action( 'fac_settings_field_before_submit_button' ); ?>

				<?php submit_button(); ?>

            </form>
        </div>
		<?php
	}

	public function fac_admin_notices() {
		$fac4wp_options = fac4wp_reload_options();

		if ( ! file_exists( WP_PLUGIN_DIR . '/fathom-analytics/fathom-analytics.php' ) ) {

			$notice = '<div class="error" id="messages"><p>';
			$notice .= sprintf(
				wp_kses(
					__( '<b>Fathom Analytics</b> plugin must be installed for the <b>Fathom Analytics Conversions</b> to work. <b><a href="%s" class="thickbox" title="Fathom Analytics">Install Fathom Analytics Now.</a></b>', 'fathom-analytics-conversions' ),
					[
						'a' => [
							'href'  => TRUE,
							'class' => TRUE,
							'title' => TRUE,
						],
						'b' => [],
					]
				),
				admin_url( 'plugin-install.php?tab=plugin-information&plugin=fathom-analytics&from=plugins&TB_iframe=true&width=600&height=550' )
			);
			$notice .= '</p></div>';
			echo wp_kses( $notice,
				[
					'div' => [
						'class' => TRUE,
						'id'    => TRUE,
					],
					'p'   => [],
					'a'   => [
						'href'  => TRUE,
						'class' => TRUE,
						'title' => TRUE,
					],
					'b'   => [],
				]
			);

		} elseif ( ! is_plugin_active( 'fathom-analytics/fathom-analytics.php' ) && empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) {
			$notice = '<div class="error" id="messages"><p>';
			$notice .= wp_kses( __( '<b>Please activate Fathom Analytics</b> below for the <b>Fathom Analytics Conversions</b> to work.', 'fathom-analytics-conversions' ),
				[
					'b' => [],
				]
			);
			$notice .= '</p></div>';
			echo wp_kses( $notice,
				[
					'div' => [
						'class' => TRUE,
						'id'    => TRUE,
					],
					'p'   => [],
					'b'   => [],
				]
			);
		}
	}

}
