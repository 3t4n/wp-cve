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


if (class_exists('woocommerce')) {

    class wpsection_wps_productajax_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'wpsection_wps_productajax';
        }

        public function get_title() {
            return __('Product Ajax Search', 'wpsection');
        }

        public function get_icon() {
            return 'eicon-site-search';
        }

        public function get_keywords() {
            return ['wpsection', 'wps_productajax'];
        }

        public function get_categories() {
             return [  'wpsection_shop' ];
        }

        protected function _register_controls() {
            $this->start_controls_section(
                'product_filter_settings',
                [
                    'label' => esc_html__('Product Ajax Search Settings', 'wpsection'),
                ]
            );

            $this->add_control(
                'max_products',
                [
                    'label' => esc_html__('Max Products', 'wpsection'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 10, // Set your default value
                    'description' => esc_html__('Enter the maximum number of products to display.', 'wpsection'),
                ]
            );

            $this->end_controls_section();
        }

        /**
         * Render button widget output on the frontend.
         * Written in PHP and used to generate the final HTML.
         *
         * @since  1.0.0
         * @access
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            $allowed_tags = wp_kses_allowed_html('post');
            ?>

            <div class="wps_product_search">

                <input type="text" id="search-input" placeholder="Light,Door,etc.."> <i class=" eicon-search"> </i>

                <div id="search-results" class="wps_search_product">
                    <div class="row">
                        
                    </div>
                </div>
            </div>

            <?php
        }


    }

    // Register widget
    Plugin::instance()->widgets_manager->register(new \wpsection_wps_productajax_Widget());
}