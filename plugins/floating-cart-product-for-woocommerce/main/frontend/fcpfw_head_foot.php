<?php

function SCFW_cart_create() {

    WC()->cart->calculate_totals();

    WC()->cart->maybe_set_cart_cookies();

    global $woocommerce,$fcpfw_comman, $ocwqv_qfcpfw_icon;

    $fcpfw_sidecart_width = $fcpfw_comman['fcpfw_sidecart_width'].'px';

    ?>
    <div class="fcpfw_container" >
        <div class="fcpfw_header">
            <div class="top_fcpfw_herder">
                <?php
                if($fcpfw_comman['fcpfw_header_cart_icon']=='yes'){
                ?>
                <span class="fcpfw_cart_icon">

                    <?php
                    if($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_2"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_2']));
                    }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_3"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_3']));
                    }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_4"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_4']));
                    }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_5"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_5']));
                    }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_6"){
                        echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_6']));
                    }else{
                         echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_1']));
                    }
                    ?>
                </span>
                <?php
                }
                ?>
                <h3 class="fcpfw_header_title" ><?php echo esc_html($fcpfw_comman['fcpfw_head_title']); ?></h3>
                <?php
                if($fcpfw_comman['fcpfw_header_close_icon']=='yes'){
                ?>
                <span class="fcpfw_close_cart">
                   <?php
                    if($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_1"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_1']));
                    }elseif($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_2"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_2']));
                    }elseif($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_3"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_3']));
                    }elseif($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_4"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_4']));
                    }elseif($fcpfw_comman['ofcpfw_close_icon'] == "close_icon_5"){
                        echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon_5']));
                    }else{
                         echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['close_icon']));
                    }
                    ?>
                </span>
                <?php
                }
                ?>
            </div>
            <?php if ($fcpfw_comman['fcpfw_freeshiping_herder'] == "yes" ){ ?>
                <div class="top_fcpfw_bottom">
                    <?php 

                        $wg_prodrule_mtvtion_msg = $fcpfw_comman['fcpfw_freeshiping_herder_txt'];

                        $fcpfw_shipping_total = $woocommerce->cart->get_cart_shipping_total();
                       

                        $wg_prodrule_mtvtion_msg_final = str_replace("{shipping_total}", $fcpfw_shipping_total, $wg_prodrule_mtvtion_msg); ?>
                    <p style="color:<?php echo  esc_attr($fcpfw_comman['fcpfw_header_shipping_text_color']); ?>"><?php echo esc_attr($wg_prodrule_mtvtion_msg_final); ?></p>
                </div>
            <?php } ?>
        </div>
        <?php
        echo SCFW_comman();
        ?>
       <?php
        $showCouponField = 'true';
        if(wp_is_mobile()) {
            if($fcpfw_comman['fcpfw_coupon_field_mobile'] == 'no') {
                $showCouponField = 'false';
            }
        }

        if($showCouponField == 'true') {
        ?>
        <div class="fcpfw_trcpn">
            <div class='fcpfw_total_tr'>
                <div class='fcpfw_total_label'>
                    <span><?php echo esc_attr($fcpfw_comman['fcpfw_subtotal_txt']); ?></span>
                </div>

                <?php
                    $item_taxs = $woocommerce->cart->get_cart();
                    $fcpfw_get_totals = WC()->cart->get_totals();
                    $fcpfw_shipping_total = $woocommerce->cart->get_cart_shipping_total();
                    $fcpfw_cart_total = $fcpfw_get_totals['subtotal'];
                    $fcpfw_cart_discount = $fcpfw_get_totals['discount_total'];
                    $fcpfw_final_subtotal = $fcpfw_cart_total - $fcpfw_cart_discount;
                ?>
                <div class='fcpfw_total_amount'>
                    <span class='fcpfw_fragtotal'><?php echo get_woocommerce_currency_symbol().number_format($fcpfw_final_subtotal, 2); ?></span>
                </div>
            </div>

            <div class='fcpfw_coupon'>
                <div class='fcpfw_apply_coupon_link' style="color: <?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_ft_clr']); ?>">
                    <a href='#' id='fcpfw_apply_coupon'><?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_txt']); ?></a>
                </div>
                <div class="fcpfw_coupon_field">
                    <input type="text" id="fcpfw_coupon_code" placeholder="<?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_plchldr_txt']); ?>">
                    <span class="fcpfw_coupon_submit" style="background-color: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_applybtn_cpn_bg_clr']); ?>; color: <?php echo esc_attr($fcpfw_comman['fcpfw_applybtn_cpn_ft_clr']); ?>;"><?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_apbtn_txt']); ?></span>
                </div>

                <?php
                $applied_coupons = WC()->cart->get_applied_coupons();
                if(!empty($applied_coupons)) {
                ?>
                    <ul class='fcpfw_applied_cpns'>
                        <?php
                            foreach($applied_coupons as $cpns ) {
                            ?>    
                            <li class='fcpfw_remove_cpn' cpcode='<?php echo esc_attr($cpns); ?>'><?php echo esc_attr($cpns); ?><span class='dashicons dashicons-no'></span></li>
                            
                            <?php
                            }
                        ?>    
                    </ul>
                <?php
                }
                ?>
            </div>
        </div>
        <?php
        }
        ?>



        <div class="fcpfw_footer">
            <div class="fcpfw_ship_txt" style="color: <?php echo esc_attr($fcpfw_comman['fcpfw_ship_ft_clr']) ?>;font-size: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_ship_ft_size'])."px" ?>;"><?php echo esc_attr($fcpfw_comman['fcpfw_ship_txt']); ?></div>
            <?php

            ?>
            <div class="fcpfw_button_fort fcpfw_dyamic_<?php echo esc_attr($fcpfw_comman['fcpfw_footer_button_row']); ?>">
            <?php  if($fcpfw_comman['fcpfw_cart_option']== "yes") { ?>
                <a  class="fcpfw_bn_1" href="<?php if(!empty($fcpfw_comman['fcpfw_orgcart_link'])){echo $fcpfw_comman['fcpfw_orgcart_link']; }else{  echo wc_get_cart_url(); }?>" style="background-color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_clr']) ?>;margin: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_mrgin'])."px" ?>;color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_txt_clr']) ?>;">
                    <?php echo esc_attr($fcpfw_comman['fcpfw_cart_txt']); ?>
                </a>
            <?php } ?>
            <?php  if($fcpfw_comman['fcpfw_checkout_option'] == "yes"){ ?>
                <a class="fcpfw_bn_2" href="<?php if(!empty($fcpfw_comman['fcpfw_orgcheckout_link'])){echo $fcpfw_comman['fcpfw_orgcheckout_link'];}else{echo wc_get_checkout_url();} ?>" style="background-color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_clr']); ?>;margin: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_mrgin'])."px" ?>;color: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_ft_btn_txt_clr']) ?>;">
                    <?php echo esc_attr($fcpfw_comman['fcpfw_checkout_txt']); ?>
                </a>
            <?php } ?>
            <?php  if($fcpfw_comman['fcpfw_conshipping_option'] == "yes"){ ?>
                <a class="fcpfw_bn_3" href="<?php echo esc_attr($fcpfw_comman['fcpfw_conshipping_link']); ?>" style="background-color: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_ft_btn_clr']); ?>;margin: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_mrgin'])."px" ?>;color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_txt_clr']) ?>;">
                    <?php echo esc_attr($fcpfw_comman['fcpfw_conshipping_txt']); ?>
                </a>
            <?php } ?>
            </div>
        </div>
    </div>
    <div class="fcpfw_container_overlay">
    </div>

    <?php if($fcpfw_comman['fcpfw_show_cart_icn'] == "yes"){ ?>

        <div class="fcpfw_cart_basket">
            <div class="cart_box">
               
                <?php if($fcpfw_comman['ocwqv_fcpfw_icon'] == 'ocwqv_fcpfw_icon_1'){ ?>

                   <?php echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_1'])); ?>

                <?php }else if($fcpfw_comman['ocwqv_fcpfw_icon'] == 'ocwqv_fcpfw_icon_2'){ ?>

                    <?php echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_2'])); ?>

                <?php }else if($fcpfw_comman['ocwqv_fcpfw_icon'] == 'ocwqv_fcpfw_icon_3'){ ?>

                    <?php echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_3'])); ?>

                <?php }else if($fcpfw_comman['ocwqv_fcpfw_icon'] == 'ocwqv_fcpfw_icon_4'){ ?>

                    <?php echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_4'])); ?>

                <?php }else if($fcpfw_comman['ocwqv_fcpfw_icon'] == 'ocwqv_fcpfw_icon_5'){ ?>

                    <?php echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_fcpfw_icon_5'])); ?>

                <?php }else if($fcpfw_comman['ocwqv_fcpfw_icon'] == 'ocwqv_fcpfw_icon_6'){ ?>

                    <?php echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_4'])); ?>

                <?php }else{ ?>

                      <?php echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_qfcpfw_icon'])); ?>

                <?php } ?>
                
            </div>
            <?php if($fcpfw_comman['fcpfw_product_cnt'] == "yes"){ ?>
                <div class="fcpfw_item_count" >
                    <?php
                    echo SCFW_counter_value();
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php
    }
}

