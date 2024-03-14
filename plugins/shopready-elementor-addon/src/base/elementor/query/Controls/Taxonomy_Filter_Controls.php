<?php 
namespace Shop_Ready\base\elementor\query\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;


class Taxonomy_Filter_Controls 
{
	public function register() 
	{
	
		add_action('shop_ready_section_taxonomy_filter_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele ,$widget) 
	{

           $ele->start_controls_section(
            'section_taxonomy_filter_tab',
                [
                    'label' => esc_html__('Taxonomy Filter', 'shopready-elementor-addon'),
                ]
            );

            $ele->add_control(
                'standard_post_format',
                [
                    'label'       => esc_html__('Standard post format', 'shopready-elementor-addon'),
                    'type'        => Controls_Manager::SWITCHER,
                    'label_on'    => esc_html__('Yes', 'shopready-elementor-addon'),
                    'label_off'   => esc_html__('No', 'shopready-elementor-addon'),
                    'default'     => 'yes',
                    'description' => esc_html__('Without any post format', 'shopready-elementor-addon'),
                ]
            );

            $ele->add_control(
                'post_formats',
                [
                    'label'       => esc_html__('Select post format', 'shopready-elementor-addon'),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => mangocube_current_theme_supported_post_format(),
                    'label_block' => true,
                    'multiple'    => true,
                    'condition'   => [ 'standard_post_format' => '' ],
                    'description' => esc_html__('Post filter by post format', 'shopready-elementor-addon'),
                ]
            );
    
            $ele->add_control(
                    'post_cats',
                    [
                        'label'       => esc_html__('Select Categories', 'shopready-elementor-addon'),
                        'type'        => Controls_Manager::SELECT2,
                        'options'     => shop_ready_get_post_tags('category'),
                        'label_block' => true,
                        'multiple'    => true,
                        'description' => esc_html__('Post filter by Category', 'shopready-elementor-addon'),
                    ]
            );
    
            $ele->add_control(
                'post_tags',
                [
                    'label'       => esc_html__('Select tags', 'shopready-elementor-addon'),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => shop_ready_get_post_tags('post_tag'),
                    'label_block' => true,
                    'multiple'    => true,
                    'description' => esc_html__('Post filter by tags', 'shopready-elementor-addon'),
                ]
            );
    
            $ele->add_control(
                'post_author',
                [
                'label'       => esc_html__('Select author', 'shopready-elementor-addon'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => shop_ready_get_post_author(),
                'label_block' => true,
                'multiple'    => true,
                'description' => esc_html__('Post filter by author ', 'shopready-elementor-addon'),
                ]
            );

            do_action( 'shop_ready_section_taxonomy_filter_tab_extra_control', $ele, $widget );
            $ele->end_controls_section();	
	}
}