<?php

/**
 * Single Listing Page.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div id="acadp-listing" class="acadp acadp-listing acadp-require-js" data-script="single-listing">
    <!-- Wrapper -->
    <div class="acadp-wrapper acadp-flex acadp-flex-col acadp-gap-6 md:acadp-flex-row">
        <!-- Primary -->
        <div class="acadp-primary acadp-flex acadp-flex-col acadp-gap-4 acadp-w-full<?php if ( $has_sidebar ) echo ' md:acadp-w-2/3'; ?>">
            <div class="acadp-header acadp-flex acadp-flex-col acadp-gap-2">
                <?php 
                // Badges
                include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/badges.php' );
                ?>

                <!-- Title -->
                <div class="acadp-header-title acadp-flex acadp-flex-wrap acadp-gap-2 acadp-items-center">  
                    <h1 class="acadp-title acadp-m-0 acadp-me-auto acadp-p-0 acadp-text-2xl">
                        <?php echo esc_html( $post->post_title ); ?>
                    </h1>
                    
                    <?php 
                    if ( $can_add_favourites || $can_report_abuse ) {
                        echo '<div class="acadp-buttons acadp-flex acadp-gap-2">';
                        
                        // Favourites
                        if ( $can_add_favourites ) {                            
                            include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/listing/favourites.php' );
                        }

                        // Report abuse
                        if ( $can_report_abuse ) {
                            include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/listing/report-abuse.php' );
                        }

                        echo '</div>';
                    }
                    ?>
                </div>

                <?php
                // Meta
                $meta = array();

                // Listing price
                if ( $can_show_price ) {
                    $price = acadp_format_amount( $post_meta['price'][0] );	
                    $price = acadp_currency_filter( $price );

                    $meta[] = sprintf( 
                        '<div class="acadp-price acadp-text-lg acadp-font-bold">%s</div>', 
                        esc_html( $price ) 
                    );
                }

                // Listing categories
                if ( $can_show_category ) {
                    $terms = array();

                    foreach ( $categories as $term ) {						
                        $terms[] = sprintf( 
                            '<a href="%s" class="acadp-underline">%s</a>', 
                            esc_url( acadp_get_category_page_link( $term ) ), 
                            esc_html( $term->name ) 
                        );						
                    }

                    if ( count( $terms ) ) {
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                        </svg>';

                        $meta[] = sprintf( 
                            '<div class="acadp-categories acadp-flex acadp-gap-1.5 acadp-items-center acadp-text-sm">%s <span class="acadp-terms-links">%s</span></div>',
                            $icon,
                            implode( ', ', $terms )
                        );
                    }
                }

                if ( count( $meta ) ) {
                    echo '<div class="acadp-header-meta acadp-flex acadp-gap-1 acadp-items-center">';
                    echo implode( '<span class="acadp-text-muted acadp-text-sm">/</span>', $meta );
                    echo '</div>';
                }
                ?>
            </div>

            <?php 
            // Images
            if ( $can_show_images ) {
                include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/listing/images.php' );
            }
            ?>

            <?php 
            // Listing description
            if ( ! empty( $description ) ) {
                echo '<div class="acadp-description">';
                echo $allow_scripts ? $description : wp_kses_post( $description ); 
                echo '</div>';
            }            
            ?> 

            <?php if ( count( $fields ) ) : 
                // Custom fields
                $__fields = array();

                foreach ( $fields as $field ) {
                    if ( ! isset( $post_meta[ $field->ID ] ) ) continue;
                    $__fields[] = $field;
                }

                if ( count( $__fields ) ) : ?>
                    <div class="acadp-fields acadp-grid acadp-grid-cols-1 acadp-gap-2 md:acadp-grid-cols-2">
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

            <div class="acadp-footer acadp-flex acadp-flex-col acadp-gap-1">
                <?php	
                // Meta			
                $meta = array();
                
                // Posted date
                if ( $can_show_date ) {
                    $meta[] = sprintf(
                        '<time class="acadp-datetime">%s</time>',
                        sprintf( 
                            esc_html__( 'Posted %s ago', 'advanced-classifieds-and-directory-pro' ), 
                            human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) 
                        )
                    );
                }
                    
                // Author
                if ( $can_show_user ) {			
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

                <?php
                // Views
                if ( $can_show_views && ! empty( $post_meta['views'][0] ) ) : ?>
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
            </div>
        </div>

        <!-- Secondary -->
        <?php if ( $has_sidebar ) : ?>
            <div class="acadp-secondary acadp-flex acadp-flex-col acadp-gap-6 md:acadp-w-1/3">
                <?php
                // Video
                if ( $can_show_video ) {
                    echo '<div class="acadp-section">';
                    include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/listing/video.php' );
                    echo '</div>';
                }
                ?>

                <?php 
                // Address
                if ( $has_location ) {   
                    echo '<div class="acadp-section">';   
                    echo sprintf(
                        '<div class="acadp-mb-3 acadp-text-lg acadp-font-bold">%s</div>', 
                        esc_html__( 'Contact details', 'advanced-classifieds-and-directory-pro' )
                    );            
                    include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/listing/address.php' );
                    echo '</div>';
                } 
                ?>

                <?php 
                // Contact form
                if ( $can_show_contact_form ) {
                    echo '<div class="acadp-section">';  
                    echo sprintf(
                        '<div class="acadp-mb-3 acadp-text-lg acadp-font-bold">%s</div>', 
                        esc_html__( 'Contact listing owner', 'advanced-classifieds-and-directory-pro' )
                    );
                    include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/listing/contact-form.php' );
                    echo '</div>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// Share buttons
include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/share-buttons.php' );