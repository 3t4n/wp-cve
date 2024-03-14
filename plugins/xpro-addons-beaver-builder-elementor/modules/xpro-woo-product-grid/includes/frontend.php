<?php

if ( class_exists( 'WooCommerce' ) ) {

    // Get the query data.
    $settings->post_type = 'product';
    $query               = FLBuilderLoop::query( $settings );

    // Render the products.
    if ( $query->have_posts() ) {

        ?>
        <div class="xpro-product-grid-wrapper xpro-woo-product-grid-layout-<?php echo esc_attr( $settings->layout ); ?>">
            <div class="xpro-woo-product-grid-main cbp">
                <?php
                if ( 'yes' === $settings->show_pagination ) {
                    $found_posts    = 0;
                    $paged          = 1;
                    $args['offset'] = '';
                    if ( get_query_var( 'paged' ) ) {
                        $paged = get_query_var( 'paged' );
                    } elseif ( get_query_var( 'page' ) ) {
                        $paged = get_query_var( 'page' );
                    }
                }

                while ( $query->have_posts() ) {

                    $query->the_post();

                    $post_id   = get_the_ID();
                    $permalink = get_permalink();

                    $cat_list  = wp_get_post_terms( $post_id, 'category' );
                    $count_cat = count( $cat_list );

                    ob_start();
                    ?>
                    <div id="xpro-woo-product-grid-id-<?php echo esc_attr( $post_id ); ?>" class="cbp-item xpro-woo-product-grid-item">

                        <div class="xpro-woo-product-grid-img">
                            <div class="xpro-woo-product-img-section">
                                <?php
                                $product        = wc_get_product( $post_id );
                                $attachment_ids = $product->get_gallery_image_ids();
                                if ( is_array( $attachment_ids ) && ! empty( $attachment_ids ) ) {
                                    if ( isset( $attachment_ids[0] ) ) {
                                        $first_image_url = wp_get_attachment_image_src( $attachment_ids[0], $settings->thumbnail_size, true );
                                        ?>
                                        <!-- first img url -->
                                        <img class="xpro-woo-product-grid-img xpro-gallery-first-img-url" src="<?php echo esc_url( $first_image_url[0] ); ?>" alt="Image Not Found">
                                        <?php
                                    }
                                    if ( isset( $attachment_ids[1] ) ) {
                                        $second_image_url = wp_get_attachment_image_src( $attachment_ids[1], $settings->thumbnail_size, true );
                                        ?>
                                        <!-- second img url -->
                                        <img class="xpro-woo-product-grid-img xpro-gallery-second-img-url" src="<?php echo esc_url( $second_image_url[0] ); ?>" alt="Image Not Found">
                                        <?php
                                    }
                                } else {
                                    $img_url         = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $settings->thumbnail_size, true );
                                    $placeholder_url = WC()->plugin_url() . '/assets/images/placeholder.png';
                                    if ( has_post_thumbnail( $post_id ) ) {
                                        $img_src = $img_url[0];
                                    } else {
                                        $img_src = $placeholder_url;
                                    }
                                    ?>
                                    <img class="xpro-woo-product-grid-img xpro-product-img-url" src="<?php echo esc_url( $img_src ); ?>" alt="Image Not Found">
                                    <?php
                                }
                                if ( 'yes' === $settings->show_badges ) {
                                    ?>
                                    <div class="xpro-woo-product-grid-badges-wrapper">
                                        <div class="xpro-woo-product-grid-badges-innner-wrapper">
                                            <?php
                                            if ( $product->is_in_stock() ) {
                                                if ( 'yes' === $settings->badges_styles->show_sale_badge ) {
                                                    if ( 'text' === $settings->badges_styles->sale_badge_type ) {
                                                        $sale_text = __( 'Sale!', 'xpro' );
                                                    } else {
                                                        if ( 'variable' === $product->get_type() ) {
                                                            $regular_price = $product->get_variation_regular_price();
                                                        } else {
                                                            $regular_price = $product->get_regular_price();
                                                        }

                                                        if ( 'variable' === $product->get_type() ) {
                                                            $sale_price = $product->get_variation_sale_price();
                                                        } else {
                                                            $sale_price = $product->get_sale_price();
                                                        }

                                                        if ( 'grouped' === $product->get_type() ) {
                                                            $sale_text = __( 'Sale!', 'xpro' );
                                                        }

                                                        if ( $sale_price ) {
                                                            $percent_sale = round( ( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ), 0 );
                                                            $sale_text    = '-[value]%';
                                                            $sale_text    = str_replace( '[value]', $percent_sale, $sale_text );
                                                        }
                                                    }
                                                    // sale flash
                                                    if ( $product->is_on_sale() ) :
                                                        echo apply_filters( 'xpro-woo-product_sale_flash_inner', '<div class="xpro-sale-flash-wrap xpro-woo-sale-flash-btn xpro-woo-badges-btn"><span class="xpro-onsale xpro-woo-sale-flash-btn-inner">' . $sale_text . '</span></div>', $product ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
                                                    endif;
                                                }

                                                if ( 'yes' === $settings->badges_styles->show_featured_badge ) {
                                                    // featured flash
                                                    $featured_text = __( 'New', 'xpro' );
                                                    if ( $product->is_featured() ) :
                                                        echo apply_filters( 'xpro-woo-product_featured_flash_inner', '<div class="xpro-featured-flash-wrap xpro-woo-featured-flash-btn xpro-woo-badges-btn"><span class="xpro-featured xpro-woo-featured-flash-btn-inner">' . $featured_text . '</span></div>', $product ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
                                                    endif;
                                                }
                                            } else {
                                                $out_stock_text = __( 'Out of Stock', 'xpro' );
                                                ?>
                                                <div class="xpro-woo-out-of-stock-btn xpro-woo-badges-btn"><span><?php echo $out_stock_text; ?></span></div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }

                                if ( 'yes' === $settings->show_qv_action && '10' !== $settings->layout ) {
                                    ?>
                                    <!-- qv call to action -->
                                    <div class="xpro-product-grid-hv-cta-section">
                                        <?php if ( 'yes' === $settings->show_qv_icon ) { ?>
                                            <div id="<?php echo esc_attr( $post_id ); ?>" class="xpro-hv-qv-btn xpro-cta-btn">
                                                <i class="xi xi-eye"></i>
                                            </div>
                                            <?php
                                        }
                                        if ( 'yes' === $settings->show_cart_icon ) {
                                            ?>
                                            <div class="xpro-hv-cart-btn xpro-cta-btn">
                                                <div class="xpro-qv-cart-btn">
                                                    <?php
                                                    do_action( '', $post_id, $settings );
                                                    woocommerce_template_loop_add_to_cart();
                                                    do_action( '', $post_id, $settings );
                                                    ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!--qv call to action end -->
                                <?php } ?>

                                <?php if ( 'yes' === $settings->show_cta && ( '1' === $settings->layout || '4' === $settings->layout || '5' === $settings->layout ) ) { ?>
                                    <!-- product actions -->
                                    <div class="xpro-woo-product-grid-btn-section">
                                        <div class="xpro-woo-product-grid-add-to-cart-btn">
                                            <?php
                                            do_action( '', $post_id, $settings );
                                            woocommerce_template_loop_add_to_cart();
                                            do_action( '', $post_id, $settings );
                                            ?>
                                        </div>
                                    </div>
                                    <!-- product actions end-->
                                <?php } ?>

                            </div>
                        </div>

                        <div class="xpro-woo-product-grid-content-sec">

                            <?php if ( 'yes' === $settings->show_category ) { ?>
                                <h4 class="xpro-woo-product-grid-category-wrapper">
                                    <?php
                                    $terms_data = get_the_terms( $post_id, 'product_cat' );
                                    foreach ( $terms_data as $t ) {
                                        ?>
                                        <a href="<?php echo esc_url( get_term_link( $t ) ); ?>">
									<span class="xpro_elementor_category_term_name">
										<?php echo esc_html( $t->name ); ?>
									</span>
                                        </a>
                                    <?php } ?>
                                </h4>
                            <?php } ?>

                            <?php if ( 'yes' === $settings->show_title ) { ?>

                                <a class="xpro-woo-product-grid-title-wrapper" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                                    <h2 class="xpro-woo-product-grid-title"><?php the_title(); ?></h2>
                                </a>

                            <?php } ?>

                            <?php if ( 'yes' === $settings->show_rating ) { ?>

                                <div class="xpro-woo-product-grid-star-rating-wrapper">
                                    <?php
                                    do_action( '', $post_id, $settings );
                                    woocommerce_template_loop_rating();
                                    do_action( '', $post_id, $settings );
                                    ?>
                                </div>

                            <?php } ?>

                            <?php if ( 'yes' === $settings->show_price ) { ?>

                                <div class="xpro-woo-product-grid-price-wrapper">
                                    <?php
                                    woocommerce_template_loop_price();
                                    ?>
                                </div>

                            <?php } ?>

                            <?php
                            if ( 'yes' === $settings->show_content ) {
                                $limit   = $settings->content_length ? $settings->content_length : 15;
                                $content = explode( ' ', get_the_excerpt(), $limit );

                                if ( count( $content ) >= $limit ) {
                                    array_pop( $content );
                                    $content = implode( ' ', $content ) . '...';
                                } else {
                                    $content = implode( ' ', $content );
                                }
                                $content = preg_replace( '`[[^]]*]`', '', $content );
                                ?>

                                <p class="xpro-woo-product-grid-excerpt">
                                    <?php echo $content; ?>
                                </p>

                            <?php } ?>

                            <?php if ( 'yes' === $settings->show_qv_action && '10' === $settings->layout ) { ?>

                                <div class="xpro-product-grid-hv-cta-section">
                                    <?php if ( 'yes' === $settings->show_qv_icon ) { ?>
                                        <div id="<?php echo esc_attr( $post_id ); ?>" class="xpro-hv-qv-btn xpro-cta-btn">
                                            <i class="xi xi-eye"></i>
                                        </div>
                                        <?php
                                    }

                                    if ( 'yes' === $settings->show_cart_icon ) {
                                        ?>
                                        <div class="xpro-hv-cart-btn xpro-cta-btn">
                                            <div class="xpro-qv-cart-btn">
                                                <?php
                                                do_action( '', $post_id, $settings );
                                                woocommerce_template_loop_add_to_cart();
                                                do_action( '', $post_id, $settings );
                                                ?>
                                            </div>
                                        </div>

                                    <?php } ?>
                                </div>

                            <?php } ?>

                            <?php if ( 'yes' === $settings->show_cta && ( '1' !== $settings->layout && '4' !== $settings->layout && '5' !== $settings->layout ) ) { ?>

                                <div class="xpro-woo-product-grid-btn-section">
                                    <div class="xpro-woo-product-grid-add-to-cart-btn">
                                        <?php
                                        do_action( '', $post_id, $settings );
                                        woocommerce_template_loop_add_to_cart();
                                        do_action( '', $post_id, $settings );
                                        ?>
                                    </div>
                                </div>

                            <?php } ?>

                        </div>

                    </div>

                    <?php
                    // Do shortcodes here so they are parsed in context of the current post.
                    echo do_shortcode( ob_get_clean() );
                }

                ?>
            </div>

        </div>
        <div class="xpro-qv-main-wrapper">
            <div class="xpro-qv-inner-wrapper xpro-qv-layouts xpro-qv-layout-<?php echo esc_attr( $settings->quick_view_styles->qv_layout ); ?>">

                <!-- quick view loader  -->
                <div class="xpro-qv-loader-wrapper xpro-qv-preloader-layout">
                    <div class="xpro-qv-preloader">
                        <div class="xpro-qv-preloader-box">
                            <div class="xpro-qv-loader-spinner spinner-1"></div>
                            <div class="xpro-qv-loader-spinner spinner-2"></div>
                            <div class="xpro-qv-loader-spinner spinner-3"></div>
                        </div>
                    </div>
                </div>

                <!-- quick view -->
                <div class="xpro-qv-popup-overlay"></div>
                <div class="xpro-qv-popup-wrapper">
                    <div class="xpro-qv-popup-inner">
                        <div id="xpro_elementor_fetch_qv_data" class="xpro-fetch-qv-cls"></div>
                    </div>
                </div>

            </div>
        </div>

        <?php
    }

    // Render the pagination.
    if ( 'yes' === $settings->show_pagination && $query->have_posts() && $query->max_num_pages > 1 ) :
        $prev_icon_class = $settings->pagination_styles->arrow;
        $next_icon_class = str_replace( 'left', 'right', $settings->pagination_styles->arrow );

        $prev_text = '<i class="' . $prev_icon_class . '"></i><span class="xpro-elementor-post-pagination-prev-text">' . $settings->pagination_styles->prev_label . '</span>';
        $next_text = '<span class="xpro-elementor-post-pagination-next-text">' . $settings->pagination_styles->next_label . '</span><i class="' . $next_icon_class . '"></i>';

        $pagination_args = array(
            'type'      => 'array',
            'current'   => max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) ),
            'total'     => $query->max_num_pages,
            'prev_next' => true,
            'prev_text' => $prev_text,
            'next_text' => $next_text,
        );

        if ( is_singular() && ! is_front_page() ) {
            global $wp_rewrite;
            if ( $wp_rewrite->using_permalinks() ) {
                $paginate_args['format'] = user_trailingslashit( 'page%#%', 'single_paged' ); // Change Occurs For Fixing Pagination Issue.
            } else {
                $paginate_args['format'] = '?page=%#%';
            }
        }

        $links = paginate_links( $pagination_args );
        ?>
        <nav class="xpro-elementor-post-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'xpro-bb-addons' ); ?>">
            <?php echo implode( PHP_EOL, $links ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </nav>


    <?php endif; ?>
    <?php

    // Render the empty message.
    if ( ! $query->have_posts() ) :

        ?>
        <div class="tnit-post-empty">
            <p><?php echo esc_attr( $settings->no_results_message ); ?></p>
            <?php if ( $settings->show_search ) : ?>
                <?php get_search_form(); ?>
            <?php endif; ?>
        </div>

    <?php

    endif;

    wp_reset_postdata();

}
