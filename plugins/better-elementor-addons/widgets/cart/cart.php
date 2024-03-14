<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Better_Cart extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-header-cart';
	}
	
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Header Cart', 'better-el-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Cart Settings', 'better-el-addons' ),
			]
		);
		
		 

        $this->add_control(
            'cart_icons',
            [
                'label' => esc_html__('Select Icon', 'better-el-addons'),
                'fa4compatibility' => 'better_search_icon',
				'default' => [
					'value' => 'fa fa-shopping-cart',
					'library' => 'fa-solid',
				],
                'label_block' => true,
                'type' => Controls_Manager::ICONS,

            ]
        );
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_icon',
			[
				'label' => __( 'Content Background', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#eee',
				'selectors' => [
					'{{WRAPPER}} .better-header-cart-icon a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-header-cart-icon a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() { 
	    $settings = $this->get_settings();
	    
	    $count = 0;
	    if ( class_exists( 'WooCommerce' ) ) {
	        // Avoiding direct function declaration inside another function
	        if (!function_exists('wc_get_cart_url')) {
	            function wc_get_cart_url() {
	                return apply_filters( 'woocommerce_get_cart_url', wc_get_page_permalink( 'cart' ) );
	            }
	        }
	        if( is_cart() && WC()->cart->cart_contents_count != 0){
	            $count = WC()->cart->cart_contents_count;
	        }
	        // Sanitize the cart link
	        $cart_link = esc_url( wc_get_cart_url() );
	    }
	    ?>
	    <div class="better-header-cart-icon hidden-xs hidden-sm">
	        <a class="<?php echo esc_attr( $settings['cart_icons']['value'] ); ?> 3" href="<?php echo esc_url( $cart_link ); ?>" title="<?php echo esc_attr__( 'View your shopping cart', 'better-el-addons' ); ?>">
	            <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
	        </a>
	    </div>
	<?php 
	}


	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


