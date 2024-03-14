<?php 
namespace Enteraddons\Widgets\Product_Carousel\Traits;
/**
 * Enteraddons template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {
    
    public static function markup_style_1() {

        $settings = self::getDisplaySettings();
        $sliderSettings = self::carouselSettings();

        ?>
            <div class="entera-product-carousel-wrap">
                <?php
                //
                if( !empty( $settings['title'] ) ) {
                    echo '<div class="product-carousel-before-section-title-wrap"><h2 class="product-carousel-before-title">'.esc_html( $settings['title'] ).'</h2></div>';
                }
                ?>
                
                <div class="owl-carousel enteraddons-nav-style--seven"  data-slidersettings="<?php echo htmlspecialchars( $sliderSettings, ENT_QUOTES, 'UTF-8'); ?>">

                    <?php
                    // Product Query
                    $args = array(
                        'limit' => !empty( $settings['product_limit'] ) ? $settings['product_limit'] : 10,
                    );

                    // Featured Product
                    if( !empty( $settings['product_type'] ) && $settings['product_type'] == 'featured_product' ) {

                        $args['tax_query'][] = array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                        'operator' => 'IN', // or 'NOT IN' to exclude feature products
                        );

                    }
                    // on sale
                    if( !empty( $settings['product_type'] ) && $settings['product_type'] == 'on_sale' ) {

                        $args['meta_query'] = array(
                            'relation' => 'OR',

                            array( // Simple products type
                                'key'           => '_sale_price',
                                'value'         => 0,
                                'compare'       => '>',
                                'type'          => 'numeric'
                            ),
                            array( // Variable products type
                                'key'           => '_min_variation_sale_price',
                                'value'         => 0,
                                'compare'       => '>',
                                'type'          => 'numeric'
                            )

                        );
                    }
                    
                    $query = new \WC_Product_Query( $args );
                    $products = $query->get_products();
                    if( !empty( $products ) ):
                        foreach( $products  as $product ):
                        $productId = $product->get_id();
                        $productLink = get_permalink( $productId );
                        $sku = $product->get_sku();
                    ?>
                    <div class="enteraddons-product-carousel-single-item">
                        <!-- Image -->
                        <div class="enteraddons-shop-img position-relative">
                            <?php 
                            echo wp_kses_post( $product->get_image() );
                            ?>
                        </div>

                        <!-- Content -->
                        <div class="enteraddons-shop-content">
                            <?php echo '<div class="enteraddons-product-tag">'.wc_get_product_category_list( $productId ).'</div>'; ?>

                            <h6 class="enteraddons-product-title">
                                <a href="<?php echo esc_url( $productLink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
                            </h6>

                            <div class="product-carousel-item-meta">
                                <!-- Price -->
                                <span class="price">
                                    <?php
                                    echo wp_kses_post( $product->get_price_html() );
                                    ?>
                                </span>
                                <?php 
                                // Rating
                                echo '<span class="star-rating">';
                                \Enteraddons\Classes\Helper::ratingStar( $product->get_average_rating() );
                                echo '</span>';
                                ?>
                                <div class="enteraddons-product-action">
                                    <?php 
                                    if( $product->is_type( 'variable' ) ) {
                                        echo '<a href="'.esc_url( $productLink ).'" class="enteraddons-shop-btn" data-quantity="1" data-product_id="'.absint($productId).'" data-product_sku="'.esc_attr($sku).'"><i class="fa fa-plus-circle"></i></a>';
                                    } else {
                                        echo '<a href="'.esc_attr( '?add-to-cart='.$productId ).'" class="enteraddons-shop-btn add_to_cart_button ajax_add_to_cart" data-quantity="1" data-product_id="'.absint($productId).'" data-product_sku="'.esc_attr($sku).'"><i class="fas fa-cart-arrow-down"></i></a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    else: 
                        esc_html_e( 'No product found.', 'enteraddons' );
                    endif;
                    // End
                    ?>
                </div>
            </div>

        <?php
    }

}