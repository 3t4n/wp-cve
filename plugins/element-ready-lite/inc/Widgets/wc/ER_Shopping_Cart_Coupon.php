<?php

namespace Element_Ready\Widgets\wc;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;

require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/box/box_style.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/content_controls/common.php' );

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ER_Shopping_Cart_Coupon extends Widget_Base {

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;

	public function get_name() {
		return 'element_ready--wooCommerce--cart-coupon';
	}

	public function get_title() {
		return esc_html__( 'ER Woo Cart Coupon', 'element-ready-lite' );
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return array('element-ready-addons');
	}

    public function get_keywords() {
        return [ 'woocommerce', 'cart', 'coupon' ,'form'];
    }

	protected function register_controls() {

		/******************************
		 * 	CONTENT SECTION
		 ******************************/
		$this->start_controls_section(
			'cart_coupon_content_section',
			[
				'label' => esc_html__( 'Content', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'show_coupon_heading',
            [
                'label'        => esc_html__( 'Coupon heading?', 'element-ready-lite' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'yes', 'element-ready-lite' ),
                'label_off'    => esc_html__( 'yes', 'element-ready-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'coupon_heading_text',
            [
                'label'       => esc_html__( 'Title', 'element-ready-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Coupon', 'element-ready-lite' ),
                'default' => esc_html__( 'Coupon', 'element-ready-lite' ),
            ]
        );

        $this->add_control(
            'coupon_input_placeholder',
            [
                'label'       => esc_html__( 'Input Placeholder', 'element-ready-lite' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Coupon Code', 'element-ready-lite' ),
                'default' => esc_html__( 'Coupon Code', 'element-ready-lite' ),
            ]
        );
	
		$this->end_controls_section();
		/*----------------------------
			BUTTON WRAP STYLE END
		-----------------------------*/	

        $this->box_layout_css(
            array(
                'title' => esc_html__('Main Wrapper','element-ready-lite'),
                'slug' => '_erwrapper_body_box_style_coupon_wr',
                'element_name' => 'wrapper_body_element_ready_coupon',
                'selector' => '{{WRAPPER}} .element-ready--coupon-row-wrapper',
               
               
            )
        );
        
        $this->box_minimum_css(
            array(
                'title' => esc_html__('Input Wrapper','element-ready-lite'),
                'slug' => 'element_body_box_style_coupon_wr_input_wrapper',
                'element_name' => 'er_body_element_ready_coupon_input_wrapper',
                'selector' => '{{WRAPPER}} .element-ready--coupon-row-wrapper .form-row-first',
               
               
            )
        ); 
        
        $this->text_css(
            array(
                'title' => esc_html__('Input','element-ready-lite'),
                'slug' => 'shop_ready_body_box_style_coupon_wr_input',
                'element_name' => 'shop_ready_body_element_ready_coupon_input',
                'selector' => '{{WRAPPER}} .element-ready--coupon-row-wrapper .form-row-first input',
               
               
            )
        );  
        
        $this->box_layout_css(
            array(
                'title' => esc_html__('Button Wrapper','element-ready-lite'),
                'slug' => 'element__body_box_style_coupon_wr_btn',
                'element_name' => 'wrapper_body_element_ready_coupon_btn',
                'selector' => '{{WRAPPER}} .element-ready--coupon-row-wrapper .form-row-last',
               
               
            )
        ); 
        
        $this->text_minimum_css(
            array(
                'title' => esc_html__('Button','element-ready-lite'),
                'slug' => 'wrapper_body_box_style_coupon_wr_btn__',
                'element_name' => 'wrapper_body_element_ready_coupon___btn',
                'selector' => '{{WRAPPER}} .element-ready--coupon-row-wrapper .form-row-last button',
               
               
            )
        );

        
	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();
        if(!class_exists( 'woocommerce' )){
            return;
        }
		?>

        <?php if (function_exists('wc_notice_count') && wc_notice_count() > 0): ?>
            <div class="er-woocommerce-notices woocommerce">
                <?php wc_print_notices(); ?>
            </div>
        <?php endif; ?>
        <form class="checkout_coupon woocommerce-form-coupon" method="post">
            <div class="element-ready--coupon-row-wrapper">
                <?php if($settings['show_coupon_heading'] == 'yes'): ?>
                    <p class="element-coupon-heading-col"><?php echo esc_html( $settings['coupon_heading_text'] ); ?></p>
                <?php endif; ?> 
                <p class="form-row form-row-first woo-ready-coupon-col">
                    <input type="text" name="coupon_code" class="input-text" placeholder="<?php echo esc_attr($settings['coupon_input_placeholder']); ?>" id="coupon_code" value="" />
                </p>
                <p class="form-row form-row-last wready-coup-btn-col">
                    <button type="submit" class="button main-btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'element-ready-lite' ); ?>"><?php esc_html_e( 'Apply coupon', 'element-ready-lite' ); ?></button>
                </p>
            </div>
        </form>
        <?php
		
	}
}
