<?php
/**
 * Class for WC checkout block
 *
 * @package Terms_Conditions_Per_Product
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'TACPP4_Terms_Conditions_Checkout_Block' ) ) {

    /**
     * Class for TACPP4_Terms_Conditions_Checkout_Block.
     */
    class TACPP4_Terms_Conditions_Checkout_Block {

        protected static $instance = null;

        public static function get_instance() {
            null === self::$instance and self::$instance = new self;

            return self::$instance;
        }


        /**
         * Constructor for class.
         */
        public function __construct() {

            // Enqueue front-end scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 100 );

            // Add product specific Terms and Conditions to WC Checkout Block
            add_filter( "render_block_woocommerce/checkout-terms-block",
                array( $this, 'add_checkout_per_product_terms_on_block' ), 10, 3 );

        }

        /**
         * Enqueue style/script.
         *
         * @return void
         */
        public function enqueue_scripts() {
            // Bailout if the Gutenberg checkout block is not present
            if ( is_admin() || ! self::is_wc_checkout_block() ) {
                return;
            }
            wp_enqueue_style(
                'terms-checkout-style',
                TACPP4_PLUGIN_URL . 'assets/css/checkout.css',
                '',
                TACPP4_PLUGIN_VERSION
            );
            // Register plugin's JS script
            wp_register_script(
                'terms-checkout-action-script',
                TACPP4_PLUGIN_URL . 'assets/js/extensions/checkout/frontend.js',
                array(
                    'jquery',
                ),
                TACPP4_PLUGIN_VERSION,
                false
            );

            $not_checked_notice = apply_filters( 'tacpp_block_checkout_not_checked_notice',
                "You must accept all Products Terms and Conditions to continue with your purchase."
            );
            $not_checked_notice = esc_html__( $not_checked_notice,
                'terms-and-conditions-per-product' );
            wp_localize_script( 'terms-checkout-action-script',
                'tacppChBlock',
                array(
                    'notCheckedNotice' => $not_checked_notice
                )
            );

            wp_enqueue_script( 'terms-checkout-action-script' );

        }


        public function add_checkout_per_product_terms_on_block( $block_content, $block, $block_instance ) {
            if ( is_admin() || ! self::is_wc_checkout_block() ) {
                return $block_content;
            }

            $output_html = ''; // Initialize an empty string to store the HTML output

            ob_start();
            TACPP4_Terms_Conditions_Per_Product::add_checkout_per_product_terms();
            $output_html .= ob_get_clean();

            $admin_settings  = get_option( 'tacpp_admin_settings' );
            $terms_must_read = $admin_settings['terms_must_read'];

            $hide_wc_terms = isset( $admin_settings['hide_default_terms'] ) ? $admin_settings['hide_default_terms'] : 0;

            if ( $hide_wc_terms > 0 ) {
                $block_content = $output_html;
            } else {
                $block_content = $block_content . $output_html;
            }

            return $block_content;
        }

        /**
         * Function to check if the WC checkout block is present.
         *
         * @return bool   True if it is, false otherwise.
         */
        public static function is_wc_checkout_block() {
            // Get the ID of the current post
            $post_id    = get_the_ID();
            // Gutenberg checkout block name
            $block_name = 'woocommerce/checkout';


            // Get the post content.
            $post_content = get_post_field( 'post_content', $post_id );

            // Parse the content to extract block information.
            $blocks = parse_blocks( $post_content );

            // Loop through the blocks to check for the specified block name.
            foreach ( $blocks as $block ) {
                if ( isset( $block['blockName'] ) && $block['blockName'] === $block_name ) {
                    return true; // Block found
                }
            }

            return false; // Block not found
        }

    }

    new TACPP4_Terms_Conditions_Checkout_Block();
}
