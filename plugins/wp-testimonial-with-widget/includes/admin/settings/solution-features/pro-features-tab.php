<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Popup Anything on click
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="wtwp_themes_tabs" class="wtwp-vtab-cnt wtwp_themes_tabs wtwp-clearfix">
	<!-- Start - WP Testimonials with rotator widget - Features -->
	<div class="wtwp-features-section">
		<div class="wtwp-sf-center">
			<h1 class="wtwp-sf-heading">Powerful Pro Features, Simplified</h1>
		</div>
		<div class="wtwp-sf-welcome-wrap wtwp-sf-center">	
			<div class="wtwp-features-box-wrap">
				<ul class="wtwp-features-box-grid">
					<li>
					<div class="wtwp-popup-icon"><img src="<?php echo esc_url(WTWP_URL); ?>assets/images/popup-icon/testimonial-grid.png" /></div>
					Testimonial Grid View</li>
					<li>
					<div class="wtwp-popup-icon"><img src="<?php echo esc_url(WTWP_URL); ?>assets/images/popup-icon/slider.png" /></div>
					Testimonial Slider View</li>
					<li>
					<div class="wtwp-popup-icon"><img src="<?php echo esc_url(WTWP_URL); ?>assets/images/popup-icon/centermode.png" /></div>
					Testimonial Centermode View</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="wtwp-deal-offer-wrap">
		<div class="wtwp-deal-offer">
			<div class="wtwp-inn-deal-offer">
				<h3 class="wtwp-inn-deal-hedding"><span>Buy Testimonial Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="wtwp-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="wtwp-inn-deal-offer-btn">
				<div class="wtwp-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(WTWP_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="wtwp-sf-btn wtwp-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div>

</div>