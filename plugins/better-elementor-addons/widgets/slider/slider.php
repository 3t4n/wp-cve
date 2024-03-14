<?php
namespace BetterWidgets\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  


		
/**
 * @since 1.0.1
 */
class Better_Slider extends Widget_Base {

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
		return 'better-slider';
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
		return __( 'Better Slider', 'better_plg' );
	}

	//script depend
	public function get_script_depends() { return [ 'swiper','better-elementor','better-lib','better-slider','better-el-addons']; }

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
		return 'eicon-slideshow';
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
				'label' => __( 'Slides', 'better-el-addons' ),
			]
		);
		
		$this->add_control(
			'better_slider_style',
			[
				'label' => __( 'Type', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'0' => __( 'Custom', 'better-el-addons' ),
					'1' => __( 'Preset 1', 'better-el-addons' ),
					'2' => __( 'Preset 2', 'better-el-addons' ),
					'3' => __( 'Preset 3', 'better-el-addons' ),
					'4' => __( 'Preset 4', 'better-el-addons' ),
					'5' => __( 'Preset 5', 'better-el-addons' ),
					'6' => __( 'Preset 6', 'better-el-addons' ),
					'7' => __( 'Preset 7', 'better-el-addons' ),
					'8' => __( 'Preset 8', 'better-el-addons' ),
					'9' => __( 'Preset 9', 'better-el-addons' ),
					'10' => __( 'Preset 10', 'better-el-addons' ),
					'11' => __( 'Preset 11', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);
		
		$this->add_control(
			'slider_list',
			[
				'label' => __( 'Slider List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_slider_style' => array('1','2','5','7','8','9','11')
				],
				'default' => [
					[
						'title' => __( 'Slider Heading Title', 'better-el-addons' ),
						'subtitle' => __( 'Slider subtitle', 'better-el-addons' ),
						'text' => __( 'Slider text', 'better-el-addons' ),
					],
					[
						'title' => __( 'Slider Heading Title', 'better-el-addons' ),
						'subtitle' => __( 'Slider subtitle', 'better-el-addons' ),
						'text' => __( 'Slider text', 'better-el-addons' ),
					],
					[
						'title' => __( 'Slider Heading Title', 'better-el-addons' ),
						'subtitle' => __( 'Slider subtitle', 'better-el-addons' ),
						'text' => __( 'Slider text', 'better-el-addons' ),
					],
				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Slider Heading Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Insert your slider heading title here..', 'better-el-addons' ),
						'default' => __( 'Slider Heading Title' ,  'better-el-addons'  ),
					],
                    [
						'name' => 'title_html_tag',
                        'label' => __( 'HTML Tag', 'geekfolio_plg' ),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            'h1' => __( 'H1', 'geekfolio_plg' ),
                            'h2' => __( 'H2', 'geekfolio_plg' ),
                            'h3' => __( 'H3', 'geekfolio_plg' ),
                            'h4' => __( 'H4', 'geekfolio_plg' ),
                            'h5' => __( 'H5', 'geekfolio_plg' ),
                            'h6' => __( 'H6', 'geekfolio_plg' ),
                            'div' => __( 'div', 'geekfolio_plg' ),
                            'span' => __( 'span', 'geekfolio_plg' ),
                            'p' => __( 'P', 'geekfolio_plg' ),
                        ],
                        'default' => 'h1',
                    ],
					[
						'name' => 'subtitle',
						'label' => __( 'Slider Subtitle', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Insert your slider subtitle here..', 'better-el-addons' ),
						'default' => __( 'Slider Subtitle' ,  'better-el-addons'  ),
					],
					[
						'name' => 'text',
						'label' => __( 'Slider Text (style 1 & 7)', 'better-el-addons' ),
						'type' => Controls_Manager::TEXTAREA,
						'label_block' => true,
						'default' => __( 'Slider Text' ,  'better-el-addons' ),
					],
					[
						'name' => 'btn_text',
						'label' => __( 'Button Text', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
					[
						'name' => 'btn_link',
						'label' => __( 'Button Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'better-el-addons' ),
					],

					[
						'name' => 'image',
						'label' => __( 'Slider Image', 'better-el-addons' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],

				],
				'title_field' => '{{{ title }}}',
			]
		);



		$this->add_control(
			'better_slider4_title',
			[
				'label' => __( 'Title', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'art & illustration' ,  'better-el-addons' ),
				'condition' => [
					'better_slider_style' => array('3','6')
				],
			]
		);
		
		$this->add_control(
			'better_slider4_subtitle',
			[
				'label' => __( 'Sub-Title', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Inspiring new space.' ,  'better-el-addons' ),
				'condition' => [
					'better_slider_style' => array('3')
				],
			]
        );

		$this->add_control(
            'bg_image',
            [
                'label' => __( 'BG Image', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
				'condition' => [
					'better_slider_style' => array('1','3','4','6','10')
				],
            ]
		);

		$this->add_control(
            'logo_image',
            [
                'label' => __( 'Logo Image', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
				'condition' => [
					'better_slider_style' => array('8')
				],
            ]
		);
		
		$this->add_control(
			'better_slider4_list',
			[
				'label' => __( 'Slider List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_slider_style' => array('3')
				],
				'default' => [
					[
						'better_slider4_list_title' => __( 'Title', 'better-el-addons' ),
						'better_slider4_list_content' => __( 'Content', 'better-el-addons' ),
					],
					[
						'better_slider4_list_title' => __( 'Title', 'better-el-addons' ),
						'better_slider4_list_content' => __( 'Content', 'better-el-addons' ),
					],
					[
						'better_slider4_list_title' => __( 'Title', 'better-el-addons' ),
						'better_slider4_list_content' => __( 'Content', 'better-el-addons' ),
					],
					[
						'better_slider4_list_title' => __( 'Title', 'better-el-addons' ),
						'better_slider4_list_content' => __( 'Content', 'better-el-addons' ),
					],
				],
				'fields' => [
					[
						'name' => 'better_slider4_list_title',
						'label' => __( 'Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Insert your slider heading title here..', 'better-el-addons' ),
						'default' => __( 'Slider Heading Title' ,  'better-el-addons'  ),
					],
					[
						'name' => 'better_slider4_list_content',
						'label' => __( 'Content', 'better-el-addons' ),
						'type' => Controls_Manager::WYSIWYG,
						'label_block' => true,
						'placeholder' => __( 'Insert your slider subtitle here..', 'better-el-addons' ),
						'default' => __( 'Slider Subtitle' ,  'better-el-addons'  ),
					],

				],
				'title_field' => '{{{ better_slider4_list_title }}}',
			]
		);


		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style8_social_list',
			[
				'label' => __( 'Social Links Settings', 'better-el-addons' ),
				'condition' => [
					'better_slider_style' => array('7','8')
				],
			]
		);

		$this->add_control(
			'style8_social_links_list',
			[
				'label' => __( 'Social Links List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'style8_social_btn_icon' => __( 'fab fa-facebook-f', 'better-el-addons' ),
						'style8_social_btn_link' => __( '#0', 'better-el-addons' ),
					],
					[
						'style8_social_btn_icon' => __( 'fab fa-facebook-f', 'better-el-addons' ),
						'style8_social_btn_link' => __( '#0', 'better-el-addons' ),
					],
					[
						'style8_social_btn_icon' => __( 'fab fa-facebook-f', 'better-el-addons' ),
						'style8_social_btn_link' => __( '#0', 'better-el-addons' ),
					],
				],
				'fields' => [
					[
						'name' => 'style8_social_btn_icon',
						'label' => esc_html__( 'Social Icon', 'better-el-addons' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'fab fa-facebook-f',
							'library' => 'fa-brand',
						],
					],
					[
						'name' => 'style8_social_btn_link',
						'label' => esc_html__( 'Social link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => __('Social link'),
					],
				],
				'title_field' => '{{{ name }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_list',
			[
				'label' => __( 'Social Links Settings', 'better-el-addons' ),
				'condition' => [
					'better_slider_style' => array('2')
				],
			]
		);

		$this->add_control(
			'social_links_list',
			[
				'label' => __( 'Social Links List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'social_btn_title' => __( 'Tw', 'better-el-addons' ),
					],
					[
						'social_btn_title' => __( 'Fb', 'better-el-addons' ),
					],
					[
						'social_btn_title' => __( 'Be', 'better-el-addons' ),
					],
				],
				'fields' => [
					[
						'name' => 'social_btn_title',
						'label' => __( 'Button Text', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
					[
						'name' => 'social_btn_link',
						'label' => __( 'Button Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
					],

				],
				'title_field' => '{{{ name }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider5_srttings',
			[
				'label' => __( 'Settings', 'better-el-addons' ),
				'condition' => [
					'better_slider_style' => array('4','10')
				],
			]
		);

		$this->add_control(
            'slider5_title1',
            [
                'label' => __( 'Slider Heading Title 1', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Insert your slider heading title here..', 'better-el-addons' ),
				'default' => __( 'Slider Heading Title 1' ,  'better-el-addons'  ),
				'condition' => [
					'better_slider_style' => array('4','10')
				],
            ]
        );

		$this->add_control(
            'slider5_title2',
            [
                'label' => __( 'Slider Heading Title 2', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Insert your slider heading title here..', 'better-el-addons'),
				'default' => __( 'Slider Heading Title 2',  'better-el-addons'),
				'condition' => [
					'better_slider_style' => '4'
				],
            ]
        );

		$this->add_control(
            'slider5_text',
            [
                'label' => __( 'Slider Text', 'better-el-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => __( 'Insert your slider text here..', 'better-el-addons' ),
				'default' => __( 'Slider Text' ,  'better-el-addons'  ),
				'condition' => [
					'better_slider_style' => array('4','10')
				],
            ]
        );

		$this->add_control(
            'slider5_btn_text',
            [
                'label' => __( 'Slider Button Text', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Insert your button text here..', 'better-el-addons' ),
				'default' => __( 'Read More' ,  'better-el-addons'  ),
				'condition' => [
					'better_slider_style' => array('4','10')
				],
            ]
        );

		$this->add_control(
            'slider5_btn_link',
            [
                'label' => __( 'Button Link', 'better-el-addons' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'Leave it blank if you don\'t need this button', 'better-el-addons' ),
				'condition' => [
					'better_slider_style' => array('4','10')
				],
			]
        );

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-slider.style-10 .caption:after, {{WRAPPER}} .better-slider.style-10 .caption .bord:after, {{WRAPPER}} .better-slider.style-10 .caption .bord:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .better-slider.style-10 .caption .bord:after, {{WRAPPER}} .better-slider.style-10 .caption .bord:before' => 'background: {{VALUE}};',
				],
				'condition' => [
					'better_slider_style' => array('10')
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_slider_options',
			[
				'label' => __( 'Slider Options', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label' => __( 'Show Arrows','better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'visible' => __( 'Show','better-el-addons' ),
					'hidden' => __( 'Hide','better-el-addons' ),
				],
				'default' => 'visible',
				'condition' => [
					'better_slider_style' => array('5','8','9','11')
				],
				'selectors' => [
					'{{WRAPPER}} .better-slider.style-2 .slick-arrow' => 'visibility: {{VALUE}};', 
					'{{WRAPPER}} .swiper-nav-ctrl' => 'visibility: {{VALUE}};',
					'{{WRAPPER}} .setwo' => 'visibility: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'show_dots',
			[
				'label' => __( 'Show Dots','better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'visible' => __( 'Show','better-el-addons' ),
					'hidden' => __( 'Hide','better-el-addons' ),
				],
				'default' => 'visible',
				'selectors' => [
					'{{WRAPPER}} .better-slider.style-2 .slick-dots' => 'visibility: {{VALUE}};',
				],
				'condition' => [
					'better_slider_style' => array('5')
				],
			]
		);

		$this->add_control(
			'slider_mask',
			[
				'label' => __( 'Slider Mask', 'avo_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				
			]
		);

        $this->add_control( 
        	'show_paging',
            [
                'label' => esc_html__( 'Show Paging', 'better-el-addons' ),
                'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'better_slider_style' => array('1','8')
				],
            ]
        );
        $this->add_control(
			'speed',
			[
				'label' => __('Slider Speed', 'better-el-addons'),
				'type' => Controls_Manager::NUMBER,
				'default' => 3000,
				'condition' => [
					'better_slider_style' => array('1','7','8')
				]
			]
		);
		$this->add_responsive_control(
			'slider_height',
			[
				'label' => __( 'Slider Height','better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'vh', '%', 'px', 'rem', 'custom' ],
                'default' => [
					'unit' => 'vh',
				],
				'condition' => [
					'better_slider_style' => array('1')
                ],
				'selectors' => [
					'{{WRAPPER}} .better-slider.style-1 .slid-half .nofull' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();


		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_slider_style' => array('1')
				],
			]
		);

		// Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .custom-font',
			]
		);
		// Main Color
		$this->add_control(
			'better_slider_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'selectors' => [
				'{{WRAPPER}} .better-slider.style-1 .cta__slider-item .caption .thin' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-btn-curve.btn-color:hover span' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-btn-curve.btn-color' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .better-btn-curve.btn-color' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'better_slider_bgcolor',
			[
				'label' => esc_html__( 'Backgrond Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'selectors' => [
				'{{WRAPPER}} .better-slider.style-1 .slid-half .nofull' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Title Style tab section ---------------------------------------------------------
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_slider_style' => array('1','9')
				],
			]
		);


		// Main Color
		$this->add_control(
			'title_slider_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'selectors' => [
				'{{WRAPPER}} .better-slider .caption .title' => 'color: {{VALUE}}',
				],
			]
		);
		// Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_title_typograph',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider .caption .title',
			]
		);

		$this->end_controls_section();



		// SubTitle Style tab section ---------------------------------------------------------
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => esc_html__( 'SubTitle', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_slider_style' => array('1','9')
				],
			]
		);


		$this->add_control(
			'subtitle_slider_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'selectors' => [
				'{{WRAPPER}} .better-slider .caption .subtitle' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_subtitle_typograph',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider .caption .subtitle',
			]
		);

		$this->end_controls_section();


		// Text Style tab section ---------------------------------------------------------
		$this->start_controls_section(
			'text_style_section',
			[
				'label' => esc_html__( 'Text', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_slider_style' => array('1','9')
				],
			]
		);


		$this->add_control(
			'text_slider_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'selectors' => [
				'{{WRAPPER}} .better-slider .caption .text' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_text_typograph',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider .caption .text',
			]
		);

		$this->end_controls_section();


		// Button Style tab section ---------------------------------------------------------
		$this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__( 'Button Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_slider_style' => array('1','9')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .better-slider .caption .button span',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor' ),
			]
		);


		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-slider .caption .button span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .better-slider .caption .button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-slider .caption .button:hover span, {{WRAPPER}} .better-slider .caption .button:focus span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-slider .caption .button:hover span, {{WRAPPER}} .better-slider .caption .button:focus span' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .better-slider .caption .button:hover:after, {{WRAPPER}} .better-slider .caption .button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .better-slider .caption .button:hover, {{WRAPPER}} .better-slider .caption .button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .better-slider .caption .button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .better-slider .caption .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .better-slider .caption .button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-slider .caption .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'style2_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_slider_style' => array('2')
				],
			]
		);

		// Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_style2_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider.style-2 .parallax-slider .caption h1',
			]
		);

		// Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_style2_sub_title_typography',
				'label' => esc_html__( 'Sub-Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider.style-2 .parallax-slider .caption p',
			]
		);

		// Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_style2_btn_typography',
				'label' => esc_html__( 'Button Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider.style-2 .parallax-slider .caption .btn-dis',
			]
		);

		$this->end_controls_section();

		// start of the Style tab section
		$this->start_controls_section(
			'style4_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_slider_style' => array('3')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_style4_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider.style-3 .cont h2',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_style4_subtitle_typography',
				'label' => esc_html__( 'Sub-Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider.style-3 .cont h6',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_style4_info_title_typography',
				'label' => esc_html__( 'Info Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider.style-3.better-bg-img .item h6',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_slider_style4_info_content_typography',
				'label' => esc_html__( 'Info Content Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-slider.style-3.better-bg-img .item p',
			]
		);

		$this->end_controls_section();
		// end of the Content tab section
		
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
		$speed = $settings['speed'] ? $settings['speed'] : 3000;
		$show_paging = $settings['show_paging'] ? 'show' : '';
		$style = $settings['better_slider_style'];	
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


