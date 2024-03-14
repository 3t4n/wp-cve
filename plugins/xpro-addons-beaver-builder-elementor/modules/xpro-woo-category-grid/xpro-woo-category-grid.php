<?php

/**
 * @class XPROWooCategoryGridModule
 */

if ( class_exists( 'WooCommerce' ) ) {

	class XPROWooCategoryGridModule extends FLBuilderModule {

		/**
		 * @method __construct
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Woo Category Grid', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$woo_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-category-grid/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-woo-category-grid/',
					'partial_refresh' => true,
				)
			);
		}

		/**
		 * @method enqueue_scripts
		 */
		public function enqueue_scripts() {

			// Register and enqueue your own.
            $this->add_css( 'cubeportfolio-css', XPRO_ADDONS_FOR_BB_URL . 'assets/css/cubeportfolio.min.css' );
            $this->add_js( 'cubeportfolio-js', XPRO_ADDONS_FOR_BB_URL . 'assets/js/jquery.cubeportfolio.min.js', array( 'jquery' ), '4.4.0', true );
		}

		public static function taxonomies_exclude() {

			$list             = array();
			$product_category = 'product_cat';
			$terms            = get_terms(
				array(
					'taxonomy'   => $product_category,
					'order'      => 'asc',
					'hide_empty' => false,
				)
			);

			foreach ( $terms as $value ) {
				$list[ $value->term_id ] = $value->name;
			}

			return $list;
		}

	}

	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module(
		'XPROWooCategoryGridModule',
		array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'content' => array(
						'title'  => __( 'General', 'xpro-bb-addons' ),
						'fields' => array(
							'layout'         => array(
								'type'    => 'select',
								'label'   => __( 'Layout', 'xpro-bb-addons' ),
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
								'toggle'  => array(
									'1'  => array(
										'fields' => array( 'item_bg_type', 'item_bg', 'item_bg_gradient', 'content_border' ),
									),
									'2'  => array(
										'fields' => array( 'content_border' ),
									),
									'3'  => array(
										'fields' => array( 'content_border' ),
									),
									'4'  => array(
										'fields' => array( 'content_border_bg' ),
									),
									'5'  => array(
										'fields' => array( 'content_border' ),
									),
									'6'  => array(
										'fields' => array( 'content_border_bg' ),
									),
									'7'  => array(
										'fields' => array( 'content_border_bg' ),
									),
									'8'  => array(
										'fields' => array( 'content_border' ),
									),
									'9'  => array(
										'fields' => array( 'content_border' ),
									),
									'10' => array(
										'fields' => array( 'content_border' ),
									),
								),
							),
							'column_grid'    => array(
								'type'       => 'select',
								'label'      => __( 'Columns', 'xpro-bb-addons' ),
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
							'show_title'     => array(
								'type'    => 'button-group',
								'label'   => __( 'Show Title', 'xpro-bb-addons' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Show', 'xpro-bb-addons' ),
									'no'  => __( 'Hide', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'yes' => array(
										'fields' => array( 'xpro-widget-seprator2', 'title_typography', 'title_color', 'title_hover_color', 'title_margin' ),
									),
								),
							),
							'show_count'     => array(
								'type'    => 'button-group',
								'label'   => __( 'Show Count', 'xpro-bb-addons' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Show', 'xpro-bb-addons' ),
									'no'  => __( 'Hide', 'xpro-bb-addons' ),
								),
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
										'fields' => array( 'content_length', 'xpro-widget-seprator3', 'description_typography', 'excerpt_color', 'excerpt_margin' ),
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
							'show_cta'       => array(
								'type'    => 'button-group',
								'label'   => __( 'Show CTA Button', 'xpro-bb-addons' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Show', 'xpro-bb-addons' ),
									'no'  => __( 'Hide', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'yes' => array(
										'fields' => array( 'btn_txt' ),
									),
								),
							),
							'btn_txt'        => array(
								'type'    => 'text',
								'label'   => __( 'Button Text', 'fl-builder' ),
								'default' => __( 'View All', 'fl-builder' ),
							),
							'clickable_div'  => array(
								'type'    => 'button-group',
								'label'   => __( 'Make full div clickable', 'xpro-bb-addons' ),
								'default' => 'no',
								'help'    => 'Make full div clickable instead of just button',
								'options' => array(
									'yes' => __( 'Yes', 'xpro-bb-addons' ),
									'no'  => __( 'No', 'xpro-bb-addons' ),
								),
							),
						),
					),
					'query'   => array(
						'title'     => __( 'Query', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'exclude'        => array(
								'type'    => 'select',
								'label'   => __( 'Exclude', 'xpro-bb-addons' ),
								'options' => XPROWooCategoryGridModule::taxonomies_exclude(),
                                'multi-select' => true,
							),
                            'orderby'        => array(
                                'type'    => 'select',
                                'label'   => __( 'Order By', 'xpro-bb-addons' ),
                                'default' => 'date',
                                'options' => array(
                                    'ID'            => __( 'Post ID', 'xpro-bb-addons' ),
                                    'author'        => __( 'Post Author', 'xpro-bb-addons' ),
                                    'title'         => __( 'Title', 'xpro-bb-addons' ),
                                    'date'          => __( 'Date', 'xpro-bb-addons' ),
                                    'modified'      => __( 'Last Modified Date', 'xpro-bb-addons' ),
                                    'parent'        => __( 'Parent ID', 'xpro-bb-addons' ),
                                    'rand'          => __( 'Random', 'xpro-bb-addons' ),
                                    'comment_count' => __( 'Comment Count', 'xpro-bb-addons' ),
                                    'menu_order'    => __( 'Menu Order', 'xpro-bb-addons' ),
                                ),
                            ),
                            'order'          => array(
                                'type'    => 'button-group',
                                'label'   => __( 'Order', 'xpro-bb-addons' ),
                                'default' => 'desc',
                                'options' => array(
                                    'asc'  => __( 'Ascending', 'xpro-bb-addons' ),
                                    'desc' => __( 'Descending', 'xpro-bb-addons' ),
                                ),
                            ),
                            'cat_only_image' => array(
                                'type'    => 'button-group',
                                'label'   => __( 'Category With Image', 'xpro-bb-addons' ),
                                'default' => 'no',
                                'options' => array(
                                    'yes' => __( 'Show', 'xpro-bb-addons' ),
                                    'no'  => __( 'Hide', 'xpro-bb-addons' ),
                                ),
                            ),
                            'term_per_page'  => array(
                                'type'    => 'unit',
                                'label'   => 'Items Per Page',
                                'default' => 6,
                            ),
                            'hide_empty'     => array(
                                'type'    => 'button-group',
                                'label'   => __( 'Hide Empty', 'xpro-bb-addons' ),
                                'default' => 'no',
                                'options' => array(
                                    'yes' => __( 'Yes', 'xpro-bb-addons' ),
                                    'no'  => __( 'No', 'xpro-bb-addons' ),
                                ),
                            ),
						),
					),
				),
			),
			'style'   => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => __( 'General', 'xpro-bb-addons' ),
						'fields' => array(
							'image_height'     => array(
								'type'         => 'unit',
								'label'        => 'Image Height',
								'units'        => array( 'px', 'vw', '%' ),
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
									'type'  => 'css',
									'rules' => array(
										array(
											'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-img',
											'property' => 'height',
										),
										array(
											'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-img',
											'property' => 'min-height',
										),
									),
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
									'property' => 'background-color',
								),
							),
							'item_bg_gradient' => array(
								'type'    => 'gradient',
								'label'   => 'Gradient Color',
								'preview' => array(
									'type'     => 'css',
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-item',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-img-section::after',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-img:hover .xpro-woo-product-img-section::after, .xpro-product-grid-wrapper .xpro-woo-product-grid-item:hover .xpro-woo-product-img-section::after',
									'property' => 'background-color',
								),
							),
						),
					),
					'content' => array(
						'title'     => __( 'Content', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'horizontal_alignment'   => array(
								'type'       => 'button-group',
								'label'      => __( 'Horizontal Alignment', 'xpro-bb-addons' ),
								'responsive' => true,
								'options'    => array(
									'none'       => __( 'None', 'xpro-bb-addons' ),
									'flex-start' => __( 'Left', 'xpro-bb-addons' ),
									'center'     => __( 'Center', 'xpro-bb-addons' ),
									'flex-end'   => __( 'Right', 'xpro-bb-addons' ),
								),
							),
							'vertical_alignment'     => array(
								'type'       => 'button-group',
								'label'      => __( 'Vertical Alignment', 'xpro-bb-addons' ),
								'responsive' => true,
								'options'    => array(
									'none'       => __( 'None', 'xpro-bb-addons' ),
									'flex-start' => __( 'Top', 'xpro-bb-addons' ),
									'center'     => __( 'Center', 'xpro-bb-addons' ),
									'flex-end'   => __( 'Bottom', 'xpro-bb-addons' ),
								),
							),
							'content_height'         => array(
								'type'         => 'unit',
								'label'        => 'Content Height',
								'units'        => array( 'px', 'vw', '%' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => array(
									'px' => array(
										'min'  => 0,
										'max'  => 1000,
										'step' => 5,
									),
								),
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
									'property' => 'height',
								),
							),
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
									'selector' => '.xpro-woo-product-grid-content-sec',
									'property' => 'background-color',
								),
							),
							'content_gradient'       => array(
								'type'    => 'gradient',
								'label'   => 'Gradient Color',
								'preview' => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-content-sec',
									'property' => 'background-image',
								),
							),
							'content_border_bg'      => array(
								'type'       => 'color',
								'label'      => __( 'Border Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'content_border'         => array(
								'type'       => 'border',
								'label'      => 'Border',
								'units'      => array( 'px', '%' ),
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator2'  => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
							),
							'title_typography'       => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title',
								),
							),
							'title_color'            => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title:hover',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-title',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator3'  => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Description<hr></h2>',
							),
							'description_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt',
								),
							),
							'excerpt_color'          => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt',
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
									'selector' => '.xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt',
									'property' => 'margin',
								),
							),
						),
					),
					'button'  => array(
						'title'     => __( 'Button', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'button_typography'    => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn',
								),
							),
							'btn_bg_type'          => array(
								'type'    => 'button-group',
								'label'   => __( 'Background Type', 'xpro-bb-addons' ),
								'default' => 'none',
								'options' => array(
									'none'   => __( 'None', 'xpro-bb-addons' ),
									'normal' => __( 'Normal', 'xpro-bb-addons' ),
									'hover'  => __( 'Hover', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'normal' => array(
										'fields' => array( 'button_color', 'button_bg' ),
									),
									'hover'  => array(
										'fields' => array( 'button_hcolor', 'button_hbg', 'button_hborder_color' ),
									),
								),
							),
							'button_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn',
									'property' => 'color',
								),
							),
							'button_bg'            => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn',
									'property' => 'background-color',
								),
							),
							'button_hcolor'        => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:hover, .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:focus',
									'property' => 'color',
								),
							),
							'button_hbg'           => array(
								'type'       => 'color',
								'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:hover, .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:focus',
									'property' => 'background-color',
								),
							),
							'button_hborder_color' => array(
								'type'       => 'color',
								'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:hover, .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:focus',
									'property' => 'border-color',
								),
							),
							'button_border'        => array(
								'type'       => 'border',
								'label'      => 'Border',
								'units'      => array( 'px', '%' ),
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn',
									'property' => 'border',
								),
							),
							'button_item_padding'  => array(
								'type'       => 'dimension',
								'label'      => 'Padding',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn',
									'property' => 'padding',
								),
							),
							'button_margin'        => array(
								'type'       => 'dimension',
								'label'      => 'Margin',
								'responsive' => true,
								'units'      => array( 'px', '%' ),
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn',
									'property' => 'margin',
								),
							),
						),
					),
				),
			),
		)
	);

} else {

	class XPROWooCategoryGridWooNotExist extends FLBuilderModule {

		/**
		 * @return void
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Woo Category Grid', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$woo_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-woo-category-grid/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-woo-category-grid/',
					'partial_refresh' => true,
				)
			);
		}
	}

	FLBuilder::register_module(
		'XPROWooCategoryGridWooNotExist',
		array(
			'general-info' => array(
				'title'       => __( 'General', 'xpro' ),
				'description' => __( 'Please Install Woocommerce Plugin to use this Module.', 'xpro' ),
			),
		)
	);

}


