<?php
        add_action('wp_head','FCPFW_craete_cart');
        add_filter( 'woocommerce_add_to_cart_fragments','FCPFW_cart_count_fragments', 10, 1 );
        add_action( 'wp_ajax_change_qty','FCPFW_change_qty_cust');
        add_action( 'wp_ajax_nopriv_change_qty','FCPFW_change_qty_cust' );
        add_action( 'wp_ajax_product_remove', 'FCPFW_ajax_product_remove' );
        add_action( 'wp_ajax_nopriv_product_remove', 'FCPFW_ajax_product_remove' );
        add_action( 'wp_ajax_coupon_ajax_call','FCPFW_coupon_ajax_call_func' );
        add_action( 'wp_ajax_nopriv_coupon_ajax_call', 'FCPFW_coupon_ajax_call_func' );
        add_action( 'wp_ajax_remove_applied_coupon_ajax_call','fcpfw_remove_applied_coupon_ajax_call_func' );
        add_action( 'wp_ajax_nopriv_remove_applied_coupon_ajax_call','fcpfw_remove_applied_coupon_ajax_call_func');
        add_action( 'wp_footer','FCPFW_single_added_to_cart_event');
        add_action( 'wp_ajax_fcpfw_prod_slider_ajax_atc','fcpfw_prod_slider_ajax_atc' );
        add_action( 'wp_ajax_nopriv_fcpfw_prod_slider_ajax_atc','fcpfw_prod_slider_ajax_atc');
        add_action( 'wp_ajax_fcpfw_get_refresh_fragments','fcpfw_get_refreshed_fragments' );
        add_action( 'wp_ajax_nopriv_fcpfw_get_refresh_fragments','fcpfw_get_refreshed_fragments');

        function fcpfw_get_refreshed_fragments(){
            WC_AJAX::get_refreshed_fragments();
        }
        

        function FCPFW_cart_create() {

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
                                echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_2']));
                            }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_3"){
                                echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_3']));
                            }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_4"){
                                echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_4']));
                            }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_5"){
                                echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_5']));
                            }elseif($fcpfw_comman['ofcpfw_shop_icon'] == "shop_icon_6"){
                                echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_6']));
                            }else{
                                 echo  html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['shop_icon_1']));
                            }
                            ?>
                        </span>
                        <?php
                        }
                        ?>
                        <h3 class="fcpfw_header_title" ><?php echo esc_attr($fcpfw_comman['fcpfw_head_title']); ?></h3>
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

                                    $zones = WC_Shipping_Zones::get_zones();

                                    $result = null;
                                    $zone = null;
                                    $free_shipping_method = null;
                                    foreach ( $zones as $zones ) {
                                            $shipping_methods_nl = $zones['shipping_methods'];
                                            $free_shipping_method = null;
                                        foreach ( $shipping_methods_nl as $method ) {
                                          if ( $method->id == 'free_shipping' ) {
                                            $free_shipping_method = $method;
                                            break;
                                          }
                                        }
                                    }

                                if ( $free_shipping_method ) {
                                  $result = $free_shipping_method->min_amount;
                                }


                                // $amount_for_free_shipping = $free_shipping_settings['min_amount'];
                                // $WC_Cart =  WC()->cart->get_cart();;
                               
                                $shiiping_total  = $result;
                                $fcpfw_subtotla       =  WC()->cart->get_subtotal();

                                $carta = $shiiping_total - $fcpfw_subtotla;


                                if($shiiping_total > $fcpfw_subtotla){
                                    $fcpfw_shipping_total =  get_woocommerce_currency_symbol().number_format(($carta), 2 );
                                    $wg_prodrule_mtvtion_msg_final = str_replace("{shipping_total}", $fcpfw_shipping_total, $wg_prodrule_mtvtion_msg);
                                }else{
                                    $wg_prodrule_mtvtion_msg_final =  $fcpfw_comman['fcpfw_freeshiping_then_herder_txt'];
                                }?>

                            <p style="color:<?php echo  esc_attr($fcpfw_comman['fcpfw_header_shipping_text_color']); ?>"><?php echo esc_attr($wg_prodrule_mtvtion_msg_final); ?></p>
                        </div>
                    <?php } ?>
                </div>
                <?php
                echo FCPFW_comman();
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
                            <div class='fcpfw_total_tr_inner'>
                                <div class='fcpfw_total_label'>
                                    <span><?php echo esc_attr($fcpfw_comman['fcpfw_subtotal_txt']); ?></span>
                                </div>
                                <?php
                                $item_taxs = $woocommerce->cart->get_cart();
                                $fcpfw_get_totals = WC()->cart->get_totals();
                                $fcpfw_shipping_total = $woocommerce->cart->get_cart_shipping_total();
                                $fcpfw_cart_total = $fcpfw_get_totals['subtotal'];
                                //$fcpfw_cart_discount = $fcpfw_get_totals['discount_total'];
                                $fcpfw_cart_discount = $fcpfw_get_totals['discount_total']+$fcpfw_get_totals['discount_tax'];
                                $fcpfw_final_subtotal = $fcpfw_cart_total ;
                                ?>
                                    <div class='fcpfw_total_amount'>
                                        <span class='fcpfw_fragtotal'><?php echo get_woocommerce_currency_symbol().number_format($fcpfw_final_subtotal, 2); ?></span>
                                    </div>
                               
                            </div>
                        </div>

                        <div class='fcpfw_coupon'>
                            <div class='fcpfw_apply_coupon_link' style="color: <?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_ft_clr']); ?>">
                                <a href='#' id='fcpfw_apply_coupon'>
                                    <?php if($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_1'){
                                        echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_1'])); 
                                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_2'){
                                        echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_2'])); 
                                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_3'){
                                        echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_3'])); 
                                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_4'){
                                        echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_4'])); 
                                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_5'){
                                        echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon_5'])); 
                                    }else{
                                        echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['coupon'])); 
                                    }
                                    ?> 
                                        <?php echo esc_attr($fcpfw_comman['fcpfw_apply_cpn_txt']); ?>
                                </a>
                                
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
                        <div class="fcpfw_ship_txt" style="color: <?php echo esc_attr($fcpfw_comman['fcpfw_ship_ft_clr']); ?>;font-size: <?php echo esc_attr($fcpfw_comman['fcpfw_ship_ft_size'])."px" ?>;"><?php echo esc_attr($fcpfw_comman['fcpfw_ship_txt']); ?></div>
                        <?php

                        ?>
                        <div class="fcpfw_button_fort fcpfw_dyamic_<?php echo esc_attr($fcpfw_comman['fcpfw_footer_button_row']); ?>">
                        <?php  if($fcpfw_comman['fcpfw_cart_option']== "yes") { ?>
                            <a  class="fcpfw_bn_1" href="<?php if(!empty($fcpfw_comman['fcpfw_orgcart_link'])){echo esc_attr($fcpfw_comman['fcpfw_orgcart_link']); }else{  echo wc_get_cart_url(); }?>" style="background-color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_clr']); ?>;margin: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_mrgin']);?>px;color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_txt_clr']); ?>;">
                                <?php echo esc_attr($fcpfw_comman['fcpfw_cart_txt']); ?>
                            </a>
                        <?php } ?>
                        <?php  if($fcpfw_comman['fcpfw_checkout_option'] == "yes"){ ?>
                            <a class="fcpfw_bn_2" href="<?php if(!empty($fcpfw_comman['fcpfw_orgcheckout_link'])){echo esc_attr($fcpfw_comman['fcpfw_orgcheckout_link']);}else{echo wc_get_checkout_url();} ?>" style="background-color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_clr']); ?>;margin: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_mrgin']); ?>px;color: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_ft_btn_txt_clr']); ?>;">
                                <?php echo esc_attr($fcpfw_comman['fcpfw_checkout_txt']); ?>
                            </a>
                        <?php } ?>
                        <?php  if($fcpfw_comman['fcpfw_conshipping_option'] == "yes"){ ?>
                            <a class="fcpfw_bn_3" href="<?php echo esc_attr($fcpfw_comman['fcpfw_conshipping_link']); ?>" style="background-color: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_ft_btn_clr']); ?>;margin: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_mrgin']);?>px;color: <?php echo esc_attr($fcpfw_comman['fcpfw_ft_btn_txt_clr']); ?>;">
                                <?php echo esc_attr($fcpfw_comman['fcpfw_conshipping_txt']); ?>
                            </a>
                        <?php } ?>
                        </div>
                    </div>

               
            </div>
            <div class="fcpfw_container_overlay">
            </div>

            <?php echo FCPFW_cart_empty_value();
        }

        function FCPFW_cart_empty_value(){
            global $fcpfw_comman;
            global $ocwqv_qfcpfw_icon;
                    echo FCPFW_cart_basket();

        }

        function FCPFW_cart_basket(){
            global $fcpfw_comman, $ocwqv_qfcpfw_icon;
            ?>
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

                          <?php echo html_entity_decode(esc_attr($ocwqv_qfcpfw_icon['ocwqv_qfcpfw_icon'])); ?>

                    <?php } ?>                    
                </div>
                <?php if($fcpfw_comman['fcpfw_product_cnt'] == "yes"){ ?>
                    <div class="fcpfw_item_count" >
                        <?php
                        echo FCPFW_counter_value();
                        ?>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
     
        function FCPFW_craete_cart(){
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
                        background-color: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_cnt_bg_clr']); ?>;
                        color: <?php echo esc_attr($fcpfw_comman['fcpfw_cnt_txt_clr']); ?>;
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
                           display: none;
                    <?php }else { ?>
                        display:block;
                       
                    <?php } ?>
                      
                    max-height: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_basket_icn_size'])."px" ?>;
                    max-width: <?php echo esc_attr($fcpfw_comman[ 'fcpfw_basket_icn_size'])."px" ?>;
                    background-color: <?php echo esc_attr($fcpfw_comman['fcpfw_basket_bg_clr']); ?>;
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
                #fcpfw_apply_coupon svg{
                fill : <?php echo esc_attr($fcpfw_comman[ 'fcpfw_apply_cpn_icon_clr']); ?> ;
                }

                
            </style>
            <?php
                if(wp_is_mobile() ){
                    if($fcpfw_comman[ 'fcpfw_mobile'] == "yes") {
                        if(is_checkout() || is_cart()){
                           add_filter( 'wp_footer','FCPFW_cart_create');
                        } else {
                            add_filter( 'wp_footer','FCPFW_cart_create');
                        }
                    }
                } else {
                    if(is_checkout() || is_cart()){
                         add_filter( 'wp_footer','FCPFW_cart_create');
                    } else {
                        add_filter( 'wp_footer','FCPFW_cart_create');
                    }
                }
        }
        
        function FCPFW_comman(){
            global $fcpfw_comman, $ocwqv_qfcpfw_icon;

            $html = '<div class="fcpfw_body">';
            if ( ! WC()->cart->is_empty() ) {

                    $html .= "<div class='fcpfw_cust_mini_cart'>";
                    global $woocommerce;
                    if($fcpfw_comman['fcpfw_cart_ordering']=='asc'){
                        $items = WC()->cart->get_cart(); 
                    }else{
                        $items = array_reverse(WC()->cart->get_cart()); 
                    }
                    
                        foreach($items as $item => $values) { 
                           $_product = apply_filters( 'woocommerce_cart_item_product', $values['data'], $values, $item );

                            $html .= "<div class='fcpfw_cart_prods' product_id='".$values['product_id']."' c_key='".$values['key']."'>";
                            $html .= "<div class='fcpfw_cart_prods_inner'>";
                            
                            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $values['product_id'], $values, $item );
                            $getProductDetail = wc_get_product( $values['product_id'] );
                            if($fcpfw_comman['fcpfw_loop_img']=='yes'){
                                $html .= "<div class='image_div'>";
                                $html .= $getProductDetail->get_image('thumbnail');
                                $html .= '</div>';  
                            }
                           
                            $html .= "<div class='description_div'>";
                         
                     
                            if($fcpfw_comman['ofcpfw_delete_icon'] == 'trash_1'){
                                $fcpfw_delete_icon =  $ocwqv_qfcpfw_icon['trash_1'];
                            }elseif($fcpfw_comman['ofcpfw_delete_icon'] == 'trash_2'){
                                $fcpfw_delete_icon =  $ocwqv_qfcpfw_icon['trash_2'];
                            }elseif($fcpfw_comman['ofcpfw_delete_icon'] == 'trash_3'){
                                $fcpfw_delete_icon =  $ocwqv_qfcpfw_icon['trash_3'];
                            }elseif($fcpfw_comman['ofcpfw_delete_icon'] == 'trash_4'){
                                $fcpfw_delete_icon =  $ocwqv_qfcpfw_icon['trash_4'];
                            }elseif($fcpfw_comman['ofcpfw_delete_icon'] == 'trash_5'){
                                $fcpfw_delete_icon =  $ocwqv_qfcpfw_icon['trash_5'];
                            }elseif($fcpfw_comman['ofcpfw_delete_icon'] == 'trash_6'){
                                $fcpfw_delete_icon =  $ocwqv_qfcpfw_icon['trash_6'];
                            }else{
                                $fcpfw_delete_icon =  $ocwqv_qfcpfw_icon['ocwqv_trash'];
                            }
                            

                            if($fcpfw_comman['fcpfw_loop_delete']=='yes'){
                                $html .= "<div class='fcpfw_prcdel_div'>";
                                $html .= apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="fcpfw_remove"  aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">'.$fcpfw_delete_icon.'</a>', 
                                        esc_url(wc_get_cart_remove_url($item)), 
                                        esc_html__('Remove this item', 'evolve'),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() ),
                                        esc_attr( $item )
                                        ), $item);
                                $html .= "</div>";
                            }

                            
                            $html .= "<div class='fcpfw_prodline_title' >";
                            
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $values ) : '', $values, $item );
                                    if ( $_product && $_product->exists() && $values['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $values, $item ) ) {
                                        $html .= "<div class='fcpfw_prodline_title_inner' >";
                                        if($fcpfw_comman['fcpfw_loop_product_name']=='yes'){
                                            if($fcpfw_comman['fcpfw_loop_link']=='yes'){
                                                $html .= apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $values, $item )  . '&nbsp;'; 
                                            }else{
                                                $html .= apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $values, $item ) . '&nbsp;'; 
                                            }
                                           
                                        }
                                        $html .= "</div>";
                                        if($fcpfw_comman['fcpfw_loop_variation']=='yes'){
                                            $html .= "<div class='oc_metadata'>"; 

                                            $html .= wc_get_formatted_cart_item_data( $values ); 
                                                
                                            $html .= "</div>";
                                        }
                                        if($fcpfw_comman['fcpfw_loop_product_price']=='yes'){
                                            $html .= "<div class='fcpfw_price_single'>".wc_price($_product->get_price())."</div>"; 
                                        }   
                                    }

                            $html .= "</div>";

                            $html .= "<div class='fcpfw_prodline_qty'>";
                            $html .= '<div class="fcpfw_qupdiv">';
                            if ($fcpfw_comman['fcpfw_qty_box'] == "yes" ) {
                               /* $html .= $values['quantity'];*/
                                $html .= '<button type="button" class="fcpfw_minus" >-</button>';
                                $html .= '<input type="text" class="fcpfw_update_qty" name="update_qty" value="'.$values['quantity'].'">';
                                $html .= '<button type="button" class="fcpfw_plus" >+</button>';
                                
                            }else {
                                $html .= $fcpfw_comman['fcpfw_qty_text']." : ".$values['quantity'];
                            }
                            $html .= '</div>';
                            if ($fcpfw_comman['fcpfw_loop_total'] == "yes" ) {
                                $html .= "<div class='fcpfw_prodline_price'>";

                                $fcpfw_product = $values['data'];
                                $fcpfw_product_subtotal = WC()->cart->get_product_subtotal( $fcpfw_product, $values['quantity'] );

                                $html .= $fcpfw_product_subtotal;

                                $html .= "</div>";
                            }

                            $html .= "</div>";
                            $html .= "</div>";
                            $html .= "</div>";
                            $html .= "</div>";
                        }

                    $html .= "</div>";

                }else{
                        if($fcpfw_comman['fcpfw_emptycart_link']!=''){
                             $html .= "<h3 class='empty_cart_text'><a href='".$fcpfw_comman['fcpfw_emptycart_link']."'>".$fcpfw_comman[ 'fcpfw_cart_is_empty']."</a></h3>";
                        }else{
                         $html .= "<h3 class='empty_cart_text'>".$fcpfw_comman['fcpfw_cart_is_empty']."</h3>";
                        }
                }

            $html .= '</div>';
            return $html;
        }

        function FCPFW_counter_value(){
            global $fcpfw_comman;
            if( $fcpfw_comman['fcpfw_product_cnt_type']=='sum_qty'){
                return '<span class="float_countc">'.WC()->cart->get_cart_contents_count().'</span>';
            }else{
                return '<span class="float_countc">'.count(WC()->cart->get_cart()).'</span>';
            }
            
        }

        function FCPFW_cart_count_fragments( $fragments ) {
            global $fcpfw_comman,$ocwqv_qfcpfw_icon;;
            WC()->cart->calculate_totals();
            WC()->cart->maybe_set_cart_cookies();

            $fragments['div.fcpfw_body'] = FCPFW_comman();

            if ($fcpfw_comman['fcpfw_freeshiping_herder'] == "yes" ){ 
                       $fcpfw_shiping = '<div class="top_fcpfw_bottom">';
                            

                                $wg_prodrule_mtvtion_msg = $fcpfw_comman['fcpfw_freeshiping_herder_txt'];
                                                               // 
                                  $zones = WC_Shipping_Zones::get_zones();

                                    $result = null;
                                    $zone = null;
                                    $free_shipping_method = null;
                                    foreach ( $zones as $zones ) {
                                            $shipping_methods_nl = $zones['shipping_methods'];
                                            $free_shipping_method = null;
                                    
                                        foreach ( $shipping_methods_nl as $method ) {
                                          if ( $method->id == 'free_shipping' ) {
                                            $free_shipping_method = $method;
                                            break;
                                          }
                                        }
                                    }

                                if ( $free_shipping_method ) {
                                  $result = $free_shipping_method->min_amount;
                                }

                                $shiiping_total  = $result;
                                $fcpfw_subtotla       =  WC()->cart->get_subtotal();

                                $carta = $shiiping_total - $fcpfw_subtotla;

                                if($shiiping_total >  $fcpfw_subtotla){
                                    $fcpfw_shipping_total =  get_woocommerce_currency_symbol().number_format(($carta ), 2 );
                                    $wg_prodrule_mtvtion_msg_final = str_replace("{shipping_total}", $fcpfw_shipping_total, $wg_prodrule_mtvtion_msg);
                                }else{
                                    $wg_prodrule_mtvtion_msg_final =  $fcpfw_comman['fcpfw_freeshiping_then_herder_txt'];
                                }

                                
                             $fcpfw_shiping .='<p style="color:'.$fcpfw_comman['fcpfw_header_shipping_text_color'].'">'.$wg_prodrule_mtvtion_msg_final.'</p>' ;
                           
                        $fcpfw_shiping .= '</div>';

                        $fragments['.top_fcpfw_bottom p'] = $fcpfw_shiping;
             } 

            
            $fragments['span.float_countc'] = FCPFW_counter_value();
            $item_taxs =WC()->cart->get_cart();
            $iteeem = WC()->cart->get_tax_totals();
            $fcpfw_get_totals = WC()->cart->get_totals();
            $fcpfw_shipping_total =  WC()->cart->get_cart_shipping_total();
            $fcpfw_cart_total = $fcpfw_get_totals['subtotal'];
            //$fcpfw_cart_discount = $fcpfw_get_totals['discount_total'];
            $fcpfw_cart_discount = $fcpfw_get_totals['discount_total']+$fcpfw_get_totals['discount_tax'];
            $fcpfw_final_subtotal = $fcpfw_cart_total ;
            
            $fcpfw_fragtotal = "<div class='fcpfw_total_amount'>".get_woocommerce_currency_symbol().number_format($fcpfw_final_subtotal, 2)."</div>";
            $fcpfw_fulltotal = "<div class='fcpfw_total_innwer_full'>".get_woocommerce_currency_symbol().number_format(WC()->cart->total, 2)."</div>";
            
            // print_R($fcpfw_cart_discount);
            $fcpfw_fulldicount = "<div class='fcpfw_oc_discount_oc'>";
                if ( $fcpfw_cart_discount != 0 ){

                    $fcpfw_fulldicount .= "<div class='fcpfw_discount_label'><span>".$fcpfw_comman['fcpfw_discount_text_trans']."</span></div><div class='fcpfw_discount_innwer_full'> <span class='fcpfw_discount_full'>".get_woocommerce_currency_symbol().number_format( $fcpfw_cart_discount, 2)."</span></div>";   

                }
            $fcpfw_fulldicount .= "</div>";

            $fcpfw_total_amountt = "<div class='fcpfw_total_amountt'>".$fcpfw_shipping_total."</div>";



            $fcpfw_total_innwer = "<span class='fcpfw_fragtotall'>";  
             if(!empty($iteeem)){             
                foreach ($iteeem as $iteeem_tac ) {
                    if(!empty($iteeem_tac->amount)){
                           $fcpfw_total_innwer .= get_woocommerce_currency_symbol().number_format($iteeem_tac->amount, 2); 
                    }
                }
            }else{     
                   $fcpfw_total_innwer .=  get_woocommerce_currency_symbol().number_format(0, 2);                         
            }
            $fcpfw_total_innwer .= "</span>";
            ob_start();
            if(isset($fcpfw_comman['fcpfw_cart_empty']) && $fcpfw_comman['fcpfw_cart_empty'] == "yes" &&  (WC()->cart->cart_contents_count == 0)){?>
                <script type="text/javascript">
                    jQuery( ".fcpfw_cart_basket" ).css( "display","none" );
                </script>

            <?php }else{ ?>
                 <script type="text/javascript">
                   jQuery( ".fcpfw_cart_basket" ).css( "display","block" );
                </script>
                
            <?php }
            if($fcpfw_comman['fcpfw_cart_empty_hide_show'] == "hide" && WC()->cart->is_empty() ){ ?>
                <script type="text/javascript">
                    jQuery( ".fcpfw_container" ).addClass( "fcpfw_cart_empty" );
                </script>

            <?php }else{ ?>
                 <script type="text/javascript">
                    jQuery( ".fcpfw_container" ).removeClass( "fcpfw_cart_empty" );
                </script>
                
            <?php }
            $fcpfw_total_innwerscript = ob_get_clean();
            $fcpfw_total_innwer .= $fcpfw_total_innwerscript;
            $fragments['div.fcpfw_total_innwer_full'] = $fcpfw_fulltotal;
            $fragments['div.fcpfw_total_amount'] = $fcpfw_fragtotal;
            $fragments['div.fcpfw_total_amountt'] = $fcpfw_total_amountt;
            $fragments['span.fcpfw_fragtotall'] = $fcpfw_total_innwer;
            $fragments['div.fcpfw_oc_discount_oc'] = $fcpfw_fulldicount;
            ob_start();
           

            $fcpfw_fragslider = ob_get_clean();
            $fragments['div.fcpfw_slider_inn'] = $fcpfw_fragslider;

            $fcpfw_coupon_html = "<div class='fcpfw_coupon'>";
            $fcpfw_coupon_html .= "<div class='fcpfw_apply_coupon_link'>";
  
            if($fcpfw_comman['fcpfw_coupon_icon'] == 'coupon_1'){
                        $fcpfw_qfcpfw_icon =$ocwqv_qfcpfw_icon['coupon_1']; 
                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_2'){
                        $fcpfw_qfcpfw_icon =$ocwqv_qfcpfw_icon['coupon_2']; 
                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_3'){
                        $fcpfw_qfcpfw_icon =$ocwqv_qfcpfw_icon['coupon_3']; 
                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_4'){
                        $fcpfw_qfcpfw_icon =$ocwqv_qfcpfw_icon['coupon_4']; 
                    }elseif($fcpfw_comman['fcpfw_coupon_icon']== 'coupon_5'){
                        $fcpfw_qfcpfw_icon = $ocwqv_qfcpfw_icon['coupon_5']; 
                    }else{
                        $fcpfw_qfcpfw_icon =$ocwqv_qfcpfw_icon['coupon']; 
                    }
            $fcpfw_coupon_html .= "<a href='#' style='color:".$fcpfw_comman[ 'fcpfw_apply_cpn_ft_clr']."' id='fcpfw_apply_coupon'>".$fcpfw_qfcpfw_icon."".$fcpfw_comman['fcpfw_apply_cpn_txt']."</a>";
            $fcpfw_coupon_html .= "</div>";
            $fcpfw_coupon_html .= '<div class="fcpfw_coupon_field">';
            $fcpfw_coupon_html .= '<input type="text" id="fcpfw_coupon_code" placeholder="'.$fcpfw_comman[ 'fcpfw_apply_cpn_plchldr_txt'].'">';
            $fcpfw_coupon_html .= '<span class="fcpfw_coupon_submit" style="background-color: '.$fcpfw_comman[ 'fcpfw_applybtn_cpn_bg_clr'].'; color: '.$fcpfw_comman[ 'fcpfw_applybtn_cpn_ft_clr'].';">'.$fcpfw_comman[ 'fcpfw_apply_cpn_apbtn_txt'].'</span>';
            $fcpfw_coupon_html .= '</div>';

            $applied_coupons = WC()->cart->get_applied_coupons();
            if(!empty($applied_coupons)) {
                $fcpfw_coupon_html .= "<ul class='fcpfw_applied_cpns'>";

                foreach($applied_coupons as $cpns ) {

                    $fcpfw_coupon_html .= "<li class='fcpfw_remove_cpn' cpcode='".$cpns."'>".$cpns." <span class='dashicons dashicons-no'></span></li>";
                    
                }

                $fcpfw_coupon_html .= "</ul>";
            }

            $fcpfw_coupon_html .= "</div>";

            $fragments['div.fcpfw_coupon'] = $fcpfw_coupon_html;

            return $fragments;
        }


        function FCPFW_ajax_product_remove() {
            ob_start();
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
                {
                    WC()->cart->remove_cart_item($cart_item_key);
                }
            }

            WC()->cart->calculate_totals();
            WC()->cart->maybe_set_cart_cookies();

            woocommerce_mini_cart();

            $mini_cart = ob_get_clean();

            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                    )
                ),
                'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
            );

            wp_send_json( $data );

            die();
        }
        
        function FCPFW_change_qty_cust() {

            $c_key = sanitize_text_field($_REQUEST['c_key']);
            $qty = sanitize_text_field($_REQUEST['qty']);
            WC()->cart->set_quantity($c_key, $qty, true);
            WC()->cart->set_session();
            exit();
        }

        function FCPFW_coupon_ajax_call_func() {
            global $fcpfw_comman;
            $code = sanitize_text_field($_REQUEST['coupon_code']);
            $code = strtolower($code);

            // Check coupon code to make sure is not empty
            if( empty( $code ) || !isset( $code ) ) {

                $fcpfw_cpnfield_empty_txt = $fcpfw_comman[ 'fcpfw_cpnfield_empty_txt'];
                // Build our response
                $response = array(
                    'result'    => 'empty',
                    'message'   => $fcpfw_cpnfield_empty_txt
                );

                header( 'Content-Type: application/json' );
                echo json_encode( $response );

                // Always exit when doing ajax
                WC()->cart->calculate_totals();
                WC()->cart->maybe_set_cart_cookies();
                WC()->cart->set_session();
                exit();
            }

            // Create an instance of WC_Coupon with our code
            $coupon = new WC_Coupon( $code );

            if (in_array($code, WC()->cart->get_applied_coupons())) {

                $fcpfw_cpn_alapplied_txt =$fcpfw_comman['fcpfw_cpn_alapplied_txt'];

                $response = array(
                    'result'    => 'already applied',
                    'message'   => $fcpfw_cpn_alapplied_txt
                );

                header( 'Content-Type: application/json' );
                echo json_encode( $response );

                // Always exit when doing ajax
                WC()->cart->calculate_totals();
                WC()->cart->maybe_set_cart_cookies();
                WC()->cart->set_session();
                exit();

            } elseif( !$coupon->is_valid() ) {

                $fcpfw_invalid_coupon_txt = $fcpfw_comman[ 'fcpfw_invalid_coupon_txt'];
                // Build our response
                $response = array(
                    'result'    => 'not valid',
                    'message'   => $fcpfw_invalid_coupon_txt
                );

                header( 'Content-Type: application/json' );
                echo json_encode( $response );

                // Always exit when doing ajax
                WC()->cart->calculate_totals();
                WC()->cart->maybe_set_cart_cookies();
                WC()->cart->set_session();
                exit();

            } else {
                
                WC()->cart->apply_coupon( $code );

                $fcpfw_coupon_applied_suc_txt = $fcpfw_comman[ 'fcpfw_coupon_applied_suc_txt'];
                // Build our response
                $response = array(
                    'result'    => 'success',
                    'message'      => $fcpfw_coupon_applied_suc_txt
                );

                header( 'Content-Type: application/json' );
                echo json_encode( $response );

                // Always exit when doing ajax
                WC()->cart->calculate_totals();
                WC()->cart->maybe_set_cart_cookies();
                WC()->cart->set_session();
                wc_clear_notices();
                exit();

            }
        }


        function fcpfw_remove_applied_coupon_ajax_call_func() {
            global $fcpfw_comman;
            $code = sanitize_text_field($_REQUEST['remove_code']);
            
            $fcpfw_coupon_removed_suc_txt =$fcpfw_comman['fcpfw_coupon_removed_suc_txt'];

            if(WC()->cart->remove_coupon( $code )) {
                echo esc_attr($fcpfw_coupon_removed_suc_txt);
            }
            WC()->cart->calculate_totals();
            WC()->cart->maybe_set_cart_cookies();
            WC()->cart->set_session();
            exit();
        }



        function FCPFW_single_added_to_cart_event(){
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
                <div class="fcpfw_inner_div" style="width:<?php echo esc_attr($fcpfw_sidecart_width); ?>;">
                    <span id="fcpfw_cpn_resp" style="width:<?php echo esc_attr($fcpfw_sidecart_width) ; ?>;"></span>
                </div>w
            </div>
            <?php
        }


        function fcpfw_prod_slider_ajax_atc() {

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === $fcpfw_comman['woocommerce_cart_redirect_after_add']) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
        }
