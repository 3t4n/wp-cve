<?php

if( !class_exists('PVTFW_AVAILABE_BTN' )):

    class PVTFW_AVAILABE_BTN {

        protected static $_instance = null;

        public function __construct(){
            $this->register();
        }


        public static function instance() {
            if( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
        *====================================================
        * Available option button block
        *====================================================
        **/
        function available_options_btn()
        {
            // Default is `false` to apply table markup and feature
            if( apply_filters( 'disable_pvt_to_apply', false ) || apply_filters( 'disable_pvt_to_show_available_option', false ) ){
                return;
            }

            global $product;
            if (!$product->is_type('variable')) {
                return;
            }

            // Get Available variations?
            $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

            $available_variations = $get_variations ? $product->get_available_variations() : false;

            // Don't do anything if variable product has an issue with setup like- price is missing
            // Just display a message as WooCommerce does.
            if ( empty( $available_variations ) && false !== $available_variations ){ ?>
                <p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
                <?php
                return;
            }


            ?>
        <div class="available-options-btn">

            <?php
                //Getting Available Button Text
                $available_btn_text =  PVTFW_COMMON::pvtfw_get_options()->available_btn_text;
                /**
                 * { Display dynamic text (if inserted by user) or hard coded text for Available Options button text }
                 * 
                 * @hook       `pvtfw_available_options_btn_text`
                 *
                 * @var        callable
                 * 
                 * @since      1.4.18
                 */
                $available_text = apply_filters( 'pvtfw_available_options_btn_text', 
                    !$available_btn_text ? __('Available options', 'product-variant-table-for-woocommerce') : $available_btn_text
                );

            ?>
            <button scrollto="#variant-table" type="button"
                class="available-options-btn single_add_to_cart_button button alt"><?php echo esc_html__( $available_text, 'product-variant-table-for-woocommerce' ); ?></button>
        </div>
        <?php
        }

        /**
        *====================================================
        * Register
        *====================================================
        **/

        function register() {
            $showAvailableOptionBtn = PVTFW_COMMON::pvtfw_get_options()->showAvailableOptionBtn;
            if ($showAvailableOptionBtn == 'on') {
                add_action('woocommerce_single_product_summary', array($this, 'available_options_btn'), 11);
            }
        }
        
    }

    $pvtfw_available_btn = PVTFW_AVAILABE_BTN::instance();

endif;