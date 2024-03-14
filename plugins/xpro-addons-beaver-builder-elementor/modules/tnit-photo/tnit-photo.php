<?php
/**
 * @class tnitphoto
 */

class TNITPhoto extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Image / Icon', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$media_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-photo/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-photo/',
				'partial_refresh' => true,
			)
		);
	}

	/**
	 * Function that renders Image
	 *
	 * @method render_image
	 */
	public function render_image() {
		$output        = '';
		$alt           = '';
		$thumb_classes = 'tnit-photo-thumb';
		$photo         = FLBuilderPhoto::get_attachment_data( $this->settings->photo );
		if ( ! empty( $photo->alt ) ) {
			$alt = htmlspecialchars( $photo->alt );
		} elseif ( ! empty( $photo->description ) ) {
			$alt = htmlspecialchars( $photo->description );
		} elseif ( ! empty( $photo->caption ) ) {
			$alt = htmlspecialchars( $photo->caption );
		} elseif ( ! empty( $photo->title ) ) {
			$alt = htmlspecialchars( $photo->title );
		}
		// get image from media library.
		if ( 'library' === $this->settings->photo_source ) {
			$output .= '<img src="' . esc_url( $this->settings->photo_src ) . '" alt="' . $alt . '">';
		}
		// get image external URL.
		elseif ( 'url' === $this->settings->photo_source ) {
			$output .= '<img src="' . esc_url( $this->settings->photo_url ) . '" alt="' . $alt . '">';
		}

		echo $output;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITPhoto',
	array(
		'general' => array(
			'title'    => __( 'General', 'tnit' ),
			'sections' => array(
				'type_general' => array(
					'title'  => __( '', 'xpro-bb-addons' ),
					'fields' => array(
						'image_type' => array(
							'type'    => 'button-group',
							'label'   => __( 'Image Type', 'xpro-bb-addons' ),
							'default' => 'icon',
							'options' => array(
								'icon'  => __( 'Icon', 'xpro-bb-addons' ),
								'photo' => __( 'Photo', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'icon'  => array(
									'sections' => array( 'icon_basic', 'icon_style', 'icon_colors' ),
								),
								'photo' => array(
									'sections' => array( 'img_basic', 'img_style', 'img_colors', 'image_overlay', 'image_effects', 'image_effects_hover' ),
								),
							),
						),
					),
				),
				'icon_basic'   => array(
					'title'  => __( 'Icon Basics', 'xpro-bb-addons' ),
					'fields' => array(
						'icon' => array(
							'type'        => 'icon',
							'label'       => __( 'Icon', 'xpro-bb-addons' ),
							'default'     => 'fas fa-user-alt',
							'show_remove' => true,
						),
					),
				),
				'img_basic'    => array(
					'title'  => __( 'Image Basics', 'xpro-bb-addons' ),
					'fields' => array(
						'photo_source' => array(
							'type'    => 'button-group',
							'label'   => __( 'Photo Source', 'xpro-bb-addons' ),
							'default' => 'library',
							'options' => array(
								'library' => __( 'Media Library', 'xpro-bb-addons' ),
								'url'     => __( 'URL', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'library' => array(
									'fields' => array( 'photo' ),
								),
								'url'     => array(
									'fields' => array( 'photo_url' ),
								),
							),
						),
						'photo'        => array(
							'type'        => 'photo',
							'label'       => __( 'Photo', 'xpro-bb-addons' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'photo_url'    => array(
							'type'        => 'text',
							'label'       => __( 'Photo URL', 'xpro-bb-addons' ),
							'placeholder' => 'http://www.example.com/my-photo.webp',
						),
					),
				),
				'link'         => array(
					'title'  => __( 'Link', 'xpro-bb-addons' ),
					'fields' => array(
						'image_link' => array(
							'type'          => 'link',
							'label'         => 'Link',
							'show_target'   => true,
							'show_nofollow' => true,
							'connections'   => array( 'url' ),
						),
					),
				),
			),
		),
		'style'   => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'icon_style'          => array(
					'title'  => __( 'Icon Style', 'xpro-bb-addons' ),
					'fields' => array(
						'icon_style'        => array(
							'type'    => 'select',
							'label'   => __( 'Icon Background Style', 'xpro-bb-addons' ),
							'default' => 'simple',
							'options' => array(
								'simple' => __( 'Simple', 'xpro-bb-addons' ),
								'circle' => __( 'Circle Background', 'xpro-bb-addons' ),
								'square' => __( 'Square Background', 'xpro-bb-addons' ),
								'custom' => __( 'Design your own', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'simple' => array(
									'fields' => array(),
								),
								'circle' => array(
									'fields' => array( 'icon_bg_size', 'icon_bg_color', 'icon_bg_hover_color' ),
								),
								'square' => array(
									'fields' => array( 'icon_bg_size', 'icon_bg_color', 'icon_bg_hover_color' ),
								),
								'custom' => array(
									'fields' => array( 'icon_bg_size', 'icon_border_style', 'icon_bg_color', 'icon_bg_hover_color', 'icon_border_hover' ),
								),
							),
						),
						'icon_alignment'    => array(
							'type'       => 'align',
							'label'      => __( 'Icon Alignment', 'xpro-bb-addons' ),
							'default'    => 'center',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon-wrapper',
										'property' => 'text-align',
									),
								),
							),
						),
						'icon_size'         => array(
							'type'        => 'unit',
							'label'       => __( 'Icon Size', 'xpro-bb-addons' ),
							'placeholder' => '24',
							'units'       => array( 'px' ),
							'slider'      => true,
							'responsive'  => true,
							'preview'     => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon',
										'property' => 'font-size',
									),
								),
							),
						),
						'icon_bg_size'      => array(
							'type'        => 'unit',
							'label'       => 'Background Size',
							'units'       => array( 'px' ),
							'placeholder' => '60',
							'responsive'  => true,
							'slider'      => true,
							'help'        => __( 'Spacing between Icon & Background edge', 'xpro-bb-addons' ),
							'preview'     => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon-circle,.tnit-photo-icon-square,.tnit-photo-icon-custom',
										'property' => 'width',
									),
									array(
										'selector' => '.tnit-photo-icon-circle,.tnit-photo-icon-square,.tnit-photo-icon-custom',
										'property' => 'height',
									),
								),
							),
						),
						'icon_border_style' => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon-custom:border',
										'property' => 'text-align',
									),
								),
							),
						),
						'icon_border_hover' => array(
							'type'       => 'border',
							'label'      => 'Border Hover',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon-custom:border:hover',
										'property' => 'text-align',
									),
								),
							),
						),
					),
				),
				'img_style'           => array(
					'title'  => __( 'Image Style', 'xpro-bb-addons' ),
					'fields' => array(
						'image_style'        => array(
							'type'    => 'select',
							'label'   => __( 'Image Style', 'xpro-bb-addons' ),
							'default' => 'simple',
							'help'    => __( 'Circle and Square style will crop your image', 'xpro-bb-addons' ),
							'options' => array(
								'simple' => __( 'Simple', 'xpro-bb-addons' ),
								'circle' => __( 'Circle', 'xpro-bb-addons' ),
								'square' => __( 'Square', 'xpro-bb-addons' ),
								'custom' => __( 'Design your own', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'simple' => array(
									'fields' => array( '' ),
								),
								'circle' => array(
									'fields' => array( '' ),
								),
								'square' => array(
									'fields' => array( '' ),
								),
								'custom' => array(
									'fields' => array( 'img_bg_size', 'img_border_style', 'photo_border', 'photo_border_hover', 'photo_padding', 'img_bg_color' ),
								),
							),
						),
						'image_size'         => array(
							'type'        => 'unit',
							'label'       => __( 'Size', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => 'auto',
							'slider'      => true,
							'responsive'  => true,
						),
						'photo_alignment'    => array(
							'type'       => 'align',
							'label'      => __( 'Photo Alignment', 'xpro-bb-addons' ),
							'default'    => 'center',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-module-image-icon',
										'property' => 'text-align',
									),
								),
							),
						),
						'img_bg_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.photo-custom-style',
										'property' => 'background-color',
									),
								),
							),
						),
						'photo_padding'      => array(
							'type'        => 'dimension',
							'label'       => 'Padding',
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'slider'      => true,
							'responsive'  => true,
							'preview'     => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.photo-custom-style',
										'property' => 'padding',
									),
								),
							),
						),
						'photo_border'       => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.photo-custom-style',
										'property' => 'border',
									),
								),
							),
						),
						'photo_border_hover' => array(
							'type'       => 'border',
							'label'      => 'Border Hover',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.photo-custom-style:hover',
										'property' => 'border',
									),
								),
							),
						),
					),
				),
				'icon_colors'         => array(
					'title'  => __( 'Icon Colors', 'xpro-bb-addons' ),
					'fields' => array(
						'icon_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon',
										'property' => 'color',
									),
								),
							),
						),
						'icon_hover_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Icon Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon:hover',
										'property' => 'color',
									),
								),
							),
						),
						'icon_bg_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon-circle,.tnit-photo-icon-square,.tnit-photo-icon-custom',
										'property' => 'background-color',
									),
								),
							),
						),
						'icon_bg_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-photo-icon-circle:hover,.tnit-photo-icon-square:hover,.tnit-photo-icon-custom:hover',
										'property' => 'background-color',
									),
								),
							),
						),
					),
				),
				'image_effects'       => array(
					'title'     => __( 'Image Effects', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'photo_blur'       => array(
							'type'        => 'unit',
							'label'       => 'Blur',
							'units'       => array( 'px' ),
							'help'        => __( 'Applies a blur effect to the image. A larger value will create more blur.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_brightness' => array(
							'type'        => 'unit',
							'label'       => 'Brightness',
							'units'       => array( '%' ),
							'help'        => __( 'Adjusts the brightness of the image. 0% will make the image completely black. 100% (1) is default and represents the original image. Values over 100% will provide brighter results.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_contrast'   => array(
							'type'        => 'unit',
							'label'       => 'Contrast',
							'units'       => array( '%' ),
							'help'        => __( 'Adjusts the contrast of the image. 0% will make the image completely black. 100% (1) is default and represents the original image. Values over 100% will provide brighter results.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_grayscale'  => array(
							'type'        => 'unit',
							'label'       => 'Grayscale',
							'units'       => array( '%' ),
							'help'        => __( 'Converts the image to grayscale. 0% (0) is default and represents the original image. 100% will make the image completely gray (used for black and white images).', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_hue_rotate' => array(
							'type'        => 'unit',
							'label'       => 'Hue Rotate',
							'units'       => array( 'deg' ),
							'help'        => __( 'Applies a hue rotation on the image. The value defines the number of degrees around the color circle the image samples will be adjusted. 0deg is default, and represents the original image.<br/><strong>Note:</strong> Maximum value is 360deg.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_invert'     => array(
							'type'        => 'unit',
							'label'       => 'Invert',
							'units'       => array( '%' ),
							'help'        => __( 'Inverts the samples in the image. 0% (0) is default and represents the original image. 100% will make the image completely inverted.<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_opacity'    => array(
							'type'        => 'unit',
							'label'       => 'Opacity',
							'units'       => array( '%' ),
							'help'        => __( 'Sets the opacity level for the image. The opacity-level describes the transparency-level, where:<br>0% is completely transparent. 100% (1) is default and represents the original image (no transparency).<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_saturate'   => array(
							'type'        => 'unit',
							'label'       => 'Saturate',
							'units'       => array( '%' ),
							'help'        => __( 'Saturates the image. 0% (0) will make the image completely un-saturated. 100% is default and represents the original image. Values over 100% provides super-saturated results.<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_sepia'      => array(
							'type'        => 'unit',
							'label'       => 'Sepia',
							'units'       => array( '%' ),
							'help'        => __( 'Converts the image to sepia. 0% (0) is default and represents the original image. 100% will make the image completely sepia.<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
					),
				),
				'image_effects_hover' => array(
					'title'     => __( 'Image Effects on Hover', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'photo_blur_hover'       => array(
							'type'        => 'unit',
							'label'       => 'Blur',
							'units'       => array( 'px' ),
							'help'        => __( 'Applies a blur effect to the image. A larger value will create more blur.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_brightness_hover' => array(
							'type'        => 'unit',
							'label'       => 'Brightness',
							'units'       => array( '%' ),
							'help'        => __( 'Adjusts the brightness of the image. 0% will make the image completely black. 100% (1) is default and represents the original image. Values over 100% will provide brighter results.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_contrast_hover'   => array(
							'type'        => 'unit',
							'label'       => 'Contrast',
							'units'       => array( '%' ),
							'help'        => __( 'Adjusts the contrast of the image. 0% will make the image completely black. 100% (1) is default and represents the original image. Values over 100% will provide brighter results.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_grayscale_hover'  => array(
							'type'        => 'unit',
							'label'       => 'Grayscale',
							'units'       => array( '%' ),
							'help'        => __( 'Converts the image to grayscale. 0% (0) is default and represents the original image. 100% will make the image completely gray (used for black and white images).', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_hue_rotate_hover' => array(
							'type'        => 'unit',
							'label'       => 'Hue Rotate',
							'units'       => array( 'deg' ),
							'help'        => __( 'Applies a hue rotation on the image. The value defines the number of degrees around the color circle the image samples will be adjusted. 0deg is default, and represents the original image.<br/><strong>Note:</strong> Maximum value is 360deg.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_invert_hover'     => array(
							'type'        => 'unit',
							'label'       => 'Invert',
							'units'       => array( '%' ),
							'help'        => __( 'Inverts the samples in the image. 0% (0) is default and represents the original image. 100% will make the image completely inverted.<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
						'photo_opacity_hover'    => array(
							'type'        => 'unit',
							'label'       => 'Opacity',
							'units'       => array( '%' ),
							'help'        => __( 'Sets the opacity level for the image. The opacity-level describes the transparency-level, where:<br>0% is completely transparent. 100% (1) is default and represents the original image (no transparency).<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_saturate_hover'   => array(
							'type'        => 'unit',
							'label'       => 'Saturate',
							'units'       => array( '%' ),
							'help'        => __( 'Saturates the image. 0% (0) will make the image completely un-saturated. 100% is default and represents the original image. Values over 100% provides super-saturated results.<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '100',
							'slider'      => true,
						),
						'photo_sepia_hover'      => array(
							'type'        => 'unit',
							'label'       => 'Sepia',
							'units'       => array( '%' ),
							'help'        => __( 'Converts the image to sepia. 0% (0) is default and represents the original image. 100% will make the image completely sepia.<br/><strong>Note:</strong> Negative values are not allowed.', 'xpro-bb-addons' ),
							'placeholder' => '0',
							'slider'      => true,
						),
					),
				),
				'image_overlay'       => array(
					'title'     => __( 'Image Overlay', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'overlay_style'      => array(
							'type'    => 'select',
							'label'   => __( 'Overlay Effect', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'       => __( 'None', 'xpro-bb-addons' ),
								'style-1'    => __( 'FadeIn', 'xpro-bb-addons' ),
								'fadeCenter' => __( 'FadeIn Center', 'xpro-bb-addons' ),
								'style-2'    => __( 'Slide Left', 'xpro-bb-addons' ),
								'style-3'    => __( 'Slide Right', 'xpro-bb-addons' ),
							),
							'help'    => 'Select image overlay effect',
						),
						'overlay_color_type' => array(
							'type'    => 'button-group',
							'label'   => __( 'Overlay Type', 'xpro-bb-addons' ),
							'default' => 'option-1',
							'options' => array(
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'overlay_color' ),
								),
								'gradient' => array(
									'fields' => array( 'overlay_gradient' ),
								),
							),
						),
						'overlay_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Overlay Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-image-item_effect2:before,.tnit-image-item_effect1:after,.tnit-image-item_effect3:after,.tnit-image-cricle_effect:before',
										'property' => 'background-color',
									),
								),
							),
						),
						'overlay_gradient'   => array(
							'type'    => 'gradient',
							'label'   => __( 'overlay Gradient', 'xpro-bb-addons' ),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-image-item_effect2:before,.tnit-image-item_effect1:after,.tnit-image-item_effect3:after,.tnit-image-cricle_effect:before',
										'property' => 'background-image',
									),
								),
							),
						),
					),
				),
			),
		),
	)
);
