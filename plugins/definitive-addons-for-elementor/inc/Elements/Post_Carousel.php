<?php
/**
 * Post Carousel
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Post Carousel
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Post_Carousel extends Widget_Base
{
    /**
     * Get element name.
     *
     * @access public
     *
     * @return string element name.
     */
    public function get_name()
    {
        return 'dafe_post_carousel';
    }
    
    /**
     * Get widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('DA: Post Carousel', 'definitive-addons-for-elementor');
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
        return 'eicon-posts-carousel';
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
        return [ 'post', 'carousel', 'slider','blog' ];
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
            'post_grid_label',
            [
            'label' =>__('Post Carousel', 'definitive-addons-for-elementor')
            ]
        );

        
        $this->add_control(
            'number_of_post',
            [
            'label' =>__('Number of Post', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' =>'9',
            ]
        );
        
        
        $this->add_control(
            'category_selection',
            [
                'label' =>__('Post Categories', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => Reuse::dafe_post_categories(),
            ]
        );
        
        
        
        $this->add_control(
            'post_orders',
            [
            'label' =>__('Post Order', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => [
                    
            'ASC' =>__('ASC', 'definitive-addons-for-elementor'),
                    
            'DESC' =>__('DESC', 'definitive-addons-for-elementor'),
            
            ],
            'default' => 'DESC',
                
            ]
        );
        
        
        $this->add_control(
            'post_order_by',
            [
            'label' =>__('Post Order By', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => [
                    
            'date' =>__('Date', 'definitive-addons-for-elementor'),
                    
            'title' =>__('Title', 'definitive-addons-for-elementor'),
                    
            ],
            'default' => 'date',
                
            ]
        );
        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_slider_nav_settings',
            [
                'label' => __('Slider Settings', 'definitive-addons-for-elementor'),
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
                'default' => 1,
                'description' => __('Default:3', 'definitive-addons-for-elementor'),
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
        
        $this->add_control(
            'show_hide_nav',
            [
                'label' => __('Show/Hide Navigation', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        // style
        $this->start_controls_section(
            'blog_section_style_overlay',
            [
                'label' =>__('Blog Post Overlay', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'overlay_width',
            [
                'label' => __('Overlay Width Full?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ovl_background',
                'selector' => '{{WRAPPER}} .da_feature_slide_border_abs',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_control(
            'ovl_hvr_background',
            [
                'label' => __('Background Hover', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .da_feature_slide_border_abs:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            'blog_section_style_title',
            [
                'label' =>__('Blog Post Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .da-slide-feature-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .da-slide-feature-title span' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-slide-feature-title span:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .da-slide-feature-title',
                
            ]
        );

          $this->end_controls_section();
       
        $this->start_controls_section(
            'blog_section_style_meta',
            [
                'label' =>__('Blog Post Meta', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
  
        $this->add_responsive_control(
            'meta_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 30
                ],
                'selectors' => [
                    '{{WRAPPER}} .da-featured-slider-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => __('Meta Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-featured-slider-meta i,{{WRAPPER}} .da-featured-slider-meta span,{{WRAPPER}} .da_slider-category .post-categories a,{{WRAPPER}} .da-featured-slider-meta span a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'meta_hover_color',
            [
                'label' => __('Meta Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-featured-slider-meta i:hover,{{WRAPPER}} .da-featured-slider-meta span:hover,{{WRAPPER}} .da-featured-slider-meta span a:hover,{{WRAPPER}} .da_slider-category .post-categories a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_font',
                'selector' => '{{WRAPPER}} .da-featured-slider-meta span,{{WRAPPER}} .da-featured-slider-meta span a,{{WRAPPER}} .da_slider-category .post-categories a',
                
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
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
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
            'label' =>__('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'nav_color',
            [
                'label' => __('Arrow Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'transparent',
                'selectors' => [
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                
            'name'     => 'dafe_nav_shadow',

            'selector' => '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow,{{WRAPPER}} .da-widget-post-slide .right.slick-arrow',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_border',
                'selector' => 
                
            '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow',
            ]
        );

        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_text_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow:hover, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow:hover, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow:hover' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );
        
        $this->add_control(
            'nav_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow:hover, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
    
        
        
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' =>__('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_nav_hvr_shadow',

            'selector' => '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow:hover,{{WRAPPER}} .da-widget-post-slide .right.slick-arrow:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'nav_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'nav_padding',
            [
                'label' => __('Arrow Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow, {{WRAPPER}} .da-widget-post-slide .right.slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .da-widget-post-slide .left.slick-arrow,{{WRAPPER}} .da-widget-post-slide .right.slick-arrow' => 'top: {{SIZE}}%!important;',
                ],
                
            ]
        );

        $this->end_controls_section();

    }

	 protected function render()
		{
        
        $settings = $this->get_settings_for_display(); 
        $post_order_by = $this->get_settings_for_display('post_order_by'); 
        $post_orders = $this->get_settings_for_display('post_orders');
    
        $number_of_post = $this->get_settings_for_display('number_of_post');
        $category_selection = $this->get_settings_for_display('category_selection');
    
        $slidesToShows = $this->get_settings_for_display('slidesToShow');
        $autoplay_speed = $this->get_settings_for_display('autoplay_speed');
        $autoplay = $this->get_settings_for_display('autoplay');
        $loop = $this->get_settings_for_display('loop');
        $show_hide_nav = $this->get_settings_for_display('show_hide_nav');
        $overlay_width = $this->get_settings_for_display('overlay_width');
    
        if ($show_hide_nav != 'yes') {
            $show_hide_nav = 'no';
        }
    
        $ovl_width = '';
        $feature_slider = '';
        if ($overlay_width == 'yes') {
            $ovl_width = 'full-width';
        }
    
        if ($slidesToShows > 1) {
            $ovl_width = 'full';
            $feature_slider = 'feature-carousel';
        }
        $this->add_render_attribute(
            'post-slide', [
            'class' => ['da-post-slide','da-widget-post-slide',$feature_slider],
            
            'data-autospeed' => $autoplay_speed,
            'data-autoplay' => $autoplay,
            'data-loop' => $loop,
            'data-showpage' => $slidesToShows,
            ] 
        );
        
       
        ?>
      <div <?php $this->print_render_attribute_string('post-slide'); ?>>

        <?php Da_Post::dafe_get_post_slider($ovl_width, $post_order_by, $post_orders, $number_of_post, $category_selection, $slidesToShows); ?>

        </div>      
        <?php 
        
        
        wp_reset_postdata();
      
    }
}