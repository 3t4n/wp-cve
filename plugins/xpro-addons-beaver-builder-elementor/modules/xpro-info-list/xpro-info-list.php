<?php
/**
 * @class XproInfoListModule
 */

class XproInfoListModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Info List', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$content_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-info-list/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-info-list/',
				'editor_export'   => false,
				'partial_refresh' => true,
			)
		);
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'XproInfoListModule',
	array(
		'list_items' => array(
			'title'    => __( 'List Item', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => 'General',
					'fields' => array(
						'list_items' => array(
							'type'         => 'form',
							'label'        => __( 'List Item', 'xpro-bb-addons' ),
							'form'         => 'xpro_info_list_form',
							'preview_text' => 'title',
							'multiple'     => true,
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'general'     => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'layout'                 => array(
							'type'    => 'button-group',
							'label'   => __( 'Layout', 'xpro-bb-addons' ),
							'default' => 'vertical',
							'options' => array(
								'vertical'   => __( 'Vertical', 'xpro-bb-addons' ),
								'horizontal' => __( 'Horizontal', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'horizontal' => array(
									'fields' => array( 'list_item_per_row', 'list_item_space' ),
								),
								'vertical'   => array(
									'fields' => array( 'vertical_align' ),
								),
							),
						),
						'list_align'             => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							'default'    => 'left',
							'responsive' => true,
						),
						'vertical_align'         => array(
							'type'       => 'button-group',
							'label'      => __( 'Vertical Alignment', 'xpro-bb-addons' ),
							'responsive' => true,
							'default'    => 'center',
							'options'    => array(
								'flex-start' => __( 'Top', 'xpro-bb-addons' ),
								'center'     => __( 'Center', 'xpro-bb-addons' ),
								'flex-end'   => __( 'Bottom', 'xpro-bb-addons' ),
							),
						),
						'list_item_per_row'      => array(
							'type'       => 'unit',
							'label'      => __( 'List Items per row', 'xpro-bb-addons' ),
							'default'    => '3',
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'responsive' => array(
								'default' => array(
									'default'    => '3',
									'medium'     => '2',
									'responsive' => '1',
								),
							),
						),
						'list_item_space'        => array(
							'type'       => 'unit',
							'label'      => __( 'Space Between', 'xpro-bb-addons' ),
							'default'    => 20,
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'list_item_space_bottom' => array(
							'type'       => 'unit',
							'label'      => __( 'Space Bottom', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'default'    => 20,
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'list_item_bg'           => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-item',
								'property'  => 'background-color',
								'important' => true,
							),
						),
						'list_item_border'       => array(
							'type'    => 'border',
							'label'   => 'Border',
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-item',
								'property'  => 'border',
								'important' => true,
							),
						),
						'list_item_padding'      => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-item',
								'property'  => 'padding',
								'important' => true,
							),
						),
					),
				),
				'media'       => array(
					'title'     => __( 'Media', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'media_item_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'default'    => 'ffffff',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-media',
								'property'  => 'color',
								'important' => true,
							),
						),
						'media_item_bg'         => array(
							'type'       => 'color',
							'label'      => __( 'Background', 'xpro-bb-addons' ),
							'default'    => '6ec1e4',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-media',
								'property'  => 'background-color',
								'important' => true,
							),
						),
						'media_item_border'     => array(
							'type'    => 'border',
							'label'   => 'Border',
							'preview' => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-media',
								'property'  => 'border',
								'important' => true,
							),
						),
						'media_margin'          => array(
							'type'       => 'dimension',
							'label'      => __( 'Margin', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-media',
								'property'  => 'margin',
								'important' => true,
							),
						),
						'media_padding'         => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-media',
								'property'  => 'padding',
								'important' => true,
							),
						),
						'xpro-widget-seprator1' => array(
							'type'    => 'raw',
							'content' => '<h2 class="xpro-widget-separator-heading">Icon<hr></h2>',
						),
						'media_icon_size'       => array(
							'type'       => 'unit',
							'label'      => __( 'Size', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'media_icon_bgsize'     => array(
							'type'       => 'unit',
							'label'      => __( 'Background Size', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'xpro-widget-seprator2' => array(
							'type'    => 'raw',
							'content' => '<h2 class="xpro-widget-separator-heading">Image<hr></h2>',
						),
						'media_image_size'      => array(
							'type'       => 'unit',
							'label'      => __( 'Width', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'image_height'          => array(
							'type'       => 'unit',
							'label'      => __( 'Height', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'object_fit'            => array(
							'type'       => 'select',
							'label'      => __( 'Object Fit', 'xpro-bb-addons' ),
							'responsive' => true,
							'default'    => 'none',
							'options'    => array(
								'none'    => __( 'Default', 'xpro-bb-addons' ),
								'fill'    => __( 'Fill', 'xpro-bb-addons' ),
								'cover'   => __( 'Cover', 'xpro-bb-addons' ),
								'contain' => __( 'Contain', 'xpro-bb-addons' ),
							),
						),
						'xpro-widget-seprator3' => array(
							'type'    => 'raw',
							'content' => '<h2 class="xpro-widget-separator-heading">Custom<hr></h2>',
						),
						'media_custom_bg_size'  => array(
							'type'       => 'unit',
							'label'      => __( 'Background Size', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'media_custom_padding'  => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
					),
				),
				'title'       => array(
					'title'     => __( 'Title', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'title_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Title Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-title',
								'property'  => 'color',
								'important' => true,
							),
						),
						'title_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Title Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'title_margin'      => array(
							'type'       => 'dimension',
							'label'      => __( 'Margin', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
						'title_padding'     => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
						),
					),
				),
				'description' => array(
					'title'     => __( 'Description', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'desc_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Description Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-desc',
								'property'  => 'color',
								'important' => true,
							),
						),
						'desc_hv_color' => array(
							'type'       => 'color',
							'label'      => __( 'Description Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'desc_margin'   => array(
							'type'       => 'dimension',
							'label'      => __( 'Margin', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-desc',
								'property'  => 'margin',
								'important' => true,
							),
						),
						'desc_padding'  => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-desc',
								'property'  => 'padding',
								'important' => true,
							),
						),

					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'xpro-bb-addons' ),
			'sections' => array(
				'title_typography'       => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'title_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-title',
								'important' => true,
							),
						),
					),
				),
				'description_typography' => array(
					'title'     => __( 'Description', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'description_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-desc',
								'important' => true,
							),
						),
					),
				),
				'count_typography'       => array(
					'title'     => __( 'Counter', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'count_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.xpro-infolist-custom',
								'important' => true,
							),
						),
					),
				),
			),
		),
	)
);

