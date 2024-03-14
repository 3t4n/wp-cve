<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Woo_Payment_Discounts_Admin class
 */
class Woo_Payment_Discounts_Admin {

	/**
	 * Initialize the plugin admin.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpd_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wpd_do_activation_redirect' ) );
		add_action( 'admin_menu', array( $this, 'wpd_screen_pages' ) );
		add_action( 'admin_head', array( $this, 'wpd_screen_remove_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Register admin menu
	 */
	public function wpd_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'Discount Per Payment method for WooCommerce', 'woo-payment-discounts' ), __( 'Discount Per Payment', 'woo-payment-discounts' ), 'manage_woocommerce', 'woo-payment-discounts', array(
				$this,
				'plugin_admin_page_callback',
			) );
	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function plugin_admin_page_callback() {
		$settings = get_option( 'woo_payment_discounts' );
		$payment_gateways = WC()->payment_gateways->payment_gateways();
		include_once 'partials/woo-payment-discounts-admin-display.php';
	}

	public function wpd_do_activation_redirect() {
		// Bail if no activation redirect
		if ( ! get_transient( '_wpd_screen_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_wpd_screen_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirect to bbPress about page
		wp_safe_redirect( add_query_arg( array( 'page' => 'wpd-about' ), admin_url( 'index.php' ) ) );
	}

	/**
	 * Welcome screen page function added.
	 */
	public function wpd_screen_pages() {
		add_dashboard_page( 'Welcome To Discounts Per Payment Method for WooCommerce', 'Discounts Per Payment Method for WooCommerce', 'read', 'wpd-about', array( $this, 'wpd_screen_content' ) );
	}

	/**
	 * Welcome screen content.
	 */
	public function wpd_screen_content() {
		?>
		<div class="wrap wpd_welcome_wrap">
			<fieldset>
				<h2>Welcome to Discounts Per Payment Method for WooCommerce <?php echo Woo_Payment_Discounts::VERSION; ?></h2>
				<div class="wpd_welcom_div">
					<div class="wpd_lite">
						<div>Thank you for installing Discounts Per Payment Method for WooCommerce <?php echo Woo_Payment_Discounts::VERSION; ?></div>
						<div>Discounts Per Payment Method for WooCommerce allows you to Setup discounts for specific payment methods is selected on checkout.</div>

						<div class="block-content"><h4>How to Setup :</h4>

							<ul>
								<li>Step-1: Go to admin dashboard.</li>
								<li>Step-2: Under WooCommerce menu page you will find 'Discount Per Payment' menu page link. Go to that page</li>
								<li>Step-3: Save settings as per your need.</li>
							</ul>
						</div>


					</div>
					<p class="wpd_pro">
						<a href="https://codecanyon.net/item/woocommerce-advanced-discounts-and-fees/19009855?s_rank=1" target="_blank"><h3>WooCommerce Advanced Discounts and Fees</h3></a>

					<p>With WooCommerce Advanced Discounts and Fees, you can charge additional fees or give discount to encourage customers to increase the order value.</p>


					<strong> Benefits of WooCommerce Advanced Discounts and Fees </strong>
					<strong>Boost site revenue</strong>
					<p>You can earn additional revenue by adding customer fees.</p>

					<strong>Incentivize use of specific payment gateway(s)</strong>
					<p>To encourage customers to use your preferred payment gateway, you can charge a payment gateway fee or give discount
						on specific payment gateways.
					</p>

					<strong>Increase cart total value</strong>
					<p>Encourage your customers to make X amount of order value and get discount or avoid additional fees.
					</p>

					<strong>Usere role specific fees and discounts </strong>
					<p>Give a special discount or charge fees to specific user role.
					</p>

					<strong>Categories specific fees and discounts </strong>
					<p>Encourage your customers to purchase products from specific categories to get massive discount. Either you can also charge an additional fees for special categories product in cart.

					</p>


					<strong>Shipping method specific fees and discounts </strong>
					<p>You can earn additional revenue by adding shipping method fees when customer choose a particular shipping method during checkout.
					</p>

					<p><strong>Key featutes WooCommerce Advanced Discounts and Fees</strong>
					</p><ul>
						<li>Option to set fees or discount based on payment gateway.</li>
						<li>Option to set fees or discount based on shipping method</li>
						<li>Option to set fees or discount based on cart total value</li>
						<li>Option to set fees or discount based on user role</li>
						<li>Option to set fees or discount based on products from specific categories in the cart. </li>
						<li>Option to set fees or discount based either fixed amount of percentage base. </li>
						<li>Option to set label for each fees or discounts. </li>
					</ul>
					<br /><br />


					<p><strong>1. Payment gateway based fees and discounts. </strong>
						<img src="https://res.cloudinary.com/dpip9b6hi/image/upload/v1600913011/wadf/01preview11_pfmysm.jpg" />
						<br /><br />
					<p><strong>2. Shipping method based fees and discounts. </strong>
						<img src="https://res.cloudinary.com/dpip9b6hi/image/upload/v1600913011/wadf/02preview22_qijz7c.jpg" />
						<br /><br />
					<p><strong>3. User role based fees and discounts. </strong>
						<img src="https://res.cloudinary.com/dpip9b6hi/image/upload/v1600913011/wadf/03preview33_c5uf4o.jpg" />
						<br /><br />
					<p><strong>4. Cart total based fees and discounts. </strong>
						<img src="https://res.cloudinary.com/dpip9b6hi/image/upload/v1600913011/wadf/04preview44_ekowxu.jpg" />
						<br /><br />

					<p><strong>5. Categories based fees and discounts. </strong>
						<img src="https://res.cloudinary.com/dpip9b6hi/image/upload/v1600913011/wadf/05preview55_erzden.jpg" />
						<br /><br />
						<strong>Plugin in action</strong>
						<img src="https://res.cloudinary.com/dpip9b6hi/image/upload/v1600913012/wadf/06_preview66_lzinvw.jpg" /></p>


					<p><strong> Support Contact </strong><br />
						<strong>Email:</strong> <a href="mailto:wpcodelibrary@gmail.com">wpcodelibrary@gmail.com</a><br />
						<strong>Skype:</strong> wpcodelibrary</p>
					<strong>Website:</strong> <a href="https://wpcodelibrary.com">https://wpcodelibrary.com</a><br />
					<a href="https://codecanyon.net/item/woocommerce-advanced-discounts-and-fees/19009855?s_rank=1" target="_blank"><h4> Download WooCommerce Advanced Discounts and Fees Plugin</h4>
					</a>
			</fieldset>
		</div>

		</div>


		<?php
	}

	/**
	 * Remove welcome screen
	 */
	public function wpd_screen_remove_menus() {
		remove_submenu_page( 'index.php', 'wpd-about' );
	}

	/**
	 * Enqueue front style css.
	 *
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'wpdcss', WPD_PLUGIN_URL . '/assets/css/wpd_custom.css', array(), false, 'all' );
	}

}

new Woo_Payment_Discounts_Admin();
