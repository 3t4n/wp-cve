<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;

/**
 * Class Elementor_WFTY_Order_Details_Widget
 */
if ( ! class_exists( 'Elementor_WFTY_Order_Details_Widget' ) ) {
	#[AllowDynamicProperties]

  class Elementor_WFTY_Order_Details_Widget extends \Elementor\Widget_Base {

		/**
		 * Get widget name.
		 *
		 *
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'wfty-order-detail';
		}

		/**
		 * Get widget title.
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __( 'Order Details', 'funnel-builder' );
		}

		/**
		 * Get widget icon.
		 *
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'wfty-icon-offer_title';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the widget belongs to.
		 * @access public
		 *
		 * @return array Widget categories.
		 */
		public function get_categories() {
			return [ 'wffn_woo_thankyou' ];
		}


		/**
		 * Register widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @access protected
		 */
		protected function register_controls() {
			$defaults = WFFN_Core()->thank_you_pages->default_shortcode_settings();

			$this->start_controls_section( 'section_detail_title', [
				'label' => __( 'Order Details', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

			$this->add_control( 'order_details_heading', [
				'label'   => __( 'Heading', 'elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'classes' => 'wfty-elementor-heading-text',
				'default' => isset( $defaults['order_details_heading'] ) ? $defaults['order_details_heading'] : __( 'Order Details', 'funnel-builder' ),
			] );

			$this->end_controls_section();

			$this->start_controls_section( 'section_subscription_title', [
				'label' => __( 'Subscription', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

			$this->add_control( 'wfty_el_subscription_heading_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This section will only show up in case of order will have subscription.', 'funnel-builder' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				'separator'       => 'before',
			] );

			$this->add_control( 'order_subscription_heading', [
				'label'   => __( 'Heading', 'elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'classes' => 'wfty-elementor-heading-text',
				'default' => isset( $defaults['order_subscription_heading'] ) ? $defaults['order_subscription_heading'] : __( 'Subscription', 'funnel-builder' ),
			] );

			$this->add_control( 'order_subscription_preview', [
				'label'        => __( 'Show Subscription Preview', 'elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			] );

			$this->end_controls_section();

			$this->start_controls_section( 'section_download_title', [
				'label' => __( 'Download', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			] );

			$this->add_control( 'wfty_el_downloads_heading_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This section will only show up in case of order will have downloads.', 'funnel-builder' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				'separator'       => 'before',
			] );

			$this->add_control( 'order_download_heading', [
				'label'   => __( 'Heading', 'elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'classes' => 'wfty-elementor-heading-text',
				'default' => isset( $defaults['order_download_heading'] ) ? $defaults['order_download_heading'] : __( 'Downloads', 'funnel-builder' )
			] );

			$this->add_control( 'order_downloads_btn_text', [
				'label'   => __( 'Download Button Text', 'elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'classes' => 'wfty-elementor-heading-text',
				'default' => $defaults['order_downloads_btn_text'],
			] );

			$this->add_control( 'order_download_preview', [
				'label'        => __( 'Show Download Preview', 'elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			] );

			$this->add_control( 'order_downloads_show_file_downloads', [
				'label'        => __( 'Show File Downloads Column', 'elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			] );

			$this->add_control( 'order_downloads_show_file_expiry', [
				'label'        => __( 'Show File Expiry Column', 'elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			] );

			$this->end_controls_section();

			$this->start_controls_section( 'section_heading_style', [
				'label' => __( 'Heading', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

			$this->_add_color( 'heading_color', [
				'label'   => __( 'Color', 'elementor-pro' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#000',

				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_title' => 'color: {{VALUE}}',
				],
			] );

			$this->_add_typography( Group_Control_Typography::get_type(), [
				'name'           => 'typography_heading',
				'label'          => 'Typography',
				'selector'       => '{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_title',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 24 ] ],
					'font_weight' => [ 'default' => 600 ],
					'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
				],
			] );
			$this->add_responsive_control( 'heading_align', [
				'label'     => __( 'Heading Alignment', 'elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_title' => 'text-align: {{VALUE}}',
				],
			] );

			$this->end_controls_section();

			$this->start_controls_section( 'section_cart_style', [
				'label' => __( 'Details', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

			$this->add_control( 'product_label', [
				'label' => __( 'Product', 'elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			] );

			$this->_add_color( 'product_text_color', [
				'label'   => __( 'Color', 'elementor-pro' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#565656',

				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont .wfty_pro_list *' => 'color: {{VALUE}}',
				],
			] );

			$this->_add_typography( Group_Control_Typography::get_type(), [
				'name'  => 'product_typography',
				'label' => 'Typography',

				'selector'       => '{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont .wfty_pro_list *',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 15 ] ],
					'font_weight' => [ 'default' => 400 ],
					'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
				],
			] );
			$this->add_control( 'order_details_img', [
				'label'        => __( 'Show Images', 'funnel-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			] );

			$this->add_control( 'subtotal_label', [
				'label'     => __( 'Subtotal', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			] );

			$this->_add_color( 'subtotal_text_color', [
				'label'   => __( 'Color', 'elementor-pro' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#565656',

				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) *' => 'color: {{VALUE}}',
				],
			] );

			$this->_add_typography( Group_Control_Typography::get_type(), [
				'name'           => 'subtotal_typography',
				'label'          => 'Typography',
				'selector'       => '{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont table tr:not(:last-child) *',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redefine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 15 ] ],
					'font_weight' => [ 'default' => 400 ],
					'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
				],
			] );
			$this->add_control( 'total_label', [
				'label'     => __( 'Total', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			] );

			$this->_add_color( 'total_text_color', [
				'label'   => __( 'Color', 'elementor-pro' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#565656',

				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont table tr:last-child *' => 'color: {{VALUE}}',
				],
			] );


			$this->_add_typography( Group_Control_Typography::get_type(), [
				'name'           => 'total_typography',
				'label'          => 'Typography',
				'selector'       => '{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont table tr:last-child *',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 20 ] ],
					'font_weight' => [ 'default' => 600 ],
					'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
				],
			] );
			$this->add_control( 'variation_label', [
				'label'     => __( 'Variation', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			] );

			$this->_add_color( 'variation_text_color', [
				'label'     => __( 'Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont .wfty_pro_list .wfty_info *' => 'color: {{VALUE}}',
				],
			] );


			$this->_add_typography( Group_Control_Typography::get_type(), [
				'name'           => 'variation_typography',
				'label'          => 'Typography',
				'selector'       => '{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_pro_list_cont .wfty_pro_list .wfty_info *',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redefine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 12 ] ],
					'font_weight' => [ 'default' => 400 ],
					'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
				],
			] );
			$this->add_control( 'divider_label', [
				'label'     => __( 'Divider', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			] );

			$this->_add_color( 'divider_color', [
				'label'     => __( 'Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dddddd',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table table'                                               => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-order-details-wrapper .wfty_pro_list_cont .wfty_pro_list .wfty-hr'                                   => 'color: {{VALUE}}; background-color: {{value}};opacity: 1;border: none;',
					'{{WRAPPER}} .wfty_order_details table tfoot tr:last-child th, {{WRAPPER}} .wfty_order_details table tfoot tr:last-child td' => 'border-top-color: {{VALUE}}',
				],
			] );

			$this->end_controls_section();

			$this->start_controls_section( 'section_subscription_style', [
				'label' => __( 'Subscription', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

			$this->add_control( 'subscription_label', [
				'label'     => __( 'Subscription', 'elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			] );

			$this->_add_color( 'subscription_text_color', [
				'label'     => __( 'Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#565656',
				'selectors' => [
					'body {{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table *, body {{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr th, body {{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td' => 'color: {{VALUE}}',
				],
			] );

			$this->_add_typography( Group_Control_Typography::get_type(), [
				'name'           => 'subscription_typography',
				'label'          => 'Typography',
				'selector'       => 'body {{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table *, body {{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr th, body {{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td, body {{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td:before',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redifine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 15 ] ],
					'font_weight' => [ 'default' => 400 ],
					'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
				],
			] );
			$this->add_control( 'subscription_button_label', [
				'label' => __( 'Button', 'elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			] );

			$this->start_controls_tabs( 'tabs_button_style' );

			$this->start_controls_tab( 'tab_button_normal', [
				'label' => __( 'Normal', 'elementor' ),
			] );

			$this->add_control( 'button_text_color', [
				'label'     => __( 'Label', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions a' => 'color: {{VALUE}}',
				],
			] );

			$this->_add_color( 'background_color', [
				'label'     => __( 'Background', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#70dc1d',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions a' => 'background-color: {{VALUE}};',
				],
			] );

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_button_hover', [
				'label' => __( 'Hover', 'custom-elementor-widget' ),
			] );

			$this->add_control( 'hover_color', [
				'label'     => __( 'Label', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions:hover a' => 'color: {{VALUE}};box-shadow: none;text-decoration: none;',
				],
			] );

			$this->add_control( 'button_background_hover_color', [
				'label'     => __( 'Background', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#89e047',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wffn_order_details_table .wfty_wrap .wfty_subscription table tr td.subscription-actions:hover a' => 'background-color: {{VALUE}};',
				],
			] );

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section( 'section_download_style', [
				'label' => __( 'Download', 'elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

			$this->_add_color( 'text_color', [
				'label'     => __( 'Color', 'elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#565656',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wfty_wrap table.wfty_order_downloads tr *' => 'color: {{VALUE}}; text-align: left;',
				],
			] );

			$this->_add_typography( Group_Control_Typography::get_type(), [
				'name'           => 'typography',
				'label'          => 'Typography',
				'selector'       => '{{WRAPPER}} .elementor-order-details-wrapper .wfty_wrap table.wfty_order_downloads *, body {{WRAPPER}} .elementor-order-details-wrapper .wfty_wrap table.wfty_order_downloads td:before',
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redefine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 15 ] ],
					'font_weight' => [ 'default' => 400 ],
					'line_height' => [ 'default' => [ 'size' => 1.5, 'unit' => 'em' ] ],
				],
			] );

			$this->add_control( 'download_button_label', [
				'label' => __( 'Button', 'elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			] );

			$this->start_controls_tabs( 'tabs_download_button_style' );

			$this->start_controls_tab( 'tab_download_button_normal', [
				'label' => __( 'Normal', 'elementor' ),
			] );

			$this->add_control( 'download_button_text_color', [
				'label'     => __( 'Label', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wfty_wrap table.wfty_order_downloads tr td.download-file a' => 'color: {{VALUE}}',
				],
			] );

			$this->_add_color( 'download_background_color', [
				'label'     => __( 'Background', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#70dc1d',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wfty_wrap table.wfty_order_downloads tr td.download-file a' => 'background-color: {{VALUE}};',
				],
			] );

			$this->end_controls_tab();

			$this->start_controls_tab( 'tab_download_button_hover', [
				'label' => __( 'Hover', 'custom-elementor-widget' ),
			] );

			$this->add_control( 'download_hover_color', [
				'label'     => __( 'Label', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wfty_wrap table.wfty_order_downloads tr td.download-file:hover a' => 'color: {{VALUE}};box-shadow: none;text-decoration: none;',
				],
			] );

			$this->add_control( 'button_background_download_hover_color', [
				'label'     => __( 'Background', 'custom-elementor-widget' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#89e047',
				'selectors' => [
					'{{WRAPPER}} .elementor-order-details-wrapper .wfty_wrap table.wfty_order_downloads tr td.download-file:hover a' => 'background-color: {{VALUE}};',
				],
			] );

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_control( 'wfty_el_downloads_style_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This section will only show up in case of order will have downloads.', 'funnel-builder' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			] );

			$this->end_controls_section();

		}

		public function _add_typography( $group, $args, $typography_type = 'TYPOGRAPHY_1' ) {

			if ( version_compare( ELEMENTOR_VERSION, '3.15.0', '>=' ) ) {
				$args['global'] = [
					'default' => Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				];
			} else {
				$args['scheme'] = Typography::TYPOGRAPHY_1;
			}
			$this->add_group_control( $group, $args );
		}

		public function _add_color( $id, $args ) {
			$this->add_control( $id, $args );
		}

		/**
		 * Render widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @access protected
		 */
		protected function render() {
			$settings                   = $this->get_settings_for_display();
			$classes                    = 'elementor-order-details-wrapper';
			$order_heading_text         = $settings['order_details_heading'];
			$order_subscription_heading = isset( $settings['order_subscription_heading'] ) ? $settings['order_subscription_heading'] : '';

			$download_btn_text       = $settings['order_downloads_btn_text'];
			$show_column_download    = ( empty( $settings['order_downloads_show_file_downloads'] ) || 'no' === $settings['order_downloads_show_file_downloads'] ) ? 'false' : 'true';
			$show_column_file_expiry = ( empty( $settings['order_downloads_show_file_expiry'] ) || 'no' === $settings['order_downloads_show_file_expiry'] ) ? 'false' : 'true';
			$order_download_heading  = isset( $settings['order_download_heading'] ) ? $settings['order_download_heading'] : '';
			$classes                 .= ( empty( $settings['order_download_preview'] ) || 'no' === $settings['order_download_preview'] ) ? ' wfty-hide-download' : '';
			$classes                 .= ( empty( $settings['order_subscription_preview'] ) || 'no' === $settings['order_subscription_preview'] ) ? ' wfty-hide-subscription' : '';

			$order_details_img = 'true';
			if ( ! isset( $settings['order_details_img'] ) || empty( $settings['order_details_img'] ) ) {
				$order_details_img = 'false';
			}

			$this->add_render_attribute( 'wrapper', 'class', $classes );
			?>
            <div <?php echo $this->get_render_attribute_string( 'wrapper' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php
				echo do_shortcode( '[wfty_order_details order_details_img="' . $order_details_img . '" order_details_heading="' . $order_heading_text . '" order_subscription_heading="' . $order_subscription_heading . '" order_download_heading="' . $order_download_heading . '" order_downloads_btn_text="' . $download_btn_text . '" order_downloads_show_file_downloads="' . $show_column_download . '"  order_downloads_show_file_expiry="' . $show_column_file_expiry . '"]' );
				?>
            </div>
			<?php
		}

	}
}