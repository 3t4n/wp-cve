<?php

namespace Sellkit\Admin\Notices;

defined( 'ABSPATH' ) || die();

/**
 * Partner themes offer notice.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @since 1.7.9
 */
class Partner_Theme_Offer extends Notice_Base {
	/**
	 * Notice key.
	 *
	 * @since 1.7.9
	 * @var string
	 */
	public $key = 'sellkit-partners-theme-offer';

	/**
	 * Notice url.
	 *
	 * @since 1.8.6
	 * @var string
	 */
	public $url = 'https://getsellkit.com/pricing/?coupon=promo_1NjxuCCrEq1rXLl9SD1SsLXM&utm_source=PhloxThemeWPAdminTopUpgradeBar&utm_medium=PartnerThemeWPAdmin';

	/**
	 * Class construct.
	 *
	 * @since 1.7.9
	 */
	public function __construct() {
		parent::__construct();

		if ( 'Bridge' === $this->active_theme ) {
			$this->url = 'https://getsellkit.com/pricing/?coupon=promo_1NjxuCCrEq1rXLl9SD1SsLXM&utm_source=BridgeThemeWPAdminTopUpgradeBar&utm_medium=PartnerThemeWPAdmin';
		}
	}

	/**
	 * Set the priority of notice.
	 *
	 * @since 1.7.9
	 * @return int
	 */
	public function priority() {
		return 1;
	}

	/**
	 * Check if notice is valid or not.
	 *
	 * @since 1.7.9
	 * @return bool
	 */
	public function is_valid() {
		$valid_themes = [ 'Phlox', 'Bridge' ];

		if (
			! in_array( $this->active_theme, $valid_themes, true ) ||
			sellkit()->has_pro
		) {
			return false;
		}

		$previous_client        = false;
		$current_time           = time();
		$valid_period           = 7 * ( 24 * 3600 ); // 7 days timestamp.
		$sellkit_installed_time = get_option( 'sellkit-installed-time', 0 );
		$dismissed_time         = get_option( 'sellkit-partner-offer-theme-dismissed', 0 );

		// User installed sellkit since or before V1.5.9 and installed time was not set.
		if ( 0 === $sellkit_installed_time ) {
			$previous_client = true;
		}

		// Do not display if dismissed less than 90 days ago.
		if (
			in_array( $this->key, $this->dismissed_notices, true ) &&
			$dismissed_time > 0 &&
			$current_time - $dismissed_time <= 90 * ( 24 * 3600 )
		) {
			return false;
		}

		// Display notice 90 days after it is dismissed.
		if (
			in_array( $this->key, $this->dismissed_notices, true ) &&
			$dismissed_time > 0 &&
			$current_time - $dismissed_time >= 90 * ( 24 * 3600 )
		) {
			return true;
		}

		// Display notice 7 days after plugin is installed.
		if (
			(
				$current_time - $sellkit_installed_time >= $valid_period &&
				$sellkit_installed_time > 0
			) ||
			true === $previous_client
		) {
			return true;
		}

		return false;
	}

	/**
	 * Notice content wrapper.
	 *
	 * @since 1.7.9
	 */
	public function notice_content_wrapper() {
		$checkmark_url = sellkit()->plugin_assets_url() . '/img/icons/checkmark.svg';
		?>
			<div class="sellkit-notice notice is-dismissible" data-key="<?php echo esc_attr( $this->key ); ?>">
				<div class="sellkit-notice-aside"><span class="sellkit-notice-aside-icon"><span></span></span></div>
				<div class="sellkit-notice-content">
					<div class="sellkit-notice-content-body">
						<div class="sellkit-notice-partner-theme-body">
							<div class="sellkit-content-left-column">
								<span class="sk-notice-title"><?php esc_html_e( 'You are using FREE version of SellKit!', 'sellkit' ); ?></span>
								<p><b><?php esc_html_e( 'Upgrade to SellKit Pro and unlock more engagement and sales for your WooCommerce store.', 'sellkit' ); ?></b></p>
								<div class="sellkit-notice-attributes-list">
									<div><img src="<?php echo esc_url( $checkmark_url ); ?>"><?php esc_html_e( 'Advanced Filter & Variation Swatches', 'sellkit' ); ?></div>
									<div><img src="<?php echo esc_url( $checkmark_url ); ?>"><?php esc_html_e( 'Express Checkout Features', 'sellkit' ); ?></div>
									<div><img src="<?php echo esc_url( $checkmark_url ); ?>"><?php esc_html_e( 'Advanced Segmentation', 'sellkit' ); ?></div>
									<div><img src="<?php echo esc_url( $checkmark_url ); ?>"><?php esc_html_e( 'Dynamic Discounts & Coupons', 'sellkit' ); ?></div>
									<div><img src="<?php echo esc_url( $checkmark_url ); ?>"><?php esc_html_e( 'Checkout Notices (FOMO, BOGO,...)', 'sellkit' ); ?></div>
								</div>
							</div>
							<div class="sellkit-content-right-column">
								<div class="right-col-content-wrap">
									<div class="btn-content">
										<a href="<?php echo esc_url( $this->url ); ?>" target="_blank">
											<div class="btn-inner-wrap">
												<div class="btn-role">
													<span><?php esc_html_e( 'Buy', 'sellkit' ); ?></span>
													<svg
														xmlns="http://www.w3.org/2000/svg"
														xmlns:xlink="http://www.w3.org/1999/xlink"
														width="70px" height="25px"
													>
														<image  x="0px" y="0px" width="70px" height="25px"  xlink:href="data:img/png;base64,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" />
													</svg>
													<svg
														xmlns="http://www.w3.org/2000/svg"
														xmlns:xlink="http://www.w3.org/1999/xlink"
														width="25px" height="11px"
													>
														<image  x="0px" y="0px" width="25px" height="11px"  xlink:href="data:img/png;base64,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" />
													</svg>
												</div>
												<p class="offer-text">
													<?php
														echo '50% ';
														/* translators: %s Theme name. */
														echo sprintf( esc_html__( 'OFF for %s users', 'sellkit' ), $this->active_theme );
													?>
												</p>
											</div>
										</a>
										<p class="offer-price-section">
											<span class="offer-price"><?php esc_html_e( '$199/year', 'sellkit' ); ?></span>
											<span class="offer-price-discount"><?php esc_html_e( '$100/year', 'sellkit' ); ?></span>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</div>
		<?php
	}
}
