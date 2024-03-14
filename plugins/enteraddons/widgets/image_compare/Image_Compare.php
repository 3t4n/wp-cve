<?php
namespace Enteraddons\Widgets\Image_Compare;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor image compare widget.
 *
 * @since 1.0
 */
class Image_Compare extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-image-compare';
	}

	public function get_title() {
		return esc_html__( 'Image Compare', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-image-compare';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ----------------------------------------  image compare content ------------------------------
        $this->start_controls_section(
            'enteraddons_image_compare_content_settings',
            [
                'label' => esc_html__( 'Image Compare Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'orientation',
            [
                'label' => esc_html__( 'Orientation', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => 'Horizontal',
                    'vertical' => 'Vertical',
                ],
                'default' => 'horizontal'
            ]
        );
       $this->add_control(
            'image_compare_orginal_img',
            [
                'label' => esc_html__( 'Orginal', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'original_image',
            [
                'label' => esc_html__( 'Upload Original Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'img_compare_original_title',
            [
                'label'     => esc_html__( 'Title', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'   => esc_html__( 'Original', 'enteraddons' )
            ]
        );
        $this->add_control(
            'image_compare_modified_img',
            [
                'label' => esc_html__( 'Modified', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'modified_image',
            [
                'label' => esc_html__( 'Upload Modified Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'img_compare_modified_title',
            [
                'label'     => esc_html__( 'Title', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'   => esc_html__( 'Modified', 'enteraddons' )
            ]
        );
        $this->end_controls_section(); // End  content

        //------------------------------ Wrapper Style -------------------
        $this->start_controls_section(
            'enteraddons_cta_wrapper_style', [
                'label' => esc_html__( 'Wrapper Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cd-image-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cd-image-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .cd-image-container',
            ]
        );
        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cd-image-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .cd-image-container',
            ]
        );
        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Image_Compare_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
	public function get_script_depends() {
        return [ 'enteraddons-main', 'twentytwenty', 'event-move' ];
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'twentytwenty' ];
    }


}
