<?php
/**
 * WooCommerce UPC Admin
 * @since       0.1.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'Woo_GTIN_Admin' ) ) {

    /**
     * Woo_GTIN_Admin class
     *
     * @since       0.2.0
     */
    class Woo_GTIN_Admin {

        /**
         * @var         Woo_GTIN_Admin $instance The one true Woo_GTIN_Admin
         * @since       0.2.0
         */
        private static $instance;
        public static $errorpath = '../php-error-log.php';
        public static $active = array();
        // sample: error_log("meta: " . $meta . "\r\n",3,self::$errorpath);

        /**
         * Get active instance
         *
         * @access      public
         * @since       0.2.0
         * @return      object self::$instance The one true Woo_GTIN_Admin
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new Woo_GTIN_Admin();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       0.2.0
         * @return      void
         */
        private function hooks() {

            add_filter( 'woocommerce_get_settings_products', array( $this,  'gtin_settings' ), 10, 2 );

            add_action( 'woocommerce_product_options_inventory_product_data', array( $this, 'product_tn_field' ) );
            add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_tn' ) );

            add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'variation_tn_field' ), 10, 3 );
            add_action( 'woocommerce_save_product_variation', array( $this, 'save_variations' ), 10, 2 );

        }

        /**
         * Add UPC Field
         *
         * @since       0.1.0
         * @return      void
         */
        public function product_tn_field() {

            global $post;

            $option_text = get_option( 'hwp_gtin_text' );

            $label = ( !empty( $option_text ) ? $option_text : 'GTIN' );

            //add GTIN field for variations
            woocommerce_wp_text_input( 
                array(    
                 'id' => 'hwp_product_gtin',
                 'label' => $label,
                 'desc_tip' => 'true',
                 'description' => 'Enter the Global Trade Item Number (UPC, EAN, ISBN)',
                 'value'       => get_post_meta( $post->ID, 'hwp_product_gtin', true ),
                )
            );

        }

        /**
         * Add GTIN Field for variations
         *
         * @since       0.1.0
         * @return      void
         */
        public function variation_tn_field( $loop, $variation_data, $variation ) {

            $option_text = get_option( 'hwp_gtin_text' );

            $label = ( !empty( $option_text ) ? $option_text : 'GTIN' );

            //add GTIN field for variations
            woocommerce_wp_text_input( 
                array(    
                 'id' => 'hwp_var_gtin[' . $variation->ID . ']',
                 'label' => $label,
                 'desc_tip' => 'true',
                 'description' => 'Unique GTIN for variation? Enter it here.',
                 'value'       => get_post_meta( $variation->ID, 'hwp_var_gtin', true ),
                )
            );

        }

        /**
         * Save variation settings
         *
         * @since       0.1.0
         * @return      void
         */
        public function save_variations( $post_id ) {

           $tn_post = $_POST['hwp_var_gtin'][ $post_id ];

           // save
           if( isset( $tn_post ) ) {
              update_post_meta( $post_id, 'hwp_var_gtin', esc_attr( $tn_post ) );
           }

           // remove if meta is empty
           $tn_meta = get_post_meta( $post_id,'hwp_var_gtin', true );

           if ( empty( $tn_meta ) ) {
              delete_post_meta( $post_id, 'hwp_var_gtin', '' );
           }

        }

        /**
         * Save simple product GTIN settings
         *
         * @since       0.1.0
         * @return      void
         */
        public function save_product_tn( $post_id ) {

            $gtin_post = $_POST['hwp_product_gtin'];

            // save the gtin
            if( isset( $gtin_post ) ) {
                update_post_meta( $post_id, 'hwp_product_gtin', esc_attr( $gtin_post ) );
            }

            // remove if GTIN meta is empty
            $gtin_meta = get_post_meta( $post_id, 'hwp_product_gtin', true );

            if( empty( $gtin_meta ) ) {
                delete_post_meta( $post_id, 'hwp_product_gtin', '' );
            }

        }

        /**
         * Add settings
         *
         * @access      public
         * @since       0.1
         */
        public function gtin_settings( $settings, $current_section ) {

            /**
             * Check the current section is what we want
             **/
            if ( $current_section == 'inventory' ) {
                // Add Title to the Settings
                $settings[] = array( 'name' => __( 'GTIN Settings', 'woo-add-gtin' ), 'type' => 'title', 'desc' => __( 'The following options are used for the WooCommerce GTIN plugin.', 'woo-add-gtin' ), 'id' => 'woo-add-gtin' );
                // Add first checkbox option
                $settings[] = array(
                    'name'     => __( 'Hide GTIN on single product pages?', 'woo-add-gtin' ),
                    //'desc_tip' => __( 'This will output the GTIN on your product pages.', 'woo-add-gtin' ),
                    'id'       => 'hwp_display_gtin',
                    'type'     => 'checkbox',
                    'css'      => 'min-width:300px;',
                );
                
                $settings[] = array( 'type' => 'sectionend', 'id' => 'woo-add-gtin' );

                $settings[] = array(
                    'name'     => __( 'Change GTIN Label', 'woo-add-gtin' ),
                    'desc_tip' => __( 'Enter the label you\'d like to use instead of GTIN.', 'woo-add-gtin' ),
                    'id'       => 'hwp_gtin_text',
                    'type'     => 'text',
                    'placeholder' => 'GTIN',
                );
                
                $settings[] = array( 'type' => 'sectionend', 'id' => 'hwp_gtin_text' );

                return $settings;
            
            /**
             * If not, return the standard settings
             **/
            } else {
                return $settings;
            }

        }

    }

    $Woo_GTIN_Admin = new Woo_GTIN_Admin();
    $Woo_GTIN_Admin->instance();

} // end class_exists check