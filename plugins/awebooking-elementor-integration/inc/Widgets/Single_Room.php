<?php
namespace AweBooking\Elementor\Widgets;

use AweBooking\Constants;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Elementor Rooms.
 */
class Single_Room extends Abstract_Widget {

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
		return 'single-room';
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
		return esc_html__( 'Single Room', 'awebooking-elementor' );
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
		return apply_filters( 'abrs_elementor_' . $this->get_name(), 'eicon-image-box' );
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
			'page_id',
			[
				'label'   => esc_html__( 'Room', 'awebooking-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->get_room_types(),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_main',
			[
				'label' => esc_html__( 'Main content', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'main_width',
			[
				'label'      => esc_html__( 'Width (%)', 'awebooking-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 66.66667,
				],
				'range'      => [
					'percent' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .hotel-content__main' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .room__title' => 'display: {{VALUE}};',
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
					'{{WRAPPER}} .room__title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'price_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'selector'  => '{{WRAPPER}} .room__title',
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
					'{{WRAPPER}} .room__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .room__price' => 'display: {{VALUE}};',
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
					'{{WRAPPER}} .room__price' => 'color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} .room__price',
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
					'{{WRAPPER}} .room__price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'price_display' => 'block',
				],
			]
		);

		$this->add_control(
			'heading_section',
			[
				'label'     => esc_html__( 'Section heading', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_heading_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .room__section-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'section_heading_typography',
				'selector'  => '{{WRAPPER}} .room__section-title',
			]
		);

		$this->add_responsive_control(
			'section_heading_margin_bottom',
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
					'{{WRAPPER}} .room__section-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_form',
			[
				'label' => esc_html__( 'Form', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_display',
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
					'{{WRAPPER}} .hotel-content__aside' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'form_width',
			[
				'label'      => esc_html__( 'Width (%)', 'awebooking-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 33.33333,
				],
				'range'      => [
					'percent' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .hotel-content__aside' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_display',
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
					'{{WRAPPER}} .room-description-section' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_section_description_heading',
			[
				'label'     => esc_html__( 'Section heading', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_description_heading_display',
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
					'{{WRAPPER}} .room-description-section .room__section-title' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_section_description_content',
			[
				'label'     => esc_html__( 'Content', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_description_content_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .room-description-section .room__content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'section_description_content_typography',
				'selector'  => '{{WRAPPER}} .room-description-section .room__content',
			]
		);

		$this->add_responsive_control(
			'section_description_content_margin_bottom',
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
					'{{WRAPPER}} .room-description-section .room__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_amenities',
			[
				'label' => esc_html__( 'Amenities', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'amenities_display',
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
					'{{WRAPPER}} .room-amenities-section' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_section_amenities_heading',
			[
				'label'     => esc_html__( 'Section heading', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_amenities_heading_display',
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
					'{{WRAPPER}} .room-amenities-section .room__section-title' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_section_amenities_content',
			[
				'label'     => esc_html__( 'Content', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_amenities_content_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .room-amenities-section .room-amenity' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'section_amenities_content_typography',
				'selector'  => '{{WRAPPER}} .room-amenities-section .room-amenity',
			]
		);

		$this->add_responsive_control(
			'section_amenities_content_margin_bottom',
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
					'{{WRAPPER}} .room-amenities-section .room-amenity' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_gallery',
			[
				'label' => esc_html__( 'Gallery', 'awebooking-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'gallery_display',
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
					'{{WRAPPER}} .room-gallery-section' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_section_gallery_heading',
			[
				'label'     => esc_html__( 'Section heading', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'section_gallery_heading_display',
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
					'{{WRAPPER}} .room-gallery-section .room__section-title' => 'display: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Gets all room types.
	 */
	protected function get_room_types() {
		$room_types = get_posts( [
			'post_type'   => Constants::ROOM_TYPE,
			'numberposts' => - 1,
		] );

		return wp_list_pluck( $room_types, 'post_title', 'ID' );
	}
}
