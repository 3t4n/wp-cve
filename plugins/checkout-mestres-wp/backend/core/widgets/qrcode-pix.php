<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class QR_Code_Pix extends \Elementor\Widget_Base {
	public function get_name() {
		return 'qrcode-pix-cwmp';
	}
	public function get_title() {
		return esc_html__( 'QRCode Pix', 'checkout-mestres-wp' );
	}
	public function get_icon() {
		return 'eicon-code';
	}
	public function get_custom_help_url() {
		return 'https://mestresdowp.com.br/';
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
				'label' => __( 'Gateways', 'mestres-wp' ),
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
					'{{WRAPPER}} .cwmp_qrcode_pix' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_size',
			[
				'label' => esc_html__( 'Tamanho', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 150,
				'max' => 500,
				'step' => 5,
				'default' => 150,

			]
		);
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		echo "<div class='cwmp_qrcode_pix'>";
		if(isset($_GET['cwmp_order'])){
			echo do_shortcode('[cwmpPixQRCode]');
		}else{
			echo "<img src='data:image/jpeg;base64,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' width='".$settings['cwmp_qrcode_pix_size']."' height='".$settings['cwmp_qrcode_pix_size']."' />";
		}
		echo "</div>";
	}
}