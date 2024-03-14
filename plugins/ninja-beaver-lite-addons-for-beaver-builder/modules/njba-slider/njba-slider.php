<?php

/**
 * @class NJBA_Slider_Module
 */
class NJBA_Slider_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Slider', 'bb-njba' ),
			'description'     => __( 'Addon to display Slider.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'carousel' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-slider/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-slider/',
			'icon'            => 'slides.svg',
			'partial_refresh' => true, // Set this to true to enable partial refresh.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'jquery-bxslider' );
		$this->add_css( 'font-awesome' );
		$this->add_js( 'jquery-bxslider' );
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

	public function delete() {
	}


}

//NJBASliderModule::image_name();
/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Slider_Module', array(
	'slides'   => array( // Tab
		'title'    => __( 'Slides', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'photos' => array(
						'type'         => 'form',
						'label'        => __( 'Slide', 'bb-njba' ),
						'form'         => 'njba_sliderspanel_form', // ID from registered form below
						'preview_text' => 'image', // Name of a field to use for the preview text
						'multiple'     => true
					),
				)
			)
		)
	),
	'carousel' => array( // Tab
		'title'    => __( 'Slider', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'heading'   => array( // Section
				'title'  => '', // Section Title
				'fields' => array(
					'autoplay'        => array(
						'type'    => 'select',
						'label'   => __( 'Autoplay', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
					),
					'hover_pause'     => array(
						'type'    => 'select',
						'label'   => __( 'Pause on hover', 'bb-njba' ),
						'default' => '1',
						'help'    => __( 'Pause when mouse hovers over slider' ),
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' ),
						),
					),
					'transition'      => array(
						'type'    => 'select',
						'label'   => __( 'Transition Mode', 'bb-njba' ),
						'default' => 'horizontal',
						'options' => array(
							'horizontal' => _x( 'Horizontal', 'Transition type.', 'bb-njba' ),
							'vertical'   => _x( 'Vertical', 'Transition type.', 'bb-njba' ),
							'fade'       => __( 'Fade', 'bb-njba' )
						),
					),
					'pause'           => array(
						'type'        => 'text',
						'label'       => __( 'Slide Delay', 'bb-njba' ),
						'default'     => '4',
						'maxlength'   => '4',
						'size'        => '5',
						'description' => _x( 'sec', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'bb-njba' )
					),
					'speed'           => array(
						'type'        => 'text',
						'label'       => __( 'Slide Transition Speed', 'bb-njba' ),
						'default'     => '0.5',
						'maxlength'   => '4',
						'size'        => '5',
						'description' => _x( 'sec', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'bb-njba' )
					),
					'loop'            => array(
						'type'    => 'select',
						'label'   => __( 'Loop', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' ),
						),
					),
					'adaptive_height' => array(
						'type'    => 'select',
						'label'   => __( 'Fixed Height', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' )
						),
						'help'    => __( 'Fix height to the tallest item.', 'bb-njba' )
					),
					'image_height'    => array(
						'type'        => 'text',
						'label'       => __( 'Image Height', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '5',
						'help'        => 'Add Image size same as image height option',
						'description' => 'px',
						'default'     => '',
						'preview'     => array(
							'type'     => 'css',
							'selector' => 'img.njba-slider-image-responsive',
							'property' => 'height',
							'unit'     => 'px'
						)
					)
				)
			),
			'arrow_nav' => array( // Section
				'title'  => 'Arrows',
				'fields' => array( // Section Fields
					'arrows'                   => array(
						'type'    => 'select',
						'label'   => __( 'Display', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'arrow_background', 'arrow_color', 'arrow_background_opacity' )
							)
						)
					),
					'arrow_color'              => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-slider-main .bx-wrapper .bx-controls-direction a i',
							'property' => 'color'
						)
					),
					'arrow_background'         => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-slider-main .bx-wrapper .bx-controls-direction a.bx-next,.njba-slider-main .bx-wrapper .bx-controls-direction a.bx-prev',
							'property' => 'background'
						)
					),
					'arrow_background_opacity' => array(
						'type'        => 'text',
						'label'       => __( 'Background Color Opacity', 'bb-njba' ),
						'maxlength'   => '3',
						'size'        => '5',
						'default'     => '100',
						'description' => '%',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-slider-main .bx-wrapper .bx-controls-direction a.bx-next,.njba-slider-main .bx-wrapper .bx-controls-direction a.bx-prev',
							'property' => 'opacity',
							'unit'     => '%'
						)
					),
				)
			),
			'dot_nav'   => array( // Section
				'title'  => 'Pager', // Section Title
				'fields' => array( // Section Fields
					'dot'              => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'None', 'bb-njba' ),
							'1' => __( 'Dots', 'bb-njba' ),
							'2' => __( 'Thumbnail', 'bb-njba' ),
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'dot_color', 'active_dot_color' )
							),
							'2' => array(
								'fields'   => array( 'toggle_color', 'toggle_bg_color', 'toggle_bg_opc' ),
								'sections' => array( 'thumbnail_show' )
							)
						)
					),
					'toggle_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Toggle Icon Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.bx-thumbnail-pager i',
							'property' => 'color'
						)
					),
					'toggle_bg_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Toggle Background Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.bx-thumbnail-pager i',
							'property' => 'background'
						)
					),
					'toggle_bg_opc'    => array(
						'type'        => 'text',
						'label'       => __( 'Opacity', 'bb-njba' ),
						'default'     => '100',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => '%',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.bx-thumbnail-pager i',
							'property' => 'opacity',
						)
					),
					'dot_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-slider-main a.bx-pager-link',
							'property' => 'background'
						)
					),
					'active_dot_color' => array(
						'type'       => 'color',
						'label'      => __( 'Active Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-slider-main a.bx-pager-link.active',
							'property' => 'background'
						)
					),
				)
			)
		)
	),
	'styles'   => array(
		'title'    => __( 'Styles', 'bb-njba' ),
		'sections' => array(
			'heading_fonts'  => array(
				'title'  => __( 'CTA Settings', 'bb-njba' ),
				'fields' => array( // Section Fields
					'desktop_device' => array(
						'type'    => 'select',
						'label'   => __( 'Desktop Device', 'bb-njba' ),
						'default' => 'block',
						'options' => array(
							'none'  => __( 'No', 'bb-njba' ),
							'block' => __( 'Yes', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-cta-box-main-inline , .njba-cta-box-main-stacked',
							'property' => 'display'
						)
					),
					'medium_device'  => array(
						'type'    => 'select',
						'label'   => __( 'Medium  Device', 'bb-njba' ),
						'default' => 'block',
						'options' => array(
							'none'  => __( 'No', 'bb-njba' ),
							'block' => __( 'Yes', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-cta-box-main-inline , .njba-cta-box-main-stacked',
							'property' => 'display'
						)
					),
					'small_device'   => array(
						'type'    => 'select',
						'label'   => __( 'Small Device', 'bb-njba' ),
						'default' => 'block',
						'options' => array(
							'none'  => __( 'No', 'bb-njba' ),
							'block' => __( 'Yes', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-cta-box-main-inline , .njba-cta-box-main-stacked',
							'property' => 'display'
						)
					),

				)
			),
			'thumbnail_show' => array(
				'title'  => __( 'Thumbnail Pager Show', 'bb-njba' ),
				'fields' => array( // Section Fields

					'thumbnail_medium_device' => array(
						'type'    => 'select',
						'label'   => __( 'Medium  Device', 'bb-njba' ),
						'default' => 'block',
						'options' => array(
							'none'  => __( 'No', 'bb-njba' ),
							'block' => __( 'Yes', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-cta-box-main-inline , .njba-cta-box-main-stacked',
							'property' => 'display'
						)
					),
					'thumbnail_small_device'  => array(
						'type'    => 'select',
						'label'   => __( 'Small Device', 'bb-njba' ),
						'default' => 'block',
						'options' => array(
							'none'  => __( 'No', 'bb-njba' ),
							'block' => __( 'Yes', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-cta-box-main-inline , .njba-cta-box-main-stacked',
							'property' => 'display'
						)
					),
				)
			),
		)
	)
) );
/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'njba_sliderspanel_form', array(
	'title' => __( 'Add Slide', 'bb-njba' ),
	'tabs'  => array(
		'general'      => array( // Tab
			'title'    => __( 'General', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'photo_details' => array(
					'title'  => 'Photo Details',
					'fields' => array(
						'photo'         => array(
							'type'        => 'photo',
							'label'       => __( 'Select Photo', 'bb-njba' ),
							'show_remove' => true
						),
						'select_option' => array(
							'type'    => 'select',
							'label'   => __( 'Display Call To Action', 'bb-njba' ),
							'default' => '0',
							'options' => array(
								'1' => __( 'Yes', 'bb-njba' ),
								'0' => __( 'No', 'bb-njba' ),
							),
							'toggle'  => array(
								'1' => array(
									'sections' => array( 'structure', 'cta_heading', 'cta_sub_text', 'separator_sec' ),
									'tabs'     => array( 'cta_settings', 'marker', 'button_tab', 'style', 'typography' ),
								),
								'0' => array(
									'sections' => array(),
								)
							)
						),
					),
				),
			),// Tab Sections
		),// Tab
		'cta_settings' => array(
			'title'    => __( 'CTA Settings', 'bb-njba' ),
			'sections' => array(
				'structure'       => array(
					'title'  => __( 'Structure', 'bb-njba' ),
					'fields' => array(
						'cta_layout' => array(
							'type'    => 'select',
							'label'   => __( 'Layout', 'bb-njba' ),
							'default' => 'inline',
							'options' => array(
								'inline'  => __( 'Inline', 'bb-njba' ),
								'stacked' => __( 'Stacked', 'bb-njba' )
							),
							'toggle'  => array(
								'inline'  => array(
									'fields' => array( 'cta_column' )
								),
								'stacked' => array(
									'fields'   => array( '' ),
									'sections' => array( 'btn_structure' ),
									'tabs'     => array( 'marker' ),
								)
							)
						),
						'cta_column' => array(
							'type'    => 'select',
							'label'   => __( 'Column', 'bb-njba' ),
							'default' => __( '70_30', 'bb-njba' ),
							'options' => array(
								'50_50' => __( '50/50', 'bb-njba' ),
								'60_40' => __( '60/40', 'bb-njba' ),
								'70_30' => __( '70/30', 'bb-njba' ),
								'80_20' => __( '80/20', 'bb-njba' )
							)
						)
					)
				),
				'cta_heading'     => array(
					'title'  => __( 'Heading', 'bb-njba' ),
					'fields' => array(
						'main_title'     => array(
							'type'    => 'text',
							'label'   => __( 'Heading', 'bb-njba' ),
							'default' => 'NJBA HEADING',
							'preview' => array(
								'type'     => 'text',
								'selector' => '.njba-heading-title'
							)
						),
						'main_title_tag' => array(
							'type'    => 'select',
							'label'   => __( 'Tag', 'bb-njba' ),
							'default' => 'h1',
							'options' => array(
								'h1' => __( 'H1', 'bb-njba' ),
								'h2' => __( 'H2', 'bb-njba' ),
								'h3' => __( 'H3', 'bb-njba' ),
								'h4' => __( 'H4', 'bb-njba' ),
								'h5' => __( 'H5', 'bb-njba' ),
								'h6' => __( 'H6', 'bb-njba' )
							)
						)
					)
				),
				'cta_sub_text'    => array(
					'title'  => __( 'Description', 'bb-njba' ),
					'fields' => array(
						'sub_title' => array(
							'type'          => 'editor',
							'label'         => __( 'Sub title', 'bb-njba' ),
							'media_buttons' => false,
							'rows'          => 6,
							'default'       => __( 'Enter description text here.', 'bb-njba' )
						)
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
								'yes' => __( 'Yes', 'bb-njba' ),
								'no'  => __( 'No', 'bb-njba' )
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
					'title'  => __( 'separator Style', 'bb-njba' ), // Section Title,
					'fields' => array( // Section Fields
						'separator_type'            => array(
							'type'    => 'select',
							'default' => 'separator_normal',
							'label'   => __( 'Type', 'bb-njba' ),
							'options' => array(
								'separator_normal' => __( 'Normal', 'bb-njba' ),
								'separator_icon'   => __( 'Separator With Icon', 'bb-njba' ),
								'separator_image'  => __( 'Separator With Image', 'bb-njba' ),
								'separator_text'   => __( 'Separator With Text', 'bb-njba' ),
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
									'fields' => array( 'separator_text_select', 'separator_text_font_size', 'separator_text_font_color' )
								)
							)
						),
						'icon_position'             => array(
							'type'    => 'select',
							'default' => 'center',
							'label'   => __( 'Position', 'bb-njba' ),
							'options' => array(
								'left'   => __( 'Left', 'bb-njba' ),
								'center' => __( 'Center', 'bb-njba' ),
								'right'  => __( 'Right', 'bb-njba' )
							)
						),
						'separator_normal_width'    => array(
							'type'        => 'text',
							'size'        => '5',
							'maxlength'   => '3',
							'default'     => '50',
							'label'       => __( 'separator Width', 'bb-njba' ),
							'description' => _x( '%', 'Value unit for separator Width. Such as: "50%"', 'bb-njba' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.njba-heading-icon',
								'property' => 'width',
								'unit'     => '%'
							)
						),
						'separator_icon_text'       => array(
							'type'  => 'icon',
							'label' => __( 'Icon', 'bb-njba' )
						),
						'separator_icon_font_size'  => array(
							'type'        => 'text',
							'size'        => '5',
							'maxlength'   => '2',
							'default'     => '18',
							'label'       => __( 'Icon Size', 'bb-njba' ),
							'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.njba-divider-content',
								'property' => 'font-size',
								'unit'     => 'px'
							)
						),
						'separator_icon_font_color' => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'bb-njba' ),
							'default'    => '000000',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.njba-divider-content',
								'property' => 'color'
							)
						),
						'separator_image_select'    => array(
							'type'        => 'photo',
							'label'       => __( 'Image', 'bb-njba' ),
							'show_remove' => true
						),
						'separator_text_select'     => array(
							'type'    => 'text',
							'label'   => __( 'Text', 'bb-njba' ),
							'default' => 'Example',
							'help'    => __( 'Use a unique small word to highlight this Heading.', 'bb-njba' )
						),
						'separator_text_font_size'  => array(
							'type'        => 'text',
							'size'        => '5',
							'maxlength'   => '2',
							'default'     => '16',
							'label'       => __( 'Font Size', 'bb-njba' ),
							'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.njba-divider-content',
								'property' => 'font-size',
								'unit'     => 'px'
							)
						),
						'separator_text_font_color' => array(
							'type'       => 'color',
							'label'      => __( 'Font Color', 'bb-njba' ),
							'default'    => '000000',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.njba-divider-content',
								'property' => 'color'
							)
						),
						'separator_margintb'        => array(
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
										'selector' => '.njba-heading-icon',
										'property' => 'margin-top',
									),
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down',
									'preview'     => array(
										'selector' => '.njba-heading-icon',
										'property' => 'margin-bottom',
									),
								)
							)
						),
						'separator_border_width'    => array(
							'type'        => 'text',
							'default'     => '1',
							'maxlength'   => '2',
							'size'        => '5',
							'label'       => __( 'Border Width', 'bb-njba' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.njba-heading-separator-line',
								'property' => 'border-top',
								'unit'     => 'px'
							)
						),
						'separator_border_style'    => array(
							'type'    => 'select',
							'label'   => __( 'Border Style', 'bb-njba' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'bb-njba' ),
								'solid'  => __( 'Solid', 'bb-njba' ),
								'dotted' => __( 'Dotted', 'bb-njba' ),
								'dashed' => __( 'Dashed', 'bb-njba' ),
								'double' => __( 'Double', 'bb-njba' ),
							),
						),
						'separator_border_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'bb-njba' ),
							'default'    => '000000',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.njba-heading-separator-line',
								'property' => 'border-color',
							)
						)
					)
				)
			)
		),
		'marker'       => array(
			'title'    => __( 'Co-Ordinates', 'bb-njba' ),
			'sections' => array(
				'marker' => array(
					'title'  => '', // Section Title
					'fields' => array( // Section Fields
						'marker' => array(
							'type'  => 'njba-draggable',
							'label' => '',
						)
					)
				),
			)
		),
		'button_tab'   => array( // Tab
			'title'    => __( 'Button', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'button_section'      => array(
					'title'  => '',
					'fields' => array(
						'button_text' => array(
							'type'    => 'text',
							'label'   => 'Text',
							'default' => __( 'GET STARTED', 'bb-njba' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => 'a.njba-btn'
							)
						),
					)
				),
				'button_link_section' => array(
					'title'  => __( 'Link', 'bb-njba' ), // Tab title',
					'fields' => array(
						'link'        => array(
							'type'        => 'link',
							'label'       => __( 'Link', 'bb-njba' ),
							'default'     => __( '#', 'bb-njba' ),
							'placeholder' => 'www.example.com',
							'preview'     => array(
								'type' => 'none'
							)
						),
						'link_target' => array(
							'type'        => 'select',
							'label'       => __( 'Target', 'bb-njba' ),
							'default'     => __( '_self', 'bb-njba' ),
							'placeholder' => 'www.example.com',
							'options'     => array(
								'_self'  => __( 'Same Window', 'bb-njba' ),
								'_blank' => __( 'New Window', 'bb-njba' ),
							),
							'preview'     => array(
								'type' => 'none'
							)
						)
					)
				),
				'button_icon_section' => array(
					'title'  => __( 'Icon', 'bb-njba' ), // Tab title',
					'fields' => array(
						'buttton_icon_select'  => array(
							'type'    => 'select',
							'label'   => __( 'Display', 'bb-njba' ),
							'default' => 'none',
							'options' => array(
								'none'      => __( 'No', 'bb-njba' ),
								'font_icon' => __( 'Yes', 'bb-njba' ),
							),
							'toggle'  => array(
								'font_icon' => array(
									'fields'   => array( 'button_font_icon', 'button_icon_aligment' ),
									'sections' => array( 'icon_section', 'icon_typography' ),
								)
							)
						),
						'button_font_icon'     => array(
							'type'  => 'icon',
							'label' => __( 'Icon', 'bb-njba' )
						),
						'button_icon_aligment' => array(
							'type'    => 'select',
							'label'   => __( 'Position', 'bb-njba' ),
							'default' => 'left',
							'options' => array(
								'left'  => __( 'Before Text', 'bb-njba' ),
								'right' => __( 'After Text', 'bb-njba' )
							),
						)
					)
				)
			)
		),
		'style'        => array(
			'title'    => __( 'Style', 'bb-njba' ),
			'sections' => array(
				'cta_box_section'               => array(
					'title'  => __( 'Box', 'bb-njba' ),
					'fields' => array(
						'cta_box_bg_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.njba-cta-box-content',
								'property' => 'background-color',
							)
						),
						'cta_box_bg_opc'   => array(
							'type'        => 'text',
							'label'       => __( 'Opacity', 'bb-njba' ),
							'default'     => '30',
							'maxlength'   => '3',
							'size'        => '5',
							'description' => '%',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.njba-cta-box-content',
								'property' => 'opacity',
							)
						),
					)
				),
				'cta_heading_style_section'     => array(
					'title'  => __( 'Title', 'bb-njba' ),
					'fields' => array(
						'heading_title_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-njba' ),
							'default'    => 'ffffff',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.njba-heading-title',
								'property' => 'color',
							)
						),
						'heading_title_alignment' => array(
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
						'heading_margin'          => array(
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
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right',
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down',
								),
								'left'   => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left',
								)
							)
						),
					)
				),
				'cta_sub_heading_style_section' => array(
					'title'  => __( 'Description', 'bb-njba' ),
					'fields' => array(
						'heading_sub_title_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-njba' ),
							'default'    => 'ffffff',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.njba-heading-sub-title',
								'property' => 'color',
							)
						),
						'heading_sub_title_alignment' => array(
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
						'heading_subtitle_margin'     => array(
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
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)

							)
						)
					)
				),
				'button_style_section'          => array(
					'title'  => __( 'Button', 'bb-njba' ),
					'fields' => array(
						'button_style'                  => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'bb-njba' ),
							'default' => 'flat',
							'class'   => 'creative_button_styles',
							'options' => array(
								'flat'        => __( 'Flat', 'bb-njba' ),
								'gradient'    => __( 'Gradient', 'bb-njba' ),
								'transparent' => __( 'Transparent', 'bb-njba' ),
								'threed'      => __( '3D', 'bb-njba' ),
							),
							'toggle'  => array(
								'flat'        => array(
									'fields'   => array( 'button_background_color', 'hover_button_style', 'button_box_shadow', 'button_box_shadow_color' ),
									'sections' => array( 'transition_section' )
								),
								'gradient'    => array(
									'fields' => array( 'button_background_color' )
								),
								'threed'      => array(
									'fields'   => array( 'button_background_color', 'hover_button_style' ),
									'sections' => array( 'transition_section' )
								),
								'transparent' => array(
									'fields'   => array( 'hover_button_style', 'button_box_shadow', 'button_box_shadow_color' ),
									'sections' => array( 'transition_section' )
								)
							)
						),
						'button_background_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => 'dfdfdf'
						),
						'button_background_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'button_text_color'             => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '404040'
						),
						'button_text_hover_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Text Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => 'ffffff'
						),
						'button_border_style'           => array(
							'type'    => 'select',
							'label'   => __( 'Border Style', 'bb-njba' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'bb-njba' ),
								'solid'  => __( 'Solid', 'bb-njba' ),
								'dotted' => __( 'Dotted', 'bb-njba' ),
								'dashed' => __( 'Dashed', 'bb-njba' ),
								'double' => __( 'Double', 'bb-njba' ),
							),
							'toggle'  => array(
								'solid'  => array(
									'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
								),
								'dotted' => array(
									'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
								),
								'dashed' => array(
									'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
								),
								'double' => array(
									'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
								),
							)
						),
						'button_border_width'           => array(
							'type'        => 'text',
							'label'       => __( 'Border Width', 'bb-njba' ),
							'default'     => '1',
							'size'        => '5',
							'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
						),
						'button_border_radius'          => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Border Radius', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'top-left'     => 0,
								'top-right'    => 0,
								'bottom-left'  => 0,
								'bottom-right' => 0
							),
							'options'     => array(
								'top-left'     => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'top-right'    => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom-left'  => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'bottom-right' => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)

							)
						),
						'button_border_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'button_border_hover_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'button_box_shadow'             => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Box Shadow', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'left_right' => 0,
								'top_bottom' => 0,
								'blur'       => 0,
								'spread'     => 0
							),
							'options'     => array(
								'left_right' => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa fa-arrows-h'
								),
								'top_bottom' => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa fa-arrows-v'
								),
								'blur'       => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa fa-circle-thin'
								),
								'spread'     => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa fa-circle'
								)

							)
						),
						'button_box_shadow_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Box Shadow Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => 'ffffff'
						),
						'button_padding'                => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Padding', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'top'    => 10,
								'right'  => 15,
								'bottom' => 10,
								'left'   => 15
							),
							'options'     => array(
								'top'    => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)

							)
						),
						'button_margin'                 => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Margin', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'top'    => 30,
								'right'  => 10,
								'bottom' => 10,
								'left'   => 10
							),
							'options'     => array(
								'top'    => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						)
					)
				),
				'icon_section'                  => array(
					'title'  => __( 'Button Icon', 'bb-njba' ),
					'fields' => array(
						'icon_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'icon_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'icon_padding'     => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Padding', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'top'    => 0,
								'right'  => 0,
								'bottom' => 0,
								'left'   => 0
							),
							'options'     => array(
								'top'    => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						),
						'icon_margin'      => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Margin', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'top'    => 0,
								'right'  => 0,
								'bottom' => 0,
								'left'   => 0
							),
							'options'     => array(
								'top'    => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						)
					)
				),
				'transition_section'            => array(
					'title'  => __( 'Button Transition', 'bb-njba' ),
					'fields' => array(
						'transition' => array(
							'type'        => 'text',
							'label'       => __( 'Transition delay', 'bb-njba' ),
							'default'     => 0.3,
							'size'        => '5',
							'description' => 'sec'
						)
					)
				),
				'structure_section'             => array(
					'title'  => __( 'Button Structure', 'bb-njba' ),
					'fields' => array(
						'width'         => array(
							'type'    => 'select',
							'label'   => __( 'Width', 'bb-njba' ),
							'default' => 'auto',
							'options' => array(
								'auto'       => __( 'Auto', 'bb-njba' ),
								'full_width' => __( 'Full Width', 'bb-njba' ),
								'custom'     => __( 'Custom', 'bb-njba' )
							),
							'toggle'  => array(
								'auto'       => array(
									'fields' => array( 'alignment' )
								),
								'full_width' => array(
									'fields' => array( '' )
								),
								'custom'     => array(
									'fields' => array( 'custom_width', 'custom_height', 'alignment' )
								)
							)
						),
						'custom_width'  => array(
							'type'        => 'text',
							'label'       => __( 'Custom Width', 'bb-njba' ),
							'default'     => 200,
							'description' => 'px',
							'size'        => 10
						),
						'custom_height' => array(
							'type'        => 'text',
							'label'       => __( 'Custom Height', 'bb-njba' ),
							'description' => 'px',
							'default'     => 45,
							'size'        => 10
						),
						'alignment'     => array(
							'type'    => 'select',
							'label'   => __( 'Alignment', 'bb-njba' ),
							'default' => 'center',
							'options' => array(
								'left'   => __( 'Left', 'bb-njba' ),
								'center' => __( 'Center', 'bb-njba' ),
								'right'  => __( 'Right', 'bb-njba' )
							)
						)
					)
				)
			)
		),
		'typography'   => array( // Tab
			'title'    => __( 'Typography', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'title_typography'   => array(
					'title'  => __( 'Title', 'bb-njba' ),
					'fields' => array(
						'heading_title_font'        => array(
							'type'    => 'font',
							'default' => array(
								'family' => 'Default',
								'weight' => 300
							),
							'label'   => __( 'Font', 'bb-njba' ),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.njba-heading-title'
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
							'description' => 'Please Enter value in pixels.',
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
							'description' => 'Please Enter value in pixels.',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.njba-heading-title',
								'property' => 'line-height',
								'unit'     => 'px'
							)
						),
					)
				),
				'subhead_typography' => array(
					'title'  => __( 'Description', 'bb-njba' ),
					'fields' => array(
						'heading_sub_title_font'        => array(
							'type'    => 'font',
							'default' => array(
								'family' => 'Default',
								'weight' => 300
							),
							'label'   => __( 'Font', 'bb-njba' ),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.njba-heading-sub-title'
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
							'description' => 'Please Enter value in pixels.',
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
							'description' => 'Please Enter value in pixels.',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.njba-heading-sub-title',
								'property' => 'line-height',
								'unit'     => 'px'
							)
						),
					)
				),
				'button_typography'  => array(
					'title'  => __( 'Button', 'bb-njba' ),
					'fields' => array(
						'button_font_family' => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'bb-njba' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default'
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.njba-btn-main a.njba-btn'
							)
						),
						'button_font_size'   => array(
							'type'        => 'njba-simplify',
							'size'        => '5',
							'label'       => __( 'Font Size', 'bb-njba' ),
							'default'     => array(
								'desktop' => '16',
								'medium'  => '15',
								'small'   => ''
							),
							'description' => 'Please Enter value in pixels.',
						)
					)
				),
				'icon_typography'    => array(
					'title'  => __( 'Button Icon', 'bb-njba' ),
					'fields' => array(
						'icon_font_size' => array(
							'type'        => 'njba-simplify',
							'size'        => '5',
							'label'       => __( 'Font Size', 'bb-njba' ),
							'default'     => array(
								'desktop' => '20',
								'medium'  => '16',
								'small'   => ''
							),
							'description' => 'Please Enter value in pixels.',
						)
					)
				),
			)
		),
	)
) );
