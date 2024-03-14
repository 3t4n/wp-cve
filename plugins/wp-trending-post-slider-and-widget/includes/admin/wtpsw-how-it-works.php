<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wrap wtpsw-wrap">
	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box .postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wtpsw-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wtpsw-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.wpos-new-feature{ font-size: 10px; margin-left:2px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff2700 !important;border-color: #ff2700 !important; font-weight: 600;}
	</style>
	<h2><?php esc_html_e( 'How It Works', 'wtpsw' ); ?></h2>
	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="metabox-holder">
						<div class="meta-box-sortables">
							<div class="postbox">
								<div class="postbox-header">
									<h2 class="hndle">
										<span><?php esc_html_e( 'Need Support & Solutions?', 'wtpsw' ); ?></span>
									</h2>
								</div>
								<div class="inside">
									<p><?php esc_html_e('Boost design and best solution for your website.', 'wtpsw'); ?></p>
									<a class="button button-primary button-orange" href="<?php echo esc_url( WTPSW_PLUGIN_LINK_UNLOCK ); ?>" target="_blank"><?php esc_html_e( 'Grab Now', 'wtpsw' ); ?></a>
								</div><!-- .inside -->
							</div><!-- #general -->

							<div class="postbox">
								<div class="postbox-header">
									<h2 class="hndle">
										<span><?php esc_html_e( 'How It Works - Display and Shortcode', 'wtpsw' ); ?></span>
									</h2>
								</div>
								<div class="inside">
									<table class="form-table">
										<tr>
											<th>
												<label><?php esc_html_e( 'Getting Started', 'wtpsw' ); ?></label>
											</th>
											<td>
												<p><?php esc_html_e( 'Trending Post display most visited post on your website. It works with WordPress default post type.', 'wtpsw' ); ?></p>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php esc_html_e( 'All Shortcodes', 'wtpsw' ); ?></label>
											</th>
											<td>
												<span class="wpos-copy-clipboard wtpsw-shortcode-preview">[wtpsw_popular_post]</span> – <?php esc_html_e( 'Trending Post Slider View', 'wtpsw' ); ?><br />
												<span class="wpos-copy-clipboard wtpsw-shortcode-preview">[wtpsw_carousel]</span> – <?php esc_html_e( 'Trending Post Carousel View', 'wtpsw' ); ?><br />
												<span class="wpos-copy-clipboard wtpsw-shortcode-preview">[wtpsw_gridbox]</span> – <?php esc_html_e( 'Trending Post Gridbox View', 'wtpsw'); ?>
												<br/><br/>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php esc_html_e('Documentation', 'wtpsw'); ?>:</label>
											</th>
											<td>
												<a class="button button-primary" href="https://docs.essentialplugin.com/trending-popular-post-slider-and-widget/" target="_blank"><?php esc_html_e('Check Documentation', 'wtpsw'); ?></a>
											</td>
										</tr>
									</table>
								</div>
							</div>

							<div class="postbox">
								<div class="postbox-header">
									<h2 class="hndle">
										<span><?php esc_html_e( 'Gutenberg Support', 'wp-testimonial-with-widget' ); ?></span>
									</h2>
								</div>
								<div class="inside">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label><?php esc_html_e( 'How it Work', 'wp-testimonial-with-widget' ); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php esc_html_e( 'Step-1. Go to the Gutenberg editor of your page.', 'wp-testimonial-with-widget' ); ?></li>
														<li><?php esc_html_e( 'Step-2. Search "testimonial" keyword in the gutenberg block list.', 'wp-testimonial-with-widget' ); ?></li>
														<li><?php esc_html_e( 'Step-3. Add any block of testimonial and you will find its relative options on the right end side.', 'wp-testimonial-with-widget' ); ?></li>
													</ul>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- .inside -->
							</div><!-- #general -->

							<div class="postbox">
								<div class="postbox-header">
									<h2 class="hndle">
										<span><?php esc_html_e( 'Help to improve this plugin!', 'wtpsw' ); ?></span>
									</h2>
								</div>
								<div class="inside">
									<p><?php esc_html_e('Enjoyed this plugin? You can help by rate this plugin', 'wtpsw'); ?> <a href="https://wordpress.org/support/plugin/wp-trending-post-slider-and-widget/reviews#new-post" target="_blank"><?php esc_html_e('5 stars!', 'wtpsw'); ?></a></p>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div>
					</div>
				</div>

				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="metabox-holder wpos-pro-box">
						<div class="meta-box-sortables">
							<div class="postbox">
								<h3 class="hndle">
									<span><?php esc_html_e( 'Upgrate to Pro', 'wtpsw' ); ?></span>
								</h3>
								<div class="inside">
									<ul class="wpos-list">
										<li>40+ stunning and cool designs for Grid, slider, carousel and gridbox</li>
										<li>8 shortcodes</li>
										<li>Visual Composer Page Builder Support</li>
										<li>6 different types of widgets</li>
										<li>Custom post type support</li>
										<li>Gutenberg Block Supports.</li>
										<li>WPBakery Page Builder Supports</li>
										<li>Elementor, Beaver and SiteOrigin Page Builder Support. <span class="wpos-new-feature">New</span></li>
										<li>Divi Page Builder Native Support. <span class="wpos-new-feature">New</span></li>
										<li>Fusion Page Builder (Avada) native support.<span class="wpos-new-feature">New</span></li>
										<li>WP Templating Features</li>
										<li>Display Desired post include and exclude </li>
										<li>Display posts with include categories and exclude categories</li>
										<li>Display posts with particular include author and exclude author</li>
										<li>Custom CSS</li>
										<li>100% Multi language</li>
									</ul>
									<div class="upgrade-to-pro">Gain access to <strong>WP Trending Post Slider and Widget</strong> included in <br /><strong>Essential Plugin Bundle</div>
									<a class="button button-primary wpos-button-full button-orange" href="<?php echo esc_url(WTPSW_PLUGIN_LINK_UNLOCK); ?>" target="_blank"><?php esc_html_e( 'Grab Now', 'wtpsw' ); ?></a>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-container-1 -->
			</div>
		</div>
	</div>
</div>