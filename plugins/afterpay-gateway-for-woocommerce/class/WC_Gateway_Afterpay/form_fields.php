<?php
/**
* Default values for the WooCommerce Afterpay Plugin Admin Form Fields
*/

$environments = include 'environments.php';

return array(
	'core-configuration-title' => array(
		'title'				=> __( 'Core Configuration', 'woo_afterpay' ),
		'type'				=> 'title'
	),
	'enabled' => array(
		'title'				=> __( 'Enable/Disable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'label'				=> __( 'Enable Afterpay', 'woo_afterpay' ),
		'default'			=> 'yes'
	),
	'title' => array(
		'title'				=> __( 'Title', 'woo_afterpay' ),
		'type'				=> 'text',
		'description'		=> __( 'This controls the payment method title which the user sees during checkout.', 'woo_afterpay' ),
		'default'			=> __( 'Afterpay', 'woo_afterpay' )
	),
	'testmode' => array(
		'title'				=> __( 'API Environment', 'woo_afterpay' ),
		'type'				=> 'select',
		'options'			=> wp_list_pluck( $environments, 'name' ),
		'default'			=> 'production',
		'description'		=> __( 'Note: Sandbox and Production API credentials are not interchangeable.', 'woo_afterpay' )
	),
	'prod-id' => array(
		'title'				=> __( 'Merchant ID (Production)', 'woo_afterpay' ),
		'type'				=> 'text',
		'custom_attributes'	=>	array(
			'environment-field' => 'production'
		),
		'default'			=> ''
	),
	'prod-secret-key' => array(
		'title'				=> __( 'Secret Key (Production)', 'woo_afterpay' ),
		'type'				=> 'password',
		'custom_attributes'	=>	array(
			'environment-field' => 'production'
		),
		'default'			=> ''
	),
	'prod-mpid' => array(
		'title'				=> __( 'Merchant Public ID (Production)', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
			'readonly' => 'true',
			'environment-field' => 'production'
		),
		'default'			=> ''
	),
	'test-id' => array(
		'title'				=> __( 'Merchant ID (Sandbox)', 'woo_afterpay' ),
		'type'				=> 'text',
		'custom_attributes'	=>	array(
			'environment-field' => 'sandbox'
		),
		'default'			=> ''
	),
	'test-secret-key' => array(
		'title'				=> __( 'Secret Key (Sandbox)', 'woo_afterpay' ),
		'type'				=> 'password',
		'custom_attributes'	=>	array(
			'environment-field' => 'sandbox'
		),
		'default'			=> ''
	),
	'test-mpid' => array(
		'title'				=> __( 'Merchant Public ID (Sandbox)', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
			'readonly' => 'true',
			'environment-field' => 'sandbox'
		),
		'default'			=> ''
	),
	'pay-over-time-limit-min' => array(
		'title'				=> __( 'Minimum Payment Amount', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
									'readonly' => 'true'
								),
		'default'			=> ''
	),
	'pay-over-time-limit-max' => array(
		'title'				=> __( 'Maximum Payment Amount', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
									'readonly' => 'true'
								),
		'default'			=> ''
	),
	'settlement-currency' => array(
		'title'				=> __( 'Settlement Currency', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
									'readonly' => 'true'
								),
		'default'			=> ''
	),
	'trading-country' => array(
		'title'				=> __( 'Merchant Country', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
									'readonly' => 'true'
								),
		'default'			=> ''
	),
	'excluded-categories' => array(
		'title'				=> __( 'Excluded Categories', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'Enter slugs (separated by comma) of ineligible product categories.', 'woo_afterpay' ),
		'default'			=> ''
	),
	'cross-border-trade-title' => array(
		'title'				=> __( 'Cross Border Trade Configuration', 'woo_afterpay' ),
		'type'				=> 'title'
	),
	'enable-multicurrency' => array(
		'title'				=> __( 'Enable Multicurrency', 'woo_afterpay' ),
		'label'				=> __( 'Enable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'description'	=> __( 'Enable Afterpay in user selected currency where applicable. Important: To avoid misleading information, this requires your site to be configured to allow consumers to pay in their selected currency.', 'woo_afterpay' ),
		'default'			=> 'no'
	),
	'cbt-countries' => array(
		'title'				=> __( 'CBT Countries', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'Cross Border Trade (CBT) allows you to sell internationally, with consumers in foreign countries paying in their local currencies, while Afterpay continues to settle with you in your local currency. This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
									'readonly' => 'true'
								),
		'default'			=> ''
	),
	'cbt-limits' => array(
		'title'				=> __( 'CBT Limits', 'woo_afterpay' ),
		'type'				=> 'input',
		'description'		=> __( 'This information is supplied by Afterpay and cannot be edited.', 'woo_afterpay' ),
		'custom_attributes'	=>	array(
									'readonly' => 'true'
								),
		'default'			=> ''
	),
	'express-checkout-title' => array(
		'title'				=> __( 'Express Checkout Configuration', 'woo_afterpay' ),
		'type'				=> 'title'
	),
	'show-express-on-cart-page' => array(
		'title'				=> __( 'Enable on Cart Page', 'woo_afterpay' ),
		'label'				=> __( 'Enable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'description'	=> __( 'Display Afterpay Express Checkout element on the cart page', 'woo_afterpay' ),
		'default'			=> 'yes'
	),
	'express-button-theme' => array(
		'title'				=> __( 'Cart Page: Express Button Theme', 'woo_afterpay' ),
		'type'				=> 'select',
		'default'			=> 'black-on-mint',
		'options' 		=> array(
			'black-on-mint' 	=> 'Black on Mint',
			'white-on-black'	=> 'White on Black'
 		)
	),
	'presentational-customisation-title' => array(
		'title'				=> __( 'Customisation', 'woo_afterpay' ),
		'type'				=> 'title',
		'description'		=> __( 'Please feel free to customise the presentation of the Afterpay elements below to suit the individual needs of your web store.</p><p><em>Note: Advanced customisations may require the assistance of your web development team. <a id="reset-to-default-link" style="cursor:pointer;text-decoration:underline;">Restore Defaults</a></em>', 'woo_afterpay' )
	),
	'show-info-on-category-pages' => array(
		'title'				=> __( 'Payment Info on Category Pages', 'woo_afterpay' ),
		'label'				=> __( 'Enable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'description'		=> __( 'Enable to display Afterpay elements on category pages', 'woo_afterpay' ),
		'default'			=> 'yes'
	),
	'category-pages-placement-attributes' => array(
		'type'				=> 'textarea',
		'default'			=> 'data-show-interest-free="false" data-show-upper-limit="true" data-show-lower-limit="true" data-logo-type="compact-badge" data-badge-theme="black-on-mint" data-size="sm" data-modal-link-style="none"',
		'description'		=> __( 'Refer to <a href="https://developers.afterpay.com/docs/api/aiude690ui329-advanced-configuration" target="_blank">Advanced Configuration</a> for styling the message.', 'woo_afterpay' )
	),
	'category-pages-hook' => array(
		'type'				=> 'text',
		'placeholder'		=> 'Enter hook name (e.g. woocommerce_after_shop_loop_item_title)',
		'default'			=> 'woocommerce_after_shop_loop_item_title',
		'description'		=> __( 'Set the hook to be used for Payment Info on Category Pages.', 'woo_afterpay' )
	),
	'category-pages-priority' => array(
		'type'				=> 'number',
		'placeholder'		=> 'Enter a priority number',
		'default'			=> 99,
		'description'		=> __( 'Set the hook priority to be used for Payment Info on Category Pages.', 'woo_afterpay' )
	),
	'category-pages-info-text' => array(
		'type'				=> 'hidden'
	),
	'show-info-on-product-pages' => array(
		'title'				=> __( 'Payment Info on Individual Product Pages', 'woo_afterpay' ),
		'label'				=> __( 'Enable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'description'		=> __( 'Enable to display Afterpay elements on individual product pages', 'woo_afterpay' ),
		'default'			=> 'yes'
	),
	'product-pages-placement-attributes' => array(
		'type'				=> 'textarea',
		'default'			=> 'data-show-upper-limit="true" data-show-lower-limit="true" data-logo-type="badge" data-badge-theme="black-on-mint" data-size="md" data-modal-theme="mint"',
		'description'		=> __( 'Refer to <a href="https://developers.afterpay.com/docs/api/aiude690ui329-advanced-configuration" target="_blank">Advanced Configuration</a> for styling the message.', 'woo_afterpay' )
	),
	'product-pages-hook' => array(
		'type'				=> 'text',
		'placeholder'		=> 'Enter hook name (e.g. woocommerce_single_product_summary)',
		'default'			=> 'woocommerce_single_product_summary',
		'description'		=> __( 'Set the hook to be used for Payment Info on Individual Product Pages.', 'woo_afterpay' )
	),
	'product-pages-priority' => array(
		'type'				=> 'number',
		'placeholder'		=> 'Enter a priority number',
		'default'			=> 10,
		'description'		=> __( 'Set the hook priority to be used for Payment Info on Individual Product Pages.', 'woo_afterpay' )
	),
	'product-pages-shortcode' => array(
		'type'				=> 'hidden',
		'description'		=> __( '<h3 class="wc-settings-sub-title">Page Builders</h3> If you use a page builder plugin, the above payment info can be placed using a shortcode instead of relying on hooks. Use [afterpay_paragraph] within a product page, or include the product ID to display the info for a specific product on any custom page. E.g.: [afterpay_paragraph id="99"]', 'woo_afterpay' )
	),
	'product-pages-info-text' => array(
		'type'				=> 'hidden'
	),
	'show-info-on-product-variant' => array(
		'title'				=> __( 'Payment Info Display for Product Variant', 'woo_afterpay' ),
		'label'				=> __( 'Enable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'description'		=> __( 'Enable to display Afterpay elements upon product variant selection', 'woo_afterpay' ),
		'default'			=> 'no'
	),
	'product-variant-placement-attributes' => array(
		'type'				=> 'textarea',
		'default'			=> 'data-show-upper-limit="true" data-show-lower-limit="true" data-logo-type="badge" data-badge-theme="black-on-mint" data-size="md" data-modal-theme="mint"',
		'description'		=> __( 'Refer to <a href="https://developers.afterpay.com/docs/api/aiude690ui329-advanced-configuration" target="_blank">Advanced Configuration</a> for styling the message.', 'woo_afterpay' )
	),
	'show-outside-limit-on-product-page' => array(
		'title'				=> __( 'Outside Payment Limit Info on Product Page', 'woo_afterpay' ),
		'label'				=> __( 'Enable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'description'		=> __( 'Enable to display Outside Payment Limits Text on the product page', 'woo_afterpay' ),
		'default'			=> 'yes'
	),
	'product-variant-info-text' => array(
		'type'				=> 'hidden'
	),
	'show-info-on-cart-page' => array(
		'title'				=> __( 'Payment Info on Cart Page', 'woo_afterpay' ),
		'label'				=> __( 'Enable', 'woo_afterpay' ),
		'type'				=> 'checkbox',
		'description'		=> __( 'Enable to display Afterpay elements on the cart page', 'woo_afterpay' ),
		'default'			=> 'yes'
	),
	'cart-page-placement-attributes' => array(
		'type'				=> 'textarea',
		'default'			=> 'data-show-upper-limit="true" data-show-lower-limit="true" data-logo-type="badge" data-badge-theme="black-on-mint" data-size="md" data-modal-theme="mint"',
		'description'		=> __( 'Refer to <a href="https://developers.afterpay.com/docs/api/aiude690ui329-advanced-configuration" target="_blank">Advanced Configuration</a> for styling the message.', 'woo_afterpay' )
	),
	'cart-page-info-text' => array(
		'type'				=> 'hidden'
	),
	'afterpay-checkout-experience' => array(
		'type'				=> 'hidden',
		'default'			=> 'redirect',
	),
	'ei-configs' => array(
		'type'				=> 'hidden'
	),
);
