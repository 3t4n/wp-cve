<?php

/**
 * Header Navbar.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>
 
<form action="<?php echo esc_url( get_permalink() ); ?>" method="post" role="form" class="acadp-flex acadp-flex-col acadp-gap-4 acadp-items-stretch sm:acadp-flex-row sm:acadp-items-center">
    <?php if ( $can_show_listings_count ) : ?>
        <!-- Listings count -->
        <div class="acadp-listings-count acadp-flex-grow acadp-me-auto acadp-text-muted">
            <?php 
            printf( 
                esc_html__( '%d item(s) found', 'advanced-classifieds-and-directory-pro' ), 
                ( ( is_front_page() && is_home() ) ? $acadp_query->post_count : $acadp_query->found_posts )
            );
            ?>
        </div>
    <?php endif; ?>

    <?php if ( $can_show_orderby_dropdown ) : ?>
        <!-- Orderby dropdown -->
        <div class="acadp-form-group">
            <select name="sort" class="acadp-form-control acadp-form-select" onchange="this.form.action = this.value; this.form.submit();">
                <?php
                $options = acadp_get_listings_orderby_options();

                printf(
                    '<option value="%s">— %s —</option>',
                    esc_url( add_query_arg( 'sort', $current_order ) ),
                    esc_html__( 'Sort by', 'advanced-classifieds-and-directory-pro' )
                );    
                
                foreach ( $options as $value => $label ) {
                    printf( 
                        '<option value="%s" %s>%s</option>',
                        esc_url( add_query_arg( 'sort', $value ) ),
                        selected( $value, $current_order, false ),                                    
                        $label
                    );
                }
                ?>
            </select>
        </div>
    <?php endif; ?>
    
    <?php if ( $can_show_views_selector ) : ?>
        <!-- Views dropdown -->
        <div class="acadp-form-group">
            <select name="view" class="acadp-form-control acadp-form-select" onchange="this.form.action = this.value; this.form.submit();">
                <?php
                $options = acadp_get_listings_view_options();

                printf(
                    '<option value="%s">— %s —</option>',
                    esc_url( add_query_arg( 'view', $current_view ) ),
                    esc_html__( 'View as', 'advanced-classifieds-and-directory-pro' )
                );

                
                foreach ( $options as $value => $label ) {
                    printf( 
                        '<option value="%s" %s>%s</option>',
                        esc_url( add_query_arg( 'view', $value ) ),
                        selected( $value, $current_view, false ),                                    
                        $label
                    );
                }
                ?>
            </select>
        </div>
    <?php endif; ?>                   
</form>
