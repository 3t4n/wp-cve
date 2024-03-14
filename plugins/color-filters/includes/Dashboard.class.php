<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewduwcfDashboard' ) ) {
/**
 * Class to handle plugin dashboard
 *
 * @since 3.0.0
 */
class ewduwcfDashboard {

	public $message;
	public $status = true;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_dashboard_to_menu' ) );

		add_action( 'admin_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );
	}

	public function add_dashboard_to_menu() {
		
		add_menu_page( 
			'WC Filters Dashboard', 
			'WC Filters', 
			'manage_options', 
			'ewd-uwcf-dashboard', 
			array( $this, 'display_dashboard_screen' ),
			'dashicons-filter',
			'50.5'
		);
	}

	// Enqueues the admin script so that our hacky sub-menu opening function can run
	public function enqueue_scripts() {
		global $admin_page_hooks;

		$currentScreen = get_current_screen();
		if ( $currentScreen->id == 'toplevel_page_ewd-uwcf-dashboard' ) {
			wp_enqueue_style( 'ewd-uwcf-admin-css', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf-admin.css', array(), EWD_UWCF_VERSION );
			wp_enqueue_script( 'ewd-uwcf-admin-js', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf-admin.js', array( 'jquery' ), EWD_UWCF_VERSION, true );
		}
	}

	public function display_dashboard_screen() { 
		global $ewd_uwcf_controller;

		$permission = $ewd_uwcf_controller->permissions->check_permission( 'styling' );

		$args = array(
			'hide_empty' 	=> false,
			'taxonomy' 		=> array( EWD_UWCF_PRODUCT_COLOR_TAXONOMY, EWD_UWCF_PRODUCT_SIZE_TAXONOMY )
		);

		$taxonomies = get_terms( $args );

		$taxonomies = is_wp_error( $taxonomies ) ? array() : $taxonomies;

		?>

		<div id="ewd-uwcf-dashboard-content-area">

			<div id="ewd-uwcf-dashboard-content-left">
		
				<?php if ( ! $permission or get_option("EWD_UWCF_Trial_Happening") == "Yes" ) {
					$premium_info = '<div class="ewd-uwcf-dashboard-new-widget-box ewd-widget-box-full">';
					$premium_info .= '<div class="ewd-uwcf-dashboard-new-widget-box-top">';
					$premium_info .= sprintf( __( '<a href="%s" target="_blank">Visit our website</a> to learn how to upgrade to premium.'), 'https://www.etoilewebdesign.com/premium-upgrade-instructions/' );
					$premium_info .= '</div>';
					$premium_info .= '</div>';

					$premium_info = apply_filters( 'ewd_dashboard_top', $premium_info, 'UWCF', 'https://www.etoilewebdesign.com/license-payment/?Selected=UWCF&Quantity=1' );

					echo wp_kses(
						$premium_info,
						apply_filters( 'ewd_dashboard_top_kses_allowed_tags', wp_kses_allowed_html( 'post' ) )
					);
				} ?>
		
				<div class="ewd-uwcf-dashboard-new-widget-box ewd-widget-box-full" id="ewd-uwcf-dashboard-support-widget-box">
					<div class="ewd-uwcf-dashboard-new-widget-box-top">Get Support<span id="ewd-uwcf-dash-mobile-support-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-uwcf-dash-mobile-support-up-caret">&nbsp;&nbsp;&#9650;</span></div>
					<div class="ewd-uwcf-dashboard-new-widget-box-bottom">
						<ul class="ewd-uwcf-dashboard-support-widgets">
							<li>
								<a href="https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-youtube.png', __FILE__ ); ?>">
									<div class="ewd-uwcf-dashboard-support-widgets-text">YouTube Tutorials</div>
								</a>
							</li>
							<li>
								<a href="https://wordpress.org/plugins/color-filters/#faq" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-faqs.png', __FILE__ ); ?>">
									<div class="ewd-uwcf-dashboard-support-widgets-text">Plugin FAQs</div>
								</a>
							</li>
							<li>
								<a href="https://www.etoilewebdesign.com/support-center/?Plugin=UWCF&Type=FAQs" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-documentation.png', __FILE__ ); ?>">
									<div class="ewd-uwcf-dashboard-support-widgets-text">Documentation</div>
								</a>
							</li>
							<li>
								<a href="https://www.etoilewebdesign.com/support-center/" target="_blank">
									<img src="<?php echo plugins_url( '../assets/img/ewd-support-icon-forum.png', __FILE__ ); ?>">
									<div class="ewd-uwcf-dashboard-support-widgets-text">Get Support</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
		
				<div class="ewd-uwcf-dashboard-new-widget-box ewd-widget-box-full" id="ewd-uwcf-dashboard-optional-table">
					<div class="ewd-uwcf-dashboard-new-widget-box-top">Colors & Sizes<span id="ewd-uwcf-dash-optional-table-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-uwcf-dash-optional-table-up-caret">&nbsp;&nbsp;&#9650;</span></div>
					<div class="ewd-uwcf-dashboard-new-widget-box-bottom">
						<table class='ewd-uwcf-overview-table wp-list-table widefat fixed striped posts'>
							<thead>
								<tr>
									<th><?php _e( 'Name', 'color-filters' ); ?></th>
									<th><?php _e( 'Type', 'color-filters' ); ?></th>
									<th><?php _e( 'Product Count', 'color-filters' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ( empty( $taxonomies ) ) {echo "<tr><td colspan='3'>" . __("No colors or sizes to display yet. Create one for it to be displayed here.", 'color-filters') . "</td></tr>";}
									else {
										foreach ( $taxonomies as $taxonomy ) { ?>
											<tr>
												<td><?php echo esc_html( $taxonomy->name ); ?></td>
												<td><?php echo ( $taxonomy->taxonomy == EWD_UWCF_PRODUCT_COLOR_TAXONOMY ? __( 'Color', 'color-filters' ) : __( 'Size', 'color-filters' ) ) ?></td>
												<td><?php echo esc_html( $taxonomy->count ) ?></td>
											</tr>
										<?php }
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
		
				<div class="ewd-uwcf-dashboard-new-widget-box ewd-widget-box-full">
					<div class="ewd-uwcf-dashboard-new-widget-box-top">What People Are Saying</div>
					<div class="ewd-uwcf-dashboard-new-widget-box-bottom">
						<ul class="ewd-uwcf-dashboard-testimonials">
							<?php $randomTestimonial = rand(0,2);
							if($randomTestimonial == 0){ ?>
								<li id="ewd-uwcf-dashboard-testimonial-one">
									<img src="<?php echo plugins_url( '../assets/img/dash-asset-stars.png', __FILE__ ); ?>">
									<div class="ewd-uwcf-dashboard-testimonial-title">"Wonderful Solution. 1st-rate Support"</div>
									<div class="ewd-uwcf-dashboard-testimonial-author">- @lbdee</div>
									<div class="ewd-uwcf-dashboard-testimonial-text">This plugin adds serious value to WordPress/WooCommerce. Just as impressive is the support which is as responsive as the plugin... <a href="https://wordpress.org/support/topic/wonderful-solution-1st-rate-support/" target="_blank">read more</a></div>
								</li>
							<?php }
							if($randomTestimonial == 1){ ?>
								<li id="ewd-uwcf-dashboard-testimonial-two">
									<img src="<?php echo plugins_url( '../assets/img/dash-asset-stars.png', __FILE__ ); ?>">
									<div class="ewd-uwcf-dashboard-testimonial-title">"Great support"</div>
									<div class="ewd-uwcf-dashboard-testimonial-author">- @aniadealemania</div>
									<div class="ewd-uwcf-dashboard-testimonial-text">Very nice and helpful support. Thanks guys! <a href="https://wordpress.org/support/topic/great-support-1286/" target="_blank">read more</a></div>
								</li>
							<?php }
							if($randomTestimonial == 2){ ?>
								<li id="ewd-uwcf-dashboard-testimonial-three">
									<img src="<?php echo plugins_url( '../assets/img/dash-asset-stars.png', __FILE__ ); ?>">
									<div class="ewd-uwcf-dashboard-testimonial-title">"Great Plugin, greater support"</div>
									<div class="ewd-uwcf-dashboard-testimonial-author">- @jstjames</div>
									<div class="ewd-uwcf-dashboard-testimonial-text">The plugin worked exactly as described and when my team needed help installing/figuring something out, they were quick to respond... <a href="https://wordpress.org/support/topic/great-plugin-greater-support-4/" target="_blank">read more</a></div>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
		
				<?php if ( ! $permission or get_option("EWD_UWCF_Trial_Happening") == "Yes" ) { ?>
					<div class="ewd-uwcf-dashboard-new-widget-box ewd-widget-box-full" id="ewd-uwcf-dashboard-guarantee-widget-box">
						<div class="ewd-uwcf-dashboard-new-widget-box-top">
							<div class="ewd-uwcf-dashboard-guarantee">
								<div class="ewd-uwcf-dashboard-guarantee-title">14-Day 100% Money-Back Guarantee</div>
								<div class="ewd-uwcf-dashboard-guarantee-text">If you're not 100% satisfied with the premium version of our plugin - no problem. You have 14 days to receive a FULL REFUND. We're certain you won't need it, though.</div>
							</div>
						</div>
					</div>
				<?php } ?>
		
			</div> <!-- left -->
		
			<div id="ewd-uwcf-dashboard-content-right">
		
				<?php if ( ! $permission or get_option("EWD_UWCF_Trial_Happening") == "Yes" ) { ?>
					<div class="ewd-uwcf-dashboard-new-widget-box ewd-widget-box-full" id="ewd-uwcf-dashboard-get-premium-widget-box">
						<div class="ewd-uwcf-dashboard-new-widget-box-top">Get Premium</div>

						<?php if ( get_option( "EWD_UWCF_Trial_Happening" ) == "Yes" ) { do_action( 'ewd_trial_happening', 'UWCF' ); } ?>

						<div class="ewd-uwcf-dashboard-new-widget-box-bottom">
							<div class="ewd-uwcf-dashboard-get-premium-widget-features-title"<?php echo ( ( get_option("EWD_UWCF_Trial_Happening") == "Yes" ) ? "style='padding-top: 20px;'" : ""); ?>>GET FULL ACCESS WITH OUR PREMIUM VERSION AND GET:</div>
							<ul class="ewd-uwcf-dashboard-get-premium-widget-features">
								<li>Search &amp; Review Summary Shortcodes</li>
								<li>WooCommerce Integration</li>
								<li>Admin &amp; Review Reminder Emails</li>
								<li>Advanced Display Options</li>
								<li>+ More</li>
							</ul>
							<a href="https://www.etoilewebdesign.com/license-payment/?Selected=UWCf&Quantity=1&utm_source=uwcf_admin&utm_content=dashboard_sidebar" class="ewd-uwcf-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
							
							<?php if ( ! get_option("EWD_UWCF_Trial_Happening") ) { 
								$trial_info = sprintf( __( '<a href="%s" target="_blank">Visit our website</a> to learn how to get a free 7-day trial of the premium plugin.'), 'https://www.etoilewebdesign.com/premium-upgrade-instructions/' );

								echo apply_filters( 'ewd_trial_button', $trial_info, 'UWCF' );
							} ?>
				</div>
					</div>
				<?php } ?>
		
				<div class="ewd-uwcf-dashboard-new-widget-box ewd-widget-box-full">
					<div class="ewd-uwcf-dashboard-new-widget-box-top">Other Plugins by Etoile</div>
					<div class="ewd-uwcf-dashboard-new-widget-box-bottom">
						<ul class="ewd-uwcf-dashboard-other-plugins">
							<li>
								<a href="https://wordpress.org/plugins/ultimate-product-catalogue/" target="_blank"><img src="<?php echo plugins_url( '../assets/img/ewd-uwcf-icon.png', __FILE__ ); ?>"></a>
								<div class="ewd-uwcf-dashboard-other-plugins-text">
									<div class="ewd-uwcf-dashboard-other-plugins-title">Product Catalog</div>
									<div class="ewd-uwcf-dashboard-other-plugins-blurb">Enables you to display your business's products in a clean and efficient manner.</div>
								</div>
							</li>
							<li>
								<a href="https://wordpress.org/plugins/ultimate-faqs/" target="_blank"><img src="<?php echo plugins_url( '../assets/img/ewd-ufaq-icon.png', __FILE__ ); ?>"></a>
								<div class="ewd-uwcf-dashboard-other-plugins-text">
									<div class="ewd-uwcf-dashboard-other-plugins-title">Ultimate FAQs</div>
									<div class="ewd-uwcf-dashboard-other-plugins-blurb">An easy-to-use FAQ plugin that lets you create, order and publicize FAQs, with many styles and options!</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
		
			</div> <!-- right -->	
		
		</div> <!-- us-dashboard-content-area -->
		
		<?php if ( ! $permission or get_option("EWD_UWCF_Trial_Happening") == "Yes" ) { ?>
			<div id="ewd-uwcf-dashboard-new-footer-one">
				<div class="ewd-uwcf-dashboard-new-footer-one-inside">
					<div class="ewd-uwcf-dashboard-new-footer-one-left">
						<div class="ewd-uwcf-dashboard-new-footer-one-title">What's Included in Our Premium Version?</div>
						<ul class="ewd-uwcf-dashboard-new-footer-one-benefits">
							<li>Multiple Filter Layouts</li>
							<li>Attribute Variations</li>
							<li>Display Attributes on Product Page</li>
							<li>Advanced Styling Options</li>
							<li>Advanced Labelling Options</li>
							<li>Email Support</li>
						</ul>
					</div>
					<div class="ewd-uwcf-dashboard-new-footer-one-buttons">
						<a class="ewd-uwcf-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=UWCF&Quantity=1&utm_source=uwcf_admin&utm_content=dashboard_footer" target="_blank">UPGRADE NOW</a>
					</div>
				</div>
			</div> <!-- us-dashboard-new-footer-one -->
		<?php } ?>	
		<div id="ewd-uwcf-dashboard-new-footer-two">
			<div class="ewd-uwcf-dashboard-new-footer-two-inside">
				<img src="<?php echo plugins_url( '../assets/img/ewd-logo-white.png', __FILE__ ); ?>" class="ewd-uwcf-dashboard-new-footer-two-icon">
				<div class="ewd-uwcf-dashboard-new-footer-two-blurb">
					At Etoile Web Design, we build reliable, easy-to-use WordPress plugins with a modern look. Rich in features, highly customizable and responsive, plugins by Etoile Web Design can be used as out-of-the-box solutions and can also be adapted to your specific requirements.
				</div>
				<ul class="ewd-uwcf-dashboard-new-footer-two-menu">
					<li>SOCIAL</li>
					<li><a href="https://www.facebook.com/EtoileWebDesign/" target="_blank">Facebook</a></li>
					<li><a href="https://twitter.com/EtoileWebDesign" target="_blank">Twitter</a></li>
					<li><a href="https://www.etoilewebdesign.com/category/blog/" target="_blank">Blog</a></li>
				</ul>
				<ul class="ewd-uwcf-dashboard-new-footer-two-menu">
					<li>SUPPORT</li>
					<li><a href="https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw" target="_blank">YouTube Tutorials</a></li>
					<li><a href="https://www.etoilewebdesign.com/support-center/?Plugin=UWCF&Type=FAQs" target="_blank">Documentation</a></li>
					<li><a href="https://www.etoilewebdesign.com/support-center/" target="_blank">Get Support</a></li>
					<li><a href="https://wordpress.org/plugins/color-filters/#faq" target="_blank">FAQs</a></li>
				</ul>
			</div>
		</div> <!-- ewd-uwcf-dashboard-new-footer-two -->
		
	<?php }

	public function display_notice() {
		if ( $this->status ) {
			echo "<div class='updated'><p>" . esc_html( $this->message ) . "</p></div>";
		}
		else {
			echo "<div class='error'><p>" . esc_html( $this->message ) . "</p></div>";
		}
	}
}
} // endif
