<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Product_Tabs extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-product-tabs';
	}

	public function get_title() {
		return esc_html__( 'Product Tabs', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-tabs';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_PRODUCT );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'general_content_section',
			array(
				'label' => esc_html__( 'General', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'tabs'      => esc_html__( 'Tabs', 'thim-elementor-kit' ),
					'all_open'  => esc_html__( 'All open', 'thim-elementor-kit' ),
					'accordion' => esc_html__( 'Accordion (cooming soon)', 'thim-elementor-kit' ),
				),
				'default' => 'tabs',
			)
		);

		$this->add_control(
			'enable_description',
			array(
				'label'        => esc_html__( 'Enable description tab', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'enable_additional_info',
			array(
				'label'        => esc_html__( 'Enable additional info tab', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'enable_reviews',
			array(
				'label'        => esc_html__( 'Enable reviews tab', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
		$this->_register_style_tabs_controls();
		$this->_register_style_title_controls();
		$this->_register_style_content_controls();

	}

	protected function _register_style_tabs_controls() {
		$this->start_controls_section(
			'section_product_general_style',
			array(
				'label'     => esc_html__( 'General', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout' => 'all_open',
				),
			)
		);
		$this->add_responsive_control(
			'tabs_space_between_tabs_title_vertical',
			array(
				'label'      => esc_html__( 'Vertical spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_product_tabs_style',
			array(
				'label'     => esc_html__( 'Tabs', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout' => 'tabs',
				),
			)
		);

		$this->add_control(
			'tab_item_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}}  .woocommerce-tabs .wc-tabs' => 'justify-content:{{VALUE}};width: 100%;',
				),
			)
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pagination_border',
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
			)
		);
		$this->add_control(
			'tab_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_responsive_control(
			'tab_item_padding',
			[
				'label'      => esc_html__( 'Padding Item', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'tab_item_spacing',
			array(
				'label'      => esc_html__( 'Horizontal spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'tabs_space_between_tab_vertical',
			array(
				'label'      => esc_html__( 'Vertical spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li,{{WRAPPER}} .woocommerce-tabs ul.wc-tabs' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_style_tab' );
		$this->start_controls_tab(
			'normal_tabs_style',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tab_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tabs_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a'         => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'active_tabs_style',
			array(
				'label' => esc_html__( 'Active', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'active_tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'active_tab_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs'             => 'border-bottom-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'active_tabs_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a'  => 'border-color: {{VALUE}} {{VALUE}} {{active_tab_bg_color.VALUE}} {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function _register_style_content_controls() {
		$this->start_controls_section(
			'section_product_content_style',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'content_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'content_item_padding',
			[
				'label'      => esc_html__( 'Padding Item', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'content_border',
				'selector' => '{{WRAPPER}} .woocommerce-Tabs-panel',
			)
		);
		$this->add_control(
			'content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _register_style_title_controls() {
		$this->start_controls_section(
			'section_description_style',
			array(
				'label'     => esc_html__( 'Title', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout' => 'all_open',
				),
			)
		);
		$this->add_control(
			'title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'text-align:{{VALUE}};',
				),
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .woocommerce-Tabs-panel h2',
			)
		);
		$this->add_responsive_control(
			'title_space',
			array(
				'label'      => esc_html__( 'spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-product/before-preview-query' );

		$product = wc_get_product( false );

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		if ( $settings['enable_additional_info'] != 'yes' ) {
			add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
				unset( $tabs['additional_information'] );

				return $tabs;
			}, 98 );
		}
		if ( $settings['enable_reviews'] != 'yes' ) {
			add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
				unset( $tabs['reviews'] );

				return $tabs;
			}, 98 );
		}
		if ( $settings['enable_description'] != 'yes' ) {
			add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
				unset( $tabs['description'] );

				return $tabs;
			}, 98 );
		}
		$class_layout_tabs = 'product-tabs-' . $settings['layout'];
		?>
		<div class="thim-ekit-single-product__tabs <?php echo $class_layout_tabs; ?>">
			<?php wc_get_template( 'single-product/tabs/tabs.php' ); ?>
		</div>

		<?php
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<script>
				jQuery('.wc-tabs-wrapper, .woocommerce-tabs, #rating').trigger('init');
			</script>
			<?php
		}

		do_action( 'thim-ekit/modules/single-product/after-preview-query' );
	}
}
