<?php

/**
 * Vertical Search Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-search-form acadp-form-vertical acadp-require-js" data-script="search-form" data-style="<?php echo esc_attr( $style ); ?>">
	<form action="<?php echo esc_url( acadp_get_search_action_page_link() ); ?>" class="acadp-flex acadp-flex-col acadp-gap-6" role="form">
    	<?php if ( ! get_option('permalink_structure') ) : ?>
        	<input type="hidden" name="page_id" value="<?php if ( $page_settings['search'] > 0 ) echo esc_attr( $page_settings['search'] ); ?>" />
        <?php endif; ?>
        
        <?php if ( isset( $_GET['lang'] ) ) : ?>
        	<input type="hidden" name="lang" value="<?php echo esc_attr( $_GET['lang'] ); ?>" />
        <?php endif; ?>
        
		<?php if ( $can_search_by_keyword ) : ?>  
			<div class="acadp-form-group">
				<input type="text" name="q" class="acadp-form-control acadp-form-input" placeholder="<?php esc_attr_e( 'Enter your keyword here', 'advanced-classifieds-and-directory-pro' ); ?>" aria-label="<?php esc_attr_e( 'Enter your keyword here', 'advanced-classifieds-and-directory-pro' ); ?>" value="<?php if ( isset( $_GET['q'] ) ) echo esc_attr( $_GET['q'] ); ?>" />
			</div>  
		<?php endif; ?>      
        
        <?php if ( $has_location && $can_search_by_location ) : ?>
         	<!-- Location field -->
			<div class="acadp-form-group">
            	<label class="acadp-form-label">
					<?php esc_html_e( 'Select location', 'advanced-classifieds-and-directory-pro' ); ?>
				</label>

				<?php 
				$locations_args = array(
					'placeholder' => '— ' . esc_html__( 'Select location', 'advanced-classifieds-and-directory-pro' ) . ' —',
					'taxonomy'    => 'acadp_locations',
					'parent'      => max( 0, (int) $general_settings['base_location'] ),
					'name' 	      => 'l',
					'class'       => 'acadp-form-control',
					'selected'    => isset( $_GET['l'] ) ? (int) $_GET['l'] : ''
				);

				$locations_args = apply_filters( 'acadp_search_form_locations_dropdown_args', $locations_args );
				echo apply_filters( 'acadp_search_form_locations_dropdown_html', acadp_get_terms_dropdown_html( $locations_args ), $locations_args );
				?>
			</div>
        <?php endif; ?>
        
        <?php if ( $can_search_by_category ) : ?>
        	<!-- Category field -->
			<div class="acadp-form-group">
            	<label class="acadp-form-label">
					<?php esc_html_e( 'Select category', 'advanced-classifieds-and-directory-pro' ); ?>
				</label>

				<?php
				$categories_args = array(
					'placeholder' => '— ' . esc_html__( 'Select category', 'advanced-classifieds-and-directory-pro' ) . ' —',
					'taxonomy'    => 'acadp_categories',
					'parent'      => 0,
					'name' 	      => 'c',
					'class'       => 'acadp-form-control acadp-category-field',
					'selected'    => isset( $_GET['c'] ) ? (int) $_GET['c'] : ''
				);

				$categories_args = apply_filters( 'acadp_search_form_categories_dropdown_args', $categories_args );
				echo apply_filters( 'acadp_search_form_categories_dropdown_html', acadp_get_terms_dropdown_html( $categories_args ), $categories_args );
				?>
			</div>
        <?php endif; ?>        

        <?php if ( $can_search_by_custom_fields ) : ?>
        	<!-- Custom fields -->
       		<?php do_action( 'wp_ajax_acadp_custom_fields_search', isset( $_GET['c'] ) ? (int) $_GET['c'] : 0, $style ); ?>
			<div class="acadp-custom-fields acadp-hidden">&nbsp;</div>
        <?php endif; ?>        
        
        <?php if ( $has_price && $can_search_by_price ) : ?>
        	<!-- Price fields -->
        	<div class="acadp-form-group">
       			<label class="acadp-form-label">
					<?php esc_html_e( 'Price Range', 'advanced-classifieds-and-directory-pro' ); ?>
				</label>

                <div class="acadp-flex acadp-gap-2">
        			<input type="text" name="price[0]" class="acadp-form-control acadp-form-input acadp-w-1/2" placeholder="<?php esc_attr_e( 'min', 'advanced-classifieds-and-directory-pro' ); ?>" value="<?php if ( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
           			<input type="text" name="price[1]" class="acadp-form-control acadp-form-input acadp-w-1/2" placeholder="<?php esc_attr_e( 'max', 'advanced-classifieds-and-directory-pro' ); ?>" value="<?php if ( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                </div>
			</div>
        <?php endif; ?>
		
        <!-- Action buttons -->
		<div class="acadp-form-group">
			<div class="acadp-button-group acadp-flex acadp-gap-2 acadp-items-center">
				<button type="submit" class="acadp-button acadp-button-primary acadp-button-submit">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
					</svg>
					<?php esc_html_e( 'Search', 'advanced-classifieds-and-directory-pro' ); ?>
				</button>

				<a href="<?php echo esc_url( get_permalink() ); ?>" class="acadp-button acadp-button-secondary acadp-button-reset">
					<?php esc_html_e( 'Reset', 'advanced-classifieds-and-directory-pro' ); ?>
				</a>
			</div>
		</div>
    </form>
</div>