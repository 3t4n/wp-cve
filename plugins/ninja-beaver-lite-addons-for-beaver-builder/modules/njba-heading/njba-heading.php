<?php

/**
 * @class NJBA_Heading_Module
 */
class NJBA_Heading_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Heading', 'bb-njba' ),
			'description'     => __( 'Addon to display Heading.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-heading/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-heading/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'icon'            => 'text.svg',
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'font-awesome' );
		$this->add_css( 'njba-heading-frontend', NJBA_MODULE_URL . 'modules/njba-heading/css/frontend.css' );
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

	public function njba_icon_module( $sep_type, $html ) {
		$html .= '';
		if ( $sep_type === 'separator_icon' ) :
			$html .= '<div class="njba-divider-content njba-divider">';
			$html .= '<i class="' . $this->settings->separator_icon_text . '" aria-hidden="true"></i>';
			$html .= '</div>';
		endif;
		if ( $sep_type === 'separator_image' ) :
			$src  = $this->njba_get_image_src();
			$html .= '<div class="njba-divider-content njba-divider">';
			$html .= '<img src="' . $src . '">';
			$html .= '</div>';
		endif;
		if ( $sep_type === 'separator_text' ) :
			$src  = $this->njba_get_image_src();
			$html .= '<div class="njba-divider-content njba-divider">';
			$html .= $this->settings->separator_text_select;
			$html .= '</div>';
		endif;

		return $html;
	}

	public function njba_get_image_src() {
		$src = $this->njba_get_image_url();

		return $src;
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

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Heading_Module', array(
	'general' => array( //Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'heading'         => array( // Section
				'title'  => __( 'Heading', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'main_title'             => array(
						'type'    => 'text',
						'label'   => __( 'Title', 'bb-njba' ),
						'default' => 'NJBA HEADING',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-heading-title'
						)
					),
					'main_title_tag'         => array(
						'type'    => 'select',
						'label'   => __( 'Tag', 'bb-njba' ),
						'default' => 'h1',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							''   => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' )
						),
					),
					'main_title_link'        => array(
						'type'        => 'link',
						'label'       => __( 'Link', 'bb-njba' ),
						'default'     => __( '', 'bb-njba' ),
						'placeholder' => 'www.example.com',
					),
					'main_title_link_target' => array(
						'type'        => 'select',
						'label'       => __( 'Target', 'bb-njba' ),
						'default'     => __( '_self', 'bb-njba' ),
						'placeholder' => 'www.example.com',
						'options'     => array(
							'_self'  => __( 'Same Window', 'bb-njba' ),
							'_blank' => __( 'New Window', 'bb-njba' ),
						),

					)
				)
			),
			'sub_title_sec'   => array( // Section
				'title'  => __( 'Description', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'sub_title' => array(
						'type'          => 'editor',
						'label'         => __( 'Subtitle', 'bb-njba' ),
						'media_buttons' => false,
						'rows'          => 6,
						'default'       => __( 'Enter description text here.', 'bb-njba' ),
						'preview'       => array(
							'type'     => 'text',
							'selector' => '.njba-heading-sub-title'
						)
					)
				)
			),
		)
	),
	'styles'  => array( //Tab
		'title'    => __( 'Styles', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'heading_style'   => array( // Section
				'title'  => __( 'Heading Style', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'heading_title_alignment'   => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'text-align'
						)
					),
					'heading_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-heading-title',
							'property' => 'font-family'
						)
					),
					'heading_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '28',
							'medium'  => '24',
							'small'   => '20',
						),
						'description' => 'Please enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '30',
							'medium'  => '26',
							'small'   => '22',
						),
						'description' => 'Please enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'heading_title_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'color'
						)
					),
					'heading_margin'            => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							)
						)
					),
				)
			),
			'sub_title_style' => array( // Section
				'title'  => __( 'Subtitle style', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'heading_sub_title_alignment'   => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'text-align'
						)
					),
					'heading_sub_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-heading-sub-title',
							'property' => 'font-family'
						)
					),
					'heading_sub_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '20',
							'medium'  => '20',
							'small'   => '20',
						),
						'description' => 'Please enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_sub_title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '20',
							'medium'  => '20',
							'small'   => '20',
						),
						'description' => 'Please enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'heading_sub_title_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'color',
						)
					),
					'heading_subtitle_margin'       => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					),
				)
			),
			'separator_sec'   => array( // Section
				'title'  => __( 'Separator', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'separator_select' => array(
						'type'    => 'select',
						'label'   => __( 'Display Separator', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'No', 'bb-njba' ),
							'yes' => __( 'Yes', 'bb-njba' )
						),
						'toggle'  => array(
							'yes' => array(
								'sections' => array( 'separator_style' ),
								'fields'   => array( '' )
							)
						)
					)
				)
			),
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
							'separator_image'  => __( 'Separator With Icon Image', 'bb-njba' ),
							'separator_text'   => __( 'Separator With Text', 'bb-njba' ),
							'image_separator'  => __( 'Image Separator', 'bb-njba' ),
						),
						'toggle'  => array(
							'separator_normal' => array(
								'fields' => array( 'separator_normal_width', 'separator_border_width','separator_border_style','separator_border_color' )
							),
							'separator_icon'   => array(
								'fields' => array( 'separator_icon_text', 'separator_icon_font_size', 'separator_icon_font_color','separator_border_width','separator_border_style','separator_border_color' )
							),
							'separator_image'  => array(
								'fields' => array( 'separator_image_select','separator_border_width','separator_border_style','separator_border_color' )
							),
							'separator_text'   => array(
								'fields' => array( 'separator_text_select', 'separator_text_font_size', 'separator_text_font_color', 'separator_text_line_height','separator_border_width','separator_border_style','separator_border_color' )
							),
							'image_separator'   => array(
								'fields' => array( 'select_image_separator' )
							),
						)
					),
					'icon_position'              => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Position', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						)
					),
					'separator_normal_width'     => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '3',
						'default'     => '50',
						'label'       => __( 'Separator Width', 'bb-njba' ),
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
							'property' => 'color'
						)
					),
					'separator_image_select'     => array(
						'type'        => 'photo',
						'label'       => __( 'Image', 'bb-njba' ),
						'help'        => 'Please upload Square icon images.',
						'show_remove' => true
					),
					'separator_text_select'      => array(
						'type'    => 'text',
						'label'   => __( 'Text', 'bb-njba' ),
						'default' => 'Example',
						'help'    => __( 'Use a unique small word to highlight this Heading.', 'bb-njba' ),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-divider'
						)
					),
					'separator_text_font_size'   => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => '16',
						'label'       => __( 'Font Size', 'bb-njba' ),
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
						'default'     => '8',
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
						'label'      => __( 'Font Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content .separator-text',
							'property' => 'color'
						)
					),
					'select_image_separator'     => array(
						'type'        => 'photo',
						'label'       => __( 'Image', 'bb-njba' ),
						'show_remove' => true
					),
					'separator_margintb'         => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 20,
							'bottom' => 20
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							)
						)
					),
					'separator_border_width'     => array(
						'type'        => 'text',
						'default'     => '1',
						'maxlength'   => '2',
						'size'        => '5',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-separator-line > span',
							'property' => 'border-top-width',
							'unit'     => 'px'
						)
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
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-separator-line > span',
							'property' => 'border-top-style'
						)
					),
					'separator_border_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-separator-line > span',
							'property' => 'border-color',
						)
					)
				)
			)
		)
	)
) );
