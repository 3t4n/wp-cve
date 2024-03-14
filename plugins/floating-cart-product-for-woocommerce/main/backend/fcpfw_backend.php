<?php
add_action( 'admin_menu','fcpfw_submenu_page');
function fcpfw_submenu_page() {
    add_submenu_page( 'woocommerce', 'Floating Cart', 'Floating Cart', 'manage_options', 'floating-cart','fcpfw_callback');
}

function fcpfw_callback() {
    global $fcpfw_comman , $ocwqv_qfcpfw_icon;?>

        <div class="wrap">
            <h2><?php echo __( 'Cart Setting', 'floating-cart-product-for-woocommerce' );?></h2>
            <div class="card fcpfw_notice">
                <h2><?php echo __('Please help us spread the word & keep the plugin up-to-date', 'floating-cart-product-for-woocommerce');?></h2>
                <p>
                    <a class="button-primary button" title="<?php echo __('Support Floating Cart Product', 'floating-cart-product-for-woocommerce');?>" target="_blank" href="https://www.plugin999.com/support/"><?php echo __('Support', 'floating-cart-product-for-woocommerce'); ?></a>
                    <a class="button-primary button" title="<?php echo __('Rate Floating Cart Product', 'floating-cart-product-for-woocommerce');?>" target="_blank" href="https://wordpress.org/support/plugin/floating-cart-product-for-woocommerce/reviews/?filter=5"><?php echo __('Rate the plugin ★★★★★', 'floating-cart-product-for-woocommerce'); ?></a>
                </p>
            </div>
            <?php if(isset($_REQUEST['message']) && $_REQUEST['message'] == 'success'){ ?>
                <div class="notice notice-success is-dismissible"> 
                    <p><strong><?php echo __( 'Record updated successfully.', 'floating-cart-product-for-woocommerce' );?></strong></p>
                </div>
            <?php } ?>
        </div>
        <div class="fcpfw_container">
            <form method="post" >
                <?php wp_nonce_field( 'fcpfw_nonce_action', 'fcpfw_nonce_field' ); ?>
                <ul class="nav-tab-wrapper woo-nav-tab-wrapper">
                    <li class="nav-tab" data-tab="fcpfw-tab-general"><?php echo __( 'General Settings', 'floating-cart-product-for-woocommerce' );?></li>
                    <li class="nav-tab" data-tab="fcpfw-tab-other"><?php echo __( 'Custom Style', 'floating-cart-product-for-woocommerce' );?></li>
                    <li class="nav-tab" data-tab="fcpfw-tab-translations"><?php echo __( 'Translations', 'floating-cart-product-for-woocommerce' );?></li>
                </ul>
                <div id="fcpfw-tab-general" class="tab-content current">
                    <div class="postbox">
                            <div class="postbox-header">
                                <h2><?php echo __( 'Show Cart Basket', 'floating-cart-product-for-woocommerce' );?></h2>
                            </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __( 'Display All Pages', 'floating-cart-product-for-woocommerce' );?></th>
                                    <td>
                                        <input type="checkbox" class="fcpfw_all_pages" name="fcpfw_comman[fcpfw_all_pages]" value="yes" checked disabled>
                                        <label><?php echo __('All Page','floating-cart-product-for-woocommerce');?></label><br>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr class="fcpfw_single_pages">
                                    <th><?php echo __( 'Selected Pages', 'floating-cart-product-for-woocommerce' );?></th>
                                    <td class="scpfw_visibility_on_pages">
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_display_home_page]" value="yes"  checked disabled>
                                        <label><?php echo __('Display On Home Page','floating-cart-product-for-woocommerce');?></label></br>

                                        <input type="checkbox" class="fcpfw_display_shop_page" name="fcpfw_comman[fcpfw_display_shop_page]" value="yes" checked disabled>
                                        <label><?php echo __('Display On Shop Page','floating-cart-product-for-woocommerce');?></label></br>

                                        <input type="checkbox" class="fcpfw_display_product_page" name="fcpfw_comman[fcpfw_display_product_page]" value="yes"checked disabled>
                                        <label><?php echo __('Display On Single Product Page','floating-cart-product-for-woocommerce');?></label></br>

                                        <input type="checkbox" name="fcpfw_comman[fcpfw_display_cart_page]" value="yes" checked disabled>
                                        <label><?php echo __('Display On Cart Page','floating-cart-product-for-woocommerce');?></label></br>

                                        <input type="checkbox" name="fcpfw_comman[fcpfw_display_checkout_page]" value="yes" checked disabled>
                                        <label><?php echo __('Display On Checkout Page','floating-cart-product-for-woocommerce');?></label></br>

                                        <input type="checkbox" name="fcpfw_comman[product_cat_page]" value="yes" checked disabled>
                                        <label><?php echo __('Product Category Page','floating-cart-product-for-woocommerce');?></label><br>

                                        <input type="checkbox" name="fcpfw_comman[product_tag_page]" value="yes" checked disabled>
                                        <label><?php echo __('Product Tag Page','floating-cart-product-for-woocommerce');?></label><br>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                         
                        <div class="postbox-header">
                            <h2><?php echo __('Side cart','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                            <table class="data_table">
                            
                                <tr>
                                    <th><?php echo __('Auto Open Cart','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_auto_open]" value="yes" <?php if ($fcpfw_comman['fcpfw_auto_open'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __( 'After Add to Cart Immeditaliy Open', 'floating-cart-product-for-woocommerce' );?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Trigger to class open cart','floating-cart-product-for-woocommerce');?> </th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_trigger_class]" value="yes" <?php if ($fcpfw_comman['fcpfw_trigger_class'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __( 'After Enable trigger then side cart open automatically', 'floating-cart-product-for-woocommerce' );?></strong>
                                        <p class="fcpfw-tips"><?php echo __( 'Note:If Enable then You need to add this class', 'floating-cart-product-for-woocommerce' );?> <strong><?php echo __( 'fcpfw_trigger', 'floating-cart-product-for-woocommerce' );?></strong><?php echo __( 'where you want to add triggers.', 'floating-cart-product-for-woocommerce' );?> </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                        
                        <div class="postbox-header">
                            <h2><?php echo __('Cart Header','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Show in Header','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_header_cart_icon]" value="yes" <?php if ($fcpfw_comman['fcpfw_header_cart_icon'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Basket Icon','floating-cart-product-for-woocommerce');?></strong><br/>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_header_close_icon]" value="yes" <?php if ($fcpfw_comman['fcpfw_header_close_icon'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Close Icon','floating-cart-product-for-woocommerce');?></strong><br/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show Freeshipping in Header','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_freeshiping_herder]" value="yes" <?php if ($fcpfw_comman['fcpfw_freeshiping_herder'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show after Freeshipping Text in Header','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_freeshiping_herder_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_freeshiping_herder_txt']); ?>" >
                                        <span class="ocwg_desc"><?php echo __('Use tag {shipping_total} for Shipping rate','floating-cart-product-for-woocommerce');?></span>
                                                                            
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show Freeshipping Text in Header','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_freeshiping_then_herder_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_freeshiping_then_herder_txt']); ?>" >
                                        <span class="ocwg_desc"><?php echo __('get Freeshipping text','floating-cart-product-for-woocommerce');?></span>
                                                                            
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                         
                            <div class="postbox-header">
                                <h2><?php echo __('Cart Loop','floating-cart-product-for-woocommerce');?></h2>
                            </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Show in Loop','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_loop_img]" value="yes" <?php if ($fcpfw_comman['fcpfw_loop_img'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Product Image','floating-cart-product-for-woocommerce');?></strong><br/>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_loop_product_name]" value="yes" <?php if ($fcpfw_comman['fcpfw_loop_product_name'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Product Name','floating-cart-product-for-woocommerce');?></strong><br/>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_loop_product_price]" value="yes" <?php if ($fcpfw_comman['fcpfw_loop_product_price'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Product Price','floating-cart-product-for-woocommerce');?></strong><br/>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_loop_total]" value="yes" <?php if ($fcpfw_comman['fcpfw_loop_total'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Product Total','floating-cart-product-for-woocommerce');?></strong><br/>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_loop_variation]" value="yes" <?php if ($fcpfw_comman['fcpfw_loop_variation'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Product Variations','floating-cart-product-for-woocommerce');?></strong><br/>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_loop_link]" value="yes" <?php if ($fcpfw_comman['fcpfw_loop_link'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Link to Product Page','floating-cart-product-for-woocommerce');?></strong><br/>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_loop_delete]" value="yes" <?php if ($fcpfw_comman['fcpfw_loop_delete'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Delete Product','floating-cart-product-for-woocommerce');?></strong><br/>
                                    </td>
                                </tr>

                                <tr>
                                    <th><?php echo __('Display Qty Box','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_qty_box]" value="yes" <?php if ($fcpfw_comman['fcpfw_qty_box'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Display Product Qty box.','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                            <div class="postbox-header">
                                <h2><?php echo __('Footer Settings','floating-cart-product-for-woocommerce');?></h2>
                            </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Show Shipping Total','floating-cart-product-for-woocommerce');?> </th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_total_shipping_option"  disabled>
                                        <strong><?php echo __('Show Shipping Total.','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show Discount ','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_discount_show_cart" disabled>
                                        <strong><?php echo __('Show Discount in cart','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show Tax Total ','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_total_tax_option" disabled>
                                        <strong><?php echo __('Show Tax Total.','floating-cart-product-for-woocommerce');?></strong>
                                         <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show All Total ','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_total_all_option" value="yes" disabled>
                                        <strong><?php echo __('Show All Total.','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                
                                
                                <tr>
                                    <th><?php echo __('Show ViewCart Button','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_cart_option]" value="yes" <?php if ($fcpfw_comman['fcpfw_cart_option'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Show Viewcart Button.','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show Checkout Button','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_checkout_option]" value="yes" <?php if ($fcpfw_comman['fcpfw_checkout_option'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Show Checkout Button.','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Show Continue Shopping Button','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_conshipping_option]" value="yes" <?php if ($fcpfw_comman['fcpfw_conshipping_option'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Show Continue Shipping Button.','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                        
                            <div class="postbox-header">
                                <h2><?php echo __('Coupon Field','floating-cart-product-for-woocommerce');?></h2>
                            </div>
                         <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Coupon Field on Mobile','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_coupon_field_mobile]" value="yes" <?php if ($fcpfw_comman['fcpfw_coupon_field_mobile'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Enable Coupon Field on Mobile','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr> 
                                
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                        
                        <div class="postbox-header">
                            <h2><?php echo __('Cart Product Slider','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                            <table class="data_table">
                                 <tr>
                                    <th><?php echo __('Product Slider on Desktop','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_prodslider_desktop" disabled>
                                        <strong><?php echo __('Enable Product Slider on Desktop','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Product Slider on Mobile','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_prodslider_mobile" value="yes" disabled>
                                        <strong><?php echo __('Enable Product Slider on Mobile','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>

                                <tr>
                                    <th><?php echo __('Select Product','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select id="fcpfw_select_product" name="fcpfw_select2[]" multiple="multiple" style="width:100%;max-width:15em;" disabled>
                                            <?php 
                                                $productsa = get_option('fcpfw_select2');
                                                if(!empty($productsa)){
                                                    foreach ($productsa as $value) {
                                                        $productc = wc_get_product( $value );
                                                        if ( !empty($productc) && $productc->is_in_stock() && $productc->is_purchasable() ) {
                                                            $title = $productc->get_name();
                                                            ?>
                                                                <option value="<?php echo esc_attr($value); ?>" selected="selected"><?php echo esc_attr($title); ?></option>
                                                            <?php   
                                                        }
                                                    }
                                                }
                                            ?>
                                       </select> 
                                       <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>   
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                        
                        
                        <div class="postbox-header">
                            <h2><?php echo __('Cart basket','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                         <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Enable','floating-cart-product-for-woocommerce');?> </th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_cart_show_hide]">
                                                <option value="fcpfw_cart_show" <?php if ($fcpfw_comman['fcpfw_cart_show_hide'] == "fcpfw_cart_show" ) { echo 'selected'; } ?>><?php echo __('Always Show','floating-cart-product-for-woocommerce');?></option>
                                                <option value="fcpfw_cart_hide" <?php if ($fcpfw_comman['fcpfw_cart_show_hide'] == "fcpfw_cart_hide" ) { echo 'selected'; } ?>><?php echo __('Always Hide','floating-cart-product-for-woocommerce');?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Cart basket Hide','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_cart_empty" value="yes" disabled>
                                        <strong><?php echo __('If Cart is Empty Then Cart Basket Hide','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Cart Icon','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_show_cart_icn]" value="yes" checked disabled>
                                        <strong><?php echo __('Show Cart Icon','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>   
                                <tr>
                                    <th><?php echo __('On Cart & Checkout Page','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_cart_check_page]"   disabled>
                                        <strong><?php echo __('Show Cart Basket on cart and checkout pages.','floating-cart-product-for-woocommerce');?></strong>
                                         <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Cart on Mobile','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_mobile]" value="yes" <?php if ($fcpfw_comman['fcpfw_mobile'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Show Cart on mobile device.','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr> 
                                <tr>
                                    <th><?php echo __('Product Count','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="checkbox" name="fcpfw_comman[fcpfw_product_cnt]" value="yes" <?php if ($fcpfw_comman['fcpfw_product_cnt'] == "yes" ) { echo 'checked="checked"'; } ?>>
                                        <strong><?php echo __('Show Product Count.','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th><?php echo __('Basket Count Type','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_product_cnt_type]">
                                                <option value="sum_qty" <?php if ($fcpfw_comman['fcpfw_product_cnt_type'] == "sum_qty" ) { echo 'selected'; } ?>><?php echo __('Sum of Quantity of all the products','floating-cart-product-for-woocommerce');?></option>
                                                <option value="num_qty" <?php if ($fcpfw_comman['fcpfw_product_cnt_type'] == "num_qty" ) { echo 'selected'; } ?>><?php echo __('Number of products','floating-cart-product-for-woocommerce');?></option>
                                        </select>
                                    </td>
                                </tr> 
                                <tr>
                                    <th><?php echo __('Basket Product ordering','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_cart_ordering]">
                                                <option value="asc" <?php if ($fcpfw_comman['fcpfw_cart_ordering'] == "asc" ) { echo 'selected'; } ?>><?php echo __('Recently added item at last (Asc)','floating-cart-product-for-woocommerce');?></option>
                                                <option value="desc" <?php if ($fcpfw_comman['fcpfw_cart_ordering'] == "desc" ) { echo 'selected'; } ?>><?php echo __('Recently added item on top (Desc)','floating-cart-product-for-woocommerce');?></option>
                                        </select>
                                    </td>
                                </tr> 
                                <tr>
                                    <th><?php echo __('Hide Basket Pages','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_on_pages" value="" disabled>
                                        <strong><?php echo __('Do not show basket on pages.','floating-cart-product-for-woocommerce');?></strong>
                                        <strong><?php echo __('Use page id separated by comma. For eg: 31,41,51','floating-cart-product-for-woocommerce');?></strong>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr> 
                            </table>
                         </div>
                    </div> 
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php echo __('All Urls Set','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                            <table class="data_table">
                                
                                <tr>
                                    <th><?php echo __('Continue Shopping Button Link','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_conshipping_link]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_conshipping_link']); ?>">
                                        <strong><?php echo __('Use "#" for the same page','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Empty Cart Button Link','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_emptycart_link]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_emptycart_link']); ?>">
                                        <strong><?php echo __('Use "#" for the same page','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Custom Cart Button Link','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_orgcart_link]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_orgcart_link']); ?>">
                                        <strong><?php echo __('if is empty then going cart page','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Custom checkout Button Link','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_orgcheckout_link]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_orgcheckout_link']); ?>">
                                        <strong><?php echo __('if is empty then going checkout page','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>  
                </div>
                <div id="fcpfw-tab-other" class="tab-content">
                    <div class="postbox">
                        <div class="postbox-header">
                             <h2><?php echo __('Important Setting','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Side Cart Width','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_sidecart_width]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_sidecart_width']); ?>">
                                        <strong><?php echo __('(in px - eg. 350)','floating-cart-product-for-woocommerce');?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Side Cart Height','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_cart_height]">
                                                <option value="full" <?php if ($fcpfw_comman['fcpfw_cart_height'] == "full" ) { echo 'selected'; } ?>><?php echo __('Full','floating-cart-product-for-woocommerce');?></option>
                                                <option value="auto" <?php if ($fcpfw_comman['fcpfw_cart_height'] == "auto" ) { echo 'selected'; } ?>><?php echo __('Auto','floating-cart-product-for-woocommerce');?></option>
                                        </select>
                                    </td>
                                </tr>
                                 <tr>
                                    <th><?php echo __('Open Side Cart From','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_cart_open_from]">
                                                <option value="right" <?php if ($fcpfw_comman['fcpfw_cart_open_from'] == "right" ) { echo 'selected'; } ?>><?php echo __('Right','floating-cart-product-for-woocommerce');?></option>
                                                <option value="left" <?php if ($fcpfw_comman['fcpfw_cart_open_from'] == "left" ) { echo 'selected'; } ?>><?php echo __('Left','floating-cart-product-for-woocommerce');?></option>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                            <div class="postbox-header">
                                <h2><?php echo __('Header Setting','floating-cart-product-for-woocommerce');?></h2>
                            </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Header Font Size','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="number" name="fcpfw_comman[fcpfw_head_ft_size]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_head_ft_size']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Header Font Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_head_ft_clr'])) {
                                                $fcpfw_head_ft_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_head_ft_clr);?>" name="fcpfw_comman[fcpfw_head_ft_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_head_ft_clr']);?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Header cart icon','floating-cart-product-for-woocommerce');?></th>
                                    <td class="ocwqv_icon_choice">
                                            
                                            <input type="radio" name="fcpfw_comman[ofcpfw_shop_icon]" value="shop_icon_1" <?php if ($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_1" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_1'])); ?>
                                            </label>
                    
                                            <input type="radio" name="fcpfw_comman[ofcpfw_shop_icon]" value="shop_icon_2" <?php if ($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_2" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_2'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ofcpfw_shop_icon]" value="shop_icon_3"  <?php if ($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_3" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_3'])); ?>
                                            </label>
                                        
                                            <input type="radio" name="fcpfw_comman[ofcpfw_shop_icon]" value="shop_icon_4" <?php if ($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_4" ) { echo 'checked'; } ?>>
                                            <label>
                                                 <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_4'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ofcpfw_shop_icon]" value="shop_icon_5"  <?php if ($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_5" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_5'])); ?>
                                            </label> 

                                            <input type="radio" name="fcpfw_comman[ofcpfw_shop_icon]" value="shop_icon_6"  <?php if ($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_6" ) { echo 'checked'; } ?>>
                                            <label>
                                                <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_6'])); ?>
                                            </label>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <th><?php echo __('Header cart icon Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_header_cart_icon_clr'])) {
                                                $fcpfw_header_cart_icon_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_header_cart_icon_clr); ?>" name="fcpfw_comman[fcpfw_header_cart_icon_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_header_cart_icon_clr']); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Header cart close icon','floating-cart-product-for-woocommerce');?></th>
                                    <td class="ocwqv_icon_choice_close">
                                            
                                            <input type="radio" name="fcpfw_comman[ofcpfw_close_icon]" value="close_icon" <?php if ($fcpfw_comman['ofcpfw_close_icon'] == "close_icon" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon'])); ?>
                                            </label>
                    
                                            <input type="radio" name="fcpfw_comman[ofcpfw_close_icon]" value="close_icon_1" <?php if ($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_1" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_1'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ofcpfw_close_icon]" value="close_icon_2"  <?php if ($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_2" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_2'])); ?>
                                            </label>
                                        
                                            <input type="radio" name="fcpfw_comman[ofcpfw_close_icon]" value="close_icon_3" <?php if ($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_3" ) { echo 'checked'; } ?>>
                                            <label>
                                                 <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_3'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ofcpfw_close_icon]" value="close_icon_4"  <?php if ($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_4" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_4'])); ?>
                                            </label> 
                                            <input type="radio" name="fcpfw_comman[ofcpfw_close_icon]" value="close_icon_5"  <?php if ($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_5" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_5'])); ?>
                                            </label> 

                                    </td>
                                    
                                </tr>
                                 <tr>
                                    <th><?php echo __('Header Close icon Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_header_close_icon_clr'])) {
                                                $fcpfw_header_close_icon_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_header_close_icon_clr); ?>" name="fcpfw_comman[fcpfw_header_close_icon_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_header_close_icon_clr']); ?>"/>
                                    </td>
                                </tr>

                                <tr>
                                    <th><?php echo __('Show Freeshipping Text in Header color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_header_shipping_text_color'])) {
                                                $fcpfw_header_shipping_text_color = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_header_shipping_text_color); ?>" name="fcpfw_comman[fcpfw_header_shipping_text_color]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_header_shipping_text_color']); ?>"/>
                                    </td>
                                </tr>
                                

                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                            
                         
                            <div class="postbox-header">
                                <h2><?php echo __('Cart Loop Setting','floating-cart-product-for-woocommerce');?></h2>
                            </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Product Title Font Size','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="number" name="fcpfw_comman[fcpfw_product_ft_size]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_product_ft_size']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Product Title Font Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_product_ft_clr'])) {
                                                $fcpfw_product_ft_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_product_ft_clr); ?>" name="fcpfw_comman[fcpfw_product_ft_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_product_ft_clr']); ?>"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                     <div class="postbox">
                            
                        <div class="postbox-header">
                            <h2><?php echo __('Empty Cart','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Cart Empty show/hide all cart detail','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_cart_empty_hide_show]" disabled>
                                                <option value="show" <?php if ($fcpfw_comman['fcpfw_cart_empty_hide_show'] == "show" ) { echo 'selected'; } ?>><?php echo __('Show All Detail','floating-cart-product-for-woocommerce');?></option>
                                                <option value="hide" <?php if ($fcpfw_comman['fcpfw_cart_empty_hide_show'] == "hide" ) { echo 'selected'; } ?>><?php echo __('Hide All Detail','floating-cart-product-for-woocommerce');?></option>
                                        </select>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                            
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                        
                         <div class="postbox-header">
                            <h2><?php echo __('Side cart','floating-cart-product-for-woocommerce');?></h2>
                         </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <td>
                                        <h3><?php echo __('Delete Setting','floating-cart-product-for-woocommerce');?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Delete Icons','floating-cart-product-for-woocommerce');?></th>
                                    <td class="ocwqv_icon_choice">
                                            <input type="radio" name="fcpfw_comman[ofcpfw_delete_icon]" value="ocwqv_trash" <?php if ($fcpfw_comman['ofcpfw_delete_icon'] == "ocwqv_trash" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_trash'])); ?>
                                            </label>
                                            <input type="radio" name="fcpfw_comman[ofcpfw_delete_icon]" value="trash_1" <?php if ($fcpfw_comman['ofcpfw_delete_icon'] == "trash_1" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['trash_1'])); ?>
                                            </label>
                    
                                            <input type="radio" name="fcpfw_comman[ofcpfw_delete_icon]" value="trash_2" <?php if ($fcpfw_comman['ofcpfw_delete_icon'] == "trash_2" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['trash_2'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ofcpfw_delete_icon]" value="trash_3"  <?php if ($fcpfw_comman['ofcpfw_delete_icon'] == "trash_3" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['trash_3'])); ?>
                                            </label>
                                        
                                            <input type="radio" name="fcpfw_comman[ofcpfw_delete_icon]" value="trash_4" <?php if ($fcpfw_comman['ofcpfw_delete_icon'] == "trash_4" ) { echo 'checked'; } ?>>
                                            <label>
                                                 <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['trash_4'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ofcpfw_delete_icon]" value="trash_5"  <?php if ($fcpfw_comman['ofcpfw_delete_icon'] == "trash_5" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['trash_5'])); ?>
                                            </label> 

                                            <input type="radio" name="fcpfw_comman[ofcpfw_delete_icon]" value="trash_6"  <?php if ($fcpfw_comman['ofcpfw_delete_icon'] == "trash_6" ) { echo 'checked'; } ?>>
                                            <label>
                                                <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['trash_6'])); ?>
                                            </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Delete icon Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_delect_icon_clr'])) {
                                                $fcpfw_delect_icon_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_delect_icon_clr); ?>" name="fcpfw_comman[fcpfw_delect_icon_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_delect_icon_clr']); ?>"/>
                                    </td>
                                </tr>
                                 <tr>
                                    <td>
                                        <h3><?php echo __('Coupon Field Settings','floating-cart-product-for-woocommerce');?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Coupon icon','floating-cart-product-for-woocommerce');?></th>
                                    <td class="ocwqv_icon_choice">
                                           
                                            <input type="radio" name="fcpfw_comman[fcpfw_coupon_icon]" value="coupon" <?php if ($fcpfw_comman['fcpfw_coupon_icon'] == "coupon" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon'])); ?>
                                            </label>
                    
                                            <input type="radio" name="fcpfw_comman[fcpfw_coupon_icon]" value="coupon_1" <?php if ($fcpfw_comman['fcpfw_coupon_icon'] == "coupon_1" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_1'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[fcpfw_coupon_icon]" value="coupon_2"  <?php if ($fcpfw_comman['fcpfw_coupon_icon'] == "coupon_2" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_2'])); ?>
                                            </label>
                                        
                                            <input type="radio" name="fcpfw_comman[fcpfw_coupon_icon]" value="coupon_3" <?php if ($fcpfw_comman['fcpfw_coupon_icon'] == "coupon_3" ) { echo 'checked'; } ?>>
                                            <label>
                                                 <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_3'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[fcpfw_coupon_icon]" value="coupon_4"  <?php if ($fcpfw_comman['fcpfw_coupon_icon'] == "coupon_4" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_4'])); ?>
                                            </label> 

                                            <input type="radio" name="fcpfw_comman[fcpfw_coupon_icon]" value="coupon_5"  <?php if ($fcpfw_comman['fcpfw_coupon_icon'] == "coupon_5" ) { echo 'checked'; } ?>>
                                            <label>
                                                <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_5'])); ?>
                                            </label>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <th><?php echo __('Apply Coupon icon Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_apply_cpn_icon_clr'])) {
                                                $fcpfw_apply_cpn_icon_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_apply_cpn_icon_clr); ?>" name="fcpfw_comman[fcpfw_apply_cpn_icon_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_icon_clr']); ?>"/>
                                    </td>
                                </tr> 
                               
                                <tr>
                                    <th><?php echo __('Apply Coupon Font Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_apply_cpn_ft_clr'])) {
                                                $fcpfw_apply_cpn_ft_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_apply_cpn_ft_clr); ?>" name="fcpfw_comman[fcpfw_apply_cpn_ft_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_ft_clr']); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Apply Button Text Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_applybtn_cpn_ft_clr'])) {
                                                $fcpfw_applybtn_cpn_ft_clr = '#ffffff';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_applybtn_cpn_ft_clr); ?>" name="fcpfw_comman[fcpfw_applybtn_cpn_ft_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_applybtn_cpn_ft_clr']); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Apply Button Background Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_applybtn_cpn_bg_clr'])) {
                                                $fcpfw_applybtn_cpn_bg_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_applybtn_cpn_bg_clr); ?>" name="fcpfw_comman[fcpfw_applybtn_cpn_bg_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_applybtn_cpn_bg_clr']); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3><?php echo __('Slider Product Settings','floating-cart-product-for-woocommerce');?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Product Font Size','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="number" name="fcpfw_comman[fcpfw_sld_product_ft_size]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_sld_product_ft_size']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Product Font Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_sld_product_ft_clr'])) {
                                                $fcpfw_sld_product_ft_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_sld_product_ft_clr); ?>" name="fcpfw_comman[fcpfw_sld_product_ft_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_sld_product_ft_clr']); ?>"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="postbox">
                        <div class="postbox-header"> 
                            <h2><?php echo __('Shipping Text Customize','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                            <table class="data_table">
                                 <tr>
                                    <th><?php echo __('Shipping Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_ship_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_ship_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Shipping Text Font Size','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="number" name="fcpfw_comman[fcpfw_ship_ft_size]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_ship_ft_size']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Shipping Text Font Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_ship_ft_clr'])) {
                                                $fcpfw_ship_ft_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_ship_ft_clr); ?>" name="fcpfw_comman[fcpfw_ship_ft_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_ship_ft_clr']); ?>"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="postbox">
                         
                          
                         <div class="postbox-header">
                             <h2><?php echo __('Footer Button Settings','floating-cart-product-for-woocommerce');?></h2>
                         </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <th><?php echo __('Button Row','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_footer_button_row]">
                                            <option value="one" <?php if($fcpfw_comman['fcpfw_footer_button_row'] == "one"){ echo "selected"; } ?>><?php echo __('One in a row ( 1+1+1 )','floating-cart-product-for-woocommerce');?></option>
                                            <option value="two_one" <?php if($fcpfw_comman['fcpfw_footer_button_row'] == "two_one"){ echo "selected"; } ?>><?php echo __('Two in first row ( 2 + 1 )','floating-cart-product-for-woocommerce');?></option>
                                            <option value="one_two" <?php if($fcpfw_comman['fcpfw_footer_button_row'] == "one_two"){ echo "selected"; } ?>><?php echo __('Two in last row ( 1 + 2 )','floating-cart-product-for-woocommerce');?></option>
                                            <option value="three" <?php if($fcpfw_comman['fcpfw_footer_button_row'] == "three"){ echo "selected"; } ?>><?php echo __('Three in one row( 3 )','floating-cart-product-for-woocommerce');?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Footer Buttons Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_ft_btn_clr'])) {
                                                $fcpfw_ft_btn_clr = '#766f6f';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_ft_btn_clr); ?>" name="fcpfw_comman[fcpfw_ft_btn_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_clr']); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Footer Buttons Text Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_ft_btn_txt_clr'])) {
                                                $fcpfw_ft_btn_txt_clr = '#ffffff';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_ft_btn_txt_clr); ?>" name="fcpfw_comman[fcpfw_ft_btn_txt_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_txt_clr']); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Footer Buttons Margin','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="number" name="fcpfw_comman[fcpfw_ft_btn_mrgin]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_mrgin']); ?>">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>              
                    <div class="postbox">
                        <div class="postbox-header">
                                <h2><?php echo __('Cart basket','floating-cart-product-for-woocommerce');?></h2>
                        </div>
                        <div class="inside">
                             <table class="data_table">
                                <tr>
                                        <th><?php echo __('Side cart Basket Icon','floating-cart-product-for-woocommerce');?></th>

                                        <td class="ocwqv_icon_choice">
                                            <input type="radio" name="fcpfw_comman[ocwqv_fcpfw_icon]" value="ocwqv_qfcpfw_icon" <?php if ($fcpfw_comman['ocwqv_fcpfw_icon'] == "ocwqv_qfcpfw_icon" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_qfcpfw_icon'])); ?>
                                            </label>
                                            <input type="radio" name="fcpfw_comman[ocwqv_fcpfw_icon]" value="ocwqv_fcpfw_icon_1" <?php if ($fcpfw_comman['ocwqv_fcpfw_icon'] == "ocwqv_fcpfw_icon_1" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_1'])); ?>
                                            </label>
                    
                                            <input type="radio" name="fcpfw_comman[ocwqv_fcpfw_icon]" value="ocwqv_fcpfw_icon_4" <?php if ($fcpfw_comman['ocwqv_fcpfw_icon'] == "ocwqv_fcpfw_icon_4" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_4'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ocwqv_fcpfw_icon]" value="ocwqv_fcpfw_icon_2"  <?php if ($fcpfw_comman['ocwqv_fcpfw_icon'] == "ocwqv_fcpfw_icon_2" ) { echo 'checked'; } ?>>
                                            <label>
                                                  <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_2'])); ?>
                                            </label>
                                        
                                            <input type="radio" name="fcpfw_comman[ocwqv_fcpfw_icon]" value="ocwqv_fcpfw_icon_5" <?php if ($fcpfw_comman['ocwqv_fcpfw_icon'] == "ocwqv_fcpfw_icon_5" ) { echo 'checked'; } ?>>
                                            <label>
                                                 <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_5'])); ?>
                                            </label>

                                            <input type="radio" name="fcpfw_comman[ocwqv_fcpfw_icon]" value="ocwqv_fcpfw_icon_3"  <?php if ($fcpfw_comman['ocwqv_fcpfw_icon'] == "ocwqv_fcpfw_icon_3" ) { echo 'checked'; } ?>>
                                            <label>
                                               <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_3'])); ?>
                                            </label> 

                                            <input type="radio" name="fcpfw_comman[ocwqv_fcpfw_icon]" value="ocwqv_fcpfw_icon_6"  <?php if ($fcpfw_comman['ocwqv_fcpfw_icon'] == "ocwqv_fcpfw_icon_6" ) { echo 'checked'; } ?>>
                                            <label>
                                                <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_4'])); ?>
                                            </label>
                                        </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Side cart Basket Shape','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_basket_shape]">
                                            <option value="square" <?php  if($fcpfw_comman['fcpfw_basket_shape'] == "square" || empty($fcpfw_comman['fcpfw_basket_shape'])){ echo "selected"; } ?>><?php echo __('Square','floating-cart-product-for-woocommerce');?></option>
                                            <option value="round" <?php if($fcpfw_comman['fcpfw_basket_shape'] == "round"){ echo "selected"; } ?>><?php echo __('Round','floating-cart-product-for-woocommerce');?></option>
                                            
                                        </select>
                                    </td>
                                </tr> 
                                <tr>
                                    <th><?php echo __('Basket Position','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_basket_position]">
                                            <option value="top" <?php if($fcpfw_comman['fcpfw_basket_position'] == "top"){ echo "selected"; } ?>>Top</option>
                                            <option value="bottom" <?php  if($fcpfw_comman['fcpfw_basket_position'] == "bottom" || empty($fcpfw_comman['fcpfw_basket_position'])){ echo "selected"; } ?>>Bottom</option>
                                        </select>
                                    </td>
                                </tr> 
                                <tr>
                                    <th><?php echo __('Basket Count  Position','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <select name="fcpfw_comman[fcpfw_basket_count_position]">
                                            <option value="top-left" <?php if($fcpfw_comman['fcpfw_basket_count_position'] == "top"){ echo "selected"; } ?>>Top Left</option>
                                            <option value="bottom-right" <?php  if($fcpfw_comman['fcpfw_basket_count_position'] == "bottom-right" || empty($fcpfw_comman['fcpfw_basket_count_position'])){ echo "selected"; } ?>>Bottom Right</option>
                                            <option value="bottom-left" <?php if($fcpfw_comman['fcpfw_basket_count_position'] == "bottom-left"){ echo "selected"; } ?>>Bottom Left</option>
                                            <option value="top-right" <?php  if($fcpfw_comman['fcpfw_basket_count_position'] == "top-right" || empty($fcpfw_comman['fcpfw_basket_count_position'])){ echo "selected"; } ?>>Top-right</option>
                                        </select>
                                    </td>
                                </tr>     
                                
                               
                                <tr>
                                    <th><?php echo __('Basket Icon Size','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="number" name="fcpfw_comman[fcpfw_basket_icn_size]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_basket_icn_size']); ?>">
                                    </td>
                                </tr> 
                                <tr>
                                    <th><?php echo __('Basket Offset ↨','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                       <input type="number" name="fcpfw_comman[fcpfw_basket_off_vertical]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_basket_off_vertical']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Basket Offset ⟷','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                       <input type="number" name="fcpfw_comman[fcpfw_basket_off_horizontal]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_basket_off_horizontal']); ?>">
                                    </td>
                                </tr>

                                <tr>
                                    <th><?php echo __('Basket Background Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_basket_bg_clr'])) {
                                                $fcpfw_basket_bg_clr = '#cccccc';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_basket_bg_clr); ?>" name="fcpfw_comman[fcpfw_basket_bg_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_basket_bg_clr']); ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <th><?php echo __('Basket Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_basket_clr'])) {
                                                $fcpfw_basket_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_basket_clr); ?>" name="fcpfw_comman[fcpfw_basket_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_basket_clr']); ?>"/>
                                    </td>

                                </tr>
                                <tr>
                                    <th><?php echo __('Count Background Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_cnt_bg_clr'])) {
                                                $fcpfw_cnt_bg_clr = '#000000';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_cnt_bg_clr); ?>" name="fcpfw_comman[fcpfw_cnt_bg_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_cnt_bg_clr']); ?>"/>
                                    </td>
                                </tr> 
                                <tr>
                                    <th><?php echo __('Count Text Color','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <?php 
                                            if( !empty($fcpfw_comman['fcpfw_cnt_txt_clr'])) {
                                                $fcpfw_cnt_txt_clr = '#ffffff';
                                            }
                                        ?>
                                        <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr($fcpfw_cnt_txt_clr); ?>" name="fcpfw_comman[fcpfw_cnt_txt_clr]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_cnt_txt_clr']); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Count Text Size','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="number" name="fcpfw_comman[fcpfw_cnt_txt_size]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_cnt_txt_size']); ?>">
                                    </td>
                                </tr> 
                                
                            </table>
                        </div>
                    </div>
                </div>
                <div id="fcpfw-tab-translations" class="tab-content">
                    <div class="postbox">
                            <div class="postbox-header">
                                <h2><?php echo __('Translations','floating-cart-product-for-woocommerce');?></h2>                               
                            </div>
                        <div class="inside">
                            <table class="data_table">
                                <tr>
                                    <td>
                                        <h3><?php echo __('Title Settings','floating-cart-product-for-woocommerce');?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Head Title','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_head_title]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_head_title']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('QTY Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_qty_text]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_qty_text']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3><?php echo __('Coupon Settings','floating-cart-product-for-woocommerce');?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Cart is empty Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_cart_is_empty]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_cart_is_empty']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Apply Coupon Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_apply_cpn_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Apply Coupon Placeholder Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_apply_cpn_plchldr_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_plchldr_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Apply Coupon Apply Button Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_apply_cpn_apbtn_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_apbtn_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Coupon Field Empty Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_cpnfield_empty_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_cpnfield_empty_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Coupon Already Applied Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_cpn_alapplied_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_cpn_alapplied_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Invalid Coupon Code Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_invalid_coupon_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_invalid_coupon_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Coupon Applied Successfully Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_coupon_applied_suc_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_coupon_applied_suc_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Coupon Removed Successfully Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_coupon_removed_suc_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_coupon_removed_suc_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3><?php echo __('Product Slider Settings','floating-cart-product-for-woocommerce');?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Add to Cart Button Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_slider_atcbtn_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_slider_atcbtn_txt']); ?>" disabled>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('View Options Button Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_slider_vwoptbtn_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_slider_vwoptbtn_txt']); ?>" disabled>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3><?php echo __('Cart Footer Settings','floating-cart-product-for-woocommerce');?></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Subtotal Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_subtotal_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_subtotal_txt']); ?>">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th><?php echo __('View Cart Button Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_cart_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_cart_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Checkout Button Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_checkout_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_checkout_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Continue Shopping Button Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_conshipping_txt]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_conshipping_txt']); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Shipping','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_shipping_text_trans]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_shipping_text_trans']); ?>" disabled>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Tax','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_apply_taxt_testx]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_taxt_testx']); ?>" disabled>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                 <tr>
                                    <th><?php echo __('Discount Text','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_discount_text_trans]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_discount_text_trans']); ?>"disabled>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo __('Total','floating-cart-product-for-woocommerce');?></th>
                                    <td>
                                        <input type="text" name="fcpfw_comman[fcpfw_apply_total_text]" value="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_total_text']); ?>" disabled>
                                        <label class="fcpfw_comman_link"><?php echo __('This Option Available in ','floating-cart-product-for-woocommerce');?> <a href="https://www.plugin999.com/plugin/floating-cart-product-for-woocommerce/" target="_blank">Pro Version</a></label>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="action" value="fcpfw_save_option">
                <input type="submit" value="Save changes" name="submit" class="button-primary" id="fcpfw-btn-space">
            </form>  
        </div>
    <?php
}

