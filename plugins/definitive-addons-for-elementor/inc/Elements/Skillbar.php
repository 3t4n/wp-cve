<?php
/**
 * Skillbar
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
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();
/**
 * Skillbar
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Skillbar extends Widget_Base
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
        return __('DA: Skill Bar', 'definitive-addons-for-elementor');
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
        return 'dafe_skillbar';
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
        return 'eicon-skill-bar';
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
        return [ 'bar', 'facts', 'skill','chart', 'bar' ];
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
            'dafe_section_skill',
            [
                'label' =>__('Skill Bar', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

      

        $this->add_control(
            'skill_text',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Skill text', 'definitive-addons-for-elementor'),
                'default' =>__('Web Developer', 'definitive-addons-for-elementor')
            ]
        );

        $this->add_control(
            'skill_val',
            [
                'type' => Controls_Manager::NUMBER,
                'label_block' => true,
                'label' => __('Skill Value', 'definitive-addons-for-elementor'),
                'default' =>65,
            ]
        );
        
        
        $this->end_controls_section();
    
        //
        
        $this->start_controls_section(
            'skill_section_style_entry',
            [
                'label' => __('Skill Item Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'skillbar_style',
            [
            'label' =>__('Skillbar Title Style', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'options' => array(
            'inline' =>__('Inline', 'definitive-addons-for-elementor'),
            'block'  =>__('Block', 'definitive-addons-for-elementor'),
                    ),
                    
            'default' => 'block',
                
            ]
        );
        $this->end_controls_section();
        
        $this->start_controls_section(
            'skill_section_style_bar',
            [
                'label' => __('Skill Bar', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'bar_height',
            [
                'label' => __('Bar Height', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 70,
                    ],
                ],
                'default' => [
                'size' => 30
                ],
                'selectors' => [
                    '{{WRAPPER}} .skillbar-bar,{{WRAPPER}} .skillbar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'inner_border_radius',
            [
                'label' => __('Skillbar Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skillbar-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

            
        $this->add_control(
            'bar_bg_color',
            [
                'label' =>__('Bar Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .skillbar-bar' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
    
        $this->end_controls_section();
        
        
       
        $this->start_controls_section(
            'skillbar_section_style_title',
            [
                'label' =>__('Skillbar Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'text_color',
            [
                'label' => __('Skillbar Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skill-container .title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_font',
                'selector' => '{{WRAPPER}} .skill-container .title',
                
            ]
        );
        
        $this->end_controls_section();
    
        $this->start_controls_section(
            'skillbar_section_style_value',
            [
                'label' => __('Skillbar Percentage', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_control(
            'skill_val_color',
            [
                'label' =>__('Percentage Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skill-bar-percent' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'val_size',
            [
                'label' => __('Percentage Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .skill-bar-percent' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
    
        $this->end_controls_section();
    }
    
	

	protected function render() {
        $settings = $this->get_settings_for_display();
		$bar_height = $this->get_settings_for_display('bar_height');
		
		
		$skill_text = $this->get_settings_for_display('skill_text');
		$skill_val = $this->get_settings_for_display('skill_val');
		
		$skillbar_style = $this->get_settings_for_display('skillbar_style');
		
		
		
		$id = uniqid();
        ?>

<div class="skill-container <?php echo esc_attr($id) ?>">
		<?php if ($skillbar_style =='block'){ ?>

		<span class="title" style="line-height:<?php echo esc_attr($bar_height['size']); ?>px;"><?php echo esc_html($skill_text); ?></span>

		<?php } ?>

	<div class="skillbar"  data-percent="<?php echo esc_attr($skill_val); ?>%">

	
		<div class="skillbar-title">
		<?php 	if ($skillbar_style == 'inline'){ ?>
			<span class="title" style="height:<?php echo esc_attr($bar_height['size']) ?>px;line-height:<?php echo esc_attr($bar_height['size']) ?>px;"> 
			
			<?php echo esc_html($skill_text); ?></span>

		<?php } ?>
		</div>
	
		<div class="skillbar-bar"></div>
		<div class="skill-bar-percent"><?php echo esc_attr($skill_val); ?>%</div>

	
	</div> <!-- End Skill Bar -->
</div>


		
	

        <?php
    }
	
}
