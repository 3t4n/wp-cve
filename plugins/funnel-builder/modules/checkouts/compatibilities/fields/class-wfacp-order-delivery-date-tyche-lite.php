<?php

/**
 * Order delivery date pro tyche
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_Order_Delivery_Date_Tyche_lite
 */
if ( ! class_exists( 'WFACP_Compatibility_Order_Delivery_Date_Tyche_lite' ) ) {
	#[AllowDynamicProperties]

  class WFACP_Compatibility_Order_Delivery_Date_Tyche_lite {
		public function __construct() {
			add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
			add_action( 'wfacp_header_print_in_head', [ $this, 'enqueue_js' ] );
			add_filter( 'wfacp_html_fields_oddt', '__return_false' );
			add_action( 'process_wfacp_html', [ $this, 'call_birthday_addon_hook' ], 10, 3 );
			add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		}

		public function add_field( $fields ) {
			if ( $this->is_enable() ) {
				$fields['oddt'] = [
					'type'       => 'wfacp_html',
					'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'aw_addon_wrap', 'oddt' ],
					'id'         => 'oddt',
					'field_type' => 'advanced',
					'label'      => __( 'Delivery Date', 'funnel-builder' ),
				];
			}

			return $fields;
		}

		private function is_enable() {
			if ( class_exists( 'Order_Delivery_Date_Lite' ) ) {
				if ( get_option( 'orddd_lite_enable_delivery_date' ) === 'on' ) {
					return true;
				}
			}

			return false;
		}

		public function enqueue_js() {
			if ( $this->is_enable() ) {
				$instance = WFACP_Common::remove_actions( ORDDD_LITE_SHOPPING_CART_HOOK, 'Order_Delivery_Date_Lite', 'orddd_lite_front_scripts_js' );
				if ( $instance instanceof Order_Delivery_Date_Lite ) {
					add_action( 'wfacp_internal_css', [ $instance, 'orddd_lite_front_scripts_js' ] );
				}
			}
		}

		public function call_birthday_addon_hook( $field, $key, $args ) {
			if ( ! empty( $key ) && 'oddt' === $key && $this->is_enable() ) {
				Orddd_Lite_Process::orddd_lite_my_custom_checkout_field();
			}
		}

		public function add_default_wfacp_styling( $args, $key ) {
			if ( $key == 'e_deliverydate' ) {
				$args['input_class'] = array_merge( $args['input_class'], [ 'wfacp-form-control' ] );
				$args['label_class'] = array_merge( $args['label_class'], [ 'wfacp-form-control-label' ] );
				$args['class']       = array_merge( $args['class'], [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'aw_addon_wrap', 'oddt' ] );
			}

			return $args;
		}
	}

	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Order_Delivery_Date_Tyche_lite(), 'oddtl' );


}
