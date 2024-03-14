<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Accordion and Accordion Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap wp-aas-wrap">
	<h2><?php esc_html_e( 'How It Works', 'accordion-and-accordion-slider' ); ?></h2>
	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wp-aas-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wp-aas-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
	</style>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">

			<!--How it workd HTML -->
			<div id="post-body-content">
				<div class="meta-box-sortables">

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'How It Works - Display and shortcode', 'accordion-and-accordion-slider' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e('Geeting Started with Accordion Slider', 'accordion-and-accordion-slider'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1. Go to "Accordion Slider --> Add New".', 'accordion-and-accordion-slider'); ?></li>
												<li><?php esc_html_e('Step-2. Enter Accordion Slider Title.', 'accordion-and-accordion-slider'); ?></li>
												<li><?php esc_html_e('Step-3. Under "Choose Gallery Images" click on "Gallery Images" button and select multiple images from WordPress media and click on "Add to Gallery" button..', 'accordion-and-accordion-slider'); ?></li>
												<li><?php esc_html_e('Step-4. You can use accordion slider parameters as per your need.', 'accordion-and-accordion-slider'); ?></li>
												<li><?php esc_html_e('Step-5. You can find out shortcode for accordion slider under "Accordion Slider" list view.', 'accordion-and-accordion-slider'); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('How Shortcode Works', 'accordion-and-accordion-slider'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1. Create a page like accordion slider OR add the shortcode in any page.', 'accordion-and-accordion-slider'); ?></li>
												
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('Shortcodes', 'accordion-and-accordion-slider'); ?>:</label>
										</th>
										<td>
											<span class="wp-aas-shortcode-preview wpos-copy-clipboard">[aas_slider id="XX"]</span> – <?php esc_html_e('Accordion Slider Shortcode.', 'accordion-and-accordion-slider'); ?> <br />
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('Documentation', 'accordion-and-accordion-slider'); ?>:</label>
										</th>
										<td>
											<a class="button button-primary" href="https://docs.essentialplugin.com/accordion-and-accordion-slider/" target="_blank"><?php esc_html_e('Check Documentation', 'accordion-and-accordion-slider'); ?></a>
										</td>
									</tr>
									
									<tr>
										<th>
											<label><?php esc_html_e('Demo', 'accordion-and-accordion-slider'); ?>:</label>
										</th>
										<td>
											<a class="button button-primary" href="https://demo.essentialplugin.com/accordion-and-accordion-slider-demo/" target="_blank"><?php esc_html_e('Check Demo', 'accordion-and-accordion-slider'); ?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Help to improve this plugin!', 'accordion-and-accordion-slider' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<p><?php esc_html_e('Enjoyed this plugin? You can help by rate this plugin', 'accordion-and-accordion-slider'); ?> <a href="https://wordpress.org/support/plugin/accordion-and-accordion-slider/reviews/#new-post" target="_blank"><?php esc_html_e('5 stars!', 'accordion-and-accordion-slider'); ?></a></p>
						</div><!-- .inside -->
					</div><!-- .postbox -->

				</div><!-- .meta-box-sortables -->
			</div><!-- #post-body-content -->
			
			<!--Upgrad to Pro HTML -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox wpos-pro-box">
						<h3 class="hndle">
							<span><?php  esc_html_e( 'Upgrate to Pro', 'accordion-and-accordion-slider' ); ?></span>
						</h3>
						<div class="inside">
							<ul class="wpos-list">
								<li>Fancy Box option.</li>
								<li>Added caption field to display image description.</li>
								<li>Drag & Drop images order change</li>
								<li>Fully responsive</li>
							</ul>
							<div class="upgrade-to-pro">Gain access to <strong>Accordion and Accordion Slider</strong> included in <br /><strong>Essential Plugin Bundle</div>
							<a class="button button-primary wpos-button-full button-orange" href="<?php echo esc_url(WP_AAS_PLUGIN_LINK_UNLOCK); ?>" target="_blank"><?php esc_html_e('Grab Accordion Slider Now', 'accordion-and-accordion-slider'); ?></a>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->
			</div><!-- #post-container-1 -->

		</div><!-- #post-body -->
	</div><!-- #poststuff -->

</div><!-- end .wp-aas-wrap -->