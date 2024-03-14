<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Photo Gallery Builder
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap photo-gallery-wrap">
	<style type="text/css">
		.pgbp-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.pgbp-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .pgbp-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.photo-gallery-wrap .pgbp-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.photo-gallery-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.photo-gallery-upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.pgbp-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.pgbp-new-feature{ font-size: 10px; margin-left:2px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff2700 !important;border-color: #ff2700 !important; font-weight: 600;}
	</style>
	<h2><?php _e( 'Documentation','pg_builder' ); ?></h2>
	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="meta-box-sortables">
						
						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Need Support & Solutions?','pg_builder' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<td>
												<p><?php _e('Boost design and best solution for your website.','pg_builder'); ?></p> <br/>
												<a class="button button-primary button-orange" href="<?php echo PHOTO_GALLERY_BUILDER_UPGRADE?>" target="_blank"><?php _e('Buy Now','pg_builder'); ?></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Documentation - Display and shortcode','pg_builder' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th>
												<label><?php _e('Geeting Started with Photo Gallery
												 Builder Pro','pg_builder'); ?>:</label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Go to "Photo Gallery --> Add New".','pg_builder'); ?></li>
													<li><?php _e('Step-2. Add Photo,Give Title and description and link and Publish.','pg_builder'); ?></li>
													
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('How Shortcode Works','pg_builder'); ?>:</label>
											</th>
											<td>
												<ul>
													<li><?php _e('Step-1. Create a page like Photo Gallery.','pg_builder'); ?></li>
													<li><?php _e('Step-2. Put below shortcode as per your need.','pg_builder'); ?></li>
												</ul>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('All Shortcodes','pg_builder'); ?>:</label>
											</th>
											<td>
												<span class="pgbp-copy-clipboard photo-gallery-shortcode-preview">[photo-builder id="id number"]</span> – <?php _e('Photo Gallery Shortcode','pg_builder'); ?>
											</td>
										</tr>

										<tr>
											<th>
												<label><?php _e('Documentation','pg_builder'); ?>:</label>
											</th>
											<td>
												<a class="button button-primary" href="https://blogwpthemes.com/docs/photo-gallery-builder-documentation/" target="_blank"><?php _e('Check Documentation','pg_builder'); ?></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- .inside -->
						</div><!-- #general -->

						<div class="postbox">
							<div class="postbox-header">
								<h2 class="hndle">
									<span><?php _e( 'Help to improve this plugin!','pg_builder' ); ?></span>
								</h2>
							</div>
							<div class="inside">
								<p><?php _e('Enjoyed this plugin? You can help by rate this plugin ','pg_builder'); ?><a href="https://wordpress.org/plugins/photo-gallery-builder/#reviews" target="_blank"><?php _e('5 stars!','pg_builder'); ?></a></p>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-body-content -->

				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox pgbp-pro-box">
							<h3 class="hndle">
								<span><?php _e( 'Upgrade to Pro','pg_builder' ); ?></span>
							</h3>
							<div class="inside">
								<ul class="pgbp-list">
									<li><?php _e( '30+ cool designs','pg_builder' ); ?></li>
									<li><?php _e( 'Create unlimited Photo Gallery inside your WordPress website.','pg_builder' ); ?></li>
									
									<li><?php _e( 'Hexagone and Diamond Design Photo Gallery','pg_builder' ); ?></li>
									<li><?php _e( 'Grid and Masonry Design Photo Gallery','pg_builder' ); ?></li>
									<li><?php _e( 'Slider Design Photo Gallery','pg_builder' ); ?></li>
									<li><?php _e( 'Filterable Design Photo Gallery','pg_builder' ); ?></li>
									
									<li><?php _e( 'Photo Gallery Filter Category Management – Add photo gallery in specific category.','pg_builder' ); ?></li>
								
									
									<li><?php _e( 'Mobile Compatibility View','pg_builder' ); ?></li>
									
									<li><?php _e( 'Elementor, Beaver and SiteOrigin Page Builder Support.','pg_builder'); ?> <span class="pgbp-new-feature">New</span></li>
									<li><?php _e( 'Divi Page Builder Native Support.','pg_builder'); ?> <span class="pgbp-new-feature">New</span></li>
									
									<li><?php _e( 'WP Templating Features','pg_builder' ); ?></li>
									<li><?php _e( 'Custom CSS','pg_builder' ); ?></li>
									<li><?php _e( 'Fully responsive','pg_builder' ); ?></li>
									<li><?php _e( '500+ Font family','pg_builder' ); ?></li>
									
								</ul>
								<div class="photo-gallery-upgrade-to-pro"><?php echo sprintf( __( 'Gain access to <strong>Photo Gallery Builder Pro</strong>','pg_builder' ) ); ?></div>
								<a class="button button-primary pgbp-button-full button-orange" href="<?php echo PHOTO_GALLERY_BUILDER_UPGRADE; ?>" target="_blank"><?php _e('Buy Now','pg_builder'); ?></a>
							</div><!-- .inside -->
						</div><!-- #general -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #post-container-1 -->
			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div>
</div>