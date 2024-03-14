<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.madebytribe.com
 * @since      1.0.0
 *
 * @package    Caddy
 * @subpackage Caddy/admin/partials
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

$caddy_tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'settings';

$caddy_tabs_name = array(
	'settings' => array(
		'tab_name' => __( 'General Settings', 'caddy' ),
		'tab_icon' => 'dashicons dashicons-admin-generic',
	),
	'styles'   => array(
		'tab_name' => __( 'Styles', 'caddy' ),
		'tab_icon' => 'dashicons dashicons-admin-appearance',
	),
);
/**
 * Filters the caddy tab names.
 *
 * @param array $caddy_tabs_name Caddy tab names.
 *
 * @since 1.3.0
 *
 */
$caddy_tabs = apply_filters( 'caddy_tab_names', $caddy_tabs_name );

if ( isset( $_POST['cc_submit_hidden'] ) && $_POST['cc_submit_hidden'] == 'Y' ) {
	
	// Check if our nonce is set and is valid
	if ( ! isset( $_POST['caddy_settings_nonce'] ) || ! wp_verify_nonce( $_POST['caddy_settings_nonce'], 'caddy-settings-save' ) ) {
		wp_die( __( 'Security check failed.', 'caddy' ) );
	}

	// UPDATE SETTINGS OPTIONS
	if ( 'settings' === $caddy_tab ) {
		$cc_product_recommendation = isset( $_POST['cc_product_recommendation'] ) ? sanitize_text_field( $_POST['cc_product_recommendation'] ) : 'disabled';
		update_option( 'cc_product_recommendation', $cc_product_recommendation );

		$cc_free_shipping_amount = ! empty( $_POST['cc_free_shipping_amount'] ) ? intval( $_POST['cc_free_shipping_amount'] ) : '';
		update_option( 'cc_free_shipping_amount', $cc_free_shipping_amount );

		$cc_shipping_country = ! empty( $_POST['cc_shipping_country'] ) ? sanitize_text_field( $_POST['cc_shipping_country'] ) : '';
		update_option( 'cc_shipping_country', $cc_shipping_country );

		$cc_disable_branding = ! empty( $_POST['cc_disable_branding'] ) ? sanitize_text_field( $_POST['cc_disable_branding'] ) : 'disabled';
		update_option( 'cc_disable_branding', $cc_disable_branding );
		
		$cc_free_shipping_tax = ! empty( $_POST['cc_free_shipping_tax'] ) ? sanitize_text_field( $_POST['cc_free_shipping_tax'] ) : 'disabled';
		update_option( 'cc_free_shipping_tax', $cc_free_shipping_tax );

		$cc_affiliate_id = ! empty( $_POST['cc_affiliate_id'] ) ? sanitize_text_field( $_POST['cc_affiliate_id'] ) : '';
		update_option( 'cc_affiliate_id', $cc_affiliate_id );
		
		$cc_menu_cart_widget = ! empty( $_POST['cc_menu_cart_widget'] ) ? sanitize_text_field( $_POST['cc_menu_cart_widget'] ) : '';
		update_option( 'cc_menu_cart_widget', $cc_menu_cart_widget );
		
		$cc_menu_saves_widget = ! empty( $_POST['cc_menu_saves_widget'] ) ? sanitize_text_field( $_POST['cc_menu_saves_widget'] ) : '';
		update_option( 'cc_menu_saves_widget', $cc_menu_saves_widget );

	} elseif ( 'styles' === $caddy_tab ) {

		$cc_custom_css = ! empty( $_POST['cc_custom_css'] ) ? sanitize_textarea_field( $_POST['cc_custom_css'] ) : '';
		update_option( 'cc_custom_css', $cc_custom_css );

	}
	?>
	<div class="updated">
		<p>
			<strong><?php echo esc_html( __( 'Settings saved.', 'caddy' ) ); ?></strong> <?php echo esc_html( __( 'If you\'re using any caching plugins please be sure to ', 'caddy' ) ); ?>
			<strong><?php echo esc_html( __( 'clear your cache. ', 'caddy' ) ); ?></strong></p>
	</div>
<?php } ?>

