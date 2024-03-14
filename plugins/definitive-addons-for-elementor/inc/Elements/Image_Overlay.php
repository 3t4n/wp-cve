<?php
/**
 * Image Overlay
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
 * Image Overlay
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Image_Overlay extends Widget_Base
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
        return __('DA: Image Overlay', 'definitive-addons-for-elementor');
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
        return 'dafe_Image_Overlay';
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
        return 'eicon-image';
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
        return [ 'overlay', 'text', 'image' ];
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
            'section_image_overlay',
            [
                'label' =>__('Image Overlay', 'definitive-addons-for-elementor'),
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
                'default' => 'medium_large',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
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
            'overlay_title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            'condition' => [
            'show_hide_ovl' => 'yes'
                ],
                'label' => __('Overlay Title', 'definitive-addons-for-elementor'),
                'default' =>__('I am Overlay Title', 'definitive-addons-for-elementor')
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
            'span' =>__('span', 'definitive-addons-for-elementor'),
            'p' =>__('P', 'definitive-addons-for-elementor'),
                ],
                'default' => 'h2',
                'condition' => [
                'show_hide_ovl' => 'yes'
                ],
                'toggle' => false,
            ]
        );
        
        $this->add_control(
            'link',
            [
                'label' => __('Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                'placeholder' =>__('https://softfirm.net/', 'definitive-addons-for-elementor'),
            'condition' => [
            'show_hide_ovl' => 'yes'
                ],
                
            ]
        );
        
        
        
        $this->add_responsive_control(
            'overlay_subtitle',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            'condition' => [
            'show_hide_ovl' => 'yes'
                ],
                'label' => __('Overlay Subtitle', 'definitive-addons-for-elementor'),
                'default' =>__('I am Overlay Subtitle', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'subtitle_tag',
            [
                'label' => __('Sub Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
               
                'options' => [
            'h1' => __('H1', 'definitive-addons-for-elementor'),
            'h2' => __('H2', 'definitive-addons-for-elementor'),
            'h3' => __('H3', 'definitive-addons-for-elementor'),
            'h4' => __('H4', 'definitive-addons-for-elementor'),
            'h5' => __('H5', 'definitive-addons-for-elementor'),
            'h6' => __('H6', 'definitive-addons-for-elementor'),
            'div' => __('div', 'definitive-addons-for-elementor'),
            'span' =>__('span', 'definitive-addons-for-elementor'),
            'p' =>__('P', 'definitive-addons-for-elementor'),
                ],
                'default' => 'H5',
                'condition' => [
                'show_hide_ovl' => 'yes'
                ],
                'toggle' => false,
            ]
        );
        $this->end_controls_section();

       

        // Overlay style
        $this->start_controls_section(
            'overlay_section_style',
            [
                'label' => __('Image Overlay Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'show_hide_ovl' => 'yes'
                ],
            ]
        );
        
         
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'overlay_border',
                'selector' => '{{WRAPPER}} .overlay_border_styles',
            ]
        );

       

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Overlay Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .overlay_border_styles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'selector' => '{{WRAPPER}} .overlay_border_styles',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_control(
            'ovl_hvr_bg_color',
            [
                'label' => __('Overlay Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .overlay_border_styles:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

          $this->end_controls_section();
        
        $this->start_controls_section(
            'overlay_section_title_style',
            [
                'label' => __('Overlay Title Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'show_hide_ovl' => 'yes'
                ],
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .overlay-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hvr_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .overlay-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .overlay-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .overlay-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_fonts',
                'selector' => '{{WRAPPER}} .overlay-title',
                
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            'overlay_section_subtitle_style',
            [
                'label' => __('Overlay Sub Title Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
            'show_hide_ovl' => 'yes'
                ],
            ]
        );
        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Sub Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .overlay-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_fonts',
                'selector' => '{{WRAPPER}} .overlay-subtitle',
                
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'image_section_style',
            [
                'label' => __('Image Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                
            ]
        );
        

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .overlay-media img',
            ]
        );

        

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .overlay-media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Image Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'image_shadow',

            'selector' => '{{WRAPPER}} .overlay-media img',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Image Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'image_hvr_shadow',
            'selector' => '{{WRAPPER}} .overlay-media:hover',
            ]
        );
        
        $this->end_controls_section();
        
    }

	protected function render( ) {
		
        $settings = $this->get_settings_for_display();
		
        
		$show_hide_ovl = $this->get_settings_for_display( 'show_hide_ovl' );
		$title_tag = $this->get_settings_for_display( 'title_tag' );
		$overlay_styles = '';
		
		if ($show_hide_ovl != 'yes'){
			$overlay_styles .= 'display:none;';
		}
		
		$overlay_styles .= 'top: 50%;left: 50%;transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);text-align: center;position:absolute;';
		
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'overlay_link', $settings['link'] );
		}

		if ($settings['image']['url']){
			?>
			<div class="image-overlay" style="position:relative;">
			
				<div class="overlay_border_styles" style="<?php echo esc_attr($overlay_styles); ?>">
					<a <?php $this->print_render_attribute_string( 'overlay_link' ); ?>>
				
						<<?php echo esc_attr($title_tag); ?> class="overlay-title"><?php echo esc_html($settings['overlay_title']); ?></<?php echo esc_attr($title_tag); ?>>
					</a>	
						
						<h6 class="overlay-subtitle"><?php echo esc_html($settings['overlay_subtitle']); ?></h6>
						
				</div> 
				
				<div class="overlay-media">
		
						<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' )); ?>
	
				</div>
			
			</div> 
		<?php  } 
    }
}
