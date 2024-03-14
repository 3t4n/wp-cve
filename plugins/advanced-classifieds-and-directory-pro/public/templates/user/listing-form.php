<?php

/**
 * Listing Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$email = '';
if ( $post_id > 0 ) {
	if ( isset( $post_meta['email'] ) ) {
		$email = $post_meta['email'][0];
	}
} else {
	$current_user = wp_get_current_user();
	$email = $current_user->user_email;
}

$required_fields = array();
if ( isset( $general_settings['required_fields'] ) ) {
	$required_fields = $general_settings['required_fields'];
}
?>

<div class="acadp acadp-listing-form acadp-require-js" data-script="listing-form">
	<form action="<?php echo esc_url( acadp_get_listing_form_page_link() ); ?>" id="acadp-listing-form" method="post" role="form" data-js-enabled="false">
		<div class="acadp-wrapper acadp-flex acadp-flex-col acadp-gap-6">
			<?php acadp_status_messages(); ?>
			
			<!-- Listing Category -->
			<div class="acadp-panel">
				<div class="acadp-panel-header">
					<?php esc_html_e( 'Choose Category', 'advanced-classifieds-and-directory-pro' ); ?>
				</div>
				<div class="acadp-panel-body">
					<fieldset id="acadp-form-group-category" class="acadp-form-group">
						<legend class="acadp-form-label">
							<?php esc_html_e( 'Select Category', 'advanced-classifieds-and-directory-pro' ); ?>
							<span class="acadp-form-required" aria-hidden="true">*</span>
							<span class="screen-reader-text acadp-form-legend-error"></span>
						</legend>

						<?php
						$categories_args = array(
							'post_id'         => $post_id,
							'placeholder'     => '— ' . esc_html__( 'Select category', 'advanced-classifieds-and-directory-pro' ) . ' —',
							'taxonomy'        => 'acadp_categories',
							'parent'          => 0,
							'parent_disabled' => $disable_parent_categories,
							'name' 	          => 'acadp_category',
							'id'              => 'acadp-form-control-category',
							'class'           => 'acadp-form-control',
							'required'        => true,
							'selected'        => wp_get_object_terms( $post_id, 'acadp_categories', array( 'fields' => 'ids' ) )
						);

						$categories_args = apply_filters( 'acadp_listing_form_categories_dropdown_args', $categories_args );
						echo apply_filters( 'acadp_listing_form_categories_dropdown_html', acadp_get_terms_dropdown_html( $categories_args ), $categories_args );
						?>

						<div hidden id="acadp-form-error-category" class="acadp-form-error"></div>
					</fieldset>										
				</div>
			</div>
			
			<!-- Listing Details -->
			<div class="acadp-panel">
				<div class="acadp-panel-header">
					<?php esc_html_e( 'Listing Details', 'advanced-classifieds-and-directory-pro' ); ?>
				</div>
			
				<div class="acadp-panel-body acadp-flex acadp-flex-col acadp-gap-6">
					<div id="acadp-form-group-title" class="acadp-form-group">
						<label for="acadp-form-control-title" class="acadp-form-label">
							<?php esc_html_e( 'Title', 'advanced-classifieds-and-directory-pro' ); ?>
							<span class="acadp-form-required" aria-hidden="true">*</span>
						</label>

						<input type="text" name="title" id="acadp-form-control-title" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php if ( $post_id > 0 ) echo esc_attr( $post->post_title ); ?>" required aria-describedby="acadp-form-error-title" />
						
						<div hidden id="acadp-form-error-title" class="acadp-form-error"></div>
					</div>
					
					<div id="acadp-custom-fields-listings" class="acadp-custom-fields acadp-hide-if-empty acadp-flex acadp-flex-col acadp-gap-6" data-post_id="<?php echo esc_attr( $post_id ); ?>"><?php 
						do_action( 'wp_ajax_acadp_public_custom_fields_listings', $post_id ); 
					?></div>
					
					<div id="acadp-form-group-description" class="acadp-form-group">
						<label for="acadp-form-control-description" class="acadp-form-label">
							<?php esc_html_e( 'Description', 'advanced-classifieds-and-directory-pro' ); ?>
						</label>

						<?php
						$post_content = ( $post_id > 0 ) ? $post->post_content : '';
						
						if ( is_admin() ) { // Fix for Gutenberg
							$editor = 'textarea';
						}

						if ( 'textarea' == $editor ) {
							printf( 
								'<textarea name="%s" id="acadp-form-control-description" class="acadp-form-control acadp-form-textarea" rows="8">%s</textarea>', 
								'description', 
								esc_textarea( $post_content ) 
							);
						} else {
							wp_editor(
								wp_kses_post( $post_content ),
								'description',
								array(
									'media_buttons' => false,
									'quicktags'     => true,
									'editor_height' => 200
								)
							);
						}
						?>
					</div>
				</div>
			</div>        
		
			<?php if ( $can_add_location ): ?>
				<!-- Contact Details -->
				<div id="acadp-panel-contact-details" class="acadp-panel">
					<div class="acadp-panel-header">
						<?php esc_html_e( 'Contact details', 'advanced-classifieds-and-directory-pro' ); ?>
					</div> 
				
					<div class="acadp-panel-body acadp-flex acadp-flex-col acadp-gap-6">
						<div class="acadp-grid acadp-grid-cols-1 acadp-gap-6 md:acadp-grid-cols-2">
							<div id="acadp-form-group-address" class="acadp-form-group">
								<label for="acadp-form-control-address" class="acadp-form-label">
									<?php esc_html_e( 'Address', 'advanced-classifieds-and-directory-pro' ); ?>								
									<?php if ( in_array( 'address', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
								</label>

								<textarea name="address" id="acadp-form-control-address" class="acadp-form-control acadp-form-control-map acadp-form-textarea acadp-form-validate" rows="3" <?php if ( in_array( 'address', $required_fields ) ) echo 'required'; ?> aria-describedby="acadp-form-error-address"><?php 
									if ( isset( $post_meta['address'] ) ) echo esc_textarea( $post_meta['address'][0] ); 
								?></textarea>

								<div hidden id="acadp-form-error-address" class="acadp-form-error"></div>
							</div>
								
							<div id="acadp-form-group-location" class="acadp-form-group">
								<label for="acadp-form-control-location" class="acadp-form-label">
									<?php esc_html_e( 'Location', 'advanced-classifieds-and-directory-pro' ); ?>
									<?php if ( in_array( 'location', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
								</label>

								<?php
								$locations_args = array(
									'post_id'     => $post_id,
									'placeholder' => '— ' . esc_html__( 'Select location', 'advanced-classifieds-and-directory-pro' ) . ' —',
									'taxonomy'    => 'acadp_locations',
									'parent'      => max( 0, (int) $general_settings['base_location'] ),
									'name' 	      => 'acadp_location',
									'id'          => 'acadp-form-control-location',
									'class'       => 'acadp-form-control acadp-form-control-map',
									'required'    => in_array( 'location', $required_fields ) ? true : false,
									'selected'    => (int) $location
								);
		
								$locations_args = apply_filters( 'acadp_listing_form_locations_dropdown_args', $locations_args );
								echo apply_filters( 'acadp_listing_form_locations_dropdown_html', acadp_get_terms_dropdown_html( $locations_args ), $locations_args );
								?>

								<div hidden id="acadp-form-error-location" class="acadp-form-error"></div>
							</div>
								
							<div id="acadp-form-group-zipcode" class="acadp-form-group">
								<label for="acadp-form-control-zipcode" class="acadp-form-label">
									<?php esc_html_e( 'Zip Code', 'advanced-classifieds-and-directory-pro' ); ?>
									<?php if ( in_array( 'zipcode', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>';	?>
								</label>

								<input type="text" name="zipcode" id="acadp-form-control-zipcode" class="acadp-form-control acadp-form-control-map acadp-form-input acadp-form-validate" value="<?php if ( isset( $post_meta['zipcode'] ) ) echo esc_attr( $post_meta['zipcode'][0] ); ?>" <?php if ( in_array( 'zipcode', $required_fields ) ) echo 'required'; ?> aria-describedby="acadp-form-error-zipcode" />
								
								<div hidden id="acadp-form-error-zipcode" class="acadp-form-error"></div>
							</div>
						
							<div id="acadp-form-group-phone" class="acadp-form-group">
								<label for="acadp-form-control-phone" class="acadp-form-label">
									<?php esc_html_e( 'Phone', 'advanced-classifieds-and-directory-pro' ); ?>
									<?php if ( in_array( 'phone', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
								</label>

								<input type="tel" name="phone" id="acadp-form-control-phone" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php if ( isset( $post_meta['phone'] ) ) echo esc_attr( $post_meta['phone'][0] ); ?>" <?php if ( in_array( 'phone', $required_fields ) ) echo 'required'; ?> aria-describedby="acadp-form-error-phone" />

								<div hidden id="acadp-form-error-phone" class="acadp-form-error"></div>
							</div>
								
							<div id="acadp-form-group-email" class="acadp-form-group">
								<label for="acadp-form-control-email" class="acadp-form-label">
									<?php esc_html_e( 'Email', 'advanced-classifieds-and-directory-pro' ); ?>
									<?php if ( in_array( 'email', $required_fields ) ) echo '<span class="acadp-form-required">*</span>'; ?>
								</label>

								<input type="email" name="email" id="acadp-form-control-email" class="acadp-form-input acadp-form-validate" value="<?php echo esc_attr( $email ); ?>" <?php if ( in_array( 'email', $required_fields ) ) echo 'required'; ?> aria-describedby="acadp-form-error-email" />

								<div hidden id="acadp-form-error-email" class="acadp-form-error"></div>
							</div>
								
							<div id="acadp-form-group-website" class="acadp-form-group">
								<label for="acadp-form-control-website" class="acadp-form-label">
									<?php esc_html_e( 'Website', 'advanced-classifieds-and-directory-pro' ); ?>
									<?php if ( in_array( 'website', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
								</label>

								<input type="url" name="website" id="acadp-form-control-website" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php if ( isset( $post_meta['website'] ) ) echo esc_attr( $post_meta['website'][0] ); ?>" <?php if ( in_array( 'website', $required_fields ) ) echo 'required'; ?> aria-describedby="acadp-form-error-website" />

								<div hidden id="acadp-form-error-website" class="acadp-form-error"></div>
							</div>
						</div>
					
						<?php if ( $has_map ) : 
							$latitude  = isset( $post_meta['latitude'] ) ? esc_attr( $post_meta['latitude'][0] ) : 0;
							$longitude = isset( $post_meta['longitude'] ) ? esc_attr( $post_meta['longitude'][0] ) : 0;

							if ( empty( $latitude ) ) {
								$coordinates = acadp_get_location_coordinates( (int) $location );
					
								$latitude  = $coordinates['latitude']; 
								$longitude = $coordinates['longitude'];
							}
							?>
							<div class="acadp-map acadp-aspect-video" data-type="form">
								<div class="marker" data-latitude="<?php echo esc_attr( $latitude ); ?>" data-longitude="<?php echo esc_attr( $longitude ); ?>"></div>    
							</div>		
							
							<input type="hidden" id="acadp-form-control-latitude" name="latitude" value="<?php echo $latitude; ?>" />
							<input type="hidden" id="acadp-form-control-longitude" name="longitude" value="<?php echo $longitude; ?>" />
					
							<label class="acadp-flex acadp-gap-1.5 acadp-items-center">								
								<input type="checkbox" name="hide_map" class="acadp-form-control acadp-form-checkbox" value="1" <?php if ( isset( $post_meta['hide_map'] ) ) checked( $post_meta['hide_map'][0], 1 ); ?>>
								<?php esc_html_e( "Don't show the Map", 'advanced-classifieds-and-directory-pro' ); ?>
							</label>
						<?php endif; ?>         
					</div>
				</div>
			<?php endif; ?>
			
			<?php if ( $can_add_images ) : ?>
				<!-- Images -->
				<div id="acadp-panel-images" class="acadp-panel">
					<div class="acadp-panel-header">
						<label class="acadp-form-label">
							<?php esc_html_e( 'Images', 'advanced-classifieds-and-directory-pro' ); ?>
							<?php if ( in_array( 'image', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
						</label>
					</div>     
					
					<div class="acadp-overflow-x-auto">
						<table id="acadp-images" class="acadp-images acadp-border-0 acadp-m-0 acadp-w-full">
							<tbody>
								<?php	
								$disable_image_upload_attr = '';

								if ( isset( $post_meta['images'] ) ) {	
									$images = unserialize( $post_meta['images'][0] );	

									foreach ( $images as $index => $image ) {	
										$image_attributes = wp_get_attachment_image_src( $images[ $index ] );
										if ( ! $image_attributes ) continue;
										?>		
										<tr class="acadp-image-row acadp-border-0 acadp-border-b acadp-bg-white">
											<td class="acadp-handle acadp-border-0 acadp-cursor-pointer acadp-p-2 acadp-w-[20px] acadp-align-middle acadp-text-center md:acadp-p-3">
												<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-inline-block acadp-flex-shrink-0">
													<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
												</svg>
											</td>     	
											<td class="acadp-image acadp-border-0 acadp-p-2 acadp-w-[100px] acadp-align-middle acadp-text-center md:acadp-p-3">
												<img src="<?php echo esc_url( $image_attributes[0] ); ?>" class="acadp-inline-block acadp-w-full" alt="" />
												<input type="hidden" name="images[]" class="acadp-image-field" value="<?php echo esc_attr( $images[ $index ] ); ?>" />
											</td>
											<td class="acadp-border-0 acadp-p-2 acadp-align-middle md:acadp-p-3">
												<div class="acadp-image-url acadp-font-medium">
													<?php echo esc_html( basename( $image_attributes[0] ) ); ?>
												</div>
												<a href="javascript:void(0);" class="acadp-delete-image acadp-underline" data-attachment_id="<?php echo esc_attr( $images[ $index ] ); ?>">
													<?php esc_html_e( 'Delete', 'advanced-classifieds-and-directory-pro' ); ?>
												</a> 
											</td>         
										</tr>
										<?php						
									}								
									
									if ( count( $images ) >= $images_limit ) {
										$disable_image_upload_attr = ' disabled';
									}		
								}
								?>
							</tbody>
						</table>   
						
						<div id="acadp-images-upload-status"></div>						
					</div> 				

					<div class="acadp-panel-footer acadp-border-t-0 acadp-bg-white">
						<div hidden id="acadp-form-error-image" class="acadp-form-error"></div>

						<div class="acadp-flex acadp-flex-wrap acadp-gap-4 acadp-items-center">
							<?php if ( $images_limit > 1 ) : ?>
								<div class="acadp-form-description acadp-flex-grow acadp-text-muted acadp-text-sm">						
									<?php 
									if ( in_array( 'image', $required_fields ) ) {
										printf( esc_html__( 'You can upload up to %d images. At least one image is required.', 'advanced-classifieds-and-directory-pro' ), $images_limit );
									} else {
										printf( esc_html__( 'You can upload up to %d images.', 'advanced-classifieds-and-directory-pro' ), $images_limit );
									}
									?>
								</div>
							<?php endif; ?> 

							<button type="button" id="acadp-button-upload-image" class="acadp-button acadp-button-secondary acadp-button-upload acadp-py-2" data-limit="<?php echo esc_attr( $images_limit ); ?>"<?php echo $disable_image_upload_attr; ?>>
								<?php esc_html_e( 'Upload Image', 'advanced-classifieds-and-directory-pro' ); ?>
							</button>
						</div>
					</div>
				</div>
			<?php endif; ?>        
			
			<?php if ( $can_add_video ) : ?>
				<!-- Video -->
				<div class="acadp-panel">
					<div class="acadp-panel-header">
						<?php esc_html_e( 'Video URL', 'advanced-classifieds-and-directory-pro' ); ?>
						<?php if ( in_array( 'video', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
					</div>
				
					<div class="acadp-panel-body">
						<div id="acadp-form-group-video" class="acadp-form-group">
							<label for="acadp-form-control-video" class="acadp-form-label">
								<?php esc_attr_e( 'Enter your YouTube / Vimeo URL', 'advanced-classifieds-and-directory-pro' ); ?>
							</label>

							<input type="url" name="video" id="acadp-form-control-video" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php if ( isset( $post_meta['video'] ) ) echo esc_attr( $post_meta['video'][0] ); ?>" <?php if ( in_array( 'video', $required_fields ) ) echo 'required'; ?> aria-describedby="acadp-form-error-video" />

							<div hidden id="acadp-form-error-video" class="acadp-form-error"></div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $can_add_price ) : ?>
				<!-- Your price -->
				<div class="acadp-panel">
					<div class="acadp-panel-header">
						<?php printf( '%s [%s]', esc_html__( 'Your price', 'advanced-classifieds-and-directory-pro' ), acadp_get_currency() ); ?>
						<?php if ( in_array( 'price', $required_fields ) ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
					</div>
				
					<div class="acadp-panel-body">
						<div id="acadp-form-group-price" class="acadp-form-group">
							<label for="acadp-form-control-price" class="acadp-form-label">
								<?php esc_html_e( 'How much do you want it to be listed for?', 'advanced-classifieds-and-directory-pro' ); ?>
							</label>

							<div class="acadp-space-y-2 acadp-w-full sm:acadp-inline-flex sm:acadp-items-center sm:acadp-space-y-0 sm:acadp-space-x-3">
								<input type="text" name="price" id="acadp-form-control-price" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php if ( isset( $post_meta['price'] ) ) echo esc_attr( $post_meta['price'][0] ); ?>" <?php if ( in_array( 'price', $required_fields ) ) echo 'required'; ?> aria-describedby="acadp-form-error-price" />
								<div class="acadp-form-description acadp-text-muted acadp-text-sm">
									<?php esc_html_e( 'You can adjust your price anytime you like, even after your listing is published.', 'advanced-classifieds-and-directory-pro' ); ?>
								</div>
							</div>

							<div hidden id="acadp-form-error-price" class="acadp-form-error"></div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<!-- Hook for developers to add new fields -->
			<?php do_action( 'acadp_listing_form_fields' ); ?>
			
			<!-- Complete listing -->
			<div class="acadp-panel">
				<div class="acadp-panel-header">
					<?php esc_html_e( 'Complete listing', 'advanced-classifieds-and-directory-pro' ); ?>
				</div>
				
				<div class="acadp-panel-body acadp-flex acadp-flex-col acadp-gap-6 acadp-overflow-x-auto">
					<?php wp_nonce_field( 'acadp_save_listing', 'acadp_listing_nonce' ); ?>
					<input type="hidden" name="post_type" value="acadp_listings" /> 

					<?php if ( $mark_as_sold ) : ?>
						<!-- Mark as Sold -->
						<label class="acadp-flex acadp-gap-1.5 acadp-items-center">
							<input type="checkbox" name="sold" class="acadp-form-control acadp-form-checkbox" value="1" <?php if ( isset( $post_meta['sold'] ) ) checked( $post_meta['sold'][0], 1 ); ?>>
							<?php esc_html_e( 'Mark as', 'advanced-classifieds-and-directory-pro' ); ?>&nbsp;
							<strong><?php echo esc_html( $general_settings['sold_listing_label'] ); ?></strong>
						</label>
					<?php endif; ?>

					<?php
					// Terms and Conditions
					include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/terms-and-conditions.php' );
					?>

					<?php 
					// Privacy Policy
					include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/privacy-policy.php' );
					?>					
					
					<?php if ( $post_id == 0 ) : ?>
						<!-- Google reCaptcha -->
						<div class="acadp-recaptcha">
							<div id="acadp-form-control-recaptcha"></div>
							<div hidden id="acadp-form-error-recaptcha" class="acadp-form-error"></div>
						</div>
					<?php endif; ?>                            
							
					<!-- Action Buttons -->
					<div id="acadp-button-group" class="acadp-flex acadp-flex-wrap acadp-gap-2">
						<?php if ( $has_draft ) : ?>
							<input type="submit" name="action" class="acadp-button acadp-button-secondary acadp-button-draft acadp-flex-grow sm:acadp-flex-grow-0" value="<?php esc_html_e( 'Save Draft', 'advanced-classifieds-and-directory-pro' ); ?>" />
						<?php endif; ?>

						<?php if ( $post_id > 0 ) : ?>
							<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>" />  
							<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="acadp-button acadp-button-secondary acadp-button-preview acadp-flex-grow sm:acadp-flex-grow-0" target="_blank">
								<?php esc_html_e( 'Preview', 'advanced-classifieds-and-directory-pro' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $has_draft ) : ?>
							<input type="submit" name="action" class="acadp-button acadp-button-primary acadp-button-submit acadp-flex-grow sm:acadp-flex-grow-0 sm:acadp-ms-auto" value="<?php esc_html_e( 'Place Listing', 'advanced-classifieds-and-directory-pro' ); ?>" />
						<?php else : ?>
							<input type="submit" name="action" class="acadp-button acadp-button-primary acadp-button-submit acadp-flex-grow sm:acadp-flex-grow-0 sm:acadp-ms-auto" value="<?php esc_html_e( 'Save Changes', 'advanced-classifieds-and-directory-pro' ); ?>" />
						<?php endif; ?>  			
					</div>
				</div>
			</div>
		</div>
    </form>
    
    <form id="acadp-form-upload" method="post" action="#" enctype="multipart/form-data" style="display: none;">
  		<input type="file" multiple name="acadp_image[]" id="acadp-form-control-image" />
        <input type="hidden" name="action" value="acadp_public_image_upload" />
		<?php wp_nonce_field( 'acadp_upload_images', 'acadp_images_nonce' ); ?>
	</form>
</div>