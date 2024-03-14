<?php

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Promote\\Load' ) ) {
	/**
	 *  Promote constants
	 */
	define( 'QLSTFT_PROMOTE_LOGO_SRC', plugins_url( '/assets/backend/img/logo.jpg', QLSTFT_PLUGIN_FILE ) );
	/**
	 * Notice review
	 */
	define( 'QLSTFT_PROMOTE_REVIEW_URL', 'https://wordpress.org/support/plugin/storefront-footer/reviews/?filter=5#new-post' );
	/**
	 * Notice premium sell
	 */
	define( 'QLSTFT_PROMOTE_PREMIUM_SELL_SLUG', 'woocommerce-direct-checkout' );
	define( 'QLSTFT_PROMOTE_PREMIUM_SELL_NAME', 'WooCommerce Direct Checkout PRO' );
	define( 'QLSTFT_PROMOTE_PREMIUM_SELL_URL', QLSTFT_PREMIUM_SELL_URL );
	/**
	 * Notice cross sell 1
	 */
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_1_SLUG', 'woocommerce-checkout-manager' );
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_1_NAME', 'WooCommerce Checkout Manager' );
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_1_DESCRIPTION', esc_html__( 'This plugin allows you to add custom fields to the checkout page, related to billing, shipping or additional fields sections.', 'storefront-footer' ) );
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_1_URL', 'https://quadlayers.com/products/woocommerce-checkout-manager/?utm_source=qlstft_admin' );
	/**
	 * Notice cross sell 2
	 */
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_2_SLUG', 'perfect-woocommerce-brands' );
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_2_NAME', 'Perfect WooCommerce Brands' );
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_2_DESCRIPTION', esc_html__( 'Perfect WooCommerce Brands the perfect tool to improve customer experience on your site. It allows you to highlight product brands and organize them in lists, dropdowns, thumbnails, and as a widget.', 'storefront-footer' ) );
	define( 'QLSTFT_PROMOTE_CROSS_INSTALL_2_URL', 'https://quadlayers.com/products/perfect-woocommerce-brands/?utm_source=qlstft_admin' );

	new \QuadLayers\WP_Notice_Plugin_Promote\Load(
		QLSTFT_PLUGIN_FILE,
		array(
			array(
				'type'               => 'ranking',
				'notice_delay'       => MONTH_IN_SECONDS,
				'notice_logo'        => QLSTFT_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! Thank you for choosing the %s plugin!',
						'storefront-footer'
					),
					QLSTFT_PLUGIN_NAME
				),
				'notice_description' => esc_html__( 'Could you please give it a 5-star rating on WordPress? Your feedback boosts our motivation, helps us promote, and continues to improve this product. Your support matters!', 'storefront-footer' ),
				'notice_link'        => QLSTFT_PROMOTE_REVIEW_URL,
				'notice_link_label'  => esc_html__(
					'Yes, of course!',
					'storefront-footer'
				),
				'notice_more_link'   => QLSTFT_SUPPORT_URL,
				'notice_more_label'  => esc_html__(
					'Report a bug',
					'storefront-footer'
				),
			),
			array(
				'plugin_slug'        => QLSTFT_PROMOTE_PREMIUM_SELL_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS,
				'notice_logo'        => QLSTFT_PROMOTE_LOGO_SRC,
				'notice_title'       => esc_html__(
					'Hello! We have a special gift!',
					'storefront-footer'
				),
				'notice_description' => sprintf(
					esc_html__(
						'Today we want to make you a special gift. Using the coupon code %1$s before the next 48 hours you can get a 20 percent discount on the premium version of the %2$s plugin.',
						'storefront-footer'
					),
					'ADMINPANEL20%',
					QLSTFT_PROMOTE_PREMIUM_SELL_NAME
				),
				'notice_more_link'   => QLSTFT_PROMOTE_PREMIUM_SELL_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'storefront-footer'
				),
			),
			array(
				'plugin_slug'        => QLSTFT_PROMOTE_CROSS_INSTALL_1_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 4,
				'notice_logo'        => QLSTFT_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'storefront-footer'
					),
					QLSTFT_PROMOTE_CROSS_INSTALL_1_NAME
				),
				'notice_description' => QLSTFT_PROMOTE_CROSS_INSTALL_1_DESCRIPTION,
				'notice_more_link'   => QLSTFT_PROMOTE_CROSS_INSTALL_1_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'storefront-footer'
				),
			),
			array(
				'plugin_slug'        => QLSTFT_PROMOTE_CROSS_INSTALL_2_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 6,
				'notice_logo'        => QLSTFT_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'storefront-footer'
					),
					QLSTFT_PROMOTE_CROSS_INSTALL_2_NAME
				),
				'notice_description' => QLSTFT_PROMOTE_CROSS_INSTALL_2_DESCRIPTION,
				'notice_more_link'   => QLSTFT_PROMOTE_CROSS_INSTALL_2_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'storefront-footer'
				),
			),
		)
	);
}
