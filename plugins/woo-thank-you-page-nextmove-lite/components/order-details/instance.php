<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Order_Details extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';
	public $is_disable = true;

	public function __construct( $order = false ) {
		parent::__construct();
		$this->viewpath = __DIR__ . '/views/view.php';
		add_action( 'xlwcty_after_components_loaded', array( $this, 'setup_fields' ) );
		add_filter( 'wc_get_template', array( $this, 'subs_get_template' ), 10, 5 );
		add_action( 'xlwcty_after_component_data_setup_xlwcty_order_details', array( $this, 'setup_style' ) );

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {
			add_action( 'xlwcty_woocommerce_order_details_after_order_table', array( 'WC_Subscriptions_Order', 'add_subscriptions_to_view_order_templates' ), 10, 1 );
		}
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
			'downloads'            => $this->get_slug() . '_downloads',
			'desc'                 => $this->get_slug() . '_after_heading_desc',
			'desc_alignment'       => $this->get_slug() . '_after_heading_desc_alignment',
			'after_desc'           => $this->get_slug() . '_below_order_table_desc',
			'after_desc_alignment' => $this->get_slug() . '_below_order_table_desc_alignment',
			'display_images'       => $this->get_slug() . '_display_images',
			'border_style'         => $this->get_slug() . '_border_style',
			'border_width'         => $this->get_slug() . '_border_width',
			'border_color'         => $this->get_slug() . '_border_color',
			'component_bg_color'   => $this->get_slug() . '_component_bg',
		);
	}

	public function subs_get_template( $located, $template_name, $args, $template_path, $default_path ) {

		if ( 'myaccount/related-subscriptions.php' === $template_name ) {
			return __DIR__ . '/views/related-subscriptions.php';
		}

		return $located;
	}

	public function setup_style( $slug ) {
		if ( $this->is_enable() ) {
			$style = array();

			if ( '' !== $this->data->heading_font_size ) {
				$style['.xlwcty_order_details_2_col .xlwcty_title']['font-size']                  = $this->data->heading_font_size . 'px';
				$style['.xlwcty_order_details_default .xlwcty_title']['font-size']                = $this->data->heading_font_size . 'px';
				$style['.xlwcty_wrap .xlwcty_order_details_default .xlwcty_title']['line-height'] = ( $this->data->heading_font_size + 4 ) . 'px';
				$style['.xlwcty_wrap .xlwcty_order_details_2_col .xlwcty_title']['line-height']   = ( $this->data->heading_font_size + 4 ) . 'px';
			}
			if ( '' !== $this->data->heading_alignment ) {
				$style['.xlwcty_order_details_2_col .xlwcty_title']['text-align'] = $this->data->heading_alignment;
			}
			if ( '' !== $this->data->border_style ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_2_col']['border-style']   = $this->data->border_style;
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_default']['border-style'] = $this->data->border_style;
			}
			if ( (int) $this->data->border_width >= 0 ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_2_col']['border-width']   = (int) $this->data->border_width . 'px';
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_default']['border-width'] = (int) $this->data->border_width . 'px';
			}
			if ( '' !== $this->data->border_color ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_2_col']['border-color']   = $this->data->border_color;
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_default']['border-color'] = $this->data->border_color;
			}
			if ( '' !== $this->data->component_bg_color ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_2_col']['background-color']   = $this->data->component_bg_color;
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_order_details_default']['background-color'] = $this->data->component_bg_color;
			}
			parent::push_css( $slug, $style );
		}
	}

}

return XLWCTY_Order_Details::get_instance();
