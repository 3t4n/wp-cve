<?php
if(!defined('ABSPATH')){
    exit;
  }
/*
* Product Grid Default Layout
*/

 foreach ($settings['products'] as $product) : 
       
        $product_image     = $product->get_image( $settings['image_size'], ['loading' => 'eager']);
        $product_price     = wc_price($product->get_price());
        $product_name      = wp_trim_words( $product->get_name(), $settings['post_title_crop'] , '' );
        $product_permalink = $product->get_permalink();
        $addToCartUrl      = $product->add_to_cart_url();
        $rating_count      = $product->get_rating_count();
        $review_count      = $product->get_review_count();
        $average           = $product->get_average_rating();
        $is_featured       = $product->get_featured();
        $total_sales       = $product->get_total_sales();
      

?>

<div class="wooready_product_components">
    <div class="wooready_product_layout_default wooready_product_layout_wrapper">
        <div class="wooready_product_thumb text-align:center position:relative ">
            <?php echo wp_kses_post( $product_image ); ?>
            <span class="wooready_sell_discount position:absolute top:15 left:15 border-radius:3">
                <?php if ($is_featured) {
                        echo esc_html( $settings['featured_text'] );
                    } else if( $total_sales >= 50 ) {
                        echo esc_html( $settings['popular_text'] );
                    } else {
                        echo esc_html( $settings['sale_text'] );
                    }
                ?>
            </span>
        </div>
        <div class="wooready_product_content_box text-align:left position:relative">
            <div class="wooready_title order:2">
                <h3 class="title">
                    <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_name ); ?></a>
                </h3>
            </div>
            <div class="wooready_review display:flex justify-content:space-between text-align:center flex-basis:100 ">

                <div class="wooready_price_box">
                    <span class="wooready_price_normal"><?php echo wp_kses_post($product_price); ?></span>
                </div>

                <?php  echo wp_kses_post(sprintf('<ul class="wooready_review_box display:inline-block">'));

                    foreach (range(0, 4) as $number) {
                        if( $number < $average ){
                            wp_kses_post(sprintf(' <li><i class="fa fa-star"></i></li>'));
                        }else{
                            echo wp_kses_post(sprintf(' <li><i class="wrinactive fa fa-star"></i></li>'));
                        }
                    }

                echo wp_kses_post('</ul>');?>
            </div>
            <div
                class="wooready_product_cart_box display:flex justify-content:flex-start align-items:center position:absolute">

                <div class="wooready_product_cart">
                    <?php if($product->is_type('simple')): ?>
                    <a class="ajax_add_to_cart <?php echo esc_attr($product->get_type()); ?>"
                        data-product-type='<?php echo esc_attr($product->get_type()); ?>' data-quantity="1"
                        data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                        href="<?php echo esc_url($addToCartUrl); ?>"><?php echo wp_kses_post($settings['add_to_cart_icon']); ?></i>
                        <?php echo wp_kses_post($settings['cart_text']); ?></a>
                    <?php else: ?>
                    <a class="add-to-cart <?php echo esc_attr($product->get_type()); ?>"
                        href="<?php echo esc_url( $addToCartUrl ); ?>"><?php echo wp_kses_post( $settings['add_to_cart_icon'] ); ?></i>
                        <?php echo esc_html( $settings['cart_text'] ); ?></a>
                    <?php endif; ?>
                </div>

                <?php if(apply_filters( 'shop_ready_product_quick_view_enable', false )): ?>
                <div class="wooready_product_popup">
                    <a href="#" data-product-type='<?php echo esc_attr( $product->get_type() ); ?>'
                        data-product_id='<?php echo esc_attr($product->get_id()); ?>' class='wready-product-quickview'>
                        <?php echo wp_kses_post($settings['quick_view_icon']); ?></i></a>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php endforeach; ?>