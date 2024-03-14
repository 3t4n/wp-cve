<?php
/**
 * Prmo Box
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
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Prmo Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Promo_Box extends Widget_Base
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
        return __('DA: Promo Box', 'definitive-addons-for-elementor');
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
        return 'dafe_promo_box';
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
        return 'eicon-logo';
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
        return [ 'promo', 'text', 'image' ];
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
            'section_promo_box',
            [
                'label' =>__('Promo Box', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' =>__('Image', 'definitive-addons-for-elementor'),
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $this->add_control(
            'link',
            [
                'label' => __('Image Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://softfirm.net/', 'definitive-addons-for-elementor'),
                
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
            'promo_title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Promo Box Text', 'definitive-addons-for-elementor'),
                'default' =>__('Promo Box Text', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'show_hide_ovl',
            [
                'label' => __('Show/Hide Overlay Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

       

        // style
        $this->start_controls_section(
            'overlay_section_style',
            [
                'label' => __('Overlay Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'overlay_design',
            [
            'label' =>__('Overlay Design', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'options' => [

            'default'  =>__('Default', 'definitive-addons-for-elementor'),
            'corner'  =>__('Corner', 'definitive-addons-for-elementor')
                    ],
            'default' => 'default',
                
            ]
        );
        
        $this->add_responsive_control(
            'overlay_padding',
            [
                'label' => __('Overlay Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .promo_box_border_style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_ovl_colors',
            [
            'label' => __('Overlay Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_ovl_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'selector' => '{{WRAPPER}} .promo_box_border_style',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'ovl_shadow',

            'selector' => '{{WRAPPER}} .promo_box_border_style',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'overlay_border',
                'selector' => '{{WRAPPER}} .promo_box_border_style',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_ovl_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        
        $this->add_control(
            'overlay_bg_hvr_color',
            [
                'label' => __('Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .promo_box_border_style:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'ovl_hvr_shadow',

            'selector' => '{{WRAPPER}} .promo_box_border_style:hover',
            ]
        );
        $this->add_control(
            'ovl_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .promo_box_border_style:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'ovl_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
            'separator' => 'before',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .promo_box_border_style' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'overlay_section_text_style',
            [
                'label' => __('Overlay Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .promo-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_hvr_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .promo-box-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_fonts',
                'selector' => '{{WRAPPER}} .promo-box-title',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'title_shadow',

            'selector' => '{{WRAPPER}} .promo-box-title',
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'container_style',
            [
                'label' => __('Container Style', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .promo-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'container_bg_color',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .promo-box' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .promo-box',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_shadow',

            'selector' => '{{WRAPPER}} .promo-box',
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
            'container_bg_hvr_color',
            [
                'label' => __('Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .promo-box:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Box Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'container_hvr_shadow',

            'selector' => '{{WRAPPER}} .promo-box:hover',
            ]
        );
        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .promo-box:hover' => 'border-color: {{VALUE}}',
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
            'separator' => 'before',
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .promo-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
            
        
        $this->end_controls_section();
    }

	protected function render( ) {
		
        $settings = $this->get_settings_for_display();
		
		$show_hide_ovl = $this->get_settings_for_display( 'show_hide_ovl' );
		
		
		$promo_overlay_styles = '';
		$promo_overlay_styles = 'top: 50%;left: 50%;transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);text-align: center;position:absolute;';
		$corner_styles = '';
		if ($show_hide_ovl != 'yes'){
			$promo_overlay_styles .= 'display:none;';
			$corner_styles = 'display:none;';
			$show_hide_ovl = 'no';
		}
		if ($settings['overlay_design'] == 'default'){
			
			$corner_styles = 'display:none;';
			$show_hide_ovl = 'no';
		}
		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'promo_box_link', $settings['link'] );
		}


		if ($settings['image']['url']){
			?>
			<div class="promo-box" style="position:relative;">
			
				<div class="promo_box_border_style" style="<?php echo esc_attr($promo_overlay_styles); ?>">

						<a <?php $this->print_render_attribute_string( 'promo_box_link' ); ?>>		 
							<h6 class="promo-box-title"><?php echo esc_html($settings['promo_title']); ?></h6>
						</a>
				
				</div> 
				
				<div class="feature-media <?php echo esc_attr($show_hide_ovl); ?>">
				
					<a <?php $this->print_render_attribute_string( 'promo_box_link' ); ?> >
					
						<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' )); ?>
				
					</a>
					<span class="feature-corner-end" style="<?php echo esc_attr($corner_styles); ?>"></span>
				</div>
			
			</div> 
			<?php } 
    }
}
