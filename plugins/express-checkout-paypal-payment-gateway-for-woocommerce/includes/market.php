<style>
	.box14{
		width: 30%;
		margin-top:2px;
		min-height: 310px;
		margin-right: 10px;
		position:absolute;
		z-index:1;
		background: -webkit-gradient(linear, 0% 20%, 0% 92%, from(#fff), to(#f3f3f3), color-stop(.1,#fff));
	}
	
	.eh-button-go-pro {
		box-shadow: none;
		border: 0;
		text-shadow: none;
		padding: 10px 15px;
		height: auto;
		font-size: 16px;
		border-radius: 4px;
		font-weight: 600;
		background: #6ABE45;
		margin-top: 20px;
		text-decoration: none;
	}

	.eh-button {
		margin-bottom: 20px;
		color: #fff;
		padding-left: 40px;
	}
	.eh-button:hover, .eh-button:visited {
		color: #fff;
	}
	.eh_gopro_block{ background: #fff; padding: 15px;}
	.eh_gopro_block h3{ text-align: center; }
	.eh_premium_features{ padding: 20px; font-weight: 600; font-size: 16px;}
	.eh_premium_features li{ padding-left:15px; padding-right: 15px; font-weight: 500; font-size: 14px; line-height: 19px; padding-top: 10px; }
	.eh_premium_features li::before {
		background-image: url(<?php echo esc_url(EH_PAYPAL_MAIN_URL.'assets/img/green-tick.svg'); ?>);
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
	    background-repeat: no-repeat;
	}
	.eh-button-documentation{
		border: 0;
		background: #d8d8dc;
		box-shadow: none;
		padding: 10px 52px;
		font-size: 15px;
		height: auto;
		margin-left: 10px;
		margin-right: 10px;
		margin-top: 10px;
		border-radius: 3px;
		text-decoration: none;
		border: 2px solid #6ABE45;
		background-color: #ffff;
	}
	.table-box-main {
		box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
		transition: all 0.3s cubic-bezier(.25,.8,.25,1);
	}

	.table-box-main:hover {
		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
	}

	.eh_premium_upgrade_head {
	    font-weight: 600;
	    font-size: 17px;
	    line-height: 25px;
	    color: #000000;
	    margin: 10px 45px 20px 45px;
	    padding: 10px;
	}

	.money-back, .support {
		display: flex;
	}

	.eh-button-go-pro:before{
	  content: '';
	  position: absolute;
	  height: 15px;
	  width: 18px;
	  background-image: url(<?php echo esc_url(EH_PAYPAL_MAIN_URL.'assets/img/white-crown.svg'); ?>);
	  background-size: contain;
	  background-repeat: no-repeat;
	  background-position: center;
	  margin: 0px -30px;
	  padding: 4px;
	}

	a.eh-button.eh-button-documentation {
		color: #6ABE45;
		font-size: 20px;
		font-weight: 700;
	}	
</style>

<div class="box14 table-box-main">
<div class="eh_gopro_block">
    <div class="eh_premium_upgrade">
        <div style="display: flex;justify-content: center;">
            <img src="<?php echo esc_url(EH_PAYPAL_MAIN_URL.'assets/img/crown.svg'); ?>" >

        </div>
        <div class="eh_premium_upgrade_head"><center><?php esc_html_e( 'Get access to advanced features of PayPal express', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></center></div>

        
    </div>
		<div class="eh_pro_features" style="font-weight: 800;display: flex; background:#E2F2FF;padding: 20px 10px;margin: 10px 5px 10px;font-size: 12px;">
			<div class="money-back" style=""><img src="<?php echo esc_url(EH_PAYPAL_MAIN_URL.'assets/img/money-back.svg'); ?>" alt="money back badge" height="36" width="36"><?php echo esc_html__( '30 Day Money Back Guarantee', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></div>

			<div class="support" style=""><img src="<?php echo esc_url(EH_PAYPAL_MAIN_URL.'assets/img/support.svg'); ?>" alt="money back badge" height="36" width="36"><?php echo esc_html__( 'Fast and Superior Support', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></div>

		</div>
			<div style="padding-top:10px;">
			   <p style="text-align: center;">
			   <?php
					$href_attr = 'https://www.webtoffee.com/product/paypal-express-checkout-gateway-for-woocommerce/?utm_source=free_plugin_sidebar&utm_medium=Paypal_basic&utm_campaign=Paypal&utm_content=' . EH_PAYPAL_VERSION;

				?>
				<a href="<?php print( esc_attr( $href_attr ) ); ?>" target="_blank" class="eh-button eh-button-go-pro"><?php echo esc_html__( 'Upgrade to Premium', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></a>
				</p>
			</div>
			<ul class="eh_premium_features"><?php echo esc_html__('Premium Features', 'express-checkout-paypal-payment-gateway-for-woocommerce'); 
			?><li><?php esc_html_e( 'Adds PayPal Smart Button Checkout option on individual Product Page.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>
			<li><?php esc_html_e( 'Accepts payment using multiple Alternative Payment Method (APM) based on country or device.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>
			<li><?php esc_html_e( 'Adds Express PayPal Checkout Option on Product Page and Mini-cart for Faster Checkout.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Capture the authorized payment later.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Partial and Full Refund the order amount directly from Order Admin Page.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Lots of Customization Options like Button Style, Position, Etc.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Option to enable In-Context checkout, to keep customers inside your store while checkout process.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li> 
				<li><?php esc_html_e( 'Supports WooCommerce Subscriptions for Express buttons.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>   
				<li><?php esc_html_e( 'Payment gateway that allow users to pay with their credit card without leaving the site(Guest Checkout).', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>   
				<li><?php esc_html_e( 'Option to set up a specific PayPal locale.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>   
				<li><?php esc_html_e( 'Shortcode support for Paypal Express button.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>     
				<li><?php esc_html_e( 'Timely compatibility updates and bug fixes.', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>
				<li><?php esc_html_e( 'Premium support!', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></li>     
				
		</ul>

	<p style="text-align: center;">
		<a href="https://www.webtoffee.com/category/documentation/paypal-express-checkout-payment-gateway-for-woocommerce/" target="_blank" class="eh-button eh-button-documentation" ><?php echo esc_html__( 'Documentation', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></a>
	</p>
</div>

<div class="eh_gopro_block">
	<h3 style="text-align: center; font-size: 20px; font-weight:500 "><?php echo esc_html__( 'Like this plugin?', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?></h3>
	<p><?php echo esc_html__( 'If you find this plugin useful please show your support and rate it', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?> <a href="https://wordpress.org/support/plugin/express-checkout-paypal-payment-gateway-for-woocommerce/reviews/" target="_blank" style="color: #ffc600; text-decoration: none;">★★★★★</a><?php echo esc_html__( ' on', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?> <a href="https://wordpress.org/support/plugin/express-checkout-paypal-payment-gateway-for-woocommerce/" target="_blank">WordPress.org</a> -<?php echo esc_html__( '  much appreciated!', 'express-checkout-paypal-payment-gateway-for-woocommerce' ); ?> :)</p>

</div>
</div>

<?php
if ( is_rtl() ) {
	?>
	<style type="text/css"> .box14 { left:0px;float:left; }</style>
	<?php
} else {
	?>
	<style type="text/css"> .box14 { right:0px;float:right; }</style>
	<?php
}

