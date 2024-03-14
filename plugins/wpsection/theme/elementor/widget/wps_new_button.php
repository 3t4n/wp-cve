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




class wpsection_wps_new_button_Widget extends \Elementor\Widget_Base {


public function get_name() {
		return 'wpsection_wps_new_button';
	}

	public function get_title() {
		return __( 'Button Lite ', 'wpsection' );
	}

	public function get_icon() {
	     return 'eicon-button';
	}

	public function get_keywords() {
		return [ 'wpsection', 'wps_new_button' ];
	}

	public function get_categories() {
    return [ 'wpsection_category' ];
	} 


	protected function register_controls() {
		$this->start_controls_section(
			'button',
			[
				'label' => esc_html__( 'button', 'wpsection' ),
			]
		);
		$this->add_control(
			'sec_class',
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
			'wps_button', [
				'label'       => esc_html__( 'Buton Text', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Button Text',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'wps_button_link', [
				'label'       => esc_html__( 'Buton Link', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => ' Button Link',
				'dynamic'     => [
					'active' => true,
				],
			]
		);
		
		


		$this->end_controls_section();

		
// Button Setting
	
$this->start_controls_section(
			'wps_button_control',
			array(
				'label' => __( 'Button Settings', 'wpsection' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
	
 $this->add_control(
                    'wps_button_alingment',
                    array(
                        'label' => esc_html__( 'Alignment', 'wpsection' ),
                        'type' => \Elementor\Controls_Manager::CHOOSE,
                        'options' => [
                            'left' => [
                                'title' => esc_html__( 'Left', 'wpsection' ),
                                'icon' => 'eicon-text-align-left',
                            ],
                            'center' => [
                                'title' => esc_html__( 'Center', 'wpsection' ),
                                'icon' => 'eicon-text-align-center',
                            ],
                            'right' => [
                                'title' => esc_html__( 'Right', 'wpsection' ),
                                'icon' => 'eicon-text-align-right',
                            ],
                        ],
                        'default' => 'center',
                        'toggle' => true,
                        'selectors' => array(
                            '{{WRAPPER}}  .defult_wps' => 'text-align: {{VALUE}} !important',
                        ),
                    )
                ); 	

		
		
$this->add_control(
			'wps_button_color',
			array(
				'label'     => __( 'Button Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
	
				'selectors' => array(
					'{{WRAPPER}}   .wps_button' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'wps_button_color_hover',
			array(
				'label'     => __( 'Button Hover Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .defult_wps .wps_button:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'wps_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .defult_wps .wps_button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'wps_button_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .defult_wps .wps_button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);	
		
		
	
$this->add_control( 'wps_button_width',
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
							'{{WRAPPER}} .defult_wps .wps_button' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'wps_button_height',
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
							'{{WRAPPER}} .defult_wps .wps_button' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'wps_button_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .defult_wps .wps_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'wps_button_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .defult_wps .wps_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'wps_button_typography',
				'label'    => __( 'Typography', 'wpsection' ),
				'selector' => '{{WRAPPER}}  .defult_wps .wps_button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'wps_button_border',
				'selector' => '{{WRAPPER}}  .defult_wps .wps_button ',
			)
		);
	

		$this->add_control(
			'wps_button_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .defult_wps .wps_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


			$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wps_button_shadow',
				
				'label' => esc_html__( 'Box Shadow', 'ecolab' ),
				'selector' => '{{WRAPPER}} .defult_wps .wps_button',
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

 /* Wps overlay */
  .defult_wps .wps-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
    z-index: 9;
}

.defult_wps .wps-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    position: relative;
    width: 80%;
}
      /* Close button */
.defult_wps .close-wps {
    position: relative;
    top: 100px;
    right: 100px;
    font-size: 70px;
    background: none;
    border: none;
    cursor: pointer;
    background: #7a7a7a;
    color: #fff;
    /* padding: 20px; */
    /* height: 100px; */
    border: 1px solid #616161;
    padding: 10px;
    border-radius: 50%;
    line-height: 43px;
    float: right;
    z-index: 999;
}


.defult_wps .open-wps {
    position: relative;
    display: inline-block;
    font-size: 16px;
    line-height: 24px;
    font-weight: 600;
    height: 60px;
    width: 200px;
    color: #fff;
    text-align: center;
    /* padding: 18px 40px; */
    text-transform: capitalize;
    z-index: 1;
    box-shadow: 0px 30px 30px rgb(0 0 0 / 10%);
    border-radius: 30px;
    transition: all 500ms ease;
    background-color: #396cf0;
}

.defult_wps .open-wps:hover{
    color: #fff;
	background-color:#222;
}
</style>';		
		

?>

<!-- This is the Main Area Astart=================== --> 
<div class="defult_three mr_wps <?php echo esc_attr($settings['sec_class']);?>">  
	
<div class="defult_wps">

<!-- Slider Mask=================== -->


       <a href="<?php echo $settings['wps_button_link'];?>"> <button class="open-wps wps_button"><?php echo $settings['wps_button'];?></button></a>
	
<!-- End Slider Mask=================== -->
</div>   
	
<!-- End of Main Area =================== -->	
</div>



             
		<?php 
	}

}


Plugin::instance()->widgets_manager->register( new \wpsection_wps_new_button_Widget() );