function fcpfw_recursive_sanitize_text_field($array) {
    if(!empty($array)) {
        foreach ( $array as $key => $value ) {
            if ( is_array( $value ) ) {
                $value = fcpfw_recursive_sanitize_text_field($value);
            }else{
                $value = sanitize_text_field( $value );
            }
        }
    }
    return $array;
}

add_action( 'init', 'fcpfw_save_options');
function fcpfw_save_options() {
    if( current_user_can('administrator') ) {
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'fcpfw_save_option') {
            if(!isset( $_POST['fcpfw_nonce_field'] ) || !wp_verify_nonce( $_POST['fcpfw_nonce_field'], 'fcpfw_nonce_action' ) ){
                print 'Sorry, your nonce did not verify.';
                exit;
            } else {

                if(!empty($_REQUEST['fcpfw_comman'])){
                    $isecheckbox = array(
                        'fcpfw_header_cart_icon',
                        'fcpfw_header_close_icon',
                        'fcpfw_freeshiping_herder',
                        'fcpfw_loop_img',
                        'fcpfw_loop_product_name',
                        'fcpfw_loop_product_price',
                        'fcpfw_loop_total',
                        'fcpfw_loop_variation',
                        'fcpfw_loop_link',
                        'fcpfw_loop_delete',
                        'fcpfw_auto_open',
                        'fcpfw_trigger_class',
                        'fcpfw_ajax_cart',
                        'fcpfw_qty_box',
                        'fcpfw_cart_option',
                        'fcpfw_checkout_option',
                        'fcpfw_conshipping_option',
                        'fcpfw_coupon_field_mobile',
                        'fcpfw_show_cart_icn',
                        'fcpfw_mobile',
                        'fcpfw_product_cnt',
                        'fcpfw_all_pages',
                        'fcpfw_display_home_page',
                        'fcpfw_display_shop_page',
                        'fcpfw_display_product_page',
                        'fcpfw_display_cart_page',
                        'fcpfw_display_checkout_page',
                        'product_cat_page',
                        'product_tag_page',
                    );

                    foreach ($isecheckbox as $key_isecheckbox => $value_isecheckbox) {
                        if(!isset($_REQUEST['fcpfw_comman'][$value_isecheckbox])){
                            $_REQUEST['fcpfw_comman'][$value_isecheckbox] ='no';
                        }
                    }
                    
                    foreach ($_REQUEST['fcpfw_comman'] as $key_fcpfw_comman => $value_fcpfw_comman) {
                        update_option($key_fcpfw_comman, sanitize_text_field($value_fcpfw_comman), 'yes');
                    }
                }

                // if(isset($_REQUEST['fcpfw_select2'])) {
                //     $fcpfw_select2 = fcpfw_recursive_sanitize_text_field($_REQUEST['fcpfw_select2'] );
                //     update_option('fcpfw_select2', $fcpfw_select2, 'yes');
                // }


                wp_redirect( admin_url( '/admin.php?page=floating-cart&message=success' ) );
                exit;
            }
        }
    }
}

add_action( 'wp_ajax_FCPFW_product_ajax','FCPFW_product_ajax');
add_action( 'wp_ajax_nopriv_FCPFW_product_ajax','FCPFW_product_ajax');
function FCPFW_product_ajax() {
    $return = array();
    $post_types = array( 'product','product_variation');
    $search_results = new WP_Query( array( 
        's'=> sanitize_text_field($_GET['q']),
        'post_status' => 'publish',
        'post_type' => $post_types,
        'posts_per_page' => -1,
        'meta_query' => array(
                            array(
                                'key' => '_stock_status',
                                'value' => 'instock',
                                'compare' => '=',
                            )
                        )
        ) );
    if( $search_results->have_posts() ) :
       while( $search_results->have_posts() ) : $search_results->the_post();   
          $productc = wc_get_product( $search_results->post->ID );
          if ( $productc && $productc->is_in_stock() && $productc->is_purchasable() ) {
             $title = $search_results->post->post_title;
             $price = $productc->get_price_html();
             $return[] = array( $search_results->post->ID, $title, $price);   
          }
       endwhile;
    endif;
    echo json_encode( $return );
    die;
}