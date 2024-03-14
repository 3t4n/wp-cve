<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Simulador_Frete extends \Elementor\Widget_Base {
	public function get_name() {
		return 'addon-simulador-frete-cwmp';
	}
	public function get_title() {
		return esc_html__( 'Simulador de Frete', 'checkout-mestres-wp' );
	}
	public function get_icon() {
		return 'eicon-code';
	}
	public function get_custom_help_url() {
		return 'https://mestresdowp.com.br/';
	}
	public function get_categories() {
		return [ 'basic' ];
	}
	public function get_keywords() {
		return [ 'checkout', 'mestres wp', 'mestres' ];
	}
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Gateways', 'mestres-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'cwmp_simulador_input_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => "Digite o seu cep"

			]
		);
		$this->add_control(
			'cwmp_simulador_text_button',
			[
				'label' => esc_html__( 'Text Button', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => "Simular"

			]
		);
		$this->add_control(
			'new_icon',
			[
				'label' => esc_html__( 'Select Icon', 'mwp-elementor-addons' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				]
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_input',
			[
				'label' => __( 'Input', 'mwp-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
		   'cwmp_simulador_input_bottom',
		   [
			  'label' => esc_html__('Background', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => [
			  '{{WRAPPER}} .cwmp-simulador-frete input' => 'background: {{VALUE}};',
			  ],
			  'default' => '#FFFFFF'
		   ]
		);
		$this->add_control(
			'cwmp_simulador_input_padding',
			[
				'label' => esc_html__('Padding', 'mwp-elementor-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cwmp-simulador-frete input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};-webkit-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'default'   => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
			]
		);
		$this->add_control(
			'cwmp_simulador_input_radius',
			[
				'label' => esc_html__('Border Radius', 'mwp-elementor-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cwmp-simulador-frete input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};-webkit-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'default'   => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
			]
		);
		$this->add_group_control(
		   \Elementor\Group_Control_Typography::get_type(),
		   [
			   'name' => 'cwmp_simulador_input_typography',
			   'selector' => '{{WRAPPER}} .cwmp-simulador-frete input',
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_input_color',
		   [
			  'label' => esc_html__('Color', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete input' => 'color: {{VALUE}};'],
			  'default' => '#000000'
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_input_border_size',
		   [
			  'label' => esc_html__('Size Border', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::NUMBER,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete input' => 'border-width: {{VALUE}}px;'],
			  'default' => '1'
		   ]
		);	
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => __( 'Button', 'mwp-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
		   'cwmp_simulador_button_bottom',
		   [
			  'label' => esc_html__('Background', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => [
			  '{{WRAPPER}} .cwmp-simulador-frete button' => 'background: {{VALUE}};',
			  ],
			  'default' => '#000000'
		   ]
		);
		$this->add_control(
			'cwmp_simulador_button_padding',
			[
				'label' => esc_html__('Padding', 'mwp-elementor-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cwmp-simulador-frete button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};-webkit-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'default'   => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
			]
		);
		$this->add_control(
			'cwmp_simulador_button_radius',
			[
				'label' => esc_html__('Border Radius', 'mwp-elementor-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cwmp-simulador-frete button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};-webkit-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'default'   => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
			]
		);
		$this->add_group_control(
		   \Elementor\Group_Control_Typography::get_type(),
		   [
			   'name' => 'cwmp_simulador_button_typography',
			   'selector' => '{{WRAPPER}} .cwmp-simulador-frete button',
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_button_color',
		   [
			  'label' => esc_html__('Color', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete button' => 'color: {{VALUE}};'],
			  'default' => '#FFFFFF'
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_button_border_size',
		   [
			  'label' => esc_html__('Size Border', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::NUMBER,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete button' => 'border-width: {{VALUE}}px;'],
			  'default' => '1'
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_button_icon_spacing',
		   [
			  'label' => esc_html__('Spacing Icon', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::NUMBER,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete button svg' => 'margin-left: {{VALUE}}px;'],
			  'default' => '10'
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_button_icon_size',
		   [
			  'label' => esc_html__('Size Icon', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::NUMBER,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete button svg' => 'width: {{VALUE}}px;'],
			  'default' => '50'
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_button_icon_color',
		   [
			  'label' => esc_html__('Color', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete button svg path' => 'fill: {{VALUE}};'],
			  'default' => '#FFFFFF'
		   ]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_item',
			[
				'label' => __( 'Items Shipping Method', 'mwp-elementor-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'cwmp_simulador_item_padding',
			[
				'label' => esc_html__('Padding Item', 'mwp-elementor-addons'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .cwmp-simulador-frete-simulador ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};-webkit-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'default'   => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
			]
		);
		$this->add_control(
		   'cwmp_simulador_item_odd',
		   [
			  'label' => esc_html__('Background ODD', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete-retorno ul li:nth-child(odd)' => 'color: {{VALUE}};'],
			  'default' => '#EEEEEE'
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_item_even',
		   [
			  'label' => esc_html__('Background EVEN', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete-retorno ul li:nth-child(even)' => 'color: {{VALUE}};'],
			  'default' => '#E2E2E2'
		   ]
		);
		$this->add_group_control(
		   \Elementor\Group_Control_Typography::get_type(),
		   [
				'label' => esc_html__('Typography Shipping Agency', 'mwp-elementor-addons'),
			   'name' => 'cwmp_simulador_title_title',
			   'selector' => '{{WRAPPER}} .cwmp-simulador-frete-retorno ul li h3',
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_item_title_color',
		   [
			  'label' => esc_html__('Color Shipping Agency', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete-retorno ul li h3' => 'color: {{VALUE}};'],
			  'default' => '#FFFFFF'
		   ]
		);
		$this->add_group_control(
		   \Elementor\Group_Control_Typography::get_type(),
		   [
				'label' => esc_html__('Typography Price', 'mwp-elementor-addons'),
			   'name' => 'cwmp_simulador_price',
			   'selector' => '{{WRAPPER}} .cwmp-simulador-frete-retorno ul li h3 span',
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_price_color',
		   [
			  'label' => esc_html__('Color Price', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete-retorno ul li h3 span' => 'color: {{VALUE}};'],
			  'default' => '#FFFFFF'
		   ]
		);
		$this->add_group_control(
		   \Elementor\Group_Control_Typography::get_type(),
		   [
				'label' => esc_html__('Typography Time', 'mwp-elementor-addons'),
			   'name' => 'cwmp_simulador_time',
			   'selector' => '{{WRAPPER}} .cwmp-simulador-frete-retorno ul li p',
		   ]
		);
		$this->add_control(
		   'cwmp_simulador_item_time_color',
		   [
			  'label' => esc_html__('Color Time', 'mwp-elementor-addons'),
			  'type' => \Elementor\Controls_Manager::COLOR,
			  'selectors' => ['{{WRAPPER}} .cwmp-simulador-frete-retorno ul li p' => 'color: {{VALUE}};'],
			  'default' => '#FFFFFF'
		   ]
		);
		$this->end_controls_section();
	}
	protected function render() {
		global $product;
		$product_id = get_the_ID($product);
		$settings = $this->get_settings_for_display();
		
?>
<form class='cwmp-simulador-frete'>
	<div>aaaaaaaaaaa<?php if(isset($settings['new_icon'])){ \Elementor\Icons_Manager::render_icon( $settings['new_icon'], [ 'aria-hidden' => 'true' ] );} ?></div>
	<input type="text" placeholder='<?php echo esc_attr( $settings['cwmp_simulador_input_placeholder'] ); ?>' />
	<button id="<?php echo $product_id; ?>">
	<?php if(isset($settings['new_icon'])){ \Elementor\Icons_Manager::render_icon( $settings['new_icon'], [ 'aria-hidden' => 'true' ] );} ?>
	</button>

</form>
<div class="cwmp-simulador-frete-retorno"><ul></ul></div>
<?php
	}



}