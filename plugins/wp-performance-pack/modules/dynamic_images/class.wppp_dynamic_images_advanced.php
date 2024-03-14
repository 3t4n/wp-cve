<?php

class WPPP_Dynamic_Images_Advanced extends WPPP_Admin_Renderer {
	public function enqueue_scripts_and_styles () {
		parent::enqueue_scripts_and_styles();
	}

	public function add_help_tab () {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_dynimg',
			'title'	=> __( 'Overview', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "When using dynamic image resizing images don't get resized on upload. Instead resizing is done when an intermediate image size is first requested. This can significantly improve upload speed. Once created, the image can get saved and is then subsequently served directly.", 'wp-performance-pack' ) . '</p>'
							. '<p>' . __ ( "Not saving intermediate images is only recommended for testing environments or when using caching or CDN for both front and back end.", 'wp-performance-pack' ) . '</p>'
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_servemethod',
			'title'	=> __( 'Serve method', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "Dynamic images can be served using different methods which differ in how much of WordPress will be loaded. <ul><li><em>WordPress 404 handler</em> - Uses WordPress' internal 404 handling to create intermediate images. Using permalinks is required but no additional rewrite rules are created. This is the slowest and most compatible method.</li><li><em>Compatible rewrite</em> - Uses own rewrite rules to create intermediate images. Loads WordPress core and plugins but not the theme (using <code>WP_USE_THEMES</code>). This allows usage of other image processing plugins for image resizing (if they are based on WP_Image_Editor).</li><li><em>Rewrite fast</em> - Fastest method to create intermediate images. Uses own rewrite rules and <code>SHORT_INIT</code> to only load WordPress' core.</li></ul>", 'wp-performance-pack' ) . '</p>'
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_regenthumbs',
			'title'	=> __( 'Regenerate Thumbnails integration', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "Using this feature you can clean up old and unused intermediate images. WPPP will hook into one of the supported plugins and instead of recreating intermediate images they will get deleted.", 'wp-performance-pack' ) . '</p>'
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_exif',
			'title'	=> __( 'EXIF thumbs', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "Usage of EXIF thumbs for thumbnail creation improves peformance when creating small thumbs. If the intermediate image size is smaller than the size of the EXIF thumbnail, the EXIF image will be used for resizing instead of the full image, which is way faster and less memory intense. But be aware that EXIF thumbs might differ from the actual image, depending on the editing software used to create the image.", 'wp-performance-pack' ) . '</p>'
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_rewriterules',
			'title'	=> __( 'Inherit rewrite rules', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( 'Add <code>RewriteOption InheritDownBefore</code> to WPPPs rewrite rules.', 'wp-performance-pack' ) . '</p><p>' . __( "Some plugins (e.g. WebP Express) place override rewrite rules by placing .htaccess files in sub directories of your WordPress installation that can disable WPPPs rewrite rules for dynamic image generation. By enabling this option the base rewrite rules (not just WPPPs, but all defined in the .htaccess file in your WordPess installations base path) will be inherited by all sub directories of your installation, thus reenabling dynamic image creation by WPPP", 'wp-performance-pack' ) . '</p><p>' . __( 'Only applies if either "Compatible rewrite" or "Fast rewrite" is used as method.', 'wp-performance-pack' ) . '</p>'
		) );
	}

	public function render_options () {
		wp_localize_script( 'wppp-admin-script', 'wpppData', array (
			'dynimg-quality' => $this->wppp->options['dynimg_quality'],
		));
		?>
		<input type="hidden" <?php $this->e_opt_name('dynimg_quality'); ?> value="<?php echo $this->wppp->options['dynimg_quality']; ?>" />

		<h3 class="title"><?php _e( 'Dynamic image resizing', 'wp-performance-pack' ); ?></h3>
		<p><?php $this->e_switchButton( 'dynamic_images', !$this->is_dynamic_images_available() ); ?></p>
		<p class="description"><?php _e( "Create intermediate images on demand, not on upload. If you deactive dynamic image resizing after some time of usage you might have to recreate thumbnails using a plugin like Regenerate Thumbnails.", 'wp-performance-pack' ); ?></p>
		<?php $this->do_hint_permalinks( true ); ?>

		<?php /*<script>
			function updateUI() {
				var method = jQuery( '#modeselect' ).val();

				jQuery( '#servemethod' ).toggle( method != 'off' );
				jQuery( '#objectcache' ).toggle( method == 'nosave' );
				jQuery( '#inheritrewrite' ).toggle( jQuery( '#servemethodselect' ).val() != 'wordpress' );
			}
		</script> */ ?>

		<table class="form-table" style="clear:none">
			<?php /*<tr valign="top">
				<th scope="row"><?php _e( 'Create intermediate images', 'wp-performance-pack' ); ?></th>
				<td>
					<div>
						<select id="modeselect" onchange="updateUI();" >
							<option value="off">on upload (WP default / WPPP image handling off)</option>
							<option>on demand, save intermediate images in same folder as original</option>
							<option>on demand, save intermediate images in "wp-content/wppp/images"</option>
							<option value="nosave">on demand, don't save intermediate images</option>
						</select>
					</div>
				</td>
			</tr>

			<tr id="servemethod" valign="top">
				<th scope="row"><?php _e( 'Serve image method', 'wp-performance-pack' ); ?></th>
				<td>
					<p>
						<select id="servemethodselect" <?php $this->e_opt_name( 'dynimg_serve_method' ); ?> value="wordpress" onchange="updateUI();">
							<option value="wordpress" <?php echo ( $this->wppp->options[ 'dynimg_serve_method' ] === 'wordpress' ) ? 'selected="selected"' : ''; ?>><?php _e( 'WordPress 404 handler', 'wp-performance-pack' ); ?></option>
							<option value="use_themes" <?php echo ( $this->wppp->options[ 'dynimg_serve_method' ] === 'use_themes' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Compatible rewrite', 'wp-performance-pack' ); ?></option>
							<option value="short_init" <?php echo ( $this->wppp->options[ 'dynimg_serve_method' ] === 'short_init' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Fast rewrite', 'wp-performance-pack' );?></option>
						</select>
					</p>
					<p class="description"><?php _e( 'Method used to dynamically create intermediate images. "WordPress 404 handler" is the slowest and most compatible. "Compatible rewrite" and "Fast rewrite" add extra rewrite rules to your .htaccess file.', 'wp-performance-pack' ); ?></p>
					<br/>
					<div id="inheritrewrite">
						<?php $this->e_checkbox( 'rewrite_inherit', 'rewrite_inherit', __( 'Inherit rewrite rules', 'wp-performance-pack' ) ); ?>
						<p class="description"><?php _e( 'Add <code>RewriteOption InheritDownBefore</code> to WPPPs rewrite rules.', 'wp-performance-pack' ); ?> <?php _e( 'Only applies if either "Compatible rewrite" or "Fast rewrite" is used as method.', 'wp-performance-pack' ) ?></p>
					</div>
				</td>
			</tr>


			<tr valign="top" id="objectcache">
				<th scope="row"><?php _e( "Use Object Cache", 'wppp-performance-pack' ); ?></th>
				<td>
					<p><?php $this->e_switchButton( 'dynamic_images_cache' ); ?></p>
					<p class="description"><?php _e( "If enabled, intermediate images smaller than 64kb in size will be stored and served using WordPress' Object Cache API to reduce CPU usage.", 'wp-performance-pack' ); ?></p>
					<?php $this->do_hint_caching(); ?>
				</td>
			</tr>


			<tr><td colspan="2"><hr/></td></tr>
			*/ ?>

			<tr valign="top">
				<th scope="row"><?php _e( 'Serve image method', 'wp-performance-pack' ); ?></th>
				<td>
					<p>
						<select <?php $this->e_opt_name( 'dynimg_serve_method' ); ?> value="short_init">
							<option value="wordpress" <?php echo ( $this->wppp->options[ 'dynimg_serve_method' ] === 'wordpress' ) ? 'selected="selected"' : ''; ?>><?php _e( 'WordPress 404 handler', 'wp-performance-pack' ); ?></option>
							<option value="use_themes" <?php echo ( $this->wppp->options[ 'dynimg_serve_method' ] === 'use_themes' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Compatible rewrite', 'wp-performance-pack' ); ?></option>
							<option value="short_init" <?php echo ( $this->wppp->options[ 'dynimg_serve_method' ] === 'short_init' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Fast rewrite', 'wp-performance-pack' );?></option>
						</select>
					</p>
					<p class="description"><?php _e( 'Method used to dynamically create intermediate images. "WordPress 404 handler" is the slowest and most compatible. "Compatible rewrite" and "Fast rewrite" add extra rewrite rules to your .htaccess file.', 'wp-performance-pack' ); ?></p>
					<br/>
					<?php $this->e_checkbox( 'rewrite_inherit', 'rewrite_inherit', __( 'Inherit rewrite rules', 'wp-performance-pack' ) ); ?>
					<p class="description"><?php _e( 'Add <code>RewriteOption InheritDownBefore</code> to WPPPs rewrite rules.', 'wp-performance-pack' ); ?> <?php _e( 'Only applies if either "Compatible rewrite" or "Fast rewrite" is used as method.', 'wp-performance-pack' ) ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Image saving', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_checkbox( 'dynamic_images_nosave', 'dynamic_images_nosave', __( "Don't save images to disc", 'wp-performance-pack' ), !$this->is_dynamic_images_available() ); ?>
					<p class="description"><?php _e( 'Dynamically recreate intermediate images on each request.', 'wp-performance-pack' ); ?></p>
					<br/>
					<p><?php _e( 'Save intermediate images into:', 'wp-performance-pack' ); ?><br/>
						<select <?php $this->e_opt_name( 'dynamic_images_thumbfolder' ); ?>>
							<option value="false" <?php echo( $this->wppp->options[ 'dynamic_images_thumbfolder' ] === false ) ? 'selected="selected"' : ''; ?>> <?php _e( 'Same folder (WP default)', 'wp-performance-pack' ); ?></option>
							<option value="true" <?php echo( $this->wppp->options[ 'dynamic_images_thumbfolder' ] === true ) ? 'selected="selected"' : ''; ?>> <?php _e( 'Mirrored into "wp-content/wppp/images"', 'wp-performance-pack' ); ?></option>
						</select>
					</p>				
					<p class="description"><?php printf( __( "Only applied if %s is <strong>not</strong> activated. When using WordPress 404 handler as serving method anything other than %s will slow down your site!", 'wp-performance-pack' ), '"' . __( "Don't save images to disc", 'wp-performance-pack' ) . '"', '"' . __( 'Same folder (WP default)', 'wp-performance-pack' ) . '"' ); ?></p>
					<br/>
					<?php $this->e_checkbox( 'dynimg-cache', 'dynamic_images_cache', __( 'Use caching', 'wp-performance-pack' ), !$this->is_dynamic_images_available() ); ?>
					<p class="description"><?php printf( __( "Cache intermediate images using Use WordPress' Object Cache API. Only applied if %s is activated.", 'wp-performance-pack' ), '"' . __( "Don't save images to disc", 'wp-performance-pack' ) . '"' ) ; ?></p>
					<?php $this->do_hint_caching(); ?>
					<br/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Use EXIF thumbnails', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'dynamic_images_exif_thumbs', !$this->is_exif_available() ); ?>
					<p class="description"><?php _e( 'If available use EXIF thumbnail to create intermediate images smaller than the specified size. <strong>Note that, depending on image editing software, the EXIF thumbnail might differ from the actual image!</strong>', 'wp-performance-pack'); ?></p>
					<br/>
					<p><?php _e( 'Use EXIF thumbs for image sizes up to the following dimensions:' ,'wp-performance-pack' ); ?></p>
					<p>Width: <input type="text" <?php $this->e_opt_name( 'exif_width' ); ?> value="<?php echo $this->wppp->options['exif_width']; ?>" size="8"> Height: <input type="text" <?php $this->e_opt_name( 'exif_height' ); ?> value="<?php echo $this->wppp->options['exif_height']; ?>" size="8"></p>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Image quality', 'wp-performance-pack' );?></th>
				<td>
					<div id="dynimg-quality-slider" style="width:25em; margin-bottom:2em;"></div>
					<p class="description"><?php _e( 'Quality setting for newly created intermediate images.', 'wp-performance-pack' );?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Regenerate Thumbnails integration', 'wp-performance-pack' );?></th>
				<td>
					<?php $this->e_switchButton( 'dynamic_images_rthook', !$this->is_regen_thumbs_available() || !$this->is_dynamic_images_available() ); ?>
					<p class="description"><?php _e( 'Integrate into thumbnail regeneration plugins to delete existing intermediate images.', 'wp-performance-pack' ); ?></p>
					<?php $this->do_hint_regen_thumbs( false ); ?>
					<br/>
					<?php $this->e_checkbox( 'dynimg-rtforce', 'dynamic_images_rthook_force', __( 'Force delete of all potential thumbnails', 'wp-performance-pack' ), !$this->is_regen_thumbs_available() || !$this->is_dynamic_images_available() ); ?>
					<p class="description"><?php _e( 'Delete all potential intermediate images (i.e. those matching the pattern "<em>imagefilename-*x*.ext</em>") while regenerating. <strong>Use with care as this option might delete files that are no thumbnails!</strong>', 'wp-performance-pack' );?></p>
				</td>
			</tr>
		</table>

		<hr/>
		<?php
	}
}