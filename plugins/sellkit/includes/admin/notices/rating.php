<?php

namespace Sellkit\Admin\Notices;

defined( 'ABSPATH' ) || die();

/**
 * SellKit rating class.
 *
 * @since 1.5.7
 */
class Rating extends Notice_Base {

	/**
	 * Notice key.
	 *
	 * @since 1.5.7
	 * @var string
	 */
	public $key = 'rating';

	/**
	 * Woocommerce_Settings constructor.
	 *
	 * @since 1.5.7
	 * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Check if notice is valid or not.
	 *
	 * @since 1.5.7
	 * @return bool
	 */
	public function is_valid() {
		global $pagenow;

		if ( 'index.php' !== $pagenow ) {
			return false;
		}

		if ( in_array( $this->key, $this->dismissed_notices, true ) ) {
			return false;
		}

		$rating_notice_trigger = sellkit_get_option( 'sellkit_rating_notice_trigger' );

		if ( ! empty( $rating_notice_trigger ) && time() < $rating_notice_trigger ) {
			return false;
		}

		if ( ! $this->has_sellkit_post() ) {
			return false;
		}

		return true;
	}

	/**
	 * Set the priority of notice.
	 *
	 * @since 1.5.7
	 * @return int
	 */
	public function priority() {
		return 1;
	}

	/**
	 * Checks if some posts has been created or not in the SellKit.
	 *
	 * @since 1.5.7
	 * @return boolean
	 */
	public function has_sellkit_post() {
		$time = strtotime( '-7 days' );

		$query = new \WP_Query( [
			'post_type' => [
				'sellkit-funnels',
				'sellkit-discount',
				'sellkit-coupon',
				'sellkit-alert'
			],
			'date_query' => [
				[
					'before'    => [
						'year'  => intval( date( 'Y', $time ) ),
						'month' => intval( date( 'm', $time ) ),
						'day'   => intval( date( 'd', $time ) ),
					],
					'inclusive' => false,
				],
			],
		] );

		if ( ! empty( $query->posts ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Notice content wrapper.
	 *
	 * @since 1.5.7
	 */
	public function notice_content_wrapper() {
		?>
		<div class="sellkit-notice notice" data-key="<?php echo esc_attr( $this->key ); ?>">
			<div class="sellkit-notice-aside"><span class="sellkit-notice-aside-icon"><span></span></span></div>
			<div class="sellkit-notice-content">
				<div class="sellkit-notice-content-body">
					<?php echo esc_html__( 'If you enjoy using SellKit, could you take a moment to give us a 5-star rating? It won’t take more than a minute. Thank you for your support.', 'sellkit' ); ?>
				</div>
				<div class="sellkit-notice-content-footer">
					<a class="button-primary" target="_blank" href="https://login.wordpress.org/?redirect_to=https%3A%2F%2Fwordpress.org%2Fsupport%2Fplugin%2Fsellkit%2Freviews%2F%23new-post&locale=en_US"><?php echo esc_html__( 'Okay, You deserve it', 'sellkit' ); ?></a>
					<a class="button-link sellkit-rating-notice-maybe-later">
						<img src="<?php echo sellkit()->plugin_assets_url() . 'img/icons/rating-notice-maybe-later.svg'; ?>" alt="">
						<?php echo esc_html__( 'Maybe later', 'sellkit' ); ?>
					</a>
					<a class="button-link sellkit-rating-notice-i-already-did">
						<img src="<?php echo sellkit()->plugin_assets_url() . 'img/icons/rating-notice-i-already-did.svg'; ?>" alt="">
						<?php echo esc_html__( 'I already did', 'sellkit' ); ?>
					</a>
				</div>
			</div>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
			</button>
		</div>
		<?php
	}
}
