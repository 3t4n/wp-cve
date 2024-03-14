<?php
/**
 * Step Flow widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined( 'ABSPATH' ) || die();

class Step_Flow extends Base {
	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Step Flow', 'skt-addons-elementor' );
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
		return 'skti skti-step-flow';
	}

	public function get_keywords() {
		return [ 'step', 'flow' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_step',
			[
				'label' => __( 'Step Flow', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		if ( skt_addons_elementor_is_elementor_version( '<', '2.6.0' ) ) {
			$this->add_control(
				'icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'type' => Controls_Manager::ICON,
					'label_block' => true,
					'options' => skt_addons_elementor_get_skt_addons_elementor_icons(),
					'default' => 'skti skti-finger-index',
				]
			);
		} else {
			$this->add_control(
				'selected_icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'label_block' => true,
					'default' => [
						'value' => 'skti skti-finger-index',
						'library' => 'skt-icons',
					]
				]
			);
		}

		$this->add_control(
			'badge',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Badge', 'skt-addons-elementor' ),
				'description' => __( 'Keep it blank, if you want to remove the Badge', 'skt-addons-elementor' ),
				'default' => __( 'Step 1', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Title', 'skt-addons-elementor' ),
				'default' => __( 'Start Marketing', 'skt-addons-elementor' ),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'description' => skt_addons_elementor_get_allowed_html_desc( 'intermediate' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Description', 'skt-addons-elementor' ),
				'default' => 'consectetur adipiscing elit, sed do<br>eiusmod Lorem ipsum dolor sit amet,<br> consectetur.',
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'content_alignment',
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
					'justify' => [
						'title' => __( 'Justify', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'show_indicator',
			[
				'label' => __( 'Show Direction', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__icon_style_controls();
		$this->__badge_style_controls();
		$this->__title_desc_style_controls();
		$this->__direction_style_controls();
	}

	protected function __icon_style_controls() {

		$this->start_controls_section(
			'_section_icon_style',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
					'em' => [
						'min' => 6,
						'max' => 300,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}' => '--skt-stepflow-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-icon' => 'padding: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}' => '--skt-stepflow-icon-padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-steps-icon',
			]
		);

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .skt-steps-icon',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-steps-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-steps-icon' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __badge_style_controls() {

		$this->start_controls_section(
			'_section_badge_style',
			[
				'label' => __('Badge', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'badge!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'badge!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'badge_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-steps-label',
				'condition' => [
					'badge!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'badge!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'badge!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'badge!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-label' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'selector' => '{{WRAPPER}} .skt-steps-label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
				'condition' => [
					'badge!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __title_desc_style_controls() {

		$this->start_controls_section(
			'_section_title_style',
			[
				'label' => __( 'Title & Description', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'skt-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-steps-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_link_color',
			[
				'label' => __( 'Link Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'link[url]!' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Hover Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'link[url]!' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .skt-steps-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .skt-steps-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .skt-steps-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_control(
			'_heading_description',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-step-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'description_shadow',
				'selector' => '{{WRAPPER}} .skt-step-description',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .skt-step-description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->end_controls_section();
	}

	protected function __direction_style_controls() {

		$this->start_controls_section(
			'_section_direction_style',
			[
				'label' => __( 'Direction', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'direction_style',
			[
				'label' => __( 'Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'skt-addons-elementor' ),
					'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
					'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .skt-step-arrow, {{WRAPPER}} .skt-step-arrow:after' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} .skt-step-arrow:after' => 'border-right-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'direction_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-step-arrow' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'direction_angle',
			[
				'label' => __( 'Angle', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
				'default' => [
					'unit' => 'deg',
				],
				'tablet_default' => [
					'unit' => 'deg',
				],
				'mobile_default' => [
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--skt-stepflow-direction-angle: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'direction_offset_toggle',
			[
				'label' => __( 'Offset', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'skt-addons-elementor' ),
				'label_on' => __( 'Custom', 'skt-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'direction_offset_y',
			[
				'label' => __( 'Offset Top', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 500,
					]
				],
				'condition' => [
					'direction_offset_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-step-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'direction_offset_x',
			[
				'label' => __( 'Offset Left', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 500,
					]
				],
				'condition' => [
					'direction_offset_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-step-arrow' => 'left: calc( 100% + {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}}' => '--skt-stepflow-direction-offset-x: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'direction_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-step-arrow' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .skt-step-arrow:after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'title', 'class', 'skt-steps-title' );

		$this->add_inline_editing_attributes( 'description', 'intermediate' );
		$this->add_render_attribute( 'description', 'class', 'skt-step-description' );

		$this->add_render_attribute( 'badge', 'class', 'skt-steps-label' );
		$this->add_inline_editing_attributes( 'badge', 'none' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			$this->add_inline_editing_attributes( 'link', 'basic', 'title' );

			$title = sprintf( '<a %s>%s</a>',
				$this->get_render_attribute_string( 'link' ),
				skt_addons_elementor_kses_basic( $settings['title'] )
			);
		} else {
			$this->add_inline_editing_attributes( 'title', 'basic' );
			$title = skt_addons_elementor_kses_basic( $settings['title'] );
		}
		?>

		<div class="skt-steps-icon">
			<?php if ( $settings['show_indicator'] === 'yes' ) : ?>
				<span class="skt-step-arrow"></span>
			<?php endif; ?>

			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) :
				skt_addons_elementor_render_icon( $settings, 'icon', 'selected_icon' );
			endif; ?>

			<?php if ( $settings['badge'] ) : ?>
				<span <?php $this->print_render_attribute_string( 'badge' ); ?>><?php echo esc_html( $settings['badge'] ); ?></span>
			<?php endif; ?>
		</div>

		<?php
		printf( '<%1$s %2$s>%3$s</%1$s>',
			skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
			$this->get_render_attribute_string( 'title' ),
			$title
		);
		?>

		<?php if ( $settings['description'] ) : ?>
			<p <?php $this->print_render_attribute_string( 'description' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $settings['description'] )); ?></p>
		<?php endif; ?>

		<?php
	}
}