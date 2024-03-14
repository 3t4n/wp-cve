<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'WFTY_Customer_Info_Component' ) ) {
	#[AllowDynamicProperties]

  class WFTY_Customer_Info_Component extends WFTY_Shortcode_Component_Abstract {

		public function __construct( $shortcode_args = [] ) {
			parent::__construct( $shortcode_args );
		}

		public function get_meta() {
			return array(
				'heading_font_size'  => '20',
				'heading_alignment'  => 'left',
				'border_style'       => 'solid',
				'border_width'       => '1',
				'border_color'       => '#d9d9d9',
				'component_bg_color' => '#ffffff',
			);
		}

		public function get_slug() {
			return '_wfty_customer_info_shortcode_component';
		}

		public function render() {
			if ( false !== $this->order ) {
				$this->setup_data();
				echo '<div class="wfty_wrap">';
				include __DIR__ . '/views/view.php';
				echo '</div>';
			}
		}

		public function render_dummy( $dummy_data ) {
			$this->setup_data();
			$billing_address = $shipping_address = $this->get_formatted_address( $dummy_data ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			echo '<div class="wfty_wrap">';
			include __DIR__ . '/views/view_dummy.php';
			echo '</div>';
		}

		public function setup_data() {
			$order_id = ( $this->order instanceof WC_Order ) ? $this->order->get_id() : 0;

			$this->data['customer_details_heading'] = isset( $this->data['customer_details_heading'] ) ? $this->data['customer_details_heading'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'customer_details_heading', $order_id );
			$this->data['layout_settings']          = isset( $this->data['layout_settings'] ) ? $this->data['layout_settings'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'layout_settings', $order_id );
		}

		public function get_formatted_address( $dummy_data ) {
			if ( isset( $dummy_data['name'] ) ) {
				unset( $dummy_data['name'] );
			}
			if ( isset( $dummy_data['email'] ) ) {
				unset( $dummy_data['email'] );
			}

			return WC()->countries->get_formatted_address( $dummy_data );
		}

	}
}