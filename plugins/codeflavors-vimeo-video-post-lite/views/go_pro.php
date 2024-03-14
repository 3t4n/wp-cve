<?php

namespace Vimeotheque;

use Vimeotheque\Admin\Helper_Admin;

?>
<div class="wrap vimeotheque about-wrap">
	<h1>
		<?php _e('Vimeotheque PRO', 'codeflavors-vimeo-video-post-lite')?>
	</h1>
	<p class="about-text" style="margin-left: 0; margin-right: 0;">
		<?php _e('Created having Vimeo publishers in mind, PRO version offers more tools to make your life easier and to allow you to focus on creating more high quality content.', 'codeflavors-vimeo-video-post-lite');?>
	</p>
    <hr />
    <h2><?php _e( 'Vimeotheque PRO vs Lite', 'codeflavors-vimeo-video-post-lite' );?></h2>
	<div class="class="feature-section two-col">
		<div class="two-col">
			<div class="col">
				<p>
					<h3><?php _e( 'Private videos import', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Allows you to create posts from your own private videos from Vimeo.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Automatic video import', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Automatically create video posts from Vimeo channels, categories, albums, uploads or groups with embedded video and full details (title, description, featured image). Once set up, the plugin will run the import process automatically.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Import videos as regular posts', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Choose to import videos as regular post type instead of plugin’s custom post type.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Import videos as WordPress theme posts', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'For video websites running video WordPress themes the plugin can import Vimeo videos as any post type needed by your theme and will automatically fill all custom fields needed by the theme to embed and display the video and its information.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Bulk import video image as post featured image', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Set up Vimeo video image as post featured image when importing videos as posts in WordPress.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Include video microdata in front-end', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'The plugin can optionally automatically create video microdata for SEO purposes directly in your pages.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'WordPress video theme compatibility layer', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'By default, the plugin is compatible with several WordPress video themes and can also be extended to include your theme if not supported.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>

				<p>
					<h3><?php _e( 'Full support', 'codeflavors-vimeo-video-post-lite' ) ;?></h3>
					<?php _e( 'Priority support and debugging directly on your website from the plugin developers.', 'codeflavors-vimeo-video-post-lite' ) ;?>
				</p>
				<p>
					<span class="yes">PRO</span>
					<span class="no">Lite</span>
				</p>
				<p class="gopro-btn-holder extra-space">
					<a class="button try-pro-btn" href="https://vvp-demo.codeflavors.com/" target="_blank"><?php _e( 'Try PRO version', 'codeflavors-vimeo-video-post-lite' ) ;?></a>
					<a class="button gopro-btn" href="<?php echo Helper_Admin::publisher_link( 'pricing' ) ;?>" target="_blank"><?php _e( 'Go PRO!', 'codeflavors-vimeo-video-post-lite' ) ;?></a>
				</p>
			</div>
		</div>
	</div>
	<hr />
	<div class="return-to-dashboard">
		<a href="<?php echo Helper_Admin::publisher_link(''); ?>">Vimeotheque PRO</a>
	</div>
</div>