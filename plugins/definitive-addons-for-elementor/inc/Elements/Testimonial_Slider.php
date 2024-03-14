<?php
/**
 * Testimonial Slider
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Testimonial Slider
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Testimonial_Slider extends Widget_Base
{
    
    /**
     * Get widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('DA: Testimonial Slider', 'definitive-addons-for-elementor');
    }

    /**
     * Get element name.
     *
     * @access public
     *
     * @return string element name.
     */ 
    public function get_name()
    {
        return 'dafe_testimonial_slider';
    }

    /**
     * Get element icon.
     *
     * @access public
     *
     * @return string element icon.
     */
    public function get_icon()
    {
        return 'eicon-testimonial-carousel';
    }
        /**
         * Get element keywords.
         *
         * @access public
         *
         * @return string element keywords.
         */
    public function get_keywords()
    {
        return [ 'testimonial', 'image','review','slide' ];
    }
    
    /**
     * Get element categories.
     *
     * @access public
     *
     * @return string element categories.
     */
    public function get_categories()
    {
        return [ 'definitive-addons' ];
    }
    
    /**
     * Registering widget content controls
     *
     * @return void.
     */
    protected function register_controls()
    {
        
        $this->start_controls_section(
            'section_testimonial_slider',
            [
                'label' => __('Testimonial Slider', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

         $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => __('Image', 'definitive-addons-for-elementor'),
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $repeater->add_control(
            'link',
            [
            'label' =>__('Link', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::URL,
                
            'placeholder' =>__('https://softfirm.com', 'definitive-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'name',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Reviewer Name', 'definitive-addons-for-elementor'),
                'default' => __('John Doe', 'definitive-addons-for-elementor')
            ]
        );
        
        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Title', 'definitive-addons-for-elementor'),
                'default' => __('Developer', 'definitive-addons-for-elementor')
            ]
        );
        
        $repeater->add_control(
            'show_hide_organization',
            [
                'label' => __('Show/Hide Organization', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
                
            ]
        );
        
        $repeater->add_control(
            'organization',
            [
                'type' => Controls_Manager::TEXT,
               
                'label' => __('Organization', 'definitive-addons-for-elementor'),
                'default' => __('Softfirm', 'definitive-addons-for-elementor'),
            'condition' => [
                        'show_hide_organization' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'reviewer_text',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'label' => __('Reviewer Text', 'definitive-addons-for-elementor'),
                'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.', 'definitive-addons-for-elementor'),
            ]
        );
        
        
        
        $this->add_control(
            'dafe_slick_slides',
            [
            'label'       =>__('Testimonial Items', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'seperator'   => 'before',
            'default' => [
                    
            [ 'title1' => 'Testimonial-1' ],
                    
            [ 'title1' => 'Testimonial-2' ],
                    
            [ 'title1' => 'Testimonial-3' ]
                    
                    
            ],
                
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title1 }}}',
            
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );
        
        $this->add_control(
            'show_hide_quote',
            [
                'label' => __('Show/Hide Quote', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
                
            ]
        );
        
        $this->add_control(
            'testimonial_alignment',
            [
            'label' =>__('Testimonial Layout', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'options' => [
            'left' => 'Image Left - Content Right',
            'right' => 'Image Right - Content Left',
            'top' => 'Image Top - Content Down',
            'bottom' => 'Image Down - Content Top',
                    
            ],
            'default' => 'top',
                
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_slider_nav_settings',
            [
                'label' => __('Slider Navigation Settings', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'slidesToShow',
            [
                'label' => __('No of Slide per Page', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
            'step' => 1,
                'default' => 2,
                'description' => __('Default:1', 'definitive-addons-for-elementor'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __('Animation Speed', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 150,
                'max' => 12000,
            'step' => 10,
                'default' => 300,
                'description' => __('Value in milliseconds. Default:300', 'definitive-addons-for-elementor'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Slider Autoplay?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
            'default' => 3000,
                'min' => 200,
                'step' => 100,
                'max' => 12000,
            'condition' => [
                    'autoplay' => 'yes'
                ],
                'description' => __('Value in milliseconds. Default:3000', 'definitive-addons-for-elementor'),
               
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Infinite Loop?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        // image style
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Reviewer Image', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Image Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'unit' => 'px',
            'size' => 100,
                ],
                'range'      => [
                        
                'px' => [
                'min' => 10,
                'max' => 200,
                ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_right_spacing',
            [
                'label' => __('Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image' => 'margin-right: {{SIZE}}{{UNIT}}!important;',
                ],
                'condition' => [
                        'testimonial_alignment' => 'left',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_left_spacing',
            [
                'label' => __('Left Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image' => 'margin-left: {{SIZE}}{{UNIT}}!important;',
                ],
                'condition' => [
                        'testimonial_alignment' => 'right',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_bottom_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
                ],
                'condition' => [
                        'testimonial_alignment' => ['top','bottom']
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .dafe-testimonial-image img',
            ]
        );

        $this->add_responsive_control(
            'entry_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Image Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_image_shadow',

            'selector' => '{{WRAPPER}} .dafe-testimonial-image img',
            ]
        );


        $this->end_controls_section();

        // Name style
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Reviewer Name', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'reviewer_name_spacing',
            [
                'label' => __('Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 10
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-name' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'reviewer_name_color',
            [
                'label' => __('Name Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'reviewer_name_hvr_color',
            [
                'label' => __('Name Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-name:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviewer_font',
                'selector' => '{{WRAPPER}} .dafe-author-name',
                
            ]
        );

        $this->add_responsive_control(
            'name_bottom_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Title style
        $this->start_controls_section(
            'section_style_reviewer_title',
            [
                'label' => __('Reviewer Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

  
        $this->add_control(
            'reviewer_title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviewer_title_font',
                'selector' => '{{WRAPPER}} .dafe-author-title',
                
            ]
        );
        
        $this->end_controls_section();
        // organization style

        $this->start_controls_section(
            'section_style_organization',
            [
                'label' => __('Reviewer Organizaiton', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
                        'show_hide_organization' => 'yes',
                ],
            ]
        );
        


        $this->add_control(
            'organization_color',
            [
                'label' => __('Organization Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-organization' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'organization_font',
                'selector' => '{{WRAPPER}} .dafe-author-organization',
                
            ]
        );

        $this->end_controls_section();
        

        $this->start_controls_section(
            'section_style_text',
            [
                'label' => __('Reviewer Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'text_left_spacing',
            [
                'label' => __('Space Between Icon and Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .speech i' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'show_hide_quote' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('Text Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-description,{{WRAPPER}} .speech' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_font',
                'selector' => '{{WRAPPER}} .dafe-testimonial-description,{{WRAPPER}} .speech',
                
            ]
        );

        $this->end_controls_section();
        
        
        
        // content style
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Testimonial Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
            'default' => [
            'top' => '10',
            'right' => '10',
            'bottom' => '10',
            'left' => '10',
    
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_container_colors',
            [
            'label' => __('Container Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_container_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'selector' => '{{WRAPPER}} .dafe-testimonial-container',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_container_shadow',

            'selector' => '{{WRAPPER}} .dafe-testimonial-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .dafe-testimonial-container',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_container_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_hvr_background',
                'selector' => '{{WRAPPER}} .dafe-testimonial-container:hover',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_container_hvr_shadow',

            'selector' => '{{WRAPPER}} .dafe-testimonial-container:hover',
            ]
        );
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-container:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'nav_section_style_start',
            [
                'label' => __('Navigation', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'nav_size',
            [
                'label' => __('Arrow Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'unit' => 'px',
            'size' => 28,
                ],
                'range'      => [
                        
                'px' => [
                'min' => 10,
                'max' => 100,
                ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow, {{WRAPPER}} .nl-testimonial-entry .right.slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'nav_padding',
            [
                'label' =>__('Arrow Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
            'default' => [
            'top' => '10',
            'right' => '10',
            'bottom' => '10',
            'left' => '10',
    
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow,{{WRAPPER}} .nl-testimonial-entry .right.slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_arrow_colors',
            [
            'label' => __('Arrow Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_arrow_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'nav_color',
            [
                'label' => __('Arrow Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow, {{WRAPPER}} .nl-testimonial-entry .right.slick-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg_color',
            [
                'label' => __('Arrow Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#9FBDCA52',
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow, {{WRAPPER}} .nl-testimonial-entry .right.slick-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' =>__('Arrow Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_nav_shadow',

            'selector' => '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow,{{WRAPPER}} .nl-testimonial-entry .right.slick-arrow',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_border',
                'selector' => '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow,{{WRAPPER}} .nl-testimonial-entry .right.slick-arrow',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_arrow_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'nav_hover_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow:hover, {{WRAPPER}} .nl-testimonial-entry .right.slick-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow:hover, {{WRAPPER}} .nl-testimonial-entry .right.slick-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' =>__('Arrow Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_nav_hvr_shadow',

            'selector' => '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow:hover,{{WRAPPER}} .nl-testimonial-entry .right.slick-arrow:hover',
            ]
        );
        
        $this->add_control(
            'arrow_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow:hover,{{WRAPPER}} .nl-testimonial-entry .right.slick-arrow:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        

        $this->add_responsive_control(
            'nav_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow,{{WRAPPER}} .nl-testimonial-entry .right.slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_top_spacing',
            [
                'label' => __('Top Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
            'default' => [
            'size' => 50
                ],
                'selectors' => [
                    '{{WRAPPER}} .nl-testimonial-entry .left.slick-arrow,{{WRAPPER}} .nl-testimonial-entry .right.slick-arrow' => 'top: {{SIZE}}%!important;',
                ],
                
            ]
        );
        
        

        $this->end_controls_section();
        

        
    }


	protected function render() {
		
        $settings = $this->get_settings_for_display();
		$align = $this->get_settings_for_display('testimonial_alignment');
		$title_tag = $this->get_settings_for_display('title_tag');
		
		$animation_speed = $this->get_settings_for_display('animation_speed');
		$autoplay_speed = $this->get_settings_for_display('autoplay_speed');
		$autoplay = $this->get_settings_for_display('autoplay');
		$loop = $this->get_settings_for_display('loop');
		$slidesToShow = $this->get_settings_for_display('slidesToShow');
	
	
	
	$id = uniqid();
	$this->add_render_attribute( 'nl-testimonial-entry', [
			'class' => 'nl-testimonial-entry',
			'data-animatespeed' => $animation_speed,
			'data-autospeed' => $autoplay_speed,
			'data-autoplay' => $autoplay,
			'data-loop' => $loop,
			'data-showpage' => $slidesToShow,
		] );
	
	
		?>
		
	<div class="widget-testimonial-slide">

			<div id="<?php echo esc_attr($id); ?>" <?php $this->print_render_attribute_string( 'nl-testimonial-entry' ); ?>>
			
			<?php foreach ( $settings['dafe_slick_slides'] as $key => $slide ) {
				
				if (  $slide['link'] ) {
					$this->add_link_attributes( 'link'.$key, $slide['link'] );
				}
                
                ?>
				<div class="dafe-testimonial-container dafe-testimonial-image-align-<?php echo esc_attr( $align ); ?> dafe-vertical-align-middle">

					<div class="dafe-testimonial-inner-container">
						<?php if (( $align  === 'left') || ($align  === 'right') || ($align  === 'top')) : ?>	
							<figure class="dafe-testimonial-image">
								<?php 
								$testimonial_image = Group_Control_Image_Size::get_attachment_image_html( $slide, 'thumbnail', 'image' );
								if (  $slide['link'] ) :
									$testimonial_image = '<a ' . $this->get_render_attribute_string( 'link') . '>' . $testimonial_image . '</a>';
								endif;
								echo wp_kses_post( $testimonial_image ); ?>
			
							</figure>
						<?php endif; ?>
					<div class="dafe-testimonial-content">
						<div class="dafe-testimonial-title">
							<?php if ( $slide['name'] ) :  ?>
								<span class="dafe-author-name"><?php echo esc_html( $slide['name'] ); ?></span>
							<?php endif; ?>
							<?php if ( $slide['title'] ) :  ?>
								<span class="dafe-author-title"><?php echo esc_html( $slide['title'] ); ?></span>
							<?php endif; ?>
							<?php if ( $slide['organization'] ) :  ?>
								<h5 class="dafe-author-organization"><?php echo esc_html( $slide['organization'] ); ?></h5>
							<?php endif; ?>
						</div>
						<?php if ($slide['reviewer_text'] ){ ?>
						<div class="dafe-testimonial-description">
							<p>
								<?php if ($settings['show_hide_quote'] == 'yes'){ ?>
									<blockquote class="speech"><i class="fa fa-quote-left"></i><?php echo wp_kses_post( $slide['reviewer_text'] ); ?>
									</blockquote>
								<?php
								}  else { ?>
									<blockquote class="speech">
                                    <?php echo wp_kses_post( $slide['reviewer_text'] ); ?> 
                                </blockquote>
                                <?php }
							?>
							</p>
						</div>
						<?php } ?>
					</div>
		
						<?php if ( $align  === 'bottom') : ?>
							<figure class="dafe-testimonial-image">
							<?php 
								$testimonial_image = Group_Control_Image_Size::get_attachment_image_html( $slide, 'thumbnail', 'image' );
								if (  $slide['link']['url'] ) :
									$testimonial_image = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $testimonial_image . '</a>';
								endif;
								echo wp_kses_post( $testimonial_image );
							?>
							</figure>
						<?php endif; ?>
					</div>

				</div>

				
				<?php }  ?>					 
            </div> <!--  end single post -->
		
	</div> 

        <?php

    }
	
}
