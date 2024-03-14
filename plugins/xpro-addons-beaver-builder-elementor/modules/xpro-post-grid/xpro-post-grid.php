<?php
/**
 * @class XproPostGridModule
 */

class XproPostGridModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Post Grid', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$content_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-post-grid/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-post-grid/',
				'partial_refresh' => true,
			)
		);
	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {

        $this->add_css( 'xpro-cubeportfolio', XPRO_ADDONS_FOR_BB_URL . 'assets/css/cubeportfolio.min.css', '', '4.4.0' );
        $this->add_js( 'xpro-cubeportfolio', XPRO_ADDONS_FOR_BB_URL . 'assets/js/jquery.cubeportfolio.min.js', array( 'jquery' ), '4.4.0', true );
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'XproPostGridModule',
	array(
		'general'       => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general'    => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'layout'         => array(
							'type'    => 'select',
							'label'   => __( 'Select Layout', 'xpro-bb-addons' ),
							'default' => '1',
							'options' => array(
								'1'  => __( 'Layout 1', 'xpro-bb-addons' ),
								'2'  => __( 'Layout 2', 'xpro-bb-addons' ),
								'3'  => __( 'Layout 3', 'xpro-bb-addons' ),
								'4'  => __( 'Layout 4', 'xpro-bb-addons' ),
								'5'  => __( 'Layout 5', 'xpro-bb-addons' ),
								'6'  => __( 'Layout 6', 'xpro-bb-addons' ),
								'7'  => __( 'Layout 7', 'xpro-bb-addons' ),
								'8'  => __( 'Layout 8', 'xpro-bb-addons' ),
								'9'  => __( 'Layout 9', 'xpro-bb-addons' ),
								'10' => __( 'Layout 10', 'xpro-bb-addons' ),
							),
							'1'       => array(
								'fields' => array( 'image_height' ),
							),
							'2'       => array(
								'fields' => array( 'item_height' ),
							),
							'3'       => array(
								'fields' => array( 'image_height' ),
							),
							'4'       => array(
								'fields' => array( 'image_height' ),
							),
							'5'       => array(
								'fields' => array( 'image_height' ),
							),
							'6'       => array(
								'fields' => array( 'item_height' ),
							),
							'7'       => array(
								'fields' => array( 'image_height', 'xpro-widget-seprator3', 'meta_wrapper_border', 'meta_wrapper_padding', 'meta_wrapper_margin' ),
							),
							'8'       => array(
								'fields' => array( 'image_height', 'item_height' ),
							),
							'9'       => array(
								'fields' => array( 'image_height', 'item_height' ),
							),
							'10'      => array(
								'fields' => array( 'image_height', 'item_height' ),
							),

						),
						'column_grid'    => array(
							'type'       => 'select',
							'label'      => __( 'Select Columns', 'xpro-bb-addons' ),
							'responsive' => array(
								'default' => array(
									'default'    => '3',
									'medium'     => '2',
									'responsive' => '1',
								),
							),
							'options'    => array(
								'1' => __( '1', 'xpro-bb-addons' ),
								'2' => __( '2', 'xpro-bb-addons' ),
								'3' => __( '3', 'xpro-bb-addons' ),
								'4' => __( '4', 'xpro-bb-addons' ),
								'5' => __( '5', 'xpro-bb-addons' ),
								'6' => __( '6', 'xpro-bb-addons' ),
							),
						),
						'show_image'     => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Image', 'xpro-bb-addons' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'thumbnail', 'image_height' ),
								),
							),
						),
						'thumbnail'      => array(
							'type'    => 'photo-sizes',
							'label'   => __( 'Image Size', 'xpro-bb-addons' ),
							'default' => 'medium',
						),
						'show_content'   => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Description', 'xpro-bb-addons' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'content_length' ),
								),
							),
						),
						'content_length' => array(
							'type'    => 'unit',
							'label'   => 'Description Length',
							'default' => 10,
							'slider'  => array(
								'min'  => 0,
								'max'  => 500,
								'step' => 5,
							),
						),
						'show_btn'       => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Button', 'xpro-bb-addons' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'show_btn_text' ),
								),
							),
						),
						'show_btn_text'  => array(
							'type'    => 'text',
							'label'   => __( 'Button Text', 'xpro-bb-addons' ),
							'default' => 'Read More',
						),
					),
				),
				'author'     => array(
					'title'     => __( 'Author', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'show_author'        => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Author', 'xpro-bb-addons' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'author_title', 'show_author_avatar' ),
									'sections' => array( 'styl-author' ),
								),
							),
						),
						'author_title'       => array(
							'type'    => 'text',
							'label'   => __( 'Author Title', 'xpro-bb-addons' ),
							'default' => 'Posted By',
						),
						'show_author_avatar' => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Avatar', 'xpro-bb-addons' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
						),
					),
				),
				'meta'       => array(
					'title'     => __( 'Meta', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'show_date_meta'     => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Date', 'xpro-bb-addons' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'date_meta_icon' ),
								),
							),
						),
						'date_meta_icon'     => array(
							'type'        => 'icon',
							'label'       => __( 'Date Icon', 'xpro-bb-addons' ),
							'default'     => 'far fa-calendar',
							'show_remove' => true,
						),
						'show_category_meta' => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Category', 'xpro-bb-addons' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'category_meta_icon' ),
								),
							),
						),
						'category_meta_icon' => array(
							'type'        => 'icon',
							'label'       => __( 'Category Icon', 'xpro-bb-addons' ),
							'default'     => 'far fa-folder',
							'show_remove' => true,
						),
						'show_comments_meta' => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Comments', 'xpro-bb-addons' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'comments_meta_icon' ),
								),
							),
						),
						'comments_meta_icon' => array(
							'type'        => 'icon',
							'label'       => __( 'Comment Icon', 'xpro-bb-addons' ),
							'default'     => 'far fa-comment-alt',
							'show_remove' => true,
						),
					),
				),
				'pagination' => array(
					'title'     => __( 'Pagination', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'show_pagination' => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Pagination', 'xpro-bb-addons' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Show', 'xpro-bb-addons' ),
								'no'  => __( 'Hide', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'prev_label', 'next_label', 'arrow' ),
									'sections' => array( 'styl-pagination' ),
								),
								'no'  => array(
									'fields' => array( 'offset' ),
								),
							),
						),
						'posts_per_page'  => array(
							'type'    => 'unit',
							'label'   => __( 'Posts Per Page', 'xpro-bb-addons' ),
							'default' => 10,
							'slider'  => true,
						),
						'prev_label'      => array(
							'type'    => 'text',
							'label'   => __( 'Prev Label', 'xpro-bb-addons' ),
							'default' => 'Prev',
						),
						'next_label'      => array(
							'type'    => 'text',
							'label'   => __( 'Next Label', 'xpro-bb-addons' ),
							'default' => 'Next',
						),
						'arrow'           => array(
							'type'    => 'select',
							'label'   => __( 'Arrows Type', 'xpro-bb-addons' ),
							'default' => 'fas fa-arrow-left',
							'options' => array(
								'fas fa-arrow-left'        => __( 'Arrow', 'xpro-bb-addons' ),
								'fas fa-angle-left'        => __( 'Angle', 'xpro-bb-addons' ),
								'fas fa-angle-double-left' => __( 'Double Angle', 'xpro-bb-addons' ),
								'fas fa-chevron-left'      => __( 'Chevron', 'xpro-bb-addons' ),
								'fas fa-chevron-circle-left' => __( 'Chevron Circle', 'xpro-bb-addons' ),
								'fas fa-caret-left'        => __( 'Caret', 'xpro-bb-addons' ),
								'xi xi-long-arrow-left'    => __( 'Long Arrow', 'xpro-bb-addons' ),
								'fas fa-arrow-circle-left' => __( 'Arrow Circle', 'xpro-bb-addons' ),
							),
						),
					),
				),
			),
		),
		'product_query' => array(
			'title' => __( 'Query', 'xpro' ),
			'file'  => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-post-grid/includes/loop-settings.php',
		),
		'style'         => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'general'      => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'alignment'        => array(
							'type'       => 'button-group',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							'responsive' => true,
							'default'    => 'left',
							'options'    => array(
								'left'   => __( 'Left', 'xpro-bb-addons' ),
								'center' => __( 'Center', 'xpro-bb-addons' ),
								'right'  => __( 'Right', 'xpro-bb-addons' ),
							),
						),
						'item_height'      => array(
							'type'         => 'unit',
							'label'        => 'Item Height',
							'units'        => array( 'px', 'vh', '%' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1200,
									'step' => 1,
								),
							),
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-item',
								'property' => 'height',
							),
						),
						'image_height'     => array(
							'type'         => 'unit',
							'label'        => 'Image Height',
							'units'        => array( 'px', 'vh', '%' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1200,
									'step' => 1,
								),
							),
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-image',
								'property' => 'height',
							),
						),
						'space_between'    => array(
							'type'         => 'unit',
							'label'        => 'Space Between',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'default'      => 15,
							'responsive'   => true,
							'slider'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 500,
									'step' => 1,
								),
							),
						),
						'item_bg_type'     => array(
							'type'    => 'button-group',
							'label'   => __( 'Background Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'     => __( 'None', 'xpro-bb-addons' ),
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'item_bg' ),
								),
								'gradient' => array(
									'fields' => array( 'item_bg_gradient' ),
								),
							),
						),
						'item_bg'          => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-item',
								'property' => 'background-color',
							),
						),
						'item_bg_gradient' => array(
							'type'    => 'gradient',
							'label'   => 'Gradient Color',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-item',
								'property' => 'background-image',
							),
						),
						'item_border'      => array(
							'type'       => 'border',
							'label'      => 'Border',
							'units'      => array( 'px', '%' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-item',
								'property' => 'border',
							),
						),
						'item_padding'     => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'responsive' => true,
							'units'      => array( 'px', '%' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-item',
								'property' => 'padding',
							),
						),
						'overlay_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Overlay Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-item .xpro-post-grid-image::after',
								'property' => 'background-color',
							),
						),
						'overlay_hcolor'   => array(
							'type'       => 'color',
							'label'      => __( 'Overlay Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-item:hover .xpro-post-grid-image::after',
								'property' => 'background-color',
							),
						),
					),
				),
				'other_styles' => array(
					'title'  => __( 'Other Styles', 'xpro-bb-addons' ),
					'fields' => array(
						'content_styles'    => array(
							'type'         => 'form',
							'label'        => __( 'Content Styles', 'xpro-bb-addons' ),
							'form'         => 'xpro_post_grid_content_form',
							'preview_text' => 'icon',
						),
						'meta_styles'       => array(
							'type'         => 'form',
							'label'        => __( 'Meta Styles', 'xpro-bb-addons' ),
							'form'         => 'xpro_post_grid_meta_form',
							'preview_text' => 'icon',
						),
						'author_styles'     => array(
							'type'         => 'form',
							'label'        => __( 'Author Styles', 'xpro-bb-addons' ),
							'form'         => 'xpro_post_grid_author_form',
							'preview_text' => 'icon',
						),
						'pagination_styles' => array(
							'type'         => 'form',
							'label'        => __( 'Pagination Styles', 'xpro-bb-addons' ),
							'form'         => 'xpro_post_grid_pagination_form',
							'preview_text' => 'icon',
						),
					),
				),
			),
		),
	)
);

