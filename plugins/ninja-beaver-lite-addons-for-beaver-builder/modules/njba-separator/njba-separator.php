<?php

class NJBA_Separator_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Separator', 'bb-njba' ),
			'description'     => __( 'Addon to display Separator.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'separator' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-separator/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-separator/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'icon'            => 'minus.svg',
			'partial_refresh' => true, // Set this to true to enable partial refresh.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'font-awesome' );
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

	public function njba_icon_module( $sep_type ) {
		$html = '';
		if ( $sep_type === 'separator_icon' ) :
			$html .= '<div class="njba-divider-content njba-divider">';
			$html .= '<i class="' . $this->settings->separator_icon_text . '" aria-hidden="true"></i>';
			$html .= '</div>';
		endif;
		if ( $sep_type === 'separator_image' ) :
			$src             = $this->njba_get_image_src();
			$html            .= '<div class="njba-divider-content njba-divider">';
			$separator_image = wp_get_attachment_image_src( $this->settings->separator_image_select );
			if ( ! is_wp_error( $separator_image ) ) {
				$separator_image_select_src    = $separator_image[0];
				$separator_image_select_width  = $separator_image[1];
				$separator_image_select_height = $separator_image[2];
			}
			$html .= '<img src="' . $src . '" >';
			$html .= '</div>';
		endif;
		if ( $sep_type === 'separator_text' ) :
			$src  = $this->njba_get_image_src();
			$html .= '<div class="njba-divider-content njba-divider">';
			$html .= '<div class="separator-text">' . $this->settings->separator_text_select . '</div>';
			$html .= '</div>';
		endif;
		return $html;
	}

	public function njba_get_image_src() {
		return $this->njba_get_image_url();
	}

	/**
	 * @method njba_get_image_url
	 * @protected
	 */
	protected function njba_get_image_url() {
		if ( ! empty( $this->settings->separator_image_select_src ) ) {
			$url = $this->settings->separator_image_select_src;
		} else {
			$url = FL_BUILDER_URL . 'img/pixel.png';
		}

		return $url;
	}
}

FLBuilder::register_module( 'NJBA_Separator_Module', array(
	'general' => array(
		'title'    => __( 'General', 'bb-njba' ),
		'sections' => array(
			'separator_style' => array( // Section
				'title'  => __( 'Separator Style', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'separator_type'             => array(
						'type'    => 'select',
						'default' => 'separator_normal',
						'label'   => __( 'Type', 'bb-njba' ),
						'options' => array(
							'separator_normal' => __( 'Normal', 'bb-njba' ),
							'separator_icon'   => __( 'Separator With Icon', 'bb-njba' ),
							'separator_image'  => __( 'Separator With Image', 'bb-njba' ),
							'separator_text'   => __( 'Separator With Text', 'bb-njba' ),
							'image_separator'  => __( 'Image Separator', 'bb-njba' ),
						),
						'toggle'  => array(
							'separator_normal' => array(
								'fields' => array( 'separator_normal_width' )
							),
							'separator_icon'   => array(
								'fields' => array( 'separator_icon_text', 'separator_icon_font_size', 'separator_icon_font_color' )
							),
							'separator_image'  => array(
								'fields' => array( 'separator_image_select' )
							),
							'separator_text'   => array(
								'fields' => array( 'separator_text_select', 'separator_text_font_size', 'separator_text_font_color', 'separator_text_line_height' )
							),
							'image_separator'   => array(
								'fields' => array( 'select_image_separator' )
							),
						),
					),
					'icon_position'              => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Position', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						),
					),
					'separator_normal_width'     => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '3',
						'default'     => '50',
						'label'       => __( 'Separator Width', 'bb-njba' ),
						'placeholder' => '50',
						'description' => _x( '%', 'Value unit for Separator Width. Such as: "50%"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-icon',
							'property' => 'width',
							'unit'     => '%'
						)
					),
					'separator_icon_text'        => array(
						'type'  => 'icon',
						'label' => __( 'Icon', 'bb-njba' )
					),
					'separator_icon_font_size'   => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => '18',
						'label'       => __( 'Icon Size', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content i',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'separator_icon_font_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content i',
							'property' => 'color',
						)
					),
					'separator_image_select'     => array(
						'type'        => 'photo',
						'label'       => __( 'Image', 'bb-njba' ),
						'show_remove' => true
					),
					'separator_text_select'      => array(
						'type'    => 'text',
						'label'   => __( 'Text', 'bb-njba' ),
						'default' => 'Example',
						'help'    => __( 'Use a unique small word to highlight this Heading.', 'bb-njba' ),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-divider-content .separator-text'
						)
					),
					'separator_text_font_size'   => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => '16',
						'label'       => __( 'Text Size', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content .separator-text',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'separator_text_line_height' => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => '18',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content .separator-text',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'separator_text_font_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content .separator-text',
							'property' => 'color',
						)
					),
					'select_image_separator'     => array(
						'type'        => 'photo',
						'label'       => __( 'Image', 'bb-njba' ),
						'show_remove' => true
					),
					'separator_border_width'     => array(
						'type'        => 'text',
						'default'     => '1',
						'maxlength'   => '2',
						'size'        => '5',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
					),
					'separator_border_style'     => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
					),
					'separator_border_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					)
				)
			)
		)
	)
) );
