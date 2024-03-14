<?php
/**
 * Slider
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;

use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();
/**
 * Slider
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Slider extends Widget_Base
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
        return __('DA: Slider', 'definitive-addons-for-elementor');
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
        return 'dafe_slider';
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
        return 'eicon-slideshow';
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
        return [ 'slider', 'image', 'gallery' ];
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
            'section_slick_slides',
            [
                'label' => __('Slides', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slider_image',
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
                'label' => __('Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                'placeholder' =>__('https://softfirm.net/', 'definitive-addons-for-elementor'),
                
            ]
        );

        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Title', 'definitive-addons-for-elementor'),
                'default' => __('I am Slide Title', 'definitive-addons-for-elementor')
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            'label' => __('Sub Title', 'definitive-addons-for-elementor'),
                'default' => __('I am Slide Sub Title', 'definitive-addons-for-elementor'),
            ]
        );
        
        $repeater->add_control(
            'btn_txt',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('Button Text', 'definitive-addons-for-elementor'),
                'default' =>__('Button Text', 'definitive-addons-for-elementor')
            ]
        );
        $repeater->add_control(
            'icon',
            [
            'label'   =>__('Button Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fas fa-check',
            'library' => 'fa-solid',
            ],
            ]
        );

        $this->add_control(
            'slick_slides',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                   
            [ 'title1' => 'Slide-1' ],
                    
            [ 'title2' => 'Slide-2' ],
                    
            [ 'title3' => 'Slide-3' ],
            [ 'title4' => 'Slide-4' ]
                  
                ]
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
        
        
        
        $this->start_controls_section(
            'content_section_style_start',
            [
                'label' => __('Slide Overlay', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'show_hide_ovl',
            [
                'label' => __('Show/Hide Overlay', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'cta_padding',
            [
                'label' => __('Overlay Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .definitive-slide-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'ovl_background',
            [
                'label' => __('Overlay Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .definitive-slide-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        

        $this->add_control(
            'ovl_hvr_background',
            [
                'label' => __('Overlay Background Hover', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .definitive-slide-item:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .definitive-slide-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .definitive-slide-title',
                
            ]
        );
        
        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Title Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                
                'selectors' => [
                    '{{WRAPPER}} .definitive-slide-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

       

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Description Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#eeeeee',
                'selectors' => [
                    '{{WRAPPER}} .definitive-slide-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_font',
                'selector' => '{{WRAPPER}} .definitive-slide-subtitle',
                
            ]
        );
        
        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => __('Description Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .definitive-slide-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .slides-container .left.slick-arrow, {{WRAPPER}} .slides-container .right.slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
       
        $this->add_control(
            'nav_color',
            [
                'label' => __('Arrow Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slides-container .left.slick-arrow, {{WRAPPER}} .slides-container .right.slick-arrow' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .slides-container .left.slick-arrow, {{WRAPPER}} .slides-container .right.slick-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'nav_hover_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slides-container .right.slick-arrow:hover, {{WRAPPER}} .slides-container .left.slick-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slides-container .left.slick-arrow:hover, {{WRAPPER}} .slides-container .right.slick-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'nav_padding',
            [
                'label' => __('Arrow Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
            'default'=>['top' =>'7','right' =>'15','bottom' =>'7','left' =>'15'],

                'selectors' => [
                    '{{WRAPPER}} .slides-container .left.slick-arrow, {{WRAPPER}} .slides-container .right.slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_border',
                'selector' => '{{WRAPPER}} .slides-container .left.slick-arrow,{{WRAPPER}} .slides-container .right.slick-arrow',
            ]
        );

        $this->add_responsive_control(
            'nav_border_radius',
            [
                'label' =>__('Navigation Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .slides-container .left.slick-arrow,{{WRAPPER}} .slides-container .right.slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
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
                    '{{WRAPPER}} .slides-container .left.slick-arrow,{{WRAPPER}} .slides-container .right.slick-arrow' => 'top: {{SIZE}}%!important;',
                ],
                
            ]
        );
        
        $this->add_responsive_control(
            'nav_left_spacing',
            [
                'label' => __('Left & Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
            'default' => [
            'size' => 0
                ],
                'selectors' => [
                    '{{WRAPPER}} .slides-container .left.slick-arrow' => 'left: {{SIZE}}%!important;',
                '{{WRAPPER}} .slides-container .right.slick-arrow' => 'right: {{SIZE}}%!important;',
                ],
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' =>__('Arrow Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_nav_shadow',

            'selector' => '{{WRAPPER}} .slides-container .left.slick-arrow,{{WRAPPER}} .slides-container .right.slick-arrow',
            ]
        );


        $this->end_controls_section();
        
        $this->start_controls_section(
            'button_style_start',
            [
                'label' => __('Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .da_button_slider' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __('Button Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#fff',
                'selectors' => [
                    '{{WRAPPER}} .dabtnslide,{{WRAPPER}} .icon-right' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Button Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#000',
                'selectors' => [
                    '{{WRAPPER}} .dabtnslide' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_hvr_color',
            [
                'label' => __('Button Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dabtnslide:hover,{{WRAPPER}} .dabtnslide:hover .icon-right' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_bg_hvr_color',
            [
                'label' => __('Button Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#000',
                'selectors' => [
                    '{{WRAPPER}} .dabtnslide:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'default' => [
                'size' => 14
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-right' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_font',
                'selector' => '{{WRAPPER}} .dabtnslide',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .dabtnslide',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dabtnslide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        

        $this->end_controls_section();
        
        
        $this->start_controls_section(
            'dots_section_style_start',
            [
                'label' => __('Dots', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dots_bg_color',
            [
                'label' => __('Dots Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'transparent',
                'selectors' => [
                    '{{WRAPPER}} .slides-container .slick-dots li button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_bg_color',
            [
                'label' =>__('Dots Active Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slides-container .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'dots_border_color',
            [
                'label' => __('Dots Border Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' =>'#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slides-container .slick-dots li button' => 'border-color: {{VALUE}}!important;',
                ],
            ]
        );
        

        $this->end_controls_section();

        
    }

	
	protected function render() {
		$slick_slides = $this->get_settings_for_display('slick_slides');
		
        $settings = $this->get_settings_for_display();
		$autoplay_speed = $this->get_settings_for_display('autoplay_speed');
		$autoplay = $this->get_settings_for_display('autoplay');
		$loop = $this->get_settings_for_display('loop');
		$show_hide_ovl = $this->get_settings_for_display( 'show_hide_ovl');

	
		if ($show_hide_ovl != 'yes'){
			$show_hide_ovl = 'no';
		}
		$add_icon_right = '';
		$slider_image = '';
		$id = uniqid();
		$this->add_render_attribute( 'definitive-slick', [
			'class' => 'definitive-slick',
			
			'data-autospeed' => $autoplay_speed,
			'data-autoplay' => $autoplay,
			'data-loop' => $loop,
		] );
	
        ?>
	<div class="slides-container left-right">
        <div <?php $this->print_render_attribute_string( 'definitive-slick' ); ?>>

            <?php foreach ( $settings['slick_slides'] as $key => $slide ) {
				
                
                    $slider_image = $slide['slider_image']['url'];
					if ( ! empty( $slide['link']['url'] ) ) {
						$this->add_link_attributes( 'slider_link'.$key, $slide['link'] );
					}
             
                ?>

                <div class="definitive-slide">
                    <div class="definitive-slide-entry">
                        
						<?php if ( $slider_image ) { ?>
						<a <?php $this->print_render_attribute_string( 'slider_link'.$key ); ?>>
				
                            <img class="definitive-slide-img" src="<?php echo esc_url( $slider_image ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>">
                       </a>
					   <?php } ?>

                        <div class="definitive-slide-item <?php echo esc_attr($show_hide_ovl); ?>">
                            <div class="definitive-slide-cta">
                                <?php if ( $slide['title'] ) { ?>
								<a <?php $this->print_render_attribute_string( 'slider_link'.$key ); ?> >
				
                                    <h2 class="definitive-slide-title"><?php echo esc_html( $slide['title'] ); ?></h2>
                                </a>
								<?php } ?>
                                <?php if ( $slide['subtitle'] ) { ?>
                                    <p class="definitive-slide-subtitle" style="text-align:center;"><?php echo esc_html( $slide['subtitle'] ); ?></p>
                                <?php } ?>
								
								<?php if ($slide['btn_txt'] != ''){  ?>
								<div class="da_button_slider">
						
								<a <?php $this->print_render_attribute_string( 'slider_link'.$key ); ?> class="btn-default dabtnslide">
									
									<?php echo esc_html($slide['btn_txt']);  ?>	
									
									<span class="<?php echo esc_attr($slide['icon']['value']); ?> icon-right">
									</span>
		
								</a>
								</div>
							<?php	} ?>
                            </div>
								
                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>
		
		
	</div>
	

        <?php
    }
}
