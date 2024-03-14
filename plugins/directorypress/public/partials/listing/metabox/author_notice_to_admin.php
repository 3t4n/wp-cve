<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="directorypress-box">
				<div class="directorypress-box-head">
					<?php _e('Note from Listing Author', 'DIRECTORYPRESS'); ?>
				</div>
				<div class="directorypress-box-content wp-clearfix">
					<div class="admin-note-from-author">
						<?php 
							if(metadata_exists('post', $listing->post->ID, '_notice_to_admin' ) ) {
								$content = get_post_meta($listing->post->ID, '_notice_to_admin', true );
								echo '<p>' . wp_kses_post($content) . '</p>';
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>