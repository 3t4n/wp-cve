<?php
/**
 * Plugin Name: Dokan - WPML Integration
 * Plugin URI: https://wedevs.com/
 * Description: WPML and Dokan compatible package
 * Version: 1.1.0
 * Author: weDevs
 * Author URI: https://wedevs.com/
 * Text Domain: dokan-wpml
 * WC requires at least: 5.5.0
 * WC tested up to: 8.6.1
 * Domain Path: /languages/
 * License: GPL2
 */

/**
 * Copyright (c) YEAR weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dokan_WPML class
 *
 * @class Dokan_WPML The class that holds the entire Dokan_WPML plugin
 */
class Dokan_WPML {

    /*
     * WordPress Endpoints text domain
     *
     * @var string
     */
    public  $wp_endpoints = 'WP Endpoints';

    /*
     * Appsero client
     *
     * @var string
     */
    protected $insights;

    /**
     * Constructor for the Dokan_WPML class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {
        register_activation_hook( __FILE__, [ $this, 'dependency_missing_notice' ] );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );

		// load all actions and filter under plugins loaded hooks
	    add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
    }

    /**
     * Initializes the Dokan_WPML() class
     *
     * Checks for an existing Dokan_WPML() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Dokan_WPML();
        }

        return $instance;
    }

	/**
	 * Execute on plugis loaded hooks
	 *
	 * @since 1.0.7 moved from constructor to plugins_loaded hook
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		if ( true !== $this->check_dependency() ) {
			return;
		}

		// load appsero tracker
		$this->appsero_init_tracker();
        add_action( 'before_woocommerce_init', [ $this, 'declare_woocommerce_feature_compatibility' ] );

		// Load all actions hook
		add_filter( 'dokan_forced_load_scripts', [ $this, 'load_scripts_and_style' ] );
		add_filter( 'dokan_force_load_extra_args', [ $this, 'load_scripts_and_style' ] );
		add_filter( 'dokan_seller_setup_wizard_url', [ $this, 'render_wmpl_home_url' ], 70 );
		add_filter( 'dokan_get_page_url', [ $this, 'reflect_page_url' ], 10, 4 );
		add_filter( 'dokan_get_terms_condition_url', [ $this, 'get_terms_condition_url' ], 10, 2 );
		add_filter( 'dokan_redirect_login', [ $this, 'redirect_if_not_login' ], 90 );
		add_filter( 'dokan_force_page_redirect', [ $this, 'force_redirect_page' ], 90, 2 );

		// Load all filters hook
        add_filter('sanitize_user_meta_product_package_id', [ $this, 'set_subscription_pack_id_in_base_language' ], 10, 3 );
        add_filter('dokan_vendor_subscription_package_title', [ $this, 'vendor_subscription_pack_title_translation' ], 10, 2 );
        add_filter('dokan_vendor_subscription_package_id', [ $this, 'get_product_id_in_base_language' ] );
		add_filter( 'dokan_get_navigation_url', [ $this, 'load_translated_url' ], 10, 2 );
		add_filter( 'body_class', [ $this, 'add_dashboard_template_class_if_wpml' ], 99 );
		add_filter( 'dokan_get_current_page_id', [ $this, 'dokan_set_current_page_id' ] );
		add_filter( 'dokan_get_translated_page_id', [ $this, 'dokan_get_translated_page_id' ] );
		add_action( 'wp_head', [ $this, 'dokan_wpml_remove_fix_fallback_links' ] );

		add_action( 'dokan_store_page_query_filter', [ $this, 'load_store_page_language_switcher_filter' ], 10, 2 );
		add_filter( 'dokan_dashboard_nav_settings_key', [ $this, 'filter_dashboard_settings_key' ] );
		add_filter( 'dokan_dashboard_nav_menu_key', [ $this, 'filter_dashboard_settings_key' ] );
		add_filter( 'dokan_dashboard_nav_submenu_key', [ $this, 'filter_dashboard_settings_key' ] );
		add_filter( 'wcml_vendor_addon_configuration', [ $this, 'add_vendor_capability' ] );
        add_filter('icl_lang_sel_copy_parameters', [ $this, 'set_language_switcher_copy_param' ] );

		add_action( 'init', [ $this, 'fix_store_category_query_arg' ], 10 );
		add_action( 'init', [ $this, 'load_wpml_admin_post_actions' ], 10 );
		add_action( 'dokan_product_change_status_after_save', [ $this, 'change_product_status' ], 10, 2 );
		add_action( 'dokan_product_status_revert_after_save', [ $this, 'change_product_status' ], 10, 2 );

        // Single string translation.
        add_action( 'dokan_pro_register_shipping_status', [ $this, 'register_shipping_status_single_string' ] );
        add_action( 'dokan_pro_register_abuse_report_reason', [ $this, 'register_abuse_report_single_string' ] );
        add_action( 'dokan_pro_register_rms_reason', [ $this, 'register_rma_single_string' ] );
        add_filter( 'dokan_pro_shipping_status', [ $this, 'get_translated_shipping_status' ] );
        add_filter( 'dokan_pro_abuse_report_reason', [ $this, 'get_translated_abuse_report_reason' ] );
        add_filter( 'dokan_pro_rma_reason', [ $this, 'get_translated_rma_reason' ] );
	}

	/**
	 * Initialize the plugin tracker
	 *
	 * @since 1.0.7
	 *
	 * @return void
	 */
	public function appsero_init_tracker() {
		$client = new \Appsero\Client( 'f7973783-e0d0-4d56-bbba-229e5581b0cd', 'Dokan - WPML Integration', __FILE__ );

		$this->insights = $client->insights();

		$this->insights->init_plugin();
	}

