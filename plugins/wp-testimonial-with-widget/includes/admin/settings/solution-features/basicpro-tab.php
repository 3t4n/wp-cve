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
<div id="wtwp_basic_tabs" class="wtwp-vtab-cnt wtwp_basic_tabs wtwp-clearfix">
	
	<h3 style="text-align:center">Compare <span class="wtwp-blue">"WP Testimonials with rotator widget"</span> Free VS Pro</h3>

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

	<table class="wpos-plugin-pricing-table">
		<colgroup></colgroup>
		<colgroup></colgroup>
		<colgroup></colgroup>
		<thead>
			<tr>
				<th></th>
				<th>
					<h2><?php esc_html_e( 'Free', 'wp-testimonial-with-widget' ); ?></h2>
				</th>
				<th>
					<h2 class="wpos-epb"><?php esc_html_e('Premium', 'wp-testimonial-with-widget'); ?></h2>
				</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<th><?php esc_html_e( 'Designs ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Designs that make your website better', 'wp-testimonial-with-widget' ); ?></span></th>
				<td>4</td>
				<td>20</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcodes ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Shortcode provide output to the front-end side', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><?php esc_html_e( '2 (Grid, Slider)', 'wp-testimonial-with-widget' ); ?></td>
				<td><?php esc_html_e( '3 (Grid, Slider, form)', 'wp-testimonial-with-widget' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcode Parameters ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Add extra power to the shortcode', 'wp-testimonial-with-widget' ); ?></span></th>
				<td>13</td>
				<td>28+</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcode Generator ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Play with all shortcode parameters with preview panel. No documentation required!!', 'wp-testimonial-with-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'WP Templating Features ', 'wp-testimonial-with-widget' ); ?><span class="subtext"><?php esc_html_e( 'You can modify plugin html/designs in your current theme.', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Widgets ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'WordPress Widgets to your sidebars.', 'wp-testimonial-with-widget' ); ?></span></th>
				<td>1</td>
				<td>1</td>
			</tr>

			<tr>
				<th><?php esc_html_e( 'Drag & Drop Post Order Change ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Arrange your desired post with your desired order and display', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Gutenberg Block Supports ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Use this plugin with Gutenberg easily', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Elementor Page Builder Support', 'wp-testimonial-with-widget' ); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e( 'Use this plugin with Elementor easily', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Bevear Builder Support', 'wp-testimonial-with-widget' ); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e( 'Use this plugin with Bevear Builder easily', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'SiteOrigin Page Builder Support', 'wp-testimonial-with-widget' ); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e( 'Use this plugin with SiteOrigin easily', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Divi Page Builder Native Support', 'wp-testimonial-with-widget' ); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e( 'Use this plugin with Divi Builder easily', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Avada Page Builder(Fusion) Native Support', 'wp-testimonial-with-widget' ); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e( 'Use this plugin with Avada Builder easily', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'WPBakery Page Builder Supports ', 'wp-testimonial-with-widget' ); ?><span class="subtext"><?php esc_html_e( 'Use this plugin with Visual Composer/WPBakery page builder easily', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Custom Read More link for Post ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Redirect post to third party destination if any', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Publicize ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Support with Jetpack to publish your News post on', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr><tr>
				<th><?php esc_html_e( 'Display Desired Post ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Display only the post you want', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>

			<tr>
				<th><?php esc_html_e( 'Display Post for Particular Categories ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Display only the posts with particular category', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Exclude Some Posts', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Do not display the posts you want', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Exclude Some Categories ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Do not display the posts for particular categories', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Post Order / Order By Parameters ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Display post according to date, title and etc', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Multiple Slider Parameters ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Slider parameters like autoplay, number of slide, sider dots and etc.', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Slider RTL Support ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Slider supports for RTL website', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Automatic Update ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Get automatic plugin updates', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><?php esc_html_e( 'Lifetime', 'wp-testimonial-with-widget' ); ?></td>
				<td><?php esc_html_e( 'Lifetime', 'wp-testimonial-with-widget' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Support ', 'wp-testimonial-with-widget' ); ?><span><?php esc_html_e( 'Get support for plugin', 'wp-testimonial-with-widget' ); ?></span></th>
				<td><?php esc_html_e( 'Lifetime', 'wp-testimonial-with-widget' ); ?></td>
				<td><?php esc_html_e( '1 Year OR Lifetime', 'wp-testimonial-with-widget' ); ?></td>
			</tr>
		</tbody>
	</table>

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