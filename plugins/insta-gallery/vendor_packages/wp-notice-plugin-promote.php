<?php

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Promote\\Load' ) ) {
	/**
	 *  Promote constants
	 */
	define( 'QLIGG_PROMOTE_LOGO_SRC', plugins_url( '/assets/backend/img/icon-128x128.gif', QLIGG_PLUGIN_FILE ) );
	/**
	 * Notice review
	 */
	define( 'QLIGG_PROMOTE_REVIEW_URL', 'https://wordpress.org/support/plugin/insta-gallery/reviews/?filter=5#new-post' );
	/**
	 * Notice premium sell
	 */
	define( 'QLIGG_PROMOTE_PREMIUM_SELL_SLUG', 'insta-gallery-pro' );
	define( 'QLIGG_PROMOTE_PREMIUM_SELL_NAME', 'Social Feed Gallery PRO' );
	define( 'QLIGG_PROMOTE_PREMIUM_INSTALL_URL', 'https://quadlayers.com/product/instagram-feed-gallery/?utm_source=qligg_admin' );
	define( 'QLIGG_PROMOTE_PREMIUM_SELL_URL', QLIGG_PREMIUM_SELL_URL );
	/**
	 * Notice cross sell 1
	 */
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_1_SLUG', 'wp-tiktok-feed' );
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_1_NAME', 'TikTok Feed' );
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_1_DESCRIPTION', esc_html__( 'TikTok Feed is a user-friendly WordPress plugin designed for easy integration. It ensures quick site updates and keeps pace with the fast-growing social media trends.', 'insta-gallery' ) );
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_1_URL', 'https://quadlayers.com/products/tiktok-feed/?utm_source=qligg_admin' );
	/**
	 * Notice cross sell 2
	 */
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_2_SLUG', 'wp-whatsapp-chat' );
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_2_NAME', 'Social Chat' );
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_2_DESCRIPTION', esc_html__( 'Social Chat allows your users to start a conversation from your website directly to your WhatsApp phone number with one click.', 'insta-gallery' ) );
	define( 'QLIGG_PROMOTE_CROSS_INSTALL_2_URL', 'https://quadlayers.com/product/whatsapp-chat/?utm_source=qligg_admin' );

	new \QuadLayers\WP_Notice_Plugin_Promote\Load(
		QLIGG_PLUGIN_FILE,
		array(
			array(
				'type'               => 'ranking',
				'notice_delay'       => MONTH_IN_SECONDS,
				'notice_logo'        => QLIGG_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! Thank you for choosing the %s plugin!',
						'insta-gallery'
					),
					QLIGG_PLUGIN_NAME
				),
				'notice_description' => esc_html__( 'Could you please give it a 5-star rating on WordPress? Your feedback boosts our motivation, helps us promote, and continues to improve this product. Your support matters!', 'insta-gallery' ),
				'notice_link'        => QLIGG_PROMOTE_REVIEW_URL,
				'notice_link_label'  => esc_html__(
					'Yes, of course!',
					'insta-gallery'
				),
				'notice_more_link'   => QLIGG_SUPPORT_URL,
				'notice_more_label'  => esc_html__(
					'Report a bug',
					'insta-gallery'
				),
			),
			array(
				'plugin_slug'          => QLIGG_PROMOTE_PREMIUM_SELL_SLUG,
				'plugin_install_link'  => QLIGG_PROMOTE_PREMIUM_INSTALL_URL,
				'plugin_install_label' => esc_html__(
					'Purchase Now',
					'insta-gallery'
				),
				'notice_delay'         => MONTH_IN_SECONDS,
				'notice_logo'          => QLIGG_PROMOTE_LOGO_SRC,
				'notice_title'         => esc_html__(
					'Hello! We have a special gift!',
					'insta-gallery'
				),
				'notice_description'   => sprintf(
					esc_html__(
						'Today we have a special gift for you. Use the coupon code %1$s within the next 48 hours to receive a %2$s discount on the premium version of the %3$s plugin.',
						'insta-gallery'
					),
					'ADMINPANEL20%',
					'20%',
					QLIGG_PROMOTE_PREMIUM_SELL_NAME
				),
				'notice_more_link'     => QLIGG_PROMOTE_PREMIUM_SELL_URL,
				'notice_more_label'    => esc_html__(
					'More info!',
					'insta-gallery'
				),
			),
			array(
				'plugin_slug'        => QLIGG_PROMOTE_CROSS_INSTALL_1_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 4,
				'notice_logo'        => QLIGG_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'insta-gallery'
					),
					QLIGG_PROMOTE_CROSS_INSTALL_1_NAME
				),
				'notice_description' => QLIGG_PROMOTE_CROSS_INSTALL_1_DESCRIPTION,
				'notice_more_link'   => QLIGG_PROMOTE_CROSS_INSTALL_1_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'insta-gallery'
				),
			),
			array(
				'plugin_slug'        => QLIGG_PROMOTE_CROSS_INSTALL_2_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 6,
				'notice_logo'        => QLIGG_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'insta-gallery'
					),
					QLIGG_PROMOTE_CROSS_INSTALL_2_NAME
				),
				'notice_description' => QLIGG_PROMOTE_CROSS_INSTALL_2_DESCRIPTION,
				'notice_more_link'   => QLIGG_PROMOTE_CROSS_INSTALL_2_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'insta-gallery'
				),
			),
		)
	);
}
