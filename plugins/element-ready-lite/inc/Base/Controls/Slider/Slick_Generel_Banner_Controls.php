<?php
 
namespace Element_Ready\Base\Controls\Slider;

use Elementor\Controls_Manager;
use Element_Ready\Base\BaseController;

class Slick_Generel_Banner_Controls extends BaseController
{
	public function register() 
	{
	    add_action('element_ready_section_general_slick_banner_slider_tab' , array( $this, 'settings_section' ), 10, 2 );
		add_action('element_ready_section_general_slick_banner_slider_tab_extra_control' , array( $this, 'settings_section_extra' ),10, 2 );
	}
    public function not_allowed_control($control,$widget){
       
        $widget_list = [
           'element_ready-post-slider' =>
                 ['']
       ];
        try{
            if(isset($widget_list[$widget])){

                $the_widget = $widget_list[$widget];
                if( in_array($control,$the_widget) ){
                  return false;
                }else{
                    return true;
                }
            }
           
            return true;
        }catch (\Exception $e) {
            return true;
        }
        return true;
    }
	public function settings_section( $ele,$widget ) 
	{
            
           $ele->start_controls_section(
            'section_general_tab',
                [
                    'label' => esc_html__('General', 'element-ready-lite'),
                ]
            );
                $ele->add_control(
                'post_count',
                    [
                        'label'         => esc_html__( 'Load Posts', 'element-ready-lite' ),
                        'type'          => Controls_Manager::NUMBER,
                        'default'       => '8',
                    ]
                );
               
                $ele->add_control(
                'post_title_crop',
                    [
                        'label'         => esc_html__( 'Post title crop', 'element-ready-lite' ),
                        'type'          => Controls_Manager::NUMBER,
                        'default'       => '8',
                    ]
                );
                // uncommon  
                if($this->not_allowed_control('show_content',$widget)){
                    $ele->add_control(
                        'show_content',
                        [
                            'label'     => esc_html__('Show content', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'yes',
                            'condition' => [ 'block_style' => 'style1' ]
                        ]
                    );
    
                }
                if($this->not_allowed_control('post_content_crop',$widget)){
                    $ele->add_control(
                        'post_content_crop',
                            [
                                'label'         => esc_html__( 'Post content crop', 'element-ready-lite' ),
                                'type'          => Controls_Manager::NUMBER,
                                'default'       => '18',
                                'condition' => [ 'block_style' => 'style1' ]
                            ]
                    );
                } 
                if($this->not_allowed_control('show_date',$widget)){
                    $ele->add_control(
                        'show_date',
                        [
                            'label'     => esc_html__('Show Date', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'yes',
                            'condition' => [ 'block_style' => ['style1'] ]
                        ]
                    );
                }

                if($this->not_allowed_control('show_cat',$widget)){
                    $ele->add_control(
                        'show_cat',
                        [
                            'label'     => esc_html__('Show Category', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'yes',
                            'condition' => [ 'block_style' => ['style1'] ]
                        ]
                    );
                }
                if($this->not_allowed_control('show_author',$widget)){
                    $ele->add_control(
                        'show_author',
                        [
                            'label'     => esc_html__('Show Author', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'yes',
                            'condition' => [ 'block_style' => ['style2','style3','style4','style5','style6'] ]
                        ]
                    );
                }
                if($this->not_allowed_control('show_author_img',$widget)){
                    $ele->add_control(
                        'show_author_img',
                        [
                            'label'     => esc_html__('Show Author image', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'no',
                            'condition' => [ 'block_style' => ['style2','style3','style4','style5'] ]
                        ]
                    );
               }
                
                  
                
                do_action( 'element_ready_section_general_slick_bannner_slider_tab_extra_control', $ele, $widget );
            $ele->end_controls_section();	
    }
    
    public function settings_section_extra($ele, $widget ){
        
        if($widget == 'video-post-slider'){

            $ele->add_responsive_control(
                'thumbnail_height',
                [
                    'label' =>esc_html__( "Thumbnail Height", 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'desktop_default' => [
                        'size' => 300,
                        'unit' => 'px',
                    ],
                    'tablet_default' => [
                        'size' => 250,
                        'unit' => 'px',
                    ],
                    'mobile_default' => [
                        'size' => 250,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .video-item.qoverlay-style' => 'min-height: {{SIZE}}{{UNIT}};',
                    ],
                
                ]
            );
        }
    }
}