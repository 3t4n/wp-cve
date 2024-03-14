<?php
/**
 * @class XproTestimonial
 */

class XproTestimonial extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Testimonial', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$creative_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-testimonial/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-testimonial/',
				'partial_refresh' => true,

			)
		);

	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {
		// Already registered
		$this->add_css( 'font-awesome' );
		$this->add_css( 'font-awesome-5' );
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'XproTestimonial',
	array(
		'general' => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'layout'        => array(
							'type'    => 'select',
							'label'   => __( 'Layout', 'xpro-bb-addons' ),
							'default' => '1',
							'options' => array(
								'1'  => __( 'Style 1', 'xpro-bb-addons' ),
								'2'  => __( 'Style 2', 'xpro-bb-addons' ),
								'3'  => __( 'Style 3', 'xpro-bb-addons' ),
								'4'  => __( 'Style 4', 'xpro-bb-addons' ),
								'5'  => __( 'Style 5', 'xpro-bb-addons' ),
								'6'  => __( 'Style 6', 'xpro-bb-addons' ),
								'7'  => __( 'Style 7', 'xpro-bb-addons' ),
								'8'  => __( 'Style 8', 'xpro-bb-addons' ),
								'9'  => __( 'Style 9', 'xpro-bb-addons' ),
								'10' => __( 'Style 10', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'1'  => array(
									'sections' => array( 'quote_style' ),
								),
								'2'  => array(
									'sections' => array( 'quote_style' ),
								),
								'3'  => array(
									'sections' => array( 'quote_style' ),
								),
								'4'  => array(
									'sections' => array( 'quote_style' ),
								),
								'5'  => array(
									'sections' => array( 'quote_style' ),
								),
								'7'  => array(
									'sections' => array( 'quote_style' ),
								),
								'8'  => array(
									'sections' => array( 'quote_style' ),
								),
								'10' => array(
									'sections' => array( 'quote_style' ),
								),
							),
						),
						'rating_style'  => array(
							'type'    => 'button-group',
							'label'   => __( 'Rating Style', 'xpro-bb-addons' ),
							'default' => 'star',
							'options' => array(
								'star' => __( 'Star', 'xpro-bb-addons' ),
								'num'  => __( 'Number', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'star' => array(
									'fields' => array( 'rating_size', 'rating_filled' ),
								),
								'num'  => array(
									'fields' => array( 'rating_bg', 'rating_typography', 'rating_border', 'rating_padding' ),
								),
							),
						),
						'columns'       => array(
							'type'       => 'select',
							'label'      => __( 'Columns', 'xpro-bb-addons' ),
							'options'    => array(
								'1' => __( 'Column 1', 'xpro-bb-addons' ),
								'2' => __( 'Column 2', 'xpro-bb-addons' ),
								'3' => __( 'Column 3', 'xpro-bb-addons' ),
								'4' => __( 'Column 4', 'xpro-bb-addons' ),
							),
							'responsive' => array(
								'default' => array(
									'default'    => '1',
									'medium'     => '1',
									'responsive' => '1',
								),
							),
						),
						'space_between' => array(
							'type'         => 'unit',
							'label'        => 'Space Between',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-wrapper',
										'property' => 'grid-column-gap',
									),
									array(
										'selector' => '{node} .xpro-testimonial-wrapper',
										'property' => 'grid-row-gap',
									),
								),
							),
						),
						'alignment'     => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							'responsive' => array(
								'default' => array(
									'default'    => 'left',
									'medium'     => 'center',
									'responsive' => 'center',
								),
							),
						),
					),
				),
				'items'   => array(
					'title'  => __( 'Items', 'xpro-bb-addons' ),
					'fields' => array(
						'items' => array(
							'type'         => 'form',
							'label'        => __( 'Testimonial', 'xpro-bb-addons' ),
							'form'         => 'XproTestimonialForm',
							'preview_text' => 'author_name',
							'multiple'     => true,
						),
					),
				),
			),
		),
		'style'   => array(
			'title'    => __( 'Styling', 'xpro-bb-addons' ),
			'sections' => array(
				'general_style'     => array(
					'title'  => '',
					'fields' => array(
						'background_type' => array(
							'type'    => 'button-group',
							'label'   => __( 'Background Type', 'xpro-bb-addons' ),
							'default' => 'color',
							'options' => array(
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'bg_color' ),
								),
								'gradient' => array(
									'fields' => array( 'bg_gradient' ),
								),
							),
						),
						'bg_color'        => array(
							'type'       => 'color',
							'label'      => ' ',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-layout-1 .xpro-testimonial-item,{node} .xpro-testimonial-layout-2 .xpro-testimonial-item,{node} .xpro-testimonial-layout-3 .xpro-testimonial-item,{node} .xpro-testimonial-layout-7 .xpro-testimonial-item,{node} .xpro-testimonial-layout-9 .xpro-testimonial-item, {node} .xpro-testimonial-layout-10 .xpro-testimonial-item',
										'property' => 'background-color',
									),
									array(
										'selector' => '{node} .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-6 .xpro-testimonial-content,{node} .xpro-testimonial-layout-8 .xpro-testimonial-content',
										'property' => 'background-color',
									),
								),
							),
						),
						'bg_gradient'     => array(
							'type'    => 'gradient',
							'label'   => ' ',
							'default' => array(
								'colors' => array(
									'0' => '81c1b7',
									'1' => '84baa2',
								),
							),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-layout-1 .xpro-testimonial-item,{node} .xpro-testimonial-layout-2 .xpro-testimonial-item,{node} .xpro-testimonial-layout-3 .xpro-testimonial-item,{node} .xpro-testimonial-layout-7 .xpro-testimonial-item,{node} .xpro-testimonial-layout-9 .xpro-testimonial-item, {node} .xpro-testimonial-layout-10 .xpro-testimonial-item',
										'property' => 'background-image',
									),
									array(
										'selector' => '{node} .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-6 .xpro-testimonial-content,{node} .xpro-testimonial-layout-8 .xpro-testimonial-content',
										'property' => 'background-image',
									),
								),
							),
						),
						'border'          => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-layout-1 .xpro-testimonial-item,{node} .xpro-testimonial-layout-2 .xpro-testimonial-item,{node} .xpro-testimonial-layout-3 .xpro-testimonial-item,{node} .xpro-testimonial-layout-7 .xpro-testimonial-item,{node} .xpro-testimonial-layout-9 .xpro-testimonial-item, {node} .xpro-testimonial-layout-10 .xpro-testimonial-item',
									),
									array(
										'selector' => '{node} .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-6 .xpro-testimonial-content,{node} .xpro-testimonial-layout-8 .xpro-testimonial-content',
									),
								),
							),
						),
						'padding'         => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-layout-1 .xpro-testimonial-item,{node} .xpro-testimonial-layout-2 .xpro-testimonial-item,{node} .xpro-testimonial-layout-3 .xpro-testimonial-item,{node} .xpro-testimonial-layout-7 .xpro-testimonial-item,{node} .xpro-testimonial-layout-9 .xpro-testimonial-item, {node} .xpro-testimonial-layout-10 .xpro-testimonial-item',
										'property' => 'padding',
									),
									array(
										'selector' => '{node} .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,{node} .xpro-testimonial-layout-6 .xpro-testimonial-content,{node} .xpro-testimonial-layout-8 .xpro-testimonial-content',
										'property' => 'padding',
									),
								),
							),
						),
					),
				),
				'image_style'       => array(
					'title'     => __( 'Image', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'image_width'  => array(
							'type'         => 'unit',
							'label'        => 'Width',
							'units'        => array( '%', 'px', 'vw' ),
							'default_unit' => 'px',
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-image > img',
										'property' => 'width',
									),
								),
							),
						),
						'image_height' => array(
							'type'         => 'unit',
							'label'        => 'Height',
							'units'        => array( 'px', 'vh' ),
							'default_unit' => 'px',
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-image > img',
										'property' => 'height',
									),
								),
							),
						),
						'object_fit'   => array(
							'type'    => 'select',
							'label'   => __( 'Object Fit', 'xpro-bb-addons' ),
							'default' => '',
							'options' => array(
								''        => __( 'Default', 'xpro-bb-addons' ),
								'fill'    => __( 'Fill', 'xpro-bb-addons' ),
								'cover'   => __( 'Cover', 'xpro-bb-addons' ),
								'contain' => __( 'Contain', 'xpro-bb-addons' ),
							),
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-image > img',
										'property' => 'object-fit',
									),
								),
							),
						),
						'image_border' => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-image > img',
									),
								),
							),
						),
						'image_shadow' => array(
							'type'        => 'shadow',
							'label'       => 'Shadow',
							'show_spread' => true,
							'preview'     => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-image > img',
										'property' => 'box-shadow',
									),
								),
							),
						),
						'image_margin' => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-image',
										'property' => 'margin',
									),
								),
							),
						),
					),
				),
				'author_style'      => array(
					'title'     => __( 'Author', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'author_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-title',
								'property' => 'color',
							),
						),
						'author_typography' => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-title',
							),
						),
						'author_margin'     => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-title',
										'property' => 'margin',
									),
								),
							),
						),
					),
				),
				'designation_style' => array(
					'title'     => __( 'Designation', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'designation_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-designation',
								'property' => 'color',
							),
						),
						'designation_typography' => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-designation',
							),
						),
						'designation_margin'     => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-designation',
										'property' => 'margin',
									),
								),
							),
						),
					),
				),
				'description_style' => array(
					'title'     => __( 'Description', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'description_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-description',
								'property' => 'color',
							),
						),
						'description_typography' => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-description',
							),
						),
						'description_margin'     => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-description',
										'property' => 'margin',
									),
								),
							),
						),
					),
				),
				'rating_style'      => array(
					'title'     => __( 'Rating', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'rating_size'       => array(
							'type'         => 'unit',
							'label'        => 'Size',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-rating',
										'property' => 'font-size',
									),
								),
							),
						),
						'rating_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-rating,{node} .xpro-rating-layout-star > i',
								'property' => 'color',
							),
						),
						'rating_filled'     => array(
							'type'       => 'color',
							'label'      => __( 'Filled', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-rating-layout-star > .xpro-rating-filled',
								'property' => 'color',
							),
						),
						'rating_bg'         => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-rating-layout-num',
										'property' => 'background-color',
									),
								),
							),
						),
						'rating_typography' => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-rating-layout-num',
							),
						),
						'rating_border'     => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-rating-layout-num',
									),
								),
							),
						),
						'rating_padding'    => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-rating-layout-num',
										'property' => 'padding',
									),
								),
							),
						),
						'rating_margin'     => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-rating',
										'property' => 'margin',
									),
								),
							),
						),
					),
				),
				'quote_style'       => array(
					'title'     => __( 'Quote', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'quote_size'   => array(
							'type'         => 'unit',
							'label'        => 'Size',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-quote',
										'property' => 'font-size',
									),
								),
							),
						),
						'quote_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-testimonial-quote',
								'property' => 'color',
							),
						),
						'quote_margin' => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-testimonial-quote',
										'property' => 'margin',
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