/**
 * Register a settings form for Content Styles.
 */
FLBuilder::register_settings_form(
	'xpro_post_grid_content_form',
	array(
		'title' => __( 'Content Styles', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'Content', 'xpro-bb-addons' ),
				'sections' => array(
					'content' => array(
						'title'  => __( 'Content', 'xpro-bb-addons' ),
						'fields' => array(
							'content_bg_type'        => array(
								'type'    => 'button-group',
								'label'   => __( 'Background Type', 'xpro-bb-addons' ),
								'default' => 'none',
								'options' => array(
									'none'     => __( 'None', 'xpro-bb-addons' ),
									'color'    => __( 'Color', 'xpro-bb-addons' ),
									'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'color'    => array(
										'fields' => array( 'content_background' ),
									),
									'gradient' => array(
										'fields' => array( 'content_gradient' ),
									),
								),
							),
							'content_background'     => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-content',
									'property' => 'background-color',
								),
							),
							'content_gradient'       => array(
								'type'    => 'gradient',
								'label'   => 'Gradient Color',
								'preview' => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-content',
									'property' => 'background-image',
								),
							),
							'content_border'         => array(
								'type'       => 'border',
								'label'      => 'Border',
								'units'      => array( 'px', '%' ),
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-content',
									'property' => 'border',
								),
							),
							'content_padding'        => array(
								'type'       => 'dimension',
								'label'      => 'Padding',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-content',
									'property' => 'padding',
								),
							),
							'content_margin'         => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-content',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator1'  => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
							),
							'title_typography'       => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-title',
								),
							),
							'title_color'            => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-title',
									'property' => 'color',
								),
							),
							'title_hover_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-title:hover',
									'property' => 'color',
								),
							),
							'title_margin'           => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-title',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator2'  => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Description<hr></h2>',
							),
							'description_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-excerpt',
								),
							),
							'excerpt_color'          => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-excerpt',
									'property' => 'color',
								),
							),
							'excerpt_margin'         => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-excerpt',
									'property' => 'margin',
								),
							),
						),
					),
				),
			),
		),
	)
);

