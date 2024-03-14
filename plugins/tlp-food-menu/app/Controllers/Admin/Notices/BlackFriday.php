<?php
/**
 * Black Friday Notice Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin\Notices;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Black Friday Notice Class.
 */
class BlackFriday {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		$current      = time();
		$black_friday = mktime( 0, 0, 0, 11, 20, 2023 ) <= $current && $current <= mktime( 0, 0, 0, 1, 15, 2024 );

		if ( ! $black_friday ) {
			return;
		}

		add_action( 'admin_init', [ $this, 'bf_notice' ] );
	}

	/**
	 * Black Friday Notice.
	 *
	 * @return void|string
	 */
	public function bf_notice() {
		if ( get_option( 'rtfm_ny_2023' ) != '1' ) {
			if ( ! isset( $GLOBALS['rt_ny_2023_notice'] ) ) {
				$GLOBALS['rt_ny_2023_notice'] = 'rtfm_ny_2023';
				self::notice();
			}
		}
	}

	/**
	 * Render Notice
	 *
	 * @return void
	 */
	private static function notice() {

		add_action(
			'admin_enqueue_scripts',
			function () {
				wp_enqueue_script( 'jquery' );
			}
		);

		add_action(
			'admin_notices',
			function () {
				$plugin_name   = 'Food Menu Pro';
				$download_link = 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/';
				?>
			<div class="notice notice-info is-dismissible" data-rtfmdismissable="rtfm_ny_2023" style="display:grid;grid-template-columns: 100px auto;padding-top: 25px; padding-bottom: 22px;">
				<img alt="<?php echo esc_attr( $plugin_name ); ?>" src="<?php echo esc_url( TLPFoodMenu()->assets_url() ) . 'images/icon-128x128.png'; ?>" width="74px" height="74px" style="grid-row: 1 / 4; align-self: center;justify-self: center"/>
				<h3 style="margin:0;"><?php echo sprintf( '%s Black Friday Sale 2023!!', esc_html( $plugin_name ) ); ?></h3>
                <p style="margin:0 0 2px;"><?php echo esc_html__( "🚀 Exciting News: $plugin_name Black Friday sale is now live!", 'food-menu' ); ?>
                    Get the plugin today and enjoy discounts up to <b> 50%.</b>
                </p>
				<p style="margin:0;">
					<a class="button button-primary" href="<?php echo esc_url( $download_link ); ?>" target="_blank">Buy Now</a>
					<a class="button button-dismiss" href="#">Dismiss</a>
				</p>
			</div>
				<?php
			}
		);

		add_action(
			'admin_footer',
			function () {
				?>
				<script type="text/javascript">
					(function ($) {
						$(function () {
							setTimeout(function () {
								$('div[data-rtfmdismissable] .notice-dismiss, div[data-rtfmdismissable] .button-dismiss')
									.on('click', function (e) {
										e.preventDefault();
										$.post(ajaxurl, {
											'action': 'rtfm_dismiss_admin_notice',
											'nonce': <?php echo json_encode( wp_create_nonce( 'rtfm-dismissible-notice' ) ); ?>
										});
										$(e.target).closest('.is-dismissible').remove();
									});
							}, 1000);
						});
					})(jQuery);
				</script>
				<?php
			}
		);

		add_action(
			'wp_ajax_rtfm_dismiss_admin_notice',
			function () {
				check_ajax_referer( 'rtfm-dismissible-notice', 'nonce' );

				update_option( 'rtfm_ny_2023', '1' );
				wp_die();
			}
		);
	}
}
