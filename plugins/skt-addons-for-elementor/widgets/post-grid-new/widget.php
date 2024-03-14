<?php
/**
 * Post Grid widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use Skt_Addons_Elementor\Elementor\Traits\Lazy_Query_Builder;
use Skt_Addons_Elementor\Elementor\Traits\Post_Grid_Markup_New;
use WP_Query;

defined( 'ABSPATH' ) || die();

class Post_Grid_New extends Base {

	use Lazy_Query_Builder;
	use Post_Grid_Markup_New;

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Post Grid', 'skt-addons-elementor' );
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
		return 'skti skti-post-grid';
	}

	public function get_keywords() {
		return ['post', 'posts', 'portfolio', 'grid', 'tiles', 'query', 'blog', 'skt-skin'];
	}

	public function conditions ($key) {
		$condition = [
			'read_more_content' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' =>  'skin',
						'operator' => '==',
						'value' => 'classic',
					],
					[
						'name' =>  'skin',
						'operator' => '==',
						'value' => 'hawai',
					],
					[
						'name' =>  'skin',
						'operator' => '==',
						'value' => 'standard',
					],
				],
			],
			'read_more_new_tab_content' => [
				'terms' => [
					[
						'relation' => 'or',
						'terms' => [
							[
								'name' =>  'skin',
								'operator' => '==',
								'value' => 'classic',
							],
							[
								'name' =>  'skin',
								'operator' => '==',
								'value' => 'hawai',
							],
							[
								'name' =>  'skin',
								'operator' => '==',
								'value' => 'standard',
							],
						],
					],
					[
						'terms' => [
							[
								'name' => 'read_more',
								'operator' => '!=',
								'value' => '',
							],
						],
					]
				]
			],
			'list_post_show' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => 'make_featured_post',
						'operator' => '!==',
						'value' => 'yes',
					],
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'make_featured_post',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'featured_post_column',
								'operator' => '!==',
								'value' => 'featured-col-2',
							],
							[
								'name' => 'column',
								'operator' => 'in',
								'value' => ['col-2'],
							],
						],
					],
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'make_featured_post',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'column',
								'operator' => 'in',
								'value' => ['col-3'],
							],
						],
					],
				],
			],
			'list_post_meta_old_style' => [
				'terms' => [
					[
						'name' => 'list_meta_active[0]',
						'operator' => 'in',
						'value' =>['author','date','comments'],
					],
				],
			],
			'feature_meta_old_style' => [
				'terms' => [
					[
						'name' => 'featured_meta_active[0]',
						'operator' => 'in',
						'value' => ['author','date','comments'],
					],
				],
			],
			'list_post_meta_style' => [
				'terms' => [
					[
						'name' => 'list_meta_active',
						'operator' => '!=',
						'value' => '',
					],
				],
			],
			'feature_meta_style' => [
				'terms' => [
					[
						'name' => 'featured_meta_active',
						'operator' => '!=',
						'value' => '',
					],
				],
			],
			'feature_item_height' => [
				'relation' => 'or',
				'terms' => [
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'make_featured_post',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'featured_post_style',
								'operator' => '===',
								'value' => 'inside-conent',
							],
							[
								'name' => 'column',
								'operator' => 'in',
								'value' => ['col-1'],
							],
						],
					],
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'make_featured_post',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'featured_post_column',
								'operator' => '===',
								'value' => 'featured-col-2',
							],
							[
								'name' => 'featured_post_style',
								'operator' => '===',
								'value' => 'inside-conent',
							],
							[
								'name' => 'column',
								'operator' => 'in',
								'value' => ['col-2'],
							],
						],
					],
				],
			],
		];

		return $condition[$key];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {

		//Layout
		$this->layout_content_tab_controls();

		//Query content
		$this->query_content_tab_controls();
    }

	/**
	 * Layout content controls
	 */
	protected function layout_content_tab_controls( ) {

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
					'hawai' => __( 'Hawai', 'skt-addons-elementor' ),
					'standard' => __( 'Standard', 'skt-addons-elementor' ),
					'monastic' => __( 'Monastic', 'skt-addons-elementor' ),
					'stylica' => __( 'Stylica', 'skt-addons-elementor' ),
					'outbox' => __( 'Outbox', 'skt-addons-elementor' ),
					'crossroad' => __( 'Crossroad', 'skt-addons-elementor' ),
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
					'{{WRAPPER}} .skt-pg-grid-wrap' => 'grid-template-columns: repeat( {{VALUE}}, 1fr );',
				],
			]
		);

        $this->add_control(
            'posts_per_page',
            [
                'label'   => __( 'Posts Per Page', 'skt-addons-elementor' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
            ]
        );

		$this->featured_image_controls();

		$this->badge_controls();

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => [
					'h1' => __( 'H1', 'skt-addons-elementor' ),
					'h2' => __( 'H2', 'skt-addons-elementor' ),
					'h3' => __( 'H3', 'skt-addons-elementor' ),
					'h4' => __( 'H4', 'skt-addons-elementor' ),
					'h5' => __( 'H5', 'skt-addons-elementor' ),
					'h6' => __( 'H6', 'skt-addons-elementor' ),
					'div' => __( 'DIV', 'skt-addons-elementor' ),
				],
				'condition' => [
					// $this->get_control_id( 'show_title' ) => 'yes',
					'show_title' => 'yes',
				],
			]
		);

		$this->meta_controls();

		$this->add_control(
			'excerpt_length',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __( 'Excerpt Length', 'skt-addons-elementor' ),
				'description' => __( 'Leave it blank to hide it.', 'skt-addons-elementor' ),
				'separator'   => 'before',
				'min'         => 0,
				'default'     => 15,
			]
		);

		$this->readmore_controls();

		$this->end_controls_section();
	}

	/**
	 * Featured Image Control
	 */
	protected function featured_image_controls() {

		$this->add_control(
			'featured_image',
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
				'name' => 'featured_image',
				'default' => 'thumbnail',
				'exclude' => [
					'custom'
				],
				'default' => 'large',
				'condition' => [
					// $this->get_control_id( 'featured_image' ) => 'yes',
					'featured_image' => 'yes',
				]
			]
		);

	}

	/**
	 * Badge Control
	 */
	protected function badge_controls() {

		$this->add_control(
			'show_badge',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'taxonomy_badge',
			[
				'label' => __( 'Badge Taxonomy', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'category',
				'options' => skt_addons_elementor_pro_get_taxonomies(),
				'condition' => [
					// $this->get_control_id( 'show_badge' ) => 'yes',
					'show_badge' => 'yes',
				],
			]
		);

	}

	/**
	 * Meta Control
	 */
	protected function meta_controls() {

		$this->add_control(
			'active_meta',
			[
				'type' => Controls_Manager::SELECT2,
				'label' => __( 'Active Meta', 'skt-addons-elementor' ),
				'description' => __( 'Select to show and unselect to hide', 'skt-addons-elementor' ),
				'label_block' => true,
				'separator' => 'before',
				'multiple' => true,
				'default' => ['author', 'date'],
				'options' => [
					'author' => __( 'Author', 'skt-addons-elementor' ),
					'date' => __( 'Date', 'skt-addons-elementor' ),
					'comments' => __( 'Comments', 'skt-addons-elementor' ),
				]
			]
		);

		$this->add_control(
			'meta_has_icon',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Enable Icon', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					// $this->get_control_id( 'active_meta!' ) => [],
					'active_meta!' => [],
				],
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'type'      => Controls_Manager::TEXT,
				'label'     => __( 'Separator', 'skt-addons-elementor' ),
				'selectors' => [
					'{{WRAPPER}} .skt-pg-meta-wrap ul li + li:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					// $this->get_control_id( 'active_meta!' ) => []
					'active_meta!' => []
				],
			]
		);

		$this->add_control(
			'meta_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'label_block' => false,
				'multiple' => true,
				'default' => 'after',
				'options' => [
					'before' => __( 'Before Title', 'skt-addons-elementor' ),
					'after' => __( 'After Title', 'skt-addons-elementor' ),
				],
				'condition' => [
					// $this->get_control_id( 'active_meta!' ) => [],
					'skin' => 'standard',
					'active_meta!' => []
				],
			]
		);

	}



	/**
	 * Readmore Control
	 */
	protected function readmore_controls() {
		$this->add_control(
			'read_more',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Read More', 'skt-addons-elementor' ),
				'placeholder' => __( 'Read More Text', 'skt-addons-elementor' ),
				'description' => __( 'Leave it blank to hide it.', 'skt-addons-elementor' ),
				'default' => __( 'Continue Reading »', 'skt-addons-elementor' ),
				'conditions' => $this->conditions('read_more_content'),
			]
		);

		$this->add_control(
			'read_more_new_tab',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Open in new window', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'conditions' => $this->conditions('read_more_new_tab_content'),
			]
		);


	}

	/**
	 * Query content controls
	 */
	protected function query_content_tab_controls( ) {

		//Query
		$this->start_controls_section(
			'_section_query',
			[
				'label' => __( 'Query', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->register_query_controls();

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

		//Divider Shape Style Start (only for stylica skin)
		$this->devider_shape_style_controls();

		//Badge Taxonomy Style Start
		$this->taxonomy_badge_style_tab_controls();

		//Content Style Start
		$this->content_style_tab_controls();

		//Meta Style Start
		$this->meta_style_tab_controls();

		//Readmore Style Start
		$this->readmore_style_tab_controls();
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
					'{{WRAPPER}} .skt-pg-grid-wrap' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .skt-pg-grid-wrap' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'alignment',
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
					'{{WRAPPER}} .skt-pg-item' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'skin' => 'hawai'
				]
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
			'item_box_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_box_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-pg-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-pg-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_box_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-pg-item',
			]
		);

		$this->add_responsive_control(
			'item_box_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Image Style controls
	 */
	protected function image_style_tab_controls() {

		//Feature Post Image overlay color

		$this->start_controls_section(
			'_section_image_style',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					// $this->get_control_id( 'featured_image' ) => 'yes',
					'featured_image' => 'yes',
				],
			]
		);

		$this->all_style_of_feature_image();

		$this->end_controls_section();
	}

	/**
	 * All Image Style
	 */
	protected function all_style_of_feature_image() {

		$this->image_overlay_style();

		$this->image_height_margin_style();

		$this->image_boxshadow_style();

		$this->image_border_styles();

		$this->image_border_radius_styles();

		$this->image_css_filter_styles();

		// Add avater bg color (only for outbox skin)
		$this->add_control(
			'avatar_bg',
			[
				'label' => __( 'View', 'skt-addons-elementor' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'avater_bg',
				'selectors' => [
					'{{WRAPPER}} .skt-pg-outbox .skt-pg-item .skt-pg-avatar svg' => 'fill: {{outbox_item_box_background_color.VALUE}};',
				],
				'condition' => [
					'skin' => 'outbox',
				]
			]
		);
	}

	/**
	 * Image Overlay Style
	 */
	protected function image_overlay_style() {

		//Feature Post Image overlay color
		$this->add_control(
			'feature_image_overlay_heading',
			[
				'label' => __( 'Image Overlay', 'skt-addons-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'skin' => 'classic',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'feature_image_overlay',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-pg-classic .skt-pg-thumb:before',
				'condition' => [
					'skin' => 'classic',
				],
			]
		);

		$this->add_control(
			'feature_image_heading',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'skin' => 'classic',
				],
			]
		);

	}

	/**
	 * Image Height & margin Style
	 */
	protected function image_height_margin_style() {

		$this->add_responsive_control(
			'feature_image_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-hawai .skt-pg-thumb-area .skt-pg-thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => 'hawai'
				]
			]
		);

		$this->add_responsive_control(
			'feature_image_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-thumb-area' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-hawai .skt-pg-thumb-area .skt-pg-thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'feature_image_margin_btm',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-thumb-area' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .skt-pg-hawai .skt-pg-thumb-area' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => ['classic','hawai','standard','monastic','stylica','outbox'],
				]
			]
		);

		// image margin bottom (only for crossroad skin)
		$this->add_responsive_control(
			'crossroad_feature_image_margin_btm',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => -20,
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-thumb-area' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => 'crossroad',
				]
			]
		);

	}

	/**
	 * Image boxshadow Style
	 */
	protected function image_boxshadow_style() {

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'feature_image_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '
					{{WRAPPER}} .skt-pg-classic .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-hawai .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-standard .skt-pg-thumb-area,
					{{WRAPPER}} .skt-pg-monastic .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-stylica .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-outbox .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-crossroad .skt-pg-thumb-area .skt-pg-thumb
				',
				'condition' => [
					'skin' => ['classic','hawai','standard','monastic'],
				]
			]
		);
	}

	/**
	 * Image border Style
	 */
	protected function image_border_styles() {

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'feature_image_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				// 'selector' => '{{WRAPPER}} .skt-pg-thumb-area .skt-pg-thumb',
				'selector' => '
					{{WRAPPER}} .skt-pg-classic .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-hawai .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-standard .skt-pg-thumb-area,
					{{WRAPPER}} .skt-pg-monastic .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-stylica .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-outbox .skt-pg-thumb-area .skt-pg-thumb,
					{{WRAPPER}} .skt-pg-crossroad .skt-pg-thumb-area .skt-pg-thumb
				',
				'condition' => [
					'skin' => ['classic','hawai','standard','monastic'],
				]
			]
		);

	}

	/**
	 * Image border radius Style
	 */
	protected function image_border_radius_styles() {

		$this->add_responsive_control(
			'feature_image_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-classic .skt-pg-thumb-area .skt-pg-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-hawai .skt-pg-thumb-area .skt-pg-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-standard .skt-pg-thumb-area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-monastic .skt-pg-thumb-area .skt-pg-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-stylica .skt-pg-thumb-area .skt-pg-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-outbox .skt-pg-thumb-area .skt-pg-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-crossroad .skt-pg-thumb-area .skt-pg-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	}

	/**
	 * Image css filter Style
	 */
	protected function image_css_filter_styles() {

		$this->start_controls_tabs( 'feature_image_tabs',
			[
				'condition' => [
					'skin!' => 'standard',
				],
			]
	    );
		$this->start_controls_tab(
			'feature_image_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'feature_image_css_filters',
                'selector' => '{{WRAPPER}} .skt-pg-thumb-area .skt-pg-thumb img',
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'feature_image_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'feature_image_hover_css_filters',
                'selector' => '{{WRAPPER}} .skt-pg-thumb-area .skt-pg-thumb:hover img',
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	/**
	 * Taxonomy Badge Style controls
	 */
	protected function taxonomy_badge_style_tab_controls() {

		$this->start_controls_section(
			'_section_taxonomy_badge_style',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					// $this->get_control_id( 'show_badge' ) => 'yes',
					'show_badge' => 'yes',
				],
			]
		);

		$this->taxonomy_badge_position();

		$this->add_responsive_control(
			'badge_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'skin',
							'operator' => '!=',
							'value' => 'classic',
						],
						[
							'name' => 'skin',
							'operator' => '!=',
							'value' => 'outbox',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-badge a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'badge_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'exclude' => [
					'color'
				],
				'selector' => '{{WRAPPER}} .skt-pg-item .skt-pg-badge a',
			]
		);

		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-badge a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
				'selector' => '{{WRAPPER}} .skt-pg-item .skt-pg-badge a',
			]
		);

		$this->start_controls_tabs( 'badge_tabs');
		$this->start_controls_tab(
			'badge_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-badge a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'badge_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-pg-item .skt-pg-badge a',
			]
		);

		$this->add_control(
			'badge_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-badge a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'badge_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'badge_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-badge a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'badge_hover_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-pg-item .skt-pg-badge a:hover',
			]
		);

		$this->add_control(
			'badge_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-badge a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Taxonomy badge Position
	 */
	protected function taxonomy_badge_position() {

        $this->add_control(
			'badge_position_toggle',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'skt-addons-elementor' ),
				'label_on' => __( 'Custom', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'skin',
							'operator' => '==',
							'value' => 'classic',
						],
						[
							'name' => 'skin',
							'operator' => '==',
							'value' => 'outbox',
						],
					],
				],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_position_x',
			[
				'label' => __( 'Position Left', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'condition' => [
					// $this->get_control_id( 'badge_position_toggle' ) => 'yes',
					'badge_position_toggle' => 'yes',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'em' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-thumb-area .skt-pg-badge' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_position_y',
			[
				'label' => __( 'Position Top', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					// $this->get_control_id( 'badge_position_toggle' ) => 'yes',
					'badge_position_toggle' => 'yes',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'em' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-item .skt-pg-thumb-area .skt-pg-badge' => 'top: {{SIZE}}{{UNIT}};bottom:auto;',
				],
			]
		);
		$this->end_popover();

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

		$this->add_responsive_control(
			'content_area_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-content-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'skin' => 'crossroad',
				]
			]
		);

		//Content area
		$this->add_responsive_control(
			'content_area_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_area_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-pg-content-area',
				'condition' => [
					'skin' => 'crossroad',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_area_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-pg-content-area',
				'condition' => [
					'skin' => 'crossroad',
				]
			]
		);

		$this->add_responsive_control(
			'content_area_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-content-area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'skin' => 'crossroad',
				]
			]
		);

		//Post Title
		$this->add_control(
			'post_title_heading',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					// $this->get_control_id( 'show_title' ) => 'yes',
					'show_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'post_title_margin_btm',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				// 'default' => [
				// 	'unit' => 'px',
				// 	'size' => '10',
				// ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-title' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: 0;',
				],
				'condition' => [
					// $this->get_control_id( 'show_title' ) => 'yes',
					'show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
				'selector' => '{{WRAPPER}} .skt-pg-title a',
				'condition' => [
					// $this->get_control_id( 'show_title' ) => 'yes',
					'show_title' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'post_title_tabs',
			[
				'condition' => [
					// $this->get_control_id( 'show_title' ) => 'yes',
					'show_title' => 'yes',
				],
			]
		);
		$this->start_controls_tab(
			'post_title_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_title_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'post_title_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Feature Post Content
		$this->add_control(
			'post_content_heading',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					// $this->get_control_id( 'excerpt_length!' ) => '',
					'excerpt_length!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'post_content_margin_btm',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				// 'default' => [
				// 	'unit' => 'px',
				// 	'size' => '10',
				// ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-excerpt > p' => 'margin-bottom: 0;',
				],
				'condition' => [
					// $this->get_control_id( 'excerpt_length!' ) => '',
					'excerpt_length!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_content_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
				'selector' => '{{WRAPPER}} .skt-pg-excerpt',
				'condition' => [
					// $this->get_control_id( 'excerpt_length!' ) => '',
					'excerpt_length!' => '',
				],
			]
		);

		$this->add_control(
			'post_content_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-excerpt' => 'color: {{VALUE}}',
				],
				'condition' => [
					// $this->get_control_id( 'excerpt_length!' ) => '',
					'excerpt_length!' => '',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Meta Style controls
	 */
	protected function meta_style_tab_controls() {

		$this->start_controls_section(
			'_section_meta_style',
			[
				'label' => __( 'Meta', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					// $this->get_control_id( 'active_meta!' ) => []
					'active_meta!' => []
				],
			]
		);

		//Post Meta
		$this->add_control(
			'meta_heading',
			[
				'label' => __( 'Meta', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'meta_icon_space',
			[
				'label' => __( 'Icon Space', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-meta-wrap ul li i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-meta-wrap ul li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_space',
			[
				'label' => __( 'Space Between', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-meta-wrap ul li' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-pg-meta-wrap ul li:last-child' => 'margin-right: 0;',
					'{{WRAPPER}} .skt-pg-meta-wrap ul li + li:before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_margin_btm',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-meta-wrap ul li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'meta_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-pg-meta-wrap',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'skin',
							'operator' => '==',
							'value' => 'classic',
						],
						[
							'name' => 'skin',
							'operator' => '==',
							'value' => 'outbox',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
				'selector' => '{{WRAPPER}} .skt-pg-meta-wrap ul li a,{{WRAPPER}} .skt-pg-meta-wrap ul li + li:before',
			]
		);

		$this->start_controls_tabs( 'meta_tabs');
		$this->start_controls_tab(
			'meta_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-meta-wrap ul li a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-pg-meta-wrap ul li a i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-pg-meta-wrap ul li a path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'meta_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'meta_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-meta-wrap ul li a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-pg-meta-wrap ul li a:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-pg-meta-wrap ul li a:hover path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'meta_separator_color',
			[
				'label' => __( 'Separator Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .skt-pg-meta-wrap ul li + li:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					// $this->get_control_id( 'meta_separator!' ) => '',
					'meta_separator!' => '',
				],
			]
		);

		$this->end_controls_section();
	}


	/**
	 * Added Read More Style controls
	 */
	protected function readmore_style_tab_controls() {

		$this->start_controls_section(
			'_section_readmore_style',
			[
				'label' => __( 'Read More', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => $this->conditions('read_more_content'),
				// 'condition' => [
				// 	$this->get_control_id( 'read_more!' ) => '',
				// ],
			]
		);

		$this->add_control(
			'readmore_overlay_heading',
			[
				'label' => __( 'Overlay', 'skt-addons-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'skin' => 'standard',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'readmore_overlay',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-pg-standard .skt-pg-item .skt-pg-readmore::before',
				'condition' => [
					'skin' => 'standard',
				],
			]
		);

		//Read More style
		$this->add_control(
			'readmore_heading',
			[
				'label' => __( 'Read More', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'skin' => 'standard',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'skin!' => 'standard',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-readmore a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				// 'condition' => [
				// 	$this->get_control_id( 'read_more!' ) => '',
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'readmore_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'exclude' => [
					'color',
				],
				'selector' => '{{WRAPPER}} .skt-pg-readmore a',
				// 'condition' => [
				// 	$this->get_control_id( 'read_more!' ) => '',
				// ],
			]
		);

		$this->add_responsive_control(
			'readmore_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pg-readmore a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				// 'condition' => [
				// 	$this->get_control_id( 'read_more!' ) => '',
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
				'selector' => '{{WRAPPER}} .skt-pg-readmore a',
				// 'condition' => [
				// 	$this->get_control_id( 'read_more!' ) => '',
				// ],
			]
		);

		$this->start_controls_tabs( 'readmore_tabs');
		// 	[
		// 		'condition' => [
		// 			$this->get_control_id( 'read_more!' ) => '',
		// 		],
		// 	]
		// );
		$this->start_controls_tab(
			'readmore_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-readmore a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'readmore_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-pg-readmore a',
			]
		);

		$this->add_control(
			'readmore_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-readmore a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'readmore_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'readmore_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-readmore a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'readmore_hover_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-pg-readmore a:hover',
			]
		);

		$this->add_control(
			'readmore_border_hover_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pg-readmore a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Added Divider Shape Style Control
	 */
	public function devider_shape_style_controls() {

		$this->start_controls_section(
			'_section_image_devider_shape_style',
			[
				'label' => __( 'Divider Shape', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'skin' => 'stylica',
					// $this->get_control_id( 'featured_image' ) => 'yes',
					'featured_image' => 'yes',
					// $this->get_control_id( 'devider_shape!' ) => 'none',
				],
			]
		);

		$this->add_control(
			'devider_shape',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Type', 'skt-addons-elementor' ),
				'label_block' => false,
				'multiple' => true,
				'default' => 'clouds',
				'options' => [
					'none'     => __( 'None', 'skt-addons-elementor' ),
					'clouds'     => __( 'Clouds', 'skt-addons-elementor' ),
					'corner'     => __( 'Corner', 'skt-addons-elementor' ),
					'cross-line' => __( 'Cross Line', 'skt-addons-elementor' ),
					'curve'      => __( 'Curve', 'skt-addons-elementor' ),
					'drops'      => __( 'Drops', 'skt-addons-elementor' ),
					'mountains'  => __( 'Mountains', 'skt-addons-elementor' ),
					'pyramids'   => __( 'Pyramids', 'skt-addons-elementor' ),
					'splash'     => __( 'Splash', 'skt-addons-elementor' ),
					'split'      => __( 'Split', 'skt-addons-elementor' ),
					'tilt'       => __( 'Tilt', 'skt-addons-elementor' ),
					'torn-paper' => __( 'Torn Paper', 'skt-addons-elementor' ),
					'triangle'   => __( 'Triangle', 'skt-addons-elementor' ),
					'wave'       => __( 'Wave', 'skt-addons-elementor' ),
					'zigzag'     => __( 'Zigzag', 'skt-addons-elementor' ),
				],
				// 'condition' => [
				// 	$this->get_control_id( 'featured_image' ) => 'yes',
				// ],
			]
		);

		$this->add_control(
			'devider_shape_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				// 'condition' => [
				// 	$this->get_control_id( 'featured_image' ) => 'yes',
				// 	$this->get_control_id( 'devider_shape!' ) => 'none',
				// ],
				'selectors' => [
					"{{WRAPPER}} .skt-pg-stylica .skt-pg-item .skt-pg-thumb-area svg" => 'fill: {{UNIT}};',
				],
				'condition' => [
					'devider_shape!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'devider_shape_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 100,
						'max' => 500,
					],
				],
				// 'condition' => [
				// 	$this->get_control_id( 'featured_image' ) => 'yes',
				// 	$this->get_control_id( 'devider_shape!' ) => 'none',
				// ],
				'selectors' => [
					"{{WRAPPER}} .skt-pg-stylica .skt-pg-item .skt-pg-thumb-area svg" => 'width: calc({{SIZE}}{{UNIT}} + 1.3px)',
				],
				'condition' => [
					'devider_shape!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'devider_shape_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'default' => [
					'size' => 90,
				],
				// 'condition' => [
				// 	$this->get_control_id( 'featured_image' ) => 'yes',
				// 	$this->get_control_id( 'devider_shape!' ) => 'none',
				// ],
				'selectors' => [
					"{{WRAPPER}} .skt-pg-stylica .skt-pg-item .skt-pg-thumb-area svg" => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'devider_shape!' => 'none',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Get Query
	 *
	 * @param array $args
	 * @return void
	 */
	public function get_query( $args = array() ) {

		$default = $this->get_post_query_args();
		$args = array_merge( $default, $args );

		$this->query = new WP_Query( $args );
		return $this->query;
	}

	/**
	 * Get post query arguments
	 *
	 * @return function
	 */
	public function get_post_query_args() {

		return $this->get_query_args();
	}

	/**
	 * Get page number link
	 *
	 * @param [init] $i
	 * @return string
	 */
	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post = get_post();
		$query_args = [];
		$url = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ] ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( 'page'.$i, 'single_paged' ); // Change Occurs For Fixing Pagination Issue.
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id'] = sanitize_text_field(wp_unslash( $_GET['preview_id'] ));
				$query_args['preview_nonce'] = sanitize_text_field(wp_unslash( $_GET['preview_nonce'] ));
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	/**
	 * Get post navigation link
	 *
	 * @param [init] $page_limit
	 * @return string
	 */
	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			// return;
			$page_limit = $this->query->max_num_pages; // Change Occurs For Fixing Pagination Issue.
		}

		$return = [];

		// $paged = $this->get_current_page();
		$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

		$link_template = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $this->get_settings_for_display( 'pagination_prev_label' ) );
		}
		// else {
		// 	$return['prev'] = sprintf( $disabled_template, 'prev', $this->get_settings_for_display( 'pagination_prev_label' ) );
		// }

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings_for_display( 'pagination_next_label' ) );
		}
		// else {
		// 	$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings_for_display( 'pagination_next_label' ) );
		// }

		return $return;
	}

	/**
	 * Render content
	 */
	public function render() {

		$settings = $this->get_settings_for_display();

		// return;
		$this->add_render_attribute(
			'grid-wrapper',
			'class',
			[
				'skt-pg-wrapper',
				'skt-pg-default',
				'skt-pg-'. $settings['skin'],
			]
		);
		// $args = $this->get_query_args();
		$args = $this->get_post_query_args();

		$args['posts_per_page'] = $settings['posts_per_page'];

		$_query = new WP_Query( $args );

		// get skin settings values
		//$button_custom_attr = $this->get_instance_value( 'button_custom_attr' );
		//$button_custom_attr_value = $this->get_instance_value( 'button_custom_attr_value' );

		$query_settings = $this->query_settings( $settings, $args );

		?>
		<?php if ( $_query->have_posts() ) : ?>
				<div <?php $this->print_render_attribute_string( 'grid-wrapper' ); ?>>
					<div class="skt-pg-grid-wrap">
					<?php while ( $_query->have_posts() ) : $_query->the_post(); ?>

						<?php $this->render_markup( $settings, $_query );?>

					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
					</div>
				</div>
			<?php endif;?>
		<?php
	}

	public function render_markup( $settings, $_query ) {

		$this->{'new_render_' . $settings['skin'] . '_markup'}( $settings, $_query );

		// if( 'classic' == $settings['skin'] ){
		// 	return self::render_classic_markup( $settings, $_query );
		// }
		// elseif( 'hawai' == $settings['skin'] ){
		// 	return self::render_hawai_markup( $settings, $_query );
		// }
		// elseif( 'standard' == $settings['skin'] ){
		// 	return self::render_standard_markup( $settings, $_query );
		// }
		// elseif( 'monastic' == $settings['skin'] ){
		// 	return self::render_monastic_markup( $settings, $_query );
		// }
		// elseif( 'stylica' == $settings['skin'] ){
		// 	return self::render_stylica_markup( $settings, $_query );
		// }
		// elseif( 'outbox' == $settings['skin'] ){
		// 	return self::render_outbox_markup( $settings, $_query );
		// }
		// elseif( 'crossroad' == $settings['skin'] ){
		// 	return self::render_crossroad_markup( $settings, $_query );
		// }

	}

	public function query_settings( $settings, $args ) {

		$query_settings = [
			'args'                => $args,
			// '_skin'            => $this->get_id(),
			'skin'               => $settings['skin'],
			'posts_post_type'     => $settings['posts_post_type'],
			'featured_image'      => $settings['featured_image'],
			'featured_image_size' => $settings['featured_image_size'],
			'show_badge'          => $settings['show_badge'],
			'show_title'          => $settings['show_title'],
			'title_tag'           => $settings['title_tag'],
			'active_meta'         => $settings['active_meta'],
			'excerpt_length'      => $settings['excerpt_length'],
		];

		if( !empty($settings['active_meta']) ){
			$query_settings ['meta_has_icon'] = $settings['meta_has_icon'];
		}

		if( !empty($settings['show_badge'] ) ){
			$query_settings ['taxonomy_badge'] = $settings['taxonomy_badge'];
		}

		if( 'classic' == $settings['skin'] || 'hawai' == $settings['skin'] || 'standard' == $settings['skin']){
			$query_settings ['read_more'] = $settings['read_more'];
			$query_settings ['read_more_new_tab'] = $settings['read_more_new_tab'];
		}

		if( 'standard' == $settings['skin'] ){
			$query_settings ['meta_position'] = $settings['meta_position'];
		}

		if( 'stylica' == $settings['skin'] ){
			$query_settings ['devider_shape'] = $settings['devider_shape'];
		}

		$query_settings = json_encode( $query_settings, true );

		return $query_settings;
	}
}