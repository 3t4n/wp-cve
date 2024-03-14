<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdotpDashboard' ) ) {
/**
 * Class to handle plugin dashboard
 *
 * @since 3.0.0
 */
class ewdotpDashboard {

	public $message;
	public $status = true;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_dashboard_to_menu' ), 99 );
	}

	public function add_dashboard_to_menu() {
		global $ewd_otp_controller;
		global $submenu;

		add_submenu_page( 
			'ewd-otp-orders', 
			'Dashboard', 
			'Dashboard', 
			$ewd_otp_controller->settings->get_setting( 'access-role' ), 
			'ewd-otp-dashboard', 
			array($this, 'display_dashboard_screen') 
		);

		// Create a new sub-menu in the order that we want
		$new_submenu = array();
		$menu_item_count = 3;

		if ( ! isset( $submenu['ewd-otp-orders'] ) or  ! is_array($submenu['ewd-otp-orders']) ) { return; }
		
		foreach ( $submenu['ewd-otp-orders'] as $key => $sub_item	 ) {
			
			if ( $sub_item[0] == 'Dashboard' ) { $new_submenu[0] = $sub_item; }
			elseif ( $sub_item[0] == 'Settings' ) { $new_submenu[ sizeof($submenu) + 1 ] = $sub_item; }
			else {

				if ( $sub_item[0] == 'Tracking' ) { $sub_item[0] = 'Orders'; }
				
				$new_submenu[$menu_item_count] = $sub_item;
				$menu_item_count++;
			}
		}

		ksort($new_submenu);
		
		$submenu['ewd-otp-orders'] = $new_submenu;
	}

	public function display_dashboard_screen() { 
		global $ewd_otp_controller;

		$permission = $ewd_otp_controller->permissions->check_permission( 'premium' );
		$ultimate = $ewd_otp_controller->permissions->check_permission( 'sms' );

		$args = array();

		$orders = $ewd_otp_controller->order_manager->get_matching_orders( $args );

		?>

		<div id="ewd-otp-dashboard-content-area">

			<div id="ewd-otp-dashboard-content-left">
		
				<?php if ( ! $permission or ! $ultimate or get_option("EWD_OTP_Trial_Happening") == "Yes" or get_option("EWD_OTPU_Trial_Happening") == "Yes" ) {
					
					$premium_info = '<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full">';
					$premium_info .= '<div class="ewd-otp-dashboard-new-widget-box-top">';
					$premium_info .= sprintf( __( '<a href="%s" target="_blank">Visit our website</a> to learn how to upgrade to premium.'), 'https://www.etoilewebdesign.com/premium-upgrade-instructions/' );
					$premium_info .= '</div>';
					$premium_info .= '</div>';

					$premium_info = apply_filters( 'ewd_dashboard_top', $premium_info, 'OTP', 'https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1' );

					if ( $permission and get_option("EWD_OTPU_Trial_Happening") != "Yes" ) {
						$ultimate_premium_notice = '<div class="ewd-otp-ultimate-notification">';
						$ultimate_premium_notice .= __( 'Thanks for being a premium user! <strong>If you\'re looking to upgrade to our ultimate version, enter your new product key below.</strong>', 'order-tracking' );
						$ultimate_premium_notice .= '</div>';
						$ultimate_premium_notice .= '<div class="ewd-otp-ultimate-upgrade-dismiss"></div>';

						$premium_info = str_replace('<div class="ewd-premium-helper-dashboard-new-widget-box-top">', '<div class="ewd-premium-helper-dashboard-new-widget-box-top">' . $ultimate_premium_notice, $premium_info);
					}

					echo wp_kses(
						$premium_info,
						apply_filters( 'ewd_dashboard_top_kses_allowed_tags', wp_kses_allowed_html( 'post' ) )
					);
				} ?>
		
				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-dashboard-support-widget-box">
					<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e('Get Support', 'order-tracking'); ?><span id="ewd-otp-dash-mobile-support-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-otp-dash-mobile-support-up-caret">&nbsp;&nbsp;&#9650;</span></div>
					<div class="ewd-otp-dashboard-new-widget-box-bottom">
						<ul class="ewd-otp-dashboard-support-widgets">
							<li>
								<a href="https://www.youtube.com/watch?v=rMULYuPjVXU&list=PLEndQUuhlvSqa6Txwj1-Ohw8Bj90CIRl0" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-youtube.png', __FILE__ ); ?>">
									<div class="ewd-otp-dashboard-support-widgets-text"><?php _e('YouTube Tutorials', 'order-tracking'); ?></div>
								</a>
							</li>
							<li>
								<a href="https://wordpress.org/plugins/order-tracking/#faq" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-faqs.png', __FILE__ ); ?>">
									<div class="ewd-otp-dashboard-support-widgets-text"><?php _e('Plugin FAQs', 'order-tracking'); ?></div>
								</a>
							</li>
							<li>
								<a href="https://www.etoilewebdesign.com/support-center/?Plugin=OTP&Type=FAQs" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-documentation.png', __FILE__ ); ?>">
									<div class="ewd-otp-dashboard-support-widgets-text"><?php _e('Documentation', 'order-tracking'); ?></div>
								</a>
							</li>
							<li>
								<a href="https://www.etoilewebdesign.com/support-center/" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-forum.png', __FILE__ ); ?>">
									<div class="ewd-otp-dashboard-support-widgets-text"><?php _e('Get Support', 'order-tracking'); ?></div>
								</a>
							</li>
						</ul>
					</div>
				</div>
		
				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-dashboard-optional-table">
					<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e('Orders', 'order-tracking'); ?><span id="ewd-otp-dash-optional-table-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-otp-dash-optional-table-up-caret">&nbsp;&nbsp;&#9650;</span></div>
					<div class="ewd-otp-dashboard-new-widget-box-bottom">
						<table class='ewd-otp-overview-table wp-list-table widefat fixed striped posts'>
							<thead>
								<tr>
									<th><?php _e("Order Number", 'order-tracking'); ?></th>
									<th><?php _e("Name", 'order-tracking'); ?></th>
									<th><?php _e("Status", 'order-tracking'); ?></th>
									<th><?php _e("Updated", 'order-tracking'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ( empty( $orders ) ) {echo "<tr><td colspan='3'>" . __("No orders to display yet. Create an order for it to be displayed here.", 'order-tracking') . "</td></tr>";}
									else {
										foreach ( $orders as $order ) { ?>
											<tr>
												<td><?php echo '<a href="admin.php?page=ewd-otp-add-edit-order&order_id=' . $order->id . '" data-id="' . esc_attr( $order->id ) . '">' . $order->number . '</a>'; ?></td>
												<td><?php echo $order->name; ?></td>
												<td><?php echo $order->status; ?></td>
												<td><?php echo $order->status_updated_fmtd; ?></td>
											</tr>
										<?php }
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
		
				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full">
					<div class="ewd-otp-dashboard-new-widget-box-top">What People Are Saying</div>
					<div class="ewd-otp-dashboard-new-widget-box-bottom">
						<ul class="ewd-otp-dashboard-testimonials">
							<?php $randomTestimonial = rand(0,2);
							if($randomTestimonial == 0){ ?>
								<li id="ewd-otp-dashboard-testimonial-one">
									<img src="<?php echo plugins_url( '../assets/img/dash-asset-stars.png', __FILE__ ); ?>">
									<div class="ewd-otp-dashboard-testimonial-title">"Great Plugin. Great Support!"</div>
									<div class="ewd-otp-dashboard-testimonial-author">- @pfernand</div>
									<div class="ewd-otp-dashboard-testimonial-text">The next best thing about finding a great plugin is finding a plugin with AWESOME support... <a href="https://wordpress.org/support/topic/great-plugin-great-support-644/" target="_blank">read more</a></div>
								</li>
							<?php }
							if($randomTestimonial == 1){ ?>
								<li id="ewd-otp-dashboard-testimonial-two">
									<img src="<?php echo plugins_url( '../assets/img/dash-asset-stars.png', __FILE__ ); ?>">
									<div class="ewd-otp-dashboard-testimonial-title">"Order tracking made easy"</div>
									<div class="ewd-otp-dashboard-testimonial-author">- @vietnamsales</div>
									<div class="ewd-otp-dashboard-testimonial-text">That’s a wonderful plugin. Did I mention that customer service was fast, friendly and useful? <a href="https://wordpress.org/support/topic/order-tracking-made-easy/" target="_blank">read more</a></div>
								</li>
							<?php }
							if($randomTestimonial == 2){ ?>
								<li id="ewd-otp-dashboard-testimonial-three">
									<img src="<?php echo plugins_url( '../assets/img/dash-asset-stars.png', __FILE__ ); ?>">
									<div class="ewd-otp-dashboard-testimonial-title">"Amazing plugin, Awesome Customer Support"</div>
									<div class="ewd-otp-dashboard-testimonial-author">- @diegoduarte</div>
									<div class="ewd-otp-dashboard-testimonial-text">The plugin is simple, but really amazing. It does everything is supposed to do. Five stars! <a href="https://wordpress.org/support/topic/amazing-plugin-awesome-customer-support/" target="_blank">read more</a></div>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
		
				<?php if ( ! $permission or get_option("EWD_OTP_Trial_Happening") == "Yes" or get_option("EWD_OTPU_Trial_Happening") == "Yes" ) { ?>
					<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-dashboard-guarantee-widget-box">
						<div class="ewd-otp-dashboard-new-widget-box-top">
							<div class="ewd-otp-dashboard-guarantee">
								<div class="ewd-otp-dashboard-guarantee-title">14-Day 100% Money-Back Guarantee</div>
								<div class="ewd-otp-dashboard-guarantee-text">If you're not 100% satisfied with the premium version of our plugin - no problem. You have 14 days to receive a FULL REFUND. We're certain you won't need it, though.</div>
							</div>
						</div>
					</div>
				<?php } ?>
		
			</div> <!-- left -->
		
			<div id="ewd-otp-dashboard-content-right">
		
				<?php if ( ! $permission or get_option("EWD_OTP_Trial_Happening") == "Yes" or get_option("EWD_OTPU_Trial_Happening") == "Yes" ) { ?>
					<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-dashboard-get-premium-widget-box">
						<div class="ewd-otp-dashboard-new-widget-box-top">Get Premium</div>

						<?php if ( get_option( "EWD_OTP_Trial_Happening" ) == "Yes" ) { do_action( 'ewd_trial_happening', 'OTP' ); } ?>
						<?php if ( get_option( "EWD_OTPU_Trial_Happening" ) == "Yes" ) { do_action( 'ewd_trial_happening', 'OTPU' ); } ?>

						<div class="ewd-otp-dashboard-new-widget-box-bottom">
							<div class="ewd-otp-dashboard-get-premium-widget-features-title"<?php echo ( ( get_option("EWD_OTP_Trial_Happening") == "Yes" or get_option( "EWD_OTPU_Trial_Happening" ) == "Yes" ) ? "style='padding-top: 20px;'" : ""); ?>>GET FULL ACCESS WITH OUR PREMIUM VERSION AND GET:</div>
							<ul class="ewd-otp-dashboard-get-premium-widget-features">
								<li>Set Up Sales Reps &amp; Customers</li>
								<li>Custom Fields</li>
								<li>WooCommerce Order Integration</li>
								<li>Advanced Display Options</li>
								<li>+ More</li>
							</ul>
							<a href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1&utm_source=otp_admin&utm_content=dashboard_sidebar" class="ewd-otp-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
							
							<?php if ( ! get_option("EWD_OTP_Trial_Happening") and ! get_option( "EWD_OTPU_Trial_Happening" ) == "Yes" ) { 
								$trial_info = sprintf( __( '<a href="%s" target="_blank">Visit our website</a> to learn how to get a free 7-day trial of the premium plugin.'), 'https://www.etoilewebdesign.com/premium-upgrade-instructions/' );		

								$version_select_modal = '<div class="ewd-otp-trial-version-select-modal-background ewd-otp-hidden"></div>';
								$version_select_modal .= '<div class="ewd-otp-trial-version-select-modal ewd-otp-hidden">';
								$version_select_modal .= '<div class="ewd-otp-trial-version-select-modal-title">' . __( 'Select version to trial', 'order-tracking' ) . '</div>';
								$version_select_modal .= '<div class="ewd-otp-trial-version-select-modal-option"><input type="radio" value="premium" name="ewd-otp-trial-version" checked /> ' . __( 'Premium', 'order-tracking' ) . '</div>';
								$version_select_modal .= '<div class="ewd-otp-trial-version-select-modal-option"><input type="radio" value="ultimate" name="ewd-otp-trial-version" /> ' . __( 'Ultimate', 'order-tracking' ) . '</div>';
								$version_select_modal .= '<div class="ewd-otp-trial-version-select-modal-explanation">' . __( 'SMS messaging will not work in the ultimate version trial.', 'order-tracking' ) . '</div>';
								$version_select_modal .= '<div class="ewd-otp-trial-version-select-modal-submit">' . __( 'Select', 'order-tracking' ) . '</div>';
								$version_select_modal .= '</div>';

								$trial_info = apply_filters( 'fsp_trial_button', $trial_info, 'OTP' );

								$trial_info = str_replace( '</form>', '</form>' . $version_select_modal, $trial_info );

								echo apply_filters( 'ewd_trial_button', $trial_info, 'OTP' );
							} ?>
				</div>
					</div>
				<?php } ?>
		
				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full">
					<div class="ewd-otp-dashboard-new-widget-box-top">Other Plugins by Etoile</div>
					<div class="ewd-otp-dashboard-new-widget-box-bottom">
						<ul class="ewd-otp-dashboard-other-plugins">
							<li>
								<a href="https://wordpress.org/plugins/ultimate-product-catalogue/" target="_blank"><img src="<?php echo plugins_url( '../assets/img/ewd-otp-icon.png', __FILE__ ); ?>"></a>
								<div class="ewd-otp-dashboard-other-plugins-text">
									<div class="ewd-otp-dashboard-other-plugins-title">Product Catalog</div>
									<div class="ewd-otp-dashboard-other-plugins-blurb">Enables you to display your business's products in a clean and efficient manner.</div>
								</div>
							</li>
							<li>
								<a href="https://wordpress.org/plugins/ultimate-faqs/" target="_blank"><img src="<?php echo plugins_url( '../assets/img/ewd-ufaq-icon.png', __FILE__ ); ?>"></a>
								<div class="ewd-otp-dashboard-other-plugins-text">
									<div class="ewd-otp-dashboard-other-plugins-title">Ultimate FAQs</div>
									<div class="ewd-otp-dashboard-other-plugins-blurb">An easy-to-use FAQ plugin that lets you create, order and publicize FAQs, with many styles and options!</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
		
			</div> <!-- right -->	
		
		</div> <!-- us-dashboard-content-area -->
		
		<?php if ( ! $permission or get_option("EWD_OTP_Trial_Happening") == "Yes" or get_option( "EWD_OTPU_Trial_Happening" ) == "Yes" ) { ?>
			<div id="ewd-otp-dashboard-new-footer-one">
				<div class="ewd-otp-dashboard-new-footer-one-inside">
					<div class="ewd-otp-dashboard-new-footer-one-left">
						<div class="ewd-otp-dashboard-new-footer-one-title">What's Included in Our Premium Version?</div>
						<ul class="ewd-otp-dashboard-new-footer-one-benefits">
							<li>Create &amp; Assign Orders to Sales Reps</li>
							<li>Create &amp; Tie Orders to Customers</li>
							<li>Custom Fields</li>
							<li>WooCommerce Order Integration</li>
							<li>Advanced Display &amp; Styling Options</li>
							<li>Front-End Customer Order Form</li>
							<li>Import/Export Orders</li>
							<li>Set Up Status Locations</li>
							<li>Email Support</li>
						</ul>
					</div>
					<div class="ewd-otp-dashboard-new-footer-one-buttons">
						<a class="ewd-otp-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1&utm_source=otp_admin&utm_content=dashboard_footer" target="_blank">UPGRADE NOW</a>
					</div>
				</div>
			</div> <!-- us-dashboard-new-footer-one -->
		<?php } ?>	
		<div id="ewd-otp-dashboard-new-footer-two">
			<div class="ewd-otp-dashboard-new-footer-two-inside">
				<img src="<?php echo plugins_url( '../assets/img/ewd-logo-white.png', __FILE__ ); ?>" class="ewd-otp-dashboard-new-footer-two-icon">
				<div class="ewd-otp-dashboard-new-footer-two-blurb">
					At Etoile Web Design, we build reliable, easy-to-use WordPress plugins with a modern look. Rich in features, highly customizable and responsive, plugins by Etoile Web Design can be used as out-of-the-box solutions and can also be adapted to your specific requirements.
				</div>
				<ul class="ewd-otp-dashboard-new-footer-two-menu">
					<li>SOCIAL</li>
					<li><a href="https://www.facebook.com/EtoileWebDesign/" target="_blank">Facebook</a></li>
					<li><a href="https://twitter.com/EtoileWebDesign" target="_blank">Twitter</a></li>
					<li><a href="https://www.etoilewebdesign.com/category/blog/" target="_blank">Blog</a></li>
				</ul>
				<ul class="ewd-otp-dashboard-new-footer-two-menu">
					<li>SUPPORT</li>
					<li><a href="https://www.youtube.com/watch?v=rMULYuPjVXU&list=PLEndQUuhlvSqa6Txwj1-Ohw8Bj90CIRl0" target="_blank">YouTube Tutorials</a></li>
					<li><a href="https://www.etoilewebdesign.com/support-center/?Plugin=OTP&Type=FAQs" target="_blank">Documentation</a></li>
					<li><a href="https://www.etoilewebdesign.com/support-center/" target="_blank">Get Support</a></li>
					<li><a href="https://wordpress.org/plugins/order-tracking/#faq" target="_blank">FAQs</a></li>
				</ul>
			</div>
		</div> <!-- ewd-otp-dashboard-new-footer-two -->
		
	<?php }

	public function display_notice() {
		if ( $this->status ) {
			echo "<div class='updated'><p>" . $this->message . "</p></div>";
		}
		else {
			echo "<div class='error'><p>" . $this->message . "</p></div>";
		}
	}
}
} // endif
