<?php
namespace AweBooking\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Elementor Rooms.
 */
class Services extends Abstract_Widget {

	/**
	 * Get widget patch.
	 *
	 * Retrieve widget patch.
	 *
	 * @access public
	 *
	 * @return string Widget patch.
	 */
	public function get_path() {
		return 'services';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Services', 'awebooking-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return apply_filters( 'abrs_elementor_' . $this->get_name(), 'eicon-menu-card' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'query_settings',
			[
				'label' => esc_html__( 'Query settings', 'awebooking-elementor' ),
			]
		);

		$this->add_control(
			'per_page',
			[
				'label'       => esc_html__( 'Per page', 'awebooking-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => - 1,
				'placeholder' => '6',
				'default'     => 6,
				'description' => esc_html__( 'How much items per page to show', 'awebooking-elementor' ),
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'       => esc_html__( 'Order by', 'awebooking-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.',
					'awebooking-elementor' ),
					'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options'     => [
					'',
					'date'          => esc_html__( 'Date', 'awebooking-elementor' ),
					'ID'            => esc_html__( 'ID', 'awebooking-elementor' ),
					'author'        => esc_html__( 'Author', 'awebooking-elementor' ),
					'title'         => esc_html__( 'Title', 'awebooking-elementor' ),
					'modified'      => esc_html__( 'Modified', 'awebooking-elementor' ),
					'rand'          => esc_html__( 'Random', 'awebooking-elementor' ),
					'comment_count' => esc_html__( 'Comment count', 'awebooking-elementor' ),
					'menu_order'    => esc_html__( 'Menu order', 'awebooking-elementor' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Sort order', 'awebooking-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.',
					'awebooking-elementor' ),
					'<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
				'options'     => [
					'DESC' => esc_html__( 'Descending', 'awebooking-elementor' ),
					'ASC'  => esc_html__( 'Ascending', 'awebooking-elementor' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Alignment', 'awebooking-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'awebooking-elementor' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .list-room' => 'text-align: {{VALUE}};',
				],
				'default'   => 'left',
			]
		);

		$this->add_responsive_control(
			'margin_bottom',
			[
				'label'     => esc_html__( 'Margin bottom (px)', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .list-room' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_media',
			[
				'label' => esc_html__( 'Media', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'media_display',
			[
				'label'              => esc_html__( 'Display?', 'awebooking-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'block' => esc_html__( 'Yes', 'awebooking-elementor' ),
					'none'  => esc_html__( 'No', 'awebooking-elementor' ),
				],
				'default'            => 'block',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .list-room__media' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'media_width',
			[
				'label'      => esc_html__( 'Width (%)', 'awebooking-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 30,
				],
				'range'      => [
					'percent' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .list-room__media' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'media_display' => 'block',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_information',
			[
				'label' => esc_html__( 'Information', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'information_width',
			[
				'label'      => esc_html__( 'Width (%)', 'awebooking-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 70,
				],
				'range'      => [
					'percent' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .list-room__info' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'     => esc_html__( 'Title', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_display',
			[
				'label'              => esc_html__( 'Display?', 'awebooking-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'block' => esc_html__( 'Yes', 'awebooking-elementor' ),
					'none'  => esc_html__( 'No', 'awebooking-elementor' ),
				],
				'default'            => 'block',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .list-room__title' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'title_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'selector'  => '{{WRAPPER}} .list-room__title',
				'condition' => [
					'title_display' => 'block',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin_bottom',
			[
				'label'     => esc_html__( 'Margin bottom (px)', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .list-room__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_display' => 'block',
				],
			]
		);

		$this->add_control(
			'heading_price',
			[
				'label'     => esc_html__( 'Price', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_display',
			[
				'label'              => esc_html__( 'Display?', 'awebooking-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'block' => esc_html__( 'Yes', 'awebooking-elementor' ),
					'none'  => esc_html__( 'No', 'awebooking-elementor' ),
				],
				'default'            => 'block',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .list-room__price' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__price' => 'color: {{VALUE}};',
				],
				'condition' => [
					'price_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'price_typography',
				'selector'  => '{{WRAPPER}} .list-room__price',
				'condition' => [
					'price_display' => 'block',
				],
			]
		);

		$this->add_responsive_control(
			'price_margin_bottom',
			[
				'label'     => esc_html__( 'Margin bottom (px)', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .list-room__price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'price_display' => 'block',
				],
			]
		);

		$this->add_control(
			'heading_desc',
			[
				'label'     => esc_html__( 'Description', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'desc_display',
			[
				'label'              => esc_html__( 'Display?', 'awebooking-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'block' => esc_html__( 'Yes', 'awebooking-elementor' ),
					'none'  => esc_html__( 'No', 'awebooking-elementor' ),
				],
				'default'            => 'block',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .list-room__desc' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__desc' => 'color: {{VALUE}};',
				],
				'condition' => [
					'desc_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'desc_typography',
				'selector'  => '{{WRAPPER}} .list-room__desc',
				'condition' => [
					'desc_display' => 'block',
				],
			]
		);

		$this->add_responsive_control(
			'desc_margin_bottom',
			[
				'label'     => esc_html__( 'Margin bottom (px)', 'awebooking-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .list-room__desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'desc_display' => 'block',
				],
			]
		);

		$this->end_controls_section();
	}
}