add_action('wp_head','FCPFW_craete_cart');
function SCFW_craete_cart(){
    global $fcpfw_comman,$ocwqv_qfcpfw_icon;
    ?>
    <style>

        <?php
        if($fcpfw_comman['fcpfw_cart_open_from']=='left'){?>

            .fcpfw_container{
            width: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_sidecart_width']).'px'; ?>;

            left: -<?php echo esc_attr($fcpfw_comman[ 'fcpfw_sidecart_width']).'px'; ?>;
         }

        <?php }else{ ?>

        .fcpfw_container{
            width: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_sidecart_width']).'px'; ?>;

            right: -<?php echo esc_attr($fcpfw_comman[ 'fcpfw_sidecart_width']).'px'; ?>;
        }

        <?php }
        if($fcpfw_comman['fcpfw_cart_height']=='auto'){
        ?>
         .fcpfw_container{
            top:auto;
            max-height: 100%;
         }
        <?php
        }
        ?>
        .fcpfw_item_count{
                background-color: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_cnt_bg_clr']) ?>;
                color: <?php echo esc_attr($fcpfw_comman['fcpfw_cnt_txt_clr']) ?>;
                font-size: <?php echo esc_attr($fcpfw_comman['fcpfw_cnt_txt_size'])."px" ?>;
            <?php if($fcpfw_comman['fcpfw_basket_count_position'] == "top-right"){?>
                top: -10px;
                right: -12px;
            <?php }elseif($fcpfw_comman['fcpfw_basket_count_position'] == "bottom-left"){ ?>
                bottom: -10px;
                left: -12px;
            <?php }elseif($fcpfw_comman['fcpfw_basket_count_position'] == "bottom-right"){ ?>
                bottom: -10px;
                right: -12px;
            <?php }else{ ?>
                top: -10px;
                left: -12px;
            <?php } ?>
        }
        .fcpfw_cart_basket{
            <?php 
            if($fcpfw_comman['fcpfw_basket_position'] == "top"){ ?>
            top: 15px;
            <?php }elseif($fcpfw_comman['fcpfw_basket_position']== "bottom") { ?>
            bottom: 15px;
            <?php } 
            if($fcpfw_comman['fcpfw_cart_open_from'] == "left"){ ?>
                left: 15px;
            <?php }else { ?>
            right: 15px;
            <?php }
            if($fcpfw_comman['fcpfw_basket_shape'] == "round"){ ?>
                   border-radius: 100%;
            <?php }else { ?>
                border-radius: 10px;
            <?php }
            if($fcpfw_comman['fcpfw_cart_show_hide'] == "fcpfw_cart_hide"){ ?>
                   diplay:none;
            <?php }else { ?>
                display:block;
               
            <?php } ?> ?>
            height: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_basket_icn_size'])."px" ?>;
            width: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_basket_icn_size'])."px" ?>;
            background-color: <?php echo esc_attr($fcpfw_comman['fcpfw_basket_bg_clr']) ?>;
            margin-bottom: <?php echo esc_attr($fcpfw_comman['fcpfw_basket_off_vertical']);?>px;
            margin-right: <?php echo esc_attr($fcpfw_comman['fcpfw_basket_off_horizontal']);?>px;
        }
        .fcpfw_container .fcpfw_header_title{
            color: <?php echo esc_attr($fcpfw_comman['fcpfw_head_ft_clr']); ?>;
            font-size: <?php echo esc_attr($fcpfw_comman['fcpfw_head_ft_size'])."px"; ?>;
        }
        
        .fcpfw_prodline_title_inner ,.fcpfw_prodline_title_inner a, .fcpfw_qupdiv{
            color: <?php echo esc_attr($fcpfw_comman['fcpfw_product_ft_clr']);?>;
            font-size: <?php echo esc_attr($fcpfw_comman['fcpfw_product_ft_size']);?>px;
        }
        .cart_box svg {
            fill : <?php echo esc_attr($fcpfw_comman[ 'fcpfw_basket_clr']); ?> ;
        }
        .fcpfw_remove svg {
        fill : <?php echo esc_attr($fcpfw_comman[ 'fcpfw_delect_icon_clr']); ?> ;

        }
        .fcpfw_cart_icon svg{
        fill : <?php echo esc_attr($fcpfw_comman[ 'fcpfw_header_cart_icon_clr']); ?> ;
        }
        .fcpfw_close_cart svg{
        fill : <?php echo esc_attr($fcpfw_comman[ 'fcpfw_header_close_icon_clr']); ?> ;
        }
        
    </style>
    <?php
        if(wp_is_mobile() ){
            if($fcpfw_comman[ 'fcpfw_mobile'] == "yes") {
                if(is_checkout() || is_cart()){
                    
                } else {
                    add_filter( 'wp_footer','SCFW_cart_create');
                }
            }
        } else {
            if(is_checkout() || is_cart()){
               
            } else {
                add_filter( 'wp_footer','SCFW_cart_create');
            }
        }
}

add_action( 'wp_footer','FCPFW_single_added_to_cart_event');
function SCFW_single_added_to_cart_event(){
    global $fcpfw_comman;
    if( isset($_POST['add-to-cart']) && isset($_POST['quantity']) ) {?>
        <script>

            jQuery(function($){

                jQuery('.fcpfw_cart_basket').click();

            });

        </script>
        <?php
    }

    ?>
    <?php $fcpfw_sidecart_width = $fcpfw_comman[ 'fcpfw_sidecart_width'].'px'; ?>
    <div class="fcpfw_coupon_response" style="left: calc(100% - <?php echo esc_attr($fcpfw_sidecart_width) ; ?>);">
        <div class="fcpfw_inner_div" style="width:<?php echo esc_attr($fcpfw_sidecart_width ); ?>;">
            <span id="fcpfw_cpn_resp" style="width:<?php echo esc_attr($fcpfw_sidecart_width) ; ?>;"></span>
        </div>
    </div>
    <?php
}
