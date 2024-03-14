<?php
/**
 * Social Icon
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;

if (! defined('ABSPATH') ) { 
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use \Elementor\Widget_Base;

/**
 * Social Icon
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Social_Icon extends Widget_Base
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
        return __('DA: Social Icons', 'definitive-addons-for-elementor');
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
        return 'dafe_social_icons';
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
        return 'eicon-social-icons';
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
        return ['social', 'icon', 'facebook', 'instagram', 'twitter'];
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
            'dafe_social_icons_content',
            [
            'label' =>__('Social Icons', 'definitive-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );
        
        $repeater = new Repeater();

        
        $repeater->add_control(
            'dafe_social_icon',
            [
            'label'       => __('Icon', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::ICONS,
            'label_block' => false,
            'skin' => 'inline',
            'exclude_inline_options' => ['svg'],
            'default'     => [
            'value'   => 'fab fa-facebook',
            'library' => 'fa-brands',
            ],
            'recommended' => [
                    'fa-brands' => Reuse::dafe_social_icon_brands(),
            ],
            ]
        );

        $repeater->add_control(
            'social_icon_link',
            [
            'label'       => __('Link', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'label_block' => true,
                
            'default'       => [
                    'url'           => '#',
                    'is_external'   => ''
                ],
                'show_external'     => true,
            'placeholder' => __('https://softfirm.net', 'definitive-addons-for-elementor'),
            ]
        );


        $repeater->add_control(
            'show_hide_text',
            [
            'label'          => __('Enable Name', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::SWITCHER,
            'separator'      => 'before',
            'label_on'       => __('Yes', 'definitive-addons-for-elementor'),
            'label_off'      => __('No', 'definitive-addons-for-elementor'),
            'return_value'   => 'yes',
            'default' => 'no',
                
            ]
        );

        $repeater->add_control(
            'social_icons_title',
            [
            'label'     => __('Social Name', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default' => 'Facebook',
            'condition' => [
            'show_hide_text' => 'yes'
            ],
                
            ]
        );

        $repeater->add_control(
            'single_icon_style',
            [
            'label'          => __('Enable Single Icon Style', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::SWITCHER,
            'separator'      => 'before',
            'label_on'       => __('Yes', 'definitive-addons-for-elementor'),
            'label_off'      => __('No', 'definitive-addons-for-elementor'),
            'return_value'   => 'yes',
            'style_transfer' => true,
                
            ]
        );

        $repeater->start_controls_tabs(
            'dafe_single_social_icon_normal_tabs',
            [
            'condition' => ['single_icon_style' => 'yes']
            ]
        );
        $repeater->start_controls_tab(
            'dafe_single_social_icon_normal_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'dafe_single_social_icon_color',
            [
            'label' => __('Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,
            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container > {{CURRENT_ITEM}}.dafe-icon-container .dafe-icon i' => 'color: {{VALUE}};',
            '{{WRAPPER}} .dafe-social-icons-container > {{CURRENT_ITEM}}.dafe-icon-name' => 'color: {{VALUE}};',

            ],
            'condition'      => ['single_icon_style' => 'yes'],
                
            ]
        );
        
        $repeater->add_control(
            'dafe_single_social_icon_bg_color',
            [
            'label' => __('Background Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,

            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container > {{CURRENT_ITEM}}.dafe-icon-container .dafe-icon' => 'background-color: {{VALUE}};',
            ],
            'condition'      => ['single_icon_style' => 'yes'],
                
            ]
        );

        $repeater->add_control(
            'dafe_single_social_icon_border_color',
            [
            'label'          => __('Border Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'condition'      => ['customize' => 'yes'],
            'style_transfer' => true,
            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container > {{CURRENT_ITEM}}.dafe-icon-container .dafe-icon' => 'border-color: {{VALUE}};',
            ]
            ]
        );

        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
            'dafe_single_social_icon_hvr_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'dafe_single_social_icon_hover_color',
            [
            'label'          => __('Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container > {{CURRENT_ITEM}}.dafe-icon-container:hover .dafe-icon i'     => 'color: {{VALUE}};',
            ],
            'condition'      => ['single_icon_style' => 'yes'],
                
            ]
        );
        $repeater->add_control(
            'dafe_single_social_icon_hover_bg_color',
            [
            'label'          => __('Background Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container {{CURRENT_ITEM}}.dafe-icon-container .dafe-icon:hover' => 'background-color: {{VALUE}};',
            ],
            'condition'      => ['single_icon_style' => 'yes'],
                
            ]
        );
        $repeater->add_control(
            'dafe_single_social_icon_hvr_border_color',
            [
            'label'          => __('Border Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'condition'      => ['single_icon_style' => 'yes'],
                
            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container {{CURRENT_ITEM}}.dafe-icon-container .dafe-icon:hover' => 'border-color: {{VALUE}};',
            ]
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'dafe_social_icon_repeater',
            [
            'label'       => __('Social Icons', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
            [
            'dafe_social_icon' => [
            'value'   => 'fab fa-facebook',
            'library' => 'fa-brands',
            ],
                        
            ],
            [
            'dafe_social_icon' => [
            'value'   => 'fab fa-twitter',
            'library' => 'fa-brands',
            ],
                        
            ],
            [
            'dafe_social_icon' => [
            'value'   => 'fab fa-linkedin',
            'library' => 'fa-brands',
            ],
                        
            ],
            ],
            'title_field' => '{{{ dafe_social_icon.value }}}',
            ]
        );
        
    
        $this->add_control(
            'dafe_social_icon_alignment',
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
            'selectors'   => [
                    '{{WRAPPER}} .dafe-social-icons-container' => 'text-align: {{VALUE}};',
            ],
            'default' => 'center',
                
            ]
        );

        
        $this->end_controls_section();

    
        $this->start_controls_section(
            'dafe_social_icon_container_style',
            [
            'label' => __('Social Icon Container', 'definitive-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE
            ]
        );
        
        $this->add_control(
            'social_icon_container_bg_color',
            [
            'label' => __('Background Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,

            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container' => 'background-color: {{VALUE}};',
            ],
                
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dafe_social_container_border',
                'selector' => '{{WRAPPER}}  .dafe-social-icons-container',
            ]
        );

        $this->add_responsive_control(
            'dafe_social_container_border_radius',
            [
                'label' => __('Container Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-social-icons-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dafe_social_container_paddings',
            [
                'label' => __('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-social-icons-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
        
        

        $this->start_controls_section(
            'dafe_social_icon_style',
            [
                'label' => __('Social Icon', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_tab_social_icon_colors',
            [
            'label' => __('social_icon_colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_normal_social_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'dafe_social_icons_color',
            [
            'label' => __('Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,

            'selectors'      => [
            '{{WRAPPER}} .dafe-icon-container .dafe-icon'  => 'color: {{VALUE}};',
                    
            ],
                
            ]
        );
        
        
        $this->add_control(
            'dafe_social_icons_bg_color',
            [
            'label' => __('Background Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,
            'default' => '#eeeeee',
            'selectors' => [
                
            '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'background-color: {{VALUE}};',

            ],
                
            ]
        );

        $this->add_control(
            'dafe_social_icons_border_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'border-color: {{VALUE}};',
            ]
            ]
        );

        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_social_icons_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'dafe_social_icons_hvr_color',
            [
            'label'          => __('Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors'      => [
            '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover i'  => 'color: {{VALUE}};',
                    
            ],
                
            ]
        );
        $this->add_control(
            'dafe_social_icons_hvr_bg_color',
            [
            'label'          => __('Background Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors'      => [
            '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'background-color: {{VALUE}};',
            ],
                
            ]
        );

        $this->add_control(
            'dafe_social_icon_hover_border_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .dafe-icon-container .dafe-icon:hover' => 'border-color: {{VALUE}};',
            ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'dafe_social_icon_size',
            [
            'label'     => __('Size', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
            'px' => [
            'min' => 20,
            'max' => 350,
            ],
            ],
            'default' => [
                    
                    'size' => 50,
            ],
            'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
            ],
            ]
        );
        
        $this->add_responsive_control(
            'dafe_space_between_icon',
            [
            'label'     => __('Space Between Icon', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
            'px' => [
            'min' => 0,
            'max' => 100,
            ],
            ],
            'default' => [
                    'unit' => 'px',
                    'size' => 15,
            ],
            'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    
            ],
            ]
        );

        $this->add_responsive_control(
            'dafe_social_icon_paddings',
            [
                'label' => __('Social Icon Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
            'default'    => [
            'top'    => '15',
            'right'  => '15',
            'bottom' => '15',
            'left'   => '15'
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
            'name'      => 'dafe_social_icon_border',
            'selector'  => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'dafe_social_icon_border_radius',
            [
            'label'      => __('Border Radius', 'definitive-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
            '{{WRAPPER}} .dafe-icon-container .dafe-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'name'     => 'dafe_social_icon_shadow',

            'selector' => '{{WRAPPER}} .dafe-icon-container .dafe-icon',
            ]
        );
        


        $this->add_control(
            'dafe_social_hvr_animation',
            [
            'label' => __('Button Hover Animation', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::HOVER_ANIMATION,
                
                
            ]
        );

        $this->end_controls_section();
        


        $this->start_controls_section(
            'dafe_social_name_style',
            [
            'label' => __('Social Name', 'definitive-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'dafe_social_icons_txt_color',
            [
            'label'          => __('Text Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors'      => [
            '{{WRAPPER}} .dafe-social-icons-container .dafe-icon-name' => 'color: {{VALUE}};',
            ],
                
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
            'name'     => 'social_name_typography',
            'label'    => __('Typography', 'definitive-addons-for-elementor'),
                
            'selector' => '{{WRAPPER}} .dafe-social-icons-container .dafe-icon-name'
            ]
        );

        $this->add_control(
            'social_name_icon_space',
            [
            'label'     => __('Space between Name and Icon', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'selectors' => [
            '{{WRAPPER}}  .dafe-social-icons-container .dafe-icon-name' => 'margin-left: {{SIZE}}{{UNIT}}',
            ],
            ]
        );

        $this->end_controls_section();

        
    }

	protected function render() {
		
		$settings  = $this->get_settings_for_display();

		?>
		<div class="dafe-social-icons-container">
			
						<?php foreach ( $settings['dafe_social_icon_repeater'] as $key => $social_icon ) :  ?>
							
						<?php	if ( ! empty( $social_icon['social_icon_link']['url'] ) ) {
							$this->add_link_attributes( 'social_link'.$key, $social_icon['social_icon_link'] );
							}  ?>
								<div class="dafe-icon-container elementor-repeater-item-<?php echo esc_attr($social_icon['_id']); ?>  elementor-animation-<?php echo esc_attr($settings['dafe_social_hvr_animation'] ); ?>">
									<a <?php $this->print_render_attribute_string( 'social_link'.$key ); ?>>
										
										
											<div class="dafe-icon">
												<?php Icons_Manager::render_icon($social_icon['dafe_social_icon'], [ 'aria-hidden' => 'true' ]); ?>
											
											</div>
										
								
									</a>
								<?php if ($social_icon['show_hide_text'] == 'yes') { ?>
									<?php if ($social_icon['social_icons_title']) { ?>
								<span class="dafe-icon-name"><?php echo esc_html($social_icon['social_icons_title']); ?> </span>
									<?php } ?>
								<?php } ?>
							
								</div>
						
						
						<?php endforeach; ?>

		</div>
		<?php
	}
}
