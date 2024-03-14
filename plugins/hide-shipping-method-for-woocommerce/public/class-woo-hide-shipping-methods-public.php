<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/public
 * @author     theDotstore <wordpress@multidots.in>
 */
class Woo_Hide_Shipping_Methods_Public
{
    private static  $admin_object = null ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     *
     * @uses     Woo_Hide_Shipping_Methods_Admin
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        self::$admin_object = new Woo_Hide_Shipping_Methods_Admin( $plugin_name, $version );
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function whsmp_enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woo_Hide_Shipping_Methods_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woo_Hide_Shipping_Methods_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ( function_exists( 'is_cart' ) && is_cart() || function_exists( 'is_checkout' ) && is_checkout() ) {
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/woo-hide-shipping-methods-public.css',
                array(),
                $this->version,
                'all'
            );
        }
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function whsmp_enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woo_Hide_Shipping_Methods_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woo_Hide_Shipping_Methods_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ( function_exists( 'is_cart' ) && is_cart() || function_exists( 'is_checkout' ) && is_checkout() ) {
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/woo-hide-shipping-methods-public.js',
                array( 'jquery' ),
                $this->version,
                false
            );
        }
    }
    
    /**
     * Match condition based on shipping list
     *
     * @param array $sm_post_data
     *
     * @return bool True if $final_condition_flag is 1, false otherwise. if $sm_status is off then also return false.
     * @since    1.0.0
     *
     * @uses     Woo_Hide_Shipping_Methods_Admin::whsma_get_default_language_with_sitepress()
     * @uses     whsma_get_woo_version_number()
     * @uses     WC_Cart::get_cart()
     * @uses     whsma_fee_array_column_public()
     * @uses     whsma_match_product_per_qty()
     * @uses     whsma_match_product_per_weight()
     * @uses     whsma_match_product_per_subtotal()
     * @uses     whsma_match_category_per_qty()
     * @uses     whsma_match_category_per_weight()
     * @uses     whsma_match_category_per_subtotal()
     * @uses     whsma_match_total_cart_qty()
     * @uses     whsma_match_total_cart_weight()
     * @uses     whsma_match_total_cart_subtotal()
     * @uses     whsma_match_country_rules()
     * @uses     whsma_match_state_rules()
     * @uses     whsma_match_postcode_rules()
     * @uses     whsma_match_zone_rules()
     * @uses     whsma_match_variable_products_rule()
     * @uses     whsma_match_simple_products_rule()
     * @uses     whsma_match_category_rule()
     * @uses     whsma_match_tag_rule()
     * @uses     whsma_match_sku_rule()
     * @uses     whsma_match_user_rule()
     * @uses     whsma_match_user_role_rule()
     * @uses     whsma_match_coupon_rule()
     * @uses     whsma_match_cart_subtotal_before_discount_rule()
     * @uses     whsma_match_cart_subtotal_after_discount_rule()
     * @uses     whsma_match_cart_total_cart_qty_rule()
     * @uses     whsma_match_cart_total_weight_rule()
     * @uses     whsma_match_shipping_class_rule()
     *
     */
    public function whsma_condition_match_rules( $sm_post_data = array() )
    {
        global  $sitepress ;
        $default_lang = self::$admin_object->whsma_get_default_language_with_sitepress();
        $wc_curr_version = $this->whsma_get_woo_version_number();
        $is_passed = array();
        $final_is_passed_general_rule = array();
        $new_is_passed = array();
        $final_passed = array();
        $final_condition_flag = array();
        $cart_array = WC()->cart->get_cart();
        $cart_product_ids_array = array();
        $cart_product = $this->whsma_fee_array_column_public( $cart_array, 'product_id' );
        if ( isset( $cart_product ) && !empty($cart_product) ) {
            foreach ( $cart_product as $cart_product_id ) {
                $product_cart_array = new WC_Product( $cart_product_id );
                if ( !$product_cart_array->is_virtual( 'yes' ) ) {
                    if ( $product_cart_array->is_type( 'simple' ) ) {
                        
                        if ( !empty($sitepress) ) {
                            $cart_product_ids_array[] = apply_filters(
                                'wpml_object_id',
                                $cart_product_id,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $cart_product_ids_array[] = $cart_product_id;
                        }
                    
                    }
                }
            }
        }
        $cart_variation_ids_array = array();
        $variation_cart_product = $this->whsma_fee_array_column_public( $cart_array, 'variation_id' );
        if ( isset( $variation_cart_product ) && !empty($variation_cart_product) ) {
            foreach ( $variation_cart_product as $cart_variation_id ) {
                
                if ( 0 !== $cart_variation_id ) {
                    $product_cart_array = wc_get_product( $cart_variation_id );
                    if ( !$product_cart_array->is_virtual( 'yes' ) ) {
                        
                        if ( !empty($sitepress) ) {
                            $cart_variation_ids_array[] = apply_filters(
                                'wpml_object_id',
                                $cart_variation_id,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $cart_variation_ids_array[] = $cart_variation_id;
                        }
                    
                    }
                }
            
            }
        }
        $variation_cart_products_array = array();
        if ( whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code() ) {
            $variation_cart_products_array = $this->whsma_get_var_name__premium_only( $sitepress, $default_lang );
        }
        $sm_status = get_post_status( $sm_post_data );
        if ( isset( $sm_status ) && 'publish' !== $sm_status ) {
            return false;
        }
        $main_rule_condition = get_post_meta( $sm_post_data, 'main_rule_condition', true );
        $cost_rule_match = get_post_meta( $sm_post_data, 'cost_rule_match', true );
        
        if ( !empty($cost_rule_match) ) {
            
            if ( is_serialized( $cost_rule_match ) ) {
                $cost_rule_match = maybe_unserialize( $cost_rule_match );
            } else {
                $cost_rule_match = $cost_rule_match;
            }
            
            
            if ( array_key_exists( 'general_rule_match', $cost_rule_match ) ) {
                $general_rule_match = $cost_rule_match['general_rule_match'];
            } else {
                $general_rule_match = 'any';
            }
        
        } else {
            $general_rule_match = 'any';
        }
        
        $get_condition_array = get_post_meta( $sm_post_data, 'sm_metabox', true );
        
        if ( !empty($get_condition_array) ) {
            $country_array = array();
            $product_array = array();
            $category_array = array();
            $tag_array = array();
            $user_array = array();
            $cart_total_array = array();
            $quantity_array = array();
            foreach ( $get_condition_array as $key => $value ) {
                if ( array_search( 'country', $value, true ) ) {
                    $country_array[$key] = $value;
                }
                if ( array_search( 'product', $value, true ) ) {
                    $product_array[$key] = $value;
                }
                if ( array_search( 'category', $value, true ) ) {
                    $category_array[$key] = $value;
                }
                if ( array_search( 'tag', $value, true ) ) {
                    $tag_array[$key] = $value;
                }
                if ( array_search( 'user', $value, true ) ) {
                    $user_array[$key] = $value;
                }
                if ( array_search( 'cart_total', $value, true ) ) {
                    $cart_total_array[$key] = $value;
                }
                if ( array_search( 'quantity', $value, true ) ) {
                    $quantity_array[$key] = $value;
                }
                //Check if is country exist
                
                if ( is_array( $country_array ) && isset( $country_array ) && !empty($country_array) && !empty($cart_product_ids_array) ) {
                    $country_passed = $this->whsma_match_country_rules( $country_array, $general_rule_match );
                    
                    if ( 'yes' === $country_passed ) {
                        $is_passed['has_fee_based_on_country'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_country'] = 'no';
                    }
                
                }
                
                //Check if is product exist
                
                if ( is_array( $product_array ) && isset( $product_array ) && !empty($product_array) && !empty($cart_product_ids_array) ) {
                    $product_passed = $this->whsma_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match );
                    
                    if ( 'yes' === $product_passed ) {
                        $is_passed['has_fee_based_on_product'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_product'] = 'no';
                    }
                
                }
                
                //Check if is Category exist
                
                if ( is_array( $category_array ) && isset( $category_array ) && !empty($category_array) && !empty($cart_product_ids_array) ) {
                    $category_passed = $this->whsma_match_category_rule( $cart_product_ids_array, $category_array, $general_rule_match );
                    
                    if ( 'yes' === $category_passed ) {
                        $is_passed['has_fee_based_on_category'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_category'] = 'no';
                    }
                
                }
                
                //Check if is tag exist
                
                if ( is_array( $tag_array ) && isset( $tag_array ) && !empty($tag_array) && !empty($cart_product_ids_array) ) {
                    $tag_passed = $this->whsma_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match );
                    
                    if ( 'yes' === $tag_passed ) {
                        $is_passed['has_fee_based_on_tag'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_tag'] = 'no';
                    }
                
                }
                
                //Check if is user exist
                
                if ( is_array( $user_array ) && isset( $user_array ) && !empty($user_array) && !empty($cart_product_ids_array) ) {
                    $user_passed = $this->whsma_match_user_rule( $user_array, $general_rule_match );
                    
                    if ( 'yes' === $user_passed ) {
                        $is_passed['has_fee_based_on_user'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_user'] = 'no';
                    }
                
                }
                
                //Check if is Cart Subtotal (Before Discount) exist
                
                if ( is_array( $cart_total_array ) && isset( $cart_total_array ) && !empty($cart_total_array) && !empty($cart_product_ids_array) ) {
                    $cart_total_before_passed = $this->whsma_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match );
                    
                    if ( 'yes' === $cart_total_before_passed ) {
                        $is_passed['has_fee_based_on_cart_total_before'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_cart_total_before'] = 'no';
                    }
                
                }
                
                //Check if is quantity exist
                
                if ( is_array( $quantity_array ) && isset( $quantity_array ) && !empty($quantity_array) && !empty($cart_product_ids_array) ) {
                    $quantity_passed = $this->whsma_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match );
                    
                    if ( 'yes' === $quantity_passed ) {
                        $is_passed['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            
            }
            
            if ( isset( $is_passed ) && !empty($is_passed) && is_array( $is_passed ) ) {
                $fnispassed = array();
                foreach ( $is_passed as $val ) {
                    if ( '' !== $val ) {
                        $fnispassed[] = $val;
                    }
                }
                
                if ( 'all' === $general_rule_match ) {
                    
                    if ( in_array( 'no', $fnispassed, true ) ) {
                        $final_is_passed_general_rule['passed'] = 'no';
                    } else {
                        $final_is_passed_general_rule['passed'] = 'yes';
                    }
                
                } else {
                    
                    if ( in_array( 'yes', $fnispassed, true ) ) {
                        $final_is_passed_general_rule['passed'] = 'yes';
                    } else {
                        $final_is_passed_general_rule['passed'] = 'no';
                    }
                
                }
            
            }
        
        }
        
        
        if ( empty($final_is_passed_general_rule) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
            $new_is_passed['passed'] = 'no';
        } else {
            
            if ( !empty($final_is_passed_general_rule) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
                $new_is_passed['passed'] = 'no';
            } else {
                
                if ( empty($final_is_passed_general_rule) && in_array( '', $final_is_passed_general_rule, true ) ) {
                    $new_is_passed['passed'] = 'no';
                } else {
                    if ( !empty($final_is_passed_general_rule) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
                        $new_is_passed['passed'] = 'yes';
                    }
                }
            
            }
        
        }
        
        
        if ( 'all' === $main_rule_condition ) {
            
            if ( in_array( 'no', $new_is_passed, true ) ) {
                $final_passed['passed'] = 'no';
            } else {
                $final_passed['passed'] = 'yes';
            }
        
        } else {
            
            if ( in_array( 'yes', $new_is_passed, true ) ) {
                $final_passed['passed'] = 'yes';
            } else {
                $final_passed['passed'] = 'no';
            }
        
        }
        
        if ( isset( $final_passed ) && !empty($final_passed) && is_array( $final_passed ) ) {
            if ( !in_array( 'no', $final_passed, true ) ) {
                $final_condition_flag['passed'] = 'yes';
            }
        }
        
        if ( empty($final_condition_flag) && $final_condition_flag === '' ) {
            return false;
        } else {
            
            if ( !empty($final_condition_flag) && in_array( 'no', $final_condition_flag, true ) ) {
                return false;
            } else {
                
                if ( empty($final_condition_flag) && in_array( '', $final_condition_flag, true ) ) {
                    return false;
                } else {
                    if ( !empty($final_condition_flag) && in_array( 'yes', $final_condition_flag, true ) ) {
                        return true;
                    }
                }
            
            }
        
        }
    
    }
    
    /**
     * Match country rules
     *
     * @param array $country_array
     *
     * @return array $is_passed
     *
     * @since    1.0.0
     *
     * @uses     WC_Customer::get_shipping_country()
     *
     */
    public function whsma_match_country_rules( $country_array, $general_rule_match )
    {
        $selected_country = WC()->customer->get_shipping_country();
        $is_passed = array();
        foreach ( $country_array as $key => $country ) {
            
            if ( 'is_equal_to' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    }
                
                }
                if ( empty($country['product_fees_conditions_values']) ) {
                    $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                }
            }
            
            if ( 'not_in' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) || in_array( 'all', $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->whsma_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_country', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match simple products rules
     *
     * @param array $cart_product_ids_array
     * @param array $product_array
     *
     * @return array $is_passed
     *
     * @since    1.0.0
     *
     */
    public function whsma_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match )
    {
        $is_passed = array();
        foreach ( $product_array as $key => $product ) {
            if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
                if ( !empty($product['product_fees_conditions_values']) ) {
                    foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                        settype( $product_id, 'integer' );
                        
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_product'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $product['product_fees_conditions_is'] ) {
                if ( !empty($product['product_fees_conditions_values']) ) {
                    foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                        settype( $product_id, 'integer' );
                        
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[$key]['has_fee_based_on_product'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                        }
                    
                    }
                }
            }
        }
        $main_is_passed = $this->whsma_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match category rules
     *
     * @param array $cart_product_ids_array
     * @param array $category_array
     *
     * @return array $is_passed
     * @uses     wp_get_post_terms()
     * @uses     whsma_array_flatten()
     *
     * @since    1.0.0
     *
     * @uses     WC_Product class
     */
    public function whsma_match_category_rule( $cart_product_ids_array, $category_array, $general_rule_match )
    {
        $is_passed = array();
        $cart_category_id_array = array();
        foreach ( $cart_product_ids_array as $product ) {
            $cart_product_category = wp_get_post_terms( $product, 'product_cat', array(
                'fields' => 'ids',
            ) );
            if ( isset( $cart_product_category ) && !empty($cart_product_category) && is_array( $cart_product_category ) ) {
                $cart_category_id_array[] = $cart_product_category;
            }
        }
        $get_cat_all = array_unique( $this->whsma_array_flatten( $cart_category_id_array ) );
        foreach ( $category_array as $key => $category ) {
            if ( 'is_equal_to' === $category['product_fees_conditions_is'] ) {
                if ( !empty($category['product_fees_conditions_values']) ) {
                    foreach ( $category['product_fees_conditions_values'] as $category_id ) {
                        settype( $category_id, 'integer' );
                        
                        if ( in_array( $category_id, $get_cat_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_category'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_category'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $category['product_fees_conditions_is'] ) {
                
                if ( !empty($category['product_fees_conditions_values']) ) {
                    $category['product_fees_conditions_values'] = array_map( 'intval', $category['product_fees_conditions_values'] );
                    foreach ( $category['product_fees_conditions_values'] as $category_id ) {
                        settype( $category_id, 'integer' );
                        
                        if ( in_array( $category_id, $get_cat_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_category'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_category'] = 'yes';
                        }
                    
                    }
                }
            
            }
        }
        $main_is_passed = $this->whsma_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_category', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match tag rules
     *
     * @param array $cart_product_ids_array
     * @param array $tag_array
     *
     * @return array $is_passed
     * @uses     wp_get_post_terms()
     * @uses     whsma_array_flatten()
     *
     * @since    1.0.0
     *
     * @uses     WC_Product class
     */
    public function whsma_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match )
    {
        $tagid = array();
        $is_passed = array();
        foreach ( $cart_product_ids_array as $product ) {
            $cart_product_tag = wp_get_post_terms( $product, 'product_tag', array(
                'fields' => 'ids',
            ) );
            if ( isset( $cart_product_tag ) && !empty($cart_product_tag) && is_array( $cart_product_tag ) ) {
                $tagid[] = $cart_product_tag;
            }
        }
        $get_tag_all = array_unique( $this->whsma_array_flatten( $tagid ) );
        foreach ( $tag_array as $key => $tag ) {
            if ( 'is_equal_to' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $tag['product_fees_conditions_is'] ) {
                
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    $tag['product_fees_conditions_values'] = array_map( 'intval', $tag['product_fees_conditions_values'] );
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                        }
                    
                    }
                }
            
            }
        }
        $main_is_passed = $this->whsma_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_tag', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Count qty for product based and cart based when apply per qty option is on. This rule will apply when advance pricing rule will disable
     *
     * @param array  $cart_array
     * @param int    $products_based_qty
     * @param float  $products_based_subtotal
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $products_based_qty, $products_based_subtotal
     * @since 1.3.3
     *
     * @uses  get_post_meta()
     * @uses  get_post()
     * @uses  get_terms()
     *
     */
    public function whsma_fees_per_qty_on_ap_rules_off(
        $cart_product_ids_array,
        $cart_array,
        $product_qty_array,
        $get_condition_array
    )
    {
        global  $sitepress ;
        $productFeesArray = $get_condition_array;
        $all_rule_check = array();
        $products_based_qty = 0;
        $products_based_subtotal = 0;
        $default_lang = self::$admin_object->whsma_get_default_language_with_sitepress();
        if ( !empty($productFeesArray) ) {
            foreach ( $productFeesArray as $condition ) {
                
                if ( array_search( 'product', $condition, true ) ) {
                    $condition['product_fees_conditions_values'] = array_map( 'intval', $condition['product_fees_conditions_values'] );
                    $site_product_id = '';
                    $cart_final_products_array = array();
                    // Product Condition Start
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $cart_array as $value ) {
                                
                                if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                                    $product_id_lan = $value['variation_id'];
                                } else {
                                    $product_id_lan = $value['product_id'];
                                }
                                
                                $_product = wc_get_product( $product_id_lan );
                                $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                                
                                if ( !empty($sitepress) ) {
                                    $site_product_id = apply_filters(
                                        'wpml_object_id',
                                        $product_id_lan,
                                        'product',
                                        true,
                                        $default_lang
                                    );
                                } else {
                                    $site_product_id = $product_id_lan;
                                }
                                
                                
                                if ( !$_product->is_virtual( 'yes' ) && false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    
                                    if ( in_array( $site_product_id, $condition['product_fees_conditions_values'], true ) ) {
                                        $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                                        
                                        if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
                                            $product_data_explode = explode( "||", $cart_final_products_array[$site_product_id] );
                                            $cart_product_qty = json_decode( $product_data_explode[0] );
                                            $prod_qty += $cart_product_qty;
                                        }
                                        
                                        $cart_final_products_array[$site_product_id] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                } else {
                                    
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                        $prod_qty = 0;
                                        $cart_final_products_array[$site_product_id] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                }
                            
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $cart_array as $value ) {
                                
                                if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                                    $product_id_lan = $value['variation_id'];
                                } else {
                                    $product_id_lan = $value['product_id'];
                                }
                                
                                $_product = wc_get_product( $product_id_lan );
                                $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                                
                                if ( !empty($sitepress) ) {
                                    $site_product_id = apply_filters(
                                        'wpml_object_id',
                                        $product_id_lan,
                                        'product',
                                        true,
                                        $default_lang
                                    );
                                } else {
                                    $site_product_id = $product_id_lan;
                                }
                                
                                
                                if ( !$_product->is_virtual( 'yes' ) && false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    
                                    if ( !in_array( $site_product_id, $condition['product_fees_conditions_values'], true ) ) {
                                        $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                                        
                                        if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
                                            $product_data_explode = explode( "||", $cart_final_products_array[$site_product_id] );
                                            $cart_product_qty = json_decode( $product_data_explode[0] );
                                            $prod_qty += $cart_product_qty;
                                        }
                                        
                                        $cart_final_products_array[$product_id_lan] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                } else {
                                    
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                        $prod_qty = 0;
                                        $cart_final_products_array[$site_product_id] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                }
                            
                            }
                        }
                    }
                    
                    if ( !empty($cart_final_products_array) ) {
                        foreach ( $cart_final_products_array as $prd_id => $cart_item ) {
                            $cart_item_explode = explode( "||", $cart_item );
                            $all_rule_check[$prd_id]['qty'] = $cart_item_explode[0];
                            $all_rule_check[$prd_id]['subtotal'] = $cart_item_explode[1];
                        }
                    }
                    // Product Condition End
                }
                
                
                if ( array_search( 'variableproduct', $condition, true ) ) {
                    $site_product_id = '';
                    $cart_final_var_products_array = array();
                    $condition['product_fees_conditions_values'] = array_map( 'intval', $condition['product_fees_conditions_values'] );
                    // Variable Product Condition Start
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $cart_array as $value ) {
                                
                                if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                                    $product_id_lan = $value['variation_id'];
                                } else {
                                    $product_id_lan = $value['product_id'];
                                }
                                
                                $_product = wc_get_product( $product_id_lan );
                                $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                                
                                if ( !empty($sitepress) ) {
                                    $site_product_id = apply_filters(
                                        'wpml_object_id',
                                        $product_id_lan,
                                        'product',
                                        true,
                                        $default_lang
                                    );
                                } else {
                                    $site_product_id = $product_id_lan;
                                }
                                
                                
                                if ( false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    
                                    if ( in_array( $site_product_id, $condition['product_fees_conditions_values'], true ) ) {
                                        $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                } else {
                                    
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                        $prod_qty = 0;
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                }
                            
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $cart_array as $value ) {
                                
                                if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                                    $product_id_lan = $value['variation_id'];
                                } else {
                                    $product_id_lan = $value['product_id'];
                                }
                                
                                $_product = wc_get_product( $product_id_lan );
                                $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                                
                                if ( !empty($sitepress) ) {
                                    $site_product_id = apply_filters(
                                        'wpml_object_id',
                                        $product_id_lan,
                                        'product',
                                        true,
                                        $default_lang
                                    );
                                } else {
                                    $site_product_id = $product_id_lan;
                                }
                                
                                
                                if ( !$_product->is_virtual( 'yes' ) && false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    
                                    if ( !in_array( $site_product_id, $condition['product_fees_conditions_values'], true ) ) {
                                        $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                } else {
                                    
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                        $prod_qty = 0;
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                }
                            
                            }
                        }
                    }
                    
                    if ( !empty($cart_final_var_products_array) ) {
                        foreach ( $cart_final_var_products_array as $prd_id => $cart_item ) {
                            $cart_item_explode = explode( "||", $cart_item );
                            $all_rule_check[$prd_id]['qty'] = $cart_item_explode[0];
                            $all_rule_check[$prd_id]['subtotal'] = $cart_item_explode[1];
                        }
                    }
                    // Variable Product Condition End
                }
                
                
                if ( array_search( 'category', $condition, true ) ) {
                    $final_cart_products_cats_ids = array();
                    $cart_final_cat_products_array = array();
                    $condition['product_fees_conditions_values'] = array_map( 'intval', $condition['product_fees_conditions_values'] );
                    $all_cats = get_terms( array(
                        'taxonomy' => 'product_cat',
                        'fields'   => 'ids',
                    ) );
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $category_id ) {
                                settype( $category_id, 'integer' );
                                $final_cart_products_cats_ids[] = $category_id;
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            $final_cart_products_cats_ids = array_diff( $all_cats, $condition['product_fees_conditions_values'] );
                        }
                    }
                    
                    $final_cart_products_cats_ids = array_map( 'intval', $final_cart_products_cats_ids );
                    $terms = array();
                    $cart_value_array = array();
                    foreach ( $cart_array as $value ) {
                        
                        if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                            $product_id = $value['variation_id'];
                        } else {
                            $product_id = $value['product_id'];
                        }
                        
                        $_product = wc_get_product( $product_id );
                        $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                        $cart_value_array[] = $value;
                        $term_ids = wp_get_post_terms( $value['product_id'], 'product_cat', array(
                            'fields' => 'ids',
                        ) );
                        foreach ( $term_ids as $term_id ) {
                            $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                            if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                $prod_qty = 0;
                            }
                            $product_id = ( $value['variation_id'] ? $value['variation_id'] : $product_id );
                            if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                
                                if ( in_array( $term_id, $final_cart_products_cats_ids, true ) ) {
                                    
                                    if ( array_key_exists( $product_id, $terms ) && array_key_exists( $term_id, $terms[$product_id] ) ) {
                                        $term_data_explode = explode( "||", $terms[$product_id][$term_id] );
                                        $cart_term_qty = json_decode( $term_data_explode[0] );
                                        $prod_qty += $cart_term_qty;
                                    }
                                    
                                    $terms[$product_id][$term_id] = $prod_qty . "||" . $line_item_subtotal;
                                }
                            
                            }
                        }
                    }
                    foreach ( $terms as $cart_product_key => $main_term_data ) {
                        foreach ( $main_term_data as $cart_term_id => $term_data ) {
                            $term_data_explode = explode( "||", $term_data );
                            $cart_term_qty = json_decode( $term_data_explode[0] );
                            $cart_term_subtotal = json_decode( $term_data_explode[1] );
                            if ( in_array( $cart_term_id, $final_cart_products_cats_ids, true ) ) {
                                $cart_final_cat_products_array[$cart_product_key][$cart_term_id] = $cart_term_qty . "||" . $cart_term_subtotal;
                            }
                        }
                    }
                    if ( !empty($cart_final_cat_products_array) ) {
                        foreach ( $cart_final_cat_products_array as $prd_id => $main_cart_item ) {
                            foreach ( $main_cart_item as $term_id => $cart_item ) {
                                $cart_item_explode = explode( "||", $cart_item );
                                $all_rule_check[$prd_id]['qty'] = $cart_item_explode[0];
                                $all_rule_check[$prd_id]['subtotal'] = $cart_item_explode[1];
                            }
                        }
                    }
                }
                
                
                if ( array_search( 'tag', $condition, true ) ) {
                    // Tag Condition Start
                    $final_cart_products_tag_ids = array();
                    $cart_final_tag_products_array = array();
                    $condition['product_fees_conditions_values'] = array_map( 'intval', $condition['product_fees_conditions_values'] );
                    $all_tags = get_terms( array(
                        'taxonomy' => 'product_tag',
                        'fields'   => 'ids',
                    ) );
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $condition['product_fees_conditions_values'] as $tag_id ) {
                                $final_cart_products_tag_ids[] = $tag_id;
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            $final_cart_products_tag_ids = array_diff( $all_tags, $condition['product_fees_conditions_values'] );
                        }
                    }
                    
                    $final_cart_products_tag_ids = array_map( 'intval', $final_cart_products_tag_ids );
                    $tags = array();
                    $cart_value_array = array();
                    foreach ( $cart_array as $value ) {
                        
                        if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                            $product_id = $value['variation_id'];
                        } else {
                            $product_id = $value['product_id'];
                        }
                        
                        $_product = wc_get_product( $product_id );
                        $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                        $cart_value_array[] = $value;
                        $tag_ids = wp_get_post_terms( $value['product_id'], 'product_tag', array(
                            'fields' => 'ids',
                        ) );
                        foreach ( $tag_ids as $tag_id ) {
                            $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                            if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                $prod_qty = 0;
                            }
                            $product_id = ( $value['variation_id'] ? $value['variation_id'] : $product_id );
                            
                            if ( in_array( $tag_id, $final_cart_products_tag_ids, true ) ) {
                                
                                if ( array_key_exists( $product_id, $tags ) && array_key_exists( $tag_id, $tags[$product_id] ) ) {
                                    $term_data_explode = explode( "||", $tags[$product_id][$tag_id] );
                                    $cart_term_qty = json_decode( $term_data_explode[0] );
                                    $prod_qty += $cart_term_qty;
                                }
                                
                                $tags[$product_id][$tag_id] = $prod_qty . "||" . $line_item_subtotal;
                            }
                        
                        }
                    }
                    foreach ( $tags as $cart_product_key => $main_tag_data ) {
                        foreach ( $main_tag_data as $cart_tag_id => $tag_data ) {
                            $tag_data_explode = explode( "||", $tag_data );
                            $cart_tag_qty = json_decode( $tag_data_explode[0] );
                            $cart_tag_subtotal = json_decode( $tag_data_explode[1] );
                            if ( !empty($final_cart_products_tag_ids) ) {
                                if ( in_array( $cart_tag_id, $final_cart_products_tag_ids, true ) ) {
                                    $cart_final_tag_products_array[$cart_product_key][$cart_tag_id] = $cart_tag_qty . "||" . $cart_tag_subtotal;
                                }
                            }
                        }
                    }
                    if ( !empty($cart_final_tag_products_array) ) {
                        foreach ( $cart_final_tag_products_array as $prd_id => $main_cart_item ) {
                            foreach ( $main_cart_item as $term_id => $cart_item ) {
                                $cart_item_explode = explode( "||", $cart_item );
                                $all_rule_check[$prd_id]['qty'] = $cart_item_explode[0];
                                $all_rule_check[$prd_id]['subtotal'] = $cart_item_explode[1];
                            }
                        }
                    }
                }
                
                
                if ( array_search( 'sku', $condition, true ) ) {
                    $pids = array();
                    if ( !empty($condition['product_fees_conditions_values']) ) {
                        foreach ( $condition['product_fees_conditions_values'] as $id ) {
                            $pids[] = wc_get_product_id_by_sku( $id );
                        }
                    }
                    $site_product_id = '';
                    $cart_final_products_array = array();
                    // Product Condition Start
                    
                    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $cart_array as $value ) {
                                
                                if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                                    $product_id_lan = $value['variation_id'];
                                } else {
                                    $product_id_lan = $value['product_id'];
                                }
                                
                                $_product = wc_get_product( $product_id_lan );
                                $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                                
                                if ( !empty($sitepress) ) {
                                    $site_product_id = apply_filters(
                                        'wpml_object_id',
                                        $product_id_lan,
                                        'product',
                                        true,
                                        $default_lang
                                    );
                                } else {
                                    $site_product_id = $product_id_lan;
                                }
                                
                                
                                if ( !$_product->is_virtual( 'yes' ) && false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    
                                    if ( in_array( $site_product_id, $pids, true ) ) {
                                        $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                                        
                                        if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
                                            $product_data_explode = explode( "||", $cart_final_products_array[$site_product_id] );
                                            $cart_product_qty = json_decode( $product_data_explode[0] );
                                            $prod_qty += $cart_product_qty;
                                        }
                                        
                                        $cart_final_products_array[$site_product_id] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                } else {
                                    
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                        $prod_qty = 0;
                                        $cart_final_products_array[$site_product_id] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                }
                            
                            }
                        }
                    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                        if ( !empty($condition['product_fees_conditions_values']) ) {
                            foreach ( $cart_array as $value ) {
                                
                                if ( !empty($value['variation_id']) && 0 !== $value['variation_id'] ) {
                                    $product_id_lan = $value['variation_id'];
                                } else {
                                    $product_id_lan = $value['product_id'];
                                }
                                
                                $_product = wc_get_product( $product_id_lan );
                                $line_item_subtotal = (double) $value['line_subtotal'] + (double) $value['line_subtotal_tax'];
                                
                                if ( !empty($sitepress) ) {
                                    $site_product_id = apply_filters(
                                        'wpml_object_id',
                                        $product_id_lan,
                                        'product',
                                        true,
                                        $default_lang
                                    );
                                } else {
                                    $site_product_id = $product_id_lan;
                                }
                                
                                
                                if ( !$_product->is_virtual( 'yes' ) && false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    
                                    if ( !in_array( $site_product_id, $pids, true ) ) {
                                        $prod_qty = ( $value['quantity'] ? $value['quantity'] : 0 );
                                        
                                        if ( array_key_exists( $site_product_id, $cart_final_products_array ) ) {
                                            $product_data_explode = explode( "||", $cart_final_products_array[$site_product_id] );
                                            $cart_product_qty = json_decode( $product_data_explode[0] );
                                            $prod_qty += $cart_product_qty;
                                        }
                                        
                                        $cart_final_products_array[$product_id_lan] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                } else {
                                    
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ) {
                                        $prod_qty = 0;
                                        $cart_final_products_array[$site_product_id] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                
                                }
                            
                            }
                        }
                    }
                    
                    if ( !empty($cart_final_products_array) ) {
                        foreach ( $cart_final_products_array as $prd_id => $cart_item ) {
                            $cart_item_explode = explode( "||", $cart_item );
                            $all_rule_check[$prd_id]['qty'] = $cart_item_explode[0];
                            $all_rule_check[$prd_id]['subtotal'] = $cart_item_explode[1];
                        }
                    }
                    // Product Condition End
                }
            
            }
        }
        if ( !empty($all_rule_check) ) {
            foreach ( $all_rule_check as $cart_item ) {
                $products_based_qty += ( isset( $cart_item['qty'] ) ? $cart_item['qty'] : 0 );
                $products_based_subtotal += ( isset( $cart_item['subtotal'] ) ? $cart_item['subtotal'] : 0 );
            }
        }
        if ( 0 === $products_based_qty ) {
            $products_based_qty = 1;
        }
        return array( $products_based_qty, $products_based_subtotal );
    }
    
    /**
     * Match user rules
     *
     * @param array $user_array
     *
     * @return bool false if user is not logged in then it will return false id user logged in then it will return array $is_passed
     * @uses     get_current_user_id()
     *
     * @since    1.0.0
     *
     * @uses     is_user_logged_in()
     */
    public function whsma_match_user_rule( $user_array, $general_rule_match )
    {
        $current_user_id = get_current_user_id();
        $is_passed = array();
        foreach ( $user_array as $key => $user ) {
            $user['product_fees_conditions_values'] = array_map( 'intval', $user['product_fees_conditions_values'] );
            if ( 'is_equal_to' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                }
            
            }
            if ( 'not_in' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                }
            
            }
        }
        $main_is_passed = $this->whsma_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_user', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on cart subtotal before discount
     *
     * @param string $wc_curr_version
     * @param array  $cart_total_array
     *
     * @return array $is_passed
     *
     * @uses     WC_Cart::get_subtotal()
     *
     * @since    1.0.0
     *
     */
    public function whsma_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match )
    {
        global  $woocommerce, $woocommerce_wpml ;
        
        if ( $wc_curr_version >= 3.0 ) {
            $total = WC()->cart->get_subtotal();
        } else {
            $total = $woocommerce->cart->subtotal;
        }
        
        
        if ( isset( $woocommerce_wpml ) && !empty($woocommerce_wpml->multi_currency) ) {
            $new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
        } else {
            $new_total = $total;
        }
        
        $is_passed = array();
        foreach ( $cart_total_array as $key => $cart_total ) {
            settype( $cart_total['product_fees_conditions_values'], 'float' );
            if ( 'is_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] === $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] >= $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] > $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] <= $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'greater_then' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $cart_total['product_fees_conditions_values'] < $new_total ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    }
                
                }
            }
            if ( 'not_in' === $cart_total['product_fees_conditions_is'] ) {
                if ( !empty($cart_total['product_fees_conditions_values']) ) {
                    
                    if ( $new_total === $cart_total['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->whsma_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_total', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on total cart quantity
     *
     * @param array $cart_array
     * @param array $quantity_array
     *
     * @return array $is_passed
     * @uses     WC_Cart::get_cart()
     *
     * @since    1.0.0
     *
     */
    public function whsma_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match )
    {
        $quantity_total = 0;
        foreach ( $cart_array as $woo_cart_item ) {
            $quantity_total += $woo_cart_item['quantity'];
        }
        $is_passed = array();
        foreach ( $quantity_array as $key => $quantity ) {
            settype( $quantity['product_fees_conditions_values'], 'integer' );
            if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    }
                
                }
            }
        }
        $main_is_passed = $this->whsma_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_quantity', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Find unique id based on given array
     *
     * @param array $array
     *
     * @return array $result if $array is empty it will return false otherwise return array as $result
     * @since    1.0.0
     *
     */
    public function whsma_array_flatten( $array )
    {
        if ( !is_array( $array ) ) {
            return false;
        }
        $result = array();
        foreach ( $array as $key => $value ) {
            
            if ( is_array( $value ) ) {
                $result = array_merge( $result, $this->whsma_array_flatten( $value ) );
            } else {
                $result[$key] = $value;
            }
        
        }
        return $result;
    }
    
    /**
     * Display array column
     *
     * @param array $input
     * @param int   $columnKey
     * @param int   $indexKey
     *
     * @return array $array It will return array if any error generate then it will return false
     * @since  1.0.0
     *
     */
    public function whsma_fee_array_column_public( array $input, $columnKey, $indexKey = null )
    {
        $array = array();
        foreach ( $input as $value ) {
            
            if ( !isset( $value[$columnKey] ) ) {
                wp_die( sprintf( esc_html__( 'Key %d does not exist in array', 'woo-hide-shipping-methods' ), esc_attr( $columnKey ) ) );
                return false;
            }
            
            
            if ( is_null( $indexKey ) ) {
                $array[] = $value[$columnKey];
            } else {
                
                if ( !isset( $value[$indexKey] ) ) {
                    wp_die( sprintf( esc_html__( 'Key %d does not exist in array', 'woo-hide-shipping-methods' ), esc_attr( $indexKey ) ) );
                    return false;
                }
                
                
                if ( !is_scalar( $value[$indexKey] ) ) {
                    wp_die( sprintf( esc_html__( 'Key %d does not contain scalar value', 'woo-hide-shipping-methods' ), esc_attr( $indexKey ) ) );
                    return false;
                }
                
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        
        }
        return $array;
    }
    
    /**
     * Remove WooCommerce currency symbol
     *
     * @param float $price
     *
     * @return float $new_price2
     * @since  1.0.0
     *
     * @uses   get_woocommerce_currency_symbol()
     *
     */
    public function whsma_remove_currency_symbol( $price )
    {
        $wc_currency_symbol = get_woocommerce_currency_symbol();
        $new_price = str_replace( $wc_currency_symbol, '', $price );
        $new_price2 = (double) preg_replace( '/[^.\\d]/', '', $new_price );
        return $new_price2;
    }
    
    /*
     * Get WooCommerce version number
     *
     * @since 1.0.0
     *
     * @return string if file is not exists then it will return null
     */
    function whsma_get_woo_version_number()
    {
        // If get_plugins() isn't available, require it
        if ( !function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';
        // If the plugin version number is set, return it
        
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
        } else {
            return null;
        }
    
    }
    
    /**
     * Match methods.
     *
     * Check all created AFRSM shipping methods have a matching condition group.
     *
     * @return array $matched_methods   List of all matched shipping methods.
     * @uses  get_posts()
     * @uses  whsma_condition_match_rules()
     * @uses  Woo_Hide_Shipping_Methods_Admin::whsma_get_shipping_method()
     *
     * @since 1.0.0
     *
     */
    public function whsm_shipping_match_methods()
    {
        $matched_methods = array();
        $sm_posts = self::$admin_object->whsma_get_shipping_method();
        
        if ( !empty($sm_posts) ) {
            foreach ( $sm_posts as $sm_post ) {
                // Check if hide shipping conditions match
                $is_match = $this->whsma_condition_match_rules( $sm_post );
                // Add to matched methods array
                if ( true === $is_match ) {
                    $matched_methods[] = $sm_post;
                }
            }
            return $matched_methods;
        }
    
    }
    
    /**
     * Unset shipping method based on rule
     *
     * @return array $available_shipping_methods
     * @uses  get_posts()
     * @uses  whsm_shipping_match_methods()
     *
     * @since 1.0.0
     *
     */
    public function whsmp_unset_shipping_method( $available_shipping_methods )
    {
        $whsm_cslist = self::$admin_object->whsm_compatible_shipping_plugin_list();
        $hide_shipping_option = get_option( 'hide_shipping_option' );
        
        if ( 'free_shipping_available' === $hide_shipping_option ) {
            
            if ( !empty($available_shipping_methods) ) {
                $free_rates = array();
                foreach ( $available_shipping_methods as $methods => $details ) {
                    
                    if ( 'free_shipping' === $details->method_id ) {
                        $free_rates[$methods] = $details;
                        break;
                    }
                
                }
                return ( !empty($free_rates) ? $free_rates : $available_shipping_methods );
            }
        
        } elseif ( 'free_local_available' === $hide_shipping_option ) {
            
            if ( !empty($available_shipping_methods) ) {
                $free_local_rates = array();
                foreach ( $available_shipping_methods as $methods => $details ) {
                    if ( 'free_shipping' === $details->method_id ) {
                        $free_local_rates[$methods] = $details;
                    }
                }
                foreach ( $available_shipping_methods as $methods => $details ) {
                    if ( 'local_pickup' === $details->method_id ) {
                        $free_local_rates[$methods] = $details;
                    }
                }
                return ( $free_local_rates ? $free_local_rates : $available_shipping_methods );
            }
        
        } else {
            $matched_rule = $this->whsm_shipping_match_methods();
            if ( !empty($matched_rule) ) {
                foreach ( $matched_rule as $sm_id ) {
                    $shipping_method_list = get_post_meta( $sm_id, 'shipping_method_list', true );
                    if ( !empty($available_shipping_methods) ) {
                        foreach ( $available_shipping_methods as $methods => $details ) {
                            $per_prod_sm = $methods;
                            if ( 'per_product' === $methods ) {
                                $methods = esc_html( $methods . ':' . $details->instance_id );
                            }
                            
                            if ( false !== strpos( $methods, ':' ) ) {
                                $ship_method = explode( ':', $methods );
                            } elseif ( false !== strpos( $methods, '_' ) ) {
                                $ship_method = explode( '_', $methods );
                            }
                            
                            if ( in_array( $ship_method[0], $whsm_cslist['compatible_shipping'], true ) ) {
                                if ( !empty($shipping_method_list) ) {
                                    if ( in_array( $methods, $shipping_method_list, true ) ) {
                                        
                                        if ( 'per_product' === $per_prod_sm ) {
                                            unset( $available_shipping_methods[$per_prod_sm] );
                                        } else {
                                            unset( $available_shipping_methods[$methods] );
                                        }
                                    
                                    }
                                }
                            }
                            if ( in_array( 'all', $shipping_method_list, true ) || empty($shipping_method_list) ) {
                                unset( $available_shipping_methods[$methods] );
                            }
                        }
                    }
                }
            }
        }
        
        return $available_shipping_methods;
    }
    
    /**
     * Unset shipping method from cart page
     *
     * @return array $show_shipping
     *
     */
    public function whsmp_unset_shipping_method_from_cart( $show_shipping )
    {
        if ( is_cart() ) {
            return false;
        }
        return $show_shipping;
    }
    
    /**
     * Add fees in cart based on rule
     *
     * @param array $package
     * //package->fees_api()->add_fee
     *
     * @since    1.0.0
     *
     * 
     * */
    public function whsmp_conditional_fee_add_to_cart( $packages )
    {
        foreach ( $packages as $key => $package ) {
            $available_shipping_methods = $package['rates'];
            $available_shipping_methods = $this->whsmp_unset_shipping_method( $available_shipping_methods );
            $packages[$key]['rates'] = $available_shipping_methods;
        }
        return $packages;
    }
    
    /**
     * Return all/any rule to match condition
     *
     * @param array  $is_passed
     * @param string $has_fee_based
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    1.3.0
     *
     */
    public function whsma_check_all_passed_general_rule( $is_passed, $has_fee_based, $general_rule_match )
    {
        $main_is_passed = 'no';
        $flag = array();
        
        if ( !empty($is_passed) ) {
            foreach ( $is_passed as $key => $is_passed_value ) {
                
                if ( 'yes' === $is_passed_value[$has_fee_based] ) {
                    $flag[$key] = true;
                } else {
                    $flag[$key] = false;
                }
            
            }
            
            if ( 'any' === $general_rule_match ) {
                
                if ( in_array( true, $flag, true ) ) {
                    $main_is_passed = 'yes';
                } else {
                    $main_is_passed = 'no';
                }
            
            } else {
                
                if ( in_array( false, $flag, true ) ) {
                    $main_is_passed = 'no';
                } else {
                    $main_is_passed = 'yes';
                }
            
            }
        
        }
        
        return $main_is_passed;
    }

}