<?php

class Supreme_LeafletMapItem extends ET_Builder_Module {

	public $slug       = 'supreme_leaflet_map_item';
	public $vb_support = 'on';
	public $type       = 'child';

	protected $module_credits = array(
		'module_uri' => 'https://divisupreme.com/',
		'author'     => 'Divi Supreme',
		'author_uri' => 'https://divisupreme.com/about/',
	);

	public function init() {
		$this->name = esc_html__( 'Leaflet Pin', 'supreme-maps' );

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content'    => esc_html__( 'Text', 'supreme-maps' ),
					'map'             => esc_html__( 'Map', 'supreme-maps' ),
					'marker_settings' => esc_html__( 'Pin', 'supreme-maps' ),
				),
			),

			'advanced' => array(
				'toggles' => array(),
			),
		);
	}

	public function get_advanced_fields_config() {
		$advanced_fields = array();

		$advanced_fields['box_shadow']      = array(
			'default' => array(
				'css' => array(
					'overlay' => 'inset',
				),
			),
		);
		$advanced_fields['margin_padding']  = array(
			'css' => array(
				'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling.
			),
		);
		$advanced_fields['filters']         = array(
			'css'                  => array(
				'main' => '%%order_class%%',
			),
			'child_filters_target' => array(
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'child_filters',
				'label'       => esc_html__( 'Map', 'supreme-maps' ),
			),
		);
		$advanced_fields['child_filters']   = array(
			'css' => array(
				'main' => '%%order_class%%',
			),
		);
		$advanced_fields['height']          = array(
			'css'     => array(
				'main' => '%%order_class%% > .supreme_leaflet_map_leaflet_map_container',
			),
			'options' => array(
				'height' => array(
					'default'        => '440px',
					'default_tablet' => '350px',
					'default_phone'  => '200px',
				),
			),
		);
		$advanced_fields['fonts']           = false;
		$advanced_fields['text']            = false;
		$advanced_fields['button']          = false;
		$advanced_fields['position_fields'] = array(
			'default' => 'relative',
		);

		return $advanced_fields;
	}

	public function get_fields() {
		return array(
			'title'              => array(
				'label'           => et_builder_i18n( 'Title' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'The title will be used within the tab button for this tab.', 'supreme-maps' ),
				'toggle_slug'     => 'main_content',
				// 'dynamic_content' => 'text',
				// 'mobile_options'  => true,
				// 'hover'           => 'tabs',
			),
			'pin_address'        => array(
				'label'             => esc_html__( 'Map Pin Address', 'supreme-maps' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'class'             => array( 'et_pb_pin_address' ),
				'description'       => esc_html__( 'Enter an address for this map pin, and the address will be geocoded and displayed on the map below.', 'supreme-maps' ),
				'additional_button' => sprintf(
					'<a href="#" class="et_pb_find_address button">%1$s</a>',
					esc_html__( 'Find', 'supreme-maps' )
				),
				'toggle_slug'       => 'map',
			),
			'zoom_level'         => array(
				'type'             => 'hidden',
				'class'            => array( 'et_pb_zoom_level' ),
				'default'          => '18',
				'default_on_front' => '',
				'option_category'  => 'basic_option',
			),
			'pin_address_lat'    => array(
				'type'            => 'hidden',
				'class'           => array( 'et_pb_pin_address_lat' ),
				'option_category' => 'basic_option',
			),
			'pin_address_lng'    => array(
				'type'            => 'hidden',
				'class'           => array( 'et_pb_pin_address_lng' ),
				'option_category' => 'basic_option',
			),
			'map_center_map'     => array(
				'type'                  => 'center_map',
				'option_category'       => 'basic_option',
				'use_container_wrapper' => false,
				'toggle_slug'           => 'map',
				'show_if_not'           => array(
					'use_custom_latlng' => 'on',
				),
			),
			'use_custom_latlng'  => array(
				'label'       => esc_html__( 'Use Custom Latitude and Longitude', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'map',
			),
			'address_custom_lat' => array(
				'label'           => esc_html__( 'Latitude', 'supreme-maps' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter your custom Longitude.', 'supreme-maps' ),
				'toggle_slug'     => 'map',
				'show_if'         => array(
					'use_custom_latlng' => 'on',
				),
			),
			'address_custom_lng' => array(
				'label'           => esc_html__( 'Longitude', 'supreme-maps' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter your custom Longitude.', 'dicm-divi-custom-modules' ),
				'toggle_slug'     => 'map',
				'show_if'         => array(
					'use_custom_latlng' => 'on',
				),
			),
			'content'            => array(
				'label'           => et_builder_i18n( 'Body' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can define the content that will be placed within the infobox for the pin.', 'supreme-maps' ),
				'toggle_slug'     => 'main_content',
				// 'dynamic_content' => 'text',
				// 'mobile_options'  => true,
				// 'hover'           => 'tabs',
			),
			'use_marker_custom'  => array(
				'label'       => esc_html__( 'Use Custom Marker Icon/Image', 'dsm-supreme-modules-pro-for-divi' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'marker_settings',
			),
			'marker_icon_src'    => array(
				'label'              => esc_html__( 'Marker Icon/Image Upload', 'et_builder' ),
				'type'               => 'upload',
				'upload_button_text' => esc_attr__( 'Upload an Marker image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose an Marker Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Icon/Image', 'et_builder' ),
				'toggle_slug'        => 'marker_settings',
				'show_if'            => array(
					'use_marker_custom' => 'on',
				),
			),
			'marker_height'      => array(
				'label'            => esc_html__( 'Marker Icon/Image Height (px)', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '36',
				'default_on_front' => '36',
				'default_unit'     => '',
				'validate_unit'    => false,
				'mobile_options'   => false,
				'unitless'         => true,
				'responsive'       => false,
				'range_settings'   => array(
					'min'  => '10',
					'max'  => '100',
					'step' => '1',
				),
				'toggle_slug'      => 'marker_settings',
				'show_if'          => array(
					'use_marker_custom' => 'on',
				),
			),
			'marker_width'       => array(
				'label'            => esc_html__( 'Marker Icon/Image Width (px)', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '24',
				'default_on_front' => '24',
				'default_unit'     => '',
				'validate_unit'    => false,
				'mobile_options'   => false,
				'unitless'         => true,
				'responsive'       => false,
				'range_settings'   => array(
					'min'  => '10',
					'max'  => '100',
					'step' => '1',
				),
				'toggle_slug'      => 'marker_settings',
				'show_if'          => array(
					'use_marker_custom' => 'on',
				),
			),
		);
	}

	public function before_render() {

	}

	/**
	 * Generates the module's HTML output based on {@see self::$props}.
	 *
	 * @since 1.0
	 *
	 * @param array  $attrs       List of unprocessed attributes.
	 * @param string $content     Content being processed.
	 * @param string $render_slug Slug of module that is used for rendering output.
	 *
	 * @return string The module's HTML output.
	 */
	public function render( $attrs, $content, $render_slug ) {
		$multi_view         = et_pb_multi_view_options( $this );
		$use_custom_latlng  = $this->props['use_custom_latlng'];
		$address_custom_lat = $this->props['address_custom_lat'];
		$address_custom_lng = $this->props['address_custom_lng'];
		$link_option_url    = $this->props['link_option_url'];
		$url_new_window     = $this->props['link_option_url_new_window'];

		$title = $multi_view->render_element(
			array(
				'tag'     => 'div',
				'content' => '{{title}}',
				'attrs'   => array(
					'class' => 'supreme_leaflet_pin_title',
				),
			)
		);

		$content = $multi_view->render_element(
			array(
				'tag'     => 'div',
				'content' => '{{content}}',
				'attrs'   => array(
					'class' => 'supreme_leaflet_pin_content',
				),
			)
		);

		$pins = array(
			'lat'                => esc_attr( et_()->to_css_decimal( $this->props['pin_address_lat'] ) ),
			'lng'                => esc_attr( et_()->to_css_decimal( $this->props['pin_address_lng'] ) ),
			'use_custom_latlng'  => 'off' !== $use_custom_latlng ? true : false,
			'address_custom_lat' => isset( $address_custom_lat ) ? esc_attr( et_()->to_css_decimal( $address_custom_lat ) ) : 0,
			'address_custom_lng' => isset( $address_custom_lng ) ? esc_attr( et_()->to_css_decimal( $address_custom_lng ) ) : 0,
			'marker_custom'      => 'off' !== $this->props['use_marker_custom'] ? true : false,
			'link_option_url'    => '' !== $link_option_url ? esc_url( $link_option_url ) : '',
			'url_new_window'     => 'off' !== $url_new_window ? true : false,
		);

		$pins_icon = array();

		if ( 'off' !== $this->props['use_marker_custom'] ) {
			$pins_icon = array(
				'icon'        => $this->props['marker_icon_src'] ? esc_url( $this->props['marker_icon_src'] ) : '',
				'icon_height' => $this->props['marker_height'] ? intval( $this->props['marker_height'] ) : 36,
				'icon_width'  => $this->props['marker_width'] ? intval( $this->props['marker_width'] ) : 24,

			);
		}

		$pin_merge = array_merge( $pins, $pins_icon );

		$pin_attr = wp_json_encode( $pin_merge );

		$this->remove_classname(
			array(
				'et_pb_module',
			)
		);

		return sprintf( '<div data-supreme-leaflet-pin=%1$s>%2$s%3$s</div>', esc_attr( $pin_attr ), $title, $content );
	}
}

new Supreme_LeafletMapItem();
