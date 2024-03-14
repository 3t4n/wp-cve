<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Repeater;
use Elementor\Utils;

class Animated_Text extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-animated-text';
	}
	
	public function get_title() {
		return esc_html__('Animated Text', 'borderless');
	}
	
	public function get_icon() {
		return 'borderless-icon-animated-text';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}

	public function get_style_depends() {
		return [ 'elementor-widget-animated-text' ];
	}

	public function get_script_depends() {
		return [ 'borderless-elementor-typewriterjs-script' ];
	}
	
	protected function _register_controls() {

		/*-----------------------------------------------------------------------------------*/
	/*  *.  Animated Text - Content
	/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_animated_text',
			[
				'label' => esc_html__( 'Animated Text', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_prefix',
			[
				'label' => __( 'Prefix', 'borderless' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your title', 'borderless' ),
				'default'     => esc_html__( 'This is the ', 'borderless'),
				'dynamic'     => [ 'active' => true ]
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'borderless_elementor_animated_text_strings_text_field',
			[
				'label'			=> esc_html__( 'Animated String', 'borderless'),
				'type'			=> Controls_Manager::TEXT,
				'label_block'	=> true,
				'dynamic'		=> [ 'active' => true ]
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_strings',
			[
				'label'       => __( 'Animated Text', 'borderless'),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      =>  $repeater->get_controls(),
				'title_field' => '{{ borderless_elementor_animated_text_strings_text_field }}',
				'default'     => [
					[
						'borderless_elementor_animated_text_strings_text_field' => __( 'First string', 'borderless'),
					],
					[
						'borderless_elementor_animated_text_strings_text_field' => __( 'Second string', 'borderless'),
					],
					[
						'borderless_elementor_animated_text_strings_text_field' => __( 'Third string', 'borderless'),
					]
				],
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_suffix',
			[
				'label' => __( 'Suffix', 'borderless' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Place your suffix text', 'borderless' ),
				'default'     => esc_html__( ' of the sentence.', 'borderless'),
				'dynamic'     => [ 'active' => true ]
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
	/*  *.  Animated Text Settings - Content
	/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_section_animated_text_settings',
			[
				'label' => esc_html__( 'Animated Text Settings', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'borderless_elementor_animated_text_alignment',
			[
				'label' => esc_html__( 'Alignment', 'borderless'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'borderless'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'borderless'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'borderless'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .borderless-elementor-animated-text-widget' => 'text-align: {{VALUE}}',
				],
			]
		);

		/*

		$this->add_control(
			'borderless_elementor_animated_text_effect',
			[
				'label' => __( 'Direction', 'borderless' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'typing',
				'options' => [
					'typing'  => __( 'Typing', 'borderless' ),
					'side-up' => __( 'Slide Up', 'borderless' ),
					'zoom-out' => __( 'Zoom Out', 'borderless' ),
					'rotate' => __( 'Rotate', 'borderless' ),
					'auto-fade' => __( 'Auto Fade', 'borderless' ),
					'custom' => __( 'Custom', 'borderless' ),
				],
			]
		);

		*/

		$this->add_control(
			'borderless_elementor_animated_text_type_speed',
			[
				'label' => __( 'Type Speed', 'borderless' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 1,
				'default' => 50,
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_back_speed',
			[
				'label' => __( 'Back Speed', 'borderless' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 1,
				'default' => 30,
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_pause_for',
			[
				'label' => __( 'Pause For', 'borderless' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 9999,
				'step' => 1,
				'default' => 1500,
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_loop',
			[
				'label' => __( 'Loop', 'borderless' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_show_cursor',
			[
				'label' => __( 'Show Cursor', 'borderless' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				/*
				'condition' => array(
					'borderless_elementor_animated_text_effect' => 'typing',
				),
				*/
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_cursor_mark',
			[
				'label' => __( 'Cursor Mark', 'borderless' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Mark', 'borderless' ),
				'default'     => esc_html__( '|', 'borderless'),
				'dynamic'     => [ 'active' => true ],
				'condition' => array(
					/* 'borderless_elementor_animated_text_effect'      => 'typing', */
					'borderless_elementor_animated_text_show_cursor' => 'yes',
				),
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
	/*  *.  Animated Text Prefix - Style 
	/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_animated_text_prefix_styles',
			[
				'label' => esc_html__( 'Prefix Text Styles', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_prefix_color',
			[
				'label' => esc_html__( 'Prefix Text Color', 'borderless'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .borderless-elementor-animated-text-prefix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'borderless_elementor_animated_text_prefix_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_size' => ['default' => ['size' => 24]],
					'font_weight' => ['default' => 600],
					'line_height' => ['default' => ['size' => 1]],
				],
				'selector' => '{{WRAPPER}} .borderless-elementor-animated-text-prefix',
			]
		);


		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Animated Text - Style 
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_animated_text_styles',
			[
				'label' => esc_html__( 'Animated Text Styles', 'Borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_color',
			[
				'label' => esc_html__( 'Animated Text Color', 'borderless'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .borderless-elementor-animated-text-strings .Typewriter__wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_cursor_color',
			[
				'label' => esc_html__( 'Animated Text Cursor Color', 'borderless'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .borderless-elementor-animated-text-strings .Typewriter__cursor' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
			'label' => esc_html__( 'Animated Text Typography', 'borderless'),
			'name' => 'borderless_elementor_animated_text_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_size' => ['default' => ['size' => 24]],
					'font_weight' => ['default' => 600],
					'line_height' => ['default' => ['size' => 1]],
				],
				'selector' => '{{WRAPPER}} .borderless-elementor-animated-text-strings .Typewriter__wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
			'label' => esc_html__( 'Animated Text Cursor Typography', 'borderless'),
			'name' => 'borderless_elementor_animated_text_cursor_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_size' => ['default' => ['size' => 24]],
					'font_weight' => ['default' => 600],
					'line_height' => ['default' => ['size' => 1]],
				],
				'selector' => '{{WRAPPER}} .borderless-elementor-animated-text-strings .Typewriter__cursor',
			]
		);


		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Animated Text Suffix - Style 
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_animated_text_suffix_styles',
			[
				'label' => esc_html__( 'Suffix Text Styles', 'Borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'borderless_elementor_animated_text_suffix_color',
			[
				'label' => esc_html__( 'Suffix Text Color', 'borderless'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .borderless-elementor-animated-text-suffix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
			'name' => 'borderless_elementor_animated_text_suffix_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_size' => ['default' => ['size' => 24]],
					'font_weight' => ['default' => 600],
					'line_height' => ['default' => ['size' => 1]],
				],
				'selector' => '{{WRAPPER}} .borderless-elementor-animated-text-suffix',
			]
		);


		$this->end_controls_section();

	}

	/*-----------------------------------------------------------------------------------*/
	/*  *.  Render
	/*-----------------------------------------------------------------------------------*/
	
	protected function render() {
			
		$settings = $this->get_settings_for_display();

		/* Start Animated Text Strings Content */

		$strings = array();
		foreach ( $settings['borderless_elementor_animated_text_strings'] as $item ) {
			if ( ! empty( $item['borderless_elementor_animated_text_strings_text_field'] ) ) {
				array_push( $strings, str_replace( '\'', '&#39;', $item['borderless_elementor_animated_text_strings_text_field'] ) );
			}
		}
		$strings = implode("|",$strings);

		/* End Animated Text Strings Content */

		$this->add_render_attribute( 'animated-text', 'data-animated-text-strings', $strings );
		$this->add_render_attribute( 'animated-text', 'data-animated-text-delay', $settings['borderless_elementor_animated_text_type_speed'] );
		$this->add_render_attribute( 'animated-text', 'data-animated-text-delete-speed', $settings['borderless_elementor_animated_text_back_speed'] );
		$this->add_render_attribute( 'animated-text', 'data-animated-text-pause-for', $settings['borderless_elementor_animated_text_pause_for'] );
		$this->add_render_attribute( 'animated-text', 'data-animated-text-cursor', $settings['borderless_elementor_animated_text_cursor_mark'] );
		$this->add_render_attribute( 'animated-text', 'data-animated-text-loop', $settings['borderless_elementor_animated_text_loop'] );

		?>

		<div class="borderless-elementor-animated-text-widget">

			<div class="borderless-elementor-animated-text">

				<?php if ( ! empty( $settings['borderless_elementor_animated_text_prefix'] ) ) { ?>
					<span class="borderless-elementor-animated-text-prefix"><?php echo wp_kses( ( $settings['borderless_elementor_animated_text_prefix'] ), true ); ?></span>
				<?php } ?>
				
				<?php if ( ! empty( $strings ) ) { ?>
					<span class="borderless-elementor-animated-text-strings" <?php echo $this->get_render_attribute_string( 'animated-text' ) ?>></span>
				<?php } ?>

				<?php if ( ! empty( $settings['borderless_elementor_animated_text_suffix'] ) ) { ?>
					<span class="borderless-elementor-animated-text-suffix"><?php echo wp_kses( ( $settings['borderless_elementor_animated_text_suffix'] ), true ); ?></span>
				<?php } ?>

			</div>

		</div>

		<?php

	}
	
	protected function _content_template() {

    }
	
	
}