	/**
     * Print error notice if dependency not active
     *
     * @since 1.0.1
     *
     * @return void
     */
    public function dependency_missing_notice() {
        deactivate_plugins( plugin_basename( __FILE__ ) );

		$missing_dependency = $this->check_dependency();
        if ( is_wp_error( $missing_dependency ) ) {
            $message = '<div class="error"><p>' . $missing_dependency->get_error_message() . '</p></div>';
            wp_die( $message );
        }
    }

	/**
	 * Check if dependency is active
	 *
	 * @since 1.0.7
	 *
	 * @return WP_Error|bool
	 */
	public function check_dependency() {
		if ( ! class_exists( 'WeDevs_Dokan' ) ) {
            // translators: %1$s: opening anchor tag, %2$s: closing anchor tag
			$error = sprintf( __( '<b>Dokan - WPML Integration</b> requires %1$s Dokan plugin %2$s to be installed & activated!', 'dokan-wpml' ), '<a target="_blank" href="https://wedevs.com/products/plugins/dokan/">', '</a>' );
			return new WP_Error( 'doakn_wpml_dependency_missing', $error );
		}

		if ( ! class_exists( 'SitePress' ) ) {
            // translators: %1$s: opening anchor tag, %2$s: closing anchor tag
			$error = sprintf( __( '<b>Dokan - WPML Integration</b> requires %1$s WPML Multilingual CMS %2$s to be installed & activated!', 'dokan-wpml' ), '<a target="_blank" href="https://wpml.org/">', '</a>' );
			return new WP_Error( 'doakn_wpml_dependency_missing', $error );
		}

		return true;
	}

	/**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'dokan-wpml', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Redirect seller setup wizerd into translated url
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function render_wmpl_home_url( $url ) {
        $translated_url = apply_filters( 'wpml_home_url', $url );
        return add_query_arg( array( 'page' => 'dokan-seller-setup' ), $translated_url );
    }

    /**
     * Load custom wpml translated page url
     *
     * @since 1.0.0
     *
     * @param  string $url
     * @param  string $name
     *
     * @return string
     */
    public function load_translated_url( $url, $name ) {
        $current_lang = apply_filters( 'wpml_current_language', null );

        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $url;
        }

