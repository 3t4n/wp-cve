<?php

namespace Sellkit\Funnel\Steps;

defined( 'ABSPATH' ) || die();

use Sellkit\Global_Checkout\Checkout as Global_Checkout;
use Elementor\Plugin as Elementor;

/**
 * Class Sellkit_Thankyou.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @since 1.1.0
 */
class Thankyou extends Base_Step {

	/**
	 * Thankyou constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'template_redirect', [ $this, 'redirect_after_purchase' ], 10 );
	}

	/**
	 * Redirects after purchasing.
	 *
	 * @since 1.1.0
	 */
	public function redirect_after_purchase() {
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		global $wp;

		$funnel = sellkit_funnel();

		if ( ! empty( $funnel->funnel_id ) && 'thankyou' === $funnel->current_step_data['type']['key'] ) {
			return;
		}

		if ( ! function_exists( 'is_checkout' ) ) {
			return;
		}

		$order_key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! empty( $order_key ) ) {
			$order_id  = wc_get_order_id_by_order_key( $order_key );
			$order     = wc_get_order( $order_id );
			$next_step = ! empty( $order ) ? $order->get_meta( 'sellkit_funnel_next_step_data' ) : '';
			$funnel_id = (int) $order->get_meta( 'sellkit_funnel_id' );

			if ( empty( $next_step ) ) {
				return;
			}

			if ( empty( $funnel_id ) ) {
				$step_id   = intval( $next_step['page_id'] );
				$step_data = get_post_meta( $step_id, 'step_data', true );
				$funnel_id = (int) $step_data['funnel_id'];
			}

			// For the new method( upsell popup ) we show thakyou page right away after checkout step.
			$thankyou_id    = $this->find_funnel_thankyou_page( $funnel_id );
			$next_step_link = add_query_arg( [ 'order-key' => $order_key ], get_permalink( $thankyou_id ) );
			$last_price     = $order->get_total() - $order->get_total_discount() - $order->get_total_tax();

			$this->contacts->add_total_spent( $last_price, $funnel_id );

			$global_funnel_id = (int) get_option( Global_Checkout::SELLKIT_GLOBAL_CHECKOUT_OPTION );
			if ( $global_funnel_id === $funnel_id ) {
				$this->global_thankyou( $thankyou_id );
				exit();
			}

			wp_safe_redirect( $next_step_link );
			exit();
		}
	}

	/**
	 * Find funnel thankyou page using one of steps data.
	 *
	 * @param array $funnel_id funnel id.
	 * @since 1.6.2
	 */
	private function find_funnel_thankyou_page( $funnel_id ) {
		$funnel_data = get_post_meta( $funnel_id, 'nodes', true );
		$id          = 0;

		foreach ( $funnel_data as $step ) {
			$step['type'] = (array) $step['type'];

			if ( 'thankyou' === $step['type']['key'] ) {
				$id = $step['page_id'];
			}
		}

		return $id;
	}

	/**
	 * Show global thankyou page.
	 *
	 * @param int $thankyou_id thankyou page id.
	 * @since 1.8.6
	 */
	private function global_thankyou( $thankyou_id ) {
		// Remove previous content.
		remove_all_filters( 'the_content' );

		add_filter( 'sellkit_global_thankyou', '__return_true' );

		// Add new content.
		add_filter( 'the_content', function() use ( $thankyou_id ) {
			ob_Start();
			$content = Elementor::instance()->frontend->get_builder_content_for_display( (int) $thankyou_id, true );
			echo do_shortcode( $content );
			return ob_get_clean();
		}, 5 );

		sellkit()->load_files( [
			'templates/canvas'
		] );
	}
}
