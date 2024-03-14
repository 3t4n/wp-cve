<?php

class Quadpay_WC_Settings
{
	const CODE = 'quadpay';

	const SETTINGS_KEY = 'woocommerce_quadpay_settings';

	const ENVIRONMENT_DEVELOP = [
		'api_url' => array(
			'US' => 'https://api-ut.quadpay.com',
			'CA' => 'https://sandbox.api.quadpay.ca'
		),
		'auth_url' => 'https://quadpay-dev.auth0.com/oauth/token',
		'auth_audience' => 'https://auth-dev.quadpay.com',
		'api_url_v2' => 'https://sandbox.gateway.quadpay.com'
	];

	const ENVIRONMENT_PRODUCTION = [
		'api_url' => array(
			'US' => 'https://api.quadpay.com',
			'CA' => 'https://api.quadpay.ca'
		),
		'auth_url' => 'https://quadpay.auth0.com/oauth/token',
		'auth_audience' => 'https://auth.quadpay.com',
		'api_url_v2' => 'https://gateway.quadpay.com'
	];

	const ALLOWED_COUNTRIES = [ 'US', 'CA' ];

	/**
	 * @var Quadpay_WC_Settings
	 */
	private static $instance;

	/**
	 * @return Quadpay_WC_Settings
	 */
	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @param string $key
	 * @return array|string|null
	 */
	public function get_environment( $key = null )
	{
		$environment = ( $this->get_option('testmode') === 'production' ) ?
			self::ENVIRONMENT_PRODUCTION :
			self::ENVIRONMENT_DEVELOP;

		if ( ! $key ) {
			return $environment;
		}

		if ( ! isset( $environment[$key] ) ) {
			return null;
		}

		if ( is_array ( $environment[$key] ) ) {
			$territory = $this->get_option('territory');
			return $environment[$key][$territory];
		}

		return $environment[$key];
	}

