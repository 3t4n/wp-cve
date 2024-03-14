<?php
/**
 * Edd Menu Cart Module
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEW_Widget_Edd_Menu_Cart_Lite extends Widget_Base {

	public function get_name() {
		return 'bew-edd-menu-cart-lite';
	}

	public function get_title() {
		return __( 'Edd Menu Cart Lite', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements-lite' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_menu_cart',
			[
				'label' 		=> __( 'EDD Menu Cart', 'briefcase-elementor-widgets' ),
			]
		);

		
		$this->add_responsive_control(
			'position',
			[
				'label' 		=> __( 'Position', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' 		=> [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
            'icon_type',
            [
                'label' => __( 'Icon Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'cart' => [
						'title' => __( 'Shopping Cart', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-cart',
					],
					'bag' => [
						'title' => __( 'Shopping Bag', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-bag',
					],
					'basket' => [
						'title' => __( 'Shopping Basket', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-basket',
					]
				],
				'default' => 'cart',
				
            ]
        );
		
		$this->add_control(
			'cart_style',
			[
				'label' 		=> __( 'Cart Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'a',
				'options' 		=> [
					'a' 			=> __( 'Circle', 'briefcase-elementor-widgets' ),
					'b' 			=> __( 'Square', 'briefcase-elementor-widgets' ),
					'c' 			=> __( 'Minimalist', 'briefcase-elementor-widgets' ),
					'd' 			=> __( 'Custom', 'briefcase-elementor-widgets' ),
				],
			]
		);
		
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General Style', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'cart_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'cart_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Cart Icon', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'size',
			[
				'label' => __( 'Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 22,
					],
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart i.fa' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'default' 		=> '#7a7a7a',
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart i' => 'color: {{VALUE}};',
				],
			]
		);
		
				
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon__hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart i:hover' => 'color: {{VALUE}};',
				],
			]
		);

				
		$this->end_controls_tab();

		$this->end_controls_tabs();	
		
		$this->add_control(
			'icon_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart .edd-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} #custom-header-add-cart .edd-header-cart .edd-cart-btn',
				'separator' => 'before',
							
			]
		);
			
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart .edd-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_quantity',
			[
				'label' 		=> __( 'Cart Quantity', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'quantity_position_top',
			[
				'label' => __( 'Top', 'elementor' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity' => 'top: {{VALUE}}px',
				],
			]
		);
		
		$this->add_control(
			'quantity_position_right',
			[
				'label' => __( 'Right', 'elementor' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity' => 'right: {{VALUE}}px',
				],
			]
		);
		
		$this->add_control(
			'quantity_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity' => 'color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'quantity_bg_color',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart span:before' => 'border-right-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'arrow_before_span',
			[
				'label' 		=> __( 'Display Arrow', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'cart_style' => [ 'b', 'd'],					
					],
			]
		);
		
		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __( 'Size (%)', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 90,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 60,
						'max' => 100,
					],
				],
				'condition' => [
					'arrow_before_span' => [ 'yes'],
					'cart_style' => [ 'b', 'd'],					
					],
				'selectors' => [
					'{{WRAPPER}} #custom-header-add-cart .edd-arrow span:before' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_control(
			'text_after_span',
			[
				'label' 		=> __( 'Display Text', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'cart_style' => [ 'c', 'd'],					
					],
			]
		);
		
		$this->add_control(
			'text_after_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart .edd-item span:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'text_after_span' => [ 'yes'],
					'cart_style' => [ 'c', 'd'],	
					],				
			]
		);
		
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'quantity_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'quantity_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'quantity_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'quantity_typo',
				'selector' 		=> '{{WRAPPER}} #custom-header-add-cart .edd-header-cart .header-cart.edd-cart-quantity',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		
		$this->end_controls_section();
		

	}
	
	

	protected function render() {
		$settings = $this->get_settings(); 	
		
		$cart = $settings['cart_style'];	
		$icon = $settings['icon_type'];			
		$arrow = $settings['arrow_before_span'];
		$item = $settings['text_after_span'];

	
		// If quantity after span
					$wrap_classes = array( );
					if ( 'yes' == $arrow and 'b' == $cart) {
					$wrap_classes[] = 'edd-arrow';
					}
					if ( 'yes' == $arrow and 'd' == $cart) {
					$wrap_classes[] = 'edd-arrow';
					}
				
				
										
		// If quantity before span
					
					if ( 'yes' == $item and 'c' == $cart) {
					$wrap_classes[] = 'edd-item';
					}
					
					if ( 'yes' == $item and 'd' == $cart) {
					$wrap_classes[] = 'edd-item';
					}
				
									
			$wrap_classes = implode( ' ', $wrap_classes );		
										
		// cart style options
					
					if ( 'a' == $cart ) {
						$type_classes = 'circle';
					}
					elseif ( 'b' == $cart ) {
						$type_classes = 'square';
					}
					elseif ( 'c' == $cart ) {
						$type_classes = 'minimalist';
					}
					elseif ( 'd' == $cart ) {
						$type_classes = 'custom';
					}
			
			?>		
				

		<div id="custom-header-add-cart" class="clr">

			<?php
			// Menu Cart EDD
			if( class_exists( 'Easy_Digital_Downloads' ) ) { ?>
    				<span class="edd-header-cart <?php echo esc_attr( $wrap_classes  ); ?>">
    					<a href="<?php echo edd_get_checkout_uri(); ?>" class="edd-cart-btn <?php echo esc_attr( $type_classes ); ?>">
    						<i class="fa fa-shopping-<?php echo esc_attr( $icon  ); ?>"></i> 
    						<span class="header-cart edd-cart-quantity <?php echo esc_attr( $type_classes ); ?>"><?php echo intval( edd_get_cart_quantity() ); ?></span>
    					</a>
    				</span>
    			<?php } ?>
		
		</div>

	<?php
	}
	
}

Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_Edd_Menu_Cart_Lite() );