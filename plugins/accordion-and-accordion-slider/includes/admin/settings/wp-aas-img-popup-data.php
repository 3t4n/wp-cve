<?php
/**
 * Popup Image Data HTML
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$prefix = WP_AAS_META_PREFIX;

// Taking some values
$alt_text 			= get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
$attachment_link 	= get_post_meta( $attachment_id, $prefix.'attachment_link', true );
?>

<div class="wp-aas-popup-title"><?php esc_html_e( 'Edit Image', 'accordion-and-accordion-slider' ); ?></div>
	
<div class="wp-aas-popup-body">

	<form method="post" class="wp-aas-attachment-form">
		
		<?php if( ! empty( $attachment_post->guid )) { ?>
		<div class="wp-aas-popup-img-preview">
			<img src="<?php echo esc_url($attachment_post->guid); ?>" alt="" />
		</div>
		<?php } ?>
		<a href="<?php echo get_edit_post_link( $attachment_id ); ?>" target="_blank" class="button right"><i class="dashicons dashicons-edit"></i> <?php esc_html_e( 'Edit Image From Attachment Page', ' accordion-and-accordion-slider' ); ?></a>

		<table class="form-table">
			<tr>
				<th><label for="wp-aas-attachment-title"><?php esc_html_e('Title', ' accordion-and-accordion-slider'); ?>:</label></th>
				<td>
					<input type="text" name="wp_aas_attachment_title" value="<?php echo esc_attr($attachment_post->post_title); ?>" class="large-text wp-aas-attachment-title" id="wp-aas-attachment-title" />
					<span class="description"><?php esc_html_e('Enter image title.', ' accordion-and-accordion-slider'); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="wp-aas-attachment-alt-text"><?php esc_html_e('Alternative Text', 'accordion-and-accordion-slider'); ?>:</label></th>
				<td>
					<input type="text" name="wp_aas_attachment_alt" value="<?php echo esc_attr($alt_text); ?>" class="large-text wp-aas-attachment-alt-text" id="wp-aas-attachment-alt-text" />
					<span class="description"><?php esc_html_e('Enter image alternative text.', 'accordion-and-accordion-slider'); ?></span>
				</td>
			</tr>		

			<tr class="wp-aas-pro-feature">
				<th>
					<?php esc_html_e('Caption ', 'accordion-and-accordion-slider'); ?><span class="wp-aas-pro-tag"><?php esc_html_e('PRO','accordion-and-accordion-slider');?></span>
				</th>
				<td>
					<textarea name="wp_igsp_attachment_desc" class="large-text wp-aas-attachment-desc" id="wp-aas-attachment-desc" disabled=""></textarea>
					<span class="description"><?php esc_html_e('Enter image caption.', 'accordion-and-accordion-slider'); ?></span><?php echo sprintf( __( ' Upgrade to <a href="%s" target="_blank">Premium Version (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'accordion-and-accordion-slider'), WP_AAS_PLUGIN_LINK_UNLOCK); ?>
				</td>
			</tr>

			<tr>
				<th><label for="wp-aas-attachment-link"><?php esc_html_e('Image Link', 'accordion-and-accordion-slider'); ?>:</label></th>
				<td>
					<input type="text" name="wp_aas_attachment_link" value="<?php echo esc_url($attachment_link); ?>" class="large-text wp-aas-attachment-link" id="wp-aas-attachment-link" />
					<span class="description"><?php esc_html_e('Enter image link. e.g', 'accordion-and-accordion-slider'); ?>https://www.essentialplugin.com</span>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="right">
					<div class="wp-aas-success wp-aas-hide"></div>
					<div class="wp-aas-error wp-aas-hide"></div>
					<span class="spinner wp-aas-spinner"></span>
					<button type="button" class="button button-primary wp-aas-save-attachment-data" data-id="<?php echo esc_attr($attachment_id); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce("wp-aas-save-attachment-data-{$attachment_id}") ); ?>"><i class="dashicons dashicons-yes"></i> <?php esc_html_e('Save Changes', ' accordion-and-accordion-slider'); ?></button>
					<button type="button" class="button wp-aas-popup-close"><?php esc_html_e('Close', ' accordion-and-accordion-slider'); ?></button>
				</td>
			</tr>
		</table>
	</form><!-- end .wp-aas-attachment-form -->

</div><!-- end .wp-aas-popup-body -->