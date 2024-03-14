<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Admin_Settings' ) ) {
	return;
}

if ( ! class_exists( '\WC_Settings_Page' ) ) {
	$relative_filename = str_replace(
		'/',
		DIRECTORY_SEPARATOR,
		'/woocommerce/includes/admin/settings/class-wc-settings-page.php'
	);
	$file             = WP_PLUGIN_DIR . $relative_filename;
	if ( file_exists( $file ) ) {
		include_once $file;
	} else {
		exit;
	}
}

/**
 * payever Admin Global Settings Payment
 *
 * This file is used for creating the payever global configuration and payever
 * administration portal in shop backend.
 *
 * Copyright (c) payever
 *
 * This script is only free to the use for merchants of payever. If
 * you have found this script useful a small recommendation as well as a
 * comment on merchant form would be greatly appreciated.
 *
 * @class       WC_Payever_Admin_Settings
 */
class WC_Payever_Admin_Settings extends WC_Settings_Page {

	use WC_Payever_WP_Wrapper_Trait;

	const PAYEVER_OPTION_PREFIX = 'payever';

	/**
	 * Setup settings class
	 */
	public function __construct( $wp_wrapper = null ) {

		if ( null !== $wp_wrapper ) {
			$this->set_wp_wrapper( $wp_wrapper );
		}

		$this->id    = 'payever_settings';
		$this->label = 'payever Checkout';

		$this->get_wp_wrapper()->add_filter(
			'woocommerce_settings_tabs_array',
			array(
				$this,
				'add_settings_page',
			),
			50
		);
		$this->get_wp_wrapper()->add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		$this->get_wp_wrapper()->add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );

		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_field_payever_synchronization_button',
			array(
				$this,
				'synchronization_button',
			)
		);
		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_field_payever_set_sandbox_mode',
			array(
				$this,
				'set_sandbox_mode',
			)
		);
		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_field_payever_embedded_support',
			array(
				$this,
				'embedded_support',
			)
		);
		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_field_payever_toggle_subscription',
			array(
				$this,
				'toggle_subscription',
			)
		);
		$this->get_wp_wrapper()->add_action(
			'woocommerce_admin_field_payever_fe_synchronization_button',
			array(
				$this,
				'fe_synchronization_button',
			)
		);

		// only add this if you need to add sections for your settings tab
		$this->get_wp_wrapper()->add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
	}

	/**
	 * phpcs:disable Generic.WhiteSpace.DisallowSpaceIndent
	 * phpcs:disable Squiz.PHP.EmbeddedPhp
	 * phpcs:disable WordPress.WhiteSpace.PrecisionAlignment
	 */
	public function embedded_support() {
		?>
		<script type='application/javascript'>
			function pe_chat_btn(e) {
				window.zESettings = {analytics: false};

				var s = document.createElement('script');
				s.src = 'https://static.zdassets.com/ekr/snippet.js?key=775ae07f-08ee-400e-b421-c190d7836142';
				s.id = 'ze-snippet';
				s.onload = function () {
					window['zE'] && window['zE']('webWidget', 'open');
					window['zE'] && window['zE']('webWidget:on', 'open', function () {
						e.target.innerText = '<?php esc_attr_e( __( 'Need help? Chat with us!', 'payever-woocommerce-gateway' ) ); ?>';
					});
				};
				document.head.appendChild(s);

				e.target.innerText = '<?php esc_attr_e( __( 'Loading chat...', 'payever-woocommerce-gateway' ) ); ?>';
				e.preventDefault();

				return false;
			}
		</script>

		<a href="javascript:void(0);"
			onclick="return pe_chat_btn(event);"><?php esc_html_e( __( 'Need help? Chat with us!', 'payever-woocommerce-gateway' ) ); ?></a>
		<p><?php esc_html_e( __( 'Our free english and german speaking support is there for you from Monday to Friday, 8am-7pm. If you want to report a specific technical problem, please include your WordPress, WooCommerce versions and payever plugin version in your message to us, and attach your plugin logs to it.', 'payever-woocommerce-gateway' ) ); ?></p>
	<?php }

	/**
	 * Output synchronization_button settings.
	 */
	public function synchronization_button() {
		?>
		<tr valign="top" class="">
			<th class="titledesc" scope="row"><?php esc_html_e( __( 'Synchronization', 'payever-woocommerce-gateway' ) ); ?></th>
			<td class="forminp">
				<fieldset>
					<label for="payever_synchronize">
						<input type="button" class="button-primary"
								onClick="location.href='<?php esc_attr_e( WC()->api_request_url( 'payever_synchronization' ) ); ?>'"
								value="<?php esc_attr_e( __( 'Synchronize', 'payever-woocommerce-gateway' ) ); ?>"
								name="payever_synchronization_button">
						<span class="description">
						<br>
							<?php esc_attr_e( __( 'You need to save settings before synchronization', 'payever-woocommerce-gateway' ) ); ?>
						</span>
					</label>
				</fieldset>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output fe_synchronization_button settings.
	 */
	public function fe_synchronization_button() {
		?>
		<tr valign="top" class="">
			<th class="titledesc" scope="row"><?php esc_html_e( __( 'Express widget synchronization', 'payever-woocommerce-gateway' ) ); ?></th>
			<td class="forminp">
				<fieldset>
					<label for="payever_synchronize">
						<input type="button" class="button-primary"
								onClick="location.href='<?php esc_attr_e( WC()->api_request_url( 'payever_fe_synchronization' ) ); ?>'"
								value="<?php esc_attr_e( __( 'Synchronize express widgets', 'payever-woocommerce-gateway' ) ); ?>"
								name="payever_fe_synchronization_button">
					</label>
					<span class="description">
						<br>
							<?php esc_html_e( __( 'You have to have entered your payever api keys in the general settings of plugin', 'payever-woocommerce-gateway' ) ); ?>
						</span>
					</label>
				</fieldset>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output toggle_subscription settings.
	 */
	public function toggle_subscription() {
		?>
		<tr valign="top" class="">
			<th class="titledesc" scope="row"><?php esc_attr_e( __( 'Synchronization', 'payever-woocommerce-gateway' ) ); ?></th>
			<td class="forminp">
				<fieldset>
					<label for="payever_toggle_subscription">
						<input type="button" class="button-primary"
								onClick="location.href='<?php esc_attr_e( WC()->api_request_url( 'payever_toggle_subscription' ) ); ?>'"
								value="<?php esc_attr_e( WC_Payever_Helper::instance()->is_products_sync_enabled() ? __( 'Disable', 'payever-woocommerce-gateway' ) : __( 'Enable', 'payever-woocommerce-gateway' ) ); ?>"
								name="payever_toggle_subscription">
					</label>
					<label for="payever_export_products">
						<input id="export_products_button" type="button" class="button-primary"
								onClick="return export_products();"
								value="<?php esc_attr_e( __( 'Export WooCommerce products', 'payever-woocommerce-gateway' ) ); ?>"
								name="payever_export_products">
						<script type="text/javascript">
							function export_products(page, aggregate) {
								jQuery('#export_products_button').prop('disabled', true);
								var data = {
									action: 'export_products',
									page: page,
									aggregate: aggregate
								};

								if (!aggregate) {
									jQuery('#export_status_messages').html('<div class="notice notice-info"><p><?php esc_html_e( __( 'Preparing exporting products...', 'payever-woocommerce-gateway' ) ); ?></p></div>');
								}

								jQuery.ajax({
									url: ajaxurl,
									data: data,
									type: 'POST',
									success: function (response) {
										console.log(response);
										switch (response.status) {
											case 'in_process':
												jQuery('#export_status_messages').html('<div class="notice notice-info"><p>' + response.message + '</p></div>');
												return export_products(response.next_page, response.aggregate);
											case 'success':
												jQuery('#export_status_messages').html('<div class="notice notice-success"><p>' + response.message + '</p></div>');
												break;
											case 'error':
												jQuery('#export_status_messages').html('<div class="notice notice-error"><p>' + response.message + '</p></div>');
												break;
											default:
												jQuery('#export_status_messages').html('<div class="notice notice-error"><p><?php esc_html_e( __( 'Something went wrong', 'payever-woocommerce-gateway' ) ); ?></p></div>');
										}

										jQuery('#export_products_button').prop("disabled", false);
									}
								});
							}
						</script>
					</label>
					<div id="export_status_messages"></div>
				</fieldset>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output set_sandbox_mode settings.
	 */
	public static function set_sandbox_mode() {
		$isset_live = (bool) ( get_option( WC_Payever_Helper::PAYEVER_ISSET_LIVE )
								&& ! empty( get_option( WC_Payever_Helper::PAYEVER_LIVE_CLIENT_SECRET ) )
								&& ! empty( get_option( WC_Payever_Helper::PAYEVER_LIVE_CLIENT_ID ) )
								&& ! empty( get_option( WC_Payever_Helper::PAYEVER_LIVE_BUSINESS_ID ) ) );
		if ( $isset_live ) :
			?>
			<tr valign="top" class="">
				<th class="titledesc"
					scope="row"><?php esc_html_e( __( 'Reset live API keys', 'payever-woocommerce-gateway' ) ); ?></th>
				<td class="forminp">
					<fieldset>
						<label for="payever_set_live_api">
							<input type="button" class="button-primary"
									onClick="location.href='<?php esc_attr_e( WC()->api_request_url( 'payever_set_live_api_keys' ) ); ?>'"
									value="<?php esc_attr_e( __( 'Reset live API keys', 'payever-woocommerce-gateway' ) ); ?>"
									name="payever_set_api_keys">
							<span class="description">
						<br>
							<?php esc_html_e( __( 'Reset live API keys', 'payever-woocommerce-gateway' ) ); ?>
						</span>
						</label>
					</fieldset>
				</td>
			</tr>
        <?php endif;?>
        <?php if ( ! $isset_live ) :?>
			<tr valign="top" class="">
				<th class="titledesc"
					scope="row"><?php esc_html_e( __( 'Set up sandbox API Keys', 'payever-woocommerce-gateway' ) ); ?></th>
				<td class="forminp">
					<fieldset>
						<label for="payever_set_sandbox_api">
							<input type="button" class="button-primary"
									onClick="location.href='<?php esc_attr_e( WC()->api_request_url( 'payever_set_sandbox_api_keys' ) ); ?>'"
									value="<?php esc_attr_e( __( 'Set up sandbox API Keys', 'payever-woocommerce-gateway' ) ); ?>"
									name="payever_set_api_keys">
							<span class="description">
						<br>
						<?php esc_html_e( __( 'Set up sandbox API Keys', 'payever-woocommerce-gateway' ) ); ?>
					</span>
						</label>
					</fieldset>
				</td>
			</tr>
			<?php
		endif;
	}

	/**
	 * @return array|mixed|void
	 * phpcs:enable Generic.WhiteSpace.DisallowSpaceIndent
	 * phpcs:enable Squiz.PHP.EmbeddedPhp
	 * phpcs:enable WordPress.WhiteSpace.PrecisionAlignment
	 */
	public function get_sections() {
		$sections = array(
			''             => __( 'Default setting', 'payever-woocommerce-gateway' ),
			'products_app' => __( 'Products App', 'payever-woocommerce-gateway' ),
			'fe_widget'    => __( 'Express Widget', 'payever-woocommerce-gateway' ),
		);

		return $this->get_wp_wrapper()->apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array
	 *
	 * @param string $current_section Optional. Defaults to empty string.
	 *
	 * @return array Array of settings
	 * @since 1.0.0
	 */
	public function get_settings( $current_section = '' ) {
		$settings = $this->get_default_settings();
		if ( 'products_app' === $current_section ) {
			$settings = $this->get_products_app_settings();
		} elseif ( 'fe_widget' === $current_section ) {
			$settings = $this->get_fe_widget_settings();
		}

		/**
		 * Filter payever Settings
		 *
		 * @param array $settings Array of the plugin settings
		 */
		return $this->get_wp_wrapper()->apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * @return mixed|null
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	private function get_default_settings() {
		$statuses = wc_get_order_statuses();
		if ( ! $this->get_wp_wrapper()->get_option( self::PAYEVER_OPTION_PREFIX . '_shipped_status' ) ) {
			$this->get_wp_wrapper()->update_option( self::PAYEVER_OPTION_PREFIX . '_shipped_status', WC_Payever_Helper::DEFAULT_SHIPPED_STATUS );
		}

		return $this->get_wp_wrapper()->apply_filters(
			'woocommerce_' . $this->id,
			array(
				array(
					'title' => 'payever Checkout',
					'id'    => self::PAYEVER_OPTION_PREFIX . '_global_settings',
					'desc'  => __( 'payever_plugin_description', 'payever-woocommerce-gateway' ),
					'type'  => 'title',
				),
				array(
					'title' => '',
					'desc'  => '',
					'id'    => self::PAYEVER_OPTION_PREFIX . '_embedded_support',
					'type'  => self::PAYEVER_OPTION_PREFIX . '_embedded_support',
				),
				array(
					'title'   => __( 'Enable / Disable', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Enable payever payment gateway', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_enabled',
					'css'     => 'width:25em;',
					'type'    => 'checkbox',
					'default' => '',
				),
				array(
					'title'   => __( 'Mode', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Choose mode', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_environment',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => WC_Payever_Helper::instance()->get_payever_modes(),
				),
				array(
					'title' => __( 'Client ID *', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Client ID Key.', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_client_id',
					'css'   => 'width:25em;',
					'type'  => 'text',
				),
				array(
					'title' => __( 'Client Secret *', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Client Secret Key.', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_client_secrect',
					'css'   => 'width:25em;',
					'type'  => 'text',
				),
				array(
					'title'   => __( 'Business UUID *', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Business UUID', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_slug',
					'css'     => 'width:25em;',
					'type'    => 'text',
					'default' => '',
				),
				array(
					'title' => __( 'Set up sandbox API Keys', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Set up sandbox API Keys', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_set_sandbox_mode',
					'type'  => self::PAYEVER_OPTION_PREFIX . '_set_sandbox_mode',
				),
				array(
					'title' => __( 'Synchronization', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Synchronization', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_synchronization',
					'type'  => self::PAYEVER_OPTION_PREFIX . '_synchronization_button',
				),
				array(
					'title'   => __( 'Display only active payment methods in settings', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Display only active payment methods in settings', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_display_active_payments',
					'css'     => 'width:25em;',
					'type'    => 'checkbox',
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Display payment name', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Display payment name', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_display_payment_name',
					'css'     => 'width:25em;',
					'type'    => 'checkbox',
					'default' => 'yes',
				),
				array(
					'title' => __( 'Display payment icon', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Display payment icon', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_display_payment_icon',
					'css'   => 'width:25em;',
					'type'  => 'checkbox',
				),
				array(
					'title' => __( 'Display payment description', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Take description automatically', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_display_payment_description',
					'css'   => 'width:25em;',
					'type'  => 'checkbox',
				),
				array(
					'title'   => __( 'Default language in checkout', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Choose default language in checkout', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_languages',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => array(
						''      => 'None',
						'store' => 'Use store locale',
						'en'    => 'English',
						'de'    => 'Deutsch',
						'es'    => 'Español',
						'no'    => 'Norsk',
						'da'    => 'Dansk',
						'sv'    => 'Svenska',
					),
				),
				array(
					'title'   => __( 'Shipped status', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Choose shipped status', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_shipped_status',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => $statuses,
					'default' => WC_Payever_Helper::DEFAULT_SHIPPED_STATUS,
				),
				array(
					'title' => __( 'Redirect to payever', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Check to get redirected to payever on a new page or leave blank to use an iframe.', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_redirect_to_payever',
					'css'   => 'width:25em;',
					'type'  => 'checkbox',
				),
				array(
					'title'   => __( 'Logging level', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_log_level',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => array(
						'debug' => 'Debug',
						'info'  => 'Info',
						'error' => 'Error',
					),
					'default' => 'info',
				),
				array(
					'title'   => __( 'Send logs via APM', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_log_diagnostic',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => array(
						0 => 'No',
						1 => 'Yes',
					),
					'default' => 0,
				),
			)
		);
	}

	private function get_fe_widget_settings() {
		return $this->get_wp_wrapper()->apply_filters(
			'woocommerce_fe_widget_settings',
			array(
				array(
					'title' => 'Express Widget',
					'id'    => self::PAYEVER_OPTION_PREFIX . '_global_settings',
					'desc'  => '',
					'type'  => 'title',
				),
				array(
					'title' => '',
					'desc'  => '',
					'id'    => self::PAYEVER_OPTION_PREFIX . '_embedded_support',
					'type'  => self::PAYEVER_OPTION_PREFIX . '_embedded_support',
				),
				array(
					'title'   => __( 'Enable / Disable on product single page', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Enable payever express widget on product single page', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_active_widget_on_single_page',
					'css'     => 'width:25em;',
					'type'    => 'checkbox',
					'default' => '',
				),
				array(
					'title'   => __( 'Enable / Disable on cart', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Enable payever express widget on cart', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_active_widget_on_cart',
					'css'     => 'width:25em;',
					'type'    => 'checkbox',
					'default' => '',
				),
				array(
					'title' => __( 'Synchronize express widgets', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Express widget synchronization', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_fe_synchronization',
					'type'  => self::PAYEVER_OPTION_PREFIX . '_fe_synchronization_button',
				),
				array(
					'title'   => __( 'Express widget type', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Please choose the express widget type', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_express_widget_type',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => WC_Payever_Helper::instance()->get_active_payever_widget_options(),
					'default' => '',
				),
				array(
					'title'   => __( 'payeverAdminMenuConfigSettingsWidgetTheme', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'payeverAdminMenuConfigSettingsWidgetThemeDescription', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_express_widget_theme',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => WC_Payever_Helper::instance()->get_widget_themes(),
					'default' => WC_Payever_Helper::WIDGET_THEME_DARK,
				),
			)
		);
	}

	/**
	 * @return mixed|null
	 */
	private function get_products_app_settings() {
		return $this->get_wp_wrapper()->apply_filters(
			'woocommerce_products_app_settings',
			array(
				array(
					'title' => 'Products App',
					'id'    => self::PAYEVER_OPTION_PREFIX . '_global_settings',
					'desc'  => '',
					'type'  => 'title',
				),
				array(
					'title' => '',
					'desc'  => '',
					'id'    => self::PAYEVER_OPTION_PREFIX . '_embedded_support',
					'type'  => self::PAYEVER_OPTION_PREFIX . '_embedded_support',
				),
				array(
					'title' => __( 'Enable', 'payever-woocommerce-gateway' ),
					'desc'  => '<br/>' . __( 'Synchronization', 'payever-woocommerce-gateway' ),
					'id'    => self::PAYEVER_OPTION_PREFIX . '_toggle_subscription',
					'type'  => self::PAYEVER_OPTION_PREFIX . '_toggle_subscription',
				),
				array(
					'title'   => __( 'Processing mode', 'payever-woocommerce-gateway' ),
					'desc'    => '<br/>' . __( 'Using cron mode is highly recommended, but please make sure you have WooCommerce cron job installed. HTTP mode may decrease site performance dramatically on stock-related requests (mostly in checkout process).', 'payever-woocommerce-gateway' ),
					'id'      => self::PAYEVER_OPTION_PREFIX . '_products_synchronization_mode',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => array(
						'instant' => __( 'Instantly on HTTP requests', 'payever-woocommerce-gateway' ),
						'cron'    => __( 'Cron queue processing', 'payever-woocommerce-gateway' ),
					),
				),
			)
		);
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );
	}
}
