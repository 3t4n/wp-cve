<?php 
namespace Shop_Ready\base\elementor\query\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class Data_Exclude_Controls
{
	public function register() 
	{
	    add_action('shop_ready_section_data_exclude_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele , $widget ) 
	{

        $ele->start_controls_section(
            'section_data_source_tab',
            [
                'label' => esc_html__('Data Exclude', 'shopready-elementor-addon'),
            ]
        );
            
        $ele->add_control(
            'post__not_in',
            [
                'label'       => esc_html__('Select excluded posts', 'shopready-elementor-addon'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => shop_ready_get_posts('product'),
                'label_block' => true,
                'multiple'    => true,
                'description' => esc_html__('Do not show selected posts', 'shopready-elementor-addon'),
            ]
        );
   
        $ele->add_control(
            'offset_enable',
                [
                'label'     => esc_html__('Post skip', 'shopready-elementor-addon'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'default'   => 'no',
                
                ]
        );
  
        $ele->add_control(
            'offset_item_num',
            [
                
            'label'     => esc_html__( 'Skip post count', 'shopready-elementor-addon' ),
            'type'      => Controls_Manager::NUMBER,
            'default'   => '1',
            'condition' => [ 'offset_enable' => 'yes' ]

            ]
        );
        
      
    $ele->end_controls_section();

	}
}