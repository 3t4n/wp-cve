<?php

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Repeater;



class wpsection_wps_search_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'wpsection_wps_search';
	}

	public function get_title() {
		return __( 'Search', 'wpsection' );
	}

	public function get_icon() {
		 return ' eicon-search';
	}

	public function get_keywords() {
		return [ 'wpsection', 'search' ];
	}

	  public function get_categories() {
         return ['wpsection_category'];
    }


	

	protected function register_controls() {
		$this->start_controls_section(
			'search_settings',
			[
				'label' => __( 'Search General', 'wpsection' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
			'search_title', [
				'label'       => esc_html__( 'Search Title', 'element-path' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Search',
				'dynamic'     => [
					'active' => true,
				],
			]
		);	
		
  $this->add_control(
    'search_block_plus_icon',
    [
        'label' => esc_html__(' Icon', 'rashid'),
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-search', // Set your default icon class here
            'library' => 'solid', // Set the icon library (solid, regular, or brands)
        ],
    ]
);
		
		
        $this->end_controls_section();	

//Search Style 		
//==================== Star of Setting area==============================================
	
$this->start_controls_section(
            'search_style_settings',
            array(
                'label' => __( 'Search Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
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
                    '{{WRAPPER}} .wps_search_box' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wps_search_box' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );

		
    $this->add_control(
            'wps_thumbnail_bg',
            [
                'label' => esc_html__('Background Color', 'wpsection'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_search_box' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#000', 
            ]
        );	
		
	    $this->add_control(
            'wps_thumbnail_hover_bg',
            [
                'label' => esc_html__('Background Hover Color', 'wpsection'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_search_box:hover' => 'background: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .wps_search_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
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
                    '{{WRAPPER}} .wps_search_box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'thumbnail_border',
                'selector' => '{{WRAPPER}} .wps_search_box',
            )
        );
                
            $this->add_control(
            'thumbnail_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_search_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );  
		
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'thumbnail_box_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_search_box',
			]
		);
        $this->end_controls_section();
        
//End of BG 
	
		

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
                    '{{WRAPPER}}  .wps_search_box' => 'display: {{VALUE}} !important',
                ),
            )
        );
	
		$this->add_control(
			'icon_color_alingment',
			array(
				'label' => esc_html__('Alignment', 'wpsection'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'wpsection'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'wpsection'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'ecwpsectionolab'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(

					'{{WRAPPER}}  .wps_search_box' => 'text-align: {{VALUE}} !important',
				),
			)
		);	
		
		
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'wpsection'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_search_box i' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'wpsection'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_search_box i:hover' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
  
        $this->add_control(
            'wps_project_icon_bg_hover',
            [
                'label' => esc_html__('Background Hover Color', 'wpsection'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_search_box:hover i' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#000', 
            ]
        );
		
		

		
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_projce_icon_typo',
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}} .wps_search_box i',
            )
        );
		
		
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_project_icon_border',
                'selector' => '{{WRAPPER}} .wps_search_box i',
            )
        );


        $this->add_control(
            'wps_project_icon_radious',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .wps_search_box i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
		
    

        $this->end_controls_section();		
				
		
//End of Style
	}

	protected function render() {
		global $plugin_root;
		$settings = $this->get_settings_for_display();



    echo '
    <script>
        jQuery(document).ready(function($)
        {
   
	//Search Popup
	if($("#mr_search_popup_block").length){
		
		//Show Popup
		$(".mr_search_toggler").on("click", function() {
			$("#mr_search_popup_block").addClass("popup-visible");
		});
		$(document).keydown(function(e){
	        if(e.keyCode === 27) {
	            $("#mr_search_popup_block").removeClass("popup-visible");
	        }
	    });
		//Hide Popup
		$(".mr_close_search, .mr_search-popup .mr_overlay-layer").on("click", function() {
			$("#mr_search_popup_block").removeClass("popup-visible");
		});
	}
    	   
        });
    </script>';
?>

		
	




  <section class="wps_search_sec">
    <div class="mr_nav-right <?php echo esc_attr($settings['sec_class']);?>">
        <div class="mr_search-box-outer mr_search_toggler">
			
		<div class="wps_search_box">
            <i class="<?php echo esc_attr($settings['search_block_plus_icon']['value']); ?>"></i>
        </div>
		
		</div>
    </div>
    <div id="mr_search_popup_block" class="mr_search-popup">
            <div class="mr_popup-inner">
				  <div class="mr_upper-box clearfix">
                    <div class="mr_close_search pull-right">								
<?php echo (class_exists('Elementor\Plugin')) ? '<i class="eicon-close-circle"></i>' : '<i class="dashicons dashicons-dismiss"></i>'; ?>
					</div>
                </div>
                <div class="mr_overlay-layer"></div>
                <div class="auto-container">
                    <div class="mr_search-form">
                       <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">	
                            <div class="mr_form-group">
                               <fieldset>    
									<input type="search" name="s" class="form-control" placeholder="<?php echo esc_attr( $settings['search_title']);?>" required="">
									<button  class="search_button"  type="submit"><i class="<?php echo esc_attr($settings['search_block_plus_icon']['value']); ?>"></i></button>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
 </section>


        <?php

	}
}

// Register widget
Plugin::instance()->widgets_manager->register( new \wpsection_wps_search_Widget() );