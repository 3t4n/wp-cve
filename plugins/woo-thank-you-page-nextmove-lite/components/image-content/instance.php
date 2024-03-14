<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Content_Block_Image extends XLWCTY_Component {

	private static $instance = null;
	public $instance_campaign_data;
	public $is_disable = true;
	public $viewpath = '';
	public $is_multiple = true;
	public $component_limit = 1;

	public function __construct( $order = false ) {
		parent::__construct();
		$this->viewpath = __DIR__ . '/views/view.php';
		add_action( 'xlwcty_after_component_data_setup_xlwcty_image', array( $this, 'setup_style' ), 10, 2 );
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
			'desc'               => $this->get_slug() . '_desc_1',
			'desc_alignment'     => $this->get_slug() . '_desc_alignment_1',
			'layout'             => $this->get_slug() . '_layout_1',
			'img_source'         => $this->get_slug() . '_img_source_1',
			'img_link'           => $this->get_slug() . '_img_link_1',
			'img_cont_ratio'     => $this->get_slug() . '_img_cont_ratio_1',
			'img_l_source'       => $this->get_slug() . '_img_l_source_1',
			'img_l_link'         => $this->get_slug() . '_img_l_link_1',
			'show_btn'           => $this->get_slug() . '_show_btn_1',
			'btn_text'           => $this->get_slug() . '_btn_text_1',
			'btn_link'           => $this->get_slug() . '_btn_link_1',
			'btn_font_size'      => $this->get_slug() . '_btn_font_size_1',
			'btn_color'          => $this->get_slug() . '_btn_color_1',
			'editor'             => $this->get_slug() . '_editor_1',
			'btn_bg_color'       => $this->get_slug() . '_btn_bg_color_1',
			'img_r_source'       => $this->get_slug() . '_img_r_source_1',
			'img_r_link'         => $this->get_slug() . '_img_r_link_1',
			'border_style'       => $this->get_slug() . '_border_style_1',
			'border_width'       => $this->get_slug() . '_border_width_1',
			'border_color'       => $this->get_slug() . '_border_color_1',
			'component_bg_color' => $this->get_slug() . '_component_bg_1',
		);
	}

	public function prepare_out_put_data() {

		parent::prepare_out_put_data();

		if ( isset( $this->data->source ) && is_array( $this->data->source ) && count( $this->data->source ) > 0 ) {
			$this->data->source = current( $this->data->source );
		}
	}

	public function setup_style( $slug ) {
		if ( $this->is_enable() ) {
			if ( $this->data->heading_font_size != '' ) {
				$style['.xlwcty_wrap .xlwcty_imgBox.xlwcty_imgBox_1 .xlwcty_title']['font-size']   = $this->data->heading_font_size . 'px';
				$style['.xlwcty_wrap .xlwcty_imgBox.xlwcty_imgBox_1 .xlwcty_title']['line-height'] = ( $this->data->heading_font_size + 4 ) . 'px';
			}
			if ( $this->data->heading_alignment != '' ) {
				$style['.xlwcty_wrap .xlwcty_imgBox.xlwcty_imgBox_1 .xlwcty_title']['text-align'] = $this->data->heading_alignment;
			}
			if ( $this->data->border_style != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1']['border-style'] = $this->data->border_style;
			}
			if ( (int) $this->data->border_width >= 0 ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1']['border-width'] = (int) $this->data->border_width . 'px';
			}
			if ( $this->data->border_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1']['border-color'] = $this->data->border_color;
			}
			if ( $this->data->component_bg_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1']['background-color'] = $this->data->component_bg_color;
			}
			if ( $this->data->btn_font_size != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1 .xlwcty_btn']['font-size'] = $this->data->btn_font_size . 'px';
			}
			if ( $this->data->btn_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1 .xlwcty_btn']['color'] = $this->data->btn_color;
			}
			if ( $this->data->btn_bg_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1 .xlwcty_btn']['background'] = $this->data->btn_bg_color;
				$rgba                                                                                      = XLWCTY_Common::hex2rgb( $this->data->btn_bg_color, true );
				if ( $rgba != '' ) {
					$style['.xlwcty_wrap .xlwcty_Box.xlwcty_imgBox.xlwcty_imgBox_1 .xlwcty_btn:hover']['background'] = "rgba({$rgba},0.70)";
				}
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

return XLWCTY_Content_Block_Image::get_instance();
