<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Shipping_Billing_Based_Map extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';
	public $map_add = '';
	public $is_disable = false;

	public function __construct( $order = false ) {
		parent::__construct();
		$this->viewpath = __DIR__ . '/views/view.php';
		add_action( 'xlwcty_after_component_data_setup_xlwcty_google_map', array( $this, 'setup_style' ) );
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
			'address'               => $this->get_slug() . '_address',
			'marker_custom_address' => $this->get_slug() . '_marker_custom_address',
			'marker_icon'           => $this->get_slug() . '_icon',
			'built_in_icon'         => $this->get_slug() . '_built_in_icon',
			'custom_icon'           => $this->get_slug() . '_custom_icon',
			'marker_text'           => $this->get_slug() . '_marker_text',
			'zoom_level'            => $this->get_slug() . '_zoom_level',
			'style'                 => $this->get_slug() . '_style',
			'heading'               => $this->get_slug() . '_heading',
			'heading_font_size'     => $this->get_slug() . '_heading_font_size',
			'heading_alignment'     => $this->get_slug() . '_heading_alignment',
			'desc'                  => $this->get_slug() . '_desc',
			'desc_alignment'        => $this->get_slug() . '_desc_alignment',
			'border_style'          => $this->get_slug() . '_border_style',
			'border_width'          => $this->get_slug() . '_border_width',
			'border_color'          => $this->get_slug() . '_border_color',
			'component_bg_color'    => $this->get_slug() . '_component_bg',
		);
	}

	public function prepare_out_put_data() {
		parent::prepare_out_put_data();
		$this->data->icon = '';

		if ( ! empty( $this->data->marker_icon ) ) {
			if ( $this->data->marker_icon == 'built_in' ) {
				$this->data->icon = $this->get_icon_src( $this->data->built_in_icon );
			}
			if ( $this->data->marker_icon == 'custom' ) {
				$this->data->icon = $this->data->custom_icon;
			}
		}
	}

	public function get_icon_src( $icon ) {
		if ( empty( $icon ) ) {
			return;
		}
		$icon_path = plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'assets/img/map-pins/' . $icon . '.png';

		return $icon_path;
	}

	public function get_view_data( $key = 'order' ) {
		$order = parent::get_view_data( $key );
		$order = $order['order_data'];
		if ( $order instanceof WC_Order ) {
			if ( 'custom' == $this->data->address && isset( $this->data->marker_custom_address ) && ! empty( $this->data->marker_custom_address ) ) {
				$this->data->map_add = $this->data->marker_custom_address;
			} else {
				$billigAddress = $order->get_address();

				$street_address = '';
				if ( ! empty( $billigAddress['address_1'] ) ) {
					$street_address = $billigAddress['address_1'];
				}
				if ( ! empty( $billigAddress['address_2'] ) ) {
					$street_address .= ' ' . $billigAddress['address_2'];
				}
				if ( ! empty( $billigAddress['city'] ) ) {
					$street_address .= ', ' . $billigAddress['city'];
				}
				if ( ! empty( $billigAddress['country'] ) ) {
					$street_address .= ', ' . $billigAddress['country'];
				}

				$this->data->map_add = $street_address;
				if ( $this->data->address == 'shipping' ) {
					unset( $billigAddress );
					$street_address = '';
					$billigAddress  = $order->get_address( 'shipping' );
					if ( ! empty( $billigAddress['address_1'] ) ) {
						$street_address = $billigAddress['address_1'];
					}
					if ( ! empty( $billigAddress['address_2'] ) ) {
						$street_address .= ' ' . $billigAddress['address_2'];
					}
					if ( ! empty( $billigAddress['city'] ) ) {
						$street_address .= ', ' . $billigAddress['city'];
					}
					if ( ! empty( $billigAddress['country'] ) ) {
						$street_address .= ', ' . $billigAddress['country'];
					}
					if ( ! empty( $street_address ) ) {
						$this->data->map_add = $street_address;
					}
				}
			}

			return array(
				'campaign_data' => $this->instance_campaign_data,
				'order_data'    => $order,
			);
		} else {
			return array(
				'order_id' => 0,
			);
		}
	}

	public function setup_style( $slug ) {
		if ( $this->is_enable() ) {
			if ( $this->data->heading_font_size != '' ) {
				$style['.xlwcty_Map .xlwcty_title']['font-size']                = $this->data->heading_font_size . 'px';
				$style['.xlwcty_wrap .xlwcty_Map .xlwcty_title']['line-height'] = ( $this->data->heading_font_size + 4 ) . 'px';
			}
			if ( $this->data->heading_alignment != '' ) {
				$style['.xlwcty_Map .xlwcty_title']['text-align'] = $this->data->heading_alignment;
			}

			if ( $this->data->border_style != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_Map']['border-style'] = $this->data->border_style;
			}
			if ( (int) $this->data->border_width >= 0 ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_Map']['border-width'] = (int) $this->data->border_width . 'px';
			}
			if ( $this->data->border_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_Map']['border-color'] = $this->data->border_color;
			}
			if ( $this->data->component_bg_color != '' ) {
				$style['.xlwcty_wrap .xlwcty_Box.xlwcty_Map']['background-color'] = $this->data->component_bg_color;
			}
			parent::push_css( $slug, $style );
		}
	}

}

return XLWCTY_Shipping_Billing_Based_Map::get_instance();
