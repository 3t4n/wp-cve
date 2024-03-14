<?php

/**
 * Provide a admin area dashboard view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Ids
 * @subpackage Catch_Ids/admin/partials
 */
?>

<?php $options = catch_gallery_get_options(); ?>

<?php if( isset($_GET['settings-updated']) ) { ?>
	<div id="message" class="notice updated fade">
		<p><strong><?php esc_html_e( 'Plugin Options Saved.', 'catch-gallery' ) ?></strong></p>
	</div>
<?php } ?>

<?php // Use nonce for verification.
	wp_nonce_field( basename( __FILE__ ), 'catch_gallery_nounce' );
?>

<div id="catch-gallery" class="catch-gallery-main">
	<div class="content-wrapper">
		<div class="header">
			<h2><?php esc_html_e( 'Settings', 'catch-gallery' ); ?></h2>
		</div> <!-- .Header -->
		<div class="content">
			<form method="post" action="options.php">
				<?php settings_fields( 'catch-gallery-group' ); ?>
				<div class="option-container">
					<table class="form-table">
						<tbody>

							<tr>
								<th scope="row"><?php esc_html_e( 'Enable Carousel', 'catch-gallery' ); ?></th>
								<td>
									<input name="catch_gallery_options[carousel_enable]" id="catch_gallery_options[carousel_enable]" type="checkbox" value="1" class="catch_gallery_options[carousel_enable]" <?php isset( $options['carousel_enable'] ) ? checked( $options['carousel_enable'], 1 ) : ''; ?>/>
									<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Display images in full-size carousel slideshow', 'catch-gallery' ); ?>"></span>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php esc_html_e( 'Carousel Background Color', 'catch-gallery' ); ?></th>

								<td>
									<select id="catch_gallery_options[carousel_background_color]" name="catch_gallery_options[carousel_background_color]" class="carousel_background_color">
											<option value="black" <?php selected( $options['carousel_background_color'], 'scroll' ); ?>><?php esc_html_e( 'Black', 'catch-gallery'); ?></option>
											<option value="white" <?php selected( $options['carousel_background_color'], 'scroll' ); ?>><?php esc_html_e( 'White', 'catch-gallery'); ?></option>
									 </select>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php esc_html_e( 'Metadata', 'catch-gallery' ); ?></th>
								<td>
									<input name="catch_gallery_options[carousel_display_exif]" id="catch_gallery_options[carousel_display_exif]" type="checkbox" value="1" class="catch_gallery_options[carousel_display_exif]" <?php isset( $options['carousel_display_exif'] ) ? checked( $options['carousel_display_exif'], 1 ) : ''; ?>/>
									<p class="description"><?php printf( esc_html__( 'Show photo metadata (%1$sExif%2$s) in carousel, when available', 'catch-gallery' ), '<a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" target="_blank" rel="nofollow">', '</a>' ); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php esc_html_e( 'Show Comments', 'catch-gallery' ); ?></th>
								<td>
									<input name="catch_gallery_options[comments_display]" id="catch_gallery_options[comments_display]" type="checkbox" value="1" class="catch_gallery_options[comments_display]" <?php isset( $options['comments_display'] ) ? checked( $options['comments_display'], 1 ) : ''; ?>/>
									<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Show Comment box Below in the Slideshow', 'catch-gallery' ); ?>"></span>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php esc_html_e( 'Show View Fullsize', 'catch-gallery' ); ?></th>
								<td>
									<input name="catch_gallery_options[fullsize_display]" id="catch_gallery_options[fullsize_display]" type="checkbox" value="1" class="catch_gallery_options[fullsize_display]" <?php isset( $options['fullsize_display'] ) ? checked( $options['fullsize_display'], 1 ) : ''; ?>/>
									<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Display fill-size in carousel slideshow', 'catch-gallery' ); ?>"></span>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php esc_html_e( 'Reset Options', 'catch-gallery' ); ?></th>
								<td>
									<?php
										echo '<input name="catch_gallery_options[reset]" id="catch_gallery_options[reset]" type="checkbox" value="1" class="catch_gallery_options[reset]" />' . esc_html__( 'Check to reset', 'catch-gallery' );
									?>
									<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Caution: Reset all settings to default', 'catch-gallery' ); ?>"></span>
								</td>
							</tr>
						</tbody>
					</table>

				<?php submit_button( esc_html__( 'Save Changes', 'catch-gallery' ) ); ?>
				</div><!-- .option-container -->
			</form>
		</div><!-- .content -->
	</div> <!-- .content-wrapper -->
</div> <!-- Main Content-->
