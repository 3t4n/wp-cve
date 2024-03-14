<?php

/**
 * Image Swap
 *
 * @package Skt_Addons
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Utils;

defined('ABSPATH') || die();

class Image_Swap extends Base {

	public function get_title() {
		return __('Image Swap', 'skt-addons-elementor');
	}

	public function get_icon() {
		return 'skti skti-image-scroll';
	}

	public function get_keywords() {
		return ['image', 'image-swap', 'swap'];
	}

	protected function register_content_controls() {

		$this->content_controls();

	}

	protected function register_style_controls() {

		$this->style_controls();
	}

	protected function content_controls() {

		$this->start_controls_section(
			'_section_content',
			[
				'label' => __('Content', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'select_effect_type',
			[
				'label'   => __('Select Effect type', 'skt-addons-elementor'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Default', 'skt-addons-elementor'),
					'slide'   => __('Slide', 'skt-addons-elementor'),
				],
			]
		);

		$this->add_control(
			'first_image',
			[
				'type'    => Controls_Manager::MEDIA,
				'label'   => __('First Image', 'skt-addons-elementor'),
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'second_image',
			[
				'type'    => Controls_Manager::MEDIA,
				'label'   => __('Second Image', 'skt-addons-elementor'),
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'swip_trigger',
			[
				'type'    => Controls_Manager::CHOOSE,
				'label'   => __('Trigger', 'skt-addons-elementor'),
				'options' => [
					'hover' => [
						'title' => __('Hover', 'skt-addons-elementor'),
						'icon'  => 'skti skti-cursor-hover-click',
					],
					'click' => [
						'title' => __('Click', 'skt-addons-elementor'),
						'icon'  => 'eicon-click',
					],
				],
				'default' => 'hover',
				'condition' => [
					'select_effect_type' => 'default',
				],
			]
		);

		$this->add_control(
			'ig_effects',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => __('Effect', 'skt-addons-elementor'),
				'options'   => [
					'fade'        => __('Fade', 'skt-addons-elementor'),
					'move_left'   => __('Move Left', 'skt-addons-elementor'),
					'move_top'    => __('Move Top', 'skt-addons-elementor'),
					'move_right'  => __('Move Right', 'skt-addons-elementor'),
					'move_bottom' => __('Move Bottom', 'skt-addons-elementor'),
					'zoom_in'     => __('Zoom In', 'skt-addons-elementor'),
					'zoom_out'    => __('Zoom Out', 'skt-addons-elementor'),
					'card_left'   => __('Card Left', 'skt-addons-elementor'),
					'card_top'    => __('Card Top', 'skt-addons-elementor'),
					'card_right'  => __('Card Right', 'skt-addons-elementor'),
					'card_bottom' => __('Card Bottom', 'skt-addons-elementor'),
				],
				'default'   => 'fade',
				'condition' => [
					'select_effect_type' => 'default',
				],
			]
		);

		$this->add_control(
			'ig_effects_slides',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => __('Effect', 'skt-addons-elementor'),
				'options'   => [
					'top'  => __('Slide Top', 'skt-addons-elementor'),
					'bottom'  => __('Slide Bottom', 'skt-addons-elementor'),
					'right' => __('Slide Right', 'skt-addons-elementor'),
					'left'  => __('Slide Left', 'skt-addons-elementor'),
				],
				'default'   => 'right',
				'condition' => [
					'select_effect_type' => 'slide',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => __('Transition Speed', 'skt-addons-elementor'),
				'description' => __('Note: Here animation speed is in seconds. Default is 0.5s', 'skt-addons-elementor'),
				'min'         => 0,
				'max'         => 10,
				'step'        => 0.1,
				'default'     => 0.5,
				'after'       => 's',
				'selectors'   => [
					'{{WRAPPER}} .skt-image-swap-wrapper__inside img' => '-webkit-transition: {{VALUE}}s;',
					'{{WRAPPER}} .skt-image-swap-wrapper__inside img' => 'transition: {{VALUE}}s;',
					'{{WRAPPER}} .skt-image-swap-wrapper'             => '--animation_speed: {{VALUE}}s;',
					'{{WRAPPER}}'                                    => '--animation_speed: {{VALUE}}s;',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function style_controls() {

		$this->start_controls_section(
			'_ig_style_section',
			[
				'label' => __('Image', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// $this->add_responsive_control(
		// 	'container_height',
		// 	[
		// 		'label'          => __('Container Height', 'skt-addons-elementor'),
		// 		'type'           => Controls_Manager::SLIDER,
		// 		'default'        => [
		// 			'unit' => 'px',
		// 			'size' => 400
		// 		],
		// 		'tablet_default' => [
		// 			'unit' => 'px',
		// 		],
		// 		'mobile_default' => [
		// 			'unit' => 'px',
		// 		],
		// 		'size_units'     => ['px', 'vh', '%'],
		// 		'range'          => [
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 1000,
		// 			],
		// 			'%' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 			'vh' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'selectors'      => [
		// 			// '{{WRAPPER}} .skt-image-swap-wrapper img' => 'height: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}} .skt_img_main_wrapper_top'     => 'height: {{SIZE}}{{UNIT}};',
		// 		],
		// 		'separator' => 'after'
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'width',
		// 	[
		// 		'label'          => __('Width', 'skt-addons-elementor'),
		// 		'type'           => Controls_Manager::SLIDER,
		// 		'default'        => [
		// 			'unit' => '%',
		// 		],
		// 		'tablet_default' => [
		// 			'unit' => '%',
		// 		],
		// 		'mobile_default' => [
		// 			'unit' => '%',
		// 		],
		// 		'size_units'     => ['%', 'px', 'vw'],
		// 		'range'          => [
		// 			'%'  => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 1000,
		// 			],
		// 			'vw' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'selectors'      => [
		// 			// '{{WRAPPER}} .skt-image-swap-wrapper img' => 'width: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}} .skt-image-swap-wrapper'  => 'width: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}} .skt-image-swap-ctn'      => 'width: {{SIZE}}{{UNIT}};',
		// 			// '{{WRAPPER}} .skt-image-swap-item' => 'width: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'space',
		// 	[
		// 		'label'          => __('Max Width', 'skt-addons-elementor'),
		// 		'type'           => Controls_Manager::SLIDER,
		// 		'default'        => [
		// 			'unit' => '%',
		// 		],
		// 		'tablet_default' => [
		// 			'unit' => '%',
		// 		],
		// 		'mobile_default' => [
		// 			'unit' => '%',
		// 		],
		// 		'size_units'     => ['%', 'px', 'vw'],
		// 		'range'          => [
		// 			'%'  => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 1000,
		// 			],
		// 			'vw' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'selectors'      => [
		// 			'{{WRAPPER}} .skt-image-swap-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}} .skt-image-swap-ctn'     => 'max-width: {{SIZE}}{{UNIT}};',
		// 			// '{{WRAPPER}} .skt-image-swap-wrapper img' => 'max-width: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'height',
		// 	[
		// 		'label'          => __('Height', 'skt-addons-elementor'),
		// 		'type'           => Controls_Manager::SLIDER,
		// 		'default'        => [
		// 			'unit' => '%',
		// 			'size' => 100
		// 		],
		// 		'tablet_default' => [
		// 			'unit' => 'px',
		// 		],
		// 		'mobile_default' => [
		// 			'unit' => 'px',
		// 		],
		// 		'size_units'     => ['%', 'px', 'vh'],
		// 		'range'          => [
		// 			'%'  => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 			'px' => [
		// 				'min' => 1,
		// 				'max' => 1000,
		// 			],
		// 			'vh' => [
		// 				'min' => 1,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'selectors'      => [
		// 			'{{WRAPPER}} .skt-image-swap-wrapper img' => 'height: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}} .skt-image-swap-ctn img'     => 'height: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// // $this->add_responsive_control(
		// // 	'image_align',
		// // 	[
		// // 		'label'          => __( 'Position', 'skt-addons-elementor' ),
		// // 		'type'           => Controls_Manager::SLIDER,
		// // 		'size_units'     => [ '%' ],
		// // 		'range'          => [
		// // 			'%' => [
		// // 				'min' => 0,
		// // 				'max' => 100,
		// // 			]
		// // 		],
		// // 		'selectors'      => [
		// // 			'{{WRAPPER}} .skt-image-swap-wrapper__inside' => 'left: {{SIZE}}{{UNIT}};',
		// // 		],
		// // 	]
		// // );

		// $this->add_responsive_control(
		// 	'image_align',
		// 	[
		// 		'label'   => __('Alignment', 'skt-addons-elementor'),
		// 		'type'    => Controls_Manager::CHOOSE,
		// 		'options' => [
		// 			'align_left'   => [
		// 				'title' => __('Left', 'skt-addons-elementor'),
		// 				'icon'  => 'eicon-text-align-left',
		// 			],
		// 			'align_center' => [
		// 				'title' => __('Center', 'skt-addons-elementor'),
		// 				'icon'  => 'eicon-text-align-center',
		// 			],
		// 			'align_right'  => [
		// 				'title' => __('Right', 'skt-addons-elementor'),
		// 				'icon'  => 'eicon-text-align-right',
		// 			],
		// 		],
		// 		'default' => 'align_center',
		// 		'toggle'  => false,
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'object-fit',
		// 	[
		// 		'label'     => __('Object Fit', 'skt-addons-elementor'),
		// 		'type'      => Controls_Manager::SELECT,
		// 		'condition' => [
		// 			'height[size]!' => '',
		// 		],
		// 		'options'   => [
		// 			''        => __('Default', 'skt-addons-elementor'),
		// 			'fill'    => __('Fill', 'skt-addons-elementor'),
		// 			'cover'   => __('Cover', 'skt-addons-elementor'),
		// 			'contain' => __('Contain', 'skt-addons-elementor'),
		// 		],
		// 		'default'   => '',
		// 		'selectors' => [
		// 			'{{WRAPPER}} .skt-image-swap-wrapper img' => 'object-fit: {{VALUE}};',
		// 			'{{WRAPPER}} .skt-image-swap-ctn img'     => 'object-fit: {{VALUE}};',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .skt-image-swap-wrapper img, {{WRAPPER}} .skt-image-swap-ctn img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __('Border Radius', 'skt-addons-elementor'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .skt-image-swap-wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-image-swap-ctn img'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// $this->add_control(
		// 	'image_position_toggle',
		// 	[
		// 		'label'        => __('Slide Position', 'skt-addons-elementor'),
		// 		'type'         => Controls_Manager::POPOVER_TOGGLE,
		// 		'label_off'    => __('None', 'skt-addons-elementor'),
		// 		'label_on'     => __('Custom', 'skt-addons-elementor'),
		// 		'return_value' => 'yes',
		// 		'condition'    => [
		// 			'select_effect_type' => 'slide',
		// 		],
		// 		'separator'    => 'before',
		// 	]
		// );

		// $this->start_popover();

		// $this->add_responsive_control(
		// 	'image_horizontal_position',
		// 	[
		// 		'label'      => __('Horizontal Position', 'skt-addons-elementor'),
		// 		'type'       => Controls_Manager::SLIDER,
		// 		'size_units' => ['px', '%'],
		// 		'default'    => [
		// 			'unit' => '%',
		// 		],
		// 		'range'      => [
		// 			'%'  => [
		// 				'min' => -100,
		// 				'max' => 100,
		// 			],
		// 			'px' => [
		// 				'min' => -1000,
		// 				'max' => 1000,
		// 			],
		// 		],
		// 		'default'    => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 0,
		// 			],
		// 		],
		// 		'condition'  => [
		// 			'image_position_toggle' => 'yes',
		// 		],
		// 		'selectors'  => [
		// 			'{{WRAPPER}} .skt-image-swap-item.active' => 'top: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}}'                            => '--top-position: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->add_responsive_control(
		// 	'image_vertical_position',
		// 	[
		// 		'label'      => __('Vertical Position', 'skt-addons-elementor'),
		// 		'type'       => Controls_Manager::SLIDER,
		// 		'size_units' => ['px', '%'],
		// 		'default'    => [
		// 			'unit' => '%',
		// 		],
		// 		'range'      => [
		// 			'%'  => [
		// 				'min' => -100,
		// 				'max' => 100,
		// 			],
		// 			'px' => [
		// 				'min' => -1000,
		// 				'max' => 1000,
		// 			],
		// 		],
		// 		// 'default'    => [
		// 		// 	'unit' => 'px',
		// 		// 	'size' => 80,
		// 		// ],
		// 		'condition'  => [
		// 			'image_position_toggle' => 'yes',
		// 		],
		// 		'selectors'  => [
		// 			'{{WRAPPER}} .skt-image-swap-item.active' => 'right: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}}'                            => '--left-position: {{SIZE}}{{UNIT}};',
		// 		],
		// 	]
		// );

		// $this->end_popover();

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ($settings['select_effect_type'] == 'slide') {

			$this->slide_images($settings);
		} else {
			$this->default_swap($settings);
		}

	}

	protected function slide_images($settings) {

		$this->add_render_attribute(
			'wrapper',
			[
				'class'        => ['skt-image-swap-ctn', 'slide_' . $settings['ig_effects_slides']],
				'data-trigger' => 'click',
				'data-layout'  => $settings['ig_effects_slides'],
			]
		);
		// if (!empty($settings['image_align'])) {
		// 	$this->add_render_attribute(
		// 		'wrapper',
		// 		[
		// 			'class' => [$settings['image_align']],
		// 		]
		// 	);
		// }
		?>
		<div class="skt_img_main_wrapper_top">
			<div <?php $this->print_render_attribute_string('wrapper');?>>
				<div class="skt-image-swap-fakeone">
					<img src="<?php echo esc_url($settings['first_image']['url']); ?>" />
				</div>
				<div class="skt-image-swap-insider">
					<div class="skt-image-swap-item">
						<img src="<?php echo esc_url($settings['first_image']['url']); ?>" />
					</div>
					<div class="skt-image-swap-item">
						<img src="<?php echo esc_url($settings['second_image']['url']); ?>" />
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	protected function default_swap($settings) {
		$this->add_render_attribute(
			'wrapper',
			[
				'class'        => ['skt-image-swap-wrapper', $settings['ig_effects']],
				'data-trigger' => $settings['swip_trigger'],
				'id'           => 'skt-image-swap-wrapper_id',
			]
		);
		if ('click' == $settings['swip_trigger']) {
			$this->add_render_attribute(
				'wrapper',
				[
					'data-click' => 'inactive',
				]
			);
		}
		$this->add_render_attribute(
			'inside',
			[
				'class' => ['skt-image-swap-wrapper__inside'],
			]
		);
		?>

		<div <?php $this->print_render_attribute_string('wrapper');?>>

			<?php printf('<img class="fake_img" src="%s">', esc_url($settings['first_image']['url']));?>

			<div <?php $this->print_render_attribute_string('inside');?>>
				<?php printf('<img class="img_swap_first" src="%s">', esc_url($settings['first_image']['url']));?>
				<?php printf('<img class="img_swap_second" src="%s">', esc_url($settings['second_image']['url']));?>
			</div>
		</div>
		<?php
	}

}
