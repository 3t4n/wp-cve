<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div id="wpnw_basic_tabs" class="wpnw-vtab-cnt wpnw_basic_tabs wpnw-clearfix">
	<h3 style="text-align:center">Compare <span class="wpnw-blue">"WP News and Scrolling Widgets"</span> Free VS Pro</h3>

	<div class="wpnw-deal-offer-wrap">
		<div class="wpnw-deal-offer"> 
			<div class="wpnw-inn-deal-offer">
				<h3 class="wpnw-inn-deal-hedding"><span>Buy WP News and Scrolling Widgets Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="wpnw-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="wpnw-inn-deal-offer-btn">
				<div class="wpnw-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(WPNW_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="wpnw-sf-btn wpnw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
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
					<h2><?php esc_html_e('Free', 'sp-news-and-widget'); ?></h2>
				</th>
				<th>
					<h2 class="wpos-epb"><?php esc_html_e('Premium', 'sp-news-and-widget'); ?></h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
			<th><?php esc_html_e('Designs', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Designs that make your website better', 'sp-news-and-widget'); ?></span></th>
			<td>2</td>
			<td>120+</td>
			</tr>
			<tr>
				<th><?php esc_html_e('Shortcodes', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Shortcode provide output to the front-end side', 'sp-news-and-widget'); ?></span></th>
				<td><?php esc_html_e('1 (Grid, List)', 'sp-news-and-widget'); ?></td>
				<td><?php esc_html_e('6 (Grid, Slider, Carousel, List, Gridbox, GridBox Slider, News Ticker)', 'sp-news-and-widget'); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Shortcode Parameters', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Add extra power to the shortcode', 'sp-news-and-widget'); ?></span></th>
				<td>9</td>
				<td>30+</td>
			</tr>
			<tr>
				<th><?php esc_html_e('Shortcode Generator', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Play with all shortcode parameters with preview panel. No documentation required!!', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('WP Templating Features', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('You can modify plugin html/designs in your current theme.', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Widgets', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('WordPress Widgets to your sidebars.', 'sp-news-and-widget'); ?></span></th>
				<td>2</td>
				<td>7</td>
			</tr>
			<tr>
			<th><?php esc_html_e('Drag & Drop Post Order Change', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Arrange your desired post with your desired order and display', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Gutenberg Block Supports', 'sp-news-and-widget'); ?><span><?php esc_html_e('Use this plugin with Gutenberg easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-yes"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Elementor Page Builder Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e('Use this plugin with Elementor easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Beaver Builder Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e('Use this plugin with Beaver Builder easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('SiteOrigin Page Builder Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e('Use this plugin with SiteOrigin easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Divi Page Builder Native Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em> <span><?php esc_html_e('Use this plugin with Divi Builder easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Fusion (Avada) Page Builder Native Support', 'sp-news-and-widget'); ?> <em class="wpos-new-feature">New</em><span><?php esc_html_e('Use this plugin with Fusion Builder easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('WPBakery Page Builder Support', 'sp-news-and-widget'); ?><span><?php esc_html_e('Use this plugin with Visual Composer easily', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"></i></td>
				<td><i class="dashicons dashicons-yes"></i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Custom Read More link for Post', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Redirect post to third party destination if any', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Publicize', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Support with Jetpack to publish your News post on', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Display Desired Post', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Display only the post you want', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Display Post for Particular Categories', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Display only the posts with particular category', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Exclude Some Posts', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Do not display the posts you want', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Exclude Some Categories', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Do not display the posts for particular categories', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Post Order / Order By Parameters', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Display post according to date, title and etc', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-yes"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Multiple Slider Parameters', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Slider parameters like autoplay, number of slide, sider dots and etc.', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
			<th><?php esc_html_e('Slider RTL Support', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Slider supports for RTL website', 'sp-news-and-widget'); ?></span></th>
				<td><i class="dashicons dashicons-no-alt"> </i></td>
				<td><i class="dashicons dashicons-yes"> </i></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Automatic Update', 'sp-news-and-widget'); ?><span><?php esc_html_e('Get automatic plugin updates', 'sp-news-and-widget'); ?></span></th>
				<td><?php esc_html_e('Lifetime', 'sp-news-and-widget'); ?></td>
				<td><?php esc_html_e('Lifetime', 'sp-news-and-widget'); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Support', 'sp-news-and-widget'); ?><span class="subtext"><?php esc_html_e('Get support for plugin', 'sp-news-and-widget'); ?></span></th>
				<td><?php esc_html_e('Limited', 'sp-news-and-widget'); ?></td>
				<td><?php esc_html_e('1 Year', 'sp-news-and-widget'); ?></td>
			</tr>
		</tbody>
	</table>

	<div class="wpnw-deal-offer-wrap">
		<div class="wpnw-deal-offer"> 
			<div class="wpnw-inn-deal-offer">
				<h3 class="wpnw-inn-deal-hedding"><span>Buy WP News and Scrolling Widgets Pro</span> today and unlock all the powerful features.</h3>
				<h4 class="wpnw-inn-deal-sub-hedding"><span style="color:red;">Extra Bonus: </span>Users will get <span>extra best discount</span> on the regular price using this coupon code.</h4>
			</div>
			<div class="wpnw-inn-deal-offer-btn">
				<div class="wpnw-inn-deal-code"><span>EPSEXTRA</span></div>
				<a href="<?php echo esc_url(WPNW_PLUGIN_BUNDLE_LINK); ?>"  target="_blank" class="wpnw-sf-btn wpnw-sf-btn-orange"><span class="dashicons dashicons-cart"></span> Get Essential Bundle Now</a>
				<em class="risk-free-guarantee"><span class="heading">Risk-Free Guarantee </span> - We offer a <span>30-day money back guarantee on all purchases</span>. If you are not happy with your purchases, we will refund your purchase. No questions asked!</em>
			</div>
		</div>
	</div>

</div>