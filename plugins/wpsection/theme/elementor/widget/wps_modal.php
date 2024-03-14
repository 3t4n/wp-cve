<?php

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;




class wpsection_wps_modal_Widget extends \Elementor\Widget_Base {


public function get_name() {
		return 'wpsection_wps_modal';
	}

	public function get_title() {
		return __( 'Modal Shop', 'wpsection' );
	}

	public function get_icon() {
		 return 'eicon-menu-toggle';
	}

	public function get_keywords() {
		return [ 'wpsection', 'wps_modal' ];
	}
	public function get_categories() {
    return [ 'wpsection_category' ];
	} 


	protected function register_controls() {
		$this->start_controls_section(
			'wps_modal',
			[
				'label' => esc_html__( 'Modal', 'wpsection' ),
			]
		);
		$this->add_control(
			'modal_sec_class',
			[
				'label'       => __( 'Section Class', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter Section Class', 'rashid' ),
			]
		);
	
	  	
		
		
	$this->add_control(
			'modal_shortocde', [
				'label'       => esc_html__( 'Modal Shortcode', 'element-path' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);
		

		$this->end_controls_section();

		
// Button Setting
	
$this->start_controls_section(
			'modal_x_control',
			array(
				'label' => __( 'Modal Settings', 'wpsection' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		



$this->add_control(
			'modal_x_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}   .wps_modal .modal-contet-wrap' => 'background: {{VALUE}} !important',
				),
			)
		);	

		
	
$this->add_control( 'modal_x_width',
					[
						'label' => esc_html__( 'Width',  'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						
						'selectors' => [
							'{{WRAPPER}}  .wps_modal .modal-contet-wrap' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'modal_x_height',
					[
						'label' => esc_html__( ' Height', 'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						
						'selectors' => [
							'{{WRAPPER}}  .wps_modal .modal-contet-wrap' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'modal_x_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}   .wps_modal .modal-contet-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);



		$this->add_control(
			'modal_x_button_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_modal .modal-contet-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


			$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'modal_x_shadow',
				'label' => esc_html__( 'Button Shadow', 'ecolab' ),
				'selector' => '{{WRAPPER}} .modal-contet-wrap',
			]
		);
		
		$this->end_controls_section();	

// Button Close Modal		
	
		
	
$this->start_controls_section(
			'modal_y_control',
			array(
				'label' => __( 'Modal Close Button Settings', 'wpsection' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
   $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'modal_y_typo',
                'label'    => __( 'Product Rating Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}}  .wps_modal .close-modal',
            )
        );

$this->add_control(
			'modal_y_color',
			array(
				'label'     => __( 'Close Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}   .wps_modal .close-modal' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'modal_y_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}   .wps_modal .close-modal' => 'background: {{VALUE}} !important',
				),
			)
		);	

		
	
$this->add_control( 'modal_y_width',
					[
						'label' => esc_html__( 'Width',  'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						
						'selectors' => [
							'{{WRAPPER}}  .wps_modal .close-modal' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'modal_y_height',
					[
						'label' => esc_html__( ' Height', 'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						
						'selectors' => [
							'{{WRAPPER}}  .wps_modal .close-modal' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'modal_y_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}   .wps_modal .close-modal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);



		$this->add_control(
			'modal_y_button_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_modal .close-modal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


			$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'modal_y_shadow',
				'label' => esc_html__( 'Button Shadow', 'ecolab' ),
				'selector' => '{{WRAPPER}} .wps_modal .close-modal',
			]
		);
		
		$this->end_controls_section();	

		
		}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');
		?>


<?php

echo '
 <style>


.wps_modal .modal {
    display: none;
    position: fixed;
    top: 0px;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 99;
}
.wps_modal .modal-content {
    background-color: #fff0;
    margin: 15% auto;
    border: none;
    width: 100%;
    position: relative;
    z-index: 999999999999;
}		

.wps_modal .close-modal {
    position: absolute;
    top: 0px;
    right: 0;
    font-size: 20px;
    cursor: pointer;
    ba: red;
    background: #ff1212;
    color: #fff;
    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 36px;
	z-index:999;
}
.modal-contet-wrap{
margin:0 auto ;
    position: relative;
}

#wpadminbar .wps_modal .modal{
   top: 32px;
}
		
</style>';		
		

?>


   <section class="wps_modal  <?php echo esc_attr($settings['modal_sec_class']);?>">
        <!-- Modal -->
        <div id="custom-modal" class="modal">
            <div class="modal-content">
				<div class="modal-contet-wrap">
                <span class="close-modal" id="closeModalBtn">&times;</span>
                 <?php echo $settings['modal_shortocde'];?>
				</div>
            </div>
        </div>
    </section>


             
		<?php 
	}

}


Plugin::instance()->widgets_manager->register( new \wpsection_wps_modal_Widget() );