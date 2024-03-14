<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Freepay Payments Blocks integration
 *
 * @since 1.0.3
 */
final class WC_Gateway_FreePay_Blocks_Support extends AbstractPaymentMethodType {

	private $gateway;

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'freepay';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
        $this->gateway = WC_FreePay::get_instance();
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return true;
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$script_path       = '/../assets/js/frontend/blocks.js';
		$script_asset_path = trailingslashit( plugin_dir_path( __FILE__ ) ) . '../assets/js/frontend/blocks.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require( $script_asset_path )
			: array(
				'dependencies' => array(),
				'version'      => '1.0.0'
			);
		$script_url        = untrailingslashit( plugins_url( '/', __FILE__ ) ) . $script_path;

		wp_register_script(
			'wc-freepay-payments-blocks',
			$script_url,
			$script_asset[ 'dependencies' ],
			$script_asset[ 'version' ],
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'wc-freepay-payments-blocks', 'freepay-for-woocommerce', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'languages/' );
		}

		return [ 'wc-freepay-payments-blocks' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return [
			'title'       => $this->gateway->title,
			'description' => $this->gateway->description,
			'supports'    => array_filter( $this->gateway->supports, [ $this->gateway, 'supports' ] ),
			'icons'		  => $this->gateway->s( 'freepay_icons' )
		];
	}
}
