<?php
function cwmp_extensions_check_checkout (){
	if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		echo "<div class='notice cwmp-notice error'><p>
		".__( '<strong>WP Masters Checkout:</strong> you must use the <strong>WooCommerce</strong> plugin.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if ( is_plugin_active( 'checkout-field-editor-and-manager-for-woocommerce/start.php' ) ) {
		echo "<div class='notice cwmp-notice error'><p>
		".__( 'The <strong>Checkout Field Editor and Manager for WooCommerce</strong> plugin is incompatible with Checkout Mestres WP.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if ( is_plugin_active( 'flexible-checkout-fields/flexible-checkout-fields.php' ) ) {
		echo "<div class='notice cwmp-notice error'><p>
		".__( 'The <strong>Flexible Checkout Fields for WooCommerce</strong> plugin is incompatible with Checkout Mestres WP.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if ( is_plugin_active( 'woo-checkout-field-editor-pro/checkout-form-designer.php' ) ) {
		echo "<div class='notice cwmp-notice error'><p>
		".__( 'The <strong>Checkout Field Editor (Checkout Manager) for WooCommerce</strong> plugin is incompatible with Checkout Mestres WP.', 'checkout-mestres-wp')."
		</p></div>";
	}
	if ( get_option('woocommerce_ship_to_destination')!="billing_only" ) {
		echo "<div class='notice  error'><p>
		".__( 'You must activate the option <strong>Force delivery to the customer`s billing address</strong> for <strong>Checkout Mestres WP</strong> to work correctly. <a href="/wp-admin/admin.php?page=wc-settings&tab=shipping&section=options">Click here</a>', 'checkout-mestres-wp')."
		</p></div>";
	}
	if(get_option('cwmp_license_cwmwp_active')!=true){
		echo "
		<div class='notice cwmp-notice'>
			<p>".__( 'Get the pro version of <strong>Checkout Mestres WP</strong> now.', 'checkout-mestres-wp')."</p><a href='https://www.mestresdowp.com.br/produto/chechout-mestres-do-wp/' target='blank'>".__( 'Buy right now', 'checkout-mestres-wp')."</a>
		</div>
		";
	}
}
add_action('admin_notices', 'cwmp_extensions_check_checkout',999);
 
 



add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets',9999999999);
  
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
 
wp_add_dashboard_widget('custom_help_widget', 'Mestres do WP', 'custom_dashboard_help','side','high');
}
 
function custom_dashboard_help() {
	$cwmp_banners_arquivo = 'https://www.mestresdowp.com.br/checkout/banners.php';
	$cwmp_banner_xml = wp_remote_get($cwmp_banners_arquivo, array(
		'method' => 'POST'
	));

	$cwmp_banner_xml = json_decode(wp_remote_retrieve_body($cwmp_banner_xml));

	foreach ($cwmp_banner_xml as $cwmp_banner) {
		echo "<a href='".$cwmp_banner->url."' target='blank'><img src='".utf8_decode($cwmp_banner->imagem)."' width='100%' /></a>";
	}
}