<?php foreach($items as $item => $values) {
    $_product =  wc_get_product( $values['data']->get_id() );
    $product_link = get_permalink( $values['data']->get_id() );
    $variations = wc_get_formatted_cart_item_data($values,true);
    ?>

    <div class="woo_amc_item_wrap">
        <div class="woo_amc_item">
            <div class="woo_amc_item_delete" data-key="<?php echo $item; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.008 16.008">
                    <g transform="translate(-1865.147 -163.146)">
                        <line x1="15.301" y2="15.301" transform="translate(1865.5 163.5)"/>
                        <line x2="15.301" y2="15.301" transform="translate(1865.5 163.5)"/>
                    </g>
                </svg>
            </div>
            <a href="<?php echo $product_link; ?>" class="woo_amc_item_img">
                <?php echo $_product->get_image(); ?>
            </a>
            <div class="woo_amc_item_content">
                <div class="woo_amc_item_title">
                    <a href="<?php echo $product_link; ?>"><?php echo $_product->get_title(); ?></a>
                </div>
                <?php if($variations){ ?>
                    <div class="woo_amc_item_dop">
                        <?php echo $variations; ?>
                    </div>
                <?php } ?>
                <div class="woo_amc_item_price_wrap">
                    <div class="woo_amc_item_price_label">Price:</div>
                    <?php echo $_product->get_price_html(); ?>
                </div>
                <div class="woo_amc_item_quanity_wrap">
                    <div class="woo_amc_item_quanity_update woo_amc_item_quanity_minus" data-type="minus">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 1">
                            <g transform="translate(-1718.5 -249)">
                                <line x2="10" transform="translate(1718.5 249.5)"/>
                            </g>
                        </svg>
                    </div>
                    <input data-key="<?php echo $item; ?>" type="text" class="woo_amc_item_quanity" value="<?php echo $values['quantity']; ?>">
                    <div class="woo_amc_item_quanity_update woo_amc_item_quanity_plus" data-type="plus">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
                            <g id="плюс" transform="translate(-1850.5 -226.5)">
                                <line x2="10" transform="translate(1850.5 231.5)"/>
                                <line y2="10" transform="translate(1855.5 226.5)"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="woo_amc_item_total_price">
                <?php echo wc_price( $values['line_total'] ); ?>
            </div>
            <div class="woo_amc_clear"></div>
        </div>
    </div>
<?php } ?>