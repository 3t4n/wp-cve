<?php

/**
 * @class NJBA_Image_Hover_Module
 */
class NJBA_Image_Hover_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Image Hover', 'bb-njba' ),
			'description'     => __( 'Addon to display image hover.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'creative' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-image-hover/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-image-hover/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'partial_refresh' => false, // Defaults to false and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'font-awesome' );
		$this->add_css( 'njba-image-hover-frontend', NJBA_MODULE_URL . 'modules/njba-image-hover/css/frontend.css' );
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
	 * @param $style_type
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function njbaImageHoverModule( $style_type ) {
		$html = '';
		$html .= '<div class="njba-col-md-1';
		if ( $style_type == 3 ) :
			$html .= ' njba-image-box-column" >';
		endif;
		if ( $style_type == 4 ) :
			if ( $this->settings->hover_effect == 1 ) :
				$html .= ' hover-one" >';
			endif;
			if ( $this->settings->hover_effect == 2 ) :
				$html .= ' hover-two" >';
			endif;
			if ( $this->settings->hover_effect == 3 ) :
				$html .= ' hover-three" >';
			endif;
			if ( $this->settings->hover_effect == 4 ) :
				$html .= ' hover-four" >';
			endif;
		endif;
		if ( $style_type == 1 || $style_type == 2 || $style_type == 5 ) :
			$html .= '" >';
		endif;
		$html .= '<div class="njba-image-box njba-square-hover';
		if ( $style_type == 2 ) :
			$html .= '-three">';
		endif;
		if ( $style_type == 3 ) :
			$html .= '-two">';
		endif;
		if ( $style_type == 4 ) :
			$html .= '-four">';
		endif;
		if ( $style_type == 5 ) :
			$html .= '-five">';
		endif;
		if ( $style_type == 1 ) :
			$html .= '">';
		endif;
		$html .= '<a href="';
		if ( ! empty( $this->settings->link_url ) ) :
			$html .= $this->settings->link_url;
		endif;
		$html .= '" target="';
		if ( ! empty( $this->settings->link_target ) ) :
			$html .= $this->settings->link_target;
		endif;
		$html        .= '">';
		$html        .= '<div class="njba-image-box-img">';
		$image_hover = wp_get_attachment_image_src( $this->settings->photo );
		if ( ! is_wp_error( $image_hover ) ) {
			$photo_src    = $image_hover[0];
			$photo_width  = $image_hover[1];
			$photo_height = $image_hover[2];
		}
		if ( ! empty( $this->settings->photo ) ) :
			$html .= '<img src="' . $this->settings->photo_src . '" width="' . $photo_width . '" height="' . $photo_height . '" class="njba-image-responsive" >';
		endif;
		if ( ! empty( $this->settings->photo_url ) ) :
			$html .= '<img src="' . $this->settings->photo_url . '" width="' . $photo_width . '" height="' . $photo_height . '" class="njba-image-responsive" >';
		endif;
		$html .= '<div class="njba-box-border-line">';
		if ( $style_type == 1 || $style_type == 3 ) :
			$html .= '<div class="njba-box-line njba-box-line-top"></div>';
			$html .= '<div class="njba-box-line njba-box-line-right"></div>';
			$html .= '<div class="njba-box-line njba-box-line-bottom"></div>';
			$html .= '<div class="njba-box-line njba-box-line-left"></div>';
		endif;
		if ( $style_type == 2 || $style_type == 4 || $style_type == 5 ) :
			$html .= '<div class="njba-box-border-line-double"></div>';
		endif;
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="njba-image-box-overlay">';
		$html .= '<div class="njba-image-box-content">';
		if ( $style_type == 1 && ! empty( $this->settings->caption ) ) :
			$caption = $this->settings->caption[0];
			$html    .= '<h1 class="caption-selector"><span>' . ucfirst( $caption ) . '</span>' . substr( $this->settings->caption, 1 ) . '</h1>';
		endif;
		if ( ( $style_type == 2 || $style_type == 3 || $style_type == 4 || $style_type == 5 ) && ( ! empty( $this->settings->caption ) ) ) :
			$html .= '<h1 class="caption-selector">' . $this->settings->caption . '</h1>';
		endif;
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</a>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Image_Hover_Module', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'style'        => array(
						'type'    => 'select',
						'label'   => __( 'Photo Style', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Style 1', 'bb-njba' ),
							'2' => __( 'Style 2', 'bb-njba' ),
							'3' => __( 'Style 3', 'bb-njba' ),
							'4' => __( 'Style 4', 'bb-njba' ),
							'5' => __( 'Style 5', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array(
									'heading_font',
									'caption_padding',
									'first_font_size',
									'first_font_color',
									'font_size',
									'font_color',
									'inside_primary_border_color',
									'border_size',
									'inside_secondary_border_color',
									'hover_color',
									'content_box_margin1',
									'hover_opacity',
									'transition'
								)
							),
							'2' => array(
								'fields' => array(
									'heading_font',
									'caption_padding',
									'font_size',
									'font_color',
									'inside_primary_border',
									'inside_primary_border_color',
									'border_size',
									'inside_secondary_border',
									'inside_secondary_border_color',
									'hover_color',
									'content_box_margin1',
									'hover_opacity',
									'transition'
								)
							),
							'3' => array(
								'fields' => array(
									'heading_font',
									'caption_padding',
									'font_size',
									'font_color',
									'hover_color',
									'hover_opacity',
									'transition',
									'before_padding',
									'after_padding'
								)
							),
							'4' => array(
								'fields' => array(
									'hover_effect',
									'heading_font',
									'caption_padding',
									'font_size',
									'font_color',
									'hover_color',
									'hover_opacity',
									'transition'
								)
							),
							'5' => array(
								'fields' => array(
									'heading_font',
									'caption_padding',
									'font_size',
									'font_color',
									'hover_color',
									'hover_opacity',
									'transition',
									'rotate',
									'rotate_hover',
									'scale'
								)
							)
						),
						// 'preview' => array(
						// 	'type' => 'none'
						// )
					),
					'photo_source' => array(
						'type'    => 'select',
						'label'   => __( 'Photo Source', 'bb-njba' ),
						'default' => 'library',
						'options' => array(
							'library' => __( 'Media Library', 'bb-njba' ),
							'url'     => __( 'URL', 'bb-njba' )
						),
						'toggle'  => array(
							'library' => array(
								'fields' => array( 'photo' )
							),
							'url'     => array(
								'fields' => array( 'photo_url' )
							)
						)
					),
					'photo'        => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'bb-njba' ),
						'show_remove' => true,
					),
					'photo_url'    => array(
						'type'        => 'text',
						'label'       => __( 'Photo 
							', 'bb-njba' ),
						'placeholder' => 'http://www.example.com/my-photo.jpg',
						'preview'     => array(
							'type' => 'none'
						)
					),
					'caption'      => array(
						'type'    => 'text',
						'label'   => __( 'Caption', 'bb-njba' ),
						'default' => 'Caption',
						'preview' => array(
							'type' => 'none'
						)
					),
					'hover_effect' => array(
						'type'    => 'select',
						'label'   => __( 'Effect', 'bb-njba' ),
						'options' => array(
							'1' => __( 'Hover Bottom To Top', 'bb-njba' ),
							'2' => __( 'Hover Top To Bottom', 'bb-njba' ),
							'3' => __( 'Hover Left To Right', 'bb-njba' ),
							'4' => __( 'Hover Right To Left', 'bb-njba' )
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'rotate'       => array(
						'type'        => 'text',
						'label'       => __( 'Rotate', 'bb-njba' ),
						'default'     => - 45,
						'size'        => '3',
						'description' => 'deg'
					),
					'rotate_hover' => array(
						'type'        => 'text',
						'label'       => __( 'After Hover Rotate', 'bb-njba' ),
						'default'     => 0,
						'size'        => '3',
						'description' => 'deg'
					),
					'scale'        => array(
						'type'        => 'text',
						'label'       => __( 'Scale', 'bb-njba' ),
						'default'     => 1.1,
						'size'        => '2',
						'description' => 'deg'
					),
				)
			),
			'link'    => array(
				'title'  => __( 'Link', 'bb-njba' ),
				'fields' => array(
					'link_type'   => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'options' => array(
							''    => _x( 'None', 'Link type.', 'bb-njba' ),
							'url' => __( 'URL', 'bb-njba' ),
						),
						'toggle'  => array(
							''    => array(),
							'url' => array(
								'fields' => array( 'link_url', 'link_target' )
							),
						),
						'help'    => __( 'Link type applies to how the image should be linked on click. You can choose a specific URL.', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
					'link_url'    => array(
						'type'    => 'link',
						'label'   => __( 'URL', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
					'link_target' => array(
						'type'    => 'select',
						'label'   => __( 'Target', 'bb-njba' ),
						'default' => '_self',
						'options' => array(
							'_self'  => __( 'Same Window', 'bb-njba' ),
							'_blank' => __( 'New Window', 'bb-njba' )
						),
						'preview' => array(
							'type' => 'none'
						)
					)
				)
			)
		)
	),
	'style'   => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'border_size'                   => array(
						'type'        => 'text',
						'label'       => __( 'Border Size', 'bb-njba' ),
						'description' => 'px',
						'default'     => '1',
						'size'        => '5'
					),
					'inside_primary_border_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Inside Primary Border Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'inside_secondary_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Inside Secondary Border Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
					'hover_color'                   => array(
						'type'       => 'color',
						'label'      => __( 'Hover Color', 'bb-njba' ),
						'default'    => '94bac3',
						'show_reset' => true,
					),
					'hover_opacity'                 => array(
						'type'        => 'text',
						'label'       => __( 'Image Hover Opacity', 'bb-njba' ),
						'default'     => '50',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
						'placeholder' => '50'
					),
					'content_box_margin1'           => array(
						'type'        => 'text',
						'label'       => __( 'Margin', 'bb-njba' ),
						'help'        => __( 'if you want to set content margin then fill up this field.', 'bb-njba' ),
						'description' => 'px',
						'default'     => '20',
						'size'        => '5'
					),
					'caption_padding'               => array(
						'type'        => 'text',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'help'        => __( 'if you want to add caption padding then fill up this field.', 'bb-njba' ),
						'default'     => '20',
						'size'        => '5'
					),
					'before_padding'                => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Before Style Padding', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '18',
							'medium'  => '16',
							'small'   => '12'
						)
					),
					'after_padding'                 => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'After Style Padding', 'bb-njba' ),
						'description' => 'Pleas enter value in pixels.',
						'default'     => array(
							'desktop' => '18',
							'medium'  => '16',
							'small'   => '12'
						)
					),
					'inside_primary_border'         => array(
						'type'    => 'select',
						'label'   => __( 'Primary Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
					),
					'inside_secondary_border'       => array(
						'type'    => 'select',
						'label'   => __( 'Secondary Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
					),
					'transition'                    => array(
						'type'        => 'text',
						'label'       => __( 'Transition', 'bb-njba' ),
						'size'        => '5',
						'description' => 'sec',
						'default'     => '0.5',
						'placeholder' => '0.5',
						'help'        => __( 'set image transition', 'bb-njba' ),
					)
				)
			)
		)
	),
	'typography'   => array( // Tab
		'title'    => __( 'Typography', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'heading_font'                  => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
					),
					'first_font_size'               => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'First Character Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '22',
							'medium'  => '20',
							'small'   => '16'
						)
					),
					'first_font_color'              => array(
						'type'       => 'color',
						'label'      => __( 'First Character Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'font_size'                     => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '18',
							'medium'  => '16',
							'small'   => '12'
						)
					),
					'font_line_height'              => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '22',
							'medium'  => '16',
							'small'   => '12'
						)
					),
					'font_color'                    => array(
						'type'       => 'color',
						'label'      => __( 'Font Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
				)
			)
		)
	)
) );
