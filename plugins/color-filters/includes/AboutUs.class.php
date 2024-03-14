<?php
/**
 * Class to create the 'About Us' submenu
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewduwcfAboutUs' ) ) {
class ewduwcfAboutUs {

	public function __construct() {

		add_action( 'wp_ajax_ewd_uwcf_send_feature_suggestion', array( $this, 'send_feature_suggestion' ) );

		add_action( 'admin_menu', array( $this, 'register_menu_screen' ) );
	}

	/**
	 * Adds About Us submenu page
	 * @since 3.2.0
	 */
	public function register_menu_screen() {
		global $ewd_uwcf_controller;

		add_submenu_page(
			'ewd-uwcf-dashboard',
			esc_html__( 'About Us', 'color-filters' ),
			esc_html__( 'About Us', 'color-filters' ),
			$ewd_uwcf_controller->settings->get_setting( 'access-role' ),
			'ewd-uwcf-about-us',
			array( $this, 'display_admin_screen' )
		);
	}

	/**
	 * Displays the About Us page
	 * @since 3.2.0
	 */
	public function display_admin_screen() { ?>

		<div class='ewd-uwcf-about-us-logo'>
			<img src='<?php echo plugins_url( "../assets/img/ewd_new_logo_purple2.png", __FILE__ ); ?>'>
		</div>

		<div class='ewd-uwcf-about-us-tabs'>

			<ul id='ewd-uwcf-about-us-tabs-menu'>

				<li class='ewd-uwcf-about-us-tab-menu-item ewd-uwcf-tab-selected' data-tab='who_we_are'>
					<?php _e( 'Who We Are', 'color-filters' ); ?>
				</li>

				<li class='ewd-uwcf-about-us-tab-menu-item' data-tab='lite_vs_premium'>
					<?php _e( 'Lite vs. Premium', 'color-filters' ); ?>
				</li>

				<li class='ewd-uwcf-about-us-tab-menu-item' data-tab='getting_started'>
					<?php _e( 'Getting Started', 'color-filters' ); ?>
				</li>

				<li class='ewd-uwcf-about-us-tab-menu-item' data-tab='suggest_feature'>
					<?php _e( 'Suggest a Feature', 'color-filters' ); ?>
				</li>

			</ul>

			<div class='ewd-uwcf-about-us-tab' data-tab='who_we_are'>

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

			<div class='ewd-uwcf-about-us-tab ewd-uwcf-hidden' data-tab='lite_vs_premium'>

				<p><?php _e( 'The premium version of Ultimate WooCommerce Filters comes with a host of advanced features that you let you further customize the filtering experience. These include advanced attribute options, separate layout options and functions for each type of filter, an option to display WooCommerce filter attributes under the thumbnails on your shop page, and more, giving you many ways to configure your WooCommerce filters just how you need.', 'color-filters' ); ?></p>

				<p><em><?php _e( 'The following table provides a comparison of the lite and premium versions.', 'color-filters' ); ?></em></p>

				<div class='ewd-uwcf-about-us-premium-table'>
					<div class='ewd-uwcf-about-us-premium-table-head'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Feature', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Lite Version', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Premium Version', 'color-filters' ); ?></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Filter products by color', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Filter products by size', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Filter products by categories, tags or custom attributes', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Gutenberg block, shortcode and widget to display filtering area', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Product search', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Table format option for shop page', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Swatch and tile options for displaying color filters', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Separate layout options for each filter type', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Display WooCommerce attributes under shop thumbnails', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Price slider filter', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Display and functionality options for each filter type', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Advanced styling options', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-uwcf-about-us-premium-table-body'>
						<div class='ewd-uwcf-about-us-premium-table-cell'><?php _e( 'Labelling options', 'color-filters' ); ?></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'></div>
						<div class='ewd-uwcf-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
				</div>

				<?php printf( __( '<a href="%s" target="_blank" class="ewd-uwcf-about-us-tab-button ewd-uwcf-about-us-tab-button-purchase">Buy Premium Version</a>', 'color-filters' ), 'https://www.etoilewebdesign.com/license-payment/?Selected=UWCF&Quantity=1&utm_source=admin_about_us' ); ?>
				
			</div>

			<div class='ewd-uwcf-about-us-tab ewd-uwcf-hidden' data-tab='getting_started'>

				<p><?php _e( 'The walk-though that ran when you first activated the plugin offers a quick way to get started with setting it up. If you would like to run through it again, just click the button below', 'color-filters' ); ?></p>

				<?php printf( __( '<a href="%s" class="ewd-uwcf-about-us-tab-button ewd-uwcf-about-us-tab-button-walkthrough">Re-Run Walk-Through</a>', 'color-filters' ), admin_url( '?page=ewd-uwcf-getting-started' ) ); ?>
				
			</div>

			<div class='ewd-uwcf-about-us-tab ewd-uwcf-hidden' data-tab='suggest_feature'>

				<div class='ewd-uwcf-about-us-feature-suggestion'>

					<p><?php _e( 'You can use the form below to let us know about a feature suggestion you might have.', 'color-filters' ); ?></p>

					<textarea placeholder="<?php _e( 'Please describe your feature idea...', 'color-filters' ); ?>"></textarea>
					
					<br>
					
					<input type="email" name="feature_suggestion_email_address" placeholder="<?php _e( 'Email Address', 'color-filters' ); ?>">
				
				</div>
				
				<div class='ewd-uwcf-about-us-tab-button ewd-uwcf-about-us-send-feature-suggestion'>Send Feature Suggestion</div>
				
			</div>

		</div>

	<?php }

	/**
	 * Sends the feature suggestions submitted via the About Us page
	 * @since 3.2.0
	 */
	public function send_feature_suggestion() {
		global $ewd_uwcf_controller;
		
		if (
			! check_ajax_referer( 'ewd-uwcf-admin-js', 'nonce' ) 
			|| 
			! current_user_can( $ewd_uwcf_controller->settings->get_setting( 'access-role' ) )
		) {
			ewduwcfHelper::admin_nopriv_ajax();
		}

		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
	    $feedback = sanitize_text_field( $_POST['feature_suggestion'] );
		$feedback .= '<br /><br />Email Address: ';
	  	$feedback .=  sanitize_email( $_POST['email_address'] );
	
	  	wp_mail( 'contact@etoilewebdesign.com', 'UWCF Feature Suggestion', $feedback, $headers );
	
	  	die();
	} 

}
} // endif;