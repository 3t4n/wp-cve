<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Oltew_Order_List_table_Ele_Widget extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'woo_recent_orders_table';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('Order List Table', 'oltew-order-list-table-ele');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-table';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url()
	{
		return 'https://wpmethods.com/contact';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['general'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return ['order', 'order table', 'woocommerce recent order', 'order list', 'woocommerce order list'];
	}


	/**
	 * Register list widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{

		/////////////Table Content Tab//////////////////

		//Table Settings Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Table Settings', 'oltew-order-list-table-ele'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'select_status',
			[
				'label' => esc_html__('Select Status', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'wc-completed' => esc_html__('Completed', 'oltew-order-list-table-ele'),
					'wc-processing' => esc_html__('Processing', 'oltew-order-list-table-ele'),
					'wc-on-hold' => esc_html__('On hold', 'oltew-order-list-table-ele'),
					'wc-failed' => esc_html__('Failed', 'oltew-order-list-table-ele'),
					'wc-cancelled' => esc_html__('Cancelled', 'oltew-order-list-table-ele'),

				],
				'default' => ['wc-completed', 'wc-processing', 'wc-on-hold', 'wc-failed', 'wc-cancelled'],
			]
		);

		$this->add_control(
			'list_per_page',
			[
				'label' => esc_html__('List Per Page', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '10',
			]
		);



		$this->add_control(
			'order_time_format',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__('Select Order Time Format', 'oltew-order-list-table-ele'),
				'options' => [
					'ago' => esc_html__('Ago', 'oltew-order-list-table-ele'),
					'date' => esc_html__('Date/Month/YR TIME', 'oltew-order-list-table-ele'),
				],
				'default' => 'ago',
			]
		);
		
		$this->add_control(
			'order_by',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__('Order', 'oltew-order-list-table-ele'),
				'options' => [
					'asc' => esc_html__('ASC', 'oltew-order-list-table-ele'),
					'desc' => esc_html__('DESC', 'oltew-order-list-table-ele'),
				],
				'default' => 'desc',
			]
		);


		$this->end_controls_section();


		// Table Header Title & Icon Section//
		$this->start_controls_section(
			'table_header',
			[
				'label' => esc_html__('Table Header Title and Icons', 'oltew-order-list-table-ele'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		//Customer Name

		$this->add_control(
			'customer_name_heading',
			[
				'label' => esc_html__('Customer Name and Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'customer_name',
			[
				'label' => esc_html__('Customer Name', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Customer',
			]
		);

		$this->add_control(
			'customer_icon',
			[
				'label' => esc_html__('Customer Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-users',
					'library' => 'solid',
				],
			]
		);

		//Sell Time
		$this->add_control(
			'sell_time_heading',
			[
				'label' => esc_html__('Sell Time and Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'sell_time',
			[
				'label' => esc_html__('Sell Time', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Sell Time',
			]
		);

		$this->add_control(
			'sell_icon',
			[
				'label' => esc_html__('Sell Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-history',
					'library' => 'solid',
				],
			]
		);

		//Order Status
		$this->add_control(
			'order_status_heading',
			[
				'label' => esc_html__('Order Status and Icons', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'order_status',
			[
				'label' => esc_html__('Order Status', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Status',
			]
		);

		$this->add_control(
			'status_icon',
			[
				'label' => esc_html__('Order Status Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-fire',
					'library' => 'solid',
				],
			]
		);

		//Product Name
		$this->add_control(
			'product_name_heading',
			[
				'label' => esc_html__('Product Name & Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'product_name',
			[
				'label' => esc_html__('Product Name', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Product Name',
			]
		);

		$this->add_control(
			'product_icon',
			[
				'label' => esc_html__('Product Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-shopping-cart',
					'library' => 'solid',
				],
			]
		);


		//Customer Phone
		$this->add_control(
			'customer_phone_heading',
			[
				'label' => esc_html__('Customer Phone', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'customer_phone',
			[
				'label' => esc_html__('Phone', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Phone',
			]
		);

		$this->add_control(
			'customer_phone_icon',
			[
				'label' => esc_html__('Customer Phone Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-phone',
					'library' => 'solid',
				],
			]
		);

		//Amount
		$this->add_control(
			'sell_amount_heading',
			[
				'label' => esc_html__('Sell Amount and Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'sell_amount',
			[
				'label' => esc_html__('Sell Amount', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Amount',
			]
		);

		$this->add_control(
			'amount_icon',
			[
				'label' => esc_html__('Amount Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-dollar-sign',
					'library' => 'solid',
				],
			]
		);

		$this->end_controls_section();

		//Data List Icons Section
		$this->start_controls_section(
			'data_icons',
			[
				'label' => esc_html__('Data List Icons', 'oltew-order-list-table-ele'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		//Customer List Icon
		$this->add_control(
			'customer_list_icon',
			[
				'label' => esc_html__('Customer Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-user',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'sell_list_time_icon',
			[
				'label' => esc_html__('Sell Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-clock-o',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'product_list_icon',
			[
				'label' => esc_html__('Product Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-shopping-bag',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'customer_phone_list_icon',
			[
				'label' => esc_html__('Phone Icon', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-phone',
					'library' => 'solid',
				],
			]
		);

		$this->end_controls_section();

		//////////////Table Column Hide & Show Section/////////////	
		///////////////////////////////////////////////////////////
		$this->start_controls_section(
			'table_column_hide_show',
			[
				'label' => esc_html__('Table Item Hide/Show', 'oltew-order-list-table-ele'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hide_order_sl',
			[
				'label' => esc_html__('Hide Order SL', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		$this->add_control(
			'hide_customer_title',
			[
				'label' => esc_html__('Hide Customer Column', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'hide_sell_time',
			[
				'label' => esc_html__('Hide Sell Time', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'hide_status',
			[
				'label' => esc_html__('Hide Order Status', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'hide_product_name',
			[
				'label' => esc_html__('Hide Product Name', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'hide_customer_phone',
			[
				'label' => esc_html__('Hide Customer Phone', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'hide_sell_amount',
			[
				'label' => esc_html__('Hide Sell Amount', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);


		$this->end_controls_section();
		
		///////Table Alignment Section//////
		$this->start_controls_section(
			'oltew_table_alignment_settings',
			[
				'label' => esc_html__('Table Alignment', 'oltew-order-list-table-ele'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'oltew_table_th_alignment',
			[
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'label' => esc_html__( 'Table Header Title', 'oltew-order-list-table-ele' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'oltew-order-list-table-ele' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'oltew-order-list-table-ele' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'oltew-order-list-table-ele' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
			]
		);
		
		$this->add_control(
			'oltew_table_td_alignment',
			[
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'label' => esc_html__( 'Table Body Text', 'oltew-order-list-table-ele' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'oltew-order-list-table-ele' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'oltew-order-list-table-ele' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'oltew-order-list-table-ele' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
			]
		);

		$this->end_controls_section();


		/////////////////Table Style Tab////////////////
		///////////////////////////////////////////////

		$this->start_controls_section(
			'table_style',
			[
				'label' => esc_html__('Table Style', 'oltew-order-list-table-ele'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_heading_style',
			[
				'label' => esc_html__('Table Header Style', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'table_heading_title_color',
			[
				'label' => esc_html__('Table Header Title Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table th' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_heading_icon_color',
			[
				'label' => esc_html__('Table Header Icon Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table th i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_heading_background_color',
			[
				'label' => esc_html__('Table Header Background Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffd000',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table thead' => 'background: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'table_heading_typography',
				'label' => esc_html__('Table Header Typography', 'oltew-order-list-table-ele'),
				'selector' => '{{WRAPPER}} .oltew-order-list-table th',
			]
		);

		//Table Body Style
		$this->add_control(
			'table_body_style',
			[
				'label' => esc_html__('Table Body Style', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'table_body_text_color',
			[
				'label' => esc_html__('Table body text color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table td' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_body_icon_color',
			[
				'label' => esc_html__('Table Body Icon Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table td i' => 'color: {{VALUE}}'
				]
			]
		);
		$this->add_control(
			'table_background_color',
			[
				'label' => esc_html__('Table Background Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table' => 'background: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'table_body_typography',
				'label' => esc_html__('Table Body Typography', 'oltew-order-list-table-ele'),
				'selector' => '{{WRAPPER}} .oltew-order-list-table table td',
			]
		);

		$this->add_control(
			'tr_nth_child_even',
			[
				'label' => esc_html__('<tr> Background (even)', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table tr:nth-child(even)' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tr_nth_child_odd',
			[
				'label' => esc_html__('<tr> Background (odd)', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table tr:nth-child(odd)' => 'background-color: {{VALUE}}',
				]
			]
		);


		//Table Border//
		$this->add_control(
			'woltw_table_border_style',
			[
				'label' => esc_html__('Border Style', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__('None', 'oltew-order-list-table-ele'),
					'solid' => esc_html__('Solid', 'oltew-order-list-table-ele'),
					'double' => esc_html__('Double', 'oltew-order-list-table-ele'),
					'dotted' => esc_html__('Dotted', 'oltew-order-list-table-ele'),
					'dashed' => esc_html__('Dashed', 'oltew-order-list-table-ele'),
					'groove' => esc_html__('Groove', 'oltew-order-list-table-ele'),
				],
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table, .oltew-order-list-table table td, .oltew-order-list-table table th' => 'border-style: {{VALUE}}',
				],
				'default' => 'solid'

			]
		);


		$this->add_control(
			'woltw_table_border_color',
			[
				'label' => esc_html__('Border Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#eee',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table,{{WRAPPER}} .oltew-order-list-table table th, {{WRAPPER}} .oltew-order-list-table table td' => 'border-color: {{VALUE}}',
				]
			]
		);
		$this->add_responsive_control(
			'woltw_table_border_width',
			[
				'label' => esc_html__('Border Width', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table,{{WRAPPER}} .oltew-order-list-table table th, {{WRAPPER}} .oltew-order-list-table table td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]

			]
		);

		$this->add_responsive_control(
			'woltw_table_padding',
			[
				'label' => esc_html__('Table Padding', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table,{{WRAPPER}} .oltew-order-list-table table th, {{WRAPPER}} .oltew-order-list-table table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]

			]
		);

		$this->end_controls_section();

		/////////////Order Status Section///////////////
		///////////////////////////////////////////////
		$this->start_controls_section(
			'order_status_style',
			[
				'label' => esc_html__('Order Status Style', 'oltew-order-list-table-ele'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		//completed style
		$this->add_control(
			'table_status_completed_style',
			[
				'label' => esc_html__('Status Completed Style', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'table_status_list_title_color',
			[
				'label' => esc_html__('Title Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_completed' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'complete_status_list_icon_color',
			[
				'label' => esc_html__('Icon Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table td .st_completed i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'complete_status_list_background_color',
			[
				'label' => esc_html__('Background Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_completed' => 'background: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'completed_padding',
			[
				'label' => esc_html__('Padding', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .st_completed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);


		//Processing style
		$this->add_control(
			'table_status_processing_style',
			[
				'label' => esc_html__('Status Processing Style', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'processing_status_list_title_color',
			[
				'label' => esc_html__('Title Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_processing' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'processing_status_list_icon_color',
			[
				'label' => esc_html__('Icon Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table td .st_processing i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'processing_status_list_background_color',
			[
				'label' => esc_html__('Background Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_processing' => 'background: {{VALUE}}'
				]
			]
		);
		$this->add_responsive_control(
			'processing_padding',
			[
				'label' => esc_html__('Padding', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .st_processing' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);



		//On Hold style
		$this->add_control(
			'table_status_on_hold_style',
			[
				'label' => esc_html__('Status On Hold', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'on_hold_status_list_title_color',
			[
				'label' => esc_html__('Title Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_on-hold' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'on_hold_status_list_icon_color',
			[
				'label' => esc_html__('Icon Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table td .st_on-hold i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'on_hold_status_list_background_color',
			[
				'label' => esc_html__('Background Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_on-hold' => 'background: {{VALUE}}'
				]
			]
		);
		$this->add_responsive_control(
			'on_hold_padding',
			[
				'label' => esc_html__('Padding', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .st_on-hold' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]

			]
		);


		//Status Failed Style
		$this->add_control(
			'table_status_failed_style',
			[
				'label' => esc_html__('Status Failed & Cancelled', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control(
			'failed_status_list_title_color',
			[
				'label' => esc_html__('Title Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_cancelled' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'failed_status_list_icon_color',
			[
				'label' => esc_html__('Icon Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .oltew-order-list-table table td .st_cancelled i' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'failed_status_list_background_color',
			[
				'label' => esc_html__('Background Color', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .st_cancelled' => 'background: {{VALUE}}'
				]
			]
		);
		$this->add_responsive_control(
			'failed_padding',
			[
				'label' => esc_html__('Padding', 'oltew-order-list-table-ele'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .st_cancelled' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);


		$this->end_controls_section();
	}


	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		//Table Settings Section
		$settings = $this->get_settings_for_display();
		$select_status = $settings['select_status'];
		$list_per_page = $settings['list_per_page'];
		$order_time_format = $settings['order_time_format'];
		$order_by = $settings['order_by'];
		// Table Header Title & Icon Section
		$customer_name = $settings['customer_name'];
		$sell_time = $settings['sell_time'];
		$order_status = $settings['order_status'];
		$product_name = $settings['product_name'];
		$customer_phone = $settings['customer_phone'];
		$sell_amount = $settings['sell_amount'];
		//Customer List Icon
		$customer_list_icon = $settings['customer_list_icon'];
		$sell_list_time_icon = $settings['sell_list_time_icon'];
		$product_list_icon = $settings['product_list_icon'];
		$customer_phone_list_icon = $settings['customer_phone_list_icon'];
		//Hide Column
		$hide_order_sl = $settings['hide_order_sl'];
		$hide_customer_title = $settings['hide_customer_title'];
		$hide_sell_time = $settings['hide_sell_time'];
		$hide_status = $settings['hide_status'];
		$hide_product_name = $settings['hide_product_name'];
		$hide_customer_phone = $settings['hide_customer_phone'];
		$hide_sell_amount = $settings['hide_sell_amount'];
		//Table Text Align
		$oltew_table_th_alignment = $settings['oltew_table_th_alignment'];
		$oltew_table_td_alignment = $settings['oltew_table_td_alignment'];

		//Check WooCommerce Plugin Active or Not
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) :
			//Woocommerce Order Query
			$customer_orders = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
				//'numberposts' => $order_count,
				'post_type'   => 'shop_order',
				'post_status' => $select_status,
				'order' => $order_by,
				'posts_per_page' => $list_per_page
			)));
			// Check ifthe customer has order and wc_get_order function exists
			if ( $customer_orders && function_exists('wc_get_order') ) : ?>
				<div class="oltew-order-list-table" style="overflow-x:auto;">
					<table>
						<thead>
							<tr style="text-align:<?php echo esc_html($oltew_table_th_alignment);?>">
								<?php if ($hide_order_sl !== 'yes') { ?><th>SL</th><?php }; ?>
								<?php if ($hide_customer_title !== 'yes') { ?><th class="customer_name_woor"><span class="nobr"><?php \Elementor\Icons_Manager::render_icon($settings['customer_icon'], ['aria-hidden' => 'true']); ?> <?php echo esc_html($customer_name); ?></span></th><?php }; ?>
								<?php if ($hide_sell_time !== 'yes') { ?><th class="sell_time_woor"><span class="nobr"><?php \Elementor\Icons_Manager::render_icon($settings['sell_icon'], ['aria-hidden' => 'true']); ?> <?php echo esc_html($sell_time); ?></span></th><?php }; ?>
								<?php if ($hide_status !== 'yes') { ?><th class="order_status_woor"><span class="nobr"><?php \Elementor\Icons_Manager::render_icon($settings['status_icon'], ['aria-hidden' => 'true']); ?> <?php echo esc_html($order_status); ?></span></th><?php }; ?>
								<?php if ($hide_product_name !== 'yes') { ?><th class="product_name_woor"><span class="nobr"><?php \Elementor\Icons_Manager::render_icon($settings['product_icon'], ['aria-hidden' => 'true']); ?> <?php echo esc_html($product_name); ?></span></th><?php }; ?>
								<?php if ($hide_customer_phone !== 'yes') { ?><th class="customer_phone_woor"><span class="nobr"><?php \Elementor\Icons_Manager::render_icon($settings['customer_phone_icon'], ['aria-hidden' => 'true']); ?> <?php echo esc_html($customer_phone); ?></span></th><?php }; ?>
								<?php if ($hide_sell_amount !== 'yes') { ?><th class="sell_amount_woor"><span class="nobr"><?php \Elementor\Icons_Manager::render_icon($settings['amount_icon'], ['aria-hidden' => 'true']); ?> <?php echo esc_html($sell_amount); ?></span></th><?php }; ?>
							</tr>
						</thead>

						<tbody>
								<?php
									
									$sl= 1;
								foreach ($customer_orders as $customer_order) {
									// Get customer order details using ID
									$order = wc_get_order( $customer_order->ID );

									//Get the date the order was created
									$order_date = $order->get_date_created();
									
								?>
								<tr style="text-align:<?php echo esc_html($oltew_table_td_alignment);?>">
									<?php if ($hide_order_sl !== 'yes') { ?>
										<td class="order_count"><?php echo esc_html($sl++); ?></td>
									<?php } if ($hide_customer_title !== 'yes') { ?>
										<td>
											
											<?php \Elementor\Icons_Manager::render_icon($customer_list_icon, ['aria-hidden' => 'true']); ?> 
											
											<?php
											//Customer Name List//
											//////////////////////

											$customer_info_order_list = $order->get_user();

											if ($customer_info_order_list) {
												$user_id = $customer_info_order_list->ID; // Get the user ID from the user object
												
												// Get the user data using the user ID
												$user_data = get_userdata($user_id);
												
												if ($user_data) {
													$display_name = $user_data->display_name; // Get the nicename
													$user_username = $user_data->user_login; // Get the username
												}
											}
											
											//Get Billing First & Last Name
											$customer_list_names = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
											
											if($order->get_billing_first_name() || $order->get_billing_last_name()){
												echo esc_html($customer_list_names); 
											}elseif($display_name){
												echo esc_html($display_name);

											}else{
												echo esc_html('Guest');
											}
											?>
										</td>
									<?php } if ($hide_sell_time !== 'yes') { ?>
										<td>
											<?php \Elementor\Icons_Manager::render_icon($sell_list_time_icon, ['aria-hidden' => 'true']); ?>
											<?php
											if ($order_time_format == 'ago') {
												if (function_exists('oltew_ago_woo_list_table')) {
													echo esc_html(oltew_ago_woo_list_table($order_date));
												}
											}
											if ($order_time_format == 'date') {
												echo esc_html(date('d/M/y h:i A', strtotime($order_date)));
											}
											?>
										</td>
									<?php } if ($hide_status !== 'yes') { ?>
										<td>
											<?php
												$order_status = $order->get_status();
												if ($order_status == 'completed') {
													echo '<span class="st_completed status_odd"><i class="fas fa-check-circle"></i> Completed</span>';
												}

												if ($order_status == 'processing') {
													echo '<span class="st_processing status_odd"><i class="fas fa-clock"></i> Processing</span>';
												}
												if ($order_status == 'on-hold') {
													echo '<span class="st_on-hold status_odd"><i class="fas fa-pause-circle"></i> On Hold</span>';
												}

												if ($order_status == 'cancelled') {
													echo '<span class="st_cancelled status_odd"><i class="fas fa-times-circle"></i> Cancelled</span>';
												}

												if ($order_status == 'failed') {
													echo '<span class="st_cancelled status_odd"><i class="fas fa-times-circle"></i> Failed</span>';
												}
											?>
										</td>
									<?php } if ($hide_product_name !== 'yes') { ?>
										<td>
											<?php \Elementor\Icons_Manager::render_icon($product_list_icon, ['aria-hidden' => 'true']); ?>
											<?php
											$item_names = array();
											foreach ($order->get_items() as $item) {
													$item_names[] = esc_html($item->get_name());
											}
											echo implode( ",", $item_names);
											?>
										</td>
									<?php } if ($hide_customer_phone !== 'yes') { ?>
										<td>
											<?php
											\Elementor\Icons_Manager::render_icon($customer_phone_list_icon, ['aria-hidden' => 'true']);
											$customer_phone_woo = $order->get_billing_phone();
											echo esc_html(' ' . substr($customer_phone_woo, 0, -4) . 'XXXX');
											?>
										</td>
									<?php } if ($hide_sell_amount !== 'yes') { ?>
										<td>
											<?php
											$oltew_amount = $order->get_total();
											$oltew_currency = $order->get_currency();
											$currency_symbol = get_woocommerce_currency_symbol( $oltew_currency );
											echo esc_html($currency_symbol.$oltew_amount.' '.$oltew_currency);
											?>
										</td>
									<?php }; ?>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
<?php endif;
	endif;
	}
}
