<?php
$pro_plugins = array(
	5 => array(
		'title' => 'Advanced Shipment Tracking',
		'description' => 'AST PRO provides powerful features to easily add tracking info to WooCommerce orders, automate the fulfillment workflows and keep your customers happy and informed.',
		'url' => 'https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/?utm_source=wp-admin&utm_medium=CEV&utm_campaign=add-ons',
		'image' => 'ast-icon.png',
		'height' => '45px',
		'file' => 'ast-pro/ast-pro.php'
	),
	0 => array(
		'title' => 'TrackShip for WooCommerce',
		'description' => 'Auto-Track all your shipments and provide a superior Post-Purchase Experience to your Customers',
		'url' => 'https://wordpress.org/plugins/trackship-for-woocommerce/?utm_source=wp-admin&utm_medium=ts4wc&utm_campaign=add-ons',
		'image' => 'trackship-logo.png',
		'height' => '45px',
		'file' => 'trackship-for-woocommerce/trackship-for-woocommerce.php'
	),
	1 => array(
		'title' => 'SMS for WooCommerce',
		'description' => 'Keep your customers informed by sending them automated SMS text messages with order & delivery updates. You can send SMS notifications to customers when the order status is updated or when the shipment is out for delivery and more…',
		'url' => 'https://www.zorem.com/product/sms-for-woocommerce/?utm_source=wp-admin&utm_medium=SMSWOO&utm_campaign=add-ons',
		'image' => 'smswoo-icon.png',
		'height' => '45px',
		'file' => 'sms-for-woocommerce/sms-for-woocommerce.php'
	),
	2 => array(
		'title' => 'Sales Report Email',
		'description' => 'The Sales Report Email Pro will help know how well your store is performing and how your products are selling by sending you a daily, weekly, or monthly sales report by email, directly from your WooCommerce store.',
		'url' => 'https://www.zorem.com/product/sales-report-email-pro/?utm_source=wp-admin&utm_medium=SRE&utm_campaign=add-ons',
		'image' => 'sre-icon.png',
		'height' => '45px',
		'file' => 'sales-report-email-pro-addon/sales-report-email-pro-addon.php'
	),		
	3 => array(
		'title' => 'Country Based Restrictions',
		'description' => 'The country-based restrictions plugin by zorem works by the WooCommerce Geolocation or the shipping country added by the customer and allows you to restrict products on your store to sell or not to sell to specific countries.',
		'url' => 'https://www.zorem.com/products/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBR&utm_campaign=add-ons',
		'image' => 'cbr-icon.png',
		'height' => '45px',
		'file' => 'country-based-restriction-pro-addon/country-based-restriction-pro-addon.php'
	),
	4 => array(
		'title' => 'Customer Email Verification',
		'description' => 'The Customer Email Verification helps you to reduce registration spam and fake orders by requiring customers to verify their email address when they register an account or before they can place an order on your store.',
		'url' => 'https://woocommerce.com/products/customer-email-verification/',
		'image' => 'cev-icon.png',
		'height' => '45px',
		'file' => 'customer-email-verification-pro/customer-email-verification-pro.php'
	),	
);
?>
<section id="wclp_content4" class="wclp_tab_section">
	<div class="d_table addons_page_dtable" style="">
		<section id="content_tab_addons" class="">
			<div class="addon_inner_section">
				<div class="row">
					<div class="col alp-features-list wclp-btn">
						<h1 class="plugin_title"><?php echo wp_kses_post('Advanced Local Pickup PRO'); ?></h1>
						<a href="https://www.zorem.com/product/advanced-local-pickup-for-woocommerce/?utm_source=wp-admin&utm_medium=ALPPRO&utm_campaign=add-ons" class="install-now button-primary pro-btn" target="blank">Starting From $10.75 / month </a>
						<ul>
							<li>Display pickup instructions during checkout</li>
							<li>Notify Your Customers When Their Order is Available For Pickup</li>
							<li>Split the Work Hours</li>
							<li>Multiple Pickup Locations</li>
							<li>Local pickup Appointments</li>
							<li>Show Local pickup availability messages</li>
							<li>Force Local Pickup</li>
							<li>Allow local pickup and other shipping methods on the same order</li>
							<li>Apply a discount or a fee for Local Pickup orders</li>
						</ul>
					</div>
					<div class="col alp-pro-image">
						<img src="<?php echo esc_url(wc_local_pickup()->plugin_dir_url() . 'assets/images/addon-banner.jpg'); ?>" width="100%" height="400px">
					</div>
				</div>
			</div>
			<div class="section-content wclp_tab_inner_container">
				<div class="plugins_section free_plugin_section">
					<?php foreach ($pro_plugins as $Plugin) { ?>
						<div class="single_plugin">
							<div class="free_plugin_inner">
								<div class="plugin_image">
									<img src="<?php echo esc_url(wc_local_pickup()->plugin_dir_url() . 'assets/images/' . $Plugin['image']); ?>" height="<?php echo esc_html($Plugin['height']); ?>">
									<h3 class="plugin_title"><?php echo esc_html($Plugin['title']); ?></h3>
								</div>
								<div class="plugin_description wclp-btn">

									<p><?php echo esc_html($Plugin['description']); ?></p>
									<?php if ( is_plugin_active( $Plugin['file'] ) ) { ?>
										<button type="button" class="button button-disabled" disabled="disabled">Installed</button>
									<?php } else { ?>
										<a href="<?php echo esc_url($Plugin['url']); ?>" class="button install-now button-primary" target="blank">more info</a>
									<?php } ?>								
								</div>
							</div>	
						</div>	
					<?php } ?>						
				</div>
			</div>	
		</section>		
	</div>
</section>
