<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP Slick Slider and Image Carousel
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div id="wpsisac_basic_tabs" class="wpsisac-vtab-cnt wpsisac_basic_tabs wpsisac-clearfix">
	<h3 class="wpsisac-basic-heading">Compare <span class="wpsisac-blue">"WP Slick Slider and Image Carousel"</span> Basic VS Pro</h3>

	<div class="wpsisac-deal-offer-wrap">
		<div class="wpsisac-deal-offer"> 
			<div class="wpsisac-inn-deal-offer">
				<h3 class="wpsisac-inn-deal-hedding"><span>Buy Slick Slider Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="wpsisac-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="wpsisac-inn-deal-offer-btn">
				<div class="wpsisac-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(WPSISAC_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="wpsisac-sf-btn wpsisac-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
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
					<h2><?php esc_html_e('Free', 'wp-slick-slider-and-image-carousel'); ?></h2>
				</th>
				<th>
					<h2 class="wpos-epb" style="margin-bottom: 10px;"><?php esc_html_e('Premium', 'wp-slick-slider-and-image-carousel'); ?></h2>
				</th>
			</tr>
		</thead>
	   <tbody>
			<tr>
				<th><?php esc_html_e( 'Designs', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Designs that make your website better', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td>6</td>
				<td>90+</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcodes', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Shortcode provide output to the front-end side', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><?php esc_html_e( '2 (Slider, Carousel)', 'wp-slick-slider-and-image-carousel' ); ?></td>
				<td><?php esc_html_e( '3 (Slider, Carousel, Variable width )', 'wp-slick-slider-and-image-carousel' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcode Parameters', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Add extra power to the shortcode', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td>10</td>
				<td>30+</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcode Generator', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Play with all shortcode parameters with preview panel. No documentation required!!', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'WP Templating Features', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'You can modify plugin html/designs in your current theme.', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Drag &amp; Drop Slide Order Change', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Arrange your desired slides with your desired order and display', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Navigation Support', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Thumbnail navigation support to some designs', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Loop Control', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Infinite scroll control', 'wp-slick-slider-and-image-carousel' ); ?> </span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Gutenberg Block Supports', 'wp-slick-slider-and-image-carousel' ); ?> <span><?php esc_html_e( 'Use this plugin with Gutenberg easily', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Elementor Page Builder Support', 'wp-slick-slider-and-image-carousel' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Elementor easily', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Bevear Builder Support', 'wp-slick-slider-and-image-carousel' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Bevear Builder easily', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'SiteOrigin Page Builder Support', 'wp-slick-slider-and-image-carousel' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with SiteOrigin easily', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Divi Page Builder Native Support', 'wp-slick-slider-and-image-carousel' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Divi Builder easily', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Fusion Page Builder (Avada) native support', 'wp-slick-slider-and-image-carousel' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Fusion(Avada) Builder easily', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'WPBakery Page Builder Supports', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Use this plugin with WPBakery Page Builder easily', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Custom Read More link Text', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Add custom name for read more link', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Arrows design', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Set arrows designs', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td>1</td>
				<td>8</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Dots Design', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Set dots designs', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td>1</td>
				<td>12</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Display Slides for Particular Categories', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Display only the slides with particular category', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Exclude Some Slides', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Do not display the slides you want', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Exclude Some Categories', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Do not display the slides for particular categories', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Slides Order / Order By Parameters', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Display slides according to date, title and etc', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Multiple Slider Parameters', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Slider parameters like autoplay, number of slide, sider dots and etc.', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Slider RTL Support', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Slider supports for RTL website', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Automatic Update', 'wp-slick-slider-and-image-carousel' ); ?> <span><?php esc_html_e( 'Get automatic  plugin updates', 'wp-slick-slider-and-image-carousel' ); ?> </span></th>
				<td><?php esc_html_e( 'Lifetime', 'wp-slick-slider-and-image-carousel' ); ?></td>
				<td><?php esc_html_e( 'Lifetime', 'wp-slick-slider-and-image-carousel' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Support', 'wp-slick-slider-and-image-carousel' ); ?> <span class="subtext"><?php esc_html_e( 'Get support for plugin', 'wp-slick-slider-and-image-carousel' ); ?></span></th>
				<td><?php esc_html_e( 'Limited', 'wp-slick-slider-and-image-carousel' ); ?></td>
				<td><?php esc_html_e( '1 Year', 'wp-slick-slider-and-image-carousel' ); ?></td>
			</tr>
		</tbody>
	</table>

	<div class="wpsisac-deal-offer-wrap">
		<div class="wpsisac-deal-offer"> 
			<div class="wpsisac-inn-deal-offer">
				<h3 class="wpsisac-inn-deal-hedding"><span>Buy Slick Slider Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="wpsisac-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="wpsisac-inn-deal-offer-btn">
				<div class="wpsisac-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(WPSISAC_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="wpsisac-sf-btn wpsisac-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div>
</div>