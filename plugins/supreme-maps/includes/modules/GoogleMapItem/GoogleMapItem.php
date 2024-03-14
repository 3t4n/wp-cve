<?php

class Supreme_GoogleMapItem extends ET_Builder_Module {

	public $slug       = 'supreme_google_map_item';
	public $vb_support = 'on';
	public $type       = 'child';

	protected $module_credits = array(
		'module_uri' => 'https://divisupreme.com/',
		'author'     => 'Divi Supreme',
		'author_uri' => 'https://divisupreme.com/about/',
	);

	public function init() {
		$this->name            = esc_html__( 'Google Pin', 'supreme-maps' );
		$this->plural          = esc_html__( 'Google Pins', 'et_builder' );
		$this->child_title_var = 'title';
		$this->custom_css_tab  = false;

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content'    => esc_html__( 'Text', 'supreme-maps' ),
					'map'             => esc_html__( 'Map', 'supreme-maps' ),
					'marker_settings' => esc_html__( 'Marker Settings', 'supreme-maps' ),
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
				'main' => '%%order_class%% > .supreme_google_map_container',
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

	function get_fields() {
		$fields = array(
			'title'             => array(
				'label'           => et_builder_i18n( 'Title' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'The title will be used within the tab button for this tab.', 'et_builder' ),
				'toggle_slug'     => 'main_content',
				'dynamic_content' => 'text',
				// 'mobile_options'  => true,
				// 'hover'           => 'tabs',
			),
			'pin_address'       => array(
				'label'             => esc_html__( 'Map Pin Address', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'class'             => array( 'et_pb_pin_address' ),
				'description'       => esc_html__( 'Enter an address for this map pin, and the address will be geocoded and displayed on the map below.', 'et_builder' ),
				'additional_button' => sprintf(
					'<a href="#" class="et_pb_find_address button">%1$s</a>',
					esc_html__( 'Find', 'et_builder' )
				),
				'toggle_slug'       => 'map',
			),
			'zoom_level'        => array(
				'type'             => 'hidden',
				'class'            => array( 'et_pb_zoom_level' ),
				'default'          => '18',
				'default_on_front' => '',
				'option_category'  => 'basic_option',
			),
			'pin_address_lat'   => array(
				'type'            => 'hidden',
				'class'           => array( 'et_pb_pin_address_lat' ),
				'option_category' => 'basic_option',
			),
			'pin_address_lng'   => array(
				'type'            => 'hidden',
				'class'           => array( 'et_pb_pin_address_lng' ),
				'option_category' => 'basic_option',
			),
			'map_center_map'    => array(
				'type'                  => 'center_map',
				'option_category'       => 'basic_option',
				'use_container_wrapper' => false,
				'toggle_slug'           => 'map',
			),
			'content'           => array(
				'label'           => et_builder_i18n( 'Body' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can define the content that will be placed within the infobox for the pin.', 'et_builder' ),
				'toggle_slug'     => 'main_content',
				'dynamic_content' => 'text',
				// 'mobile_options'  => true,
				// 'hover'           => 'tabs',
			),
			'use_marker_custom' => array(
				'label'       => esc_html__( 'Use Custom Marker Icon/Image', 'dsm-supreme-modules-pro-for-divi' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'marker_settings',
			),
			'marker_icon_src'   => array(
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
			'marker_height'     => array(
				'label'            => esc_html__( 'Marker Icon/Image Height (px)', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '43',
				'default_on_front' => '43',
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
			'marker_width'      => array(
				'label'            => esc_html__( 'Marker Icon/Image Width (px)', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '27',
				'default_on_front' => '27',
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
		return $fields;
	}

	/**
	 * Renders the module output.
	 *
	 * @param  array  $attrs       List of attributes.
	 * @param  string $content     Content being processed.
	 * @param  string $render_slug Slug of module that is used for rendering output.
	 *
	 * @return string
	 */
	public function render( $attrs, $content, $render_slug ) {
		$multi_view = et_pb_multi_view_options( $this );
		$title      = $multi_view->render_element(
			array(
				'tag'     => 'div',
				'content' => '{{title}}',
				'attrs'   => array(
					'class' => 'supreme_google_marker_title',
				),
			)
		);

		$content = $multi_view->render_element(
			array(
				'tag'     => 'div',
				'content' => '{{content}}',
				'attrs'   => array(
					'class' => 'supreme_google_marker_content',
				),
			)
		);

		$pins = array(
			'lat'           => esc_attr( et_()->to_css_decimal( $this->props['pin_address_lat'] ) ),
			'lng'           => esc_attr( et_()->to_css_decimal( $this->props['pin_address_lng'] ) ),
			'marker_custom' => 'off' !== $this->props['use_marker_custom'] ? true : false,
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

		return sprintf(
			'<div data-supreme-google-marker=%1$s>%2$s%3$s</div>',
			esc_attr( $pin_attr ),
			$title,
			$content
		);
	}

	/**
	 * Filter multi view value.
	 *
	 * @since 3.27.1
	 *
	 * @see ET_Builder_Module_Helper_MultiViewOptions::filter_value
	 *
	 * @param mixed                                     $raw_value Props raw value.
	 * @param array                                     $args {
	 *                                         Context data.
	 *
	 *     @type string $context      Context param: content, attrs, visibility, classes.
	 *     @type string $name         Module options props name.
	 *     @type string $mode         Current data mode: desktop, hover, tablet, phone.
	 *     @type string $attr_key     Attribute key for attrs context data. Example: src, class, etc.
	 *     @type string $attr_sub_key Attribute sub key that availabe when passing attrs value as array such as styes. Example: padding-top, margin-botton, etc.
	 * }
	 * @param ET_Builder_Module_Helper_MultiViewOptions $multi_view Multiview object instance.
	 *
	 * @return mixed
	 */
	public function multi_view_filter_value( $raw_value, $args, $multi_view ) {
		$name = isset( $args['name'] ) ? $args['name'] : '';
		$mode = isset( $args['mode'] ) ? $args['mode'] : '';

		$fields_need_escape = array(
			'title',
		);

		if ( $raw_value && in_array( $name, $fields_need_escape, true ) ) {
			return $this->_esc_attr( $multi_view->get_name_by_mode( $name, $mode ), 'none', $raw_value );
		}

		return $raw_value;
	}
}

	new Supreme_GoogleMapItem();
