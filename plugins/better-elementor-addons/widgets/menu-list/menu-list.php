<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.1
 */
class Better_Menu_List extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-menu-list';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Menu List', 'better_plg' );
	}

    //script depend
	public function get_script_depends() { return [ 'better-menu-list']; }


	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-blockquote';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Menu list Settings', 'better_plg' ),
			]
		);
		
		$this->add_control(
			'menu_list_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
					'3' => __( 'Style 3', 'better-el-addons' ),

				],
				'default' => '1',
			]
		);
	
		$this->add_control(
			'menu_menu_list_1',
			[
				'label' => __( 'Menu List', 'better_plg' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'menu_list_style' => '1',
				],
				'default' => [
					[
						'title_1' => 'Title',
						'price_1' => '5$',
						'description_1' => 'Description',
					],
					[
						'title_1' => 'Title',
						'price_1' => '5$',
						'description_1' => 'Description',
					],
					[
						'title_1' => 'Title',
						'price_1' => '5$',
						'description_1' => 'Description',
					],
				],
				'fields' => [
					[
						'name' => 'title_1',
						'label' => __( 'Title', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Title', 'better_plg' ),
					],
					
					[
						'name' => 'price_1',
						'label' => __( 'Price', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Price', 'better_plg' ),
					],
					[
						'name' => 'image_1',
						'label' => __( 'Client Image', 'better_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'description_1',
						'label' => __( 'Description', 'better_plg' ),
						'type' => Controls_Manager::TEXTAREA,
						'label_block' => true,
						'placeholder' => __( 'Testimonial Text..', 'better_plg' ),
					],
				],
				'title_field' => '{{ title_1 }}',
			]
		);

		$this->add_control(
			'menu_menu_list',
			[
				'label' => __( 'Menu List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
                'condition' => [
					'menu_list_style' => array('2','3'),
				],
				'default' => [
					[
						'title' => 'Menu',
                        'number' => '01',
					],
				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Title', 'better-el-addons' ),
					],
                    [
						'name' => 'number',
						'label' => __( 'Number', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Number', 'better-el-addons' ),
					],
                    [
                        'name' => 'menu_list_items',
                        'label' => __( 'Style', 'better-el-addons' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '1' => __( 'Item 1', 'better-el-addons' ),
                            '2' => __( 'Item 2', 'better-el-addons' ),
                            '3' => __( 'Item 3', 'better-el-addons' ),
                            '4' => __( 'Item 4', 'better-el-addons' ),
                            '5' => __( 'Item 5', 'better-el-addons' ),
                            '6' => __( 'Item 6', 'better-el-addons' ),
                            '7' => __( 'Item 7', 'better-el-addons' ),
                            '8' => __( 'Item 8', 'better-el-addons' ),

                        ],
                        'default' => '1',
                    ],
                    [
                        'name' => 'title1',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '1',
                        ],
                    ],
                    [
                        'name' => 'image1',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '1',
                        ],
                    ],
                    [
                        'name' => 'price1',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '1',
                        ],
                    ],
                    [
                        'name' => 'description1',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '1',
                        ],
                    ],
                    [
                        'name' => 'title2',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '2',
                        ],
                    ],
                    [
                        'name' => 'image2',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '2',
                        ],
                    ],
                    [
                        'name' => 'price2',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '2',
                        ],
                    ],
                    [
                        'name' => 'description2',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '2',
                        ],
                    ],
                    [
                        'name' => 'title3',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '3',
                        ],
                    ],
                    [
                        'name' => 'image3',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '3',
                        ],
                    ],
                    [
                        'name' => 'price3',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '3',
                        ],
                    ],
                    [
                        'name' => 'description3',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '3',
                        ],
                    ],
                    [
                        'name' => 'title4',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '4',
                        ],
                    ],
                    [
                        'name' => 'image4',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '4',
                        ],
                    ],
                    [
                        'name' => 'price4',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '4',
                        ],
                    ],
                    [
                        'name' => 'description4',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '4',
                        ],
                    ],
                    [
                        'name' => 'title5',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '5',
                        ],
                    ],
                    [
                        'name' => 'image5',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '5',
                        ],
                    ],
                    [
                        'name' => 'price5',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '5',
                        ],
                    ],
                    [
                        'name' => 'description5',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '5',
                        ],
                    ],
                    [
                        'name' => 'title6',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '6',
                        ],
                    ],
                    [
                        'name' => 'image6',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '6',
                        ],
                    ],
                    [
                        'name' => 'price6',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '6',
                        ],
                    ],
                    [
                        'name' => 'description6',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '6',
                        ],
                    ],
                    [
                        'name' => 'title7',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '7',
                        ],
                    ],
                    [
                        'name' => 'image7',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '7',
                        ],
                    ],
                    [
                        'name' => 'price7',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '7',
                        ],
                    ],
                    [
                        'name' => 'description7',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '7',
                        ],
                    ],
                    [
                        'name' => 'title8',
                        'label' => __( 'Title','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Leave it blank if you don\'t need this item',
                        'condition' => [
                            'menu_list_items' => '8',
                        ],
                    ],
                    [
                        'name' => 'image8',
                        'label' => __( 'Choose Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'dynamic' => [
                            'active' => true,
                        ],
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition'	=> [
                            'menu_list_items'	=> '8',
                        ],
                    ],
                    [
                        'name' => 'price8',
                        'label' => __( 'Price','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your price..',
                        'condition' => [
                            'menu_list_items' => '8',
                        ],
                    ],
                    [
                        'name' => 'description8',
                        'label' => __( 'Description','better-el-addons' ),
                        'type' => Controls_Manager::TEXT,
                        'label_block' => true,
                        'placeholder' => 'Insert your description..',
                        'condition' => [
                            'menu_list_items' => '8',
                        ],
                    ],
				],
				'title_field' => '{{ title }}',
			]
		);

        $this->add_responsive_control(
            'btn_text',
            [
                'label' => __( 'Button Text','better-el-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => 'Insert your Text..',
				'condition' => [
					'menu_list_style' => array('2','3'),
				],
            ]
        );

        $this->add_responsive_control(
            'btn_link',
            [
                'label' => __( 'Button Link','better-el-addons' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => 'Insert your LInk..',
				'condition' => [
					'menu_list_style' => array('2','3'),
				],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'tabs_settting',
			[
				'label' => __( 'Tabs Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'active_tab_color',
			[
				'label' => __( 'Active Tab Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-menu-list .tab-icons li.ui-tabs-active a' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'tab_color',
			[
				'label' => __( 'Tab Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-menu-list .tab-icons li a' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'tab_num_color',
			[
				'label' => __( 'Tab Number Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-menu-list .tab-icons li a span' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'block_settting',
			[
				'label' => __( 'Text Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'block_content',
			[
				'label' => __( 'Block Margin', 'canteen-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-menu-list.style-1 .menu-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_settting',
			[
				'label' => __( 'Text Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-menu-list.style-1 .menu-block .item-inner h3.list-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-menu-list .list .box .flex h6' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .better-menu-list.style-1 .menu-block .item-inner h3.list-title,{{WRAPPER}} .better-menu-list .list .box .flex h6',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'price_settings',
			[
				'label' => __( 'Price Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-menu-list.style-1 .menu-block .item-inner h3.list-price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-menu-list .list .box .flex .price h4' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .better-menu-list .list .box .flex .price h4 span' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'name_typography',
				'label'     => __( 'Name Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .better-menu-list.style-1 .menu-block .item-inner h3.list-price,{{WRAPPER}} .better-menu-list .list .box .flex .price h4',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'desc_settting',
			[
				'label' => __( 'Description Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'post_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-menu-list.style-1 .menu-block .item-inner p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-menu-list .list .box p' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'post_typography',
				'label'     => __( 'Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .better-menu-list.style-1 .menu-block .item-inner p,{{WRAPPER}} .better-menu-list .list .box p',
			]
		);
		
		$this->end_controls_section();
	
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();
        $id_int = substr( $this->get_id_int(), 0, 3 );
		
		$style = $settings['menu_list_style'];	
		require( 'styles/style'.$style.'.php' );
		  
		}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


