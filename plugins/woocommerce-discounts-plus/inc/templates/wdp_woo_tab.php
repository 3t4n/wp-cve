<?php

    global $wdp_dir, $wdp_url, $wdp_premium_link;


?>


<style>
    body{
        background-color: rgb(241, 241, 241);
        font-size: 13px;
        line-height: 1.4em;
        color: #444;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
    }



    .wdp-guy ul{
        margin-top: 13px;
        margin-bottom: 13px;
        box-sizing: unset;
    }
</style>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="alert alert-info text-center">
            <?php _e('All settings are moved in separate tabs for your convenience.', "wcdp") ?> <?php _e('Please click on your desired tab and proceed with the discounts criteria.', "wcdp") ?>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12 text-center ">
        <div class="btn-group  ld ld-breath wpd_tab_btn_group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary wpd_tab_switch ld-breath" data-tab="general_tab"><?php _e('General Settings', "wcdp") ?></button>
            <button type="button" class="btn btn-secondary wpd_tab_switch ld-breath" data-tab="global_tab"><?php _e('Global Criteria', "wcdp") ?></button>
            <button type="button" class="btn btn-secondary wpd_tab_switch ld-breath" data-tab="category_tab"><?php _e('Category Based Criteria', "wcdp") ?></button>
            <button type="button" class="btn btn-secondary wpd_tab_switch ld-breath" data-tab="cart_amount_tab"><?php _e('Cart Amount Based Criteria', "wcdp") ?></button>
            <button type="button" class="btn btn-secondary wpd_tab_switch ld-breath" data-tab="product_tab"><?php _e('Product Based Criteria', "wcdp") ?></button>
            <button type="button" class="btn btn-secondary wpd_tab_switch ld-breath" data-tab="error_tab"><?php _e('Error Messages', "wcdp") ?></button>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12 text-center">

        <a href="<?php echo esc_url($wdp_premium_link); ?>" target="_blank"><img class="img-thumbnail" src="<?php echo esc_url($wdp_url); ?>images/features-console.gif" alt="" /></a>

    </div>
</div>


