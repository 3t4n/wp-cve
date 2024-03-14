<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Utils;

class Thim_Ekit_Widget_Back_To_Course extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-back-to-course';
	}

	public function get_title() {
		return esc_html__( 'Back To Course', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-arrow-left';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE_ITEM );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Content', 'thim-elementor-kit' )
			]
		);

		$this->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Select Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'separator'   => 'before',
				'default'     => array(
					'value'   => 'fa fa-long-arrow-left',
					'library' => 'fa-solid',
				),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->add_control(
			'tag_name',
			array(
				'label'   => esc_html__( 'HTML Tag', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => 'h1',
			)
		);

		$this->add_control(
			'hide_title',
			[
				'label'   => esc_html__( 'Hide Title?', 'eduma' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'hide_title!' => 'yes'
				],
 			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit__back-single-course a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'title_color_hover',
			array(
				'label'     => esc_html__( 'Text Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit__back-single-course a:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .thim-ekit__back-single-course a',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => array( 'icon[value]!' => '' ),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => __( 'Icon Size (px)', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 14,
				'min'       => 0,
				'step'      => 1,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon i'   => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon svg' => 'width: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'border_style',
			[
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'thim-elementor-kit' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'thim-elementor-kit' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'thim-elementor-kit' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'thim-elementor-kit' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'thim-elementor-kit' ),
				],
				'default'   => 'none',
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_dimensions',
			[
				'label'     => esc_html_x( 'Width', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => [
					'border_style!' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_color_icon_border_style' );

		$this->start_controls_tab(
			'tab_color_color_normal',
			[
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon i'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     => esc_html__( 'Border Color:', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'border_style!' => 'none'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_color_icon_border_hover',
			[
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'color_hover',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course:hover .wrapper-icon i'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit__back-single-course:hover .wrapper-icon svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color_hover',
			[
				'label'     => esc_html__( 'Border Color:', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course:hover .wrapper-icon' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'border_style!' => 'none'
				],
			]
		);

		$this->add_control(
			'bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekit__back-single-course:hover .wrapper-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .thim-ekit__back-single-course .wrapper-icon',
			)
		);

		$this->end_controls_section();
	}


	public function render() {
		do_action( 'thim-ekit/modules/single-course-item/before-preview-query' );

		$settings = $this->get_settings_for_display();
		?>
		<<?php Utils::print_validated_html_tag( $settings['tag_name'] ); ?> class="thim-ekit__back-single-course">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php
			if ( $settings['icon']['value'] ) {
				echo '<span class="wrapper-icon">';
				Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
				echo '</span>';
			}
			?>

			<?php
			if ( $settings['hide_title'] != 'yes' ) {
				the_title();
			}
			?>
 		</a>
		</<?php Utils::print_validated_html_tag( $settings['tag_name'] ); ?>>

		<?php
		do_action( 'thim-ekit/modules/single-course-item/after-preview-query' );
	}
}
