<?php
/**
 * States
 *
 * Returns an array of country states. This deprecates and replaces the /states/ directory found in older versions.
 * States should be defined in English and translated native through localisation files.
 * Country codes and states (or province) names should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/country-names).
 * Countries defined with empty arrays have no states.
 *
 *
 */

defined( 'ABSPATH' ) || exit;

return apply_filters( 'bwf_settings_config', array(

	'general'               => array(
		'title'    => __( 'License', 'woofunnels' ),
		'heading'  => __( 'License', 'woofunnels' ),
		'slug'     => 'general',
		'fields'   => apply_filters( 'bwf_settings_config_general', array(
			array(
				'key'           => 'default_selected_builder',
				'type'          => 'select',
				'label'         => 'Default Page Builder',
				'hint'          => '',
				'values'        => [
					[ 'id' => 'elementor', 'name' => __( 'Elementor', 'woofunnels' ) ],
					[ 'id' => 'divi', 'name' => __( 'Divi', 'woofunnels' ) ],
					[ 'id' => 'customizer', 'name' => __( 'Customizer', 'woofunnels' ) ],
					[ 'id' => 'wp_editor', 'name' => __( 'Other', 'woofunnels' ) ],
				],
				'selectOptions' => [
					'hideNoneSelectedText' => true,
				],
			),
			array(

				'key'   => 'set_funnel_as_home',
				'type'  => 'html',
				'label' => __( 'Set Funnel as Homepage', 'funnel-builder' ),
				'hint'  => '',
				'html' => __( 'Select the Funnel page you want to set as the Homepage.  <a href="' . admin_url( 'options-reading.php' ) . '">Go to Wordpress Settings</a>', 'funnel-builder' ),
			),
		) ),
		'priority' => 1,
	),
	'permalinks'            => array(
		'title'    => __( 'Permalinks', 'woofunnels' ),
		'heading'  => __( 'Permalinks', 'woofunnels' ),
		'slug'     => 'permalinks',
		'fields'   => array(),
		'priority' => 5,
	),
	'funnelkit_google_maps' => array(
		'title'    => __( 'Google Maps', 'woofunnels' ),
		'heading'  => __( 'Google Maps', 'woofunnels' ),
		'slug'     => 'funnelkit_google_maps',
		'fields'   => array(
			array(
				'key'   => 'funnelkit_google_map_key',
				'type'  => 'text',
				'label' => 'Google Map API Key',
				'hint'  => __( 'Enter the Google Maps API key for Google Address AutoComplete on Checkout.', 'funnel-builder' ),
				'value' => '',
			)
		),
		'priority' => 5,
	),
	'facebook_pixel'        => array(
		'title'    => __( 'Facebook Pixel', 'woofunnels' ),
		'heading'  => __( 'Facebook Pixel', 'woofunnels' ),
		'slug'     => 'facebook_pixel',
		'fields'   => array(
			array(
				'key'         => 'fb_pixel_key',
				'label'       => __( 'Pixel ID', 'woofunnels' ),
				'type'        => 'input',
				'placeholder' => __( '294123501257422', 'woofunnels' ),
				'hint'        => __( 'Log into your Facebook ads account to find your Pixel ID. <a target="_blank" href="https://www.facebook.com/ads/manager/pixel/facebook_pixel/">Learn More</a>', 'woofunnels' ),
			),

			array(
				'key'    => 'is_fb_purchase_conversion_api',
				'type'   => 'checklist',
				'label'  => '',
				'hint'   => __( 'Send events directly from server to Facebook through the Conversion API. An access token is required to use the server-side API. <a target="_blank" href="https://funnelkit.com/docs/funnel-builder/global-settings/facebook-conversion-api/">Generate Access Token</a>', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable Conversion API', 'woofunnels' ),
						'value' => 'yes',
					),
				),

			),
			array(
				'key'         => 'conversion_api_access_token',
				'type'        => 'textArea',
				'label'       => '',
				'placeholder' => __( 'EAAJkZC3UfZBwBAJm8ZAqFmZAjOZA4eZBZAfdZAkZCZA3hCZAjBZCpZA5ZAjVZBmZA0ZBZAaZAfLZA2eZA5ZBZBZCZA4ZAuVZB4ZAfPZAejZAjMeZAjHZCZAfLZAkZAjZAZCZA1ZAjBZA3ZCZAeZAf9ZAkZAgZDZD', 'woofunnels' ),
				'toggler'     => array(
					'key'   => 'is_fb_purchase_conversion_api',
					'value' => array( 'yes' ),
				),
			),


			array(
				'key'   => 'is_fb_conv_enable_test',
				'label' => __( '', 'woofunnels' ),
				'hint'  => __( 'Use test_event_code to verify server-side events. <strong>Uncheck this option after testing</strong>.', 'woofunnels' ),

				'type'    => "checklist",
				'values'  => array(
					array(
						'name'  => __( 'Test server events via test_event_code', 'woofunnels' ),
						'value' => 'yes',
					),
				),
				'toggler' => array(
					'key'   => 'is_fb_purchase_conversion_api',
					'value' => array( 'yes' ),
				),
			),


			array(
				'key'         => 'conversion_api_test_event_code',
				'type'        => 'input',
				'label'       => '',
				'hint'        => __( '<a target="_blank" href="https://funnelkit.com/docs/funnel-builder/global-settings/facebook-conversion-api/#step-1-select-your-pixel-id-and-go-to-%E2%80%9Ctest-events%E2%80%9D">Learn how to get test_event_code</a>', 'woofunnels' ),
				'placeholder' => __( 'Paste your test_event_code here', 'woofunnels' ),
				'toggler'     => array(
					'key'   => 'is_fb_conv_enable_test',
					'value' => array( 'yes' ),
				),
			),
			array(
				'key'     => 'is_fb_conversion_api_log',
				'type'    => 'checklist',
				'label'   => '',
				'hint'    => __( 'Use this option to log API request & response. <strong>Uncheck this option after testing</strong>.  <a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs' ) ) . '">Click here to access logs.</a>', 'woofunnels' ),
				'values'  => array(
					array(
						'name'  => __( 'Enable Purchase Event Logs', 'woofunnels' ),
						'value' => 'yes',
					),
				),
				'toggler' => array(
					'key'   => 'is_fb_purchase_conversion_api',
					'value' => array( 'yes' ),
				),
			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Site Wide Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_fb_page_view_global',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'type'         => "checkbox",
				'hint'         => __( 'Use this option to fire PageView event on all site pages', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),
			array(
				'key'   => 'is_fb_add_to_cart_global',
				'label' => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire AddtoCart event when customer add items to the cart', 'woofunnels' ),
			),
			array(
				'key'   => 'is_fb_page_product_content_global',
				'label' => __( 'Enable ViewContent Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire ViewContent event on product pages', 'woofunnels' ),
			),
			array(
				'key'    => 'is_fb_page_view_lp',
				'label'  => __( 'Sales Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_fb_page_view_op',
				'label'  => __( 'Optin Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_fb_lead_op',
				'label'  => __( '', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable Lead Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Order Bump Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),


			array(
				'key'          => 'is_fb_add_to_cart_bump',
				'type'         => 'checkbox',
				'label'        => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'hint'         => __( 'Fire "AddToCart" event when user accepts the order bump', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),
			array(
				'key'   => 'is_fb_custom_bump',
				'type'  => 'checkbox',
				'label' => __( 'Enable Order Bump Conversion Event', 'woofunnels' ),
				'hint'  => __( 'Fire "Woofunnels_Bump" custom event when user accepts the order bump', 'woofunnels' ),

			),

			array(
				'key'          => 'label_section_head_fb',
				'type'         => "label",
				'label'        => __( 'Checkout Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'pixel_is_page_view',
				'type'         => 'checkbox',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end' ],
			),
			array(
				'key'   => 'pixel_initiate_checkout_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable InitiateCheckout Event', 'woofunnels' ),

			),
			array(
				'key'   => 'pixel_add_to_cart_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable AddtoCart Event', 'woofunnels' ),

			),
			array(
				'key'   => 'pixel_add_payment_info_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable AddPaymentInfo Event', 'woofunnels' ),

			),
			array(
				'key'    => 'is_fb_purchase_page_view',
				'type'   => 'checklist',
				'label'  => __( 'Purchase Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( "Enable PageView Event", 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_fb_purchase_event',
				'type'   => 'checklist',
				'label'  => '',
				'hint'   => __( 'Note: FunnelKit will send total order value and store currency based on order. <a target="_blank" href="https://developers.facebook.com/docs/facebook-pixel/pixel-with-ads/conversion-tracking#add-value">Learn More</a>', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable Purchase Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


			array(
				'key'    => 'enable_general_event',
				'type'   => 'checklist',
				'label'  => '',
				'hint'   => __( 'Use the GeneralEvent for your Custom Audiences and Custom Conversions.', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable General Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),

			),
			array(
				'type'        => 'input',
				'key'         => 'general_event_name',
				'label'       => '',
				'placeholder' => __( 'General Event Name', 'woofunnels' ),
				'hint'        => __( 'Customize the name of general event.', 'woofunnels' ),
				'toggler'     => array(
					'key'   => 'enable_general_event',
					'value' => array( 'yes' ),
				),
			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Track Steps', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'   => 'is_fb_custom_events',
				'label' => __( 'Enable Custom Funnel Step Event', 'woofunnels' ),
				'hint'  => __( 'Fire "Woofunnels_Sales", "Woofunnels_Checkout", "Woofunnels_Upsell", "Woofunnels_Downsell", "Woofunnels_Thankyou", "Woofunnels_Optin" & "Woofunnels_OptinConfirmation" respectively when user visits the page.', 'woofunnels' ),

				'type'         => "checkbox",
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Advanced', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_fb_enable_content',
				'type'         => 'checklist',
				'label'        => '',
				'hint'         => __( 'Note: Your Product catalog must be synced with Facebook. <a target="_blank" href="https://developers.facebook.com/docs/facebook-pixel/get-started/dynamic-ads">Learn More</a>', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width', 'rem_hint_pad' ],
				'values'       => array(
					array(
						'name'  => __( 'Enable Content Settings for Dynamic Ads', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'          => 'pixel_variable_as_simple',
				'type'         => 'checkbox',
				'label'        => __( 'Treat variable products like simple products', 'woofunnels' ),
				'hint'         => __( 'Turn this option ON when your Product Catalog doesn\'t include the variants for variable products.', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'bwf_vue_checkbox_label' ],
				'toggler'      => array(
					'key'   => 'is_fb_enable_content',
					'value' => array( 'yes' ),
				),
			),
			array(
				'key'           => 'pixel_content_id_type',
				'styleClasses'  => [ 'group-one-class' ],
				'type'          => 'select',
				'label'         => '',
				'default'       => '0',
				'values'        => [
					[ 'id' => '0', 'name' => __( 'Select content id parameter', 'woofunnels' ) ],
					[ 'id' => 'product_id', 'name' => __( 'Product ID', 'woofunnels' ) ],
					[ 'id' => 'product_sku', 'name' => __( 'Product SKU', 'woofunnels' ) ],
				],
				'selectOptions' => [
					'hideNoneSelectedText' => true,
				],
				'toggler'       => array(
					'key'   => 'is_fb_enable_content',
					'value' => array( 'yes' ),
				),
			),
			array(
				'key'         => 'pixel_content_id_prefix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content ID Prefix', 'woofunnels' ),
				'hint'        => __( 'Add prefix to the content_id parameter (optional)', 'woofunnels' ),
				'toggler'     => array(
					'key'   => 'is_fb_enable_content',
					'value' => array( 'yes' ),
				),

			),
			array(
				'key'         => 'pixel_content_id_suffix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content id suffix', 'woofunnels' ),
				'hint'        => __( 'Add suffix to the content_id parameter (optional)', 'woofunnels' ),
				'toggler'     => array(
					'key'   => 'is_fb_enable_content',
					'value' => array( 'yes' ),
				),

			),
			array(
				'key'     => 'exclude_from_total',
				'label'   => '',
				'type'    => 'checklist',
				'hint'    => __( 'Check above boxes to exclude shipping/taxes from the total.', 'woofunnels' ),
				'values'  => array(
					array(
						'name'  => __( 'Exclude Shipping from Total', 'woofunnels' ),
						'value' => 'is_disable_shipping',
					),
					array(
						'name'  => __( 'Exclude Taxes from Total', 'woofunnels' ),
						'value' => 'is_disable_taxes',
					),

				),
				'toggler' => array(
					'key'   => 'is_fb_purchase_event',
					'value' => array( 'yes' ),
				),
			),

			array(
				'type'   => 'checklist',
				'key'    => 'is_fb_advanced_event',
				'label'  => '',
				'hint'   => __( 'Note: FunnelKit will send customer\'s email, name, phone, address fields whichever available in the order. <a target="_blank" href="https://developers.facebook.com/docs/facebook-pixel/pixel-with-ads/conversion-tracking#advanced_match">Learn More', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable Advanced Matching With the Pixel', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


		),
		'priority' => 10,
	),
	'google_analytics'      => array(
		'title'    => __( 'Google Analytics', 'woofunnels' ),
		'heading'  => __( 'Google Analytics', 'woofunnels' ),
		'slug'     => 'google_analytics',
		'fields'   => array(
			array(
				'key'         => 'ga_key',
				'type'        => 'input',
				'label'       => __( 'Analytics ID', 'woofunnels' ),
				'placeholder' => __( 'G-9F3K2TGHH4', 'woofunnels' ),
				'hint'        => __( 'Log into your Google Analytics account to find your Analytics ID. <a target="_blank" href="https://support.google.com/analytics/answer/10269537">Learn More</a>', 'woofunnels' ),
			),
			array(
				'key'    => 'is_ga4_tracking',
				'type'   => 'checklist',
				'label'  => __( 'Analytics 4', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable Analytics 4', 'woofunnels' ),
						'value' => 'yes',
					),
				),
				'hint'   => __( 'Note: GA4 must be activated in your Google Analytics account. To enable GA4 <a target="_blank" href="https://support.google.com/analytics/answer/9304153?hl=en&ref_topic=9303319">Learn More</a>', 'woofunnels' ),

			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Site Wide Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_ga_page_view_global',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'type'         => "checkbox",
				'hint'         => __( 'Use this option to fire PageView event on all site pages', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),

			array(
				'key'   => 'is_ga_add_to_cart_global',
				'label' => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire AddtoCart event when customer add items to the cart', 'woofunnels' ),
			),
			array(
				'key'   => 'is_ga_view_item_global',
				'label' => __( 'Enable ViewItem Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire ViewItem event on product pages', 'woofunnels' ),
			),

			array(
				'key'    => 'is_ga_page_view_lp',
				'type'   => 'checklist',
				'label'  => __( 'Sales Page Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'type'   => 'checklist',
				'key'    => 'is_ga_page_view_op',
				'label'  => __( 'Optin Page Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_ga_lead_op',
				'label'  => __( '', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable Lead Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Order Bump Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),


			array(
				'key'          => 'is_ga_add_to_cart_bump',
				'type'         => 'checkbox',
				'label'        => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'hint'         => __( 'This event will fire when user accepts the order bump', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),
			array(
				'key'   => 'is_ga_custom_bump',
				'type'  => 'checkbox',
				'label' => __( 'Enable Order Bump Conversion Event', 'woofunnels' ),
				'hint'  => __( 'Fire "Woofunnels_Bump" custom event when user accepts the order bump', 'woofunnels' ),

			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Checkout Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'google_ua_is_page_view',
				'type'         => 'checkbox',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),
			array(
				'type'  => 'checkbox',
				'key'   => 'google_ua_add_to_cart_event',
				'label' => __( 'Enable AddtoCart Event', 'woofunnels' ),
			),
			array(
				'type'  => 'checkbox',
				'key'   => 'google_ua_initiate_checkout_event',
				'label' => __( 'Enable BeginCheckout Event', 'woofunnels' ),
			),
			array(
				'type'  => 'checkbox',
				'key'   => 'google_ua_add_payment_info_event',
				'label' => __( 'Enable AddPaymentInfo Event', 'woofunnels' ),
			),


			array(
				'key'    => 'is_ga_purchase_page_view',
				'type'   => 'checklist',
				'label'  => __( 'Purchase Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( "Enable PageView Event", 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_ga_purchase_event',
				'type'   => 'checklist',
				'label'  => '',
				'values' => array(
					array(
						'name'  => __( 'Enable Purchase Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'type'         => 'label',
				'key'          => 'label_section_head_tr',
				'label'        => __( 'Track Steps', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_ga_custom_events',
				'label'        => __( 'Enable Custom Funnel Step Event', 'woofunnels' ),
				'hint'         => __( 'Fire "Woofunnels_Sales", "Woofunnels_Checkout", "Woofunnels_Upsell", "Woofunnels_Downsell", "Woofunnels_Thankyou", "Woofunnels_Optin" & "Woofunnels_OptinConfirmation" respectively when user visits the page.', 'woofunnels' ),
				'type'         => "checkbox",
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_adv',
				'label'        => __( 'Advanced', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'google_ua_variable_as_simple',
				'type'         => 'checkbox',
				'label'        => __( 'Treat variable products like simple products', 'woofunnels' ),
				'hint'         => __( 'Turn this option ON when your Product Catalog doesn\'t include the variants for variable products.', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width', 'rem_hint_pad' ],
			),
			array(
				'key'           => 'google_ua_content_id_type',
				'styleClasses'  => [ 'group-one-class' ],
				'type'          => 'select',
				'label'         => '',
				'default'       => '0',
				'values'        => [
					[ 'id' => '0', 'name' => __( 'Select content id parameter', 'woofunnels' ) ],
					[ 'id' => 'product_id', 'name' => __( 'Product ID', 'woofunnels' ) ],
					[ 'id' => 'product_sku', 'name' => __( 'Product SKU', 'woofunnels' ) ],
				],
				'selectOptions' => [
					'hideNoneSelectedText' => true,
				],
			),
			array(
				'key'         => 'google_ua_content_id_prefix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content ID Prefix', 'woofunnels' ),
				'hint'        => __( 'Add prefix to the content_id parameter (optional)', 'woofunnels' ),

			),
			array(
				'key'         => 'google_ua_content_id_suffix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content ID Suffix', 'woofunnels' ),
				'hint'        => __( 'Add suffix to the content_id parameter (optional)', 'woofunnels' ),

			),
			array(
				'key'    => 'ga_exclude_from_total',
				'label'  => '',
				'type'   => 'checklist',
				'hint'   => __( 'Check above boxes to exclude shipping/taxes from the total.', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Exclude Shipping from Total', 'woofunnels' ),
						'value' => 'is_disable_shipping',
					),
					array(
						'name'  => __( 'Exclude Taxes from Total', 'woofunnels' ),
						'value' => 'is_disable_taxes',
					),

				),

			),

		),
		'priority' => 15,
	),
	'google_ads'            => array(

		'title'    => __( 'Google Ads', 'woofunnels' ),
		'heading'  => __( 'Google Ads', 'woofunnels' ),
		'slug'     => 'google_ads',
		'fields'   => array(
			array(
				'key'         => 'gad_key',
				'type'        => 'input',
				'label'       => __( 'Conversion ID', 'woofunnels' ),
				'placeholder' => __( 'AW-837491263', 'woofunnels' ),
				'hint'        => __( 'Log into your Google Ads account to find your Conversion ID. <a target="_blank" href="https://funnelkit.com/docs/funnel-builder/global-settings/google-ads/#step-1-go-to-your-google-ads-account">Learn More</a>', 'woofunnels' ),
			),

			array(
				'key'         => 'gad_conversion_label',
				'type'        => 'input',
				'label'       => __( 'Conversion Label', 'woofunnels' ),
				'placeholder' => __( 'LkP4CIKm59UBEP7n3t4C', 'woofunnels' ),
				'hint'        => __( 'Log into your Google Ads account to find your conversion label. <a target="_blank" href="https://funnelkit.com/docs/funnel-builder/global-settings/google-ads/#step-4-set-up-the-conversion-for-your-google-ads-account">Learn More</a>', 'woofunnels' ),

			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Site Wide Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_gad_page_view_global',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'type'         => "checkbox",
				'hint'         => __( 'Use this option to fire PageView event on all site pages', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),
			array(
				'key'   => 'is_gad_add_to_cart_global',
				'label' => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire AddtoCart event when customer add items to the cart', 'woofunnels' ),
			),
			array(
				'key'   => 'is_gad_view_item_global',
				'label' => __( 'Enable ViewItem Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire ViewItem event on product pages', 'woofunnels' ),
			),
			array(
				'key'    => 'is_gad_page_view_lp',
				'label'  => __( 'Sales Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_gad_page_view_op',
				'label'  => __( 'Optin Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_gad_lead_op',
				'label'  => __( '', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable Lead Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Order Bump Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),


			array(
				'key'          => 'is_gad_add_to_cart_bump',
				'type'         => 'checkbox',
				'label'        => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'hint'         => __( 'This event will fire when user accepts the order bump', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),
			array(
				'key'   => 'is_gad_custom_bump',
				'type'  => 'checkbox',
				'label' => __( 'Enable Order Bump Conversion Event', 'woofunnels' ),
				'hint'  => __( 'Fire "Woofunnels_Bump" custom event when user accepts the order bump', 'woofunnels' ),

			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_gad',
				'label'        => __( 'Checkout Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),

			array(
				'key'          => 'google_ads_is_page_view',
				'type'         => 'checkbox',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],

			),
			array(
				'key'   => 'google_ads_add_to_cart_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable AddToCart Event', 'woofunnels' ),

			),
			array(
				'key'    => 'is_gad_pageview_event',
				'type'   => 'checklist',
				'label'  => __( 'Purchase Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_gad_purchase_event',
				'type'   => 'checklist',
				'label'  => __( '', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable Conversion Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Track Steps', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'   => 'is_gad_custom_events',
				'label' => __( 'Enable Custom Funnel Step Event', 'woofunnels' ),
				'hint'  => __( 'Fire "Woofunnels_Sales", "Woofunnels_Checkout", "Woofunnels_Upsell", "Woofunnels_Downsell", "Woofunnels_Thankyou", "Woofunnels_Optin" & "Woofunnels_OptinConfirmation" respectively when user visits the page.', 'woofunnels' ),

				'type'         => "checkbox",
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Advanced', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'google_ads_variable_as_simple',
				'type'         => 'checkbox',
				'label'        => __( 'Treat variable products like simple products', 'woofunnels' ),
				'hint'         => __( 'Turn this option ON when your Product Catalog doesn\'t include the variants for variable products.', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width', 'rem_hint_pad' ],
			),
			array(
				'key'           => 'google_ads_content_id_type',
				'styleClasses'  => [ 'group-one-class' ],
				'type'          => 'select',
				'label'         => '',
				'default'       => '0',
				'values'        => [
					[ 'id' => '0', 'name' => __( 'Select content id parameter', 'woofunnels' ) ],
					[ 'id' => 'product_id', 'name' => __( 'Product ID', 'woofunnels' ) ],
					[ 'id' => 'product_sku', 'name' => __( 'Product SKU', 'woofunnels' ) ],
				],
				'selectOptions' => [
					'hideNoneSelectedText' => true,
				],
			),
			array(
				'key'         => 'google_ads_content_id_prefix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content ID Prefix', 'woofunnels' ),
				'hint'        => __( 'Add prefix to the content_id parameter (optional)', 'woofunnels' ),

			),
			array(
				'key'         => 'google_ads_content_id_suffix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content ID Suffix', 'woofunnels' ),
				'hint'        => __( 'Add suffix to the content_id parameter (optional)', 'woofunnels' ),

			),
			array(
				'key'    => 'gad_exclude_from_total',
				'label'  => '',
				'type'   => 'checklist',
				'hint'   => __( 'Check above boxes to exclude shipping/taxes from the total.', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Exclude Shipping from Total', 'woofunnels' ),
						'value' => 'is_disable_shipping',
					),
					array(
						'name'  => __( 'Exclude Taxes from Total', 'woofunnels' ),
						'value' => 'is_disable_taxes',
					),

				),

			),


		),
		'priority' => 20,
	),
	'pinterest'             => array(
		'title'   => __( 'Pinterest', 'woofunnels' ),
		'heading' => __( 'Pinterest', 'woofunnels' ),
		'slug'    => 'pinterest',
		'fields'  => array(
			array(
				'type'        => 'input',
				'key'         => 'pint_key',
				'label'       => __( 'Tag ID', 'woofunnels' ),
				'placeholder' => __( '2614535298742', 'woofunnels' ),
				'hint'        => __( 'Log into your Pinterest Ads Manager Account and find Pixel ID. <a target="_blank" href="https://funnelkit.com/docs/funnel-builder/global-settings/pinterest/">Learn More</a>', 'woofunnels' )
			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Site Wide Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_pint_page_view_global',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'type'         => "checkbox",
				'hint'         => __( 'Use this option to fire PageView event on all site pages', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),
			array(
				'key'   => 'is_pint_page_visit_global',
				'label' => __( 'Enable PageVisit Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire PageVisit event on product pages', 'woofunnels' ),
			),
			array(
				'key'   => 'is_pint_add_to_cart_global',
				'label' => __( 'Enable AddToCart Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire AddtoCart event when customer add items to the cart', 'woofunnels' ),
			),

			array(
				'key'    => 'is_pint_page_view_lp',
				'label'  => __( 'Sales Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),

			array(
				'key'    => 'is_pint_page_view_op',
				'label'  => __( 'Optin Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),

			array(
				'key'    => 'is_pint_lead_op',
				'label'  => __( '', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable Lead Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Order Bump Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),


			array(
				'key'          => 'is_pint_add_to_cart_bump',
				'type'         => 'checkbox',
				'label'        => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'hint'         => __( 'This event will fire when user accepts the order bump', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),
			array(
				'key'   => 'is_pint_custom_bump',
				'type'  => 'checkbox',
				'label' => __( 'Enable Order Bump Conversion Event', 'woofunnels' ),
				'hint'  => __( 'Fire "Woofunnels_Bump" custom event when user accepts the order bump', 'woofunnels' ),

			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_pint',
				'label'        => __( 'Checkout Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),

			array(
				'key'          => 'pint_is_page_view',
				'type'         => 'checkbox',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),

			array(
				'key'   => 'pint_add_to_cart_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable AddToCart Event', 'woofunnels' ),

			),
			array(
				'key'   => 'pint_initiate_checkout_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable InitiateCheckout Event', 'woofunnels' ),

			),
			array(
				'key'    => 'is_pint_pageview_event',
				'type'   => 'checklist',
				'label'  => __( 'Purchase Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_pint_purchase_event',
				'type'   => 'checklist',
				'label'  => __( '', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable Purchase Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Track Steps', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'   => 'is_pint_custom_events',
				'label' => __( 'Enable Custom Funnel Step Event', 'woofunnels' ),
				'hint'  => __( 'Fire "Woofunnels_Sales", "Woofunnels_Checkout", "Woofunnels_Upsell", "Woofunnels_Downsell", "Woofunnels_Thankyou", "Woofunnels_Optin", "Woofunnels_OptinConfirmation" respectively when user visits the page.', 'woofunnels' ),

				'type'         => "checkbox",
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Advanced', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'pint_variable_as_simple',
				'type'         => 'checkbox',
				'label'        => __( 'Treat variable products like simple products', 'woofunnels' ),
				'hint'         => __( 'Turn this option ON when your Product Catalog doesn\'t include the variants for variable products.', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width', 'rem_hint_pad' ],
			),
			array(
				'key'           => 'pint_content_id_type',
				'styleClasses'  => [ 'group-one-class' ],
				'type'          => 'select',
				'label'         => '',
				'default'       => '0',
				'values'        => [
					[ 'id' => '0', 'name' => __( 'Select content id parameter', 'woofunnels' ) ],
					[ 'id' => 'product_id', 'name' => __( 'Product ID', 'woofunnels' ) ],
					[ 'id' => 'product_sku', 'name' => __( 'Product SKU', 'woofunnels' ) ],
				],
				'selectOptions' => [
					'hideNoneSelectedText' => true,
				],
			),
			array(
				'key'         => 'pint_content_id_prefix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content ID Prefix', 'woofunnels' ),
				'hint'        => __( 'Add prefix to the content_id parameter (optional)', 'woofunnels' ),

			),
			array(
				'key'         => 'pint_content_id_suffix',
				'type'        => 'input',
				'label'       => '',
				'placeholder' => __( 'Content ID Suffix', 'woofunnels' ),
				'hint'        => __( 'Add suffix to the content_id parameter (optional)', 'woofunnels' ),

			),
			array(
				'key'    => 'pint_exclude_from_total',
				'label'  => '',
				'type'   => 'checklist',
				'hint'   => __( 'Check above boxes to exclude shipping/taxes from the total.', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Exclude Shipping from Total', 'woofunnels' ),
						'value' => 'is_disable_shipping',
					),
					array(
						'name'  => __( 'Exclude Taxes from Total', 'woofunnels' ),
						'value' => 'is_disable_taxes',
					),

				),

			),
		),


		'priority' => 25,
	),
	'tiktok'                => array(

		'title'    => __( 'TikTok', 'woofunnels' ),
		'heading'  => __( 'TikTok', 'woofunnels' ),
		'slug'     => 'tiktok',
		'fields'   => array(
			array(
				'key'         => 'tiktok_pixel',
				'type'        => 'input',
				'label'       => __( 'TikTok ID', 'woofunnels' ),
				'placeholder' => __( 'D4L9N62FLPENAMTU4HG9', 'woofunnels' ),
				'hint'        => __( 'Log into your Tiktok Business Account and find TikTok ID. <a target="_blank" href="https://funnelkit.com/docs/funnel-builder/global-settings/tiktok/">Learn More</a>', 'woofunnels' )

			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Site Wide Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_tiktok_page_view_global',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'type'         => "checkbox",
				'hint'         => __( 'Use this option to fire PageView event on all site pages', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),
			array(
				'key'   => 'is_tiktok_add_to_cart_global',
				'label' => __( 'Enable AddToCart Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire AddtoCart event when customer add items to the cart', 'woofunnels' ),
			),
			array(
				'key'   => 'is_tiktok_page_product_content_global',
				'label' => __( 'Enable ViewContent Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire ViewContent event on product pages', 'woofunnels' ),
			),
			array(
				'key'    => 'is_tiktok_page_view_lp',
				'label'  => __( 'Sales Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_tiktok_page_view_op',
				'label'  => __( 'Optin Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Order Bump Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),


			array(
				'key'          => 'is_tiktok_add_to_cart_bump',
				'type'         => 'checkbox',
				'label'        => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'hint'         => __( 'Fire this event when user accepts the order bump', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_tiktok',
				'label'        => __( 'Checkout Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),

			array(
				'key'          => 'tiktok_is_page_view',
				'type'         => 'checkbox',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),

			array(
				'key'   => 'tiktok_add_to_cart_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable AddToCart Event', 'woofunnels' ),
			),
			array(
				//google_ua_add_to_cart_event
				'key'   => 'tiktok_initiate_checkout_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable InitiateCheckout Event', 'woofunnels' ),

			),
			array(
				'key'    => 'is_tiktok_pageview_event',
				'type'   => 'checklist',
				'label'  => __( 'Purchase Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_tiktok_purchase_event',
				'type'   => 'checklist',
				'label'  => __( '', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable PlaceOrder Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_tiktok_complete_payment_event',
				'type'   => 'checklist',
				'label'  => __( '', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable CompletePayment Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
		),
		'priority' => 30,
	),
	'snapchat'              => array(

		'title'    => __( 'Snapchat', 'woofunnels' ),
		'heading'  => __( 'Snapchat', 'woofunnels' ),
		'slug'     => 'snapchat',
		'fields'   => array(
			array(
				'key'         => 'snapchat_pixel',
				'type'        => 'input',
				'label'       => __( 'Pixel ID', 'woofunnels' ),
				'placeholder' => __( '7fc81b23-8e1c-4fde-b2bf-e3f67a19d2fa', 'woofunnels' ),
				'hint'        => __( 'Log into your Snapchat Business Account and find Pixel ID. <a target="_blank" href="https://funnelkit.com/docs/funnel-builder/global-settings/snapchat/">Learn More</a>', 'woofunnels' )

			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Site Wide Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'is_snapchat_page_view_global',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'type'         => "checkbox",
				'hint'         => __( 'Use this option to fire PageView event on all site pages', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad' ],
			),
			array(
				'key'   => 'is_snapchat_add_to_cart_global',
				'label' => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'type'  => "checkbox",
				'hint'  => __( 'Use this option to fire AddtoCart event when customer add items to the cart', 'woofunnels' ),
			),
			array(
				'key'    => 'is_snapchat_page_view_lp',
				'label'  => __( 'Sales Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
			array(
				'key'    => 'is_snapchat_page_view_op',
				'label'  => __( 'Optin Page Events', 'woofunnels' ),
				'type'   => "checklist",
				'values' => array(
					array(
						'name'  => __( 'Enable PageView Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),


			array(
				'type'         => 'label',
				'key'          => 'label_section_head_ga',
				'label'        => __( 'Order Bump Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),


			array(
				'key'          => 'is_snapchat_add_to_cart_bump',
				'type'         => 'checkbox',
				'label'        => __( 'Enable AddtoCart Event', 'woofunnels' ),
				'hint'         => __( 'Fire this event when user accepts the order bump', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],
			),

			array(
				'type'         => 'label',
				'key'          => 'label_section_head_snapchat',
				'label'        => __( 'Checkout Events', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),

			array(
				'key'          => 'snapchat_is_page_view',
				'type'         => 'checkbox',
				'label'        => __( 'Enable PageView Event', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end', 'bwf_remove_lft_pad', 'bwf_clear_rgt_width' ],

			),
			array(
				'key'   => 'snapchat_add_to_cart_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable AddToCart Event', 'woofunnels' ),
			),
			array(
				'key'   => 'snapchat_initiate_checkout_event',
				'type'  => 'checkbox',
				'label' => __( 'Enable InitiateCheckout Event', 'woofunnels' ),

			),
			array(
				'key'    => 'is_snapchat_purchase_event',
				'type'   => 'checklist',
				'label'  => __( 'Purchase Events', 'woofunnels' ),
				'values' => array(
					array(
						'name'  => __( 'Enable Purchase Event', 'woofunnels' ),
						'value' => 'yes',
					),
				),
			),
		),
		'priority' => 30,
	),
	'utm_parameter'         => array(
		'title'    => __( 'First Party Tracking', 'woofunnels' ),
		'heading'  => __( 'First Party Tracking', 'woofunnels' ),
		'slug'     => 'utm_parameter',
		'fields'   => array(
			array(
				'type'         => 'label',
				'key'          => 'label_section_head_fb',
				'label'        => __( 'Conversion Tracking', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'track_utms',
				'type'         => 'upgrade_pro',
				'label'        => __( 'In the lite version, you manage UTM tracking with third-party tools. In the Pro version, we store UTM information and provide real-time analytics.', 'funnel-builder' ),
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end' ],
				'hint'         => '',
			),

		),
		'priority' => 5,
	),
	'fk_stripe_gateway'     => array(
		'title'    => __( 'Activate Stripe', 'woofunnels' ),
		'heading'  => __( 'Activate Stripe', 'woofunnels' ),
		'slug'     => 'fk_stripe_gateway',
		'fields'   => array(
			array(
				'type'         => 'label',
				'key'          => 'label_fk_stripe',
				'label'        => __( 'Activate Stripe', 'woofunnels' ),
				'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
			),
			array(
				'key'          => 'fk_stripe',
				'type'         => 'fk_stripe',
				'label'        => '',
				'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end' ],
				'hint'         => __( ' Use Apple/Google Pay express checkout with deeper compatibility with FunnelKit including upsells. <a target="_blank" href="https://funnelkit.com/docs/stripe-gateway-for-woocommerce?utm_source=WordPress&utm_campaign=Stripe+Plugin&utm_medium=Stripe+Install+Notification">Learn More</a>', 'woofunnels' ),
			),
		),
		'priority' => 45,
	),
) );