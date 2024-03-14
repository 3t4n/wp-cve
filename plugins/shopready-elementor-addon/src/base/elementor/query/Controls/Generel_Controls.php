<?php
namespace Shop_Ready\base\elementor\query\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Generel_Controls {
    
    public function register() {

        add_action( 'shop_ready_section_general_tab', [ $this, 'settings_section' ], 10, 2 );
        add_action( 'shop_ready_section_product_minimum_general_tab', [ $this, 'product_minimum_general_tab' ], 10, 2 );
    }

    public function settings_section( $ele, $widget ) {

        

        $ele->start_controls_section(
            'section_general_tab',
            [
                'label' => esc_html__( 'General', 'shopready-elementor-addon' ),
            ]
        );

        $ele->add_control(
            'post_count',
            [
                'label'   => esc_html__( 'Count', 'shopready-elementor-addon' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '6',
            ]
        );

        $ele->add_control(
            'post_title_crop',
            [
                'label'   => esc_html__( 'Title crop', 'shopready-elementor-addon' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '10',
            ]
        );

        $ele->add_control(
            'show_content',
            [
                'label'     => esc_html__( 'Show content', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
                'default'   => 'yes',
            ]
        );

        $ele->add_control(
            'post_content_crop',
            [
                'label'   => esc_html__( 'Content crop', 'shopready-elementor-addon' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '18',
            ]
        );

        $ele->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => 'product_image_size',
                'exclude'     => [],
                'default'     => 'medium',
                'label_block' => true,
            ]
        );

        $ele->add_control(
            'show_date',
            [
                'label'     => esc_html__( 'Show Date', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
                'default'   => 'yes',
            ]
        );

        $ele->add_control(
            'show_cat',
            [
                'label'     => esc_html__( 'Show Category', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
                'default'   => 'yes',
            ]
        );

        $ele->add_control(
            'show_author',
            [
                'label'     => esc_html__( 'Show Author', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
                'default'   => 'yes',
            ]
        );

        $ele->add_control(
            'show_author_img',
            [
                'label'     => esc_html__( 'Show Author image', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
                'default'   => 'no',
            ]
        );

        $ele->add_control(
            'show_readmore',
            [
                'label'     => esc_html__( 'Show Readmore', 'shopready-elementor-addon' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
                'default'   => 'yes',
            ]
        );

        $ele->end_controls_section();
    }

    public function product_minimum_general_tab( $ele, $widget ) {
       
        $ele->start_controls_section(
            'section_general_tab',
            [
                'label' => esc_html__( 'General', 'shopready-elementor-addon' ),
            ]
        );

        $ele->add_control(
            'post_count',
            [
                'label'   => esc_html__( 'Count', 'shopready-elementor-addon' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '6',
            ]
        );

        $ele->add_control(
            'post_title_crop',
            [
                'label'   => esc_html__( 'Title crop', 'shopready-elementor-addon' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '10',
            ]
        );

        if(!in_array( $widget,['general_product_slider'] )){   

            $ele->add_control(
                'show_content',
                [
                    'label'     => esc_html__( 'Show content', 'shopready-elementor-addon' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_on'  => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                    'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
                    'default'   => 'yes',
                ]
            );

            $ele->add_control(
                'post_content_crop',
                [
                    'label'   => esc_html__( 'Content crop', 'shopready-elementor-addon' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => '18',
                ]
            );
        } // end in_array

        $ele->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => 'product_image_size',
                'exclude'     => [],
                'default'     => 'medium',
                'label_block' => true,
            ]
        );

        $ele->end_controls_section();
    }

}