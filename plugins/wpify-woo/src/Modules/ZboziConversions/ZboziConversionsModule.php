<?php

namespace WpifyWoo\Modules\ZboziConversions;

use WpifyWoo\Abstracts\AbstractModule;
use WpifyWoo\WooCommerceIntegration;

class ZboziConversionsModule extends AbstractModule {
	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action( 'woocommerce_thankyou', [ $this, 'tracking_code' ] );

		if (
				function_exists( 'wpify_woo_zbozi_conversions_container' ) &&
				wpify_woo_container()->get( WooCommerceIntegration::class )->is_module_enabled( 'zbozi_conversions' )
		) {
			add_action( 'admin_notices', [ $this, 'duplicity_code_notice' ] );
		}
	}

	/**
	 * Module ID
	 * @return string
	 */
	public function id(): string {
		return 'zbozi_conversions_lite';
	}

	/**
	 * Module name
	 * @return string
	 */
	public function name(): string {
		return __( 'Zbozi.cz/Sklik Conversions Limited', 'wpify-woo' );
	}

	/**
	 * Module settings
	 * @return array[]
	 */
	public function settings(): array {
		$settings = array(
				array(
						'label' => __( 'Shop ID', 'wpify-woo' ),
						'desc'  => __( 'Enter Shop ID for Zbozi.cz', 'wpify-woo' ),
						'id'    => 'shop_id',
						'type'  => 'text',
				),
				array(
						'label' => __( 'Private Key', 'wpify-woo' ),
						'desc'  => __( 'Enter private key for Zbozi.cz', 'wpify-woo' ),
						'id'    => 'private_key',
						'type'  => 'text',
				),
				array(
						'label' => __( 'Sklik ID', 'wpify-woo' ),
						'desc'  => __( 'Enter Sklik ID if you want to enable conversions for Sklik.cz', 'wpify-woo' ),
						'id'    => 'sklik_id',
						'type'  => 'text',
				),
				array(
						'type'  => 'title',
						'label' => __( 'Marketing cookie', 'wpify-woo' ),
						'desc'  => __( 'You need consent from the visitor for marketing cookies. If you don`t enter the name and value of the marketing cookie the Seznam will process the data as if consent had been given.', 'wpify-woo' ),
				),
				array(
						'id'    => 'cookie_name',
						'type'  => 'text',
						'label' => __( 'Marketing cookie name', 'wpify-woo' ),
						'desc'  => __( 'Enter the name of the cookie that represents the agreed marketing cookies. For example, in the case of using the "Complianz" plugin, this is <code>cmplz_marketing</code>.', 'wpify-woo' ),
				),
				array(
						'id'    => 'cookie_value',
						'type'  => 'text',
						'label' => __( 'Marketing cookie value', 'wpify-woo' ),
						'desc'  => __( 'Enter the value of the cookie that represents the agreed marketing cookies. For example, in the case of using the "Complianz" plugin, this is <code>allow</code>.', 'wpify-woo' ),
				),
				array(
						'id'      => 'wpify_pro_notice',
						'type'    => 'html',
						'content' => sprintf( '<div class="notice notice-warning"><p>%s</p></div>', sprintf( __( 'This module sends only limited conversion measurements using frontend code. For a more detailed standard measurement with also backend sending data for Zboží.cz, please install the premium extension <a href="%s" target="_blank">WPify Woo Zbozi.cz Conversion tracking </a>. This premium extension also allows you to send a customer satisfaction survey.', 'wpify-woo' ), __( 'https://wpify.io/product/wpify-woo-zbozi-cz-conversion-tracking/', 'wpify-woo' ) ) ),
				)
		);

		return $settings;
	}

	/**
	 * Render conversion script on thank you page
	 *
	 * @param $order_id
	 */
	public function tracking_code( $order_id ) {
		if ( ! $this->get_setting( 'shop_id' ) || apply_filters( 'wpify_woo_zbozi_conversion_render_code', true ) === false ) {
			return;
		}
		$parameters = $this->get_parameters( $order_id );

		?>
		<!-- Zbozi.cz / Sklik conversion Limited -->
		<script type="text/javascript" src="https://c.seznam.cz/js/rc.js"></script>
		<script>
			var conversionConf = {
				<?php
				foreach ( $parameters as $key => $parameter ) {
					echo $key . ': ' . $parameter . ', ';
				}
				?>
			};
			if (window.rc && window.rc.conversionHit) {
				window.rc.conversionHit(conversionConf);
			}
		</script>
	<?php }

	/**
	 * Get parameters for conversion code
	 *
	 * @param $order_id
	 *
	 * @return mixed|void
	 */
	public function get_parameters( $order_id ) {
		$parameters = array();

		$shop_id = (int) $this->get_setting( 'shop_id' );
		if ( $shop_id ) {
			$parameters['zboziId'] = $shop_id;
			$parameters['orderId'] = '"' . $order_id . '"';
		}

		$parameters['zboziType'] = '"limited"';

		$sklik_id = $this->get_setting( 'sklik_id' );
		if ( $sklik_id ) {
			$wc_order = wc_get_order( $order_id );

			$parameters['id']    = $sklik_id;
			$parameters['value'] = $wc_order->get_total();
		}

		$cookie_name  = $this->get_setting( 'cookie_name' );
		$cookie_value = $this->get_setting( 'cookie_value' );
		if ( $cookie_name && $cookie_value ) {
			$parameters['consent'] = 'document.cookie.includes("' . $cookie_name . '=' . $cookie_value . '") ? 1 : 0';
		}

		return apply_filters( 'wpify_woo_zbozi_conversion_script_parameters', $parameters );
	}

	/**
	 * Notice that the Zbozi.cz/Sklik Conversions pro plugin is also active
	 */
	function duplicity_code_notice() {
		$title  = __( 'Duplicate conversion code may be generated for Zbozi.cz/Sklik', 'wpify-woo' );
		$string = __( 'The <b>Zbozi.cz/Sklik Conversions Limited</b> module is active at the same time as the premium extension <b>Zbozi.cz/Sklik Conversions</b>. If you have both modules active and there will be duplicate generation of the queue conversion code once for limited and once for standard conversion measurement and measurement errors may occur. Please deactivate one of these modules.', 'wpify-woo' );
		printf( '<div class="notice notice-warning"><h2>%s</h2><p>%s</p></div>', $title, $string, );
	}
}
