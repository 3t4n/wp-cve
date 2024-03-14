<?php 

namespace Element_Ready\Base\Controls;

use Elementor\Controls_Manager;
use Element_Ready\Base\BaseController;

class Taxonomy_Filter_Controls extends BaseController
{
	public function register() 
	{
		add_action('element_ready_section_taxonomy_filter_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele ,$widget) 
	{
           $ele->start_controls_section(
            'section_taxonomy_filter_tab',
                [
                    'label' => esc_html__('Taxonomy Filter', 'element-ready-lite'),
                ]
            );
            $ele->add_control(
                'standard_post_format',
                [
                    'label'       => esc_html__('Standard post format', 'element-ready-lite'),
                    'type'        => Controls_Manager::SWITCHER,
                    'label_on'    => esc_html__('Yes', 'element-ready-lite'),
                    'label_off'   => esc_html__('No', 'element-ready-lite'),
                    'default'     => 'yes',
                    'description' => esc_html__('Without any post format', 'element-ready-lite'),
                ]
            );
            $ele->add_control(
                'post_formats',
                [
                    'label'       => esc_html__('Select post format', 'element-ready-lite'),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => element_ready_current_theme_supported_post_format(),
                    'label_block' => true,
                    'multiple'    => true,
                    'condition'   => [ 'standard_post_format' => '' ],
                    'description' => esc_html__('Post filter by post format', 'element-ready-lite'),
                ]
            );
    
            $ele->add_control(
                    'post_cats',
                    [
                        'label'       => esc_html__('Select Categories', 'element-ready-lite'),
                        'type'        => Controls_Manager::SELECT2,
                        'options'     => element_ready_get_post_category(),
                        'label_block' => true,
                        'multiple'    => true,
                        'description' => esc_html__('Post filter by Category', 'element-ready-lite'),
                    ]
            );
    
            $ele->add_control(
                'post_tags',
                [
                    'label'       => esc_html__('Select tags', 'element-ready-lite'),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => element_ready_get_post_tags(),
                    'label_block' => true,
                    'multiple'    => true,
                    'description' => esc_html__('Post filter by tags', 'element-ready-lite'),
                ]
            );
    
            $ele->add_control(
                'post_author',
                [
                'label'       => esc_html__('Select author', 'element-ready-lite'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => element_ready_get_post_author(),
                'label_block' => true,
                'multiple'    => true,
                'description' => esc_html__('Post filter by author ', 'element-ready-lite'),
                ]
            );

            do_action( 'element_ready_section_taxonomy_filter_tab_extra_control', $ele, $widget );
            $ele->end_controls_section();	
	}
}