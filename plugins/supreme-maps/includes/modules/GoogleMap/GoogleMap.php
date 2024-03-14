<?php

class Supreme_GoogleMap extends ET_Builder_Module {
	public $slug       = 'supreme_google_map';
	public $vb_support = 'on';
	public $child_slug = 'supreme_google_map_item';

	protected $module_credits = array(
		'module_uri' => 'https://divisupreme.com/',
		'author'     => 'Divi Supreme',
		'author_uri' => 'https://divisupreme.com/about/',
	);

	public function init() {
		$this->name      = esc_html__( 'Google Map', 'supreme-maps' );
		$this->plural    = esc_html__( 'Google Maps', 'supreme-maps' );
		$this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'map'             => esc_html__( 'Map', 'supreme-maps' ),
					'settings'        => array(
						'title'             => esc_html__( 'Settings', 'supreme-maps' ),
						'priority'          => 60,
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'control'  => array(
								'name' => 'Control',
							),
							'position' => array(
								'name' => 'Position',
							),
							'styles'   => array(
								'name' => 'Styles',
							),
						),
					),
					'marker_settings' => array(
						'title'    => esc_html__( 'Marker Settings', 'supreme-maps' ),
						'priority' => 65,
					),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'infowindow'    => array(
						'title'             => esc_html__( 'InfoWindow', 'supreme-maps' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'   => array(
								'name' => 'Title',
							),
							'content' => array(
								'name' => 'Content',
							),
							'close'   => array(
								'name' => 'Close',
							),
							'styles'  => array(
								'name' => 'Styles',
							),
						),
					),
					'child_filters' => array(
						'title'    => esc_html__( 'Map', 'supreme-maps' ),
						'priority' => 51,
					),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		$advanced_fields = array();

		$advanced_fields['fonts']['infowindow_title']   = array(
			'label'             => esc_html__( 'Title', 'supreme-maps' ),
			'css'               => array(
				'main'        => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_title',
				'hover'       => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_title:hover, %%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_title:hover a',
				'color_hover' => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_title:hover a',
			),
			'font_size'         => array(
				'default' => '18px',
			),
			'line_height'       => array(
				'default' => '1em',
			),
			'hide_header_level' => true,
			'tab_slug'          => 'advanced',
			'toggle_slug'       => 'infowindow',
			'sub_toggle'        => 'title',
		);
		$advanced_fields['fonts']['infowindow_content'] = array(
			'label'       => esc_html__( 'Content', 'supreme-maps' ),
			'css'         => array(
				'main'        => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_content',
				'hover'       => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_content:hover, %%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_content:hover a',
				'color_hover' => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_content:hover',
			),
			'font_size'   => array(
				'default' => '13px',
			),
			'line_height' => array(
				'default' => '1.2em',
			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'infowindow',
			'sub_toggle'  => 'content',
		);
		$advanced_fields['fonts']['infowindow_close']   = array(
			'label'               => esc_html__( 'Close', 'supreme-maps' ),
			'css'                 => array(
				'main'        => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_close',
				'hover'       => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_close:hover, %%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_close:hover a',
				'color_hover' => '%%order_class%% .supreme_google_map_infowindow .supreme_google_map_infowindow_close:hover a',
			),
			'font_size'           => array(
				'default' => '24px',
			),
			'hide_line_height'    => true,
			'hide_font'           => true,
			'hide_font_weight'    => true,
			'hide_text_align'     => true,
			'hide_letter_spacing' => true,
			'tab_slug'            => 'advanced',
			'toggle_slug'         => 'infowindow',
			'sub_toggle'          => 'close',
		);

		$advanced_fields['borders']['default'] = array(
			'css' => array(
				'main' => '%%order_class%%',
			),
		);

		$advanced_fields['box_shadow']     = array(
			'default' => array(
				'css' => array(
					'overlay' => 'inset',
				),
			),
		);
		$advanced_fields['margin_padding'] = array(
			'css' => array(
				'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling.
			),
		);
		$advanced_fields['filters']        = array(
			'css'                  => array(
				'main' => '%%order_class%%',
			),
			'child_filters_target' => array(
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'child_filters',
				'label'       => esc_html__( 'Map', 'supreme-maps' ),
			),
		);
		$advanced_fields['child_filters']  = array(
			'css' => array(
				'main' => '%%order_class%% .gm-style>div>div>div>div>div>img',
			),
		);
		$advanced_fields['height']         = array(
			'css'     => array(
				'main' => '%%order_class%% > .et_pb_map',
			),
			'options' => array(
				'height' => array(
					'default'        => '440px',
					'default_tablet' => '350px',
					'default_phone'  => '200px',
				),
			),
		);

		$advanced_fields['text']            = false;
		$advanced_fields['link_options']    = false;
		$advanced_fields['button']          = false;
		$advanced_fields['position_fields'] = array(
			'default' => 'relative',
		);
		return $advanced_fields;
	}

	function get_fields() {
		$position_options = array(
			'BOTTOM_CENTER' => esc_html__( 'Bottom Center', 'supreme-maps' ),
			'BOTTOM_LEFT'   => esc_html__( 'Bottom Left', 'supreme-maps' ),
			'BOTTOM_RIGHT'  => esc_html__( 'Bottom Right', 'supreme-maps' ),
			'LEFT_BOTTOM'   => esc_html__( 'Left Bottom', 'supreme-maps' ),
			'LEFT_CENTER'   => esc_html__( 'Left Center', 'supreme-maps' ),
			'LEFT_TOP'      => esc_html__( 'Left Top', 'supreme-maps' ),
			'RIGHT_BOTTOM'  => esc_html__( 'Right Bottom', 'supreme-maps' ),
			'RIGHT_CENTER'  => esc_html__( 'Right Center', 'supreme-maps' ),
			'RIGHT_TOP'     => esc_html__( 'Right Top', 'supreme-maps' ),
			'TOP_CENTER'    => esc_html__( 'Top Center', 'supreme-maps' ),
			'TOP_LEFT'      => esc_html__( 'Top Left', 'supreme-maps' ),
			'TOP_RIGHT'     => esc_html__( 'Top Right', 'supreme-maps' ),
		);
		$fields           = array(
			'google_maps_script_notice'    => array(
				'type'        => 'warning',
				'value'       => et_pb_enqueue_google_maps_script(),
				'display_if'  => false,
				'message'     => esc_html__(
					sprintf(
						'The Google Maps API Script is currently disabled in the <a href="%s" target="_blank">Theme Options</a>. This module will not function properly without the Google Maps API.',
						admin_url( 'admin.php?page=et_divi_options' )
					),
					'supreme-maps'
				),
				'toggle_slug' => 'map',
			),
			'google_api_key'               => array(
				'label'                  => esc_html__( 'Google API Key', 'supreme-maps' ),
				'type'                   => 'text',
				'option_category'        => 'basic_option',
				'attributes'             => 'readonly',
				'additional_button'      => sprintf(
					' <a href="%2$s" target="_blank" class="et_pb_update_google_key button" data-empty_text="%3$s">%1$s</a>',
					esc_html__( 'Change API Key', 'supreme-maps' ),
					esc_url( et_pb_get_options_page_link() ),
					esc_attr__( 'Add Your API Key', 'supreme-maps' )
				),
				'additional_button_type' => 'change_google_api_key',
				'class'                  => array( 'et_pb_google_api_key', 'et-pb-helper-field' ),
				'description'            => et_get_safe_localization( sprintf( __( 'The Maps module uses the Google Maps API and requires a valid Google API Key to function. Before using the map module, please make sure you have added your API key inside the Divi Theme Options panel. Learn more about how to create your Google API Key <a href="%1$s" target="_blank">here</a>.', 'supreme-maps' ), esc_url( 'http://www.elegantthemes.com/gallery/divi/documentation/map/#gmaps-api-key' ) ) ),
				'toggle_slug'            => 'map',
			),
			'address'                      => array(
				'label'             => esc_html__( 'Map Center Address', 'supreme-maps' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'additional_button' => sprintf(
					' <a href="#" class="et_pb_find_address button">%1$s</a>',
					esc_html__( 'Find', 'supreme-maps' )
				),
				'class'             => array( 'et_pb_address' ),
				'description'       => esc_html__( 'Enter an address for the map center point, and the address will be geocoded and displayed on the map below.', 'supreme-maps' ),
				'toggle_slug'       => 'map',
			),
			'zoom_level'                   => array(
				'type'    => 'hidden',
				'class'   => array( 'et_pb_zoom_level' ),
				'default' => '18',
			),
			'address_lat'                  => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_address_lat' ),
			),
			'address_lng'                  => array(
				'type'  => 'hidden',
				'class' => array( 'et_pb_address_lng' ),
			),
			'map_center_map'               => array(
				'type'                  => 'center_map',
				'use_container_wrapper' => false,
				'option_category'       => 'basic_option',
				'toggle_slug'           => 'map',
			),
			'gesture_handling'             => array(
				'label'            => esc_html__( 'Gesture Handling', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'cooperative' => esc_html__( 'Cooperative', 'supreme-maps' ),
					'auto'        => esc_html__( 'Auto', 'supreme-maps' ),
					'greedy'      => esc_html__( 'Greedy', 'supreme-maps' ),
					'none'        => esc_html__( 'None', 'supreme-maps' ),
				),
				'default'          => 'cooperative',
				'default_on_front' => 'cooperative',
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'When a user scrolls a page that contains a map, the scrolling action can unintentionally cause the map to zoom. This behavior can be controlled using the gestureHandling map option.', 'supreme-maps' ),
			),
			/*
			'mouse_wheel'                  => array(
				'label'            => esc_html__( 'Mouse Wheel Zoom', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'Here you can choose whether the zoom level will be controlled by mouse wheel or not.', 'supreme-maps' ),
				'default_on_front' => 'off',
				'show_if'          => array(
					'gesture_handling' => 'greedy',
				),
			),
			'mobile_dragging'              => array(
				'label'            => esc_html__( 'Draggable On Mobile', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'Here you can choose whether or not the map will be draggable on mobile devices.', 'supreme-maps' ),
				'default_on_front' => 'on',
			),*/
			'map_types'                    => array(
				'label'            => esc_html__( 'Map Types', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'roadmap'   => esc_html__( 'Roadmap', 'supreme-maps' ),
					'satellite' => esc_html__( 'Satellite', 'supreme-maps' ),
					'hybrid'    => esc_html__( 'Hybrid', 'supreme-maps' ),
					'terrain'   => esc_html__( 'Terrain', 'supreme-maps' ),
				),
				'default'          => 'roadmap',
				'default_on_front' => 'roadmap',
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
			),
			'disable_default_ui'           => array(
				'label'            => esc_html__( 'Disable Default UI', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'This will remove all control in the map', 'supreme-maps' ),
				'default_on_front' => 'off',
			),
			'zoom_control'                 => array(
				'label'            => esc_html__( 'Use Zoom Control', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'The Zoom control displays "+" and "-" buttons for changing the zoom level of the map. This control appears by default in the bottom right corner of the map.', 'supreme-maps' ),
				'default_on_front' => 'on',
				'show_if'          => array(
					'disable_default_ui' => 'off',
				),
			),
			'map_type_control'             => array(
				'label'            => esc_html__( 'Use Map Type Control', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'The Map Type control is available in a dropdown or horizontal button bar style, allowing the user to choose a map type (ROADMAP, SATELLITE, HYBRID, or TERRAIN). This control appears by default in the top left corner of the map.', 'supreme-maps' ),
				'default_on_front' => 'on',
				'show_if'          => array(
					'disable_default_ui' => 'off',
				),
			),
			'map_type_control_style'       => array(
				'label'            => esc_html__( 'Map Type Control Style', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'horizontal_bar' => esc_html__( 'Horizontal Bar', 'supreme-maps' ),
					'dropdown_menu'  => esc_html__( 'Dropdown Menu', 'supreme-maps' ),
				),
				'default'          => 'horizontal_bar',
				'default_on_front' => 'horizontal_bar',
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'show_if'          => array(
					'map_type_control'   => 'on',
					'disable_default_ui' => 'off',
				),
			),
			'scale_control'                => array(
				'label'            => esc_html__( 'Use Scale Control', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'The Scale control displays a map scale element. This control is disabled by default.', 'supreme-maps' ),
				'default_on_front' => 'off',
				'show_if'          => array(
					'disable_default_ui' => 'off',
				),
			),
			'street_view_control'          => array(
				'label'            => esc_html__( 'Use Street View Control', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'The Street View control contains a Pegman icon which can be dragged onto the map to enable Street View. This control appears by default near the bottom right of the map.', 'supreme-maps' ),
				'default_on_front' => 'on',
				'show_if'          => array(
					'disable_default_ui' => 'off',
				),
			),
			'rotate_control'               => array(
				'label'            => esc_html__( 'Use Rotate Control', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'The Rotate control provides a combination of tilt and rotate options for maps containing oblique imagery. This control appears by default near the bottom right of the map. See 45° imagery for more information.', 'supreme-maps' ),
				'default_on_front' => 'on',
				'show_if'          => array(
					'disable_default_ui' => 'off',
				),
			),
			'fullscreen_control'           => array(
				'label'            => esc_html__( 'Use Fullscreen Control', 'supreme-maps' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => et_builder_i18n( 'On' ),
					'off' => et_builder_i18n( 'Off' ),
				),
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'control',
				'description'      => esc_html__( 'The Fullscreen control offers the option to open the map in fullscreen mode. This control is enabled by default on desktop and mobile devices. Note: iOS does not support the fullscreen feature. The fullscreen control is therefore not visible on iOS devices.', 'supreme-maps' ),
				'default_on_front' => 'on',
				'show_if'          => array(
					'disable_default_ui' => 'off',
				),
			),
			// Position.
			'zoom_control_position'        => array(
				'label'            => esc_html__( 'Zoom Control Position', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => $position_options,
				'default'          => 'RIGHT_BOTTOM',
				'default_on_front' => 'RIGHT_BOTTOM',
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'position',
				'show_if'          => array(
					'street_view_control' => 'on',
					'disable_default_ui'  => 'off',
				),
			),
			'street_view_control_position' => array(
				'label'            => esc_html__( 'Streetview Control Position', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => $position_options,
				'default'          => 'RIGHT_BOTTOM',
				'default_on_front' => 'RIGHT_BOTTOM',
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'position',
				'show_if'          => array(
					'fullscreen_control' => 'on',
					'disable_default_ui' => 'off',
				),
			),
			'fullscreen_control_position'  => array(
				'label'            => esc_html__( 'Fullscreen Control Position', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => $position_options,
				'default'          => 'RIGHT_TOP',
				'default_on_front' => 'RIGHT_TOP',
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'position',
				'show_if'          => array(
					'fullscreen_control' => 'on',
					'disable_default_ui' => 'off',
				),
			),
			// Styles.
			'map_preset_styles'            => array(
				'label'            => esc_html__( 'Preset Map Styles', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'default'            => esc_html__( 'Default (Google)', 'supreme-maps' ),
					'blue_water'         => esc_html__( 'Blue Water', 'supreme-maps' ),
					'lemon_tree'         => esc_html__( 'Lemon Tree', 'supreme-maps' ),
					'unsaturated_browns' => esc_html__( 'Unsaturated Browns', 'supreme-maps' ),
					'holiday'            => esc_html__( 'Holiday', 'supreme-maps' ),
					'flat_map'           => esc_html__( 'Flat Map', 'supreme-maps' ),
					'avocado_world'      => esc_html__( 'Avocado World', 'supreme-maps' ),
				),
				'default'          => 'default',
				'default_on_front' => 'default',
				'toggle_slug'      => 'settings',
				'sub_toggle'       => 'styles',
			),
			'info_window_trigger'          => array(
				'label'            => esc_html__( 'InfoWindow Trigger Method', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'click'     => esc_html__( 'On Click', 'supreme-maps' ),
					'mouseover' => esc_html__( 'Mouseover', 'supreme-maps' ),
				),
				'default'          => 'click',
				'default_on_front' => 'click',
				'toggle_slug'      => 'marker_settings',
				'description'      => esc_html__( 'Here you can choose whether the pin infowindow should show up on click or mouseover.', 'supreme-maps' ),
			),
			'info_window_max_width'        => array(
				'label'            => esc_html__( 'InfoWindow Max Width', 'supreme-maps' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '320',
				'default_on_front' => '320',
				'default_unit'     => '',
				'validate_unit'    => false,
				'mobile_options'   => false,
				'unitless'         => true,
				'responsive'       => false,
				'range_settings'   => array(
					'min'  => '100',
					'max'  => '500',
					'step' => '1',
				),
				'toggle_slug'      => 'marker_settings',
			),
		);
		return $fields;
	}

	public function get_transition_fields_css_props() {
		$fields  = parent::get_transition_fields_css_props();
		$filters = $this->get_transition_filters_fields_css_props( 'child_filters' );

		return array_merge( $fields, $filters );
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
		$address_lat     = $this->props['address_lat'];
		$address_lng     = $this->props['address_lng'];
		$zoom_level      = $this->props['zoom_level'];
		$mouse_wheel     = $this->props['mouse_wheel'];
		$mobile_dragging = $this->props['mobile_dragging'];

		if ( et_pb_enqueue_google_maps_script() ) {
			wp_enqueue_script( 'google-maps-api' );
		}

		$video_background          = $this->video_background();
		$parallax_image_background = $this->get_parallax_image_background();

		$all_pins_content = $this->content;

		// Map Tiles: Add CSS Filters and Mix Blend Mode rules (if set)
		if ( array_key_exists( 'child_filters', $this->advanced_fields ) && array_key_exists( 'css', $this->advanced_fields['child_filters'] ) ) {
			$this->add_classname(
				$this->generate_css_filters(
					$render_slug,
					'child_',
					self::$data_utils->array_get( $this->advanced_fields['child_filters']['css'], 'main', '%%order_class%%' )
				)
			);
		}

		$map_options = array(
			'lat'                       => esc_attr( et_()->to_css_decimal( $address_lat ) ),
			'lng'                       => esc_attr( et_()->to_css_decimal( $address_lng ) ),
			'zoom'                      => isset( $zoom_level ) ? intval( $zoom_level ) : 12,
			'fullscreenControl'         => 'off' !== $this->props['fullscreen_control'] ? true : false,
			'fullscreenControlPosition' => isset( $this->props['fullscreen_control_position'] ) ? esc_attr( $this->props['fullscreen_control_position'] ) : 'RIGHT_TOP',
			'mapTypeControl'            => 'off' !== $this->props['map_type_control'] ? true : false,
			'mapTypeControlStyle'       => isset( $this->props['map_type_control_style'] ) ? esc_attr( $this->props['map_type_control_style'] ) : 'horizontal_bar',
			'scaleControl'              => 'off' !== $this->props['scale_control'] ? true : false,
			'streetViewControl'         => 'off' !== $this->props['street_view_control'] ? true : false,
			'streetViewControlPosition' => isset( $this->props['street_view_control_position'] ) ? esc_attr( $this->props['street_view_control_position'] ) : 'RIGHT_BOTTOM',
			'rotateControl'             => 'off' !== $this->props['rotate_control'] ? true : false,
			'zoomControl'               => 'off' !== $this->props['zoom_control'] ? true : false,
			'zoomControlPosition'       => isset( $this->props['zoom_control_position'] ) ? esc_attr( $this->props['zoom_control_position'] ) : 'RIGHT_BOTTOM',
			'disableDefaultUI'          => 'off' !== $this->props['disable_default_ui'] ? true : false,
			'gestureHandling'           => 'off' !== $this->props['gesture_handling'] ? true : false,
			'type'                      => isset( $this->props['map_types'] ) ? $this->props['map_types'] : 'roadmap',
			'mapStyles'                 => isset( $this->props['map_preset_styles'] ) ? $this->props['map_preset_styles'] : 'default',
			'infoWindowTrigger'         => isset( $this->props['info_window_trigger'] ) ? esc_attr( $this->props['info_window_trigger'] ) : 'click',
			'infoWindowMaxWidth'        => isset( $info_window_max_width ) ? intval( $info_window_max_width ) : 320,
		);

		if ( function_exists( 'et_core_is_fb_enabled' ) && function_exists( 'et_builder_bfb_enabled' ) ) {
			if ( ! et_core_is_fb_enabled() || ! et_builder_bfb_enabled() ) {
				wp_enqueue_script( 'supreme_maps-google-infobox' );
			}
		}

		wp_enqueue_script( 'google-maps-api' );
		wp_enqueue_script( 'supreme-maps-frontend-bundle' );

		$output = sprintf(
			'<div class="supreme_google_map_container" data-supreme-maps-options=%4$s>
				%3$s
				%2$s
			</div>
			%1$s',
			$all_pins_content,
			$video_background, // #2
			$parallax_image_background,
			wp_json_encode( $map_options )
		);

		return $output;
	}
}

new Supreme_GoogleMap();
