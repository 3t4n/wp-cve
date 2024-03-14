<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Team extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'team';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Team', 'better-el-addons' );
	}
	
	//script depend
	public function get_script_depends() { return [ 'better-slick','swiper','wow','isotope','youtubepopup-js','bootstrap-js','splitting','parallaxie','simpleParallax','justifiedgallery','scrollit','counterup','better-el-addons']; }

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	
	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		// start of the Content tab section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'better-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'better_team_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
					'3' => __( 'Style 3', 'better-el-addons' ),
					'4' => __( 'Style 4', 'better-el-addons' ),
					'5' => __( 'Style 5', 'better-el-addons' ),
					'6' => __( 'Style 6', 'better-el-addons' ),
					'7' => __( 'Style 7', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'team7_angle',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'left' => __( 'left', 'better-el-addons' ),
					'right' => __( 'right', 'better-el-addons' ),
				],
				'default' => 'left',
				'condition' => [
					'better_team_style' => array('7')
				],
			]
		);

		$this->add_control(
        	'better_team5_title',
			[
				'label'         => esc_html__('Title', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'Our Team.',
				'condition' => [
					'better_team_style' => array('5')
				],
			]
		);

		$this->add_control(
        	'better_team5_sub_title',
			[
				'label'         => esc_html__('Sub-Title', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'Employees',
				'condition' => [
					'better_team_style' => array('5')
				],
			]
		);

		// Team Image
		$this->add_control(
			'better_team_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'better_team_style' => array('1','3','4','6','7')
				],
			]
		);

		// Team Name
		$this->add_control(
        	'better_team_title',
			[
				'label'         => esc_html__('Name', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'John Doe',
				'condition' => [
					'better_team_style' => array('1','3','4','6','7')
				],
			]
		);
		
		// Team Designation
		$this->add_control(
        	'better_team_desg',
			[
				'label'         => esc_html__('Designation', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'Web Developer',
				'condition' => [
					'better_team_style' => array('1','3','4','6','7')
				],
			]
		);

		$this->add_control(
			'better_team_hover_style',
			[
				'label' => __( 'Hover Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'square' => __( 'square', 'better-el-addons' ),
					'circle' => __( 'circle', 'better-el-addons' ),
					'triangle' => __( 'triangle', 'better-el-addons' ),
				],
				'default' => 'square',
				'condition' => [
					'better_team_style' => array('4')
				],
			]
		);

		// List Repeater 
		$repeater = new \Elementor\Repeater();

		// Social List
		$repeater->add_control(
        	'better_social_title',
			[
				'label'         => esc_html__('Social Title', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'fa fa-star',
			]
        );

		$repeater->add_control(
        	'better_social_icon',
			[
				'label'         => esc_html__('Social Icon', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::ICONS,
				'label_block'   => true,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
			]
        );

		// List Group Title 
		$repeater->add_control(
			'better_social_link',
			[
				'label'         => esc_html__('Social Link', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::URL,
				'label_block'   => true,
				'default'       => [
					'url'   => '#',
				],
			]
		);

		// Brand Logo List
		$this->add_control(
			'better_social_list',
			[
				'label' => esc_html__( 'Social Profile List', 'better-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'condition' => [
					'better_team_style' => array('1','3','4')
				],
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'better_social_title' => __( 'Facebook', 'better-elementor-widgets' ),
						'better_social_icon' => 'fab fa-facebook-f',
						'better_social_link' => 'https://www/yourlink.com',
					],
					[
						'better_social_title' => __( 'Twitter', 'better-elementor-widgets' ),
						'better_social_icon' => 'fa fa-twitter',
						'better_social_link' => 'https://www/yourlink.com',
					],
					[
						'better_social_title' => __( 'Linkedin', 'better-elementor-widgets' ),
						'better_social_icon' => 'fa fa-linkedin',
						'better_social_link' => 'https://www/yourlink.com',
					],
					[
						'better_social_title' => __( 'Linkedin', 'better-elementor-widgets' ),
						'better_social_icon' => __( 'Youtube', 'better-elementor-widgets' ),
						'better_social_icon' => 'fa fa-youtube',
						'better_social_link' => 'https://www/yourlink.com',
					],
				],
				'title_field' => '{{{ better_social_title }}}',
			]
		);

		$this->add_control(
			'better_member_list',
			[
				'label' => __( 'Slider List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_team_style' => array('2','5')
				],
				'fields' => [
					[
						'name' => 'better_team2_title',
						'label' => __( 'Name', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => __( 'John Doe' ,  'better-el-addons'  ),
					],
					[
						'name' => 'better_team2_desg',
						'label' => __( 'Designation', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => __( 'Web Developer' ,  'better-el-addons'  ),
					],
					[
						'name' => 'better_team2_image',
						'label' => __( 'Slider Image', 'better-el-addons' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'better_team2_social_icon_1',
						'label' => esc_html__( 'Social Icon #1', 'better-el-addons' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'fab fa-facebook-f',
							'library' => 'fa-brand',
						],
					],
					[
						'name' => 'better_team2_social_link_1',
						'label' => esc_html__( 'Social link #1', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => __('Leave it blank if you don\'t need this button'),
					],
					[
						'name' => 'better_team2_social_icon_2',
						'label' => esc_html__( 'Social Icon #2', 'better-el-addons' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'fab fa-twitter',
							'library' => 'fa-brand',
						],
					],
					[
						'name' => 'better_team2_social_link_2',
						'label' => esc_html__( 'Social link #2', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => __('Leave it blank if you don\'t need this button'),
					],
					[
						'name' => 'better_team2_social_icon_3',
						'label' => esc_html__( 'Social Icon #3', 'better-el-addons' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'fab fa-behance',
							'library' => 'fa-brand',
						],
					],
					[
						'name' => 'better_team2_social_link_3',
						'label' => esc_html__( 'Social link #3', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => __('Leave it blank if you don\'t need this button'),
					],
					[
						'name' => 'better_team2_social_icon_4',
						'label' => esc_html__( 'Social Icon #4', 'better-el-addons' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'fab fa-linkedin-in',
							'library' => 'fa-brand',
						],
					],
					[
						'name' => 'better_team2_social_link_4',
						'label' => esc_html__( 'Social link #4', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => __('Leave it blank if you don\'t need this button'),
					],

				],
				'title_field' => '{{{ better_team2_title }}}',
			]
		);

		$this->end_controls_section();

		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'better_team_section_title_color',
			[
				'label' => esc_html__( 'Section Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .better-team.style-5 h6' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_team_style' => array('5')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_team_section_title_typography',
				'label' => esc_html__( 'Section Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-team.style-5 h6',
				'condition' => [
					'better_team_style' => array('5')
				],
			]
		);

		$this->add_control(
			'better_team_section_sub_title_color',
			[
				'label' => esc_html__( 'Section Sub-Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .better-team.style-5 h3' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_team_style' => array('5')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_team_section_sub_title_typography',
				'label' => esc_html__( 'Section Sub-Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-team.style-5 h3',
				'condition' => [
					'better_team_style' => array('5')
				],
			]
		);

		// Team Title Options
		$this->add_control(
			'better_team_title_options',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Team Title Color
		$this->add_control(
			'better_team_title_color',
			[
				'label' => esc_html__( 'Item Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .better-team.style-1 .team-hover h4' => 'color: {{VALUE}}',
					'{{WRAPPER}} .better-team.style-5 .item .info h5' => 'color: {{VALUE}}',
					'{{WRAPPER}} .better-team .item .info h6' => 'color: {{VALUE}}',
					'{{WRAPPER}} .better-team.style-2 .item .info h5' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_team_style' => array('1','2','5','6','7')
				],
			]
		);

		// Team Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_team_title_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-team .item .info h6, {{WRAPPER}} .better-team.style-1 .team-hover h4, {{WRAPPER}} .better-team.style-2 .item .info h5, {{WRAPPER}} .better-team.style-5 .item .info h5',
			]
		);

		// Team Designation Options
		$this->add_control(
			'better_team_desg_options',
			[
				'label' => esc_html__( 'Designation', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Team Designation Color
		$this->add_control(
			'better_team_designation_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
				'{{WRAPPER}} .better-team.style-1 .team-hover p' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-team .item .info span' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-team.style-2 .item .info span' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_team_style' => array('1','2','5','6','7')
				],
			]
		);

		// Team Designation Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_team_designation_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-team.style-1 .team-hover p, {{WRAPPER}} .better-team.style-2 .item .info span, {{WRAPPER}} .better-team .item .info span',
			]
		);

		// Social List Options
		$this->add_control(
			'better_social_list_options',
			[
				'label' => esc_html__( 'Social Link', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'better_team_style!' => '6',
					'better_team_style!' => '7',
				],
			]
		);

		// Social Icon Color
		$this->add_control(
			'better_social_icon_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'default' => '#db3157',
				'selectors' => [
					'{{WRAPPER}} .better-team.style-1 .team-social a, {{WRAPPER}} .better-team.style-2 .item .img .social a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .better-team.style-5 .item .info .social a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_team_style!' => '6',
					'better_team_style!' => '7',
				],
			]
		);

		// Social Icon Background Color
		$this->add_control(
			'better_social_icon_background',
			[
				'label' => esc_html__( 'Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .better-team.style-1 .team-social a, {{WRAPPER}} .better-team.style-2 .item .img .social' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'better_team_style!' => '6',
					'better_team_style!' => '7',
				],
			]
		);

		// Social Icon Background Color
		$this->add_control(
			'better_active_dot_color',
			[
				'label' => esc_html__( 'Active Dot Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-team.style-2 .slick-dots li.slick-active' => 'background: {{VALUE}}',
				],
				'condition' => [
					'better_team_style!' => '6',
					'better_team_style!' => '7',
				],
			]
		);

		$this->end_controls_tabs();

		$this->end_controls_section();
		// end of the Style tab section

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		$better_team_image = $settings['better_team_image']['url'];
		$better_team_title = $settings['better_team_title'];
		$better_team_desg = $settings['better_team_desg'];

		$style = $settings['better_team_style'];	
		require( 'styles/style'.$style.'.php' );

	}
}