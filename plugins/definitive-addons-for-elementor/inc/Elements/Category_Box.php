<?php
/**
 * Product Category Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Product Category Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Product_Category_Box extends Widget_Base
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
        return __('DA: Product Category Box', 'definitive-addons-for-elementor');
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
        return 'dafe_product_category_box';
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
        return 'eicon-product-categories';
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
        return [ 'category', 'product', 'post','box' ];
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
            'section_category_box',
            [
                'label' => __('Category Box', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

  
        
        $this->add_control(
            'pcat_selection',
            [
                'label' =>__('Select Product Category', 'definitive-addons-for-elementor'),
            'label_block' => true,
                'type' => Controls_Manager::SELECT2,
               
                'options' =>Reuse::dafe_product_categories_lists(),
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
            'ovl_background',
            [
                'label' => __('Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat-box-title a,{{WRAPPER}} .product-category-box-text' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'ovl_hvr_background',
            [
                'label' => __('Background Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-category-box-text:hover,{{WRAPPER}} .product-category-box-text:hover .product-cat-box-title a' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dafe_cat_ovl_paddings',
            [
                'label' => __('Overlay Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product-category-box-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Overlay Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_icon_box_shadow',

            'selector' => '{{WRAPPER}} .dafe-icon-box-entry',
            ]
        );
        
        


        $this->end_controls_section();
        
        // Overlay Title style
        
        $this->start_controls_section(
            'overlay_section_title_style',
            [
                'label' => __('Title Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat-box-title a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat-box-title a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_fonts',
                'selector' => '{{WRAPPER}} .product-cat-box-title a',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'dafe_title_text_shadow',

            'selector' => '{{WRAPPER}} .product-cat-box-title a',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [    
                
            'name' => 'ovl_title_stroke',
            'selector' => '{{WRAPPER}} .product-cat-box-title a',
            ]
        );
        
    
        $this->end_controls_section();
        
        // Overlay Count style
        
        $this->start_controls_section(
            'overlay_section_count_style',
            [
                'label' => __('Count Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'count_color',
            [
                'label' => __('Count Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .box-product-count a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'count_hover_color',
            [
                'label' => __('Count Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .box-product-count a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_fonts',
                'selector' => '{{WRAPPER}} .box-product-count',
                
            ]
        );

    
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                
            'name'     => 'dafe_count_text_shadow',

            'selector' => '{{WRAPPER}} .box-product-count',
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [    
                
            'name' => 'ovl_count_stroke',
            'selector' => '{{WRAPPER}} .box-product-count',
            ]
        );
        $this->end_controls_section();
        // Image style
        
        $this->start_controls_section(
            'overlay_section_image_style',
            [
                'label' => __('Image Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [    
                
                'name' => 'cat_image_border',
                'selector' => '{{WRAPPER}} .product-category-box img',
            ]
        );
       
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'name'     => 'cat_image_shadow',

            'selector' => '{{WRAPPER}} .product-category-box img',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                
            'name' => 'css_filters',
            'selector' => '{{WRAPPER}} .product-category-box img',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
            'label' => __('Hover CSS Filter', 'definitive-addons-for-elementor'),
            'name' => 'css_hvr_filters',
            'selector' => '{{WRAPPER}} .product-category-box img:hover',
            ]
        );
        
        $this->end_controls_section();
        
        //Container style
        
        $this->start_controls_section(
            'category_box_container_style',
            [
                'label' => __('Container Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'dafe_container_paddings',
            [
                'label' => __('Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .product-category-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_container_colors',
            [
            'label' =>__('Container Colors', 'definitive-addons-for-elementor'),
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
            'label' =>__('Background Color', 'definitive-addons-for-elementor'),
            'type'  => Controls_Manager::COLOR,
            'default' => '#eeeeee',
            'selectors' => [
                    '{{WRAPPER}} .product-category-box' => 'background-color: {{VALUE}}',
                ],
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [    
                
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .product-category-box',
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
            'label'          => __('Background Color', 'definitive-addons-for-elementor'),
            'type'           => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .product-category-box:hover' => 'background-color: {{VALUE}}',
                    
            ],
                
            ]
        );

        $this->add_control(
            'container_border_hvr_color',
            [
            'label'     => __('Border Color', 'definitive-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                    '{{WRAPPER}} .product-category-box:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        
    }


	protected function render( ) {
		
        $settings = $this->get_settings_for_display();
		
		$category_data ='';
		$product1_image_url = '';
		
        $category_data = $this->get_settings_for_display('pcat_selection');
		if (empty($category_data)){return;}
		
							$cat_link = get_category_link( $category_data );
							$cat_name = get_term( $category_data );
							$thumb_id = get_term_meta( $category_data, 'thumbnail_id', true );
							
							$product_image_url = wp_get_attachment_image_src( $thumb_id, 'product-category-image' );
							
							if ($product_image_url){
							
								$product1_image_url = $product_image_url[0]; 
							
							}else {
								
								$product1_image_url = DAFE_URI . '/css/dummy-image.jpg';
							}
							
							if (!empty($cat_name)){
								$cats_name = $cat_name->name;
							} ?>
							
							<div class="product-category-box">
								<?php if ( $product1_image_url ) { ?>
								
								
									<a  class="product-category-box-link" href="<?php echo esc_url( $cat_link ); ?>">
									<img  src="<?php echo esc_url( $product1_image_url ); ?>" alt="<?php echo esc_attr( $cats_name ); ?>" />
									
									</a>
								
								<?php } ?>
								<div class="product-category-box-text">
									
									<h5 class="product-cat-box-title"><a title="<?php echo esc_attr($cats_name); ?>" 
									href="<?php echo esc_url( $cat_link ); ?>">
									<?php echo esc_html( $cats_name ); ?></a></h5>
									
									<div class="box-product-count">
									<a href="<?php echo esc_url( $cat_link ); ?>">
									<?php 
									if (!empty($cat_name)){
									$number_of_prod = $cat_name->count;
									}
									if ($number_of_prod) { 
									echo absint($number_of_prod); 
									} ?> 
									<?php esc_html_e('Products','definitive-addons-for-elementor'); ?></a>
									</div>
								</div>
							</div> 
							
						<?php 
	
}
}