<style>
    
.eh-button-go-pro {
    box-shadow: none;
    border: 0;
    text-shadow: none;
    padding: 10px 20px 10px 30px;
    height: auto;
    font-size: 16px;
    border-radius: 4px;
    font-weight: 600;
    background: #6ABE45;
    margin: 20px 2px 20px 2px;
    text-decoration: none;
}

.eh-button {
    margin-bottom: 20px;
    color: #fff;
}
.eh-button:hover, .eh-button:visited {
    color: #fff;
}
.eh_gopro_block{ background: #fff; float: left; height:auto; padding: 10px; box-shadow: 0px 2px 2px #ccc; margin-bottom: 32px; width: 100%; border-top:solid 1px #cccccc; }
.eh_gopro_block h3{ text-align: center; }

.eh_premium_upgrade_head {
    font-weight: 600;
    font-size: 17px;
    line-height: 25px;
    color: #000000;
    margin-bottom: 20px;
}
.eh_pro_features li{ padding-left:25px;  }

.eh_pro_features li.money-back:before{
  content: '';
  position: absolute;
  margin-right: 10px;
  margin-left: -40px;
  height:40px ;
  width: 50px;
  background-image: url(<?php echo esc_url(EH_STRIPE_MAIN_URL_PATH.'assets/img/money-back.svg'); ?>);
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;
}

.eh_pro_green_btn:before{
  content: '';
  position: absolute;
  height: 15px;
  width: 18px;
  background-image: url(<?php echo esc_url(EH_STRIPE_MAIN_URL_PATH.'assets/img/white-crown.svg'); ?>);
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  margin: 2px 7px;
}
.eh_premium_features{
    padding: 18px 0px 18px 5px; 
    font-size: 14px; 
    font-weight: 549;
    background:#F3FAFF; 
    border-radius: 10px;
    border-color: #c6e7ef;
    border-width: 0.5px;
    border-style: solid;
}
.eh_premium_features li{ padding:10px 5px 10px 35px;  }
.eh_premium_features li::before {
    background-image: url(<?php echo esc_url(EH_STRIPE_MAIN_URL_PATH.'assets/img/green-tick.svg'); ?>);
    font-weight: 400;
    font-style: normal;
    vertical-align: top;
    text-align: center;
    content: "";
    margin-right: 10px;
    margin-left: -25px;
    font-size: 16px;
    color: #3085bb;
    height: 18px;
    width: 18px;
    position: absolute;
}
.eh-button-documentation{
    border: 0;
    background: #d8d8dc;
    box-shadow: none;
    padding: 10px 30px;
    font-size: 12px;
    font-weight: 550;
    height: auto;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 10px;
    border-radius: 3px;
    text-decoration: none;
}
.wfte_branding{
    text-align:end; 
    width: 25%;
    float: right;
    padding-top: 5px;
}
.wfte_branding_label{
    font-size: 11px;
    font-weight: 600;
}
</style>

<div class="eh_gopro_block">
    <div class="eh_premium_upgrade">
        <div>
            <img src="<?php echo EH_STRIPE_MAIN_URL_PATH.'assets/img/crown.svg' ?>" style="margin: 4px auto 20px 65px; display:inline-block;">

        </div>
        <div class="eh_premium_upgrade_head"><center><?php _e( 'Upgrade to premium', 'payment-gateway-stripe-and-woocommerce-integration' ); ?></center></div>

        
    </div>
        <ul class="eh_premium_features">
            <li><?php echo __('Supports recurring payments for WooCommerce subscriptions','payment-gateway-stripe-and-woocommerce-integration'); ?></li>         
            <li><?php echo __('Premium priority support','payment-gateway-stripe-and-woocommerce-integration'); ?></li>         
            <li><?php echo __('Timely compatibility updates & bug fixes','payment-gateway-stripe-and-woocommerce-integration'); ?></li>
            <div class="eh_pro_green_btn" style="padding-top:15px; padding-left: 25px; padding-bottom: 15px;">
               <!--<p style="text-align: left;">-->
                <a href="https://www.webtoffee.com/product/woocommerce-stripe-payment-gateway/?utm_source=free_plugin_sidebar&utm_medium=Stripe_basic&utm_campaign=Stripe&utm_content=<?php echo EH_STRIPE_VERSION;?>" target="_blank" class="eh-button eh-button-go-pro"><?php echo __('Upgrade to Premium','payment-gateway-stripe-and-woocommerce-integration'); ?></a>
            <!--</p>-->
            </div>         
            
        </ul>
    <p style="text-align: center;">
            <ul class="eh_pro_features" style="font-weight: 549; color:#666; list-style: none; background:#F3FAFF; padding:20px;  font-size: 14px; line-height: 26px; border-radius:10px; border-color: #c6e7ef;border-width: 0.5px;border-style: solid;">
                <li class="money-back" style=""><?php echo __('30 Day Money Back Guarantee','payment-gateway-stripe-and-woocommerce-integration'); ?></li>
                <!--<li class="support" style=""><?php //echo __('Fast and Superior Support','payment-gateway-stripe-and-woocommerce-integration'); ?></li>-->

            </ul>


        <br/>
    </p>
    <!--<p style="text-align: center;">
            <a href="https://www.webtoffee.com/category/documentation/stripe-payment-gateway-for-woocommerce/" target="_blank" class="eh-button eh-button-documentation" style=" color: #555 !important;"><?php //echo __('Documentation','payment-gateway-stripe-and-woocommerce-integration'); ?></a>

    </p>-->
</div>

<div class="eh_gopro_block">
    <h3 style="text-align: center;"><?php echo __('Like this plugin?','payment-gateway-stripe-and-woocommerce-integration'); ?></h3>
    <p><?php echo __('If you find this plugin useful please show your support and rate it','payment-gateway-stripe-and-woocommerce-integration'); ?> <a href="http://wordpress.org/support/view/plugin-reviews/payment-gateway-stripe-and-woocommerce-integration" target="_blank" style="color: #ffc600; text-decoration: none;">★★★★★</a><?php echo __(' on','payment-gateway-stripe-and-woocommerce-integration'); ?> <a href="http://wordpress.org/support/view/plugin-reviews/payment-gateway-stripe-and-woocommerce-integration" target="_blank">WordPress.org</a> -<?php echo __('  much appreciated!','payment-gateway-stripe-and-woocommerce-integration'); ?> :)</p>

</div>