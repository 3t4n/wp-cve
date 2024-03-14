<?php
/**
 * Smart Post List widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Background;
use Skt_Addons_Elementor\Elementor\Traits\Lazy_Query_Builder;
use Skt_Addons_Elementor\Elementor\Controls\Lazy_Select;
use Skt_Addons_Elementor\Lazy_Query_Manager;
use Skt_Addons_Elementor\Elementor\Traits\Smart_Post_List_Markup;


defined( 'ABSPATH' ) || die();
class Smart_Post_List extends Base {

	use Lazy_Query_Builder;
	use Smart_Post_List_Markup;

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'Smart Post List', 'skt-addons-elementor' );
	}

	public function get_custom_help_url () {
		return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon () {
		return 'skti skti-post-list';
	}

	public function get_keywords () {
		return [ 'smart-post-list', 'smart', 'posts', 'post', 'post-list', 'list', 'news' ];
	}

	public function conditions ($key) {
		$condition = [
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
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__layout_content_controls();
		$this->__top_bar_content_controls();
		$this->__featured_post_content_controls();
		$this->__list_post_content_controls();
		$this->__query_content_controls();
	}

	//Layout Settings
	protected function __layout_content_controls() {

		//Layout Settings
		$this->start_controls_section(
			'_section_spl_layout',
			[
				'label' => __( 'Layout', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'column',
			[
				'label' => __( 'Column', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'col-3',
				'options' => [
					'col-1' => __('Column 1', 'skt-addons-elementor'),
					'col-2' => __('Column 2', 'skt-addons-elementor'),
					'col-3' => __('Column 3', 'skt-addons-elementor'),
				],
			]
		);

		$this->add_control(
			'top_bar_show',
			[
				'label' => __( 'Show Top Bar', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'make_featured_post',
			[
				'label' => __( 'First Post Featured', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'featured_post_column',
			[
				'label' => __( 'Featured Post Column', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'featured-col-1',
				'options' => [
					'featured-col-1' => __('Column 1', 'skt-addons-elementor'),
					'featured-col-2' => __('Column 2', 'skt-addons-elementor'),
				],
				'condition' => [
					'column!' => 'col-1',
					'make_featured_post' => 'yes',
				]
			]
		);

		$this->end_controls_section();
	}

	//Top Bar Settings
	protected function __top_bar_content_controls() {

		//Top Bar Settings
		$this->start_controls_section(
			'_section_spl_top_bar',
			[
				'label' => __( 'Top Bar', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'top_bar_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'widget_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Trending Articles',
			]
		);

		$this->add_control(
			'widget_title_tag',
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
					'widget_title!' => '',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

	}

	//Featured Post Settings
	protected function __featured_post_content_controls() {

		//Featured Post Settings
		$this->start_controls_section(
			'_section_spl_featured_post',
			[
				'label' => __( 'Featured Post', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'make_featured_post' => 'yes',
				]
			]
		);

		$this->add_control(
			'featured_post_style',
			[
				'label' => __( 'Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside-conent',
				'options' => [
					'inside-conent' => __('Content Inside', 'skt-addons-elementor'),
					'outside-conent' => __('Content Outside', 'skt-addons-elementor'),
				],
				'condition' => [
					'make_featured_post' => 'yes',
				]
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
				'condition' => [
					'make_featured_post' => 'yes'
				]
			]
		);

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
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_control(
			'featured_post_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'make_featured_post' => 'yes',
				]
			]
		);

		$this->add_control(
			'featured_post_title_tag',
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
					'make_featured_post' => 'yes',
					'featured_post_title' => 'yes'
				],
			]
		);

		$this->add_control(
			'featured_meta_active',
			[
				'type' => Controls_Manager::SELECT2,
				'label' => __( 'Active Meta', 'skt-addons-elementor' ),
				'description' => __( 'Select to show and unselect to hide', 'skt-addons-elementor' ),
				'label_block' => true,
				'multiple' => true,
				'default' => ['author', 'date', 'comments'],
				'options' => [
					'author' => __( 'Author', 'skt-addons-elementor' ),
					'date' => __( 'Date', 'skt-addons-elementor' ),
					'comments' => __( 'Comments', 'skt-addons-elementor' ),
				]
			]
		);

		$this->add_control(
            'featured_post_author_icon',
            [
                'label' => esc_html__( 'Author Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-solid',
                ],
                'condition' => [
					'make_featured_post' => 'yes',
					'featured_meta_active' => 'author',
                ],
            ]
        );

		$this->add_control(
            'featured_post_date_icon',
            [
                'label' => esc_html__( 'Date Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-calendar-alt',
                    'library' => 'fa-solid',
				],
                'condition' => [
					'make_featured_post' => 'yes',
					'featured_meta_active' => 'date',
                ],
            ]
        );

		$this->add_control(
            'featured_post_comment_icon',
            [
                'label' => esc_html__( 'Comment Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-comments',
                    'library' => 'fa-solid',
				],
                'condition' => [
					'make_featured_post' => 'yes',
					'featured_meta_active' => 'comments',
                ],
            ]
		);

		$this->add_control(
			'featured_excerpt_length',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Excerpt Length', 'skt-addons-elementor' ),
				'min' => 0,
				'default' => 15,
				'condition' => [
					'make_featured_post' => 'yes',
				]
			]
		);

		$this->add_control(
			'featured_post_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'default' => 'bottom',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'style_transfer' => true,
				'selectors_dictionary' => [
					'top' => '-webkit-box-align:start;-ms-flex-align:start;align-items:flex-start;',
					'middle' => '-webkit-box-align:center;-ms-flex-align:center;align-items:center;',
					'bottom' => '-webkit-box-align:end;-ms-flex-align:end;align-items:flex-end;',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-inside-conent' => '{{VALUE}};',
					'{{WRAPPER}} .skt-spl-featured-outside-conent' => '{{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	//List Post Settings
	protected function __list_post_content_controls() {

		//List Post Settings
		$this->start_controls_section(
			'_section_spl_list_post',
			[
				'label' => __( 'List Post', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'conditions' => $this->conditions('list_post_show'),
			]
		);

		$this->add_control(
			'list_post_title_tag',
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
			]
		);

		$this->add_control(
			'list_post_image',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
                'conditions' => [
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
                                    'name' => 'column',
                                    'operator' => 'in',
                                    'value' => ['col-1','col-2','col-3'],
                                ],
                            ],
                        ]
                    ],
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'list_post_image',
				'default' => 'thumbnail',
				'exclude' => [
					'custom'
				],
				'condition' => [
					'list_post_image' => 'yes'
				]
			]
		);

		$this->add_control(
			'list_meta_active',
			[
				'type' => Controls_Manager::SELECT2,
				'label' => __( 'Active Meta', 'skt-addons-elementor' ),
				'description' => __( 'Select to show and unselect to hide', 'skt-addons-elementor' ),
				'label_block' => true,
				'multiple' => true,
				'default' => ['author'],
				'options' => [
					'author' => __( 'Author', 'skt-addons-elementor' ),
					'date' => __( 'Date', 'skt-addons-elementor' ),
					'comments' => __( 'Comments', 'skt-addons-elementor' ),
				]
			]
		);

		$this->add_control(
            'list_post_author_icon',
            [
                'label' => esc_html__( 'Author Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-solid',
				],
                'condition' => [
					'list_meta_active' => 'author',
                ],
            ]
        );

		$this->add_control(
            'list_post_date_icon',
            [
                'label' => esc_html__( 'Date Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-calendar-alt',
                    'library' => 'fa-solid',
                ],
                'condition' => [
					'list_meta_active' => 'date',
                ],
            ]
        );

		$this->add_control(
            'list_post_comment_icon',
            [
                'label' => esc_html__( 'Comment Icon', 'skt-addons-elementor' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => false,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-comments',
                    'library' => 'fa-solid',
				],
                'condition' => [
					'list_meta_active' => 'comments',
                ],
            ]
		);

		$this->add_control(
			'list_post_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'style_transfer' => true,
				'selectors_dictionary' => [
					'top' => '-webkit-box-align:start;-ms-flex-align:start;align-items:flex-start;',
					'middle' => '-webkit-box-align:center;-ms-flex-align:center;align-items:center;',
					'bottom' => '-webkit-box-align:end;-ms-flex-align:end;align-items:flex-end;',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list' => '{{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	//Query Settings
	protected function __query_content_controls() {

		$this->start_controls_section(
			'_section_spl_query',
			[
				'label' => __( 'Query', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->register_query_controls();

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__layout_style_controls();
		$this->__top_bar_style_controls();
		$this->__top_bar_title_style_controls();
		$this->__top_bar_filter_style_controls();
		$this->__top_bar_navigation_style_controls();
		$this->__featured_post_style_controls();
		$this->__featured_post_badge_style_controls();
		$this->__list_post_style_controls();
	}

	//Layout Style
	protected function __layout_style_controls(){

		$this->start_controls_section(
			'_section_spl_layout_style',
			[
				'label' => __( 'Layout', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => [
				// 	'make_featured_post' => 'yes',
				// 	'show_badge' => 'yes',
				// ],
			]
		);

		$this->add_responsive_control(
			'spl_grid_gap',
			[
				'label' => __( 'Column Gap', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => '30',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-grid-area' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-grid-area.skt-spl-featured-post-on .skt-spl-list-wrap' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'spl_grid_row_gap',
			[
				'label' => __( 'Row Gap', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => '30',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-grid-area' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-grid-area.skt-spl-featured-post-on .skt-spl-list-wrap' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_single_grid_height',
			[
				'label' => __( 'Feature Item Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-grid-area.skt-spl-col-1.skt-spl-featured-post-on' => 'grid-template-rows: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-grid-area.skt-spl-col-2.skt-spl-featured-post-on' => 'grid-template-rows: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->conditions('feature_item_height'),
			]
		);

		$this->add_responsive_control(
			'spl_post_list_grid_height',
			[
				'label' => __( 'Post List Item Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-grid-area.skt-spl-featured-post-off' => 'grid-auto-rows: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-list-wrap' => 'grid-auto-rows: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->conditions('list_post_show'),
			]
		);

		$this->end_controls_section(); //Layout Style End
	}

	//Top Bar Style
	protected function __top_bar_style_controls() {

		//Top Bar Style
		$this->start_controls_section(
			'_section_spl_top_bar_style',
			[
				'label' => __( 'Topbar', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'top_bar_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'spl_top_bar_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-spl-header',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'spl_top_bar_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-header',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_top_bar_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-header',
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); //Top Bar Style End
	}

	//Top Bar Title Style
	protected function __top_bar_title_style_controls(){

		$this->start_controls_section(
			'_section_spl_top_bar_title_style',
			[
				'label' => __( 'Topbar Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'top_bar_show' => 'yes',
					'widget_title!' => '',
				],
			]
		);

		//Widget Title
		/* $this->add_control(
			'spl_top_bar_widget_title_heading',
			[
				'label' => __( 'Widget Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'widget_title!' => '',
				],
			]
		); */

		$this->add_responsive_control(
			'spl_top_bar_widget_title_margin_right',
			[
				'label' => __( 'Margin Right', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-widget-title' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'widget_title!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_widget_title_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-widget-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'widget_title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'spl_top_bar_widget_title_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-spl-widget-title',
				'condition' => [
					'widget_title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_top_bar_widget_title_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-widget-title',
				'condition' => [
					'widget_title!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_widget_title_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-widget-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'widget_title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_top_bar_widget_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-widget-title',
				'condition' => [
					'widget_title!' => '',
				],
			]
		);

		$this->add_control(
			'spl_top_bar_widget_title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-widget-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'widget_title!' => '',
				],
			]
		);

		$this->end_controls_section(); //Top Bar Title Style End
	}

	//Top Bar Filter Style
	protected function __top_bar_filter_style_controls(){

		$this->start_controls_section(
			'_section_spl_top_bar_filter_style',
			[
				'label' => __( 'Topbar Filter', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'top_bar_show' => 'yes',
					'category_filter' => 'yes',
				],
			]
		);

		//Inline Filter
		$this->add_control(
			'spl_top_bar_filter_heading',
			[
				'label' => __( 'Inline Filter', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'category_filter' => 'yes',
					'category_filter_style' => 'inline',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_filter_item_margin',
			[
				'label' => __( 'Item margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'category_filter' => 'yes',
					'category_filter_style' => 'inline',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_filter_item_padding',
			[
				'label' => __( 'Item Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'category_filter' => 'yes',
					'category_filter_style' => 'inline',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_top_bar_filter_item_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span',
				'condition' => [
					'category_filter' => 'yes',
					'category_filter_style' => 'inline',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_filter_item_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'category_filter' => 'yes',
					'category_filter_style' => 'inline',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_top_bar_filter_item_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span',
				'condition' => [
					'category_filter' => 'yes',
					'category_filter_style' => 'inline',
				]
			]
		);

		$this->start_controls_tabs( 'spl_top_bar_filter_tabs',
			[
				'condition' => [
					'category_filter' => 'yes',
					'category_filter_style' => 'inline',
				]
			]
		);
		$this->start_controls_tab(
			'spl_top_bar_filter_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_top_bar_filter_item_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'spl_top_bar_filter_item_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_top_bar_filter_hover_tab',
			[
				'label' => __( 'Hover/Active', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_top_bar_filter_item_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span.skt-active' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'spl_top_bar_filter_item_hover_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-filter ul.skt-spl-filter-list li span.skt-active' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Dropdown Filter
		$this->add_control(
			'spl_top_bar_select_heading',
			[
				'label' => __( 'Dropdown Filter', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_nice_select_height',
			[
				'label' => __( 'Select Box Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_nice_select_space',
			[
				'label' => __( 'Space Right', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_nice_select_padding',
			[
				'label' => __( 'Dropdown Item Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_top_bar_nice_select_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .nice-select.skt-spl-custom-select,{{WRAPPER}} .nice-select.skt-spl-custom-select .list',
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_nice_select_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_top_bar_nice_select_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .nice-select.skt-spl-custom-select span.current,
				{{WRAPPER}} .nice-select.skt-spl-custom-select .option',
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->start_controls_tabs( 'spl_top_bar_nice_select_tabs',
			[
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);
		$this->start_controls_tab(
			'spl_top_bar_nice_select_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_top_bar_nice_select_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select span.current' => 'color: {{VALUE}}',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select:after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option' => 'color: {{VALUE}}',
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_control(
			'spl_top_bar_nice_select_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .list:hover .option:not(:hover)' => 'background-color: {{VALUE}}!important',
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_top_bar_nice_select_hover_tab',
			[
				'label' => __( 'Hover/Active', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_top_bar_nice_select_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option.focus' => 'color: {{VALUE}}',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option.selected.focus' => 'color: {{VALUE}}',
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->add_control(
			'spl_top_bar_nice_select_hover_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .nice-select.skt-spl-custom-select .option.selected.focus' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'category_filter' => 'yes',
				]
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();


		$this->end_controls_section(); //Top Bar Filter Style End
	}

	//Top Bar Navigation Style
	protected function __top_bar_navigation_style_controls(){

		$this->start_controls_section(
			'_section_spl_top_bar_navigation_style',
			[
				'label' => __( 'Topbar Navigation', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'top_bar_show' => 'yes',
					'navigation_show' => 'yes',
				],
			]
		);

		//Navigation
		/* $this->add_control(
			'spl_top_bar_nav_heading',
			[
				'label' => __( 'Navigation', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation_show' => 'yes',
				]
			]
		); */

		$this->add_responsive_control(
			'spl_top_bar_nav_space',
			[
				'label' => __( 'Space Between', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button:first-child' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'navigation_show' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_nav_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_show' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_top_bar_nav_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-pagination button',
				'exclude' => ['color'],
				'condition' => [
					'navigation_show' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_nav_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_show' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'spl_top_bar_nav_font_size',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button i' => 'font-size: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'navigation_show' => 'yes',
				]
			]
		);

		$this->start_controls_tabs( 'spl_top_bar_nav_tabs',
			[
				'condition' => [
					'navigation_show' => 'yes',
				]
			]
		);
		$this->start_controls_tab(
			'spl_top_bar_nav_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_top_bar_nav_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'spl_top_bar_nav_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'spl_top_bar_nav_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button' => 'border-color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_top_bar_nav_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_top_bar_nav_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'spl_top_bar_nav_hover_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'spl_top_bar_nav_border_hover_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-pagination button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();


		$this->end_controls_section(); //Top Bar Navigation Style End
	}

	//Featured Post Style
	protected function __featured_post_style_controls(){

		//Feature Post Style
		$this->start_controls_section(
			'_section_spl_feature_post_style',
			[
				'label' => __( 'Featured Post', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'make_featured_post' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'spl_feature_post_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-spl-featured-post-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'spl_feature_post_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-featured-post-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_feature_post_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-featured-post-wrap',
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//Feature Post Image overlay color
		$this->add_control(
			'spl_feature_post_image_overlay_heading',
			[
				'label' => __( 'Image Overlay', 'skt-addons-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'featured_post_style' => 'inside-conent',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'spl_feature_post_image_overlay',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-spl-featured-inside-conent .skt-spl-featured-thumb:before',
				'condition' => [
					'featured_post_style' => 'inside-conent',
				],
			]
		);

		$this->add_control(
			'spl_feature_post_image_overlay_note',
			[
				'label' => __( 'Image Overlay Note', 'skt-addons-elementor' ),
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'This overlay color only apply when post has an image.', 'skt-addons-elementor' ),
				'content_classes' => 'elementor-control-field-description',
				'condition' => [
					'featured_post_style' => 'inside-conent',
				],
			]
		);

		//Feature Post Image
		$this->add_control(
			'spl_feature_post_image_heading',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'featured_post_style' => 'outside-conent',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_image_width',
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
					'{{WRAPPER}} .skt-spl-featured-outside-conent .skt-spl-featured-thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'featured_post_style' => 'outside-conent',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_image_margin_btm',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-outside-conent .skt-spl-featured-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'featured_post_style' => 'outside-conent',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'spl_feature_post_image_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-featured-outside-conent .skt-spl-featured-thumb img',
				'condition' => [
					'featured_post_style' => 'outside-conent',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_feature_post_image_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-featured-outside-conent .skt-spl-featured-thumb img',
				'condition' => [
					'featured_post_style' => 'outside-conent',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_image_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-outside-conent .skt-spl-featured-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'featured_post_style' => 'outside-conent',
				],
			]
		);

		//Feature Post Title
		$this->add_control(
			'spl_feature_post_title_heading',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'featured_post_title' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_title_margin_btm',
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
				'default' => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-title' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: 0;',
				],
				'condition' => [
					'featured_post_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_feature_post_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-featured-post .skt-spl-title a',
				'condition' => [
					'featured_post_title' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'spl_feature_post_title_tabs',
			[
				'condition' => [
					'featured_post_title' => 'yes',
				],
			]
		);
		$this->start_controls_tab(
			'spl_feature_post_title_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_feature_post_title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_feature_post_title_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_feature_post_title_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Feature Post Meta
		$this->add_control(
			'spl_feature_post_meta_heading',
			[
				'label' => __( 'Meta', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => $this->conditions('feature_meta_style'),
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_meta_icon_size',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta .skt-spl-meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->conditions('feature_meta_style'),
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_meta_icon_space',
			[
				'label' => __( 'Icon Space', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta ul li i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta ul li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->conditions('feature_meta_style'),
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_meta_space',
			[
				'label' => __( 'Space Between', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta ul li' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta ul li:last-child' => 'margin-right: 0;',
				],
				'conditions' => $this->conditions('feature_meta_style'),
			]
		);

		$this->add_responsive_control(
			'spl_feature_post_meta_margin_btm',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta ul li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->conditions('feature_meta_style'),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_feature_post_meta_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta .skt-spl-meta-text',
				'conditions' => $this->conditions('feature_meta_style'),
			]
		);

		$this->start_controls_tabs( 'spl_feature_post_meta_tabs',
			[
				'conditions' => $this->conditions('feature_meta_style'),
			]
		);
		$this->start_controls_tab(
			'spl_feature_post_meta_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_feature_post_meta_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta .skt-spl-meta-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta .skt-spl-meta-icon path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta .skt-spl-meta-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_feature_post_meta_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_feature_post_meta_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta a:hover .skt-spl-meta-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta a:hover .skt-spl-meta-icon path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-meta a:hover .skt-spl-meta-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Feature Post Content
		$this->add_control(
			'spl_feature_post_content_heading',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'featured_excerpt_length!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_feature_post_content_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-featured-post .skt-spl-desc',
				'condition' => [
					'featured_excerpt_length!' => '',
				],
			]
		);

		$this->add_control(
			'spl_feature_post_content_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-desc' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-desc p' => 'margin-bottom: 0',
				],
				'condition' => [
					'featured_excerpt_length!' => '',
				],
			]
		);

		$this->end_controls_section(); //Feature Post Style End
	}

	//Featured Post Badge Style
	protected function __featured_post_badge_style_controls(){

		//Taxonomy Badge
		$this->start_controls_section(
			'_section_spl_featured_post_badge_style',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'make_featured_post' => 'yes',
					'show_badge' => 'yes',
				],
			]
		);

		//Taxonomy Badge
		/* $this->add_control(
			'spl_feature_badge_heading',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		); */

		$this->add_responsive_control(
			'spl_feature_badge_margin_btm',
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
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_badge_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_feature_badge_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'exclude' => [
					'color'
				],
				'selector' => '{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a',
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_feature_badge_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_feature_badge_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a',
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'spl_feature_badge_tabs',
			[
				'condition' => [
					'show_badge' => 'yes',
				],
			]
		);
		$this->start_controls_tab(
			'spl_feature_badge_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_feature_badge_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'spl_feature_badge_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a',
			]
		);

		$this->add_control(
			'spl_feature_badge_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_feature_badge_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_feature_badge_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'spl_feature_badge_hover_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [
					'image'
				],
				'selector' => '{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a:hover',
			]
		);

		$this->add_control(
			'spl_feature_badge_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-featured-post .skt-spl-badge a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section(); //Featured Post Badge Style End
	}

	//List Post Style
	protected function __list_post_style_controls(){

		//List Post Style
		$this->start_controls_section(
			'_section_spl_list_post_style',
			[
				'label' => __( 'List Post', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => $this->conditions('list_post_show'),
			]
		);

		$this->add_responsive_control(
			'spl_list_post_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'spl_list_post_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-spl-list',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'spl_list_post_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-list',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_list_post_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-list',
			]
		);

		$this->add_responsive_control(
			'spl_list_post_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//List Post Image
		$this->add_control(
			'spl_list_post_image_heading',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'list_post_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_list_post_image_width',
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
					'{{WRAPPER}} .skt-spl-list .skt-spl-list-thumb' => 'max-width: {{SIZE}}{{UNIT}};-webkit-box-flex: 0;-webkit-flex: 0 0 {{SIZE}}{{UNIT}};-ms-flex: 0 0 {{SIZE}}{{UNIT}};flex: 0 0 {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'list_post_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_list_post_image_margin_right',
			[
				'label' => __( 'Margin Right', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-list-thumb' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'list_post_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'spl_list_post_image_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-list .skt-spl-list-thumb img',
				'condition' => [
					'list_post_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'spl_list_post_image_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-spl-list .skt-spl-list-thumb img',
				'condition' => [
					'list_post_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'spl_list_post_image_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-list-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'list_post_image' => 'yes',
				],
			]
		);

		//List Post Title
		$this->add_control(
			'spl_list_post_title_heading',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'spl_list_post_title_margin_btm',
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
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_list_post_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-list .skt-spl-list-title a',
			]
		);

		$this->start_controls_tabs( 'spl_list_post_title_tabs');
		$this->start_controls_tab(
			'spl_list_post_title_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_list_post_title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-list-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_list_post_title_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_list_post_title_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-list-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		//List Post Meta
		$this->add_control(
			'spl_list_post_meta_heading',
			[
				'label' => __( 'Meta', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => $this->conditions('list_post_meta_style'),
			]
		);

		$this->add_responsive_control(
			'spl_list_post_meta_icon_size',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta .skt-spl-meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->conditions('list_post_meta_style'),
			]
		);

		$this->add_responsive_control(
			'spl_list_post_meta_icon_space',
			[
				'label' => __( 'Icon Space', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta ul li i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta ul li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->conditions('list_post_meta_style'),
			]
		);

		$this->add_responsive_control(
			'spl_list_post_meta_space',
			[
				'label' => __( 'Space Between', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta ul li' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta ul li:last-child' => 'margin-right: 0;',
				],
				'conditions' => $this->conditions('list_post_meta_style'),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'spl_list_post_meta_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-spl-list .skt-spl-meta .skt-spl-meta-text',
				'conditions' => $this->conditions('list_post_meta_style'),
			]
		);

		$this->start_controls_tabs( 'spl_list_post_meta_tabs',
			[
				'conditions' => $this->conditions('list_post_meta_style'),
			]
		);
		$this->start_controls_tab(
			'spl_list_post_meta_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_list_post_meta_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta .skt-spl-meta-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta .skt-spl-meta-icon path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta .skt-spl-meta-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'spl_list_post_meta_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'spl_list_post_meta_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta a:hover .skt-spl-meta-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta a:hover .skt-spl-meta-icon path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .skt-spl-list .skt-spl-meta a:hover .skt-spl-meta-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section(); //List Post Style End
	}


	/**
	 * get column class
	 */
	public function get_column_cls ($column) {

		switch( $column ){
			case "col-1":
				$column_cls = "skt-spl-col-1";
				break;
			case "col-2":
				$column_cls = "skt-spl-col-2";
				break;
			case "col-3":
				$column_cls = "skt-spl-col-3";
				break;
			default:
			  	$column_cls = "skt-spl-col-3";
		}

		return $column_cls;

	}

	/**
	 * get featured column class
	 */
	public function get_featured_column_cls ($column) {

		switch( $column ){
			case "featured-col-1":
				$column_cls = "skt-spl-featured-col-1";
				break;
			case "featured-col-2":
				$column_cls = "skt-spl-featured-col-2";
				break;
			default:
			  	$column_cls = "skt-spl-featured-col-1";
		}

		return $column_cls;

	}

	/**
	 * get item per page
	 */
	public function get_item_per_page ($item = 'per_page') {
		$settings = $this->get_settings_for_display();

		$per_page = -1;
		$list_column = '';

		if( 'col-1' === $settings['column'] ) {
			if( 'yes' === $settings['make_featured_post'] ){
				$per_page = 1;
			}else{
				$per_page = 4;
				$list_column = 'skt-spl-list-col-1';
			}
		}

		if( 'col-2' === $settings['column'] ) {
			if( 'yes' === $settings['make_featured_post'] && 'featured-col-1' === $settings['featured_post_column'] ){
				$per_page = 5;
				$list_column = 'skt-spl-list-col-1';
			}elseif( 'yes' === $settings['make_featured_post'] && 'featured-col-2' === $settings['featured_post_column'] ){
				$per_page = 1;
			}else{
				$per_page = 8;
				$list_column = 'skt-spl-list-col-2';
			}
		}

		if( 'col-3' === $settings['column'] ) {
			if( 'yes' === $settings['make_featured_post'] && 'featured-col-1' === $settings['featured_post_column'] ){
				$per_page = 9;
				$list_column = 'skt-spl-list-col-2';
			}elseif( 'yes' === $settings['make_featured_post'] && 'featured-col-2' === $settings['featured_post_column'] ){
				$per_page = 5;
				$list_column = 'skt-spl-list-col-1';
			}else{
				$per_page = 12;
				$list_column = 'skt-spl-list-col-3';
			}
		}

		if( $item === 'list_column' ){
			return $list_column;
		}else{
			return $per_page;
		}

	}

	/**
	 * render header markup
	 */
	public function render_header_markup ($header = 'yes') {
		if ( 'yes' != $header ) {
			return;
		}
		$settings = $this->get_settings_for_display();

		$post_taxonomies = get_object_taxonomies(  $settings['posts_post_type'] );
		if( !empty( $settings['filter_terms_ids'] ) ){
			$categories = get_terms( [
				'taxonomy' => $post_taxonomies,
				'term_taxonomy_id' => $settings['filter_terms_ids'],
				// 'include' => $settings['filter_terms_ids'],
				'hide_empty' => false,
				// 'orderby' => 'include',
			] );
		}else{
			$categories = get_terms( [
				'taxonomy' => $post_taxonomies,
				'hide_empty' => false,
			] );
		}

		?>
			<!-- header -->
			<div class="skt-spl-header">
				<?php
				if (  $settings['widget_title'] ) {
					printf( '<%1$s %2$s>%3$s</%1$s>',
						skt_addons_elementor_escape_tags( $settings['widget_title_tag'] ),
						'class="skt-spl-widget-title"',
						esc_html( $settings['widget_title'] )
					);
				}
				?>
			</div>
			<!-- /header -->
		<?php
	}

	protected function render () {

		$settings = $this->get_settings_for_display();
		if ( ! $settings['posts_post_type'] ) {
			return;
		}

		$column = $this->get_column_cls( $settings['column'] );
		$per_page = $this->get_item_per_page('per_page');
		$list_column = $this->get_item_per_page('list_column');
		$featured_post_column = $this->get_featured_column_cls( $settings['featured_post_column'] );

		$this->add_render_attribute( 'wrapper', 'class', [ 'skt-spl-wrapper' ] );

		$this->add_render_attribute(
			'grid_wrap',
			[
				'class' => [
					'skt-spl-grid-area',
					esc_attr( $column ),
					'yes' === $settings['make_featured_post'] ? 'skt-spl-featured-post-on' : 'skt-spl-featured-post-off'
				],
			]
		);

		$args = $this->get_query_args();

		$args['posts_per_page'] = -1;
		$posts = get_posts( $args );


		if( 'yes' === $settings['top_bar_show'] ){
			$query_settings = [
				'args' => $args,
				'posts_post_type' => $settings['posts_post_type'],
				'per_page' => $per_page,
				'column' => $settings['column'],
				'make_featured_post' => $settings['make_featured_post'],
				'featured_post_column' => $featured_post_column,
				'featured_post_style' => $settings['featured_post_style'],
				'featured_image' => $settings['featured_image_size'],
				// 'featured_post_cat' => $settings['featured_post_cat'],
				'show_badge' => $settings['show_badge'],
				'taxonomy_badge' => $settings['taxonomy_badge'],
				'featured_post_title' => $settings['featured_post_title'],
				'featured_post_title_tag' => $settings['featured_post_title_tag'],
				'featured_meta_active' => $settings['featured_meta_active'],

				'featured_post_author_icon' => $settings['featured_post_author_icon'],
				'featured_post_date_icon' => $settings['featured_post_date_icon'],
				'featured_post_comment_icon' => $settings['featured_post_comment_icon'],
				'featured_excerpt_length' => $settings['featured_excerpt_length'],

				'list_column' => $list_column,
				'list_post_image' => $settings['list_post_image'],
				'list_post_image_size' => $settings['list_post_image_size'],
				'list_post_title_tag' => $settings['list_post_title_tag'],
				'list_meta_active' => $settings['list_meta_active'],

				'list_post_author_icon' => $settings['list_post_author_icon'],
				'list_post_date_icon' => $settings['list_post_date_icon'],
				'list_post_comment_icon' => $settings['list_post_comment_icon'],
			];
			$query_settings = json_encode( $query_settings, true );

			$this->add_render_attribute( 'wrapper', 'data-settings', $query_settings );
			$this->add_render_attribute( 'wrapper', 'data-total-offset', '0' );
			$this->add_render_attribute( 'wrapper', 'data-offset', $per_page );
		}
		$class_array = [];
		if( 'yes' === $settings['make_featured_post']) {
			$class_array['featured'] = 'skt-spl-column skt-spl-featured-post-wrap '.esc_attr( $featured_post_column );
			$class_array['featured_inner'] = 'skt-spl-featured-post '.'skt-spl-featured-'.esc_attr($settings['featured_post_style']);
		}

		$loop = 1;
		if ( count( $posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

				<?php $this->render_header_markup( $settings['top_bar_show'] );?>

				<div <?php $this->print_render_attribute_string( 'grid_wrap' ); ?>>

					<?php self::render_spl_markup( $settings, $posts, $class_array, $list_column, $per_page ); ?>

				</div>
			</div>
		<?php
		else:
			printf( '%1$s %2$s %3$s',
				__( 'No ', 'skt-addons-elementor' ),
				esc_html( $settings['posts_post_type'] ),
				__( 'Found', 'skt-addons-elementor' )
			);
		endif;
	}
}