<?php
/**
 * Teaser Box
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
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();
/**
 * Teaser Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Teaser_Box extends Widget_Base
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
        return 'dafe_teaser_box';
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
        return __('DA: Card', 'definitive-addons-for-elementor');
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
        return 'eicon-person';
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
        return [ 'definitive','card', 'addons','image-box' ];
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
            'teaser_box_label',
            [
            'label' =>__('Card', 'definitive-addons-for-elementor')
            ]
        );
        

        $this->add_control(
            'box_image',
            [
            'label' => __('Upload Image', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::MEDIA,
            'default' => [
            'url' => Utils::get_placeholder_image_src(),
            ],
            ]
        );


        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'large',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );
        $this->add_control(
            'show_hide_title',
            [
                'label' => __('Show/Hide Title', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'box_title',
            [
            'label' =>__('Card Title', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
            'show_hide_title' => 'yes'
            ],
            'default' =>__('John Doe', 'definitive-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
               
                'options' => [
                    'h1'  => [
                        'title' => __('H1', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __('H2', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __('H3', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __('H4', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __('H5', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __('H6', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'condition' => [
                'show_hide_title' => 'yes'
                ],
                'default' => 'h4',
                'toggle' => false,
            ]
        );
        
        $this->add_control(
            'link',
            [
                'label' => __('Button Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                
                'placeholder' =>__('https://softfirm.net/', 'definitive-addons-for-elementor'),
               
            ]
        );
        
        $this->add_control(
            'show_hide_text',
            [
                'label' => __('Show/Hide Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'box_text',
            [
            'label' =>__('Card Text', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXTAREA,
            'condition' => [
            'show_hide_text' => 'yes'
            ],
            'default' =>__('Add Card text here or leave it blank.', 'elementor-definitive-for-addons'),
            ]
        );
        
        $this->add_control(
            'show_hide_btn',
            [
                'label' => __('Show/Hide Button', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'box_button',
            [
            'label' =>__('Button Text', 'definitive-addons-for-elementor'),
            'condition' => [
            'show_hide_btn' => 'yes'
            ],
            'type' => Controls_Manager::TEXT,
            'default' =>__('Read More', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'image_box_alignment',
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
                    '{{WRAPPER}} .image-box,{{WRAPPER}} .image-box-subtitle' => 'text-align: {{VALUE}};',
                ],
                
            ]
        );

        $this->end_controls_section();

        // style
        // image style
        $this->start_controls_section(
            'section_style_image',
            [
                'label' =>__('Image', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .dafe-card-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .dafe-card-image img',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-card-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
            'label' => __('CSS Filter', 'definitive-addons-for-elementor'),
            'name' => 'img_css_filter',
            'selector' => '{{WRAPPER}} .dafe-card-image img',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
            'label' => __('CSS Filter Hover', 'definitive-addons-for-elementor'),
            'name' => 'img_css_hvr_filter',
            'selector' => '{{WRAPPER}}:hover .dafe-card-image img',
            ]
        );

        
        
        

        $this->end_controls_section();

       
        // Title style
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Card Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'show_hide_title' => 'yes'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'title_top_spacing',
            [
                'label' => __('Title Top Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .image-box-content' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .image-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .image-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .image-box-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .image-box-title',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .image-box-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .image-box-title',
            ]
        );


          $this->end_controls_section();

        // subtitle style
        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' =>__('Card Description', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'show_hide_text' => 'yes'
                ],
            ]
        );
        

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .image-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Description Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .image-box-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'subtitle_hvr_color',
            [
                'label' => __('Description Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .image-box-subtitle:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_font',
                'selector' => '{{WRAPPER}} .image-box-subtitle',
                
            ]
        );

        $this->end_controls_section();
        
        
        
        
        // button style
        $this->start_controls_section(
            'imagebox_button_section',
            [
                'label' => __('Card Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'show_hide_btn' => 'yes'
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_bottom_spacing',
            [
                'label' => __('Button Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .image-box .box-button' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __('Button Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .image-box a.box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
            'btn_color',
            [
                'label' => __('Button Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .image-box a.box-btn' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'btn_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#000',
                'selectors' => [
                    '{{WRAPPER}} .image-box a.box-btn' => 'background-color: {{VALUE}}!important',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                
            'name'     => 'btn_box_shadow',

            'selector' => '{{WRAPPER}} .image-box a.box-btn',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .image-box a.box-btn',
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
            'btn_hover_color',
            [
                'label' => __('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#000',
                'selectors' => [
                    '{{WRAPPER}} .image-box a.box-btn:hover' => 'color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_control(
            'btn_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default'  => '#F21F06',
                'selectors' => [
                    '{{WRAPPER}} .image-box a.box-btn:hover' => 'background-color: {{VALUE}}!important',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'btn_hvr_shadow',

            'selector' => '{{WRAPPER}} .image-box a.box-btn:hover',
            ]
        );
        
        $this->add_control(
            'btn_hover_border_color',
            [
            'label'     => __('Border Hover Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .image-box .box-btn:hover' => 'border-color: {{VALUE}};',
            ]
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
                    '{{WRAPPER}} .image-box .box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_font',
                'selector' => '{{WRAPPER}} .image-box a.box-btn',
                
            ]
        );
        

        $this->end_controls_section();
        
        //container style
        $this->start_controls_section(
            'imagebox_section_style_content',
            [
                'label' => __('Card Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .image-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'name' => 'container_background',
                'selector' => '{{WRAPPER}} .image-box',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'image_box_shadow',

            'selector' => '{{WRAPPER}} .image-box',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .image-box',
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
            'container_hvr_bg_color',
            [
                'label' => __('Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .image-box:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'image_box_hvr_shadow',

            'selector' => '{{WRAPPER}} .image-box:hover',
            ]
        );
        
        
        $this->add_control(
            'container_hover_border_color',
            [
            'label'     => __('Border Hover Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .image-box:hover' => 'border-color: {{VALUE}};',
            ]
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
                    '{{WRAPPER}} .image-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
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
                    '{{WRAPPER}} .image-box' => 'transform: rotate({{SIZE}}{{UNIT}});',
            ],
            ]
        );
        
        $this->end_controls_section();
        

    }


	protected function render( ) {
		
      $settings = $this->get_settings_for_display();
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'card_link', $settings['link'] );
		}
	 
	  $title_tag = $this->get_settings_for_display( 'title_tag' );

	
                $image = wp_get_attachment_image_url( $settings['box_image']['id'], $settings['thumbnail_size'] );
                if ( ! $image ) {
                    $image = $settings['box_image']['url'];
                }
			
			
                ?>

                 <div class="image-box style3">
                    <div class="image-box-entry">
                        <?php if ( $image ) : ?>
						<figure class="dafe-card-image">
						
                           <?php  echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'box_image' )); ?>
						
						</figure>
						<?php endif; ?>

                        
                            <div class="image-box-content">
                                <?php if ( $settings['box_title'] ) : ?>
								<a <?php $this->print_render_attribute_string( 'card_link' ); ?>>
                                    <<?php echo esc_attr($title_tag); ?> class="image-box-title"><?php echo esc_html( $settings['box_title'] ); ?></<?php echo esc_attr($title_tag); ?>>
                                </a>
								<?php endif; ?>
								
                                <?php if ( $settings['box_text'] ) : ?>
                                    <p class="image-box-subtitle"><?php echo esc_html( $settings['box_text'] ); ?></p>
                                <?php endif; ?>
								<?php if ( $settings['box_button'] ) : ?>
								<div class="box-button">
								<a <?php  $this->print_render_attribute_string( 'card_link' ); ?> class="box-btn link">
										<?php echo esc_html($settings['box_button']); ?>
										
								</a>
								</div>
								<?php endif; ?>
                            
							</div>
                      
                    </div>
                </div>

	<?php
	}
}