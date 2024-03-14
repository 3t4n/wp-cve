<?php
/**
 * Number widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

defined( 'ABSPATH' ) || die();

class Number extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Number', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-madel';
	}

	public function get_keywords() {
		return [ 'number', 'animate', 'text' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_number',
			[
				'label' => __( 'Number', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'number_text',
			[
				'label' => __( 'Text', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::TEXT,
				'default' => 7,
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

        $this->add_control(
            'animate_number',
            [
                'label' => __( 'Animate', 'skt-addons-elementor' ),
                'description' => __( 'Only number is animatable' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'animate_duration',
            [
                'label' => __( 'Duration', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 10,
                'default' => 500,
                'condition' => [
                    'animate_number!' => ''
                ],
            ]
        );

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__number_bg_style_controls();
		$this->__text_style_controls();
	}

	protected function __number_bg_style_controls() {

		$this->start_controls_section(
			'number_background_style',
			[
				'label' => __( 'General', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'number_width_height',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-number-body' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'number_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-number-body ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'number_border',
                'label' => __( 'Border', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-number-body',
            ]
        );

        $this->add_control(
            'number_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-number-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'number_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-number-body',
			]
		);

		$this->add_responsive_control(
			'number_align',
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
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .skt-number-body'  => '{{VALUE}};'
				],
                'selectors_dictionary' => [
                    'left' => 'float: left',
                    'center' => 'margin: 0 auto',
                    'right' => 'float:right'
                ],
			]
		);

        $this->add_control(
            '_heading_bg',
            [
                'label' => __( 'Background', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'number_background_color',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .skt-number-body',
            ]
        );

        $this->add_control(
                '_heading_bg_overlay',
                [
                    'label' => __( 'Background Overaly', 'skt-addons-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'number_background_overlay_color',
                'label' => __( 'Background', 'skt-addons-elementor' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .skt-number-overlay',
            ]
        );

        $this->add_control(
            'number_background_overlay_blend_mode',
            [
                'label' => __( 'Blend Mood', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => skt_addons_elementor_get_css_blend_modes(),
                'selectors' => [
                    '{{WRAPPER}} .skt-number-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_background_overlay_blend_mode_opacity',
            [
                'label' => __( 'Opacity', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => .1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-number-overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );

		$this->end_controls_section();
	}

	protected function __text_style_controls() {

        $this->start_controls_section(
            '_section_style_text',
            [
                'label' => __( 'Text', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'number_text_color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-number-body' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_text_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-number-text',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'number_text_shadow',
                'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
                'selector' => '{{WRAPPER}} .skt-number-text',
            ]
        );

        $this->add_control(
            'number_text_rotate',
            [
                'label' => __( 'Text Rotate', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-number-text' => '-webkit-transform: rotate({{SIZE}}deg);-ms-transform: rotate({{SIZE}}deg);transform: rotate({{SIZE}}deg);'
                ],
            ]
        );

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'number_text', 'class', 'skt-number-text' );
		$number = $settings['number_text'];

		if ( $settings['animate_number'] ) {
		    $data = [
		        'toValue' => intval( $settings['number_text'] ),
                'duration' => intval( $settings['animate_duration'] ),
            ];
		    $this->add_render_attribute( 'number_text', 'data-animation', wp_json_encode( $data ) );
            $number = 0;
        }
        ?>

		<div class="skt-number-body">
			<div class="skt-number-overlay"></div>
			<span <?php $this->print_render_attribute_string( 'number_text' ); ?>><?php echo esc_html( $number ); ?></span>
		</div>

		<?php
	}
}