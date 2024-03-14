<?php

/**
 * Metabox: Listing Details.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-require-js" data-script="listings">
	<table class="acadp-form-table form-table widefat">
		<tbody>
			<tr class="acadp-form-group-acadp_category">
				<th scope="row">
					<label for="acadp-form-control-acadp_category">
						<?php esc_html_e( 'Category', 'advanced-classifieds-and-directory-pro' ); ?>
					</label>
				</th>
				<td>
					<?php
					$categories_args = array(
						'post_id'     => $post->ID,
						'placeholder' => '— ' . esc_html__( 'Select category', 'advanced-classifieds-and-directory-pro' ) . ' —',
						'taxonomy'    => 'acadp_categories',
						'parent'      => 0,
						'name' 	      => 'acadp_category',
						'id'          => 'acadp-form-control-category',
						'class'       => 'acadp-form-control widefat postform',
						'selected'    => wp_get_object_terms( $post->ID, 'acadp_categories', array( 'fields' => 'ids' ) )
					);

					$categories_args = apply_filters( 'acadp_listing_form_categories_dropdown_args', $categories_args );
					echo apply_filters( 'acadp_listing_form_categories_dropdown_html', acadp_get_terms_dropdown_html( $categories_args ), $categories_args );
					?>
				</td>
			</tr>
			<?php if ( $has_price ) : ?>
				<tr class="acadp-form-group-price">
					<th scope="row">
						<label for="acadp-form-control-price">
							<?php 
							printf( 
								'%s (%s)', 
								esc_html__( 'Price', 'advanced-classifieds-and-directory-pro' ), 
								acadp_get_currency() 
							); 
							?>
						</label>
					</th>
					<td>
						<input type="text" name="price" id="acadp-form-control-price" class="acadp-form-control acadp-form-input widefat" placeholder="<?php esc_html_e( 'How much do you want it to be listed for?', 'advanced-classifieds-and-directory-pro' ); ?>" value="<?php if ( isset( $post_meta['price'] ) ) echo acadp_format_amount( $post_meta['price'][0] ); ?>" />
					</td>
				</tr>  
			<?php endif; ?> 
		</tbody>
	</table>

	<div id="acadp-custom-fields-listings" class="acadp-custom-fields acadp-hide-if-empty" data-post_id="<?php echo esc_attr( $post->ID ); ?>"><?php 
		do_action( 'wp_ajax_acadp_custom_fields_listings', $post->ID ); 
	?></div>

	<table class="acadp-form-table form-table widefat">
		<tbody>
			<tr class="acadp-form-group-views">
				<th scope="row">
					<label for="acadp-form-control-views">
						<?php esc_html_e( 'Views count', 'advanced-classifieds-and-directory-pro' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="views" id="acadp-form-control-views" class="acadp-form-control acadp-form-input widefat" value="<?php if ( isset( $post_meta['views'] ) ) echo esc_attr( $post_meta['views'][0] ); ?>" />
				</td>
			</tr>   
		</tbody>
	</table>
</div>