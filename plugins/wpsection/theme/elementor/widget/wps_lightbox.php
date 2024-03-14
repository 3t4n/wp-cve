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



class wpsection_wps_lightbox_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wpsection_wps_lightbox';
	}

	public function get_title() {
		return __( 'Video LightBox', 'wpsection' );
	}

	public function get_icon() {
		 return 'eicon-post-slider';
	}

	public function get_keywords() {
		return [ 'wpsection', 'Video LighBox' ];
	}

	public function get_categories() {
      return ['wpsection_category'];
	} 

	
	protected function register_controls() {

        $this->start_controls_section(
            'wps_lightbox',
            [
                'label' => esc_html__('Genarel Settings', 'wpsection'),
            ]
        );
        $this->add_control(
            'sec_class',
            [
                'label'       => __('Section Class', 'wpsection'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter Section Class', 'wpsection'),
            ]
        );


 $this->add_control(
            'wps_lightbox_image',
            [
                'label'   => esc_html__( 'Select BG Image', 'wpsection' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
                'default' => [
                    'url' => WPSECTION_PLUGIN_URL ."assets/images/placeholder.png",
                ],
            ]
        );
  
	

  $this->add_control(
    'wps_lightbox_icon',
    [
        'label' => esc_html__('Link Icon', 'rashid'),
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-editor-external-link', // Set your default icon class here
            'library' => 'solid', // Set the icon library (solid, regular, or brands)
        ],
    ]
);

  $this->add_control(
            'wps_lightbox_youtube_link', [
                'label'       => esc_html__( 'Youtube Link', 'wpsection' ),
      
                'type'        => Controls_Manager::URL,
            ]
        );  


$this->end_controls_section();

//==================== Star of Setting area==============================================
	
$this->start_controls_section(
            'thumbnail_control',
            array(
                'label' => __( 'Thumbanil Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        



		
    $this->add_control(
            'wps_thumbnail_bg',
            [
                'label' => esc_html__('Background Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .video-popup-box' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#000', 
            ]
        );	
		
	    $this->add_control(
            'wps_thumbnail_hover_bg',
            [
                'label' => esc_html__('Background Hover Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .video-popup-box:before' => 'background: {{VALUE}} !important;',
                ],
                'default' => '#D315FF70', 
            ]
        );		
		
		
    $this->add_control(
            'thumbnail_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
        
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_video_popup .video-popup-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'thumbnail_x_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_video_popup .video-popup-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'thumbnail_border',
                'selector' => '{{WRAPPER}} .wps_video_popup .video-popup-box',
            )
        );
                
            $this->add_control(
            'thumbnail_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_video_popup .video-popup-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );  
		
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'thumbnail_box_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_video_popup .video-popup-box',
			]
		);
        $this->end_controls_section();
        
//End of Thumbnail 
	
		

        $this->start_controls_section(
            'section_portfollio_style',
            [
                'label' => esc_html__('Icon Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
  
	   $this->add_control(
            'wps_project_icon',
            array(
                'label' => esc_html__('Show Icons', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}}  .wps_video_popup .icon-box' => 'display: {{VALUE}} !important',
                ),
            )
        );
	
		
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box i' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box i:hover' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__(' Background Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#D62128', 
            ]
        );
        $this->add_control(
            'wps_project_icon_bg_hover',
            [
                'label' => esc_html__('Background Hover Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box:hover' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#000', 
            ]
        );
		
		
	        $this->add_control(
            'wps_project_icon_width',
            [
                'label' => esc_html__('Icon Box Width',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => 'px',
                    'size' => 85,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'wps_project_icon_height',
            [
                'label' => esc_html__('Icon Box Height', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => 'px',
                    'size' => 85,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );



        $this->add_control(
            'wps_project_icono_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_video_popup .icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'wps_icon_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_video_popup .icon-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_projce_icon_typo',
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}} .wps_video_popup .icon-box',
            )
        );
		
		
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_project_icon_border',
                'selector' => '{{WRAPPER}} .wps_video_popup .icon-box ',
            )
        );


        $this->add_control(
            'wps_project_icon_radious',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .wps_video_popup .icon-box ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
		
    


        $this->add_control(
            'wps_project_expand_icon_horizontal',
            [
                'label' => esc_html__(' Icon Horizontal',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box' => 'left: {{SIZE}}{{UNIT}};',
                ]

            ]
        );
		
		
	$this->add_control(
            'wps_project_expand_icon_vertical',
            [
                'label' => esc_html__(' Icon Vertical', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_video_popup .icon-box' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );	
		
		
		

	
	    $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'project_icon_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_video_popup .icon-box',
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
.video-popup-box{
    position: relative;
    width: 100%;
    margin: 0 auto;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
}
.video-popup-box:before{
    position:absolute;
    content:"";
    left:0px;
    top:0px;
    right:0px;
    bottom:0px;
    background-color:rgba(77,39,63,0.20);
}
.video-popup-box .inner-column{
    position:static;
}
.video-popup-box .image img{
    display: block;
    width: 100%;
}
.video-popup-box .overlay-link{
    position: absolute;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.03);
    transition: all 0.6s ease;
    -moz-transition: all 0.6s ease;
    -webkit-transition: all 0.6s ease;
    -ms-transition: all 0.6s ease;
    -o-transition: all 0.6s ease;
}
.video-popup-box .overlay-link .icon-box{
    position: absolute;
    left: 50%;
    top: 50%;
    width: 80px;
    height: 80px;
    color: #ffffff;
    font-size: 22px;
    padding-left: 6px;
    line-height: 80px;
    text-align: center;
    border-radius: 50%;
    margin-bottom: 50px;
    display: inline-block;

}

.video-popup-box .overlay-link .icon-box:before,
.video-popup-box .overlay-link .icon-box:after{
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: transparent;
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  -webkit-animation-delay: .9s;
  animation-delay: .9s;
  content: "";
  position: absolute;
  -webkit-box-shadow: 0 0 0 0 #E770C1;
  box-shadow: 0 0 0 0 #E770C1;
  -webkit-animation: ripple 3s infinite;
  animation: ripple 3s infinite;
  -webkit-transition: all .4s ease;
  transition: all .4s ease;
}
.video-popup-box .overlay-link .icon-box:after,
.video-popup-box .overlay-link .icon-box:after{
  -webkit-animation-delay: .6s;
  animation-delay: .6s;
}

@-webkit-keyframes ripple {
    70% {
      -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0);
              box-shadow: 0 0 0 30px rgba(255, 255, 255, 0);
    }
    100% {
      -webkit-box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
              box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
    }
  }
  @keyframes ripple {
    70% {
      -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0);
              box-shadow: 0 0 0 30px rgba(255, 255, 255, 0);
    }
    100% {
      -webkit-box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
              box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
    }
}

.fancybox-container{
 z-index: 999999;
}

.fancybox-button {
    position: relative;
    top: 400px;
	background:red;
}

.video-popup-box .overlay-link .icon-box {
    transition: .5s ease;
}

        </style>';

        ?>
<?php
    echo '
    <script>
        jQuery(document).ready(function($)
        {
            // LightBox / Fancybox
            if ($(\'.lightbox-image\').length) {
                $(\'.lightbox-image\').fancybox({
                    openEffect: \'fade\',
                    closeEffect: \'fade\',
                    helpers: {
                        media: {}
                    }
                });
            }
        });
    </script>';
?>


<section class="wps_video_popup" >
    <div class="video-popup-box" style="background-image: url(<?php echo wp_get_attachment_url($settings['wps_lightbox_image']['id']); ?>)">
        <div class="inner-column">
            <div class="image">
                <img src="<?php echo wp_get_attachment_url($settings['wps_lightbox_image']['id']); ?>" alt="">
            </div>
            <a href="<?php echo esc_url($settings['wps_lightbox_youtube_link']['url']); ?>" class="overlay-link lightbox-image">
                <div class="icon-box">
                      <i class="<?php echo esc_attr($settings['wps_lightbox_icon']['value']); ?>"></i>
                </div>
            </a>
        </div>
    </div>
</section>
<!-- End of Main Area =================== -->
             
		

		<?php 
	}

}





// Register widget
Plugin::instance()->widgets_manager->register( new \wpsection_wps_lightbox_Widget() );


