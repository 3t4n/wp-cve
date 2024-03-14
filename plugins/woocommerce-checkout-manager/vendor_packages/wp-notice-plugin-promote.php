<?php

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Promote\\Load' ) ) {
	/**
	 *  Promote constants
	 */
	define( 'WOOCCM_PROMOTE_LOGO_SRC', plugins_url( '/assets/backend/img/logo.jpg', WOOCCM_PLUGIN_FILE ) );
	/**
	 * Notice review
	 */
	define( 'WOOCCM_PROMOTE_REVIEW_URL', 'https://wordpress.org/support/plugin/woocommerce-checkout-manager/reviews/?filter=5#new-post' );
	/**
	 * Notice premium sell
	 */
	define( 'WOOCCM_PROMOTE_PREMIUM_SELL_SLUG', 'woocommerce-checkout-manager-pro' );
	define( 'WOOCCM_PROMOTE_PREMIUM_SELL_NAME', 'WooCommerce Checkout Manager PRO' );
	define( 'WOOCCM_PROMOTE_PREMIUM_SELL_URL', WOOCCM_PREMIUM_SELL_URL );
	define( 'WOOCCM_PROMOTE_PREMIUM_INSTALL_URL', 'https://quadlayers.com/product/woocommerce-checkout-manager/?utm_source=wooccm_admin' );
	/**
	 * Notice cross sell 1
	 */
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_1_SLUG', 'woocommerce-direct-checkout' );
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_1_NAME', 'Direct Checkout' );
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_1_DESCRIPTION', esc_html__( 'Direct Checkout for WooCommerce allows you to reduce the steps in the checkout process by skipping the shopping cart page. This can encourage buyers to shop more and quickly. You will increase your sales reducing cart abandonment.', 'woocommerce-checkout-manager' ) );
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_1_URL', 'https://quadlayers.com/products/woocommerce-direct-checkout/?utm_source=wooccm_admin' );
	/**
	 * Notice cross sell 2
	 */
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_2_SLUG', 'perfect-woocommerce-brands' );
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_2_NAME', 'Perfect WooCommerce Brands' );
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_2_DESCRIPTION', esc_html__( 'Perfect WooCommerce Brands the perfect tool to improve customer experience on your site. It allows you to highlight product brands and organize them in lists, dropdowns, thumbnails, and as a widget.', 'woocommerce-checkout-manager' ) );
	define( 'WOOCCM_PROMOTE_CROSS_INSTALL_2_URL', 'https://quadlayers.com/products/perfect-woocommerce-brands/?utm_source=wooccm_admin' );

	new \QuadLayers\WP_Notice_Plugin_Promote\Load(
		WOOCCM_PLUGIN_FILE,
		array(
			array(
				'type'               => 'ranking',
				'notice_delay'       => MONTH_IN_SECONDS,
				'notice_logo'        => WOOCCM_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! Thank you for choosing the %s plugin!',
						'woocommerce-checkout-manager'
					),
					WOOCCM_PLUGIN_NAME
				),
				'notice_description' => esc_html__( 'Could you please give it a 5-star rating on WordPress? Your feedback boosts our motivation, helps us promote, and continues to improve this product. Your support matters!', 'woocommerce-checkout-manager' ),
				'notice_link'        => WOOCCM_PROMOTE_REVIEW_URL,
				'notice_link_label'  => esc_html__(
					'Yes, of course!',
					'woocommerce-checkout-manager'
				),
				'notice_more_link'   => WOOCCM_SUPPORT_URL,
				'notice_more_label'  => esc_html__(
					'Report a bug',
					'woocommerce-checkout-manager'
				),
			),
			array(
				'plugin_slug'        => WOOCCM_PROMOTE_PREMIUM_SELL_SLUG,
				'plugin_install_link'   => WOOCCM_PROMOTE_PREMIUM_INSTALL_URL,
				'plugin_install_label'  => esc_html__(
					'Purchase Now',
					'woocommerce-checkout-manager'
				),
				'notice_delay'       => MONTH_IN_SECONDS,
				'notice_logo'        => WOOCCM_PROMOTE_LOGO_SRC,
				'notice_title'       => esc_html__(
					'Hello! We have a special gift!',
					'woocommerce-checkout-manager'
				),
				'notice_description' => sprintf(
					esc_html__(
						'Today we have a special gift for you. Use the coupon code %1$s within the next 48 hours to receive a %2$s discount on the premium version of the %3$s plugin.',
						'woocommerce-checkout-manager'
					),
					'ADMINPANEL20%',
					'20%',
					WOOCCM_PROMOTE_PREMIUM_SELL_NAME
				),
				'notice_more_link'   => WOOCCM_PROMOTE_PREMIUM_SELL_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'woocommerce-checkout-manager'
				),
			),
			array(
				'plugin_slug'        => WOOCCM_PROMOTE_CROSS_INSTALL_1_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 4,
				'notice_logo'        => WOOCCM_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'woocommerce-checkout-manager'
					),
					WOOCCM_PROMOTE_CROSS_INSTALL_1_NAME
				),
				'notice_description' => WOOCCM_PROMOTE_CROSS_INSTALL_1_DESCRIPTION,
				'notice_more_link'   => WOOCCM_PROMOTE_CROSS_INSTALL_1_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'woocommerce-checkout-manager'
				),
			),
			array(
				'plugin_slug'        => WOOCCM_PROMOTE_CROSS_INSTALL_2_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 6,
				'notice_logo'        => WOOCCM_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'woocommerce-checkout-manager'
					),
					WOOCCM_PROMOTE_CROSS_INSTALL_2_NAME
				),
				'notice_description' => WOOCCM_PROMOTE_CROSS_INSTALL_2_DESCRIPTION,
				'notice_more_link'   => WOOCCM_PROMOTE_CROSS_INSTALL_2_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'woocommerce-checkout-manager'
				),
			),
		)
	);
}
