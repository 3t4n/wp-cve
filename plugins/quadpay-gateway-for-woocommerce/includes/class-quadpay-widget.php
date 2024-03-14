<?php

class Quadpay_WC_Widget
{
	const WIDGET_SOURCE = 'https://cdn.quadpay.com/v1/quadpay.js';

	/**
	 * @var Quadpay_WC_Settings
	 */
	private $settings;

	/**
	 * @var Quadpay_WC_Widget
	 */
	private static $instance;

	/**
	 * @return Quadpay_WC_Widget
	 */
	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Quadpay_WC_Widget constructor.
	 */
	public function __construct()
	{
		$this->settings = Quadpay_WC_Settings::instance();
	}

	/**
	 * Init hooks
	 */
	public function init()
	{
		if ( ! $this->settings->is_enabled() ) {
			return;
		}

		// init actions and filters
		add_filter( 'woocommerce_get_price_html', array( $this, 'quadpay_product_price_html_widget' ), 10, 2 );
		add_filter( 'woocommerce_variable_price_html', array( $this, 'quadpay_variable_price_html_widget' ) );
		add_action( 'woocommerce_proceed_to_checkout', array( $this, 'quadpay_info_cart_page_widget' ), 15 );
		add_action( 'wp_enqueue_scripts', array( $this, 'quadpay_wc_frontend_js' ) );
	}

	/**
	 * Showing the Zip widget on a simple product page
	 *
	 * @param $price_html
	 * @param $product
	 * @return string
	 */
	public function quadpay_product_price_html_widget( $price_html, $product ) {

		if ( ! is_product() ) {
			return $price_html;
		}

		if ( ! $this->settings->get_option_bool( 'enable_product_widget' ) ) {
			return $price_html;
		}

		if ( 'simple' !== $product->get_type() ) {
			return $price_html;
		}

		if ( did_action( 'woocommerce_before_add_to_cart_quantity' ) ) {
			return $price_html;
		}

		$widget_wrapper       = $this->settings->get_option( 'product_page_widget_wrapper', '' );
		$widget_customization = $this->settings->get_option( 'product_page_widget_customization', '' );
		$merchantId           = $this->settings->get_option( 'merchant_id', '' );

		$price = $product->get_price();
		$tag_version_suffix = ( $this->settings->get_option_bool( 'widget_backward_compatibility' ) ) ? '' : '-v3';

		ob_start();
		?>
		<div style="min-height:20px;<?php echo $widget_wrapper; ?>">
			<quadpay-widget<?php echo $tag_version_suffix ?>
				amount="<?php echo $price; ?>"
			<?php if (!empty($merchantId)):?>
				merchantId="<?php echo $merchantId; ?>"
			<?php endif; ?>
				<?php echo $widget_customization; ?>>
			</quadpay-widget<?php echo $tag_version_suffix ?>>
		</div>
		<?php

		$quadpay_widget = ob_get_clean();

		return $price_html . $quadpay_widget;

	}

	/**
	 * Showing the Zip widget on a variable product page
	 *
	 * @param $price_html
	 * @return string
	 */
	public function quadpay_variable_price_html_widget( $price_html ) {

		if ( ! is_product() ) {
			return $price_html;
		}

		if ( ! $this->settings->get_option_bool( 'enable_product_widget' ) ) {
			return $price_html;
		}

		global $product;

		$widget_wrapper       = $this->settings->get_option( 'product_page_widget_wrapper', '' );
		$widget_customization = $this->settings->get_option( 'product_page_widget_customization', '' );
		$merchantId           = $this->settings->get_option( 'merchant_id', '' );

		$price = $product->get_price();
		$tag_version_suffix = ( $this->settings->get_option_bool( 'widget_backward_compatibility' ) ) ? '' : '-v3';

		ob_start();
		?>
		<div style="min-height:20px;<?php echo $widget_wrapper; ?>">
			<quadpay-widget<?php echo $tag_version_suffix ?>
				amount="<?php echo $price; ?>"
			<?php if (!empty($merchantId)):?>
				merchantId="<?php echo $merchantId; ?>"
			<?php endif; ?>
				<?php echo $widget_customization; ?>
			>
			</quadpay-widget<?php echo $tag_version_suffix ?>>
		</div>
		<?php

		$quadpay_widget = ob_get_clean();

		return $price_html . $quadpay_widget;

	}

	/**
	 * Showing the Zip widget on the Cart page
	 */
	public function quadpay_info_cart_page_widget() {

		$amount = WC()->cart->total;

		if ( ! $this->settings->get_option_bool('enable_cart_widget') ) {
			return;
		}

		$widget_wrapper       = $this->settings->get_option( 'cart_widget_wrapper', '' );
		$widget_customization = $this->settings->get_option( 'cart_widget_customization', '' );
		$merchantId           = $this->settings->get_option( 'merchant_id', '' );

		$tag_version_suffix = ( $this->settings->get_option_bool( 'widget_backward_compatibility' ) ) ? '' : '-v3';

		ob_start();
		?>
		<div style="min-height:20px;<?php echo $widget_wrapper; ?>">
			<quadpay-widget<?php echo $tag_version_suffix ?>
				amount="<?php echo $amount; ?>"
			<?php if (!empty($merchantId)):?>
				merchantId="<?php echo $merchantId; ?>"
			<?php endif; ?>
				<?php echo $widget_customization; ?>>
			</quadpay-widget<?php echo $tag_version_suffix ?>>
		</div>
		<?php

		echo ob_get_clean();

	}

	/**
	 * Showing the Zip widget on the Checkout page
	 */
	public function get_payment_widget() {

		if ( ! $this->settings->get_option_bool('enable_payment_widget') ) {
			return '';
		}

		$amount = WC()->cart->total;
		$widget_wrapper       = $this->settings->get_option( 'payment_widget_wrapper', '' );
		$widget_customization = $this->settings->get_option( 'payment_widget_customization', '' );
		$merchantId           = $this->settings->get_option( 'merchant_id', '' );

		ob_start();
		?>
		<div style="<?php echo $widget_wrapper; ?>">
			<zip-payment-widget amount="<?php echo $amount; ?>"
			<?php if (!empty($merchantId)):?>
				merchantId="<?php echo $merchantId; ?>"
			<?php endif; ?>
				<?php echo $widget_customization; ?>>
			</zip-payment-widget>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Enqueue javascript
	 */
	public function quadpay_wc_frontend_js() {

		$enable_cart_widget = $this->settings->get_option_bool( 'enable_cart_widget' );
		$enable_product_widget = $this->settings->get_option_bool( 'enable_product_widget' );

		if ( ( is_singular( 'product' ) && $enable_product_widget ) ||
			( is_cart() && $enable_cart_widget ) ||
			( is_checkout() && $this->settings->get_option_bool('enable_payment_widget') )
		) {

			$widget_backward_compatibility = $this->settings->get_option_bool( 'widget_backward_compatibility' );
			wp_enqueue_script(
				'quadpay_widget',
				self::WIDGET_SOURCE . ($widget_backward_compatibility ? '?tagname=quadpay-widget' : ''),
				array(),
				QUADPAY_WC_VERSION,
				true
			);

			wp_enqueue_script(
				'quadpay_frontend',
				QUADPAY_WC_PLUGIN_URL .  'assets/js/frontend.js',
				array( 'jquery', 'quadpay_widget' ),
				QUADPAY_WC_VERSION,
				true
			);
		}

	}

}
