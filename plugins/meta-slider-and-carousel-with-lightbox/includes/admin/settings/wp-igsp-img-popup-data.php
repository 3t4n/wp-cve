<?php
/**
 * Popup Image Data HTML
 *
 * @package Meta slider and carousel with lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$prefix = WP_IGSP_META_PREFIX;

// Taking some values
$alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

?>

<div class="wp-igsp-popup-title"><?php esc_html_e('Edit Image', 'meta-slider-and-carousel-with-lightbox'); ?></div>

<div class="wp-igsp-popup-body">

	<form method="post" class="wp-igsp-attachment-form">

		<?php if( ! empty( $attachment_url ) ) { ?>
		<div class="wp-igsp-popup-img-preview">
			<img src="<?php echo esc_url( $attachment_url ); ?>" alt="" />
		</div>
		<?php } ?>
		<a href="<?php echo get_edit_post_link( $attachment_id ); ?>" target="_blank" class="button right"><i class="dashicons dashicons-edit"></i> <?php esc_html_e('Edit Image From Attachment Page', 'meta-slider-and-carousel-with-lightbox'); ?></a>

		<table class="form-table">
			<tr>
				<th><label for="wp-igsp-attachment-title"><?php esc_html_e('Title', 'meta-slider-and-carousel-with-lightbox'); ?>:</label></th>
				<td>
					<input type="text" name="wp_igsp_attachment_title" value="<?php echo esc_attr( $attachment_post->post_title ); ?>" class="large-text wp-igsp-attachment-title" id="wp-igsp-attachment-title" />
					<span class="description"><?php esc_html_e('Enter image title.', 'meta-slider-and-carousel-with-lightbox'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="wp-igsp-attachment-alt-text"><?php esc_html_e('Alternative Text', 'meta-slider-and-carousel-with-lightbox'); ?>:</label></th>
				<td>
					<input type="text" name="wp_igsp_attachment_alt" value="<?php echo esc_attr( $alt_text ); ?>" class="large-text wp-igsp-attachment-alt-text" id="wp-igsp-attachment-alt-text" />
					<span class="description"><?php esc_html_e('Enter image alternative text.', 'meta-slider-and-carousel-with-lightbox'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="wp-igsp-attachment-caption"><?php esc_html_e('Caption', 'meta-slider-and-carousel-with-lightbox'); ?>:</label></th>
				<td>
					<textarea name="wp_igsp_attachment_caption" class="large-text wp-igsp-attachment-caption" id="wp-igsp-attachment-caption"><?php echo esc_attr($attachment_post->post_excerpt); ?></textarea>
					<span class="description"><?php esc_html_e('Enter image caption.', 'meta-slider-and-carousel-with-lightbox'); ?></span>
				</td>
			</tr>

			<tr class="wp-igsp-pro-feature">
				<th>
					<?php esc_html_e('Description ', 'meta-slider-and-carousel-with-lightbox'); ?><span class="wp-igsp-pro-tag"><?php esc_html_e('PRO','meta-slider-and-carousel-with-lightbox');?></span>
				</th>
				<td>
					<textarea name="wp_igsp_attachment_desc" class="large-text wp-igsp-attachment-desc" id="wp-igsp-attachment-desc" disabled=""></textarea>
					<span class="description"><?php esc_html_e('Enter image description.', 'meta-slider-and-carousel-with-lightbox'); ?></span><strong><?php echo sprintf( __( ' Utilize these <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'meta-slider-and-carousel-with-lightbox'), WP_IGSP_PLUGIN_LINK_UNLOCK); ?></strong>
				</td>
			</tr>

			<tr class="wp-igsp-pro-feature">
				<th>
					<?php esc_html_e('Image Link ', 'meta-slider-and-carousel-with-lightbox'); ?><span class="wp-igsp-pro-tag"><?php esc_html_e('PRO','meta-slider-and-carousel-with-lightbox');?></span>
				</th>
				<td>
					<input type="url" name="wp_igsp_attachment_link" value="" class="large-text wp-igsp-attachment-link" disabled=""/>
					<span class="description"><?php esc_html_e('Enter image link.', 'meta-slider-and-carousel-with-lightbox'); ?></span><?php echo sprintf( __( ' Utilize these <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'meta-slider-and-carousel-with-lightbox'), WP_IGSP_PLUGIN_LINK_UNLOCK); ?>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="right">
					<div class="wp-igsp-success wp-igsp-hide"></div>
					<div class="wp-igsp-error wp-igsp-hide"></div>
					<span class="spinner wp-igsp-spinner"></span>
					<button type="button" class="button button-primary wp-igsp-save-attachment-data" data-id="<?php echo esc_attr( $attachment_id ); ?>" data-nonce="<?php echo wp_create_nonce("wp-igsp-save-attachment-data-{$attachment_id}"); ?>"><?php esc_html_e('Save Changes', 'meta-slider-and-carousel-with-lightbox'); ?></button>
					<button type="button" class="button wp-igsp-popup-close"><?php esc_html_e('Close', 'meta-slider-and-carousel-with-lightbox'); ?></button>
				</td>
			</tr>
		</table>
	</form><!-- end .wp-igsp-attachment-form -->

</div><!-- end .wp-igsp-popup-body -->