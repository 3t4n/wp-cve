<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Oxygen_WFTY_Customer_Details_Widget
 */
if ( ! class_exists( 'Oxygen_WFTY_Customer_Details_Widget' ) ) {

	#[AllowDynamicProperties]

  class Oxygen_WFTY_Customer_Details_Widget extends WFFN_ThankYou_WC_HTML_Block_Oxy {
		public $slug = 'wffn_thankyou_customer_details';
		protected $id = 'wffn_thankyou_customer_details';

		/**
		 * Oxygen_WFTY_Customer_Details_Widget constructor.
		 */
		public function __construct() {
			$this->name = __( 'Customer Details', 'funnel-builder' );

			parent::__construct();
		}


		public function name() {
			return $this->name;
		}

		/**
		 * @param $template WFACP_Template_Common;
		 */
		public function setup_controls() {
			$this->register_heading_fields();
			$this->register_details_fields();
		}


		protected function register_heading_fields() {
			$defaults        = WFFN_Core()->thank_you_pages->default_shortcode_settings();
			$tab_id          = $this->add_tab( __( 'Customer Details', 'funnel-builder' ) );
			$default_heading = isset( $defaults['customer_details_heading'] ) ? $defaults['customer_details_heading'] : __( 'Customer Details', 'funnel-builder' );
			$this->add_text( $tab_id, 'customer_details_heading', __( 'Heading' ), $default_heading );

			$this->add_heading( $tab_id, __( 'Layout', 'funnel-builder' ) );
			$this->add_select( $tab_id, 'customer_layout', __( 'Layout', 'funnel-builder' ), [
				'2c' => __( 'Two Columns', 'funnel-builder' ),
				'1c' => __( 'Full Width', 'funnel-builder' ),
			], '2c' );

			$this->add_typography( $tab_id, 'customer_details_heading', '.oxy-customer-details-wrapper .wfty-customer-info-heading.wfty_title' );


			do_action( 'wffn_additional_controls', $this );

		}

		protected function register_details_fields() {
			$tab_id = $this->add_tab( __( 'Details', 'funnel-builder' ) );
			$this->add_typography( $tab_id, 'typography_details_heading', '.oxy-customer-details-wrapper .wfty_customer_info .wfty_text_bold strong', __( 'Heading', 'funnel-builder' ) );
			$this->add_typography( $tab_id, 'typography_details_details', '.oxy-customer-details-wrapper .wffn_customer_details_table .wfty_wrap .wfty_box.wfty_customer_details_2_col table tr th,.oxy-customer-details-wrapper .wffn_customer_details_table .wfty_wrap .wfty_box.wfty_customer_details_2_col table tr td,.oxy-customer-details-wrapper .wffn_customer_details_table,.oxy-customer-details-wrapper .wfty_view,.oxy-customer-details-wrapper .wffn_customer_details_table *', __( 'Details', 'funnel-builder' ) );
		}


		protected function html( $settings, $defaults, $content ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			$heading_text    = $settings['customer_details_heading'];
			$customer_layout = ( isset( $settings['customer_layout'] ) && '2c' !== $settings['customer_layout'] ) ? ' wfty_full_width' : '2c'; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			$customer_layout .= ( isset( $settings['customer_layout_tablet'] ) && '2c' === $settings['customer_layout_tablet'] ) ? ' wfty_2c_tab_width' : ''; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			$customer_layout .= ( isset( $settings['customer_layout_phone'] ) && '2c' === $settings['customer_layout_phone'] ) ? ' wfty_2c_mob_width' : ''; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			if ( $customer_layout !== '' && $customer_layout !== '2c' ) {
				$customer_layout .= " wfty_cont_style";
			}

			?>
            <div class="oxy-customer-details-wrapper">
				<?php
				echo do_shortcode( '[wfty_customer_details layout_settings ="' . $customer_layout . '" customer_details_heading="' . $heading_text . '"]' );

				?>
            </div>
			<?php
		}


	}

	new Oxygen_WFTY_Customer_Details_Widget;
}