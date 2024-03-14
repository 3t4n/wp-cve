<?php
/**
 * Filterable Post
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
 * Filterable Post
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Filterable_Post extends Widget_Base
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
        return 'dafe_filterable_post';
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
        return __('DA: Filterable Portfolio/Post', 'definitive-addons-for-elementor');
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
        return 'eicon-post';
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
        return ['post', 'filter', 'portfolio', 'blog', 'project'];
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
            'dafe_post_grid_label',
            [
            'label' =>__('Filterable Portfolio/Post', 'definitive-addons-for-elementor')
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
            'no_of_column',
            [
            'label' =>__('Column Number', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            
            'options' => [
                    
            '1' =>__('One', 'definitive-addons-for-elementor'),
            '2' =>__('Two', 'definitive-addons-for-elementor'),
            '3' =>__('Three', 'definitive-addons-for-elementor'),
            '4' =>__('Four', 'definitive-addons-for-elementor'),
                    
                    
            ],
            'default' => '3',
                
            ]
        );
        
        $this->add_control(
            'column_gap',
            [
            'label' =>__('Column Gap', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => [
                    
            '0' =>__('0px', 'definitive-addons-for-elementor'),
            '10' =>__('10px', 'definitive-addons-for-elementor'),
            '15' =>__('15px', 'definitive-addons-for-elementor'),
            '25' =>__('25px', 'definitive-addons-for-elementor'),
            '35' =>__('35px', 'definitive-addons-for-elementor'),
                    
            
            ],
            'default' => '25',
                
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
            'category_exclude',
            [
                'label' =>__('Exclude Categories', 'definitive-addons-for-elementor'),
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
        
        $this->add_control(
            'portfolio_hvr_style',
            [
            'label' =>__('Portfolio/Post Hover Style', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => [
                    
            'hover1' =>__('Hover Animation-1', 'definitive-addons-for-elementor'),
            'hover2' =>__('Hover Animation-2', 'definitive-addons-for-elementor'),
            'hover3' =>__('Hover Animation-3', 'definitive-addons-for-elementor'),
            'hover4' =>__('Hover Animation-4', 'definitive-addons-for-elementor'),
                    
            ],
            'default' => 'hover2',
                
            ]
        );
        
        $this->add_control(
            'post_filter_align',
            [
            'label' =>__('Filter Align', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-left',
            ],
            'center' => [
            'title' =>__('Center', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-center',
            ],
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-right',
            ],
            ],
            'default' => 'left',
            'selectors' => [
                    '{{WRAPPER}} .filters' => 'text-align: {{VALUE}};',
                ],
                
                
            ]
        );
        
        $this->add_control(
            'read_more_text',
            [
            'label'     => __('Read More on Overlay', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default' => __('Read More', 'definitive-addons-for-elementor'),
                
                
                
            ]
        );
        
        $this->add_control(
            'enable_post_title',
            [
                'label' => __('Enable Title?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                
            ]
        );
        
        $this->add_control(
            'enable_meta_content',
            [
                'label' => __('Enable Meta & Content?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                
            ]
        );
        
        $this->add_control(
            'enable_excerpt',
            [
                'label' => __('Enable Excerpt?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
            'condition' => [
            'enable_meta_content' => 'yes',
                    
                ],
                
            ]
        );
        $this->add_control(
            'post_grid_align',
            [
            'label' =>__('Post Align', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-left',
            ],
            'center' => [
            'title' =>__('Center', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-center',
            ],
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-right',
            ],
            ],
            'default' => 'center',
            'condition' => [
                
                    'enable_meta_content' => 'yes',
                    
            ],
            'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .dafe-post-entry-meta,{{WRAPPER}} .dafe-widget-portfolio-wrap .portfolio-title-down,{{WRAPPER}} .dafe-widget-portfolio-wrap .blog-buttons' => 'text-align: {{VALUE}};',
                ],
                
            ]
        );
        
    
        $this->end_controls_section();


        // style

        $this->start_controls_section(
            'post_section_style_filter',
            [
                'label' =>__('Category Filter', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_responsive_control(
            'filter_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_filter_colors',
            [
            'label' => __('Filter Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_filter_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'filter_color',
            [
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .filters li a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .filters li a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_filter_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'filter_hover_color',
            [
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filters li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'filter_bg_hvr_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .filters li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_font',
                'selector' => '{{WRAPPER}} .filters li',
                
            ]
        );

          $this->end_controls_section();
      
        $this->start_controls_section(
            'blog_section_style_image',
            [
                'label' => __('Image Overlay', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_control(
            'image_overlay_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .dafe-widget-portfolio-txt' => 'background-color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'image_overlay_title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .dafe-widget-portfolio-txt .dafe-portfolio-title a' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'image_overlay_title_hvr_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .dafe-widget-portfolio-txt .dafe-portfolio-title a:hover' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'overlay_title_font',
                'selector' => '{{WRAPPER}} .dafe-widget-portfolio-wrap .dafe-widget-portfolio-txt .dafe-portfolio-title',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_overlay_border',
                'selector' => '{{WRAPPER}} .dafe-widget-portfolio-wrap .dafe-widget-portfolio-txt',
            ]
        );
        
        $this->add_responsive_control(
            'image_ovr_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .dafe-widget-portfolio-txt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
    
        $this->end_controls_section();

       

        $this->start_controls_section(
            'blog_section_style_title',
            [
                'label' =>__('Portfolio/Post Title', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .portfolio-title-down' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_top_spacing',
            [
                'label' => __('Top Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .portfolio-title-down' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .portfolio-title-down a' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-wrap .portfolio-title-down a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .dafe-widget-portfolio-wrap .portfolio-title-down',
                
            ]
        );

          $this->end_controls_section();
       
        $this->start_controls_section(
            'blog_section_style_meta',
            [
                'label' =>__('Portfolio/Post Meta', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                
            'condition' => [
            'enable_meta_content' => 'yes',
                    
                ],
            ]
        );
        

        $this->add_responsive_control(
            'meta_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-post-entry-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' =>__('Meta Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-header .post-categories a,{{WRAPPER}} .dafe-post-entry-meta span,{{WRAPPER}} .dafe-post-entry-meta span a,{{WRAPPER}} .dafe-post-entry-meta i' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_font',
                'selector' => '{{WRAPPER}} .entry-header .post-categories a,{{WRAPPER}} .dafe-post-entry-meta span,{{WRAPPER}} .dafe-post-entry-meta a',
               
            ]
        );

          $this->end_controls_section();


        $this->start_controls_section(
            'blog_section_style_text',
            [
                'label' => __('Portfolio/Post Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'enable_meta_content' => 'yes',
                    
                ],
            ]
        );
        

        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('Content Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-post-entry-content p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Content Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-post-entry-content p,.page-content .dafe-post-entry-content p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_font',
                'selector' => '{{WRAPPER}} .dafe-post-entry-content p,.page-content .dafe-post-entry-content p',
               
            ]
        );
        
        $this->add_control(
            'post_text_align',
            [
            'label' =>__('Text Align', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-left',
            ],
            'center' => [
            'title' =>__('Center', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-center',
            ],
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-right',
            ],
            'justify' => [
            'title' =>__('Justify', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-justify',
            ],
            ],
            'default' => 'left',
                
            ]
        );
        
        

        $this->end_controls_section();
        
       
        
        $this->start_controls_section(
            'blog_read_more_btn',
            [
                'label' => __('Blog Read More Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'enable_meta_content' => 'yes',
                    
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_button_colors',
            [
            'label' => __('Text Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_button_normal_color_tab',
            [
            'label' =>__('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.more-link' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Button Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.more-link' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Button Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'btn_box_shadow',

            'selector' => '{{WRAPPER}} a.more-link',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} a.more-link',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_button_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        
        $this->add_control(
            'button_hvr_color',
            [
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.more-link:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        
        $this->add_control(
            'button_bg_hvr_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.more-link:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'btn_box_hvr_shadow',

            'selector' => '{{WRAPPER}} a.more-link:hover',
            ]
        );
        
        $this->add_control(
            'btn_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} a.more-link:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} a.more-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        $this->end_controls_section();
        
      

        $this->start_controls_section(
            'blog_section_style_content',
            [
                'label' => __('Portfolio/Post Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'enable_meta_content' => 'yes',
                    
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Item Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-title-down,{{WRAPPER}} .dafe-post-content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .dafe-widget-portfolio-entry,{{WRAPPER}} .dafe-widget-portfolio-entry p',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_box_shadow',

            'selector' => '{{WRAPPER}} .dafe-widget-portfolio-entry',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .dafe-widget-portfolio-entry',
            ]
        );
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_container_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'content_bg_hvr_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-entry:hover,{{WRAPPER}} .dafe-widget-portfolio-entry:hover p' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
    
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_shadow',

            'selector' => '{{WRAPPER}} .dafe-widget-portfolio-entry:hover',
            ]
        );
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-entry:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Container Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-widget-portfolio-entry' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        

    }

	protected function render( )
    {
        
        $settings = $this->get_settings_for_display(); 
        $post_order_by = $this->get_settings_for_display('post_order_by'); 
        $post_orders = $this->get_settings_for_display('post_orders');
        $no_of_column = $this->get_settings_for_display('no_of_column');
        $number_of_post = $this->get_settings_for_display('number_of_post');
        $column_gap = $this->get_settings_for_display('column_gap');
    
        $category_selection = $this->get_settings_for_display('category_selection');
    
        $enable_excerpt = $this->get_settings_for_display('enable_excerpt');
        $hvr_style = $this->get_settings_for_display('portfolio_hvr_style');
    
        $category_exclude = $this->get_settings_for_display('category_exclude');
        $post_grid_align = $this->get_settings_for_display('post_grid_align');
        $post_text_align = $this->get_settings_for_display('post_text_align');
    
        $enable_meta_content = $this->get_settings_for_display('enable_meta_content');
        $read_more_text = $this->get_settings_for_display('read_more_text');
        $enable_post_title = $this->get_settings_for_display('enable_post_title');

        ?>
     
      
    <div class="dafe-portfolio-container">
  
        <ul class="filters">
                <li><a href="#" data-filter="*" class="selected"><?php esc_attr_e('All', 'definitive-addons-for-elementor');?></a></li>
        <?php 
        $terms = get_terms("category"); 
        $count = count($terms); 
        if ($count > 0 ) {  
            foreach ( $terms as $term ) { 
                printf("<li><a href='#' data-filter='.".esc_attr($term->slug)."' >" . esc_html($term->name) . "</a></li>\n");
        
            }
        } 
        ?>
        </ul>
            
        <?php	
    
        Da_Post::dafe_get_portfolio_post(
            $post_order_by, $post_orders, $no_of_column, $number_of_post,
            $column_gap, $category_selection, $enable_excerpt, $hvr_style, $category_exclude, $post_grid_align,
            $post_text_align, $enable_meta_content, $read_more_text, $enable_post_title
        );
        ?>
</div>
        <?php 

      
    }
}