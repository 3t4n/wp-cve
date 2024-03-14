<?php
namespace Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Prevent direct access to files
if (!defined('ABSPATH')) {
    exit;
}

class Json_Based_Animation_Addon_Kap_Asias extends Widget_Base {
	
	public function get_name() {
		return 'jbafe_lottie_animation';
	}

	public function get_title() {
		return esc_html__('JSON Animation', 'jbafe');
	}

	public function get_icon() {
		 return 'eicon-favorite';
	}
	public function get_categories() {
        return array('kap-asia');
    }
	public function get_keywords() {
		return [ 'animations', 'lottiefiles', 'bodylines','lottie animation','bodymoving','hover','click','mouse over out effect','parallax effect'];
	}
	
	protected function register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'JSON Based Animation', 'jbafe' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'la_json_url',
			[
				'label' => esc_html__( 'JSON URL', 'jbafe' ),
				'type' => Controls_Manager::URL,				
				'placeholder' => esc_html__( 'https://www.demo-link.com', 'jbafe' ),
				'default' => [
					'url' => '',
				],				
			]
		);		
		$this->end_controls_section();
		/*setting option start*/
		$this->start_controls_section(
			'section_la_settings_option',
			[
				'label' => esc_html__( 'JSON Based Animation Settings', 'jbafe' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);		
		$this->add_control(
			'la_action',
			[
				'label' => esc_html__( 'Play on', 'jbafe' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'         => esc_html__( 'Default', 'jbafe' ),
					'autoplay'         => esc_html__( 'Auto Play', 'jbafe' ),
					'hover'    => esc_html__( 'Hover', 'jbafe' ),
					'click'    => esc_html__( 'Click', 'jbafe' ),				
					'mouseoverout'  => esc_html__( 'Mouse Over Out Effect', 'jbafe' ),
					'parallax_effect'  => esc_html__( 'Parallax Effect', 'jbafe' ),					
					'reverse_second_click'  => esc_html__( 'Reverse on Second Click', 'jbafe' ),					
				],				
			]
		);
		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop Animation', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'ON', 'jbafe' ),
				'label_off' => esc_html__( 'OFF', 'jbafe' ),
				'return_value' => 'true',
				'default' => 'false',
				'separator' => 'before',
				'condition' => [
					'la_action!' => 'default',
				],
			]
		);
		$this->add_control(
			'loop_counter',
			[
				'label' => esc_html__( 'Loops Counter', 'jbafe' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 100,
				'step' => 1,
				'condition' => [
					'loop' => 'true',
				],
			]
		);
		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Animation Play Speed', 'jbafe' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 1,
                        'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.5,
				],
				'condition' => [
					'la_action!' => ['default','parallax_effect'],
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'la_scrollbased',
			[
				'label' => esc_html__( 'On Scroll Animation', 'jbafe' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'la_custom',
				'options' => [
					'la_custom' => esc_html__( 'Inline', 'jbafe' ),
					'la_document'  => esc_html__( 'Body', 'jbafe' ),
				],
				'description' => __( 'Note : If you select "Body", Animation will start and end based on whole page\'s height. In Inline, You need to give offset and duration for animation.', 'jbafe' ),
				'separator' => 'before',
				'condition' => [
					'la_action' => 'parallax_effect',
				],
			]
		);
		
		$this->add_control(
			'la_section_duration',
			[
				'label' => esc_html__( 'Duration', 'jbafe' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 2000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'condition' => [
					'la_action' => 'parallax_effect',
					'la_scrollbased' => 'la_custom',
				],
			]
		);
		$this->add_control(
			'la_section_offset',
			[
				'label' => esc_html__( 'Offset', 'jbafe' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [
					'la_action' => 'parallax_effect',
					'la_scrollbased' => 'la_custom',
				],
			]
		);
		$this->add_control(
			'la_custom_time',
			[
				'label' => esc_html__( 'Animation Custom Time', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'ON', 'jbafe' ),
				'label_off' => esc_html__( 'OFF', 'jbafe' ),
				'condition' => [
					'la_action' => ['hover','click','mouseoverout','parallax_effect'],
				],
			]
		);
		$this->add_control(
			'la_start_time',
			[
				'label' => esc_html__( 'Start Time', 'jbafe' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'step' => 1,
				'condition' => [
					'la_action' => ['hover','click','mouseoverout','parallax_effect'],
					'la_custom_time' => 'yes',
				],
			]
		);		
		$this->add_control(
			'la_end_time',
			[
				'label' => esc_html__( 'End Time', 'jbafe' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'step' => 1,
				'condition' => [
					'la_action' => ['hover','click','mouseoverout','parallax_effect'],
					'la_custom_time' => 'yes',
				],
			]
		);		
		$this->add_control(
			'la_link',
			[
				'label' => esc_html__( 'URL', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'jbafe' ),
				'label_off' => esc_html__( 'Disable', 'jbafe' ),				
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'la_link_url',
			[
				'label' => esc_html__( 'URL', 'jbafe' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.demo-link.com', 'jbafe' ),
				'default' => [
					'url' => '#',
				],
				'condition' => [
					'la_link' => 'yes',					
				],
			]
		);	
		$this->add_control(
			'la_link_delay',
			[
				'label' => esc_html__( 'Click Delay', 'jbafe' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 10000,
                        'step' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1000,
				],
				'condition' => [
					'la_link' => 'yes',					
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'la_lconic_layout',
			[
				'label' => esc_html__( 'Iconic Layout', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'jbafe' ),
				'label_off' => esc_html__( 'Disable', 'jbafe' ),				
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'la_lconic_layout_heading',
			[
				'label' => esc_html__( 'Heading', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'jbafe' ),
				'label_off' => esc_html__( 'Disable', 'jbafe' ),				
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'la_lconic_layout' => 'yes',
				],
			]
		);
		$this->add_control(
			'la_lconic_layout_title',
			[
				'label' => esc_html__( 'Title', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'jbafe' ),
				'label_off' => esc_html__( 'Disable', 'jbafe' ),				
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'la_lconic_layout' => 'yes',
				],
			]
		);
		$this->add_control(
			'la_lconic_layout_description',
			[
				'label' => esc_html__( 'Description', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'jbafe' ),
				'label_off' => esc_html__( 'Disable', 'jbafe' ),				
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'la_lconic_layout' => 'yes',
				],
			]
		);
		$this->add_control(
			'la_cursor',
			[
				'label' => esc_html__( 'Cursor', 'jbafe' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'jbafe' ),
				'label_off' => esc_html__( 'Disable', 'jbafe' ),				
				'default' => 'no',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'la_cursor_select',
			[
				'label' => esc_html__( 'Select', 'jbafe' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'pointer',
				'options' => [
					'pointer' => esc_html__( 'Default', 'jbafe' ),
					'alias'  => esc_html__( 'Alias', 'jbafe' ),					
					'all-scroll' => esc_html__( 'All Scroll', 'jbafe' ),
					'crosshair' => esc_html__( 'Crosshair', 'jbafe' ),
					'e-resize' => esc_html__( 'E Resize', 'jbafe' ),
					'grab' => esc_html__( 'Grab', 'jbafe' ),
					'help' => esc_html__( 'Help', 'jbafe' ),
					'wait' => esc_html__( 'Wait', 'jbafe' ),
					'zoom-in' => esc_html__( 'Zoom In', 'jbafe' ),
				],
				'condition' => [
					'la_cursor' => 'yes',
				],
			]
		);
		$this->end_controls_section();
		/*setting option end*/
		
		/*Heading option start*/
		$this->start_controls_section(
			'section_heading_option',
			[
				'label' => esc_html__( 'Heading Options', 'jbafe' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_heading' => 'yes',
				],
			]
		);
		$this->add_control(
			'heading_tag',
			[
				'label' => esc_html__( 'Tag', 'jbafe' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1'  => esc_html__( 'H1', 'jbafe' ),
					'h2' => esc_html__( 'H2', 'jbafe' ),
					'h3' => esc_html__( 'H3', 'jbafe' ),
					'h4' => esc_html__( 'H4', 'jbafe' ),
					'h5' => esc_html__( 'H5', 'jbafe' ),
					'h6' => esc_html__( 'H6', 'jbafe' ),
				],
			]
		);
		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Heading', 'jbafe' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true,],
				'default' => esc_html__( 'HEADING', 'jbafe' ),
				'placeholder' => esc_html__( 'Enter Heading', 'jbafe' ),				
			]
		);
		$this->add_responsive_control(
			'heading_align',
			[
				'label' => esc_html__( 'Alignment', 'jbafe' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jbafe' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jbafe' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jbafe' ),
						'icon' => 'fa fa-align-right',
					],
				],				
				'default' => 'center',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading' => 'text-align: {{VALUE}};',
				],
				
			]
		);		
		$this->end_controls_section();
		/*Heading option end*/
		
		/*Title option start*/
		$this->start_controls_section(
			'section_title_option',
			[
				'label' => esc_html__( 'Title Options', 'jbafe' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_title' => 'yes',
				],
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Tag', 'jbafe' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h1'  => esc_html__( 'H1', 'jbafe' ),
					'h2' => esc_html__( 'H2', 'jbafe' ),
					'h3' => esc_html__( 'H3', 'jbafe' ),
					'h4' => esc_html__( 'H4', 'jbafe' ),
					'h5' => esc_html__( 'H5', 'jbafe' ),
					'h6' => esc_html__( 'H6', 'jbafe' ),
				],
			]
		);
		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( 'Title', 'jbafe' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true,],
				'default' => esc_html__( 'Title', 'jbafe' ),
				'placeholder' => esc_html__( 'Enter Title', 'jbafe' ),				
			]
		);
		$this->add_responsive_control(
			'title_align',
			[
				'label' => esc_html__( 'Alignment', 'jbafe' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jbafe' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jbafe' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jbafe' ),
						'icon' => 'fa fa-align-right',
					],
				],				
				'default' => 'center',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title' => 'text-align: {{VALUE}};',
				],
				
			]
		);
		$this->end_controls_section();
		/*Title option end*/
		
		/*Description option start*/
		$this->start_controls_section(
			'section_description_option',
			[
				'label' => esc_html__( 'Description Options', 'jbafe' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_description' => 'yes',
				],
			]
		);
		$this->add_control(
			'description_tag',
			[
				'label' => esc_html__( 'Tag', 'jbafe' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'div',
				'options' => [
					'h1'  => esc_html__( 'H1', 'jbafe' ),
					'h2' => esc_html__( 'H2', 'jbafe' ),
					'h3' => esc_html__( 'H3', 'jbafe' ),
					'h4' => esc_html__( 'H4', 'jbafe' ),
					'h5' => esc_html__( 'H5', 'jbafe' ),
					'h6' => esc_html__( 'H6', 'jbafe' ),
					'p' => esc_html__( 'P', 'jbafe' ),
					'div' => esc_html__( 'DIV', 'jbafe' ),
				],
			]
		);
		$this->add_control(
			'description_text',
			[
				'label' => esc_html__( 'Description', 'jbafe' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jbafe' ),
				'dynamic' => ['active' => true,],
			]
		);
		$this->add_responsive_control(
			'description_align',
			[
				'label' => esc_html__( 'Alignment', 'jbafe' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jbafe' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jbafe' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jbafe' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'jbafe' ),
						'icon' => 'fa fa-align-justify',
					],
				],				
				'default' => 'center',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description' => 'text-align: {{VALUE}};',
				],
				
			]
		);
		$this->end_controls_section();
		/*Description option end*/
		
		/*Renderer start*/
		$this->start_controls_section(
			'section_render_option',
			[
				'label' => esc_html__( 'Render JSON', 'jbafe' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'anim_renderer',
			[
				'label' => esc_html__( 'Render as', 'jbafe' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg',
				'options' => [
					'svg'  => esc_html__( 'SVG', 'jbafe' ),
					'canvas' => esc_html__( 'Canvas', 'jbafe' ),
					'html' => esc_html__( 'HTML', 'jbafe' ),
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_section();
		/*Renderer option end*/
		
		/*style start*/
		/*Heading & Bottom Shape Style start*/
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__( 'Heading & Bottom Shape Style', 'jbafe' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'la_lconic_layout' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'heading_padding',
			[
				'label' => esc_html__( 'Padding', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_heading' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'heading_margin',
			[
				'label' => esc_html__( 'Margin', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_heading' => 'yes',
				],
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => esc_html__( 'Typography', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading',
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_heading' => 'yes',
				],
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'jbafe' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading' => 'color: {{VALUE}}',
				],
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_heading' => 'yes',
				],
			]
		);
		$this->add_control(
			'bg_color_head',
			[
				'label' => esc_html__( 'You need to set below Three background color.', 'jbafe' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
            'bg_color_1',
            [
                'label' => esc_html__('Background Color 1', 'jbafe'),
                'type' => Controls_Manager::COLOR,				
            ]
        );
		$this->add_control(
            'bg_color_2',
            [
                'label' => esc_html__('Background Color 2', 'jbafe'),
                'type' => Controls_Manager::COLOR,
            ]
        );
		$this->add_control(
            'bg_color_3',
            [
                'label' => esc_html__('Background Color 3', 'jbafe'),
                'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading,
					{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading:before' => 'background: linear-gradient(90deg, {{bg_color_1.VALUE}} 0%, {{bg_color_2.VALUE}} 30%, {{bg_color_3.VALUE}} 100%);',
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic:after' => 'background: linear-gradient(270deg, {{bg_color_1.VALUE}} 0%, {{bg_color_2.VALUE}} 30%, {{bg_color_3.VALUE}} 100%)',
				],				
            ]
        );
		$this->add_control(
            'bg_color_half_round',
            [
                'label' => esc_html__('Half Round Shape Background', 'jbafe'),
                'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [					
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-heading:after' => 'background: {{VALUE}};',
				],
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_heading' => 'yes',
				],
            ]
        );
		$this->add_responsive_control(
			'bottom_shape_height_width',
			[
				'label' => esc_html__( 'Bottom Shape Height', 'jbafe' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic:after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_heading' => 'yes',
				],
			]
		);
		$this->end_controls_section();
		/*Heading & Bottom Shape Style end*/
		
		/*Title Style start*/
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title Style', 'jbafe' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_title' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'jbafe' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'title_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title',
				
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'title_border',
				'label' => esc_html__( 'Border', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'title_br',
			[
				'label'      => esc_html__( 'Border Radius', 'jbafe' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'label' => esc_html__( 'Box Shadow', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-title',				
			]
		);
		$this->end_controls_section();
		/*Title Style end*/
		
		/*Description Style start*/
		$this->start_controls_section(
			'section_description_style',
			[
				'label' => esc_html__( 'Description Style', 'jbafe' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'la_lconic_layout' => 'yes',
					'la_lconic_layout_description' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'description_padding',
			[
				'label' => esc_html__( 'Padding', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Margin', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => esc_html__( 'Typography', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description',
			]
		);
		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'jbafe' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'description_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description',
				
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'description_border',
				'label' => esc_html__( 'Border', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'description_br',
			[
				'label'      => esc_html__( 'Border Radius', 'jbafe' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'description_shadow',
				'label' => esc_html__( 'Box Shadow', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic .jbafes-iconic-description',				
			]
		);
		$this->end_controls_section();
		/*Description Style end*/
		
		/*JSON Animation Style start*/
		$this->start_controls_section(
			'section_lotties_option',
			[
				'label' => esc_html__( 'JSON Animation Style', 'jbafe' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'jbafe' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jbafe' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jbafe' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jbafe' ),
						'icon' => 'fa fa-align-right',
					],
				],				
				'default' => 'center',
				'prefix_ class' => 'text-%s',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'max_width',
			[
				'label' => esc_html__( 'Maximum Width', 'jbafe' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'separator' => 'before',
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'minimum_height',
			[
				'label' => esc_html__( 'Minimum Height', 'jbafe' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'la_marign',
			[
				'label' => esc_html__( 'Margin', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_section();
		/*JSON Animation Style end*/
		
		/*Iconic Box Style start*/
		$this->start_controls_section(
			'section_iconic_box_style',
			[
				'label' => esc_html__( 'Iconic Box Style', 'jbafe' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'la_lconic_layout' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'iconic_box_padding',
			[
				'label' => esc_html__( 'Padding', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		$this->add_responsive_control(
			'iconic_box_margin',
			[
				'label' => esc_html__( 'Margin', 'jbafe' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],				
				'selectors' => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'iconic_box_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic',
				
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'iconic_box_border',
				'label' => esc_html__( 'Border', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic',
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'iconic_box_br',
			[
				'label'      => esc_html__( 'Border Radius', 'jbafe' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'iconic_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'jbafe' ),
				'selector' => '{{WRAPPER}} .jbafes-lotties-animation-wrapper-iconic',				
			]
		);
		$this->end_controls_section();
		/*Iconic Box Style end*/
		/*style end*/		
	}
	
	public function json_html_tag_check_and_verify(){
		return [ 'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'div',
			'span',
			'p',
			'main',
			'nav',		
			'section',
			'header',
			'footer',
			'article',
			'aside',		
		];
	}		

	public function json_check_and_validate_html_tag( $chk_tag ) {
		return in_array( strtolower( $chk_tag ), $this->json_html_tag_check_and_verify() ) ? $chk_tag : 'div';
	}
	protected function render() {

		$settings = $this->get_settings_for_display();
		$la_lconic_layout_heading= (!empty($settings['la_lconic_layout_heading'])) ? $settings['la_lconic_layout_heading'] : 'no';
		$heading_tag= (!empty($settings['heading_tag'])) ? $settings['heading_tag'] : 'h2';
		$la_lconic_layout_title= (!empty($settings['la_lconic_layout_title'])) ? $settings['la_lconic_layout_title'] : 'no';
		$title_tag= (!empty($settings['title_tag'])) ? $settings['title_tag'] : 'h3';
		$la_lconic_layout_description= (!empty($settings['la_lconic_layout_description'])) ? $settings['la_lconic_layout_description'] : 'no';
		$description_tag= (!empty($settings['description_tag'])) ? $settings['description_tag'] : 'div';
				
		$id=uniqid("jbafes-la");
		
		$la_start_time=$la_end_time='';
		if(!empty($settings['la_custom_time']) && $settings['la_custom_time']=='yes'){
			$la_start_time = ($settings['la_start_time']!='') ? $settings['la_start_time'] : 1;
			$la_end_time = ($settings['la_end_time']!='') ? $settings['la_end_time'] : 100;
		}
		
		$la_scrollbased = (!empty($settings['la_scrollbased'])) ? $settings['la_scrollbased'] : 'la_custom';
		$la_section_duration=500;
		if(!empty($settings['la_section_duration']['size'])){
			$la_section_duration = $settings['la_section_duration']['size'];
		}
		$la_section_offset=0;
		if(!empty($settings['la_section_offset']['size'])){
			$la_section_offset = $settings['la_section_offset']['size'];
		}
		
		$loop = 0;
		if((isset($settings['loop']) && $settings['loop']=='true')){
			$loop = $settings['loop_counter'];
		}
		
		$max_width =(!empty($settings['max_width']["size"])) ? $settings['max_width']["size"].$settings['max_width']["unit"] : '100%';		
		$minimum_height =(!empty($settings['minimum_height']["size"])) ? $settings['minimum_height']["size"].$settings['minimum_height']["unit"] : '';
		$speed =(!empty($settings['speed']['size'])) ? $settings['speed']['size'] : '0.5';
		
		
		$la_action ='';
		if(!empty($settings['la_action'])){
			$la_action =$settings['la_action'];
		}
		if ( $settings["anim_renderer"] ) {
			$anim_renderer = esc_attr($settings["anim_renderer"]);
		}
		
		$content_align=$settings["content_align"];
		$style_atts = $classes = '';
		if ( $content_align ) {
			$classes .= ' align-' . $content_align;
		}
		if ( !empty( $anim_renderer ) ) {
			$classes .= ' renderer-' . $anim_renderer;
		}
		
		if ( !empty( $anim_renderer ) && $anim_renderer == 'html' ) {
			$style_atts .= 'position: relative;';
		}
		if ( !empty( $content_align ) && $content_align == 'right'  ) {
			$style_atts .= 'margin-left: auto;';
		} elseif ( !empty( $content_align ) && $content_align == 'center' ) {
			$style_atts .= 'margin-right: auto;';
			$style_atts .= 'margin-left: auto;';
		}
		
		$kap_opt = '';
		if ( $settings['la_json_url']['url'] != '' ) {
			$PATHINFO_EXTENSION = pathinfo($settings['la_json_url']['url'], PATHINFO_EXTENSION);			
			if($PATHINFO_EXTENSION!='json'){
				echo '<h3 class="posts-not-found">'.esc_html__("Opps!! Please Enter Only JSON File Extension.",'jbafe').'</h3>';
				return false;
			}else{				
				$kap_opt =  'data-id="'.$id.'"';
				$kap_opt .=  ' data-path="'.esc_url($settings['la_json_url']['url']).'"';
				$kap_opt .=  ' data-loop="'.$loop.'"';
				$kap_opt .=  ' data-anim_renderer="'.$anim_renderer.'"';
				$kap_opt .=  ' data-width="'.$max_width.'"';
				$kap_opt .=  ' data-height="'.$minimum_height.'"';
				$kap_opt .=  ' data-playspeed="'.$speed.'"';
				$kap_opt .=  ' data-play_action="'.$la_action.'"';
				$kap_opt .=  ' data-la_scrollbased="'.$la_scrollbased.'"';
				$kap_opt .=  ' data-la_section_duration="'.$la_section_duration.'"';
				$kap_opt .=  ' data-la_section_offset="'.$la_section_offset.'"';
				$kap_opt .=  ' data-la_start_time="'.$la_start_time.'"';
				$kap_opt .=  ' data-la_end_time="'.$la_end_time.'"';
			}
		}else{
			echo '<h3 class="posts-not-found">'.esc_html__("Opps!! Please Enter JSON File Extension.",'jbafe').'</h3>';
		}
		
		
		
		$la_op ='';			
		if(!empty($settings['la_lconic_layout']) && $settings['la_lconic_layout']=='yes'){
			wp_enqueue_style('jbafe-la-css');
			
			$la_op .='<div class="jbafes-lotties-animation-wrapper-iconic jbafes-iconic'.esc_attr($id).'">';
				if(!empty($settings['la_lconic_layout_heading']) && $settings['la_lconic_layout_heading']=='yes'){
					if(!empty($settings['heading_text'])){
						$la_op .='<'.esc_attr($this->json_check_and_validate_html_tag($heading_tag)).' class="jbafes-iconic-heading">'.$settings['heading_text'].'</'.esc_attr($this->json_check_and_validate_html_tag($heading_tag)).'>';
					}
				}
				if(!empty($settings['la_lconic_layout_title']) && $settings['la_lconic_layout_title']=='yes'){
					if(!empty($settings['title_text'])){
						$la_op .='<'.esc_attr($this->json_check_and_validate_html_tag($title_tag)).' class="jbafes-iconic-title">'.$settings['title_text'].'</'.esc_attr($this->json_check_and_validate_html_tag($title_tag)).'>';
					}
				}
				if(!empty($settings['la_lconic_layout_description']) && $settings['la_lconic_layout_description']=='yes'){
					if(!empty($settings['description_text'])){
						$la_op .='<'.$description_tag.' class="jbafes-iconic-description">'.wp_kses_post($settings['description_text']).'</'.$description_tag.'>';
					}
				}
		}
			
		if((!empty($settings['la_link']) && $settings['la_link'] == 'yes') && !empty($settings['la_link_url']['url']) && !empty($settings["la_link_delay"])){
			$la_op .='<script>(function($){"use strict";$( document ).ready(function(){$("a.jbafe-lotties-link").click(function(e){e.preventDefault();var storeurl = this.getAttribute("href");setTimeout(function(){window.location = storeurl;}, '.$settings["la_link_delay"]["size"].');});});})(jQuery);</script>';
			$la_op .='<a class="jbafe-lotties-link" href="'.esc_url($settings['la_link_url']['url']).'">';
		}
		
		
		if((!empty($settings['la_cursor']) && $settings['la_cursor']=='yes') && !empty($settings['la_cursor_select'])){
			$style_atts .='cursor:'.$settings['la_cursor_select'].';';
		}
	
		$la_op .='<div id="'.esc_attr($id).'" class="jbafes-lotties-animation-wrapper jbafes-'.esc_attr($id).' '.$classes.'"  style="'.$style_atts.'" '.$kap_opt.'>';
		$la_op .='</div>';
				
		if((!empty($settings['la_link']) && $settings['la_link'] == 'yes') && !empty($settings['la_link_url']['url']) && !empty($settings["la_link_delay"])){
			$la_op .='</a>';
		}
		
		if(!empty($settings['la_lconic_layout']) && $settings['la_lconic_layout']=='yes'){
			$la_op .='</div>';
		}
		
		echo wp_kses_normalize_entities($la_op);
	}
}

Plugin::instance()->widgets_manager->register( new Json_Based_Animation_Addon_Kap_Asias() );