/**
 * Register a settings form for Meta Styles.
 */
FLBuilder::register_settings_form(
	'xpro_post_grid_meta_form',
	array(
		'title' => __( 'Meta Styles', 'xpro-bb-addons' ),
		'tabs'  => array(
			'meta' => array(
				'title'    => __( 'Meta', 'xpro-bb-addons' ),
				'sections' => array(
					'style_meta' => array(
						'title'  => __( 'Meta', 'xpro-bb-addons' ),
						'fields' => array(
							'meta_typography'       => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-meta-list > li',
								),
							),
							'meta_space_between'    => array(
								'type'         => 'unit',
								'label'        => 'Space Between',
								'units'        => array( 'px', '%' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => array(
									'px' => array(
										'min'  => 0,
										'max'  => 1000,
										'step' => 1,
									),
								),
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-meta-list',
									'property' => 'grid-gap',
								),
							),
							'meta_icon_color'       => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-meta-list > li > i',
									'property' => 'color',
								),
							),
							'meta_color'            => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-meta-list > li, .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li a',
									'property' => 'color',
								),
							),
							'meta_bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-meta-list > li',
									'property' => 'background-color',
								),
							),
							'meta_border'           => array(
								'type'       => 'border',
								'label'      => 'Border',
								'units'      => array( 'px', '%' ),
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-meta-list > li',
									'property' => 'border',
								),
							),
							'meta_padding'          => array(
								'type'       => 'dimension',
								'label'      => 'Padding',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-meta-list > li',
									'property' => 'padding',
								),
							),
							'meta_margin'           => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-meta-list > li',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator3' => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Wrapper<hr></h2>',
							),
							'meta_wrapper_border'   => array(
								'type'       => 'border',
								'label'      => 'Border',
								'units'      => array( 'px', '%' ),
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-layout-7 .xpro-post-grid-meta-list',
									'property' => 'border',
								),
							),
							'meta_wrapper_padding'  => array(
								'type'       => 'dimension',
								'label'      => 'Padding',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-layout-7 .xpro-post-grid-meta-list',
									'property' => 'padding',
								),
							),
							'meta_wrapper_margin'   => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-layout-7 .xpro-post-grid-meta-list',
									'property' => 'margin',
								),
							),
						),
					),
				),
			),
		),
	)
);

