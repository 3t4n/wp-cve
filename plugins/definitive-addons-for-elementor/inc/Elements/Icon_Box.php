<?php
/**
 * Icon_Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Icon_Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Icon_Box extends Widget_Base
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
        return __('DA: Icon Box', 'definitive-addons-for-elementor');
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
        return 'dafe_icon_box';
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
        return 'eicon-icon-box';
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
        return [ 'box', 'icon', 'feature' ];
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
            'dafe_section_icon_box',
            [
                'label' => __('Icon Box', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

       

        $this->add_control(
            'new_icon_id',
            [
            'label'   =>__('Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fa fa-cogs',
            'library' => 'fa-solid',
            ]
                
            ]
        );
        
        $this->add_control(
            'link',
            [
                'label' => __('Icon Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://softfirm.net/', 'definitive-addons-for-elementor'),
                
            ]
        );
        
        $this->add_control(
            'icon_design',
            [
                'label' => __('Icon Design', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
               
                'options' => [
            'normal' => __('Normal', 'definitive-addons-for-elementor'),
            'circle' =>  __('Circle', 'definitive-addons-for-elementor'),
            'square' =>  __('Square', 'definitive-addons-for-elementor'),
            'rounded' => __('Rounded', 'definitive-addons-for-elementor'),
                    
                ],
                'default' => 'circle',
                'toggle' => false,
            ]
        );

       
        $this->add_control(
            'title',
            [
            'label' =>__('Icon Box Title', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' =>__('I am Icon Title.', 'definitive-addons-for-elementor'),
            ]
        );
        
        
        
        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
               
                'options' => [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
            'div' => 'div',
            'span' => 'span',
            'p' => 'p',
                ],
                'default' => 'h4',
                'toggle' => false,
            ]
        );


      
        $this->add_control(
            'subtitle',
            [
            'label' =>__('Icon Box Text', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXTAREA,
            'default' =>__('Add Icon text here or leave it blank.', 'definitive-addons-for-elementor'),
            ]
        );
        
        
        $this->add_control(
            'enable_desc_link',
            [
            'label'          => __('Enable Link on Text?', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::SWITCHER,
            'separator'      => 'before',
            'label_on'       => __('Yes', 'definitive-addons-for-elementor'),
            'label_off'      => __('No', 'definitive-addons-for-elementor'),
            'return_value'   => 'yes',
            'default' => 'no',
                
            ]
        );
        
        $this->add_control(
            'icon_box_alignment',
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
            'default' => 'center',
            'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .dafe-icon-box-desc' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .dafe-icon-box-content' => 'text-align: {{VALUE}};',
                    
                ],
                
            ]
        );

        $this->end_controls_section();

       

        // style
    
         
        /*
        * Icon style
        */
        
        $this->start_controls_section(
            'icon_box_section_style_icon',
            [
                'label' => __('Icon Style', 'definitive-addons-for-elementor'),
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
                'size' => 30
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-box-entry .dafe-icon-container i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __('Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                'size' => 16
                ],
                'condition' => [
                'icon_design!' => 'normal',
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'padding: {{SIZE}}{{UNIT}};',
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
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_background',
                'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            'condition' => [
            'icon_design!' => 'normal',
                ],
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'name'     => 'dafe_icon_shadow',

            'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            'condition' => [
            'icon_design!' => 'normal',
            ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            'condition' => [
            'icon_design!' => 'normal',
                ],
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
            'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'icon_hover_bg_color',
            [
                'label' => __('Icon Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                'icon_design!' => 'normal',
                ],
            ]
        );

        $this->add_control(
            'icon_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'border-color: {{VALUE}}',
                ],
            'condition' => [
                    'icon_design!' => 'normal',
            ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
/*
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __('Icon Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                'icon_design!' => 'normal',
                ],
            ]
        );
*/
        $this->add_control(
            'dafe_icon_hvr_animation',
            [
            'label' => __('Hover Animation', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::HOVER_ANIMATION,
                
                
            ]
        );
        
        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __('Icon Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                'size' => 15
                ]
            ]
        );

        $this->end_controls_section();
        
        
        /*
        * Icon box title style
        */
        

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Icon Box Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Title Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hvr_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-box-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .dafe-icon-box-title',
               
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .dafe-icon-box-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .dafe-icon-box-title',
            ]
        );

        
        
        $this->end_controls_section();
        
        /*
        * Icon box description style
        */
    
        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => __('Icon Box Description', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => __('Description Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-box-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

       
        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Description Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#54595F',
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-box-desc' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_font',
                'selector' => '{{WRAPPER}} .dafe-icon-box-desc',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'subtitle_stroke',
            'selector' => '{{WRAPPER}} .dafe-icon-box-desc',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'subtitle_shadow',
            'selector' => '{{WRAPPER}} .dafe-icon-box-desc',
            ]
        );
    
        $this->end_controls_section();
        
        /*
        * Icon box container style
        */
    
        $this->start_controls_section(
            'icon_box_section_style_entry',
            [
                'label' => __('Icon Box Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'=>['top' =>'10','right' =>'10','bottom' =>'10','left' =>'10'],

                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-box-entry' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        
        $this->add_control(
            'dafe_icon_box_bg_color',
            [
            'label' => __('Background Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,
            'default' => '#fff',
            'selectors'      => [
                
            '{{WRAPPER}} .dafe-icon-box-entry' => 'background-color: {{VALUE}};',

            ],
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_icon_box_shadow',

            'selector' => '{{WRAPPER}} .dafe-icon-box-entry',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dafe_icon_box_container_border',
                'selector' => '{{WRAPPER}} .dafe-icon-box-entry',
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
            'dafe_icon_box_hvr_bg_color',
            [
            'label'          => __('Background Hover Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors'      => [
            '{{WRAPPER}} .dafe-icon-box-entry:hover' => 'background-color: {{VALUE}};',
            ],
                
            ]
        );

        $this->add_control(
            'dafe_icon_box_hover_border_color',
            [
            'label'     => __('Border Hover Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .dafe-icon-box-entry:hover' => 'border-color: {{VALUE}};',
            ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_icon_box_hvr_shadow',

            'selector' => '{{WRAPPER}} .dafe-icon-box-entry:hover',
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
                    '{{WRAPPER}} .dafe-icon-box-entry' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dafe_icon_box_hvr_animation',
            [
            'label' => __('Icon Hover Animation', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::HOVER_ANIMATION,
                
                
            ]
        );
    
        $this->end_controls_section();
        
        
    }
	
	protected function render() {
		
        $settings = $this->get_settings_for_display();
		
		
		$title_tag = $this->get_settings_for_display( 'title_tag' );
		$icon_design = $this->get_settings_for_display('icon_design');
		
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'icon_box_link', $settings['link'] );
		}

        ?>
		
	<div class="dafe-icon-box-entry <?php echo esc_attr($settings['icon_box_alignment'] ); ?> elementor-animation-<?php echo esc_attr($settings['dafe_icon_box_hvr_animation'] ); ?>">
        <a <?php $this->print_render_attribute_string( 'icon_box_link' ); ?>>
                             
			<div class="dafe-icon-box-item">
			<a <?php $this->print_render_attribute_string( 'icon_box_link' ); ?>>
				<div class="dafe-icon-container elementor-animation-<?php echo esc_attr($settings['dafe_icon_hvr_animation'] ); ?>">
                       
					<div class="dafe-icon dafe-<?php echo esc_attr($icon_design)?> ">
						<?php Icons_Manager::render_icon($settings['new_icon_id'], [ 'aria-hidden' => 'true' ]); ?>
											
					</div>
				</div>
			</a>

                            <div class="dafe-icon-box-content">
                                <?php if ( $settings['title'] ) : ?>
								<a <?php echo $this->print_render_attribute_string( 'icon_box_link' ); ?>>
                                    
									<<?php echo esc_attr($title_tag); ?> class="dafe-icon-box-title"><?php echo esc_html( $settings['title'] ); ?></<?php echo esc_attr($title_tag); ?>>
                                </a>
								<?php endif; ?>
								<?php if ($settings['enable_desc_link'] == 'yes') { ?>	
                                <?php if ( $settings['subtitle'] ) : ?>
								<a <?php echo $this->print_render_attribute_string( 'icon_box_link' ); ?>>
                                   
                                    <p class="dafe-icon-box-desc <?php echo esc_attr($settings['icon_box_alignment'] ); ?>"><?php echo esc_html( $settings['subtitle'] ); ?></p>
                                </a>
								<?php endif; ?>
								<?php } else { ?>
								<?php if ( $settings['subtitle'] ) : ?>
								
                                    <p class="dafe-icon-box-desc"><?php echo esc_html( $settings['subtitle'] ); ?></p>
                                
								<?php endif; ?>
								<?php } ?>
                            </div>
                       
            </div>
		</a>	
     </div>

        <?php
    }
}
