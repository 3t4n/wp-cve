<?php

class Coupon_Code extends Tag_Base {
	/**
	 * Get class id.
	 *
	 * @return string
	 */
	public function get_id() {
		return '_coupon_code';
	}

	/**
	 * Get class title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Coupon Code', 'sellkit' );
	}

	/**
	 * Render true content.
	 *
	 * @param array $atts array of shortcode arguments.
	 * @return string
	 */
	public function render_content( $atts ) {
		$this->get_data();

		if ( empty( self::$order ) ) {
			return $this->shortcode_content( $atts );
		}

		$order_data = $this->get_coupon_data( self::$order );

		if ( empty( $order_data ) ) {
			return $this->shortcode_content( $atts );
		}

		return $order_data;
	}

	/**
	 * Get coupon data.
	 *
	 * @since 1.2.2
	 * @param object $order object of order parameters.
	 * @return array
	 */
	public function get_coupon_data( $order ) {
		if ( empty( $order->get_items( 'coupon' ) ) ) {
			return;
		}

		foreach ( $order->get_items( 'coupon' ) as $coupon_id => $coupon ) {
			$query_args = [
				'post_type' => 'shop_coupon',
				'post_title' => $coupon->get_name(),
				'posts_per_page' => 1,
			];

			$coupons_query      = new WP_Query( $query_args );
			$current_coupon_obj = (object) $coupons_query->get_posts()[0];
			$current_coupon_id  = $current_coupon_obj->ID;
			$current_coupon     = new WC_Coupon( $current_coupon_id );

			$order_data = $current_coupon->get_data();
		}

		return $order_data['code'];
	}
}
