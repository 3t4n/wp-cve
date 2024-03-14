<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Additional_Information extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';
	public $is_disable = true;

	public function __construct( $order = false ) {
		parent::__construct();
		$this->viewpath = __DIR__ . '/views/view.php';
		add_action( 'xlwcty_after_component_data_setup_xlwcty_additional_info', array( $this, 'setup_style' ), 10 );
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
			'border_style'       => $this->get_slug() . '_border_style',
			'border_width'       => $this->get_slug() . '_border_width',
			'border_color'       => $this->get_slug() . '_border_color',
			'component_bg_color' => $this->get_slug() . '_component_bg',
		);
	}


	public function setup_style( $slug ) {
		if ( $this->is_enable() ) {

			if ( '' !== $this->data->border_style ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty-wc-thankyou']['border-style'] = $this->data->border_style;
			}
			if ( (int) $this->data->border_width >= 0 ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty-wc-thankyou']['border-width'] = (int) $this->data->border_width . 'px';
			}
			if ( '' !== $this->data->border_color ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty-wc-thankyou']['border-color'] = $this->data->border_color;
			}
			if ( '' !== $this->data->component_bg_color ) {
				$style['.xlwcty_wrap .xlwcty_textBox.xlwcty-wc-thankyou']['background-color'] = $this->data->component_bg_color;
			}

			parent::push_css( $slug, $style );
		}
	}

}

return XLWCTY_Additional_Information::get_instance();
