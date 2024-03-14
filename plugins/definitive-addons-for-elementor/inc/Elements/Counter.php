<?php
/**
 * Counter
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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Counter
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */    
class Counter extends Widget_Base
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
        return __('DA: Counter', 'definitive-addons-for-elementor');
    }
    
    /**
     * Get widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'dafe_counter';
    }
    
    /**
     * Get widget icon.
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-counter';
    }
    
    
    /**
     * Get widget keywords.
     *
     * @access public
     *
     * @return string Widget keywords.
     */
    public function get_keywords()
    {
        return [ 'counter', 'facts', 'skill' ];
    }
    
    /**
     * Get widget categories.
     *
     * @access public
     *
     * @return string Widget categories.
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
            'dafe_section_counter',
            [
                'label' => __('Counter', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'counter_icon',
            [
                    'show_label' => false,
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'label_block' => true,
                    'default' => [
                        'value' => 'fas fa-smile-wink',
                        'library' => 'fa-solid',
                    ]
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
            'counter_text',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Counter text', 'definitive-addons-for-elementor'),
                'default' => __('Projects', 'definitive-addons-for-elementor')
            ]
        );

        $this->add_control(
            'counter_start_val',
            [
                'type' => Controls_Manager::NUMBER,
                'label_block' => true,
               'label' => __('Start Value', 'definitive-addons-for-elementor'),
                'default' => 1,
            ]
        );
        
        $this->add_control(
            'counter_end_val',
            [
                'type' => Controls_Manager::NUMBER,
                'label_block' => true,
                'label' => __('Ending Value', 'definitive-addons-for-elementor'),
                'default' => 350,
            ]
        );
        
        $this->add_control(
            'counter_val_position',
            [
            'label' =>__('Counter Value Position', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => [
            'inline'  =>__('Inline', 'definitive-addons-for-elementor'),
            'block'  =>__('Block', 'definitive-addons-for-elementor')
                    ],
            'default' => 'inline',
                
            ]
        );

        
        $this->add_control(
            'counter_alignment',
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
                    '{{WRAPPER}} .counter-container' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .dafe-icon-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
    
        $this->end_controls_section();
    
        //

        $this->start_controls_section(
            'counter_section_style_icon',
            [
                'label' =>__('Counter Icon', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}}  .dafe-icon-container .dafe-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
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

        $this->add_control(
            'icon_color',
            [
            'label' => __('Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}}  .dafe-icon-container .dafe-icon i' => 'color: {{VALUE}}',
                ],
                
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
            'icon_hvr_color',
            [
            'label'          => __('Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-icon:hover i' => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_hover_background',
    
                'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover',
            'condition' => [
            'icon_design!' => 'normal',
                ],
                'exclude' => [
                    'image'
                ]
            ]
        );

        $this->add_control(
            'icon_border_hvr_color',
            [
            'label'     =>__('Border Color', 'definitive-addons-for-elementor'),
            'condition' => [
            'icon_design!' => 'normal',
            ],
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'border-color: {{VALUE}}',
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
            'condition' => [
            'icon_design!' => 'normal',
                ],
                
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
     */   
        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __('Icon Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Value Style

        $this->start_controls_section(
            'section_style_value',
            [
                'label' => __('Counter Value', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_control(
            'counter_val_color',
            [
                'label' => __('Value Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-counter-number' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'counter_val_font',
                'selector' => '{{WRAPPER}} .dafe-counter-number',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'counter_val_shadow',

            'selector' => '{{WRAPPER}} .dafe-counter-number',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [    
                
            'name' => 'counter_val_stroke',
            'selector' => '{{WRAPPER}} .dafe-counter-number',
            ]
        );
        
        $this->add_responsive_control(
            'counter_val_spacing',
            [
                'label' => __('Counter Value & Text Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 10
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-counter-number' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    
        $this->end_controls_section();
        
        
       
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Counter Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'text_color',
            [
                'label' => __('Counter Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-text' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->add_control(
            'text_hvr_color',
            [
                'label' => __('Counter Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-text:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'counter_text_shadow',

            'selector' => '{{WRAPPER}} .counter-text',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [    
                
            'name' => 'counter_text_stroke',
            'selector' => '{{WRAPPER}} .counter-text',
            ]
        );

        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('Text Left Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .counter-text' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_font',
                'selector' => '{{WRAPPER}} .counter-text',
                
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Counter Content', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

       

        $this->start_controls_tabs(
            'dafe_counter_content_colors',
            [
            'label' => __('Counter Content Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_normal_counter_content_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'selector' => '{{WRAPPER}} .counter-container',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_control(
            'dafe_counter_content_border_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .counter-container' => 'border-color: {{VALUE}};',
            ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_shadow',

            'selector' => '{{WRAPPER}} .counter-container',
            ]
        );
        

        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_counter_content_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_hvr_background',
                'selector' => '{{WRAPPER}} .counter-container:hover',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        
        $this->add_control(
            'content_border_hvr_color',
            [
                'label' => __('Counter Border Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-container:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Hover Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_shadow',

            'selector' => '{{WRAPPER}} .counter-container:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
            'exclude' => [
                    'color'
                ],
                'selector' => '{{WRAPPER}} .counter-container',
            ]
        );


        $this->add_responsive_control(
            'counter_border_radius',
            [
                'label' =>__('Content Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .counter-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default' => [
            'top' => '20',
            'right' => '5',
            'bottom' => '20',
            'left' => '5',
    
                ],
                'selectors' => [
                    '{{WRAPPER}} .counter-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .counter-container' => 'transform: rotate({{SIZE}}{{UNIT}});',
            ],
            ]
        );
        

        $this->end_controls_section();
        
        
    }
    
    
	

	protected function render() {
        $settings = $this->get_settings_for_display();

		$counter_alignment = $this->get_settings_for_display( 'counter_alignment' );

		$counter_icon = $settings['counter_icon']['value'];

		
		$this->add_render_attribute( 'counter', [
			'class' => 'dafe-counter-number',
			'data-startval' => $settings['counter_start_val'],
			'data-endval' => $settings['counter_end_val'],
		] );
        ?>

        <div class="counter-container style3 <?php echo esc_attr($counter_alignment); ?>">
		<?php if ($settings['counter_icon']){ ?>
			<div class="dafe-icon-container">
						<div class="dafe-icon dafe-<?php echo esc_attr($settings['icon_design'])?>">
						<?php Icons_Manager::render_icon($settings['counter_icon'], [ 'aria-hidden' => 'true' ]); ?>
											
						</div>
			</div>
		
		<?php } ?>
		<div class="counter-content">
			<span <?php $this->print_render_attribute_string( 'counter' ); ?>></span>
			<span class="counter-text <?php echo esc_attr($settings['counter_val_position']); ?>">
     
			<?php echo esc_html($settings['counter_text']); ?>
  
			</span>
		</div>
	</div>


        <?php
    }
	
}
