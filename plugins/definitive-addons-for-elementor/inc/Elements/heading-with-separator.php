<?php
/**
 * Heading With Separator
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
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Heading With Separator
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Heading_With_Separator extends Widget_Base
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
        return __('DA: Heading with Separator', 'definitive-addons-for-elementor');
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
        return 'dafe_heading_with_separator';
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
        return 'eicon-t-letter';
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
        return [ 'counter', 'facts', 'skill' ];
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
                'label' => __('Heading with Separator', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'heading',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Heading Text', 'definitive-addons-for-elementor'),
                'default' => __('I am heading or text', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'title_tag',
            [
            'label' => __('Title HTML Tag', 'plugin-domain'),
            'type' =>Controls_Manager::SELECT,
            'default' => 'h3',
            'options' => [
            'h1' => __('H1', 'definitive-addons-for-elementor'),
            'h2' => __('H2', 'definitive-addons-for-elementor'),
            'h3' => __('H3', 'definitive-addons-for-elementor'),
            'h4' => __('H4', 'definitive-addons-for-elementor'),
            'h5' => __('H5', 'definitive-addons-for-elementor'),
            'h6' => __('H6', 'definitive-addons-for-elementor'),
            'span' =>__('Span', 'definitive-addons-for-elementor')
            ],
            ]
        );
        $this->add_control(
            'show_hide_sep',
            [
                'label' => __('Show/Hide Separator', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
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
                
                
                
            ]
        );

        $this->end_controls_section();

        //

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Heading', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        

        
        $this->add_responsive_control(
            'title_bottom_spacing',
            [
                'label' => __('Title Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .font-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_title_colors',
            [
            'label' => __('Title Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_title_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .font-heading' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_bg_color',
            [
                'label' => __('Title Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .font-heading' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_title_hover_tab',
            [
            'label' =>__('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' =>__('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .font-heading:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_bg_hvr_color',
            [
                'label' =>__('Title Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .font-heading:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .font-heading',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'heading_shadow',

            'selector' => '{{WRAPPER}} .font-heading',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [    
                
            'name' => 'heading_stroke',
            'selector' => '{{WRAPPER}} .font-heading',
            ]
        );
        
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __('Title Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .font-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        //
        $this->start_controls_section(
            'heading_section_style_separator',
            [
                'label' => __('Heading Separator', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'separator_color',
            [
                'label' => __('separator Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-separator' => 'border-color: {{VALUE}}',
                ],
                'default' =>'#6EC1E4'
            ]
        );
        
        $this->add_control(
            'separator_hvr_color',
            [
                'label' => __('Separator Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .da-separator:hover' => 'border-color: {{VALUE}}',
                ],
                'default' =>'#6EC1D4'
            ]
        );
        
        $this->add_responsive_control(
            'separator_width',
            [
                'label' => __('Separator Width', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
            'default' => [
            'size' => 10
                ],
                'selectors' => [
                    '{{WRAPPER}} .da-widget-heading .da-separator' => 'width: {{SIZE}}%;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'separator_height',
            [
                'label' =>__('Separator Height', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 1
                ],
                'selectors' => [
                    '{{WRAPPER}} .da-separator' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'separator_bottom_spacing',
            [
                'label' => __('Separator Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .da-separator' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
       

        
    }
    
	
	
	
	

	protected function render() {
		
        $settings = $this->get_settings_for_display();
		$heading_alignment = $this->get_settings_for_display('heading_alignment');
		$separator_width = $this->get_settings_for_display('separator_width');
		$title_tag = $this->get_settings_for_display( 'title_tag' );
		$show_hide_sep = $this->get_settings_for_display( 'show_hide_sep' );
		$separator_styles = '';
		
		if ($show_hide_sep != 'yes'){
			$separator_styles .= 'display:none;';
		}
		
		$style = '';
		$container_styles = 'text-align: '.$heading_alignment.';';
	
		if ($heading_alignment == 'left'){
		
			$separator_styles .= "left:0%;";
		}
	 
		if ($heading_alignment == 'center'){
			$separator_w = 100 - intval($separator_width['size']);
			$separator_l = intval($separator_w)/2;
			$separator_styles .= "left:".intval($separator_l)."%;";
		}
	
		if ($heading_alignment == 'right'){
			$separator_w = 100 - intval($separator_width['size']);
			$separator_styles .= "left:".intval($separator_w)."%;";
		}
	
	   $separator_styles .= 'border-style:solid;';
	   $separator_styles .= 'position:relative;';
	
		$styles ='';
	
		if($title_tag == "") {
		$title_tag = "h3";
		}
		
        ?>

        <div class="widget-heading" 
			style="<?php echo esc_attr($container_styles); ?>">
		    
			<<?php echo esc_attr($title_tag); ?> class="font-heading" style="<?php echo esc_attr($styles); ?>">
				<?php echo esc_html($settings['heading']); ?>
			</<?php echo esc_attr($title_tag); ?>>
		<div class="separator" style="<?php echo esc_attr($separator_styles); ?>"></div>
		</div>

        <?php
    }
	
}
