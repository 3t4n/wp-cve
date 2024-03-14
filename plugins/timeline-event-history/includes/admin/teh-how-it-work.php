<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Timeline and History
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap timline-wp-wrap">
	<style type="text/css">
		.teh-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.teh-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .teh-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.timline-wp-wrap .teh-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.timline-wp-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.teh-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.teh-new-feature{ font-size: 10px; margin-left:2px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff2700 !important;border-color: #ff2700 !important; font-weight: 600;}
	</style>
	<h2><?php _e( 'Documentation', timeline_wp ); ?></h2>
	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="meta-box-sortables">
						
						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Need Support & Solutions?', timeline_wp ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<td>
												<p><?php _e('Boost design and best solution for your website.', timeline_wp); ?></p> <br/>
												<a class="button button-primary button-orange" href="<?php echo TIMELINE_WP_PLUGIN_UPGRADE?>" target="_blank"><?php _e('Buy Now', timeline_wp); ?></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Documentation - Display and shortcode', timeline_wp ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th>
												<label><?php _e('Geeting Started with Timeline Event History', timeline_wp); ?>:</label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Go to "Timeline WP --> Add New".', timeline_wp); ?></li>
													<li><?php _e('Step-2. Add title, Date , Icon type and description and Publish.', timeline_wp); ?></li>
													
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('How Shortcode Works', timeline_wp); ?>:</label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Create a page like Timeline OR Post Timeline.', timeline_wp); ?></li>
													<li><?php _e('Step-2. Put below shortcode as per your need.', timeline_wp); ?></li>
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('All Shortcodes', timeline_wp); ?>:</label>
											</th>
											<td>
												<span class="teh-copy-clipboard timline-wp-shortcode-preview">[timeline_wp id="id number"]</span> – <?php _e('Timeline WP Shortcode', timeline_wp); ?>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('Documentation', timeline_wp); ?>:</label>
											</th>
											<td>
												<a class="button button-primary" href="https://blogwpthemes.com/docs/timeline-event-history-plugin-documentation/
" target="_blank"><?php _e('Check Documentation', timeline_wp); ?></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Gutenberg Support', timeline_wp ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th>
												<label><?php _e('How it Work', timeline_wp); ?>:</label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Go to the Gutenberg editor of your page.', timeline_wp); ?></li>
													<li><?php _e('Step-2. Search "Timeline WP" keyword in the gutenberg block list.', timeline_wp); ?></li>
													<li><?php _e('Step-3. Add any block of Timeline WP and you will find its relative options on the right end side.', timeline_wp); ?></li>
												</ul>
											</td>
										</tr>											
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- .postbox -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Help to improve this plugin!', timeline_wp ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<p><?php _e('Enjoyed this plugin? You can help by rate this plugin ', timeline_wp); ?><a href="https://wordpress.org/support/plugin/timeline-event-history/reviews/" target="_blank"><?php _e('5 stars!', timeline_wp); ?></a></p>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-body-content -->

				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox teh-pro-box">
							<h3 class="hndle">
								<span><?php _e( 'Upgrade to Pro', timeline_wp ); ?></span>
							</h3>
							<div class="inside">
								<ul class="teh-list">
									<li><?php _e( '17+ cool designs', timeline_wp ); ?></li>
									<li><?php _e( 'Create unlimited timeline stories inside your WordPress website or blog.', timeline_wp ); ?></li>
									<li><?php _e( 'Use via 1 Shortcode and adding 17+ Designs', timeline_wp ); ?></li>
									<li><?php _e( 'Vertical and Horizontal Timeline', timeline_wp ); ?></li>
									<li><?php _e( 'Also work with WordPress POST', timeline_wp ); ?></li>
									<li><?php _e( 'Timeline Category Management – Add stories in specific category.', timeline_wp ); ?></li>
									<li><?php _e( 'Timeline Stories Content Format – Add font awesome icon to display timeline stories format.', timeline_wp ); ?></li>
									<li><?php _e( 'Timeline Scrolling Navigation – Quickly and easily navigate your timeline with a beautiful scrolling navigation inside your timeline.', timeline_wp ); ?></li>
									<li><?php _e( 'Mobile Compatibility View', timeline_wp ); ?></li>
									<li><?php _e( 'Gutenberg Block Supports.', timeline_wp); ?></li>
									
									<li><?php _e( 'Elementor, Beaver and SiteOrigin Page Builder Support.', timeline_wp); ?> <span class="teh-new-feature">New</span></li>
									<li><?php _e( 'Divi Page Builder Native Support.', timeline_wp); ?> <span class="teh-new-feature">New</span></li>
									
									<li><?php _e( 'WP Templating Features', timeline_wp ); ?></li>
									<li><?php _e( 'Custom CSS', timeline_wp ); ?></li>
									<li><?php _e( 'Fully responsive', timeline_wp ); ?></li>
									
								</ul>
								<div class="upgrade-to-pro"><?php echo sprintf( __( 'Gain access to <strong>Timeline Event History Pro</strong>', timeline_wp ) ); ?></div>
								<a class="button button-primary teh-button-full button-orange" href="<?php echo TIMELINE_WP_PLUGIN_UPGRADE; ?>" target="_blank"><?php _e('Buy Now', timeline_wp); ?></a>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-container-1 -->
			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div>
</div>