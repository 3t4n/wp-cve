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




class wpsection_wps_popup_Widget extends \Elementor\Widget_Base {


public function get_name() {
		return 'wpsection_wps_popup';
	}

	public function get_title() {
		return __( 'Popup Button', 'wpsection' );
	}

	public function get_icon() {
		 return 'eicon-dual-button';
	}

	public function get_keywords() {
		return [ 'wpsection', 'wps_popup' ];
	}
	public function get_categories() {
    return [ 'wpsection_category' ];
	} 


	protected function register_controls() {
		$this->start_controls_section(
			'wps_popup',
			[
				'label' => esc_html__( 'Popup', 'wpsection' ),
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
			'popup_title', [
				'label'       => esc_html__( 'Popup Buton', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Popup Button',
				'dynamic'     => [
					'active' => true,
				],
			]
		);
		
	$this->add_control(
			'popup_shortocde', [
				'label'       => esc_html__( 'Popup Shortcode', 'element-path' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => 'Popup Shortcode',
				'dynamic'     => [
					'active' => true,
				],
			]
		);
		

		$this->end_controls_section();

		
// Button Setting
	
$this->start_controls_section(
			'popup_button_control',
			array(
				'label' => __( 'Popup Button Settings', 'wpsection' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
 $this->add_control(
                    'popup__alingment',
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
                            '{{WRAPPER}} .defult_popup' => 'text-align: {{VALUE}} !important',
                        ),
                    )
                ); 	

$this->add_control(
			'popup_button_color',
			array(
				'label'     => __( 'Button Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
	
				'selectors' => array(
					'{{WRAPPER}}  .defult_popup .popup_button' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'popup_button_color_hover',
			array(
				'label'     => __( 'Button Hover Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .defult_popup .popup_button:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'popup_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .defult_popup .popup_button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'popup_button_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .defult_popup .popup_button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);	
		
		
	
$this->add_control( 'popup_button_width',
					[
						'label' => esc_html__( 'Arraw Width',  'wpsection' ),
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
							'{{WRAPPER}} .defult_popup .popup_button' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'popup_button_height',
					[
						'label' => esc_html__( 'Arraw Height', 'wpsection' ),
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
							'{{WRAPPER}} .defult_popup .popup_button' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'popup_button_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .defult_popup .popup_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'popup_button_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .defult_popup .popup_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'popup_button_typography',
				'label'    => __( 'Typography', 'wpsection' ),
				'selector' => '{{WRAPPER}}  .defult_popup .popup_button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'popup_button_border',
				'selector' => '{{WRAPPER}}  .defult_popup .popup_button ',
			)
		);
	

		$this->add_control(
			'popup_button_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .defult_popup .popup_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


			$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'popup_shadow',
				'label' => esc_html__( 'Button Shadow', 'ecolab' ),
				'selector' => '{{WRAPPER}} .defult_popup .popup_button',
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
     <script>
 jQuery(document).ready(function($)
 {


    // Open Popup
      const openPopupButtons = document.querySelectorAll(".open-popup");
      openPopupButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const popup = button.nextElementSibling;
          popup.style.display = "flex";
        });
      });

      // Close Popup
      const closePopupButtons = document.querySelectorAll(".close-popup");
      closePopupButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const popup = button.parentElement.parentElement;
          popup.style.display = "none";
        });
      });

      // Close Popup when clicked outside of it
      window.addEventListener("click", (event) => {
        const popups = document.querySelectorAll(".popup-overlay");
        popups.forEach((popup) => {
          if (event.target === popup) {
            popup.style.display = "none";
          }
        });
      });



//put the code above the line 
  });
</script>';
		

      echo '
 <style>

 /* Popup overlay */
  .defult_popup .popup-overlay {
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

.defult_popup .popup-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    position: relative;
    width: 80%;
}
      /* Close button */
.defult_popup .close-popup {
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


.defult_popup .open-popup {
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

.defult_popup .open-popup:hover{
    color: #fff;
	background-color:#222;
}
</style>';		
		

?>

<!-- This is the Main Area Astart=================== --> 
<div class="defult_five mr_popup <?php echo esc_attr($settings['sec_class']);?>">  
	
<div class="defult_popup">

<!-- Slider Mask=================== -->
        <button class="open-popup popup_button"><?php echo $settings['popup_title'];?></button>
            <div class="popup-overlay">
            <div class="popup-content">
                <button class="close-popup">&times;</button>
                <?php echo $settings['popup_shortocde'];?>
            </div>
            </div>
 
	
<!-- End Slider Mask=================== -->
</div>   
	
<!-- End of Main Area =================== -->	
</div>



             
		<?php 
	}

}


Plugin::instance()->widgets_manager->register( new \wpsection_wps_popup_Widget() );