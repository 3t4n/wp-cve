<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliBlog_Grid extends Elementinvader_Base {

    // Default widget settings
    public $defaults = array();
    public $view_folder = 'blog_grid';
    public $items_num = 0;

    public function __construct($data = array(), $args = null) {
        wp_enqueue_style('eli-main', plugins_url('/assets/css/main.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-blog';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Blog Grid', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {
      
        /* TAB_STYLE */

		$this->start_controls_section(
			'config',
			[
				'label' => __( 'Query', 'elementinvader-addons-for-elementor' ),
			]
		);

        $this->add_control(
			'is_mobile_view_enable',
			[
				'label' => __( 'Horizontal mobile view', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'True', 'elementinvader-addons-for-elementor' ),
				'label_off' => __( 'False', 'elementinvader-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

        $this->add_control(
			'is_pagination_enable',
			[
				'label' => __( 'Pagination Enable', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'True', 'elementinvader-addons-for-elementor' ),
				'label_off' => __( 'False', 'elementinvader-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'config_limit',
			[
				'label' => __( 'Limit Results(per page)', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 50,
				'step' => 1,
				'default' => 6,
			]
		);

        $this->add_control(
			'results_on',
			[
                'label'         => __('Show results based on', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::SELECT,
                'options'       => [
					'post_type'  => __('Post Type', 'elementinvader-addons-for-elementor'),
					'custom_id'    => __('ID', 'elementinvader-addons-for-elementor'),
					'on_title' => __('Titles', 'elementinvader-addons-for-elementor'),
					'on_query' => __('Query', 'elementinvader-addons-for-elementor'),
				],
				'default'       => 'post_type',
			]
		);

		$this->add_control(
			'config_limit_post_type',
			[
				'label'         => __('Post Type', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::SELECT,
				'options'       => $this->ma_el_get_post_types(),
				'default'       => 'post',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'results_on',
                            'operator' => '==',
                            'value' => 'post_type',
                        ]
                    ],
                ],
			]
		);

		$this->add_control(
			'custom_title',
			[
				'label'         => __('Based on title search posts', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXT,
				'default'       => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'results_on',
                            'operator' => '==',
                            'value' => 'on_title',
                        ]
                    ],
                ],
			]
		);

		$this->add_control(
			'custom_query',
			[
				'label'         => __('Based on query', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXTAREA,
				'default'       => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'results_on',
                            'operator' => '==',
                            'value' => 'on_query',
                        ]
                    ],
                ],
			]
		);

        if(true){
            $repeater = new Repeater();
            $repeater->start_controls_tabs( 'custom_posts' );
            $repeater->add_control(
                'post_id',
                [
                    'label' => esc_html__('Post ID', 'wpdirectorykit'),
                    'type' => Controls_Manager::NUMBER,
                ]
            );

            $repeater->end_controls_tabs();
                            
            $this->add_control(
                'custom_posts_id',
                [
                    'type' => Controls_Manager::REPEATER,
                    'label' => __('Define custom posts id', 'elementinvader-addons-for-elementor'),
                    'fields' => $repeater->get_controls(),
                    'default' => [
                    ],
                    'title_field' => '{{{ post_id }}}',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'results_on',
                                'operator' => '==',
                                'value' => 'custom_id',
                            ]
                        ],
                    ],
                ]
            );
        }
      
		$this->add_control(
			'config_limit_order',
			[
				'label'         => __('Post Order', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::SELECT,
				'label_block'   => true,
				'options'       => [
					'asc'           => __('Ascending', 'elementinvader-addons-for-elementor'),
					'desc'          => __('Descending', 'elementinvader-addons-for-elementor')
				],
				'default'       => 'desc'
			]
		);

		$this->add_control(
			'config_limit_order_by',
			[
				'label'         => __('Order By', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::SELECT,
				'label_block'   => true,
				'options'       => [
					'none'  => __('None', 'elementinvader-addons-for-elementor'),
					'ID'    => __('ID', 'elementinvader-addons-for-elementor'),
					'author' => __('Author', 'elementinvader-addons-for-elementor'),
					'title' => __('Title', 'elementinvader-addons-for-elementor'),
					'name'  => __('Name', 'elementinvader-addons-for-elementor'),
					'date'  => __('Date', 'elementinvader-addons-for-elementor'),
					'modified' => __('Last Modified', 'elementinvader-addons-for-elementor'),
					'rand'  => __('Random', 'elementinvader-addons-for-elementor'),
					'comment_count' => __('Number of Comments', 'elementinvader-addons-for-elementor'),
					'menu_order ' => __('Field Order ', 'elementinvader-addons-for-elementor'),
				],
				'default'       => 'date'
			]
		);
        
        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(__( 'Manager Posts <a href="%1$s" target="_blank"> open </a>', 'elementinvader-addons-for-elementor' ), admin_url('edit.php')),
                'content_classes' => 'eli_elementor_hint',
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();

		$this->start_controls_section(
			'config_fields',
			[
				'label' => __( 'Config fields', 'elementinvader-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'config_fields_title',
			[
				'label'         => __('Custom Title field', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXT,
				'default'       => '',
			]
		);

		$this->add_control(
			'config_fields_subtitle',
			[
				'label'         => __('Custom Sub Title field', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXT,
				'default'       => '',
			]
		);

        $this->add_control(
            'part_order_position_header',
            [
                'label' => esc_html__('Position In Cart Order', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $items = [
            [
                'key'=>'part_order_position_thumbnail',
                'label'=> esc_html__('Thumbnail', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eli_blog .eliblog-card.fixed-size .eliblog-card-thumbnail',
            ],
            [
                'key'=>'part_order_position_content',
                'label'=> esc_html__('Content', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eli_blog .eliblog-card .eliblog-card-content',
            ]
        ];
        foreach ($items as $key=>$item) {
            $this->add_responsive_control(
                $item['key'],
                [
                    'label' =>  $item['label'],
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => $key,
                    'selectors' => [
                        '{{WRAPPER}} '.$item['selector'] => 'order: {{UNIT}};',
                    ],
                ]
            );
        }

        $this->add_control(
            'content_order_position_header',
            [
                'label' => esc_html__('Position Order', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $items = [
            [
                'key'=>'content_order_position_title',
                'label'=> esc_html__('Title', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-title-box',
            ],
            [
                'key'=>'content_order_position_subtitle',
                'label'=> esc_html__('Sub Title', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-subtitle-box',
            ],
            [
                'key'=>'content_order_position_date',
                'label'=> esc_html__('Date', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-date-box',
            ],
            [
                'key'=>'content_order_position_meta',
                'label'=> esc_html__('Meta', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-meta-box',
            ],
            [
                'key'=>'content_order_position_text',
                'label'=> esc_html__('Text', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-text-box',
            ],
            [
                'key'=>'content_order_position_view_btn',
                'label'=> esc_html__('View btn', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-btn-box',
            ]
        ];
        foreach ($items as $key=>$item) {
            $this->add_responsive_control(
                $item['key'],
                [
                    'label' =>  $item['label'],
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 10,
                    'step' => 1,
                    'default' => $key,
                    'selectors' => [
                        '{{WRAPPER}} '.$item['selector'] => 'order: {{UNIT}};',
                    ],
                ]
            );
        }
     
        $this->end_controls_section();


        /* TAB_STYLE */
        $this->start_controls_section(
                'styles_grid',
                [
                    'label' => esc_html__('Grid', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
			'masonry_enable',
			[
				'label' => __( 'Masonry', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
				'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
            
        $this->add_responsive_control(
                'row_gap_col',
                [
                        'label' => __( 'Columns', 'elementinvader-addons-for-elementor' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '' => esc_html__('Default', 'elementinvader-addons-for-elementor'),
                            'auto' => esc_html__('Auto', 'elementinvader-addons-for-elementor'),
                            '100%' => '1',
                            '50%' => '2',
                            'calc(100% / 3)' => '3',
                            '25%' => '4',
                            '20%' => '5',
                            'auto_flexible' => 'auto flexible',
                        ],
                        'selectors_dictionary' => [
                            'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                            '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                            '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                            'calc(100% / 3)' =>  'width:33%;-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                            '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                            '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                            'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                            'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .eli_blog .eli_col' => '{{UNIT}}',
                        ],
                        'default' => 'calc(100% / 3)', 
                        'separator' => 'before',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'masonry_enable',
                                    'operator' => '==',
                                    'value' => '',
                                ]
                            ],
                        ],
                ]
        );
            
        $this->add_responsive_control(
                'row_gap_col_mas',
                [
                        'label' => __( 'Columns', 'elementinvader-addons-for-elementor' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                        ],
                        'selectors_dictionary' => [
                            '1' => 'columns: 1;',
                            '2' => 'columns: 2;',
                            '3' => 'columns: 3;',
                            '4' => 'columns: 4;',
                            '5' => 'columns: 5;',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .eli_row.masonry' => '{{UNIT}}',
                        ],
                        'default' => '3', 
                        'separator' => 'before',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'masonry_enable',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ]
                            ],
                        ],
                ]
        );

        $this->add_responsive_control(
                'column_gap',
                [
                    'label' => esc_html__('Columns Gap', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli_blog .eli_col' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                        '{{WRAPPER}} .eli_blog .eli_row' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'row_gap',
                [
                    'label' => esc_html__('Rows Gap', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli_blog  .eli_col' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'pagination_styles',
            [
                'label' => esc_html__('Pagination Section', 'wpdirectorykit'),
                'tab' => '1',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'is_pagination_enable',
                            'operator' => '==',
                            'value' => 'yes',
                        ]
                    ],
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_styles_align',
            [
                'label' => __( 'Align', 'wpdirectorykit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                            'title' => esc_html__( 'Left', 'wpdirectorykit' ),
                            'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                            'title' => esc_html__( 'Center', 'wpdirectorykit' ),
                            'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                            'title' => esc_html__( 'Right', 'wpdirectorykit' ),
                            'icon' => 'eicon-text-align-right',
                    ],
                ],
                'render_type' => 'ui',
                'selectors_dictionary' => [
                    'left' => 'justify-content: flex-start;',
                    'center' => 'justify-content: center;',
                    'right' => 'justify-content: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eli-pagination.pagination' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_gap',
            [
                'label' => esc_html__('Gap', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'render_type' => 'template',
                'default' => [
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eli_blog .eli-pagination .nav-links' => 'gap: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'pagination_styles_head',
                [
                    'label' => esc_html__('Pagination Links', 'wpdirectorykit'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .eli-pagination.pagination .nav-links > *',
            'hover'=>'{{WRAPPER}} .eli-pagination.pagination .nav-links > *%1$s',
            'active'=>'{{WRAPPER}} .eli-pagination.pagination .nav-links > *.current'
        );
        $this->generate_renders_tabs($selectors, 'pagination_styles_items_dynamic', ['typo','color','background','border','border_radius','padding','shadow','transition', 'width','height']);
        
        $this->end_controls_section();
        
              
        /* TAB_STYLE */

        $items = [
            [
                'key'=>'style_options_card',
                'label'=> esc_html__('Card', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_card_content',
                'label'=> esc_html__('Content Box', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-content',
                'options'=>'block',
            ],
            [
                'key'=>'style_options_card_content_items',
                'label'=> esc_html__('Content Items', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-content > div',
                'options'=>['padding'],
            ],
            [
                'key'=>'style_options_title',
                'label'=> esc_html__('Title', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-title',
                'selector_align'=>'.eliblog-card .eliblog-card-title-box',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_subtitle',
                'label'=> esc_html__('Sub Title', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-subtitle',
                'selector_align'=>'.eliblog-card .eliblog-card-subtitle-box',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_date',
                'label'=> esc_html__('Date', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-date',
                'selector_align'=>'.eliblog-card .eliblog-card-date-box',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_meta',
                'label'=> esc_html__('Meta', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eli-post-meta',
                'selector_align'=>'.eliblog-card .eliblog-card-meta-box',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_text',
                'label'=> esc_html__('Text', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-content > div.eliblog-card-text-box',
                'selector_align'=>'.eliblog-card .eliblog-card-content > div.eliblog-card-text-box',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_tabel',
                'label'=> esc_html__('Label', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eli_blog .eliblog-card .eliblog-card-thumbnail .label',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_thumbnail',
                'label'=> esc_html__('Thumbnail', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-thumbnail',
                'options'=>'full',
            ],
            [
                'key'=>'style_options_view_btn',
                'label'=> esc_html__('View btn', 'elementinvader-addons-for-elementor'),
                'selector'=>'.eliblog-card .eliblog-card-view' ,
                'selector_align'=>'.eliblog-card .eliblog-card-btn-box',
                'options'=>'full',
            ]
        ];

        foreach ($items as $item) {
            $this->start_controls_section(
                $item['key'].'_section',
                [
                    'label' => $item['label'],
                    'tab' => 'tab_layout'
                ]
            );

            $hide_selector = $item['selector'];
            if( isset($item ['selector_align'])){
                $hide_selector = $item['selector_align'];
            }
            $this->add_responsive_control(
                $item['key'].'_hide',
                    [
                            'label' => esc_html__( 'Hide Element', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::SWITCHER,
                            'none' => esc_html__( 'Hide', 'elementinvader-addons-for-elementor' ),
                            'block' => esc_html__( 'Show', 'elementinvader-addons-for-elementor' ),
                            'return_value' => 'none',
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} '.$hide_selector => 'display: {{VALUE}} !important;',
                            ],
                    ]
            );

            if($item['key'] == 'style_options_view_btn')
                $this->add_responsive_control(
                    'style_options_view_btn_text',
                    [
                        'label' => esc_html__('Button Text', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'default' => 'View',
                    ]
                );
                

            if($item['key'] == 'style_options_text')
                $this->add_responsive_control(
                    'text_limit',
                    [
                        'label' => esc_html__('Limit Words', 'elementinvader-addons-for-elementor'),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 5,
                        'max' => 100,
                        'step' => 1,
                        'default' => 14,
                    ]
                );
                
            if($item['key'] == 'style_options_thumbnail'){
                $this->add_control(
                    'thumbnail_cover',
                    [
                        'label' => __( 'Cover', 'elementinvader-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
                        'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
                        'return_value' => 'yes',
                        'default' => '',
                    ]
                );
                $this->add_control(
                    'thumbnail_fixed_size',
                    [
                        'label' => __( 'Fixed image Size', 'elementinvader-addons-for-elementor' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
                        'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                    ]
                );
                $this->add_responsive_control(
                    'thumbnail_height',
                    [
                        'label' => esc_html__('Height', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'min' => 100,
                                'max' => 1500,
                            ],
                        ],
                        'render_type' => 'template',
                        'default' => [
                            'size' => 350,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .eli_blog .eliblog-card.cover' => 'height: {{SIZE}}px',
                        ],
                        'separator' => 'after',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'thumbnail_cover',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ]
                            ],
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'thumbnail_fixed_height',
                    [
                        'label' => esc_html__('Height', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'min' => 100,
                                'max' => 1500,
                            ],
                        ],
                        'render_type' => 'template',
                        'default' => [
                            'size' => 350,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .eli_blog .eliblog-card.fixed-size .eliblog-card-thumbnail' => 'height: {{SIZE}}px',
                        ],
                        'separator' => 'after',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'thumbnail_fixed_size',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ]
                            ],
                        ]
                    ]
                );
                $this->add_responsive_control(
                    'eliblog-card_content',
                    [
                        'label' => __( 'Content Vertical Align', 'elementinvader-addons-for-elementor' ),
                        'type' => Controls_Manager::CHOOSE,
                        'options' => [
                            'top' => [
                                    'title' => esc_html__( 'Top', 'elementinvader-addons-for-elementor' ),
                                    'icon' => 'eicon-v-align-top',
                            ],
                            'center' => [
                                    'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                    'icon' => 'eicon-text-align-center',
                            ],
                            'bottom' => [
                                    'title' => esc_html__( 'Bottom', 'elementinvader-addons-for-elementor' ),
                                    'icon' => 'eicon-v-align-bottom',
                            ],
                        ],
                        'default' => 'bottom',
                        'render_type' => 'template',
                        'selectors_dictionary' => [
                            'top' => 'justify-content: flex-start;',
                            'center' => 'justify-content: center;',
                            'bottom' => 'justify-content: flex-end;',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .eli_blog .eliblog-card.cover' => '{{VALUE}};',
                        ],
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'thumbnail_cover',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ]
                            ],
                        ]
                    ]
                );

                $this->add_responsive_control(
                    'thumbnail_masc',
                    [
                            'label' => esc_html__( 'Mask Color', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .eli_blog .eliblog-card .eliblog-card-thumbnail:after'=> 'background-color: {{VALUE}};',
                            ],
                    ]
                );

            }
            if( isset($item ['selector_align'])){
                $selectors = array(
                    'normal' => '{{WRAPPER}} '.$item ['selector_align'],
                );
                $this->generate_renders_tabs($selectors, $item['key'].'_dynamic_align', ['align']);
            }

            $selectors = array(
                'normal' => '{{WRAPPER}} '.$item['selector'],
                'hover'=>'{{WRAPPER}} '.$item['selector'].'%1$s'
            );
            $this->generate_renders_tabs($selectors, $item['key'].'_dynamic', $item['options'],  ['align']);

            $this->end_controls_section();
            /* END special for some elements */
        }

        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        parent::render();

        $id_int = substr($this->get_id_int(), 0, 3);
        $settings = $this->get_settings();

        $args = array();

        global $paged;
        $allposts = array( 
            'post_type'           =>  'post',
            'orderby'      =>  $settings['config_limit_order_by'],
            'order'      =>  $settings['config_limit_order'],
            'post_type'      =>  $settings['config_limit_post_type'],
            'posts_per_page'      =>  $settings['config_limit'],
            'post_status'		  => 'publish',	
            'ignore_sticky_posts' => true,
            'paged'			      => $paged,
        );

        if(!empty($settings['results_on'])) {
            switch ($settings['results_on']) {
                case 'post_type':
                    $allposts = array( 
                        'post_type'           =>  'post',
                        'orderby'      =>  $settings['config_limit_order_by'],
                        'order'      =>  $settings['config_limit_order'],
                        'post_type'      =>  $settings['config_limit_post_type'],
                        'posts_per_page'      =>  $settings['config_limit'],
                        'post_status'		  => 'publish',	
                        'ignore_sticky_posts' => true,
                        'paged'			      => $paged
                    );
                    break;
                case 'custom_id':
                    $post__in = array();
                    foreach ($settings['custom_posts_id'] as $key => $value) {
                        if(!empty($value['post_id']))
                            $post__in[] = intval($value['post_id']);
                    }

                    $allposts = array( 
                        'post__in'  => $post__in,
                        'post_status'		  => 'publish',	
                        'ignore_sticky_posts' => true,
                    );
                    break;
                case 'on_title':
                    $allposts = array( 
                        'post_type'           =>  'post',
                        's' => $settings['custom_title'],
                        'post_status'		  => 'publish',	
                        'ignore_sticky_posts' => true,
                        'paged'			      => $paged
                    );
                    break;
                case 'on_query':
                    $allposts = $settings['custom_query'];
                    break;
            }
        }

        if(is_string($allposts)){
            $allposts .= '&paged='.$paged;
            $allposts .= '&posts_per_page='.$settings['config_limit'];
            if(isset($_GET['s']))
                $allposts .= '&s='.sanitize_text_field($_GET['s']);
            if(isset($_GET['search']))
                $allposts .= '&s='.sanitize_text_field($_GET['search']);

        }elseif((is_array($allposts))) {
            if(isset($_GET['s'])) {
                $allposts ['s'] = sanitize_text_field($_GET['s']);
            }
            if(isset($_GET['search'])) {
                $allposts ['s'] = sanitize_text_field($_GET['search']);
            }
        }

        if(isset($_GET['cat'])) {
            if(is_string($allposts)){
                $allposts .= '&category_name='.sanitize_text_field($_GET['cat']);
            }elseif((is_array($allposts))) {
                $allposts['category_name'] = sanitize_text_field($_GET['cat']);
            }
        }

        if(isset($_GET['tag'])) {
            if(is_string($allposts)){
                $allposts .= '&tag='.sanitize_text_field($_GET['tag']);
            }elseif((is_array($allposts))) {

                $allposts['tag'] = sanitize_text_field($_GET['tag']);
            }
        }


        $wp_query = new \WP_Query($allposts); 
        
        $object = ['wp_query'=>$wp_query, 'settings'=>$settings,'id_int'=>$id_int];
                
        $object['is_edit_mode'] = false;          
        if(Plugin::$instance->editor->is_edit_mode())
            $object['is_edit_mode'] = true;
      
        echo $this->view('widget_layout', $object); 
    }

	public static function ma_el_get_post_types()
	{
		$post_type_args = array(
			'public'            => true,
			'show_in_nav_menus' => true
		);

		$post_types = get_post_types($post_type_args, 'objects');
		$post_lists = array();
		foreach ($post_types as $post_type) {
			$post_lists[$post_type->name] = $post_type->labels->singular_name;
		}
		return $post_lists;
	}

	public static function set_dinamic_field($field_id = '', $field = '', $default = '')
	{
        $out = '';
        if(!empty($field) && wp_get_post_terms($field_id, $field, array('fields' => 'names')) && ( ! is_wp_error( wp_get_post_terms($field_id, $field, array('fields' => 'names')) ) ))
            $out = implode(',',wp_get_post_terms($field_id, $field, array('fields' => 'names')));
        elseif(function_exists('rwmb_meta') && !empty($field) &&  rwmb_meta( $field,[], $field_id)) {
            $out = rwmb_meta( $field,[], $field_id);
        }  
        if(empty($out))
            $out = $default;

		return $out;
	}

}
