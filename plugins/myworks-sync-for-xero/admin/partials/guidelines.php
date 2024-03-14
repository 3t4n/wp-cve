<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;

# Page / Tab Conditions
$page = $MWXS_L->var_g('page');
$tab = $MWXS_L->var_g('tab');

$map_page_p = 'myworks-wc-xero-sync-map';
$push_page_p = 'myworks-wc-xero-sync-push';

$map_page = ($page == $map_page_p)?true:false;
$push_page = ($page == $push_page_p)?true:false;

$customer_map_page = ($map_page && $tab == 'customer')?true:false;
$product_map_page = ($map_page && $tab == 'product')?true:false;
$variation_map_page = ($map_page && $tab == 'variation')?true:false;

$hide_gl_help = (($map_page || $push_page) && empty($tab))?true:false;
if(!$hide_gl_help):

#GL Content
$guidelines_content = __( 'Need help on this? Please contact our support anytime!', 'myworks-sync-for-xero' );

# Map Pages
if($map_page){
	if($customer_map_page){
		$guidelines_content = array(
			__( 'This page allows you to map existing WooCommerce customers to existing Xero customers. Only customers that exist in both systems need to be mapped.', 'myworks-sync-for-xero' ),
			
			__( 'When new customers are created in WooCommerce from this point forward, they will be automatically synced to Xero AND mapped in this page.', 'myworks-sync-for-xero' )
		);
	}
	
	if($product_map_page){
		$guidelines_content = array(
			__( 'This section allows you to map (or link) together products that exist in both systems. If a product exists in WooCommerce, but not in Xero, you would not be able to map it here until you push it to Xero in MyWorks Sync > Push > Products. (Pushing a product will also automatically map it here.)', 'myworks-sync-for-xero' ),
			
			__( 'When new products are created in WooCommerce from this point forward, they will be automatically synced to Xero AND mapped in this page - if you have the Product switch enabled in MyWorks Sync > Settings > Automatic Sync.', 'myworks-sync-for-xero' ),
			
			__( 'We recommend you map as many of your products as you can. Mapping products ensures that orders are accurately synced over and inventory can be accurately synced.', 'myworks-sync-for-xero' ),
			
			__( 'There is no need to map a parent variable product, since a parent variable product itself never actually gets ordered. Only its variations would need to get mapped - in the Variations tab on this page. Since Xero does not directly support variations, variations in WooCommerce can be mapped to Xero products.', 'myworks-sync-for-xero' )	
		);
	}
	
	if($variation_map_page){
		$guidelines_content = array(
			__( 'This section allows you to map (or link) together products that exist in both systems. If a product exists in WooCommerce, but not in Xero, you would not be able to map it here until you push it to Xero in MyWorks Sync > Push > Variations. (Pushing a variation will also automatically map it here.)', 'myworks-sync-for-xero' ),
			
			__( 'When new products are created in WooCommerce from this point forward, they will be automatically synced to Xero AND mapped in this page - if you have the Product switch enabled in MyWorks Sync > Settings > Automatic Sync.', 'myworks-sync-for-xero' ),
			
			__( 'We recommend you map as many of your products as you can. Mapping products ensures that orders are accurately synced over and inventory can be accurately synced.', 'myworks-sync-for-xero' ),
			
			__( 'There is no need to map a parent variable product, since a parent variable product itself never actually gets ordered. Only its variations would need to get mapped - in the Variations tab on this page. Since Xero does not directly support variations, variations in WooCommerce can be mapped to Xero products.', 'myworks-sync-for-xero' )	
		);
	}
	
	if($tab == 'payment-method'){
		$guidelines_content = array(
			__( 'To map & configure WooCommerce payment gateways for when orders are synced to Xero, turn the ‘Enable Payment Syncing’ switch on for each payment method you’d like to sync, and then select a label and a <strong>bank account</strong> for this payment method. This ensures that payments are deposited into the correct Xero bank account when we sync orders over to Xero.', 'myworks-sync-for-xero' ),
			
			__( 'Advanced options are available - click “Show Advanced Options” and hover over the question marks on the right for an explanation of each setting.', 'myworks-sync-for-xero' )
		);
	}
	
	if($tab == 'tax-class'){
		$guidelines_content = array(
			__( 'This page allows you to map WooCommerce tax rules to existing Xero tax rules.
            If a tax rule is not mapped, and an order is placed that includes that tax rule, an error will most likely occur..', 'myworks-sync-for-xero' ),
			
			__( 'If you have more than 100 tax rules, we highly recommend considering an automated tax rule management system - such as <a href="https://docs.myworks.software/woocommerce-sync-for-quickbooks-online/compatibility-addons/avalara-avatax">Avalara</a>. This will greatly reduce the time you spend manually managing your tax rates in WooCommerce.', 'myworks-sync-for-xero' )
		);
	}
}

#Push Pages
if($push_page){
	if($tab == 'customer'){
		$guidelines_content = array(
			__( 'This section allows you to push customers from WooCommerce to Xero. If a customer already exists in Xero, you should map them in MyWorks Sync > Map > Customers.', 'myworks-sync-for-xero' ),
			
			__( 'It isn\'t very common to push customers on this page, as if a customer does not exist in Xero, our integration will automatically create them in Xero the next time they place an order in WooCommerce. Hence, it is perfectly fine to leave customers unsynced on this page if they don\'t exist in Xero.', 'myworks-sync-for-xero' )
		);
	}
	
	if($tab == 'product'){
		$guidelines_content = array(
			__( 'This section allows you to push products from WooCommerce to Xero. If a product already exists in Xero, you should map it - in MyWorks Sync > Map > Products.', 'myworks-sync-for-xero' ),
			
			__( 'Products that have Manage Stock turned on in WooCommerce will be created in Xero as Inventory Products when you push them. It is important to note that the inventory Start Date of these products in Xero will be today\'s date (the day that you push them). ', 'myworks-sync-for-xero' ),
			
			__( 'If a product already exists in Xero, and you simply want to update its inventory level in Xero - then you should visit MyWorks Sync > Push > Inventory Levels. ', 'myworks-sync-for-xero' ),
		);
	}
	
	if($tab == 'variation'){
		$guidelines_content = array(
			__( 'This section allows you to push variations from WooCommerce to Xero. If a product already exists in Xero, you should map it - in MyWorks Sync > Map > Variations.', 'myworks-sync-for-xero' ),
			
			__( 'Since Xero does not directly support variations, variations in WooCommerce will be created in Xero as products - and mapped together.', 'myworks-sync-for-xero' ),
			
			__( 'Variations that have Manage Stock turned on in WooCommerce will be created in Xero as Inventory Products when you push them. It is important to note that the inventory Start Date of these products in Xero will be today\'s date (the day that you push them). ', 'myworks-sync-for-xero' ),
		);
	}
	
	if($tab == 'order'){
		$guidelines_content = array(
			__( 'This section allows you to push existing WooCommerce orders into Xero. New WooCommerce orders will be automatically synced into Xero. If an order already exists in Xero, and you push it here - it will simply be updated in Xero, never duplicated.', 'myworks-sync-for-xero' ),
			
			__( 'If you have orders set to sync to Xero as Invoices (in MyWorks Sync > Settings > Order), note that you must also push over the Payment after you push the Order over - as pushing orders on this page will push the invoice over to Xero. You can push payments in MyWorks Sync > Push > Payments.', 'myworks-sync-for-xero' ),
			
			__( 'We recommend you only push over orders that are completed or processing. If an order is pending payment or cancelled, for example - pushing it to Xero will create it in Xero as an actual order, which would be incorrect.', 'myworks-sync-for-xero' ),
		);
	}
}
?>

<style>
	.wqam_ndc{
		padding: 20px 20px 50px 20px;
	}
	
	.wqam_tbl{
		width:360px;
	}
	
	.wqam_tbl td {
	  padding: 10px 0px 10px 0px;
	}
	.wqam_select{
		width:170px;
		float:none !important;
	}
</style>

<div class="container guide-bg-none">
	<div class="guide-wrap">
		<div class="guide-outer">
            <div class="guidelines">
				<?php if($map_page):?>
				<div class="common-content">
					<?php if($customer_map_page):?>
						<span id="mwqs_automap_customers_msg"></span>						
					<?php endif;?>
					
					<?php if($product_map_page):?>
						<span id="mwqs_automap_products_msg"></span>
					<?php endif;?>
					
					<?php if($variation_map_page):?>
						<span id="mwqs_automap_variations_msg"></span>
					<?php endif;?>
				</div>
				<?php endif;?>
				
				<div class="tab_prdct_sect">
					<ul>
                        <li>
							<span id="gl_tb" class="toggle-btn"><?php _e( 'Guidelines', 'myworks-sync-for-xero' );?> <i class="fa fa-angle-down"></i></span>
						</li>
					</ul>
					
					<div id="guide-target">						
						<?php if(is_array($guidelines_content)):?>
						<?php if(!empty($guidelines_content)):?>
						<?php foreach($guidelines_content as $glc):?>
						<div class="guide">
							<?php echo $MWXS_L->escape($glc);?>
						</div>
						<?php endforeach;?>
						<?php endif;?>
						<?php else:?>
						<div class="guide">
							<?php echo $MWXS_L->escape($guidelines_content);?>
						</div>
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="guide-dropdown-outer guide-responsive">
			<?php if($customer_map_page):?>
			<div class="refresh g-d-o-btn">
				<?php wp_nonce_field( 'myworks_wc_xero_sync_quick_refresh_customers', 'quick_refresh_customers' );?>
				<input type="hidden" id="mwxs_qr_c_tf" value="0">
				<a href="#" id="mwqs_refresh_data_from_qbo" class="glp_rxc">
					<button title="Update customers from xero to local database">Refresh Xero Customers</button>
				</a>
			</div>
			
			<div class="aoutomated-outer g-d-o-btn">
				<div class="col col-m auto-map-btn">
					<span  class="dropbtn col-m-btn">Automap Customers<i class="fa fa-angle-down"></i></span>
					<div class="dropdown-content wqam_ndc">
						<table class="wqam_tbl">
							<tr>
								<td width="50%"><?php _e( 'WooCommerce Field', 'myworks-sync-for-xero' );?> :</td>
								<td>
									<?php wp_nonce_field( 'myworks_wc_xero_sync_automap_customers_wf_xf', 'automap_customers_wf_xf' ); ?>
									<select class="wqam_select" id="cam_wf">
										<option value=""></option>
										<?php $MWXS_L->only_option('',$MWXS_L->wc_customer_automap_fields());?>
									</select>
								</td>
							</tr>
							
							<tr>
								<td><?php _e( 'Xero Field', 'myworks-sync-for-xero' );?> :</td>
								<td>
									<select class="wqam_select" id="cam_qf">
										<option value=""></option>
										<?php $MWXS_L->only_option('',$MWXS_L->xero_customer_automap_fields());?>
									</select>
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<input type="checkbox" id="cam_moum_chk" value="true" checked>
									&nbsp;
									<?php _e( 'Only apply to unmapped customers', 'myworks-sync-for-xero' );?>
								</td>
							</tr>
							
							<tr>								
								<td>								
									<button id="mwqs_automap_customers_wf_qf">Automap</button>
								</td>
								
								<td>
									<span id="cam_wqf_e_msg"></span>
								</td>
							</tr>
						</table>						
					</div>
				</div>
			</div>
			<?php endif;?>
			
			<?php if($product_map_page || $variation_map_page):?>
			<div class="refresh g-d-o-btn">
				<?php wp_nonce_field( 'myworks_wc_xero_sync_quick_refresh_products', 'quick_refresh_products' );?>
				<input type="hidden" id="mwxs_qr_p_tf" value="0">
				<a href="#" id="mwqs_refresh_data_from_qbo"  class="glp_rxp">
					<button title="Update products from xero to local database">Refresh Xero Products</button>
				</a>
			</div>
			<?php endif;?>
			
			<?php if($product_map_page):?>
			<div class="aoutomated-outer g-d-o-btn">
				<div class="col col-m auto-map-btn">
					<span  class="dropbtn col-m-btn">Automap Products<i class="fa fa-angle-down"></i></span>
					<div class="dropdown-content wqam_ndc">
						<table class="wqam_tbl">
							<tr>
								<td width="50%"><?php _e( 'WooCommerce Field', 'myworks-sync-for-xero' );?> :</td>
								<td>
									<?php wp_nonce_field( 'myworks_wc_xero_sync_automap_products_wf_xf', 'automap_products_wf_xf' ); ?>
									<select class="wqam_select" id="pam_wf">
										<option value=""></option>
										<?php $MWXS_L->only_option('',$MWXS_L->wc_product_automap_fields());?>
									</select>
								</td>
							</tr>
							
							<tr>
								<td><?php _e( 'Xero Field', 'myworks-sync-for-xero' );?> :</td>
								<td>
									<select class="wqam_select" id="pam_qf">
										<option value=""></option>
										<?php $MWXS_L->only_option('',$MWXS_L->xero_product_automap_fields());?>
									</select>
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<input type="checkbox" id="pam_moum_chk" value="true" checked>
									&nbsp;
									<?php _e( 'Only apply to unmapped products', 'myworks-sync-for-xero' );?>
								</td>
							</tr>
							
							<tr>								
								<td>								
									<button id="mwqs_automap_products_wf_qf">Automap</button>
								</td>
								
								<td>
									<span id="pam_wqf_e_msg"></span>
								</td>
							</tr>
						</table>						
					</div>
				</div>
			</div>			
			<?php endif;?>
			
			<?php if($variation_map_page):?>
			<div class="aoutomated-outer g-d-o-btn">
				<div class="col col-m auto-map-btn">
					<span  class="dropbtn col-m-btn">Automap Variations<i class="fa fa-angle-down"></i></span>
					<div class="dropdown-content wqam_ndc">
						<table class="wqam_tbl">
							<tr>
								<td width="50%"><?php _e( 'WooCommerce Field', 'myworks-sync-for-xero' );?> :</td>
								<td>
									<?php wp_nonce_field( 'myworks_wc_xero_sync_automap_variations_wf_xf', 'automap_variations_wf_xf' ); ?>
									<select class="wqam_select" id="vam_wf">
										<option value=""></option>
										<?php $MWXS_L->only_option('',$MWXS_L->wc_variation_automap_fields());?>
									</select>
								</td>
							</tr>
							
							<tr>
								<td><?php _e( 'Xero Field', 'myworks-sync-for-xero' );?> :</td>
								<td>
									<select class="wqam_select" id="vam_qf">
										<option value=""></option>
										<?php $MWXS_L->only_option('',$MWXS_L->xero_variation_automap_fields());?>
									</select>
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<input type="checkbox" id="vam_moum_chk" value="true" checked>
									&nbsp;
									<?php _e( 'Only apply to unmapped variations', 'myworks-sync-for-xero' );?>
								</td>
							</tr>
							
							<tr>								
								<td>								
									<button id="mwqs_automap_variations_wf_qf">Automap</button>
								</td>
								
								<td>
									<span id="vam_wqf_e_msg"></span>
								</td>
							</tr>
						</table>						
					</div>
				</div>
			</div>			
			<?php endif;?>
			
			<div class="guide-dropdown" style="position:static">
				<span class="dropbtn">Need Help?  <i class="fa fa-angle-down"></i></span>
				<div class="dropdown-content">
					<ul id="guide-accordion" class="guide-accordion">
						<li>
							<div class="acco-link">Need more help?</div>
							<ul class="guide-submenu">
								<li><a>Our support team is always available to help you!</a></li>
								<li><a target="_blank" href="https://support.myworks.software/hc/en-us/requests/new">Open a Ticket</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			
		</div>		
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#gl_tb').click(function() {
			$('#guide-target').slideToggle('fast');
			$(".toggle-btn").toggleClass("toggle-sub");
		});
		
		$('.guide-accordion').find('li').click(function(){
			if($(this).hasClass('open')){			
				$(this).find('.guide-submenu').slideUp();
				$(this).removeClass('open');
			}else{
				$('.guide-accordion').find('.guide-submenu').slideUp();
				$('.guide-accordion').find('li').removeClass('open');
				$(this).find('.guide-submenu').slideDown();
				$(this).addClass('open');
			}
		});
		
	});
</script>
<?php endif;?>