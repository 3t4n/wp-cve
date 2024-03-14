<?php 
namespace Shop_Ready\base\elementor\query\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;


class Product_Taxonomy 
{
	public function register() 
	{
	
		add_action('shop_ready_product_taxonomy_filter_tab' , array( $this, 'settings_section' ), 10 , 2 );
	}

	public function settings_section( $ele ,$widget) 
	{

           $ele->start_controls_section(
            'section_product_taxonomy_filter_tab',
                [
                    'label' => esc_html__('Proudct Filter', 'shopready-elementor-addon'),
                ]
            );

    
            $ele->add_control(
                    'post_cats',
                    [
                        'label'       => esc_html__('Select Categories', 'shopready-elementor-addon'),
                        'type'        => Controls_Manager::SELECT2,
                        'options'     => shop_ready_get_post_cat('product_cat'),
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
                    'options'     => shop_ready_get_post_tags('product_tag'),
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

            do_action( 'shop_ready_product_taxonomy_filter_tab_extra_control', $ele, $widget );
            
            $ele->end_controls_section();	
	}
}