<?php

/**
 * Layout Grid.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$fields = array();
if ( isset( $instance['show_custom_fields'] ) && 1 == $instance['show_custom_fields'] ) {
    $fields = acadp_get_custom_fields_listings_archive();
}
?>

<div class="acadp acadp-listings acadp-layout-grid">
	<div class="acadp-body acadp-grid acadp-grid-cols-1 acadp-gap-6 md:acadp-grid-cols-<?php echo (int) $instance['columns']; ?>">
		<!-- The loop -->
		<?php 
        while ( $acadp_query->have_posts() ) : 
			$acadp_query->the_post(); 
			$post_meta = get_post_meta( $post->ID ); 

			$classes = array( 'acadp-card' );
			if ( isset( $post_meta['featured'] ) && 1 == (int) $post_meta['featured'][0] ) {
				$classes[] = 'acadp-featured';
			}
			?> 
			<div class="<?php echo implode( ' ', $classes ); ?>">
				<?php if ( $instance['has_images'] && $instance['show_image'] ) :
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
					<div class="acadp-image">   
						<a href="<?php the_permalink(); ?>" class="acadp-block acadp-leading-none">
							<img src="<?php echo esc_attr( $image ); ?>" class="acadp-w-full acadp-aspect-video acadp-object-cover acadp-rounded-t" alt="" />
						</a>      	
					</div>
                <?php endif; ?>

                <div class="acadp-content acadp-flex acadp-flex-col acadp-gap-2 acadp-p-4">
                    <?php 
                    // Badges
                    include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/badges.php' );
                    ?>

                    <!-- Listing Title -->       
                    <div class="acadp-title">
                        <a href="<?php the_permalink(); ?>" class="acadp-text-xl">
                            <?php echo esc_html( get_the_title() ); ?>
                        </a>
                    </div>
                    
                    <?php
                    // Categories
                    if ( $instance['show_category'] && $terms = wp_get_object_terms( $post->ID, 'acadp_categories' ) ) :
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
                    if ( $instance['has_location'] && $instance['show_location'] && $terms = wp_get_object_terms( $post->ID, 'acadp_locations' ) ) :
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
                    if ( $instance['show_views'] && ! empty( $post_meta['views'][0] ) ) : ?>
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
                    // Listing excerpt
                    if ( $instance['show_excerpt'] && ! empty( $post->post_content ) && ! empty( $this->listings_settings['excerpt_length'] ) ) : ?>
                        <div class="acadp-excerpt">
                            <?php echo wp_trim_words( $post->post_content, $this->listings_settings['excerpt_length'], '...' ); ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Custom fields
                    if ( count( $fields ) ) : 
                        $__fields = array();

                        foreach ( $fields as $field ) {
                            if ( ! isset( $post_meta[ $field->ID ] ) ) continue;
                            $__fields[] = $field;
                        }

                        if ( count( $__fields ) ) : ?>
                            <div class="acadp-fields acadp-grid acadp-grid-cols-1 acadp-gap-2">
                                <?php foreach ( $__fields as $field ) : 
                                    $field_value = acadp_get_custom_field_display_text( $post_meta[ $field->ID ][0], $field );
                                    if ( '' == $field_value ) continue;
                                    ?> 
                                    <div class="acadp-field acadp-field-<?php echo esc_attr( $field->type ); ?>">
                                        <dt class="acadp-field-name acadp-m-0 acadp-p-0 acadp-font-bold">
                                            <?php echo esc_html( $field->post_title ); ?>
                                        </dt>

                                        <dd class="acadp-field-value acadp-m-0 acadp-p-0">
                                            <?php 
                                            if ( 'textarea' == $field->type ) {
                                                echo wp_kses_post( nl2br( $field_value ) );
                                            } else {
                                                echo wp_kses_post( $field_value );
                                            }
                                            ?>
                                        </dd>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>   
                    <?php endif; ?>

                    <?php                 
                    $meta = array();					

                    // Posted date
                    if ( $instance['show_date'] ) {
                        $meta[] = sprintf(
                            '<time class="acadp-datetime">%s</time>',
                            sprintf( 
                                esc_html__( 'Posted %s ago', 'advanced-classifieds-and-directory-pro' ), 
                                human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) 
                            )
                        );
                    }
                        
                    // Author
                    if ( $instance['show_user'] ) {			
                        $meta[] = sprintf( 
                            '<a href="%s" class="acadp-author acadp-underline">%s</a>',
                            esc_url( acadp_get_user_page_link( $post->post_author ) ),
                            get_the_author()
                        );
                    }

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
                if ( $instance['has_price'] && $instance['show_price'] && isset( $post_meta['price'] ) && $post_meta['price'][0] > 0 ) {
                    $price = acadp_format_amount( $post_meta['price'][0] );	
                    
                    $meta[] = sprintf(
                        '<div class="acadp-price acadp-text-lg acadp-font-bold">%s</div>',
                        esc_html( acadp_currency_filter( $price ) )
                    );
                }              

                if ( count( $meta ) ) {
                    echo '<div class="acadp-footer acadp-flex acadp-items-center acadp-mt-auto acadp-border-0 acadp-border-t acadp-border-gray-100 acadp-p-3">';
                    echo implode( '', $meta );
                    echo '</div>';
                }
                ?>                   
			</div>
		<?php endwhile; ?>
		<!-- End of the loop -->

		<!-- Reset postdata to restore orginal query -->
		<?php wp_reset_postdata(); ?>
	</div>   
</div>