// Register form for thead.
FLBuilder::register_settings_form(
	'xpro_info_list_form',
	array(
		'title' => __( 'Add List Item', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => __( 'General Settings', 'xpro-bb-addons' ),
						'fields' => array(
							'media_type'         => array(
								'type'    => 'select',
								'label'   => __( 'Media Type', 'xpro-bb-addons' ),
								'default' => 'icon',
								'options' => array(
									'none'   => __( 'None', 'xpro-bb-addons' ),
									'icon'   => __( 'Icon', 'xpro-bb-addons' ),
									'image'  => __( 'Image', 'xpro-bb-addons' ),
									'custom' => __( 'Custom', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'icon'   => array(
										'fields' => array( 'icon' ),
									),
									'image'  => array(
										'fields' => array( 'image', 'image_size' ),
									),
									'custom' => array(
										'fields' => array( 'custom', 'media_color', 'media_bgcolor', 'media_border_color' ),
									),
								),
							),
							'icon'               => array(
								'type'    => 'icon',
								'label'   => __( 'Icon', 'xpro-bb-addons' ),
								'default' => 'fas fa-check',
							),
							'image'              => array(
								'type'        => 'photo',
								'label'       => __( 'Image', 'xpro-bb-addons' ),
								'show_remove' => true,
								'connections' => array( 'photo' ),
							),
							'custom'             => array(
								'type'    => 'text',
								'label'   => __( 'Custom', 'xpro-bb-addons' ),
								'default' => '01',
							),
							'media_color'        => array(
								'type'        => 'color',
								'connections' => array( 'color' ),
								'label'       => __( 'Content Color', 'xpro-bb-addons' ),
								'show_reset'  => true,
								'show_alpha'  => true,
							),
							'media_bgcolor'      => array(
								'type'        => 'color',
								'connections' => array( 'color' ),
								'label'       => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset'  => true,
								'show_alpha'  => true,
							),
							'media_border_color' => array(
								'type'        => 'color',
								'connections' => array( 'color' ),
								'label'       => __( 'Border Color', 'xpro-bb-addons' ),
								'show_reset'  => true,
								'show_alpha'  => true,
							),
							'title'              => array(
								'type'        => 'text',
								'label'       => __( 'Title', 'xpro-bb-addons' ),
								'default'     => __( 'List Title Here', 'xpro-bb-addons' ),
								'connections' => array( 'string', 'html' ),
							),
							'description'        => array(
								'type'          => 'editor',
								'label'         => __( 'Description', 'xpro-bb-addons' ),
								'default'       => __( 'List description here', 'xpro-bb-addons' ),
								'placeholder'   => __( 'Type your description here', 'xpro-bb-addons' ),
								'media_buttons' => false,
								'connections'   => array( 'string', 'html' ),
								'wpautop'       => false,
							),
							'link'               => array(
								'type'          => 'link',
								'label'         => __( 'Link', 'xpro-bb-addons' ),
								'show_target'   => true,
								'show_nofollow' => true,
								'connections'   => array( 'url' ),
								'placeholder'   => __( 'https://your-link.com', 'xpro-bb-addons' ),
							),
						),
					),
				),
			),
		),
	)
);
