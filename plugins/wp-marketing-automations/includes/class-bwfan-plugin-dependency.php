<?php

/**
 * WC Dependency Checker
 */
class BWFAN_Plugin_Dependency {

	private static $active_plugins;

	public static function init() {
		self::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
	}

	public static function woocommerce_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'WooCommerce' ) ) {
			return true;
		}

		if ( ! function_exists( 'is_checkout' ) ) {
			return false;
		}

		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
	}

	public static function edd_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			return true;
		}

		return in_array( 'easy-digital-downloads/easy-digital-downloads.php', self::$active_plugins, true ) || array_key_exists( 'easy-digital-downloads/easy-digital-downloads.php', self::$active_plugins );
	}

	public static function woocommerce_subscriptions_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		/** adding checking for function exists as causing issue with Webtofee subscription plugin */
		if ( class_exists( 'WC_Subscriptions' ) && function_exists( 'wcs_get_subscription_statuses' ) ) {
			return true;
		}

		return in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', self::$active_plugins, true ) || array_key_exists( 'woocommerce-subscriptions/woocommerce-subscriptions.php', self::$active_plugins );
	}

	public static function woocommerce_membership_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		return in_array( 'woocommerce-memberships/woocommerce-memberships.php', self::$active_plugins, true ) || array_key_exists( 'woocommerce-memberships/woocommerce-memberships.php', self::$active_plugins );
	}

	/** checking paid membership pro is active
	 *
	 * @return bool
	 */
	public static function paid_membership_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		return in_array( 'paid-memberships-pro/paid-memberships-pro.php', self::$active_plugins, true ) || array_key_exists( 'paid-memberships-pro/paid-memberships-pro.php', self::$active_plugins );
	}


	public static function woofunnels_upstroke_one_click_upsell() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'WFOCU_Core' ) ) {
			return true;
		}

		return in_array( 'woofunnels-upstroke-one-click-upsell/woofunnels-upstroke-one-click-upsell.php', self::$active_plugins, true ) || array_key_exists( 'woofunnels-upstroke-one-click-upsell/woofunnels-upstroke-one-click-upsell.php', self::$active_plugins );
	}

	public static function autonami_pro_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'BWFAN_Pro' ) ) {
			return true;
		}

		return in_array( 'autonami-automations-pro/autonami-automations-pro.php', self::$active_plugins, true ) || array_key_exists( 'autonami-automations-pro/autonami-automations-pro.php', self::$active_plugins );
	}

	public static function autonami_connector_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'WFCO_Autonami_Connectors_Core' ) ) {
			return true;
		}

		return in_array( 'autonami-automations-connectors/autonami-automations-connectors.php', self::$active_plugins, true ) || array_key_exists( 'autonami-automations-connectors/autonami-automations-connectors.php', self::$active_plugins );
	}

	public static function affiliatewp_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'Affiliate_WP' ) ) {
			return true;
		}

		return in_array( 'affiliate-wp/affiliate-wp.php', self::$active_plugins, true ) || array_key_exists( 'affiliate-wp/affiliate-wp.php', self::$active_plugins );
	}

	public static function gforms_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'GFForms' ) ) {
			return true;
		}

		return in_array( 'gravityforms/gravityforms.php', self::$active_plugins, true ) || array_key_exists( 'gravityforms/gravityforms.php', self::$active_plugins );
	}

	public static function elementorpro_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return true;
		}

		return in_array( 'elementor-pro/elementor-pro.php', self::$active_plugins, true ) || array_key_exists( 'elementor-pro/elementor-pro.php', self::$active_plugins );
	}

	public static function learndash_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( defined( 'LEARNDASH_VERSION' ) ) {
			return true;
		}

		return in_array( 'sfwd-lms/sfwd_lms.php', self::$active_plugins, true ) || array_key_exists( 'sfwd-lms/sfwd_lms.php', self::$active_plugins );
	}

	public static function wpforms_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( defined( 'WPFORMS_VERSION' ) ) {
			return true;
		}

		return in_array( 'wpforms-lite/wpforms.php', self::$active_plugins, true ) || array_key_exists( 'wpforms-lite/wpforms.php', self::$active_plugins );
	}

	public static function cf7_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( defined( 'WPCF7_VERSION' ) ) {
			return true;
		}

		return in_array( 'contact-form-7/wp-contact-form-7.php', self::$active_plugins, true ) || array_key_exists( 'contact-form-7/wp-contact-form-7.php', self::$active_plugins );
	}

	public static function tve_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		return in_array( 'thrive-leads/thrive-leads.php', self::$active_plugins, true ) || array_key_exists( 'thrive-leads/thrive-leads.php', self::$active_plugins );
	}

	public static function translatepress_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		return in_array( 'translatepress-multilingual/index.php', self::$active_plugins, true ) || array_key_exists( 'translatepress-multilingual/index.php', self::$active_plugins );
	}

	/**
	 * Checking if tutorlms plugin active
	 * @return bool
	 */
	public static function tutorlms_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}
		if ( class_exists( '\TUTOR\Tutor' ) ) {
			return true;
		}

		return in_array( 'tutor/tutor.php', self::$active_plugins, true ) || array_key_exists( 'tutor/tutor.php', self::$active_plugins );
	}

	/**
	 * Checking if memberpress plugin active
	 * @return bool
	 */
	public static function mepr_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'MeprCtrlFactory' ) ) {
			return true;
		}

		return in_array( 'memberpress/memberpress.php', self::$active_plugins, true ) || array_key_exists( 'memberpress/memberpress.php', self::$active_plugins );
	}

	/**
	 * Checking if memberpress courses  plugin active
	 * @return bool
	 */
	public static function bwfan_is_mepr_courses_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		return in_array( 'memberpress-courses/main.php', self::$active_plugins, true ) || array_key_exists( 'memberpress-courses/main.php', self::$active_plugins );
	}

	/**
	 * Checking if wishlist member plugin active
	 * @return bool
	 */
	public static function wlm_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'WishListMember' ) ) {
			return true;
		}

		return in_array( 'wishlist-member-x/wpm.php', self::$active_plugins, true ) || array_key_exists( 'wishlist-member-x/wpm.php', self::$active_plugins );
	}

	/**
	 * Checking if formidable plugin active
	 * @return bool
	 */
	public static function formidable_forms_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'FrmWelcomeController' ) ) {
			return true;
		}

		return in_array( 'formidable/formidable.php', self::$active_plugins, true ) || array_key_exists( 'formidable/formidable.php', self::$active_plugins );
	}

	/**
	 * Checking if divi plugin or theme active
	 * @return bool
	 */
	public static function divi_forms_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		$theme = wp_get_theme();

		if ( 'Divi' === $theme->get_template() ) {
			return true;
		}

		if ( class_exists( 'ET_Builder_Plugin' ) ) {
			return true;
		}

		return in_array( 'divi-builder/divi-builder.php', self::$active_plugins, true ) || array_key_exists( 'divi-builder/divi-builder.php', self::$active_plugins );
	}

	/**
	 * Checking if Funnel Builder - Optin form plugin active
	 * @return bool
	 */
	public static function optin_forms_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( class_exists( 'WFFN_Core' ) ) {
			return true;
		}

		return in_array( 'funnel-builder/funnel-builder.php', self::$active_plugins, true ) || array_key_exists( 'woofunnels-flex-funnels/woofunnels-flex-funnels.php', self::$active_plugins );
	}

	/**
	 * Checking if utm grabber plugin active
	 * @return bool
	 */
	public static function handle_utm_grabber_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( defined( 'HANDL_UTM_V3_LINK' ) ) {
			return true;
		}

		return in_array( 'handl-utm-grabber/handl-utm-grabber.php', self::$active_plugins, true ) || array_key_exists( 'handl-utm-grabber/handl-utm-grabber.php', self::$active_plugins ) || in_array( 'handl-utm-grabber-v3/handl-utm-grabber-v3.php', self::$active_plugins, true ) || array_key_exists( 'handl-utm-grabber-v3/handl-utm-grabber-v3.php', self::$active_plugins );
	}

	/**
	 * Checking if weglot lanuage plugin active
	 * @return bool
	 */
	public static function weglot_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		if ( defined( 'WEGLOT_NAME' ) ) {
			return true;
		}

		return in_array( 'weglot/weglot.php', self::$active_plugins, true ) || array_key_exists( 'weglot/weglot.php', self::$active_plugins );
	}

	/**
	 * Xl next move  plugin checking
	 * @return bool
	 */
	public static function xl_nextmove_thankyou_active_check() {
		if ( ! self::$active_plugins ) {
			self::init();
		}

		return in_array( 'thank-you-page-for-woocommerce-nextmove/woocommerce-thankyou-pages.php', self::$active_plugins, true ) || array_key_exists( 'thank-you-page-for-woocommerce-nextmove/woocommerce-thankyou-pages.php', self::$active_plugins );
	}
}
