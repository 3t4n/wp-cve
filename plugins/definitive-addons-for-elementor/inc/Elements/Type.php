<?php
/**
 * Type
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
 * Type
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Type extends Widget_Base
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
        return __('DA: Type Animation', 'definitive-addons-for-elementor');
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
        return 'dafe_type';
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
        return 'eicon-h-align-right';
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
        return [ 'type', 'animation', 'text' ];
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
      * Register widget content controls
      *
      * @return void.
      */
    protected function register_controls()
    {
        
        $this->start_controls_section(
            'dafe_section_heading',
            [
                'label' => __('Type Animation', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'sentence1',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Typing Text', 'definitive-addons-for-elementor'),
                'default' => __('I am typing animation', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'typeSpeed',
            [
                'type' => Controls_Manager::NUMBER,
                'label_block' => true,
                'label' => __('Typing Speed', 'definitive-addons-for-elementor'),
                'default' =>'35'
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
            'default' => 'center'
                
            ]
        );

        $this->end_controls_section();

        //

        $this->start_controls_section(
            '_section_style_title',
            [
                'label' => __('Typing Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
    
       

        $this->add_control(
            'type_txt_color',
            [
                'label' => __('Type Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .writing,{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'type_txt_bg_color',
            [
                'label' => __('Type Text Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .writing,{{WRAPPER}} .typed-cursor' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'type_font',
                'selector' => '{{WRAPPER}} .writing,{{WRAPPER}} .typed-cursor',
                
            ]
        );


        $this->add_control(
            'type_text_hover_color',
            [
                'label' => __('Type Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .writing:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        
        $this->end_controls_section();
        

        
    }
    
	
	

	protected function render() {
		
        $settings = $this->get_settings_for_display();
		$sentence1 = $this->get_settings_for_display('sentence1');
		$typespeed = $this->get_settings_for_display('typeSpeed');
		
		$text_alignment = $this->get_settings_for_display('heading_alignment');
		$styles = '';
	
		$id = uniqid();
		$this->add_render_attribute( 'writing', [
			'class' => 'writing',
			'data-typespeed' => $typespeed,
			'data-mediaheading' => $sentence1,
		] );
		
        ?>

        <div id="<?php echo esc_attr($id); ?>" class="type-container <?php echo esc_attr($text_alignment); ?>">
					
			<span <?php $this->print_render_attribute_string( 'writing' ); ?>></span>
			
		</div>

        <?php
    }
	
}
