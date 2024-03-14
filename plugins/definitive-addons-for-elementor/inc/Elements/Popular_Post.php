<?php
/**
 * Popular Post
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
 * Popular Post
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Popular_Post extends Widget_Base
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
        return 'dafe_popular_post';
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
        return __('DA: Popular Post', 'definitive-addons-for-elementor');
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
        return 'eicon-columns';
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
        return [ 'popular', 'post', 'grid','blog' ];
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
            'post_grid_label',
            [
            'label' =>__('Popular Post', 'definitive-addons-for-elementor')
            ]
        );

        
        $this->add_control(
            'category_selection',
            [
                'label' =>__('Post Categories', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => Reuse::dafe_post_categories(),
            ]
        );
        

        $this->end_controls_section();

        // style
        
        $this->start_controls_section(
            'blog_section_style_title',
            [
                'label' =>__('Blog Post Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .blog-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blog-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blog-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .blog-title',
                
            ]
        );

          $this->end_controls_section();
       

    }



	protected function render( ) {
		
    $settings = $this->get_settings_for_display(); 
	$popular_post_number = 4;
	$category_selection = $this->get_settings_for_display('category_selection');
	Da_Post::dafe_get_popular_post_template($category_selection, $popular_post_number);
 
	}
}