<?php
defined( 'ABSPATH' ) || exit;

#[AllowDynamicProperties]

 final class WFACP_template_Elementor extends WFACP_Elementor_template {
	private static $ins = null;

	protected function __construct() {
		parent::__construct();
		$this->template_dir  = __DIR__;
		$this->template_slug = 'elementor';
		$this->template_type = 'elementor';
		$this->css_default_classes();

		add_filter( 'wc_get_template', [ $this, 'replace_native_checkout_form' ], 999, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ], 999 );

	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function set_selected_template( $data ) {
		parent::set_selected_template( $data );
		$this->template_slug = $data['slug'];

	}

	public function css_default_classes() {
		$css_class         = [
			'billing_email'      => [
				'class' => 'wfacp-col-full',
			],
			'product_switching'  => [
				'class' => 'wfacp-col-full',
			],
			'billing_first_name' => [
				'class' => 'wfacp-col-left-half',
			],
			'billing_last_name'  => [
				'class' => 'wfacp-col-left-half',
			],
			'address'            => [
				'class' => 'wfacp-col-left-half',
			],
			'billing_company'    => [
				'class' => 'wfacp-col-full',
			],
			'billing_address_1'  => [
				'class' => 'wfacp-col-left-half',
			],
			'billing_address_2'  => [
				'class' => 'wfacp-col-left-half',
			],

			'billing_country'  => [
				'class' => 'wfacp-col-left-third',
			],
			'billing_city'     => [
				'class' => 'wfacp-col-left-half',
			],
			'billing_postcode' => [
				'class' => 'wfacp-col-left-third',
			],

			'billing_state' => [
				'class' => 'wfacp-col-left-third',
			],
			'billing_phone' => [
				'class' => 'wfacp-col-full',
			],

			'shipping_email'      => [
				'class' => 'wfacp-col-full',
			],
			'shipping_first_name' => [
				'class' => 'wfacp-col-left-half',
			],
			'shipping_last_name'  => [
				'class' => 'wfacp-col-left-half',
			],
			'shipping_company'    => [
				'class' => 'wfacp-col-full',
			],
			'shipping_address_1'  => [
				'class' => 'wfacp-col-left-half',
			],
			'shipping_address_2'  => [
				'class' => 'wfacp-col-left-half',
			],
			'shipping_country'    => [
				'class' => 'wfacp-col-left-third',
			],
			'shipping_city'       => [
				'class' => 'wfacp-col-left-half',
			],
			'shipping_postcode'   => [
				'class' => 'wfacp-col-left-third',
			],
			'shipping_state'      => [
				'class' => 'wfacp-col-left-third',
			],
			'shipping_phone'      => [
				'class' => 'wfacp-col-full',
			],
			'order_comments'      => [
				'class' => 'wfacp-col-full',
			],
		];
		$this->css_classes = apply_filters( 'wfacp_default_form_classes', $css_class );
	}

	public function replace_native_checkout_form( $template, $template_name ) {
		if ( 'checkout/form-checkout.php' === $template_name ) {
			return $this->wfacp_get_form();
		}

		return $template;

	}


	public function enqueue_style() {

		wp_enqueue_style( 'elementor-style', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp-form.min.css', array(), WFACP_VERSION, false );

		if ( is_rtl() ) {
			wp_enqueue_style( 'layout1-style-rtl', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp-form-style-rtl.css', '', WFACP_VERSION, false );
		}
	}

}

return WFACP_template_Elementor::get_instance();