	/**
	 * @return array[]
	 */
	public function get_form_fields() {

		return array(
			'api_title' => array(
				'title'       => __( 'API Settings', 'woo_quadpay ' ),
				'type'        => 'title',
				'description' => '',
			),
			'enabled'     => array(
				'title'   => __( 'Enable/Disable', 'woo_quadpay' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Zip', 'woo_quadpay' ),
				'default' => 'yes',
			),
			'territory'       => array(
				'title'       => __( 'Territory', 'woo_quadpay' ),
				'label'       => __( 'Choose territory', 'woo_quadpay' ),
				'type'        => 'select',
				'options'     => array(
					'US' => __( 'United States', 'woo_quadpay' ),
					'CA' => __ ( 'Canada', 'woo_quadpay' )
				),
				'default' => 'US'
			),
			'testmode' => array(
				'title'       => __( 'Mode', 'woo_quadpay' ),
				'label'       => __( 'Select mode', 'woo_quadpay' ),
				'type'        => 'select',
				'options'     => array (
					'develop' => __( 'Sandbox Test', 'woo_quadpay' ),
					'production' => __( 'Production', 'woo_quadpay' )
				),
				'description' => __( 'When in Test/Sandbox mode, no transactions will actually be processed.', 'woo_quadpay' ),
				'default' => 'develop'
			),
			'client_id' => array(
				'title'       => __( 'Client ID', 'woo_quadpay' ),
				'type'        => 'text',
				'description' => __( 'Zip Client ID credential', 'woo_quadpay' ),
				'default'     => '',
			),
			'client_secret'   => array(
				'title'       => __( 'Client Secret', 'woo_quadpay' ),
				'type'        => 'text',
				'description' => __( 'Zip Client Secret credential', 'woo_quadpay' ),
				'default'     => '',
			),
            'merchant_id' => array(
                'title'       => __( 'Merchant ID', 'woo_quadpay' ),
                'type'        => 'text',
                'description' => __( 'Zip Merchant ID credential.', 'woo_quadpay' ),
                'default'     => '',
            ),
			'mfpp' => array(
				'title'       => __( 'Merchant Fee for Payment Plan', 'woo_quadpay' ),
				'label'       => __( 'Enable Merchant Fee for Payment Plan', 'woo_quadpay' ),
				'type'        => 'checkbox',
				'description' => __( 'Enable only if option is arranged and configured on Zip side. When enabled, fee may be applied to customers choosing Zip.', 'woo_quadpay' ),
				'default'     => 'no'
			),
			'logging' => array(
				'title'       => __( 'Logging', 'woo_quadpay' ),
				'label'       => __( 'Log debug messages', 'woo_quadpay' ),
				'type'        => 'checkbox',
				'description' => __( 'Save debug messages to the WooCommerce System Status log.', 'woo_quadpay' ),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'order_check_settings' => array(
				'title'       => __( 'Order Check', 'woo_quadpay ' ),
				'type'        => 'title',
				'description' => ''
			),
			'order_check_time' => array(
				'title'       => __( 'Order Check Timeframe', 'woo_quadpay' ),
				'label'       => __( 'Order Check Timeframe', 'woo_quadpay' ),
				'type'        => 'select',
				'options'     => array(
					'DAY_IN_SECONDS'   => __( '24h hours', 'woo_quadpay' ),
					'WEEK_IN_SECONDS'  => __( 'One Week', 'woo_quadpay' ),
					'MONTH_IN_SECONDS' => __( 'One Month', 'woo_quadpay' ),
				),
				'description' => __( 'How far back should authorized, not captured orders be checked for status change.', 'woo_quadpay' ),
				'default' => 'DAY_IN_SECONDS'
			),
			'widget_settings' => array(
				'title'       => __( 'Widget Settings', 'woo_quadpay ' ),
				'type'        => 'title',
				'description' =>'',
			),
			'enable_product_widget' => array(
				'title'   => __( 'Product Page Widget', 'woo_quadpay' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Product Page Widget', 'woo_quadpay' ),
				'default' => 'yes',
			),
			'enable_cart_widget' => array(
				'title'   => __( 'Cart Page Widget', 'woo_quadpay' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Cart Page Widget', 'woo_quadpay' ),
				'default' => 'yes',
			),
			'enable_payment_widget' => array(
				'title'   => __( 'Payment Widget', 'woo_quadpay' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Checkout Page Widget', 'woo_quadpay' ),
				'default' => 'no',
			),
			'widget_customization' => array(
				'title'       => __( 'Widget Customization', 'woo_quadpay ' ),
				'type'        => 'title',
				'description' => __( 'Customize the look of the Zip widget.', 'woo_quadpay' ),
			),
			'product_page_widget_customization' => array(
				'title'             => __( 'Product Page Widget Customization', 'woo_quadpay' ),
				'type'              => 'textarea',
				'description'       => sprintf( __( 'Optional: To customize the appearance of the widget, you can pass attributes. Each attribute should be added on a new line. For further technical support, please refer to the <a href="%s" target="_blank">documentation</a>.', 'woo_quadpay' ), 'https://docs.quadpay.com/docs/widget-integration-v3' ),
				'default'           => '',
			),
			'product_page_widget_wrapper' => array(
				'title'             => __( 'Product Page Widget Wrapper', 'woo_quadpay' ),
				'type'              => 'textarea',
				'description'       => sprintf( __( 'Optional: To adjust the spacing around the widget, you can pass CSS styles. Please refer to the <a href="%s" target="_blank">documentation</a>. A technical background may be helpful when passing these styles.', 'woo_quadpay' ), 'https://docs.quadpay.com/docs/widget-integration-v3' ),
				'default'           => 'margin: 0 0 10px 0;',
			),
			'cart_widget_customization' => array(
				'title'             => __( 'Cart Widget Customization', 'woo_quadpay' ),
				'type'              => 'textarea',
				'default'           => __( '', 'woo_quadpay' ),
				'description'       => sprintf( __( 'Optional: To customize the appearance of the widget, you can pass attributes. Each attribute should be added on a new line. For further technical support, please refer to the <a href="%s" target="_blank">documentation</a>.', 'woo_quadpay' ), 'https://docs.quadpay.com/docs/widget-integration-v3' ),
			),
			'cart_widget_wrapper' => array(
				'title'             => __( 'Cart Widget Wrapper', 'woo_quadpay' ),
				'type'              => 'textarea',
				'description'       => sprintf( __( 'Optional: To adjust the spacing around the widget, you can pass CSS styles. Please refer to the <a href="%s" target="_blank">documentation</a>. A technical background may be helpful when passing these styles.', 'woo_quadpay' ), 'https://docs.quadpay.com/docs/widget-integration-v3' ),
				'default'           => 'margin: 0 0 10px 0;',
			),
			'payment_widget_customization' => array(
				'title'             => __( 'Payment Widget Customization', 'woo_quadpay' ),
				'type'              => 'textarea',
				'default'           => __( '', 'woo_quadpay' ),
				'description'       => sprintf( __( 'Optional: To customize the appearance of the widget, you can pass attributes. Each attribute should be added on a new line. For further technical support, please refer to the <a href="%s" target="_blank">documentation</a>.', 'woo_quadpay' ), 'https://docs.quadpay.com/docs/widget-integration-v3' ),
			),
			'payment_widget_wrapper' => array(
				'title'             => __( 'Payment Widget Wrapper', 'woo_quadpay' ),
				'type'              => 'textarea',
				'description'       => sprintf( __( 'Optional: To adjust the spacing around the widget, you can pass CSS styles. Please refer to the <a href="%s" target="_blank">documentation</a>. A technical background may be helpful when passing these styles.', 'woo_quadpay' ), 'https://docs.quadpay.com/docs/widget-integration-v3' ),
				'default'           => 'margin: 10px 0 10px 0;',
			),
			'widget_backward_compatibility' => array(
				'title'   => __( 'Old Widget Compatibility', 'woo_quadpay' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable backward compatibility with an old widget', 'woo_quadpay' ),
				'description'   => __( 'Use only if you have custom implementation of old &lt;quadpay-widget&gt; tag on your site.', 'woo_quadpay' ),
				'default' => 'no',
			),
		);

	}

	/**
	 * @param $key
	 * @param mixed $default
	 * @return mixed|null
	 */
	public function get_option( $key, $default = null )
	{
		$quadpay_settings = get_option( self::SETTINGS_KEY, [] );

		if ( isset( $quadpay_settings[$key] ) ) {
			return $quadpay_settings[$key];
		}

		if ( isset( $this->get_form_fields()[$key]['default'] ) ) {
			return $this->get_form_fields()[$key]['default'];
		}

		return $default;
	}

	/**
	 * @param $options
	 * @return bool
	 */
	public function update_options( $options )
	{
		$quadpay_settings = get_option( self::SETTINGS_KEY, [] );
		$quadpay_settings = array_merge( $quadpay_settings, $options );

		return update_option( 'woocommerce_quadpay_settings', $quadpay_settings );
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function get_option_bool( $key )
	{
		return $this->get_option( $key ) === 'yes' || $this->get_option( $key ) === true;
	}

	/**
	 * @return bool
	 */
	public function is_enabled()
	{
		return $this->get_option_bool('enabled') &&
			$this->get_option('client_id') &&
			$this->get_option('client_secret');
	}

	/**
	 * @param string $api
	 * @return string
	 */
	public function get_api_url($api)
	{
		return $this->get_environment('api_url') . $api;
	}

	/**
	 * @param string $api
	 * @return string
	 */
	public function get_api_url_v2($api)
	{
		return $this->get_environment('api_url_v2') . $api;
	}

}
