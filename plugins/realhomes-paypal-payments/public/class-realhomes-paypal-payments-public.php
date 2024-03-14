<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    realhomes-paypal-payments
 * @subpackage realhomes-paypal-payments/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and public-facing stylesheet and JavaScript.
 *
 * @since 1.0.0
 */
class Realhomes_Paypal_Payments_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
     * PayPal's payment button for the properties.
     *
	 * @param $property_id
	 *
	 * @return void
	 */
	public static function payment_button( $property_id ) {
		?>
        <div class="paypal-button-container" data-property-id="<?php echo esc_attr( $property_id ); ?>"></div>
		<?php
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/realhomes-paypal-payments-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$rpp_settings = get_option( 'rpp_settings' );
		wp_enqueue_script( 'paypal-sdk-js', 'https://www.paypal.com/sdk/js?client-id=' . $rpp_settings['client_id'] . '&enable-funding=paypal&disable-funding=card', array( 'jquery' ), null, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/realhomes-paypal-payments-public.js', array( 'jquery' ), $this->version, false );
	}

}
