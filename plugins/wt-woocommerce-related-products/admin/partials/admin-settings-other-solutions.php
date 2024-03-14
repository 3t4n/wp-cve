
<?php
if ( ! defined( 'WPINC' ) ) {die;
}


$wt_rp_admin_img_path = CRP_PLUGIN_URL . 'admin/img/other_solutions';
?>
<style>

.wt_crp_branding {
    text-align: end;
    width: 100%;
    margin-bottom: 10px;
}
.wt_crp_brand_label {
    width: 100%;
    padding-bottom: 10px;
    font-size: 11px;
    font-weight: 600;
}
.wt_crp_brand_label {
    width: 100%;
    padding-bottom: 10px;
    font-size: 11px;
    font-weight: 600;
}
.wt_crp_brand_logo img {
    max-width: 100px;
}

.wt-crp-main-container{
    background-color: white;
    padding: 20px 10px 10px 20px;
    display: inline-block;
    margin-top: 23px;
}
.wt-crp-main-container{ background-color: white; margin-top: 23px; }

/* Style for You may also need tab starts here */

.wt_exclusive{ position: relative; color: #433434;}

.wt_rp_row_1{ display: flex; position: relative; padding-top: 40px;}
.wt_rp_row_2 { padding: 1% 3%;font-weight: 400;}
.wt_rp_row_2 h2{ font-weight: 700; font-size: 24px;}
.wt_rp_discount_logo{ position: absolute; top: 20px; left: 10px;}

.wt_rp_promo_code { margin-top: 30px; display: flex; border-left: 2px solid #007FFF; padding-left: 30px; padding-right: 90px}
.wt_rp_copied {position: absolute; top: 109px; left: 866px; display: none; font-size: 10px;}
.wt_rp_promo_code p{ font-size: 15px; padding-right: 20px;}
.wt_rp_promo_code_text{ height: 110px; display: flex; border: 2px solid #007FFF; padding-left: 60px; margin-left: 130px; border-radius: 6px;}

.wt_rp_exclusive_for_you { padding-right: 100px}
.wt_rp_exclusive_for_you h1{ font-weight:700; font-size:24px; }
.wt_rp_exclusive_for_you p{ font-weight: 400; font-size: 14px; }

/*.wt_rp_also_need_plugin_row{width: 100%; background: #12265F; color: #FFFFFF; display: flex;}*/
.wt_rp_also_need_plugin_row{width: 102%; background: #12265F; color: #FFFFFF; display: flex; margin-left: -19px;}


.wt_rp_also_need_plugin_title_wrapper img { width: 45px;height: 45px; }
.wt_rp_also_need_plugin_title_wrapper h3{ margin-left: 12px; font-size: 15px; font-weight: 700; }
.wt_rp_also_need_plugin_title_wrapper{ padding-top:18px; display: flex; }

/*.wt_rp_also_need_plugin_img img{ padding: 65px 100px 60px 100px; }*/
.wt_rp_also_need_plugin_img img{ padding: 5% 74px; }
.wt_rp_also_need_plugin_content{ width:100%; box-sizing:border-box;  height: auto; padding: 2% 5%;}
.wt_rp_also_need_plugin_content ul{ list-style:none; margin-left:20px; margin-top: 15px; }
.wt_rp_also_need_plugin_content li{ float:left; width:calc(100% - 23px); margin-left:23px; box-sizing:border-box; padding-left:23px; padding:4px 0px; font-size: 15px; font-weight: 400;}
.wt_rp_also_need_plugin_content li .dashicons{ margin-left:-20px; float:left; color:#6abe45; }
.wt_rp_also_need_plugin_content li .dashicons-yes-alt{ color:#18c01d; margin-right: 20px; font-size: 17px;}

.wt_rp_fbt_content{ box-sizing:border-box; padding: 3% 4%;}
.wt_rp_fbt_content ul{ list-style:none; margin-left:20px;color: #434343; }
.wt_rp_fbt_content li{ float:left; width:calc(100% - 23px); margin-left:23px; box-sizing:border-box; padding-left:23px; padding:4px 0px; font-size: 15px; font-weight: 400; }
.wt_rp_fbt_content li .dashicons{ margin-left:-20px; float:left; color:#434343; }
.wt_rp_fbt_content li .dashicons-yes-alt{ color:#18c01d; margin-right: 20px; font-size: 17px;}

.wt_rp_visit_plugin_btn{ width: 140px; display:inline-block; padding:16px 35px; color:#fff; background:#007FFF; border-radius:5px; text-decoration:none; font-size:14px; margin-top:14px; font-weight: 600; margin-left:30px }
.wt_rp_visit_plugin_btn:hover{ color:#fff; text-decoration:none; background:#1da5f8; color:#fff; }

.dashicons-info-outline{ color: #007FFF; font-size: 14px; }
.tooltip{position: absolute; left: 529px; bottom: -1px; margin: 10px;}
/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #D1E5FE;
    color: #434343;
    text-align: center;
    padding: 5px 0;

    /* Position the tooltip text */
    position: absolute;
    z-index: 1;
    top: 100%;
    width: 300px;
    margin-left: -47px;

    /* Fade in tooltip */
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 13px;
    font-weight: 400;
    font-style: italic;
}

/* Tooltip arrow */
.tooltip .tooltiptext::after {
  content: "";
    position: absolute;
    bottom: 100%;
    left: 50%;
    margin-left: -117px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent #D1E5FE transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}

/* Style for You may also need tab ends here */

</style>

<div class="wt-crp-main-container">
<!-- You may also like starts here -->
    <div class="wt_exclusive">

        <div class="wt_rp_row_1">
            <img class="wt_rp_discount_logo" src="<?php echo esc_url($wt_rp_admin_img_path . '/' . 'discount-image.svg');?>">
            <div class="wt_rp_promo_code_text">
                <div class="wt_rp_exclusive_for_you">
                   <h1><?php _e('Exclusive for you!', 'wt-woocommerce-related-products'); ?></h1>
                   <p><?php _e('If you like our free plugin, you will definitely like our premium ones.<br>Get all of the premium promotion plugins at <b>30% off<b>. <div class="tooltip"> <span class="dashicons dashicons-info-outline"></span> <span class="tooltiptext">(Coupon applicable for the first purchase only)</span> </div> <br>', 'wt-woocommerce-related-products'); ?></p>             
                </div>
                <div class="wt_rp_promo_code">
                   <p><?php _e('Use code', 'wt-woocommerce-related-products'); ?></p>
                   <a href="#" class="wt_rp_copy_content" value='PROMO30' style="background: #007FFF; border-radius: 5px;"><img src="<?php echo esc_url($wt_rp_admin_img_path . '/' . 'promo-code.svg');?>" style=" height: 35px; margin-top: 8px;"></a>           
                   <p class="wt_rp_copied"><?php _e('Copied!', 'wt-woocommerce-related-products'); ?></p>
                   <p style="padding-left: 17px;"><?php _e('at checkout.', 'wt-woocommerce-related-products'); ?></p> 
                </div>
            </div>
        </div>

        <div class="wt_rp_row_2">
            <h2><?php _e('Premium extensions', 'wt-woocommerce-related-products'); ?></h2>
            <p style="font-size: 15px;"><?php _e('Level up your product suggestions and improve conversion rates!', 'wt-woocommerce-related-products'); ?></p>
        </div>
    </div>


    <div class="wt_rp_also_need_plugin_row" > 
        <div class="wt_rp_also_need_plugin_content" >
            <div class="wt_rp_also_need_plugin_title_wrapper">
                <img src="<?php echo esc_url($wt_rp_admin_img_path . '/' . 'product-recommendation-plugin.svg');?>">
                <h3 style="color: #FFFFFF;"><?php _e('WooCommerce Product Recommendations', 'wt-woocommerce-related-products'); ?></h3>
            </div>
            <ul>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Automatic product recommendations','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Generate suggestions using filters','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Place recommendations on relevant pages','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Sort products by price, popularity, rating, etc.','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Display recommendations in a grid or a slider','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Hide out-of-stock products from suggestions','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Multiple product type support','wt-woocommerce-related-products'); ?></li>
            </ul>
            <a href="https://www.webtoffee.com/product/woocommerce-product-recommendations/?utm_source=addon_page&utm_medium=related_free_plugin&utm_campaign=Product_Recommendations" target="_blank" class="wt_rp_visit_plugin_btn"><?php _e('Visit plugin page', 'wt-woocommerce-related-products'); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
        </div>
        <div class="wt_rp_also_need_plugin_img">
            <img src="<?php echo esc_url($wt_rp_admin_img_path . '/' .'product-recommendations.png')?>" width="556px"; height="429px";>
        </div>
    </div>

    <div class="wt_rp_also_need_plugin_row" style=" background: white;!important color: #434343; display: flex; !important"> 
        <div class="wt_rp_also_need_plugin_img">
            <img src="<?php echo esc_url($wt_rp_admin_img_path . '/' .'frequently-bought-together.png')?>" width="556px"; height="429px";>
        </div>
        <div class="wt_rp_fbt_content">
            <div class="wt_rp_also_need_plugin_title_wrapper">
                <img src="<?php echo esc_url($wt_rp_admin_img_path . '/' . 'frequently-bought-together-plugin.svg');?>">
                <h3 style="color: #2647A3;"><?php _e('Frequently Bought Together For WooCommerce', 'wt-woocommerce-related-products'); ?></h3>
            </div>
            <ul>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Suggest products based on store order history','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Display suggestions on product pages','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Multiple recommendation layouts','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Offers discounts on product bundles','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Use upsells, cross-sells, & related products as frequently bought products','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Customize the title, button, and label texts','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'A quick edit page to enable, edit or remove suggestions','wt-woocommerce-related-products'); ?></li>
            </ul>
            <a href="https://www.webtoffee.com/product/woocommerce-frequently-bought-together/?utm_source=addon_page&utm_medium=related_free_plugin&utm_campaign=Frequently_Bought_Together" target="_blank" class="wt_rp_visit_plugin_btn"><?php _e('Visit plugin page', 'wt-woocommerce-related-products'); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
        </div>
    </div>

    <div class="wt_rp_also_need_plugin_row" style="background: #12265F;"> 
        <div class="wt_rp_also_need_plugin_content" >
            <div class="wt_rp_also_need_plugin_title_wrapper">
                <img src="<?php echo esc_url($wt_rp_admin_img_path . '/' . 'best-sellers-plugin.svg');?>">
                <h3 style="color: #FFFFFF;"><?php _e('Best Sellers For WooCommerce', 'wt-woocommerce-related-products'); ?></h3>
            </div>
            <ul>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Add best-seller labels to products','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Add best-seller seals to products','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Create a dedicated page for bestsellers','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Display products based on sales count','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Supports simple and variable products','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Create a best-seller slider on the shop page, category page, and product page','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Customize slider view for best sellers','wt-woocommerce-related-products'); ?></li>
                <li><span class="dashicons dashicons-yes-alt"></span><?php esc_html_e( 'Limit the number of products displayed','wt-woocommerce-related-products'); ?></li>
            </ul>
            <a href="https://www.webtoffee.com/product/woocommerce-best-sellers/?utm_source=addon_page&utm_medium=related_free_plugin&utm_campaign=WooCommerce_Best_Sellers" target="_blank" class="wt_rp_visit_plugin_btn"><?php _e('Visit plugin page', 'wt-woocommerce-related-products'); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
        </div>
        <div class="wt_rp_also_need_plugin_img">
            <img src="<?php echo esc_url($wt_rp_admin_img_path . '/' .'best-seller.png')?>" width="556px"; height="429px";>
        </div>
    </div>
<!-- You may also like ends here -->
</div>