<div class="wrap">
	<div class="cc-header-wrap">
		<img src="<?php echo plugin_dir_url( __DIR__ ) ?>img/caddy-logo.svg" width="110" height="32" class="cc-logo">
		<div class="cc-version"><?php echo CADDY_VERSION; ?></div>
		<?php do_action( 'caddy_header_links' ); ?>
	</div>

	<h2 class="nav-tab-wrapper">
		<?php
		foreach ( $caddy_tabs as $key => $value ) {
			$active_tab_class = ( $key == $caddy_tab ) ? ' nav-tab-active' : '';
			?>
			<a class="nav-tab<?php echo $active_tab_class; ?>" href="?page=caddy&amp;tab=<?php echo $key; ?>"><i class="<?php echo $value['tab_icon']; ?>"></i>&nbsp;<?php echo
				$value['tab_name']; ?></a>
		<?php } ?>
	</h2>
	<?php
	$cc_dismiss_welcome_notice = get_option( 'cc_dismiss_welcome_notice', true );
	if ( 'yes' !== $cc_dismiss_welcome_notice ) {
		?>
		<?php $cc_user_info = get_userdata( get_current_user_id() );
		$cc_first_name      = $cc_user_info->first_name; ?>
		<div class="notice cc-welcome-notice is-dismissible" data-cc-dismissible-notice="welcome">
			<img src="<?php echo plugin_dir_url( __DIR__ ) ?>img/caddy-welcome.svg" width="150" height="150" class="cc-celebrate animated">
			<div class="cc-notice-text">
				<h3 class="cc-notice-heading"><?php _e( 'Woohoo ', 'caddy' ); ?><?php echo "$cc_first_name"; ?><?php _e( '! You\'ve just upgraded your shopping cart.', 'caddy' ); ?></h3>
				<?php
				echo sprintf(
					'<p>%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s <a href="%5$s" target="_blank">%6$s</a>. %7$s <a href="%8$s" target="_blank">%9$s</a> %10$s. <i>%11$s</i></p>',
					esc_html__( 'To get started, we recommend reading through our', 'caddy' ),
					esc_url( 'https://usecaddy.com/docs/?utm_source=welcome-notice&amp;utm_medium=plugin&amp;utm_campaign=plugin-links' ),
					esc_html__( 'getting started', 'caddy' ),
					esc_html__( 'help docs. For tips on growing your store, check out and subscribe to our', 'caddy' ),
					esc_url( 'https://usecaddy.com/blog/?utm_source=welcome-notice&amp;utm_medium=plugin&amp;utm_campaign=plugin-links' ),
					esc_html__( 'blog', 'caddy' ),
					esc_html__( 'If you have any questions or need help, don\'t hesitate to', 'caddy' ),
					esc_url( 'https://usecaddy.com/contact-us/?utm_source=welcome-notice&amp;utm_medium=plugin&amp;utm_campaign=plugin-links' ),
					esc_html__( 'reach out', 'caddy' ),
					esc_html__( 'to us', 'caddy' ),
					esc_html__( '- The Caddy Crew', 'caddy' )
				);
				?>
			</div>
		</div>
	<?php } ?>

	<?php
	$current_user_id               = get_current_user_id();
	$cc_dismiss_user_optin_notice  = get_user_meta( $current_user_id, 'cc_dismiss_user_optin_notice', true );
	if ( 'yes' !== $cc_dismiss_user_optin_notice && ! class_exists( 'Caddy_Premium' ) ) {
		?>
		<div class="notice cc-optin-notice is-dismissible" data-cc-dismissible-notice="optin">
			<div class="cc-optin-left"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/caddy-trophy.svg'; ?>" width="150" height="150" alt="Join our VIP email list"></div>
			<div class="cc-optin-right">
				<h2><?php echo esc_html( __( 'Join our email list and get 40% off a premium license', 'caddy' ) ); ?></h2>
				<p><?php echo esc_html( __( 'Get the latest tips on how to grow your store\'s sales and save on Caddy Premium. Unsubscribe at anytime. ' ) ); ?></p>
				<form id="caddy-email-signup" class="cc-klaviyo-default-styling" action="//manage.kmail-lists.com/subscriptions/subscribe"
				      data-ajax-submit="//manage.kmail-lists.com/ajax/subscriptions/subscribe" method="GET" target="_blank" validate="validate">
					<input type="hidden" name="g" value="YctmsM">
					<input type="hidden" name="$fields" value="$consent">
					<input type="hidden" name="$list_fields" value="$consent">
					<div class="cc-klaviyo-field-group">
						<input class="" type="text" value="" name="first_name" id="k_id_first_name" placeholder="Your First Name">
						<input class="" type="email" value="" name="email" id="k_id_email" placeholder="Your email" required>
						<div class="cc-klaviyo-field-group cc-klaviyo-form-actions cc-klaviyo-opt-in">
							<input type="checkbox" name="$consent" id="cc-consent-email" value="email" required>
							<label for="cc-consent-email">
								<?php
								echo sprintf(
									'%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s <a href="%5$s" target="_blank">%6$s</a>.',
									esc_html__( 'I agree with the ', 'caddy' ),
									esc_url( 'https://www.usecaddy.com/terms-and-conditions/' ),
									esc_html__( 'Terms', 'caddy' ),
									esc_html__( ' &amp; ', 'caddy' ),
									esc_url( 'https://www.usecaddy.com/privacy-policy/' ),
									esc_html__( 'Privacy Policy', 'caddy' )
								);
								?>
							</label>
						</div>
					</div>
					<div class="cc-klaviyo-messages">
						<div class="success_message" style="display:none;"></div>
						<div class="error_message" style="display:none;"></div>
					</div>
					<div class="cc-klaviyo-form-actions">
						<button type="submit" class="cc-klaviyo-submit-button button button-primary"><?php echo esc_html( __( 'Subscribe', 'caddy' ) ); ?></button>
					</div>
				</form>
				<script type="text/javascript" src="//www.klaviyo.com/media/js/public/klaviyo_subscribe.js"></script>
				<script type="text/javascript">
									KlaviyoSubscribe.attachToForms( '#caddy-email-signup', {
										hide_form_on_success: true,
										success_message: "Thank you for signing up! Your special offer is on its way!",
										extra_properties: {
											$source: 'CaddyPluginSignup',
											Website: '<?php echo get_site_url();?>',
										}
									} );
				</script>
			</div>
		</div>
	<?php } ?>

	<?php do_action( 'cc_before_setting_options' ); ?>
	<div class="cc-settings-wrap">
		<form name="caddy-form" id="caddy-form" method="post" action="">
			<?php wp_nonce_field('caddy-settings-save', 'caddy_settings_nonce'); ?>
			<input type="hidden" name="cc_submit_hidden" value="Y">
			<div class="cc-settings-container">
				<?php
				//Include tab screen files
				do_action( 'caddy_admin_tab_screen' );
				?>
			</div>
			<p class="submit cc-primary-save">
				<input type="submit" name="Submit" class="button-primary cc-primary-save-btn" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</p>
		</form>
		<div class="cc-notices-container">

			<?php do_action( 'cc_upgrade_to_premium' ); ?>
			<div class="cc-box cc-box-cta cc-woo-course">
				<span class="dashicons dashicons-chart-area"></span>
				<h3><?php echo esc_html( __( 'Ready to scale ', 'caddy' ) ); ?><span
							class="cc-underline"><?php echo get_bloginfo( 'name' ); ?></span><?php echo esc_html( __( ' conversions to the next level ', 'caddy' ) ); ?><?php echo get_user_meta( get_current_user_id(), 'first_name', true ); ?><?php echo esc_html( __( '?', 'caddy' ) ); ?>
					<br><span class="cc-sub-heading"><?php echo esc_html( __( 'TAKE THE FREE 7-DAY COURSE', 'caddy' ) ); ?></span></h3>
				<p><?php echo esc_html( __( 'Learn the latest, strategies, tactics & tools you need to improve conversions and increase revenue for your store.', 'caddy' ) ); ?></p>
				<?php
				echo sprintf(
					'<a href="%1$s" target="_blank" class="button-primary">%2$s <span class="dashicons dashicons-arrow-right-alt"></span></a>',
					esc_url( 'https://usecaddy.com/woocommerce-bootcamp/?utm_source=caddy-plugin&amp;utm_medium=plugin&amp;utm_campaign=plugin-links' ),
					esc_html( __( 'Join Free', 'caddy' ) )
				);
				?>
			</div>
			<div class="cc-box cc-links">
				<h3><?php echo esc_html( __( 'More Premium Plugins', 'caddy' ) ); ?></h3>
				<ul class="cc-product-links">
					<li>
						<img src="<?php echo plugin_dir_url( __DIR__ ) ?>img/klaviyo-logo.jpg" width="40" height="40" />
						<div>
							<a href="https://www.madebytribe.com/products/klaviyo-toolkit/?utm_source=caddy-plugin&amp;utm_medium=plugin&amp;utm_campaign=caddy-links"
							   target="_blank"><?php echo esc_html( __( 'Klaviyo ToolKit', 'caddy' ) ); ?></a>
							<p><?php echo esc_html( __( 'Improve your WooCommerce email marketing with Klaviyo.', 'caddy' ) ); ?></p>
						</div>
					</li>
					<li>
						<img src="<?php echo plugin_dir_url( __DIR__ ) ?>img/rk-logo-avatar.svg" width="40" height="40" />
						<div>
							<a href="https://www.getretentionkit.com/?utm_source=caddy-plugin&amp;utm_medium=plugin&amp;utm_campaign=caddy-links"
							   target="_blank"><?php echo esc_html( __( 'RetentionKit', 'caddy' ) ); ?></a>
							<p><?php echo esc_html( __( 'Learn why users cancel their WC subscriptions with exit surveys, offer renewal discounts to stay and more.', 'caddy' ) ); ?></p>
						</div>
					</li>
				</ul>
			</div>
			<div class="cc-box cc-links">
				<h3><?php echo esc_html( __( 'Caddy Quick Links', 'caddy' ) ); ?></h3>
				<ul>
					<li>
						<a href="https://usecaddy.com/docs/?utm_source=caddy-plugin&amp;utm_medium=plugin&amp;utm_campaign=plugin-links"><?php echo esc_html( __( 'Read the documentation', 'caddy' ) ); ?></a>
					</li>
					<li>
						<a href="https://usecaddy.com/my-account/?utm_source=caddy-plugin&amp;utm_medium=plugin&amp;utm_campaign=plugin-links"><?php echo esc_html( __( 'Register / Log into your account', 'caddy' ) ); ?></a>
					</li>
					<li>
						<a href="https://wordpress.org/support/plugin/caddy/reviews/#new-post" target="_blank"><?php echo esc_html( __( 'Leave a review', 'caddy' ) ); ?></a>
					</li>
					<li>
						<a href="https://usecaddy.com/contact-us/?utm_source=caddy-plugin&amp;utm_medium=plugin&amp;utm_campaign=plugin-links"><?php echo esc_html( __( 'Contact support', 'caddy' ) ); ?></a>
					</li>
				</ul>
			</div>

		</div>
	</div>
	<?php do_action( 'cc_after_setting_options' ); ?>
	<div class="cc-footer-links">
		<?php echo esc_html( __( 'Made with', 'caddy' ) ); ?> <span style="color: #e25555;">♥</span> <?php echo esc_html( __( 'by', 'caddy' ) ); ?>
		<a href="<?php echo esc_url( 'https://www.madebytribe.com' ); ?>" target="_blank"><?php echo esc_html( __( 'TRIBE', 'caddy' ) ); ?></a>
	</div>
</div>