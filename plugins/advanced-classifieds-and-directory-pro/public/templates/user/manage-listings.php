<?php

/**
 * Manage Listings.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-manage-listings">
	<div class="acadp-wrapper acadp-flex acadp-flex-col acadp-gap-6">
		<?php
		// Status messages
		include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/status-messages.php' );
		?>
		
		<!-- Header -->
		<div class="acadp-header">
			<form action="<?php echo esc_url( acadp_get_manage_listings_page_link( true ) ); ?>" class="acadp-flex acadp-flex-col acadp-gap-4 acadp-items-stretch sm:acadp-flex-row sm:acadp-gap-2 sm:acadp-items-center" role="form">
				<?php if ( ! get_option('permalink_structure') ) : ?>
					<input type="hidden" name="page_id" value="<?php if ( $page_settings['manage_listings'] > 0 ) echo esc_attr( $page_settings['manage_listings'] ); ?>" />
				<?php endif; ?>
		
				<div class="acadp-form-group">
					<?php $search_query = isset( $_REQUEST['u'] ) ? $_REQUEST['u'] : ''; ?>
					<input type="text" name="u" class="acadp-form-control acadp-form-input" placeholder="<?php esc_attr_e( 'Search by title', 'advanced-classifieds-and-directory-pro' ); ?>" value="<?php echo esc_attr( $search_query ); ?>" />
				</div>

				<div class="acadp-button-group acadp-flex acadp-gap-4 sm:acadp-gap-2">
					<button type="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-flex-grow">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
							<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
						</svg>
						<?php esc_html_e( 'Search', 'advanced-classifieds-and-directory-pro' ); ?>
					</button>

					<a href="<?php echo esc_url( acadp_get_manage_listings_page_link() ); ?>" class="acadp-button acadp-button-secondary acadp-button-reset acadp-flex-grow">
						<?php esc_html_e( 'Reset', 'advanced-classifieds-and-directory-pro' ); ?>
					</a>
				</div>

				<a href="<?php echo esc_url( acadp_get_listing_form_page_link() ); ?>" class="acadp-button acadp-button-primary acadp-button-post sm:acadp-ms-auto">
					<?php esc_html_e( 'Add New Listing', 'advanced-classifieds-and-directory-pro' ); ?>
				</a>
			</form>			
		</div>    
	
		<!-- Body -->
		<div class="acadp-body acadp-grid acadp-grid-cols-1 acadp-gap-4">
			<?php if ( ! $acadp_query->have_posts() ) : ?>
				<div class="acadp-text-center acadp-text-muted">
					<?php esc_html_e( 'No listings found', 'advanced-classifieds-and-directory-pro' ); ?>
				</div>
			<?php else : ?>
				<!-- The loop -->		
				<?php while ( $acadp_query->have_posts() ) : 
					$acadp_query->the_post(); 
					$post_meta = get_post_meta( $post->ID ); 
					?>
					<div class="acadp-card md:acadp-flex-row md:acadp-gap-4 md:acadp-p-4">
						<?php if ( $can_show_images ) :
							$image = '';
					
							if ( isset( $post_meta['images'] ) ) {
								$images = unserialize( $post_meta['images'][0] );
								$image_attributes = wp_get_attachment_image_src( $images[0], 'medium' );
								if ( $image_attributes ) $image = $image_attributes[0];
							}
							
							if ( empty( $image ) ) {
								$image = ACADP_PLUGIN_IMAGE_PLACEHOLDER;
							}
							?>
							<div class="acadp-image md:acadp-w-1/3">   
								<a href="<?php the_permalink(); ?>" class="acadp-block acadp-leading-none md:acadp-h-full">
									<img src="<?php echo esc_attr( $image ); ?>" class="acadp-w-full acadp-aspect-video acadp-object-cover acadp-rounded-t md:acadp-aspect-auto md:acadp-rounded md:acadp-h-full" alt="" />
								</a>      	
							</div>
						<?php endif; ?>
						
						<div class="acadp-content acadp-flex acadp-flex-col acadp-gap-4 md:acadp-w-2/3"> 
							<div class="acadp-info acadp-flex acadp-flex-col acadp-gap-2 acadp-p-4 acadp-pb-0 md:acadp-p-0">
								<?php 
								// Badges
								include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/badges.php' );
								?>

								<!-- Listing Title -->       
								<div class="acadp-title">
									<a href="<?php the_permalink(); ?>" class="acadp-text-lg">
										<?php echo esc_html( get_the_title() ); ?>
									</a>
								</div> 							

								<?php
								// Categories
								if ( $terms = wp_get_object_terms( $post->ID, 'acadp_categories' ) ) :
									$links = array();

									foreach ( $terms as $term ) {						
										$links[] = sprintf( 
											'<a href="%s" class="acadp-underline">%s</a>', 
											esc_url( acadp_get_category_page_link( $term ) ), 
											esc_html( $term->name ) 
										);						
									}

									if ( ! empty( $links ) ) : ?>
										<div class="acadp-categories acadp-flex acadp-gap-1.5 acadp-items-center acadp-text-sm">
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
												<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
											</svg>
											<span class="acadp-terms-links">
												<?php echo implode( ', ', $links ); ?>
											</span>
										</div>
									<?php endif; ?>
								<?php endif; ?>

								<?php
								// Locations
								if ( $has_location && $terms = wp_get_object_terms( $post->ID, 'acadp_locations' ) ) :
									$links = array();

									foreach ( $terms as $term ) {						
										$links[] = sprintf( 
											'<a href="%s" class="acadp-underline">%s</a>', 
											esc_url( acadp_get_location_page_link( $term ) ), 
											esc_html( $term->name ) 
										);						
									}

									if ( ! empty( $links ) ) : ?>
										<div class="acadp-locations acadp-flex acadp-gap-1.5 acadp-items-center acadp-text-sm">
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
												<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
												<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
											</svg>
											<span class="acadp-terms-links">
												<?php echo implode( ', ', $links ); ?>
											</span>
										</div>
									<?php endif; ?>
								<?php endif; ?>

								<?php
								// Views
								if ( ! empty( $post_meta['views'][0] ) ) : ?>
									<div class="acadp-views-count acadp-flex acadp-gap-1.5 acadp-items-center acadp-text-sm">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
											<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
											<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
										</svg>

										<?php 
										printf( 
											esc_html__( '%d views', 'advanced-classifieds-and-directory-pro' ), 
											$post_meta['views'][0] 
										); 
										?>
									</div>
								<?php endif; ?>						

								<?php                
								// Listing Status
								echo '<div class="acadp-listing-status acadp-text-sm">';
								echo sprintf( '<strong>%s</strong>: ', esc_html__( 'Status', 'advanced-classifieds-and-directory-pro' ) );
								echo esc_html( acadp_get_listing_status_i18n( $post->post_status ) );
								echo '</div>';  
								?>

								<?php                
								// Expires On
								if ( ! empty( $post_meta['never_expires'] ) ) {
									echo '<div class="acadp-expires-on acadp-text-sm">';
									echo sprintf( '<strong>%s</strong>: ', esc_html__( 'Expires on', 'advanced-classifieds-and-directory-pro' ) );
									echo esc_html__( 'Never Expires', 'advanced-classifieds-and-directory-pro' );
									echo '</div>';  
								} elseif ( ! empty( $post_meta['expiry_date'] ) ) {
									echo '<div class="acadp-expires-on acadp-text-sm">';
									echo sprintf( '<strong>%s</strong>: ', esc_html__( 'Expires on', 'advanced-classifieds-and-directory-pro' ) );
									echo sprintf( '<time>%s</time>', date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $post_meta['expiry_date'][0] ) ) );
									echo '</div>';  
								}
								?>

								<?php 
								$meta = array();					
		
								// Author
								$meta[] = sprintf(
									'<time class="acadp-datetime">%s</time>',
									sprintf( 
										esc_html__( 'Posted %s ago', 'advanced-classifieds-and-directory-pro' ), 
										human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) 
									)
								);
									
								// Posted date
								$meta[] = sprintf( 
									'<a href="%s" class="acadp-author acadp-underline">%s</a>',
									esc_url( acadp_get_user_page_link( $post->post_author ) ),
									get_the_author()
								);
		
								if ( count( $meta ) ) {
									echo '<div class="acadp-author-datetime acadp-text-muted acadp-text-sm">';
									echo implode( ' ' . esc_html__( 'by', 'advanced-classifieds-and-directory-pro' ) . ' ', $meta );
									echo '</div>';
								}	                 
								?>
							</div>

							<?php
							$meta = array();

							// Price                 
							if ( isset( $post_meta['price'] ) && $post_meta['price'][0] > 0 ) {
								$price = acadp_format_amount( $post_meta['price'][0] );	
								
								$meta[] = sprintf(
									'<div class="acadp-price acadp-flex-grow acadp-text-lg acadp-font-bold">%s</div>',
									esc_html( acadp_currency_filter( $price ) )
								);
							}

							// Buttons
							$buttons = array();

							$listing_status = ! empty( $post_meta['listing_status'][0] ) ? $post_meta['listing_status'][0] : '';
							$can_edit = 1;
							
							if ( in_array( $listing_status, array( 'renewal', 'expired' ) ) ) {
								if ( 'expired' == $listing_status ) {
									$can_edit = 0;
								}					

								if ( $can_renew ) {
									$icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
										<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
									</svg>';

									$buttons[] = sprintf( 
										'<a href="%s" class="acadp-button acadp-button-secondary acadp-button-renew acadp-shadow-none acadp-py-2">%s<span class="acadp-hidden xs:acadp-block">%s</span></a>', 
										esc_url( acadp_get_listing_renewal_page_link( $post->ID ) ), 
										$icon,
										esc_html__( 'Renew', 'advanced-classifieds-and-directory-pro' ) 
									);
								}								
							} else {							
								if ( 'pending' == $post->post_status ) {
									$can_edit = 0;
								}
								
								if ( $can_promote && empty( $post_meta['featured'][0] ) && 'publish' == $post->post_status ) {
									$icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
										<path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
									</svg>';

									$buttons[] = sprintf( 
										'<a href="%s" class="acadp-button acadp-button-secondary acadp-button-promote acadp-shadow-none acadp-py-2">%s<span class="acadp-hidden xs:acadp-block">%s</span></a>', 
										esc_url( acadp_get_listing_promote_page_link( $post->ID ) ),
										$icon, 
										esc_html__( 'Promote', 'advanced-classifieds-and-directory-pro' ) 
									);
								}
							}

							if ( $can_edit ) {
								$icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
									<path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
								</svg>';

								$buttons[] = sprintf( 
									'<a href="%s" class="acadp-button acadp-button-secondary acadp-button-edit acadp-shadow-none acadp-py-2">%s<span class="acadp-hidden xs:acadp-block">%s</span></a>', 
									esc_url( acadp_get_listing_edit_page_link( $post->ID ) ), 
									$icon,
									esc_html__( 'Edit', 'advanced-classifieds-and-directory-pro' ) 
								);
							}

							$icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
								<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
							</svg>';

							$buttons[] = sprintf( 
								'<a href="%s" class="acadp-button acadp-button-secondary acadp-button-delete acadp-shadow-none acadp-py-2" onclick="return confirm( \'%s\' );">%s<span class="acadp-hidden xs:acadp-block">%s</span></a>', 
								esc_url( acadp_get_listing_delete_page_link( $post->ID ) ), 
								esc_attr__( 'Are you sure you want to delete this listing?', 'advanced-classifieds-and-directory-pro' ),
								$icon,
								esc_html__( 'Delete', 'advanced-classifieds-and-directory-pro' ) 
							);

							$meta[] = sprintf(
								'<div class="acadp-button-group acadp-flex acadp-gap-2 acadp-items-center acadp-ms-auto">%s</div>',
								implode( '', $buttons )
							);

							// ...
							echo '<div class="acadp-footer acadp-flex acadp-items-center acadp-mt-auto acadp-border-0 acadp-border-t acadp-border-gray-100 acadp-p-4 md:acadp-p-0 md:acadp-pt-4">';
							echo implode( '', $meta );
							echo '</div>';
							?>
						</div>
					</div>				
				<?php endwhile; ?>
				<!-- End of the loop -->

				<!-- Reset postdata to restore orginal query -->
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>

		<?php 
		// Pagination
		include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/pagination.php' ); 
		?>
	</div>
</div>