/**
 * Register a settings form for Author Styles.
 */
FLBuilder::register_settings_form(
	'xpro_post_grid_author_form',
	array(
		'title' => __( 'Author Styles', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'Author', 'xpro-bb-addons' ),
				'sections' => array(
					'style_author' => array(
						'title'     => __( 'Author', 'xpro-bb-addons' ),
						'fields'    => array(
							'avatar_size'             => array(
								'type'         => 'unit',
								'label'        => 'Avatar Size',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
							),
							'author_space_between'    => array(
								'type'         => 'unit',
								'label'        => 'Space Between',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-author',
									'property' => 'grid-gap',
								),
							),
							'author_border'           => array(
								'type'       => 'border',
								'label'      => 'Border',
								'units'      => array( 'px', '%' ),
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-author',
									'property' => 'border',
								),
							),
							'author_wrapper_margin'   => array(
								'type'       => 'dimension',
								'label'      => 'Wrapper Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-wrapper .xpro-post-grid-author',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator1'   => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
							),
							'author_title_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-author-title',
								),
							),
							'author_title_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-author-title',
									'property' => 'color',
								),
							),
							'author_title_margin'     => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-author-title',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator2'   => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Name<hr></h2>',
							),
							'author_name_typography'  => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-author-name',
								),
							),
							'author_name_color'       => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-author-name',
									'property' => 'color',
								),
							),
							'author_name_margin'      => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-grid-author-name',
									'property' => 'margin',
								),
							),
						),
					),
				),
			),
		),
	)
);

