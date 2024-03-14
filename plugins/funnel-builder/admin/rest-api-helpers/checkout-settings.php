<?php
return [
	'tracking_analysis' => [
		'title'    => __( 'Tracking Analytics', 'funnel-builder' ),
		'heading'  => __( 'Tracking and Analytics', 'funnel-builder' ),
		'slug'     => 'tracking_analysis',
		'hint'     => __( 'Use this to adjust the tracking events for one-page checkouts', 'funnel-builder' ),
		'fields'   => [
			[
				'type'   => 'radios',
				'key'    => 'override_global_track_event',
				'label'  => __( 'Override Global Settings', 'funnel-builder' ),
				'hint'   => '',
				'values' => [
					0 => [
						'value' => 'true',
						'name'  => 'Yes',
					],
					1 => [
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'bwf-label',
				'key'     => 'fb_pixel',
				'label'   => __( 'Facebook Pixel', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
			],
			// FB Pixel.
			[
				'type'    => 'radios',
				'key'     => 'pixel_is_page_view',
				'label'   => __( 'Enable PageView Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'pixel_add_to_cart_event',
				'label'   => __( 'Enable AddtoCart Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'pixel_add_to_cart_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "pixel_add_to_cart_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			[
				'type'    => 'radios',
				'key'     => 'pixel_initiate_checkout_event',
				'label'   => __( 'Enable InitiateCheckout Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'pixel_initiate_checkout_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "pixel_initiate_checkout_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			[
				'type'    => 'radios',
				'key'     => 'pixel_add_payment_info_event',
				'label'   => __( 'Enable AddPaymentInfo Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			// Google Analytics.
			[
				'type'    => 'bwf-label',
				'key'     => 'google_analytics',
				'label'   => __( 'Google Analytics', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'google_ua_is_page_view',
				'label'   => __( 'Enable PageView Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'google_ua_add_to_cart_event',
				'label'   => __( 'Enable AddtoCart Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'google_ua_add_to_cart_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "google_ua_add_to_cart_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			[
				'type'    => 'radios',
				'key'     => 'google_ua_initiate_checkout_event',
				'label'   => __( 'Enable BeginCheckout Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'google_ua_initiate_checkout_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "google_ua_initiate_checkout_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			[
				'type'    => 'radios',
				'key'     => 'google_ua_add_payment_info_event',
				'label'   => __( 'Enable AddPaymentInfo Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			// Google Ads.
			[
				'type'    => 'bwf-label',
				'key'     => 'google_ads',
				'label'   => __( 'Google ADS', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'google_ads_is_page_view',
				'label'   => __( 'Enable PageView Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'google_ads_add_to_cart_event',
				'label'   => __( 'Enable AddtoCart Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'google_ads_add_to_cart_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "google_ads_add_to_cart_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			// Pinterest
			[
				'type'    => 'bwf-label',
				'key'     => 'pinterest',
				'label'   => __( 'Pinterest', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'pint_is_page_view',
				'label'   => __( 'Enable PageView Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'pint_add_to_cart_event',
				'label'   => __( 'Enable AddtoCart Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'pint_add_to_cart_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "pint_add_to_cart_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			[
				'type'    => 'radios',
				'key'     => 'pint_initiate_checkout_event',
				'label'   => __( 'Enable InitiateCheckout Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			// Tiktok.
			[
				'type'    => 'bwf-label',
				'key'     => 'TikTok',
				'label'   => __( 'TikTok', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'tiktok_is_page_view',
				'label'   => __( 'Enable PageView Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'tiktok_add_to_cart_event',
				'label'   => __( 'Enable AddtoCart Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'tiktok_add_to_cart_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "tiktok_add_to_cart_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			[
				'type'    => 'radios',
				'key'     => 'tiktok_initiate_checkout_event',
				'label'   => __( 'Enable InitiateCheckout Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'tiktok_initiate_checkout_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "tiktok_initiate_checkout_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],

			// Snapchat.
			[
				'type'    => 'bwf-label',
				'key'     => 'snap_chat',
				'label'   => __( 'SnapChat', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'snapchat_is_page_view',
				'label'   => __( 'Enable PageView Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'radios',
				'key'     => 'snapchat_add_to_cart_event',
				'label'   => __( 'Enable AddtoCart Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'snapchat_add_to_cart_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "snapchat_add_to_cart_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
			[
				'type'    => 'radios',
				'key'     => 'snapchat_initiate_checkout_event',
				'label'   => __( 'Enable InitiateCheckout Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					'key'   => 'override_global_track_event',
					'value' => 'true',
				],
				'values'  => [
					[
						'value' => 'true',
						'name'  => 'Yes',
					],
					[
						'value' => 'false',
						'name'  => 'No',
					],
				],
			],
			[
				'type'    => 'select',
				'key'     => 'snapchat_initiate_checkout_event_position',
				'label'   => __( 'Trigger Event', 'funnel-builder' ),
				'hint'    => '',
				'toggler' => [
					[
						"key"   => "override_global_track_event",
						"value" => "true"
					],
					[
						"key"   => "snapchat_initiate_checkout_event",
						"value" => "true"
					]
				],
				'values'  => $track_event_options,
			],
		],
		'priority' => 10,
		'values'   => $tracking_analysis,
	],
	'header_css'        => [
		'title'    => __( 'Custom CSS', 'funnel-builder' ),
		'heading'  => __( 'Custom CSS', 'funnel-builder' ),
		'hint'     => __( 'Add Custom CSS on checkout page', 'funnel-builder' ),
		'slug'     => 'custom_css',
		'fields'   => [
			[
				'key'   => 'header_css',
				'type'  => 'textArea',
				'label' => __( 'CSS', 'funnel-builder' ),
				'placeholder' => __( 'Paste your CSS code here', 'funnel-builder' ),
				'className'   => 'bwf-textarea-lg-resizable',
			],
		],
		'priority' => 30,
		'values'   => [
			'header_css' => ! empty( $values['header_css'] ) ? $values['header_css'] : '',
		],
	],
	'custom_js'         => [
		'title'    => __( 'Custom Scripts', 'funnel-builder' ),
		'heading'  => __( 'Embed Script', 'funnel-builder' ),
		'hint'     => __( 'Add custom scripts on checkout page', 'funnel-builder' ),
		'slug'     => 'custom_js',
		'fields'   => [
			[
				'key'         => 'header_script',
				'type'        => 'textArea',
				'label'       => __( 'Header', 'funnel-builder' ),
				'placeholder' => __( 'Paste your code here', 'funnel-builder' ),
				'className'   => 'bwf-textarea-lg-resizable',
			],
			[
				'key'         => 'footer_script',
				'type'        => 'textArea',
				'label'       => __( 'Footer', 'funnel-builder' ),
				'placeholder' => __( 'Paste your code here', 'funnel-builder' ),
				'className'   => 'bwf-textarea-lg-resizable',
			]
		],
		'priority' => 20,
		'values'   => [
			'header_script' => ! empty( $values['header_script'] ) ? $values['header_script'] : '',
			'footer_script' => ! empty( $values['footer_script'] ) ? $values['footer_script'] : '',
		],
	],
];