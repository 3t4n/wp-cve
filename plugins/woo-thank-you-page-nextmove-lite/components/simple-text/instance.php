<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Simple_text extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';
	public $is_disable = true;
	public $is_multiple = true;
	public $component_limit = 1;

	public function __construct( $order = false ) {
		parent::__construct();
		$this->viewpath = __DIR__ . '/views/view.php';
		add_action( 'xlwcty_after_component_data_setup_xlwcty_simple_text', array( $this, 'setup_style' ), 10, 2 );
		add_action( 'xlwcty_after_components_loaded', array( $this, 'setup_fields' ) );
	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function setup_fields() {
		$this->fields = array(
			'heading'            => $this->get_slug() . '_heading_1',
			'heading_font_size'  => $this->get_slug() . '_heading_font_size_1',
			'heading_alignment'  => $this->get_slug() . '_heading_alignment_1',
			'text'               => $this->get_slug() . '_text_1',
			'alignment'          => $this->get_slug() . '_alignment_1',
			'heading_alignment'  => $this->get_slug() . '_heading_alignment_1',
			'border_style'       => $this->get_slug() . '_border_style_1',
			'border_width'       => $this->get_slug() . '_border_width_1',
			'border_color'       => $this->get_slug() . '_border_color_1',
			'component_bg_color' => $this->get_slug() . '_component_bg_1',
		);
	}

	public function prepare_out_put_data() {
		parent::prepare_out_put_data();
	}

	public function setup_style( $slug ) {
		if ( $this->is_enable() ) {

			if ( $this->data->heading_font_size != '' ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1 .xlwcty_title']['font-size']   = $this->data->heading_font_size . 'px';
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1 .xlwcty_title']['line-height'] = ( $this->data->heading_font_size + 4 ) . 'px';
			}
			if ( $this->data->alignment != '' ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1 .xlwcty_content']['text-align'] = $this->data->alignment;
			}
			if ( $this->data->heading_alignment != '' ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1 .xlwcty_title']['text-align'] = $this->data->heading_alignment;
			}
			if ( $this->data->border_style != '' ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1']['border-style'] = $this->data->border_style;
			}
			if ( (int) $this->data->border_width >= 0 ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1']['border-width'] = (int) $this->data->border_width . 'px';
			}
			if ( $this->data->border_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1']['border-color'] = $this->data->border_color;
			}
			if ( $this->data->component_bg_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty_textBoxSimpleText.xlwcty_textBoxSimpleText_1']['background-color'] = $this->data->component_bg_color;
			}
			parent::push_css( $slug . '1', $style );
		}
	}

	public function is_enable( $index = 0 ) {
		if ( XLWCTY_Core()->data->get_meta( $this->get_slug() . '_enable_1', 'raw' ) == '1' ) {
			return true;
		}
	}

}

return XLWCTY_Simple_text::get_instance();
