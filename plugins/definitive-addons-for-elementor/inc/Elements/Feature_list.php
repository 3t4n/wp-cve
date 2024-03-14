<?php
/**
 * Feature List
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
use Elementor\Icons_Manager;
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
 * Feature List
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Feature_List extends Widget_Base
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
        return __('DA: Feature List', 'definitive-addons-for-elementor');
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
        return 'dafe_feature_list';
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
        return 'eicon-editor-list-ul';
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
        return [ 'box', 'feature', 'list' ];
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
            'section_feature_list',
            [
                'label' => __('Feature List', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        
        $repeater->add_control(
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

        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Title', 'definitive-addons-for-elementor'),
                'default' => __('I am feature title', 'definitive-addons-for-elementor')
            ]
        );
        
        $repeater->add_control(
            'link',
            [
                'label' => __('Title Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://softfirm.net/', 'definitive-addons-for-elementor'),
                
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'label' => __('Feature Description', 'definitive-addons-for-elementor'),
                'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt', 'definitive-addons-for-elementor'),
            
            ]
        );

        $this->add_control(
            'feature_lists',
            [
            'label'       =>__('Feature Item', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'seperator'   => 'before',
            'default' => [
                    
            [ 'title' => 'Feature List#1' ],
                    
            [ 'title' => 'Feature List#2' ],
                    
            [ 'title' => 'Feature List#3' ]
                    
                    
            ],
                
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            
            ]
        );
        $this->add_control(
            'icon_design',
            [
                'label' => __('Icon Design', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
               
                'options' => [
            'default' => __('Default', 'definitive-addons-for-elementor'),
            'circle' =>  __('Circle', 'definitive-addons-for-elementor'),
            'square' =>  __('Square', 'definitive-addons-for-elementor'),
            'rounded' => __('Rounded', 'definitive-addons-for-elementor'),
                    
                ],
                'default' => 'circle',
                'toggle' => false,
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
               
                'options' => [
            'h1' => __('H1', 'definitive-addons-for-elementor'),
            'h2' => __('H2', 'definitive-addons-for-elementor'),
            'h3' => __('H3', 'definitive-addons-for-elementor'),
            'h4' => __('H4', 'definitive-addons-for-elementor'),
            'h5' => __('H5', 'definitive-addons-for-elementor'),
            'h6' => __('H6', 'definitive-addons-for-elementor'),
            'div' => __('div', 'definitive-addons-for-elementor'),
            'span' => __('span', 'definitive-addons-for-elementor'),
            'p' => __('p', 'definitive-addons-for-elementor'),
                ],
                'default' => 'h3',
                'toggle' => false,
            ]
        );
        
        $this->add_control(
            'feature_list_alignment',
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
            'default' => 'right',
                
            ]
        );

        $this->end_controls_section();

       

        // style
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => __('Feature Icon', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
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
                'icon_design!' => 'default',
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
            'dafe_icon_normal_color_tab',
            [
            'label' =>__('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#0eeae3',
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        
        $this->add_control(
            'icon_bg_color',
            [
                'label' => __('Icon Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#eee',
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Icon Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'icon_box_shadow',

            'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
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
                'label' => __('Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_hover_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Icon Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'icon_box_hvr_shadow',

            'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover',
            ]
        );
        
        $this->add_control(
            'icon_border_hvr_color',
            [
            'label'     =>__('Border Color', 'definitive-addons-for-elementor'),
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
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ '%','px' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'feature_list_alignment' => 'center',
                ],
                
            ]
        );
    
        $this->end_controls_section();

        

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Feature List Title', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' =>__('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_hvr_color',
            [
                'label' =>__('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-title span',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-title',
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => __('Feature List Description', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => __('Description Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Description Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_font',
                'selector' => '{{WRAPPER}} .dafe-feature-list-container .dafe-feature-list-description',
                
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_content',
            [
                'label' =>__('Feature List Content', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' =>__('Content Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'=>['top' =>'10','right' =>'10','bottom' =>'10','left' =>'10'],
                'selectors' => [
                    '{{WRAPPER}} .feature-list-inner-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .feature-list-inner-container',
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

            'selector' => '{{WRAPPER}} .feature-list-inner-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .feature-list-inner-container',
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
                'selector' => '{{WRAPPER}} .feature-list-inner-container:hover',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_box_shadow',

            'selector' => '{{WRAPPER}} .feature-list-inner-container:hover',
            ]
        );
        
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .feature-list-inner-container:hover' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .feature-list-inner-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
            
        
        $this->end_controls_section();
        
    }

	protected function render( ) {
        
		$feature_lists = $this->get_settings_for_display('feature_lists');
        if (empty($feature_lists) ) {
            return;
        }
        $settings = $this->get_settings_for_display();
        $icon_design = $this->get_settings_for_display('icon_design');
        
        $align = $this->get_settings_for_display('feature_list_alignment'); ?>


		<?php foreach ( $settings['feature_lists'] as $key => $feature_list) : ?>
		<div class="dafe-feature-list-container feature-list-align-<?php echo esc_attr( $align ); ?>">
			<div class="feature-list-inner-container">
					<div class="dafe-icon-container">
						<div class="dafe-icon dafe-<?php echo esc_attr($icon_design)?> ">
						<?php Icons_Manager::render_icon($feature_list['new_icon_id'], [ 'aria-hidden' => 'true' ]); ?>
											
						</div>
					</div>
					<div class="dafe-feature-list-content">
					<?php	if ( ! empty( $feature_list['link']['url'] ) ) {
							
							$this->add_link_attributes( 'feature_list_link'.$key, $feature_list['link'] );
					}?>
					
					<a <?php $this->print_render_attribute_string( 'feature_list_link'.$key ); ?>>
						<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="dafe-feature-list-title">
						<span>
							<?php echo esc_html( $feature_list['title'] ); ?>				
						</span>
						</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
					</a>
							<p class="dafe-feature-list-description <?php echo esc_attr( $align ); ?>">
							<?php echo wp_kses_post( $feature_list['subtitle'] ); ?>
							</p>
					</div>
			</div>
		</div>
		<?php endforeach; ?>
	

        <?php
    }
	
	
	protected function content_template() {
		
	}
}
