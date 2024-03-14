<?php 

if( !class_exists('PVTFW_COMMON' )):

    class PVTFW_COMMON {


        /**
        *====================================================
        * On install selected columns
        *====================================================
        **/
        public static function get_default_columns(){
            return apply_filters( 'pvtfw_default_columns', array(
                    'image_link' => "on",
                    'sku' => "off",
                    'variation_description' => "off",
                    'attributes' => "on",
                    'dimensions_html' => "off",
                    'weight_html' => "off",
                    'availability_html' => "on",
                    'price_html' => "on",
                    'quantity' => "on",
                    'action' => "on",
                )
            );
        }

        /**
        *====================================================
        * Label name initialized
        *====================================================
        **/
        public static function get_columns_labels(){
            $default_column_lables = array(
                'image_link' => __('Thumbnail', 'product-variant-table-for-woocommerce'),
                'sku' => __('SKU', 'product-variant-table-for-woocommerce'),
                'variation_description' => __('Description', 'product-variant-table-for-woocommerce'),
                'attributes' => __('Attributes', 'product-variant-table-for-woocommerce'),
                'dimensions_html' => __('Dimensions', 'product-variant-table-for-woocommerce'),
                'weight_html' => __('Weight', 'product-variant-table-for-woocommerce'),
                'availability_html' => __('Stock', 'product-variant-table-for-woocommerce'),
                'price_html' => __('Price', 'product-variant-table-for-woocommerce'),
                'quantity' => __('Quantity', 'product-variant-table-for-woocommerce'),
                'action' => __('Action', 'product-variant-table-for-woocommerce'),
            );

            $columns_labels_filter = apply_filters( 'pvtfw_columns_labels', $default_column_lables );

            return $columns_labels_filter;
        }


        /**
		 * ====================================================
		 * Get Setings as an object
		 * ====================================================
		 */
		public static function pvtfw_get_options(){

			$options = array(
				// previously was place now table_place
				'table_place' 		=> get_option('pvtfw_variant_table_place', 'woocommerce_after_single_product_summary_9'),
        		'showTableHeader' 	=> get_option('pvtfw_variant_table_show_table_header', 'on'),
				'showAvailableOptionBtn' => get_option('pvtfw_variant_table_show_available_options_btn', 'on'),
				'available_btn_text' =>  get_option('pvtfw_variant_table_available_options_btn_text'),
                'available_title_text' =>  get_option('pvtfw_variant_table_show_available_options_text', 'on'),
				// previously was btn_text now cart_btn_text
				'cart_btn_text' 	=>  get_option('pvtfw_variant_table_cart_btn_text'),
				'qty_layout' 		=> get_option('pvtfw_variant_table_qty_layout', 'plus/minus'),
        		'showSubTotal'		=> get_option('pvtfw_variant_table_sub_total', ''),
                'scrollToTop'       => get_option('pvtfw_variant_table_scroll_to_top', 'on'),
        		'cartNotice' 		=> get_option('pvtfw_variant_table_cart_notice', 'on'),
        		'fullTable' 		=> get_option('pvtfw_variant_table_full_table', ''),
        		'scrollableTableX' 	=> get_option( 'pvtfw_variant_table_scrollable_x', '' ),
				'table_min_width' 	=>  absint( get_option( 'pvtfw_variant_table_min_width', '1000' ) ),
        		'curTab' 			=> get_option('pvtfw_variant_table_tab', '')
			);

			$pvt_option = apply_filters('all_pvt_options', $options);

			return (object)$pvt_option;

		}

        /**
		 * ====================================================
		 * Table container class
		 * ====================================================
		 */

        public static function container( $data ){

            if( empty($data) ){
                return;
            } 
            else{
                $classes = implode(' ', $data);
                return $classes;
            }
            
        }


        /**
		 * ====================================================
		 * Admin setting badge
		 * ====================================================
		 */

        public static function badge( $text = "", $return = "" ){

            if ( "" == $text ){
                $badge = __('New', 'product-variant-table-for-woocommerce');
            }
            else{
                $badge = $text;
            }

            if ( "" == $return ){
                echo sprintf('
                    <span class="pvtfw-new-feature-tick">%s</span>',
                    _x( esc_html($badge), 'Plugin Setting: Badge Text', 'product-variant-table-for-woocommerce' )
                );
            }
            else{
                if( ( ! PVTFW_TABLE::is_pvtfw_pro_Active() ) ){
                    return sprintf('
                        <span class="%s" style="%s"></span>',
                        "dashicons dashicons-lock",
                        "color: #9e0303; font-size: 16px; margin-top: 4px; margin-right: 2px;"
                    );
                }
            }
        }

        /**
         * ====================================================
         * Options
         * ====================================================
         */
        public static function plugin_options(){
            $options_array = array(
                'pvtfw_variant_table_place',
                'pvtfw_variant_table_columns',
                'pvtfw_variant_table_show_available_options_btn',
                'pvtfw_variant_table_available_options_btn_text',
                'pvtfw_variant_table_cart_btn_text',
                'pvtfw_variant_table_qty_layout',
                'pvtfw_variant_table_sub_total',
                'pvtfw_variant_table_scroll_to_top',
                'pvtfw_variant_table_cart_notice',
                'pvtfw_variant_table_show_table_header',
                'pvtfw_variant_table_full_table',
                'pvtfw_variant_table_scrollable_x',
                'pvtfw_variant_table_min_width',
                'pvtfw_variant_table_tab',
            );
            return $options_array;
        }

        /**
		 * ====================================================
		 * Check plugin in installation list
		 * ====================================================
		 */

        public static function check_plugin_state( $plugin_name ){

            include_once ABSPATH . 'wp-admin/includes/plugin.php';

            if (is_plugin_active( $plugin_name.'/'.$plugin_name.'.php' ) ){

                return true;

            }
            else{

                return false;

            }

        }

        /**
         * ====================================================
         * Function to check the price 
         * and availability of variation
         * @since 1.4.15
         * @return array
         * ====================================================
         */
        public static function check_price_availability( $single_variation ){

            $args = array();

            if ( 'yes' === get_option( 'woocommerce_calc_taxes' ) && 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {

                $args['price'] = wc_get_price_including_tax( $single_variation );

            }

            else{

                $args['price'] = $single_variation->get_price();

            }

            $args['variation_availability'] = $single_variation->is_in_stock() ? 'yes' : 'no';

            return $args;

        }
        
        

    }

endif;