<?php
/**
 * Class to create the 'About Us' submenu
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpAboutUs' ) ) {
class ewdotpAboutUs {

	public function __construct() {

		add_action( 'wp_ajax_ewd_otp_send_feature_suggestion', array( $this, 'send_feature_suggestion' ) );

		add_action( 'admin_menu', array( $this, 'register_menu_screen' ) );
	}

	/**
	 * Adds About Us submenu page
	 * @since 3.2.0
	 */
	public function register_menu_screen() {
		global $ewd_otp_controller;

		add_submenu_page(
			'ewd-otp-orders',
			esc_html__( 'About Us', 'order-tracking' ),
			esc_html__( 'About Us', 'order-tracking' ),
			$ewd_otp_controller->settings->get_setting( 'access-role' ),
			'ewd-otp-about-us',
			array( $this, 'display_admin_screen' )
		);
	}

	/**
	 * Displays the About Us page
	 * @since 3.2.0
	 */
	public function display_admin_screen() { ?>

		<div class='ewd-otp-about-us-logo'>
			<img src='<?php echo plugins_url( "../assets/img/ewd_new_logo_purple2.png", __FILE__ ); ?>'>
		</div>

		<div class='ewd-otp-about-us-tabs'>

			<ul id='ewd-otp-about-us-tabs-menu'>

				<li class='ewd-otp-about-us-tab-menu-item ewd-otp-tab-selected' data-tab='who_we_are'>
					<?php _e( 'Who We Are', 'order-tracking' ); ?>
				</li>

				<li class='ewd-otp-about-us-tab-menu-item' data-tab='lite_vs_premium'>
					<?php _e( 'Lite vs. Premium vs. Ultimate', 'order-tracking' ); ?>
				</li>

				<li class='ewd-otp-about-us-tab-menu-item' data-tab='getting_started'>
					<?php _e( 'Getting Started', 'order-tracking' ); ?>
				</li>

				<li class='ewd-otp-about-us-tab-menu-item' data-tab='suggest_feature'>
					<?php _e( 'Suggest a Feature', 'order-tracking' ); ?>
				</li>

			</ul>

			<div class='ewd-otp-about-us-tab' data-tab='who_we_are'>

				<p>
					<strong>Founded in 2014, Etoile Web Design is a leading WordPress plugin development company. </strong>
					Privately owned and located in Canada, our growing business is expanding in size and scope. 
					We have more than 50,000 active users across the world, over 2,000,000 total downloads, and our client based is steadily increasing every day. 
					Our reliable WordPress plugins bring a tremendous amount of value to our users by offering them solutions that are designed to be simple to maintain and easy to use. 
					Our plugins, like the <a href='https://www.etoilewebdesign.com/plugins/ultimate-product-catalog/?utm_source=admin_about_us' target='_blank'>Ultimate Product Catalog</a>, <a href='https://www.etoilewebdesign.com/plugins/order-tracking/?utm_source=admin_about_us' target='_blank'>Order Status Tracking</a>, <a href='https://www.etoilewebdesign.com/plugins/ultimate-faq/?utm_source=admin_about_us' target='_blank'>Ultimate FAQs</a> and <a href='https://www.etoilewebdesign.com/plugins/ultimate-reviews/?utm_source=admin_about_us' target='_blank'>Ultimate Reviews</a> are rich in features, highly customizable and responsive. 
					We provide expert support to all of our customers and believe in being a part of their success stories.
				</p>

				<p>
					Our current team consists of web developers, marketing associates, digital designers and product support associates. 
					As a small business, we are able to offer our team flexible work schedules, significant autonomy and a challenging environment where creative people can flourish.
				</p>

			</div>

			<div class='ewd-otp-about-us-tab ewd-otp-hidden' data-tab='lite_vs_premium'>

				<p><?php _e( 'The premium version of the plugin includes a large number of features to enhance the tracking experience for your visitors and order management for you. These include new layout options, locations, custom fields, the ability to let customers submit their own orders and to require payment for orders.', 'order-tracking' ); ?></p>

				<p><?php _e( 'Turn on the included <strong>WooCommerce tracking integration</strong> in the premium version to <strong>automatically create new orders</strong> in our plugin whenever someone checks out via your WooCommerce shop! With this you can provide WooCommerce order updates straight from within this plugin, allowing you to take advantage of the extra features available in our plugin, like custom fields, to provide specific info for your customers.', 'order-tracking' ); ?></p>

				<p><?php _e( 'The premium version also comes with the ability to create <strong>customers and sales reps</strong> in the plugin, and then <strong>assign orders directly to them</strong>. This will make the browsing experience easier for your customers and the order management easier for you and your team.', 'order-tracking' ); ?></p>

				<p><?php _e( 'With the ultimate version you can send SMS notifications to keep your customers up to date on their order statuses.', 'order-tracking' ); ?></p>

				<p><em><?php _e( 'The following table provides a comparison of the lite, premium and ultimate versions.', 'order-tracking' ); ?></em></p>

				<div class='ewd-otp-about-us-premium-table'>
					<div class='ewd-otp-about-us-premium-table-head'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Feature', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Lite Version', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Premium Version', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Ultimate Version', 'order-tracking' ); ?></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Unlimited orders', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Order tracking form', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Create unlimited statuses and assign them orders', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Email verification for orders', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Customizable order and status update notifications', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Add public and private notes to orders', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Order print option', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Date format and timezone settings', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'WooCommerce integration', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Assign orders to customers', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Assign orders to sales reps', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Create locations to associate with orders and statuses', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Advanced layout and styling options', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'PayPal integration for order payment', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Custom fields for orders', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Customer order form', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Google reCAPTCHA for the customer order form', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Export orders to spreadsheet', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'Import for bulk updating or order creation', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-otp-about-us-premium-table-body'>
						<div class='ewd-otp-about-us-premium-table-cell'><?php _e( 'SMS notifications for order and status updates', 'order-tracking' ); ?></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'></div>
						<div class='ewd-otp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
				</div>

				<div class='ewd-otp-about-us-tab-buttons'>
					<?php printf( __( '<a href="%s" target="_blank" class="ewd-otp-about-us-tab-button ewd-otp-about-us-tab-button-purchase-alternate">Buy Premium Version</a>', 'order-tracking' ), 'https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1&utm_source=admin_about_us' ); ?>
					<?php printf( __( '<a href="%s" target="_blank" class="ewd-otp-about-us-tab-button ewd-otp-about-us-tab-button-purchase">Buy Ultimate Version</a>', 'order-tracking' ), 'https://www.etoilewebdesign.com/license-payment/?Selected=OTPU&Quantity=12&utm_source=admin_about_us' ); ?>
				</div>
				
			</div>

			<div class='ewd-otp-about-us-tab ewd-otp-hidden' data-tab='getting_started'>

				<p><?php _e( 'The walk-though that ran when you first activated the plugin offers a quick way to get started with setting it up. If you would like to run through it again, just click the button below', 'order-tracking' ); ?></p>

				<?php printf( __( '<a href="%s" class="ewd-otp-about-us-tab-button ewd-otp-about-us-tab-button-walkthrough">Re-Run Walk-Through</a>', 'order-tracking' ), admin_url( '?page=ewd-otp-getting-started' ) ); ?>

				<p><?php _e( 'We also have a series of video tutorials that cover the available settings as well as key features of the plugin.', 'order-tracking' ); ?></p>

				<?php printf( __( '<a href="%s" target="_blank" class="ewd-otp-about-us-tab-button ewd-otp-about-us-tab-button-youtube">YouTube Playlist</a>', 'order-tracking' ), 'https://www.youtube.com/playlist?list=PLEndQUuhlvSqa6Txwj1-Ohw8Bj90CIRl0' ); ?>

				
			</div>

			<div class='ewd-otp-about-us-tab ewd-otp-hidden' data-tab='suggest_feature'>

				<div class='ewd-otp-about-us-feature-suggestion'>

					<p><?php _e( 'You can use the form below to let us know about a feature suggestion you might have.', 'order-tracking' ); ?></p>

					<textarea placeholder="<?php _e( 'Please describe your feature idea...', 'order-tracking' ); ?>"></textarea>
					
					<br>
					
					<input type="email" name="feature_suggestion_email_address" placeholder="<?php _e( 'Email Address', 'order-tracking' ); ?>">
				
				</div>
				
				<div class='ewd-otp-about-us-tab-button ewd-otp-about-us-send-feature-suggestion'>Send Feature Suggestion</div>
				
			</div>

		</div>

	<?php }

	/**
	 * Sends the feature suggestions submitted via the About Us page
	 * @since 3.2.0
	 */
	public function send_feature_suggestion() {
		global $ewd_otp_controller;
		
		if (
			! check_ajax_referer( 'ewd-otp-admin-js', 'nonce' ) 
			|| 
			! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdotpHelper::admin_nopriv_ajax();
		}

		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
	    $feedback = sanitize_text_field( $_POST['feature_suggestion'] );
		$feedback .= '<br /><br />Email Address: ';
	  	$feedback .=  sanitize_email( $_POST['email_address'] );
	
	  	wp_mail( 'contact@etoilewebdesign.com', 'OTP Feature Suggestion', $feedback, $headers );
	
	  	die();
	} 

}
} // endif;