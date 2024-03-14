<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Copy_Past_Pix extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'copy_past-pix-cwmp';
	}
	public function get_title() {
		return esc_html__( 'Copy Past Pix', 'checkout-mestres-wp' );
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
				'label' => esc_html__( 'Altura', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 3,
				'max' => 10,
				'step' => 1,
				'default' => 5,

			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content_section_two',
			[
				'label' => __( 'Botão', 'mestres-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'cwmp_qrcode_pix_button_text',
			[
				'label'     => __( 'Label', 'mestres-wp' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default' => 'Copiar código'
			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_font',
			[
				'label' => esc_html__( 'Fonte', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::FONT,
				'default' => "'Open Sans', sans-serif",
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'font-family: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_weight',
			[
				'label'     => __( 'Peso', 'mestres-wp' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'      => 'mp',
				'default'=>'none',
				'options' => [
					'100' => '100',
					'300' => '300',
					'400' => '400',
					'500' => '500',
					'600' => '600',
					'700' => '700',
					'900' => '900'
				],
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'font-weight: {{VALUE}}',
				]
			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_style',
			[
				'label'     => __( 'Transformação', 'mestres-wp' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'      => 'mp',
				'default'=>'none',
				'options' => [
					'none' => 'None',
					'capitalize' => 'Capitalize',
					'uppercase' => 'Uppercase',
					'lowercase' => 'Lowercase'
				],
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'text-transform: {{VALUE}}',
				]
			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_size_font',
			[
				'label' => esc_html__( 'Tamanho', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 12,
				'max' => 30,
				'step' => 1,
				'default' => 15,
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'font-size: {{VALUE}}px;',
				],

			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_size_width',
			[
				'label' => esc_html__( 'Largura', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 10,
				'max' => 100,
				'step' => 1,
				'default' => 50,
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'width: {{VALUE}}%;',
				],

			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_size_height',
			[
				'label' => esc_html__( 'Altura', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 40,
				'max' => 100,
				'step' => 1,
				'default' => 50,
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'height: {{VALUE}}px;',
				],

			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 100,
				'step' => 10,
				'default' => 20,
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'border-radius: {{VALUE}}px;',
				],

			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_background',
			[
				'label' => esc_html__( 'Background', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'background: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'cwmp_qrcode_pix_button_color',
			[
				'label' => esc_html__( 'Cor', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .buttoncopypast' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		echo "<div class='cwmp_qrcode_pix'>";
		if(isset($_GET['cwmp_order'])){
		$code_pix = do_shortcode('[cwmpPixCopyPast]');
		echo "
		<textarea class='copypast' rows='".$settings['cwmp_qrcode_pix_size']."'>" . $code_pix . "</textarea>
		<button class='buttoncopypast' style='border:0px;'>".$settings['cwmp_qrcode_pix_button_text']."</button>
		<p style='display:none' class='return_copy'>Código Copiado</p>
		<script type='text/javascript'>
		jQuery(document).ready(function($) {
		$('.buttoncopypast').click(function(){
			navigator.clipboard.writeText($('textarea.copypast').val());
			$('.return_copy').show();
		});
		});
		</script>
		";
		}else{
			echo "
			<textarea class='copypast' rows='".$settings['cwmp_qrcode_pix_size']."'>00020126350014br.gov.bcb.pix0113j@rjsc.com.br5204000053039865406116.995802BR5924JARDELJUNIORJARDELJUNIOR6006Niteri62240520mpqrinter530739545526304C1FF</textarea>
			<button class='buttoncopypast'>".$settings['cwmp_qrcode_pix_button_text']."</button>
			<p style='display:none' class='return_copy'>Código Copiado</p>
			<script type='text/javascript'>
			jQuery(document).ready(function($) {
			$('.buttoncopypast').click(function(){
				navigator.clipboard.writeText($('textarea.copypast').val());
				$('.return_copy').show();
			});
			});
			</script>
				
				";
			}
		echo "</div>";
	}



}