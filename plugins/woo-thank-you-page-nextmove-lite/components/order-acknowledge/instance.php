<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Content_Order_Acknowledge extends XLWCTY_Component {

	private static $instance = null;
	public $is_disable = false;
	public $viewpath = '';
	public $source = '';
	public $height = '';
	public $width = '';
	public $heading1 = '';
	public $heading1_color = '';
	public $heading1_font = '';
	public $heading2 = '';
	public $heading2_color = '';
	public $heading2_font = '';
	public $icon_type = '';
	public $icon_html = '';

	public function __construct( $order = false ) {
		parent::__construct();
		$this->viewpath = __DIR__ . '/views/view.php';
		add_action( 'xlwcty_after_components_loaded', array( $this, 'setup_fields' ) );
		add_action( 'xlwcty_after_component_data_setup_xlwcty_order', array( $this, 'setup_style' ) );
	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function setup_fields() {
		$this->fields = array(
			'icon'               => $this->get_slug() . '_icon',
			'icon_builtin'       => $this->get_slug() . '_built_in',
			'icon_builtin_color' => $this->get_slug() . '_built_in_color',
			'icon_custom'        => $this->get_slug() . '_icon_custom',
			'heading'            => $this->get_slug() . '_heading1',
			'heading_font_size'  => $this->get_slug() . '_heading1_font_size',
			'heading_color'      => $this->get_slug() . '_heading1_color',
			'heading2'           => $this->get_slug() . '_heading2',
			'heading2_font_size' => $this->get_slug() . '_heading2_font_size',
			'heading2_color'     => $this->get_slug() . '_heading2_color',
		);
	}

	public function prepare_out_put_data() {
		parent::prepare_out_put_data();
		$icon_html = '';
		if ( $this->data->icon == 'built_in' && $this->data->icon_builtin != '' ) {
			$icon_html = '<div class="xlwcty_circle"><i class="';
			if ( strpos( $this->data->icon_builtin, 'xlwcty-fa' ) !== false ) {
				$icon_html .= 'xlwcty-fa ';
			}
			$icon_html .= $this->data->icon_builtin;
			$icon_html .= '"></i></div>';
		} elseif ( $this->data->icon == 'custom' && $this->data->icon_custom != '' ) {
			$icon_html = '<div class="xlwcty_circle"><img src="' . $this->data->icon_custom . '" /></div>';
		}
		$this->icon_html = $icon_html;
	}

	public function setup_style( $slug ) {
		if ( $this->is_enable() ) {
			$style = array();
			if ( $this->data->icon == 'none' ) {
				$style['.xlwcty_wrap.xlwcty_circle_show .xlwcty_in_wrap']['padding-left']                    = '0';
				$style['.xlwcty_wrap.xlwcty_circle_show .xlwcty_in_wrap .xlwcty_order_info']['padding-left'] = '0';
			}
			if ( $this->data->heading_font_size != '' ) {
				$style['.xlwcty_wrap .xlwcty_order_info .xlwcty_order_no']['font-size']   = $this->data->heading_font_size . 'px';
				$style['.xlwcty_wrap .xlwcty_order_info .xlwcty_order_no']['line-height'] = ( $this->data->heading_font_size + 4 ) . 'px';
			}
			if ( $this->data->heading_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_order_info .xlwcty_order_no']['color'] = $this->data->heading_color;
			}
			if ( $this->data->heading2_font_size != '' ) {
				$style['.xlwcty_wrap .xlwcty_order_info .xlwcty_userN']['font-size']   = $this->data->heading2_font_size . 'px';
				$style['.xlwcty_wrap .xlwcty_order_info .xlwcty_userN']['line-height'] = ( $this->data->heading2_font_size + 4 ) . 'px';
			}
			if ( $this->data->heading2_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_order_info .xlwcty_userN']['color'] = $this->data->heading2_color;
			}
			if ( $this->data->icon == 'built_in' && $this->data->icon_builtin != '' && $this->data->icon_builtin_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_circle']['border-color'] = $this->data->icon_builtin_color;
				$style['.xlwcty_wrap .xlwcty_circle']['color']        = $this->data->icon_builtin_color;
				$style['.xlwcty_wrap .xlwcty_circle i']['color']      = $this->data->icon_builtin_color;
			}
			if ( $this->data->icon == 'none' ) {
				$style['.xlwcty_wrap.xlwcty_circle_show .xlwcty_circle']['display'] = 'none';
			}
			parent::push_css( $slug, $style );
		}
	}

}

return XLWCTY_Content_Order_Acknowledge::get_instance();