        if ( ! empty( $name ) ) {
            if ( $current_lang ) {
                $name_arr = explode( '/', $name );

                if ( isset( $name_arr[1] ) ) {
                    $name = apply_filters( 'wpml_translate_single_string', $name_arr[0], $this->wp_endpoints, $name_arr[0], $current_lang ) . '/' . $name_arr[1];
                } else {
                    $get_name = ( ! empty( $name_arr[0] ) ) ? $name_arr[0] : $name;
                    $name     = apply_filters( 'wpml_translate_single_string', $get_name, $this->wp_endpoints, $get_name, $current_lang );
                }
            }

            $url = $this->get_dokan_url_for_language( ICL_LANGUAGE_CODE, $name . '/' );

        } else {
            $url = $this->get_dokan_url_for_language( ICL_LANGUAGE_CODE );
        }

        return $url;
    }

	/**
	 * @param string $endpoint
	 *
	 * @return string
	 */
    private function translate_endpoint( $endpoint ) {
    	return apply_filters( 'wpml_translate_single_string', $endpoint, $this->wp_endpoints, $endpoint );
    }

    /**
     * Reflect page url
     *
     * @since 1.0.1
     *
     * @return string
     */
    public function reflect_page_url( $url, $page_id, $context, $subpage ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $url;
        }

        $page_id = wpml_object_id_filter( $page_id, 'page', true, ICL_LANGUAGE_CODE );

        $url = get_permalink( $page_id );

        if ( $subpage ) {
            $subpages    = explode( '/', $subpage );
            $subpages[0] = $this->translate_endpoint( $subpages[0] );
            $subpage     = implode( '/', $subpages );
            $url         = function_exists( 'dokan_add_subpage_to_url' ) ? dokan_add_subpage_to_url( $url, $subpage ) : $url;
        }

        return $url;
    }

    /**
     * Get terms and condition page url
     *
     * @since 1.0.1
     *
     * @return string
     */
    public function get_terms_condition_url( $url, $page_id ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $url;
        }

        $page_id = wpml_object_id_filter( $page_id, 'page', true, ICL_LANGUAGE_CODE );

        return get_permalink( $page_id );
    }

    /**
     * Redirect if not login
     *
     * @since 1.0.1
     *
     * @return string
     */
    public function redirect_if_not_login( $url ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $url;
        }

        $page_id      = wc_get_page_id( 'myaccount' );
        $lang_post_id = wpml_object_id_filter( $page_id, 'page', true, ICL_LANGUAGE_CODE );

        return get_permalink( $lang_post_id );
    }

    /**
     * Undocumented function
     *
     * @since 1.0.1
     *
     * @return bool
     */
    public function force_redirect_page( $flag, $page_id ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return false;
        }

        $lang_post_id = wpml_object_id_filter( $page_id, 'page', true, ICL_LANGUAGE_CODE );

        if ( is_page( $lang_post_id ) ) {
            return true;
        }

        return false;
    }

    /**
     * Filter dokan navigation url for specific language
     *
     * @since 1.0.0
     *
     * @param  string $language
     * @param  string $name
     *
     * @return string [$url]
     */
    public function get_dokan_url_for_language( $language, $name = '' ) {
        $post_id      = $this->get_raw_option( 'dashboard', 'dokan_pages' );
        $lang_post_id = '';

        if ( function_exists( 'wpml_object_id_filter' ) ) {
            $lang_post_id = wpml_object_id_filter( $post_id, 'page', true, $language );
        }

        if ( (int) $lang_post_id !== 0 ) {
            $url = get_permalink( $lang_post_id );
        } else {
            $url = apply_filters( 'wpml_home_url', get_option( 'home' ) );
        }

        if ( $name ) {
	        $urlParts         = wp_parse_url( $url );
	        $urlParts['path'] = $urlParts['path'] . $name;
	        $url              = http_build_url( '', $urlParts );
        }

        return $url;
    }

    /**
     * Set Language switcher copy param
     *
     * @since 1.0.11
     *
     * @param array $params Copy params.
     *
     * @return array
     */
    public function set_language_switcher_copy_param( $params ) {
        $dokan_params = [
            'product_listing_search',
            '_product_listing_filter_nonce',
            'product_search_name',
            'product_cat',
            'post_status',
            'date',
            'product_type',
            'pagenum',
            'product_id',
            'action',
            '_dokan_edit_product_nonce',
            'customer_id',
            'search',
            'order_date_start',
            'order_date_end',
            'order_status',
            'dokan_order_filter',
            'seller_order_filter_nonce',
            'order_id',
            '_wpnonce',
            'order_date',
            'security',
            'subscription_id',
            'coupons_type',
            'post',
            'view',
            'coupon_nonce_url',
            'delivery_type_filter',
            'chart',
            'start_date_alt',
            'start_date',
            'end_date',
            'end_date_alt',
            'dokan_report_filter_nonce',
            'dokan_report_filter',
            'comment_status',
            'type',
            '_withdraw_link_nonce',
            'status',
            'request',
            'staff_id',
            'booking_id',
            'booking_status',
            'calendar_month',
            'tab',
            'filter_bookings',
            'calendar_year',
            'id',
            'tab',
            'step',
            'file',
            'delimiter',
            'character_encoding',
            'products-imported',
            'products-imported-variations',
            'products-failed',
            'products-updated',
            'products-skipped',
            'file-name',
            'ticket_start_date',
            'ticket_end_date',
            'ticket_keyword',
            'ticket_status',
            'dokan-support-listing-search-nonce',
        ];

        return array_merge( $params, $dokan_params );
    }

    /**
     * Add Dokan Dashboard body class when change language
     *
     * @since 1.0.0
     *
     * @param array $classes
     */
    public function add_dashboard_template_class_if_wpml( $classes ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $classes;
        }

        global $post;

        if ( ! is_object( $post ) ) {
            return $classes;
        }

        $page_id         = $this->get_raw_option( 'dashboard', 'dokan_pages' );
        $current_page_id = wpml_object_id_filter( $post->ID, 'page', true, wpml_get_default_language() );

        if ( ( $current_page_id == $page_id ) ) {
            $classes[] = 'dokan-dashboard';
        }

        return $classes;
    }

    /**
     * Load All dashboard styles and scripts
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function load_scripts_and_style() {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return false;
        }

        global $post;

        if ( ! is_object( $post ) ) {
            return false;
        }

        $page_id         = (int) $this->get_raw_option( 'dashboard', 'dokan_pages' );
        $current_page_id = (int) wpml_object_id_filter( $post->ID, 'page', true, wpml_get_default_language() );

        if ( ( $current_page_id === $page_id ) || ( get_query_var( 'edit' ) && is_singular( 'product' ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * Dokan set current page id
     *
     * @since 1.0.2
     *
     * @param  int page_id
     *
     * @return int
     */
    public function dokan_set_current_page_id( $page_id ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $page_id;
        }

        return wpml_object_id_filter( $page_id, 'page', true, wpml_get_default_language() );
    }

    /**
     * Dokan get translated page id.
     *
     * @since 1.0.9
     *
     * @param  int $page_id Page ID to be translated.
     *
     * @return int
     */
    public function dokan_get_translated_page_id( $page_id ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $page_id;
        }

        return wpml_object_id_filter( $page_id, 'page', true, ICL_LANGUAGE_CODE );
    }


    /**
     * Store Vendor Subscription pack in default language.
     *
     * @since 1.0.11
     *
     * @param mixed $meta_value Meta Value
     * @param string $meta_key Meta Key
     * @param string $object_type Object Type
     *
     * @return int
     */
    public function set_subscription_pack_id_in_base_language( $meta_value, $meta_key, $object_type ) {
        if ( 'product_package_id' !== $meta_key || 'user' !== $object_type ) {
            return $meta_value;
        }

        return $this->get_product_id_in_base_language( absint( $meta_value ) );
    }
    /**
     * Dokan get base product id from translated product id.
     *
     * @since 1.0.11
     *
     * @param int $product_id Product ID.
     *
     * @return int
     */
    public function get_product_id_in_base_language( $product_id ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $product_id;
        }

        $default_lang = apply_filters('wpml_default_language', null );

        return wpml_object_id_filter( $product_id, 'product', true, $default_lang );
    }

    /**
     * Get product id in current language.
     *
     * @since 1.0.11
     *
     * @param int $product_id Product ID.
     *
     * @return int
     */
    public function get_product_id_in_current_language( $product_id ) {
        if ( ! function_exists( 'wpml_object_id_filter' ) ) {
            return $product_id;
        }

        return wpml_object_id_filter( $product_id, 'product', true, ICL_LANGUAGE_CODE );
    }

    /**
     * Vendor Subscription pack title translation.
     *
     * @since 1.0.11
     *
     * @param string $title Title.
     * @param \WC_Product|bool $product Product.
     *
     * @return string
     */
    public function vendor_subscription_pack_title_translation( $title, $product ) {
        if ( ! $product || ! function_exists( 'wc_get_product' ) ) {
            return $title;
        }

        $product_id = $this->get_product_id_in_current_language( $product->get_id() );
        $product    = wc_get_product( $product_id );

        return $product ? $product->get_title() : $title;
    }

    /**
     * Get raw value from database
     *
     * @since  1.0.3
     *
     * @param  string $option
     * @param  string $section
     * @param  mixed $default
     *
     * @return mixed
     */
    public function get_raw_option( $option, $section, $default = '' ) {
        if ( ! class_exists( 'WPML_Multilingual_Options_Utils' ) ) {
            return dokan_get_option( $option, $section, $default );
        }

        global $wpdb;

        $util    = new WPML_Multilingual_Options_Utils( $wpdb );
        $options = $util->get_option_without_filtering( $section );

        return isset( $options[ $option ] ) ? $options[ $option ] : $default;
    }

    /**
     * Remove callback links with WPML on vendor dashboard
     *
     * @since 1.0.3
     *
     * @return void
     */
    public function dokan_wpml_remove_fix_fallback_links() {
        if ( function_exists( 'dokan_is_seller_dashboard' ) && ! dokan_is_seller_dashboard() ) {
            return;
        }

        if ( ! class_exists( 'WPML_Fix_Links_In_Display_As_Translated_Content' ) || ! function_exists( 'dokan_remove_hook_for_anonymous_class' ) ) {
            return;
        }

        dokan_remove_hook_for_anonymous_class( 'the_content', 'WPML_Fix_Links_In_Display_As_Translated_Content', 'fix_fallback_links', 99 );
    }

	/**
     * Load store page language switcher filter
     *
     * @since 1.0.4
     *
	 * @param \WP_query $query
	 * @param array     $store_info
     *
     * @return void
	 */
    public function load_store_page_language_switcher_filter( $query, $store_info ) {
		// This needs to be improved, I am probably missing a smarter way to get the current store URL.
		// Perhaps the current store URL could be included in the $store_info (2nd argument).
		$custom_store_url = dokan_get_option( 'custom_store_url', 'dokan_general', 'store' );
		$store_slug       = $query->get( $custom_store_url );
		$store_user       = get_user_by( 'slug', $store_slug );
		$store_url        = dokan_get_store_url( $store_user->ID );

		add_filter( 'wpml_ls_language_url', function( $url, $data ) use ( $store_url ) {
		    return apply_filters( 'wpml_permalink', $store_url, $data['code'] );
	    }, 10, 2 );
    }

    /**
	 * @param string $settings_key
	 *
	 * @return string
	 */
    public function filter_dashboard_settings_key( $settings_key ) {
    	return $this->translate_endpoint( $settings_key );
    }

	/**
	 * Add vendor capability for WooCommerce WPML
	 *
	 * @since 1.0.6
	 *
	 * @return array
	 */
	public function add_vendor_capability() {
		return [
			'vendor_capability' => 'seller',
		];
	}

	/**
	 * Remove home URL translation.
	 *
	 * @since 1.0.7
	 *
	 * @return void
	 */
	public static function remove_url_translation() {
		global $wpml_url_filters;

		if ( class_exists( 'WPML_URL_Filters' ) ) {
			remove_filter( 'home_url', [ $wpml_url_filters, 'home_url_filter' ], -10 );
			if ( $wpml_url_filters->frontend_uses_root() === true ) {
				remove_filter( 'page_link', array( $wpml_url_filters, 'page_link_filter_root' ), 1 );
			} else {
				remove_filter( 'page_link', array( $wpml_url_filters, 'page_link_filter' ), 1 );
			}
		}

		if ( function_exists( 'wpml_get_home_url_filter' ) ) {
			remove_filter( 'wpml_home_url', 'wpml_get_home_url_filter', 10 );
		}

		remove_filter( 'dokan_get_page_url', [ self::init(), 'reflect_page_url' ], 10 );
		remove_filter( 'dokan_get_navigation_url', [ self::init(), 'load_translated_url'], 10 );
	}

	/**
	 * Restore home URL translation.
	 *
	 * @since 1.0.7
	 *
	 * @return void
	 */
	public static function restore_url_translation() {
		global $wpml_url_filters;

		if ( class_exists( 'WPML_URL_Filters' ) ) {
			add_filter( 'home_url', [ $wpml_url_filters, 'home_url_filter' ], -10, 4 );
			if ( $wpml_url_filters->frontend_uses_root() === true ) {
				add_filter( 'page_link', array( $wpml_url_filters, 'page_link_filter_root' ), 1, 2 );
			} else {
				add_filter( 'page_link', array( $wpml_url_filters, 'page_link_filter' ), 1, 2 );
			}
		}

		if ( function_exists( 'wpml_get_home_url_filter' ) ) {
			add_filter( 'wpml_home_url', 'wpml_get_home_url_filter', 10 );
		}

		add_filter( 'dokan_get_page_url', [ self::init(), 'reflect_page_url' ], 10, 4 );
		add_filter( 'dokan_get_navigation_url', [ self::init(), 'load_translated_url' ], 10, 2 );
	}

    /**
     * Add tax_query arg in WP_User_Query used in dokan()->vendor->get_vendors()
     *
     * @since 1.0.8
     *
     * @param string[] $args
     *
     * @return void
     */
    public function fix_store_category_query_arg() {
        // return if dokan pro is not active or store category object is not available
        if (
            ! function_exists( 'dokan_is_store_categories_feature_on' ) ||
            ! dokan_is_store_categories_feature_on() ||
            ! dokan_pro()->store_category ) {
            return;
        }

        // remove existing pre_user_query action
        remove_action( 'pre_user_query', [ dokan_pro()->store_category, 'add_store_category_query' ] );

        // translated store category slug was unicode encoded, so we needed to decode it to get the correct slug
        add_filter(
            'dokan_get_store_categories', function ( $store_categories ) {
                foreach ( $store_categories as &$category ) {
                    $slug             = urldecode( $category['slug'] ); // decode the percent encoding
                    $slug             = str_replace( '\\', '\\\\', $slug ); // escape the backslashes
                    $category['slug'] = json_decode( '"' . $slug . '"' ); // parse as JSON
                }

                return $store_categories;
            }
        );

        // add pre_user_query action with WPML filter
        add_action(
            'pre_user_query', function ( $wp_user_query ) {
                if ( ! empty( $wp_user_query->query_vars['store_category_query'] ) ) {
                    global $sitepress, $wpdb;

                    $current_language = wpml_get_current_language();
                    $sitepress->switch_lang( $sitepress->get_default_language() );
                    $store_category_query = new WP_Tax_Query( $wp_user_query->query_vars['store_category_query'] );
                    $clauses              = $store_category_query->get_sql( $wpdb->users, 'ID' );

                    $wp_user_query->query_fields = 'DISTINCT ' . $wp_user_query->query_fields;
                    $wp_user_query->query_from   .= $clauses['join'];
                    $wp_user_query->query_where  .= $clauses['where'];
                    $sitepress->switch_lang( $current_language );
                }
            }
        );
    }

	/**
	 * Load wpml post actions on frontend
	 *
	 * @since 1.0.8
	 *
	 * @return void
	 */
	public function load_wpml_admin_post_actions() {
		if ( is_admin() ) {
			return;
		}

		global $wpdb, $sitepress;

		if ( class_exists( 'WPML_Admin_Post_Actions' ) && method_exists( $sitepress, 'get_settings' ) ) {
			$settings = $sitepress->get_settings();
			$wpml_post_translations = new WPML_Admin_Post_Actions( $settings, $wpdb );
			$wpml_post_translations->init();
		}
	}

	/**
	 * Change product status if base product status is changed.
	 *
	 * @since 1.0.8
	 *
	 * @param WC_Product $product
	 * @param string $status
	 *
	 * @return void
	 */
	public function change_product_status( $product, $status ) {
		$type         = apply_filters( 'wpml_element_type', get_post_type( $product->get_id() ) );
		$trid         = apply_filters( 'wpml_element_trid', false, $product->get_id(), $type );
		$translations = apply_filters( 'wpml_get_element_translations', array(), $trid, $type );

		foreach ( $translations as $lang => $translation ) {
			if ( $translation->original ) {
				continue;
			}

			// get product id
			$translated_product = wc_get_product( $translation->element_id );
			if ( ! $translated_product ) {
				continue;
			}

			// set product status
			$translated_product->set_status( $status );
			$translated_product->save();
		}
	}

    /**
     * Register single string.
     *
     * @since 1.0.11
     *
     * @param string $context This value gives the string you are about to register a context.
     * @param string $name The name of the string which helps the translator understand what’s being translated.
     * @param string $value The string that needs to be translated.
     *
     * @return void
     */
    public function register_single_string( $context, $name, $value ) {
        do_action( 'wpml_register_single_string', $context, $name, $value );
    }

    /**
     * Get translated single string.
     *
     * @since 1.0.11
     *
     * @param string $original_value The string’s original value.
     * @param string $domain The string’s registered domain.
     * @param string $name The string’s registered name.
     * @param $language_code
     *
     * @return string
     */
    public function get_translated_single_string( $original_value, $domain, $name, $language_code = null ) {
        return apply_filters( 'wpml_translate_single_string', $original_value, $domain, $name, $language_code );
    }

    /**
     * Register shipping status single string.
     *
     * @since 1.0.11
     *
     * @param string $status Shipping Status.
     *
     * @return void
     */
    public function register_shipping_status_single_string( $status ) {
        $this->register_single_string( 'dokan', 'Dokan Shipping Status: ' . $status, $status );
    }

    /**
     * Register abuse report single string.
     *
     * @since 1.0.11
     *
     * @param string $reason Abuse report reason.
     *
     * @return void
     */
    public function register_abuse_report_single_string( $reason ) {
        $this->register_single_string( 'dokan', 'Dokan Abuse Reason: ' . $reason, $reason );
    }

    /**
     * Register RMA reason single string.
     *
     * @since 1.0.11
     *
     * @param string $reason RMA reason.
     *
     * @return void
     */
    public function register_rma_single_string( $reason ) {
        $this->register_single_string( 'dokan', 'Dokan Refund and Returns Reason: ' . $reason, $reason );
    }

    /**
     * Get translated shipping status.
     *
     * @since 1.0.11
     *
     * @param string $status Shipping Status.
     *
     * @return string
     */
    public function get_translated_shipping_status( $status ) {
        return $this->get_translated_single_string( $status, 'dokan', 'Dokan Shipping Status: ' . $status );
    }

    /**
     * Get translated abuse report reason.
     *
     * @since 1.0.11
     *
     * @param string $reason Abuse report reason.
     *
     * @return string
     */
    public function get_translated_abuse_report_reason( $reason ) {
        return $this->get_translated_single_string( $reason, 'dokan', 'Dokan Abuse Reason: ' . $reason );
    }

    /**
     * Get translated RMA reason.
     *
     * @since 1.0.11
     *
     * @param string $reason RMA reason.
     *
     * @return string
     */
    public function get_translated_rma_reason( $reason ) {
        return $this->get_translated_single_string( $reason, 'dokan', 'Dokan Refund and Returns Reason: ' . $reason );
    }

    /**
     * Add High Performance Order Storage Support
     *
     * @since 1.0.10
     *
     * @return void
     */
    public function declare_woocommerce_feature_compatibility() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
        }
    }
} // Dokan_WPML

function dokan_load_wpml() { // phpcs:ignore
    return Dokan_WPML::init();
}

dokan_load_wpml();
