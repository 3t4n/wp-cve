<?php

namespace Sellkit\Global_Checkout;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin as Elementor;
use Sellkit\Elementor\Modules\Checkout\Classes\Helper;
use Sellkit\Funnel\Steps\Checkout as CheckoutStep;

/**
 * Checkout.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @since 1.7.4
 */
class Checkout {
	const SELLKIT_GLOBAL_CHECKOUT_OPTION = 'sellkit_global_checkout_id';

	/**
	 * Construct.
	 *
	 * @since 1.7.4
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'init_sellkit_global_checkout' ] );
	}

	/**
	 * Init sellkit global checkout.
	 *
	 * @since 1.7.4
	 */
	public function init_sellkit_global_checkout() {
		$global_checkout_id = get_option( self::SELLKIT_GLOBAL_CHECKOUT_OPTION, 0 );

		if (
			0 === $global_checkout_id ||
			'publish' !== get_post_status( (int) $global_checkout_id ) ||
			! is_checkout() ||
			! defined( 'ELEMENTOR_VERSION' )
		) {
			return;
		}

		$steps             = get_post_meta( $global_checkout_id, 'nodes', true );
		$checkout_id       = 0;
		$bump_data         = [];
		$optimization_data = '';

		if ( ! is_array( $steps ) ) {
			return;
		}

		foreach ( $steps as $step ) {
			$step['type'] = (array) $step['type'];

			if ( 'checkout' === $step['type']['key'] ) {
				$checkout_id       = $step['page_id'];
				$bump_data         = ! empty( $step['bump'] ) ? $step['bump'] : [];
				$optimization_data = ! empty( $step['data']['optimization'] ) ? $step['data']['optimization'] : '';
			}
		}

		if ( 0 === $checkout_id ) {
			return;
		}

		// Remove previous content.
		remove_all_filters( 'the_content' );

		// Set the page content.
		add_filter( 'the_content', function() use ( $checkout_id ) {
			ob_Start();
			echo Elementor::instance()->frontend->get_builder_content_for_display( (int) $checkout_id, true );
			return ob_get_clean();
		}, 5 );

		// Set sellkit canvas templates as the page template.
		add_action( 'template_redirect', function() {
			sellkit()->load_files( [
				'templates/canvas'
			] );

			exit;
		} );

		add_filter( 'sellkit_global_checkout_activated', function() {
			return true;
		} );

		add_action( 'sellkit_checkout_required_hidden_fields', function() use ( $checkout_id ) {
			?>
				<input type="hidden" name="sellkit_current_page_id" value="<?php echo esc_attr( $checkout_id ); ?>" >
				<input type="hidden" name="sellkit_global_checkout_id" value="<?php echo esc_attr( $checkout_id ); ?>" >
			<?php
		} );

		if ( ! empty( $bump_data ) ) {
			$bump_data = $this->get_valid_bumps( $bump_data );

			set_query_var( 'bump_data', $bump_data );
		}

		if ( ! empty( $optimization_data ) && CheckoutStep::apply_coupon_validation( $optimization_data ) ) {
			foreach ( $optimization_data['auto_apply_coupons'] as $auto_apply_coupon ) {
				wc()->cart->add_discount( get_the_title( $auto_apply_coupon['value'] ) );
			}

			wc_clear_notices();
		}
	}

	/**
	 * Checks all bumps and return data.
	 *
	 * @since 1.8.1
	 * @param array $bump_data Bump data.
	 * @return array
	 */
	public function get_valid_bumps( $bump_data ) {
		$valid_bumps = [];

		foreach ( $bump_data as $bump ) {
			$conditions = ! empty( $bump['data']['conditions'] ) ? $bump['data']['conditions'] : '';

			if ( ! empty( $conditions ) && empty( sellkit_conditions_validation( $conditions ) ) ) {
				continue;
			}

			$valid_bumps[] = $bump;
		}

		return $valid_bumps;
	}
}

new Checkout();
