<?php
/**
 * Staff/Team Member
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
use Elementor\Group_Control_Css_Filter;
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
 * Staff/Team Member
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Staff_Member extends Widget_Base
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
        return __('DA: Staff Member', 'definitive-addons-for-elementor');
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
        return 'dafe_staff_member';
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
         return 'eicon-lock-user';
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
        return [ 'team', 'staff', 'member' ];
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
            'dafe_section_staff_member',
            [
                'label' => __('Staff Member', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => __('Image', 'definitive-addons-for-elementor'),
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'full',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );
        
        $this->add_control(
            'staff_name',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Name', 'definitive-addons-for-elementor'),
            'default' =>__('John Doe', 'definitive-addons-for-elementor'),
                
            ]
        );

        $this->add_control(
            'staff_position',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
               'label' => __('Job Position', 'definitive-addons-for-elementor'),
            'default' =>__('Developer', 'definitive-addons-for-elementor'),
                
            ]
        );
        
        
        $this->add_control(
            'staff_text',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label' => __('About Staff Member', 'definitive-addons-for-elementor'),
                'default' =>__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor', 'definitive-addons-for-elementor'),
                
            ]
        );
        
        $this->add_control(
            'staff_member_alignment',
            [
            'label' =>__('Set Alignment', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .staff-member,{{WRAPPER}} .staff-member-text' => 'text-align: {{VALUE}};',
                ],
                
                
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_social_icon',
            [
                'label' => __('Social Icons', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'social_off_on',
            [
                'label' => __('Social Icons Show/Hide', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        $repeater = new Repeater();

        
        $repeater->add_control(
            'icon_name',
            [
            'label'   => __('Social Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fab fa-facebook',
            'library' => 'fa-brands',
            ],
            'recommended' => [
                    'fa-brands' => Reuse::dafe_social_icon_brands(),
            ],
                
                
            ]
        );

        $repeater->add_control(
            'icon_link', [
                'label' => __('Social Icon Link', 'definitive-addons-for-elementor'),
              
            'type' => Controls_Manager::URL,
                'label_block' => true,
                'autocomplete' => false,
                'show_external' => false,
      
            ]
        );
        
        $this->add_control(
            'social_icons',
            [
                
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
               
                'default' => [
                    [
                        'icon_name' => ['value' => 'fab fa-facebook'],
                        
            'icon_link' => ['url' => 'https://facebook.com/'],
                    ],
                    [
                        
                        'icon_name' => ['value' => 'fab fa-twitter'],
                    'icon_link' => ['url' => 'https://twitter.com/'],
                    ],
                    [
                        
                        'icon_name' => ['value' => 'fab fa-linkedin'],
                    'icon_link' => ['url' => 'https://linkedin.com/'],
                    ]
                ],
                'title_field' => '<# print("Social Icon"); #>',
            ]
        );

       
        $this->end_controls_section();

        // style
     
        $this->start_controls_section(
            'staff_section_style_image',
            [
                'label' => __('Staff Member Image', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Image Width(%)', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
            'default' => [
            'unit' => '%',
            'size' => 100,
                ],
                'range'      => [
                        
                '%' => [
                'min' => 10,
                'max' => 100,
                ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .staff-member .dafe-staff-image img' => 'width: {{SIZE}}%;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .dafe-staff-image img',
            ]
        );

        $this->add_control(
            'img_hvr_border_color',
            [
                'label' => __('Image Border Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .staff-member .dafe-staff-image img:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-staff-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __('Image Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-staff-image img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'image_shadow',

            'selector' => '{{WRAPPER}} .dafe-staff-image img',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
            'label' => __('CSS Filter', 'definitive-addons-for-elementor'),
            'name' => 'img_css_filter',
            'selector' => '{{WRAPPER}} .dafe-staff-image img',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
            'label' => __('CSS Filter Hover', 'definitive-addons-for-elementor'),
            'name' => 'img_css_hvr_filter',
            'selector' => '{{WRAPPER}}:hover .dafe-staff-image img',
            ]
        );

    
        $this->end_controls_section();

        
        
        $this->start_controls_section(
            'staff_section_style_name',
            [
                'label' => __('Staff Member Name', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
       

        $this->add_responsive_control(
            'name_spacing',
            [
                'label' => __('Name Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 10
                ],
                'selectors' => [
                    '{{WRAPPER}} .staff-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Name Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .staff-member-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->add_control(
            'name_hvr_color',
            [
                'label' => __('Name Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .staff-member-name:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_font',
                'selector' => '{{WRAPPER}} .staff-member-name',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'staff_name_shadow',

            'selector' => '{{WRAPPER}} .staff-member-name',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [    
                
            'name' => 'staff_name_stroke',
            'selector' => '{{WRAPPER}} .staff-member-name',
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            'job_section_style_position',
            [
                'label' => __('Job Position', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'job_position_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .staff-member-job-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'job_position_color',
            [
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .staff-member-job-position' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'job_position_hvr_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .staff-member-job-position:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'job_position_font',
                'selector' => '{{WRAPPER}} .staff-member-job-position',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'staff_position_shadow',

            'selector' => '{{WRAPPER}} .staff-member-job-position',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [    
                
            'name' => 'staff_position_stroke',
            'selector' => '{{WRAPPER}} .staff-member-job-position',
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'staff_section_style_text',
            [
                'label' => __('Staff Member Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
       

        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('Bottom Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} p.staff-member-text,.site-main {{WRAPPER}} p.staff-member-text' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} p.staff-member-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_fonts',
                'selector' => '{{WRAPPER}} p.staff-member-text',
                
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => __('Social Icon', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 16
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-container' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_height',
            [
                'label' => __('Icon Height', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
            'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 35
                ],        

                'selectors' => [
                    '{{WRAPPER}} .icon-container' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .icon-container' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_width',
            [
                'label' => __('Icon Width', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 35
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_icon_colors',
            [
            'label' => __('Icon Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_normal_icon_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        
        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .icon-container .icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_background',
                'selector' => '{{WRAPPER}} .icon-container',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                
            'name'     => 'container_box_shadow',

            'selector' => '{{WRAPPER}} .icon-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .icon-container',
            ]
        );
        
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_hover_icon_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-container:hover .icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-container:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_box_shadow',

            'selector' => '{{WRAPPER}} .icon-container',
            ]
        );
        
        $this->add_control(
            'icon_hover_border_color',
            [
                'label' => __('Border Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-container:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .icon-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        
        $this->add_responsive_control(
            'icon_right_spacing',
            [
                'label' => __('Icon Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .icon-container' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_responsive_control(
            'icon_bottom_spacing',
            [
                'label' => __('Icon Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    
        $this->end_controls_section();

        
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Staff Member Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'=>['top' => '','right' => '10','bottom' => '','left' => '10','isLinked' => 'true',],
                'selectors' => [
                    '{{WRAPPER}} .staff-member' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .staff-member',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_shadow',

            'selector' => '{{WRAPPER}} .staff-member',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .staff-member',
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
            'content_hover_bg_color',
            [
                'label' => __('Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .staff-member:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_shadow',

            'selector' => '{{WRAPPER}} .staff-member:hover',
            ]
        );
        
        $this->add_control(
            'content_border_hvr_color',
            [
                'label' => __('Border Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .staff-member:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
                
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' =>__('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .staff-member' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->add_control(
            'container_rotate',
            [
            'label' =>__('Rotate', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
            'size' => 0,
            'unit' => 'deg',
            ],
            'selectors' => [
                    '{{WRAPPER}} .staff-member' => 'transform: rotate({{SIZE}}{{UNIT}});',
            ],
            ]
        );
    
        $this->end_controls_section();
        
        
    }
	
	protected function render() {
        $settings = $this->get_settings_for_display();

		if ($settings['social_off_on'] == 'yes'){
			
			$settings['social_off_on'] = 'yes';
			
		} else {
			
			$settings['social_off_on'] = 'no';
			
		}
		$image = $settings['image']['url'];
		
		
        ?>

                    <div class="staff-member style3">
                        <?php if ( $image ) : ?>
						<figure class="dafe-staff-image">
					<?php 
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ));
							
					?>
					</figure>
					<?php endif; ?>
						
                          <div class="staff-member-content">
                                <?php if ( $settings['staff_name'] ) : ?>
                                    <h3 class="staff-member-name"><?php echo esc_html( $settings['staff_name'] ); ?></h3>
                                <?php endif; ?>
                                <?php if ( $settings['staff_position'] ) : ?>
                                    <h6 class="staff-member-job-position"><?php echo esc_html( $settings['staff_position']); ?></h6>
                                <?php endif; ?>
								<?php if ( $settings['staff_text'] ) : ?>
                                    <p class="staff-member-text"><?php echo esc_html( $settings['staff_text']); ?></p>
                                <?php endif; ?>
                            </div>
                      
						<div class="social-icon-profile <?php echo esc_attr($settings['social_off_on']); ?>">
						<?php foreach ( $settings['social_icons'] as $key => $social_icon ) :  ?>
								<?php if ( ! empty( $social_icon['icon_link']['url'] ) ) {
									 $this->add_link_attributes( 'staff_link'.$key, $social_icon['icon_link'] );
		}  						?>
						<div class="icon-container" style="text-align:center;display: inline-block;">
							<a <?php $this->print_render_attribute_string( 'staff_link'.$key ); ?>>
								<i class="<?php echo esc_attr($social_icon['icon_name']['value']); ?> icon"></i>
							</a>
						</div>
						
						<?php endforeach; ?>
						
						 </div>
                    </div>
       
        <?php
    }
}
