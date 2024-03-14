<?php
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WFOCU_Common' ) ) {
	class xlwcty_Rule_Upstroke extends xlwcty_Rule_Base {

		public function __construct() {
			parent::__construct( 'stock_status' );
		}

		public function get_possibile_rule_operators() {
			$operators = array(
				'in'    => __( 'is', 'thank-you-page-for-woocommerce-nextmove' ),
				'notin' => __( 'is not', 'thank-you-page-for-woocommerce-nextmove' ),
			);

			return $operators;
		}

		public function get_possibile_rule_values() {
			$result  = array();
			$args    = array(
				'post_type'   => WFOCU_Common::get_funnel_post_type_slug(),
				'numberposts' => - 1,
				'post_status' => 'any',
			);
			$funnels = get_posts( $args );

			if ( ! empty( $funnels ) ) {
				foreach ( $funnels as $funnel ) {
					$result[ $funnel->ID ] = $funnel->post_title;
				}
			}

			return $result;
		}

		public function get_condition_input_type() {
			return 'Chosen_Select';
		}

		public function is_match( $rule_data, $order_id ) {
			$result = false;

			if ( isset( $rule_data['condition'] ) && isset( $rule_data['operator'] ) ) {
				$order = wc_get_order( $order_id );
				if ( ! $order instanceof WC_Order ) {
					return $this->return_is_match( $result, $rule_data );
				}

				$funnel_id = $order->get_meta( '_wfocu_funnel_id', true );
				$in        = false;
				if ( in_array( $funnel_id, $rule_data['condition'] ) ) {
					$in = true;
				}

				$result = 'in' === $rule_data['operator'] ? $in : ! $in;
			}

			return $this->return_is_match( $result, $rule_data );
		}

	}
}

if ( class_exists( 'WFACP_Core' ) ) {
	class xlwcty_Rule_Aerocheckout extends xlwcty_Rule_Base {

		public function __construct() {
			parent::__construct( 'stock_level' );
		}

		public function get_possibile_rule_operators() {
			$operators = array(
				'in'    => __( 'is', 'thank-you-page-for-woocommerce-nextmove' ),
				'notin' => __( 'is not', 'thank-you-page-for-woocommerce-nextmove' ),
			);

			return $operators;
		}

		public function get_possibile_rule_values() {
			$result = array();
			$data   = WFACP_Common::get_saved_pages();

			if ( is_array( $data ) && count( $data ) > 0 ) {

				foreach ( $data as $v ) {

					$result[ $v['ID'] ] = $v['post_title'];
				}
			}

			return $result;
		}

		public function get_condition_input_type() {
			return 'Chosen_Select';
		}

		public function is_match( $rule_data, $order_id ) {
			$result = false;

			if ( isset( $rule_data['condition'] ) && isset( $rule_data['operator'] ) ) {
				$order = wc_get_order( $order_id );
				if ( ! $order instanceof WC_Order ) {
					return $this->return_is_match( $result, $rule_data );
				}

				$aero_id = $order->get_meta( '_wfacp_post_id', true );
				$in      = false;
				if ( in_array( $aero_id, $rule_data['condition'] ) ) {
					$in = true;
				}

				$result = 'in' === $rule_data['operator'] ? $in : ! $in;
			}

			return $this->return_is_match( $result, $rule_data );
		}

	}
}
