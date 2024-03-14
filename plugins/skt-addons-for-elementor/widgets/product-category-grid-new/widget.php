<?php
/**
 * Product Category Grid widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Product_Category_Grid_New extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Product Category Grid', 'skt-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-Category-Carousel';
	}

	public function get_keywords() {
		return [ 'product-category-grid', 'ecommerce', 'woocommerce', 'product', 'categroy', 'grid', 'sale', 'skt-skin' ];
	}

	/**
	 * Get parent category list
	 */
	protected function get_parent_cats() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$parent_categories = [ 'none' => __( 'None', 'skt-addons-elementor' ) ];

		$args = array(
			'parent' => 0
		);
		$parent_cats = get_terms( 'product_cat', $args );

		foreach ( $parent_cats as $parent_cat ) {
			$parent_categories[$parent_cat->term_id] = $parent_cat->name;
		}
		return $parent_categories;
	}

	/**
	 * Get all category list
	 */
	protected function get_all_cats_list() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$cats_list = [];

		$args = [
			'orderby'    => 'name',
			'order'      => 'DESC',
		];
		$cats = get_terms( 'product_cat', $args );

		if($cats){
			foreach ( $cats as $cat ) {
				$cats_list[$cat->term_id] = $cat->name;
			}
		}
		return $cats_list;
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {

		//Layout
		$this->layout_content_tab_controls();

		//Query content
		$this->query_content_tab_controls();

		//Load More content
		$this->load_more_content_tab_controls();

    }

	/**
	 * Layout content controls
	 */
	protected function layout_content_tab_controls() {

		$this->start_controls_section(
			'_section_layout',
			[
				'label' => __( 'Layout', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

		$this->add_control(
			'skin',
			[
				'label' => __( 'Skin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'classic' => __( 'Classic', 'skt-addons-elementor' ),
					'minimal' => __( 'Minimal', 'skt-addons-elementor' ),
				],
				'default' => 'classic',
			]
		);

        $this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'skt-pg-grid%s-',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-wrapper' => 'grid-template-columns: repeat( {{VALUE}}, 1fr );',
				],
			]
		);

		$this->add_control(
            'cat_per_page',
            [
                'label' => __( 'Category Per Page', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
				'max' => 1000,
				'default' => '3',
            ]
		);

		$this->add_control(
			'cat_image_show',
			[
				'label' => __( 'Featured Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'cat_image',
				'default' => 'thumbnail',
				'exclude' => [
					'custom'
				],
				'condition' => [
					'cat_image_show' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_cats_count',
			[
				'label' => __( 'Count Number', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'image_overlay',
			[
				'label' => __( 'Image Overlay', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
				'prefix_class' => 'skt-image-overlay-',
				'selectors_dictionary' => [
                    'yes' => 'content:\'\';',
                ],
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-thumbnail:before' => '{{VALUE}}',
				],
				'condition' => [
					'cat_image_show' => 'yes',
				]
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Query content controls
	 */
	protected function query_content_tab_controls( ) {

		$this->start_controls_section(
            '_section_term_query',
            [
                'label' => __( 'Query', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
		);

		$this->add_control(
            'query_type',
            [
                'label' => __( 'Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
				'default' => 'all',
                'options' => [
                    'all' => __( 'All', 'skt-addons-elementor' ),
                    'parents' => __( 'Only Parents', 'skt-addons-elementor' ),
                    'child' => __( 'Only Child', 'skt-addons-elementor' )
                ],
            ]
		);

		$this->start_controls_tabs( '_tabs_terms_include_exclude',
			[
				'condition' => [ 'query_type' => 'all' ]
			]
		);
		$this->start_controls_tab(
            '_tab_term_include',
            [
				'label' => __( 'Include', 'skt-addons-elementor' ),
				'condition' => [ 'query_type' => 'all' ]
            ]
		);

		$this->add_control(
			'cats_include_by_id',
			[
				'label' => __( 'Categories', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_type' => 'all'
				],
				'options' => $this->get_all_cats_list(),
			]
		);

		$this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_term_exclude',
            [
				'label' => __( 'Exclude', 'skt-addons-elementor' ),
				'condition' => [ 'query_type' => 'all' ]
            ]
		);

		$this->add_control(
			'cats_exclude_by_id',
			[
				'label' => __( 'Categories', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'condition' => [
					'query_type' => 'all'
				],
				'options' => $this->get_all_cats_list(),
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
            'parent_cats',
            [
                'label' => __( 'Child Categories of', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_parent_cats(),
				'condition' => [
					'query_type' => 'child'
				]
            ]
        );

		$this->add_control(
            'order_by',
            [
                'label' => __( 'Order By', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
				'default' => 'name',
                'options' => [
                    'name' => __( 'Name', 'skt-addons-elementor' ),
                    'count' => __( 'Count', 'skt-addons-elementor' ),
                    'slug' => __( 'Slug', 'skt-addons-elementor' )
                ],
            ]
		);

		$this->add_control(
            'order',
            [
                'label' => __( 'Order', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
				'default' => 'desc',
                'options' => [
                    'desc' => __( 'Descending', 'skt-addons-elementor' ),
                    'asc' => __( 'Ascending', 'skt-addons-elementor' ),
                ],
            ]
		);

		$this->add_control(
			'show_empty_cat',
			[
				'label' => __( 'Show Empty Categories', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
			]
		);

        $this->end_controls_section();

	}

	/**
	 * Load More content controls
	 */
	protected function load_more_content_tab_controls( ) {

		$this->start_controls_section(
			'_section_content_more',
			[
				'label' => __( 'More...', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_load_more',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Show Load More Button', 'skt-addons-elementor' ),
				'default' => '',
				'return_value' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'load_more_text',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Button Text', 'skt-addons-elementor' ),
				'default' => __( 'More category', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_load_more' => 'yes',
				]
			]
		);

		$this->add_control(
			'load_more_link',
			[
				'type' => Controls_Manager::URL,
				'label' => __( 'Button URL', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'show_load_more' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}


	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {

		//Laout Style Start
		$this->layout_style_tab_controls();

		//Box Style Start
		$this->box_style_tab_controls();

		//Feature Image Style Start
		$this->image_style_tab_controls();

		//Content Style Start
		$this->content_style_tab_controls();

		//Load More Style Start
		$this->load_more_style_tab_controls();

	}

	/**
	 * Layout Style controls
	 */
	protected function layout_style_tab_controls() {

		$this->start_controls_section(
			'_section_layout_style',
			[
				'label' => __( 'Layout', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-wrapper' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-wrapper' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
    }

	/**
	 * Box Style controls
	 */
	protected function box_style_tab_controls() {

		$this->start_controls_section(
			'_section_item_box_style',
			[
				'label' => __( 'Item Box', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
            'item_heght',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-item' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
		);

		$this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
				'name' => 'item_border',
                'selector' => '{{WRAPPER}} .skt-product-cat-grid-item-inner',
            ]
		);

		$this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .skt-product-cat-grid-item-inner'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-product-cat-grid-item-inner',
            ]
		);

		$this->end_controls_section();
	}

	/**
	 * Image Style controls
	 */
	protected function image_style_tab_controls() {

		$this->start_controls_section(
			'_section_image_style',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'cat_image_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
            'feature_image_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-thumbnail img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

		$this->add_responsive_control(
            'feature_image_height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-thumbnail img' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_image_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'image_overlay_color',
			[
				'label' => __( 'Overlay Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'image_overlay' => 'yes',
				],
				'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-thumbnail:before' => 'background-color: {{VALUE}};',
				],
			]
        );

		$this->end_controls_section();
	}

	/**
	 * Content Style controls
	 */
	protected function content_style_tab_controls() {

		$this->start_controls_section(
			'_section_content_style',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->content_area_style_tab_controls();

		$this->title_style_tab_controls();

		$this->count_style_tab_controls();

		$this->end_controls_section();

	}

	/**
	 * Content area Style controls
	 */
	protected function content_area_style_tab_controls() {

		$this->add_control(
			'content_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'prefix_class' => 'skt-product-cat-grid-content-align-',
                'condition' => [
					'skin' => 'minimal',
                ],
			]
		);

		$this->add_responsive_control(
            'content_margin',
            [
                'label' => __( 'Margin', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-product-cat-grid-content-inner',
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
				'name' => 'content_item_border',
                'selector' => '{{WRAPPER}} .skt-product-cat-grid-content-inner',
            ]
		);

		$this->add_responsive_control(
            'content_item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	}

	/**
	 * Title area Style controls
	 */
	protected function title_style_tab_controls() {

        $this->add_control(
            '_heading_title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-product-cat-grid-title a',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-title a' => 'color: {{VALUE}};'
                ],
            ]
		);

		$this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Hover Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-title a:hover' => 'color: {{VALUE}};'
                ],
            ]
        );

	}

	/**
	 * Count area Style controls
	 */
	protected function count_style_tab_controls() {

		$this->add_control(
            '_heading_count',
            [
                'label' => __( 'Count', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
					'show_cats_count' => 'yes',
                ],
            ]
		);

        $this->add_responsive_control(
            'classic_count_space',
            [
                'label' => __( 'Left Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-count' => 'margin-left: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
					'skin' => 'classic',
					'show_cats_count' => 'yes',
                ],
            ]
		);

        $this->add_responsive_control(
            'minimal_count_space',
            [
                'label' => __( 'Top Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-grid-count' => 'margin-top: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
					'skin' => 'minimal',
					'show_cats_count' => 'yes',
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-product-cat-grid-count',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
                'condition' => [
					'show_cats_count' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-count' => 'color: {{VALUE}};'
                ],
                'condition' => [
					'show_cats_count' => 'yes',
                ],
            ]
        );

	}

	/**
	 * Load More Style controls
	 */
	protected function load_more_style_tab_controls( ) {

		$this->start_controls_section(
			'_section_style_load_more_button',
			[
				'label' => __( 'Load More Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_load_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'load_more_btn_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_btn_margin_top',
			[
				'label' => __( 'Margin Top', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'load_more_btn_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'load_more_btn_typography',
				'selector' => '{{WRAPPER}} .skt-product-cat-grid-load-more-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'load_more_btn_border',
				'selector' => '{{WRAPPER}} .skt-product-cat-grid-load-more-btn',
			]
		);

		$this->add_control(
			'load_more_btn_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( '_tabs_load_more_btn_stat' );

		$this->start_controls_tab(
			'_tab_load_more_btn_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'load_more_btn_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'load_more_btn_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'load_more_btn_box_shadow',
				'selector' => '{{WRAPPER}} .skt-product-cat-grid-load-more-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_load_more_btn_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'load_more_btn_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn:hover, {{WRAPPER}} .skt-product-cat-grid-load-more-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'load_more_btn_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn:hover, {{WRAPPER}} .skt-product-cat-grid-load-more-btn:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'load_more_btn_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'btn_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn:hover, {{WRAPPER}} .skt-product-cat-grid-load-more-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'load_more_btn_hove_box_shadow',
				'selector' => '{{WRAPPER}} .skt-product-cat-grid-load-more-btn:hover, {{WRAPPER}} .skt-product-cat-grid-load-more-btn:focus',
			]
		);


		$this->add_control(
			'load_more_btn_hove_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .skt-product-cat-grid-load-more-btn:focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Get Load More button markup
	 */
	public function get_load_more_button() {
		$settings = $this->get_settings_for_display();
		if ( $settings['show_load_more'] !== 'yes' ) {
			return;
		}
		$this->add_link_attributes( 'load_more', $settings['load_more_link'] );
		$this->add_render_attribute( 'load_more', 'class', 'skt-product-cat-grid-load-more-btn' );
		?>
		<div class="skt-product-cat-grid-load-more">
			<a <?php $this->print_render_attribute_string( 'load_more' ); ?>><?php echo esc_html( $settings['load_more_text'] ); ?></a>
		</div>
		<?php
	}

	/**
	 * Get query
	 *
	 * @return object
	 */
	public function get_query( $cat_per_page ) {
		$settings = $this->get_settings_for_display();

		$args = array(
			'orderby'    => ( $settings['order_by'] ) ? $settings['order_by'] : 'name',
			'order'      => ( $settings['order'] ) ? $settings['order'] : 'ASC',
			'hide_empty' => $settings['show_empty_cat'] == 'yes' ? false : true,
		);

		if ( $settings['query_type'] == 'all' ) {
			! empty( $settings['cats_include_by_id'] ) ? $args['include'] = $settings['cats_include_by_id'] : null;
			! empty( $settings['cats_exclude_by_id'] ) ? $args['exclude'] = $settings['cats_exclude_by_id'] : null;
		} elseif ( $settings['query_type'] == 'parents' ) {
			$args['parent'] = 0;
		} elseif ( $settings['query_type'] == 'child' ) {
			if ( $settings['parent_cats'] != 'none' &&  ! empty( $settings['parent_cats'] ) ) {
				$args['child_of'] = $settings['parent_cats'];
			} elseif ( $settings['parent_cats'] == 'none' ) {
				if ( is_admin() ) {
					return printf( '<div class="skt-category-carousel-error">%s</div>', __( 'Select Parent Category from <strong>Query > Child Categories of</strong>.', 'skt-addons-elementor' ) );
				}
			}
		}

		$product_cats = get_terms( 'product_cat', $args );

		if ( !empty( $cat_per_page ) && count( $product_cats ) > $cat_per_page ) {
			$product_cats = array_splice( $product_cats, 0, $cat_per_page );
		}

		return $product_cats;
	}

	/**
	 * render content
	 */
	public function render() {
        if ( ! class_exists( 'WooCommerce' ) ) {
			printf( '<div class="skt-product-cat-grid-error">%s</div>', __( 'Please Install/Activate Woocommerce Plugin.', 'skt-addons-elementor' ) );

			return;
		}

		$settings = $this->get_settings_for_display();
		$cat_per_page = $settings['cat_per_page'];
		$product_cats = $this->get_query($cat_per_page);

		if ( empty( $product_cats ) ) {
			if ( is_admin() ) {
				return printf( '<div class="skt-product-cat-grid-error">%s</div>', __( 'Nothing Found. Please Add Category.', 'skt-addons-elementor' ) );
			}
		}

		$this->add_render_attribute(
			'wrapper',
			'class',
			[
				'skt-product-cat-grid-wrapper',
				'skt-product-cat-grid-'. $settings['skin'],
			]
		);

		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			foreach ( $product_cats as $product_cat ) :

				$image_src = Utils::get_placeholder_image_src();
				$thumbnail_id = get_term_meta( $product_cat->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_image_src( $thumbnail_id, $settings['cat_image_size'], false );

				if ( $image ) {
					$image_src = $image[0];
				}

				$has_image = '';
				if ( 'yes' == $settings['cat_image_show'] ) {
					$has_image = esc_attr( ' skt-product-cat-grid-has-image' );
				}

				?>
				<article class="skt-product-cat-grid-item<?php echo esc_attr( ' ' . $has_image ); ?>">
					<div class="skt-product-cat-grid-item-inner">

						<?php if ( $image_src && 'yes' == $settings['cat_image_show'] ) : ?>
							<div class="skt-product-cat-grid-thumbnail">
								<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $product_cat->name ); ?>">
							</div>
						<?php endif; ?>

						<div class="skt-product-cat-grid-content">
							<div class="skt-product-cat-grid-content-inner">
								<h2 class="skt-product-cat-grid-title">
									<a href="<?php echo esc_url( get_term_link( $product_cat->term_id, 'product_cat' ) ); ?>">
										<?php echo esc_html( $product_cat->name ); ?>
									</a>
								</h2>

								<?php if ( $settings['show_cats_count'] == 'yes' ) : ?>
									<?php
										$count_text = '';
										if( 'classic' == $settings['skin'] ){
											$count_text = '('. $product_cat->count . ')';
										}
										if( 'minimal' == $settings['skin'] ){
											$count_text = $product_cat->count > 1 ? $product_cat->count . __( ' Items', 'skt-addons-elementor' ) : $product_cat->count . __( ' Item', 'skt-addons-elementor' );
										}
									?>
									<div class="skt-product-cat-grid-count">
										<?php esc_html_e( $count_text ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</article>
				<?php
			endforeach;
			?>
		</div>
		<?php

		$this->get_load_more_button();
	}
}