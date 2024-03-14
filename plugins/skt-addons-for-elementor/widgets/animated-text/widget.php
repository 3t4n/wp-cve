<?php
/**
 * animated-text
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Text_Shadow;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Foreground;

defined('ABSPATH') || die();

class Animated_Text extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Animated Text', 'skt-addons-elementor');
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
		return 'skti skti-text-animation';
	}

	public function get_keywords() {
		return ['animated-text', 'animated', 'text'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__animated_text_content_controls();
		$this->__settings_content_controls();
	}

	protected function __animated_text_content_controls() {

		$this->start_controls_section(
			'_section_animated_text',
			[
				'label' => __('Content', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'animation_type',
			[
				'label' => __('Animation Type', 'skt-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'rotate-1',
				'options' => [
					'rotate-1' => __('Rotate 1', 'skt-addons-elementor'),
					'letters type' => __('Type', 'skt-addons-elementor'),
					'letters rotate-2' => __('Rotate 2', 'skt-addons-elementor'),
					'loading-bar' => __('Loading Bar', 'skt-addons-elementor'),
					'slide' => __('Slide', 'skt-addons-elementor'),
					'clip' => __('Clip', 'skt-addons-elementor'),
					'zoom' => __('Zoom', 'skt-addons-elementor'),
					'letters rotate-3' => __('Rotate 3', 'skt-addons-elementor'),
					'letters scale' => __('Scale', 'skt-addons-elementor'),
					'push' => __('Push', 'skt-addons-elementor'),
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'before_text',
			[
				'label' => __('Before Text', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => __('SKT Addons is', 'skt-addons-elementor'),
				'placeholder' => __('Before Text', 'skt-addons-elementor'),
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$this->add_control(
			'after_text',
			[
				'label' => __('After Text', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __('After Text', 'skt-addons-elementor'),
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text', [
				'label' => __('Text', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Awesome', 'skt-addons-elementor'),
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$repeater->add_control(
			'text_customize',
			[
				'label' => __('Want To Customize Text?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'skt-addons-elementor'),
				'label_off' => __('No', 'skt-addons-elementor'),
				'return_value' => 'yes',
                'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'text_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text > {{CURRENT_ITEM}} ' => 'color: {{VALUE}}; -webkit-background-clip: initial; -webkit-text-fill-color:initial; background: none;',
					'{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text > {{CURRENT_ITEM}} i' => 'color: {{VALUE}}; -webkit-background-clip: initial; -webkit-text-fill-color:initial; background: none;',
					'{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text > {{CURRENT_ITEM}} i em' => 'color: {{VALUE}}; -webkit-background-clip: initial; -webkit-text-fill-color:initial; background: none;',
				],
				'condition' => [
					'text_customize' => 'yes'
				],
                'style_transfer' => true,
			]
		);


		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'exclude' => [
					'font_size',
					'line_height',
				],
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text > {{CURRENT_ITEM}}, {{WRAPPER}} .skt-animated-text-wrap .skt-animated-text > {{CURRENT_ITEM}} i, {{WRAPPER}} .skt-animated-text-wrap .skt-animated-text > {{CURRENT_ITEM}} em',
				'condition' => [
					'text_customize' => 'yes'
				],
                'style_transfer' => true,
			]
		);

		$repeater->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'label' => __('Text Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-animated-text > {{CURRENT_ITEM}}',
				'condition' => [
					'text_customize' => 'yes'
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'animated_text',
			[
				'label' => __('Animated Text', 'skt-addons-elementor'),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => __('Awesome', 'skt-addons-elementor'),
					],
					[
						'text' => __('Cool', 'skt-addons-elementor'),
					],
					[
						'text' => __('Nice', 'skt-addons-elementor'),
					],
				],
				'title_field' => '{{{ text }}}',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __('HTML Tag', 'skt-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => __('H1', 'skt-addons-elementor'),
					'h2' => __('H2', 'skt-addons-elementor'),
					'h3' => __('H3', 'skt-addons-elementor'),
					'h4' => __('H4', 'skt-addons-elementor'),
					'h5' => __('H5', 'skt-addons-elementor'),
					'h6' => __('H6', 'skt-addons-elementor'),
					'p' => __('P', 'skt-addons-elementor'),
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __('Alignment', 'skt-addons-elementor'),
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
					'justify' => [
						'title' => __('Justify', 'skt-addons-elementor'),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .cd-headline' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

		$this->start_controls_section(
			'_section_animation_settings',
			[
				'label' => __('Animation Settings', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'animation_type',
							'operator' => '!=',
							'value' => 'letters type',
						],
						[
							'name' => 'animation_type',
							'operator' => '!=',
							'value' => 'loading-bar',
						],
						[
							'name' => 'animation_type',
							'operator' => '!=',
							'value' => 'clip',
						],
					],
				],
			]
		);

		$this->add_control(
			'animation_delay',
			[
				'label' => __('Animation Delay', 'skt-addons-elementor'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1000,
				'step' => 100,
				'max' => 30000,
				'default' => 2500,
				'description' => __('Animation Delay in milliseconds. Min 1000 and Max 30000.', 'skt-addons-elementor'),
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'animation_type',
							'operator' => '!=',
							'value' => 'letters type',
						],
						[
							'name' => 'animation_type',
							'operator' => '!=',
							'value' => 'loading-bar',
						],
						[
							'name' => 'animation_type',
							'operator' => '!=',
							'value' => 'clip',
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__before_text_style_controls();
		$this->__after_text_style_controls();
		$this->__animated_text_style_controls();
	}

	protected function __common_style_controls() {

		$this->start_controls_section(
			'_section_animated_text_common_style',
			[
				'label' => __('Common Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'common_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-animated-text-wrap' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'common_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'exclude' => [
					'line_height',
				],
				'default' => [
					'font_size' => ['']
				],
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap, {{WRAPPER}} .skt-animated-text-wrap b, {{WRAPPER}} .skt-animated-text-wrap i, {{WRAPPER}} .skt-animated-text-wrap em',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'common_shadow',
				'label' => __('Text Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap',
			]
		);

		$this->add_control(
			'loading_bar_color',
			[
				'label' => __('Loading Bar Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-animated-text-wrap.loading-bar .skt-animated-text::after' => 'background: {{VALUE}}',
				],
				'condition' => [
					'animation_type' => 'loading-bar'
				]
			]
		);

		$this->add_control(
			'cursor_color',
			[
				'label' => __('Cursor Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-animated-text-wrap.clip .skt-animated-text::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .skt-animated-text-wrap.type .skt-animated-text::after' => 'background-color: {{VALUE}}',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'animation_type',
							'operator' => '==',
							'value' => [
								'clip',
							],
						],
						[
							'name' => 'animation_type',
							'operator' => '==',
							'value' => [
								'letters type',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'select_text_color',
			[
				'label' => __('Select Text Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-animated-text-wrap.type .skt-animated-text.selected' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'animation_type' => 'letters type',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __before_text_style_controls() {

		$this->start_controls_section(
			'_section_animated_text_before_style',
			[
				'label' => __('Before Text Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name' => 'before_text_color',
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-before-text',
			]
		);

		$this->add_control(
			'before_text_color_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'before_text_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'exclude' => [
					'font_size',
					'line_height',
				],
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-before-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'before_text_shadow',
				'label' => __('Text Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-before-text',
			]
		);

		$this->end_controls_section();
	}

	protected function __after_text_style_controls() {

		$this->start_controls_section(
			'_section_animated_text_after_style',
			[
				'label' => __('After Text Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name' => 'after_text_color',
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-after-text',
			]
		);

		$this->add_control(
			'after_text_color_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'after_text_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'exclude' => [
					'font_size',
					'line_height',
				],
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-after-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'after_text_shadow',
				'label' => __('Text Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-after-text',
			]
		);

		$this->end_controls_section();
	}

	protected function __animated_text_style_controls() {

		$this->start_controls_section(
			'_section_animated_text_style',
			[
				'label' => __('Animated Text Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name' => 'animated_text_color',
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text b,{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text i,{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text em',
			]
		);

		$this->add_control(
			'animated_text_color_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'animated_text_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'exclude' => [
					'font_size',
					'line_height',
				],
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text b,{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text i,{{WRAPPER}} .skt-animated-text-wrap .skt-animated-text em',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'animated_text_shadow',
				'label' => __('Text Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-animated-text-wrap b',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$animation_type = $settings['animation_type'];
		$this->add_render_attribute( 'skt-animated', 'class',
            [
                'skt-animated-text-wrap',
                'cd-headline',
                $animation_type
            ]
        );

		if ( $animation_type &&
            'letters type' !== $animation_type &&
            'letters type' !== $animation_type &&
            'clip' !== $animation_type
        ) {
			$this->add_render_attribute( 'skt-animated', 'data-animation-delay', $settings['animation_delay'] );
		}

		$animated_text = '';

        if ( $settings['before_text'] ) {
			$this->add_render_attribute( 'skt-animated', 'class', 'skt-animated-has-before-text' );
            $animated_text .= sprintf( '<span class="skt-animated-before-text">%s</span>', esc_html( $settings['before_text'] ) );
        }

        if ( $settings['animated_text'] && is_array( $settings['animated_text'] ) ) {
            $animated_animation_text = '';

            foreach ( $settings['animated_text'] as $key => $item ) {
                $animated_animation_text .= sprintf(
                    '<b class="elementor-repeater-item-%s">%s</b>',
                    esc_attr( $item['_id'] . ( $key === 0 ? ' is-visible' : '' ) ),
                    esc_html( $item['text'] )
                );
            }

            $animated_text .= sprintf(
                ' <span class="skt-animated-text cd-words-wrapper">%s</span>',
                $animated_animation_text
            );
        }

        if ( $settings['after_text'] ) {
			$this->add_render_attribute( 'skt-animated', 'class', 'skt-animated-has-after-text' );
            $animated_text .= sprintf( ' <span class="skt-animated-after-text">%s</span>', esc_html( $settings['after_text'] ) );
        }

		printf(
            '<%1$s %2$s>%3$s</%1$s>',
            skt_addons_elementor_escape_tags( $settings['html_tag'] ),
            $this->get_render_attribute_string( 'skt-animated' ),
            $animated_text
            );
	}
}