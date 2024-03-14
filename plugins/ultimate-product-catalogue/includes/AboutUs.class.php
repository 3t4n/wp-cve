<?php
/**
 * Class to create the 'About Us' submenu
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdupcpAboutUs' ) ) {
class ewdupcpAboutUs {

	public function __construct() {

		add_action( 'wp_ajax_ewd_upcp_send_feature_suggestion', array( $this, 'send_feature_suggestion' ) );

		add_action( 'admin_menu', array( $this, 'register_menu_screen' ) );
	}

	/**
	 * Adds About Us submenu page
	 * @since 5.2.0
	 */
	public function register_menu_screen() {
		global $ewd_upcp_controller;

		add_submenu_page(
			'edit.php?post_type=upcp_product', 
			esc_html__( 'About Us', 'ultimate-product-catalogue' ),
			esc_html__( 'About Us', 'ultimate-product-catalogue' ),
			$ewd_upcp_controller->settings->get_setting( 'access-role' ),
			'ewd-upcp-about-us',
			array( $this, 'display_admin_screen' )
		);
	}

	/**
	 * Displays the About Us page
	 * @since 5.2.0
	 */
	public function display_admin_screen() { ?>

		<div class='ewd-upcp-about-us-logo'>
			<img src='<?php echo plugins_url( "../assets/img/ewd_new_logo_purple2.png", __FILE__ ); ?>'>
		</div>

		<div class='ewd-upcp-about-us-tabs'>

			<ul id='ewd-upcp-about-us-tabs-menu'>

				<li class='ewd-upcp-about-us-tab-menu-item ewd-upcp-tab-selected' data-tab='who_we_are'>
					<?php _e( 'Who We Are', 'ultimate-product-catalogue' ); ?>
				</li>

				<li class='ewd-upcp-about-us-tab-menu-item' data-tab='lite_vs_premium'>
					<?php _e( 'Lite vs. Premium', 'ultimate-product-catalogue' ); ?>
				</li>

				<li class='ewd-upcp-about-us-tab-menu-item' data-tab='getting_started'>
					<?php _e( 'Getting Started', 'ultimate-product-catalogue' ); ?>
				</li>

				<li class='ewd-upcp-about-us-tab-menu-item' data-tab='suggest_feature'>
					<?php _e( 'Suggest a Feature', 'ultimate-product-catalogue' ); ?>
				</li>

			</ul>

			<div class='ewd-upcp-about-us-tab' data-tab='who_we_are'>

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

			<div class='ewd-upcp-about-us-tab ewd-upcp-hidden' data-tab='lite_vs_premium'>

				<p><?php _e( 'The premium version of the plugin includes a large number of features, such as the ability to add over 100 products, additional product images, custom fields for sorting within your product catalog, custom product pages, a product inquiry cart, multiple layout options, product import/export and more!', 'ultimate-product-catalogue' ); ?></p>

				<p><?php _e( 'Turn on the included <strong>WooCommerce product integration</strong> to sync all your products between the product catalog and your WooCommerce shop, and also allow your customers to <strong>check out directly from the catalog</strong>.', 'ultimate-product-catalogue' ); ?></p>

				<p><em><?php _e( 'The following table provides a comparison of the lite and premium versions.', 'ultimate-product-catalogue' ); ?></em></p>

				<div class='ewd-upcp-about-us-premium-table'>
					<div class='ewd-upcp-about-us-premium-table-head'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Feature', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Lite Version', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Premium Version', 'ultimate-product-catalogue' ); ?></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Powerful asynchronous filtering engine', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Catalog Gutenberg blocks, shortcodes and widgets', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( '3 switchable catalog views', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Product search', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Catalog sorting', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Sale prices', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Unlimited categories and sub-categories', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Catalog overview mode', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Price slider', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Social media sharing', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Unlimited products', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'WooCommerce checkout ingetration', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Advanced layout and styling options', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Responsive tabbed product page layouts', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Custom fields for filtering and product page use', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Product inquiry form and cart options', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Product reviews', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Product FAQs', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'SEO-friendly pretty permalinks', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Product comparison feature', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Change control type for any filtering option', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-upcp-about-us-premium-table-body'>
						<div class='ewd-upcp-about-us-premium-table-cell'><?php _e( 'Advanced labelling options', 'ultimate-product-catalogue' ); ?></div>
						<div class='ewd-upcp-about-us-premium-table-cell'></div>
						<div class='ewd-upcp-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
				</div>

				<?php printf( __( '<a href="%s" target="_blank" class="ewd-upcp-about-us-tab-button ewd-upcp-about-us-tab-button-purchase">Buy Premium Version</a>', 'ultimate-product-catalogue' ), 'https://www.etoilewebdesign.com/license-payment/?Selected=UPCP&Quantity=1&utm_source=admin_about_us' ); ?>
				
			</div>

			<div class='ewd-upcp-about-us-tab ewd-upcp-hidden' data-tab='getting_started'>

				<p><?php _e( 'The walk-though that ran when you first activated the plugin offers a quick way to get started with setting it up. If you would like to run through it again, just click the button below', 'ultimate-product-catalogue' ); ?></p>

				<?php printf( __( '<a href="%s" class="ewd-upcp-about-us-tab-button ewd-upcp-about-us-tab-button-walkthrough">Re-Run Walk-Through</a>', 'ultimate-product-catalogue' ), admin_url( '?page=ewd-upcp-getting-started' ) ); ?>

				<p><?php _e( 'We also have a series of video tutorials that cover the available settings as well as key features of the plugin.', 'ultimate-product-catalogue' ); ?></p>

				<?php printf( __( '<a href="%s" target="_blank" class="ewd-upcp-about-us-tab-button ewd-upcp-about-us-tab-button-youtube">YouTube Playlist</a>', 'ultimate-product-catalogue' ), 'https://www.youtube.com/playlist?list=PLEndQUuhlvSoTRGeY6nWXbxbhmgepTyLi' ); ?>

				
			</div>

			<div class='ewd-upcp-about-us-tab ewd-upcp-hidden' data-tab='suggest_feature'>

				<div class='ewd-upcp-about-us-feature-suggestion'>

					<p><?php _e( 'You can use the form below to let us know about a feature suggestion you might have.', 'ultimate-product-catalogue' ); ?></p>

					<textarea placeholder="<?php _e( 'Please describe your feature idea...', 'ultimate-product-catalogue' ); ?>"></textarea>
					
					<br>
					
					<input type="email" name="feature_suggestion_email_address" placeholder="<?php _e( 'Email Address', 'ultimate-product-catalogue' ); ?>">
				
				</div>
				
				<div class='ewd-upcp-about-us-tab-button ewd-upcp-about-us-send-feature-suggestion'>Send Feature Suggestion</div>
				
			</div>

		</div>

	<?php }

	/**
	 * Sends the feature suggestions submitted via the About Us page
	 * @since 5.2.0
	 */
	public function send_feature_suggestion() {
		global $ewd_upcp_controller;
		
		if (
			! check_ajax_referer( 'ewd-upcp-admin-js', 'nonce' ) 
			|| 
			! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
	    $feedback = sanitize_text_field( $_POST['feature_suggestion'] );
		$feedback .= '<br /><br />Email Address: ';
	  	$feedback .=  sanitize_email( $_POST['email_address'] );
	
	  	wp_mail( 'contact@etoilewebdesign.com', 'UPCP Feature Suggestion', $feedback, $headers );
	
	  	die();
	} 

}
} // endif;