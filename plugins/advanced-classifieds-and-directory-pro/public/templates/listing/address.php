<?php

/**
 * Contact details.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$listing_settings = get_option( 'acadp_listing_settings' );
?>

<div class="acadp-contact-details acadp-flex acadp-flex-col acadp-gap-3">   
    <?php 
    // Map
    if ( $can_show_map ) {
        include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/listing/map.php' ); 
    }
    ?>
    
    <div class="acadp-address acadp-flex acadp-flex-col acadp-gap-1.5">
        <?php                                                     
        // Street address                  
        if ( ! empty( $post_meta['address'][0] ) ) {
            echo '<div class="acadp-street-address">';
            echo esc_html( $post_meta['address'][0] );
            echo '</div>';
        }
        
        // Locations   
        $location_id = ! empty( $location ) ? $location->term_id : 0;
        $location_ancestors = get_ancestors( $location_id, 'acadp_locations' );
        
        $location_ids = array_merge( array( $location_id ), $location_ancestors );
        $location_ids = array_filter( $location_ids );       
        
        if ( count( $location_ids ) ) {
            $locations = array();

            foreach ( $location_ids as $location_id ) {
                $term = get_term( $location_id, 'acadp_locations' );

                if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
                    $locations[] = sprintf(
                        '<span class="acadp-location"><a href="%s" class="acadp-underline">%s</a></span>',
                        esc_url( acadp_get_location_page_link( $term ) ),
                        $term->name
                    );
                }
            }

            if ( count( $locations ) ) {
                echo '<div class="acadp-locations">';
                echo implode( ', ', $locations );
                echo '</div>';
            }
        }      

        // Zipcode                        
        if ( ! empty( $post_meta['zipcode'][0] ) ) {
            echo '<div class="acadp-zipcode">';
            echo esc_html( $post_meta['zipcode'][0] );
            echo '</div>';
        }
        
        // Phone
        if ( 'never' != $listing_settings['show_phone_number'] && ! empty( $post_meta['phone'][0] ) ) {
            echo '<div class="acadp-phone acadp-flex acadp-gap-1.5 acadp-items-center">';

            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 3.75v4.5m0-4.5h-4.5m4.5 0l-6 6m3 12c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 014.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 00-.38 1.21 12.035 12.035 0 007.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 011.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 01-2.25 2.25h-2.25z" />
            </svg>';

            if ( 'open' == $listing_settings['show_phone_number'] ) {
                echo sprintf( 
                    '<span class="acadp-phone-number"><a href="tel:%1$s" class="acadp-underline">%1$s</a></span>', 
                    esc_html( $post_meta['phone'][0] )
                );
            } else {
                echo sprintf( 
                    '<a class="acadp-link-show-phone-number acadp-underline" href="javascript: void(0);">%s</a>', 
                    __( 'Show phone number', 'advanced-classifieds-and-directory-pro' ) 
                );

                echo sprintf( 
                    '<span class="acadp-phone-number" style="display: none;"><a href="tel:%1$s" class="acadp-underline">%1$s</a></span>', 
                    esc_html( $post_meta['phone'][0] )
                );
            }

            echo '</div>';
        }
            
        // Email
        if ( 'never' != $listing_settings['show_email_address'] && ! empty( $post_meta['email'][0] ) ) {	
            echo '<div class="acadp-email acadp-flex acadp-gap-1.5 acadp-items-center">';
            
            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
            </svg>';

            if ( 'public' == $listing_settings['show_email_address'] || is_user_logged_in() ) {
                echo sprintf( 
                    '<a href="mailto:%1$s" class="acadp-underline">%1$s</a>', 
                    esc_html( $post_meta['email'][0] )
                );
            } else {
                echo '*****';                
            }

            echo '</div>';
        }
        
        if ( ! empty( $post_meta['website'][0] ) ) {
            echo '<div class="acadp-website acadp-flex acadp-gap-1.5 acadp-items-center">';

            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
            </svg>';
            
            echo sprintf( 
                '<a href="%1$s" class="acadp-underline" target="_blank">%1$s</a>', 
                esc_html( $post_meta['website'][0] ) 
            );

            echo '</div>';
        }
        ?>
    </div>
</div>