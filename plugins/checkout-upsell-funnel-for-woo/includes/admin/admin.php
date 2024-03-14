<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Admin_Admin {
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter(
			'plugin_action_links_checkout-upsell-funnel-for-woo/checkout-upsell-funnel-for-woo.php', array(
				$this,
				'settings_link'
			)
		);
	}

	public function settings_link( $links ) {
		$settings_link = sprintf( '<a href="%s?page=checkout-upsell-funnel-for-woo" title="%s">%s</a>', esc_attr( admin_url( 'admin.php' ) ),
			esc_attr__( 'Settings', 'checkout-upsell-funnel-for-woo' ),
			esc_html__( 'Settings', 'checkout-upsell-funnel-for-woo' )
		);
		array_unshift( $links, $settings_link );

		return $links;
	}

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'checkout-upsell-funnel-for-woo' );
		load_textdomain( 'checkout-upsell-funnel-for-woo', VICUFFW_CHECKOUT_UPSELL_FUNNEL_LANGUAGES . "checkout-upsell-funnel-for-woo-$locale.mo" );
		load_plugin_textdomain( 'checkout-upsell-funnel-for-woo', false, VICUFFW_CHECKOUT_UPSELL_FUNNEL_LANGUAGES );

	}

	public function init() {
		load_plugin_textdomain( 'checkout-upsell-funnel-for-woo' );
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'   => 'https://wordpress.org/support/plugin/checkout-upsell-funnel-for-woo/',
					'docs'      => 'http://docs.villatheme.com/?item=woocommerce-checkout-upsell-funnel',
					'review'    => 'https://wordpress.org/support/plugin/checkout-upsell-funnel-for-woo/reviews/?rate=5#rate-response',
					'pro_url'   => 'https://1.envato.market/oeemke',
					'css'       => VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS,
					'image'     => VICUFFW_CHECKOUT_UPSELL_FUNNEL_IMAGES,
					'slug'      => 'checkout-upsell-funnel-for-woo',
					'menu_slug' => 'checkout-upsell-funnel-for-woo',
					'survey_url' => 'https://script.google.com/macros/s/AKfycbyfqTf88p5Gg9Zu---1jzC971UPdK3pqp48eYToiP69AOI56mwmfXnR7z1weIrpT4wI/exec',
					'version'   => VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION
				)
			);
		}
	}
}