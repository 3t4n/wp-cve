<?php
/**
 * Class to create the 'About Us' submenu
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdulbAboutUs' ) ) {
class ewdulbAboutUs {

	public function __construct() {

		add_action( 'wp_ajax_ewd_ulb_send_feature_suggestion', array( $this, 'send_feature_suggestion' ) );

		add_action( 'admin_menu', array( $this, 'register_menu_screen' ), 11 );
	}

	/**
	 * Adds About Us submenu page
	 * @since 2.2.0
	 */
	public function register_menu_screen() {
		global $ewd_ulb_controller;

		add_submenu_page(
			'ulb-settings', 
			esc_html__( 'About Us', 'ultimate-lightbox' ),
			esc_html__( 'About Us', 'ultimate-lightbox' ),
			'manage_options',
			'ewd-ulb-about-us',
			array( $this, 'display_admin_screen' )
		);
	}

	/**
	 * Displays the About Us page
	 * @since 2.2.0
	 */
	public function display_admin_screen() { ?>

		<div class='ewd-ulb-about-us-logo'>
			<img src='<?php echo plugins_url( "../assets/img/ewd_new_logo_purple2.png", __FILE__ ); ?>'>
		</div>

		<div class='ewd-ulb-about-us-tabs'>

			<ul id='ewd-ulb-about-us-tabs-menu'>

				<li class='ewd-ulb-about-us-tab-menu-item ewd-ulb-tab-selected' data-tab='who_we_are'>
					<?php _e( 'Who We Are', 'ultimate-lightbox' ); ?>
				</li>

				<li class='ewd-ulb-about-us-tab-menu-item' data-tab='getting_started'>
					<?php _e( 'Getting Started', 'ultimate-lightbox' ); ?>
				</li>

				<li class='ewd-ulb-about-us-tab-menu-item' data-tab='suggest_feature'>
					<?php _e( 'Suggest a Feature', 'ultimate-lightbox' ); ?>
				</li>

			</ul>

			<div class='ewd-ulb-about-us-tab' data-tab='who_we_are'>

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

			<div class='ewd-ulb-about-us-tab ewd-ulb-hidden' data-tab='getting_started'>

				<p><?php _e( 'The walk-though that ran when you first activated the plugin offers a quick way to get started with setting it up. If you would like to run through it again, just click the button below', 'ultimate-lightbox' ); ?></p>

				<?php printf( __( '<a href="%s" class="ewd-ulb-about-us-tab-button ewd-ulb-about-us-tab-button-walkthrough">Re-Run Walk-Through</a>', 'ultimate-lightbox' ), admin_url( '?page=ulb-getting-started' ) ); ?>

				<p><?php _e( 'We also have a series of video tutorials that cover the available settings as well as key features of the plugin.', 'ultimate-lightbox' ); ?></p>

				<?php printf( __( '<a href="%s" target="_blank" class="ewd-ulb-about-us-tab-button ewd-ulb-about-us-tab-button-youtube">YouTube Playlist</a>', 'ultimate-lightbox' ), 'https://www.youtube.com/playlist?list=PLEndQUuhlvSpWdeRl6sfZ-QbO1Oc5CR4f' ); ?>

				
			</div>

			<div class='ewd-ulb-about-us-tab ewd-ulb-hidden' data-tab='suggest_feature'>

				<div class='ewd-ulb-about-us-feature-suggestion'>

					<p><?php _e( 'You can use the form below to let us know about a feature suggestion you might have.', 'ultimate-lightbox' ); ?></p>

					<textarea placeholder="<?php _e( 'Please describe your feature idea...', 'ultimate-lightbox' ); ?>"></textarea>
					
					<br>
					
					<input type="email" name="feature_suggestion_email_address" placeholder="<?php _e( 'Email Address', 'ultimate-lightbox' ); ?>">
				
				</div>
				
				<div class='ewd-ulb-about-us-tab-button ewd-ulb-about-us-send-feature-suggestion'>Send Feature Suggestion</div>
				
			</div>

		</div>

	<?php }

	/**
	 * Sends the feature suggestions submitted via the About Us page
	 * @since 2.2.0
	 */
	public function send_feature_suggestion() {
		global $ewd_ulb_controller;
		
		if (
			! check_ajax_referer( 'ewd-ulb-admin-js', 'nonce' ) 
			|| 
			! current_user_can( 'manage_options' )
		) {
			ewdulbHelper::admin_nopriv_ajax();
		}

		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
	    $feedback = sanitize_text_field( $_POST['feature_suggestion'] );
		$feedback .= '<br /><br />Email Address: ';
	  	$feedback .=  sanitize_email( $_POST['email_address'] );
	
	  	wp_mail( 'contact@etoilewebdesign.com', 'ULB Feature Suggestion', $feedback, $headers );
	
	  	die();
	} 

}
} // endif;