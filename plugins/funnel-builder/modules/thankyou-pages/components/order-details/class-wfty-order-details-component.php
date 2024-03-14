<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFTY_Order_Details_Component' ) ) {
	#[AllowDynamicProperties]

  class WFTY_Order_Details_Component extends WFTY_Shortcode_Component_Abstract {

		public function __construct( $shortcode_args = [] ) {
			parent::__construct( $shortcode_args );
			add_filter( 'wc_get_template', array( $this, 'subs_get_template' ), 10, 5 );

			if ( class_exists( 'WC_Subscriptions_Order' ) ) {
				add_action( 'wfty_woocommerce_order_subscription', array( 'WC_Subscriptions_Order', 'add_subscriptions_to_view_order_templates' ), 10, 1 );
				add_action( 'wfty_subscription_notice', array( 'WC_Subscriptions_Order', 'subscription_thank_you' ) );
			}
		}

		public function get_meta() {
			return array(
				'border_style'       => 'solid',
				'border_width'       => '1',
				'border_color'       => '#d9d9d9',
				'component_bg_color' => '#ffffff',
			);
		}

		public function setup_data() {
			$order_id                                          = ( $this->order instanceof WC_Order ) ? $this->order->get_id() : 0;
			$this->data['order_details_img']                   = isset( $this->data['order_details_img'] ) ? $this->data['order_details_img'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'order_details_img', $order_id );
			$this->data['order_details_heading']               = isset( $this->data['order_details_heading'] ) ? $this->data['order_details_heading'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'order_details_heading', $order_id );
			$this->data['order_subscription_heading']          = isset( $this->data['order_subscription_heading'] ) ? $this->data['order_subscription_heading'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'order_subscription_heading', $order_id );
			$this->data['order_downloads_btn_text']            = isset( $this->data['order_downloads_btn_text'] ) ? $this->data['order_downloads_btn_text'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'order_downloads_btn_text', $order_id );
			$this->data['order_downloads_show_file_downloads'] = isset( $this->data['order_downloads_show_file_downloads'] ) ? $this->data['order_downloads_show_file_downloads'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'order_downloads_show_file_downloads', $order_id );
			$this->data['order_downloads_show_file_expiry']    = isset( $this->data['order_downloads_show_file_expiry'] ) ? $this->data['order_downloads_show_file_expiry'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'order_downloads_show_file_expiry', $order_id );
			$this->data['order_download_heading']              = isset( $this->data['order_download_heading'] ) ? $this->data['order_download_heading'] : WFFN_Core()->thank_you_pages->get_optionsShortCode( 'order_download_heading', $order_id );
		}

		public function subs_get_template( $located, $template_name, $args, $template_path, $default_path ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

			if ( 'myaccount/related-subscriptions.php' === $template_name ) {
				return __DIR__ . '/views/related-subscriptions.php';
			}

			return $located;
		}

		public function get_slug() {
			return '_wfty_order_details_shortcode_component';
		}

		public function render() {
			if ( false !== $this->order ) {
				$this->setup_data();
				WFFN_Core()->thank_you_pages->data->component_order_details = $this;
				echo '<div class="wfty_wrap wfty_frontend_view">';
				include __DIR__ . '/views/view.php';
				echo '</div>';
			}
		}

		public function render_dummy() {
			$this->setup_data();
			echo '<div class="wfty_wrap">';
			include __DIR__ . '/views/view_dummy.php';
			echo '</div>';
		}
	}
}