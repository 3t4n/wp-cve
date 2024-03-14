<?php
/**
 * Class to create the 'About Us' submenu
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdufaqAboutUs' ) ) {
class ewdufaqAboutUs {

	public function __construct() {

		add_action( 'wp_ajax_ewd_ufaq_send_feature_suggestion', array( $this, 'send_feature_suggestion' ) );

		add_action( 'admin_menu', array( $this, 'register_menu_screen' ) );
	}

	/**
	 * Adds About Us submenu page
	 * @since 2.2.0
	 */
	public function register_menu_screen() {
		global $ewd_ufaq_controller;

		add_submenu_page(
			'edit.php?post_type=ufaq',
			esc_html__( 'About Us', 'ultimate-faqs' ),
			esc_html__( 'About Us', 'ultimate-faqs' ),
			$ewd_ufaq_controller->settings->get_setting( 'access-role' ),
			'ewd-ufaq-about-us',
			array( $this, 'display_admin_screen' )
		);
	}

	/**
	 * Displays the About Us page
	 * @since 2.2.0
	 */
	public function display_admin_screen() { ?>

		<div class='ewd-ufaq-about-us-logo'>
			<img src='<?php echo plugins_url( "../assets/img/ewd_new_logo_purple2.png", __FILE__ ); ?>'>
		</div>

		<div class='ewd-ufaq-about-us-tabs'>

			<ul id='ewd-ufaq-about-us-tabs-menu'>

				<li class='ewd-ufaq-about-us-tab-menu-item ewd-ufaq-tab-selected' data-tab='who_we_are'>
					<?php _e( 'Who We Are', 'ultimate-faqs' ); ?>
				</li>

				<li class='ewd-ufaq-about-us-tab-menu-item' data-tab='lite_vs_premium'>
					<?php _e( 'Lite vs. Premium', 'ultimate-faqs' ); ?>
				</li>

				<li class='ewd-ufaq-about-us-tab-menu-item' data-tab='getting_started'>
					<?php _e( 'Getting Started', 'ultimate-faqs' ); ?>
				</li>

				<li class='ewd-ufaq-about-us-tab-menu-item' data-tab='suggest_feature'>
					<?php _e( 'Suggest a Feature', 'ultimate-faqs' ); ?>
				</li>

			</ul>

			<div class='ewd-ufaq-about-us-tab' data-tab='who_we_are'>

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

			<div class='ewd-ufaq-about-us-tab ewd-ufaq-hidden' data-tab='lite_vs_premium'>

				<p><?php _e( 'The premium version of the Ultimate FAQ plugin includes a large number of features to extend the functionality of the plugin and offer your visitors the best possible FAQ experience. This includes advanced layout, styling and labelling options, drag and drop re-ordering, export and import functionality and an FAQ submission form, to let your visitors suggest their own FAQs for your site.', 'ultimate-faqs' ); ?></p>

				<p><?php _e( 'In order to make it as easy as possible for your visitors to find the information they are looking for, the premium plugin comes with a built-in <strong>FAQ search form</strong>, which uses asynchronous requests to <strong>search your whole database of FAQs in real time</strong>, without having to reload the page, and includes options to auto-complete the search, highlight search results and display all FAQs on initial page load.', 'ultimate-faqs' ); ?></p>

				<p><?php _e( 'The premium version also comes with <strong>WooCommerce integration</strong>, so you can easily <strong>add FAQs tab to your product page</strong>, with options to either automatically add FAQs, by creating an FAQ category that matches the name of a WooCommerce product or category, or to <strong>manage FAQs directly from the product edit screen</strong>.', 'ultimate-faqs' ); ?></p>

				<p><em><?php _e( 'The following table provides a comparison of the lite and premium versions.', 'ultimate-faqs' ); ?></em></p>

				<div class='ewd-ufaq-about-us-premium-table'>
					<div class='ewd-ufaq-about-us-premium-table-head'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Feature', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Lite Version', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Premium Version', 'ultimate-faqs' ); ?></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Unlimited FAQs', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'FAQ Gutenberg blocks', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Accordion layout', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'FAQ structured data', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Sorting and ordering options', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Translation ready', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Style with custom CSS', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'FAQ statistics', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Comments on FAQs', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Advanced FAQ layouts', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'FAQ Search', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'WooCommerce FAQs', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'FAQ submit form', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Drag and drop FAQ ordering', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Add custom fields to your FAQs', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Icon and animation options', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Pretty permalinks for FAQs and categories', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Advanced styling options', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Export FAQs to PDF or spreadsheet', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Import FAQs', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Share FAQs on social media', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Advanced styling options', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
					<div class='ewd-ufaq-about-us-premium-table-body'>
						<div class='ewd-ufaq-about-us-premium-table-cell'><?php _e( 'Labelling options', 'ultimate-faqs' ); ?></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'></div>
						<div class='ewd-ufaq-about-us-premium-table-cell'><img src="<?php echo plugins_url( '../assets/img/dash-asset-checkmark.png', __FILE__ ); ?>"></div>
					</div>
				</div>

				<?php printf( __( '<a href="%s" target="_blank" class="ewd-ufaq-about-us-tab-button ewd-ufaq-about-us-tab-button-purchase">Buy Premium Version</a>', 'ultimate-faqs' ), 'https://www.etoilewebdesign.com/license-payment/?Selected=UFAQ&Quantity=1&utm_source=admin_about_us' ); ?>
				
			</div>

			<div class='ewd-ufaq-about-us-tab ewd-ufaq-hidden' data-tab='getting_started'>

				<p><?php _e( 'The walk-though that ran when you first activated the plugin offers a quick way to get started with setting it up. If you would like to run through it again, just click the button below', 'ultimate-faqs' ); ?></p>

				<?php printf( __( '<a href="%s" class="ewd-ufaq-about-us-tab-button ewd-ufaq-about-us-tab-button-walkthrough">Re-Run Walk-Through</a>', 'ultimate-faqs' ), admin_url( '?page=ewd-ufaq-getting-started' ) ); ?>

				<p><?php _e( 'We also have a series of video tutorials that cover the available settings as well as key features of the plugin.', 'ultimate-faqs' ); ?></p>

				<?php printf( __( '<a href="%s" target="_blank" class="ewd-ufaq-about-us-tab-button ewd-ufaq-about-us-tab-button-youtube">YouTube Playlist</a>', 'ultimate-faqs' ), 'https://www.youtube.com/watch?v=ULAq7e-JyL8&list=PLEndQUuhlvSrNdfu5FKa1uGHsaKZxgdWt&ab_channel=EtoileWebDesign' ); ?>

				
			</div>

			<div class='ewd-ufaq-about-us-tab ewd-ufaq-hidden' data-tab='suggest_feature'>

				<div class='ewd-ufaq-about-us-feature-suggestion'>

					<p><?php _e( 'You can use the form below to let us know about a feature suggestion you might have.', 'ultimate-faqs' ); ?></p>

					<textarea placeholder="<?php _e( 'Please describe your feature idea...', 'ultimate-faqs' ); ?>"></textarea>
					
					<br>
					
					<input type="email" name="feature_suggestion_email_address" placeholder="<?php _e( 'Email Address', 'ultimate-faqs' ); ?>">
				
				</div>
				
				<div class='ewd-ufaq-about-us-tab-button ewd-ufaq-about-us-send-feature-suggestion'>Send Feature Suggestion</div>
				
			</div>

		</div>

	<?php }

	/**
	 * Sends the feature suggestions submitted via the About Us page
	 * @since 2.2.0
	 */
	public function send_feature_suggestion() {
		global $ewd_ufaq_controller;
		
		if (
			! check_ajax_referer( 'ewd-ufaq-admin-js', 'nonce' ) 
			|| 
			! current_user_can( $ewd_ufaq_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdufaqHelper::admin_nopriv_ajax();
		}

		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
	    $feedback = sanitize_text_field( $_POST['feature_suggestion'] );
		$feedback .= '<br /><br />Email Address: ';
	  	$feedback .=  sanitize_email( $_POST['email_address'] );
	
	  	wp_mail( 'contact@etoilewebdesign.com', 'UFAQ Feature Suggestion', $feedback, $headers );
	
	  	die();
	} 

}
} // endif;