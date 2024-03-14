<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Customer_Information extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';

	public function __construct( $order = false ) {
		parent::__construct();
		$this->viewpath = __DIR__ . '/views/view.php';

		add_action( 'xlwcty_after_component_data_setup_xlwcty_customer_information', array( $this, 'setup_style' ) );
		add_action( 'xlwcty_after_components_loaded', array( $this, 'setup_fields' ) );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function setup_fields() {
		$this->fields = array(
			'heading'              => $this->get_slug() . '_heading',
			'heading_font_size'    => $this->get_slug() . '_heading_font_size',
			'heading_alignment'    => $this->get_slug() . '_heading_alignment',
			'layout'               => $this->get_slug() . '_layout',
			'desc'                 => $this->get_slug() . '_after_heading_desc',
			'desc_alignment'       => $this->get_slug() . '_after_heading_desc_alignment',
			'after_desc'           => $this->get_slug() . '_after_customer_information_desc',
			'after_desc_alignment' => $this->get_slug() . '_after_customer_information_desc_alignment',
			'show_shipping'        => $this->get_slug() . '_show_shipping',
			'show_billing'         => $this->get_slug() . '_show_billing',
			'border_style'         => $this->get_slug() . '_border_style',
			'border_width'         => $this->get_slug() . '_border_width',
			'border_color'         => $this->get_slug() . '_border_color',
			'component_bg_color'   => $this->get_slug() . '_component_bg',
		);
	}

	public function prepare_out_put_data() {
		parent::prepare_out_put_data();
	}

	public function setup_style( $slug ) {
		if ( $this->is_enable() ) {
			$style = array();

			if ( '' !== $this->data->heading_font_size ) {
				$style['.xlwcty_customer_info .xlwcty_title']['font-size']                = $this->data->heading_font_size . 'px';
				$style['.xlwcty_wrap .xlwcty_customer_info .xlwcty_title']['line-height'] = ( $this->data->heading_font_size + 4 ) . 'px';
			}
			if ( '' !== $this->data->heading_alignment ) {
				$style['.xlwcty_customer_info .xlwcty_title']['text-align'] = $this->data->heading_alignment;
			}

			if ( '' !== $this->data->border_style ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_customer_info']['border-style'] = $this->data->border_style;
			}
			if ( (int) $this->data->border_width >= 0 ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_customer_info']['border-width'] = (int) $this->data->border_width . 'px';
			}
			if ( '' !== $this->data->border_color ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_customer_info']['border-color'] = $this->data->border_color;
			}
			if ( '' !== $this->data->component_bg_color ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_customer_info']['background-color'] = $this->data->component_bg_color;
			}

			parent::push_css( $slug, $style );
		}
	}

	public function xlwcty_format_billing_address( $billing_address, $order ) {
		if ( isset( $billing_address['first_name'] ) ) {
			unset( $billing_address['first_name'] );
		}
		if ( isset( $billing_address['last_name'] ) ) {
			unset( $billing_address['last_name'] );
		}
		if ( isset( $billing_address['company'] ) ) {
			unset( $billing_address['company'] );
		}

		/** checking if not array */
		if ( ! is_array( $billing_address ) ) {
			$billing_address = [];
		}

		return $billing_address;
	}

	public function xlwcty_format_shipping_address( $shipping_address, $order ) {
		if ( isset( $shipping_address['first_name'] ) ) {
			unset( $shipping_address['first_name'] );
		}
		if ( isset( $shipping_address['last_name'] ) ) {
			unset( $shipping_address['last_name'] );
		}
		if ( isset( $shipping_address['company'] ) ) {
			unset( $shipping_address['company'] );
		}

		/** checking if not array */
		if ( ! is_array( $shipping_address ) ) {
			$shipping_address = [];
		}

		return $shipping_address;
	}

}

return XLWCTY_Customer_Information::get_instance();
