<?php
/**
 * Popup Image Data HTML
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$prefix = LSWSS_META_PREFIX;

// Taking some values
$alt_text 			= get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
$attachment_link 	= get_post_meta( $attachment_id, $prefix.'attachment_link', true );
?>

<div class="lswssp-popup-title"><?php _e('Edit Logo', 'logo-showcase-with-slick-slider'); ?></div>

<div class="lswssp-popup-body">
	<form method="post" class="lswssp-attachment-form">
		
		<?php if( ! empty( $attachment_post->guid ) ) { ?>
		<div class="lswssp-popup-img-preview">
			<img src="<?php echo esc_url( $attachment_post->guid ); ?>" alt="" />
		</div>
		<?php } ?>
		<a href="<?php echo get_edit_post_link( $attachment_id ); ?>" target="_blank" class="button button-primary right"><i class="dashicons dashicons-edit"></i> <?php esc_html_e('Edit Logo From Attachment Page', 'logo-showcase-with-slick-slider'); ?></a>

		<table class="form-table">
			<tr>
				<th><label for="lswssp-attachment-title"><?php _e('Logo Title', 'logo-showcase-with-slick-slider'); ?></label></th>
				<td>
					<input type="text" name="lswss_attachment_title" value="<?php echo esc_attr( $attachment_post->post_title ); ?>" class="large-text lswssp-attachment-title" id="lswssp-attachment-title" />
					<span class="description"><?php _e('Enter logo title.', 'logo-showcase-with-slick-slider'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="lswssp-attachment-alt-text"><?php _e('Alternative Text', 'logo-showcase-with-slick-slider'); ?></label></th>
				<td>
					<input type="text" name="lswss_attachment_alt" value="<?php echo esc_attr( $alt_text ); ?>" class="large-text lswssp-attachment-alt-text" id="lswssp-attachment-alt-text" />
					<span class="description"><?php _e('Enter logo image alternative text.', 'logo-showcase-with-slick-slider'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="lswssp-attachment-desc"><?php _e('Logo Description', 'logo-showcase-with-slick-slider'); ?></label></th>
				<td>
					<textarea name="lswss_attachment_desc" class="large-text lswssp-attachment-desc" id="lswssp-attachment-desc" rows="5"><?php echo esc_textarea( $attachment_post->post_content ); ?></textarea>
					<span class="description"><?php _e('Enter logo description.', 'logo-showcase-with-slick-slider'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="lswssp-attachment-link"><?php _e('Logo Link', 'logo-showcase-with-slick-slider'); ?></label></th>
				<td>
					<input type="text" name="lswss_attachment_link" value="<?php echo esc_url( $attachment_link ); ?>" class="large-text lswssp-attachment-link" id="lswssp-attachment-link" />
					<span class="description"><?php _e('Enter logo link. e.g. https://premium.infornweb.com', 'logo-showcase-with-slick-slider'); ?></span>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="right">
					<div class="lswssp-success lswssp-hide"></div>
					<div class="lswssp-error lswssp-hide"></div>
					<span class="spinner lswssp-spinner"></span>
					<button type="button" class="button button-primary lswssp-save-attachment-data" data-id="<?php echo esc_attr( $attachment_id ); ?>"><?php esc_html_e('Save Changes', 'logo-showcase-with-slick-slider'); ?></button>
					<button type="button" class="button lswssp-popup-close"><?php esc_html_e('Close', 'logo-showcase-with-slick-slider'); ?></button>
					<input type="hidden" name="lswss_nonce" value="<?php echo esc_attr( wp_create_nonce( 'lswss_save_attachment_data_nonce_' . $attachment_id ) ); ?>" />
				</td>
			</tr>
		</table>
	</form><!-- end .lswssp-attachment-form -->
</div><!-- end .lswssp-popup-body -->