<?php
/**
 * Icon List
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
 * Icon List
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Icon_List extends Widget_Base
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
        return __('DA: Icon List', 'definitive-addons-for-elementor');
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
        return 'dafe_icon_list';
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
        return 'eicon-bullet-list';
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
        return [ 'icon', 'list'];
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
            'section_icon_list',
            [
                'label' => __('Icon List', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon',
            [
            'label'   =>__('List Icon', 'definitive-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
            'value' => 'fas fa-check',
            'library' => 'fa-solid',
            ],
    
            ]
        );

        $repeater->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' =>__('List Text', 'definitive-addons-for-elementor'),
                'default' =>__('Icon List #1', 'definitive-addons-for-elementor')
            ]
        );
        
        $repeater->add_control(
            'link',
            [
                'label' => __('Icon List Link', 'definitive-addons-for-elementor'),
                'separator' => 'before',
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://softfirm.net/',
                
            ]
        );

        $this->add_control(
            'icon_lists',
            [
            'label'       =>__('Icon List Item', 'definitive-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'seperator'   => 'before',
            'default' => [
                    
            [ 'title' => 'Icon List#1' ],
                    
            [ 'title' => 'Icon List#2' ],
                    
            [ 'title' => 'Icon List#3' ]
                    
                    
            ],
                
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{title}}',
            
            ]
        );
        
        $this->add_control(
            'icon_list_alignment',
            [
            'label' =>__('Icon List Align', 'definitive-addons-for-elementor'),
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
            'default' => 'left',
                
            ]
        );
        
        
        $this->add_control(
            'icon_position',
            [
            'label' =>__('Icon Position', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-left',
            ],
                    
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-right',
            ],
            ],
            'default' => 'left',
                
            ]
        );
        
        $this->add_control(
            'layout',
            [
            'label' =>__('Icon List Layout', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'options' => [
            'inline'  => __('Inline', 'definitive-addons-for-elementor'),
            'block' => __('Block', 'definitive-addons-for-elementor'),
                    
            ],
            'default' => 'block',
                
            ]
        );

            
        $this->end_controls_section();

       

        // style
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => __('List Icon', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Size', 'definitive-addons-for-elementor'),
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
                    '{{WRAPPER}} .icon-left,{{WRAPPER}} .icon-right' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
            'default' => '#6EC1E4',
                'selectors' => [
                    '{{WRAPPER}} .icon-left,{{WRAPPER}} .icon-right' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-left:hover,{{WRAPPER}} .icon-right:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_spacing',
            [
                'label' => __('Icon List Spacing', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'icon_btm_spacing',
            [
                'label' => __('Icon List Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .list-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'layout' => 'block',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_list_right_spacing',
            [
                'label' =>__('Icon List Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .list-text.inline' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'layout' => 'inline',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_btn_spacing_left',
            [
                'label' => __('Space between Icon & Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'icon_position' => 'left',
                ],
                
            ]
        );
        $this->add_responsive_control(
            'icon_btn_spacing_right',
            [
                'label' =>__('Space between Icon & Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'icon_position' => 'right',
                ],
                
            ]
        );
        $this->end_controls_section();
        
        

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Icon List Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
      

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-text' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_hvr_color',
            [
                'label' => __('Text Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .list-text:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .list-text',
                
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_content',
            [
                'label' =>__('Icon List Content', 'definitive-addons-for-elementor'),
                'tab'  => Controls_Manager::TAB_STYLE,
            ]
        );

        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'selector' => '{{WRAPPER}} .list-text',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->end_controls_section();

        
    }
    
	
	
	protected function render( ) {
        
		$icon_lists = $this->get_settings_for_display('icon_lists');
		if ( empty( $icon_lists ) ) {
            return;
        }
		
		$settings = $this->get_settings_for_display();
		$list_text = $this->get_settings_for_display('title');
		$icon_list_alignment = $this->get_settings_for_display('icon_list_alignment');
		$icon_position = $this->get_settings_for_display('icon_position');
		$layout = $this->get_settings_for_display('layout');
		
	
		
		$add_icon_left = '';
		$add_icon_right = '';
		
	
  
        ?>

       
		
	<div class="icon-list-container <?php echo esc_attr($icon_list_alignment) ?>">
      <?php
	  foreach ( $settings['icon_lists'] as $key => $icon_list) :
	  
	 
		if ($icon_position == 'left'){
			$add_icon_left = $icon_list['icon']['value'];
		}else {
			$add_icon_right = $icon_list['icon']['value'];
		} 
		
		if ( ! empty( $icon_list['link']['url'] ) ) {
			$this->add_link_attributes( 'icon_list_link'.$key, $icon_list['link'] );
		}
		
	  if ($icon_list['link']['url']){  ?>
			<a  <?php $this->print_render_attribute_string( 'icon_list_link'.$key ); ?>
				class="list-text <?php echo esc_attr($layout); ?>">
				<span  class="<?php echo esc_attr($add_icon_left); ?> icon-left"></span><?php echo Reuse::dafe_wp_kses($icon_list['title']);?>
				<span  class="<?php echo esc_attr($add_icon_right); ?> icon-right"></span>
			</a>
	<?php  } else {  ?>
			<span class="list-text <?php echo esc_attr($layout); ?>">
				<span class="<?php echo esc_attr($add_icon_left); ?> icon-left"></span>
				<?php echo esc_html($icon_list['title']); ?>
				<span class="<?php echo esc_attr($add_icon_right); ?> icon-right"></span>
			</span>  
	<?php   }  ?>
       
        <?php endforeach; ?>
	</div>
		
	

        <?php
    }
	
	
	protected function content_template() {
		
	}
}
