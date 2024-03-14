<?php

/**
 * Class: LaStudioKit_Woo_Add_To_Cart
 * Name: Add To Cart
 * Slug: lakit-addtocart
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Cart Widget
 */
class LaStudioKit_Woo_Add_To_Cart extends Widget_Button {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        $this->enqueue_addon_resources();
    }

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    $this->add_style_depends( 'lastudio-kit-woocommerce' );
	    }
    }

    public function get_name() {
        return 'lakit-addtocart';
    }

    public function get_categories() {
        return [ 'lastudiokit-woocommerce' ];
    }

    public function get_title() {
        return 'LaStudioKit ' . esc_html__( 'Custom Add To Cart', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-woocommerce';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' ];
    }

    public function on_export( $element ) {
        unset( $element['settings']['product_id'] );

        return $element;
    }

    public function unescape_html( $safe_text, $text ) {
        return $text;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_product',
            [
                'label' => esc_html__( 'Product', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' =>  esc_html__( 'Product', 'lastudio-kit' ),
                'type' => 'lastudiokit-query',
                'options' => [],
                'label_block' => true,
                'autocomplete' => [
                    'object' => 'post',
                    'query' => [
                        'post_type' => [ 'product' ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'show_quantity',
            [
                'label' =>  esc_html__( 'Show Quantity', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' =>  esc_html__( 'Hide', 'lastudio-kit' ),
                'label_on' =>  esc_html__( 'Show', 'lastudio-kit' ),
                'description' =>  esc_html__( 'Please note that switching on this option will disable some of the design controls.', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'quantity',
            [
                'label' =>  esc_html__( 'Quantity', 'lastudio-kit' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'show_quantity' => '',
                ],
            ]
        );

        $this->end_controls_section();

        parent::register_controls();

        $this->update_control(
            'link',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->update_control(
            'text',
            [
                'default' =>  esc_html__( 'Add to Cart', 'lastudio-kit' ),
                'placeholder' =>  esc_html__( 'Add to Cart', 'lastudio-kit' ),
            ]
        );

        $this->update_control(
            'selected_icon',
            [
                'default' => [
                    'value' => 'lastudioicon-shopping-cart-1',
                    'library' => 'lastudioicon',
                ],
            ]
        );

        $this->update_control(
            'size',
            [
                'condition' => [
                    'show_quantity' => '',
                ],
            ]
        );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['product_id'] ) ) {
            $product_id = $settings['product_id'];
        }
        elseif ( wp_doing_ajax() ) {
            $product_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
        }
        else {
            $product_id = get_queried_object_id();
        }

        global $product;
        $product = wc_get_product( $product_id );

        if ( 'yes' === $settings['show_quantity'] ) {
            $this->render_form_button( $product );
        }
        else {
            $this->render_ajax_button( $product );
        }
    }

    /**
     * @param \WC_Product $product
     */
    private function render_ajax_button( $product ) {
        $settings = $this->get_settings_for_display();

        if ( $product ) {
            $product_type = $product->get_type();
            $class = implode( ' ', array_filter( [
                'product_type_' . $product_type,
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button la-addcart' : '',
                $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
            ] ) );

            $this->add_render_attribute( 'button',
                [
                    'rel' => 'nofollow',
                    'href' => $product->add_to_cart_url(),
                    'data-quantity' => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
                    'data-product_id' => $product->get_id(),
                    'class' => $class,
                ]
            );

        } elseif ( current_user_can( 'manage_options' ) ) {
            $settings['text'] =  esc_html__( 'Please set a valid product', 'lastudio-kit' );
            $this->set_settings( $settings );
        }

        parent::render();
    }

    /**
     * @param \WC_Product $product
     */
    private function render_form_button( $product ) {

        if ( !$product ) {
            if(current_user_can( 'manage_options' )){
                echo  esc_html__( 'Please set a valid product', 'lastudio-kit' );
            }
            return;
        }

        $text_callback = function() {
            ob_start();
            $this->render_text();

            return ob_get_clean();
        };

        add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
        add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
        add_filter( 'esc_html', [ $this, 'unescape_html' ], 10, 2 );

        ob_start();
        echo '<div class="woocommerce">';
        echo '<div class="' . esc_attr( implode( ' ', wc_get_product_class( '', $product ) ) ) . '">';
	    echo '<div class="elementor-add-to-cart elementor-product-' . $product->get_type() . '">';
        woocommerce_template_single_add_to_cart();
        echo '</div>';
        echo '</div>';
        echo '</div>';
        $form = ob_get_clean();
        $form = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button elementor-button', $form );
        echo $form;

        remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
        remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
        remove_filter( 'esc_html', [ $this, 'unescape_html' ] );
    }
    
    protected function content_template() {}

}