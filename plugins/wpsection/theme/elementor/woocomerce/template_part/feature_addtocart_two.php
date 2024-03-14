<?php
$unique_id = 'product_basic_one_' . uniqid(); 
echo '
<script>
    jQuery(document).ready(function ($) {
        $(".' . $unique_id . ' .cart.wps_cart_qnt").on("click", ".plus, .minus", function () {
            var $qty = $(this).closest(".' . $unique_id . ' .cart").find(".qty");
            var currentVal = parseInt($qty.val()) || 0;
            var max = parseFloat($qty.attr("max")) || 0;
            var min = parseFloat($qty.attr("min")) || 0;
            var step = 1; // Set step to 1 for each click

            if ($(this).is(".plus")) {
                if (!max || (currentVal < max)) {
                    $qty.val(currentVal + step).change();
                }
            } else {
                if (!min || (currentVal > min)) {
                    $qty.val(Math.max(min, currentVal - step)).change();
                }
            }
        });
    });     
</script>';
?>

       

    <?php if ($settings['show_product_features']) { ?> 
        <?php if (!get_post_meta(get_the_id(), 'meta_show_featuretext', true)) : ?>                                        
            <div class="wps_order order-<?php echo $settings['position_order_seven']; ?> ">
                <div class="wps_meta_text">
                    <?php echo wp_kses(get_post_meta(get_the_id(), 'meta_text', true), wp_kses_allowed_html('post')); ?>                     
                </div> 
            </div>  
        <?php endif; ?>                                          
    <?php } ?>                                          


    <?php if ($settings['show_prduct_x_button']) { ?> 
        <?php if (!get_post_meta(get_the_id(), 'meta_show_product_button', true)) : ?>                             
            <div class="wps_order order-<?php echo $settings['position_order_eight']; ?> <?php echo $unique_id; ?> hider_area_2">  

                <?php if ($settings['wps_quick_view_button_link']) : ?>       
                    <div class="quick_defult_wps">
                        <a href="<?php echo $settings['wps_quick_view_button_link']; ?>"> 
                            <button class="open-wps wps_button"><?php echo $settings['wps_quick_view_button']; ?></button>
                        </a>
                    </div>  
                <?php else : ?> 
				
			
				
                    <?php if ($product->is_in_stock()) :
                        do_action('woocommerce_before_add_to_cart_form');
                    ?>
                        <form class="cart wps_cart_qnt" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                            <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                            <?php if ($settings['wps_product_qun_hide']) { ?>       
                                <div class="wps_qnt_button">
                                    <div class="quantity">
                                        <input type="button" value="-" class="minus">
                                        <input type="number" class="qty" step="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>" name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" />
                                        <input type="button" value="+" class="plus">
                                    </div>
                                </div>
                            <?php } ?>      

                            <div class="cart-btn quick_defult_wps">
                                <button class="open-wps wps_button" type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" > 
                                    <?php if ($settings['show_prduct_addtocart_icon']) { ?>     
                                        <i class="<?php echo str_replace("icon ", " ", esc_attr($settings['wps_product_adcart_icon']['value'])); ?>"></i> 
                                    <?php } ?>      
                                    <?php echo $settings['wps_quick_view_button']; ?>
                                </button>
                            </div>

                            <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                        </form>

                        <?php do_action('woocommerce_after_add_to_cart_form'); ?>

                    <?php endif; ?>
                <?php endif; ?>

            </div>                            
        <?php endif; ?>                                         
    <?php } ?>  


