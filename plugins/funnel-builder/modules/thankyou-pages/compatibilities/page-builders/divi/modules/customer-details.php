<?php

#[AllowDynamicProperties]

  class WFTY_Customer_Details extends WFTY_Divi_HTML_BLOCK {
	public $slug = 'wfty_customer_details';
	protected $main_css = '%%order_class%%.et_wfty_customer_details';

	public function __construct() {
		parent::__construct();
		add_action( 'wp_footer', [ $this, 'localize_locals' ] );
	}

	public function setup_data() {
		$tab_id = $this->add_tab( __( 'Customer Details', 'funnel-builder' ), 5 );

		$this->add_text( $tab_id, 'heading', __( 'Heading', 'funnel-builder' ), __( 'Customer Details', 'funnel-builder' ) );

		$this->add_select( $tab_id, 'customer_layout', __( 'Layout', 'funnel-builder' ), [
			'2c' => __( 'Two Columns', 'elementor' ),
			'1c' => __( 'Full Width', 'elementor' ),
		], '2c' );

		$this->style_field();

	}

	private function style_field() {

		$key = "wfty_customer_details";

		$head_id = $this->add_tab( __( 'Heading', 'funnel-builder' ), 2 );

		$font_side_default = array(
			'font_size'   => array(
				'default' => '24px',
				'unit'    => 'px'
			),
			'line_height' => array(
				'default' => '1.5',
				'unit'    => 'em'
			),
		);

		$this->add_typography( $head_id, $key . '_heading_typography', '%%order_class%% .wfty-customer-info-heading.wfty_title', '', '', $font_side_default );
		$this->add_color( $head_id, $key . '_heading_color', '%%order_class%% .wfty-customer-info-heading.wfty_title', __( 'Color', 'funnel-builder' ), '#000000' );
		$this->add_text_alignments( $head_id, 'align', '%%order_class%% .wfty-customer-info-heading.wfty_title', '', 'left' );

		$det_id = $this->add_tab( __( 'Details', 'funnel-builder' ), 2 );

		$this->add_heading( $det_id, __( 'Heading', 'funnel-builder' ) );

		$font_side_default['font_size']['default'] = '20px';
		$this->add_typography( $det_id, $key . '_det_heading_typography', '%%order_class%% .wfty_customer_info .wfty_text_bold strong', '', '', $font_side_default );
		$this->add_color( $det_id, $key . '_det_heading_color', '%%order_class%% .wfty_customer_info .wfty_text_bold strong', __( 'Color', 'funnel-builder' ), '#000000' );

		$this->add_heading( $det_id, __( 'Details', 'funnel-builder' ) );

		$font_side_default['font_size']['default'] = '15px';
		$this->add_typography( $det_id, $key . '_det_text_typography', '%%order_class%% .wffn_customer_details_table .wfty_wrap .wfty_box.wfty_customer_details_2_col table tr th, %%order_class%% .wffn_customer_details_table .wfty_wrap .wfty_box.wfty_customer_details_2_col table tr td, %%order_class%% .wffn_customer_details_table, %%order_class%% .wfty_view, %%order_class%% .wffn_customer_details_table *', '', '', $font_side_default );
		$this->add_color( $det_id, $key . '_det_text_color', '%%order_class%% .wffn_customer_details_table .wfty_wrap .wfty_box.wfty_customer_details_2_col table tr th, %%order_class%% .wffn_customer_details_table .wfty_wrap .wfty_box.wfty_customer_details_2_col table tr td, %%order_class%% .wffn_customer_details_table, %%order_class%% .wfty_view, %%order_class%% .wffn_customer_details_table *', __( 'Color', 'funnel-builder' ), '#565656' );

	}

	public function localize_locals() {
		$data = array(
			'shipping'   => 'false',
			'email_text' => __( 'Email', 'funnel-builder' ),
			'phone_text' => __( 'Phone', 'funnel-builder' ),
			'bill_text'  => __( 'Billing address', 'funnel-builder' ),
			'ship_text'  => __( 'Shipping address', 'funnel-builder' ),
		);

		$shipping_option = get_option( 'woocommerce_ship_to_countries' );
		if ( 'disabled' !== $shipping_option ) {
			$data['shipping'] = 'true';
		}

		$data = implode( ', ', array_map( function ( $v, $k ) {
			return sprintf( "%s:'%s'", $k, $v );
		}, $data, array_keys( $data ) ) );

		?>
        <script>
            let wftyDiviCustomer = {<?php echo $data; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>};
        </script>
		<?php
	}

	public function html() {
		$settings        = $this->props;
		$heading_text    = $settings['heading'];
		$customer_layout = ( isset( $settings['customer_layout'] ) && '2c' !== $settings['customer_layout'] ) ? ' wfty_full_width' : '2c'; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		$customer_layout .= ( isset( $settings['customer_layout_tablet'] ) && '2c' === $settings['customer_layout_tablet'] ) ? ' wfty_2c_tab_width' : ''; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		$customer_layout .= ( isset( $settings['customer_layout_phone'] ) && '2c' === $settings['customer_layout_phone'] ) ? ' wfty_2c_mob_width' : ''; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		if ( $customer_layout !== '' && $customer_layout !== '2c' ) {
			$customer_layout .= " wfty_cont_style";
		}
		ob_start();
		?>
		<?php
		echo do_shortcode( '[wfty_customer_details layout_settings ="' . $customer_layout . '" customer_details_heading="' . $heading_text . '"]' );
		?>
		<?php
		return ob_get_clean();
	}


}

return new WFTY_Customer_Details;