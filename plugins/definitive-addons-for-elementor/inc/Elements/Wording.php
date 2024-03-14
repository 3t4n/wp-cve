<?php
/**
 * Wording/Multi-Color Text
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
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();
/**
 * Wording/Multi-Color Text
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Wording extends Widget_Base
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
        return __('DA: Multi-Color Text', 'definitive-addons-for-elementor');
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
        return 'dafe_wording';
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
        return 'eicon-form-vertical';
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
        return [ 'word', 'title', 'text','animation' ];
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
            'dafe_section_heading',
            [
                'label' => __('Multi-Color Text', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'word1',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Enter one or more word', 'definitive-addons-for-elementor'),
                'default' => __('Definitive', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'word2',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Enter one or more word', 'definitive-addons-for-elementor'),
                'default' =>__('Addons', 'definitive-addons-for-elementor')
            ]
        );
        $this->add_control(
            'word3',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Enter one or more word', 'definitive-addons-for-elementor'),
                'default' =>__('For', 'definitive-addons-for-elementor')
            ]
        );
        $this->add_control(
            'word4',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Enter one or more word', 'definitive-addons-for-elementor'),
                'default' =>__('Elementor', 'definitive-addons-for-elementor')
            ]
        );
        
                
        $this->add_control(
            'wording_display_style',
            [
            'label' =>__('Wording Display Style', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'options' => [
                        'inline' => __('Inline', 'definitive-addons-for-elementor'),
                        'block' => __('Block', 'definitive-addons-for-elementor'),
                    ],
            'default' => 'inline',
            ]
        );
        
        $this->add_control(
            'animation_iteration',
            [
            'label' =>__('Animation Iteration', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'default' => 'once',
            'options' => [
                        'once' => __('Once', 'definitive-addons-for-elementor'),
                        'infinite' => __('Infinite', 'definitive-addons-for-elementor'),
                    ],
                
            ]
        );
        
        
        
        $this->add_control(
            'heading_alignment',
            [
            'label' =>__('Set Alignment', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-left',
            ],
            'center' => [
            'title' =>__('Center', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-center',
            ],
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-right',
            ],
                    
            ],
            'default' => 'center'
                
            ]
        );

        $this->end_controls_section();

        //

        $this->start_controls_section(
            'section_style_word1',
            [
                'label' => __('Word1 Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_control(
            'word1_txt_color',
            [
                'label' => __('Word1 Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word1' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word1_txt_bg_color',
            [
                'label' => __('Word1 Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word1' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word1_txt_hvr_color',
            [
                'label' => __('Word1 Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word1:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word1_txt_hvr_bg_color',
            [
                'label' => __('Word1 Text Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word1:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'word1_font',
                'selector' => '{{WRAPPER}} .word1',
                
            ]
        );

        $this->add_responsive_control(
            'word1_padding',
            [
                'label' => __('Word1 Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .word1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
    
        
        $this->add_control(
            'word1_animation_type',
            [
            'label' =>__('Animation Type', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => Reuse::dafe_css_animations()
                
            ]
        );
        
    
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_word2',
            [
                'label' => __('Word2 Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'word2_txt_color',
            [
                'label' => __('Word2 Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word2' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'word2_txt_bg_color',
            [
                'label' => __('Word2 Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word2' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'word2_txt_hvr_color',
            [
                'label' => __('Word2 Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word2:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'word2_txt_hvr_bg_color',
            [
                'label' => __('Word2 Text Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word2:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'word2_font',
                'selector' => '{{WRAPPER}} .word2',
                
            ]
        );

        $this->add_responsive_control(
            'word2_padding',
            [
                'label' => __('Word2 Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .word2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_control(
            'word2_animation_type',
            [
            'label' =>__('Animation Type', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => Reuse::dafe_css_animations()
                
            ]
        );
    
        $this->end_controls_section();
        
        
        $this->start_controls_section(
            'section_style_word3',
            [
                'label' => __('Word3 Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
       

        $this->add_control(
            'word3_txt_color',
            [
                'label' => __('Word3 Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word3' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word3_txt_bg_color',
            [
                'label' => __('Word3 Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word3' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word3_txt_hvr_color',
            [
                'label' => __('Word3 Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word3:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word3_txt_hvr_bg_color',
            [
                'label' => __('Word3 Text Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word3:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'word3_font',
                'selector' => '{{WRAPPER}} .word3',
                
            ]
        );

        $this->add_responsive_control(
            'word3_padding',
            [
                'label' => __('Word3 Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .word3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_control(
            'word3_animation_type',
            [
            'label' =>__('Animation Type', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => Reuse::dafe_css_animations()
                
            ]
        );
        
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_word4',
            [
                'label' => __('Word4 Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
       

        $this->add_control(
            'word4_txt_color',
            [
                'label' => __('Word4 Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word4' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word4_txt_bg_color',
            [
                'label' => __('Word4 Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word4' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word4_txt_hvr_color',
            [
                'label' => __('Word4 Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word4:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'word4_txt_hvr_bg_color',
            [
                'label' => __('Word4 Text Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .word4:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'word4_font',
                'selector' => '{{WRAPPER}} .word4',
                
            ]
        );

        $this->add_responsive_control(
            'word4_padding',
            [
                'label' => __('Word4 Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .word4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_control(
            'word4_animation_type',
            [
            'label' =>__('Animation Type', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => Reuse::dafe_css_animations()
                
            ]
        );
        
        
        $this->end_controls_section();
        

    }
    
	
	

	protected function render() {
		
		
        $settings = $this->get_settings_for_display();

		$wording_display = $this->get_settings_for_display('wording_display_style');
		
		
		$alignment = $this->get_settings_for_display('heading_alignment');
		
		
		
		$animation1 = $this->get_settings_for_display('word1_animation_type');
		$animation2 = $this->get_settings_for_display('word2_animation_type');
		$animation3 = $this->get_settings_for_display('word3_animation_type');
		
		$animation4 = $this->get_settings_for_display('word4_animation_type');
		
		
		$animation_iteration = $this->get_settings_for_display('animation_iteration');
		
		
		$styles = '';
	
		$id = uniqid();
		
        ?>

        <div class="custom-letter <?php echo esc_attr($id); ?> <?php echo esc_attr($alignment); ?>">
		
			<div class="<?php echo esc_attr($wording_display) ?> word1 animated <?php echo esc_attr($animation_iteration); ?> <?php echo esc_attr($animation1); ?>">
				<?php echo esc_html($settings['word1']); ?>
			</div>
		
			<div class="<?php echo esc_attr($wording_display) ?> word2 animated <?php echo esc_attr($animation_iteration); ?> <?php echo esc_attr($animation2); ?>">
				<?php echo esc_html($settings['word2']); ?>
			</div>
		
			<div class="<?php echo esc_attr($wording_display) ?> word3 animated <?php echo esc_attr($animation_iteration); ?> <?php echo esc_attr($animation3); ?>">
				<?php echo esc_html($settings['word3']); ?>
			</div>
		
			<div class="<?php echo esc_attr($wording_display) ?> word4 animated <?php echo esc_attr($animation_iteration); ?> <?php echo esc_attr($animation4); ?>">
				<?php echo esc_html($settings['word4']); ?>
			</div>
		
	
		</div>
		<?php

	}  
}
	

