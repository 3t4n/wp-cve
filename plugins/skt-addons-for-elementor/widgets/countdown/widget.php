<?php
/**
 * Countdown
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Control_Media;

defined('ABSPATH') || die();

class Countdown extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Countdown', 'skt-addons-elementor');
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
		return 'skti skti-refresh-time';
	}

	public function get_keywords() {
		return ['countdown', 'timer'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__time_content_controls();
		$this->__settings_content_controls();
		$this->__end_action_content_controls();
	}

	protected function __time_content_controls() {

		$this->start_controls_section(
			'_section_time',
			[
				'label' => __('Time', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'due_date',
			[
				'label' => __('Time', 'skt-addons-elementor'),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date("Y-m-d", strtotime("+ 1 day")),
				'description' => esc_html__('Set the due date and time', 'skt-addons-elementor'),
			]
		);
		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

		$this->start_controls_section(
			'_section_countdown_settings',
			[
				'label' => __('Countdown Settings', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'label_position',
			[
				'label' => __('Label Position', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'right' => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon' => 'eicon-h-align-right',
					],
					'bottom' => [
						'title' => __('Bottom', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'toggle' => false,
				'default' => 'bottom',
				'prefix_class' => 'skt-countdown-label-',
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'label_space',
			[
				'label' => __('Label Space', 'skt-addons-elementor'),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'label_position' => 'right',
				],
                'style_transfer' => true,
			]
		);
		$this->start_popover();
		$this->add_control(
			'label_space_top',
			[
				'label' => __('Label Space Top', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-countdown-label-right .skt-countdown-item .skt-countdown-label' => 'top: {{SIZE || 0}}{{UNIT}};',
				],
				'condition' => [
					'label_position' => 'right',
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'label_space_left',
			[
				'label' => __('Label Space Left', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.skt-countdown-label-right .skt-countdown-item .skt-countdown-label' => 'left: {{SIZE || 0}}{{UNIT}};',
				],
				'condition' => [
					'label_position' => 'right',
				],
                'style_transfer' => true,
			]
		);
		$this->end_popover(); //End Prover

		$this->add_control(
			'show_label_days',
			[
				'label' => esc_html__('Show Label Days?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'label_days',
			[
				'label' => esc_html__('Label Days', 'skt-addons-elementor'),
				'description' => esc_html__('Set the label for days.', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __('Days', 'skt-addons-elementor'),
				'default' => 'Days',
				'condition' => [
					'show_label_days' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_label_hours',
			[
				'label' => esc_html__('Show Label Hours?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'label_hours',
			[
				'label' => esc_html__('Label Hours', 'skt-addons-elementor'),
				'description' => esc_html__('Set the label for hours.', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __('Hours', 'skt-addons-elementor'),
				'default' => 'Hours',
				'condition' => [
					'show_label_hours' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_label_minutes',
			[
				'label' => esc_html__('Show Label Minutes?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'label_minutes',
			[
				'label' => esc_html__('Label Minutes', 'skt-addons-elementor'),
				'description' => esc_html__('Set the label for minutes.', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __('Minutes', 'skt-addons-elementor'),
				'default' => 'Minutes',
				'condition' => [
					'show_label_minutes' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_label_seconds',
			[
				'label' => esc_html__('Show Label Seconds?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'label_seconds',
			[
				'label' => esc_html__('Label Seconds', 'skt-addons-elementor'),
				'description' => esc_html__('Set the label for seconds.', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __('Seconds', 'skt-addons-elementor'),
				'default' => 'Seconds',
				'condition' => [
					'show_label_seconds' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'align',
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
					]
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}}'
				]
			]
		);
		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__('Show Separator?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'on',
				'default' => '',
				'separator' => 'before',
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'separator',
			[
				'label' => __('Separator', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => ':',
				'condition' => [
					'show_separator' => 'on',
				],
			]
		);
		$this->add_control(
			'separator_color',
			[
				'label' => __('Separator Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item.skt-countdown-separator-on .skt-countdown-separator' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_separator' => 'on',
				],
                'style_transfer' => true,
			]
		);
		$this->add_responsive_control(
			'separator_font',
			[
				'label' => __('Separator Font Size', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item.skt-countdown-separator-on .skt-countdown-separator' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'on',
				],
                'style_transfer' => true,
			]
		);
		$this->add_control(
			'separator_position',
			[
				'label' => __('Separator Position', 'skt-addons-elementor'),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'show_separator' => 'on',
				],
                'style_transfer' => true,
			]
		);

		$this->start_popover();
		$this->add_control(
			'separator_position_top',
			[
				'label' => __('Position Top', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item.skt-countdown-separator-on .skt-countdown-separator' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'on'
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'separator_position_right',
			[
				'label' => __('Position Right', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item.skt-countdown-separator-on .skt-countdown-separator' => 'right: {{SIZE || -16}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'on'
				],
                'style_transfer' => true,
			]
		);

		$this->end_popover();

		$this->end_controls_section();
	}

	protected function __end_action_content_controls() {

		$this->start_controls_section(
			'_section_end_action',
			[
				'label' => __('End Action', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'end_action_type',
			[
				'label' => esc_html__('End Action Type', 'skt-addons-elementor'),
				'label_block' => false,
				'type' => Controls_Manager::SELECT,
				'description' => esc_html__('Choose which action you want to at the end of countdown.', 'skt-addons-elementor'),
				'options' => [
					'none' => esc_html__('None', 'skt-addons-elementor'),
					'message' => esc_html__('Message', 'skt-addons-elementor'),
					'url' => esc_html__('Redirection Link', 'skt-addons-elementor'),
					'img' => esc_html__('Image', 'skt-addons-elementor'),
				],
				'default' => 'none'
			]
		);
		$this->add_control(
			'end_message',
			[
				'label' => __('Countdown End Message', 'skt-addons-elementor'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __('Countdown End!', 'skt-addons-elementor'),
				'placeholder' => __('Type your message here', 'skt-addons-elementor'),
				'condition' => [
					'end_action_type' => 'message'
				],
			]
		);
		$this->add_control(
			'end_redirect_link',
			[
				'label' => __('Redirection Link', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __('https://example.com/', 'skt-addons-elementor'),
				'condition' => [
					'end_action_type' => 'url'
				],
			]
		);

		$this->add_control(
			'end_image',
			[
				'label' => __('Image', 'skt-addons-elementor'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'end_action_type' => 'img'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'end_image_size',
				'default' => 'large',
				'separator' => 'none',
				'condition' => [
					'end_action_type' => 'img'
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
		$this->__days_style_controls();
		$this->__hours_style_controls();
		$this->__minutes_style_controls();
		$this->__seconds_style_controls();
	}

	protected function __common_style_controls() {

		$this->start_controls_section(
			'_section_common_style',
			[
				'label' => __('Countdown Common Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_width',
			[
				'label' => __('Box Width', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'box_height',
			[
				'label' => __('Box Height', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'common_box_bg',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-countdown-item',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => __('Box Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-countdown-item',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'box_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow',
				'label' => __('Box Shadow', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-countdown-item',
			]
		);
		$this->add_control(
			'common_box_time_color',
			[
				'label' => __('Time Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-time' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'common_box_time_typography',
				'label' => __('Time Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => ['']
				],
				'selector' => '{{WRAPPER}} .skt-countdown-time',
			]
		);
		$this->add_control(
			'common_box_label_color',
			[
				'label' => __('Label Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-label' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'common_box_label_typography',
				'label' => __('Label Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => [''],
				],
				'selector' => '{{WRAPPER}} .skt-countdown-label',
			]
		);
		$this->add_responsive_control(
			'common_box_spacing',
			[
				'label' => __('Spacing Between Box', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'box_padding',
			[
				'label' => __('Box Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __days_style_controls() {

		$this->start_controls_section(
			'_section_days_style',
			[
				'label' => __('Days Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'days_bg',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-days',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'days_border',
				'label' => __('Box Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-days',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'days_time_color',
			[
				'label' => __('Time Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-days .skt-countdown-time' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'days_time_typography',
				'label' => __('Time Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => ['']
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-days .skt-countdown-time',
			]
		);
		$this->add_control(
			'days_label_color',
			[
				'label' => __('Label Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-days .skt-countdown-label' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'days_label_typography',
				'label' => __('Label Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => [''],
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-days .skt-countdown-label',
			]
		);

		$this->end_controls_section();
	}

	protected function __hours_style_controls() {

		$this->start_controls_section(
			'_section_hours_style',
			[
				'label' => __('Hours Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hours_bg',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-hours',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'hours_border',
				'label' => __('Box Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-hours',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'hours_time_color',
			[
				'label' => __('Time Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-hours .skt-countdown-time' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hours_time_typography',
				'label' => __('Time Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => ['']
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-hours .skt-countdown-time',
			]
		);
		$this->add_control(
			'hours_label_color',
			[
				'label' => __('Label Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-hours .skt-countdown-label' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hours_label_typography',
				'label' => __('Label Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => [''],
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-hours .skt-countdown-label',
			]
		);

		$this->end_controls_section();
	}

	protected function __minutes_style_controls() {

		$this->start_controls_section(
			'_section_minutes_style',
			[
				'label' => __('Minutes Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'minutes_bg',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-minutes',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'minutes_border',
				'label' => __('Box Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-minutes',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'minutes_time_color',
			[
				'label' => __('Time Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-minutes .skt-countdown-time' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'minutes_time_typography',
				'label' => __('Time Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => ['']
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-minutes .skt-countdown-time',
			]
		);
		$this->add_control(
			'minutes_label_color',
			[
				'label' => __('Label Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-minutes .skt-countdown-label' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'minutes_label_typography',
				'label' => __('Label Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => [''],
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-minutes .skt-countdown-label',
			]
		);

		$this->end_controls_section();
	}

	protected function __seconds_style_controls() {

		$this->start_controls_section(
			'_section_seconds_style',
			[
				'label' => __('Seconds Style', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'seconds_bg',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-seconds',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'seconds_border',
				'label' => __('Box Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-countdown-item.skt-countdown-item-seconds',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'seconds_time_color',
			[
				'label' => __('Time Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-seconds .skt-countdown-time' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'seconds_time_typography',
				'label' => __('Time Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => ['']
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-seconds .skt-countdown-time',
			]
		);
		$this->add_control(
			'seconds_label_color',
			[
				'label' => __('Label Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-countdown-item-seconds .skt-countdown-label' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'seconds_label_typography',
				'label' => __('Label Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'exclude' => [
					'line_height'
				],
				'default' => [
					'font_size' => [''],
				],
				'selector' => '{{WRAPPER}} .skt-countdown-item-seconds .skt-countdown-label',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$due_date = date("M d Y G:i:s", strtotime($settings['due_date']));
		$this->add_render_attribute('skt-countdown', 'class', 'skt-countdown');
		$this->add_render_attribute('skt-countdown', 'data-date', esc_attr($due_date));
		$this->add_render_attribute('skt-countdown', 'data-end-action', esc_attr($settings['end_action_type']));
		if ('url' === $settings['end_action_type'] && $settings['end_redirect_link']) {
			$this->add_render_attribute('skt-countdown', 'data-redirect-link', esc_url($settings['end_redirect_link']));
		}
		$this->add_render_attribute('days', 'class', 'skt-countdown-item skt-countdown-item-days');
		$this->add_render_attribute('hours', 'class', 'skt-countdown-item skt-countdown-item-hours');
		$this->add_render_attribute('minutes', 'class', 'skt-countdown-item skt-countdown-item-minutes');
		$this->add_render_attribute('seconds', 'class', 'skt-countdown-item skt-countdown-item-seconds');
		if ('on' == $settings['show_separator']) {
			$this->add_render_attribute('days', 'class', 'skt-countdown-separator-on');
			$this->add_render_attribute('hours', 'class', 'skt-countdown-separator-on');
			$this->add_render_attribute('minutes', 'class', 'skt-countdown-separator-on');
			$this->add_render_attribute('seconds', 'class', 'skt-countdown-separator-on');
		}
		?>
		<?php if (!empty($due_date)): ?>
			<div class="skt-countdown-wrap">
				<div <?php $this->print_render_attribute_string('skt-countdown'); ?>>
					<div <?php $this->print_render_attribute_string('days'); ?>>
						<span data-days class="skt-countdown-time skt-countdown-days">0</span>
						<?php if ('yes' == $settings['show_label_days'] && !empty($settings['label_days'])): ?>
							<span
								class="skt-countdown-label skt-countdown-label-days"><?php echo esc_html($settings['label_days']); ?></span>
						<?php endif; ?>
						<?php if ('on' == $settings['show_separator'] && !empty($settings['separator'])): ?>
							<span class="skt-countdown-separator"><?php echo esc_attr($settings['separator']); ?></span>
						<?php endif; ?>
					</div>
					<div <?php $this->print_render_attribute_string('hours'); ?>>
						<span class="skt-countdown-time skt-countdown-hours" data-hours>0</span>
						<?php if ('yes' == $settings['show_label_hours'] && !empty($settings['label_hours'])): ?>
							<span
								class="skt-countdown-label skt-countdown-label-hours"><?php echo esc_html($settings['label_hours']); ?></span>
						<?php endif; ?>
						<?php if ('on' == $settings['show_separator'] && !empty($settings['separator'])): ?>
							<span class="skt-countdown-separator"><?php echo esc_attr($settings['separator']); ?></span>
						<?php endif; ?>
					</div>
					<div <?php $this->print_render_attribute_string('minutes'); ?>>
						<span class="skt-countdown-time skt-countdown-minutes" data-minutes>0</span>
						<?php if ('yes' == $settings['show_label_minutes'] && !empty($settings['label_minutes'])): ?>
							<span
								class="skt-countdown-label skt-countdown-label-minutes"><?php echo esc_html($settings['label_minutes']); ?></span>
						<?php endif; ?>
						<?php if ('on' == $settings['show_separator'] && !empty($settings['separator'])): ?>
							<span class="skt-countdown-separator"><?php echo esc_attr($settings['separator']); ?></span>
						<?php endif; ?>
					</div>
					<div <?php $this->print_render_attribute_string('seconds'); ?>>
						<span class="skt-countdown-time skt-countdown-seconds" data-seconds>0</span>
						<?php if ('yes' == $settings['show_label_seconds'] && !empty($settings['label_seconds'])): ?>
							<span
								class="skt-countdown-label skt-countdown-label-seconds"><?php echo esc_html($settings['label_seconds']); ?></span>
						<?php endif; ?>
					</div>
					<!--End action markup-->
					<?php if ('none' != $settings['end_action_type'] && !empty($settings['end_action_type'])): ?>
						<div class="skt-countdown-end-action">
							<?php if ('message' == $settings['end_action_type'] && $settings['end_message']) :
								echo '<div class="skt-countdown-end-message">' . wpautop(wp_kses_post($settings['end_message'])) . '</div>';
							endif; ?>
							<?php if ('img' == $settings['end_action_type'] && ($settings['end_image']['url'] || $settings['end_image']['id'])) :
								$this->add_render_attribute('image', 'src', $settings['end_image']['url']);
								$this->add_render_attribute('image', 'alt', Control_Media::get_image_alt($settings['end_image']));
								$this->add_render_attribute('image', 'title', Control_Media::get_image_title($settings['end_image']));
								?>
								<figure class="skt-countdown-end-action-image">
									<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($settings, 'end_image_size', 'end_image')); ?>
								</figure>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}
}