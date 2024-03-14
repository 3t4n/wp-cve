<?php

?>
<style>
	.woocommerce-save-button{
		display: none !important;
	}
	.box14{
		width: 100%;
		margin-top:2px;
		min-height: 310px;
		margin-right: 400px;
		padding:10px;
		z-index:1;
		right:0px;
		float:left;
		background: -webkit-gradient(linear, 0% 20%, 0% 92%, from(#fff), to(#f3f3f3), color-stop(.1,#fff));
		border: 1px solid #ccc;
		-webkit-border-radius: 60px 5px;
		-webkit-box-shadow: 0px 0px 35px rgba(0, 0, 0, 0.1) inset;
	}
	.box14_ribbon{
		position:absolute;
		top:0; right: 0;
		width: 130px;
		height: 40px;
		background: -webkit-gradient(linear, 555% 20%, 0% 92%, from(rgba(0, 0, 0, 0.1)), to(rgba(0, 0, 0, 0.0)), color-stop(.1,rgba(0, 0, 0, 0.2)));
		border-left: 1px dashed rgba(0, 0, 0, 0.1);
		border-right: 1px dashed rgba(0, 0, 0, 0.1);
		-webkit-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.2);
		-webkit-transform: rotate(6deg) skew(0,0) translate(-60%,-5px);
	}
	.box14 h3
	{
		text-align:center;
		margin:2px;
	}
	.box14 p
	{
		text-align:center;
		margin:2px;
		border-width:1px;
		border-style:solid;
		padding:5px;
		border-color: rgb(204, 204, 204);
	}
	.box14 .feature-container
	{
		text-align:center;
		margin:2px;
		border-width:1px;
		border-style:solid;
		padding:5px;
		border-color: rgb(204, 204, 204);
	}
	.box14 span
	{
		background:#fff;
		padding:5px;
		display:block;
		box-shadow:green 0px 3px inset;
		margin-top:10px;
	}
	.box14 img {
		margin-top: 5px;
	}
	.table-box-main {
		box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
		transition: all 0.3s cubic-bezier(.25,.8,.25,1);
	}

	.table-box-main:hover {
		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
	}
	span ul li{
		margin:4px;
	}
	.marketing_logos{
	width: 300px;
	height: 300px;
	border-radius: 10px;
	}
	.marketing_redirect_links{
		padding: 0px 2px !important;
		background-color: #0d6efd;
		color: white  !important;
		height: 52px;
		font-weight: 600 !important;
		font-size: 18px !important;
		min-width: 210px;
		border-radius: 5px;
	}
</style>

	<div class="elex_dp_wrapper">
		<center style="margin-top: 20px;">
			<div class="panel panel-default" style="margin: 20px;">
				<div style="background-color: #0d6efd;" class="panel-heading">
					<h3 style="color: white;" class="panel-title"><?php esc_html_e( 'ELEXtensions Plugins You May Be Interested In' ); ?></h3>
				</div> 
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<div class="row">
								<div class="col-md-12">
									<img src="<?php echo esc_url( MINIMUM_ORDER_MAIN_PATH . 'assests/img/dhl.png' ); ?>" class="marketing_logos">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h5><a href="https://elextensions.com/plugin/woocommerce-dhl-express-ecommerce-paket-shipping-plugin-with-print-label/" data-wpel-link="internal" target="_blank">ELEX WooCommerce DHL Express / eCommerce / Paket Shipping Plugin with Print Label</a></h5>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<div class="col-md-12">
									<img src="<?php echo esc_url( MINIMUM_ORDER_MAIN_PATH . 'assests/img/easy_post.png' ); ?>" class="marketing_logos">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h5><a href="https://elextensions.com/plugin/easypost-shipping-method-plugin-for-woocommerce/" data-wpel-link="internal" target="_blank">ELEX EasyPost (FedEx, UPS & USPS) Shipping & Label Printing Plugin for WooCommerce</a></h5>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<div class="col-md-12">
									<img src="<?php echo esc_url( MINIMUM_ORDER_MAIN_PATH . 'assests/img/australia_post.png' ); ?>" class="marketing_logos">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h5><a href="https://elextensions.com/plugin/woocommerce-australia-post-shipping-plugin-with-print-label-tracking/" data-wpel-link="internal" target="_blank">ELEX WooCommerce Australia Post Shipping Plugin with Print Label & Tracking</a></h5>
								</div>
							</div>
						</div>


						<div class="col-md-4">
							<div class="row">
								<div class="col-md-12">
									<img src="<?php echo esc_url( MINIMUM_ORDER_MAIN_PATH . 'assests/img/bulk_edit.png' ); ?>" class="marketing_logos">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h5><a href="https://elextensions.com/plugin/bulk-edit-products-prices-attributes-for-woocommerce/" data-wpel-link="internal" target="_blank">ELEX WooCommerce Bulk Edit Products, Prices & Attributes</a></h5>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<div class="col-md-12">
									<img src="<?php echo esc_url( MINIMUM_ORDER_MAIN_PATH . 'assests/img/dynamic_pricing.png' ); ?>" class="marketing_logos">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h5><a href="https://elextensions.com/plugin/dynamic-pricing-and-discounts-plugin-for-woocommerce/" data-wpel-link="internal" target="_blank">ELEX Dynamic Pricing and Discounts Plugin for WooCommerce</a></h5>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<div class="col-md-12">
									<img src="<?php echo esc_url( MINIMUM_ORDER_MAIN_PATH . 'assests/img/wsdesk.png' ); ?>" class="marketing_logos">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<h5><a href="https://elextensions.com/plugin/wsdesk-wordpress-support-desk-plugin/" data-wpel-link="internal" target="_blank">WSDesk – ELEX WordPress Helpdesk Plugin</a></h5>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<a href="https://elextensions.com/products/?filter_product_cat=plugins" target="_blank">
								<button class="marketing_redirect_links">
									Browse All ELEXtensions Plugins
								</button>
							</a>						
						</div>
					</div>
				</div>   
			</div>
		</center>
	</div>
</div>
<script>
	jQuery(document).ready(function () {
		jQuery("button[name='save']").hide();
	});     
</script>
