<?php
/**
 * Post Grid
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
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
 * Post Grid
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Post_Grid extends Widget_Base
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
        return 'dafe_post_grid';
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
        return __('DA: Post Grid', 'definitive-addons-for-elementor');
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
        return 'eicon-posts-grid';
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
            'label' =>__('Post Grid', 'definitive-addons-for-elementor')
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
            'label' =>__('Column Number & Layout', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            
            'options' => [
                    
            '1' =>__('One', 'definitive-addons-for-elementor'),
            '1a' =>__('One - Inline', 'definitive-addons-for-elementor'),
            '2' =>__('Two', 'definitive-addons-for-elementor'),
            '2a' =>__('Two - First Post Full Width', 'definitive-addons-for-elementor'),
            '3' =>__('Three', 'definitive-addons-for-elementor'),
            '3a' =>__('Three - First Post Full Width', 'definitive-addons-for-elementor'),
                    
            ],
            'default' => '1a',
                
            ]
        );
        $this->add_control(
            'post_style',
            [
            'label' =>__('Blog Background Shadow', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => [
                    
            'style1' =>__('White Shadow', 'definitive-addons-for-elementor'),
                    
            'none' =>__('Simple', 'definitive-addons-for-elementor'),
                    
            ],
            'default' => 'style1',
                
            ]
        );
        
        $this->add_control(
            'post_grid_align',
            [
            'label' =>__('Header Align', 'definitive-addons-for-elementor'),
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
            'enable_excerpt',
            [
                'label' => __('Enable Excerpt?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'hide_date',
            [
                'label' => __('Show/Hide Date on Meta?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'hide_date_thumbnail',
            [
                'label' => __('Show/Hide Date on Feature Image?', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        
        $this->end_controls_section();


        // style
        $this->start_controls_section(
            'blog_section_style_image',
            [
                'label' => __('Image', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_responsive_control(
            'post_image_spacing',
            [
                'label' => __('Image Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'condition' => [
            'no_of_column!' => '1a',
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .da-post-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .da-post-thumbnail-img',
            ]
        );
        
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .da-post-thumbnail-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .da-entry-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da_grid_row.ms-post-grid .da-entry-title,{{WRAPPER}} .da_grid_row.ms-post-grid .da-entry-title a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da_grid_row.ms-post-grid .da-entry-title:hover,{{WRAPPER}} .da_grid_row.ms-post-grid .da-entry-title a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .da-entry-title,.page-content .da-entry-title a',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .da_grid_row.ms-post-grid .da-entry-title a',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .da-entry-title,.page-content .da-entry-title a',
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
                'selectors' => [
                    '{{WRAPPER}} .da-entry-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' =>__('Meta Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#54595F',
                'selectors' => [

            '{{WRAPPER}} .da_grid_row.ms-post-grid i,{{WRAPPER}} .da_grid_row.ms-post-grid span,{{WRAPPER}} .da_grid_row.ms-post-grid .post-categories a,{{WRAPPER}} .da_grid_row.ms-post-grid span a' => 'color: {{VALUE}}', 
                ],
            ]
        );
        
        $this->add_control(
            'meta_hover_color',
            [
                'label' => __('Meta Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                
                   '{{WRAPPER}} .da_grid_row.ms-post-grid i:hover,{{WRAPPER}} .da_grid_row.ms-post-grid span:hover,{{WRAPPER}} .da_grid_row.ms-post-grid .post-categories a:hover,{{WRAPPER}} .da_grid_row.ms-post-grid span a:hover' => 'color: {{VALUE}}', 
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_font',
                
                'selector' => '{{WRAPPER}} .da_grid_row.ms-post-grid span,{{WRAPPER}} .da_grid_row.ms-post-grid .post-categories a,{{WRAPPER}} .da_grid_row.ms-post-grid span a', 
            ]
        );

          $this->end_controls_section();


        $this->start_controls_section(
            'blog_section_style_text',
            [
                'label' => __('Blog Post Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('Content Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .da-entry-content p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Content Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-entry-content p,.page-content .da-entry-content p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_font',
                'selector' => '{{WRAPPER}} .da-entry-content p,.page-content .da-entry-content p',
               
            ]
        );
        
        $this->add_control(
            'post_text_align',
            [
            'label' =>__('Text Align', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'leftp' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-left',
            ],
            'centerp' => [
            'title' =>__('Center', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-center',
            ],
            'rightp' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-right',
            ],
            'justifyp' => [
            'title' =>__('Justify', 'definitive-addons-for-elementor'),
            'icon' => 'eicon-text-align-justify',
            ],
            ],
            'default' => 'leftp',
                
            ]
        );

        $this->end_controls_section();
        
      
        
        $this->start_controls_section(
            'blog_read_more_btn',
            [
                'label' => __('Blog Read More Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_button_colors',
            [
            'label' => __('Button Colors', 'definitive-addons-for-elementor'),
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
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.more-link' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
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
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} a.more-link',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_button_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'button_hvr_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.more-link:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hvr_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
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
            'name'     => 'btn_hvr_shadow',

            'selector' => '{{WRAPPER}} a.more-link:hover',
            ]
        );
        
        $this->add_control(
            'button_border_hvr_color',
            [
                'label' => __('Border Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} a.more-link:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
  
        $this->end_controls_section();
        
       
        
        
        $this->start_controls_section(
            'blog_section_style_content',
            [
                'label' => __('Blog Post Content', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .da_home_blog_border_style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .da_home_blog_border_style,{{WRAPPER}} .da_home_blog_border_style p',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name' => 'container_box_shadow',

            'selector' => '{{WRAPPER}} .da_grid_row .da_home_blog_border_style',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .da_grid_row .da_home_blog_border_style',
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
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .da_grid_row .da_home_blog_border_style:hover,{{WRAPPER}} .da_grid_row .da_home_blog_border_style:hover p' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name' => 'container_hvr_shadow',

            'selector' => '{{WRAPPER}} .da_grid_row .da_home_blog_border_style:hover',
            ]
        );
        
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .da_grid_row .da_home_blog_border_style:hover' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .da_grid_row .da_home_blog_border_style' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        $no_of_column = $this->get_settings_for_display('no_of_column');
        $number_of_post = $this->get_settings_for_display('number_of_post');
        $category_selection = $this->get_settings_for_display('category_selection');
    
        $enable_excerpt = $this->get_settings_for_display('enable_excerpt');
        $style = $this->get_settings_for_display('post_style');
        $hide_date = $this->get_settings_for_display('hide_date');
        $category_exclude = $this->get_settings_for_display('category_exclude');
        $post_grid_align = $this->get_settings_for_display('post_grid_align');
        $post_text_align = $this->get_settings_for_display('post_text_align');
        $hide_date_thumbnail = $this->get_settings_for_display('hide_date_thumbnail');
        Da_Post::dafe_get_post_grid_template(
            $post_order_by, $post_orders, $no_of_column, $number_of_post, $category_selection,
            $enable_excerpt, $style, $hide_date, $category_exclude, $post_grid_align, $post_text_align, $hide_date_thumbnail
        );
    
    }
}