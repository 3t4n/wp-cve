<?php
/**
 * Extended Compatibility Class.
 *
 * @package    StorePress\TwoCheckoutPaymentGateway
 * @since      1.0.0
 */

namespace StorePress\TwoCheckoutPaymentGateway;

defined( 'ABSPATH' ) || die( 'Keep Silent' );

use StorePress\AdminUtils\Upgrade_Notice;

/**
 * Extended Plugin Compatibility Notice.
 */
class Extended_Plugin_Upgrade_Notice extends Upgrade_Notice {

	/** Init
	 *
	 * @return self
	 */
	public static function instance(): self {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Get Pro plugin file.
	 *
	 * @return string
	 */
	public function plugin_file(): string {
		return 'woo-2checkout-pro/woo-2checkout-pro.php';
	}

	/**
	 * Get required version of Extended Plugin.
	 *
	 * @return string
	 */
	public function compatible_version(): string {
		return woo_2checkout()->get_compatible_extended_version();
	}

	/**
	 * Should deactivate incompatible version.
	 *
	 * @return bool
	 */
	public function deactivate_incompatible(): bool {
		return false;
	}

	/**
	 * Notice string format.
	 *
	 * @return string
	 */
	public function localize_notice_format(): string {
		// translators: 1: Extended Plugin Name. 2: Extended Plugin Version. 3: Extended Plugin Compatible Version.
		return __( 'You are using an incompatible version of <strong>%1$s - (%2$s)</strong>. Please upgrade to version <strong>%3$s</strong> or upper.', 'woo-2checkout' );
	}
}
