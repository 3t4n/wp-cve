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
<div id="bdpw_basic_tabs" class="bdpw-vtab-cnt bdpw_basic_tabs bdpw-clearfix">
	
	<h3 style="text-align:center">Compare <span class="bdpw-sf-blue">"Blog Designer - Post and Widget"</span> Free VS Pro</h3>

	<div class="bdpw-deal-offer-wrap">
		<div class="bdpw-deal-offer"> 
			<div class="bdpw-inn-deal-offer">
				<h3 class="bdpw-inn-deal-hedding"><span>Buy Blog Designer Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="bdpw-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="bdpw-inn-deal-offer-btn">
				<div class="bdpw-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(BDPW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="bdpw-sf-btn bdpw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
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
					<h2><?php esc_html_e( 'Free', 'blog-designer-for-post-and-widget' ); ?></h2>
				</th>
				<th>
					<h2 class="wpos-epb"><?php esc_html_e( 'Premium', 'blog-designer-for-post-and-widget' ); ?></h2>
				</th>               
			</tr>
		</thead>
		<tbody>
			<tr>
				<th><?php esc_html_e( 'Designs ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Designs that make your website better', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td>4</td>
				<td>130+</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcodes ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Shortcode provide output to the front-end side', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><?php esc_html_e( '2 - (Post Grid, Post Slider)', 'blog-designer-for-post-and-widget' ); ?></td>
				<td><?php esc_html_e( '9 - (Post Grid, Post List, Post Slider, Post Carousel, Recent Post, Post Ticker, Post GridBox, Post GridBox Slider, Post Masonry)', 'blog-designer-for-post-and-widget' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcode Parameters ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Add extra power to the shortcode', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td>14</td>
				<td>30+</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'WP Templating Features ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'You can modify plugin html/designs in your current theme.', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Ticker View ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Display post in a Ticker view', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Masonry View ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Display post in a masonry view', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Shortcode Generator ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Play with all shortcode parameters with preview panel. No documentation required!!', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Widgets', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"> <?php esc_html_e( 'WordPress Widgets to your sidebars.', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><?php esc_html_e( '1 Widget', 'blog-designer-for-post-and-widget' ); ?></td>
				<td><?php esc_html_e( '5 Widgets', 'blog-designer-for-post-and-widget' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Drag &amp; Drop Post Order Change ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Arrange your desired post with your desired order and display', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Gutenberg Block Supports ', 'blog-designer-for-post-and-widget' ); ?><span><?php esc_html_e( 'Use this plugin with Gutenberg easily', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Elementor Page Builder Support', 'blog-designer-for-post-and-widget' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Elementor easily', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Bevear Builder Support', 'blog-designer-for-post-and-widget' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Bevear Builder easily', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'SiteOrigin Page Builder Support', 'blog-designer-for-post-and-widget' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with SiteOrigin easily', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Divi Page Builder Native Support', 'blog-designer-for-post-and-widget' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Divi Builder easily', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Fusion Page Builder (Avada) native support', 'blog-designer-for-post-and-widget' ); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e( 'Use this plugin with Fusion Page Builder (Avada) easily', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'WPBakery Page Builder Supports ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Use this plugin with Visual Composer/WPBakery page builder easily', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Custom Read More link for Post ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Redirect post to third party destination if any', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Display Desired Post ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Display only the post you want', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Display Post for Particular Categories ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Display only the posts with particular category', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Exclude Some Posts ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Do not display the posts you want', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Exclude Some Categories ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Do not display the posts for particular categories', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Post Order / Order By Parameters ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Display post according to date, title and etc', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Multiple Slider Parameters ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Slider parameters like autoplay, number of slide, sider dots and etc.', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Slider RTL Support ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Slider supports for RTL website', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Automatic Update ', 'blog-designer-for-post-and-widget' ); ?><span><?php esc_html_e( 'Get automatic  plugin updates ', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><?php esc_html_e( 'Lifetime', 'blog-designer-for-post-and-widget' ); ?></td>
				<td><?php esc_html_e( 'Lifetime', 'blog-designer-for-post-and-widget' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Support ', 'blog-designer-for-post-and-widget' ); ?><span class="subtext"><?php esc_html_e( 'Get support for plugin', 'blog-designer-for-post-and-widget' ); ?></span></th>
				<td><?php esc_html_e( 'Limited', 'blog-designer-for-post-and-widget' ); ?></td>
				<td><?php esc_html_e( '1 Year', 'blog-designer-for-post-and-widget' ); ?></td>
			</tr>
		</tbody>
	</table>

	<div class="bdpw-deal-offer-wrap">
		<div class="bdpw-deal-offer"> 
			<div class="bdpw-inn-deal-offer">
				<h3 class="bdpw-inn-deal-hedding"><span>Buy Blog Designer Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="bdpw-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="bdpw-inn-deal-offer-btn">
				<div class="bdpw-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(BDPW_PLUGIN_BUNDLE_LINK); ?>" target="_blank" class="bdpw-sf-btn bdpw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div>

</div>