<?php

//phpcs:ignore
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/admin
 * @author     theDotstore <wordpress@multidots.in>
 */
class Woo_Hide_Shipping_Methods_Admin
{
    public static  $hook = null ;
    /**
     * Output the Admin UI
     *
     * @since 3.5
     */
    const  post_type = 'wc_whsm' ;
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
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    1.0.0
     *
     */
    public function whsma_enqueue_styles( $hook )
    {
        
        if ( false !== strpos( $hook, '_page_whsm' ) ) {
            wp_enqueue_style(
                $this->plugin_name . 'select2-min',
                plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-jquery-ui-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-timepicker-min-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css',
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-font-awesome',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'media-css',
                plugin_dir_url( __FILE__ ) . 'css/media.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'plugin-new-style',
                plugin_dir_url( __FILE__ ) . 'css/plugin-new-style.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-plugin-setup-wizard',
                plugin_dir_url( __FILE__ ) . 'css/plugin-setup-wizard.css',
                array(),
                'all'
            );
            if ( !(whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code()) ) {
                wp_enqueue_style(
                    $this->plugin_name . '-upgrade-dashboard-style',
                    plugin_dir_url( __FILE__ ) . 'css/upgrade-dashboard.css',
                    array(),
                    'all'
                );
            }
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    1.0.0
     *
     */
    public function whsma_enqueue_scripts( $hook )
    {
        
        if ( false !== strpos( $hook, '_page_whsm' ) ) {
            wp_enqueue_script( 'jquery-ui-accordion' );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script(
                $this->plugin_name . '-select2-full-min',
                plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
                array( 'jquery', 'jquery-ui-datepicker' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-timepicker-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/woo-hide-shipping-methods-admin.js',
                array(
                'jquery',
                'jquery-ui-dialog',
                'jquery-ui-accordion',
                'jquery-ui-sortable',
                'select2'
            ),
                $this->version,
                false
            );
            wp_enqueue_script( 'jquery-tiptip' );
            wp_enqueue_script( 'jquery-blockui' );
            $dimension_unit = get_option( 'woocommerce_dimension_unit' );
            $dimension_unit = ( !empty($dimension_unit) ? '(' . $dimension_unit . ')' : '' );
            $weight_unit = get_option( 'woocommerce_weight_unit' );
            $weight_unit = ( !empty($weight_unit) ? '(' . $weight_unit . ')' : '' );
            wp_localize_script( $this->plugin_name, 'coditional_vars', array(
                'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
                'ajax_icon'                      => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
                'plugin_url'                     => plugin_dir_url( __FILE__ ),
                'dsm_ajax_nonce'                 => wp_create_nonce( 'dsm_nonce' ),
                'country'                        => esc_html__( 'Country', 'woo-hide-shipping-methods' ),
                'city'                           => esc_html__( 'City ( In Pro )', 'woo-hide-shipping-methods' ),
                'state'                          => esc_html__( 'State ( In Pro )', 'woo-hide-shipping-methods' ),
                'postcode'                       => esc_html__( 'Postcode ( In Pro )', 'woo-hide-shipping-methods' ),
                'zone'                           => esc_html__( 'Zone ( In Pro )', 'woo-hide-shipping-methods' ),
                'cart_contains_product'          => esc_html__( 'Cart contains product', 'woo-hide-shipping-methods' ),
                'cart_contains_variable_product' => esc_html__( 'Cart contains variable product ( In Pro )', 'woo-hide-shipping-methods' ),
                'cart_contains_category_product' => esc_html__( 'Cart contains category\'s product', 'woo-hide-shipping-methods' ),
                'cart_contains_tag_product'      => esc_html__( 'Cart contains tag\'s product', 'woo-hide-shipping-methods' ),
                'cart_contains_sku_product'      => esc_html__( 'Cart contains SKU\'s product ( In Pro )', 'woo-hide-shipping-methods' ),
                'user'                           => esc_html__( 'User', 'woo-hide-shipping-methods' ),
                'user_role'                      => esc_html__( 'User Role ( In Pro )', 'woo-hide-shipping-methods' ),
                'cart_subtotal_before_discount'  => esc_html__( 'Cart Subtotal (Before Discount)', 'woo-hide-shipping-methods' ),
                'cart_subtotal_after_discount'   => esc_html__( 'Cart Subtotal (After Discount) ( In Pro )', 'woo-hide-shipping-methods' ),
                'weight'                         => sprintf( __( 'Weight %s ( In Pro )', 'woo-hide-shipping-methods' ), $weight_unit ),
                'length'                         => sprintf( __( 'Length %s ( In Pro )', 'woo-hide-shipping-methods' ), $dimension_unit ),
                'width'                          => sprintf( __( 'Width %s ( In Pro )', 'woo-hide-shipping-methods' ), $dimension_unit ),
                'height'                         => sprintf( __( 'Height %s ( In Pro )', 'woo-hide-shipping-methods' ), $dimension_unit ),
                'volume'                         => sprintf( __( 'Volume %s ( In Pro )', 'woo-hide-shipping-methods' ), $dimension_unit ),
                'coupon'                         => esc_html__( 'Coupon ( In Pro )', 'woo-hide-shipping-methods' ),
                'shipping_class'                 => esc_html__( 'Shipping Class ( In Pro )', 'woo-hide-shipping-methods' ),
                'quantity'                       => esc_html__( 'Quantity', 'woo-hide-shipping-methods' ),
                'equal_to'                       => esc_html__( 'Equal to ( = )', 'woo-hide-shipping-methods' ),
                'not_equal_to'                   => esc_html__( 'Not Equal to ( != )', 'woo-hide-shipping-methods' ),
                'less_or_equal_to'               => esc_html__( 'Less or Equal to ( <= )', 'woo-hide-shipping-methods' ),
                'less_than'                      => esc_html__( 'Less then ( < )', 'woo-hide-shipping-methods' ),
                'greater_or_equal_to'            => esc_html__( 'Greater or Equal to ( >= )', 'woo-hide-shipping-methods' ),
                'greater_than'                   => esc_html__( 'Greater then ( > )', 'woo-hide-shipping-methods' ),
                'delete'                         => esc_html__( 'Delete', 'woo-hide-shipping-methods' ),
                'location_specific'              => esc_html__( 'Location Specific', 'woo-hide-shipping-methods' ),
                'product_specific'               => esc_html__( 'Product Specific', 'woo-hide-shipping-methods' ),
                'user_specific'                  => esc_html__( 'User Specific', 'woo-hide-shipping-methods' ),
                'cart_specific'                  => esc_html__( 'Cart Specific', 'woo-hide-shipping-methods' ),
                'checkout_specific'              => esc_html__( 'Checkout Specific', 'woo-hide-shipping-methods' ),
                'payment_method'                 => esc_html__( 'Payment Method ( In Pro )', 'woo-hide-shipping-methods' ),
                'attribute_specific'             => esc_html__( 'Attribute Specific', 'woo-hide-shipping-methods' ),
                'attribute_list'                 => esc_html__( 'Attribute ( In Pro )', 'woo-hide-shipping-methods' ),
                'note'                           => esc_html__( 'Note: ', 'woo-hide-shipping-methods' ),
                'select_shipping'                => esc_html__( 'Select shipping method', 'woo-hide-shipping-methods' ),
                'setup_wizard_ajax_nonce'        => wp_create_nonce( 'wizard_ajax_nonce' ),
                'dpb_api_url'                    => WHSM_STORE_URL,
                'select_product'                 => esc_html__( 'Select a product', 'woo-hide-shipping-methods' ),
                'select_category'                => esc_html__( 'Select a category', 'woo-hide-shipping-methods' ),
                'select_country'                 => esc_html__( 'Select a country', 'woo-hide-shipping-methods' ),
                'select_tag'                     => esc_html__( 'Select a product tag', 'woo-hide-shipping-methods' ),
                'select_user'                    => esc_html__( 'Select a user', 'woo-hide-shipping-methods' ),
                'select_float_number'            => esc_html__( '0.00', 'woo-hide-shipping-methods' ),
                'select_integer_number'          => esc_html__( '10', 'woo-hide-shipping-methods' ),
                'select_city'                    => esc_html__( "City 1\nCity 2", 'woo-hide-shipping-methods' ),
                'delete_confirm'                 => esc_html__( 'Are you sure want to delete this rule?', 'woo-hide-shipping-methods' ),
            ) );
        }
    
    }
    
    public function whsma_register_post_type()
    {
        register_post_type( self::post_type, array(
            'labels'          => array(
            'name'          => __( 'Hide Shipping For WooCommerce', 'woo-hide-shipping-methods' ),
            'singular_name' => __( 'Hide Shipping For WooCommerce', 'woo-hide-shipping-methods' ),
        ),
            'rewrite'         => false,
            'query_var'       => false,
            'public'          => false,
            'capability_type' => 'page',
        ) );
    }
    
    /*
     * Hide Shipping Pro Menu
     *
     * @since 1.0.0
     */
    public function whsma_dot_store_menu_shipping_method_pro()
    {
        $get_hook = add_submenu_page(
            'woocommerce',
            'Hide Shipping',
            'Hide Shipping',
            'manage_options',
            'whsm-start-page',
            array( $this, 'whsma_start_page' )
        );
        self::$hook = $get_hook;
        add_action( "load-{$get_hook}", array( $this, "whsma_screen_options" ) );
    }
    
    /**
     * Hide Shipping List Page
     *
     * @since    1.0.0
     */
    public function whsma_start_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/whsm-start-page.php';
    }
    
    /**
     * Screen option for hide shipping list
     *
     * @since    1.0.0
     */
    public function whsma_screen_options()
    {
        $get_action = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( isset( $get_action ) && 'woo_hide_shipping' === $get_action ) {
            $args = array(
                'label'   => esc_html__( 'List Per Page', 'woo-hide-shipping-methods' ),
                'default' => 10,
                'option'  => 'whsm_per_page',
            );
            add_screen_option( 'per_page', $args );
            if ( !class_exists( 'WC_Shipping_Methods_Table' ) ) {
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/list-tables/class-wc-hide-shipping-methods-table.php';
            }
            $list_table_obj = new WC_Shipping_Methods_Table();
            $list_table_obj->_column_headers = $list_table_obj->get_column_info();
        }
    
    }
    
    /**
     * Remove submenu from admin section
     *
     * @since 1.4.0
     */
    public function whsma_remove_admin_submenus()
    {
        remove_submenu_page( 'woocommerce', 'whsm-start-page-account' );
        remove_submenu_page( 'woocommerce', 'whsm-start-page-pricing' );
    }
    
    /**
     * Redirect to quick start guide after plugin activation
     *
     * @since    1.0.0
     */
    public function whsma_welcome_shipping_method_screen_do_activation_redirect()
    {
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_whsm_mode_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_whsm_mode_activation_redirect_data' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( admin_url( 'plugins.php' ) ) );
        exit;
    }
    
    /**
     * Display textfield and multiselect dropdown based on country, state, zone and etc
     *
     * @return string $html
     * @since 1.0.0
     *
     * @uses  whsma_get_country_list()
     * @uses  whsma_get_states_list__premium_only()
     * @uses  whsma_get_zones_list__premium_only()
     * @uses  whsma_get_product_list()
     * @uses  whsma_get_varible_product_list__premium_only()
     * @uses  whsma_get_category_list()
     * @uses  whsma_get_tag_list()
     * @uses  whsma_get_sku_list__premium_only()
     * @uses  whsma_get_user_list()
     * @uses  whsma_get_user_role_list__premium_only()
     * @uses  whsma_get_coupon_list__premium_only()
     * @uses  whsma_get_shipping_class__premium_only()
     * @uses  whsma_allowed_html_tags()
     *
     */
    public function whsma_product_fees_conditions_values_ajax()
    {
        $get_condition = filter_input( INPUT_GET, 'condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_count = filter_input( INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT );
        $condition = ( isset( $get_condition ) ? sanitize_text_field( $get_condition ) : '' );
        $count = ( isset( $get_count ) ? sanitize_text_field( $get_count ) : '' );
        $html = '';
        
        if ( 'country' === $condition ) {
            $html .= wp_json_encode( $this->whsma_get_country_list( $count, [], true ) );
        } elseif ( 'product' === $condition ) {
            $html .= wp_json_encode( $this->whsma_get_product_list(
                $count,
                [],
                '',
                true
            ) );
        } elseif ( 'category' === $condition ) {
            $html .= wp_json_encode( $this->whsma_get_category_list( $count, [], true ) );
        } elseif ( 'tag' === $condition ) {
            $html .= wp_json_encode( $this->whsma_get_tag_list( $count, [], true ) );
        } elseif ( 'user' === $condition ) {
            $html .= wp_json_encode( $this->whsma_get_user_list( $count, [], true ) );
        } elseif ( 'cart_total' === $condition ) {
            $html .= 'input';
        } elseif ( 'quantity' === $condition ) {
            $html .= 'input';
        }
        
        echo  wp_kses( $html, self::whsma_allowed_html_tags() ) ;
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    
    /**
     * Get country list
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string $html
     * @since  1.0.0
     *
     * @uses   WC_Countries() class
     *
     */
    public function whsma_get_country_list( $count = '', $selected = array(), $json = false )
    {
        $countries_obj = new WC_Countries();
        $getCountries = $countries_obj->__get( 'countries' );
        $html = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="whsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_country" multiple="multiple">';
        if ( !empty($getCountries) ) {
            foreach ( $getCountries as $code => $country ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $code, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $code ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $country ) . '</option>';
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->whsma_convert_array_to_json( $getCountries );
        }
        return $html;
    }
    
    /**
     * Get product list
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string $html
     * @since  1.0.0
     *
     * @uses   whsma_get_default_language_with_sitepress()
     *
     */
    public function whsma_get_product_list(
        $count = '',
        $selected = array(),
        $action = '',
        $json = false
    )
    {
        
        if ( 'edit' === $action ) {
            $posts_per_page = -1;
        } else {
            $posts_per_page = 10;
        }
        
        $get_all_products = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'post__in'       => $selected,
            'posts_per_page' => $posts_per_page,
        ) );
        $default_lang = $this->whsma_get_default_language_with_sitepress();
        $defaultlang_simple_product_ids = array();
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                $defaultlang_simple_product_ids = $this->whsma_simple_product_id(
                    $defaultlang_simple_product_ids,
                    $get_all_product->ID,
                    $_product,
                    $default_lang
                );
            }
        }
        $html = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="whsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_product" multiple="multiple">';
        if ( isset( $defaultlang_simple_product_ids ) && !empty($defaultlang_simple_product_ids) ) {
            foreach ( $defaultlang_simple_product_ids as $new_product_id ) {
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( '' !== $selectedVal ) {
                    $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                }
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return [];
        }
        return $html;
    }
    
    /**
     * Get simple and variable product list in Advance pricing rules
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @uses   WC_Product::is_type()
     *
     * @since  1.0.0
     *
     * @uses   whsma_get_default_language_with_sitepress()
     * @uses   wc_get_product()
     */
    public function whsma_get_product_options( $count = '', $selected = array() )
    {
        $default_lang = $this->whsma_get_default_language_with_sitepress();
        $all_selected_product_ids = array();
        if ( !empty($selected) && is_array( $selected ) ) {
            foreach ( $selected as $product_id ) {
                $_product = wc_get_product( $product_id );
                if ( isset( $_product ) && !empty($_product) ) {
                    
                    if ( 'product_variation' === $_product->post_type ) {
                        $all_selected_product_ids[] = $_product->get_parent_id();
                        //parent_id;
                    } else {
                        $all_selected_product_ids[] = $product_id;
                    }
                
                }
            }
        }
        $get_all_products = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'post__in'       => $all_selected_product_ids,
        ) );
        $baselang_variation_product_ids = array();
        $defaultlang_simple_product_ids = array();
        $html = '';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( isset( $_product ) && !empty($_product) ) {
                    $baselang_variation_product_ids = $this->whsma_variable_product_id( $baselang_variation_product_ids, $_product, $default_lang );
                    $defaultlang_simple_product_ids = $this->whsma_simple_product_id(
                        $defaultlang_simple_product_ids,
                        $get_all_product->ID,
                        $_product,
                        $default_lang
                    );
                }
            
            }
        }
        $baselang_product_ids = array_merge( $baselang_variation_product_ids, $defaultlang_simple_product_ids );
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $baselang_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( '' !== $selectedVal ) {
                    $html .= '<option value="' . esc_attr( $baselang_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
                }
            }
        }
        return $html;
    }
    
    /**
     * Get category list in Advance pricing rules
     *
     * @param array $selected
     * @param bool  $json
     *
     * @return string $html
     * @uses   whsma_get_default_language_with_sitepress()
     *
     * @since  1.0.0
     *
     */
    public function whsma_get_category_options( $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->whsma_get_default_language_with_sitepress();
        $filter_category_list = [];
        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => $post_status,
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_categories = get_categories( $args );
        $html = '';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( !empty($sitepress) ) {
                    $new_cat_id = apply_filters(
                        'wpml_object_id',
                        $get_all_category->term_id,
                        'product_cat',
                        true,
                        $default_lang
                    );
                } else {
                    $new_cat_id = $get_all_category->term_id;
                }
                
                $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                
                if ( !empty($selected) ) {
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                    
                    if ( $category->parent > 0 ) {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . '' . esc_html( $parent_category->name ) . '->' . esc_html( $category->name ) . '</option>';
                    } else {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . esc_html( $category->name ) . '</option>';
                    }
                
                } else {
                    
                    if ( $category->parent > 0 ) {
                        $filter_category_list[$category->term_id] = $parent_category->name . '->' . $category->name;
                    } else {
                        $filter_category_list[$category->term_id] = $category->name;
                    }
                
                }
            
            }
        }
        
        if ( true === $json ) {
            return wp_json_encode( $this->whsma_convert_array_to_json( $filter_category_list ) );
        } else {
            return $html;
        }
    
    }
    
    /**
     * Get category list in Shipping Method Rules
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string $html
     * @since  1.0.0
     *
     * @uses   whsma_get_default_language_with_sitepress()
     * @uses   get_categories()
     * @uses   get_term_by()
     *
     */
    public function whsma_get_category_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->whsma_get_default_language_with_sitepress();
        $filter_categories = [];
        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => $post_status,
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_categories = get_categories( $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="whsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_cat_product" multiple="multiple">';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( !empty($sitepress) ) {
                    $new_cat_id = apply_filters(
                        'wpml_object_id',
                        $get_all_category->term_id,
                        'product_cat',
                        true,
                        $default_lang
                    );
                } else {
                    $new_cat_id = $get_all_category->term_id;
                }
                
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                
                if ( $category->parent > 0 ) {
                    $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $parent_category->name ) . '->' . esc_html( $category->name ) . '</option>';
                    $filter_categories[$category->term_id] = '#' . $parent_category->name . '->' . $category->name;
                } else {
                    $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . esc_html( $category->name ) . '</option>';
                    $filter_categories[$category->term_id] = $category->name;
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->whsma_convert_array_to_json( $filter_categories );
        }
        return $html;
    }
    
    /**
     * Get tag list in Shipping Method Rules
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string $html
     * @uses   whsma_get_default_language_with_sitepress()
     * @uses   get_term_by()
     *
     * @since  1.0.0
     *
     */
    public function whsma_get_tag_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->whsma_get_default_language_with_sitepress();
        $filter_tags = [];
        $taxonomy = 'product_tag';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_tags = get_categories( $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="whsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_tag_product" multiple="multiple">';
        if ( isset( $get_all_tags ) && !empty($get_all_tags) ) {
            foreach ( $get_all_tags as $get_all_tag ) {
                
                if ( !empty($sitepress) ) {
                    $new_tag_id = apply_filters(
                        'wpml_object_id',
                        $get_all_tag->term_id,
                        'product_tag',
                        true,
                        $default_lang
                    );
                } else {
                    $new_tag_id = $get_all_tag->term_id;
                }
                
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_tag_id, $selected, true ) ? 'selected=selected' : '' );
                $tag = get_term_by( 'id', $new_tag_id, 'product_tag' );
                $html .= '<option value="' . esc_attr( $tag->term_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $tag->name ) . '</option>';
                $filter_tags[$tag->term_id] = $tag->name;
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->whsma_convert_array_to_json( $filter_tags );
        }
        return $html;
    }
    
    /**
     * Get user list in Shipping Method Rules
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string $html
     * @since  1.0.0
     *
     */
    public function whsma_get_user_list( $count = '', $selected = array(), $json = false )
    {
        $filter_users = [];
        $get_all_users = get_users();
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="whsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_user" multiple="multiple">';
        if ( isset( $get_all_users ) && !empty($get_all_users) ) {
            foreach ( $get_all_users as $get_all_user ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $get_all_user->data->ID, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $get_all_user->data->ID ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_user->data->user_login ) . '</option>';
                $filter_users[$get_all_user->data->ID] = $get_all_user->data->user_login;
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->whsma_convert_array_to_json( $filter_users );
        }
        return $html;
    }
    
    /**
     * Display product list based product specific option
     *
     * @return string $html
     * @uses   whsma_get_default_language_with_sitepress()
     * @uses   whsma_allowed_html_tags()
     *
     * @since  1.0.0
     *
     */
    public function whsma_product_fees_conditions_values_product_ajax()
    {
        $json = true;
        $filter_product_list = [];
        $default_lang = $this->whsma_get_default_language_with_sitepress();
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $posts_per_page = filter_input( INPUT_GET, 'posts_per_page', FILTER_SANITIZE_NUMBER_INT );
        $_page = filter_input( INPUT_GET, '_page', FILTER_SANITIZE_NUMBER_INT );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        $baselang_product_ids = array();
        function whsma_posts_where( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( !empty($search_term) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_args = array(
            'post_type'        => 'product',
            'posts_per_page'   => $posts_per_page,
            'offset'           => ($_page - 1) * $posts_per_page,
            'search_pro_title' => $post_value,
            'post_status'      => 'publish',
            'orderby'          => 'title',
            'order'            => 'ASC',
        );
        add_filter(
            'posts_where',
            'whsma_posts_where',
            10,
            2
        );
        $get_wp_query = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'whsma_posts_where',
            10,
            2
        );
        $get_all_products = $get_wp_query->posts;
        if ( isset( $get_all_products ) && !empty($get_all_products) ) {
            foreach ( $get_all_products as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                $baselang_product_ids = $this->whsma_simple_product_id(
                    $baselang_product_ids,
                    $get_all_product->ID,
                    $_product,
                    $default_lang
                );
            }
        }
        $html = '';
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $html .= '<option value="' . esc_attr( $baselang_product_id ) . '">' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
                $filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
            }
        }
        
        if ( $json ) {
            echo  wp_json_encode( $filter_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, self::whsma_allowed_html_tags() ) ;
        wp_die();
    }
    
    /**
     * Display simple and variable product list based product specific option in Advance Pricing Rules
     *
     * @return string $html
     * @uses   whsma_get_default_language_with_sitepress()
     * @uses   wc_get_product()
     * @uses   WC_Product::is_type()
     * @uses   get_available_variations()
     * @uses   whsma_allowed_html_tags()
     *
     * @since  1.0.0
     *
     */
    public function whsm_change_status_from_list_section()
    {
        $active_items = 0;
        /* Check for post request */
        $get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
        $get_current_shipping_id = $get_current_shipping_id;
        $get_current_value = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_post_id = ( isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '' );
        
        if ( empty($get_post_id) ) {
            echo  '<strong>' . esc_html__( 'Something went wrong', 'woo-hide-shipping-methods' ) . '</strong>' ;
            wp_die();
        }
        
        $current_value = ( isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '' );
        
        if ( 'true' === $current_value ) {
            // bhavesh working here
            $post_args = array(
                'ID'          => $get_post_id,
                'post_status' => 'publish',
                'post_type'   => self::post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $get_post_id, 'whsm_status', 'on' );
        } else {
            $post_args = array(
                'ID'          => $get_post_id,
                'post_status' => 'draft',
                'post_type'   => self::post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $get_post_id, 'whsm_status', 'off' );
        }
        
        
        if ( !empty($post_update) ) {
            $message = esc_html__( 'Status changed successfully.', 'woo-hide-shipping-methods' );
        } else {
            $message = esc_html__( 'Something went wrong', 'woo-hide-shipping-methods' );
        }
        
        wp_send_json( array(
            'active_count' => $active_items,
            'message'      => $message,
        ) );
    }
    
    /**
     * Variable product id
     *
     * @param object $_product
     * @param string $default_lang
     *
     * @return array $baselang_variation_product_ids
     *
     * @since  1.0.0
     */
    public function whsma_variable_product_id( $baselang_variation_product_ids, $_product, $default_lang )
    {
        global  $sitepress ;
        if ( !$_product->is_virtual( 'yes' ) ) {
            
            if ( $_product->is_type( 'variable' ) ) {
                $variations = $_product->get_available_variations();
                foreach ( $variations as $value ) {
                    
                    if ( !empty($sitepress) ) {
                        $defaultlang_variation_product_id = apply_filters(
                            'wpml_object_id',
                            $value['variation_id'],
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $defaultlang_variation_product_id = $value['variation_id'];
                    }
                    
                    $baselang_variation_product_ids[] = $defaultlang_variation_product_id;
                }
            }
        
        }
        return $baselang_variation_product_ids;
    }
    
    /**
     * Simple product id
     *
     * @param int $prd_id
     * @param object $_product
     * @param string $default_lang
     *
     * @return array $baselang_variation_product_ids
     *
     * @since  1.0.0
     */
    public function whsma_simple_product_id(
        $baselang_simple_product_ids,
        $prd_id,
        $_product,
        $default_lang
    )
    {
        global  $sitepress ;
        if ( !$_product->is_virtual( 'yes' ) ) {
            
            if ( $_product->is_type( 'simple' ) ) {
                
                if ( !empty($sitepress) ) {
                    $defaultlang_simple_product_id = apply_filters(
                        'wpml_object_id',
                        $prd_id,
                        'product',
                        true,
                        $default_lang
                    );
                } else {
                    $defaultlang_simple_product_id = $prd_id;
                }
                
                $baselang_simple_product_ids[] = $defaultlang_simple_product_id;
            }
        
        }
        return $baselang_simple_product_ids;
    }
    
    /**
     * Get default site language
     *
     * @return string $default_lang
     *
     * @since  1.0.0
     */
    public function whsma_get_default_language_with_sitepress()
    {
        global  $sitepress ;
        
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_current_language();
        } else {
            $default_lang = $this->whsma_get_current_site_language();
        }
        
        return $default_lang;
    }
    
    /**
     * Get current site language
     *
     * @return string $default_lang
     * @since 1.0.0
     *
     */
    public function whsma_get_current_site_language()
    {
        $get_site_language = get_bloginfo( "language" );
        
        if ( false !== strpos( $get_site_language, '-' ) ) {
            $get_site_language_explode = explode( '-', $get_site_language );
            $default_lang = $get_site_language_explode[0];
        } else {
            $default_lang = $get_site_language;
        }
        
        return $default_lang;
    }
    
    /**
     * Get all hide shipping rules
     *
     * @return array|object $get_all_shipping
     *
     * @since  1.0.0
     *
     */
    public function whsma_get_shipping_method()
    {
        global  $sitepress ;
        $default_lang = $this->whsma_get_default_language_with_sitepress();
        $matched_rules = array();
        $sm_args = array(
            'post_type'        => self::post_type,
            'posts_per_page'   => -1,
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'post_status'      => 'publish',
            'fields'           => 'ids',
            'suppress_filters' => false,
        );
        $get_all_shipping = new WP_Query( $sm_args );
        if ( !empty($get_all_shipping->posts) ) {
            foreach ( $get_all_shipping->posts as $sid ) {
                
                if ( !empty($sitepress) ) {
                    $sm_post_id = apply_filters(
                        'wpml_object_id',
                        $sid,
                        self::post_type,
                        true,
                        $default_lang
                    );
                } else {
                    $sm_post_id = $sid;
                }
                
                
                if ( !empty($sitepress) ) {
                    
                    if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
                        $language_information = apply_filters( 'wpml_post_language_details', null, $sm_post_id );
                    } else {
                        $language_information = wpml_get_language_information( $sm_post_id );
                    }
                    
                    $post_id_language_code = $language_information['language_code'];
                } else {
                    $post_id_language_code = $this->whsma_get_default_language_with_sitepress();
                }
                
                if ( $post_id_language_code === $default_lang ) {
                    array_push( $matched_rules, $sm_post_id );
                }
            }
        }
        return $matched_rules;
    }
    
    /**
     * Convert array to json
     *
     * @param array $arr
     *
     * @return array $filter_data
     * @since 1.0.0
     *
     */
    public function whsma_convert_array_to_json( $arr )
    {
        $filter_data = [];
        foreach ( $arr as $key => $value ) {
            $option = [];
            $option['name'] = $value;
            $option['attributes']['value'] = $key;
            $filter_data[] = $option;
        }
        return $filter_data;
    }
    
    /**
     * Allowed html tags used for wp_kses function
     *
     * @return array
     * @since     1.0.0
     *
     */
    public static function whsma_allowed_html_tags()
    {
        $allowed_tags = array(
            'a'        => array(
            'href'         => array(),
            'title'        => array(),
            'class'        => array(),
            'target'       => array(),
            'data-tooltip' => array(),
        ),
            'ul'       => array(
            'class' => array(),
        ),
            'li'       => array(
            'class' => array(),
        ),
            'div'      => array(
            'class' => array(),
            'id'    => array(),
        ),
            'select'   => array(
            'rel-id'      => array(),
            'id'          => array(),
            'name'        => array(),
            'class'       => array(),
            'multiple'    => array(),
            'style'       => array(),
            'placeholder' => array(),
        ),
            'input'    => array(
            'id'          => array(),
            'value'       => array(),
            'name'        => array(),
            'class'       => array(),
            'type'        => array(),
            'data-index'  => array(),
            'placeholder' => array(),
        ),
            'textarea' => array(
            'id'          => array(),
            'name'        => array(),
            'class'       => array(),
            'placeholder' => array(),
        ),
            'option'   => array(
            'id'       => array(),
            'selected' => array(),
            'name'     => array(),
            'value'    => array(),
        ),
            'br'       => array(),
            'p'        => array(),
            'b'        => array(
            'style' => array(),
        ),
            'em'       => array(),
            'strong'   => array(),
            'i'        => array(
            'class' => array(),
        ),
            'span'     => array(
            'class' => array(),
        ),
            'small'    => array(
            'class' => array(),
        ),
            'label'    => array(
            'class' => array(),
            'id'    => array(),
            'for'   => array(),
        ),
        );
        return $allowed_tags;
    }
    
    /**
     * Add screen option for per page
     *
     * @param bool   $status
     * @param string $option
     * @param int    $value
     *
     * @return int $value
     * @since 1.0.0
     *
     */
    public function whsma_set_screen_options( $status, $option, $value )
    {
        $whsm_screens = array( 'whsm_per_page' );
        if ( in_array( $option, $whsm_screens, true ) ) {
            return $value;
        }
        return $status;
    }
    
    /**
     * Tab array
     *
     * @return array $tab_array
     * @since 1.0.0
     *
     *
     */
    public static function whsma_tab_array()
    {
        $hide_shipping_option = get_option( 'hide_shipping_option' );
        $hide_tab_array = array( 'free_shipping_available', 'free_local_available', 'other_shipping_hide' );
        
        if ( in_array( $hide_shipping_option, $hide_tab_array, true ) ) {
            $tab_array = array(
                'general_setting'   => esc_html__( 'General Setting', 'woo-hide-shipping-methods' ),
                'get_started'       => esc_html__( 'Get Started', 'woo-hide-shipping-methods' ),
                'quick_info'        => esc_html__( 'Quick info', 'woo-hide-shipping-methods' ),
                'upgrade_dashboard' => esc_html__( 'Upgrade Dashboard', 'woo-hide-shipping-methods' ),
            );
        } else {
            $tab_array = array(
                'general_setting'   => esc_html__( 'General Setting', 'woo-hide-shipping-methods' ),
                'woo_hide_shipping' => esc_html__( 'Manage Conditional Hide Shipping Rules', 'woo-hide-shipping-methods' ),
                'get_started'       => esc_html__( 'Get Started', 'woo-hide-shipping-methods' ),
                'quick_info'        => esc_html__( 'Quick info', 'woo-hide-shipping-methods' ),
                'upgrade_dashboard' => esc_html__( 'Upgrade Dashboard', 'woo-hide-shipping-methods' ),
            );
        }
        
        return $tab_array;
    }
    
    /**
     * Display message in admin side
     *
     * @param string $message
     * @param string $tab
     *
     * @return bool
     * @since 1.0.0
     *
     */
    public function whsma_updated_message( $message, $tab, $validation_msg )
    {
        if ( empty($message) ) {
            return false;
        }
        
        if ( 'woo_hide_shipping' === $tab ) {
            
            if ( 'created' === $message ) {
                $updated_message = esc_html__( "Hide shipping rule created.", 'woo-hide-shipping-methods' );
            } elseif ( 'saved' === $message ) {
                $updated_message = esc_html__( "Hide shipping rule updated.", 'woo-hide-shipping-methods' );
            } elseif ( 'deleted' === $message ) {
                $updated_message = esc_html__( "Hide shipping rule deleted.", 'woo-hide-shipping-methods' );
            } elseif ( 'duplicated' === $message ) {
                $updated_message = esc_html__( "Hide shipping rule duplicated.", 'woo-hide-shipping-methods' );
            } elseif ( 'disabled' === $message ) {
                $updated_message = esc_html__( "Hide shipping rule disabled.", 'woo-hide-shipping-methods' );
            } elseif ( 'enabled' === $message ) {
                $updated_message = esc_html__( "Hide shipping rule enabled.", 'woo-hide-shipping-methods' );
            }
            
            
            if ( 'failed' === $message ) {
                $failed_messsage = esc_html__( "There was an error with saving data.", 'woo-hide-shipping-methods' );
            } elseif ( 'nonce_check' === $message ) {
                $failed_messsage = esc_html__( "There was an error with security check.", 'woo-hide-shipping-methods' );
            }
            
            if ( 'validated' === $message ) {
                $validated_messsage = esc_html( $validation_msg );
            }
        } else {
            if ( 'saved' === $message ) {
                $updated_message = esc_html__( "Settings have been saved successfully.", 'woo-hide-shipping-methods' );
            }
            if ( 'nonce_check' === $message ) {
                $failed_messsage = esc_html__( "There was an error with security check.", 'woo-hide-shipping-methods' );
            }
            if ( 'validated' === $message ) {
                $validated_messsage = esc_html( $validation_msg );
            }
        }
        
        
        if ( !empty($updated_message) ) {
            echo  sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) ) ;
            return false;
        }
        
        
        if ( !empty($failed_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) ) ;
            return false;
        }
        
        
        if ( !empty($validated_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) ) ;
            return false;
        }
    
    }
    
    /**
     * add class into body
     *
     * @param string $classes
     *
     * @return string $classes
     * @since 1.0.0
     *
     */
    public function whsma_admin_body_class( $classes )
    {
        $get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( isset( $get_page ) && 'whsm-start-page' === $get_page ) {
            if ( isset( $get_tab ) && 'woo_hide_shipping' === $get_tab ) {
                $classes = 'shipping-method-class';
            }
        }
        return $classes;
    }
    
    /**
     * List out shipping plugin
     *
     * @param string $args
     *
     * @return array $combine_shipping_method_list
     * @uses  WC_Shipping::get_shipping_methods()
     *
     * @uses  WC_Shipping_Zones::get_zones()
     * @since 1.0.0
     *
     */
    public function whsma_list_out_shipping( $args )
    {
        $whsm_cslist = $this->whsm_compatible_shipping_plugin_list();
        $delivery_zones = WC_Shipping_Zones::get_zones();
        $zone_status_array = array();
        foreach ( $delivery_zones as $the_zone ) {
            foreach ( $the_zone['shipping_methods'] as $val ) {
                if ( in_array( $val->id, $whsm_cslist['default_shipping'], true ) ) {
                    if ( 'yes' === $val->enabled ) {
                        
                        if ( 'advanced' === $args ) {
                            
                            if ( isset( $val->cost ) && !empty($val->cost) ) {
                                $default_shipping_unique_id = $val->id . ':' . $val->instance_id;
                                $zone_status_array[$default_shipping_unique_id] = $the_zone['zone_name'] . ' - ' . $val->title;
                            }
                        
                        } else {
                            $default_shipping_unique_id = $val->id . ':' . $val->instance_id;
                            $zone_status_array[$default_shipping_unique_id] = $the_zone['zone_name'] . ' - ' . $val->title;
                        }
                    
                    }
                }
                if ( class_exists( 'Flexible_Shipping_Plugin' ) ) {
                    if ( in_array( $val->id, $whsm_cslist['whsm_fs'], true ) ) {
                        if ( 'yes' === $val->enabled ) {
                            
                            if ( !empty($val->instance_id) ) {
                                $fsmi = get_option( 'woocommerce_flexible_shipping_single_' . $val->instance_id . '_settings' );
                                if ( !empty($fsmi) ) {
                                    
                                    if ( 'advanced' === $args ) {
                                        if ( !empty($fsmi['method_free_shipping']) ) {
                                            $zone_status_array[$fsmi['id_for_shipping']] = $the_zone['zone_name'] . ' - ' . $fsmi['method_title'];
                                        }
                                    } else {
                                        $zone_status_array[$fsmi['id_for_shipping']] = $the_zone['zone_name'] . ' - ' . $fsmi['method_title'];
                                    }
                                
                                }
                            }
                        
                        }
                    }
                }
                if ( class_exists( 'JEMTR_Table_Rate_Shipping_Method' ) ) {
                    if ( in_array( $val->id, $whsm_cslist['jem_table_rate'], true ) ) {
                        
                        if ( 'yes' === $val->enabled && !empty($val->instance_id) ) {
                            $jemtr_methods = get_option( $val->id . '_shipping_methods_' . $val->instance_id );
                            if ( !empty($jemtr_methods) ) {
                                foreach ( $jemtr_methods as $jemtr_method ) {
                                    
                                    if ( 'yes' === $jemtr_method['method_enabled'] ) {
                                        $method_name = $the_zone['zone_name'] . ' - ' . $val->method_title . ' > ' . $jemtr_method['method_title'];
                                        $method_id = $val->id . '_' . $val->instance_id . '_' . sanitize_title( $jemtr_method['method_title'] );
                                        $zone_status_array[$method_id] = $method_name;
                                    }
                                
                                }
                            }
                        }
                    
                    }
                }
                if ( class_exists( 'WC_apg_shipping' ) ) {
                    if ( in_array( $val->id, $whsm_cslist['apg_shipping'], true ) ) {
                        
                        if ( 'yes' === $val->enabled && !empty($val->instance_id) ) {
                            $apg_method = get_option( 'woocommerce_' . $val->id . '_' . $val->instance_id . '_settings' );
                            $method_name = $the_zone['zone_name'] . ' - ' . $val->method_title . ' > ' . $apg_method['title'];
                            $method_id = $val->id . ':' . $val->instance_id;
                            $zone_status_array[$method_id] = $method_name;
                        }
                    
                    }
                }
            }
        }
        // Include default zone shipping methods
        $default_zone = new WC_Shipping_Zone( 0 );
        // ADD ZONE "0" MANUALLY
        $default_zone_name = $default_zone->get_zone_name();
        $default_zone_shipping_methods = $default_zone->get_shipping_methods();
        if ( !empty($default_zone_shipping_methods) && is_array( $default_zone_shipping_methods ) ) {
            foreach ( $default_zone_shipping_methods as $default_zone_shipping_method ) {
                $method_user_title = $default_zone_shipping_method->get_title();
                // e.g. "Flat Rate"
                $method_rate_id = $default_zone_shipping_method->get_rate_id();
                // e.g. "flat_rate:18"
                $zone_status_array[$method_rate_id] = $default_zone_name . ' - ' . $method_user_title;
            }
        }
        $default_woo_list = array();
        foreach ( $zone_status_array as $unique_shipping => $zone_id ) {
            $default_woo_list[$unique_shipping] = $zone_id;
        }
        $get_other_shipping_method_list = array();
        $get_shipping_methods_list = WC()->shipping()->get_shipping_methods();
        if ( !empty($get_shipping_methods_list) && count( $get_shipping_methods_list ) > 0 ) {
            foreach ( $get_shipping_methods_list as $get_object ) {
                if ( class_exists( 'Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro' ) ) {
                    
                    if ( in_array( $get_object->id, $whsm_cslist['whsm_aflrsfw'], true ) ) {
                        $afrd = $this->whsma_custom_other_plugin_query( $get_object->id, 'afrd', $args );
                        if ( !empty($afrd) ) {
                            foreach ( $afrd as $afrd_id => $afrd_value ) {
                                $get_other_shipping_method_list[$afrd_id] = $afrd_value;
                            }
                        }
                    }
                
                }
                if ( class_exists( 'WbsVendors_DgmWpPluginBootstrapGuard' ) ) {
                    
                    if ( in_array( $get_object->id, $whsm_cslist['whsm_wbs'], true ) ) {
                        $wbs = $this->whsma_custom_other_plugin_query( $get_object->id, 'wbs', $args );
                        if ( !empty($wbs) ) {
                            foreach ( $wbs as $wbs_id => $wbs_value ) {
                                $get_other_shipping_method_list[$wbs_id] = $wbs_value;
                            }
                        }
                    }
                
                }
            }
        }
        $combine_shipping_method_list = $default_woo_list + $get_other_shipping_method_list;
        return $combine_shipping_method_list;
    }
    
    /**
     * Shipping list from other plugin
     *
     * @param int    $plugins_unique_id
     * @param string $other_plugin
     *
     * @return array $tr_shipping_list
     *
     * @since 1.0.0
     *
     */
    public function whsma_custom_other_plugin_query( $plugins_unique_id, $other_plugin, $args )
    {
        $tr_shipping_list = array();
        if ( 'afrd' === $other_plugin ) {
            
            if ( class_exists( 'Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro' ) ) {
                $get_all_shipping = array();
                
                if ( class_exists( 'Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin' ) ) {
                    $adrsfwp = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
                    $get_all_shipping = $adrsfwp::afrsm_pro_get_shipping_method( 'not_list' );
                }
                
                if ( !empty($get_all_shipping) ) {
                    foreach ( $get_all_shipping as $get_all_shipping_data ) {
                        $unique_shipping_id = $plugins_unique_id . ':' . $get_all_shipping_data->ID;
                        $sm_cost = get_post_meta( $get_all_shipping_data->ID, 'sm_product_cost', true );
                        
                        if ( 'advanced' === $args ) {
                            if ( !empty($sm_cost) || '0' !== $sm_cost ) {
                                $tr_shipping_list[$unique_shipping_id] = $get_all_shipping_data->post_title;
                            }
                        } else {
                            $tr_shipping_list[$unique_shipping_id] = $get_all_shipping_data->post_title;
                        }
                    
                    }
                }
            }
        
        }
        if ( 'wbs' === $other_plugin ) {
            /* Pro-Free */
            
            if ( class_exists( 'WbsVendors_DgmWpPluginBootstrapGuard' ) ) {
                $unique_name = new \Wbs\Plugin( wp_normalize_path( WP_PLUGIN_DIR . '/weight-based-shipping-for-woocommerce/plugin.php' ) );
                $wbs_config = get_option( 'wbs_config' );
                if ( isset( $wbs_config ) && is_array( $wbs_config ) ) {
                    if ( true === $wbs_config['enabled'] ) {
                        foreach ( $wbs_config['rules'] as $wbs_value ) {
                            if ( !empty($wbs_value) ) {
                                foreach ( $wbs_value as $wbs_meta_value ) {
                                    if ( !empty($wbs_meta_value['title']) ) {
                                        
                                        if ( 'advanced' === $args ) {
                                            $idParts = array();
                                            $hash = substr( md5( $wbs_meta_value['title'] ), 0, 8 );
                                            $idParts[] = $hash;
                                            $slug = strtolower( $wbs_meta_value['title'] );
                                            $slug = preg_replace( '/[^a-z0-9]+/', '_', $slug );
                                            $slug = preg_replace( '/_+/', '_', $slug );
                                            $slug = trim( $slug, '_' );
                                            if ( $slug !== '' ) {
                                                $idParts[] = $slug;
                                            }
                                            $id = implode( '_', $idParts );
                                            $unique_shipping_id = $unique_name::ID . ':' . $id;
                                            $tr_shipping_list[$unique_shipping_id] = $wbs_meta_value['title'];
                                            // }
                                        } else {
                                            $idParts = array();
                                            $hash = substr( md5( $wbs_meta_value['title'] ), 0, 8 );
                                            $idParts[] = $hash;
                                            $slug = strtolower( $wbs_meta_value['title'] );
                                            $slug = preg_replace( '/[^a-z0-9]+/', '_', $slug );
                                            $slug = preg_replace( '/_+/', '_', $slug );
                                            $slug = trim( $slug, '_' );
                                            if ( $slug !== '' ) {
                                                $idParts[] = $slug;
                                            }
                                            $id = implode( '_', $idParts );
                                            $unique_shipping_id = $unique_name::ID . ':' . $id;
                                            $tr_shipping_list[$unique_shipping_id] = $wbs_meta_value['title'];
                                        }
                                    
                                    }
                                }
                            }
                        }
                    }
                }
            }
        
        }
        return $tr_shipping_list;
    }
    
    /**
     * Compatible shipping plugin list
     *
     * @return array $retun_pram
     *
     * @since 1.0.0
     *
     */
    public function whsm_compatible_shipping_plugin_list()
    {
        $whsm_default_cslist = array(
            'flat_rate',
            'free_shipping',
            'local_pickup',
            'per_product'
        );
        $whsm_all_cslist = array(
            'flat_rate',
            'free_shipping',
            'local_pickup',
            'per_product',
            'advanced_flat_rate_shipping',
            'table_rate',
            'flexible_shipping_single',
            'wbs',
            'jem_table_rate',
            'apg_shipping'
        );
        $whsm_aflrsfw = array( 'advanced_flat_rate_shipping' );
        $whsm_tbl_rate = array( 'table_rate' );
        $whsm_fs = array( 'flexible_shipping_single' );
        $whsm_wbs = array( 'wbs' );
        $whsm_jem_table_rate = array( 'jem_table_rate' );
        $whsm_apg_shipping = array( 'apg_shipping' );
        $retun_pram = array(
            'default_shipping'    => $whsm_default_cslist,
            'compatible_shipping' => $whsm_all_cslist,
            'whsm_aflrsfw'        => $whsm_aflrsfw,
            'whsm_tbl_rate'       => $whsm_tbl_rate,
            'whsm_fs'             => $whsm_fs,
            'whsm_wbs'            => $whsm_wbs,
            'jem_table_rate'      => $whsm_jem_table_rate,
            'apg_shipping'        => $whsm_apg_shipping,
        );
        return $retun_pram;
    }
    
    /**
     * Admin footer review
     *
     * @since 1.0.0
     */
    public function whsma_admin_footer_review()
    {
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/hide-shipping-method-for-woocommerce/#reviews' );
        $html = sprintf(
            '%s<strong>%s</strong>%s<a href=%s target="_blank">%s</a>',
            esc_html__( 'If you like ', 'woo-hide-shipping-methods' ),
            esc_html__( 'Hide Shipping Method For WooCommerce ', 'woo-hide-shipping-methods' ),
            esc_html__( 'plugin, please leave us &#9733;&#9733;&#9733;&#9733;&#9733; ratings on ', 'woo-hide-shipping-methods' ),
            $url,
            esc_html__( 'DotStore', 'woo-hide-shipping-methods' )
        );
        echo  wp_kses_post( $html ) ;
    }
    
    /**
     * Fetch slug based on id
     *
     * @since    1.3.2
     */
    public function whsm_fetch_slug( $id_array, $condition )
    {
        $return_array = array();
        if ( !empty($id_array) ) {
            foreach ( $id_array as $key => $ids ) {
                if ( !empty($ids) ) {
                    
                    if ( 'product' === $condition || 'variableproduct' === $condition || 'cpp' === $condition || 'zone' === $condition ) {
                        $get_posts = get_post( $ids );
                        if ( !empty($get_posts) ) {
                            $return_array[] = $get_posts->post_name;
                        }
                    } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                        $term = get_term( $ids, 'product_cat' );
                        if ( !empty($term) ) {
                            $return_array[] = $term->slug;
                        }
                    } elseif ( 'tag' === $condition ) {
                        $tag = get_term( $ids, 'product_tag' );
                        if ( !empty($tag) ) {
                            $return_array[] = $tag->slug;
                        }
                    } elseif ( 'shipping_class' === $condition ) {
                        $shipping_class = get_term( $key, 'product_shipping_class' );
                        if ( !empty($shipping_class) ) {
                            $return_array[$shipping_class->slug] = $ids;
                        }
                    } elseif ( 'cpsc' === $condition ) {
                        $return_array[] = $ids;
                    } elseif ( 'cpp' === $condition ) {
                        $cpp_posts = get_post( $ids );
                        if ( !empty($cpp_posts) ) {
                            $return_array[] = $cpp_posts->post_name;
                        }
                    } else {
                        $return_array[] = $ids;
                    }
                
                }
            }
        }
        return $return_array;
    }
    
    /**
     * Fetch id based on slug
     *
     * @since    1.3.2
     */
    public function whsm_fetch_id( $slug_array, $condition )
    {
        $return_array = array();
        if ( !empty($slug_array) ) {
            foreach ( $slug_array as $slugs ) {
                if ( !empty($slugs) ) {
                    
                    if ( 'product' === $condition ) {
                        $post = get_page_by_path( $slugs, OBJECT, 'product' );
                        // phpcs:ignore
                        
                        if ( !empty($post) ) {
                            $id = $post->ID;
                            $return_array[] = $id;
                        }
                    
                    } elseif ( 'variableproduct' === $condition ) {
                        $args = array(
                            'post_type' => 'product_variation',
                            'fields'    => 'ids',
                            'name'      => $slugs,
                        );
                        $variable_posts = get_posts( $args );
                        // phpcs:ignore
                        if ( !empty($variable_posts) ) {
                            foreach ( $variable_posts as $val ) {
                                $return_array[] = $val;
                            }
                        }
                    } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                        $term = get_term_by( 'slug', $slugs, 'product_cat' );
                        if ( !empty($term) ) {
                            $return_array[] = $term->term_id;
                        }
                    } elseif ( 'tag' === $condition ) {
                        $term_tag = get_term_by( 'slug', $slugs, 'product_tag' );
                        if ( !empty($term_tag) ) {
                            $return_array[] = $term_tag->term_id;
                        }
                    } elseif ( 'shipping_class' === $condition || 'cpsc' === $condition ) {
                        $shipping_class = get_term_by( 'slug', $slugs, 'product_shipping_class' );
                        if ( !empty($shipping_class) ) {
                            $return_array[$shipping_class->term_id] = $slugs;
                        }
                    } elseif ( 'cpp' === $condition ) {
                        $args = array(
                            'post_type' => array( 'product_variation', 'product' ),
                            'name'      => $slugs,
                        );
                        $variable_posts = get_posts( $args );
                        // phpcs:ignore
                        if ( !empty($variable_posts) ) {
                            foreach ( $variable_posts as $val ) {
                                $return_array[] = $val->ID;
                            }
                        }
                    } elseif ( 'zone' === $condition ) {
                        $post = get_page_by_path( $slugs, OBJECT, 'wc_afrsm_zone' );
                        // phpcs:ignore
                        
                        if ( !empty($post) ) {
                            $id = $post->ID;
                            $return_array[] = $id;
                        }
                    
                    } else {
                        $return_array[] = $slugs;
                    }
                
                }
            }
        }
        return $return_array;
    }
    
    /**
     * Get and save plugin setup wizard data
     * 
     * @since 1.4.0
     * 
     */
    public function whsm_plugin_setup_wizard_submit()
    {
        check_ajax_referer( 'wizard_ajax_nonce', 'nonce' );
        $survey_list = filter_input( INPUT_GET, 'survey_list', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !empty($survey_list) && 'Select One' !== $survey_list ) {
            update_option( 'whsm_where_hear_about_us', $survey_list );
        }
        wp_die();
    }
    
    /**
     * Send setup wizard data to sendinblue
     * 
     * @since 1.4.0
     * 
     */
    public function whsm_send_wizard_data_after_plugin_activation()
    {
        $send_wizard_data = filter_input( INPUT_GET, 'send-wizard-data', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( isset( $send_wizard_data ) && !empty($send_wizard_data) ) {
            
            if ( !get_option( 'whsm_data_submited_in_sendiblue' ) ) {
                $whsm_where_hear = get_option( 'whsm_where_hear_about_us' );
                $get_user = whsm_fs()->get_user();
                $data_insert_array = array();
                if ( isset( $get_user ) && !empty($get_user) ) {
                    $data_insert_array = array(
                        'user_email'              => $get_user->email,
                        'ACQUISITION_SURVEY_LIST' => $whsm_where_hear,
                    );
                }
                $feedback_api_url = WHSM_STORE_URL . 'wp-json/dotstore-sendinblue-data/v2/dotstore-sendinblue-data?' . wp_rand();
                $query_url = $feedback_api_url . '&' . http_build_query( $data_insert_array );
                
                if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
                    $response = vip_safe_wp_remote_get(
                        $query_url,
                        3,
                        1,
                        20
                    );
                } else {
                    $response = wp_remote_get( $query_url );
                }
                
                
                if ( !is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
                    update_option( 'whsm_data_submited_in_sendiblue', '1' );
                    delete_option( 'whsm_where_hear_about_us' );
                }
            
            }
        
        }
    }
    
    /**
     * Get dynamic promotional bar of plugin
     *
     * @param   String  $plugin_slug  slug of the plugin added in the site option
     * @since   1.4.0
     * 
     * @return  null
     */
    public function whsm_get_promotional_bar( $plugin_slug = '' )
    {
        $promotional_bar_upi_url = WHSM_STORE_URL . 'wp-json/dpb-promotional-banner/v2/dpb-promotional-banner?' . wp_rand();
        $promotional_banner_request = wp_remote_get( $promotional_bar_upi_url );
        //phpcs:ignore
        
        if ( empty($promotional_banner_request->errors) ) {
            $promotional_banner_request_body = $promotional_banner_request['body'];
            $promotional_banner_request_body = json_decode( $promotional_banner_request_body, true );
            echo  '<div class="dynamicbar_wrapper">' ;
            if ( !empty($promotional_banner_request_body) && is_array( $promotional_banner_request_body ) ) {
                foreach ( $promotional_banner_request_body as $promotional_banner_request_body_data ) {
                    $promotional_banner_id = $promotional_banner_request_body_data['promotional_banner_id'];
                    $promotional_banner_cookie = $promotional_banner_request_body_data['promotional_banner_cookie'];
                    $promotional_banner_image = $promotional_banner_request_body_data['promotional_banner_image'];
                    $promotional_banner_description = $promotional_banner_request_body_data['promotional_banner_description'];
                    $promotional_banner_button_group = $promotional_banner_request_body_data['promotional_banner_button_group'];
                    $dpb_schedule_campaign_type = $promotional_banner_request_body_data['dpb_schedule_campaign_type'];
                    $promotional_banner_target_audience = $promotional_banner_request_body_data['promotional_banner_target_audience'];
                    
                    if ( !empty($promotional_banner_target_audience) ) {
                        $plugin_keys = array();
                        
                        if ( is_array( $promotional_banner_target_audience ) ) {
                            foreach ( $promotional_banner_target_audience as $list ) {
                                $plugin_keys[] = $list['value'];
                            }
                        } else {
                            $plugin_keys[] = $promotional_banner_target_audience['value'];
                        }
                        
                        $display_banner_flag = false;
                        if ( in_array( 'all_customers', $plugin_keys, true ) || in_array( $plugin_slug, $plugin_keys, true ) ) {
                            $display_banner_flag = true;
                        }
                    }
                    
                    if ( true === $display_banner_flag ) {
                        
                        if ( 'default' === $dpb_schedule_campaign_type ) {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            
                            if ( empty($banner_cookie_show) && empty($banner_cookie_visible_once) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes', time() + 86400 * 7 );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            
                            if ( !empty($banner_cookie_show) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                
                                if ( empty($banner_cookie) && 'yes' !== $banner_cookie ) {
                                    ?>
                            	<div class="dpb-popup <?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>">
                                    <?php 
                                    
                                    if ( !empty($promotional_banner_image) ) {
                                        ?>
                                        <img src="<?php 
                                        echo  esc_url( $promotional_banner_image ) ;
                                        ?>"/>
                                        <?php 
                                    }
                                    
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo  wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) ) ;
                                    if ( !empty($promotional_banner_button_group) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo  esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ) ;
                                            ?>" target="_blank"><?php 
                                            echo  esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ) ;
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                    	</p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo  esc_attr( $promotional_banner_id ) ;
                                    ?>" data-popup-name="<?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_attr( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            
                            }
                        
                        } else {
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag = false;
                            
                            if ( empty($banner_cookie_show) && empty($banner_cookie_visible_once) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' );
                                //phpcs:ignore
                                $flag = true;
                            }
                            
                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            
                            if ( !empty($banner_cookie_show) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = ( isset( $banner_cookie ) ? $banner_cookie : '' );
                                
                                if ( empty($banner_cookie) && 'yes' !== $banner_cookie ) {
                                    ?>
                    			<div class="dpb-popup <?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>">
                                    <?php 
                                    
                                    if ( !empty($promotional_banner_image) ) {
                                        ?>
                                            <img src="<?php 
                                        echo  esc_url( $promotional_banner_image ) ;
                                        ?>"/>
                                        <?php 
                                    }
                                    
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php 
                                    echo  wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) ) ;
                                    if ( !empty($promotional_banner_button_group) ) {
                                        foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                            ?>
                                                    <a href="<?php 
                                            echo  esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ) ;
                                            ?>" target="_blank"><?php 
                                            echo  esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ) ;
                                            ?></a>
                                                    <?php 
                                        }
                                    }
                                    ?>
                                        </p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php 
                                    echo  esc_attr( $promotional_banner_id ) ;
                                    ?>" data-popup-name="<?php 
                                    echo  ( isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner' ) ;
                                    ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php 
                                }
                            
                            }
                        
                        }
                    
                    }
                }
            }
            echo  '</div>' ;
        }
    
    }

}