<?php
defined( 'ABSPATH' ) || exit;

$ga_after_row_text_link = add_query_arg( array(
	'utm_source'   => 'nextmove-lite',
	'utm_campaign' => 'settings',
	'utm_medium'   => 'text-click',
	'utm_term'     => 'javascript-event',
), 'https://xlplugins.com/docs/nextmove-woocommerce-thank-you-page/how-to/add-custom-javascript-events-thank-page/' );

$ga_after_row_text = '<h4>' . __( 'Already set up Facebook Pixel or Google Analytics Events? You <em>don\'t need to</em> check these settings. Just ensure that your Pixels/Events are triggered on NextMove Thank You Page(s).', 'woo-thank-you-page-nextmove-lite' );
$ga_after_row_text .= '<br/><br/>' . __( 'If you want to fire custom javascript events on thank you page, follow this', 'woo-thank-you-page-nextmove-lite' ) . ' <a href="' . $ga_after_row_text_link . '" target="_blank">link</a>.</h4>';

return array(
	array(
		'name'       => __( 'NextMove Mode', 'woo-thank-you-page-nextmove-lite' ),
		'desc'       => __( 'Select Live button to go live. In <strong>Sandbox mode</strong>, only preview works.', 'woo-thank-you-page-nextmove-lite' ),
		'id'         => 'xlwcty_preview_mode',
		'type'       => 'radio_inline',
		'options'    => array(
			'live'    => __( 'Live', 'woo-thank-you-page-nextmove-lite' ),
			'sandbox' => __( 'Sandbox', 'woo-thank-you-page-nextmove-lite' ),
		),
		'before_row' => '<h3>' . __( 'General', 'woo-thank-you-page-nextmove-lite' ) . '</h3>',
	),
	array(
		'name'       => __( 'Left Right Padding (gap)', 'woo-thank-you-page-nextmove-lite' ),
		'id'         => 'wrap_left_right_padding',
		'type'       => 'text',
		'desc'       => __( 'In responsive mode if you find left right padding is required', 'woo-thank-you-page-nextmove-lite' ),
		'attributes' => array(
			'type'    => 'number',
			'min'     => '0',
			'pattern' => '\d*',
		),
	),
	array(
		'name'        => __( 'Allow ThankYou pages on Order Status', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'allowed_order_statuses',
		'type'        => 'xlwcty_post_select',
		'row_classes' => array( 'xlwcty_cmb2_chosen' ),
		'options'     => XLWCTY_Admin_CMB2_Support::get_wc_order_statuses(),
		'desc'        => __( 'Note: If you are receiving payments using PayPal kindly enable "pending payment" in order statuses', 'woo-thank-you-page-nextmove-lite' ),
		'attributes'  => array(
			'multiple'         => 'multiple',
			'name'             => 'allowed_order_statuses[]',
			'data-placeholder' => __( 'Choose Order Status', 'woo-thank-you-page-nextmove-lite' ),
		),
	),
	array(
		'name'       => __( 'Google Map Api Key', 'woo-thank-you-page-nextmove-lite' ),
		'id'         => 'google_map_api',
		'type'       => 'text',
		'desc'       => __( 'To generate API key, <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Click here</a>', 'woo-thank-you-page-nextmove-lite' ),
		'before_row' => '<h3>' . __( 'Google Map', 'woo-thank-you-page-nextmove-lite' ) . '</h3>',
	),
	array(
		'name' => __( 'Google Map Error Text', 'woo-thank-you-page-nextmove-lite' ),
		'id'   => 'google_map_error_txt',
		'type' => 'text',
		'desc' => __( 'This text would be visible when map is unable to load', 'woo-thank-you-page-nextmove-lite' ),
	),
	array(
		'name'        => __( 'Enable Facebook Pixel Tracking', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'enable_fb_ecom_tracking',
		'desc'        => __( 'Check this box if you want to fire Facebook pixels on Thank You page.', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border' ),
		'type'        => 'checkbox',
		'before_row'  => '<h3>' . __( 'eCommerce Tracking Codes', 'woo-thank-you-page-nextmove-lite' ) . '</h3>',
	),
	array(
		'name'        => __( 'Facebook Pixel ID', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'ga_fb_pixel_id',
		'desc'        => __( 'Log into your facebook ads account to find your Pixel ID. <a href="https://www.facebook.com/ads/manager/pixel/facebook_pixel" target="_blank">Click here for more info</a>.', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_pt0' ),
		'before'      => '<p>Facebook Pixel ID</p>',
		'type'        => 'text',
		'attributes'  => array(
			'data-conditional-id'    => 'enable_fb_ecom_tracking',
			'data-conditional-value' => 'on',
		),
	),
	array(
		'name'        => __( 'Fire Facebook PageView event', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'enable_fb_pageview_event',
		'desc'        => __( 'Fire Facebook PageView event', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_pt0' ),
		'type'        => 'checkbox',
		'default'     => '',
		'attributes'  => array(
			'data-conditional-id'    => 'enable_fb_ecom_tracking',
			'data-conditional-value' => 'on',
		),
	),
	array(
		'name'        => __( 'Fire Facebook Purchase event to Add Conversion Values', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'enable_fb_purchase_event_conversion_val',
		'desc'        => __( 'Fire Facebook Purchase event to Add Conversion Values', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_pt0' ),
		'type'        => 'checkbox',
		'default'     => '',
		'attributes'  => array(
			'data-conditional-id'    => 'enable_fb_ecom_tracking',
			'data-conditional-value' => 'on',
		),
		'after'       => __( '<p>Note: NextMove will send total order value and store currency based on order. <a href="https://developers.facebook.com/docs/facebook-pixel/pixel-with-ads/conversion-tracking#add-value" target="_blank">Click here</a> to know more.</p>', 'woo-thank-you-page-nextmove-lite' ),
	),
	array(
		'name'        => __( 'Fire Facebook Purchase event with Order item\'s complete data i.e. product name, category & product_id.', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'enable_fb_purchase_event',
		'desc'        => __( 'Fire Facebook Purchase event with Order item\'s complete data i.e. product name, category & product_id.', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_pt0' ),
		'type'        => 'checkbox',
		'default'     => '',
		'attributes'  => array(
			'data-conditional-id'    => 'enable_fb_ecom_tracking',
			'data-conditional-value' => 'on',
		),
		'after'       => __( '<p>Note: Your Product catalog must be synced with Facebook. <a href="https://developers.facebook.com/docs/marketing-api/dynamic-product-ads/product-catalog" target="_blank">Click here</a> to know more.</p>', 'woo-thank-you-page-nextmove-lite' ),
	),
	array(
		'name'        => __( 'Setup advanced matching with the pixel', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'enable_fb_advanced_matching_event',
		'desc'        => __( 'Setup advanced matching with the pixel', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_pt0' ),
		'type'        => 'checkbox',
		'default'     => '',
		'attributes'  => array(
			'data-conditional-id'    => 'enable_fb_ecom_tracking',
			'data-conditional-value' => 'on',
		),
		'after'       => __( '<p>Note: NextMove will send customer\'s email, name, phone, address fields whichever available in the order. <a href="https://developers.facebook.com/docs/facebook-pixel/pixel-with-ads/conversion-tracking#advanced_match" target="_blank">Click here</a> to know more.</p>', 'woo-thank-you-page-nextmove-lite' ),
	),
	array(
		'name'        => __( 'Enable Google Analytics Tracking', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'enable_ga_ecom_tracking',
		'desc'        => __( 'Check this box if you want to push google analytics eCommerce events on Thank You page. ( Google analytics 4 supported )', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border' ),
		'default'     => '',
		'type'        => 'checkbox',
	),
	array(
		'name'        => __( 'Google Analytics ID', 'woo-thank-you-page-nextmove-lite' ),
		'id'          => 'ga_analytics_id',
		'desc'        => __( 'Log into your google analytics account to <a href="https://support.google.com/analytics/answer/1008080?hl=en#trackingID" target="_blank">find your ID</a>. eg: G-XXXXXXXX.', 'woo-thank-you-page-nextmove-lite' ),
		'row_classes' => array( 'xlwcty_no_border', 'xlwcty_hide_label', 'xlwcty_pt0' ),
		'before'      => '<p>Note: GA4 must be activated in your Google Analytics account. To enable GA4 <a href="https://support.google.com/analytics/answer/9304153?hl=en&ref_topic=9303319" target="_blank">click here for more information.</a></p><p>Google Analytics ID</p>',
		'after_row'   => $ga_after_row_text,
		'type'        => 'text',
		'attributes'  => array(
			'data-conditional-id'    => 'enable_ga_ecom_tracking',
			'data-conditional-value' => 'on',
		),
	),
	array(
		'name'       => '_wpnonce',
		'id'         => '_wpnonce',
		'type'       => 'hidden',
		'attributes' => array(
			'value' => wp_create_nonce( 'woocommerce-settings' ),
		),
	),
);
