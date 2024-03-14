<?php

/**
 * Metabox: Images.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( ! $general_settings['has_images'] ) {
	return false;
}
?>

<div class="acadp">
	<div class="acadp-flex acadp-flex-col acadp-gap-4">
		<table id="acadp-images" class="acadp-border-0 widefat striped">
			<tbody>
				<?php
				if ( isset( $post_meta['images'] ) ) {
					$images = unserialize( $post_meta['images'][0] );
				
					foreach ( $images as $image_id ) {	
						$image_attributes = wp_get_attachment_image_src( $image_id );					
						if ( empty( $image_attributes ) ) continue;

						$image_url = $image_attributes[0];
						?>			
						<tr class="acadp-image-row acadp-border-0 acadp-bg-white"> 
							<td class="acadp-handle acadp-border-0 acadp-border-b acadp-border-solid acadp-border-gray-200 acadp-cursor-pointer acadp-p-2 acadp-w-7 acadp-align-middle acadp-text-center md:acadp-p-3">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-inline-block acadp-flex-shrink-0">
									<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
								</svg>
							</td>         	
							<td class="acadp-image acadp-border-0 acadp-border-b acadp-border-solid acadp-border-gray-200 acadp-p-2 acadp-w-16 acadp-align-middle acadp-text-center md:acadp-p-3"> 
								<img src="<?php echo esc_url( $image_url ); ?>" class="acadp-inline-block acadp-w-full" alt="" />
								<input type="hidden" name="images[]" class="acadp-image-field" value="<?php echo esc_attr( $image_id ); ?>" />
							</td>
							<td class="acadp-border-0 acadp-border-b acadp-border-solid acadp-border-gray-200 acadp-p-2 acadp-align-middle md:acadp-p-3">
								<div class="acadp-image-url acadp-font-medium">
									<?php echo esc_html( basename( $image_url ) ); ?>
								</div>

								<div class="acadp-flex acadp-gap-1 acadp-items-center">
									<a href="post.php?post=<?php echo (int) $image_id; ?>&action=edit" target="_blank">
										<?php esc_html_e( 'Edit', 'advanced-classifieds-and-directory-pro' ); ?>
									</a>
									<span class="acadp-text-muted acadp-text-sm">/</span>
									<a href="javascript:void(0)" class="acadp-delete-image" data-attachment_id="<?php echo esc_attr( $image_id ); ?>">
										<?php esc_html_e( 'Delete Permanently', 'advanced-classifieds-and-directory-pro' ); ?>
									</a>
								</div> 
							</td>             
						</tr>
						<?php						
					} // endfor		
				} // endif
				?>
			</tbody>
		</table>

		<button type="button" id="acadp-button-upload-image" class="acadp-ms-auto button">
			<?php esc_html_e( 'Upload Image', 'advanced-classifieds-and-directory-pro' ); ?>
		</button>
	</div>
</div>