<table class="wcmmq-table universal-setting">
    <thead>
        <tr>
            <th class="wcmmq-inside">
                <div class="wcmmq-table-header-inside">
                    <h3><?php echo esc_html__( 'Settings (Universal)', 'wcmmq' ); ?></h3>
                </div>
                
            </th>
            <th>
            <div class="wcmmq-table-header-right-side"></div>
            </th>
        </tr>
    </thead>

    <tbody>
        <!-- <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        LabelTagHere
                    </div>
                    <div class="form-field col-lg-6">
                        InputFieldOrAnyOtherField
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    DescriptionOfField_and_docLink
                </div> 
            </td>
        </tr> -->
        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]"> <?php echo esc_html__( 'Minimum Quantity', 'wcmmq' ); ?></label>
                    </div>
                    <div class="form-field col-lg-6">
                    <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>min_quantity]" class="ua_input_number config_min_qty" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'min_quantity']; ?>"  type="number" step=any>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    Minimum value for input box. it's Global. 
                    <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                </div> 
            </td>
        </tr>
        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                    <label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]"><?php echo esc_html__('Maximum Quantity','wcmmq');?></label>
                    </div>
                    <div class="form-field col-lg-6">
                    <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>max_quantity]" class="ua_input_number config_max_qty" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'max_quantity']; ?>"  type="number" step=any>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                </div> 
            </td>
        </tr>


        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]"><?php echo esc_html__('Quantity Step','wcmmq');?></label>
                    </div>
                    <div class="form-field col-lg-6">
                        <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>product_step]" class="ua_input_number" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'product_step']; ?>"  type="number" step=any>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    this is step for input filed
                <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                </div> 
            </td>
        </tr>

        <tr class="divider-row wcmmq-free-pro-notice-message">
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        Message for min,max,step
                    </div>
                    <div class="form-field col-lg-6">
                        <?php if($this->is_pro){
                            $class = 'wcmmq-input-decimal-msg-pro';
                            $msgs = 'You able to add decimal value as your min max and step.';
                        }else{
                            $msgs = 'If you want Decimal value for minimum, maxium and step, Need pro version.';
                            $class = 'wcmmq-input-decimal-msg-free';
                        }
                        ?>
                        
                        <p class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $msgs ); ?></p>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    <p>It's a notice for free or premium version.</p>
                </div> 
            </td>
        </tr>

        <?php
        //At this moment, no need it
        // $exist_dfl_qty = $saved_data[WC_MMQ_PREFIX . 'default_quantity'] ?? false;
        $default_qty = apply_filters( 'wcmmq_default_qty_option', false, $saved_data );
        if( $default_qty ){
        ?>

        

        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]">
                            <?php echo esc_html__('Default Quantity','wcmmq');?> 
                            <span class="hightlighted_text"><?php echo esc_html__('(Optional)','wcmmq');?></span>
                        </label>
                    </div>
                    <div class="form-field col-lg-6">
                        <input name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]" id="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>default_quantity]" class="ua_input_number" value="<?php echo $saved_data[WC_MMQ_PREFIX . 'default_quantity']; ?>"  type="number" step=any>
                        <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/'); ?>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    <p style="color: #228b22;">
                        It's shoold empty, If you don't know, what is this.
                    </p>
                </div> 
            </td>
        </tr>
        <?php
        }
        if( wc_get_price_decimal_separator() != '.' ){ 
            $decimal_separator = $saved_data['decimal_separator'] ?? '.';
        ?>

        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="data[decimal_separator]"><?php echo esc_html__('Quantity Decimal Separator','wcmmq');?> 
                            <span class="hightlighted_text"><?php echo esc_html__('(Optional)','wcmmq');?></span>
                        </label>
                    </div>
                    <div class="form-field col-lg-6">
                        <input name="data[decimal_separator]" id="data[decimal_separator]" class="ua_input_number" value="<?php echo $decimal_separator ; ?>">
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    
                </div> 
            </td>
        </tr>    
        <?php } ?>
        

            <!-- 
            * Will add quantity box on archive pages
            * @ since 3.6.0
            * @ Author Fazle Bari 
            -->
            <?php $quantiy_box_archive = isset( $saved_data['quantiy_box_archive' ] ) && $saved_data['quantiy_box_archive' ] == '1' ? 'checked' : false; ?>
        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="_quantiy_box_archive"><?php echo esc_html__('Archive Quantity Box','wcmmq');?></label>
                    </div>
                    <div class="form-field col-lg-6">
                        <label class="switch">
                            <input value="1" name="data[quantiy_box_archive]"
                                <?php echo $quantiy_box_archive; /* finding checked or null */ ?> type="checkbox" id="_quantiy_box_archive">
                            <div class="slider round"><!--ADDED HTML -->
                                <span class="on"><?php echo esc_html__('ON','wcmmq');?></span><span class="off"> <?php echo esc_html__('OFF','wcmmq');?></span><!--END-->
                            </div>
                        </label>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">

                    <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/add-quantity-box-on-shop-page/'); ?>
                    <p><b>Nee pro, if want qty box for variable product.</b></p>
                    <p>
                    For ajax add to cart, Enable from <strong>WooCommerce->Settings->Products->Add to cart behaviour</strong>.<br>
                    and use <code>add_filter('wcmmq_ajax_cart_single_page', '__return_true');</code> in your child theme bor by code snippet.
                    If you need Plus Minus Button for your Quantity Box install <a href="https://wordpress.org/plugins/wc-quantity-plus-minus-button/" target="_blank">Quantity Plus Minus Button for WooCommerce by CodeAstrology</a>
                    </p>
                </div> 
            </td>
        </tr>

        <?php $disable_order_page = isset( $saved_data[ WC_MMQ_PREFIX . 'disable_order_page' ] ) && $saved_data[ WC_MMQ_PREFIX . 'disable_order_page' ] == '1' ? 'checked' : false; ?>
        <tr>
            <td>
                <div class="wcmmq-form-control">
                    <div class="form-label col-lg-6">
                        <label for="_disable_order_page"><?php echo esc_html__('Order Page (Condition)','wcmmq');?></label>
                    </div>
                    <div class="form-field col-lg-6">
                        <label class="switch">
                            <input value="1" name="data[<?php echo esc_attr( WC_MMQ_PREFIX ); ?>disable_order_page]"
                                <?php echo $disable_order_page; /* finding checked or null */ ?> type="checkbox" id="_disable_order_page">
                            <div class="slider round"><!--ADDED HTML -->
                                <span class="on"><?php echo esc_html__('ON','wcmmq');?></span><span class="off"> <?php echo esc_html__('OFF','wcmmq');?></span><!--END-->
                            </div>
                        </label>
                    </div>
                </div>
            </td>
            <td>
                <div class="wcmmq-form-info">
                    <?php wcmmq_doc_link('https://codeastrology.com/min-max-quantity/set-conditions-on-woocommerce-order-page/'); ?>
                </div> 
            </td>
        </tr>
        <?php if(!$this->is_pro){ ?>

            <tr class="user_can_not_edit">
                <td>
                    <div class="wcmmq-form-control">
                        <div class="form-label col-lg-6">
                            <label for="data[_wcmmq_s_display_price_with_min]">
                            Multiply Price By Quantity                        </label>
                        </div>
                        <div class="form-field col-lg-6">
                            <label class="switch">
                                <input value="1" name="data[display_price_with_min]" type="checkbox" id="_wcmmq_display_price_with_min">
                                <div class="slider round">
                                    <!--ADDED HTML -->
                                    <span class="on">ON</span><span class="off">OFF</span><!--END-->
                                </div>
                            </label>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="wcmmq-form-info">
                        <a href="https://codeastrology.com/min-max-quantity/multiply-price-by-quantity/" target="_blank" class="wpt-doc-lick"><i class="wcmmq_icon-help-circled-alt"></i>Helper doc</a>
                        <p>Product price will multiply by the quantity before being displayed on a single product page and will Increase or decrease in accordance with the quantity. </p>
                    </div>
                </td>
            </tr>

        <?php } ?>
        <?php
        /**
         * Obviously need tr and td here
         * 
         */
        do_action( 'wcmmq_setting_bottom_row', $saved_data );
        ?>
    </tbody>

    
</table>