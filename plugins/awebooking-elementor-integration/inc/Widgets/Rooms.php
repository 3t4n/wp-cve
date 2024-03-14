<?php
namespace AweBooking\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Elementor Rooms.
 */
class Rooms extends Abstract_Widget {

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
		return 'rooms';
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
		return esc_html__( 'Rooms', 'awebooking-elementor' );
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
		return apply_filters( 'abrs_elementor_' . $this->get_name(), 'eicon-photo-library' );
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
			'hotel',
			[
				'label'   => esc_html__( 'Hotel', 'awebooking-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->get_list_hotels(),
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

		$this->add_control(
			'offset',
			[
				'label'       => esc_html__( 'Offset', 'awebooking-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'placeholder' => '0',
				'description' => esc_html__( 'Number of post to displace or pass over.', 'awebooking-elementor' ),
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
					'size' => 45,
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
					'size' => 55,
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

		$this->start_controls_tabs( 'title_colors' );

		$this->start_controls_tab(
			'title_colors_normal',
			[
				'label'     => esc_html__( 'Normal', 'awebooking-elementor' ),
				'condition' => [
					'title_display' => 'block',
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
					'{{WRAPPER}} .list-room__title a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'title_display' => 'block',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_colors_hover',
			[
				'label'     => esc_html__( 'Hover', 'awebooking-elementor' ),
				'condition' => [
					'title_display' => 'block',
				],
			]
		);

		$this->add_control(
			'hover_title_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__title a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'title_display' => 'block',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		$this->add_control(
			'heading_additional_info',
			[
				'label'     => esc_html__( 'Additional Information', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'additional_info_display',
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
					'{{WRAPPER}} .list-room__additional-info' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'additional_info_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__additional-info' => 'color: {{VALUE}};',
				],
				'condition' => [
					'additional_info_display' => 'block',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'additional_info_typography',
				'selector'  => '{{WRAPPER}} .list-room__additional-info, {{WRAPPER}} .list-room__info-list',
				'condition' => [
					'additional_info_display' => 'block',
				],
			]
		);

		$this->add_responsive_control(
			'additional_info_margin_bottom',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .list-room__additional-info' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'additional_info_display' => 'block',
				],
			]
		);

		$this->add_control(
			'heading_button',
			[
				'label'     => esc_html__( 'Button', 'awebooking-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_display',
			[
				'label'              => esc_html__( 'Display?', 'awebooking-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'inline-block' => esc_html__( 'Yes', 'awebooking-elementor' ),
					'none'         => esc_html__( 'No', 'awebooking-elementor' ),
				],
				'default'            => 'inline-block',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .list-room__footer a.button' => 'display: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'button_colors' );

		$this->start_controls_tab(
			'button_colors_normal',
			[
				'label'     => esc_html__( 'Normal', 'awebooking-elementor' ),
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__footer a.button' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Background color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__footer a.button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => esc_html__( 'Border color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__footer a.button' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_colors_hover',
			[
				'label'     => esc_html__( 'Hover', 'awebooking-elementor' ),
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'hover_button_color',
			[
				'label'     => esc_html__( 'Color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__footer a.button:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'hover_button_bg_color',
			[
				'label'     => esc_html__( 'Background color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__footer a.button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_control(
			'hover_button_border_color',
			[
				'label'     => esc_html__( 'Border color', 'awebooking-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .list-room__footer a.button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .list-room__footer a.button',
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'awebooking-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .list-room__footer a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin_bottom',
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
					'{{WRAPPER}} .list-room__footer a.button' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_display' => 'inline-block',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Gets list hotels.
	 *
	 * @return array
	 */
	protected function get_list_hotels() {
		return abrs_list_hotels()->pluck( 'name', 'id' )->all();
	}
}