/**
 * Register a settings form for Meta Styles.
 */
FLBuilder::register_settings_form(
	'xpro_post_grid_pagination_form',
	array(
		'title' => __( 'Pagination Styles', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'Pagination', 'xpro-bb-addons' ),
				'sections' => array(
					'styl_pagination' => array(
						'title'     => __( 'Pagination', 'xpro-bb-addons' ),
						'fields'    => array(
							'text-align'                  => array(
								'type'       => 'align',
								'label'      => 'Alignment',
								'default'    => 'center',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination',
									'property' => 'justify-content',
								),
							),
							'pagination_typography'       => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers',
								),
							),
							'pagination_space_between'    => array(
								'type'         => 'unit',
								'label'        => 'Space Between',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination',
									'property' => 'grid-gap',
								),
							),
							'pagination_bg_type'          => array(
								'type'    => 'button-group',
								'label'   => __( 'Background Type', 'xpro-bb-addons' ),
								'default' => 'none',
								'options' => array(
									'none'   => __( 'None', 'xpro-bb-addons' ),
									'normal' => __( 'Normal', 'xpro-bb-addons' ),
									'hover'  => __( 'Hover', 'xpro-bb-addons' ),
									'active' => __( 'Active', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'normal' => array(
										'fields' => array( 'pagination_color', 'pagination_bg_color' ),
									),
									'hover'  => array(
										'fields' => array( 'pagination_hover_color', 'pagination_bg_hover_color' ),
									),
									'active' => array(
										'fields' => array( 'pagination_active_color', 'pagination_bg_arctive_color' ),
									),
								),
							),
							'pagination_color'            => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => ' .xpro-elementor-post-pagination .page-numbers',
									'property' => 'color',
								),
							),
							'pagination_bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers',
									'property' => 'background-color',
								),
							),
							'pagination_hover_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers:hover',
									'property' => 'color',
								),
							),
							'pagination_bg_hover_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers:hover',
									'property' => 'background-color',
								),
							),
							'pagination_active_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers.current',
									'property' => 'color',
								),
							),
							'pagination_bg_arctive_color' => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers.current',
									'property' => 'background-color',
								),
							),
							'pagination_border'           => array(
								'type'       => 'border',
								'label'      => 'Border',
								'units'      => array( 'px', '%' ),
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers',
									'property' => 'border',
								),
							),
							'pagination_padding'          => array(
								'type'       => 'dimension',
								'label'      => 'Padding',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination .page-numbers',
									'property' => 'padding',
								),
							),
							'pagination_margin'           => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-elementor-post-pagination',
									'property' => 'margin',
								),
							),
						),
					),
				),
			),
		),
	)
);
