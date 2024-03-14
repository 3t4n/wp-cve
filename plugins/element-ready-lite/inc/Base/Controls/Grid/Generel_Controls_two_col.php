<?php
 
namespace Element_Ready\Base\Controls\Grid;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Custom_Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Element_Ready\Base\BaseController;

class Generel_Controls_two_col extends BaseController
{
	public function register() 
	{
	
		add_action('er_section_general_grid_tab_two_col' , array( $this, 'settings_section' ), 10, 2 );
	
	}
    public function not_allowed_control($control,$widget){
       
        $widget_list = [
           'er-post-slider' =>
                 ['show_date','show_cat','show_readmore']
       ];

        try{
            if (isset( $widget_list[ $widget ] )) {

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
                        'label'         => esc_html__( 'Post count', 'element-ready-lite' ),
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
               
              
                $ele->add_control(
                    'show_content',
                    [
                        'label'     => esc_html__('Show content', 'element-ready-lite'),
                        'type'      => Controls_Manager::SWITCHER,
                        'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                        'label_off' => esc_html__('No', 'element-ready-lite'),
                        'default'   => 'yes',
                    ]
                );

                $ele->add_control(
                    'post_content_crop',
                        [
                            'label'         => esc_html__( 'Post content crop', 'element-ready-lite' ),
                            'type'          => Controls_Manager::NUMBER,
                            'default'       => '18',
                        ]
                );

                $ele->add_control(
                    'show_right_content',
                    [
                        'label'     => esc_html__('right col content', 'element-ready-lite'),
                        'type'      => Controls_Manager::SWITCHER,
                        'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                        'label_off' => esc_html__('No', 'element-ready-lite'),
                        'default'   => 'yes',
                        
                    ]
                );

                $ele->add_control(
                    'post_right_content_crop',
                        [
                            'label'         => esc_html__( 'Right Col content crop', 'element-ready-lite' ),
                            'type'          => Controls_Manager::NUMBER,
                            'default'       => '18',
                        ]
                );
    
            
               
                    
               
                    $ele->add_control(
                        'show_cat',
                        [
                            'label'     => esc_html__('Show Category', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'yes',
                        ]
                    );

                    
                    $ele->add_control(
                        'show_date',
                        [
                            'label'     => esc_html__('Show Date', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'yes',
                        ]
                    );

                    $ele->add_control(
                        'show_readmore',
                        [
                            'label'     => esc_html__('Show Readmore', 'element-ready-lite'),
                            'type'      => Controls_Manager::SWITCHER,
                            'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                            'label_off' => esc_html__('No', 'element-ready-lite'),
                            'default'   => 'yes',
                        ]
                    );

                    $ele->add_control(
                        'readmore_text',
                        [
                            
                        'label'         => esc_html__( 'Readmore title', 'element-ready-lite' ),
                        'type'          => Controls_Manager::TEXT,
                        'default'      => esc_html__( 'Read more', 'element-ready-lite' ),  
                        'condition' => [ 'show_readmore' => 'yes' ]
                        ]
                     );

                     
                    $ele->add_control(
                        'readmore_icon',
                        [
                            'label' => esc_html__( 'Icon', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'default' => [
                                'value' => 'fas fa-star',
                                'library' => 'solid',
                            ],
                            'exclude_inline_options'=>[''],
                        ]
                    );
               
            $ele->end_controls_section();	
           
    }
 
}