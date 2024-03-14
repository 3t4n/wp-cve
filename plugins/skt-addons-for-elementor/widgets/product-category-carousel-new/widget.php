<?php
/**
 * Product Category Carousel widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;

defined( 'ABSPATH' ) || die();

class Product_Category_Carousel_New extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Product Category Carousel', 'skt-addons-elementor' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-Category-Carousel';
    }

    public function get_keywords() {
        return [ 'ecommerce', 'woocommerce', 'product', 'categroy', 'carousel', 'sale', 'skt-skin' ];
	}

	/**
     * Overriding default function to add custom html class.
     *
     * @return string
     */
    public function get_html_wrapper_class() {
        $html_class = parent::get_html_wrapper_class();
        $html_class .= ' skt-addon-pro';
        $html_class .= ' ' . str_replace('-new', '', $this->get_name());
        return $html_class;
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

        if ($cats) {
            foreach ( $cats as $cat ) {
                $cats_list[$cat->term_id] = $cat->name;
            }
        }

		return $cats_list;
	}

	/**
	 * Register content controls
	 */
    protected function register_content_controls() {

		//Layout content controls
		$this->layout_content_tab_controls();

		//Query content controls
		$this->query_content_tab_controls();

		//Carousel Settings controls
		$this->carousel_settings_content_tab_controls();

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
					'{{WRAPPER}} .skt-product-cat-carousel-thumbnail:before' => '{{VALUE}}',
				],
				'condition' => [
					'cat_image_show' => 'yes',
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Query content controls
	 */
	protected function query_content_tab_controls() {

		$this->start_controls_section(
            '_term_query',
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

		$this->add_control(
            'cat_per_page',
            [
                'label' => __( 'Number of Categories', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'max' => 1000,
            ]
		);

		$this->end_controls_section();

	}

	/**
	 * Carousel Settings controls
	 */
	protected function carousel_settings_content_tab_controls() {

		$this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Carousel Settings', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __( 'Animation Speed', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 10,
                'max' => 10000,
                'default' => 800,
                'description' => __( 'Slide speed in milliseconds', 'skt-addons-elementor' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __( 'Autoplay Speed', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'max' => 10000,
                'default' => 2000,
                'description' => __( 'Autoplay speed in milliseconds', 'skt-addons-elementor' ),
                'condition' => [
                    'autoplay' => 'yes'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Infinite Loop?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => __( 'Navigation', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'skt-addons-elementor' ),
                    'arrow' => __( 'Arrow', 'skt-addons-elementor' ),
                    'dots' => __( 'Dots', 'skt-addons-elementor' ),
                    'both' => __( 'Arrow & Dots', 'skt-addons-elementor' ),
                ],
                'default' => 'arrow',
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __( 'Slides To Show', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    1 => __( '1 Slide', 'skt-addons-elementor' ),
                    2 => __( '2 Slides', 'skt-addons-elementor' ),
                    3 => __( '3 Slides', 'skt-addons-elementor' ),
                    4 => __( '4 Slides', 'skt-addons-elementor' ),
                    5 => __( '5 Slides', 'skt-addons-elementor' ),
                    6 => __( '6 Slides', 'skt-addons-elementor' ),
                ],
                'desktop_default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'frontend_available' => true,
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();

	}

	/**
	 * Register style controls
	 */
    protected function register_style_controls() {

		//Item Box Style Start
		$this->item_box_style_tab_controls();

		//Feature Image Style Start
		$this->image_style_tab_controls();

		//Content Style Start
		$this->content_style_tab_controls();

		//Nav Arrow Style Start
		$this->nav_arrow_style_tab_controls();

		//Nav Dot Style Start
		$this->nav_dot_style_tab_controls();
	}

	/**
	 * Item Box Style controls
	 */
	protected function item_box_style_tab_controls() {

		$this->start_controls_section(
			'_section_item_box_style',
			[
				'label' => __( 'Carousel Item', 'skt-addons-elementor' ),
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
                    '{{WRAPPER}} .skt-product-cat-carousel-item' => 'height: {{SIZE}}{{UNIT}};'
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
                    '{{WRAPPER}} .skt-product-cat-carousel-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
				'name' => 'item_border',
                'selector' => '{{WRAPPER}} .skt-product-cat-carousel-item-inner',
            ]
		);

		$this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-carousel-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .skt-product-cat-carousel-item-inner'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-product-cat-carousel-item-inner',
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
                    '{{WRAPPER}} .skt-product-cat-carousel-thumbnail img' => 'width: {{SIZE}}{{UNIT}};'
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
                    '{{WRAPPER}} .skt-product-cat-carousel-thumbnail img' => 'height: {{SIZE}}{{UNIT}};'
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
                    '{{WRAPPER}} .skt-product-cat-carousel-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .skt-product-cat-carousel-thumbnail:before' => 'background-color: {{VALUE}};',
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
				'prefix_class' => 'skt-product-cat-carousel-content-align-',
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
                    '{{WRAPPER}} .skt-product-cat-carousel-content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .skt-product-cat-carousel-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-product-cat-carousel-content-inner',
            ]
        );

		$this->add_group_control(
            Group_Control_Border::get_type(),
            [
				'name' => 'content_item_border',
                'selector' => '{{WRAPPER}} .skt-product-cat-carousel-content-inner',
            ]
		);

		$this->add_responsive_control(
            'content_item_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-product-cat-carousel-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .skt-product-cat-carousel-title a',
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
                    '{{WRAPPER}} .skt-product-cat-carousel-title a' => 'color: {{VALUE}};'
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
                    '{{WRAPPER}} .skt-product-cat-carousel-title a:hover' => 'color: {{VALUE}};'
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
                    '{{WRAPPER}} .skt-product-cat-carousel-count' => 'margin-left: {{SIZE}}{{UNIT}};'
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
                    '{{WRAPPER}} .skt-product-cat-carousel-count' => 'margin-top: {{SIZE}}{{UNIT}};'
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
				'selector' => '{{WRAPPER}} .skt-product-cat-carousel-count',
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
					'{{WRAPPER}} .skt-product-cat-carousel-count' => 'color: {{VALUE}};'
                ],
                'condition' => [
					'show_cats_count' => 'yes',
                ],
            ]
        );

	}

	/**
	 * Nav Arrow style controls
	 */
	protected function nav_arrow_style_tab_controls() {

		$this->start_controls_section(
            '_section_style_arrow',
            [
                'label' => __( 'Navigation - Arrow', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_position_toggle',
            [
                'label' => __( 'Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'skt-addons-elementor' ),
                'label_on' => __( 'Custom', 'skt-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );

		$this->start_popover();

		$this->add_control(
			'arrow_sync_position',
			[
				'label' => __( 'Sync Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'yes' => [
						'title' => __( 'Yes', 'skt-addons-elementor' ),
						'icon' => 'eicon-sync',
					],
					'no' => [
						'title' => __( 'No', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-stretch',
					]
				],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'default' => 'no',
				'toggle' => false,
				'prefix_class' => 'skt-arrow-sync-'
			]
		);

		$this->add_control(
			'sync_position_alignment',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					]
				],
				'condition' => [
					'arrow_position_toggle' => 'yes',
					'arrow_sync_position' => 'yes'
				],
				'default' => 'center',
				'toggle' => false,
				'selectors_dictionary' => [
					'left' => 'left: 0',
					'center' => 'left: 50%',
					'right' => 'left: 100%',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => '{{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'arrow_position_y',
			[
				'label' => __( 'Vertical', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'top: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'arrow_position_x',
			[
				'label' => __( 'Horizontal', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-arrow-sync-no .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-arrow-sync-no .slick-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-arrow-sync-yes .slick-next, {{WRAPPER}}.skt-arrow-sync-yes .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_spacing',
			[
				'label' => __( 'Space between Arrows', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'arrow_position_toggle' => 'yes',
					'arrow_sync_position' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
                ],
                'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}}.skt-arrow-sync-yes .slick-next' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __( 'Box Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 70,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_font_size',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 2,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'selector' => '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next',
            ]
        );

        $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs( '_tabs_arrow' );

        $this->start_controls_tab(
            '_tab_arrow_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_arrow_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_border_color',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'arrow_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

	/**
	 * Nav Dot style controls
	 */
	protected function nav_dot_style_tab_controls() {

		$this->start_controls_section(
            '_section_style_dots',
            [
                'label' => __( 'Navigation - Dots', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dots_nav_position_y',
            [
                'label' => __( 'Vertical Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_spacing',
            [
                'label' => __( 'Space Between', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_nav_align',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs( '_tabs_dots' );
        $this->start_controls_tab(
            '_tab_dots_normal',
            [
                'label' => __( 'Normal', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_nav_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_hover',
            [
                'label' => __( 'Hover', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_nav_hover_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li button:hover:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_dots_active',
            [
                'label' => __( 'Active', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_nav_active_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-dots .slick-active button:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
	}

	/**
	 * Get query
	 *
	 * @return object
	 */
    public function get_query() {
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
		if ( !empty( $settings['cat_per_page'] ) && count( $product_cats ) > $settings['cat_per_page'] ) {
			$product_cats = array_splice( $product_cats, 0, $settings['cat_per_page'] );
		}

		return $product_cats;
	}



	/**
	 * render content
	 *
	 * @return void
	 */
	public function render() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			printf( '<div class="skt-cat-carousel-error">%s</div>', __( 'Please Install/Activate Woocommerce Plugin.', 'skt-addons-elementor' ) );

			return;
		}

		$settings = $this->get_settings_for_display();
		$product_cats = $this->get_query();

		if ( empty( $product_cats ) ) {
			if ( is_admin() ) {
				return printf( '<div class="skt-cat-carousel-error">%s</div>', __( 'Nothing Found. Please Add Category.', 'skt-addons-elementor' ) );
			}
		}

		$this->add_render_attribute(
			'wrapper',
			'class',
			[
				'skt-product-cat-carousel',
				'skt-product-cat-carousel-'. $settings['skin'],
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
					$has_image = esc_attr( ' skt-product-cat-carousel-has-image' );
				}
				?>
				<article class="skt-product-cat-carousel-item<?php echo esc_attr( ' ' . $has_image ); ?>">
					<div class="skt-product-cat-carousel-item-inner">
						<?php if ( $image_src && 'yes' == $settings['cat_image_show'] ) : ?>
							<div class="skt-product-cat-carousel-thumbnail">
								<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $product_cat->name ); ?>">
							</div>
						<?php endif; ?>
						<div class="skt-product-cat-carousel-content">
							<div class="skt-product-cat-carousel-content-inner">
								<<?php echo wp_kses_post(skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ).' class="skt-product-cat-carousel-title"'); ?>>
									<a href="<?php echo esc_url( get_term_link( $product_cat->term_id, 'product_cat' ) ); ?>">
										<?php echo esc_html( $product_cat->name ); ?>
									</a>
								</<?php echo wp_kses_post(skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' )); ?>>

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
									<div class="skt-product-cat-carousel-count">
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
	}
}