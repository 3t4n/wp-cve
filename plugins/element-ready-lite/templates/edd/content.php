<?php

    $author    = get_user_by( 'id', get_query_var( 'author' ) );
    $author_id = get_the_author_meta('ID');
    /*-----------------------
        ENABLE OPTION
    ------------------------*/
    global $post;
    $download_id               = get_the_ID();
    $download_cats             = get_the_term_list( get_the_ID(), 'download_category', '', _x(' , ', '', 'element-ready-lite' ), '' );
    $variable_pricing_options  = $settings['product_variable_pricing_options'];

?>
<?php
    /*-----------------------
        DOWNLOAD COUNT
    ------------------------*/
    global $edd_logs;
    $single_download_count = $edd_logs->get_log_count(get_the_ID(), 'file_download');
    $total_download_count  = $edd_logs->get_log_count('*', 'file_download');
    
    /*-----------------------
        SALE COUNT
    ------------------------*/
    $single_sales_count    = edd_get_download_sales_stats( get_the_ID() );
    $total_sales_count     = $edd_logs->get_log_count('*', 'file_sale');
    $single_sales_count    = $single_sales_count > 1 ? $single_sales_count . '<span class="sale__count__text">'.esc_html__( ' Sales', 'element-ready-lite' ).'</span>'  : $single_sales_count . '<span class="sale__count__text">'.esc_html__( ' Sale', 'element-ready-lite' ).'</span>';
    
    /*-----------------------
        PRICE DETAILS
    ------------------------*/
    $price_amount = edd_get_download_price(get_the_ID());
    $range_price  = edd_price_range( get_the_ID() );

    if ( $price_amount == "0.00" ) {
        $separate_price = esc_html__( 'Free', 'element-ready-lite' );
    }elseif( edd_has_variable_prices(get_the_ID()) ){
        $separate_price = edd_price( get_the_ID(), false );
    }else{
        $separate_price = edd_currency_filter( edd_format_amount( $price_amount ));
    }
    $price_with_decimal = edd_currency_filter($price_amount);

    /*------------------------
        PURCHASE BUTTON
    --------------------------*/
    $default_purchase_button  = edd_get_purchase_link( array( 'download_id' => get_the_ID()) );
    $separate_purchase_button = edd_get_purchase_link( array( 'download_id' => get_the_ID(),'price'=>false ) );
    $separate_variable_button = sprintf('<a href="%s" target="_blank" class="price__variable__button">%s</a>' , esc_url(get_the_permalink()) , esc_html__( 'View Details', 'element-ready-lite' ));

    /*-------------------------
        POST UNIQUE SLUG
    --------------------------*/
    $donwload_unique_slug     = get_post( get_the_ID())->post_name;
    /*edd_count_total_file_downloads();edd_get_file_downloaded_count()*/

    /*-------------------------
        PREVIEW URL & GRID STYLE
    --------------------------*/
    $live_preview_text  = $settings['product_preview_text'];
    $product_grid_style = $settings['product_grid_style'];

?>
<article <?php post_class(); ?>>
    <div class="single__donwload__product">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="download__thumb__gallery">
                <?php the_post_thumbnail($settings['thumbnail_size_size']); ?>
                <?php  if( 'product_grid_2' == $product_grid_style ) : ?>
                    <div class="download__price">
                        <?php echo wp_kses_post( $separate_price ); ?>
                    </div>
                <?php endif; ?>
                <div class="download__preview__button__block">
                    <!-- DWONLOAD BUTTON CUSTOM TEXT -->
                        <div class="download__custom__preview__link">
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" target="<?php echo esc_attr__( '_blank', 'element-ready-lite' ); ?>"><?php echo esc_html($live_preview_text); ?></a>
                        </div>
                    <!--  DOWNLOAD BUTTON CUSTOM TEXT END -->
                    <div class="download__bottom__flex">
                        <!-- PRICE DEFAULT & VARIABLE -->
                        <?php if ( $variable_pricing_options == 'default' ) : ?>
                            <?php echo wp_kses_post( edd_get_purchase_link( array( 'download_id' => get_the_ID() ) ) ); ?>
                        <?php else : ?>
                            <?php if( edd_has_variable_prices( get_the_ID() ) ) : ?>
                                <a class="price__variable__modl__button" href="#<?php echo esc_attr( $donwload_unique_slug ); ?>" data-lity>
                                    <?php esc_html_e('View Prices','element-ready-lite'); ?>
                                </a>
                                <!-- DOWNLOAD BUTTON MODAL -->
                                <div id="<?php echo esc_attr($donwload_unique_slug); ?>" class="download__modal__popup__overlay lity-hide">
                                    <div class="donwload__purchase__popup">
                                        <div class="modal__header ">
                                            <h4><?php esc_html_e('Choose Your Desired Options','element-ready-lite'); ?></h4>
                                            <button class="lity-close" type="button" data-lity-close>&times;</button>
                                        </div>
                                        <div class="modal__body">
                                            <?php echo wp_kses_post( edd_get_purchase_link( array( 'download_id' => get_the_ID()) ) ); ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- DOWNLOAD BUTTON MODAL END -->
                            <?php else : ?>
                                <?php echo wp_kses_post( edd_get_purchase_link( array( 'download_id' => get_the_ID() ) ) ); ?>
                            <?php endif; ?>

                        <?php endif; ?>
                        <!-- PRICE DEFAULT & VARIABLE END -->
                    </div>
                </div>
                
            </div>
        <?php endif; ?>
        
        <?php  if( !is_singular( 'download' ) ) : ?>
        <div class="download__product__details">
            
            <?php  if( 'product_grid_1' == $product_grid_style ) : ?>
            <div class="download__price__inline">
                <?php echo wp_kses_post( $separate_price ); ?>
            </div>
            <?php endif; ?>

            <div class="download__top__meta">
                <span class="download__metadata metadata__author"><?php esc_html_e('By ','element-ready-lite'); ?>
                    <?php if ( class_exists( 'EDD_Front_End_Submissions' ) ) : ?>
                        <a href="<?php echo esc_url( element_ready_fes_author_url(get_the_author_meta( 'ID',$author_id )) ); ?>"><?php the_author(); ?></a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(add_query_arg('author_downloads', 'true', get_author_posts_url( get_the_author_meta('ID')))); ?>"><?php the_author(); ?></a>
                    <?php endif; ?>
                </span>
                <?php if( $download_cats ) : ?>
                <span class="download__metadata metadata__category"><?php esc_html_e('In ','element-ready-lite'); ?><?php echo wp_kses_post( $download_cats ); ?></span>
                <?php endif; ?>
            </div>

            <div class="download__price__and__title">
                <?php 
                    if ( get_the_title() ) {
                        if ( is_single() ) {
                            the_title( '<h3 class="single__download__title">', '</h3>' );
                        }else{
                            the_title( '<h3 class="single__download__title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' );
                        }
                    }
                ?>
            </div>
            <?php if( 'yes' == $settings['show_sale_count'] || 'yes' == $settings['show_ratting'] ) : ?>
            <div class="download__bottom__meta">

                <?php if( 'yes' == $settings['show_sale_count'] ) : ?>
                <div class="download__count">
                    <i class="ti-shopping-cart"></i><?php echo wp_kses_post( $single_sales_count ); ?>
                </div>
                <?php endif; ?>

                <?php if( 'yes' == $settings['show_ratting']  ) : ?>
                <div class="download__review__count">
                    <?php element_ready_edd_rating(true); ?>
                </div>
                <?php endif; ?>

            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</article>