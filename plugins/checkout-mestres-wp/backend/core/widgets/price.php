<?php
class Checkout_Price extends \Elementor\Widget_Base {
	public function get_name() {
		return 'checkout-price';
	}
	public function get_title() {
		return esc_html__( 'Preço | MWP', 'checkout-mestres-wp' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'cwmp-addons' ];
	}

	public function get_keywords() {
		return [ 'checkout', 'mestres wp', 'mestres' ];
	}
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Preço Regular', 'mestres-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'cwmp_qrcode_pix_align',
			[
				'label' => esc_html__( 'Alinhamento', 'mestres-wp' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'mestres-wp' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mestres-wp' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'mestres-wp' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .pmwp_price' => 'text-align: {{VALUE}};',
				],
			]
		);


		
		$this->end_controls_section();

	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		//update_option('cwmp_checkout_box_icon_color_cart',$settings['cwmp_checkout_box_icon_color_cart']);
		echo do_shortcode('[get_pmwp_price]');
	}


}