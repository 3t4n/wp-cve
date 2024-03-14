<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$prefix					= LSWSS_META_PREFIX; // Metabox prefix
$display_type_list		= lswss_display_type();
$logo_grid_designs		= lswss_logo_grid_designs();
$logo_slider_designs	= lswss_logo_slider_designs();

// Taking some variables
$gallery_imgs			= get_post_meta( $post->ID, $prefix.'gallery_id', true );
$display_type 			= get_post_meta( $post->ID, $prefix.'display_type', true );
$post_sett				= lswss_get_post_sett( $post->ID );

$display_type			= ! empty( $display_type ) ? $display_type : 'slider';
$no_img_cls				= ! empty( $gallery_imgs ) ? 'lswssp-hide' : '';
$style_mngr_link		= add_query_arg( array( 'post_type' => LSWSS_POST_TYPE, 'page' => 'lswssp-styles' ), admin_url( 'edit.php' ) );
$upgrade_link			= add_query_arg( array('page' => 'logo-showcase-with-slick-slider-pricing'), admin_url('admin.php') );
?>

<div class="lswssp-wrap lswssp-sett-wrap lswssp-clearfix">
	<table class="form-table lswssp-tbl lswssp-post-sett-table">
		<tbody>
			<tr>
				<th>
					<label><?php _e('Logo Images', 'logo-showcase-with-slick-slider'); ?></label>
				</th>
				<td>
					<button type="button" class="button button-primary lswssp-img-uploader" id="lswssp-gallery-imgs" data-multiple="true" data-button-text="<?php esc_html_e('Add to Logo Showcase', 'logo-showcase-with-slick-slider'); ?>" data-title="<?php _e('Add Images to Logo Showcase', 'logo-showcase-with-slick-slider'); ?>"><i class="dashicons dashicons-format-gallery"></i> <?php esc_html_e('Choose Logo Images', 'logo-showcase-with-slick-slider'); ?></button>
					<button type="button" class="button button-primary lswssp-del-gallery-imgs"><i class="dashicons dashicons-trash"></i> <?php esc_html_e('Remove All Logo Images', 'logo-showcase-with-slick-slider'); ?></button>
					<br/>

					<div class="lswssp-gallery-imgs-prev lswssp-imgs-preview lswssp-gallery-imgs-wrp" data-nonce="<?php echo esc_attr( wp_create_nonce( 'lswss_get_attachment_data_nonce' ) ); ?>">
						<?php if( ! empty( $gallery_imgs ) ) {
							foreach ($gallery_imgs as $img_key => $img_data) {

								$attachment_url 		= wp_get_attachment_thumb_url( $img_data );
								$attachment_edit_link	= get_edit_post_link( $img_data );
						?>
								<div class="lswssp-img-wrp">
									<div class="lswssp-img-tools">
										<span class="lswssp-tool-icon lswssp-edit-img dashicons dashicons-edit" title="<?php esc_html_e('Edit Image in Popup', 'logo-showcase-with-slick-slider'); ?>"></span>
										<a href="<?php echo esc_url( $attachment_edit_link ); ?>" target="_blank" title="<?php esc_html_e('Edit Image', 'logo-showcase-with-slick-slider'); ?>"><span class="lswssp-tool-icon lswssp-edit-attachment dashicons dashicons-visibility"></span></a>
										<span class="lswssp-tool-icon lswssp-del-tool lswssp-del-img dashicons dashicons-no" title="<?php esc_html_e('Remove Image', 'logo-showcase-with-slick-slider'); ?>"></span>
									</div>
									<img class="lswssp-img" src="<?php echo esc_url( $attachment_url ); ?>" alt="" />
									<input type="hidden" class="lswssp-attachment-no" name="lswss_img[]" value="<?php echo esc_attr( $img_data ); ?>" />
								</div>
						<?php }
						} ?>
						<p class="lswssp-img-placeholder <?php echo esc_attr( $no_img_cls ); ?>"><?php esc_html_e('No logo images selected.', 'logo-showcase-with-slick-slider'); ?></p>
					</div><!-- end .lswssp-imgs-preview -->
					<span class="description"><?php _e('Choose images for logo showcase. Hold Ctrl key to select multiple images at a time.', 'logo-showcase-with-slick-slider'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="lswssp-display-type"><?php _e('Logo Showcase Display Type', 'logo-showcase-with-slick-slider'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>display_type" class="lswssp-select-box lswssp-display-type" id="lswssp-display-type">
						<?php
						if( ! empty( $display_type_list ) ) {
							foreach ($display_type_list as $key => $value) {
								
								$disable_opt = ( $key == 'slider' || $key == 'grid' ) ? '' : 'disabled="disabled"';
								
								echo '<option value="'.esc_attr( $key ).'" '.selected( $display_type, $key, false ).' '.$disable_opt.'>'.esc_html( $value ).'</option>';
							}
						}
						?>
					</select>
					<br />
					<span class="description"><?php _e('Select logo showcase display type.', 'logo-showcase-with-slick-slider'); ?></span><br /><br />
					<span class="description"><i class="dashicons dashicons-lock"></i> <?php echo sprintf( __('For more layouts like Logo Ticker, Logo List, Logo Masonry, Logo Table and etc, please %scheck premium demo%s.', 'logo-showcase-with-slick-slider'), '<a href="https://premium.infornweb.com/logo-showcase-with-slick-slider-pro/" target="_blank">', '</a>' ); ?></span>
				</td>
			</tr>
		</tbody>
	</table><!-- end .lswssp-post-sett-table -->
	<br/>

	<?php
	// Logo Grid Meta Settings
	include_once( LSWSS_DIR . '/includes/admin/metabox/lswss-grid-sett.php' );

	// Logo Slider Meta Settings
	include_once( LSWSS_DIR . '/includes/admin/metabox/lswss-slider-sett.php' );	
	
	?>

	<input type="hidden" class="lswssp-selected-tab" name="<?php echo esc_attr( $prefix ); ?>sett[tab]" value="<?php echo esc_attr( $post_sett['tab'] ); ?>" />
</div>