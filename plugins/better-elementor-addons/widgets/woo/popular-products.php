<div class="better-woo-widgets">
    <div class="item">
        <div class="tit">
            <h4><?php echo esc_html__('Popular Products', 'better-el-addons'); ?></h4>
        </div>
        <?php if ( class_exists( 'WooCommerce' ) ) { ?>
            <div class="pop-prod">
                <?php 
                $args = array(
                    'post_type'         => array( 'product' ),
                    'meta_key'          => 'total_sales',
                    'orderby'           => 'meta_value_num',
                    'order'             => 'desc',
                    'posts_per_page'    => 5
                );

                $popular_products = new WP_Query( $args );

                if ( $popular_products->have_posts() ) :
                    while ( $popular_products->have_posts() ) : $popular_products->the_post(); ?>
                        
                        <div class="product">
                            <div class="img">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="">
                            </div>
                            <div class="info">
                                <h6><a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a></h6>
                                <?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
                                <span class="price"><?php echo wp_kses_post( wc_price( $price ) ); ?></span>
                                <div class="rate woocommerce">
                                    <?php
                                        global $product;
                                        $rating_count = $product->get_rating_count();
                                        $review_count = $product->get_review_count();
                                        $average      = $product->get_average_rating();
                                        
                                        if ( $rating_count >= 0 ) : ?>
                                        
                                            <?php echo wp_kses_post( wc_get_rating_html($average, $rating_count) ); ?>                                    
                                        
                                        <?php endif;
                                    ?>
                                </div>
                            </div>
                        </div>

                    <?php
                    endwhile;
                endif;

                wp_reset_postdata();
                ?>
            </div>
        <?php }; ?>
    </div>
</div>
