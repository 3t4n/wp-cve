<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Clients extends Widget_Base {

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
		return 'better-clients';
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
		return esc_html__( 'BETTER Clients', 'elementor-hello-world' );
	}

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
		return 'eicon-posts-ticker';
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
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'elementor-hello-world','splitting' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		// start of the Content tab section
		$this->start_controls_section(
			'clients_content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'clients_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'dark_style',
			[
				'label' => esc_html__( 'Dark Style', 'genesis-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'genesis-core' ),
				'label_off' => esc_html__( 'Off', 'genesis-core' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'clients_style' => '1'
				],
			]
		);

        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
			'client_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => esc_url( Utils::get_placeholder_image_src() ),
				],
			]
		);
		
		// Price Plan Button Text
		$repeater->add_control(
			'client_name',
			[
				'label' => esc_html__( 'Client Name', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Byra', 'better-el-addons' ),
			]
		);

		// Price Plan Button Link
		$repeater->add_control(
			'client_link', 
			[
				'label' => __( 'Client Link', 'better-el-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'label_block' => true,
				'default'       => [
					'url'   => '#',
				],
			]
		);

		// Features List
		$this->add_control(
			'better_clients_list',
			[
				'label' => esc_html__( 'Features List', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'condition' => [
					'clients_style' => '1'
				],
				'default' => [
					[
						'client_image' => esc_url( Utils::get_placeholder_image_src() ),
					],
					[
						'client_image' => esc_url( Utils::get_placeholder_image_src() ),
					],
					[
						'client_image' => esc_url( Utils::get_placeholder_image_src() ),
					],
					[
						'client_image' => esc_url( Utils::get_placeholder_image_src() ),
					],
					[
						'client_image' => esc_url( Utils::get_placeholder_image_src() ),
					],
					[
						'client_image' => esc_url( Utils::get_placeholder_image_src() ),
					],
				],
				'title_field' => '{{{ name }}}',
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __( 'Text','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'default' => __( 'Insert text here..', 'better-el-addons' ),
				'condition'	=> [
					'clients_style'	=> '2'
				],
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => __( 'Client Link','architec_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave Link here',
				'condition'	=> [
					'clients_style'	=> '2'
				],
			]
		);


		$this->add_control(
            'image',
            [
                'label' => __( 'Image dark', 'architec_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'	=> [
					'clients_style'	=> '2'
				],
            ]
        );

		$this->end_controls_section();
		// end of the Content tab section

		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'client_size',
			[
				'label' => __( 'Size', 'architec_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' =>0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-clients .brands .img img' => 'max-width: {{SIZE}}px;',
				],
			]
		);

		// Price Plan Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_client_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-clients .item .link',
			]
		);

		$this->add_control(
			'better_client_title_color',
			[
				'label' => esc_html__( 'Contact Link Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-clients .item .link' => 'color: {{VALUE}}',
                ],
			]
        );

		$this->end_controls_section();

	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		$style = $settings['clients_style'];
		include( 'styles/style'.$style.'.php' );
	}
}