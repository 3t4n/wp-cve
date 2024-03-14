<?php
/**
 * Testimonial widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Testimonial extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Testimonial', 'skt-addons-elementor' );
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
		return 'skti skti-testimonial';
	}

	public function get_keywords() {
		return [ 'testimonial', 'review', 'feedback' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__testimonial_content_controls();
		$this->__reviewer_content_controls();
	}

	protected function __testimonial_content_controls() {

		$this->start_controls_section(
			'_section_testimonial',
			[
				'label' => __( 'Testimonial', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'testimonial',
			[
				'label' => __( 'Testimonial', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXTAREA,
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
				'placeholder' => __( 'Type testimonial', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'toggle' => false,
				'default' => 'left',
				'prefix_class' => 'skt-testimonial--',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'_design',
			[
				'label' => __( 'Design', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'basic' => __( 'Default', 'skt-addons-elementor' ),
					'bubble' => __( 'Bubble', 'skt-addons-elementor' ),
				],
				'default' => 'basic',
				'prefix_class' => 'skt-testimonial--',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function __reviewer_content_controls() {

		$this->start_controls_section(
			'_section_reviewer',
			[
				'label' => __( 'Reviewer', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'full',
				'exclude' => ['custom'],
				'separator' => 'none',
			]
		);

		$this->add_control(
			'name',
			[
				'label' => __( 'Name', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'SKT', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type Reviewer Name', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'CMO, SKT Addons', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type reviewer title', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__testimonial_style_controls();
		$this->__image_style_controls();
		$this->__reviewer_style_controls();
	}

	protected function __testimonial_style_controls() {

		$this->start_controls_section(
			'_section_style_testimonial',
			[
				'label' => __( 'Testimonial', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'testimonial_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'testimonial_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'testimonial_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'testimonial_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__content' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .skt-testimonial__content:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonial_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-testimonial__content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_responsive_control(
			'testimonial_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'testimonial_box_shadow',
				'selector' => '{{WRAPPER}} .skt-testimonial__content',
			]
		);

		$this->end_controls_section();
	}

	protected function __image_style_controls() {

		$this->start_controls_section(
			'_section_style_image',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 65,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__reviewer-thumb' => '-webkit-flex: 0 0 {{SIZE}}{{UNIT}}; -ms-flex: 0 0 {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.skt-testimonial--left .skt-testimonial__reviewer-meta' => '-webkit-flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); -ms-flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); max-width: calc(100% - {{SIZE}}{{UNIT}});',

					'{{WRAPPER}}.skt-testimonial--right .skt-testimonial__reviewer-meta' => '-webkit-flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); -ms-flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); flex: 0 0 calc(100% - {{SIZE}}{{UNIT}}); max-width: calc(100% - {{SIZE}}{{UNIT}});',

					'{{WRAPPER}}.skt-testimonial--left .skt-testimonial__content:after' => 'left: calc(({{SIZE}}{{UNIT}} / 2) - 18px);',

					'{{WRAPPER}}.skt-testimonial--right .skt-testimonial__content:after' => 'right: calc(({{SIZE}}{{UNIT}} / 2) - 18px);',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__reviewer-thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}}.skt-testimonial--left .skt-testimonial__reviewer-meta' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-testimonial--right .skt-testimonial__reviewer-meta' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.skt-testimonial--center .skt-testimonial__reviewer-meta' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .skt-testimonial__reviewer-thumb img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__reviewer-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '.skt-testimonial__reviewer-thumb img',
			]
		);

		$this->end_controls_section();
	}

	protected function __reviewer_style_controls() {

		$this->start_controls_section(
			'_section_style_reviewer',
			[
				'label' => __( 'Reviewer', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_name',
			[
				'label' => __( 'Name', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__reviewer-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-testimonial__reviewer-name',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_responsive_control(
			'name_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__reviewer-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-testimonial__reviewer-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-testimonial__reviewer-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'testimonial', 'intermediate' );
		$this->add_render_attribute( 'testimonial', 'class', 'skt-testimonial__content' );

		$this->add_inline_editing_attributes( 'name', 'basic' );
		$this->add_render_attribute( 'name', 'class', 'skt-testimonial__reviewer-name' );

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'skt-testimonial__reviewer-title' );
		?>

		<div <?php $this->print_render_attribute_string( 'testimonial' ); ?>>
			<?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $settings['testimonial'] )); ?>
		</div>
		<div class="skt-testimonial__reviewer">
			<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
				<div class="skt-testimonial__reviewer-thumb">
					<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' )); ?>
				</div>
			<?php endif; ?>

			<div class="skt-testimonial__reviewer-meta">
				<div <?php $this->print_render_attribute_string( 'name' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['name'] )); ?></div>
				<div <?php $this->print_render_attribute_string( 'title' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['title'] )); ?></div>
			</div>
		</div>
		<?php
	}
}