// Xpro Testimonial Form
FLBuilder::register_settings_form(
	'XproTestimonialForm',
	array(
		'title' => __( 'Add Testimonial', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'title' => array(
						'title'  => __( 'Title', 'xpro-bb-addons' ),
						'fields' => array(
							'author_name'  => array(
								'type'    => 'text',
								'label'   => __( 'Author Name', 'xpro-bb-addons' ),
								'default' => __( 'John Smiths', 'xpro-bb-addons' ),
							),
							'author_link'  => array(
								'type'          => 'link',
								'label'         => 'Author Link',
								'show_target'   => true,
								'show_nofollow' => true,
							),
							'author_image' => array(
								'type'        => 'photo',
								'label'       => __( 'Author Image', 'xpro-bb-addons' ),
								'show_remove' => true,
							),
							'image_size'   => array(
								'type'    => 'photo-sizes',
								'label'   => __( 'Image Size', 'xpro-bb-addons' ),
								'default' => 'medium',
							),
							'designation'  => array(
								'type'    => 'text',
								'label'   => __( 'Designation', 'xpro-bb-addons' ),
								'default' => __( 'UI/UX Designer', 'xpro-bb-addons' ),
							),
							'rating'       => array(
								'type'    => 'unit',
								'label'   => 'Rating',
								'default' => 4,
								'slider'  => array(
									'min'  => 0,
									'max'  => 5,
									'step' => 1,
								),
							),
							'description'  => array(
								'type'    => 'textarea',
								'rows'    => 6,
								'default' => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'xpro-bb-addons' ),
							),
						),
					),
				),
			),
		),
	)
);
