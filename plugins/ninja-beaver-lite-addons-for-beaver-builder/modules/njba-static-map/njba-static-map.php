<?php

/**
 * @class NJBA_Static_Map_Module
 */
class NJBA_Static_Map_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Google Static Map', 'bb-njba' ),
			'description'     => __( 'Addon to display Google Static map.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'creative' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-static-map/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-static-map/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
	}

	/**
	 * Get google map zoom level
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function njba_Get_Google_Map_Zoom_Levels() {
		$count = array();
		for ( $i = 1; $i < 21; $i ++ ) {
			$count[] = $i;
		}

		return $count;
	}

	/**
	 * Use this method to work with settings data before
	 * it is saved. You must return the settings object.
	 *
	 * @method update
	 * @param $settings {object}
	 *
	 * @return object
	 */
	public function update( $settings ) {
		return $settings;
	}

	/**
	 * This method will be called by the builder
	 * right before the module is deleted.
	 *
	 * @method delete
	 */
	public function delete() {
	}

	/**
	 * Get google static map url
	 * @return string
	 * @since 1.0.0
	 */
	public function njba_get_google_static_map_url() {
		return '//maps.googleapis.com/maps/api/staticmap?' . $this->njba_get_google_map_url_parameters() . $this->njba_get_Google_map_markers_url();
	}

	public function njba_get_google_map_url_parameters() {
		$map_size     = $this->settings->map_custom_size;
		$admin_option = get_option( 'njba_options' );
		$url_query    = array(
			'center' => isset( $this->settings->map_location ) ? $this->settings->map_location : '',
			'scale'  => 2,
			'zoom'   => isset( $this->settings->map_zoom ) ? $this->settings->map_zoom : '15',
			'size'   => isset( $this->settings->map_custom_size ) ? $this->settings->map_custom_size : '640x320',
			'format' => isset( $this->settings->map_image_format ) ? $this->settings->map_image_format : 'jpg',
			'key'    => $admin_option['google_static_map_api_key'],
		);

		return http_build_query( $url_query );
	}

	/**
	 * Get google static map marker url
	 * @return bool|string|null
	 * @since 1.0.0
	 */
	public function njba_get_Google_map_markers_url() {
		if ( empty( $this->settings->markers ) ) {
			return null;
		}
		unset( $marker_styles );
		$marker_styles = array();
		$i             = 0;
		foreach ( $this->settings->markers as $marker ) {
			$marker_styles[ $i ] = array(
				'size'    => isset( $marker->marker_size ) ? $marker->marker_size : 'normal',
				'markers' => array(
					isset( $marker->marker_location ) ? $marker->marker_location : '',
				),
			);
			if ( is_object( $marker ) ) {
				if ( 'custom_image' === $marker->marker_type && isset( $marker->marker_custom_image_src ) ) {
					$marker_styles[ $i ]['icon'] = $marker->marker_custom_image_src;
				} else {
					$marker_styles[ $i ]['color'] = isset( $marker->marker_color ) ? '0x' . $marker->marker_color : '';
				}
			}
			$i ++;
		}
		$marker_configs = array();
		$marker_url     = '';
		if ( ! empty( $marker_styles ) ) {
			foreach ( $marker_styles as $marker_style ) {
				$marker_style_locations = implode( '|', $marker_style['markers'] ); // Gather the encoded URL locations for this specific marker style
				unset( $marker_style['markers'] ); // Remove this from the array as it doesn't conform to same format as other properties
				$marker_properties_formatted = array();
				foreach ( $marker_style as $property => $value ) {
					$marker_properties_formatted[] = $property . ':' . $value;
				}
				$marker_configs[] = implode( '|', $marker_properties_formatted ) . '|' . $marker_style_locations;
			}
		}
		if ( ! empty( $marker_configs ) ) {
			foreach ( $marker_configs as $marker_config ) {
				$marker_url .= '&markers=' . urlencode( $marker_config );
			}
		}
		if ( isset( $marker_url ) ) {
			return $marker_url;
		}

		return false;
	}
}

FLBuilder::register_module( 'NJBA_Static_Map_Module', array(
	'general' => array(
		'title'    => __( 'General', 'bb-njba' ),
		'sections' => array(
			'map'     => array(
				'title'  => __( 'Map', 'bb-njba' ),
				'fields' => array(
					'map_location'     => array(
						'type'  => 'text',
						'label' => __( 'Center location', 'bb-njba' ),
					),
					'map_zoom'         => array(
						'type'    => 'select',
						'label'   => __( 'Zoom level', 'bb-njba' ),
						'default' => '15',
						'options' => NJBA_Static_Map_Module::njba_Get_Google_Map_Zoom_Levels(),
					),
					'map_custom_size'  => array(
						'type'    => 'select',
						'label'   => __( 'Map Style', 'bb-njba' ),
						'default' => '640x640',
						'options' => array(
							'640x640' => __( 'Square', 'bb-njba' ),
							'640x320' => __( 'Horizontal Rectangle', 'bb-njba' ),
							'320x640' => __( 'Vertical Rectangle', 'bb-njba' ),
						),
					),
					'map_image_format' => array(
						'type'    => 'select',
						'label'   => __( 'Map image format', 'bb-njba' ),
						'default' => 'jpg',
						'options' => array(
							'jpg' => __( 'JPEG', 'bb-njba' ),
							'gif' => __( 'GIF', 'bb-njba' ),
							'PNG' => __( 'PNG', 'bb-njba' ),
						)
					),
				),
			),
			'markers' => array(
				'title'  => __( 'Markers', 'bb-njba' ),
				'fields' => array(
					'markers' => array(
						'type'         => 'form',
						'label'        => __( 'Marker', 'bb-njba' ),
						'form'         => 'njba_map_marker_form',
						'preview_text' => __( 'Marker', 'bb-njba' ),
						'multiple'     => true,
					),
				),
			),
		),
	),
) );
FLBuilder::register_settings_form( 'njba_map_marker_form', array(
	'title' => __( 'Google Map marker', 'bb-njba' ),
	'tabs'  => array(
		'general' => array(
			'title'    => __( 'General', 'bb-njba' ),
			'sections' => array(
				'marker_location' => array(
					'title'  => __( 'Marker location', 'bb-njba' ),
					'fields' => array(
						'marker_location' => array(
							'type'  => 'text',
							'label' => __( 'Marker Location', 'bb-njba' ),
							'help'  => __( 'Enter the location of your place.', 'bb-njba' )
						),
					),
				),
				'marker_style'    => array(
					'title'  => __( 'Marker style', 'bb-njba' ),
					'fields' => array(
						'marker_type'         => array(
							'type'    => 'select',
							'label'   => __( 'Marker type', 'bb-njba' ),
							'default' => 'pin',
							'options' => array(
								'pin'          => __( 'Pin', 'bb-njba' ),
								'custom_image' => __( 'Custom image', 'bb-njba' ),
							),
							'toggle'  => array(
								'pin'          => array(
									'fields' => array( 'marker_color' ),
								),
								'custom_image' => array(
									'fields' => array( 'marker_custom_image' ),
								),
							)
						),
						'marker_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Marker colour', 'bb-njba' ),
							'show_reset' => true,
						),
						'marker_custom_image' => array(
							'type'  => 'photo',
							'label' => __( 'Custom marker image', 'bb-njba' ),
							'help'  => __( 'Must be a maximum of 64x64px in size, and GIF, PNG or JPG format', 'bb-njba' ),
						),
					),
				),
			)
		)
	)
) );
?>
