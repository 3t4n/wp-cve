<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Peach Payments Blocks integration
 *
 * @since 1.0.3
 */
final class WC_Gateway_Peach_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'peach-payments';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_peach-payments_settings', [] );
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		$payment_gateways_class = WC()->payment_gateways();
		$payment_gateways       = $payment_gateways_class->payment_gateways();
		
		if($payment_gateways['peach-payments']){
			return $payment_gateways['peach-payments']->is_available();
		}else{
			return false;
		}
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$script_asset_path = plugins_url( '/frontend/blocks.asset.php',  __FILE__  );
		//$script_asset_path = plugin_dir_url( __DIR__ ). '/blocks/frontend/blocks.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => WC_PEACH_VER,
			);

		//$script_url = plugin_dir_url( __DIR__ ). '/blocks/frontend/blocks.js';
		$script_url = plugins_url( '/frontend/blocks.js',  __FILE__  );

		wp_register_script(
			'wc-peach-payments-blocks',
			$script_url,
			$script_asset['dependencies'],
			'',
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'wc-peach-payments-blocks', 'woocommerce-gateway-peach-payments', WC_PEACH_PLUGIN_PATH . '/languages/' );
		}

		return [ 'wc-peach-payments-blocks' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		$payment_gateways_class = WC()->payment_gateways();
		$payment_gateways       = $payment_gateways_class->payment_gateways();
		$gateway                = $payment_gateways['peach-payments'];
		//$this->gateway  = new WC_Peach_Payments();
		ob_start();
		$gateway->payment_fields();
		$output = ob_get_clean();
		//$output = '<p>Test Output</p>';
		return [
			'title'       => $this->get_setting( 'title' ),
			'description' => $this->get_setting( 'description' ),
			//'supports'    => array_filter( $this->gateway->supports, [ $this->gateway, 'supports' ] ),
			'supports'    => array_filter( $gateway->supports, array( $gateway, 'supports' ) ),
			'whatever'    => $output
		];
	}
}
