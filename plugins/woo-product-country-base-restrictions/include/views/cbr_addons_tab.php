<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $fzpcr; 
$pro_plugins = array(	
	5 => array(
		'title' => 'Advanced Shipment Tracking',
		'description' => 'AST PRO provides powerful features to easily add tracking info to WooCommerce orders, automate the fulfillment workflows and keep your customers happy and informed. AST allows you to easily add tracking and fulfill your orders straight from the Orders page, while editing orders, and allows customers to view the tracking i from the View Order page.',
		'url' => 'https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/?utm_source=wp-admin&utm_medium=CEV&utm_campaign=add-ons',
		'image' => 'ast-icon.png',
		'height' => '45px',
		'file' => 'ast-pro/ast-pro.php'
	),	
	0 => array(
		'title' => 'TrackShip for WooCommerce',
		'description' => 'Take control of your post-shipping workflows, reduce time spent on customer service and provide a superior post-purchase experience to your customers.Beyond automatic shipment tracking, TrackShip brings a branded tracking experience into your store, integrates into your workflow, and takes care of all the touch points with your customers after shipping.',
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
		'file' => 'sales-report-email-pro/sales-report-email-pro.php'
	),		
	3 => array(
		'title' => 'Advanced Local Pickup',
		'description' => 'The Advanced Local Pickup (ALP) helps you manage the local pickup orders workflow more conveniently by extending the WooCommerce Local Pickup shipping method. The Pro you set up multiple pickup locations, split the business hours, apply discounts by pickup location, display local pickup message on the products pages, allow customers to choose pickup location per product, force products to be local pickup only and more…',
		'url' => 'https://www.zorem.com/product/advanced-local-pickup-pro/?utm_source=wp-admin&utm_medium=ALPPRO&utm_campaign=add-ons',
		'image' => 'alp-icon.png',
		'height' => '45px',
		'file' => 'advanced-local-pickup-pro/advanced-local-pickup-pro.php'
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
<section id="cbr_content4" class="cbr_tab_section">
	<div class="d_table addons_page_dtable" style="">
		<section id="content_tab_addons" class="
		<?php
		if ( class_exists( 'Country_Based_Restrictions_PRO_Add_on' ) ) {
			?>
			inner_tab_section
			<?php } ?>"> 
			<div class="addon_inner_section">
				<div class="row">
					<div class="col cbr-features-list cbr-btn">
						<h1 class="plugin_title"><?php echo wp_kses_post('Country Based Restrictions Pro'); ?></h1>
						<ul>
							<li>GeoLocation Detection</li>
							<li>Catalog Visibility</li>
							<li>Product Restrictions</li>
							<li>Debug Mode</li>
							<li>Country Detection Widget</li>
							<li>Catalog Restrictions rules</li>
							<li>Payment Gateway by Country</li>
							<li>Bulk import Restrictions</li>
						</ul>
						<a href="https://www.zorem.com/product/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBRPRO&utm_campaign=add-ons" class="install-now button-primary pro-btn" target="blank">UPGRADE NOW</a>	
					</div>
					<div class="col cbr-pro-image">
						<img src="<?php echo esc_url($fzpcr->plugin_dir_url() . 'assets/images/addon-banner.jpg'); ?>" width="100%">
					</div>
				</div>
			</div>
			<div class="section-content">
				<div class="plugins_section free_plugin_section">
					<?php foreach ($pro_plugins as $Plugin) { ?>
						<div class="single_plugin">
							<div class="free_plugin_inner">
								<div class="plugin_image">
									<img src="<?php echo esc_url($fzpcr->plugin_dir_url() . 'assets/images/' . $Plugin['image']); ?>" height="<?php echo esc_html($Plugin['height']); ?>">
									<h3 class="plugin_title"><?php echo esc_html($Plugin['title']); ?></h3>
								</div>
								<div class="plugin_description cbr-btn">

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
