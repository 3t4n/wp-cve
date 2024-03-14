<?php
/**
 * @class TNITHoverCard
 */

class TNITIconListModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Icon List', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$creative_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-icon-list/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-icon-list/',
				'editor_export'   => false,
				'partial_refresh' => true,
			)
		);
	}


	/**
	 * Function that renders Icon
	 *
	 * @method render_icon
	 */
	public function render_icon( $i ) {
        $settings  = $this->settings;
		$list_item = $settings->list_items[ $i ];

		if ( ! empty( $list_item->icon ) ) {
			$icon_class = 'tnit-icon tnit-icon-' . $settings->icon_bg_style;

			$output  = '<span class="tnit-icon-wrap">';
			$output .= '<span class="' . $icon_class . '">';
			$output .= '<i class="' . $list_item->icon . '"></i>';
			$output .= '</span>';
			$output .= '</span>';

			echo $output;
		}
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITIconListModule',
	array(
		'icon_list'  => array(
			'title'    => __( 'Icon List', 'xpro-bb-addons' ),
			'sections' => array(
				'list_items' => array(
					'title'  => __( 'List Items', 'xpro-bb-addons' ),
					'fields' => array(
						'list_items' => array(
							'type'         => 'form',
							'label'        => __( 'List Item', 'xpro-bb-addons' ),
							'form'         => 'icon_list_form',
							'preview_text' => 'title',
							'multiple'     => true,
						),
					),
				),
			),
		),
		'icon_image' => array(
			'title'    => __( 'Icon', 'xpro-bb-addons' ),
			'sections' => array(
				'style'  => array(
					'title'  => __( 'Style', 'xpro-bb-addons' ),
					'fields' => array(
						'icon_bg_style' => array(
							'type'    => 'select',
							'label'   => __( 'Background Style', 'xpro-bb-addons' ),
							'default' => 'simple',
							'options' => array(
								'simple' => __( 'Simple', 'xpro-bb-addons' ),
								'circle' => __( 'Circle Background', 'xpro-bb-addons' ),
								'square' => __( 'Square Background', 'xpro-bb-addons' ),
								'custom' => __( 'Design your own', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'circle' => array(
									'fields' => array( 'icon_bg_color', 'icon_bg_hvr_color' ),
								),
								'square' => array(
									'fields' => array( 'icon_bg_color', 'icon_bg_hvr_color' ),
								),
								'custom' => array(
									'fields' => array( 'icon_bg_color', 'icon_bg_hvr_color', 'icon_bg_size', 'icon_border', 'icon_border_hvr_color' ),
								),
							),
						),
						'icon_size'     => array(
							'type'        => 'unit',
							'label'       => __( 'Icon Size', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '25',
							'slider'      => true,
							'responsive'  => true,
						),
						'icon_bg_size'  => array(
							'type'        => 'unit',
							'label'       => __( 'Background Size', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '50',
							'slider'      => true,
							'responsive'  => true,
						),
						'icon_border'   => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'xpro-bb-addons' ),
							'responsive' => true,
						),
					),
				),
				'colors' => array(
					'title'  => __( 'Colors', 'xpro-bb-addons' ),
					'fields' => array(
						'icon_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
						'icon_hvr_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Icon Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
						'icon_bg_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_bg_hvr_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_border_hvr_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'structure' => array(
					'title'  => __( 'Structure', 'xpro-bb-addons' ),
					'fields' => array(
						'list_item_space'  => array(
							'type'        => 'unit',
							'label'       => __( 'Space Between List Items', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '10',
							'slider'      => true,
							'responsive'  => true,
						),
						'icon_title_space' => array(
							'type'        => 'unit',
							'label'       => __( 'Space Between Icon & Title', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '10',
							'slider'      => true,
							'responsive'  => true,
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'xpro-bb-addons' ),
			'sections' => array(
				'title_typography' => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'title_tag'        => array(
							'type'    => 'select',
							'label'   => __( 'HTML Tag', 'xpro-bb-addons' ),
							'default' => 'h3',
							'options' => array(
								'h1'   => __( 'H1', 'xpro-bb-addons' ),
								'h2'   => __( 'H2', 'xpro-bb-addons' ),
								'h3'   => __( 'H3', 'xpro-bb-addons' ),
								'h4'   => __( 'H4', 'xpro-bb-addons' ),
								'h5'   => __( 'H5', 'xpro-bb-addons' ),
								'h6'   => __( 'H6', 'xpro-bb-addons' ),
								'div'  => __( 'Div', 'xpro-bb-addons' ),
								'p'    => __( 'p', 'xpro-bb-addons' ),
								'span' => __( 'span', 'xpro-bb-addons' ),
							),
						),
						'title_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'title_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
						'title_hvr_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
					),
				),
			),
		),
	)
);

/**
 * Register the module and its form thead.
 */
FLBuilder::register_settings_form(
	'icon_list_form',
	array(
		'title' => __( 'Add List Item', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general'    => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general'      => array(
						'title'  => __( 'General Settings', 'xpro-bb-addons' ),
						'fields' => array(
							'title' => array(
								'type'        => 'text',
								'label'       => __( 'Title', 'xpro-bb-addons' ),
								'default'     => __( 'List item title here.', 'xpro-bb-addons' ),
								'connections' => array( 'string', 'html' ),
							),
							'link'  => array(
								'type'          => 'link',
								'label'         => __( 'Link', 'xpro-bb-addons' ),
								'show_target'   => true,
								'show_nofollow' => true,
							),
						),
					),
					'title_colors' => array(
						'title'  => __( 'Title Colors', 'xpro-bb-addons' ),
						'fields' => array(
							'title_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
							'title_hvr_color' => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
						),
					),
				),
			),
			'icon_image' => array(
				'title'    => __( 'Icon', 'xpro-bb-addons' ),
				'sections' => array(
					'icon_image' => array(
						'title'  => __( 'Icon Basics', 'xpro-bb-addons' ),
						'fields' => array(
							'icon' => array(
								'type'    => 'icon',
								'label'   => __( 'Choose Icon', 'xpro-bb-addons' ),
								'default' => 'fas fa-check',
							),
						),
					),
					'colors'     => array(
						'title'     => __( 'Colors', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'icon_color'            => array(
								'type'       => 'color',
								'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
							'icon_hvr_color'        => array(
								'type'       => 'color',
								'label'      => __( 'Icon Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
							'icon_bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'icon_bg_hvr_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'icon_border_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Border Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'icon_border_hvr_color' => array(
								'type'       => 'color',
								'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
						),
					),
				),
			),
		),
	)
);
