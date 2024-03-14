<?php
/**
 * Ninja Forms
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
 * Ninja Forms
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Ninja_Forms extends Widget_Base
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
        return __('DA: Ninja Forms', 'definitive-addons-for-elementor');
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
        return 'dafe_ninja_forms';
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
        return 'eicon-form-horizontal';
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
        return [ 'forms', 'ninja', 'contact','ninjaforms' ];
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
        $da = new Reuse();
        if (!class_exists('Ninja_Forms')) {
            $this->start_controls_section(
                'dafe_reminder_msg',
                [
                    'label' =>__('Reminder Message!', 'definitive-addons-for-elementor'),
                ]
            );

            $this->add_control(
                'ninja_reminder_msg_txt',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' =>__('Ninja Forms is not installed & activated. Please install and activate it.', 'definitive-addons-for-elementor'),
                    'content_classes' => 'reminder_msg',
                ]
            );

               $this->end_controls_section();
        } else {
            
            $this->start_controls_section(
                'dafe_section_wp',
                [
                'label' => __('NinjaForms', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );
            
            
            if (is_array($da->dafe_get_ninjaforms_list()) ) {
        
                $this->add_control(
                    'ninja_form_list',
                    [
                    'label' =>__('Select Form', 'definitive-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' =>$da->dafe_get_ninjaforms_list(),
                    'default' => '0',
                    ]
                );
                
            } else {
                
                $this->add_control(
                    'ninja_form_list',
                    [
                    'type' => Controls_Manager::RAW_HTML,
                    'label' =>$da->dafe_get_ninjaforms_list()
                    ]
                );
            }
            
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
                'show_hide_default_title',
                [
                'label' => __('Show/Hide Default Title', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                ]
            );
        
            $this->add_control(
                'title',
                [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Title Text', 'definitive-addons-for-elementor'),
                'condition' => [
                  'show_hide_title' => 'yes',
                ],
                'default' => __('I am NinjaForms Title.', 'definitive-addons-for-elementor')
                ]
            );
        
            $this->add_control(
                'title_tag',
                [
                'label' => __('Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' =>Controls_Manager::SELECT,
                'default' => 'h1',
                
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
                'show_hide_desc',
                [
                'label' => __('Show/Hide Description', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
                'return_value' => 'yes',
                'frontend_available' => true,
                ]
            );
        
            $this->add_control(
                'description_txt',
                [
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'label' => __('Description Text', 'definitive-addons-for-elementor'),
                'condition' => [
                  'show_hide_desc' => 'yes',
                ],
                'default' => __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget .', 'definitive-addons-for-elementor')
                ]
            );
            
            $this->add_control(
                'ninja_alignment',
                [
                'label' =>__('Title Align', 'definitive-addons-for-elementor'),
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
                'default' => 'center',
                
                ]
            );

            $this->end_controls_section();

            //

            $this->start_controls_section(
                'ninja_container',
                [
                'label' => __('Form Container', 'definitive-addons-for-elementor'),
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
                '{{WRAPPER}} .ninja_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
            $this->add_responsive_control(
                'container_margin',
                [
                'label' => __('Container Margin', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .ninja_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_control(
                'container_bg_color',
                [
                'label' => __('Container Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#eee',
                'selectors' => [
                '{{WRAPPER}} .ninja_container' => 'background-color: {{VALUE}}',
                ],
                ]
            );
        
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .ninja_container',
                ]
            );

            $this->add_responsive_control(
                'container_border_radius',
                [
                'label' => __('Container Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                '{{WRAPPER}} .ninja_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                ]
            );
            $this->end_controls_section();
        
            $this->start_controls_section(
                'ninja_title_section',
                [
                'label' => __('Form Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'title_bottom_spacing',
                [
                'label' => __('Title Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                'size' => 20
                ],
                'selectors' => [
                '{{WRAPPER}} .ninja_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'title_color',
                [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_title' => 'color: {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .ninja_title',
                
                ]
            );


       
            $this->end_controls_section();
        
            $this->start_controls_section(
                'ninja_description_section',
                [
                'label' => __('Form Description', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'desc_bottom_spacing',
                [
                'label' => __('Description Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default' => [
                'size' => 40
                ],
                'selectors' => [
                '{{WRAPPER}} .ninja_description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'desc_color',
                [
                'label' => __('Description Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_description' => 'color: {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'Description_font',
                'selector' => '{{WRAPPER}} .ninja_description',
                
                ]
            );


       
            $this->end_controls_section();
        
            $this->start_controls_section(
                'ninja_label_section',
                [
                'label' => __('Form Label', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'label_bottom_spacing',
                [
                'label' => __('Label Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-form-content label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'label_color',
                [
                'label' => __('Label Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-form-content label' => 'color: {{VALUE}}',
                ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'label_font',
                'selector' => '{{WRAPPER}} .ninja_container .nf-form-content label',
                
                ]
            );


       
            $this->end_controls_section();
        
            $this->start_controls_section(
                'ninja_input_section',
                [
                'label' => __('Text & Textarea Input', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'txt_input_bottom_spacing',
                [
                'label' => __('Text/Textarea Input Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-field' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );
            $this->add_responsive_control(
                'txt_input_width',
                [
                'label' => __('Text Input Width', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-field input[type="text"],{{WRAPPER}} .ninja_container .nf-field input[type="email"]' =>'width: {{SIZE}}{{UNIT}};',
                    
                
                ],
                ]
            );

            $this->add_control(
                'txt_input_color',
                [
                'label' => __('Text/Textarea Input Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-field input[type=text],
					{{WRAPPER}} .ninja_container .nf-field input[type=email],
					{{WRAPPER}} .ninja_container .nf-field textarea' => 'color: {{VALUE}}',
                ],
                ]
            );
        
            $this->add_control(
                'txt_input_bg_color',
                [
                'label' => __('Text/Textarea Input Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-field input[type=text],
					{{WRAPPER}} .ninja_container .nf-field input[type=email],
					{{WRAPPER}} .ninja_container .nf-field textarea,
					{{WRAPPER}} .ninja_container .nf-field select' => 'background-color: {{VALUE}};',
                ],
                ]
            );
        
        

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'txt_input_font',
                'selector' => '{{WRAPPER}} .ninja_container .nf-field input[type=text],
					{{WRAPPER}} .ninja_container .nf-field input[type=email],
					{{WRAPPER}} .ninja_container .nf-field textarea',
                
                ]
            );
        
            $this->add_responsive_control(
                'txt_input_padding',
                [
                'label' => __('Text Input Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-field input[type=text],
					{{WRAPPER}} .ninja_container .nf-field input[type=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_responsive_control(
                'txtarea_input_padding',
                [
                'label' => __('Textarea Input Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                  
                '{{WRAPPER}} .ninja_container .nf-field textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                'name' => 'txt_input_border',
                'selector' => '{{WRAPPER}} .ninja_container .nf-field input[type=text],
					{{WRAPPER}} .ninja_container .nf-field input[type=email],
					{{WRAPPER}} .ninja_container .nf-field textarea',
                ]
            );

            $this->add_responsive_control(
                'txt_input_border_radius',
                [
                'label' => __('Text/Textarea Input Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .nf-field input[type=text],
					{{WRAPPER}} .ninja_container .nf-field input[type=email],
					{{WRAPPER}} .ninja_container .nf-field textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                ]
            );

            $this->end_controls_section();
        
        
        
            $this->start_controls_section(
                'ninja_button_section',
                [
                'label' => __('Form Button', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_responsive_control(
                'btn_bottom_spacing',
                [
                'label' => __('Button Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .submit-container input[type="button"]' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                ]
            );

            $this->add_control(
                'btn_color',
                [
                'label' => __('Button Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_container .submit-container input[type="button"]' => 'color: {{VALUE}}',
                ],
                ]
            );
        
            $this->add_control(
                'btn_bg_color',
                [
                'label' => __('Button Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_container .submit-container input[type="button"]' => 'background-color: {{VALUE}}',
                ],
                ]
            );
            $this->add_control(
                'btn_hover_color',
                [
                'label' => __('Button Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_container .submit-container input[type="button"]:hover' => 'color: {{VALUE}}',
                ],
                ]
            );
            $this->add_control(
                'btn_hover_bg_color',
                [
                'label' => __('Button Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                '{{WRAPPER}} .ninja_container .submit-container input[type="button"]:hover' => 'background-color: {{VALUE}}',
                ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                'name' => 'btn_font',
                'selector' => '{{WRAPPER}} .ninja_container .submit-container input[type="button"]',
                
                ]
            );
        
            $this->add_responsive_control(
                'btn_padding',
                [
                'label' => __('Button Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .submit-container input[type="button"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );
        
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .ninja_container .submit-container input[type="button"]',
                ]
            );

            $this->add_responsive_control(
                'btn_border_radius',
                [
                'label' => __('Button Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                '{{WRAPPER}} .ninja_container .submit-container input[type="button"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
                ]
            );


            $this->end_controls_section();
    
        } 
    }
    
	

	protected function render() {
		
		if (! class_exists( 'Ninja_Forms' ) ) {
            return;
        }
		
        $settings = $this->get_settings_for_display();
		$ninja_form_list = $this->get_settings_for_display('ninja_form_list');
		$title_tag = $this->get_settings_for_display('title_tag');
		$description_txt = $this->get_settings_for_display('description_txt');
		$align = $this->get_settings_for_display('ninja_alignment');
		$show_hide_title = $this->get_settings_for_display('show_hide_title');
		$show_hide_desc = $this->get_settings_for_display('show_hide_desc');
		$show_hide_default_title = $this->get_settings_for_display('show_hide_default_title');
		if ($show_hide_default_title == 'yes'){
			$show_hide_default_title = 'yes';
			} else {
			$show_hide_default_title = 'no';	
			}
		
		?>
			
			<div  id="ninja_form" class="ninja_container <?php echo esc_attr($show_hide_default_title); ?>">
				<div class="form_header <?php echo esc_attr($align); ?>">
				<?php if (!empty($settings['title'])){  ?>
				<<?php echo esc_attr($title_tag);?> class="ninja_title <?php echo esc_attr($show_hide_title); ?>"><?php echo esc_html($settings['title']);?></<?php echo esc_attr($title_tag);?>>
				<?php } ?>
				<p class="ninja_description <?php echo esc_attr($align); ?> <?php echo esc_attr($show_hide_desc); ?>"><?php echo esc_html($description_txt);?></p>
				</div>
				
			
			<?php 
			if ($ninja_form_list){
			
			echo do_shortcode( '[ninja_form id="' . esc_attr($ninja_form_list) . '" ]' );
			}
			 ?>
			</div>
		<?php
    }
	
}
