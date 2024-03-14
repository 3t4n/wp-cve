<?php

/**
 * @class NJBA_Image_Separator
 */
class NJBA_Image_Separator extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public $data = null;

	protected $_editor = null;

	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Image Separator', 'bb-njba' ),
			'description'     => __( 'Use Image as a separator', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'separator' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-img-separator/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-img-separator/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => false, // Defaults to false and can be omitted.
			'icon'            => 'minus.svg',
		) );

		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'font-awesome' );
		$this->add_css( 'njba-separator-frontend', NJBA_MODULE_URL . 'modules/njba-img-separator/css/frontend.css' );

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
		if ( ! isset( $settings->photo_src ) ) { // Make sure we have a photo_src property.
			$settings->photo_src = '';
		}
		$data = FLBuilderPhoto::get_attachment_data( $settings->photo ); // Cache the attachment data.
		if ( $data ) {
			$settings->data = $data;
		}
		$this->crop(); // Save a crop if necessary.

		return $settings;
	}


	/**
	 * @return bool|mixed
	 */
	public function crop() {
		$this->delete(); // Delete an existing crop if it exists.
		if ( ! empty( $this->settings->image_style ) && $this->settings->image_style !== 'simple' && $this->settings->image_style !== 'custom' ) { // Do a crop.
			$editor = $this->njbaGetEditor();
			if ( ! $editor || is_wp_error( $editor ) ) {
				return false;
			}
			$cropped_path = $this->njbaGetCroppedPath();
			$size         = $editor->get_size();
			$new_width    = $size['width'];
			$new_height   = $size['height'];
			if ( $this->settings->image_style === 'circle' ) { // Get the crop ratios.
				$ratio_1 = 1;
				$ratio_2 = 1;
			} elseif ( $this->settings->image_style === 'square' ) {
				$ratio_1 = 1;
				$ratio_2 = 1;
			}
			if ( $size['width'] / $size['height'] < $ratio_1 ) {
				$new_height = $size['width'] * $ratio_2;
			} else {
				$new_width = $size['height'] * $ratio_1;
			}
			@ini_set( 'memory_limit', '300M' ); // Make sure we have enough memory to crop.
			$editor->resize( $new_width, $new_height, true ); // Crop the photo.
			$editor->save( $cropped_path['path'] ); // Save the photo.

			return $cropped_path['url']; // Return the new url.
		}

		return false;
	}

	/**
	 * This method will be called by the builder
	 * right before the module is deleted.
	 *
	 * @method delete
	 */
	public function delete() {
		$cropped_path = $this->njbaGetCroppedPath();
		if ( file_exists( $cropped_path['path'] ) ) {
			unlink( $cropped_path['path'] );
		}
	}

	/**
	 * @method njbaGetCroppedPath
	 * @protected
	 */
	protected function njbaGetCroppedPath() {
		$crop      = empty( $this->settings->image_style ) ? 'simple' : $this->settings->image_style;
		$url       = $this->njbaGetUncroppedUrl();
		$cache_dir = FLBuilderModel::get_cache_dir();
		if ( empty( $url ) ) {
			$filename = uniqid( '', true ); // Return a file that doesn't exist.
		} else {
			if ( strpos( $url, '?' ) !== false ) {
				$parts = explode( '?', $url );
				$url   = $parts[0];
			}
			$pathinfo = pathinfo( $url );
			$dir      = $pathinfo['dirname'];
			$ext      = $pathinfo['extension'];
			$name     = wp_basename( $url, ".$ext" );
			$new_ext  = strtolower( $ext );
			$filename = "{$name}-{$crop}.{$new_ext}";
		}

		return array(
			'filename' => $filename,
			'path'     => $cache_dir['path'] . $filename,
			'url'      => $cache_dir['url'] . $filename
		);
	}

	/**
	 * @method njbaGetUncroppedUrl
	 * @protected
	 */
	protected function njbaGetUncroppedUrl() {
		if ( ! empty( $this->settings->photo_src ) ) {
			$url = $this->settings->photo_src;
		} else {
			$url = FL_BUILDER_URL . 'img/pixel.png';
		}

		return $url;
	}

	/**
	 * @method njbaGetEditor
	 * @protected
	 */
	protected function njbaGetEditor() {
		if ( $this->njbaHasSource() && $this->_editor === null ) {
			$url_path  = $this->njbaGetUncroppedUrl();
			$file_path = str_ireplace( home_url(), ABSPATH, $url_path );
			if ( file_exists( $file_path ) ) {
				$this->_editor = wp_get_image_editor( $file_path );
			} else {
				$this->_editor = wp_get_image_editor( $url_path );
			}
		}

		return $this->_editor;
	}

	/**
	 * @method njbaHasSource
	 * @protected
	 */
	protected function njbaHasSource() {
		if ( ! empty( $this->settings->photo_src ) ) {
			return true;
		}

		return false;
	}


	/**
	 * @return string
	 * @since 1.0.0
	 */
	public function njbaGetImgClass() {
		$classes = array( 'njba-sep-image' );
		if ( ! empty( $this->settings->photo ) ) {
			$data = $this->njbaGetData();
			if ( is_object( $data ) ) {
				$classes[] = 'wp-image-' . $data->id;
				if ( isset( $data->sizes ) ) {
					foreach ( $data->sizes as $key => $size ) {
						if ( $size->url === $this->settings->photo_src ) {
							$classes[] = 'size-' . $key;
							break;
						}
					}
				}
			}
		}

		return implode( ' ', $classes );
	}

	/**
	 * @method njbaGetData
	 */
	public function njbaGetData() {
		if ( ! $this->data ) {
			// Photo source is set to "library".
			if ( is_object( $this->settings->photo ) ) {
				$this->data = $this->settings->photo;
			} else {
				$this->data = FLBuilderPhoto::get_attachment_data( $this->settings->photo );
			}
			// Data object is empty, use the settings cache.
			if ( ! $this->data && isset( $this->settings->data ) ) {
				$this->data = $this->settings->data;
			}
		}

		return $this->data;
	}


	/**
	 * @return bool|mixed|string
	 * @since 1.0.0
	 */
	public function njbaGetImgSrc() {
		$src = $this->njbaGetUncroppedUrl();
		if ( $this->njbaHasSource() && ! empty( $this->settings->image_style ) ) { // Return a cropped photo.
			$cropped_path = $this->njbaGetCroppedPath();
			if ( file_exists( $cropped_path['path'] ) ) { // See if the cropped photo already exists.
				$src = $cropped_path['url'];
			} // It doesn't, check if this is a demo image.
			elseif ( stripos( $src, FL_BUILDER_DEMO_URL ) !== false && stripos( FL_BUILDER_DEMO_URL, $_SERVER['HTTP_HOST'] ) === false ) {
				$src = $this->GetCroppedDemoUrl();
			} // It doesn't, check if this is a OLD demo image.
			elseif ( stripos( $src, FL_BUILDER_OLD_DEMO_URL ) !== false ) {
				$src = $this->GetCroppedDemoUrl();
			} // A cropped photo doesn't exist, try to create one.
			else {
				$url = $this->crop();
				if ( $url ) {
					$src = $url;
				}
			}
		}

		return $src;
	}


	/**
	 * @return string
	 * @protected
	 * @since 1.0.0
	 */
	protected function GetCroppedDemoUrl() {
		$info = $this->njbaGetCroppedPath();

		return FL_BUILDER_DEMO_CACHE_URL . $info['filename'];
	}


	/**
	 * @return string
	 * @since 1.0.0
	 */
	public function njbaGetImgAlt() {
		$photo = $this->njbaGetData();
		if ( ! empty( $photo->alt ) ) {
			return htmlspecialchars( $photo->alt );
		}

		if ( ! empty( $photo->description ) ) {
			return htmlspecialchars( $photo->description );
		}

		if ( ! empty( $photo->caption ) ) {
			return htmlspecialchars( $photo->caption );
		}

		if ( ! empty( $photo->title ) ) {
			return htmlspecialchars( $photo->title );
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Image_Separator', array(
	'general'   => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			/* Image Basic Setting */
			'img_basic'    => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'photo'           => array(
						'type'        => 'photo',
						'label'       => __( 'Separator Image', 'bb-njba' ),
						'show_remove' => true,
					),
					'img_size'        => array(
						'type'        => 'text',
						'label'       => __( 'Desktop Width', 'bb-njba' ),
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
						'help'        => __( 'Image size cannot be more than parent size.', 'bb-njba' ),
					),
					'medium_img_size' => array(
						'type'        => 'text',
						'label'       => __( 'Tablet Width', 'bb-njba' ),
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
						'help'        => __( 'Apply image size for medium devices. It will inherit desktop size if empty.', 'bb-njba' ),
						'preview'     => array(
							'type' => 'none'
						)
					),
					'small_img_size'  => array(
						'type'        => 'text',
						'label'       => __( 'Mobile Width', 'bb-njba' ),
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
						'help'        => __( 'Apply image size for small devices. It will inherit medium size if empty.', 'bb-njba' ),
						'preview'     => array(
							'type' => 'none'
						)
					),
				)
			),
			'img_stucture' => array(
				'title'  => __( 'Image Structure', 'bb-njba' ),
				'fields' => array(
					/* Image Position */
					'image_position'    => array(
						'type'    => 'select',
						'label'   => __( 'Image Top / Bottom Position', 'bb-njba' ),
						'default' => 'bottom',
						'help'    => __( 'Select the position to display Image Separator', 'bb-njba' ),
						'options' => array(
							'bottom' => __( 'Bottom', 'bb-njba' ),
							'top'    => __( 'Top', 'bb-njba' )
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					/* Image top bottom size */
					'gutter'            => array(
						'type'        => 'text',
						'label'       => __( 'Value from Top / Bottom', 'bb-njba' ),
						'placeholder' => '50',
						'help'        => __( '50% is default. Increase to push the image outside or decrease to pull the image inside.', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '6',
						'description' => '%',
					),
					'image_position_lr' => array(
						'type'    => 'select',
						'label'   => __( 'Image Left / Right Position', 'bb-njba' ),
						'default' => 'center',
						'help'    => __( 'Select the position to display Image Separator', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						),
						'toggle'  => array(
							'left'  => array(
								'fields' => array( 'gutter_lr', 'responsive_center' )
							),
							'right' => array(
								'fields' => array( 'gutter_lr', 'responsive_center' )
							)
						)
					),
					'gutter_lr'         => array(
						'type'        => 'text',
						'label'       => __( 'Value from Left / Right', 'bb-njba' ),
						'placeholder' => '50',
						'help'        => __( 'From left / From right', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '6',
						'description' => '%',
					),

					'responsive_center' => array(
						'type'    => 'select',
						'label'   => __( 'Responsive Alignment', 'bb-njba' ),
						'default' => 'none',
						'help'    => __( 'To view Image Separator center aligned on different devices use this setting', 'bb-njba' ),
						'options' => array(
							'none'  => __( 'Default', 'bb-njba' ),
							'small' => __( 'Small Device', 'bb-njba' ),
							'both'  => __( 'Small & Medium Devices', 'bb-njba' ),
						),
					),
					'enable_link'       => array(
						'type'    => 'select',
						'label'   => __( 'Enable Link', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array( 'link', 'link_target' )
							),
						),
					),
					'link'              => array(
						'type'    => 'link',
						'label'   => __( 'Link', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
					'link_target'       => array(
						'type'    => 'select',
						'label'   => __( 'Link Target', 'bb-njba' ),
						'default' => '_self',
						'options' => array(
							'_self'  => __( 'Same Window', 'bb-njba' ),
							'_blank' => __( 'New Window', 'bb-njba' )
						),
						'preview' => array(
							'type' => 'none'
						)
					),
				)
			),
		)
	),
	/* Image Style Section */
	'style'     => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'img_style'    => array(
				'title'  => __( 'Style', 'bb-njba' ),
				'fields' => array(
					/* Image Style */
					'image_style'          => array(
						'type'    => 'select',
						'label'   => __( 'Image Style', 'bb-njba' ),
						'default' => 'simple',
						'help'    => __( 'Circle and Square style will crop your image in 1:1 ratio', 'bb-njba' ),
						'options' => array(
							'simple' => __( 'Simple', 'bb-njba' ),
							'circle' => __( 'Circle', 'bb-njba' ),
							'square' => __( 'Square', 'bb-njba' ),
							'custom' => __( 'Design your own', 'bb-njba' ),
						),
						'toggle'  => array(
							'simple' => array(
								'fields' => array()
							),
							'circle' => array(
								'fields' => array(),
							),
							'square' => array(
								'fields' => array(),
							),
							'custom' => array(
								'sections' => array( 'image_colors' ),
								'fields'   => array( 'img_bg_size', 'img_border_style', 'img_bg_border_radius', 'box_shadow', 'box_shadow_color', 'box_shadow_opacity' )
							)
						)
					),
					'img_bg_size'          => array(
						'type'        => 'text',
						'label'       => __( 'Background Size', 'bb-njba' ),
						'help'        => __( 'Spacing between Image edge & Background edge', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '6',
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-image .njba-sep-image',
							'property' => 'padding',
							'unit'     => 'px'
						)
					),
					'img_border_style'     => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'none',
						'help'    => __( 'The type of border to use. Double borders must have a width of at least 3px to render properly.', 'bb-njba' ),
						'options' => array(
							'none'   => __( 'None', 'Border type.', 'bb-njba' ),
							'solid'  => __( 'Solid', 'Border type.', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'Border type.', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'Border type.', 'bb-njba' ),
							'double' => __( 'Double', 'Border type.', 'bb-njba' )
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'img_border_width', 'img_border_color', 'img_border_hover_color' )
							),
							'dashed' => array(
								'fields' => array( 'img_border_width', 'img_border_color', 'img_border_hover_color' )
							),
							'dotted' => array(
								'fields' => array( 'img_border_width', 'img_border_color', 'img_border_hover_color' )
							),
							'double' => array(
								'fields' => array( 'img_border_width', 'img_border_color', 'img_border_hover_color' )
							)
						),
					),
					'img_border_width'     => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '6',
						'placeholder' => '1',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-image .njba-sep-image',
							'property' => 'border-width',
							'unit'     => 'px'
						)
					),
					'img_bg_border_radius' => array(
						'type'        => 'text',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '6',
						'placeholder' => '0',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-image .njba-sep-image',
							'property' => 'border-radius',
							'unit'     => 'px'
						)
					),
					'box_shadow'           => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Box Shadow', 'bb-njba' ),
						'default' => array(
							'vertical'   => 0,
							'horizontal' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options' => array(
							'vertical'   => array(
								'placeholder' => __( 'Vertical', 'bb-njba' ),
								'tooltip'     => __( 'Vertical', 'bb-njba' ),
								'icon'        => 'fa-arrows-v'
							),
							'horizontal' => array(
								'placeholder' => __( 'Horizontal', 'bb-njba' ),
								'tooltip'     => __( 'Horizontal', 'bb-njba' ),
								'icon'        => 'fa-arrows-h'
							),
							'blur'       => array(
								'placeholder' => __( 'Blur', 'bb-njba' ),
								'tooltip'     => __( 'Blur', 'bb-njba' ),
								'icon'        => 'fa-circle-o'
							),
							'spread'     => array(
								'placeholder' => __( 'Spread', 'bb-njba' ),
								'tooltip'     => __( 'Spread', 'bb-njba' ),
								'icon'        => 'fa-paint-brush'
							),
						)
					),
					'box_shadow_color'     => array(
						'type'    => 'color',
						'label'   => __( 'Shadow Color', 'bb-njba' ),
						'default' => '000000',
					),
					'box_shadow_opacity'   => array(
						'type'        => 'text',
						'label'       => __( 'Shadow Opacity', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => 50,
						'placeholder' => 50,
					),
				)
			),
			'image_colors' => array( // Section
				'title'  => __( 'Colors', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					/* Background Color Dependent on Icon Style **/
					'image_bg_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-image .njba-sep-image',
							'property' => 'background',
						)
					),
					'img_bg_color_opc'       => array(
						'type'        => 'text',
						'label'       => __( 'Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'img_bg_hover_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'img_bg_hover_color_opc' => array(
						'type'        => 'text',
						'label'       => __( 'Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'img_border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-image .njba-sep-image',
							'property' => 'border-color',
						)
					),
					'img_border_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none',
						)
					),
				)
			),
		)
	),
	'animation' => array( // Tab
		'title'    => __( 'Animation', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'animation_general' => array(
				'title'  => '',
				'fields' => array(
					'img_animation'         => array(
						'type'    => 'select',
						'label'   => __( 'Animation', 'bb-njba' ),
						'default' => 'no',
						'help'    => __( 'Choose one of the animation types for Separator.', 'bb-njba' ),
						'options' => array(
							'no'                => __( 'No', 'bb-njba' ),
							'bounce'            => __( 'bounce', 'bb-njba' ),
							'flash'             => __( 'flash', 'bb-njba' ),
							'pulse'             => __( 'pulse', 'bb-njba' ),
							'rubberBand'        => __( 'rubberBand', 'bb-njba' ),
							'shake'             => __( 'shake', 'bb-njba' ),
							'headShake'         => __( 'headShake', 'bb-njba' ),
							'swing'             => __( 'swing', 'bb-njba' ),
							'tada'              => __( 'tada', 'bb-njba' ),
							'wobble'            => __( 'wobble', 'bb-njba' ),
							'jello'             => __( 'jello', 'bb-njba' ),
							'bounceIn'          => __( 'bounceIn', 'bb-njba' ),
							'bounceInDown'      => __( 'bounceInDown', 'bb-njba' ),
							'bounceInLeft'      => __( 'bounceInLeft', 'bb-njba' ),
							'bounceInRight'     => __( 'bounceInRight', 'bb-njba' ),
							'bounceInUp'        => __( 'bounceInUp', 'bb-njba' ),
							'fadeIn'            => __( 'fadeIn', 'bb-njba' ),
							'fadeInDown'        => __( 'fadeInDown', 'bb-njba' ),
							'fadeInDownBig'     => __( 'fadeInDownBig', 'bb-njba' ),
							'fadeInLeft'        => __( 'fadeInLeft', 'bb-njba' ),
							'fadeInLeftBig'     => __( 'fadeInLeftBig', 'bb-njba' ),
							'fadeInRight'       => __( 'fadeInRight', 'bb-njba' ),
							'fadeInRightBig'    => __( 'fadeInRightBig', 'bb-njba' ),
							'fadeInUp'          => __( 'fadeInUp', 'bb-njba' ),
							'fadeInUpBig'       => __( 'fadeInUpBig', 'bb-njba' ),
							'flipInX'           => __( 'flipInX', 'bb-njba' ),
							'flipInY'           => __( 'flipInY', 'bb-njba' ),
							'flipOutX'          => __( 'flipOutX', 'bb-njba' ),
							'flipOutY'          => __( 'flipOutY', 'bb-njba' ),
							'lightSpeedIn'      => __( 'lightSpeedIn', 'bb-njba' ),
							'rotateIn'          => __( 'rotateIn', 'bb-njba' ),
							'rotateInDownLeft'  => __( 'rotateInDownLeft', 'bb-njba' ),
							'rotateInDownRight' => __( 'rotateInDownRight', 'bb-njba' ),
							'rotateInUpLeft'    => __( 'rotateInUpLeft', 'bb-njba' ),
							'rotateInUpRight'   => __( 'rotateInUpRight', 'bb-njba' ),
							'rollIn'            => __( 'rollIn', 'bb-njba' ),
							'zoomIn'            => __( 'zoomIn', 'bb-njba' ),
							'zoomInDown'        => __( 'zoomInDown', 'bb-njba' ),
							'zoomInLeft'        => __( 'zoomInLeft', 'bb-njba' ),
							'zoomInRight'       => __( 'zoomInRight', 'bb-njba' ),
							'zoomInUp'          => __( 'zoomInUp', 'bb-njba' ),
							'slideInDown'       => __( 'slideInDown', 'bb-njba' ),
							'slideInLeft'       => __( 'slideInLeft', 'bb-njba' ),
							'slideInRight'      => __( 'slideInRight', 'bb-njba' ),
							'slideInUp'         => __( 'slideInUp', 'bb-njba' ),
						),
					),
					'img_animation_delay'   => array(
						'type'        => 'text',
						'label'       => __( 'Animation Delay', 'bb-njba' ),
						'placeholder' => '0',
						'help'        => __( 'Delay the animation effect for seconds you entered.', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '6',
						'description' => 'sec',
					),
					'img_animation_repeat'  => array(
						'type'        => 'text',
						'label'       => __( 'Repeat Animation', 'bb-njba' ),
						'placeholder' => '1',
						'help'        => __( 'The animation effect will repeat to the count you enter. Enter 0 if you want to repeat it infinitely.', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '6',
						'description' => 'times',
					),
					'img_viewport_position' => array(
						'type'        => 'text',
						'label'       => __( 'Viewport Position', 'bb-njba' ),
						'placeholder' => '90',
						'help'        => __( 'The area of screen from top where animation effect will start working.', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '6',
						'description' => '%',
					),
				)
			),
		)
	),
) );
