 
<?php if (!get_post_meta(get_the_id(), 'meta_show_overlay', true)) : ?>
    <div class="overlay">
        <div class="meta-style-one">
            <ul class="product-buttons mr_pro_list wps_order_container">
            
                <?php if (isset($settings['show_whishlist']) && $settings['show_whishlist']) : ?>
                    <?php if (function_exists('yith_wishlist_constructor')) : ?>
                        <?php if (!get_post_meta(get_the_id(), 'meta_show_wishlist', true)) : ?>
                            <li class="single_metas order-<?php echo $settings['overlay_order_one']; ?>">
                                <span class="tool_tip <?php echo esc_attr($settings['tooltip_alingment']); ?>"><?php echo $settings['wishlist_tooltip']; ?></span>
                                <a href="<?php echo $settings['wps_wishlist_link']; ?>"> <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
           
                <?php if (isset($settings['show_compare']) && $settings['show_compare']) : ?>
                    <?php if (!get_post_meta(get_the_id(), 'meta_show_compare', true)) : ?>
                        <li class="single_metas mr_compare_li order-<?php echo $settings['overlay_order_two']; ?>">
                            <a class="compare mr_compare_a" data-product_id="<?php echo get_the_ID(); ?>"></a>
                            <span class="tool_tip <?php echo esc_attr($settings['tooltip_alingment']); ?>"><?php echo $settings['compare_tooltip']; ?></span>
                            <i class="mr_compare_i <?php echo str_replace("icon ", " ", esc_attr($settings['compare_icon']['value'])); ?>"></i>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
        
                <?php if (isset($settings['show_quickview']) && $settings['show_quickview']) : ?>
                    <?php if (!get_post_meta(get_the_id(), 'meta_show_quickview', true)) : ?>
                        <li class="single_metas btn_same btn-quick mr_quickview_li order-<?php echo $settings['overlay_order_three']; ?>">
                            <a href="<?php echo esc_attr($product->get_id()); ?>" class="mr_quickview_a wpsection_quick_view_btn">
                                <span class="tool_tip <?php echo esc_attr($settings['tooltip_alingment']); ?>"><?php echo $settings['quickview_tooltip']; ?></span>
                                <i class="mr_quickview_i <?php echo str_replace("icon ", " ", esc_attr($settings['quickview_icon']['value'])); ?>"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (isset($settings['show_quickview']) && $settings['show_quickview']) : ?>
                    <?php if (!get_post_meta(get_the_id(), 'meta_show_addtocart', true)) : ?>
                        <li class="single_metas mr_addtocart mr_addtocar_li order-<?php echo $settings['overlay_order_four']; ?>">
                            <a href="<?php echo site_url(); ?>/?add-to-cart=<?php echo get_the_ID(); ?>" data-quantity="1" class="mr_addtocart_a product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo get_the_ID(); ?>"></a>
                            <span class="tool_tip <?php echo esc_attr($settings['tooltip_alingment']); ?>"><?php echo $settings['addtocart_tooltip']; ?></span>
                            <i class="addtocarticon <?php echo str_replace("icon ", " ", esc_attr($settings['addtocart_icon']['value'])); ?>"></i>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

            </ul>
        </div>
    </div>

<?php endif; ?>
