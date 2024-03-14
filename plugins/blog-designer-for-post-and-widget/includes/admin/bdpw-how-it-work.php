<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Blog Designer - Post and Widget
 * @since 1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wrap bdpw-wrap">
	<style type="text/css">
		.wpos-box{box-shadow: 0 5px 30px 0 rgba(214,215,216,.57);background: #fff; padding-bottom:10px; position:relative;}
		.wpos-box ul{padding: 15px;}
		.wpos-box h5{background:#555; color:#fff; padding:15px; text-align:center;}
		.wpos-box h4{ padding:0 15px; margin:5px 0; font-size:18px;}
		.wpos-box .button{margin:0px 15px 15px 15px; text-align:center; padding:7px 15px; font-size:15px;display:inline-block;}
		.wpos-box .wpos-list{list-style:square; margin:10px 0 0 20px;}
		.wpos-clearfix:before, .wpos-clearfix:after{content: "";display: table;}
		.wpos-clearfix::after{clear: both;}
		.wpos-clearfix{clear: both;}
		.wpos-col{width: 47%; float: left; margin-right:10px; margin-bottom:10px;}
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box.postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.bdpw-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.bdpw-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.wpos-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.button-orange{background: #ff5d52 !important;border-color: #ff5d52 !important; font-weight: 600;}
		.button-blue{background: #0055fb !important;border-color: #0055fb !important; font-weight: 600;}
		.wpos-new-feature{font-size: 10px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal;}
	</style>

	<h2><?php esc_html_e( 'How It Works', 'blog-designer-for-post-and-widget' ); ?></h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!--How it workd HTML -->
			<div id="post-body-content">
				<div class="meta-box-sortables">

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'How It Works - Display and Shortcode', 'blog-designer-for-post-and-widget' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e('Getting Started', 'blog-designer-for-post-and-widget'); ?></label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1. Go to "Post --> Add New".', 'blog-designer-for-post-and-widget'); ?></li>
												<li><?php esc_html_e('Step-2. Add post title, description and images', 'blog-designer-for-post-and-widget'); ?></li>
												<li><?php esc_html_e('Step-3. Select Category and Tags', 'blog-designer-for-post-and-widget'); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('How Shortcode Works', 'blog-designer-for-post-and-widget'); ?></label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1. Create a page like Blog', 'blog-designer-for-post-and-widget'); ?></li>
												<li><?php esc_html_e('Step-2. Put below shortcode as per your need.', 'blog-designer-for-post-and-widget'); ?></li>
											</ul>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('All Shortcodes', 'blog-designer-for-post-and-widget'); ?></label>
										</th>
										<td>
											<span class="bdpw-shortcode-preview wpos-copy-clipboard">[wpspw_post]</span> – <?php esc_html_e('Blog Grid Shortcode', 'blog-designer-for-post-and-widget'); ?> <br />
											<span class="bdpw-shortcode-preview wpos-copy-clipboard">[wpspw_recent_post_slider]</span> – <?php esc_html_e('Recent Post Slider Shortcode', 'blog-designer-for-post-and-widget'); ?><br/>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('Documentation', 'blog-designer-for-post-and-widget'); ?></label>
										</th>
										<td>
											<a class="button button-primary" href="https://docs.essentialplugin.com/blog-designer-post-and-widget/" target="_blank"><?php esc_html_e('Check Documentation', 'blog-designer-for-post-and-widget'); ?></a>
										</td>
									</tr>

									<tr>
										<th>
											<label><?php esc_html_e('Demo', 'blog-designer-for-post-and-widget'); ?></label>
										</th>
										<td>
											<a class="button button-primary" href="https://demo.essentialplugin.com/blog-designer-post-and-widget/" target="_blank"><?php esc_html_e('Check Free Demo', 'blog-designer-for-post-and-widget'); ?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Gutenberg Support', 'blog-designer-for-post-and-widget' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<table class="form-table">
								<tbody>
									<tr>
										<th>
											<label><?php esc_html_e('How it Work', 'blog-designer-for-post-and-widget'); ?>:</label>
										</th>
										<td>
											<ul>
												<li><?php esc_html_e('Step-1. Go to the Gutenberg editor of your page.', 'blog-designer-for-post-and-widget'); ?></li>
												<li><?php esc_html_e('Step-2. Search "post grid" and "post slider" keyword in the Gutenberg block list.', 'blog-designer-for-post-and-widget'); ?></li>
												<li><?php esc_html_e('Step-3. Add any block of blog designer and you will find its relative options on the right end side.', 'blog-designer-for-post-and-widget'); ?></li>
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
								<span><?php esc_html_e( 'Help to improve this plugin!', 'blog-designer-for-post-and-widget' ); ?></span>
							</h2>
						</div>
						<div class="inside">
							<p><?php echo sprintf( __( 'Enjoyed this plugin? You can help by rate this plugin <a href="%s" target="_blank">5 stars!', 'blog-designer-for-post-and-widget'), 'https://wordpress.org/support/plugin/blog-designer-for-post-and-widget/reviews/#new-post' ); ?></a></p>
						</div><!-- .inside -->
					</div><!-- #general -->
				</div><!-- .meta-box-sortables -->

			</div><!-- #post-body-content -->

			<!--Upgrad to Pro HTML -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox wpos-pro-box">
						<div class="postbox-header">
							<h2 class="hndle">
								<span><?php esc_html_e( 'Blog Designer Premium Features', 'blog-designer-for-post-and-widget' ); ?></span>
							</h2	>
						</div>	
						<div class="inside">
							<ul class="wpos-list">
								<li><?php esc_html_e( '130+ stunning and cool layouts.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '8 Shortcodes.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '50 Designs for Blog Post Grid.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '45 Designs for Blog Post Slider/Carousel.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '24 Designs for Blog Post Masonry Layout.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '8 Designs for Blog Post List View.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '13 Designs for Blog Post Grid Box.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '8 Designs for Blog Post Grid Box Slider.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '5 types of Widgets (Grid, slider and list etc).', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Gutenberg Block Support.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Template overriding feature support.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Elementor, Beaver and SiteOrigin Page Builder Support.', 'blog-designer-for-post-and-widget'); ?> <span class="wpos-new-feature"><?php esc_html_e('New', 'blog-designer-for-post-and-widget'); ?></span></li>
								<li><?php esc_html_e( 'Divi Page Builder Native Support.', 'blog-designer-for-post-and-widget'); ?> <span class="wpos-new-feature"><?php esc_html_e('New', 'blog-designer-for-post-and-widget'); ?></span></li>
								<li><?php esc_html_e( 'Fusion Page Builder (Avada) native support.', 'blog-designer-for-post-and-widget'); ?> <span class="wpos-new-feature"><?php esc_html_e('New', 'blog-designer-for-post-and-widget'); ?></span></li>
								<li><?php esc_html_e( 'WPBakery Page Builder Supports.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Custom Read More link for Blog Post.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Blog display with categories.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Drag & Drop feature to display Blog post in your desired order and other 6 types of order parameter.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Two type Pagination with Next – Previous or Numeric type support with grid layout.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( 'Slider RTL support.', 'blog-designer-for-post-and-widget' ); ?></li>
								<li><?php esc_html_e( '100% Multilanguage.', 'blog-designer-for-post-and-widget' ); ?></li>
							</ul>
							<div class="upgrade-to-pro"><?php echo sprintf( esc_html__('Gain access to %sBlog Designer - Post and Widget Pro%s', 'blog-designer-for-post-and-widget' ), '<strong>', '</strong>' ); ?></div>
							<a class="button button-primary wpos-button-full button-orange" href="<?php echo esc_url( BDPW_PLUGIN_LINK_UNLOCK ); ?>" target="_blank"><?php esc_html_e('Grab Blog Designer Now', 'blog-designer-for-post-and-widget'); ?></a>
						</div><!-- .inside -->
					</div><!-- #general -->

				</div><!-- .metabox-holder -->
			</div><!-- #post-container-1 -->

		</div><!-- #post-body -->
	</div><!-- #poststuff -->
</div>