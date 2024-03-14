<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package WP Slick Slider and Image Carousel
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wrap wpsisacm-wrap">
	<style type="text/css">
		.wpos-new-feature{font-size: 10px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal;}
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpsisacm-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpsisacm-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
	</style>
	<h2><?php esc_html_e( 'How It Works', 'wp-slick-slider-and-image-carousel' ); ?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<div id="post-body-content">

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'How It Works - Display and Shortcode', 'wp-slick-slider-and-image-carousel' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e( 'Getting Started', 'wp-slick-slider-and-image-carousel' ); ?></label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e( 'Step-1. Go to "Slick Slider --> Add Slide tab".', 'wp-slick-slider-and-image-carousel' ); ?></li>
												<li><?php esc_html_e( 'Step-2. Add image title, description and images as a featured image', 'wp-slick-slider-and-image-carousel' ); ?></li>
												<li><?php esc_html_e( 'Step-3. Repeat this process for number of slides you want.', 'wp-slick-slider-and-image-carousel' ); ?></li>
												<li><?php esc_html_e( 'Step-4. To display multiple slider, you can use category shortcode under "Slick Slider--> Slider Category"', 'wp-slick-slider-and-image-carousel' ); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e( 'How Shortcode Works', 'wp-slick-slider-and-image-carousel' ); ?></label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e( 'Step-1. Create a page like Slider OR add the shortcode in any page.', 'wp-slick-slider-and-image-carousel' ); ?></li>
												<li><?php esc_html_e( 'Step-2. Put below shortcode as per your need.', 'wp-slick-slider-and-image-carousel' ); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e( 'All Shortcodes', 'wp-slick-slider-and-image-carousel' ); ?></label>
										</th>
										<td>
											<span class="wpsisacm-shortcode-preview wpos-copy-clipboard">[slick-slider]</span> – <?php esc_html_e( 'Slick slider Shortcode (design-1 to design-5)', 'wp-slick-slider-and-image-carousel' ); ?> <br />
											<span class="wpsisacm-shortcode-preview wpos-copy-clipboard">[slick-carousel-slider]</span> – <?php esc_html_e( 'Slick slider carousel Shortcode (design-6)', 'wp-slick-slider-and-image-carousel' ); ?> <br />
											<span class="wpsisacm-shortcode-preview wpos-copy-clipboard">[slick-carousel-slider centermode="true"]</span> – <?php esc_html_e( 'Slick slider carousel with center mode Shortcode (design-6)', 'wp-slick-slider-and-image-carousel' ); ?> <br />
											<span class="wpsisacm-shortcode-preview wpos-copy-clipboard">[slick-carousel-slider variablewidth="true" centermode="true"]</span> – <?php esc_html_e( 'Slick slider carousel with variable width Shortcode (design-6)', 'wp-slick-slider-and-image-carousel' ); ?>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e( 'Documentation', 'wp-slick-slider-and-image-carousel' ); ?></label>
										</th>
										<td>
											<a class="button button-primary" href="https://docs.essentialplugin.com/wp-slick-slider-and-image-carousel/" target="_blank"><?php esc_html_e('Check Documentation', 'wp-slick-slider-and-image-carousel'); ?></a>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e( 'Demo', 'wp-slick-slider-and-image-carousel' ); ?></label>
										</th>
										<td>
											<a class="button button-primary" href="https://demo.essentialplugin.com/slick-slider-demo/" target="_blank"><?php esc_html_e( 'Check Free Demo', 'wp-slick-slider-and-image-carousel' ); ?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Gutenberg Support', 'wp-slick-slider-and-image-carousel' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e( 'How it Work', 'wp-slick-slider-and-image-carousel' ); ?></label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e( 'Step-1. Go to the Gutenberg editor of your page.', 'wp-slick-slider-and-image-carousel' ); ?></li>
												<li><?php esc_html_e( 'Step-2. Search "Slick Slider" keyword in the Gutenberg block list.', 'wp-slick-slider-and-image-carousel' ); ?></li>
												<li><?php esc_html_e( 'Step-3. Add any block of slick slider and you will find its relative options on the right end side.', 'wp-slick-slider-and-image-carousel' ); ?></li>
											</ul>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

				<!-- Help to improve this plugin! -->
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Help to improve this plugin!', 'wp-slick-slider-and-image-carousel' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<p><?php esc_html_e( 'Enjoyed this plugin? You can help by rate this plugin', 'wp-slick-slider-and-image-carousel'); ?> <a href="https://wordpress.org/support/plugin/wp-slick-slider-and-image-carousel/reviews/" target="_blank"><?php esc_html_e( '5 stars!', 'wp-slick-slider-and-image-carousel'); ?></a></p>
						</div>
					</div>
				</div><!-- .meta-box-sortables -->
			</div><!-- .post-body-content -->

			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox wpos-pro-box">
						<h3 class="hndle">
							<span><?php esc_html_e( 'Slick Slider Premium Features', 'wp-slick-slider-and-image-carousel' ); ?></span>
						</h3>
						<div class="inside">
							<ul class="wpos-list">
								<li><?php esc_html_e( '90+ Predefined stunning designs', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( '30 Image Slider Designs', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( '30 Image Carousel and Center Slider Designs', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( '33 Slider Variable width Designs', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'Drag & Drop order change', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'Gutenberg Block Supports', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'WPBakery Page Builder Supports', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'Elementor, Beaver and SiteOrigin Page Builder Support. ', 'wp-slick-slider-and-image-carousel'); ?> <span class="wpos-new-feature">New</span></li>
								<li><?php esc_html_e( 'Divi Page Builder Native Support.', 'wp-slick-slider-and-image-carousel'); ?> <span class="wpos-new-feature">New</span></li>
								<li><?php esc_html_e( 'Fusion Page Builder (Avada) native support.', 'wp-slick-slider-and-image-carousel'); ?> <span class="wpos-new-feature">New</span></li>
								<li><?php esc_html_e( 'WP Templating Features', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'Custom CSS', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'Slider Center Mode Effect', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'Slider RTL support', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( 'Fully responsive', 'wp-slick-slider-and-image-carousel'); ?></li>
								<li><?php esc_html_e( '100% Multi language', 'wp-slick-slider-and-image-carousel'); ?></li>
							</ul>
							<div class="upgrade-to-pro"><?php esc_html_e( 'Gain access to', 'wp-slick-slider-and-image-carousel'); ?> <strong><?php esc_html_e( 'WP Slick Slider and Image Carousel', 'wp-slick-slider-and-image-carousel'); ?></strong></div>
							<a class="button button-primary wpos-button-full button-orange" href="<?php echo esc_url(WPSISAC_PLUGIN_LINK_UNLOCK); ?>" target="_blank"><?php esc_html_e( 'Grab Slick Slider Now', 'wp-slick-slider-and-image-carousel' ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- end .wpsisacm-wrap -->