<?php

class Supreme_LeafletMap extends ET_Builder_Module {

	public $slug       = 'supreme_leaflet_map';
	public $vb_support = 'on';
	public $child_slug = 'supreme_leaflet_map_item';

	protected $module_credits = array(
		'module_uri' => 'https://divisupreme.com/',
		'author'     => 'Divi Supreme',
		'author_uri' => 'https://divisupreme.com/about/',
	);

	public function init() {
		$this->name      = esc_html__( 'Leaflet Map', 'supreme-maps' );
		$this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'map'                     => esc_html__( 'Map', 'supreme-maps' ),
					'settings'                => esc_html__( 'Map Settings', 'supreme-maps' ),
					'popup_settings'          => esc_html__( 'Popup Settings', 'supreme-maps' ),
					'marker_cluster_settings' => esc_html__( 'Marker Clustering Settings', 'supreme-maps' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'popup_styles'   => esc_html__( 'Popup Styles', 'supreme-maps' ),
					'popup_title'    => esc_html__( 'Popup Title', 'supreme-maps' ),
					'popup_content'  => esc_html__( 'Popup Content', 'supreme-maps' ),
					'marker_cluster' => esc_html__( 'Marker Cluster', 'supreme-maps' ),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		$advanced_fields = array();

		$advanced_fields['box_shadow']['popup_wrapper'] = array(
			'label'       => esc_html__( 'Popup Box Shadow', 'supreme-maps' ),
			'toggle_slug' => 'popup_styles',
			'tab_slug'    => 'advanced',
			'css'         => array(
				'main' => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content-wrapper, %%order_class%% .supreme_leaflet_map_popup .leaflet-popup-tip',
			),
		);

		$advanced_fields['borders']['default'] = array(
			'css' => array(
				'main' => '%%order_class%%',
			),
		);

		$advanced_fields['borders']['popup_wrapper'] = array(
			'label'        => esc_html__( 'Popup', 'supreme-maps' ),
			'label_prefix' => esc_html__( 'Popup', 'supreme-maps' ),
			'tab_slug'     => 'advanced',
			'toggle_slug'  => 'popup_styles',
			'css'          => array(
				'main'      => array(
					'border_radii'  => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content-wrapper',
					'border_styles' => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content-wrapper',
					'defaults'      => array(
						'border_radii'  => 'on|12px|12px|12px|12px',
						'border_styles' => array(
							'width' => '0px',
							'style' => 'none',
						),
					),
				),
				'important' => 'all',
			),
		);

		$advanced_fields['box_shadow']['default'] = array(
			'css' => array(
				'overlay' => 'inset',
			),
		);

		$advanced_fields['margin_padding']          = array(
			'css' => array(
				'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling.
			),
		);
		$advanced_fields['filters']                 = array(
			'css'                  => array(
				'main' => '%%order_class%%',
			),
			'child_filters_target' => array(
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'child_filters',
				'label'       => esc_html__( 'Map', 'supreme-maps' ),
			),
		);
		$advanced_fields['child_filters']           = array(
			'css' => array(
				'main' => '%%order_class%%',
			),
		);
		$advanced_fields['height']                  = array(
			'css'     => array(
				'main' => '%%order_class%% .supreme_leaflet_map_leaflet_map_container',
			),
			'options' => array(
				'height' => array(
					'default'        => '440px',
					'default_tablet' => '350px',
					'default_phone'  => '200px',
				),
			),
		);
		$advanced_fields['fonts']['popup_title']    = array(
			'label'        => esc_html__( 'Title', 'supreme-maps' ),
			'css'          => array(
				'main'        => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_title',
				'hover'       => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_title:hover, %%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_title:hover a',
				'color_hover' => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_title:hover a',
			),
			'font_size'    => array(
				'default' => '18px',
			),
			'line_height'  => array(
				'default' => '1em',
			),
			'header_level' => array(
				'default' => 'h4',
			),
			'tab_slug'     => 'advanced',
			'toggle_slug'  => 'popup_title',
		);
		$advanced_fields['fonts']['popup_content']  = array(
			'label'       => esc_html__( 'Content', 'supreme-maps' ),
			'css'         => array(
				'main'        => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_content',
				'hover'       => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_content:hover, %%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_content:hover a',
				'color_hover' => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_content:hover',
			),
			'font_size'   => array(
				'default' => '16px',
			),
			'line_height' => array(
				'default' => '1.3em',
			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'popup_content',
		);
		$advanced_fields['fonts']['marker_cluster'] = array(
			'label'           => esc_html__( 'Marker Cluster', 'supreme-maps' ),
			'css'             => array(
				'main'        => '%%order_class%% .marker-cluster>div',
				'hover'       => '%%order_class%% .marker-cluster>div:hover, %%order_class%% .marker-cluster>div:hover a',
				'color_hover' => '%%order_class%% .marker-cluster>div:hover a',
			),
			'font_size'       => array(
				'default' => '12px',
			),
			'line_height'     => array(
				'default' => '30px',
			),
			'hide_text_align' => true,
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'marker_cluster',
		);
		$advanced_fields['text']                    = false;
		$advanced_fields['link_options']            = false;
		$advanced_fields['button']                  = false;
		$advanced_fields['position_fields']         = array(
			'default' => 'relative',
		);
		return $advanced_fields;
	}

	public function get_fields() {
		return array(
			/*
			'geo_address'            => array(
				'label'             => esc_html__( 'Map Center Address', 'supreme-maps' ),
				'type'              => 'suml_leaflet_input',
				'option_category'   => 'basic_option',
				'additional_button' => sprintf(
					'<a href="#" class="et_pb_pin_address button">%1$s</a>',
					esc_html__( 'Find', 'supreme-maps' )
				),
				'description'       => esc_html__( 'Enter an address for the map center point, and the address will be geocoded and displayed on the map below.', 'supreme-maps' ),
				'toggle_slug'       => 'map',
			),*/
			'google_maps_script_notice'    => array(
				'type'        => 'warning',
				'value'       => et_pb_enqueue_google_maps_script(),
				'display_if'  => false,
				'message'     => sprintf(
									/* translators: %s: divi option link */
					__( 'The Google Maps API Script is currently disabled in the <a href="%s" target="_blank">Theme Options</a>. This Divi Leaflet Map module uses Google Map API for the Divi Visual Builder to get geocoding addresses for better accuracy.<br><br>Alternatively you can always enter your own latitude and longitude by enabling "Use Custom Latitude and Longitude" option below. Rest assure that on the frontend, Google Map API will not be enqueued when using this module.', 'supreme-maps' ),
					admin_url( 'admin.php?page=et_divi_options' )
				),
				'toggle_slug' => 'map',
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
				'show_if_not'       => array(
					'use_custom_latlng' => 'on',
				),
			),
			'zoom_level'                   => array(
				'type'    => 'hidden',
				'class'   => array( 'et_pb_zoom_level' ),
				'default' => '13',
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
				'show_if_not'           => array(
					'use_custom_latlng' => 'on',
				),
			),
			'use_custom_latlng'            => array(
				'label'       => esc_html__( 'Use Custom Latitude and Longitude', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'map',
			),
			'address_custom_lat'           => array(
				'label'           => esc_html__( 'Latitude', 'supreme-maps' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter your custom Longitude.', 'supreme-maps' ),
				'toggle_slug'     => 'map',
				'show_if'         => array(
					'use_custom_latlng' => 'on',
				),
			),
			'address_custom_lng'           => array(
				'label'           => esc_html__( 'Longitude', 'supreme-maps' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter your custom Longitude.', 'supreme-maps' ),
				'toggle_slug'     => 'map',
				'show_if'         => array(
					'use_custom_latlng' => 'on',
				),
			),
			'address_custom_zoom'          => array(
				'label'           => esc_html__( 'Zoom', 'supreme-maps' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'toggle_slug'     => 'map',
				'default_unit'    => '',
				'default'         => '15',
				'allow_empty'     => false,
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '22',
					'step' => '1',
				),
				'unitless'        => true,
				'show_if'         => array(
					'use_custom_latlng' => 'on',
				),
			),
			// Settings.
			'map_tiles'                    => array(
				'label'            => esc_html__( 'Map Tiles', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'osm'               => esc_html__( 'OpenStreetMap', 'supreme-maps' ),
					'stamen_toner'      => esc_html__( 'Stamen (Toner)', 'supreme-maps' ),
					'stamen_watercolor' => esc_html__( 'Stamen (Watercolor)', 'supreme-maps' ),
					'stamen_terrain'    => esc_html__( 'Stamen (Terrain)', 'supreme-maps' ),
				),
				'default'          => 'osm',
				'default_on_front' => 'osm',
				'toggle_slug'      => 'settings',
			),
			'zoom_control'                 => array(
				'label'       => esc_html__( 'Show Zoom Control', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'on',
				'toggle_slug' => 'settings',
			),
			'scroll_wheel_zoom'            => array(
				'label'       => esc_html__( 'Use Scroll Wheel Zoom', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'settings',
			),
			'double_click_zoom'            => array(
				'label'       => esc_html__( 'Use Double Click Zoom', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'on',
				'toggle_slug' => 'settings',
			),
			'center_map'                   => array(
				'label'       => esc_html__( 'Center Map', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'settings',
			),
			'center_map_padding'           => array(
				'label'           => esc_html__( 'Center Map Padding', 'supreme-maps' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'toggle_slug'     => 'settings',
				'default_unit'    => '',
				'default'         => '100',
				'allow_empty'     => false,
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '200',
					'step' => '1',
				),
				'unitless'        => true,
				'show_if'         => array(
					'center_map' => 'on',
				),
				'description'     => esc_html__( 'Fit the display to more restricted bounds.', 'supreme-maps' ),
			),
			'popup_trigger'                => array(
				'label'            => esc_html__( 'Trigger Popup', 'supreme-maps' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'click' => esc_html__( 'On Click', 'supreme-maps' ),
					'hover' => esc_html__( 'Hover', 'supreme-maps' ),
				),
				'default'          => 'click',
				'default_on_front' => 'click',
				'toggle_slug'      => 'popup_settings',
			),
			'popup_max_width'              => array(
				'label'            => esc_html__( 'Popup Max Width', 'supreme-maps' ),
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
				'toggle_slug'      => 'popup_settings',
			),
			'close_popup_on_click'         => array(
				'label'       => esc_html__( 'Close Popup On Map Click', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'popup_settings',
			),
			'open_all_popup'               => array(
				'label'       => esc_html__( 'Open All Popups', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'popup_settings',
			),
			'auto_close_popup'             => array(
				'label'       => esc_html__( 'Auto Close Popup', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'popup_settings',
				'show_if_not' => array(
					'open_all_popup' => 'on',
				),
			),
			'popup_background_color'       => array(
				'label'          => esc_html__( 'Popup Background Color', 'supreme-maps' ),
				'type'           => 'color-alpha',
				'custom_color'   => true,
				'mobile_options' => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'popup_styles',
			),
			'title_bottom_padding'         => array(
				'label'            => esc_html__( 'Bottom Padding', 'supreme-maps' ),
				'type'             => 'range',
				'option_category'  => 'layout',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'popup_title',
				'mobile_options'   => true,
				'validate_unit'    => true,
				'default'          => '10px',
				'default_unit'     => 'px',
				'default_on_front' => '10px',
				'responsive'       => true,
				'description'      => esc_html__( 'Here you can define bottom padding between the title and the content', 'supreme-maps' ),
			),
			'marker_cluster'               => array(
				'label'       => esc_html__( 'Use Marker Clustering', 'supreme-maps' ),
				'type'        => 'yes_no_button',
				'options'     => array(
					'on'  => esc_html__( 'Yes', 'supreme-maps' ),
					'off' => esc_html__( 'No', 'supreme-maps' ),
				),
				'default'     => 'off',
				'toggle_slug' => 'marker_cluster_settings',
			),
			'max_cluster_radius'           => array(
				'label'           => esc_html__( 'Maximum Cluster Radius', 'supreme-maps' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'toggle_slug'     => 'marker_cluster_settings',
				'default_unit'    => '',
				'default'         => '80',
				'allow_empty'     => false,
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '200',
					'step' => '1',
				),
				'unitless'        => true,
				'show_if'         => array(
					'marker_cluster' => 'on',
				),
				'description'     => esc_html__( 'The maximum radius that a cluster will cover from the central marker (in pixels). Default 80. Decreasing will make more, smaller clusters. You can also use a function that accepts the current map zoom and returns the maximum cluster radius in pixels.', 'supreme-maps' ),
			),
			'marker_cluster_small'         => array(
				'label'          => esc_html__( 'Small Cluster Color', 'supreme-maps' ),
				'type'           => 'color',
				'custom_color'   => true,
				'show_if'        => array(
					'marker_cluster' => 'on',
				),
				'default'        => 'rgba(110, 204, 57, 0.85)',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'marker_cluster',
				'description'    => esc_html__( 'Here you can define a custom color for small cluster', 'supreme-maps' ),
				'mobile_options' => true,
			),
			'marker_cluster_small_border'  => array(
				'label'          => esc_html__( 'Small Cluster Border Color', 'supreme-maps' ),
				'type'           => 'color',
				'custom_color'   => true,
				'show_if'        => array(
					'marker_cluster' => 'on',
				),
				'default'        => 'rgba(110, 204, 57, 0.6)',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'marker_cluster',
				'description'    => esc_html__( 'Here you can define a custom border color for small cluster', 'supreme-maps' ),
				'mobile_options' => true,
			),
			'marker_cluster_medium'        => array(
				'label'          => esc_html__( 'Medium Cluster Color', 'supreme-maps' ),
				'type'           => 'color',
				'custom_color'   => true,
				'show_if'        => array(
					'marker_cluster' => 'on',
				),
				'default'        => 'rgba(240, 194, 12, 0.85)',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'marker_cluster',
				'description'    => esc_html__( 'Here you can define a custom color medium small cluster', 'supreme-maps' ),
				'mobile_options' => true,
			),
			'marker_cluster_medium_border' => array(
				'label'          => esc_html__( 'Medium Cluster Border Color', 'supreme-maps' ),
				'type'           => 'color',
				'custom_color'   => true,
				'show_if'        => array(
					'marker_cluster' => 'on',
				),
				'default'        => 'rgba(241, 211, 87, 0.6)',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'marker_cluster',
				'description'    => esc_html__( 'Here you can define a custom border color for medium cluster', 'supreme-maps' ),
				'mobile_options' => true,
			),
			'marker_cluster_large'         => array(
				'label'          => esc_html__( 'Large Cluster Color', 'supreme-maps' ),
				'type'           => 'color',
				'custom_color'   => true,
				'show_if'        => array(
					'marker_cluster' => 'on',
				),
				'default'        => 'rgba(241, 128, 23, 0.85)',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'marker_cluster',
				'description'    => esc_html__( 'Here you can define a custom color for large cluster', 'supreme-maps' ),
				'mobile_options' => true,
			),
			'marker_cluster_large_border'  => array(
				'label'          => esc_html__( 'Large Cluster Border Color', 'supreme-maps' ),
				'type'           => 'color',
				'custom_color'   => true,
				'show_if'        => array(
					'marker_cluster' => 'on',
				),
				'default'        => 'rgba(253, 156, 115, 0.6)',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'marker_cluster',
				'description'    => esc_html__( 'Here you can define a custom border color for large cluster', 'supreme-maps' ),
				'mobile_options' => true,
			),
			/*
			'popup_border_radius'    => array(
				'label'           => esc_html__( 'Popup Border Radius', 'supreme-maps' ),
				'type'            => 'border-radius',
				'validate_input'  => true,
				'option_category' => 'border',
				'default'         => 'on|12px|12px|12px|12px',
				'mobile_options'  => true,
				'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'popup_styles',
			),*/

		);
	}

	public function get_pin() {
		global $pins;
		return $pins;
	}

	public function render( $attrs, $content, $render_slug ) {
		$address_lat                      = $this->props['address_lat'];
		$address_lng                      = $this->props['address_lng'];
		$zoom_level                       = $this->props['zoom_level'];
		$use_custom_latlng                = $this->props['use_custom_latlng'];
		$address_custom_lat               = $this->props['address_custom_lat'];
		$address_custom_lng               = $this->props['address_custom_lng'];
		$address_custom_zoom              = $this->props['address_custom_zoom'];
		$map_tiles                        = $this->props['map_tiles'];
		$scroll_wheel_zoom                = $this->props['scroll_wheel_zoom'];
		$double_click_zoom                = $this->props['double_click_zoom'];
		$zoom_control                     = $this->props['zoom_control'];
		$popup_trigger                    = $this->props['popup_trigger'];
		$popup_max_width                  = $this->props['popup_max_width'];
		$close_popup_on_click             = $this->props['close_popup_on_click'];
		$open_all_popup                   = $this->props['open_all_popup'];
		$auto_close_popup                 = $this->props['auto_close_popup'];
		$center_map                       = $this->props['center_map'];
		$center_map_padding               = $this->props['center_map_padding'];
		$popup_background_color           = $this->props['popup_background_color'];
		$title_bottom_padding             = $this->props['title_bottom_padding'];
		$title_bottom_padding_tablet      = $this->props['title_bottom_padding_tablet'];
		$title_bottom_padding_phone       = $this->props['title_bottom_padding_phone'];
		$title_bottom_padding_last_edited = $this->props['title_bottom_padding_last_edited'];
		$marker_cluster                   = $this->props['marker_cluster'];
		$max_cluster_radius               = $this->props['max_cluster_radius'];
		// $popup_border_radius    = $this->props['popup_border_radius'];

		$leaflet_options = array(
			'address_lat'         => isset( $address_lat ) ? esc_attr( et_()->to_css_decimal( $address_lat ) ) : 0,
			'address_lng'         => isset( $address_lng ) ? esc_attr( et_()->to_css_decimal( $address_lng ) ) : 0,
			'zoom_level'          => isset( $zoom_level ) ? intval( $zoom_level ) : 15,
			'use_custom_latlng'   => 'off' !== $use_custom_latlng ? true : false,
			'address_custom_lat'  => isset( $address_custom_lat ) ? esc_attr( et_()->to_css_decimal( $address_custom_lat ) ) : 0,
			'address_custom_lng'  => isset( $address_custom_lng ) ? esc_attr( et_()->to_css_decimal( $address_custom_lng ) ) : 0,
			'address_custom_zoom' => isset( $address_custom_zoom ) ? intval( $address_custom_zoom ) : 15,
			'maxZoom'             => 15,
			'mapTiles'            => isset( $map_tiles ) ? esc_attr( $map_tiles ) : esc_attr( 'osm' ),
			'scrollWheelZoom'     => 'off' !== $scroll_wheel_zoom ? true : false,
			'doubleClickZoom'     => 'off' !== $double_click_zoom ? true : false,
			'popupTrigger'        => esc_attr( $popup_trigger ),
			'PopupMaxWidth'       => isset( $popup_max_width ) ? intval( $popup_max_width ) : 320,
			'openAllPopup'        => 'off' !== $open_all_popup ? true : false,
			'closePopupOnClick'   => 'off' !== $close_popup_on_click ? true : false,
			'zoomControl'         => 'off' !== $zoom_control ? true : false,
			'autoClosePopup'      => 'off' !== $auto_close_popup ? true : false,
			'centerMap'           => 'off' !== $center_map ? true : false,
			'centerMapPadding'    => isset( $center_map_padding ) ? intval( $center_map_padding ) : 100,
			'markerCluster'       => 'off' !== $marker_cluster ? true : false,
			'maxClusterRadius'    => isset( $max_cluster_radius ) ? intval( $max_cluster_radius ) : 80,

		);

		if ( '' !== $title_bottom_padding_tablet || '' !== $title_bottom_padding_phone || '10px' !== $title_bottom_padding ) {
			$title_bottom_padding_responsive_active = et_pb_get_responsive_status( $title_bottom_padding_last_edited );

			$title_bottom_padding_values = array(
				'desktop' => $title_bottom_padding,
				'tablet'  => $title_bottom_padding_responsive_active ? $title_bottom_padding_tablet : '',
				'phone'   => $title_bottom_padding_responsive_active ? $title_bottom_padding_phone : '',
			);
				et_pb_responsive_options()->generate_responsive_css( $title_bottom_padding_values, '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content .supreme_leaflet_pin_title', 'padding-bottom', $render_slug );
		}

		$this->generate_styles(
			array(
				'base_attr_name' => 'popup_background_color',
				'selector'       => '%%order_class%% .supreme_leaflet_map_popup .leaflet-popup-content-wrapper, %%order_class%% .supreme_leaflet_map_popup .leaflet-popup-tip',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);

		$this->generate_styles(
			array(
				'base_attr_name' => 'marker_cluster_small',
				'selector'       => '%%order_class%% .marker-cluster.marker-cluster-small>div',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);
		$this->generate_styles(
			array(
				'base_attr_name' => 'marker_cluster_small_border',
				'selector'       => '%%order_class%% .marker-cluster.marker-cluster-small',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);
		$this->generate_styles(
			array(
				'base_attr_name' => 'marker_cluster_medium',
				'selector'       => '%%order_class%% .marker-cluster.marker-cluster-medium>div',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);
		$this->generate_styles(
			array(
				'base_attr_name' => 'marker_cluster_medium_border',
				'selector'       => '%%order_class%% .marker-cluster.marker-cluster-medium',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);
		$this->generate_styles(
			array(
				'base_attr_name' => 'marker_cluster_large',
				'selector'       => '%%order_class%% .marker-cluster.marker-cluster-large>div',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);
		$this->generate_styles(
			array(
				'base_attr_name' => 'marker_cluster_large_border',
				'selector'       => '%%order_class%% .marker-cluster.marker-cluster-large',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);

		if ( function_exists( 'et_core_is_fb_enabled' ) && function_exists( 'et_builder_bfb_enabled' ) ) {
			if ( ! et_core_is_fb_enabled() || ! et_builder_bfb_enabled() ) {
				wp_enqueue_script( 'supreme_maps-leaflet' );
				wp_enqueue_script( 'supreme_maps-leaflet-bing' );
				if ( 'on' === $marker_cluster ) {
					wp_enqueue_script( 'supreme_maps-leaflet-clustering' );
				}
			}
		}
		wp_enqueue_script( 'supreme-maps-frontend-bundle' );
		wp_dequeue_script( 'google-maps-api' );

		return sprintf(
			'<div class="supreme_leaflet_map_container" data-supreme-leaflet-options=%1$s>%2$s</div>',
			wp_json_encode( $leaflet_options ),
			$this->props['content']
		);

	}

}

new Supreme_LeafletMap();
