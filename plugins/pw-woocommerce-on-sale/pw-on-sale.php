<?php
/**
 * Plugin Name: PW WooCommerce On Sale!
 * Plugin URI: https://www.pimwick.com/pw-woocommerce-on-sale/
 * Description: Simply the FASTEST way to schedule sales in WooCommerce!
 * Version: 1.33
 * Author: Pimwick, LLC
 * Author URI: https://www.pimwick.com
 * WC requires at least: 4.0
 * WC tested up to: 8.2
*/

/*
Copyright (C) Pimwick, LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'PW_ON_SALE_REQUIRES_PRIVILEGE', 'manage_woocommerce' );

if ( ! class_exists( 'PW_On_Sale' ) ) :

global $pw_on_sale;

final class PW_On_Sale {

    private $active_sales_cache = null;

    function __construct() {
        add_action( 'init', array( 'PW_On_Sale', 'register_post_types' ), 9 );
        add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
        add_action( 'woocommerce_init', array( $this, 'woocommerce_init' ) );

        // WooCommerce High Performance Order Storage (HPOS) compatibility declaration.
        add_action( 'before_woocommerce_init', function() {
            if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            }
        } );
    }

    function plugins_loaded() {
        load_plugin_textdomain( 'pimwick', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    function woocommerce_init() {
        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_action( 'wp_ajax_pw-on-sale-save', array( $this, 'ajax_save_sale' ) );
            add_action( 'wp_ajax_pw-on-sale-delete', array( $this, 'ajax_sale_delete' ) );
        }

        add_filter( 'woocommerce_product_is_on_sale', array( $this, 'woocommerce_product_is_on_sale' ), 10, 2 );
        add_filter( 'pwos_to_current_currency', array( $this, 'pwos_to_current_currency' ) );
        add_filter( 'pwos_to_default_currency', array( $this, 'pwos_to_default_currency' ) );

        if ( $this->wc_min_version( '3.0' ) ) {
            add_filter( 'woocommerce_product_get_price', array( $this, 'override_price' ), 10, 2 );
            add_filter( 'woocommerce_product_get_sale_price', array( $this, 'override_price' ), 10, 2 );
            add_filter( 'woocommerce_product_variation_get_price', array( $this, 'override_price' ), 10, 2 );
            add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'override_price' ), 10, 2 );
            add_filter( 'woocommerce_variation_prices', array( $this, 'woocommerce_variation_prices' ), 10, 2 );
        } else {
            add_filter( 'woocommerce_get_price', array( $this, 'override_price' ), 10, 2 );
            add_filter( 'woocommerce_get_sale_price', array( $this, 'override_price' ), 10, 2 );
            add_filter( 'woocommerce_variation_prices', array( $this, 'woocommerce_variation_prices' ), 10, 2 );
        }
    }

    function get_active_sales() {
        if ( is_null( $this->active_sales_cache ) ) {
            $this->active_sales_cache = array();

            $all_sales = get_posts( array(
                'posts_per_page' => -1,
                'post_type' => 'pw_on_sale',
                'post_status' => 'publish'
            ) );

            foreach ( $all_sales as $sale ) {

                // Before parsing the begin/end dates and times, switch over to their local timezone.
                $configured_timezone = wc_timezone_string();
                if ( !empty( $configured_timezone ) && $configured_timezone != '+00:00' ) {
                    $original_timezone = date_default_timezone_get();
                    date_default_timezone_set( $configured_timezone );
                }

                $sale->begin_datetime = strtotime( get_post_meta( $sale->ID, 'begin_date', true ) . ' ' . get_post_meta( $sale->ID, 'begin_time', true ) );
                $sale->end_datetime = strtotime( get_post_meta( $sale->ID, 'end_date', true ) . ' ' . get_post_meta( $sale->ID, 'end_time', true ) );

                if ( time() < $sale->begin_datetime || time() > $sale->end_datetime ) {
                    continue;
                }

                // Now that we're done formatting, switch it back.
                if ( isset( $original_timezone ) ) {
                    date_default_timezone_set( $original_timezone );
                }

                $sale->discount_percentage = get_post_meta( $sale->ID, 'discount_percentage', true );

                $this->active_sales_cache[] = $sale;
            }
        }

        return $this->active_sales_cache;
    }

    function override_price( $value, $product ) {
        return $this->maybe_get_sale_price( $value, $product );
    }

    function woocommerce_variation_prices( $prices_array, $product ) {
        $sale = $this->get_active_sale();
        if ( false !== $sale ) {
            $regular_prices = $prices_array['regular_price'];

            foreach( $prices_array['price'] as $variation_id => &$price ) {
                $price = $this->get_discounted_price( $sale, $regular_prices[ $variation_id ] );
            }
        }

        return $prices_array;
    }

    function get_active_sale() {
        $active_sales = $this->get_active_sales();
        if ( count( $active_sales ) > 0 ) {
            return $active_sales[0];
        }

        return false;
    }

    function maybe_get_sale_price( $sale_price, $product ) {
        $regular_price = $product->get_regular_price();

        if ( is_a( $product, 'WC_Product_Variation' ) ) {
            if ( $this->wc_min_version( '3.0' ) ) {
                $product = wc_get_product( $product->get_parent_id() );
            } else {
                $product = $product->parent;
            }
        }

        $sale = $this->get_active_sale();
        if ( false !== $sale ) {
            $sale_price = $this->get_discounted_price( $sale, $regular_price );
        }

        return apply_filters( 'pwos_to_default_currency', $sale_price );
    }

    function get_discounted_price( $sale, $regular_price ) {
        if ( empty( $regular_price ) ) {
            return $regular_price;
        }

        return $regular_price * ( 1.0 - ( $sale->discount_percentage / 100 ) );
    }

    function woocommerce_product_is_on_sale( $is_on_sale, $product ) {
        if ( false !== $this->get_active_sale() ) {
            $is_on_sale = true;
        }

        return $is_on_sale;
    }

    public static function plugin_activate() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        PW_On_Sale::register_post_types();
    }

    public static function plugin_deactivate() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
    }

    public static function register_post_types() {
        if ( post_type_exists('pw_on_sale') ) {
            return;
        }

        $labels = array(
            'name'                  => _x( 'PW WooCommerce On Sale', 'Post Type General Name', 'pimwick' ),
            'singular_name'         => _x( 'PW WooCommerce On Sale', 'Post Type Singular Name', 'pimwick' ),
            'menu_name'             => __( 'PW On Sale!', 'pimwick' ),
            'name_admin_bar'        => __( 'PW On Sale!', 'pimwick' ),
            'archives'              => __( 'Sale archives', 'pimwick' ),
            'parent_item_colon'     => __( 'Parent PW On Sale!:', 'pimwick' ),
            'all_items'             => __( 'All sales', 'pimwick' ),
            'add_new_item'          => __( 'Add New sale', 'pimwick' ),
            'add_new'               => __( 'Create new sale', 'pimwick' ),
            'new_item'              => __( 'New sale', 'pimwick' ),
            'edit_item'             => __( 'Edit sale', 'pimwick' ),
            'update_item'           => __( 'Update sale', 'pimwick' ),
            'view_item'             => __( 'View sale', 'pimwick' ),
            'search_items'          => __( 'Search sales', 'pimwick' ),
            'not_found'             => __( 'Not found', 'pimwick' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'pimwick' ),
            'featured_image'        => __( 'Logo', 'pimwick' ),
            'set_featured_image'    => __( 'Set Logo', 'pimwick' ),
            'remove_featured_image' => __( 'Remove Logo', 'pimwick' ),
            'use_featured_image'    => __( 'Use as Logo', 'pimwick' ),
            'insert_into_item'      => __( 'Insert into item', 'pimwick' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'pimwick' ),
            'items_list'            => __( 'Sale list', 'pimwick' ),
            'items_list_navigation' => __( 'Sale list navigation', 'pimwick' ),
            'filter_items_list'     => __( 'Filter sale list', 'pimwick' ),
        );

        $args = array(
            'label'                 => __( 'PW On Sale!', 'pimwick' ),
            'description'           => __( 'PW On Sale!', 'pimwick' ),
            'labels'                => $labels,
            'supports'              => array( 'title' ),
            'show_ui'               => true,
            'show_in_menu'          => false,
            'has_archive'           => true
        );

        register_post_type( 'pw_on_sale', $args );
    }

    function admin_menu() {
        if ( empty ( $GLOBALS['admin_page_hooks']['pimwick'] ) ) {
            add_menu_page(
                'PW On Sale!',
                'Pimwick Plugins',
                PW_ON_SALE_REQUIRES_PRIVILEGE,
                'pimwick',
                array( $this, 'index' ),
                plugins_url( '/assets/images/pimwick-icon-120x120.png', __FILE__ ),
                6
            );

            add_submenu_page(
                'pimwick',
                'PW On Sale!',
                'Pimwick Plugins',
                PW_ON_SALE_REQUIRES_PRIVILEGE,
                'pimwick',
                array( $this, 'index' )
            );

            remove_submenu_page( 'pimwick', 'pimwick' );
        }

        add_submenu_page(
            'pimwick',
            'PW On Sale!',
            'PW On Sale!',
            PW_ON_SALE_REQUIRES_PRIVILEGE,
            'pw-on-sale',
            array( $this, 'index' )
        );
    }

    function index() {
        $data = get_plugin_data( __FILE__ );
        $version = $data['Version'];

        if ( isset( $_REQUEST['sale_id'] ) ) {
            $sale_id = absint( $_REQUEST['sale_id'] );

            if ( !empty( $sale_id ) ) {
                $pwos_sale = get_post( $sale_id );
                $pwos_sale->begin_date = get_post_meta( $sale_id, 'begin_date', true );
                $pwos_sale->begin_time = get_post_meta( $sale_id, 'begin_time', true );
                $pwos_sale->end_date = get_post_meta( $sale_id, 'end_date', true );
                $pwos_sale->end_time = get_post_meta( $sale_id, 'end_time', true );
                $pwos_sale->discount_percentage = get_post_meta( $sale_id, 'discount_percentage', true );
            }
        }

        require( 'ui/index.php' );
    }

    function wizard_field_required( $step, $value, $error_message ) {
        if ( empty( $value ) ) {
            $this->wizard_error( $step, $error_message );
        }
    }

    function wizard_error( $step, $message ) {
        $result['complete'] = false;
        $result['step'] = $step;
        $result['message'] = $message;

        wp_send_json( $result );
    }

    function ajax_save_sale() {
        $sale_id                                = absint( $_POST['sale_id'] );
        $title                                  = stripslashes( wc_clean( $_POST['title'] ) );
        $begin_date                             = wc_clean( $_POST['begin_date'] );
        $begin_time                             = wc_clean( $_POST['begin_time'] );
        $end_date                               = wc_clean( $_POST['end_date'] );
        $end_time                               = wc_clean( $_POST['end_time'] );
        $begin_datetime                         = "$begin_date $begin_time";
        $end_datetime                           = "$end_date $end_time";

        $discount_percentage                    = wc_format_decimal( $_POST['discount_percentage'] );

        //
        // BEGIN VALIDATION
        //
        $this->wizard_field_required( 1, $begin_date, 'Begin Date is required.' );
        $this->wizard_field_required( 1, $begin_time, 'Begin Time is required.' );
        $this->wizard_field_required( 1, $end_date, 'End Date is required.' );
        $this->wizard_field_required( 1, $end_time, 'End Time is required.' );
        if ( false === strtotime( $begin_datetime ) ) {
            $this->wizard_error( 1, "$begin_datetime is not a valid date and time." );
        }
        if ( false === strtotime( $end_datetime ) ) {
            $this->wizard_error( 1, "$end_datetime is not a valid date and time." );
        }
        if ( strtotime( $begin_datetime ) >= strtotime( $end_datetime ) ) {
            $this->wizard_error( 1, "Begin date must come before End date." );
        }
        $this->wizard_field_required( 2, $discount_percentage, 'Discount Percentage is required.' );
        $this->wizard_field_required( 3, $title, 'Title is required.' );
        //
        // END VALIDATION
        //

        if ( empty( $sale_id ) ) {
            $sale = array();
            $sale['post_type'] = 'pw_on_sale';
            $sale['post_status'] = 'publish';
            $sale['post_title'] = $title;

            $sale_id = wp_insert_post( $sale );
        } else {
            $sale = get_post( $sale_id );
            $sale->post_title = $title;
            wp_update_post( $sale );
        }

        if ( !is_wp_error( $sale_id ) ) {
            update_post_meta( $sale_id, 'begin_date', $begin_date );
            update_post_meta( $sale_id, 'begin_time', $begin_time );
            update_post_meta( $sale_id, 'end_date', $end_date );
            update_post_meta( $sale_id, 'end_time', $end_time );
            update_post_meta( $sale_id, 'discount_percentage', $discount_percentage );

            $result['complete'] = true;
            wp_send_json( $result );

        } else {
            $this->wizard_error( 1, $sale_id->get_error_message() );
        }
    }

    function ajax_sale_delete() {
        $sale_id = absint( $_POST['sale_id'] );
        wp_delete_post( $sale_id, true );
        wp_die();
    }

    function navigation_buttons( $step ) {
        global $pwos_step;

        $pwos_step = $step;

        require( 'ui/wizard/navigation_buttons.php' );
    }

    function admin_enqueue_scripts( $hook ) {
        global $wp_scripts;

        if ( !empty( $hook ) && substr( $hook, -strlen( 'pw-on-sale' ) ) === 'pw-on-sale' ) {
            wp_register_style( 'pw-on-sale-style', $this->relative_url( '/assets/css/style.css' ), array(), $this->version() );
            wp_enqueue_style( 'pw-on-sale-style' );

            wp_register_style( 'pw-on-sale-font-awesome', $this->relative_url( '/assets/css/font-awesome.min.css' ), array(), $this->version() );
            wp_enqueue_style( 'pw-on-sale-font-awesome' );

            wp_enqueue_script( 'pw-on-sale-script', $this->relative_url( '/assets/js/script.js' ), array( 'jquery' ), $this->version() );

            wp_register_style( 'jquery-ui-style', $this->relative_url( '/assets/css/jquery-ui-style.min.css' ), array(), $this->version() );
            wp_enqueue_style( 'jquery-ui-style' );

            wp_enqueue_script( 'jquery-ui-datepicker' );
        }

        wp_register_style( 'pw-on-sale-icon', plugins_url( '/assets/css/icon-style.css', __FILE__ ), array(), $this->version() );
        wp_enqueue_style( 'pw-on-sale-icon' );
    }

    function relative_url( $url ) {
        return plugins_url( $url, __FILE__ );
    }

    function require_file( $filename, $once = false ) {
        $relative_path = trailingslashit( __DIR__ ) . ltrim( $filename, '/' );
        if ( $once === true ) {
            require_once( $relative_path );
        } else {
            require( $relative_path );
        }
    }

    function version() {
        $data = get_plugin_data( __FILE__ );
        return $data['Version'];
    }

    function wc_min_version( $version ) {
        return version_compare( WC()->version, $version, ">=" );
    }

    function starts_with( $needle, $haystack ) {
        $length = strlen( $needle );
        return ( substr( $haystack, 0, $length ) === $needle );
    }

    /**
     * Source: http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list
     * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
     * placed under a 'children' member of their parent term.
     * @param Array   $cats     taxonomy term objects to sort
     * @param Array   $into     result array to put them in
     * @param integer $parentId the current parent ID to put them in
     */
    function sort_terms_hierarchicaly(array &$cats, array &$into, $parentId = 0) {
        foreach ( $cats as $i => $cat ) {
            if ( $cat->parent == $parentId ) {
                $into[$cat->term_id] = $cat;
                unset( $cats[$i] );
            }
        }

        foreach ( $into as $topCat ) {
            $topCat->children = array();
            $this->sort_terms_hierarchicaly( $cats, $topCat->children, $topCat->term_id );
        }
    }

    function hierarchical_select($categories, $selected_category_ids, $level = 0, $parent = NULL, $prefix = '') {
        foreach ( $categories as $category ) {
            $selected = selected( in_array( $category->term_id, $selected_category_ids ), true, false );
            echo "<option value='" . esc_attr( $category->term_id ) . "' $selected>$prefix " . esc_html( $category->name ) . "</option>\n";

            if ( $category->parent == $parent ) {
                $level = 0;
            }

            if ( count( $category->children ) > 0 ) {
                echo $this->hierarchical_select( $category->children, $selected_category_ids, ( $level + 1 ), $category->parent, "$prefix " . esc_html( $category->name ) . " &#8594;" );
            }
        }
    }

    function pwos_to_default_currency( $amount ) {
        // WooCommerce Currency Switcher by realmag777
        if ( isset( $GLOBALS['WOOCS'] ) ) {
            $cs = $GLOBALS['WOOCS'];
            $default_currency = false;
            $currencies = $cs->get_currencies();

            foreach ( $currencies as $currency ) {
                if ( $currency['is_etalon'] === 1 ) {
                    $default_currency = $currency;
                    break;
                }
            }

            if ( $default_currency ) {
                if ( $cs->current_currency != $default_currency['name'] ) {
                    $amount = (float) $cs->back_convert( $amount, $currencies[ $cs->current_currency ]['rate'] );
                }
            }
        }

        return $amount;
    }
}

register_activation_hook( __FILE__, array( 'PW_On_Sale', 'plugin_activate' ) );
register_deactivation_hook( __FILE__, array( 'PW_On_Sale', 'plugin_deactivate' ) );

$pw_on_sale = new PW_On_Sale();

endif;
